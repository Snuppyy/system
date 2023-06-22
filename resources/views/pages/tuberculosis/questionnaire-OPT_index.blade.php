@extends('layouts.app')
{{--@php(return abort(403))--}}
@section('styles')
    <link href="{{ asset('lib/jquery.switcher/switcher.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/jquery.switcher/switcher.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/uploader/dist/css/jquery.dm-uploader.min.css') }}" rel="stylesheet">
    <style>
        .switcher.default {
            float: right;
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

            $('.datepicker').datetimepicker({
                format: 'Y-m-d',
                step: 1,
                timepicker: false
            });

            $('.select2').select2();
        });
    </script>
@endsection

@section('header')
    <div class="sh-breadcrumb">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{ route('index') }}">INTILISH v3.1</a>
            <span class="breadcrumb-item">ГФ-ТБ</span>
            <span class="breadcrumb-item">Опросники</span>
            <span class="breadcrumb-item active">Анкета первичной оценки больных туберкулезом в местах исполнения наказания</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <div class="sh-pagetitle">

        <div class="input-group">
            <!-- search block -->
        </div><!-- input-group -->

        <div class="sh-pagetitle-left">
            <div class="sh-pagetitle-icon"><i class="icon ion-help"></i></div>
            <div class="sh-pagetitle-title">
                <h2>Анкета первичной оценки больных туберкулезом в местах исполнения наказания</h2>
            </div><!-- sh-pagetitle-left-title -->
        </div><!-- sh-pagetitle-left -->

    </div><!-- sh-pagetitle -->
@endsection

@section('content')
    <form id="form" autocomplete="off" action="{{ route('QuestionnaireOPTSave') }}" method="post" class="form-layout col-lg-12">
        {{ csrf_field() }}
        <div class="row mg-b-25 justify-content-lg-center">
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="form-control-label">
                        @lang('opt.date')
                        <span class="tx-danger">*</span>
                    </label>
                    <input placeholder="" name="date" type="text" class="form-control datepicker" required>
                </div>
            </div><!-- col-6 -->

            <div class="col-lg-6">
                <div class="form-group">
                    <label class="form-control-label">
                        @lang('opt.region')
                        <span class="tx-danger">*</span>
                    </label>
                    @if(Auth::user()->region->id === 0 || Auth::user()->role <= 2)
                    <select name="region" id="region" class="form-control select2" data-placeholder="Выберите регион" required>
                        <option></option>
                        @foreach($regions as $region)
                            <option value="{{ $region->id }}">{{ $region->encoding }}</option>
                        @endforeach
                    </select>
                    @else
                        <input readonly placeholder="" value="{{ Auth::user()->region->encoding }}" type="text" class="form-control" required>
                    @endif
                </div>
            </div><!-- col-6 -->

            <div class="col-lg-6">
                <div class="form-group">
                    <label class="form-control-label">
                        @lang('opt.place')
                        <span class="tx-danger">*</span>
                    </label>
                    <select name="place" id="place" class="form-control select2" data-placeholder="Выберите место" required>
                        <option></option>
                        @foreach($prisons as $prison)
                            <option value="{{ $prison->id }}">{{ $prison->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-6">
                <div class="form-group">
                    <label class="form-control-label">
                        @lang('opt.place_other')
                    </label>
                    <input placeholder="" name="place_other" type="text" class="form-control">
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <div class="form-group">
                    <label class="form-control-label">
                        @lang('opt.sex')
                        <span class="tx-danger">*</span>
                    </label>
                    <select name="sex" id="sex" class="form-control select2" required>
                        <option></option>
                        <option value="1">@lang('opt.sex_0')</option>
                        <option value="2">@lang('opt.sex_1')</option>
                    </select>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-6">
                <div class="form-group">
                    <label class="form-control-label">
                        @lang('opt.s_name')
                        <span class="tx-danger">*</span>
                    </label>
                    <input placeholder="" name="s_name" type="text" class="form-control" required>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-6">
                <div class="form-group">
                    <label class="form-control-label">
                        @lang('opt.f_name')
                        <span class="tx-danger">*</span>
                    </label>
                    <input placeholder="" name="f_name" type="text" class="form-control" required>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-6">
                <div class="form-group">
                    <label class="form-control-label">
                        @lang('opt.birthday')
                        <span class="tx-danger">*</span>
                    </label>
                    <input placeholder="" name="birthday" type="text" class="form-control datepicker" required>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-6">
                <div class="form-group">
                    <label class="form-control-label">
                        @lang('opt.encoding')
                        <span class="tx-danger">*</span>
                    </label>
                    <input placeholder="" name="encoding" type="text" class="form-control">
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <div class="form-group">
                    <label class="form-control-label">
                        @lang('opt.diagnosis')
                        <span class="tx-danger">*</span>
                    </label>
                    <textarea placeholder="" name="diagnosis" type="text" class="form-control" required></textarea>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-6">
                <div class="form-group">
                    <label class="form-control-label">
                        @lang('opt.date_tb_start')
                        <span class="tx-danger">*</span>
                    </label>
                    <input placeholder="" name="date_tb_start" type="text" class="form-control datepicker" required>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-6">
                <div class="form-group">
                    <label class="form-control-label">
                        @lang('opt.date_tb_end')
                    </label>
                    <input placeholder="" name="date_tb_end" type="text" class="form-control datepicker">
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <div class="form-group">
                    <label class="form-control-label">
                        @lang('opt.date_release')
                    </label>
                    <input placeholder="" name="date_release" type="text" class="form-control datepicker">
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-4">
                <div class="form-group">
                    <label class="form-control-label">
                        @lang('opt.phone')
                        <span class="tx-danger">*</span>
                    </label>
                    <input placeholder="@lang('opt.phone_home')" name="phone_home" type="text" class="form-control">
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-4">
                <label class="form-control-label">
                    &nbsp;
                </label>
                <div class="form-group">
                    <input placeholder="@lang('opt.phone_mobile')" name="phone_mobile" type="text" class="form-control">
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-4">
                <label class="form-control-label">
                    &nbsp;
                </label>
                <div class="form-group">
                    <input placeholder="@lang('opt.phone_alt')" name="phone_alt" type="text" class="form-control">
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.state')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <select name="state" id="state" class="form-control select2" data-placeholder="Выберите гражданство клиента" required>
                        <option></option>
                        <option value="Нет гражданства">Нет гражданства</option>
                        <option value="Республика Узбекистан">Республика Узбекистан</option>
                        <option value="Российская Федерация">Российская Федерация</option>
                    </select>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.address')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <textarea placeholder="" name="address" type="text" class="form-control" required></textarea>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.have_home')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <select name="have_home" class="form-control select2" data-placeholder="@lang('opt.placeholder_select')" required>
                        <option></option>
                        <option value="1">@lang('opt.yes')</option>
                        <option value="0">@lang('opt.not')</option>
                    </select>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.problem_home')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <textarea placeholder="" name="problem_home" type="text" class="form-control" required></textarea>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.help_home')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <select name="help_home" class="form-control select2" data-placeholder="@lang('opt.placeholder_select')" required>
                        <option></option>
                        <option value="1">@lang('opt.yes')</option>
                        <option value="0">@lang('opt.not')</option>
                    </select>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.problem_registration')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <select name="problem_registration" class="form-control select2" data-placeholder="@lang('opt.placeholder_select')" required>
                        <option></option>
                        <option value="1">@lang('opt.yes')</option>
                        <option value="0">@lang('opt.not')</option>
                    </select>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.problem_state')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <select name="problem_state" class="form-control select2" data-placeholder="@lang('opt.placeholder_select')" required>
                        <option></option>
                        <option value="1">@lang('opt.yes')</option>
                        <option value="0">@lang('opt.not')</option>
                    </select>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.help_state')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <select name="help_state" class="form-control select2" data-placeholder="@lang('opt.placeholder_select')" required>
                        <option></option>
                        <option value="1">@lang('opt.yes')</option>
                        <option value="0">@lang('opt.not')</option>
                    </select>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.status_marital')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <select name="status_marital" class="form-control select2" data-placeholder="@lang('opt.placeholder_select')" required>
                        <option></option>
                        <option value="0">@lang('opt.0')</option>
                        <option value="1">@lang('opt.status_marital_1')</option>
                        <option value="2">@lang('opt.status_marital_2')</option>
                        <option value="3">@lang('opt.status_marital_3')</option>
                        <option value="4">@lang('opt.status_marital_4')</option>
                        <option value="5">@lang('opt.status_marital_5')</option>
                    </select>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.status_passport')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <select name="status_passport[]" class="form-control select2" data-placeholder="@lang('opt.placeholder_select')" required multiple>
                        <option></option>
                        <option value="1">@lang('opt.status_passport_1')</option>
                        <option value="2">@lang('opt.status_passport_2')</option>
                        <option value="3">@lang('opt.status_passport_3')</option>
                        <option value="4">@lang('opt.status_passport_4')</option>
                        <option value="5">@lang('opt.status_passport_5')</option>
                        <option value="6">@lang('opt.status_passport_6')</option>
                        <option value="7">@lang('opt.status_passport_7')</option>
                    </select>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.problems')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <select name="problems[]" class="form-control select2" data-placeholder="@lang('opt.placeholder_select')" required multiple>
                        <option></option>
                        <option value="10">@lang('opt.problems_10')</option>
                        <option value="11">@lang('opt.problems_11')</option>
                        <option value="1">@lang('opt.problems_1')</option>
                        <option value="2">@lang('opt.problems_2')</option>
                        <option value="3">@lang('opt.problems_3')</option>
                        <option value="4">@lang('opt.problems_4')</option>
                        <option value="5">@lang('opt.problems_5')</option>
                        <option value="6">@lang('opt.problems_6')</option>
                        <option value="7">@lang('opt.problems_7')</option>
                        <option value="8">@lang('opt.problems_8')</option>
                        <option value="9">@lang('opt.problems_9')</option>
                        <option value="0">@lang('opt.problems_0')</option>
                    </select>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    Какого вида пособие вы хотите получать?
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <textarea class="form-control" rows="2"></textarea>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.help_problems')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <select name="help_problems" class="form-control select2" data-placeholder="@lang('opt.placeholder_select')" required>
                        <option></option>
                        <option value="1">@lang('opt.yes')</option>
                        <option value="0">@lang('opt.not')</option>
                    </select>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.type_problems')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <textarea placeholder="" name="type_problems" type="text" class="form-control" required></textarea>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.childrens')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <select name="childrens[]" class="form-control select2" data-placeholder="@lang('opt.placeholder_select')" required multiple>
                        <option></option>
                        <option value="0">@lang('opt.0')</option>
                        <option value="1">@lang('opt.childrens_1')</option>
                        <option value="2">@lang('opt.childrens_2')</option>
                        <option value="3">@lang('opt.childrens_3')</option>
                        <option value="4">@lang('opt.childrens_4')</option>
                        <option value="5">@lang('opt.childrens_5')</option>
                        <option value="6">@lang('opt.childrens_6')</option>
                    </select>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.statement')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <label class="form-control-label">
                        @lang('opt.statement_1')
                        <span class="tx-danger">*</span>
                    </label>
                    <select name="statement_1" class="form-control select2" data-placeholder="@lang('opt.placeholder_select')" required>
                        <option></option>
                        <option value="0">@lang('opt.0')</option>
                        <option value="1">@lang('opt.yes')</option>
                        <option value="0">@lang('opt.not')</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-control-label">
                        @lang('opt.statement_2')
                        <span class="tx-danger">*</span>
                    </label>
                    <select name="statement_2" class="form-control select2" data-placeholder="@lang('opt.placeholder_select')" required>
                        <option></option>
                        <option value="0">@lang('opt.0')</option>
                        <option value="1">@lang('opt.yes')</option>
                        <option value="0">@lang('opt.not')</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-control-label">
                        @lang('opt.statement_3')
                        <span class="tx-danger">*</span>
                    </label>
                    <select name="statement_3" class="form-control select2" data-placeholder="@lang('opt.placeholder_select')" required>
                        <option></option>
                        <option value="0">@lang('opt.0')</option>
                        <option value="1">@lang('opt.yes')</option>
                        <option value="0">@lang('opt.not')</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-control-label">
                        @lang('opt.statement_4')
                        <span class="tx-danger">*</span>
                    </label>
                    <select name="statement_4" class="form-control select2" data-placeholder="@lang('opt.placeholder_select')" required>
                        <option></option>
                        <option value="0">@lang('opt.0')</option>
                        <option value="1">@lang('opt.yes')</option>
                        <option value="0">@lang('opt.not')</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-control-label">
                        @lang('opt.statement_5')
                        <span class="tx-danger">*</span>
                    </label>
                    <select name="statement_5" class="form-control select2" data-placeholder="@lang('opt.placeholder_select')" required>
                        <option></option>
                        <option value="0">@lang('opt.0')</option>
                        <option value="1">@lang('opt.yes')</option>
                        <option value="0">@lang('opt.not')</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-control-label">
                        @lang('opt.statement_6')
                        <span class="tx-danger">*</span>
                    </label>
                    <select name="statement_6" class="form-control select2" data-placeholder="@lang('opt.placeholder_select')" required>
                        <option></option>
                        <option value="0">@lang('opt.0')</option>
                        <option value="1">@lang('opt.yes')</option>
                        <option value="0">@lang('opt.not')</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-control-label">
                        @lang('opt.statement_7')
                        <span class="tx-danger">*</span>
                    </label>
                    <select name="statement_7" class="form-control select2" data-placeholder="@lang('opt.placeholder_select')" required>
                        <option></option>
                        <option value="0">@lang('opt.0')</option>
                        <option value="1">@lang('opt.yes')</option>
                        <option value="0">@lang('opt.not')</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-control-label">
                        @lang('opt.statement_8')
                        <span class="tx-danger">*</span>
                    </label>
                    <select name="statement_8" class="form-control select2" data-placeholder="@lang('opt.placeholder_select')" required>
                        <option></option>
                        <option value="0">@lang('opt.0')</option>
                        <option value="1">@lang('opt.yes')</option>
                        <option value="0">@lang('opt.not')</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-control-label">
                        @lang('opt.statement_9')
                        <span class="tx-danger">*</span>
                    </label>
                    <select name="statement_9" class="form-control select2" data-placeholder="@lang('opt.placeholder_select')" required>
                        <option></option>
                        <option value="0">@lang('opt.0')</option>
                        <option value="1">@lang('opt.yes')</option>
                        <option value="0">@lang('opt.not')</option>
                    </select>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.help_statement')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <select name="help_statement" class="form-control select2" data-placeholder="@lang('opt.placeholder_select')" required>
                        <option></option>
                        <option value="1">@lang('opt.yes')</option>
                        <option value="0">@lang('opt.not')</option>
                    </select>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.type_statement')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <textarea placeholder="" name="type_statement" type="text" class="form-control" required></textarea>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.education')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <select name="education" class="form-control select2" data-placeholder="@lang('opt.placeholder_select')" required>
                        <option></option>
                        <option value="0">@lang('opt.0')</option>
                        <option value="1">@lang('opt.education_1')</option>
                        <option value="2">@lang('opt.education_2')</option>
                        <option value="3">@lang('opt.education_3')</option>
                        <option value="4">@lang('opt.education_4')</option>
                        <option value="5">@lang('opt.education_5')</option>
                        <option value="6">@lang('opt.education_6')</option>
                    </select>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.education_before')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <input placeholder="" name="education_before" type="text" class="form-control" required>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.profession_before')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <input placeholder="" name="profession_before" type="text" class="form-control" required>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.education_alt')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <select name="education_alt[]" class="form-control select2" data-placeholder="@lang('opt.placeholder_select')" required>
                        <option></option>
                        <option value="0">@lang('opt.0')</option>
                        <option value="1">@lang('opt.education_alt_1')</option>
                        <option value="2">@lang('opt.education_alt_2')</option>
                    </select>
                    <textarea placeholder="" name="education_alt[]" type="text" class="form-control"></textarea>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.want_education')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <select name="want_education" class="form-control select2" data-placeholder="@lang('opt.placeholder_select')" required>
                        <option></option>
                        <option value="1">@lang('opt.yes')</option>
                        <option value="0">@lang('opt.not')</option>
                    </select>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.want_education_name')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <input placeholder="" name="want_education_name" type="text" class="form-control" required>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.relationships')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <select name="relationships" class="form-control select2" data-placeholder="@lang('opt.placeholder_select')" required>
                        <option></option>
                        <option value="0">@lang('opt.0')</option>
                        <option value="1">@lang('opt.relationships_1')</option>
                        <option value="2">@lang('opt.relationships_2')</option>
                        <option value="3">@lang('opt.relationships_3')</option>
                    </select>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.have_family')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <select name="have_family" class="form-control select2" data-placeholder="@lang('opt.placeholder_select')" required>
                        <option></option>
                        <option value="1">@lang('opt.yes')</option>
                        <option value="0">@lang('opt.not')</option>
                    </select>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.have_family_problem')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <select name="have_family_problem" class="form-control select2" data-placeholder="@lang('opt.placeholder_select')" required>
                        <option></option>
                        <option value="1">@lang('opt.yes')</option>
                        <option value="0">@lang('opt.not')</option>
                    </select>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.type_family_problem')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <textarea placeholder="" name="type_family_problem" type="text" class="form-control" required></textarea>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.hiv')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <select name="hiv" class="form-control select2" data-placeholder="@lang('opt.placeholder_select')" required>
                        <option></option>
                        <option value="1">@lang('opt.yes')</option>
                        <option value="0">@lang('opt.not')</option>
                    </select>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.help_hiv')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <select name="help_hiv" class="form-control select2" data-placeholder="@lang('opt.placeholder_select')" required>
                        <option></option>
                        <option value="1">@lang('opt.yes')</option>
                        <option value="0">@lang('opt.not')</option>
                    </select>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.type_hiv')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <textarea placeholder="" name="type_hiv" type="text" class="form-control" required></textarea>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.addiction')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <select name="addiction" class="form-control select2" data-placeholder="@lang('opt.placeholder_select')" required>
                        <option></option>
                        <option value="0">@lang('opt.0')</option>
                        <option value="1">@lang('opt.addiction_1')</option>
                        <option value="2">@lang('opt.addiction_2')</option>
                        <option value="3">@lang('opt.addiction_3')</option>
                    </select>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.help_addiction')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <select name="help_addiction" class="form-control select2" data-placeholder="@lang('opt.placeholder_select')" required>
                        <option></option>
                        <option value="1">@lang('opt.yes')</option>
                        <option value="0">@lang('opt.not')</option>
                    </select>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.help_medical')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <select name="help_medical" class="form-control select2" data-placeholder="@lang('opt.placeholder_select')" required>
                        <option></option>
                        <option value="1">@lang('opt.yes')</option>
                        <option value="0">@lang('opt.not')</option>
                    </select>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.type_medical')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <textarea placeholder="" name="type_medical" type="text" class="form-control" required></textarea>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.help_disability')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <select name="help_disability" class="form-control select2" data-placeholder="@lang('opt.placeholder_select')" required>
                        <option></option>
                        <option value="1">@lang('opt.yes')</option>
                        <option value="0">@lang('opt.not')</option>
                    </select>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.type_disability')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <textarea placeholder="" name="type_disability" type="text" class="form-control" required></textarea>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.emotions')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <select name="emotions[]" class="form-control select2" data-placeholder="@lang('opt.placeholder_select')" required multiple>
                        <option></option>
                        <option value="1">@lang('opt.emotions_1')</option>
                        <option value="2">@lang('opt.emotions_2')</option>
                        <option value="3">@lang('opt.emotions_3')</option>
                        <option value="4">@lang('opt.emotions_4')</option>
                        <option value="5">@lang('opt.emotions_5')</option>
                        <option value="6">@lang('opt.emotions_6')</option>
                        <option value="7">@lang('opt.emotions_7')</option>
                        <option value="8">@lang('opt.emotions_8')</option>
                    </select>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.job')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <select name="job" class="form-control select2" data-placeholder="@lang('opt.placeholder_select')" required>
                        <option></option>
                        <option value="1">@lang('opt.yes')</option>
                        <option value="2">@lang('opt.job_2')</option>
                        <option value="3">@lang('opt.job_3')</option>
                    </select>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.return_job')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <select name="return_job" class="form-control select2" data-placeholder="@lang('opt.placeholder_select')" required>
                        <option></option>
                        <option value="1">@lang('opt.yes')</option>
                        <option value="0">@lang('opt.not')</option>
                        <option value="3">@lang('opt.return_job_2')</option>
                    </select>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.profession_jail')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <textarea placeholder="" name="profession_jail" type="text" class="form-control" required></textarea>
                </div>
            </div><!-- col-12 -->


            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.status_job')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <select name="status_job" class="form-control select2" data-placeholder="@lang('opt.placeholder_select')" required multiple>
                        <option></option>
                        <option value="1">@lang('opt.status_job_0')</option>
                        <option value="2">@lang('opt.status_job_1')</option>
                        <option value="3">@lang('opt.status_job_2')</option>
                        <option value="4">@lang('opt.status_job_3')</option>
                        <option value="5">@lang('opt.status_job_4')</option>
                    </select>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.plans')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <select name="plans" class="form-control select2" data-placeholder="@lang('opt.placeholder_select')" required>
                        <option></option>
                        <option value="0">@lang('opt.plans_0')</option>
                        <option value="1">@lang('opt.plans_1')</option>
                        <option value="2">@lang('opt.plans_2')</option>
                    </select>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-4">
                <label class="form-control-label">
                    @lang('opt.lawyer')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <select name="lawyer" class="form-control select2" data-placeholder="@lang('opt.placeholder_select')" required>
                        <option></option>
                        <option value="1">@lang('opt.yes')</option>
                        <option value="0">@lang('opt.not')</option>
                    </select>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-4">
                <label class="form-control-label">
                    @lang('opt.psychologist')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <select name="psychologist" class="form-control select2" data-placeholder="@lang('opt.placeholder_select')" required>
                        <option></option>
                        <option value="1">@lang('opt.yes')</option>
                        <option value="0">@lang('opt.not')</option>
                    </select>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-4">
                <label class="form-control-label">
                    @lang('opt.social')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <select name="social" class="form-control select2" data-placeholder="@lang('opt.placeholder_select')" required>
                        <option></option>
                        <option value="1">@lang('opt.yes')</option>
                        <option value="0">@lang('opt.not')</option>
                    </select>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.other_help')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <textarea placeholder="" name="other_help" type="text" class="form-control" required></textarea>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.other_notes')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <textarea placeholder="" name="other_notes" type="text" class="form-control" required></textarea>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">
                    @lang('opt.recommendations')
                    <span class="tx-danger">*</span>
                </label>
                <div class="form-group">
                    <select name="recommendations[]" class="form-control select2" data-placeholder="@lang('opt.placeholder_select')" required multiple>
                        <option></option>
                        <option value="1">@lang('opt.recommendations_1')</option>
                        <option value="2">@lang('opt.recommendations_2')</option>
                        <option value="3">@lang('opt.recommendations_3')</option>
                        <option value="4">@lang('opt.recommendations_4')</option>
                    </select>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <button class="btn btn-block btn-outline-success">@lang('opt.save')</button>
            </div>
        </div>
    </form>
@endsection
