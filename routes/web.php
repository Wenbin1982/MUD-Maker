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


Auth::routes();


Route::get('login', 'MainController@index')->name('login');
Route::post('/myLogin', 'Auth\LoginController@login')->name('myLogin');
Route::post('user/register', 'Auth\RegisterController@store')->name('user.register');
Route::get('reset-password-user/{key}', 'Auth\PasswordController@show');
Route::post('api/reset-password', 'Auth\PasswordController@postResetPassword')->name('api.resetPassword');
Route::post('api/password/email', 'Auth\PasswordController@sendResetLinkEmail')->name('api.password.email');
Route::get('api/password/reset-form', 'Auth\PasswordController@showResetForm')->name('api.show.reset');
Route::post('api/password/reset', 'Auth\PasswordController@resetAdminPass')->name('api.password.reset');
Route::get('users/confirmation', 'Auth\RegisterController@confirmationUser');
Route::get('users/register-confirm-admin', 'Auth\RegisterController@getRegisterConfirmAdmin');
Route::get('users/register-deny-admin', 'Auth\RegisterController@getRegisterDenyAdmin');

Route::get('mudFile/{manufacturer_id}/{device_id}/{model_id}/{version_id}/{name}', 'JsonController@linkMudFile')->name('linkMudFile');

Route::group(array("middleware" => ['auth', 'confirmed']), function () {
    Route::get('/', 'MainController@app');
    Route::get('/create', 'MainController@app');
    Route::get('/edit/{edit}', 'MainController@app');
    Route::get('/show/{id}', 'MainController@app');
    Route::get('/mudFile/{mudFile}', 'MainController@app');
});

Route::group(array("middleware" => ["auth", 'confirmed', "admin"]), function() {
    Route::get('/admin/users', 'UserController@index')->name('admin.users');
    Route::get('/admin/domains', 'DomainController@index')->name('admin.domains');
    Route::get('/admin/files', 'JsonController@viewAdminMudFiles');
    Route::get('/admin/mudFiles', 'JsonController@showAdminMudFiles')->name('admin.mudFiles');
});

Route::group(['prefix' => '/api/v1'], function () {
    //reset password page
    Route::put('reset-password-user/{key}', 'Auth\PasswordController@getAccessUser')->name('reset.password');
    //login page
    Route::get('/', 'JsonController@index')->name('main');
    //create mudfile
    Route::get('/create', 'JsonController@createShow')->name('create.show');
    Route::post('/store', 'JsonController@store')->name('store');
    //update mudfile
    Route::post('/update', 'JsonController@update')->name('update');

    Route::post('/user/update', 'UserController@update')->name('user.update');
    Route::post('/delete/{id}', 'JsonController@delete')->name('deleteJson');
    Route::post('/restore', 'JsonController@restore')->name('restore');
    Route::post('/show/downloads', 'JsonController@downloadJson')->name('downloadJson');
    Route::post('/logout', 'JsonController@myLogout')->name('myLogout');
    Route::post('/show', 'JsonController@show')->name('showJson');
    Route::get('/edit/{edit}', 'JsonController@edit')->name('editJson');
    Route::get('/cloneMudFile/{edit}', 'JsonController@cloneMudFile')->name('clone.MudFile');
    //admin
    Route::get('/admin/users', 'UserController@show')->name('admin.users.show');
    Route::get('/isAdmin', 'UserController@isAdmin')->name('isAdmin.show');
    Route::get('/admin/domains', 'DomainController@show')->name('admin.domains.show');
    Route::delete('/admin/domains/destroy/{id}', 'DomainController@destroy')->name('admin.domains.delete');
    Route::post('/admin/domains/store', 'DomainController@store')->name('admin.domains.store');
});



