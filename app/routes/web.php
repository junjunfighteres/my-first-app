<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DisplayController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\RegistrationController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// DisplayController
Route::controller(DisplayController::class)->group(function () {
    Route::get('/search', 'search')->name('search.events');
});

// AjaxController
Route::controller(AjaxController::class)->group(function () {
    Route::get('/ajax/events/{type}', 'fetchEventsByType')->name('ajax.events.fetch');
});

// RegistrationController
Route::controller(RegistrationController::class)->group(function () {
    Route::get('/events/{type}', 'fetchByType');
});

// 単純ビュー
Route::view('/user_main', 'user_main');