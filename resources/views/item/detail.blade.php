@extends('layouts.app')

@section('content')
<h1>商品詳細</h1>
<p width="200">{{ $item->name }}</p>
<p width="200">{{ $item->price }}</p>
<p width="200">{{ $item->quantity }}</p>
@if (0 < $item->quantity)
<p>在庫あり</p>
@else
<p>在庫なし</p>
@endif
@if (!isAdminRoute())
<p><a href="{{ route('item.index')}}">商品一覧へ</a></p>
@else
<p><a href="{{ route('admin.item.index') }}">商品一覧へ</a></p>
<p></p>
<p><a href="{{ route('admin.item.edit', ['id => $item->id']) }}">商品編集へ</a></p>
@endif
@endsection


