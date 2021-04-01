@extends('layouts.app')
@section('content')
@if ($errors->any())
	<div class="alert alert-danger">
		@foreach ($errors->all() as $error)
			<ul>
				<li>{{ $error }}</li>
			</ul>
		@endforeach
	</div>
	<?php
	//バリデーションエラー時の入力値再現
	$address->zip = old('zip');
	$address->state = old('state');
	$address->city = old('city');
	$address->street = old('street');
	$address->tel = old('tel');
	?>
@endif
@if (!empty($addresses))
<div class="container">
<div class="row">
<div class="col-md-8 col-md-offset-2">
<div class="panel panel-default">
<div class="panel-heading">お届け先住所の選択
	<a style="margin:0px 0px 0px 30px" href="{{ route('cart.index') }}">カート一覧ページへ</a></div>
@endif
	<div class="panel-body">
		@if (!empty($addresses) && 0 < $addresses->count())
			<form action="{{ route('order.confirm') }}" method="POST" name="address_id">
				{{ csrf_field() }}
				<table style="border-bottom: 1px solid #e0e0e0;">
					<tbody>
						<tr>
							<th style="width=150px">選択</th>
							<th style="text-align:center">住所</th>
						</tr>
						@foreach ($addresses as $index => $add)
						<tr>
							@if ($index == 0)
								<td><input type="radio" name="address" value="{{ $add->id }}" checked></td>
							@else
								<td><input type="radio" name="address" value="{{ $add->id }}"></td>
							@endif
							<td>{{ $add->fullAddress() }}
							<td style="font-weight:bold; text-align:left; text-indent: 1em">
								<a href="{{ route('address.updateForm', ['id' => encrypt($add->id)]) }}">編集</a>
							</td>
							<td style="font-weight:bold; text-align:left; text-indent: 1em">
								<a href="{{ route('address.delete', ['id' => encrypt($add->id)]) }}">削除</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				<p style="margin:20px"></p>
				@if (!empty($carts))
					<input type="hidden" name="carts" value="{{ $carts }}">
					<input type="submit" value="お届け先を選択して注文の確認へ">
				@endif
			</form>
		@elseif (empty($address->id))
						<tr>
							<td>登録された住所がありません。</td>
						</tr>
					</tbody>
				</table>
		@endif
	</div>
@if (!empty($addresses))
</div>
</div>
</div>
</div>
</div>
@endif
@include('address.form', ['address' => $address])
@endsection

