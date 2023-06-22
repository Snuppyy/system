@extends('layouts.app')

@section('scripts')
    <script src="{{ asset('lib/datetimepicker/jquery.datetimepicker.full.js') }}"></script>
    <script src="{{ asset('lib/select2/dist/js/select2.full.js') }}"></script>
    {{--    <script type="text/javascript" src="{{ asset('lib/moment/moment.js') }}"></script>--}}
    <script type="text/javascript" src="{{ asset('lib/moment/locale/ru.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/moment/moment-timezone.js') }}"></script>

    <script type="text/javascript" src="{{ asset('lib/bootstrap/bootstrap.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/jquery.steps/jquery.steps.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/parsleyjs/parsley.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/datatables/jquery.dataTables.js') }}"></script>

@endsection

@section('styles')
    <link href="{{ asset('lib/select2/dist/css/select2.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('lib/datetimepicker/jquery.datetimepicker.min.css') }}"/>
    <link href="{{ asset('lib/jquery.steps/jquery.steps.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/datatables/jquery.dataTables.css') }}" rel="stylesheet">
@endsection

@section('header')
    <div class="sh-breadcrumb">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{ route('index') }}">INTILISH v3.1</a>
            <span class="breadcrumb-item active">Редактирование деятельности</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <div class="sh-pagetitle bg-warning">

        <div class="input-group">
            <!-- search block -->
        </div><!-- input-group -->

        <div class="sh-pagetitle-left">
            <div class="sh-pagetitle-icon"><i class="icon ion-ios-contact-outline"></i></div>
            <div class="sh-pagetitle-title">
                <span>Деятельность</span>
                <h2>Клонирование деятельности №{{ $activity->id }}</h2>
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
                timepicker: false
            });

        })
    </script>
@endsection

@section('content')
    @if(auth()->user()->role === 1)
        <a href="{{ route('assignments-get', ['id' => $activity->assignment_id]) }}"
           class="btn btn-success btn-block col-12">Посмотреть поручение</a>
    @endif
    {{--    {{ dd($activity) }}--}}
    <form autocomplete="off"  method="post"
          class="form-layout col-lg-12">
        {{ csrf_field() }}

        <div class="row mg-b-25">

            <div class="col-lg-12">
                <div class="form-group mg-b-10-force">
                    <label for="users" class="form-control-label">Сотрудники: <span
                                class="tx-danger">*</span></label>
                    <select name="users[]" id="users" multiple class="select2 form-control">
                        @foreach($users as $region => $user)
                            <optgroup label="{{ $region }}">
                                @foreach($user as $id => $name)
                                    @if($id !== $activity->user_id)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endif
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
            </div><!-- col-4 -->


            <div class="col-lg-6">
                <div class="form-group mg-b-10-force">
                    <label for="date" class="form-control-label">Дата: <span
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

            <div class="col-lg-12">
                <div class="form-group mg-b-10-force">
                    <label for="comment" class="form-control-label">Комментарии: <span
                                class="tx-danger">*</span></label>
                    <textarea class="form-control" id="comment" name="comment"
                              required>{{ $activity->comment }}</textarea>
                </div>
            </div><!-- col-4 -->

            <div class="form-layout-footer col-lg-12">
                <button class="btn btn-warning btn-block">Клонировать</button>
            </div><!-- form-layout-footer -->
        </div><!-- row -->
    </form><!-- form-layout -->
@endsection