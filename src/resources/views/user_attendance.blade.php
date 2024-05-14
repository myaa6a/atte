@extends('layouts/app')


@section('css')
<link rel="stylesheet" href="{{ asset('css/user-attendance.css')}}">
@endsection

@section('content')
<div class="content">
    <div class="content__heading">
        <h1>{{ $user->name }}</h1>
    </div>
    <div class="attendance__content">
        <table class="attendance__table">
            <tr class="attendance__row">
                <th class="attendance__label">勤務日</th>
                <th class="attendance__label">勤務開始</th>
                <th class="attendance__label">勤務終了</th>
                <th class="attendance__label">休憩時間</th>
                <th class="attendance__label">勤務時間</th>
            </tr>
            @foreach($stamps as $stamp)
            <tr class="attendance__row">
                <td class="attendance__data">{{$stamp->stamp_date}}</td>
                <td class="attendance__data">{{$stamp->work_start_at}}</td>
                <td class="attendance__data">{{$stamp->work_end_at}}</td>
                @if(!empty($stamp->rest->rest_time))
                <td class="attendance__data">{{$stamp->rest->rest_time}}</td>
                @else
                <td class="attendance__data">{{'00:00:00'}}</td>
                @endif
                <td class="attendance__data">{{$stamp->work_time}}</td>
            </tr>
            @endforeach
        </table>
    </div>
    {{ $stamps->appends(['id' => $search])->links('vendor.pagination.custom') }}
</div>
@endsection