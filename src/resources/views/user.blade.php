@extends('layouts/app')


@section('css')
<link rel="stylesheet" href="{{ asset('css/user.css')}}">
@endsection

@section('content')
<div class="content">
    <div class="content__heading">
        <h1>ユーザー一覧</h1>
    </div>
    <div class="user__content">
        <table class="user__table">
            <tr class="user__row">
                <th class="user__label">ID</th>
                <th class="user__label">名前</th>
                <th class="user__label">メールアドレス</th>

            </tr>
            @foreach($users as $user)
            <tr class="user__row">
                <td class="user__data">{{$user->id}}</td>
                <td class="user__data">{{$user->name}}</td>
                <td class="user__data">{{$user->email}}</td>
                <td class="user__data">
                    <form class="user-attendance-search__form" action="/user_search" method="get">
                    @csrf
                        <input class="user-attendance-search__input" type="hidden" name="id" value="{{$user->id}}">
                        <input class="user-attendance-search__btn" type="submit" value="勤怠表">
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
    {{ $users->links('vendor.pagination.custom') }}
</div>
@endsection