<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Address;

class AddressController extends Controller
{
	private $address;

	public function __construct() {
		$this->address = new Address;
	}
	public function index() {
		$addresses = Address::where('user_id', userinfo()->id)->get();
		return view('address.index', compact('addresses'));
	}
	public function add(Request $req) {
		$md5_address = $this->address->md5Address($req);
		$is_new = Address::where('address_sum', $md5_address)->get()->isEmpty();
		if ($is_new) {
			$req->merge(['user_id' => userInfo()->id]);
			$req->merge(['address_sum' => $md5_address]);
			$this->address->fill($req->all())->save();
			return redirect(route('address.index'))->with('true_message', '住所を追加しました。');
		} else {
			return redirect(route('address.index'))->with('false_message', 'すでに同じ住所があります。');
		}
	}
}
