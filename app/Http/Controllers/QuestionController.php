<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Http\Requests\QuestionRequest;
use App\User;
use App\Question;

class QuestionController extends Controller
{
	private $user;
	private $question;

	public function __construct(
		User $user,
		Question $question)
	{
		$this->user = $user;
		$this->question = $question;
	}
	private function sendMail($question, $email) {
		$body = $question->name . '様' . "\n" . "\n" .
			'下記の内容でお問い合わせを承りました。' . "\n" .
			'ご返信までしばらくお待ちくださるようお願いいたします。' . "\n" . "\n" .
			'「' . $question->content . '」' . "\n" . "\n" .
			'from ecsite.';
		Mail::raw($body, function ($m) use (&$question, $email) {
			$m->from('customer@ecsite.com', 'from :ecsite - user info');
			$m->to($email, $question->name)->subject('お問い合わせのご確認: お問い合わせ番号 - ' . $question->id);
		});
	}
	//Admin
	public function index() {
		$questions = $this->question
			->where('id', '>', 0)
			->orderBy('created_at', 'desc')
			->paginate(15);
		return view('question.index', compact('questions'));
	}
	public function detailForAdmin(Request $req) {
		if (empty(decryptOrNull($req->id))) {
			return redirect(route('admin.question.index'))->with('false_message', '不正な操作です。');
		}
		$is_post = false;
		$req = $this->question
			->where('id', decryptOrNull($req->id))
			->first();
		return view('question.detail', compact('req', 'is_post'));
	}
	//Guest, User
	public function post(Request $req) {
		if (!array_key_exists($req->type_id, config('dropmenu.question'))) {
			return redirect(route('question.form'))->with('false_message', '不正な操作です。');
		}
		$user_id = decryptOrNull($req->user_id);
		$order_id = decryptOrNull($req->order_id);
		$question = $this->question;
		$question->fill([
			'user_id' => $user_id,
			'order_id' => $order_id,
			'type_id' => $req->type_id,
			'email' => $req->email,
			'name' => $req->name,
			'title' => $req->title,
			'content' => $req->content,
		]);
		try {
			DB::beginTransaction();
			$this->question->save();
			$this->sendMail($this->question, $this->question->email);
			$this->sendMail($this->question, config('mail.from.address'));
			$is_post = true;
			DB::commit();
			return view('question.detail', compact('req', 'is_post'));
		} catch (Exception $e) {
			DB::rollback();
			return redirect(route('question.form'))->with('false_message', $e . 'のエラーでお問い合わせの送信に失敗しました。');
		}
	}
	public function detail(QuestionRequest $req) {
		if (!array_key_exists($req->type_id, config('dropmenu.question'))) {
			return redirect(route('question.form'))->with('false_message', '不正な操作です。');
		}
		$is_post = false;
		return view('question.detail', compact('req', 'is_post'));
	}
	public function form(Request $req) {
		$order_id = decryptOrNull($req->order_id);
		if (!empty(userInfo()) && getUserType() == 'User') {
			$user = userInfo();
		} else {
			$user = new User([
				'name' => '',
				'email' => '',
				'password' => '',
			]);
		}
		return view('question.form', compact('user', 'order_id'));
	}
}
