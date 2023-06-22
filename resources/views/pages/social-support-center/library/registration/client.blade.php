@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('lib/select2/dist/css/select2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('lib/datetimepicker/jquery.datetimepicker.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('lib/jquery.switcher/switcher.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('lib/uploader/dist/css/jquery.dm-uploader.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('lib/datetimepicker/daterangepicker.css') }}"/>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('lib/select2/dist/js/select2.full.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/datetimepicker/jquery.datetimepicker.full.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/mask/jquery.mask.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/parsleyjs/parsley.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/jquery.switcher/switcher.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/uploader/dist/js/jquery.dm-uploader.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/moment/moment.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/moment/locale/ru.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/datetimepicker/daterangepicker.js') }}"></script>
@endsection

@section('scriptsFooter')
    <script>
        $(function () {
            'use strict';

            $('.select2').select2({
                placeholder: 'Выберите из списка'
            });

            $('form.form-layout:not(.select2)').parsley({
                excluded: '.select2'
            });

            $('#datetime').datetimepicker({
                format: 'Y-m-d H:i:s',
                step: 1,
                timepicker: true,
                onShow: function(time ,i) { if ($(i).attr('readonly')) { return false; } }
            });

            $('#birthday').datetimepicker({
                format: 'Y-m-d',
                step: 1,
                mask: '0000-00-00',
            });

            $('#encoding').mask("AAAA000", {
                translation: {
                    'A': {
                        pattern: '^[А-Я]$'
                    }
                },
                placeholder: "AAAA000"
            });

            $('.phone').mask("+998 00 000-00-00", {
                placeholder: "+998 00 000-00-00"
            });

            $('select#place').on('change', function () {
                if ($(this).val() === 'other') {
                    $(this).after('<input class="form-control" name="place" id="tmp-place" required placeholder="Другое..." ">');
                    $(this).select2('destroy');
                    $('select#place').removeAttr('name');
                    $('#tmp-place').focus();
                    $(this).hide();
                    $('#datetime').removeAttr('readonly');
                }
            });

            $('form').on('focusout', '#tmp-place', function () {
                if (!$(this).val()) {
                    $(this).remove();
                    $('select#place').show().attr('name', 'place');
                    $('select#place').val('').select2({
                        placeholder: 'Выберите из списка'
                    });
                    $('#datetime').attr('readonly', 'readonly');
                }
            });

            $('select#came').on('change', function () {
                if ($(this).val() === 'other') {
                    $(this).after('<input class="form-control" name="came" id="tmp-came" required placeholder="Другое..." ">');
                    $(this).select2('destroy');
                    $('select#came').removeAttr('name');
                    $('#tmp-came').focus();
                    $(this).hide();

                }
            });

            $('form').on('focusout', '#tmp-came', function () {
                if (!$(this).val()) {
                    $(this).remove();
                    $('select#came').show().attr('name', 'came');
                    $('select#came').val('').select2({
                        placeholder: 'Выберите из списка'
                    });
                }
            });

            $('select.registration').on('change', function () {
                if($(this).val() === '1') {
                    $(this).siblings('input.registration').removeClass('hidden-xs-up').focus();
                    $(this).select2('destroy');
                    $(this).hide();
                }
            });

            $('form').on('focusout', 'input.registration', function () {
                if (!$(this).val()) {
                    $(this).addClass('hidden-xs-up');
                    var width = $(this).siblings('select.registration').width();
                    $(this).siblings('select.registration').val('').select2({
                        placeholder: 'Выберите из списка',
                        width: width
                    });
                }
            });

            $('.jail-date').daterangepicker({
                defaultViewDate: {year:2010, month:1, day:1},
                opens: 'center',
                autoApply: true,
                showDropdowns: true,
                locale: {
                    "format": "YYYY-MM-DD",
                    "separator": " - ",
                    "daysOfWeek": [
                        "Вс",
                        "Пн",
                        "Вт",
                        "Ср",
                        "Чт",
                        "Пт",
                        "Сб"
                    ],
                    "monthNames": [
                        "Январь",
                        "Февраль",
                        "Март",
                        "Апрель",
                        "Май",
                        "Июнь",
                        "Июль",
                        "Август",
                        "Сентябрь",
                        "Октябрь",
                        "Ноябрь",
                        "Декабрь"
                    ],
                    "firstDay": 1
                }
            });

            $('select#tb').on('change', function () {
                if ($(this).val() === '1') {
                    $(this).parents('.tb-block').addClass('hidden-xs-up');
                    $('.tb-result-block').removeClass('hidden-xs-up');
                    $('.tb-result-block').find('select').val('').select2({
                        placeholder: 'Выберите из списка'
                    });
                }
            });

            $('select#tb-result').on('change', function () {
                if ($(this).val() === '0') {
                    $(this).parents('.tb-result-block').addClass('hidden-xs-up');
                    $('.tb-block').removeClass('hidden-xs-up');
                    $('.tb-block').find('select').val('').select2({
                        placeholder: 'Выберите из списка'
                    });
                }
            });

            $('select#jail').on('change', function () {
                if ($(this).val() === '1') {
                    $(this).parents('.jail-block').addClass('hidden-xs-up');
                    $('.jail-institute-block').removeClass('hidden-xs-up');
                    $('.jail-institute-block').find('select').select2({
                        placeholder: 'Выберите из списка'
                    });
                }
            });
            $('select#jail-institute').on('change', function () {
                $.each($(this).val(), function (key, val) {
                    if(key === 0) {
                        $('.jail-date-block').not(':first').remove();
                        $('.jail-date-block').removeClass('hidden-xs-up').find('span.jail-institute').text(val);
                    } else {
                        console.log($('.jail-date-block:first'));
                        $('.jail-date-block:first').clone().insertAfter('.jail-date-block').find('span.jail-institute').text(val);
                    }
                });
            });
        });
    </script>
@endsection

@section('header')
    <div class="sh-breadcrumb">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{ route('index') }}">INTILISH v3.1</a>
            <span class="breadcrumb-item">ЦСП</span>
            <span class="breadcrumb-item">Библиотека</span>
            <span class="breadcrumb-item active">Клиенты</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <div class="sh-pagetitle">

        <div class="input-group">
            <!-- search block -->
        </div><!-- input-group -->

        <div class="sh-pagetitle-left">
            <div class="sh-pagetitle-icon"><i class="icon ion-ios-bookmarks-outline"></i></div>
            <div class="sh-pagetitle-title">
                <h2>Скрининг - анкета</h2>
            </div><!-- sh-pagetitle-left-title -->
        </div><!-- sh-pagetitle-left -->

    </div><!-- sh-pagetitle -->
@endsection

@section('content')
    <form autocomplete="off" action="{{ route('registration-outreach-set') }}" method="post"
          class="form-layout col-lg-12">
        {{ csrf_field() }}
        <div class="row mg-b-25">
            <div class="col-lg-2">
                <div class="form-group mg-b-10-force">
                    <label for="datetime" class="form-control-label">Дата и время заполнения: <span
                                class="tx-danger">*</span></label>
                    <input id="datetime" class="form-control" type="text" name="datetime"
                           data-parsley-pattern="^[0-9]{4}[-]{1}[0-9]{2}[-]{1}[0-9]{2}[ ]{1}[0-9]{2}[:]{1}[0-9]{2}[:]{1}[0-9]{2}$"
                           data-parsley-trigger="change"
                           value="{{ old('birthday') ?? now() }}" readonly required>
                </div>
            </div><!-- col-2 -->

            <div class="col-lg-2">
                <div class="form-group mg-b-10-force">
                    <label for="place" class="form-control-label">Место проведения: <span
                                class="tx-danger">*</span></label>
                    <select id="place" class="select2 form-control" name="place"
                            data-parsley-trigger="change" required>
                        <option></option>
                        <optgroup label="В ОФИСЕ">
                            @foreach($regions as $region)
                                <option value="{{ $region->id }}">{{ $region->encoding }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="ВНЕ ОФИСА">
                            <option value="other">Другое</option>
                        </optgroup>
                    </select>
                </div>
            </div><!-- col-2 -->

            <div class="col-lg-2">
                <div class="form-group mg-b-10-force">
                    <label for="came" class="form-control-label">Откуда был перенаправлен: <span
                                class="tx-danger">*</span></label>
                    <select id="came" class="select2 form-control" name="came"
                            data-parsley-trigger="change" required>
                        <option></option>
                        <option value="came">Самотек</option>
                        <option value="other">Другое</option>
                    </select>
                </div>
            </div><!-- col-2 -->

            <div class="col-lg-2">
                <div class="form-group mg-b-10-force">
                    <label for="sex" class="form-control-label">Пол: <span
                                class="tx-danger">*</span></label>
                    <select id="sex" class="select2 form-control" name="sex"
                            data-parsley-trigger="change" required>
                        <option></option>
                        <option value="1">Мужской</option>
                        <option value="2">Женский</option>
                    </select>
                </div>
            </div><!-- col-2 -->

            <div class="col-lg-2">
                <div class="form-group mg-b-10-force">
                    <label for="encoding" class="form-control-label">Кодировка: <span
                                class="tx-danger">*</span></label>
                    <input id="encoding" class="form-control" type="text" name="encoding"
                           data-parsley-error-message="Кодировку следует писать с заглавными русскими буквами"
                           data-parsley-pattern="^[А-ЯЁ]{4}[0-9]{3}$" data-parsley-trigger="change"
                           value="{{ old('encoding') }}">
                </div>
            </div><!-- col-2 -->

            <div class="col-lg-2">
                <div class="form-group mg-b-10-force">
                    <label for="birthday" class="form-control-label">Дата рождения: <span
                                class="tx-danger">*</span></label>
                    <input id="birthday" class="form-control" type="text" name="birthday"
                           data-parsley-pattern="^[0-9]{4}[-]{1}[0-9]{2}[-]{1}[0-9]{2}$" data-parsley-trigger="change"
                           value="{{ old('birthday') }}">
                </div>
            </div><!-- col-2 -->

            <div class="col-lg-3">
                <div class="form-group mg-b-10-force">
                    <label for="f_name" class="form-control-label">Имя: <span
                                class="tx-danger">*</span></label>
                    <input id="f_name" class="form-control" type="text" name="f_name" data-parsley-trigger="change"
                           data-parsley-pattern="^[А-ЯЁ][а-яё]+$"
                           data-parsley-error-message="Имя следует указать русскими буквами по паспорту, первая буква должна быть заглавной"
                           value="{{ old('f_name') }}" required>
                </div>
            </div><!-- col-6 -->

            <div class="col-lg-3">
                <div class="form-group mg-b-10-force">
                    <label for="s_name" class="form-control-label">Фамилия: <span
                                class="tx-danger">*</span></label>
                    <input id="s_name" class="form-control" type="text" name="s_name" data-parsley-trigger="change"
                           data-parsley-pattern="^[А-ЯЁ][а-яё]+$"
                           data-parsley-error-message="Фамилию следует указать русскими буквами по паспорту, первая буква должна быть заглавной"
                           value="{{ old('s_name') }}" required>
                </div>
            </div><!-- col-6 -->

            <div class="col-lg-2">
                <div class="form-group mg-b-10-force">
                    <label for="home_phone" class="form-control-label">Домашний номер телефона: <span
                                class="tx-danger">*</span></label>
                    <input id="home_phone" class="form-control phone" type="text" name="phone[0]"
                           data-parsley-error-message="Номер телефона нужно указать в формате +998 00 000-00-00"
                           data-parsley-pattern="^[+998 ][0-9]{2}[ ][0-9]{3}[-][0-9]{2}[-][0-9]{2}$" data-parsley-trigger="change"
                           value="{{ old('phone[0]') }}">
                </div>
            </div><!-- col-2 -->

            <div class="col-lg-2">
                <div class="form-group mg-b-10-force">
                    <label for="mobile_phone" class="form-control-label">Мобильный номер телефона: <span
                                class="tx-danger">*</span></label>
                    <input id="mobile_phone" class="form-control phone" type="text" name="phone[1]"
                           data-parsley-error-message="Номер телефона нужно указать в формате +998 00 000-00-00"
                           data-parsley-pattern="^[+998 ][0-9]{2}[ ][0-9]{3}[-][0-9]{2}[-][0-9]{2}$" data-parsley-trigger="change"
                           value="{{ old('phone[1]') }}">
                </div>
            </div><!-- col-2 -->

            <div class="col-lg-2">
                <div class="form-group mg-b-10-force">
                    <label for="alt_phone" class="form-control-label">Альтернативный номер телефона: <span
                                class="tx-danger">*</span></label>
                    <input id="alt_phone" class="form-control phone" type="text" name="phone[2]"
                           data-parsley-error-message="Номер телефона нужно указать в формате +998 00 000-00-00"
                           data-parsley-pattern="^[+998 ][0-9]{2}[ ][0-9]{3}[-][0-9]{2}[-][0-9]{2}$" data-parsley-trigger="change"
                           value="{{ old('phone[2]') }}">
                </div>
            </div><!-- col-2 -->

            <div class="col-lg-2">
                <div class="form-group mg-b-10-force">
                    <label for="citizenship" class="form-control-label">Гражданство: <span
                                class="tx-danger">*</span></label>
                    <select id="citizenship" class="select2 form-control" name="citizenship"
                            data-parsley-trigger="change" required>
                        <option></option>
                        <option value="Узбекистан">Узбекистан</option>
                    </select>
                </div>
            </div><!-- col-2 -->

            <div class="col-lg-5">
                <div class="form-group mg-b-10-force">
                    <label for="main_registration" class="form-control-label">Имеете ли Вы постоянную прописку: <span
                                class="tx-danger">*</span></label>
                    <select id="main_registration" class="select2 form-control registration"
                            data-parsley-trigger="change" required>
                        <option></option>
                        <option value="1">Да</option>
                        <option value="2">Нет</option>
                    </select>
                    <input type="text" class="form-control hidden-xs-up registration" name="main_registration" placeholder="Укажите город">
                </div>
            </div><!-- col-2 -->

            <div class="col-lg-5">
                <div class="form-group mg-b-10-force">
                    <label for="temp_registration" class="form-control-label">Имеете ли Вы временную прописку: <span
                                class="tx-danger">*</span></label>
                    <select id="temp_registration" class="select2 form-control registration"
                            data-parsley-trigger="change" required>
                        <option></option>
                        <option value="1">Да</option>
                        <option value="2">Нет</option>
                    </select>
                    <input type="text" class="form-control hidden-xs-up registration" name="temp_registration" placeholder="Укажите город">
                </div>
            </div><!-- col-2 -->

            <div class="col-lg-4 tb-block">
                <div class="form-group mg-b-10-force">
                    <label for="tb" class="form-control-label">Проходили ли Вы диагностику на ТБ за последние 3 месяца: <span
                                class="tx-danger">*</span></label>
                    <select id="tb" class="select2 form-control"
                            data-parsley-trigger="change" name="tb" required>
                        <option></option>
                        <option value="1">Да</option>
                        <option value="2">Нет</option>
                    </select>
                </div>
            </div><!-- col-2 -->

            <div class="col-lg-4 hidden-xs-up tb-result-block">
                <div class="form-group mg-b-10-force">
                    <label for="tb-result" class="form-control-label">Какой результат: <span
                                class="tx-danger">*</span></label>
                    <select id="tb-result" class="select2 form-control"
                            data-parsley-trigger="change" name="tb-result" required>
                        <option></option>
                        <option value="0">Отмена</option>
                        <option value="1">Не узнал(а) результат</option>
                        <option value="2">Отрицательный</option>
                        <optgroup label="ЛЧ-ТБ">
                            <option value="3">БК+</option>
                            <option value="4">БК-</option>
                        </optgroup>
                        <optgroup label="МЛУ-ТБ">
                            <option value="5">БК+</option>
                            <option value="6">БК-</option>
                        </optgroup>
                        <optgroup label="ШЛУ-ТБ">
                            <option value="7">БК+</option>
                            <option value="8">БК-</option>
                        </optgroup>
                    </select>
                </div>
            </div><!-- col-2 -->

            <div class="col-lg-4">
                <div class="form-group mg-b-10-force">
                    <label for="hiv" class="form-control-label">Проходили ли Вы диагностику на ВИЧ за последние 3 месяца: <span
                                class="tx-danger">*</span></label>
                    <select id="hiv" class="select2 form-control"
                            data-parsley-trigger="change" name="hiv" required>
                        <option></option>
                        <option value="1">Да</option>
                        <option value="2">Нет</option>
                    </select>
                </div>
            </div><!-- col-2 -->

            <div class="col-lg-4">
                <div class="form-group mg-b-10-force">
                    <label for="hiv-result" class="form-control-label">Состоите ли Вы на учете в центре по борьбе со СПИДом: <span
                                class="tx-danger">*</span></label>
                    <select id="hiv-result" class="select2 form-control"
                            data-parsley-trigger="change" name="hiv-result" required>
                        <option></option>
                        <option value="1">Да</option>
                        <option value="2">Нет</option>
                    </select>
                </div>
            </div><!-- col-2 -->

            <div class="col-lg-12 jail-block">
                <div class="form-group mg-b-10-force">
                    <label for="jail" class="form-control-label">Отбывали ла Вы наказание в местах лишения свободы: <span
                                class="tx-danger">*</span></label>
                    <select id="jail" class="select2 form-control"
                            data-parsley-trigger="change" name="jail" required>
                        <option></option>
                        <option value="1">Да</option>
                        <option value="2">Нет</option>
                    </select>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-3 jail-institute-block hidden-xs-up">
                <div class="form-group mg-b-10-force">
                    <label for="jail-institute" class="form-control-label">Выберите исправительно-трудовые учреждения: <span
                                class="tx-danger">*</span></label>
                    <select id="jail-institute" class="select2 form-control"
                            data-parsley-trigger="change" name="jail-institute[]" multiple required>
                        <option></option>
                        <option value="ИВС">ИВС</option>
                        <option value="СИЗО">СИЗО</option>
                        <option value="КСР">КСР</option>
                        <option value="КОР">КОР</option>
                        <option value="КП">КП</option>
                    </select>
                </div>
            </div><!-- col-12 -->

            <div class="col-lg-2 jail-date-block hidden-xs-up">
                <div class="form-group mg-b-10-force">
                    <label for="jail-date" class="form-control-label">Укажите срок <span class="jail-institute"></span>: <span
                                class="tx-danger">*</span></label>
                    <input id="jail-date" class="form-control jail-date" type="text" name="jail-date[]"
                           data-parsley-pattern="^[0-9]{4}[-][0-9]{2}[-][0-9]{2}[-\s]{3}[0-9]{4}[-][0-9]{2}[-][0-9]{2}$" data-parsley-trigger="change"
                           value="{{ old('birthday') }}">
                </div>
            </div><!-- col-12 -->

            <div class="form-layout-footer col-lg-12">
                <button class="btn btn-success btn-block">Зарегистрировать</button>
            </div><!-- form-layout-footer -->
        </div><!-- row -->
    </form><!-- form-layout -->
@endsection