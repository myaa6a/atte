@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/stamp.css') }}">
@endsection

@section('scripts-csrf')
<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="content">
    <div class="content__heading">
        <h1>{{ Auth::user()->name }}さんお疲れ様です！</h1>
    </div>

    @if(session('message'))
    <div class="stamp__message">
        {{ session('message') }}
    </div>
    @endif

    <div class="stamp__content">
        <div class="form__group--top">
            <form class="form" action="/timein" method="post">
            @csrf
                @if(empty($old_stamp->work_start_at))
                <button class="form__button-submit" type="submit">勤務開始</button>
                @elseif(!empty($old_stamp->work_end_at))
                <button class="form__button-submit" type="submit">勤務開始</button>
                @else
                <button disabled class="form__button-submit--disabled" type="submit">勤務開始</button>
                @endif
            </form>
            <form class="form" action="/timeout" method="post">
            @csrf
                @if(!empty($old_stamp->work_start_at) && empty($old_stamp->work_end_at) && empty($old_stamp->rest->rest_time))
                <button class="form__button-submit" type="submit">勤務終了</button>
                @elseif(!empty($old_stamp->work_start_at) && empty($old_stamp->work_end_at) && (($old_stamp->rest->rest_start_at) < ($old_stamp->rest->rest_end_at)))
                <button class="form__button-submit" type="submit">勤務終了</button>
                @else
                <button disabled class="form__button-submit--disabled" type="submit">勤務終了</button>
                @endif
            </form>
        </div>
        <div class="form__group--bottom">
            <form class="form" action="/restin" method="post">
            @csrf
                @if(!empty($old_stamp->work_start_at) && empty($old_stamp->work_end_at) && empty($old_stamp->rest->rest_start_at))
                <button class="form__button-submit" type="submit">休憩開始</button>
                @elseif(!empty($old_stamp->work_start_at) && empty($old_stamp->work_end_at) && (($old_stamp->rest->rest_start_at) < ($old_stamp->rest->rest_end_at)))
                <button class="form__button-submit" type="submit">休憩開始</button>
                @else
                <button disabled class="form__button-submit--disabled" type="submit">休憩開始</button>
                @endif
            </form>
            <form class="form" action="/restout" method="post">
            @csrf
                @if(!empty($old_stamp->work_start_at) && empty($old_stamp->work_end_at) && !empty($old_stamp->rest->rest_start_at) && empty($old_stamp->rest->rest_end_at))
                <button class="form__button-submit" type="submit">休憩終了</button>
                @elseif(!empty($old_stamp->work_start_at) && empty($old_stamp->work_end_at) && !empty($old_stamp->rest->rest_start_at) && !empty($old_stamp->rest->rest_end_at) && (($old_stamp->rest->rest_start_at) > ($old_stamp->rest->rest_end_at)))
                <button class="form__button-submit" type="submit">休憩終了</button>
                @else
                <button disabled class="form__button-submit--disabled" type="submit">休憩終了</button>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function() {
    $.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': $("[name='csrf-token']").attr("content") },
    })

    // 現在の時刻を取得する関数
    function getCurrentTime() {
        var now = new Date();
        return now.getHours() + ":" + now.getMinutes() + ":" + now.getSeconds();
    }

    // 1秒ごとに時刻を取得して表示
    setInterval(function() {
        var currentTime = getCurrentTime();
        console.log(currentTime); // コンソールに時刻を表示（デバッグ用）
    }, 1000);

    // Ajaxリクエストを使用してPHPに時刻を送信
    function sendTimeToPHP(currentTime) {
        $.ajax({
            type: "POST",
            url: "/switch", // PHPスクリプトのパス
            data: { time: currentTime }, // 送信するデータ（ここでは時刻）
            // 送信するデータ
            dataType: 'json', // 応答データの型
            success: function(response) {
                console.log('データが更新されました:', response);
            }, // 成功時の処理
            error: function(xhr, status, error) {
                console.error('データの更新に失敗しました:', error);
            } // エラー時の処理
        });
    }

    // 1秒ごとに時刻を取得してPHPに送信
    setInterval(function() {
        var currentTime = getCurrentTime();
        sendTimeToPHP(currentTime);
    }, 1000);
});
</script>
@endsection