@extends('layouts.app')
@section('content')
<?php
	$name = $user->name;
	$email = $user->email;
?>
<div class="container">
<div class="row">
<div class="col-md-8 col-md-offset-2">
<div class="panel panel-default">
<div class="panel-heading">会員詳細
	<a style="margin:0px 0px 0px 30px" href="{{ route('admin.account.index') }}">会員一覧へ</a></div>
	<div class="panel-body">
		<div class="col-md-3 control-label"><label for="name">名前</label></div>
		<div class="col-md-7 control-label"><label for="name">{{ $name }}</label></div>
		<div class="col-md-3 control-label"><label for="email">E-Mail Address</label></div>
		<div class="col-md-7 control-label"><label for="email">{{ $email }}</label></div>
		<div class="col-md-10 control-label" style=".border-bottom;margin: 10px 0px 0px 0px"><p></p></div>
			@if (!$addresses->isEmpty())
				@foreach ($addresses as $index => $add)
					<div class="col-md-8 control-label">
						<tr>
							<td>{{ $add->zip }}</td>
							<td>{{ config('pref.' . $add->state) }}</td>
							<td>{{ $add->city }}</td>
							<td>{{ $add->street }}</td>
							<td>{{ $add->tel }}</td>
						</tr>
					</div>
				@endforeach
			@else
					<div class="col-md-8 control-label">
						<tr>
							<td>登録された住所がありません。</td>
						</tr>
					</div>
			@endif
	</div>
</div>
</div>
</div>
</div>
</div>
@endsection
