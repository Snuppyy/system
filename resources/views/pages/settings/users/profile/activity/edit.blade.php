@extends('layouts.app')

@section('scripts')
    <script src="{{ asset('lib/select2/dist/js/select2.full.js') }}"></script>
    {{--    <script type="text/javascript" src="{{ asset('lib/moment/moment.js') }}"></script>--}}
    <script type="text/javascript" src="{{ asset('lib/moment/locale/ru.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/moment/moment-timezone.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/datetimepicker/daterangepicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/bootstrap/bootstrap.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/jquery.steps/jquery.steps.js') }}"></script>

    <script type="text/javascript" src="{{ asset('lib/datatables/jquery.dataTables.js') }}"></script>

@endsection

@section('styles')
    <link href="{{ asset('lib/select2/dist/css/select2.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('lib/datetimepicker/daterangepicker.css') }}"/>
    <link href="{{ asset('lib/jquery.steps/jquery.steps.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/datatables/jquery.dataTables.css') }}" rel="stylesheet">
    <style>
        #activities {
            width: 100%;
        }
    </style>
@endsection

@section('header')
    <div class="sh-breadcrumb">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{ route('index') }}">INTILISH v3.1</a>
            <span class="breadcrumb-item active">Редактирование деятельности</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <div class="sh-pagetitle">

        <div class="input-group">
            <!-- search block -->
        </div><!-- input-group -->

        <div class="sh-pagetitle-left">
            <div class="sh-pagetitle-icon"><i class="icon ion-ios-contact-outline"></i></div>
            <div class="sh-pagetitle-title">
                <span>Деятельность</span>
                <h2>Редактирование деятельности №{{ $activity->id }}</h2>
            </div><!-- sh-pagetitle-left-title -->
        </div><!-- sh-pagetitle-left -->

    </div><!-- sh-pagetitle -->
@endsection

@section('scriptsFooter')
    <script>
        $(function () {
            'use strict';

            $('.select2').select2();

            $('form.form-layout').parsley();

            window.ParsleyValidator
                .addValidator('mindate', function (value, requirement) {
                    // is valid date?
                    var timestamp = Date.parse(value),
                        minTs = Date.parse(requirement);

                    return isNaN(timestamp) ? false : timestamp > minTs;
                }, 32);

            $('.timepicker').datetimepicker({
                format: 'H:i',
                step: 1,
                mask: '__:__',
                datepicker: false
            });

            $('#date').datetimepicker({
                format: 'Y-m-d',
                step: 1,
                mask: '____-__-__',
                timepicker: false,
                minDate: '2019/04/01',
                maxDate: '2019/06/30'
            });

        })
    </script>
@endsection

@section('content')
    @if(auth()->user()->role === 1)
        <a href="{{ route('assignments-get', ['id' => $activity->assignment_id]) }}" class="btn btn-success btn-block col-12">Посмотреть поручение</a>
    @endif
{{--    {{ dd($project) }}--}}
    <form autocomplete="off" action="{{ route('activity-profile-editSave', ['id' => $activity->id, 'project' => $project]) }}" method="post"
          class="form-layout col-lg-12">
        {{ csrf_field() }}
        <input type="hidden" name="assignment" value="{{ $activity->assignment_id }}">
        <input type="hidden" name="position" value="{{ $position }}">
        <input type="hidden" name="user" value="{{ $activity->user }}">


        <div class="row mg-b-25">

{{--            <div class="col-lg-12">--}}
{{--                <div class="form-group mg-b-10-force">--}}
{{--                    <label for="mark" class="form-control-label">Название поручения: <span--}}
{{--                                class="tx-danger">*</span></label>--}}
{{--                    <input class="form-control" id="mark" name="mark" value="{{ $activity->mark }}" required>--}}
{{--                </div>--}}
{{--            </div><!-- col-4 -->--}}

{{--            <div class="col-lg-12">--}}
{{--                <div class="form-group mg-b-10-force">--}}
{{--                    <label for="user" class="form-control-label">Сотрудник: <span--}}
{{--                                class="tx-danger">*</span></label>--}}
{{--                    <select id="user" class="form-control select2"--}}
{{--                            data-parsley-trigger="change" name="user" required>--}}
{{--                        @foreach($users as $keys => $values)--}}
{{--                            @foreach($users[$keys] as $key => $value)--}}
{{--                                <optgroup label="{{ $key }}">--}}
{{--                                    @foreach($users[$keys][$key] as $k => $v)--}}
{{--                                        @if($k === $activity->user)--}}
{{--                                            <option selected value="{{ $k }}">{{ $v }}</option>--}}
{{--                                        @else--}}
{{--                                            <option value="{{ $k }}">{{ $v }}</option>--}}
{{--                                        @endif--}}
{{--                                    @endforeach--}}
{{--                                </optgroup>--}}
{{--                            @endforeach--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
{{--                </div>--}}
{{--            </div><!-- col-4 -->--}}

{{--            <div class="col-lg-12">--}}
{{--                <div class="form-group mg-b-10-force">--}}
{{--                    <label for="responsibility" class="form-control-label">Функциональная обязанность: <span--}}
{{--                                class="tx-danger">*</span></label>--}}
{{--                    <select id="responsibility" class="form-control select2"--}}
{{--                            data-parsley-trigger="change" name="responsibility" required>--}}
{{--                        @foreach($responsibilities as $responsibility)--}}
{{--                            @if($rid->id === $responsibility->id)--}}
{{--                                <option selected--}}
{{--                                        value="{{ $responsibility->id }}">{{ $responsibility->name }}</option>--}}
{{--                            @else--}}
{{--                                <option value="{{ $responsibility->id }}">{{ $responsibility->name }}</option>--}}
{{--                            @endif--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
{{--                </div>--}}
{{--            </div><!-- col-4 -->--}}

            <div class="col-lg-6">
                <div class="form-group mg-b-10-force">
                    <label for="organization" class="form-control-label">Дата: <span
                                class="tx-danger">*</span></label>
                    <input class="form-control" id="date" type="text" lang="ru" name="date"
                           data-parsley-pattern="^[0-9]{4}[-]{1}[0-9]{2}[-]{1}[0-9]{2}$"
                           data-parsley-trigger="change" value="{{ $activity->date->format('Y-m-d') }}" required>
                </div>
            </div><!-- col-4 -->

            <div class="col-lg-3">
                <div class="form-group mg-b-10-force">
                    <label for="start" class="form-control-label">Время начала: <span
                                class="tx-danger">*</span></label>
                    <input class="form-control timepicker" id="start" type="text" lang="ru" name="start"
                           value="{{ $activity->start }}"
                           data-parsley-pattern="^[0-9]{2}[:]{1}[0-9]{2}$"
                           data-parsley-trigger="change" required>
                </div>
            </div><!-- col-4 -->

            <div class="col-lg-3">
                <div class="form-group mg-b-10-force">
                    <label for="end" class="form-control-label">Время конца: <span
                                class="tx-danger">*</span></label>
                    <input class="form-control timepicker" id="end" type="text" lang="ru" name="end"
                           value="{{ $activity->end }}"
                           data-parsley-pattern="^[0-9]{2}[:]{1}[0-9]{2}$"
                           data-parsley-trigger="change" required>
                </div>
            </div><!-- col-4 -->

            @if($activity->clients)
            <div class="col-lg-12">
                <div class="form-group mg-b-10-force">
                    <label for="responsibility" class="form-control-label">Клиенты: <span
                                class="tx-danger">*</span></label>
                    <select id="clients" class="form-control select2 sel2 wd-100p-force"
                            data-placeholder="Выберите клиентов"
                            multiple name="clients[]">
                        <option label="Выберите клиентоа"></option>
                        @foreach($clients as $client)
                            @if(in_array($client->id, $activity->clients))
                                <option selected
                                        value="{{ $client->id }}">{{ $client->f_name .' ' . $client->s_name }}</option>
                            @else
                                <option value="{{ $client->id }}">{{ $client->f_name .' ' . $client->s_name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div><!-- col-4 -->
            @endif

            <div class="col-lg-12">
                <div class="form-group mg-b-10-force">
                    <label for="comment" class="form-control-label">Комментарии: <span
                                class="tx-danger">*</span></label>
                    <textarea class="form-control" id="comment" name="comment" required>{{ $activity->comment }}</textarea>
                </div>
            </div><!-- col-4 -->

            <div class="form-layout-footer col-lg-12">
                <button class="btn btn-warning btn-block">Сохранить</button>
            </div><!-- form-layout-footer -->
        </div><!-- row -->
    </form><!-- form-layout -->
@endsection