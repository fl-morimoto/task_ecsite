<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\AccountRequest;
use App\User;
use App\ChangeUser;

class AccountController extends Controller
{
	private $user;
	private $change_user;
	public function __construct(User $user, ChangeUser $change_user) {
		$this->user = $user;
		$this->change_user = $change_user;
	}
	public function detail() {
		$user = userInfo();
		return view('account.detail', compact('user'));
	}
	public function update(AccountRequest $req) {
		$user = $this->user->find(userInfo()->id);
		//現在パスワードの確認
		if (!empty($req->new_pass)) {
			$new_pass = Hash::make($req->new_pass);
		} else {
			$new_pass = $user->password;
		}
		$check_pass = Hash::check($req->current_pass, $user->password);
		if (!$check_pass) {

			return redirect(route('account.detail'))->with('false_message', 'パスワードが違います。');
		}
		//token生成
		$token = hash_hmac('sha256', str_random(40) . $req->email, env('APP_KEY'));
		//change_userデータを生成
		$this->change_user->fill([
			'user_id' => $user->id,
			'new_name' => $req->name,
			'new_email' => $req->email,
			'new_password' => $new_pass,
			'update_token' => $token,
		]);
		//emailが更新されているか？
		$change_email = $user->email != $req->email;
		if ($change_email) {
			//URLの生成
			$url = config('const.Urls.PUBLIC') . 'account/updateEmail?token=' . $token;
			//メール本文生成
			$body = 'URL先を開いてemailの変更を確認してください。' . "\n" . $url;
			//メール送信
			Mail::raw($body, function ($m) use (&$user, &$req) {
				$m->from('hello@app.com', 'from :ecsite - user info');
				$m->to($req->email, $user->name)->subject('your reminder!');
			});
			$this->change_user->save();
			return redirect(route('account.detail'))->with('true_message', 'emailはまだ更新されていません。' . "\n" . 'メールを確認して更新処理を完了してください。');
		} else {
			//emailの更新がなければ仮データで本データを更新
			if ($user->name != $this->change_user->new_name || $user->email != $this->change_user->new_email || !empty($req->new_pass)) {
				$user->fill([
					'name' => $this->change_user->new_name,
					'email' => $this->change_user->new_email,
					'password' => $new_pass,
				])->save();
				return redirect(route('account.detail'))->with('true_message', 'ユーザー情報を更新しました。');
			} else {
				return redirect(route('account.detail'))->with('false_message', 'ユーザー情報の変更がありません。');

			}

		}
	}
	public function updateEmail(Request $req) {
		//時間制限
		$user = $this->user->find(userInfo()->id);
		$token = $req->input('token');
		$change_user = $this->change_user->where('update_token', $token)->first();
		$user->fill([
			'name' => $change_user->new_name,
			'email' => $change_user->new_email,
			'password' => $change_user->new_password,
		]);
		$user->save();
		$change_user->delete();
		return redirect(route('account.detail'))->with('true_message', 'ユーザー情報を更新しました。');
	}
}

