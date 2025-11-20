<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Event;
use App\Models\Application;
use App\Models\Report;
use App\Models\Bookmark;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/withdraw/complete', function () {
    return view('auth.withdraw_complete');
})->name('withdraw.complete');

Route::post('/user/withdraw', 'User\ProfileController@withdraw')->name('user.withdraw');

// Route::get('/home', function () {
//     return redirect()->route('index'); // ここをあなたのメインページルート名に
// })->name('home');

Route::middleware('auth')->group(function () {
    Route::namespace('User')->group(function () {

        // ======================
        // Event 詳細の公開範囲制御
        // ======================
        Route::get('events/{event}', 'User\EventController@show')
            ->middleware('auth')
            ->name('events.show');

        // 7. 一般メインページ
        Route::get('/events', 'DisplayController@index')->name('events.index');
        Route::get('/', 'DisplayController@index')->name('user.main');

        // プロフィール表示
        Route::get('/user/profile', 'ProfileController@show')->name('user.profile');

        // プロフィール画像更新
        Route::post('/user/profile/avatar', 'ProfileController@updateAvatar')->name('user.profile.avatar');

        // 他ユーザーのプロフィール
        Route::get('/user/{id}/profile', 'ProfileController@showOtherUser')->name('user.profile.other');

        // ============================
        // Event（閲覧・参加者向け）
        // ============================
        Route::resource('events', 'EventController')->only([
            'index', 'show'
        ]);
        // show → 8. イベント詳細

        // ============================
        // 参加申込 Application
        // ============================
        Route::get('events/{event}/apply', 'ApplicationController@applyForm')
            ->name('events.apply'); // 9. 申込画面

        Route::post('events/apply/confirm', 'ApplicationController@applyConfirm')
            ->name('events.confirm'); // 10. 申込確認

        Route::post('events/apply/complete', 'ApplicationController@applyComplete')
            ->name('events.complete'); // 11. 完了画面

        Route::post('events/{event}/cancel', 'ApplicationController@cancel')
            ->name('events.cancel');

        Route::resource('applications', 'ApplicationController')
            ->only([
                'store'
            ]);

        // ============================
        // 12. 違反報告
        // ============================
        Route::get('report/{event}/create', 'ReportController@create')
            ->name('report.create');

        Route::post('report/{event}/store', 'ReportController@store')
            ->name('report.store');

        Route::get('report/complete', 'ReportController@complete')
            ->name('report.complete');

        // ============================
        // Host（主催者向け Event CRUD）
        // ※ 一般ユーザー内部に存在する「主催者」機能
        // ============================
        Route::prefix('host')->group(function () {

            // 13. 新規作成
            Route::get('events/create', 'EventController@create')
                ->name('host.events.create');

            // 14. 新規登録確認
            Route::post('events/create/confirm', 'EventController@storeConfirm')
                ->name('host.events.store.confirm');

            // 15. 新規登録完了
            Route::post('events/create/complete', 'EventController@storeComplete')
                ->name('host.events.store.complete');

            // 16. 主催イベント詳細
            Route::get('events/{event}', 'EventController@Show')
                ->name('host.events.show');

            // 17. 編集
            Route::get('events/{event}/edit', 'EventController@edit')
                ->name('host.events.edit');

            // 18. 編集内容確認
            Route::post('events/confirm', 'EventController@updateConfirm')
                ->name('host.events.update.confirm');

            // 19. 更新完了
            Route::post('events/complete', 'EventController@updateComplete')
                ->name('host.events.update.complete');

            // 20. 削除
            Route::delete('events/{event}', 'EventController@destroy')
                ->name('host.events.destroy');
        });
    });


/*
|--------------------------------------------------------------------------
| Admin（管理者向け）
| URL は /admin/
| namespace: App\Http\Controllers\Admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->namespace('Admin')->as('admin.')->middleware('admin')
    ->group(function () {

        // 20. 管理者ダッシュボード
        Route::get('/', 'AdminController@index')->name('home');

        // ============================
        // 21. イベント管理
        // ============================
        Route::resource('events', 'AdminEventController')->only([
            'index', 'show', 'update'
        ]);

        // 25. 非表示確認
        Route::post('events/{event}/hidden/confirm', 'AdminEventController@hiddenConfirm')
            ->name('events.hidden.confirm');

        // 26. 非表示完了
        Route::post('events/{event}/hidden/complete', 'AdminEventController@hiddenComplete')
            ->name('events.hidden.complete');

        // ============================
        // 22. ユーザー管理
        // ============================
        Route::resource('users', 'AdminUserController')->only([
            'index', 'show', 'update'
        ]);

        // 27. 利用停止確認
        Route::post('users/{user}/suspend/confirm', 'AdminUserController@suspendConfirm')
            ->name('users.suspend.confirm');

        // 28. 利用停止完了
        Route::post('users/{user}/suspend/complete', 'AdminUserController@suspendComplete')
            ->name('users.suspend.complete');

        // ============================
        // 23. 参加申込監視
        // ============================
        Route::get('applications/observe', 'AdminApplicationController@observe')
            ->name('applications.observe');

    });
});


/*
|--------------------------------------------------------------------------
| Ajax（API風 非同期処理）
|--------------------------------------------------------------------------
*/
Route::prefix('ajax')->namespace('Ajax')->middleware('auth')->group(function () {

    // ブックマーク Ajax用（indexでタブ切り替えも処理）
    Route::get('bookmarks', 'BookmarkAjaxController@index')->name('ajax.bookmarks.index');
    Route::post('bookmarks', 'BookmarkAjaxController@store')->name('ajax.bookmarks.store');
    Route::delete('bookmarks/{event}', 'BookmarkAjaxController@destroy')->name('ajax.bookmarks.destroy');

    // タブ切り替え専用（旧TabControllerは不要）
    Route::get('tabs/{type}', 'TabController@show');


    Route::resource('applications', 'ApplicationAjaxController')->only(['store', 'destroy']);
    });

Auth::routes();