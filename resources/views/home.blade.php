@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">
<div class="col-md-8 col-md-offset-2">
<div class="panel panel-default">
<div class="panel-heading">
{{ $user->name . 'さん: ' }}
{{ $user->id . ', ' }}
{{ $user->email }}
{{ '属性: ' . getUserType() }}
</div>

<div class="panel-body">
@if (session('status'))
<div class="alert alert-success">
{{ session('status') }}
</div>
@endif
@if (getUserType() == 'Admin')
<p><a href="{{ route('admin.item.index')}}">管理者商品一覧へ</a></p>
<p><a href="{{ route('admin.item.detail', ['id' => '1']) }}">管理者商品詳細ページへ->1</a></p>
<p><a href="{{ route('admin.item.form')}}">管理者商品追加へ</a></p>
<p><a href="{{ route('admin.item.form', ['id' => '1']) }}">商品編集ページへ</a></p>
@elseif (getUserType() == 'User')
<p>カート</p>
<p><a href="{{ route('cart.index')}}">カート一覧へ</a></p>
<p>商品</p>
<p><a href="{{ route('item.index')}}">商品一覧へ</a></p>
<p><a href="{{ route('item.detail', ['id' => '1']) }}">商品詳細ページへ->1</a></p>
@endif
You are logged in!
</div>
</div>
</div>
</div>
</div>
@endsection
