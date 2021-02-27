@extends('layouts.app')
@section('content')
<body>
@if (0 < $carts->count())
<h1>カート内容</h1>
<table>
<tr style="background-color:#e3f0fb">
<th width="200">商品名</th>
<th width="100">購入数</th>
<th width="100">単価</th>
<th width="100">小計</th>
<th width="100">削除</th>
</tr>
@foreach ($carts as $cart)
<tr style="background-color:#f5f5f5">
<td align="right">{{ $cart->item->name }}</td>
<td align="right">{{ $cart->quantity }}</td>
<td align="right">{{ $cart->item->price }}</td>
<td align="right">{{ $cart->subtotal() }}</td>
<td align="center"><form method="post" action="{{ route('cart.delete') }}">
{{ csrf_field() }}
<input type="hidden" name="cart_id" value="{{ $cart->id }}">
<button type="submit">削除</button>
</form></tr>
@endforeach
<div style="background-color:#f5f5f5">
<td align="center">合計</td>
<td>{{ '合計金額' }}</td>
<td>税込: {{ '税込み' }}</td>
</div>
@endif
</body>
@endsection
