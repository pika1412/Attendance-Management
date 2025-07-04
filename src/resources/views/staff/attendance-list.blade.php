@extends('layouts.staff')
@section('css')
<link rel="stylesheet" href=" {{ asset('css/attendance-list.css') }}" />
@endsection

@section('content')
<div class="attendance-list-content">
    <div class="list-title">
        <h2>勤怠一覧</h2>
    </div><!--list-title-->
    <nav>
        <ul class="month-navigation">
            <li class="previous-month">
                <a href="{{ route('attendance.index',['month' => $prevMonth->format('Y-m')]) }}">前月</a>
            </li>
            <li class="current-month"><span>{{ $currentMonth->format('Y/m') }}</span>
            </li>
            <li class="next-month">
                <a href="{{ route('attendance.index',['month' => $nextMonth->format('Y-m')]) }}">翌月</a>
            </li>
        </ul>
    </nav>
    <div class="attendance-list">
        <table>
            <thead>
                <tr class="table-row">
                    <th>日付</th>
                    <th>出勤</th>
                    <th>退勤</th>
                    <th>休憩</th>
                    <th>合計</th>
                    <th>詳細</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendances as $attendance)
                    <tr class="table-row">
                        <td>{{\Carbon\Carbon::parse($attendance->work_date)->format('Y/m/d') }}</td>
                        <td>{{ \Carbon\Carbon::parse($attendance->start_time)->format('H:i') ?? '' }}</td>
                        <td>{{ \Carbon\Carbon::parse($attendance->end_time)->format('H:i') ?? '' }}</td>
                        <td>{{ $attendance->formatted_break_time ?? ''}}</td>
                        <td>{{ $attendance->total_time_formatted ?? '' }}</td>
                        <td><a href="{{ route('attendance.show', $attendance->id)}}">詳細</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div><!--attendance-list-content-->


@endsection