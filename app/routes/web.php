<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| 今後 Auth を導入することを見越しつつ、
| User / Host（主催）/ Admin / Ajax をキレイに整理した構成。
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| 認証関連（Auth実装後にここに Auth::routes() を追加）
|--------------------------------------------------------------------------
|
| Auth::routes(); // ← 今後ここに追加する
|
| ※ login / register / password reset は
|   Auth コントローラが担当するのでここには書かない。
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| User（一般ユーザー向け）
| namespace: App\Http\Controllers\User
|--------------------------------------------------------------------------
*/
Route::namespace('User')->group(function () {

    // 7. 一般メインページ
    Route::get('/', 'DisplayController@index')->name('user.home');

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
    Route::get('applications/{event}/apply', 'ApplicationController@create')
        ->name('applications.apply'); // 9. 申込画面

    Route::post('applications/confirm', 'ApplicationController@confirm')
        ->name('applications.confirm'); // 10. 申込確認

    Route::post('applications/complete', 'ApplicationController@complete')
        ->name('applications.complete'); // 11. 完了画面

    Route::resource('applications', 'ApplicationController')->only([
        'store'
    ]);


    // ============================
    // 12. 違反報告
    // ============================
    Route::get('report/{event}', 'ReportController@create')
        ->name('report.create');

    Route::post('report', 'ReportController@store')
        ->name('report.store');


    // ============================
    // Host（主催者向け Event CRUD）
    // ※ 一般ユーザー内部に存在する「主催者」機能
    // ============================
    Route::prefix('host')->group(function () {

        // 13. 主催イベント詳細
        Route::get('events/{event}', 'EventController@hostShow')
            ->name('host.events.show');

        // 14. 編集
        Route::get('events/{event}/edit', 'EventController@edit')
            ->name('host.events.edit');

        // 15. 編集内容確認
        Route::post('events/confirm', 'EventController@updateConfirm')
            ->name('host.events.update.confirm');

        // 16. 更新完了
        Route::post('events/complete', 'EventController@updateComplete')
            ->name('host.events.update.complete');

        // 17. 新規作成
        Route::get('events/create', 'EventController@create')
            ->name('host.events.create');

        // 18. 新規登録確認
        Route::post('events/create/confirm', 'EventController@storeConfirm')
            ->name('host.events.store.confirm');

        // 19. 新規登録完了
        Route::post('events/create/complete', 'EventController@storeComplete')
            ->name('host.events.store.complete');

    });
});



/*
|--------------------------------------------------------------------------
| Admin（管理者向け）
| URL は /admin/
| namespace: App\Http\Controllers\Admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->namespace('Admin')
    ->group(function () {

        // 20. 管理者ダッシュボード
        Route::get('/', 'AdminController@index')->name('admin.home');

        // ============================
        // 21. イベント管理
        // ============================
        Route::resource('events', 'EventController')->only([
            'index', 'show', 'update'
        ]);

        // 25. 非表示確認
        Route::post('events/{event}/hidden/confirm', 'EventController@hiddenConfirm')
            ->name('admin.events.hidden.confirm');

        // 26. 非表示完了
        Route::post('events/{event}/hidden/complete', 'EventController@hiddenComplete')
            ->name('admin.events.hidden.complete');

        // ============================
        // 22. ユーザー管理
        // ============================
        Route::resource('users', 'UserController')->only([
            'index', 'show', 'update'
        ]);

        // 27. 利用停止確認
        Route::post('users/{user}/suspend/confirm', 'UserController@suspendConfirm')
            ->name('admin.users.suspend.confirm');

        // 28. 利用停止完了
        Route::post('users/{user}/suspend/complete', 'UserController@suspendComplete')
            ->name('admin.users.suspend.complete');

        // ============================
        // 23. 参加申込監視
        // ============================
        Route::get('applications/observe', 'ApplicationController@observe')
            ->name('admin.applications.observe');

    });



/*
|--------------------------------------------------------------------------
| Ajax（API風 非同期処理）
|--------------------------------------------------------------------------
*/
Route::prefix('ajax')
    ->namespace('Ajax')
    ->group(function () {

        // ブックマーク Ajax用
        Route::resource('bookmarks', 'BookmarkAjaxController')->only([
            'index', 'store', 'destroy'
        ]);
    });
