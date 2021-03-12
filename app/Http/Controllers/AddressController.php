<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddressRequest;
use App\Address;

class AddressController extends Controller
{
	private $address;

	public function __construct() {
		$this->address = new Address;
	}
	public function index() {
		$addresses = $this->address->where('user_id', userinfo()->id)->get();
		$address = new Address;
		return view('address.index', compact('addresses', 'address'));
	}
	private function md5Address(Request $ad) {
		$param = userInfo()->id . $ad->zip . $ad->state . $ad->city . $ad->street . $ad->tel;
		$hashParam = md5($param);
		return $hashParam;
	}
	//要バリデーション
	public function insert(AddressRequest $req) {
		$md5_address = $this->md5Address($req);
		$is_new = $this->address->where('address_sum', $md5_address)->get()->isEmpty();
		if ($is_new) {
			$req->merge(['user_id' => userInfo()->id]);
			$req->merge(['address_sum' => $md5_address]);
			$this->address->fill($req->all())->save();
			return redirect(route('address.index'))->with('true_message', '住所を追加しました。');
		} else {
			return redirect(route('address.index'))->with('false_message', 'すでに同じ住所があります。');
		}
	}
	public function updateForm(Request $req) {
		$address_id = decryptOrNull($req->input('id'));
		if (empty($address_id)) {
			return redirect(route('address.index'))->with('false_message', 'アクセスIDが不正です。');
		}
		$address = $this->address->find($address_id);
		if (!empty($address)) {
			return view('address.index', compact('address'));
		} else {
			return redirect(route('address.index'))->with('false_message', 'その住所は見つかりません。');
		}
	}
	//要バリデーション
	public function update(AddressRequest $req) {
		if (empty($req)) {
			return redirect(route('address.index'))->with('false_message', '無効な操作です。');
		}
		$address_id = decryptOrNull($req->input('id'));
		if (empty($address_id)) {
			return redirect(route('address.index'))->with('false_message', 'アクセスIDが不正です。');
		}
		$address = $this->address->find($address_id);
		$address->zip = $req->zip;
		$address->state = $req->state;
		$address->city = $req->city;
		$address->street = $req->street;
		$address->tel = $req->tel;
		$address->save();
		return redirect(route('address.index'))->with('true_message', '住所を編集しました。');
	}
	public function delete(Request $req) {
		$address_id = decryptOrNull($req->input('id'));
		if (empty($address_id)) {
			return redirect(route('address.index'))->with('false_message', 'アクセスIDが不正です。');
		}
		$address = $this->address->find($address_id);
		if (!empty($address)) {
			$address->delete();
			return redirect(route('address.index'))->with('true_message', '住所を削除しました。');
		} else {
			return redirect(route('address.index'))->with('false_message', 'その住所は存在しません。');
		}
	}
}
