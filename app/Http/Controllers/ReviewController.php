<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ReviewRequest;
use App\Review;
use App\Item;

class ReviewController extends Controller
{
	private $review;
	private $item;

	public function __construct(Review $review, Item $item) {
		$this->review = $review;
		$this->item = $item;
	}
	public function insert(ReviewRequest $req) {
		if (!array_key_exists($req->review_point, config('dropmenu.review'))) {
			return redirect(route('review.form'))->with('false_message', '不正な操作です。');
		}
		$item_id = decryptOrNull($req->item_id);
		if (empty($item_id)) {
			return redirect(route('item.index'))->with('false_message', '不正な操作です。');
		}
		$user = userInfo();
		$this->review->fill([
			'item_id' => $item_id,
			'user_id' => $user->id,
			'review_point' => $req->review_point,
			'comment' => $req->comment,
		]);
		$this->review->save();
		return redirect(route('item.detail', ['id' => encrypt($item_id)]))->with('true_message', '商品レビューされました。');

	}
	public function form(Request $req) {
		$item_id = decryptOrNull($req->item_id);
		if (empty($item_id)) {
			return redirect(route('item.index'))->with('false_message', '不正な操作です。');
		}
		$item = $this->item
			->where('id', '=', $item_id)
			->first();
		return view('review/form', compact('item'));
	}

}
