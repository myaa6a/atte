@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify-email.css')}}">
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('メールアドレスの確認') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    {{ __('続行する前に、電子メールで確認リンクを確認してください。') }}
                    <form class="form" method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="btn">{{ __('メールが届かない場合は、ここをクリックしてください') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection