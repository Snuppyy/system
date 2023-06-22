@extends('layouts.app')

@section('styles')
    <link href="{{ asset('lib/select2/dist/css/select2.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('lib/datetimepicker/daterangepicker.css') }}"/>
    <link href="{{ asset('lib/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet">
@endsection

@section('scripts')
    <script src="{{ asset('lib/datetimepicker/jquery.datetimepicker.full.js') }}"></script>
    <script src="{{ asset('lib/mask/jquery.mask.min.js') }}"></script>
    {{--    <script type="text/javascript" src="{{ asset('lib/moment/moment.js') }}"></script>--}}
    <script type="text/javascript" src="{{ asset('lib/moment/locale/ru.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/moment/moment-timezone.js') }}"></script>
@endsection


@section('header')
    <div class="sh-breadcrumb">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{ route('index') }}">INTILISH v3.1</a>
            <a class="breadcrumb-item" href="{{ route('index') }}">Главная</a>
            <span class="breadcrumb-item active">Программный отчет</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <div class="sh-pagetitle">

        <div class="input-group">
            <!-- search block -->
        </div><!-- input-group -->

        <div class="sh-pagetitle-left">
            <div class="sh-pagetitle-icon"><i class="icon ion-stats-bars"></i></div>
            <div class="sh-pagetitle-title">
                <h2>Программный отчет</h2>
            </div><!-- sh-pagetitle-left-title -->
        </div><!-- sh-pagetitle-left -->

    </div><!-- sh-pagetitle -->
@endsection

@section('content')
    @php
        $page = explode('/',url()->current());
    @endphp
    <div class="btn-group wd-100p">
        <a href="{{ route('programReport', ['start' => '2018-11-01', 'end' => '2018-12-31']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '2018-12-31' ? 'active' : '' }}">4 квартал 2018</a>
        <a href="{{ route('programReport', ['start' => '2019-01-01', 'end' => '2019-03-31']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '2019-03-31' ? 'active' : '' }}">1 квартал</a>
        <a href="{{ route('programReport', ['start' => '2019-04-01', 'end' => '2019-06-30']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '2019-06-30' ? 'active' : '' }}">2 квартал</a>
        <a href="{{ route('programReport', ['start' => '2019-01-01', 'end' => '2019-06-30']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '2019-06-30' ? 'active' : '' }}">1 полугодие</a>
        <a href="{{ route('programReport', ['start' => '2019-07-01', 'end' => '2019-09-30']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '2019-09-30' ? 'active' : '' }}">3 квартал</a>
        <a href="{{ route('programReport', ['start' => '2019-10-01', 'end' => '2019-12-31']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '2019-12-31' ? 'active' : '' }}">4 квартал</a>
        <a href="{{ route('programReport', ['start' => '2019-07-01', 'end' => '2019-12-31']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '2019-12-31' ? 'active' : '' }}">2 полугодие</a>
        <a href="{{ route('programReport', ['start' => '2019-01-01', 'end' => '2019-12-31']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '2019-12-31' ? 'active' : '' }}">2019 год</a>
    </div>


    <table class="table table-bordered table-hover">
        <thead>
        <th>Индикаторы</th>
        <th>Результат</th>
        </thead>

        <tbody>
        <tr>
            <td>Количество охваченных информационно-образовательными мероприятиями аутрич-сотрудников и волонтеров</td>
            <td>{{ $countAnswersOutreach }}/{{ $countAnswersVolunteer }}</td>
        </tr>
        <tr>
            <td>Количество промониторированных возможных мест продажи профилактических средст</td>
            <td>{{ $countVisitions }}</td>
        </tr>
        <tr>
            <td>Количество проведенных вэбинаров/очных обучающих мероприятий для аутрич-сотрудников и волонтеров</td>
            <td>{{ $countWebinar }}/{{ $countSeminar }}</td>
        </tr>
        <tr>
            <td>Количество аутрич-сотрудников, которые предоставили доступ для мониторинга своих сетей</td>
            <td>{{ $countOutreaches }}</td>
        </tr>
        <tr>
            <td>Количество проведенных анкетирований бенефициаров субпроекта по оценке качества услуг и уровня
                удовлетворенности
            </td>
            <td>{{ $countOpu }}</td>
        </tr>
        </tbody>

        <tfoot>
        <th>Индикаторы</th>
        <th>Результат</th>
        </tfoot>
    </table>
@endsection
