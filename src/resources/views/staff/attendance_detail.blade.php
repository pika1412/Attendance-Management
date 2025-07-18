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
        <form action="{{ route('attendance.updateDetail',['id' => $attendance->id]) }}" method="POST">
            @csrf
            @method('PATCH')
            <table>
                <tr class="name-row">
                    <th>名前</th>
                    <td>{{$attendance->user->name}}</td>
                </tr>
                <tr class="date-row">
                    <th>日付</th>
                    <td><span>{{\Carbon\Carbon::parse($attendance->work_date)->format('Y年') }}<span></span>{{ \Carbon\Carbon::parse($attendance->work_date)->format('n月j日')}}</span></td>
                </tr>
                <tr class="table-row">
                    <th>出勤・退勤</th>
                    <td><input type="text" name="start_time" value="{{ $attendance?->start_time ? \Carbon\Carbon::parse($attendance->start_time)->format('H:i') :null}}">  ~  <input type="text" name="end_time" value="{{$attendance?->end_time ? \Carbon\Carbon::parse($attendance->end_time)->format('H:i') :null}}">
                    @if($errors->first('start_time') === '出勤時間もしくは退勤時間が不適切な値です' || $errors->first('end_time') === '出勤時間もしくは退勤時間が不適切な値です')
                    <div class="error">出勤時間もしくは退勤時間が不適切な値です</div>
                    @endif
                    </td>
                </tr>
                @foreach($attendance->breakTimes as $index => $break)
                <tr class="table-row">
                    <th>休憩{{ $index + 1 }}</th>
                    <td><input type="text" name="start_break" value="{{ $break->start_break ? \Carbon\Carbon::parse($break->start_break)->format('H:i') : '' }}">  ~  <input type="text" name="end_break" value="{{ $break->end_break ? \Carbon\Carbon::parse($break->end_break)->format('H:i') : '' }}">
                    @if($errors->first('start_break') === '休憩時間が勤務時間外です' || $errors->first('end_break') === '休憩時間が勤務時間外です')
                    <div class="error">休憩時間が勤務時間外です</div>
                    @endif
                    </td>
                </tr>
                @endforeach
                </tr>
                <tr class="table-row">
                    <th>備考</th>
                    <td><textarea name="memo" id="memo">{{ old('memo', $attendance->memo) }}</textarea>
                    @error('memo')
                    <div class="error">{{$message}}</div>
                    @enderror
                    </td>
                </tr>
            </table>
            </div><!--detail-table-->
            @if($status === null || $status === 'rejected')
                <div class="button-container">
                <button class="button-submit" type="submit">修正
                </button>
                </div>
            @elseif($status === 'pending')
                <p class="message">※承認待ちの為修正はできません</p>
            @endif
        </form>
</div><!--detail-content-->
@endsection