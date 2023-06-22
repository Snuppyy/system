@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('lib/datetimepicker/daterangepicker.css') }}"/>
    <style>
        .disabled {
            color: currentColor;
            cursor: not-allowed;
            opacity: 0.5;
            text-decoration: none;
        }
    </style>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('lib/moment/moment.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/moment/locale/ru.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/datetimepicker/daterangepicker.js') }}"></script>
    <script>
        $(function () {
                    @if(isset($_GET['startDate']) && isset($_GET['startDate']))
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
                    "1 квартал": [moment().quarter(1).startOf("quarter"), moment().quarter(1).endOf("quarter")],
                    "2 квартал": [moment().quarter(2).startOf("quarter"), moment().quarter(2).endOf("quarter")],
                    "1 полугодие": [moment().quarter(1).startOf("quarter"), moment().quarter(2).endOf("quarter")],
                    "3 квартал": [moment().quarter(3).startOf("quarter"), moment().quarter(3).endOf("quarter")],
                    "4 квартал": [moment().quarter(4).startOf("quarter"), moment().quarter(4).endOf("quarter")],
                    "2 полугодие": [moment().quarter(3).startOf("quarter"), moment().quarter(4).endOf("quarter")],
                    "Год": [moment().quarter(1).startOf("quarter"), moment().quarter(4).endOf("quarter")],
                },
            }, cb);

            $('#filterDate').on('apply.daterangepicker', function (ev, picker) {
                window.location.href = "{{ route('ActivityStatisticsClients', ['project' => $project]) }}?startDate=" + picker.startDate.format('YYYY-MM-DD') + "&endDate=" + picker.endDate.format('YYYY-MM-DD');
            });
        });
    </script>
@endsection


@section('header')
    <div class="sh-breadcrumb">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{ route('index') }}">INTILISH v3.1</a>
            <a class="breadcrumb-item" href="{{ route('index') }}">Главная</a>
            <span class="breadcrumb-item active">Статистика по деятельности</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <div class="sh-pagetitle">

        <div class="input-group">
            <!-- search block -->
        </div><!-- input-group -->

        <div class="sh-pagetitle-left">
            <div class="sh-pagetitle-icon"><i class="icon ion-stats-bars"></i></div>
            <div class="sh-pagetitle-title">
                <h2>Статистика по деятельности</h2>
            </div><!-- sh-pagetitle-left-title -->
        </div><!-- sh-pagetitle-left -->

    </div><!-- sh-pagetitle -->
@endsection

@section('content')
    <div class="btn-group wd-100p">
        <a href="{{ route('ActivityStatisticsClients', ['project' => $project, 'filter' => 'quarter1']) }}"
           class="btn btn-secondary wd-100p {{ $_GET['filter'] === 'quarter1' ? 'active' : '' }}">1 квартал</a>
        <a href="{{ route('ActivityStatisticsClients', ['project' => $project, 'filter' => 'quarter2']) }}"
           class="btn btn-secondary wd-100p {{ $_GET['filter'] === 'quarter2' ? 'active' : '' }}">2 квартал</a>
        <a href="{{ route('ActivityStatisticsClients', ['project' => $project, 'filter' => 'half1']) }}"
           class="btn btn-secondary wd-100p {{ $_GET['filter'] === 'half1' ? 'active' : '' }}">1 полугодие</a>
        <a href="{{ route('ActivityStatisticsClients', ['project' => $project, 'filter' => 'quarter3']) }}"
           class="btn btn-secondary wd-100p {{ $_GET['filter'] === 'quarter3' ? 'active' : '' }}">3 квартал</a>
        <a href="{{ route('ActivityStatisticsClients', ['project' => $project, 'filter' => 'quarter4']) }}"
           class="btn btn-secondary wd-100p {{ $_GET['filter'] === 'quarter4' ? 'active' : '' }}">4 квартал</a>
        <a href="{{ route('ActivityStatisticsClients', ['project' => $project, 'filter' => 'half2']) }}"
           class="btn btn-secondary wd-100p {{ $_GET['filter'] === 'half2' ? 'active' : '' }}">2 полугодие</a>
        <a href="{{ route('ActivityStatisticsClients', ['project' => $project, 'filter' => 'year']) }}"
           class="btn btn-secondary wd-100p {{ $_GET['filter'] === 'year' ? 'active' : '' }}">2019 год</a>
    </div>
    <div class="btn-group wd-100p">
        <a href="{{ route('ActivityStatisticsClients', ['project' => $project, 'filter' => '01']) }}"
           class="btn btn-secondary wd-100p {{ $_GET['filter'] === '01' ? 'active' : '' }}">Январь</a>
        <a href="{{ route('ActivityStatisticsClients', ['project' => $project, 'filter' => '02']) }}"
           class="btn btn-secondary wd-100p {{ $_GET['filter'] === '02' ? 'active' : '' }}">Февраль</a>
        <a href="{{ route('ActivityStatisticsClients', ['project' => $project, 'filter' => '03']) }}"
           class="btn btn-secondary wd-100p {{ $_GET['filter'] === '03' ? 'active' : '' }}">Март</a>
        <a href="{{ route('ActivityStatisticsClients', ['project' => $project, 'filter' => '04']) }}"
           class="btn btn-secondary wd-100p {{ $_GET['filter'] === '04' ? 'active' : '' }}">Апрель</a>
        <a href="{{ route('ActivityStatisticsClients', ['project' => $project, 'filter' => '05']) }}"
           class="btn btn-secondary wd-100p {{ $_GET['filter'] === '05' ? 'active' : '' }}">Май</a>
        <a href="{{ route('ActivityStatisticsClients', ['project' => $project, 'filter' => '06']) }}"
           class="btn btn-secondary wd-100p {{ $_GET['filter'] === '06' ? 'active' : '' }}">Июнь</a>
        <a href="{{ route('ActivityStatisticsClients', ['project' => $project, 'filter' => '07']) }}"
           class="btn btn-secondary wd-100p {{ $_GET['filter'] === '07' ? 'active' : '' }}">Июль</a>
        <a href="{{ route('ActivityStatisticsClients', ['project' => $project, 'filter' => '08']) }}"
           class="btn btn-secondary wd-100p {{ $_GET['filter'] === '08' ? 'active' : '' }}">Август</a>
        <a href="{{ route('ActivityStatisticsClients', ['project' => $project, 'filter' => '09']) }}"
           class="btn btn-secondary wd-100p {{ $_GET['filter'] === '09' ? 'active' : '' }}">Сентябрь</a>
        <a href="{{ route('ActivityStatisticsClients', ['project' => $project, 'filter' => '10']) }}"
           class="btn btn-secondary wd-100p{{ $_GET['filter'] === '10' ? 'active' : '' }}">Октябрь</a>
        <a href="{{ route('ActivityStatisticsClients', ['project' => $project, 'filter' => '11']) }}"
           class="btn btn-secondary wd-100p{{ $_GET['filter'] === '11' ? 'active' : '' }}">Ноябрь</a>
        <a href="{{ route('ActivityStatisticsClients', ['project' => $project, 'filter' => '12']) }}"
           class="btn btn-secondary wd-100p{{ $_GET['filter'] === '12' ? 'active' : '' }}">Декабрь</a>
    </div>

    <h4 class="text-center wd-100p mg-20">Статистика по мероприятиям в КИН</h4>
    <table class="table table-primary table-bordered table-hover">
        <thead class="tx-center-force">
        <tr>
            <th style="vertical-align: middle;" rowspan="2">
                Индикатор
            </th>
            <th colspan="2">
                Общее
            </th>
            <th colspan="2">
                Бухара
            </th>
            <th colspan="2">
                Ташкент
            </th>
            {{--            <th colspan="2">--}}
            {{--                Ташкентский Офис--}}
            {{--            </th>--}}
            <th colspan="2">
                Ташкентская область
            </th>
            {{--            <th colspan="2">--}}
            {{--                Ташкентский областной Офис--}}
            {{--            </th>--}}

            {{--            <th colspan="2">--}}
            {{--                Бухарский Офис--}}
            {{--            </th>--}}
        </tr>
        <tr>
            <th>
                кол-во мероприятий
            </th>
            <th>
                кол-во человек
            </th>
            <th>
                кол-во мероприятий
            </th>
            <th>
                кол-во человек
            </th>
            <th>
                кол-во мероприятий
            </th>
            <th>
                кол-во человек
            </th>
            <th>
                кол-во мероприятий
            </th>
            <th>
                кол-во человек
            </th>
            {{--            <th>--}}
            {{--                кол-во мероприятий--}}
            {{--            </th>--}}
            {{--            <th>--}}
            {{--                кол-во человек--}}
            {{--            </th>--}}
            {{--            <th>--}}
            {{--                кол-во мероприятий--}}
            {{--            </th>--}}
            {{--            <th>--}}
            {{--                кол-во человек--}}
            {{--            </th>--}}
            {{--            <th>--}}
            {{--                кол-во мероприятий--}}
            {{--            </th>--}}
            {{--            <th>--}}
            {{--                кол-во человек--}}
            {{--            </th>--}}
        </tr>
        </thead>

        <tbody>

        @foreach($indicators as $indicator)
            <tr>
                <td>{{ $indicator['name'] }}</td>
                <td>{{ $indicator[$_GET['filter']][2][1]['actions'] + $indicator[$_GET['filter']][1][11]['actions'] + $indicator[$_GET['filter']][3][3]['actions'] }}</td>
                <td>{{ $indicator[$_GET['filter']][2][1]['clients'] + $indicator[$_GET['filter']][1][11]['clients'] + $indicator[$_GET['filter']][3][3]['clients'] }}</td>
                <td>{{ $indicator[$_GET['filter']][3][3]['actions'] }}</td>
                <td>{{ $indicator[$_GET['filter']][3][3]['clients'] }}</td>
                <td>{{ $indicator[$_GET['filter']][2][1]['actions'] }}</td>
                <td>{{ $indicator[$_GET['filter']][2][1]['clients'] }}</td>
                {{--                <td>{{ count($indicator[4][1]['actions']) }}</td>--}}
                {{--                <td>{{ count($indicator[4][1]['clients']) }}</td>--}}
                <td>{{ $indicator[$_GET['filter']][1][11]['actions'] }}</td>
                <td>{{ $indicator[$_GET['filter']][1][11]['clients'] }}</td>
                {{--                <td>{{ count($indicator[4][11]['actions']) }}</td>--}}
                {{--                <td>{{ count($indicator[4][11]['clients']) }}</td>--}}

                {{--                <td>{{ count($indicator[5][3]['actions']) }}</td>--}}
                {{--                <td>{{ count($indicator[5][3]['clients']) }}</td>--}}
            </tr>
        @endforeach
        </tbody>

        <tfoot class="tx-center-force">
        <tr>
            <th style="vertical-align: middle;" rowspan="2">
                Индикатор
            </th>
            <th colspan="2">
                Общее
            </th>
            <th colspan="2">
                Бухара
            </th>
            <th colspan="2">
                Ташкент
            </th>
            {{--            <th colspan="2">--}}
            {{--                Ташкентский Офис--}}
            {{--            </th>--}}
            <th colspan="2">
                Ташкентская область
            </th>
            {{--            <th colspan="2">--}}
            {{--                Ташкентский областной Офис--}}
            {{--            </th>--}}

            {{--            <th colspan="2">--}}
            {{--                Бухарский Офис--}}
            {{--            </th>--}}
        </tr>
        <tr>
            {{--            <th>--}}
            {{--                кол-во мероприятий--}}
            {{--            </th>--}}
            {{--            <th>--}}
            {{--                кол-во человек--}}
            {{--            </th>--}}
            {{--            <th>--}}
            {{--                кол-во мероприятий--}}
            {{--            </th>--}}
            {{--            <th>--}}
            {{--                кол-во человек--}}
            {{--            </th>--}}
            {{--            <th>--}}
            {{--                кол-во мероприятий--}}
            {{--            </th>--}}
            {{--            <th>--}}
            {{--                кол-во человек--}}
            {{--            </th>--}}
            <th>
                кол-во мероприятий
            </th>
            <th>
                кол-во человек
            </th>
            <th>
                кол-во мероприятий
            </th>
            <th>
                кол-во человек
            </th>
            <th>
                кол-во мероприятий
            </th>
            <th>
                кол-во человек
            </th>
            <th>
                кол-во мероприятий
            </th>
            <th>
                кол-во человек
            </th>
        </tr>
        </tfoot>
    </table>

    <h4 class="text-center wd-100p mg-20">Статистика по мероприятиям в офисах</h4>
    <table class="table table-primary table-bordered table-hover">
        <thead class="tx-center-force">
        <tr>
            <th style="vertical-align: middle;" rowspan="2">
                Индикатор
            </th>
            <th colspan="2">
                Общее
            </th>
            <th colspan="2">
                Бухарский Офис
            </th>
            {{--            <th colspan="2">--}}
            {{--                Ташкент--}}
            {{--            </th>--}}
            <th colspan="2">
                Ташкентский Офис
            </th>
            {{--            <th colspan="2">--}}
            {{--                Ташкентская область--}}
            {{--            </th>--}}
            <th colspan="2">
                Ташкентский областной Офис
            </th>
            {{--            <th colspan="2">--}}
            {{--                Бухара--}}
            {{--            </th>--}}
        </tr>
        <tr>
            <th>
                кол-во мероприятий
            </th>
            <th>
                кол-во человек
            </th>
            <th>
                кол-во мероприятий
            </th>
            <th>
                кол-во человек
            </th>
            <th>
                кол-во мероприятий
            </th>
            <th>
                кол-во человек
            </th>
            <th>
                кол-во мероприятий
            </th>
            <th>
                кол-во человек
            </th>
            {{--            <th>--}}
            {{--                кол-во мероприятий--}}
            {{--            </th>--}}
            {{--            <th>--}}
            {{--                кол-во человек--}}
            {{--            </th>--}}
            {{--            <th>--}}
            {{--                кол-во мероприятий--}}
            {{--            </th>--}}
            {{--            <th>--}}
            {{--                кол-во человек--}}
            {{--            </th>--}}
            {{--            <th>--}}
            {{--                кол-во мероприятий--}}
            {{--            </th>--}}
            {{--            <th>--}}
            {{--                кол-во человек--}}
            {{--            </th>--}}
        </tr>
        </thead>

        <tbody>
        {{--        {{ dd($indicators) }}--}}
        @php
            $i=1;
        @endphp
        @foreach($indicators as $indicator)
            @if($i !== 3)
                <tr>
                    <td>{{ $indicator['name_o'] }}</td>
                    <td>{{ $indicator[$_GET['filter']][4][1]['actions'] + $indicator[$_GET['filter']][4][11]['actions'] + $indicator[$_GET['filter']][5][3]['actions'] }}</td>
                    <td>{{ $indicator[$_GET['filter']][4][1]['clients'] + $indicator[$_GET['filter']][4][11]['clients'] + $indicator[$_GET['filter']][5][3]['clients'] }}</td>
                    <td>{{ $indicator[$_GET['filter']][5][3]['actions'] }}</td>
                    <td>{{ $indicator[$_GET['filter']][5][3]['clients'] }}</td>
                    {{--                <td>{{ $indicator[2][1]['actions']) }}</td>--}}
                    {{--                <td>{{ $indicator[2][1]['clients']) }}</td>--}}
                    <td>{{ $indicator[$_GET['filter']][4][1]['actions'] }}</td>
                    <td>{{ $indicator[$_GET['filter']][4][1]['clients'] }}</td>
                    {{--                <td>{{ $indicator[1][11]['actions'] }}</td>--}}
                    {{--                <td>{{ $indicator[1][11]['clients'] }}</td>--}}
                    <td>{{ $indicator[$_GET['filter']][4][11]['actions'] }}</td>
                    <td>{{ $indicator[$_GET['filter']][4][11]['clients'] }}</td>
                    {{--                <td>{{ $indicator[3][3]['actions']) }}</td>--}}
                    {{--                <td>{{ $indicator[3][3]['clients']) }}</td>--}}

                </tr>
            @endif
            @php
                $i++;
            @endphp
        @endforeach
        </tbody>

        <tfoot class="tx-center-force">
        <tr>
            <th style="vertical-align: middle;" rowspan="2">
                Индикатор
            </th>
            <th colspan="2">
                Общее
            </th>
            <th colspan="2">
                Бухарский Офис
            </th>
            {{--            <th colspan="2">--}}
            {{--                Ташкент--}}
            {{--            </th>--}}
            <th colspan="2">
                Ташкентский Офис
            </th>
            {{--            <th colspan="2">--}}
            {{--                Ташкентская область--}}
            {{--            </th>--}}
            <th colspan="2">
                Ташкентский областной Офис
            </th>
            {{--            <th colspan="2">--}}
            {{--                Бухара--}}
            {{--            </th>--}}

        </tr>
        <tr>
            {{--                        <th>--}}
            {{--                            кол-во мероприятий--}}
            {{--                        </th>--}}
            {{--                        <th>--}}
            {{--                            кол-во человек--}}
            {{--                        </th>--}}
            {{--                        <th>--}}
            {{--                            кол-во мероприятий--}}
            {{--                        </th>--}}
            {{--                        <th>--}}
            {{--                            кол-во человек--}}
            {{--                        </th>--}}
            {{--                        <th>--}}
            {{--                            кол-во мероприятий--}}
            {{--                        </th>--}}
            {{--                        <th>--}}
            {{--                            кол-во человек--}}
            {{--                        </th>--}}
            <th>
                кол-во мероприятий
            </th>
            <th>
                кол-во человек
            </th>
            <th>
                кол-во мероприятий
            </th>
            <th>
                кол-во человек
            </th>
            <th>
                кол-во мероприятий
            </th>
            <th>
                кол-во человек
            </th>
            <th>
                кол-во мероприятий
            </th>
            <th>
                кол-во человек
            </th>
        </tr>
        </tfoot>
    </table>
@endsection
