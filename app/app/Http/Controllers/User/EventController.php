<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Event;
use App\Models\Application;
use App\Models\Bookmark;

// ★ 追加：FormRequest の use 文
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\StoreEventCompleteRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Requests\UpdateEventCompleteRequest;

class EventController extends Controller
{
    /* ============================================================
     *  一覧表示
     * ============================================================ */
    public function index()
    {
        $user = Auth::user();

        $events = Event::query()
            ->where('del_flg', 0)
            ->where(function ($q) use ($user) {
                $q->where('status', 'public')
                  ->orWhere('user_id', $user->id);
            })
            ->when(request('keyword'), function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('title', 'like', "%$keyword%")
                      ->orWhere('description', 'like', "%$keyword%");
                });
            })
            ->when(request('start_date'), fn($q) => $q->where('date', '>=', request('start_date')))
            ->when(request('end_date'), fn($q) => $q->where('date', '<=', request('end_date')))
            ->when(request('platform'), fn($q) => $q->where('format', request('platform')))
            ->orderBy('date', 'asc')
            ->get();

        return view('user.main', compact('events'));
    }

    /* ============================================================
     *  詳細表示（参加者用）
     * ============================================================ */
    public function show(Event $event)
    {
        if ($event->status === 'private' && $event->user_id !== Auth::id()) {
            abort(404);
        }

        $event->load('user');

        $related = Event::where('format', $event->format)
            ->where('id', '!=', $event->id)
            ->where('del_flg', 0)
            ->where(function ($q) {
                $q->where('status', 'public')
                  ->orWhere('user_id', Auth::id());
            })
            ->limit(6)
            ->get();

        $isJoined = Application::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->exists();

        $isBookmarked = Bookmark::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->exists();

        $comments = $event->applications()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.events.detail', compact(
            'event', 'related', 'isJoined', 'comments', 'isBookmarked'
        ));
    }

    /* ============================================================
     *  主催者用詳細
     * ============================================================ */
    public function showHost(Event $event)
    {
        $this->authorizeOwner($event);
        return view('user.host.detail', compact('event'));
    }

    /* ============================================================
     *  編集画面
     * ============================================================ */
    public function edit(Event $event)
    {
        $this->authorizeOwner($event);
        return view('user.host.edit', compact('event'));
    }

    /* ============================================================
     *  編集内容確認（FormRequest 使用）
     * ============================================================ */
    public function updateConfirm(UpdateEventRequest $request)
    {
        $validated = $request->validated();

        // temp に画像保存（新規アップロードがある場合）
        $validated['image_path'] = $request->hasFile('image')
            ? $request->file('image')->store('temp_events', 'public')
            : ($validated['current_image'] ?? null);

        return view('user.host.edit_confirm', ['data' => $validated]);
    }

    /* ============================================================
     *  編集完了（FormRequest 使用）
     * ============================================================ */
    public function updateComplete(UpdateEventCompleteRequest $request)
    {
        $validated = $request->validated();

        $event = Event::findOrFail($validated['event_id']);
        $this->authorizeOwner($event);

        $finalImage = $event->image_path;

        // temp → events へ移動
        if (!empty($validated['image_path']) &&
            str_starts_with($validated['image_path'], 'temp_events')) {

            $newPath = str_replace('temp_events', 'events', $validated['image_path']);
            Storage::disk('public')->move($validated['image_path'], $newPath);
            $finalImage = $newPath;
        }

        $event->update([
            'title'       => $validated['title'],
            'date'        => $validated['date'],
            'start_time'  => $validated['start_time'],
            'end_time'    => $validated['end_time'],
            'format'      => $validated['format'],
            'capacity'    => $validated['capacity'],
            'status'      => $validated['status'],
            'description' => $validated['description'],
            'image_path'  => $finalImage,
        ]);

        return view('user.host.edit_complete', compact('event'));
    }

    /* ============================================================
     *  削除（論理削除）
     * ============================================================ */
    public function destroy(Event $event)
    {
        $this->authorizeOwner($event);

        $event->update(['del_flg' => 1]);

        return redirect()->route('events.index')
            ->with('success', 'イベントを削除しました。');
    }

    /* ============================================================
     *  新規作成画面
     * ============================================================ */
    public function create()
    {
        return view('user.host.create');
    }

    /* ============================================================
     *  新規作成確認（FormRequest 使用）
     * ============================================================ */
    public function storeConfirm(StoreEventRequest $request)
    {
        $validated = $request->validated();

        $data = array_intersect_key($validated, array_flip([
            'title', 'date', 'start_time', 'end_time',
            'format', 'capacity', 'status', 'description'
        ]));

        $tempPath = $request->hasFile('image')
            ? $request->file('image')->store('temp_events', 'public')
            : null;

        return view('user.host.create_confirm', [
            'data'     => $data,
            'tempPath' => $tempPath,
        ]);
    }

    /* ============================================================
     *  新規作成完了（FormRequest 使用）
     * ============================================================ */
    public function storeComplete(StoreEventCompleteRequest $request)
    {
        $validated = $request->validated();

        $finalImage = null;

        if (!empty($validated['temp_image'])) {
            $finalImage = str_replace('temp_events', 'events', $validated['temp_image']);
            Storage::disk('public')->move($validated['temp_image'], $finalImage);
        }

        $event = Event::create([
            'user_id'     => Auth::id(),
            'title'       => $validated['title'],
            'date'        => $validated['date'],
            'start_time'  => $validated['start_time'],
            'end_time'    => $validated['end_time'],
            'format'      => $validated['format'],
            'capacity'    => $validated['capacity'],
            'status'      => $validated['status'],
            'description' => $validated['description'] ?? null,
            'image_path'  => $finalImage,
            'del_flg'     => 0,
        ]);

        return view('user.host.create_complete', compact('event'));
    }

    /* ============================================================
     *  共通：主催者チェック
     * ============================================================ */
    private function authorizeOwner(Event $event)
    {
        if ($event->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
