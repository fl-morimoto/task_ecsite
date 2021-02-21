<?php
use Illuminate\Support\Facades\Auth;

if (! function_exists('userInfo')) {
	function userInfo() {
		return Auth::guard()->user();
	}
}
if (! function_exists('isLogin')) {
	function isLogin() {
		return !empty(userInfo());
	}
}
if (! function_exists('getUserType')) {
	function getUserType() {
		if (!empty(userInfo())) {
			switch (get_class(userInfo())) {
				case 'App\Admin':
					$userType = 'Admin';
					break;
				default:
					$userType = 'User';
			}
		} else {
			$userType = 'Guest';
		}
		return $userType;
	}
}
if (! function_exists('isAdminRoute')) {
	function isAdminRoute() {
		return strpos(\Route::currentRouteName(), 'admin') !== false;
	}
}
