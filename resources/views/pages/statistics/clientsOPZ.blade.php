@extends('layouts.app')

@php
    $page = explode('/',url()->current());
@endphp

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('lib/datetimepicker/daterangepicker.css') }}"/>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('lib/moment/moment.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/moment/locale/ru.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/datetimepicker/daterangepicker.js') }}"></script>
@endsection

@section('scriptsFooter')
    <script type="text/javascript">
        $(function () {
                    @if(isset($_GET['startDate']) && isset($_GET['endDate']))
            var start = moment("{{ $_GET['startDate'] }}");
            var end = moment("{{ $_GET['endDate'] }}");
            cb(start, end);

            @endif


            function cb(start, end) {
                $('#filterDate span').html(start.format('YYYY MMMM DD') + ' - ' + end.format('YYYY MMMM DD'));
            }

            $('#filterDate').daterangepicker({
                @if(isset($_GET['startDate']) && isset($_GET['startDate']))
                startDate: start,
                endDate: end,
                @endif
                opens: 'center',
                locale: {
                    "format": "YYYY-MM-DD",
                    "separator": " - ",
                    "applyLabel": "Принять",
                    "cancelLabel": "Отменить",
                    "fromLabel": "От",
                    "toLabel": "До",
                    "customRangeLabel": "Период",
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
                },
                ranges: {
                    "Сегодня": [moment(), moment()],
                    "Вчера": [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    "Последние 7 дней": [moment().subtract(6, 'days'), moment()],
                    "Последние 30 дней": [moment().subtract(29, 'days'), moment()],
                    "Этот месяц": [moment().startOf('month'), moment().endOf('month')],
                    "Предыдущий месяц": [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);

            $('#filterDate').on('apply.daterangepicker', function (ev, picker) {
                window.location.href = "{{ route('StatisticsClientsOPZ', ['region' => $page[7]]) }}?startDate=" + picker.startDate.format('YYYY-MM-DD') + "&endDate=" + picker.endDate.format('YYYY-MM-DD');
            });
        });
    </script>
@endsection

@section('header')
    <div class="sh-breadcrumb">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{ route('index') }}">INTILISH v3.1</a>
            <span class="breadcrumb-item">Сводка</span>
            <span class="breadcrumb-item active">Клиенты</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <div class="sh-pagetitle">

        <div class="input-group">
            <!-- search block -->
        </div><!-- input-group -->

        <div class="sh-pagetitle-left">
            <div class="sh-pagetitle-icon"><i class="icon ion-ios-help-outline"></i></div>
            <div class="sh-pagetitle-title">
                <h2>Результаты оценки знаний по ТБ</h2>
            </div><!-- sh-pagetitle-left-title -->
        </div><!-- sh-pagetitle-left -->

    </div><!-- sh-pagetitle -->
@endsection

@section('content')
    <div class="col-12">
        @if(Auth::user()->region->id === 0)
            <div class="pd-10 bd mg-b-20">
                <ul class="nav nav-pills flex-column flex-md-row justify-content-center" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link{{ $page[7] === 'all' ? ' active' : '' }}"
                           @if(isset($_GET['startDate']) && isset($_GET['endDate']))
                           href="{{ route('StatisticsClientsOPZ', ['region' => 'all', 'startDate' => $_GET['startDate'], 'endDate' => $_GET['endDate']]) }}"
                           @else
                           href="{{ route('StatisticsClientsOPZ', ['region' => 'all']) }}"
                           @endif
                           role="tab">Все</a>
                    </li>
                    @foreach($regions as $region)
                        @if($region->id !== 0)
                            <li class="nav-item">
                                <a class="nav-link{{ $page[7] == $region->encoding ? ' active' : '' }}"
                                   @if(isset($_GET['startDate']) && isset($_GET['endDate']))
                                   href="{{ route('StatisticsClientsOPZ', ['region' => $region->encoding, 'startDate' => $_GET['startDate'], 'endDate' => $_GET['endDate']]) }}"
                                   @else
                                   href="{{ route('StatisticsClientsOPZ', $region->encoding) }}"
                                   @endif
                                   role="tab">{{ $region->encoding }}</a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
    <div class="col-lg-12 alert alert-success wd-100p-force text-center">
        @if($page[7] == '03-BU')
            Всего анкет: 1416<br>
            Всего ПРЕ анкет: 708 / 36.8%<br>
            Всего ПОСТ анкет: 708 / 55.5%<br>
        @elseif($page[7] == '11-TV')
            Всего анкет: 516<br>
            Всего ПРЕ анкет: 258 / 24.9%<br>
            Всего ПОСТ анкет: 258 / 44.9%<br>
        @elseif($page[7] == '01-TA')
            Всего анкет: 462<br>
            Всего ПРЕ анкет: 231 / 48.9%<br>
            Всего ПОСТ анкет: 231 / 67.8%<br>
        @else
            Всего анкет: 2394‬<br>
            Всего ПРЕ анкет: 1197 / 36.9%<br>
            Всего ПОСТ анкет: 1197 / 56.1%<br>
        @endif
    </div>
    <div class="text-center card card-body tx-white-8 bg-info bd-0"
         style="display: block; flex-direction: row; cursor: pointer;" id="filterDate">
        <i class="fa fa-calendar"></i>&nbsp;
        <span>Укажите период</span> <i class="fa fa-caret-down"></i>
    </div>
    <a
            class="btn btn-primary btn-block mg-b-10"
            href="{{ route('StatisticsClientsOPZ', ['region' => $page[7]]) }}"
    >
        Сбросить
    </a>
    <table class="table table-hover table-bordered table-primary">
        <thead>
        <th>регион</th>
        <th>Дата</th>
        <th>Количество участников</th>
        <th>ПРЕ</th>
        <th>ПОСТ</th>
        </thead>
        <tbody>
        @php
            unset($data['count']);
            unset($data[1]);
            unset($data[2]);
        @endphp
        @php($i = 1)
        @foreach($data as $date => $DRegions)
            @foreach($DRegions as $region => $datum)
                <tr>
                    <td>{{ $regions->find($region)->encoding }}</td>
                    <td>{{ $date }}</td>
                    <td>{{ max($datum[1]['clients'], $datum[2]['clients']) }}</td>
                    <td>{{ $datum[1] ? round((($datum[1]['data'] / $datum[1]['count'])*100), 1) : '0' }}%</td>
                    <td>{{ $datum[2]['percent'] }}%</td>
                </tr>
            @endforeach
        @endforeach
        </tbody>
        <tfoot>
        <th>регион</th>
        <th>Дата</th>
        <th>Количество участников</th>
        <th>ПРЕ</th>
        <th>ПОСТ</th>
        </tfoot>
    </table>
@endsection
