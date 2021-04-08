@extends('layouts.app')
@section('content')
<div class="container">
<div class="row">
<div class="col-md-8 col-md-offset-2">
<div class="panel panel-default">
	<div class="panel-heading" style="background-color:#f5f5f5">
	お問い合わせフォーム
	</div>
	<div class="panel-body">
		<form method="post" action="{{ route('question.detail') }}">
			{{ csrf_field() }}
			<input type="hidden" name="user_id" value="{{ $user->id ? encrypt($user->id) : old('user_id', '') }}">
			<input type="hidden" name="order_id" value="{{ $order_id ? encrypt($order_id) : old('order_id', '') }}">
			@if ($order_id)
			<p>注文番号 - {{ $order_id }} に対するお問い合わせ</p>
			@endif
			<p>件名</p>
			<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
				<p><input class="form-control" type="text" name="title" value="{{ old('title', '') }}"></p>
				@if ($errors->has('title'))
				<span class="help-block">
				<strong>{{ $errors->first('title') }}</strong>
				</span>
				@endif
			</div>
			<p>問い合わせの種類<a style="margin:0px 0px 0px 50px" href="{{ route('order.index') }}">注文に対するお問い合わせはこちらから</a></p>
			<p><select class="form-control" name="type_id">
			@foreach (config('dropmenu.question') as $index => $type)
				<option value="{{ $index }}" @if ($index == old('type_id', '')) selected @endif>{{ $type }}</option>'0'
			@endforeach
			</select></p>
			@if ($errors->has('type_id'))
			<span class="help-block">
			<strong>{{ $errors->first('type_id') }}</strong>
			</span>
			@endif
			<p>お問い合わせの内容</p>
			<div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
				<p><textarea class="form-control" style="width:500px;height:180px;resize:none" name="content" value="">{{ old('content', '') }}</textarea></p>
				@if ($errors->has('content'))
				<span class="help-block">
				<strong>{{ $errors->first('content') }}</strong>
				</span>
				@endif
			</div>
			<p>お客様のお名前</p>
			<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
				<p><input class="form-control" type="text" name="name" value="{{ $user->name ? $user->name : old('name', '') }}"></p>
				@if ($errors->has('name'))
				<span class="help-block">
				<strong>{{ $errors->first('name') }}</strong>
				</span>
				@endif
			</div>
			<p>メールアドレス</p>
			<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
				<p><input class="form-control" type="email" name="email" value="{{ $user->email ? $user->email : old('email', '') }}"></p>
				@if ($errors->has('email'))
				<span class="help-block">
				<strong>{{ $errors->first('email') }}</strong>
				</span>
				@endif
			</div>
			<p>確認用メールアドレス</p>
			<div class="form-group{{ $errors->has('valid_email') ? ' has-error' : '' }}">
				<p><input class="form-control" type="email" name="valid_email" value="{{ $user->email ? $user->email : old('valid_email') }}"></p>
				@if ($errors->has('valid_email'))
				<span class="help-block">
				<strong>{{ $errors->first('valid_email') }}</strong>
				</span>
				@endif
			</div>
			<p style="margin:20px"></p>
			<p><input type="submit" value="送信" style="width:100px;height:30px"></p>
		</form>
	</div>
</div>
</div>
</div>
</div>
@endsection
