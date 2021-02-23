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

// User 認証不要
Route::get('/', 'ItemController@index')->name('item.index');
Route::get('/item/detail', 'ItemController@detail')->name('item.detail');
// User ログイン後
Route::group(['middleware' => 'auth:user'], function() {
    Route::get('home', 'HomeController@index')->name('home');
});
// Admin 認証不要
Route::group(['prefix' => 'admin'], function() {
    Route::get('login', 'Admin\LoginController@showLoginForm')->name('admin.login');
    Route::post('login', 'Admin\LoginController@login');
});
// Admin ログイン後
Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin'], function() {
    Route::post('logout', 'Admin\LoginController@logout')->name('admin.logout');
    Route::get('home', 'HomeController@index')->name('admin.home');
	//ログイン後のリダイレクト先
    Route::get('item/index', 'ItemController@index')->name('admin.item.index');
    Route::get('item/form', 'ItemController@form')->name('admin.item.form');
    Route::post('item/create', 'ItemController@create')->name('admin.item.create');
    Route::post('item/edit', 'ItemController@edit')->name('admin.item.edit');
    Route::get('item/detail', 'ItemController@detail')->name('admin.item.detail');

});


