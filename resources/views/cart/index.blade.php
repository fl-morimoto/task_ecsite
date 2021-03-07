@extends('layouts.app')
@section('content')
<div style="font-size: 16px;margin: 0px 0px 20px 20px">
	@if (isLogin() && getUserType() == 'User')
		<p><a href="{{ route('item.index') }}">商品一覧ページへ</a></p>
		<p><a href="{{ route('address.index') }}">お届け先選択ページへ</a></p>
	@endif
</div>
<div class="container">
<div class="row">
<div class="col-md-8 col-md-offset-2">
<div class="panel panel-default">
	<div class="panel-heading">カート一覧</div>
		<div class="panel-body">
			@if (0 < $carts->count())
				<table class="table">
					<tbody>
						<tr style="background-color:#f5f5f5">
							<th width="200">商品名</th>
							<th width="100">価格</th>
							<th width="100">購入数</th>
							<th width="100">小計</th>
							<th width="100">削除</th>
						</tr>
						@foreach ($carts as $cart)
							<tr>
								<td align="right">{{ $cart->item->name }}</td>
								<td align="right">{{ $cart->item->price }}</td>
								<td align="right">{{ $cart->quantity }}</td>
								<td align="right">{{ $cart->subtotal() }}</td>
								<td align="center"><form method="post" action="{{ route('cart.delete') }}">
								{{ csrf_field() }}
								<input type="hidden" name="cart_id" value="{{ encrypt($cart->id) }}">
								<button type="submit">削除</button>
								</form>
							</tr>
						@endforeach
					</tbody>
				</table>
				<div class="panel-footer" style="font-size: 16px;margin: 0px 0px 20px 20px">
					<label align="center">カート合計 ￥ </label>
					<label>{{ number_format($total) }} 円 (税抜)</label>
				</div>
			@else
				<div class="panel-footer" style="font-size: 16px;margin: 0px 0px 20px 20px">
					<p>カートが空です。</p>
				</div>
			@endif
		</div>
	</div>
</div>
</div>
</div>
</div>
@endsection
