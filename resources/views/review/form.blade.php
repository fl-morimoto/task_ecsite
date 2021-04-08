@extends('layouts.app')
@section('content')
<div class="container">
<div class="row">
<div class="col-md-8 col-md-offset-2">
<div class="panel panel-default">
	<div class="panel-heading" style="background-color:#f5f5f5">
	「{{ $item->name }}」&nbsp;を商品レビューする。
	</div>
	<div class="panel-body">
		<form method="post" action="{{ route('review.insert') }}">
			{{ csrf_field() }}
			<input type="hidden" name="item_id" value="{{ $item->id ? encrypt($item->id) : old('item_id', '') }}">
			<p>レビュー</p>
			<p><select class="form-control" name="review_point">
			@foreach (config('dropmenu.review') as $index => $review)
				<option value="{{ $index }}" @if ($index == old('review_point', '')) selected @endif>{{ $review }}</option>
			@endforeach
			</select></p>
			@if ($errors->has('review_point'))
			<span class="help-block">
			<strong>{{ $errors->first('review_point') }}</strong>
			</span>
			@endif
			<p>レビューコメント</p>
			<div class="form-group{{ $errors->has('comment') ? ' has-error' : '' }}">
				<p><textarea class="form-control" style="width:500px;height:180px;resize:none" name="comment" value="">{{ old('comment', '') }}</textarea></p>
				@if ($errors->has('comment'))
				<span class="help-block">
				<strong>{{ $errors->first('comment') }}</strong>
				</span>
				@endif
			</div>
			<p style="margin:20px"></p>
			<p><input type="submit" value="送信" style="width:100px;height:30px"></p>
		</form>
	</div>
</div>
</div>
</div>
</div>
@endsection
