@extends('layouts.app')

@section('styles')
    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet">

    <style>
        .unselectable {
            -webkit-touch-callout: none; /* iOS Safari */
            -webkit-user-select: none; /* Chrome/Safari/Opera */
            -khtml-user-select: none; /* Konqueror */
            -moz-user-select: none; /* Firefox */
            -ms-user-select: none; /* Internet Explorer/Edge */
            user-select: none;
            /* Non-prefixed version, currently
                                   not supported by any browser */
        }

        .edited {
            background: #c9c9c9;
        }
    </style>
@endsection

@section('scripts')
    <script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('lib/datetimepicker/jquery.datetimepicker.full.js') }}"></script>
    <script src="{{ asset('lib/mask/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('lib/parsleyjs/parsley.js') }}"></script>
@endsection

@section('scriptsFooter')
    <script>



    </script>
@endsection

@section('header')

@endsection

@section('content')
    <button id="save" class="btn btn-success fixed-top btn-block" style="left: 240px; top: 100px; padding-right: 240px">Сохранить</button>
    <table class="table table-primary table-hover table-bordered unselectable pd-t-20">
        <thead>
        <tr>
            <th>№</th>
            <th>Сотрудник</th>
            <th>Функциональная обязанность</th>
            <th>Комментарий</th>
            <th>Дата</th>
            <th>Начало</th>
            <th>Конец</th>
            <th>Выработка</th>
            <th>Управление</th>
        </tr>
        </thead>
        <tbody>
        <tr id="activity-17528">
            <td class="id">17528</td>
            <td class="user" data-user="9">Данилов Владислав</td>
            <td class="select" data-assignment="7">Осуществляет регистрацию собственной деятельности в электронной
                информационной системе
                организации
            </td>
            <td class="textarea">Ввод данных в ЭИС</td>
            <td class="date" nowrap="">2019-08-31</td>
            <td class="start" nowrap="">17:00:00</td>
            <td class="end" nowrap="">17:15:00</td>
            <td class="diff" nowrap="">00:15:00</td>
            <td nowrap="">
                <a href="javascript:void(0)"
                   class="fa fa-copy text-info copy"></a>
                <a href="https://system.intilish.uz/user/profile/17528/activity/supervision"
                   class="fa fa-calendar-check text-success"></a>
            </td>
        </tr>
        <tr id="activity-17528">
            <td class="id">17528</td>
            <td class="user" data-user="9">Данилов Владислав</td>
            <td class="select" data-assignment="7">Осуществляет регистрацию собственной деятельности в электронной
                информационной системе
                организации
            </td>
            <td class="textarea">Ввод данных в ЭИС</td>
            <td class="date" nowrap="">2019-08-31</td>
            <td class="start" nowrap="">17:00:00</td>
            <td class="end" nowrap="">17:15:00</td>
            <td class="diff" nowrap="">00:15:00</td>
            <td nowrap="">
                <a href="javascript:void(0)"
                   class="fa fa-copy text-info copy"></a>
                <a href="https://system.intilish.uz/user/profile/17528/activity/supervision"
                   class="fa fa-calendar-check text-success"></a>
            </td>
        </tr>
        <tr id="activity-17528">
            <td class="id">17528</td>
            <td class="user" data-user="9">Данилов Владислав</td>
            <td class="select" data-assignment="7">Осуществляет регистрацию собственной деятельности в электронной
                информационной системе
                организации
            </td>
            <td class="textarea">Ввод данных в ЭИС</td>
            <td class="date" nowrap="">2019-08-31</td>
            <td class="start" nowrap="">17:00:00</td>
            <td class="end" nowrap="">17:15:00</td>
            <td class="diff" nowrap="">00:15:00</td>
            <td nowrap="">
                <a href="javascript:void(0)"
                   class="fa fa-copy text-info copy"></a>
                <a href="https://system.intilish.uz/user/profile/17528/activity/supervision"
                   class="fa fa-calendar-check text-success"></a>
            </td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
            <th>№</th>
            <th>Сотрудник</th>
            <th>Функциональная обязанность</th>
            <th>Комментарий</th>
            <th>Дата</th>
            <th>Начало</th>
            <th>Конец</th>
            <th>Выработка</th>
            <th>Управление</th>
        </tr>
        </tfoot>
    </table>
@endsection