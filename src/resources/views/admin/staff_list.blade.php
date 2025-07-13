@extends('layouts.admin')
@section('css')
<link rel="stylesheet" href="{{ asset('css/staff_list.css') }}"/>
@endsection
<!--勤怠一覧-->
@section('content')
<div class="attendance-list-content">
    <div class="list-title">
        <h2>スタッフ一覧</h2>
    </div><!--list-title-->
    <div class="attendance-list">
        <table>
                <tr class="table-row">
                    <th>名前</th>
                    <th>メールアドレス</th>
                    <th>詳細</th>
                </tr>
                    @foreach($users as $user)
                    <tr class="table-row">
                        <td>{{ $user->name}}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <a href="{{ route('admin.staff_attendance_list', ['id' => $user->id]) }}">詳細</a>
                        </td>
                    </tr>
                    @endforeach
            </tbody>
        </table>
    </div>

</div><!--attendance-list-content-->

@endsection