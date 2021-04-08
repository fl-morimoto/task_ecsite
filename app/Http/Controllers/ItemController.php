<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ItemInsertRequest;
use App\Http\Requests\ItemUpdateRequest;
use App\Item;
use App\Review;

class ItemController extends Controller
{
	private $item;
	private $review;

	public function __construct(Item $item, Review $review) {
		$this->item = $item;
		$this->review = $review;
	}
	public function index()
	{
		$items = $this->item::all();
		return view('item/index', compact('items'));
	}
	public function detail(Request $req)
	{
		$item_id = decryptOrNull($req->id);
		if (empty($item_id)) {
			if (getUserType() == 'User') {
				return redirect(route('item.index'))->with('false_message', 'アクセスIDが不正です。');
			} elseif (getUserType() == 'Admin') {
				return redirect(route('admin.item.index'))->with('false_message', 'アクセスIDが不正です。');
			}
		}
		$item = $this->item
			->select('items.*', 'reviews.review_point')
			->join('reviews', 'items.id', '=', 'reviews.item_id', 'left outer')
			->where('items.id', '=', $item_id)
			->first();
		$reviews = $this->review
			->select('reviews.*', 'users.name')
			->join('users', 'users.id', '=', 'reviews.user_id', 'left outer')
			->where('reviews.item_id', '=', $item_id)
			->orderBy('created_at', 'desc')
			->paginate(5);
		$avg_point = number_format($this->review->avgPoint($reviews), 2);
		return view('item/detail', compact('item', 'reviews', 'avg_point'));
	}
	public function form(Request $req)
   	{
		if (!empty($req->id)) {
			$item_id = decryptOrNull($req->id);
			if (empty($item_id)) {
				return redirect(route('admin.item.index'))->with('false_message', 'アクセスIDが不正です。');
			}
			$item = $this->item::find($item_id);
		} else {
			$item = new Item;
		}
		return view('item.form', compact('item'));
	}
	public function insert(ItemInsertRequest $req)
   	{
		$this->item->fill($req->all())->save();
		return redirect(route('admin.item.index'))->with('true_message', '商品を追加しました。');
	}
	public function update(ItemUpdateRequest $req)
	{
		$item = $this->item->findOrFail(decryptOrNull($req->id));
		if (empty($item)) {
			return redirect(route('admin.item.index'))->with('false_message', 'アクセスIDが不正です。');
		}
		//画像アップロード処理
		$filename = null;
		if (!empty($req->image)) {
			$file_extention = $this->getImageTypeOrNull($req);
			if (!empty($file_extention)) {
				$filename = $this->makeImageName($req) . $file_extention;
				$image_path = $req->image->storeAs('public/upload', $filename);
				if (!empty($item->image_name)) {
					Storage::delete('public/upload/' . $item->image_name);
				}
			} else {
				return redirect(route('admin.item.detail', ['id' => encrypt($item->id)]))->with('false_message', '画像はJPEG、PNG、GIFのみ登録できます。');
			}
		}
		$item->fill([
			'name' => $req->input('name'),
			'content' => $req->input('content'),
			'price' => $req->input('price'),
			'quantity' => $req->input('quantity'),
			'image_name' => $filename
		]);
		$item->save();
		return redirect(route('admin.item.detail', ['id' => encrypt($item->id)]))->with('true_message', '商品を編集しました。');
	}
	private function makeImageName($req) {
		$basename = md5($req->image->getClientOriginalName() . date('Y-m-d H:i:s'));
		return $basename;
	}
	private function getImageTypeOrNull($req) {
		$tmp_file = $req->image->getPathname();
		if (!is_uploaded_file($tmp_file)) {
			return null;
		}
		$image_type = @exif_imagetype($tmp_file);
		switch ($image_type) {
			case IMAGETYPE_JPEG:
				$ext = '.jpg';
				break;
			case IMAGETYPE_PNG:
				$ext = '.png';
				break;
			case IMAGETYPE_GIF:
				$ext = '.gif';
				break;
			default:
				$ext = null;
				break;
		}
		return $ext;
	}
}
