<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Encryption\DecryptException;

if (! function_exists('decryptOrNull')) {
	function decryptOrNull($value) {
		try {
			$val = decrypt($value);
			return $val;
		} catch (DecryptException $e) {
			return null;
		}
	}
}
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
		if (isLogin()) {
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
if (! function_exists('isAdminLogin')) {
	function isAdminLogin() {
		return isAdminRoute() && strpos(\Route::currentRouteName(), 'login') !== false;
	}
}
