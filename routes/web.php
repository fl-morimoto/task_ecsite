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
    Route::get('cart/index', 'CartController@index')->name('cart.index');
    Route::post('cart/insert', 'CartController@insert')->name('cart.insert');
    Route::post('cart/delete', 'CartController@delete')->name('cart.delete');
    Route::get('address/index', 'AddressController@index')->name('address.index');
    Route::post('address/insert', 'AddressController@insert')->name('address.insert');
    Route::get('address/updateForm', 'AddressController@updateForm')->name('address.updateForm');
    Route::get('address/delete', 'AddressController@delete')->name('address.delete');
    Route::post('address/update', 'AddressController@update')->name('address.update');
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
    Route::post('item/insert', 'ItemController@insert')->name('admin.item.insert');
    Route::post('item/update', 'ItemController@update')->name('admin.item.update');
    Route::get('item/detail', 'ItemController@detail')->name('admin.item.detail');
});


