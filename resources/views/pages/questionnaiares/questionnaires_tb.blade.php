@extends('layouts.app')

@section('styles')
    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/jquery.switcher/switcher.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/uploader/dist/css/jquery.dm-uploader.min.css') }}" rel="stylesheet">
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
            'use strict';

            $('.select2').select2();

            $('.switcher').switcher();

            $('form.form-layout').parsley();

            $('.date').datetimepicker({
                format: 'Y-m-d',
                timepicker: false,
                step: 1,
                mask: '____-__-__',
            });

            $('#volunteer').mask("AAAA000", {
                translation: {
                    'A': {
                        pattern: '^[А-Я]$'
                    }
                },
                placeholder: "AAAA000"
            });
        })
    </script>
@endsection

@section('header')
    <div class="sh-breadcrumb">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{ route('index') }}">INTILISH v3.1</a>
            <span class="breadcrumb-item">Формы</span>
            <span class="breadcrumb-item active">{{ $questionnaire['name'] }}</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <div class="sh-pagetitle">

        <div class="input-group">
            <!-- search block -->
        </div><!-- input-group -->

        <div class="sh-pagetitle-left">
            <div class="sh-pagetitle-icon"><i class="icon ion-ios-bookmarks-outline"></i></div>
            <div class="sh-pagetitle-title">
                <h2>{{ $questionnaire['name'] }}</h2>
            </div><!-- sh-pagetitle-left-title -->
        </div><!-- sh-pagetitle-left -->

    </div><!-- sh-pagetitle -->
@endsection

@section('content')
    @if(session('success'))
        <div class="col-lg-12 alert alert-success wd-100p-force text-center">
            {{ session('success') }}
        </div>
    @endif
    <form autocomplete="off" action="{{ route('set-questionnaires', $questionnaire['encoding']) }}" method="post"
          class="form-layout col-lg-12">
        {{ csrf_field() }}
        <input type="hidden" name="questionnaire" value="{{ $questionnaire['id'] }}">
        <div class="row mg-b-25">
            <div class="col-lg-1">
                <div class="form-group mg-b-10-force">
                    <label for="region" class="form-control-label">Регион: <span
                                class="tx-danger">*</span></label>
                    @if(Auth::user()->region->id === 0 || Auth::user()->role <= 2)
                        <select name="region" id="region" class="form-control select2" data-placeholder="Выберите регион" required>
                            <option></option>
                            @foreach($regions as $region)
                                <option value="{{ $region->id }}">{{ $region->encoding }}</option>
                            @endforeach
                        </select>
                    @else
                        <input id="region" class="form-control" type="text" name="region"
                               value="{{ Auth::user()->region->encoding }}"
                               data-parsley-trigger="change" required readonly>
                    @endif
                </div>
            </div><!-- col-1 -->

            <div class="col-lg-2">
                <div class="form-group">
                    <label for="date" class="form-control-label">Дата анкетирования: <span
                                class="tx-danger">*</span></label>
                    <input id="date" class="form-control date" type="text" lang="ru" name="date"
                           data-parsley-pattern="^[0-9]{4}[-]{1}[0-9]{2}[-]{1}[0-9]{2}$" data-parsley-trigger="change"
                           required>
                </div>
            </div><!-- col-3 -->

            <div class="col-lg-3">
                <div class="form-group mg-b-10-force sel2">
                    <label for="client" class="form-control-label">Выберите клиента: <span
                                class="tx-danger">*</span></label>
                    <select id="client" class="form-control select2 sel2"
                            data-placeholder="Выберите клиента"
                            data-parsley-trigger="change" name="client" data-parsley-trigger="change" required>
                        <option label="Выберите клиента"></option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div><!-- col-5 -->
            @if($questionnaire['id'] != 10)
            <div class="col-lg-2">
                <div class="form-group">
                    <label class="form-control-label" for="type">Тип анкетирования: <span
                                class="tx-danger">*</span></label>
                    <div id="typeClient" class="form-control">
                        <label class="rdiobox">
                            <input name="type" value="1" type="radio" data-parsley-class-handler="#typeClient"
                                   data-parsley-errors-container="#typeClientErrorContainer"
                                   data-parsley-trigger="change" required>
                            <span>ПРЕ</span>
                        </label>
                        <label class="rdiobox mg-b-0-force">
                            <input name="type" value="2" type="radio" data-parsley-trigger="change" required>
                            <span>ПОСТ</span>
                        </label>
                    </div>
                    <div id="typeClientErrorContainer"></div>
                </div>
            </div><!-- col-2 -->

            <div class="col-lg-2">
                <div class="form-group mg-b-10-force sel2">
                    <label for="sex" class="form-control-label">Пол клиента: <span
                                class="tx-danger">*</span></label>
                    <select id="sex" class="form-control select2 sel2"
                            data-placeholder="Укажите пол клиента"
                            data-parsley-trigger="change" name="sex" data-parsley-trigger="change" required>
                        <option></option>
                        <option value="1">Мужской</option>
                        <option value="1">Женский</option>
                    </select>
                </div>
            </div><!-- col-5 -->

            <div class="col-lg-2">
                <div class="form-group">
                    <label for="birthday" class="form-control-label">Дата рождения:</label>
                    <input id="birthday" class="form-control date" type="text" lang="ru" name="birthday">
                </div>
            </div><!-- col-3 -->
            @else
                <div class="col-lg-2">
                    <div class="form-group">
                        <label class="form-control-label" for="type">Тип анкетирования: <span
                                    class="tx-danger">*</span></label>
                        <div id="typeClient" class="form-control">
                            <label class="rdiobox">
                                <input name="type" value="1" type="radio" data-parsley-class-handler="#typeClient"
                                       data-parsley-errors-container="#typeClientErrorContainer"
                                       data-parsley-trigger="change" required>
                                <span>ПРЕ</span>
                            </label>
                            <label class="rdiobox mg-b-0-force">
                                <input name="type" value="2" type="radio" data-parsley-trigger="change" required>
                                <span>ПОСТ</span>
                            </label>
                        </div>
                        <div id="typeClientErrorContainer"></div>
                    </div>
                </div><!-- col-2 -->
            @endif

            @php ($i = 0)
            @foreach($questionnaire as $id_question => $question)
                @if(is_int($id_question))
                    @php ($i++)
                    <div class="col-lg-12">
                        <div class="form-group mg-b-10-force pd-t-10">
                            <label class="form-control-label"><span class="tx-bold">{{ $i }}
                                    .</span> {{ $questionnaire[$id_question]['question'] }}
                                <span
                                        class="tx-danger">*</span></label>
                            @foreach($questionnaire[$id_question]['answers'] as $id_answer => $answer)
                                <div class="form-control {{ $questionnaire['encoding'].'_'.$id_question }}">
                                    <label class="{{ $questionnaire[$id_question]['type'] == 0 ? 'rdiobox' : 'ckbox' }} mg-b-0-force">
                                        <input name="{{ $questionnaire['encoding'].'_'.$id_question }}{{ $questionnaire[$id_question]['type'] == 2 ? '[]' : '' }}"
                                               value="{{ $id_answer }}" type="{{ $questionnaire[$id_question]['type'] == 0 ? 'radio' : 'checkbox' }}"
                                               data-parsley-class-handler=".{{ $questionnaire['encoding'].'_'.$id_question }}"
                                               data-parsley-errors-container="#{{ $questionnaire['encoding'].'_'.$id_question }}ErrorContainer"
                                               data-parsley-trigger="change" required><span>{{ $answer }}</span>
                                    </label>
                                </div>
                            @endforeach

                            <div class="form-control {{ $questionnaire['encoding'].'_'.$id_question }}">
                                <label class="rdiobox mg-b-0-force">
                                    <input name="{{ $questionnaire['encoding'].'_'.$id_question }}"
                                           value="0" type="radio"
                                           data-parsley-class-handler=".{{ $questionnaire['encoding'].'_'.$id_question }}"
                                           data-parsley-errors-container="#{{ $questionnaire['encoding'].'_'.$id_question }}ErrorContainer"
                                           data-parsley-trigger="change" required><span>Не знаю</span>
                                </label>
                            </div>

                            <div id="{{ $questionnaire['encoding'].'_'.$id_question }}ErrorContainer"></div>
                        </div>
                    </div><!-- col-12 -->
                @endif
            @endforeach
            <div class="form-layout-footer col-lg-12">
                <button class="btn btn-success btn-block">Сохранить</button>
            </div><!-- form-layout-footer -->
        </div>
    </form>
@endsection
