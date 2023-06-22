@extends('layouts.app')

@section('styles')
    <link href="{{ asset('lib/jquery.switcher/switcher.min.css') }}" rel="stylesheet">
    <style>
        .switcher.default {
            float: right;
        }
    </style>
@endsection

@section('scripts')
    <script src="{{ asset('lib/jquery.switcher/switcher.js') }}"></script>
@endsection

@section('scriptsFooter')
    <script>
        $(function () {
            $('input.switcher').switcher({
                style: "default",
                selected: false,
                language: "ru",
                disabled: false
            });
        });
    </script>
@endsection

@section('header')
    <div class="sh-breadcrumb">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{ route('index') }}">INTILISH v3.1</a>
            <span class="breadcrumb-item">Психология</span>
            <span class="breadcrumb-item">Опросники</span>
            <span class="breadcrumb-item active">Опросник Басса-Дарки</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <div class="sh-pagetitle">

        <div class="input-group">
            <!-- search block -->
        </div><!-- input-group -->

        <div class="sh-pagetitle-left">
            <div class="sh-pagetitle-icon"><i class="icon ion-help"></i></div>
            <div class="sh-pagetitle-title">
                <h2>Опросник Басса-Дарки</h2>
            </div><!-- sh-pagetitle-left-title -->
        </div><!-- sh-pagetitle-left -->

    </div><!-- sh-pagetitle -->
@endsection

@section('content')
    <div class="col-12">
        <p class="h1">Имя респондента: {{ $data['name'] }}</p>
        <p class="h3">Вывод по опроснику:</p>
        <ul>
            @foreach($data['result'] as $key => $value)
                @if($value === true)
                    <li>
                        <p class="h4">
                            {{ $key }}
                        </p>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
@endsection
