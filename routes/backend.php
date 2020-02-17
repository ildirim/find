<?php

Route::get('/login','Auth\LoginController@showLoginForm')->name('loginAdmin');
Route::post('/login','Auth\LoginController@login');
Route::post('/logout','Auth\LoginController@logout')->name('logout');

//Forgot Password Routes
Route::get('/password/reset','ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('/password/email','ForgotPasswordController@sendResetLinkEmail')->name('password.email');

//Reset Password Routes
Route::get('/password/reset/{token}','ResetPasswordController@showResetForm')->name('password.reset');
Route::post('/password/reset','ResetPasswordController@reset')->name('password.update');
Route::get('/dashboard','HomeController@index')->name('home');


