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
if (! function_exists('isAdmin')) {
	function isAdmin() {
		if (isAdminRoute()) {
				$user = Auth::guard('admin')->user();
			} else {
				$user = Auth::guard('user')->user();
		}
		$bool = strpos(\Route::currentRouteName(), 'admin') !== false;
		return $bool && isset($user);
	}
}
function isAdminRoute() {
	$bool = strpos(\Route::currentRouteName(), 'admin') !== false;
	return $bool;
}
?>
