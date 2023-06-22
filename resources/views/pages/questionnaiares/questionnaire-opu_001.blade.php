@extends('layouts.app')

@section('styles')
    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/jquery.switcher/switcher.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/uploader/dist/css/jquery.dm-uploader.min.css') }}" rel="stylesheet">

    <style>
        label {
            white-space: nowrap;
        }

        thead {
            text-align: center;
            vertical-align: center;
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
            'use strict';

            $('.select2').select2();

            $('.requiredCheckbox').change(function () {
                if($(this).is(':checked')){
                    $('#ladder-meters').attr('required');
                } else {
                    $('#ladder-meters').removeAttr('required');
                }
            });

            $('.date').datetimepicker({
                format: 'Y-m-d',
                timepicker: false,
                step: 1,
                mask: '____-__-__',
            });

            $('#encoding').mask("AAAA000", {
                translation: {
                    'A': {
                        pattern: '^[А-Я]$'
                    }
                },
                placeholder: "AAAA000"
            });

            $('form.form-layout').parsley();

            $('#interviewer').change(function (e) {
                var val = $(this).val();
                $('#position').val($(this).children('[value="' + val + '"]').data('position'));
            });

            $('.date_hiv').change(function () {
                if($(this)[0].checked){
                    $('#date_hiv').remove();
                } else {
                    $(this).parents('td').append('<input id="date_hiv" name="date_hiv" type="text" class="form-control date" data-parsley-pattern="^[0-9]{4}[-]{1}[0-9]{2}[-]{1}[0-9]{2}$" data-parsley-trigger="change" required>');
                    $('.date').datetimepicker({
                        format: 'Y-m-d',
                        timepicker: false,
                        step: 1,
                        mask: '____-__-__',
                    });
                }
            });

            $('.date_fluorography').change(function () {
                if($(this)[0].checked){
                    $('#date_fluorography').remove();
                } else {
                    $(this).parents('td').append('<input id="date_fluorography" name="date_fluorography" type="text" class="form-control date" data-parsley-pattern="^[0-9]{4}[-]{1}[0-9]{2}[-]{1}[0-9]{2}$" data-parsley-trigger="change" required>');
                    $('.date').datetimepicker({
                        format: 'Y-m-d',
                        timepicker: false,
                        step: 1,
                        mask: '____-__-__',
                    });
                }
            });
        })
    </script>
@endsection

@section('header')
    <div class="sh-breadcrumb">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{ route('index') }}">INTILISH v3.1</a>
            <span class="breadcrumb-item">Формы</span>
            <span class="breadcrumb-item active">Оценка предоставленных услуг</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <div class="sh-pagetitle">

        <div class="input-group">
            <!-- search block -->
        </div><!-- input-group -->

        <div class="sh-pagetitle-left">
            <div class="sh-pagetitle-icon"><i class="icon ion-ios-bookmarks-outline"></i></div>
            <div class="sh-pagetitle-title">
                <h2>Анкета по оценке предоставленных услуг</h2>
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
    <form autocomplete="off" action="{{ route('questionnaire-set-opu_001') }}" method="post" class="form-layout col-lg-12">
        {{ csrf_field() }}
        <div class="row mg-b-25">
            <div class="col-lg-1">
                <div class="form-group mg-b-10-force">
                    <label for="region" class="form-control-label">Регион: <span
                                class="tx-danger">*</span></label>
                    <input id="region" class="form-control" type="text" name="region"
                           value="{{ Auth::user()->region->encoding }}"
                           data-parsley-trigger="change" required readonly>
                </div>
            </div><!-- col-1 -->

            <div class="col-lg-5">
                <div class="form-group mg-b-10-force sel2">
                    <label for="drop_inCenter" class="form-control-label">Выберите кабинет доверия: <span
                                class="tx-danger">*</span></label>
                    <select id="drop_inCenter" class="form-control select2 sel2"
                            data-placeholder="Выберите кабинет доверия"
                            name="drop_inCenter" data-parsley-trigger="change" required>
                        <option label="Выберите кабинет доверия"></option>
                        @foreach($drop_inCenters as $drop_inCenter)
                            <option value="{{ $drop_inCenter->id }}">{{ $drop_inCenter->encoding }}
                                - {{ $drop_inCenter->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div><!-- col-3 -->

            <div class="col-lg-2">
                <div class="form-group">
                    <label for="date" class="form-control-label">Дата анкетирования: <span
                                class="tx-danger">*</span></label>
                    <input id="date" class="form-control date" type="text" lang="ru" name="date"
                           data-parsley-pattern="^[0-9]{4}[-]{1}[0-9]{2}[-]{1}[0-9]{2}$" data-parsley-trigger="change" required>
                </div>
            </div><!-- col-3 -->

            <div class="col-lg-2">
                <div class="form-group">
                    <label class="form-control-label" for="encoding">Код клиента: <span
                                class="tx-danger">*</span></label>
                    <input id="encoding" class="form-control" type="text" name="encoding" data-parsley-error-message="Кодировку следует писать с заглавными латинскими буквами" data-parsley-pattern="^[А-Я]{4}[0-9]{3}$" data-parsley-trigger="change" required>
                </div>
            </div><!-- col-3 -->

            <div class="col-lg-2">
                <div class="form-group">
                    <label class="form-control-label" for="type">Тип клиента: <span
                                class="tx-danger">*</span></label>
                    <div id="typeClient" class="form-control">
                        <label class="rdiobox">
                            <input name="type" value="1" type="radio" data-parsley-class-handler="#typeClient" data-parsley-errors-container="#typeClientErrorContainer" data-parsley-trigger="change" required>
                            <span>Первичный</span>
                        </label>
                        <label class="rdiobox mg-b-0-force">
                            <input name="type" value="2" type="radio" data-parsley-trigger="change" required>
                            <span>Вторичный</span>
                        </label>
                    </div>
                    <div id="typeClientErrorContainer"></div>
                </div>
            </div><!-- col-2 -->
            <div class="col-lg-12">
                <div class="form-group mg-b-10-force sel2">
                    <label for="interviewer" class="form-control-label">Выберите интервьювера: <span
                                class="tx-danger">*</span></label>
                    <select id="interviewer" class="form-control select2" data-placeholder="Выберите интервьювера"
                            data-parsley-trigger="change" name="interviewer" data-parsley-trigger="change" required>
                        <option label="Выберите интервьювера"></option>
                        @foreach($staff as $user)
                                <option data-position="{{ $user->positionName }}" data-position-id="{{ $user->position[0] }}"
                                        value="{{ $user->id }}">{{ $user->name_ru }}</option>
                        @endforeach
                    </select>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <div class="form-group mg-b-10-force">
                    <label for="position" class="form-control-label">Должность интервьювера: <span
                                class="tx-danger">*</span></label>
                    <input id="position" class="form-control" readonly>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <div class="form-group mg-b-10-force sel2">
                    <label for="outreach" class="form-control-label">Выберите аутрич - сотрудника: <span
                                class="tx-danger">*</span></label>
                    <select id="outreach" class="form-control select2" data-placeholder="Выберите аутрич - сотрудника"
                            data-parsley-trigger="change" name="outreach" data-parsley-trigger="change" required>
                        <option label="Выберите аутрич - сотрудника"></option>
                        @foreach($outreaches as $outreach)
                            <option value="{{ $outreach->id }}">{{ $outreach->encoding ? $outreach->encoding.' - '.$outreach->name : $outreach->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <div class="form-group mg-b-10-force pd-t-10">
                    <label class="form-control-label">1. Выберите только правильные ответы: <span
                                class="tx-danger">*</span></label>

                    <div class="form-control opu_001_0001">
                        <label class="ckbox mg-b-0-force">
                            <input name="opu_001_0001[]" value="1" type="checkbox" data-parsley-class-handler=".opu_001_0001" data-parsley-errors-container="#opu_001_0001ErrorContainer" data-parsley-trigger="change" required><span>ВИЧ - это вирус иммунодефицита человека, который передаётся только от человека к человеку</span>
                        </label>
                    </div>

                    <div class="form-control opu_001_0001">
                        <label class="ckbox mg-b-0-force">
                            <input name="opu_001_0001[]" value="2"
                                   type="checkbox" data-parsley-trigger="change" required><span>ВИЧ - последняя стадия СПИДа</span>
                        </label>
                    </div>

                    <div class="form-control opu_001_0001">
                        <label class="ckbox mg-b-0-force">
                            <input name="opu_001_0001[]" value="3" type="checkbox" data-parsley-trigger="change" required><span>СПИД - это последняя стадия ВИЧ - инфекции</span>
                        </label>
                    </div>

                    <div id="opu_001_0001ErrorContainer"></div>

                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <div class="form-group mg-b-10-force pd-t-10">
                    <label class="form-control-label mg-b-10">2. Согласны ли Вы со следующими утверждениями, что ВИЧ:
                        <span class="tx-danger">*</span></label>
                    <div class="form-control opu_001_0002">
                        <label class="ckbox mg-b-0-force">
                            <input name="opu_001_0002[]" value="1" type="checkbox" data-parsley-class-handler=".opu_001_0002" data-parsley-errors-container="#opu_001_0002ErrorContainer" data-parsley-trigger="change" required><span>Быстро погибает вне организма человека</span>
                        </label>
                    </div>

                    <div class="form-control opu_001_0002">
                        <label class="ckbox mg-b-0-force">
                            <input name="opu_001_0002[]" value="2" type="checkbox" data-parsley-trigger="change" required><span>Передается через кровососущих насекомых</span>
                        </label>
                    </div>

                    <div class="form-control opu_001_0002">
                        <label class="ckbox mg-b-0-force">
                            <input name="opu_001_0002[]" value="3" type="checkbox" data-parsley-trigger="change" required><span>Находится в воздухе и передается как грипп</span>
                        </label>
                    </div>

                    <div class="form-control opu_001_0002">
                        <label class="ckbox mg-b-0-force">
                            <input name="opu_001_0002[]" value="4" type="checkbox" data-parsley-trigger="change" required><span>Находится только в жидкостях человеческого организма (крови, сперме)</span>
                        </label>
                    </div>

                    <div id="opu_001_0002ErrorContainer"></div>

                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <div class="form-group mg-b-10-force pd-t-10">
                    <label class="form-control-label">3. Как Вы думаете, можно ли заразиться ВИЧ-инфекцией: <span
                                class="tx-danger">*</span></label>
                    <div id="opu_001_0003_001" class="form-control">
                        <span>В плавательном бассейне</span>
                        <div class="float-right">
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0003_001" value="2" type="radio" data-parsley-class-handler="#opu_001_0003_001" data-parsley-errors-container="#opu_001_0003_001ErrorContainer" data-parsley-trigger="change" required>
                                <span>Да</span>
                            </label>
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0003_001" value="0" type="radio" data-parsley-trigger="change" required>
                                <span>Нет</span>
                            </label>
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0003_001" value="1" type="radio" data-parsley-trigger="change" required>
                                <span>Не знаю</span>
                            </label>
                        </div>
                    </div>
                    <div id="opu_001_0003_001ErrorContainer"></div>

                    <div id="opu_001_0003_002" class="form-control">
                        <span>При инъекции лекарства</span>
                        <div class="float-right">
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0003_002" value="2" type="radio" data-parsley-class-handler="#opu_001_0003_002" data-parsley-errors-container="#opu_001_0003_002ErrorContainer" data-parsley-trigger="change" required>
                                <span>Да</span>
                            </label>
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0003_002" value="0" type="radio" data-parsley-trigger="change" required>
                                <span>Нет</span>
                            </label>
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0003_002" value="1" type="radio" data-parsley-trigger="change" required>
                                <span>Не знаю</span>
                            </label>
                        </div>
                    </div>
                    <div id="opu_001_0003_002ErrorContainer"></div>

                    <div id="opu_001_0003_003" class="form-control">
                        <span>Через сидение унитаза</span>
                        <div class="float-right">
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0003_003" value="2" type="radio" data-parsley-class-handler="#opu_001_0003_003" data-parsley-errors-container="#opu_001_0003_003ErrorContainer" data-parsley-trigger="change" required>
                                <span>Да</span>
                            </label>
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0003_003" value="0" type="radio" data-parsley-trigger="change" required>
                                <span>Нет</span>
                            </label>
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0003_003" value="1" type="radio" data-parsley-trigger="change" required>
                                <span>Не знаю</span>
                            </label>
                        </div>
                    </div>
                    <div id="opu_001_0003_003ErrorContainer"></div>

                    <div id="opu_001_0003_004" class="form-control">
                        <span>При использовании общих шприцев / игл при употреблении наркотиков</span>
                        <div class="float-right">
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0003_004" value="2" type="radio" data-parsley-class-handler="#opu_001_0003_004" data-parsley-errors-container="#opu_001_0003_004ErrorContainer" data-parsley-trigger="change" required>
                                <span>Да</span>
                            </label>
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0003_004" value="0" type="radio" data-parsley-trigger="change" required>
                                <span>Нет</span>
                            </label>
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0003_004" value="1" type="radio" data-parsley-trigger="change" required>
                                <span>Не знаю</span>
                            </label>
                        </div>
                    </div>
                    <div id="opu_001_0003_004ErrorContainer"></div>

                    <div id="opu_001_0003_005" class="form-control">
                        <span>При поцелуе</span>
                        <div class="float-right">
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0003_005" value="2" type="radio" data-parsley-class-handler="#opu_001_0003_005" data-parsley-errors-container="#opu_001_0003_005ErrorContainer" data-parsley-trigger="change" required>
                                <span>Да</span>
                            </label>
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0003_005" value="0" type="radio" data-parsley-trigger="change" required>
                                <span>Нет</span>
                            </label>
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0003_005" value="1" type="radio" data-parsley-trigger="change" required>
                                <span>Не знаю</span>
                            </label>
                        </div>
                    </div>
                    <div id="opu_001_0003_005ErrorContainer"></div>

                    <div id="opu_001_0003_006" class="form-control">
                        <span>При незащищенном половом контакте</span>
                        <div class="float-right">
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0003_006" value="2" type="radio" data-parsley-class-handler="#opu_001_0003_006" data-parsley-errors-container="#opu_001_0003_006ErrorContainer" data-parsley-trigger="change" required>
                                <span>Да</span>
                            </label>
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0003_006" value="0" type="radio" data-parsley-trigger="change" required>
                                <span>Нет</span>
                            </label>
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0003_006" value="1" type="radio" data-parsley-trigger="change" required>
                                <span>Не знаю</span>
                            </label>
                        </div>
                    </div>
                    <div id="opu_001_0003_006ErrorContainer"></div>

                    <div id="opu_001_0003_007" class="form-control">
                        <span>При рукопожатии</span>
                        <div class="float-right">
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0003_007" value="2" type="radio" data-parsley-class-handler="#opu_001_0003_007" data-parsley-errors-container="#opu_001_0003_007ErrorContainer" data-parsley-trigger="change" required>
                                <span>Да</span>
                            </label>
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0003_007" value="0" type="radio" data-parsley-trigger="change" required>
                                <span>Нет</span>
                            </label>
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0003_007" value="1" type="radio" data-parsley-trigger="change" required>
                                <span>Не знаю</span>
                            </label>
                        </div>
                    </div>
                    <div id="opu_001_0003_007ErrorContainer"></div>

                    <div id="opu_001_0003_008" class="form-control">
                        <span>При сдаче крови</span>
                        <div class="float-right">
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0003_008" value="2" type="radio" data-parsley-class-handler="#opu_001_0003_008" data-parsley-errors-container="#opu_001_0003_008ErrorContainer" data-parsley-trigger="change" required>
                                <span>Да</span>
                            </label>
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0003_008" value="0" type="radio" data-parsley-trigger="change" required>
                                <span>Нет</span>
                            </label>
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0003_008" value="1" type="radio" data-parsley-trigger="change" required>
                                <span>Не знаю</span>
                            </label>
                        </div>
                    </div>
                    <div id="opu_001_0003_008ErrorContainer"></div>

                    <div id="opu_001_0003_009" class="form-control">
                        <span>При переливании крови</span>
                        <div class="float-right">
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0003_009" value="2" type="radio" data-parsley-class-handler="#opu_001_0003_009" data-parsley-errors-container="#opu_001_0003_009ErrorContainer" data-parsley-trigger="change" required>
                                <span>Да</span>
                            </label>
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0003_009" value="0" type="radio" data-parsley-trigger="change" required>
                                <span>Нет</span>
                            </label>
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0003_009" value="1" type="radio" data-parsley-trigger="change" required>
                                <span>Не знаю</span>
                            </label>
                        </div>
                    </div>
                    <div id="opu_001_0003_009ErrorContainer"></div>

                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <div class="form-group mg-b-10-force pd-t-10">
                    <label class="form-control-label">4. На Ваш взгляд, можете ли Вы защитить себя от заражения
                        ВИЧ-инфекции: <span
                                class="tx-danger">*</span></label>
                    <div id="opu_001_0004_001" class="form-control">
                        <span>Если совсем не заниматься сексом</span>
                        <div class="float-right">
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0004_001" value="2" type="radio" data-parsley-class-handler="#opu_001_0004_001" data-parsley-errors-container="#opu_001_0004_001ErrorContainer" data-parsley-trigger="change" required>
                                <span>Да</span>
                            </label>
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0004_001" value="0" type="radio" data-parsley-trigger="change" required>
                                <span>Нет</span>
                            </label>
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0004_001" value="1" type="radio" data-parsley-trigger="change" required>
                                <span>Не знаю</span>
                            </label>
                        </div>
                    </div>
                    <div id="opu_001_0004_001ErrorContainer"></div>

                    <div id="opu_001_0004_002" class="form-control">
                        <span>Если использовать противозачаточные таблетки</span>
                        <div class="float-right">
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0004_002" value="2" type="radio" data-parsley-class-handler="#opu_001_0004_002" data-parsley-errors-container="#opu_001_0004_002ErrorContainer" data-parsley-trigger="change" required>
                                <span>Да</span>
                            </label>
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0004_002" value="0" type="radio" data-parsley-trigger="change" required>
                                <span>Нет</span>
                            </label>
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0004_002" value="1" type="radio" data-parsley-trigger="change" required>
                                <span>Не знаю</span>
                            </label>
                        </div>
                    </div>
                    <div id="opu_001_0004_002ErrorContainer"></div>

                    <div id="opu_001_0004_003" class="form-control">
                        <span>Если использовать презервативы при каждом половом контакте</span>
                        <div class="float-right">
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0004_003" value="2" type="radio" data-parsley-class-handler="#opu_001_0004_003" data-parsley-errors-container="#opu_001_0004_003ErrorContainer" data-parsley-trigger="change" required>
                                <span>Да</span>
                            </label>
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0004_003" value="0" type="radio" data-parsley-trigger="change" required>
                                <span>Нет</span>
                            </label>
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0004_003" value="1" type="radio" data-parsley-trigger="change" required>
                                <span>Не знаю</span>
                            </label>
                        </div>
                    </div>
                    <div id="opu_001_0004_003ErrorContainer"></div>

                    <div id="opu_001_0004_004" class="form-control">
                        <span>Если избегать грязных мест общественного пользования</span>
                        <div class="float-right">
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0004_004" value="2" type="radio" data-parsley-class-handler="#opu_001_0004_004" data-parsley-errors-container="#opu_001_0004_004ErrorContainer" data-parsley-trigger="change" required>
                                <span>Да</span>
                            </label>
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0004_004" value="0" type="radio" data-parsley-trigger="change" required>
                                <span>Нет</span>
                            </label>
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0004_004" value="1" type="radio" data-parsley-trigger="change" required>
                                <span>Не знаю</span>
                            </label>
                        </div>
                    </div>
                    <div id="opu_001_0004_004ErrorContainer"></div>

                    <div id="opu_001_0004_005" class="form-control">
                        <span>Если не употреблять наркотики</span>
                        <div class="float-right">
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0004_005" value="2" type="radio" data-parsley-class-handler="#opu_001_0004_005" data-parsley-errors-container="#opu_001_0004_005ErrorContainer" data-parsley-trigger="change" required>
                                <span>Да</span>
                            </label>
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0004_005" value="0" type="radio" data-parsley-trigger="change" required>
                                <span>Нет</span>
                            </label>
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0004_005" value="1" type="radio" data-parsley-trigger="change" required>
                                <span>Не знаю</span>
                            </label>
                        </div>
                    </div>
                    <div id="opu_001_0004_005ErrorContainer"></div>

                    <div id="opu_001_0004_006" class="form-control">
                        <span>Если при инъекции использовать стерильные шприцы</span>
                        <div class="float-right">
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0004_006" value="2" type="radio" data-parsley-class-handler="#opu_001_0004_006" data-parsley-errors-container="#opu_001_0004_006ErrorContainer" data-parsley-trigger="change" required>
                                <span>Да</span>
                            </label>
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0004_006" value="0" type="radio" data-parsley-trigger="change" required>
                                <span>Нет</span>
                            </label>
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0004_006" value="1" type="radio" data-parsley-trigger="change" required>
                                <span>Не знаю</span>
                            </label>
                        </div>
                    </div>
                    <div id="opu_001_0004_006ErrorContainer"></div>

                    <div id="opu_001_0004_007" class="form-control">
                        <span>Если при инъекции использовать стерильные индивидуальные инструменты и материалы для приготовления раствора наркотика</span>
                        <div class="float-right">
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0004_007" value="2" type="radio" data-parsley-class-handler="#opu_001_0004_007" data-parsley-errors-container="#opu_001_0004_007ErrorContainer" data-parsley-trigger="change" required>
                                <span>Да</span>
                            </label>
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0004_007" value="0" type="radio" data-parsley-trigger="change" required>
                                <span>Нет</span>
                            </label>
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0004_007" value="1" type="radio" data-parsley-trigger="change" required>
                                <span>Не знаю</span>
                            </label>
                        </div>
                    </div>
                    <div id="opu_001_0004_007ErrorContainer"></div>

                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <div class="form-group mg-b-10-force pd-t-10">
                    <label class="form-control-label">5. Как Вам кажется, увеличивается ли риск заразиться ВИЧ-инфекцией
                        при: <span
                                class="tx-danger">*</span></label>
                    <div id="opu_001_0005_001" class="form-control">
                        <span>Половом контакте с использованием презерватива</span>
                        <div class="float-right">
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0005_001" value="2" type="radio" data-parsley-class-handler="#opu_001_0005_001" data-parsley-errors-container="#opu_001_0005_001ErrorContainer" data-parsley-trigger="change" required>
                                <span>Да</span>
                            </label>
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0005_001" value="0" type="radio" data-parsley-trigger="change" required>
                                <span>Нет</span>
                            </label>
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0005_001" value="1" type="radio" data-parsley-trigger="change" required>
                                <span>Не знаю</span>
                            </label>
                        </div>
                    </div>
                    <div id="opu_001_0005_001ErrorContainer"></div>

                    <div id="opu_001_0005_002" class="form-control">
                        <span>Половом контакте без презерватива</span>
                        <div class="float-right">
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0005_002" value="2" type="radio" data-parsley-class-handler="#opu_001_0005_002" data-parsley-errors-container="#opu_001_0005_002ErrorContainer" data-parsley-trigger="change" required>
                                <span>Да</span>
                            </label>
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0005_002" value="0" type="radio" data-parsley-trigger="change" required>
                                <span>Нет</span>
                            </label>
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0005_002" value="1" type="radio" data-parsley-trigger="change" required>
                                <span>Не знаю</span>
                            </label>
                        </div>
                    </div>
                    <div id="opu_001_0005_002ErrorContainer"></div>

                    <div id="opu_001_0005_003" class="form-control">
                        <span>Беспорядочных половых контактах</span>
                        <div class="float-right">
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0005_003" value="2" type="radio" data-parsley-class-handler="#opu_001_0005_003" data-parsley-errors-container="#opu_001_0005_003ErrorContainer" data-parsley-trigger="change" required>
                                <span>Да</span>
                            </label>
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0005_003" value="0" type="radio" data-parsley-trigger="change" required>
                                <span>Нет</span>
                            </label>
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0005_003" value="1" type="radio" data-parsley-trigger="change" required>
                                <span>Не знаю</span>
                            </label>
                        </div>
                    </div>
                    <div id="opu_001_0005_003ErrorContainer"></div>

                    <div id="opu_001_0005_004" class="form-control">
                        <span>Общении с больным СПИДом</span>
                        <div class="float-right">
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0005_004" value="2" type="radio" data-parsley-class-handler="#opu_001_0005_004" data-parsley-errors-container="#opu_001_0005_004ErrorContainer" data-parsley-trigger="change" required>
                                <span>Да</span>
                            </label>
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0005_004" value="0" type="radio" data-parsley-trigger="change" required>
                                <span>Нет</span>
                            </label>
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0005_004" value="1" type="radio" data-parsley-trigger="change" required>
                                <span>Не знаю</span>
                            </label>
                        </div>
                    </div>
                    <div id="opu_001_0005_004ErrorContainer"></div>

                    <div id="opu_001_0005_005" class="form-control">
                        <span>Использовании общего инструментария при инъекциях</span>
                        <div class="float-right">
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0005_005" value="2" type="radio" data-parsley-class-handler="#opu_001_0005_005" data-parsley-errors-container="#opu_001_0005_005ErrorContainer" data-parsley-trigger="change" required>
                                <span>Да</span>
                            </label>
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0005_005" value="0" type="radio" data-parsley-trigger="change" required>
                                <span>Нет</span>
                            </label>
                            <label class="rdiobox rdiobox-inline mg-l-10">
                                <input name="opu_001_0005_005" value="1" type="radio" data-parsley-trigger="change" required>
                                <span>Не знаю</span>
                            </label>
                        </div>
                    </div>
                    <div id="opu_001_0005_005ErrorContainer"></div>

                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <div class="form-group mg-b-10-force sel2">
                    <label for="drug" class="form-control-label">6. Какой наркотик Вы употребляете? <span
                                class="tx-danger">*</span></label>
                    <select id="drug" class="form-control select2" data-placeholder="Выберите наркотик"
                            data-parsley-trigger="change" name="drug" data-parsley-trigger="change" required>
                        <option label="Выберите наркотик"></option>
                        @foreach($drugs as $drug)
                            <option value="{{ $drug->id }}">{{ $drug->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div><!-- col-12 -->

            <div class="col-8">
                <span>7. Сколько раз в месяц Вы ... с аутрич-сотрудником?</span>
            </div><!-- col-6 -->

            <div class="col-2">
                <div class="form-group form-inline float-right">
                    <label for="meetings_0" class="form-control-label wd-100p">встречаетесь</label>
                    <input id="meetings_0" class="form-control wd-100p-force" type="number" min="0" name="meetings_0"
                           data-parsley-trigger="change" required>
                </div>
            </div><!-- col-3 -->

            <div class="col-2">
                <div class="form-group form-inline float-right">
                    <label for="meetings_1" class="form-control-label wd-100p">хотели бы встречаться </label>
                    <input id="meetings_1" class="form-control wd-100p-force" type="number" min="0" name="meetings_1"
                           data-parsley-trigger="change" required>
                </div>
            </div><!-- col-3 -->

            <div class="col-lg-12">
                <label class="form-control-label">8. Заполните таблицу в соответствии строк и колонок: <span
                            class="tx-danger">*</span></label>
                <table class="table table-bordered table-responsive table-hover table-primary">
                    <col width="10%">
                    <col width="10%">
                    <col width="10%">
                    <col width="35%">
                    <col width="35%">
                    <thead>
                    <tr>
                        <td>
                            Инструментарии
                        </td>
                        <td>
                            Сколько Вы получаете в месяц?
                        </td>
                        <td>
                            Сколько Вы бы хотели получать в месяц?
                        </td>
                        <td>
                            Если Вам не нравится какой - либо инструментарий, опишите недостатки
                        </td>
                        <td>
                            Напишите, где Вы берете инструментарий, если Вам его не хватает
                        </td>
                    </tr>
                    </thead>
                    <tbody>
		    <tr valign="middle">
                        <td>
                            Всего шприцев
                        </td>
                        <td>
                            <input type="number" min="0" name="SyringesGet" class="form-control" data-parsley-trigger="change" required>
                        </td>
                        <td>
                            <input type="number" min="0" name="SyringesWant" class="form-control" data-parsley-trigger="change" required>
                        </td>
                        <td>
                            <textarea class="form-control" name="SyringesNotLike" data-parsley-trigger="change" required></textarea>
                        </td>
                    </tr>
                    <tr valign="middle">
                        <td>
                            2 мг шприцы
                        </td>
                        <td>
                            <input type="number" min="0" name="Syringes2Get" class="form-control" data-parsley-trigger="change" required>
                        </td>
                        <td>
                            <input type="number" min="0" name="Syringes2Want" class="form-control" data-parsley-trigger="change" required>
                        </td>
                        <td>
                            <textarea class="form-control" name="Syringes2NotLike" data-parsley-trigger="change" required></textarea>
                        </td>
                        <td>
                            <textarea class="form-control" name="Syringes2Take" data-parsley-trigger="change" required></textarea>
                        </td>
                    </tr>
                    <tr valign="middle">
                        <td>
                            5 мг шприцы
                        </td>
                        <td>
                            <input type="number" min="0" name="Syringes5Get" class="form-control" data-parsley-trigger="change" required>
                        </td>
                        <td>
                            <input type="number" min="0" name="Syringes5Want" class="form-control" data-parsley-trigger="change" required>
                        </td>
                        <td>
                            <textarea class="form-control" name="Syringes5NotLike" data-parsley-trigger="change" required></textarea>
                        </td>
                        <td>
                            <textarea class="form-control" name="Syringes5Take" data-parsley-trigger="change" required></textarea>
                        </td>
                    </tr>
                    <tr valign="middle">
                        <td>
                            10 мг шприцы
                        </td>
                        <td>
                            <input type="number" min="0" name="Syringes10Get" class="form-control" data-parsley-trigger="change" required>
                        </td>
                        <td>
                            <input type="number" min="0" name="Syringes10Want" class="form-control" data-parsley-trigger="change" required>
                        </td>
                        <td>
                            <textarea class="form-control" name="Syringes10NotLike" data-parsley-trigger="change" required></textarea>
                        </td>
                        <td>
                            <textarea class="form-control" name="Syringes10Take" data-parsley-trigger="change" required></textarea>
                        </td>
                    </tr>
                    <tr valign="middle">
                        <td>
                            Спиртовые салфетки
                        </td>
                        <td>
                            <input type="number" min="0" name="DoilyGet" class="form-control" data-parsley-trigger="change" required>
                        </td>
                        <td>
                            <input type="number" min="0" name="DoilyWant" class="form-control" data-parsley-trigger="change" required>
                        </td>
                        <td>
                            <textarea class="form-control" name="DoilyNotLike" data-parsley-trigger="change" required></textarea>
                        </td>
                        <td>
                            <textarea class="form-control" name="DoilyTake" data-parsley-trigger="change" required></textarea>
                        </td>
                    </tr>
                    <tr valign="middle">
                        <td>
                            Мужские средства защиты
                        </td>
                        <td>
                            <input type="number" min="0" name="CondomsMGet" class="form-control" data-parsley-trigger="change" required>
                        </td>
                        <td>
                            <input type="number" min="0" name="CondomsMWant" class="form-control" data-parsley-trigger="change" required>
                        </td>
                        <td>
                            <textarea class="form-control" name="CondomsMNotLike" data-parsley-trigger="change" required></textarea>
                        </td>
                        <td>
                            <textarea class="form-control" name="CondomsMTake" data-parsley-trigger="change" required></textarea>
                        </td>
                    </tr>
                    <tr valign="middle">
                        <td>
                            Женские средства защиты
                        </td>
                        <td>
                            <input type="number" min="0" name="CondomsWGet" class="form-control" data-parsley-trigger="change" required>
                        </td>
                        <td>
                            <input type="number" min="0" name="CondomsWWant" class="form-control" data-parsley-trigger="change" required>
                        </td>
                        <td>
                            <textarea class="form-control" name="CondomsWNotLike" data-parsley-trigger="change" required></textarea>
                        </td>
                        <td>
                            <textarea class="form-control" name="CondomsWTake" data-parsley-trigger="change" required></textarea>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <label class="form-control-label">9. Заполните таблицу в соответствии строк и колонок: <span
                            class="tx-danger">*</span></label>
                <table class="table table-bordered table-responsive table-hover table-primary">
                    <col width="40%">
                    <col width="30%">
                    <col width="30%">
                    <thead>
                    <tr>
                        <td>

                        </td>
                        <td>
                            тестирование на ВИЧ
                        </td>
                        <td>
                            флюорография легких
                        </td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr valign="middle">
                        <td>
                            Проходили ли Вы процедуру тестирования в текущем году
                        </td>
                        <td>
                            <select class="form-control select2" data-placeholder="Выберите верное утверждение"
                                    data-parsley-trigger="change" name="PassHiv" data-parsley-trigger="change" required>
                                <option label="Выберите верное утверждение"></option>
                                <option value="1">Да</option>
                                <option value="0">Нет</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-control select2" data-placeholder="Выберите верное утверждение"
                                    data-parsley-trigger="change" name="PassFluorography" data-parsley-trigger="change" required>
                                <option label="Выберите верное утверждение"></option>
                                <option value="1">Да</option>
                                <option value="0">Нет</option>
                            </select>
                        </td>
                    </tr>
                    <tr valign="middle">
                        <td>
                            Когда последний раз Вы проходили процедуру
                        </td>
                        <td>
                            <label class="ckbox">
                                <input type="checkbox" class="date_hiv"><span>Ни когда</span>
                            </label>
                            <input id="date_hiv" name="date_hiv" type="text" class="form-control date" data-parsley-pattern="^[0-9]{4}[-]{1}[0-9]{2}[-]{1}[0-9]{2}$" data-parsley-trigger="change" required>
                        </td>
                        <td>
                            <label class="ckbox">
                                <input type="checkbox" class="date_fluorography"><span>Ни когда</span>
                            </label>
                            <input id="date_fluorography" name="date_fluorography" type="text" class="form-control date"
                                   data-parsley-pattern="^[0-9]{4}[-]{1}[0-9]{2}[-]{1}[0-9]{2}$" data-parsley-trigger="change" required>
                        </td>
                    </tr>
                    <tr valign="middle">
                        <td>
                            Предлагали ли Вам аутрич-сотрудники пройти процедуру
                        </td>
                        <td>
                            <div class="form-group mg-b-0-force sel2">
                                <select class="form-control select2" data-placeholder="Выберите верное утверждение"
                                        data-parsley-trigger="change" name="OfferHiv" data-parsley-trigger="change" required>
                                    <option label="Выберите верное утверждение"></option>
                                    <option value="1">Да</option>
                                    <option value="0">Нет</option>
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class="form-group mg-b-0-force sel2">
                                <select class="form-control select2" data-placeholder="Выберите верное утверждение"
                                        data-parsley-trigger="change" name="OfferFluorography" data-parsley-trigger="change" required>
                                    <option label="Выберите верное утверждение"></option>
                                    <option value="1">Да</option>
                                    <option value="0">Нет</option>
                                </select>
                            </div>
                        </td>
                    </tr>
                    <tr valign="middle">
                        <td>
                            Сопровождали ли Вас аутрич-сотрудники на обследование?
                        </td>
                        <td>
                            <div class="form-group mg-b-0-force sel2">
                                <select class="form-control select2" data-placeholder="Выберите верное утверждение"
                                        data-parsley-trigger="change" name="EscortHiv" data-parsley-trigger="change" required>
                                    <option label="Выберите верное утверждение"></option>
                                    <option value="1">Да</option>
                                    <option value="0">Нет</option>
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class="form-group mg-b-0-force sel2">
                                <select class="form-control select2" data-placeholder="Выберите верное утверждение"
                                        data-parsley-trigger="change" name="EscortFluorography" data-parsley-trigger="change" required>
                                    <option label="Выберите верное утверждение"></option>
                                    <option value="1">Да</option>
                                    <option value="0">Нет</option>
                                </select>
                            </div>
                        </td>
                    </tr>
                    <tr valign="middle">
                        <td>
                            Опишите недостатки процедуры
                        </td>
                        <td>
                            <textarea class="form-control" name="LimitationsHiv" data-parsley-trigger="change" required></textarea>
                        </td>
                        <td>
                            <textarea class="form-control" name="LimitationsFluorography" data-parsley-trigger="change" required></textarea>
                        </td>
                    </tr>
                    <tr valign="middle">
                        <td>
                            Болеете ли Вы в настоящее время туберкулезом?
                        </td>
                        <td colspan="2">
                            <div class="form-group mg-b-0-force sel2">
                                <select class="form-control select2" data-placeholder="Выберите верное утверждение"
                                        data-parsley-trigger="change" name="TbStatus" data-parsley-trigger="change" required>
                                    <option label="Выберите верное утверждение"></option>
                                    <option value="2">Да</option>
                                    <option value="1">Нет</option>
                                    <option value="0">Не знаю</option>
                                </select>
                            </div>
                        </td>
                    </tr>
                    <tr valign="middle">
                        <td>
                            Получаете ли лечение от туберкулеза?
                        </td>
                        <td colspan="2">
                            <div class="form-group mg-b-0-force sel2">
                                <select class="form-control select2" data-placeholder="Выберите верное утверждение"
                                        data-parsley-trigger="change" name="TbDoc" data-parsley-trigger="change" required>
                                    <option label="Выберите верное утверждение"></option>
                                    <option value="2">Да</option>
                                    <option value="1">Нет</option>
                                </select>
                            </div>
                        </td>
                    </tr>
                    <tr valign="middle">
                        <td>
                            Предлагали ли Вам аутрич-сотрудники пройти скрининг анкетирование по вопросам туберкулеза?
                        </td>
                        <td colspan="2">
                            <div class="form-group mg-b-0-force sel2">
                                <select onchange="if($(this).val() == 1){$('#hidden-question').hide()} else {$('#hidden-question').show()}" class="form-control select2" data-placeholder="Выберите верное утверждение"
                                        data-parsley-trigger="change" name="TbOut" data-parsley-trigger="change" required>
                                    <option label="Выберите верное утверждение"></option>
                                    <option value="2">Да</option>
                                    <option value="1">Нет. Перейти к 10 вопросу</option>
                                </select>
                            </div>
                        </td>
                    </tr>
                    <tr valign="middle" id="hidden-question">
                        <td>
                            Если да, выявился ли у Вас риск наличия туберкулеза?
                        </td>
                        <td colspan="2">
                            <div class="form-group mg-b-0-force sel2">
                                <select class="form-control select2" data-placeholder="Выберите верное утверждение"
                                        data-parsley-trigger="change" name="TbRisk" data-parsley-trigger="change">
                                    <option label="Выберите верное утверждение"></option>
                                    <option value="2">Да</option>
                                    <option value="1">Нет</option>
                                    <option value="0">Не знаю</option>
                                </select>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <div id="RegistrationHiv" class="form-control mg-b-10-force">
                    <span>10. Состоите ли Вы на учете в Центре по борьбе со СПИДом? <span
                                class="tx-danger">*</span></span>
                    <div class="float-right">
                        <label class="rdiobox rdiobox-inline mg-l-10">
                            <input name="RegistrationHiv" value="1" type="radio" data-parsley-class-handler="#RegistrationHiv" data-parsley-errors-container="#RegistrationHivErrorContainer" data-parsley-trigger="change" required>
                            <span>Да</span>
                        </label>
                        <label class="rdiobox rdiobox-inline mg-l-10">
                            <input name="RegistrationHiv" value="0" type="radio" data-parsley-trigger="change" required>
                            <span>Нет</span>
                        </label>
                    </div>
                </div>
                <div id="RegistrationHivErrorContainer"></div>

            </div><!-- col-12 -->

            <div class="col-lg-12">
                <div class="form-group mg-b-10-force">
                    <label class="form-control-label">11. О чем беседует с Вами аутрич-сотрудник? <span
                                class="tx-danger">*</span></label>
                    <textarea name="TalkOutreach" class="form-control" rows="5" data-parsley-trigger="change" required></textarea>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-12">
                <div class="form-group mg-b-10-force">
                    <label class="form-control-label">12. С Вашей точки зрения, в каких услугах Вы еще нуждаетесь? <span
                                class="tx-danger">*</span></label>
                    <textarea name="services" class="form-control" rows="5" data-parsley-trigger="change" required></textarea>
                </div>
            </div><!-- col-12 -->

            <div class="form-layout-footer col-lg-12">
                <button class="btn btn-success btn-block">Сохранить</button>
            </div><!-- form-layout-footer -->
        </div><!-- row -->
    </form><!-- form-layout -->
@endsection
