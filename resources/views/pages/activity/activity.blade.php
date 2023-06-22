@extends('layouts.app')

@section('styles')
    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/jquery.switcher/switcher.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/uploader/dist/css/jquery.dm-uploader.min.css') }}" rel="stylesheet">

    <style>
        .sel2 .parsley-errors-list.filled {
            margin-top: 42px;
            margin-bottom: -60px;
        }

        .sel2 .parsley-errors-list:not(.filled) {
            display: none;
        }

        .sel2 .parsley-errors-list.filled + span.select2 {
            margin-bottom: 30px;
        }

        .sel2 .parsley-errors-list.filled + span.select2 span.select2-selection--single {
            background: #FAEDEC !important;
            border: 1px solid #E85445;
        }

        #files {
            overflow-y: scroll !important;
            min-height: 320px;
        }

        @media (min-width: 768px) {
            #files {
                min-height: 0;
            }
        }

        .dm-uploader {
            border: 0.25rem dashed #A5A5C7;
            text-align: center;
        }

        .dm-uploader.active {
            border-color: red;
            border-style: solid;
        }
    </style>

@endsection

@section('scripts')
    <script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('lib/datetimepicker/jquery.datetimepicker.full.js') }}"></script>
    <script src="{{ asset('lib/mask/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('lib/parsleyjs/parsley.js') }}"></script>
    <script src="{{ asset('lib/jquery.switcher/switcher.js') }}"></script>
    <script src="{{ asset('lib/uploader/dist/js/jquery.dm-uploader.min.js') }}"></script>
    <script src="{{ asset('lib/uploader/dist/js/functions.js') }}"></script>

@endsection

@section('scriptsFooter')
    <script>
        $(function () {
            'use strict';

            $('#date').datetimepicker({
                format: 'Y-m-d',
                step: 1,
                mask: '____-__-__',
                timepicker: false
            });

            $('.select2').select2();

            $('#nextStepActivity').click(function () {
                var href = '{{ route('activityRedirectUser', ['id' => ':id', 'date' => ':date', 'position' => ':position']) }}';
                if ($('#user').val() && ($('#date').val() !== '____-__-__') || {{ auth()->user()->role }} != 1 ) {
                    href = href.replace(':id', $('#user').val() ? $('#user').val() : '{{ auth()->user()->id }}');
                    href = href.replace(':date', $('#date').val());
                    href = href.replace(':position', $('#user option:selected').data('position') ? $('#user option:selected').data('position') : '{{ $userThis->position }}');
                    window.location.href = href;
                }
            });
        })
    </script>
@endsection

@section('header')
    <div class="sh-breadcrumb">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{ route('index') }}">INTILISH v3.1</a>
            <span class="breadcrumb-item">Деятельность</span>
            <a class="breadcrumb-item" href="{{ route('activity') }}">Выбор проекта</a>
            <span class="breadcrumb-item active">Выбор сотрудника</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <div class="sh-pagetitle">

        <div class="input-group">
            <!-- search block -->
        </div><!-- input-group -->

        <div class="sh-pagetitle-left">
            <div class="sh-pagetitle-icon"><i class="far ion-ios-box-outline"></i></div>
            <div class="sh-pagetitle-title">
                <h2>Регистрация деятельности</h2>
            </div><!-- sh-pagetitle-left-title -->
        </div><!-- sh-pagetitle-left -->

    </div><!-- sh-pagetitle -->
@endsection

@section('content')
    <div class="row col-12 justify-content-md-center">
        @if(Auth::user()->role <= 2)
            <div class="col-lg-6">
                <div class="form-group mg-b-10-force sel2">
                    <label for="user" class="form-control-label">Сотрудник: <span
                                class="tx-danger">*</span></label>
                    <select id="user" class="form-control select2 sel2 wd-100p-force"
                            data-placeholder="Выберите сотрудника"
                            required
                            name="user">
                        <option label="Выберите сотрудника"></option>
                        @foreach($users as $keys => $values)
                            @foreach($users[$keys] as $key => $value)
                                <optgroup label="{{ $key }}">
                                    @foreach($users[$keys][$key] as $k => $v)
                                        <option value="{{ $k }}" data-position="{{ $v['position'] }}">{{ $v['name'] }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        @endforeach
                    </select>
                </div>
            </div><!-- col-4 -->
        @endif

        <div class="{{ Auth::user()->role !== 1 ? 'col-offset-3' : '' }} col-3">
            <div class="form-group mg-b-10-force">
                <label for="date" class="form-control-label">Дата: <span
                            class="tx-danger">*</span></label>
                <input class="form-control" id="date" type="text" lang="ru" name="date"
                       data-parsley-pattern="^[0-9]{4}[-]{1}[0-9]{2}[-]{1}[0-9]{2}$"
                       data-parsley-trigger="change" required>
            </div>
        </div><!-- col-4 -->

        <div class="form-layout-footer col-lg-12">
            <button id="nextStepActivity" class="btn btn-success btn-block">Далее</button>
        </div><!-- form-layout-footer -->
    </div>
@endsection
