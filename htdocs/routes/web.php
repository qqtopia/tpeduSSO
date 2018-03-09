<?php

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

//Auth::routes();
// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');
// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::get('/', 'HomeController@index')->middleware(['auth', 'auth.email'])->name('home');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/changePassword', 'HomeController@showChangePasswordForm');
    Route::post('/changePassword', 'HomeController@changePassword')->name('changePassword');
    Route::get('/changeAccount', 'HomeController@showChangeAccountForm');
    Route::post('/changeAccount', 'HomeController@changeAccount')->name('changeAccount');
    Route::get('/profile', 'HomeController@showProfileForm');
    Route::post('/profile', 'HomeController@changeProfile')->name('profile');
    Route::get('/oauth', 'oauthController@index')->name('oauth');
});

Route::get('/schoolAdmin', 'HomeController@showSchoolAdminSettingForm');
Route::post('/schoolAdmin', 'HomeController@addSchoolAdmin')->name('schoolAdmin');
Route::post('/schoolAdminRemove', 'HomeController@delSchoolAdmin')->name('schoolAdminRemove');