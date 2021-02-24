@extends('layouts.app')
@section('content')
<body>

@if (0 < $carts->count())
<table>
<h1>カート内容</h1>
<tr style="background-color:#e3f0fb">
<th>商品名</th>
<th>購入数</th>
<th>単価</th>
<th>価格小計</th>
<th>削除</th>
</tr>
@foreach ($carts as $cart)
<tr style="background-color:#f5f5f5">
<td align="right">{{ $cart->item->name }}</td>
<td align="right">{{ $cart->quantity }}</td>
<td align="right">{{ $cart->price }}</td>
<td align="right">{{ $cart->subtotal() }}</td>
<td><form method="post" action="{{ route('cart.delete') }}">
{{ csrf_field() }}
<input type="hidden" name="cart_id" value="{{ $cart->id }}">
<button type="submit">削除</button>
</form></td></tr>
@endforeach
<td style="background-color:#f5f5f5">
<td>合計</td>
<?php dd('trap'); ?>
<td>{{ $subtotals }}</td>
<td>税込: {{ $totals }}</td>
<td></td>
</td>
</table>
<br>
<--form method="get" action="{{ route('address.index') }}">
{{ csrf_field() }}
<button type="submit">お届け先選択</button>
</form-->
@else
<h1>カートに商品はありません</h1>
@endif
<br>
<form method="get" action="{{ route('item.index') }}">
{{ csrf_field() }}
<button type="submit">商品一覧</button>
</form>
</body>
@endsection
