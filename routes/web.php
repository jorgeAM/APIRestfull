<?php

/*Rutas ubicadas en Router -> sypmhony*/

// Authentication Routes...
$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('login', 'Auth\LoginController@login');
$this->post('logout', 'Auth\LoginController@logout')->name('logout');
// Password Reset Routes...
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
$this->post('password/reset', 'Auth\ResetPasswordController@reset');

#ruta que usara el componente en vuejs
Route::get('/home/my-tokens', 'HomeController@getTokens')->name('personal-tokens');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', function(){
	//retorna welcome
	return view('welcome');
})->middleware('guest');
