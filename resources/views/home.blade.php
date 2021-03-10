@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">
					{{ $user->name . 'さん: ' }}
					{{ 'ID: ' . $user->id . ', ' }}
					{{ 'email: ' . $user->email }}
					{{ '属性: ' . getUserType() }}
				</div>
				<div class="panel-body">
					@if (getUserType() == 'Admin')
					<p><a href="{{ route('admin.item.index')}}">管理者商品一覧へ</a></p>
					<p><a href="{{ route('admin.item.detail', ['id' => encrypt('1')]) }}">管理者商品詳細[1]ページへ</a></p>
					<p><a href="{{ route('admin.item.form')}}">管理者商品追加へ</a></p>
					<p><a href="{{ route('admin.item.form', ['id' => encrypt('1')]) }}">商品編集ページへ</a></p>
					<p><a href="{{ route('admin.account.index') }}">ユーザー一覧ページへ</a></p>
					<p><a href="{{ route('admin.detailForAdmin', ['id' => encrypt('2')]) }}">ユーザー詳細[2]へ</a></p>
					@elseif (getUserType() == 'User')
					<p>カート</p>
					<p><a href="{{ route('cart.index')}}">カート一覧へ</a></p>
					<p>商品</p>
					<p><a href="{{ route('item.index')}}">商品一覧へ</a></p>
					<p><a href="{{ route('item.detail', ['id' => encrypt('1')]) }}">商品詳細[1]ページへ</a></p>
					<p>住所</p>
					<p><a href="{{ route('address.index') }}">住所一覧へ</a></p>
					<p>アカウント</p>
					<p><a href="{{ route('account.detail') }}">ユーザー編集へ</a></p>
					@endif
				</div>
				<div class="panel-footer">
					@if (session('status'))
						<div class="alert alert-success">
							{{ session('status') }}
						</div>
					@endif
					サイトマップ
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
