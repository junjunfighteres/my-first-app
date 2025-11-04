<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\DisplayController;
use App\Http\Controllers\User\RegistrationController;
use App\Http\Controllers\AjaxController;
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
Route::get('/user_main', [DisplayController::class, 'main'])->name('user_main');
Route::get('/user_main/{id}/detail', [DisplayController::class, 'eventDetail'])->name('events.detail');
Route::get('/search', [DisplayController::class, 'search'])->name('search.events');

// AjaxController
Route::get('/ajax/events/{type}', [AjaxController::class, 'fetchEventsByType'])->name('ajax.events.fetch');

// RegistrationController
Route::get('/events/{type}', [RegistrationController::class, 'fetchByType'])->name('events.by_type');