@extends('layouts.app')

@section('styles')
    <link href="{{ asset('lib/jquery.switcher/switcher.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/select2/dist/css/select2.css') }}" rel="stylesheet">
    <style>
        .switcher.default {
            float: right;
        }
    </style>
@endsection

@section('scripts')
    <script src="{{ asset('lib/jquery.switcher/switcher.js') }}"></script>
    <script src="{{ asset('lib/select2/dist/js/select2.full.js') }}"></script>
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

            $('.select2').select2({
                placeholder: "Выберите клиента",
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
    <form id="form" autocomplete="off" action="{{ route('BussDurkeeSave') }}" method="post" class="form-layout col-lg-12">
        {{ csrf_field() }}
        <div class="row mg-b-25 justify-content-lg-center">
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="form-control-label">
                        Респондент:
                        <span class="tx-danger">*</span>
                    </label>
                    <select class="form-control select2" name="client" required>
                        <option></option>
                        @foreach($clients as $prison => $client)
                            <optgroup label="{{ $prison }}">
                                @foreach($client as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-2">
                <div class="form-group">
                    <label class="form-control-label">
                        Тип:
                        <span class="tx-danger">*</span>
                    </label>
                    <select class="form-control select2" name="type" required>
                        <option value="1">ПРЕ-ТЕСТ</option>
                        <option value="2">ПОСТ-ТЕСТ</option>
                    </select>
                </div>
            </div><!-- col-12 -->
            @foreach($questions as $question)
                <div class="col-lg-8">
                    <div class="form-group">
                        <label class="form-control-label">
                            {{ $question->id }} - {{ $question->question }}:
                        </label>
                        <input type="checkbox" name="answers[{{ $question->id }}]" value="1" class="switcher">
                    </div>
                </div><!-- col-12 -->
            @endforeach
            <div class="col-lg-8">
                <button class="btn btn-block btn-outline-success">Расчитать</button>
            </div>
        </div>
    </form>
@endsection
