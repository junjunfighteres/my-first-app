<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\TestMailController;

// =============================
// ログインページ
// =============================
Route::get('/', function () {
    return redirect()->route('login');
});

// =============================
// 退会完了
// =============================
Route::get('/withdraw/complete', function () {
    return view('auth.withdraw_complete');
})->name('withdraw.complete');

// =============================
// パスワードリセット（独自実装）
// =============================
Route::get('/password/reset', function () {
    return view('auth.passwords.email');
})->name('password.request');

Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('password.email');

Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])
    ->name('password.reset');

Route::post('/password/reset', [ResetPasswordController::class, 'reset'])
    ->name('password.update');

// =============================
// テストメール
// =============================
Route::get('/test-mail', [TestMailController::class, 'test']);

// =============================
// ユーザー退会
// =============================
Route::post('/user/withdraw', 'User\ProfileController@withdraw')
    ->name('user.withdraw');

// =============================
// 認証必要ルート（User）
// =============================
Route::middleware('auth')->namespace('User')->group(function () {

    // 一般メインページ
    Route::get('/events', 'DisplayController@index')->name('events.index');
    Route::get('/', 'DisplayController@index')->name('user.main');

    // プロフィール
    Route::get('/user/profile', 'ProfileController@show')->name('user.profile');
    Route::post('/user/profile/avatar', 'ProfileController@updateAvatar')->name('user.profile.avatar');
    Route::get('/user/{id}/profile', 'ProfileController@showOtherUser')->name('user.profile.other');

    // Event 閲覧
    Route::resource('user/events', 'EventController')
        ->only(['index', 'show'])
        ->names([
            'index' => 'user.events.index',
            'show'  => 'user.events.show',
        ]);

    // 参加申込
    Route::get('events/{event}/apply', 'ApplicationController@applyForm')->name('events.apply');
    Route::post('events/apply/confirm', 'ApplicationController@applyConfirm')->name('events.confirm');
    Route::post('events/apply/complete', 'ApplicationController@applyComplete')->name('events.complete');
    Route::post('events/{event}/cancel', 'ApplicationController@cancel')->name('events.cancel');

    Route::resource('applications', 'ApplicationController')->only(['store']);

    // 違反報告
    Route::get('report/{event}/create', 'ReportController@create')->name('report.create');
    Route::post('report/{event}/store', 'ReportController@store')->name('report.store');
    Route::get('report/complete', 'ReportController@complete')->name('report.complete');

    // Host CRUD
    Route::prefix('host')->group(function () {
        Route::get('events/create', 'EventController@create')->name('host.events.create');
        Route::post('events/create/confirm', 'EventController@storeConfirm')->name('host.events.store.confirm');
        Route::post('events/create/complete', 'EventController@storeComplete')->name('host.events.store.complete');
        Route::get('events/{event}', 'EventController@show')->name('host.events.show');
        Route::get('events/{event}/edit', 'EventController@edit')->name('host.events.edit');
        Route::post('events/confirm', 'EventController@updateConfirm')->name('host.events.update.confirm');
        Route::post('events/complete', 'EventController@updateComplete')->name('host.events.update.complete');
        Route::delete('events/{event}', 'EventController@destroy')->name('host.events.destroy');
    });
});

// =============================
// Admin
// =============================
Route::prefix('admin')->namespace('Admin')->middleware('admin')->group(function () {
    Route::get('/', 'AdminController@index')->name('admin.home');

    Route::resource('events', 'AdminEventController')->only(['index', 'show', 'update']);

    Route::post('events/{event}/hidden/confirm', 'AdminEventController@hiddenConfirm')->name('admin.events.hidden.confirm');
    Route::post('events/{event}/hidden/complete', 'AdminEventController@hiddenComplete')->name('admin.events.hidden.complete');

    Route::resource('users', 'AdminUserController')->only(['index', 'show', 'update']);

    Route::post('users/{id}/suspend/confirm', 'AdminUserController@suspendConfirm')->name('admin.users.suspend.confirm');
    Route::post('users/{id}/suspend', 'AdminUserController@suspend')->name('admin.users.suspend');
    Route::post('users/{id}/unsuspend/confirm', 'AdminUserController@unsuspendConfirm')->name('admin.users.unsuspend.confirm');
    Route::post('users/{id}/unsuspend', 'AdminUserController@unsuspend')->name('admin.users.unsuspend');

    Route::get('applications/observe', 'AdminApplicationController@observe')->name('admin.applications.observe');

    Route::post('events/{event}/reports/disable', 'AdminEventController@disableReports')->name('admin.events.reports.disable');
    Route::post('events/{event}/reports/enable', 'AdminEventController@enableReports')->name('admin.events.reports.enable');
});

// =============================
// Ajax
// =============================
Route::prefix('ajax')->namespace('Ajax')->middleware('auth')->group(function () {
    Route::get('bookmarks', 'BookmarkAjaxController@index')->name('ajax.bookmarks.index');
    Route::post('bookmarks', 'BookmarkAjaxController@store')->name('ajax.bookmarks.store');
    Route::delete('bookmarks/{event}', 'BookmarkAjaxController@destroy')->name('ajax.bookmarks.destroy');

    Route::get('tabs/{type}', 'TabController@show');
    Route::resource('applications', 'ApplicationAjaxController')->only(['store', 'destroy']);
});

// =============================
// 認証ルート（ログインだけ必要）
// =============================
Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
