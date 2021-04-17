<div class="col-md-3">
	<div class="card">
		<div class="card-body">
			<div class="panel panel-default">
				<div class="panel-heading">名前で探す</div>
				<ul class="nav nav-pills nav-stacked" style="display:block;padding:15px">
					<li>
					<form action="{{ route('admin.order.index') }}" method="get">
						{{ csrf_field() }}
						<input type="text" name="username" value="{{ !empty($search['username']) ? $search['username'] : "" }}">
						<input type="hidden" id="datepicker" name="date_from" value="{{ !empty($search['date_from']) ? $search['date_from'] : null }}">
						<input type="hidden" id="datepicker" name="date_to" value="{{ !empty($search['date_to']) ? $search['date_to'] : null }}">
						<input type="hidden" name="amount_from" value="{{ !empty($search['amount_from']) ? $search['amount_from'] : null }}">
						<input type="hidden" name="amount_to" value="{{ !empty($search['amount_to']) ? $search['amount_to'] : null }}">
						<input type="hidden" name="status_id" value="{{ !empty($search['status_id']) ? $search['status_id'] : null }}">
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
					<form action="{{ route('admin.order.index') }}" method="get">
						{{ csrf_field() }}
						<p><input type="date" id="datepicker" name="date_from" value="{{ $search['date_from'] }}">&nbsp;&nbsp;から</p>
						<p><input type="date" id="datepicker" name="date_to" value="{{ $search['date_to'] }}">&nbsp;&nbsp;まで</p>
						<input type="hidden" name="amount_from" value="{{ !empty($search['amount_from']) ? $search['amount_from'] : null }}">
						<input type="hidden" name="amount_to" value="{{ !empty($search['amount_to']) ? $search['amount_to'] : null }}">
						<input type="hidden" name="status_id" value="{{ !empty($search['status_id']) ? $search['status_id'] : null }}">
						<input type="hidden" name="username" value="{{ !empty($search['username']) ? $search['username'] : null }}">
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
					<form action="{{ route('admin.order.index') }}" method="get">
						{{ csrf_field() }}
						<select style="font-size:16px" name="status_id">
						@foreach (config('dropmenu.status') as $index => $status)
							<option value="{{ $index }}" @if ($index == $search['status_id']) selected @endif>{{ $status }}</option>
						@endforeach
						</select>
						<input type="hidden" id="datepicker" name="date_from" value="{{ !empty($search['date_from']) ? $search['date_from'] : null }}">
						<input type="hidden" id="datepicker" name="date_to" value="{{ !empty($search['date_to']) ? $search['date_to'] : null }}">
						<input type="hidden" name="amount_from" value="{{ !empty($search['amount_from']) ? $search['amount_from'] : null }}">
						<input type="hidden" name="amount_to" value="{{ !empty($search['amount_to']) ? $search['amount_to'] : null }}">
						<input type="hidden" name="username" value="{{ !empty($search['username']) ? $search['username'] : null }}">
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
					<form action="{{ route('admin.order.index') }}" method="get">
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
						</select>まで
						<input type="hidden" id="datepicker" name="date_from" value="{{ !empty($search['date_from']) ? $search['date_from'] : null }}">
						<input type="hidden" id="datepicker" name="date_to" value="{{ !empty($search['date_to']) ? $search['date_to'] : null }}">
						<input type="hidden" name="username" value="{{ !empty($search['username']) ? $search['username'] : null }}">
						<input type="hidden" name="status_id" value="{{ !empty($search['status_id']) ? $search['status_id'] : null }}">
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
