<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;
use Carbon\Carbon;

class Order extends Model
{
	use SoftDeletes;
	use Sortable;

	protected $fillable = ['amount', 'user_id', 'user_name', 'user_zip', 'user_state', 'user_city', 'user_street', 'user_tel'];
	protected $dates = ['deleted_at'];

	public $sortable = ['created_at', 'amount', 'user_name'];
    //
	public function fullAddress() {
		$full_address = $this->user_zip . "&nbsp;" .
						 config('pref.' . $this->user_state) . "&nbsp;" .
						 $this->user_city . "&nbsp;" .
						 $this->user_street . "&nbsp;&nbsp;&nbsp;" . 'Tel - ' .
						 $this->user_tel;
		return $full_address;
	}
	public function search_date_to($req) {
		if (!empty($req->date_to)) {
			return $req->date_to;
		} else {
			return '0';
		}
	}
	public function date_to($req) {
		if (!empty($req->date_to)) {
			return (new Carbon($req->date_to))->addDays(1)->format('Y-m-d');
		} else {
			return '2999-12-31';
		}
	}
	public function search_date_from($req) {
		if (!empty($req->date_from)) {
			return $req->date_from;
		} else {
			return '0';
		}
	}
	public function date_from($req) {
		if (!empty($req->date_from)) {
			return $req->date_from;
		} else {
			return '1900-01-01';
		}
	}
	public function search_amount_to($req) {
		if (!empty($req->amount_to)) {
			return $req->amount_to;
		} else {
			return '0';
		}
	}
	public function amount_to($req) {
		if (!empty($req->amount_to)) {
			return config('dropmenu.amount_to.' . $req->amount_to);
		} else {
			return '99999999';
		}
	}
	public function search_amount_from($req) {
		if (!empty($req->amount_from)) {
			return $req->amount_from;
		} else {
			return '0';
		}
	}
	public function amount_from($req) {
		if (!empty($req->amount_from)) {
			return config('dropmenu.amount_from.' . $req->amount_from);
		} else {
			return '0';
		}
	}
	public function search_username($req) {
		if (!empty($req->username)) {
			return '%' . $req->username . '%';
		} else {
			return '';
		}
	}
	public function username($req) {
		if (!empty($req->username)) {
			return $req->username;
		} else {
			return '';
		}
	}
	public function status($req) {
		if (!empty($req->status_id)) {
			return $req->status_id;
		} else {
			return '0';
		}
	}
	public function usernameOpe($req) {
		if ($req->username == "") {
			return '<>';
		} else {
			return 'like';
		}
	}
	public function statusOpe($req) {
		if ($req->status_id == 0) {
			return '<>';
		} else {
			return '=';
		}
	}
}
