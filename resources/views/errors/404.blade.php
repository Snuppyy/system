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

    <!-- Theme CSS -->
    <link rel="stylesheet" href="{{ asset('css/shamcey.css') }}">

</head>

<body class="bg-gray-900">

<div class="ht-100v d-flex align-items-center justify-content-center">
    <div class="wd-lg-70p wd-xl-50p tx-center pd-x-40">
        <h1 class="tx-100 tx-xs-140 tx-normal tx-gray-300 mg-b-0">404!</h1>
        <h5 class="tx-xs-24 tx-normal tx-primary mg-b-30 lh-5">Страница, которую вы ищете, не найдена.</h5>
        <p class="tx-16 mg-b-30">Возможно, страница, которую вы ищете, была удалена, ее имя было изменено или
            недоступно.</p>
    </div>
</div><!-- ht-100v -->
</body>
</html>
