@extends('layouts.app')
@section('content')
<div class="container">
<div class="row">
<div class="col-md-12">
<div class="panel panel-default">
	<div class="panel-heading">お問い合わせ一覧:
		<a style="margin:0px 0px 0px 30px" href="{{ route('admin.home') }}">管理者メニューへ</a></div>
		@if (0 < $questions->count())
			<div class="panel-body">
				<table class="table-striped table-condensed" style="font-size:16px">
					<tbody>
						<tr style="background-color:#f5f5f5">
							<th style="width:8%;text-align:center">日付</th>
							<th style="width:20%;text-align:center">件名</th>
							<th style="width:25%;text-align:center">お問い合わせの種類</th>
							<th style="width:13%;text-align:center">顧客名</th>
							<th style="width:17%;text-align:center">メールアドレス</th>
							<th style="width:8%;text-align:center">詳細</th>
						</tr>
						@foreach ($questions as $question)
							<tr>
								<td>{{ date('Y/m/d', strtotime($question->created_at)) }}</td>
								<td style="text-align:right">{{ $question->title }}</td>
								<td style="text-align:right">{{ config('dropmenu.question.' . $question->type_id) }}</td>
								<td style="text-align:right">{{ $question->name }}</td>
								<td style="text-align:right">{{ $question->email }}</td>
								<td style="text-align:right"><a href="{{ route('admin.question.detail', ['id' => encrypt($question->id)]) }}">詳細</a></td>
							</tr>
						@endforeach
					</tbody>
				</table>
				<div style="text-align:center">{{ $questions->links() }}</div>
			</div>
		@else
			<div class="panel-heading">お問い合わせはありません。
				<div class="panel-body"></div>
			</div>
		@endif
	</div>
</div>
</div>
</div>
</div>
@endsection
