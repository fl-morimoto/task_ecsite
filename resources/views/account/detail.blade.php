@extends('layouts.app')
@section('content')
<?php
if (empty(old('name'))) {
	$name = $user->name;
} else {
	$name = old('name');
}
if (empty(old('email'))) {
  $email = $user->email;
} else {
	$email = old('email');
}
?>
<div class="container">
<div class="row">
<div class="col-md-8 col-md-offset-2">
<div class="panel panel-default">
<div class="panel-heading">ユーザー情報編集</div>
	<div class="panel-body">
		<form class="form-horizontal" method="POST" action="{{ route('account.update') }}">
			{{ csrf_field() }}
			<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
				<label for="email" class="col-md-4 control-label">名前</label>
				<div class="col-md-6">
					<input id="name" type="text" class="form-control" name="name" value="{{ $name }}" autofocus>
					@if ($errors->has('name'))
					<span class="help-block">
					<strong>{{ $errors->first('name') }}</strong>
					</span>
					@endif
				</div>
			</div>
			<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
				<label for="email" class="col-md-4 control-label">E-Mail Address</label>
				<div class="col-md-6">
					<input id="email" type="email" class="form-control" name="email" value="{{ $email }}">
					@if ($errors->has('email'))
					<span class="help-block">
					<strong>{{ $errors->first('email') }}</strong>
					</span>
					@endif
				</div>
			</div>
			<div class="form-group{{ $errors->has('new_pass') ? ' has-error' : '' }}">
				<label for="password" class="col-md-4 control-label">新しいパスワード</label>
				<div class="col-md-6">
					<input id="new_pass" type="password" class="form-control" name="new_pass">
					@if ($errors->has('password'))
					<span class="help-block">
					<strong>{{ $errors->first('new_pass') }}</strong>
					</span>
					@endif
				</div>
			</div>
			<div class="form-group{{ $errors->has('confirm_pass') ? ' has-error' : '' }}">
				<label for="confirm_pass" class="col-md-4 control-label">新しいパスワードの確認</label>
				<div class="col-md-6">
					<input id="confirm_pass" type="password" class="form-control" name="confirm_pass">
					@if ($errors->has('confirm_pass'))
					<span class="help-block">
					<strong>{{ $errors->first('confirm_pass') }}</strong>
					</span>
					@endif
				</div>
			</div>
			<div class="form-group{{ $errors->has('current_pass') ? ' has-error' : '' }}">
				<label for="current_pass" class="col-md-4 control-label">現在のパスワード(必須)</label>
				<div class="col-md-6">
					<input id="current_pass" type="password" class="form-control" name="current_pass">
					@if ($errors->has('current_pass'))
					<span class="help-block">
					<strong>{{ $errors->first('current_pass') }}</strong>
					</span>
					@endif
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-8 col-md-offset-4">
					<button type="submit" class="btn btn-primary">
						送信
					</button>
				</div>
			</div>
		</form>
	</div>
</div>
</div>
</div>
</div>
</div>
@endsection
