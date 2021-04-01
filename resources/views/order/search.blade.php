<div class="col-md-3">
	<div class="card">
		<div class="card-body">
			<div class="panel panel-default">
				<div class="panel-heading">名前で探す</div>
				<ul class="nav nav-pills nav-stacked" style="display:block;padding:15px">
					<li>
					<form action="{{ route('admin.order.index') }}" method="POST">
						{{ csrf_field() }}
						@if (!empty($search['username']))
						<input type="text" name="username" value="{{ $search['username']}}">
						@else
						<input type="text" name="username" value="">
						@endif
						@if (!empty($search['date_from']))
						<input type="hidden" id="datepicker" name="date_from" value="{{ $search['date_from'] }}">
						@endif
						@if (!empty($search['date_to']))
						<input type="hidden" id="datepicker" name="date_to" value="{{ $search['date_to'] }}">
						@endif
						@if (!empty($search['amount_from']))
						<input type="hidden" name="amount_from" value="{{ $search['amount_from']}}">
						@endif
						@if (!empty($search['amount_to']))
						<input type="hidden" name="amount_to" value="{{ $search['amount_to']}}">
						@endif
						@if (!empty($search['status_id']))
						<input type="hidden" name="status_id" value="{{ $search['status_id']}}">
						@endif
						</select>
						<input style="float:right" type="submit" value="検索">
					</form>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="card">
		<div class="card-body">
			<div class="panel panel-default">
			<div class="panel-heading">日付から探す</div>
				<ul class="nav nav-pills nav-stacked" style="display:block;padding:15px">
					<li>
					<form action="{{ route('admin.order.index') }}" method="POST">
						{{ csrf_field() }}
						<p><input type="date" id="datepicker" name="date_from" value="{{ $search['date_from'] }}">&nbsp;&nbsp;から</p>
						<p><input type="date" id="datepicker" name="date_to" value="{{ $search['date_to'] }}">&nbsp;&nbsp;まで</p>
						@if (!empty($search['amount_from']))
						<input type="hidden" name="amount_from" value="{{ $search['amount_from']}}">
						@endif
						@if (!empty($search['amount_to']))
						<input type="hidden" name="amount_to" value="{{ $search['amount_to']}}">
						@endif
						@if (!empty($search['status_id']))
						<input type="hidden" name="status_id" value="{{ $search['status_id']}}">
						@endif
						@if (!empty($search['username']))
						<input type="hidden" name="username" value="{{ $search['username']}}">
						@endif
						<input style="float:right" type="submit" value="検索">
					</form>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="card">
		<div class="card-body">
			<div class="panel panel-default">
				<div class="panel-heading">ステータスから探す</div>
				<ul class="nav nav-pills nav-stacked" style="display:block;padding:15px">
					<li>
					<form action="{{ route('admin.order.index') }}" method="POST">
						{{ csrf_field() }}
						<select style="font-size:16px" name="status_id">
						@foreach (config('dropmenu.status') as $index => $status)
							<option value="{{ $index }}" @if ($index == $search['status_id']) selected @endif>{{ $status }}</option>
						@endforeach
						@if (!empty($search['date_from']))
						<input type="hidden" id="datepicker" name="date_from" value="{{ $search['date_from'] }}">
						@endif
						@if (!empty($search['date_to']))
						<input type="hidden" id="datepicker" name="date_to" value="{{ $search['date_to'] }}">
						@endif
						@if (!empty($search['amount_from']))
						<input type="hidden" name="amount_from" value="{{ $search['amount_from']}}">
						@endif
						@if (!empty($search['amount_to']))
						<input type="hidden" name="amount_to" value="{{ $search['amount_to']}}">
						@endif
						@if (!empty($search['status_id']))
						<input type="hidden" name="status_id" value="{{ $search['status_id']}}">
						@endif
						@if (!empty($search['username']))
						<input type="hidden" name="username" value="{{ $search['username']}}">
						@endif
						</select>
						<input style="float:right" type="submit" value="検索">
					</form>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="card">
		<div class="card-body">
			<div class="panel panel-default">
				<div class="panel-heading">金額から探す</div>
				<ul class="nav nav-pills nav-stacked" style="display:block;padding:15px">
					<li>
					<form action="{{ route('admin.order.index') }}" method="POST">
						{{ csrf_field() }}
						<p><select style="font-size:16px" name="amount_from">
						@foreach (config('dropmenu.amount_from') as $index => $a_from)
							<option value="{{ $index }}" @if ($index == $search['amount_from']) selected @endif>{{ $a_from }}</option>
						@endforeach
						</select>から</p>
						<select style="font-size:16px" name="amount_to">
						@foreach (config('dropmenu.amount_to') as $index => $a_to)
							<option value="{{ $index }}" @if ($index == $search['amount_to']) selected @endif>{{ $a_to }}</option>
						@endforeach
						@if (!empty($search['status_id']))
						<input type="hidden" name="status_id" value="{{ $search['status_id']}}">
						@endif
						@if (!empty($search['date_from']))
						<input type="hidden" id="datepicker" name="date_from" value="{{ $search['date_from'] }}">
						@endif
						@if (!empty($search['date_to']))
						<input type="hidden" id="datepicker" name="date_to" value="{{ $search['date_to'] }}">
						@endif
						@if (!empty($search['username']))
						<input type="hidden" name="username" value="{{ $search['username']}}">
						@endif
						</select>まで
						<input style="float:right" type="submit" value="検索">
					</form>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="card">
		<div class="card-body">
			<div class="panel panel-default">
				<ul class="nav nav-pills nav-stacked" style="display:block;padding:15px">
					<li>
					<form action="{{ route('admin.order.index') }}" method="POST">
						{{ csrf_field() }}
						<p><input type="hidden" name="reset" value=""></p>
						<div style="text-align:center"><input type="submit" value="条件リセット"></div>
					</form>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>
