<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\GoogleController;
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
/*
Route::get('/', function () {
    return view('welcome');
});*/

//Auth::routes();
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

    Route::get('/login/google', [GoogleController::class, 'redirectToGoogle'])->name('login/google');
    Route::get('/login/google/callback', [GoogleController::class, 'googleCallback']);
    Route::get('/getApi', [App\Http\Controllers\GoogleController::class, 'getApi']);

