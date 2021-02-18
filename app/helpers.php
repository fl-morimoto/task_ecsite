<?php
use Illuminate\Support\Facades\Auth;

if (! function_exists('getUser')) {
	function getUser() {
		if (isAdminRoute()) {
			return Auth::guard('admin')->user();
		} else {
			return Auth::guard('user')->user();
		}
	}
}
if (! function_exists('isLogin')) {
	function isLogin() {
		return !empty(getUser());
	}
}
if (! function_exists('isGuest')) {
	function isGuest() {
		return !isLogin() && !isAdminRoute();
	}
}
if (! function_exists('isAdminRoute')) {
	function isAdminRoute() {
		return strpos(\Route::currentRouteName(), 'admin') !== false;
	}
}
