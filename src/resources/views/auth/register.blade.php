@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
<div class="content">
  <div class="content__heading">
    <h1>会員登録</h1>
  </div>
  <div class="register__content">
    <form class="form" action="/register" method="post">
      @csrf
      <div class="form__group">
          <div class="form__input">
            <input type="text" name="name" placeholder="名前" value="{{ old('name') }}" />
          </div>
          <div class="form__error__container">
            <div class="form__error">
              @error('name')
              {{ $message }}
              @enderror
            </div>
          </div>
      </div>
      <div class="form__group">
          <div class="form__input">
            <input type="email" name="email" placeholder="メールアドレス" value="{{ old('email') }}" />
          </div>
          <div class="form__error__container">
            <div class="form__error">
              @error('email')
              {{ $message }}
              @enderror
            </div>
          </div>
      </div>
      <div class="form__group">
          <div class="form__input">
            <input type="password" name="password" placeholder="パスワード" />
          </div>
          <div class="form__error__container">
            <div class="form__error">
              @error('password')
              {{ $message }}
              @enderror
            </div>
          </div>
      </div>
      <div class="form__group">
          <div class="form__input">
            <input type="password" name="password_confirmation" placeholder="確認用パスワード" />
          </div>
          <div class="form__error__container">
            <div class="form__error">
              @error('password_confirmation')
              {{ $message }}
              @enderror
            </div>
          </div>
      </div>
      <div class="form__button">
        <button class="form__button-submit" type="submit">会員登録</button>
      </div>
    </form>
    <div class="login__link">
      <p>アカウントをお持ちの方はこちらから</p>
      <a class="login__link__button" href="/login">ログイン</a>
    </div>
  </div>
</div>
@endsection