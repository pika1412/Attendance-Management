@extends('layouts.admin')
@section('css')
<link rel="stylesheet" href=" {{ asset('css/stamp-list.css') }}" />
@endsection

@section('content')
<div class="application-list-content">
    <div class="application-title">
        <h2>申請一覧</h2>
    </div>
    <div class="border">
        <ul class="border-list">
            <li class="{{ $page === 'pending' ? 'active' : ''}}"><a href="{{ route('admin.stamp_list',['page'=>'pending']) }}">承認待ち</a></li>
            <li class="{{ $page === 'approved' ? 'active' : ''}}"><a href="{{ route('admin.stamp_list',['page'=>'approved']) }}">承認済み</a></li>
        </ul>
    </div><!--border-->
    <div class="application-table">
        @if($page == 'pending' || $page == 'approved')
        <table>
            <tr class="table-row">
                <th>状態</th>
                <th>名前</th>
                <th>対象日時</th>
                <th>申請理由</th>
                <th>申請日時</th>
                <th>詳細</th>
            </tr>
            @foreach($applications as $app)
            <tr>
                <td>
                    @switch($app->status)
                        @case('pending')
                            承認待ち
                            @break
                        @case('approved')
                            承認済み
                            @break
                        @case('rejected')
                            否認済み
                            @break
                        @default
                            不明
                        @endswitch
                </td>
                <td>{{$app->user->name}}</td>
                <td>{{$app->attendance->work_date}}</td>
                <td>{{$app->attendance->memo}}</td>
                <td>{{$app->applied_at}}</td>
                <td> <a href="{{ route('admin.application_approval', ['attendance_correct_request' => $app->attendance_id]) }}">詳細</a></td>
            </tr>
            @endforeach
        </table>
        @endif
    </div><!--application-table-->
</div><!--application-list-content-->
@endsection
