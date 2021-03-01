<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\User;

class Address extends Model
{
	use SoftDeletes;
	protected $fillable = ['user_id', 'name', 'zip', 'state', 'city', 'street', 'tel', 'address_sum'];
	protected $tabel = 'addresses';

	public function md5Address(Request $ad) {
		$param = userInfo()->id . $ad->zip . $ad->state . $ad->city . $ad->street . $ad->tel;
		$hashParam = md5($param);
		return $hashParam;
	}
}
