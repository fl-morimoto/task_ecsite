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

	public function fullAddress() {
		$full_address = $this->zip . "&nbsp;" .
						 config('pref.' . $this->state) . "&nbsp;" .
						 $this->city . "&nbsp;" .
						 $this->street . "&nbsp;&nbsp;&nbsp;" . 'Tel - ' .
						 $this->tel;
		return $full_address;
	}
}
