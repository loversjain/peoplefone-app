<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PhoneController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', [\App\Http\Controllers\User\UserController::class, 'index'])->name('users.index');

// Route group for UserController
Route::controller(\App\Http\Controllers\User\UserController::class)
    ->prefix('users')
    ->group(function () {
        Route::get('/', 'index')->name('users.index');
        Route::get('/impersonate/{id}', 'impersonate')->name('users.impersonate');
        Route::put('/settings', 'updateSettings')->name('users.updateSettings');
        Route::get('/settings/{userId}','showSettings')->name('users.settings');
    });

// Route group for NotificationController
Route::controller(\App\Http\Controllers\Notification\NotificationController::class)
    ->prefix('notifications')
    ->group(function () {
        Route::get('/create', 'create')->name('notifications.create');
        Route::post('/', 'store')->name('notifications.store');
        Route::post('/{userId}/mark-as-read/{id}',  'markAsRead')->name('notifications.markAsRead');
        Route::post('/home/{userId}/mark-all-as-read',  'markAllAsRead')->name('notifications.markAllAsRead');



    });
Route::controller(\App\Http\Controllers\HomeController::class)
    ->prefix('home')
    ->group(function () {
        Route::get('/', 'index')->name('home');
        Route::get('/{userId}', 'index')->name('home.user');

    });



//Route::get('/lookup', [PhoneController::class, 'lookup']);
