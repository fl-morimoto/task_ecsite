@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">
<div class="col-md-8 col-md-offset-2">
<div class="panel panel-default">
<div class="panel-heading">
<?php
$userType = getUserType();
?>
{{ $user->name . 'さん: ' }}
{{ $user->id . ', ' }}
{{ $user->email }}
{{ '属性: ' . $userType }}
</div>

<div class="panel-body">
@if (session('status'))
<div class="alert alert-success">
{{ session('status') }}
</div>
@endif
<p><a href="{{ route('admin.item.index')}}">管理者商品一覧へ</a></p>
<p><a href="{{ route('admin.item.create')}}">管理者商品追加へ</a></p>
<p><a href="{{ route('admin.item.edit', ['id' => '1']) }}">商品編集ページへ</a></p>
<p><a href="{{ route('admin.item.detail', ['id' => '1']) }}">管理者商品詳細ページへ</a></p>
You are logged in!
</div>
</div>
</div>
</div>
</div>
@endsection
