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
	Route::post('address/index', 'AddressController@index')->name('address.index');
	Route::post('address/insert', 'AddressController@insert')->name('address.insert');
	Route::get('address/updateForm', 'AddressController@updateForm')->name('address.updateForm');
	Route::get('address/delete', 'AddressController@delete')->name('address.delete');
	Route::post('address/update', 'AddressController@update')->name('address.update');
	Route::get('account/detail', 'AccountController@detail')->name('account.detail');
	Route::post('account/update', 'AccountController@update')->name('account.update');
	Route::get('account/updateEmail', 'AccountController@updateEmail')->name('account.updateEmail');
	Route::post('order/charge', 'OrderController@charge')->name('order.charge');
	Route::post('order/confirm', 'OrderController@confirm')->name('order.confirm');
	Route::get('order/index', 'OrderController@index')->name('order.index');
	Route::post('order/index', 'OrderController@index')->name('order.index');
	Route::get('order/detail', 'OrderController@detail')->name('order.detail');
	Route::post('order/cancel', 'OrderController@cancel')->name('order.cancel');

	Route::get('cart/insert', function () { return redirect(route('cart.index')); });
	Route::get('cart/delete', function () { return redirect(route('cart.index')); });
	Route::get('address/insert', function () { return redirect(route('address.index')); });
	Route::get('address/update', function () { return redirect(route('address.index')); });
	Route::get('account/update', function () { return redirect(route('account.detail')); });
	Route::get('order/charge', function () { return redirect(route('cart.index')); });
	Route::get('order/confirm', function () { return redirect(route('cart.index')); });
	Route::get('order/cancel', function () { return redirect(route('order.index')); });
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
	Route::get('item/index', 'ItemController@index')->name('admin.item.index');
	Route::get('item/form', 'ItemController@form')->name('admin.item.form');
	Route::post('item/insert', 'ItemController@insert')->name('admin.item.insert');
	Route::post('item/update', 'ItemController@update')->name('admin.item.update');
	Route::get('item/detail', 'ItemController@detail')->name('admin.item.detail');
	Route::get('account/index', 'AccountController@index')->name('admin.account.index');
	Route::get('account/detail', 'AccountController@detailForAdmin')->name('admin.detailForAdmin');
	Route::get('order/index', 'OrderController@indexForAdmin')->name('admin.order.index');
	Route::post('order/index', 'OrderController@indexForAdmin')->name('admin.order.index');
	Route::get('order/detail', 'OrderController@detail')->name('admin.order.detail');
	Route::post('order/changeStatus', 'OrderController@changeStatus')->name('admin.order.changeStatus');

	Route::get('item/insert', function () { return redirect(route('admin.item.index')); });
	Route::get('item/insert', function () { return redirect(route('admin.item.index')); });
	Route::get('order/changeStatus', function () { return redirect(route('admin.order.index')); });
});


