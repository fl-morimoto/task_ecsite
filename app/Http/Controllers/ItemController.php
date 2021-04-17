<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ItemInsertRequest;
use App\Http\Requests\ItemUpdateRequest;
use App\Item;
use App\Review;
use App\Lib\Facades\CSV;

class ItemController extends Controller
{
	private $item;
	private $review;

	public function __construct(Item $item, Review $review)
	{
		$this->item = $item;
		$this->review = $review;
	}
	public function upload(Request $req)
	{
		$validator = $this->item->validateCsvFile($req);
		if ($validator->fails()) {
			return redirect(route('admin.item.form'))->with('false_message', $validator->errors()->first('csvfile'));
		}
		$csv_obj = new \SplFileObject($req->file('csvfile')->path());
		$csv_obj->setFlags(
			\SplFileObject::READ_CSV |
			\SplFileObject::READ_AHEAD |
			\SplFileObject::SKIP_EMPTY |
			\SplFileObject::DROP_NEW_LINE
		);
		$items = [];
		foreach ($csv_obj as $index => $line) {
			//新規作成時、IDは'null'もしくは空きデータとする。
			$item = $this->item->makeItemArray($line);
			if ($item['id'] != 'ID') {
				$items[] = $item;
				$validator = $this->item->validateCsvRecoerd($item);
				if ($validator->fails()) {
					$index ++;
					return redirect(route('admin.item.form'))->with('false_message', 'Line: ' . $index . ' => ' . $validator->errors()->first());
				}
			}
		}
		DB::beginTransaction();
		try {
			$this->item->itemCreateOrUpdate($items);
			DB::commit();
		} catch (Exception $e) {
			DB::rollback();
			return redirect(route('admin.item.form'))->with('false_message', $e . 'が原因でCSVアップロードに失敗しました。');
		}
		return redirect(route('admin.item.index'))->with('true_message', 'CSVアップロードを完了しました。');
	}
	public function download()
	{
		$items = $this->item
			->select('id', 'name', 'content', 'price', 'quantity')
			->get()
			->toArray();
			$csvHeader = ['ID', '名前', '説明', '単価', '在庫数'];
		return CSV::download($items, $csvHeader, 'Item_list.csv');
	}
	public function index()
	{
		$items = $this->item
			->paginate(15);
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
		if (!empty($filename)) {
			$item->fill([
				'name' => $req->input('name'),
				'content' => $req->input('content'),
				'price' => $req->input('price'),
				'quantity' => $req->input('quantity'),
				'image_name' => $filename
			]);
		} else {
			$item->fill([
				'name' => $req->input('name'),
				'content' => $req->input('content'),
				'price' => $req->input('price'),
				'quantity' => $req->input('quantity'),
			]);
		}
		$item->save();
		return redirect(route('admin.item.detail', ['id' => encrypt($item->id)]))->with('true_message', '商品を編集しました。');
	}
	private function makeImageName($req)
	{
		$basename = md5($req->image->getClientOriginalName() . date('Y-m-d H:i:s'));
		return $basename;
	}
	private function getImageTypeOrNull($req)
	{
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
