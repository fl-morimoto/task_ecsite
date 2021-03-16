<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ItemInsertRequest;
use App\Http\Requests\ItemUpdateRequest;
use App\Item;

class ItemController extends Controller
{
	private $item;

	public function __construct(Item $item) {
		$this->item = new Item;
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
		$item = $this->item::find($item_id);
		return view('item/detail', compact('item'));
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
		//編集後のItemをDBにupdate
		$item->fill(['name' => $req->input('name')]);
		$item->fill(['content' => $req->input('content')]);
		$item->fill(['price' => $req->input('price')]);
		$item->fill(['quantity' => $req->input('quantity')]);
		if (!empty($filename)) {
			$item->fill(['image_name' => $filename]);
		}
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
