<?php

use Illuminate\Support\Facades\Route;

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

Route::get('{any}', function () {
    return view('index');
})
->where('any', '.*')
->where('any', '^(?!api).*$');

Route::get('/auth/reset-password/{token}', function ($token) {
    return view('index', ['token'=> $token]);
})->name("password.reset");