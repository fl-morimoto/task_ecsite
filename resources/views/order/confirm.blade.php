@extends('layouts.app')
@section('content')
</div>
<div class="container">
<div class="row">
<div class="col-md-8 col-md-offset-2">
<div class="panel panel-default">
	<div class="panel-heading">ご注文内容</div>
		<div class="panel-body">
			@if (0 < count($carts))
				<table class="table-striped table-condensed" style="font-size:16px">
					<tbody>
						<tr style="background-color:#f5f5f5">
							<th style="width:15%;text-align:center">商品名</th>
							<th style="width:10%;text-align:center">価格</th>
							<th style="width:10%;text-align:center">購入数</th>
							<th style="width:10%;text-align:center">小計</th>
						</tr>
						@foreach ($carts as $cart)
							<tr>
								<td style="text-align:right">{{ $cart->item->name }}</td>
								<td style="text-align:right">{{ $cart->item->price }}</td>
								<td style="text-align:right">{{ $cart->quantity }}</td>
								<td style="text-align:right">{{ $cart->subtotal() }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
				<div class="panel-footer" style="font-size: 16px;margin: 0px 0px 20px 0px">
					<label style="text-align:center;margin:0px 0px 0px 30px">支払い総額</label>
					<label style="margin:0px 0px 0px 10px">￥ {{ number_format($total) }} 円（税込） </label>
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
<div class="container">
<div class="row">
<div class="col-md-2"></div>
<div class="col-md-8" style="padding:15px;margin:-15px 0px -10px 0px">
<div class="panel panel-default">
	<div class="panel-heading">選択されたお届先住所</div>
		<div class="panel-body">
			<p style="font-weight:bold;margin:0px 0px 0px 45px">〒{{ $address->zip . ' ' .
																	 config('pref.' . $address->state) . ' ' .
																	 $address->city . ' ' .
																	 $address->street }} &nbsp;&nbsp;&nbsp;
																	 {{ 'Tel - ' .
																	 $address->tel }}</p>
		</div>
	</div>
</div>
</div>
<div class="col-md-2"></div>
</div>
</div>
@if (!empty($total))
	<div style="margin-bottom:50px">
		<form action="{{ route('order.charge') }}" method="POST" class="text-center mt-5">
			{{ csrf_field() }}
			<script
				src="https://checkout.stripe.com/checkout.js" class="stripe-button"
				data-key="{{ config('services.stripe.key') }}"
				data-amount="{{ $total }}"
				data-name="クレジット決済"
				data-label="この内容で購入する"
				data-description="商品代金として。"
				data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
				data-locale="auto"
				data-currency="JPY">
			</script>
		</form>
	</div>
@endif
@endsection
