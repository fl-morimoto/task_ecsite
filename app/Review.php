<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
	use SoftDeletes;
	protected $fillable = ['user_id', 'item_id', 'review_point', 'comment'];
	protected $table = 'reviews';

	public function avgPoint($reviews) {
		$count = $reviews->count();
		if (0 < $count) {
			$sum = $reviews->sum('review_point');
			$res = $sum / $count;
		} else {
			$res = null;
		}
		return $res;
	}
}
