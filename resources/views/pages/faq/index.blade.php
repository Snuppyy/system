@extends('layouts.app')

@section('header')
<div class="sh-breadcrumb">
    <nav class="breadcrumb">
        <a class="breadcrumb-item" href="{{ route('index') }}">INTILISH v3.1</a>
        <span class="breadcrumb-item">Инструменты</span>
        <span class="breadcrumb-item active">FAQ</span>
    </nav>
</div><!-- sh-breadcrumb -->

<div class="sh-pagetitle">

    <div class="input-group">
        <!-- search block -->
    </div><!-- input-group -->

    <div class="sh-pagetitle-left">
        <div class="sh-pagetitle-icon"><i class="icon icon ion-social-youtube"></i></div>
        <div class="sh-pagetitle-title">
            <h2>FAQ</h2>
        </div><!-- sh-pagetitle-left-title -->
    </div><!-- sh-pagetitle-left -->

</div><!-- sh-pagetitle -->
@endsection

@section('styles')
    <link href="https://unpkg.com/video.js/dist/video-js.min.css" rel="stylesheet">
    <script src="https://unpkg.com/video.js/dist/video.min.js"></script>
@endsection

@section('scriptsFooter')
<script>
    $(function () {
        'use strict';
        var options = {};

        var player = videojs('my-player', options, function onPlayerReady() {
            videojs.log('Your player is ready!');

            // In this context, `this` is the player that was created by Video.js.
            this.play();

            // How about an event listener?
            this.on('ended', function() {
                videojs.log('Awww...over so soon?!');
            });
        });
    })
</script>
@endsection

@section('content')
    <div class="col-4 mg-t-20">
        <h4>Создание поручений</h4>
        <video
                class="col-11"
                style="height: 300px !important; "
                controls
                poster="img/logo.png">
            <source src="storage/video/faq/1.mp4" type="video/mp4"></source>
        </video>
    </div>

    <div class="col-4 mg-t-20 ">
        <h4>Регистрация деятельности</h4>
        <video
                style="height: 300px !important; "
                class="col-11"
                controls
                poster="img/logo.png">
            <source src="storage/video/faq/2.mp4" type="video/mp4"></source>
        </video>
    </div>

    <div class="col-4 mg-t-20">
        <h4>Анализ деятельности</h4>
        <video
                class="col-11"
                style="height: 300px !important; "
                controls
                poster="img/logo.png">
            <source src="storage/video/faq/3.mp4" type="video/mp4"></source>
        </video>
    </div>

    <div class="col-4 mg-t-20">
        <h4>Установка Zoom Meetings</h4>
        <video
                class="col-11"
                style="height: 300px !important; "
                controls
                poster="img/logo.png">
            <source src="storage/video/faq/4.mp4" type="video/mp4"></source>
        </video>
    </div>

    <div class="col-4 mg-t-20">
        <h4>Настройка своего аккаунта в Zoom Meetings</h4>
        <video
                class="col-11"
                style="height: 300px !important; "
                controls
                poster="img/logo.png">
            <source src="storage/video/faq/5.mp4" type="video/mp4"></source>
        </video>
    </div>

    <div class="col-4 mg-t-20">
        <h4>Демонстрация экрана в Zoom Meetings</h4>
        <video
                class="col-11"
                style="height: 300px !important; "
                controls
                poster="img/logo.png">
            <source src="storage/video/faq/6.mp4" type="video/mp4"></source>
        </video>
    </div>

    <div class="col-4 mg-t-20">
        <h4>Работа с Google Диском</h4>
        <video
                class="col-11"
                style="height: 300px !important; "
                controls
                poster="img/logo.png">
            <source src="storage/video/faq/7.mp4" type="video/mp4"></source>
        </video>
    </div>
@endsection
