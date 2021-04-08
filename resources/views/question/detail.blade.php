@extends('layouts.app')
@section('content')
@if ($errors->any())
	<div class="alert alert-danger">
		<ul>
			@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
@endif
<div class="container">
<div class="row">
<div class="col-md-8 col-md-offset-2">
<div class="panel panel-default">
	@if ($is_post)
		<div class="panel-heading">お問い合わせの手続きの完了
	@else
		@if (getUserType() == 'Admin')
		<div class="panel-heading">お問い合わせ詳細
		<a style="margin:0px 0px 0px 30px" href="{{ route('admin.question.index') }}">お問い合わせ一覧へ</a></div>
		@else
		<div class="panel-heading">お問い合わせの確認
		@endif
	@endif
		<div class="panel-body">
			<table class="table-striped table-condensed" style="d-flex">
				<tbody style="font-size: 18px">
					<tr>
						<td style="width:25%">{{ '件名: ' }}</td>
						<td style="width:50%">{{ $req->title }}</td>
					</tr>
					<tr>
						<td>{{ 'お問い合わせの種類: ' }}</td>
						<td>{{ config('dropmenu.question.' . $req->type_id) }}</td>
					</tr>
					<tr>
						<td>{{ 'お問い合わせの内容: ' }}</td>
						<td>{{ $req->content }}</td>
					</tr>
					<tr>
						<td>{{ 'お名前: ' }}</td>
						<td>{{ $req->name . ' 様'}}</td>
					</tr>
					<tr>
						<td>{{ 'メールアドレス: ' }}</td>
						<td>{{ $req->email }}</td>
					</tr>
				</tbody>
			</table>
			@if ($is_post)
				<div class="d-flex flex-column" style="font-weight:bold;font-size:18px;text-align:left;margin:50px 0px 0px 0px">
					お問い合わせをこの内容で承りました。<br>メールをご確認ください。
			@else
				@if (getUserType() == 'Guest' || getUserType() == 'User')
				<div class="d-flex flex-column" style="text-align:center;margin:50px 0px 0px 0px">
					<form method="post" action="{{ route('question.post') }}">
						{{ csrf_field() }}
						<input type="hidden" name="user_id" value="{{ $req->user_id ? $req->user_id : null }}">
						<input type="hidden" name="order_id" value="{{ $req->order_id ? $req->order_id : null }}">
						<input type="hidden" name="type_id" value="{{ $req->type_id }}">
						<input type="hidden" name="email" value="{{ $req->email }}">
						<input type="hidden" name="name" value="{{ $req->name }}">
						<input type="hidden" name="title" value="{{ $req->title }}">
						<input type="hidden" name="content" value="{{ $req->content }}">
						<button type="submit">この内容で送信する</button>
					</form>
				@endif
			@endif
			</div>
		</div>
		</div>
	</div>
</div>
</div>
</div>
</div>
@endsection

