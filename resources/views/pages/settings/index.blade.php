@extends('layouts.app')

@section('styles')
    <style>
        .disabled {
            color: currentColor;
            cursor: not-allowed;
            opacity: 0.5;
            text-decoration: none;
        }
    </style>
@endsection

@section('scriptsFooter')

@endsection


@section('header')
    <div class="sh-breadcrumb">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{ route('index') }}">INTILISH v3.1</a>
            <span class="breadcrumb-item active">Главная</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <div class="sh-pagetitle">

        <div class="input-group">
            <!-- search block -->
        </div><!-- input-group -->

        <div class="sh-pagetitle-left">
            <div class="sh-pagetitle-icon"><i class="icon ion-ios-gear-outline"></i></div>
            <div class="sh-pagetitle-title">
                <span>Настройки</span>
                <h2>Управление</h2>
            </div><!-- sh-pagetitle-left-title -->
        </div><!-- sh-pagetitle-left -->

    </div><!-- sh-pagetitle -->
@endsection

@section('content')
        <div class="col-6 col-sm-4 col-md">
            <a href="{{ route('organizations') }}" class="shortcut-icon">
                <div>
                    <i class="icon ion-ios-albums"></i>
                    <span>Организации</span>
                </div>
            </a>
        </div><!-- col -->
        <div class="col-6 col-sm-4 col-md">
            <a href="{{ route('projects') }}" class="shortcut-icon">
                <div>
                    <i class="icon ion-ios-analytics"></i>
                    <span>Проекты</span>
                </div>
            </a>
        </div><!-- col -->
        <div class="col-6 col-sm-4 col-md mg-t-10 mg-sm-t-0">
            <a href="#" class="shortcut-icon disabled">
                <div>
                    <i class="icon ion-ios-bookmarks"></i>
                    <span>Шаблоны</span>
                </div>
            </a>
        </div><!-- col -->
        <div class="col-6 col-sm-4 col-md mg-t-10 mg-md-t-0">
            <a href="#" class="shortcut-icon disabled">
                <div>
                    <i class="icon ion-ios-chatboxes"></i>
                    <span>Чаты</span>
                </div>
            </a>
        </div><!-- col -->
        <div class="col-6 col-sm-4 col-md mg-t-10 mg-md-t-0">
            <a href="#" class="shortcut-icon disabled">
                <div>
                    <i class="icon ion-ios-download"></i>
                    <span>Загрузки</span>
                </div>
            </a>
        </div><!-- col -->

        <div class="col-6 col-sm-4 col-md mg-t-10 mg-md-t-0">
            <a href="#" class="shortcut-icon disabled">
                <div>
                    <i class="icon ion-stats-bars"></i>
                    <span>Статистика</span>
                </div>
            </a>
        </div><!-- col -->
@endsection