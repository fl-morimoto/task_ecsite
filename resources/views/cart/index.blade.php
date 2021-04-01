@extends('layouts.app')
@section('content')
<div class="container">
<div class="row">
<div class="col-md-8 col-md-offset-2">
<div class="panel panel-default">
	<div class="panel-heading">カート一覧
		@if (isLogin() && getUserType() == 'User')
			<a style="margin:0px 0px 0px 30px" href="{{ route('item.index') }}">商品一覧ページへ</a></div>
		@endif
		<div class="panel-body">
			@if (0 < $carts->count())
				<table class="table-striped table-condensed" style="font-size:16px">
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
								</form></td>
							</tr>
						@endforeach
					</tbody>
				</table>
				<div class="panel-footer" style="font-size: 16px;margin: 0px 0px 20px 0px">
					<label align="center">支払い総額</label>
					<label style="margin:0px 0px 0px 10px">￥ {{ number_format($total) }} 円（税込） </label>
				</div>
				<form action="{{ route('address.index') }}" method="POST" style="margin:0px 0px 0px 20px">
					{{ csrf_field() }}
					<input type="hidden" name="carts" value="{{ $carts }}">
					<button type="submit">レジに進む（お届け先選択ページ）</button>
				</form>
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





