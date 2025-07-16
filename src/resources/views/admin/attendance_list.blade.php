@extends('layouts.admin')
@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance-list.css') }}"/>
@endsection
<!--勤怠一覧-->
@section('content')
<div class="attendance-list-content">
    <div class="list-title">
        <h2>{{now()->isoFormat('YYYY年MM月DD日') }}の勤怠</h2>
    </div><!--list-title-->
    <nav>
        <ul class="day-navigation">
            <li class="previous-day">
                <a href="{{ route('admin.attendance_list',['day' => $yesterday->format('Y-m-d')]) }}">前日</a>
            </li>
            <li class="today"><span>{{ $today->format('Y/m/d') }}</span>
            </li>
            <li class="next-day">
                <a href="{{ route('admin.attendance_list',['day' => $tomorrow->format('Y-m-d')]) }}">翌日</a>
            </li>
        </ul>
    </nav>
    <div class="attendance-list-table">
        <table>
                <tr>
                    <th>名前</th>
                    <th>出勤</th>
                    <th>退勤</th>
                    <th>休憩</th>
                    <th>合計</th>
                    <th>詳細</th>
                </tr>
                    @foreach($todayAttendances as $attendance)
                    <tr>
                        <td>{{ optional($attendance?->user)->name ?? ''}}</td>
                        <td>{{ $attendance?->start_time ? \Carbon\Carbon::parse($attendance->start_time)->format('H:i') :null }}</td>
                        <td>{{ $attendance?->end_time ? \Carbon\Carbon::parse($attendance->end_time)->format('H:i') : null }}</td>
                        <td>{{ $attendance->formatted_break_time ?? ''}}</td>
                        <td>{{ $attendance->total_time_formatted ?? '' }}</td>
                        <td>
                        @if ($attendance)
                            <a href="{{ route('admin.attendance_detail', ['id' => $attendance->id]) }}">詳細</a>
                        @else
                    @endif</td>
                    </tr>
                    @endforeach
            </tbody>
        </table>
    </div>

</div><!--attendance-list-content-->

@endsection