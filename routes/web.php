<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\Admin\RolePermission\RoleController;
use App\Http\Controllers\Admin\Weather\WeatherHistoryController;

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

Route::get('/', [HomeController::class, 'welcome']);

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::as('admin.')->group(function () {
        Route::resource('roles', RoleController::class)->parameters(['roles' => 'id']);
        Route::resource('users', UserController::class)->parameters(['users' => 'id']);
        Route::resource('weather-histories', WeatherHistoryController::class)->only([
            'edit', 'update', 'destroy'
        ])->parameters(['weather-histories' => 'id']);
    });
});