@extends('layouts.staff')
@section('css')
<link rel="stylesheet" href=" {{ asset('css/stamp.css') }}" />
@endsection
@section('content')
<div class="stamp-content">
    <div class="stamp-title">
        <h2>勤怠詳細</h2>
    </div>
    <div class="stamp-table">
            <table>
                <tr class="name-row">
                    <th>名前</th>
                    <td>{{$attendance->user->name}}</td>
                </tr>
                <tr class="date-row">
                    <th>日付</th>
                    <td><span>{{\Carbon\Carbon::parse($attendance->work_date)->format('Y年') }}<span></span>{{ \Carbon\Carbon::parse($attendance->work_date)->format('n月j日')}}</td></span>
                </tr>
                <tr class="table-row">
                    <th>出勤・退勤</th>
                    <td><input type="text" name ="start_time" value="{{ $attendance?->start_time ? \Carbon\Carbon::parse($attendance->start_time)->format('H:i') :null}}">  ~  <input type="text" name ="end_time" value =" {{$attendance?->end_time ? \Carbon\Carbon::parse($attendance->end_time)->format('H:i') :null}}"></td>
                </tr>
                @foreach($attendance->breakTimes as $index => $break)
                <tr class="table-row">
                    <th>休憩{{ $index + 1 }}</th>
                    <td><input type="text" name="start_break" value="{{ $break->start_break ? \Carbon\Carbon::parse($break->start_break)->format('H:i') : '' }}">  ~  <input type="text" name="end_break" value="{{ $break->end_break ? \Carbon\Carbon::parse($break->end_break)->format('H:i') : '' }}"></td>
                </tr>
                @endforeach
                </tr>
                <tr class="table-row">
                    <th>備考</th>
                    <td><textarea name="memo" id="memo">{{$attendance->memo}}</textarea></td>
                </tr>
            </table>
            </div><!--detail-table-->
            <p class="warning">*承認待ちのため修正できません。</p>
</div><!--detail-content-->
@endsection