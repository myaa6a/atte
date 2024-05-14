@extends('layouts/app')


@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance.css')}}">
@endsection

@section('content')
<div class="content">
    <div class="content__heading">
        <form class="day-search__form" action="/day_search" method="get">
        @csrf
            <input class="day-search__input" type="hidden" name="prev_stamp_day" value="{{$stamp_day}}">
            <input class="day-search__prev-btn" type="submit" value="<">
        </form>
        <h1>{{ $stamp_day->format('Y-m-d') }}</h1>
        <form class="day-search__form" action="/day_search" method="get">
        @csrf
            <input class="search-form__input" type="hidden" name="next_stamp_day" value="{{$stamp_day}}">
            <input class="day-search__next-btn" type="submit" value=">">
        </form>
    </div>
    <div class="attendance__content">
        <table class="attendance__table">
            <tr class="attendance__row">
                <th class="attendance__label">名前</th>
                <th class="attendance__label">勤務開始</th>
                <th class="attendance__label">勤務終了</th>
                <th class="attendance__label">休憩時間</th>
                <th class="attendance__label">勤務時間</th>
            </tr>
            @foreach($stamps as $stamp)
            <tr class="attendance__row">
                <td class="attendance__data">{{$stamp->user->name}}</td>
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
    @if(($search ?? '') == (request()->input('prev_stamp_day')))
    {{ $stamps->appends(['prev_stamp_day' => $search ?? '' ?? ''])->links('vendor.pagination.custom') }}
    @elseif(($search ?? '') == (request()->input('next_stamp_day')))
    {{ $stamps->appends(['next_stamp_day' => $search ?? '' ?? ''])->links('vendor.pagination.custom') }}
    @else
    {{ $stamps->links('vendor.pagination.custom') }}
    @endif
</div>
@endsection