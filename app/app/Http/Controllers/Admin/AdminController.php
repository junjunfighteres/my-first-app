<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Event;
use App\Models\Report;
use App\Models\Application;

class AdminController extends Controller
{
    public function index()
    {
        // 簡易データを取得
        $userCount   = User::count();
        $eventCount  = Event::where('del_flg', 0)->count();
        $reportCount = Report::count();
        $joinCount   = Application::count();

        return view('admin.main', compact(
            'userCount',
            'eventCount',
            'reportCount',
            'joinCount'
        ));
    }
}