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
<div class="panel-body" style="margin 0px 0px 20px 0px">
	<p><a href="{{ route('cart.index') }}">カート一覧ページへ</a></p>
	@if (!empty($addresses))
		<table style="border-bottom: 1px solid #e0e0e0;">
			<caption>お届け先一覧</caption>
			<tr>
				<th>{{ '選択' }}</th>
				<th>{{ '郵便番号' }}</th>
				<th>{{ '都道府県' }}</th>
				<th>{{ '市' }}</th>
				<th>{{ '番地' }}</th>
				<th>{{ '電話番号' }}</th>
			</tr>
			@if (!$addresses->isEmpty())
				@foreach ($addresses as $index => $add)
					<tr>
						@if ($index == 0)
							<td><input type="radio" name="address_id" value="{{ $add->id }}" checked></td>
						@else
							<td><input type="radio" name="address_id" value="{{ $add->id }}"></td>
						@endif
						<td>{{ $add->zip }}</td>
						<td>{{ $add->state }}</td>
						<td>{{ $add->city }}</td>
						<td>{{ $add->street }}</td>
						<td>{{ $add->tel }}</td>
						<td style="font-weight:bold; text-align:left; text-indent: 1em">
							<a href="{{ route('address.updateForm', ['id' => encrypt($add->id)]) }}">編集</a>
						</td>
						<td style="font-weight:bold; text-align:left; text-indent: 1em">
							<a href="{{ route('address.delete', ['id' => encrypt($add->id)]) }}">削除</a>
						</td>
					</tr>
				@endforeach
			@else
				<tr>
					<td><p>登録された住所がありません。</p></td>
				</tr>
			@endif
		</table>
	@endif
	@include('address.form', ['address' => $address])
</div>
@endsection
