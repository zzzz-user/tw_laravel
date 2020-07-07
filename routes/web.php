<?php

use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    if (Auth::check()) {
        return view('home');
    } else {
        return view('welcome');
    }
});

Route::get('/home', function () {
    return view('home');
});

Route::get('/logout', function () {
    Auth::logout();
    return view('welcome');
});

// Twitterログイン
Route::get('login', 'Auth\LoginController@redirectToProvider')->name('login');
Route::get('login/tw/callback', 'Auth\LoginController@handleProviderCallback');


// ユーザーページ
Route::get('user/{id}', 'UserController@show');

