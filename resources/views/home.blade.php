@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $user->name }} さん</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
<p><a href="{{ route('admin.item.index')}}">管理者商品一覧へ</p>
                    You are logged in!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
