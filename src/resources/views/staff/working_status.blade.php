@extends('layouts.staff')
@section('css')
<link rel="stylesheet" href="{{ asset('css/status.css') }}"/>
@endsection

@section('content')
<div class="working-content">
    <form action="{{ route('working_status.update') }}" method="POST">
    @csrf
<!--出勤前-->
    @if($status === 'off_duty')
        <div class="status">
            <div class="working-status">勤務外
            </div><!--working-status-->

            <p>{{ now()->isoFormat('YYYY年MM月DD日 (ddd)') }}</p>

            <p>{{ now()->format('H:i') }}</p>

            <button class="on-duty" type="submit" name="action" value="working">出勤</button>
        </div><!--status-->
<!--出勤後-->
    @elseif($status === 'working')
        <div class="status">
            <div class="working-status">出勤中
            </div><!--working-status-->

            <p>{{ now()->isoFormat('YYYY年MM月DD日 (ddd)') }}</p>
            <p>{{ now()->format('H:i') }}</p>

            <button class="working-button" type="submit" name="action" value="finished">退勤</button>

            <button class="start-break-button" type="submit" name="action" value="start_break">休憩入</button>
        </div><!--status-->
<!--休憩中-->
    @elseif($status === 'on_break')
            <div class="working-status">休憩中
            </div><!--working-status-->

            <p>{{ now()->isoFormat('YYYY年MM月DD日 (ddd)') }}</p>
            <p>{{ now()->format('H:i') }}</p>
            <button class="end-break-button" type="submit" name="action" value="end_break">休憩戻</button>
        </div><!--status-->
<!--退勤後-->
    @elseif($status === 'finished')
        <div class="status">
            <div class="working-status">退勤済
            </div><!--working-status-->
            <p>{{ now()->isoFormat('YYYY年MM月DD日 (ddd)') }}</p>
            <p>{{ now()->format('H:i') }}</p>
            <p class="finished">お疲れさまでした。</p>
        </div><!--status-->
    @endif
    </form>
</div><!--working-content-->
@endsection