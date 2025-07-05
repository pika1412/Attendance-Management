@extends('layouts.staff')
@section('css')
<link rel="stylesheet" href=" {{ asset('css/attendance-detail.css') }}" />
@endsection
@section('content')
<div class="detail-content">
    <div class="detail-title">
        <h2>勤怠詳細</h2>
    </div>
    <div class="detail-table">
        <form action="{{ route('attendance.detail',['id' => $attendance->id]) }}">
            <table>
                <tr class="table-row">
                    <th>名前</th>
                    <td>{{$attendance->user->name}}</td>
                </tr>
                <tr class="table-row">
                    <th>日付</th>
                    <td>{{\Carbon\Carbon::parse($attendance->work_date)->format('Y年') }}&nbsp;{{ \Carbon\Carbon::parse($attendance->work_date)->format('n月j日')}}</td>
                </tr>
                <tr class="table-row">
                    <th>出勤・退勤</th>
                    <td><input type="text" value="{{ $attendance?->start_time ? \Carbon\Carbon::parse($attendance->start_time)->format('H:i') :null}}">  ~  <input type="text" value="{{$attendance?->end_time ? \Carbon\Carbon::parse($attendance->end_time)->format('H:i') :null}}"></td>
                </tr>
                @foreach($attendance->breakTimes as $index => $break)
                <tr class="table-row">
                    <th>休憩{{ $index + 1 }}</th>
                    <td><input type="text" value="{{ $break->start_break ? \Carbon\Carbon::parse($break->start_break)->format('H:i') : '' }}">  ~  <input type="text" value="{{ $break->end_break ? \Carbon\Carbon::parse($break->end_break)->format('H:i') : '' }}"></td>
                </tr>
                @endforeach
                </tr>
                <tr class="table-row">
                    <th>備考</th>
                    <td><textarea name="memo" id="memo"></textarea></td>
                </tr>
            </table>
            <div class="application-button">
                <button class="application-button-submit" type="submit">修正</button>
            </div>
        </form>
    </div><!--detail-table-->

</div><!--detail-content-->
@endsection