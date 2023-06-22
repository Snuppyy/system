<?php
    Debugbar::disable();
?>
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Meta -->
    <meta name="description" content="Электронная информационная система организации ННО РИОЦ 'INTILISH'">
    <meta name="author" content="Dee^xD">


    <title>{{ config('app.name') }}</title>

    <!-- Vendor css -->
    <link href="{{ asset('lib/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/Ionicons/css/ionicons.css') }}" rel="stylesheet">

    <!-- Theme CSS -->
    <link rel="stylesheet" href="{{ asset('css/shamcey.css') }}">

</head>

<body class="bg-gray-900">

<div class="signpanel-wrapper">
    <div class="signbox">
        <form method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}

            <div class="signbox-header">
                <h2>INTILISH EIS v3.1</h2>
                <p class="mg-b-0">Электронная информационная система организации<br>ННО РИОЦ "INTILISH"</p>

            </div><!-- signbox-header -->

            <div class="signbox-body">
                <div class="form-group">
                    <label class="form-control-label is-invalid" for="email">Адрес электронной почты:</label>
                    <input id="email" type="email" name="email" placeholder="Введите адрес электронной почты" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" required>
                    @if ($errors->has('email'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif

                </div><!-- form-group -->

                <div class="form-group">
                    <label class="form-control-label" for="password">Пароль:</label>
                    <input id="password" type="password" name="password" placeholder="Укажите пароль" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" required>
                    @if ($errors->has('password'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif

                </div><!-- form-group -->

                <button class="btn btn-success btn-block">Войти</button>

            </div><!-- signbox-body -->

        </form>
    </div><!-- signbox -->

</div><!-- signpanel-wrapper -->

<script src="{{ asset('lib/jquery/jquery.js') }}"></script>
<script src="{{ asset('lib/popper.js/popper.js') }}"></script>
<script src="{{ asset('lib/bootstrap/bootstrap.js') }}"></script>
<script src="{{ asset('lib/perfect-scrollbar/js/perfect-scrollbar.jquery.js') }}"></script>

<!-- Custom Scripts -->
<script src="{{ asset('js/shamcey.js') }}"></script>

</body>
</html>
