<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\AccountRequest;
use App\User;
use App\ChangeUser;
use Carbon\Carbon;

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
	private function makeNewPassword($req, $user) {
		//新パスワードの初期設定
		if (!empty($req->new_pass)) {
			if (mb_strlen($req->new_pass) < 6) {
				return false;
			}
			$new_pass = Hash::make($req->new_pass);
		} else {
			$new_pass = $user->password;
		}
		return $new_pass;
	}
	private function sendMail($token, $user, $req) {
		$url = config('const.Urls.PUBLIC') . 'account/updateEmail?token=' . $token;
		$body = 'URL先を開いてユーザー情報の変更を完了してください。' . "\n" . $url;
		Mail::raw($body, function ($m) use (&$user, &$req) {
			$m->from('hello@app.com', 'from :ecsite - user info');
			$m->to($req->email, $user->name)->subject('ユーザー情報変更の確認メールです');
		});
	}
	public function update(AccountRequest $req) {
		$user = $this->user->find(userInfo()->id);
		$new_pass = $this->makeNewPassword($req, $user);
		if (!$new_pass) {
			return redirect(route('account.detail'))->with('false_message', 'パスワードは6文字以上にしてください。');
		}
		//現在パスワードの確認 -> ガード節
		$check_pass = Hash::check($req->current_pass, $user->password);
		if (!$check_pass) {
			return redirect(route('account.detail'))->with('false_message', 'パスワードが違います。');
		}
		$token = hash_hmac('sha256', str_random(40) . $req->email, env('APP_KEY'));
		//change_userデータを生成
		$this->change_user->fill([
			'user_id' => $user->id,
			'new_name' => $req->name,
			'new_email' => $req->email,
			'new_password' => $new_pass,
			'update_token' => $token,
		]);
		$change_email = $user->email != $req->email;
		if ($change_email) {
			//emailの変更があればメール送信
			$this->sendmail($token, $user, $req);
			$this->change_user->save();
			return redirect(route('account.detail'))->with('true_message', 'ユーザー情報はまだ更新されていません。' . "\n" . 'メールを確認して更新処理を完了してください。');
		} else {
			//emailの更新がなければ直にデータを更新
			$change_userinfo = $user->name != $this->change_user->new_name || $user->email != $this->change_user->new_email || !empty($req->new_pass);
			if ($change_userinfo) {
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
		$change_user = $this->change_user->where(['update_token' => $token, 'user_id' => userInfo()->id])->first();
		if (!empty($chenge_user) && Carbon::now()->subMinutes(30) <= $change_user->created_at) {
			//30分以内なら
			$user->fill([
				'name' => $change_user->new_name,
				'email' => $change_user->new_email,
				'password' => $change_user->new_password,
			])->save();
			$change_user->delete();
			return redirect(route('account.detail'))->with('true_message', 'ユーザー情報を更新しました。');
		} else {
			return redirect(route('account.detail'))->with('false_message', 'このURLは無効です。');
		}
	}
}

