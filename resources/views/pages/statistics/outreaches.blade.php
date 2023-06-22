@extends('layouts.app')

@php
    $page = explode('/',url()->current());
    $avg_type1_answers = 0;
    $counts_type1_answers = 0;
    $avg_type2_answers = 0;
    $counts_type2_answers = 0;
    $j = 0;
    for ($i = 1; $i <= 14; $i++){
        $regions_avg_type1_answers[$i] = 0;
        $regions_counts_type1_answers[$i] = 0;
        $regions_avg_type2_answers[$i] = 0;
        $regions_counts_type2_answers[$i] = 0;
        $regions_events[$i] = 0;
    }
    $all_events = 0;
@endphp

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('lib/datetimepicker/daterangepicker.css') }}"/>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('lib/moment/moment.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/moment/locale/ru.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/datetimepicker/daterangepicker.js') }}"></script>
@endsection

@section('header')
    <div class="sh-breadcrumb">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{ route('index') }}">INTILISH v3.1</a>
            <span class="breadcrumb-item">Сводка</span>
            <span class="breadcrumb-item active">Аутрич - сотрудники</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <div class="sh-pagetitle">

        <div class="input-group">
            <!-- search block -->
        </div><!-- input-group -->

        <div class="sh-pagetitle-left">
            <div class="sh-pagetitle-icon"><i class="icon ion-ios-help-outline"></i></div>
            <div class="sh-pagetitle-title">
                <h2>Результаты анкет для аутрич сотрудников и волонтеров</h2>
            </div><!-- sh-pagetitle-left-title -->
        </div><!-- sh-pagetitle-left -->

    </div><!-- sh-pagetitle -->
@endsection

@section('scriptsFooter')
    <script type="text/javascript">
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
                    "Этот месяц": [moment().startOf('month'), moment().endOf('month')],
                    "Предыдущий месяц": [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);

            $('#filterDate').on('apply.daterangepicker', function (ev, picker) {
                window.location.href = "{{ route('StatisticsOutreaches', ['encoding' => $page[6], 'region' => $page[7]]) }}?startDate=" + picker.startDate.format('YYYY-MM-DD') + "&endDate=" + picker.endDate.format('YYYY-MM-DD');
            });
        });
    </script>
@endsection

@section('content')
    <div class="btn-group wd-100p">
        <a href="{{ route('DownloadStatsOutreach', ['region' => $page[6], 'startDate' => $_GET['startDate'], 'endDate' => $_GET['endDate']]) }}"
           class="btn btn-success wd-100p">Скачать статистику</a>
    </div>
    <div class="btn-group wd-100p">
        <a href="{{ route('StatisticsOutreaches', ['encoding' => $page[6], 'region' => $page[7], 'startDate' =>  $_GET['startDate'] ? $_GET['startDate'] : NULL, 'endDate' => $_GET['endDate'] ? $_GET['endDate'] : NULL, 'webinar' => 1]) }}"
           class="btn btn-warning wd-100p {{ $_GET['webinar'] === '1' ? 'active' : '' }}">Вебинары</a>
        <a href="{{ route('StatisticsOutreaches', ['encoding' => $page[6], 'region' => $page[7], 'startDate' =>  $_GET['startDate'] ? $_GET['startDate'] : NULL, 'endDate' => $_GET['endDate'] ? $_GET['endDate'] : NULL, 'webinar' => 0]) }}"
           class="btn btn-warning wd-100p {{ $_GET['webinar'] === '0' ? 'active' : '' }}">Семинары</a>
    </div>
    <div class="col-12">
        <div class="pd-10 bd mg-b-20">
            <ul class="nav nav-pills flex-column flex-md-row justify-content-center" role="tablist">
                <li class="nav-item">
                    <a class="nav-link{{ $page[6] === 'all' ? ' active' : '' }}"
                       @if($page[7] !== 'all' && isset($_GET['startDate']) && isset($_GET['endDate']))
                       href="{{ route('StatisticsOutreaches', ['encoding' => 'all', 'region' => $page[7], 'startDate' => $_GET['startDate'], 'endDate' => $_GET['endDate']]) }}"
                       @elseif($page[7] !== 'all')
                       href="{{ route('StatisticsOutreaches', ['encoding' => 'all', 'region' => $page[7]]) }}"
                       @elseif(isset($_GET['startDate']) && isset($_GET['endDate']))
                       href="{{ route('StatisticsOutreaches', ['encoding' => 'all', 'region' => $page[7], 'startDate' => $_GET['startDate'], 'endDate' => $_GET['endDate']]) }}"
                       @else
                       href="{{ route('StatisticsOutreaches', ['region' => 'all', 'encoding' => 'all']) }}"
                       @endif
                       role="tab">Все</a>
                </li>
                @foreach($questionnaires as $questionnaire)
                    <li class="nav-item">
                        <a class="nav-link{{ $page[6] === $questionnaire->encoding ? ' active' : '' }}"
                           @if($page[7] !== 'all' && isset($_GET['startDate']) && isset($_GET['endDate']))
                           href="{{ route('StatisticsOutreaches', ['encoding' => $questionnaire->encoding, 'region' => $page[7], 'startDate' => $_GET['startDate'], 'endDate' => $_GET['endDate']]) }}"
                           @elseif($page[7] !== 'all')
                           href="{{ route('StatisticsOutreaches', ['encoding' => $questionnaire->encoding, 'region' => $page[7]]) }}"
                           @else
                           href="{{ route('StatisticsOutreaches', ['region' => 'all', 'encoding' => $questionnaire->encoding]) }}"
                           @endif
                           role="tab">{{ $questionnaire->encoding }}</a>
                    </li>
                    @php
                        $events !== 0 ? $all_events += array_sum(array_column($events, $questionnaire->id)) : 0;
                    @endphp
                @endforeach
            </ul>
        </div>
    </div>
    <div class="col-12">
        @if(Auth::user()->region->id === 0)
            <div class="pd-10 bd mg-b-20">
                <ul class="nav nav-pills flex-column flex-md-row justify-content-center" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link{{ $page[7] === 'all' ? ' active' : '' }}"
                           @if($page[6] !== 'all' && isset($_GET['startDate']) && isset($_GET['endDate']))
                           href="{{ route('StatisticsOutreaches', ['region' => 'all', 'encoding' => $page[6], 'startDate' => $_GET['startDate'], 'endDate' => $_GET['endDate']]) }}"
                           @elseif($page[6] !== 'all')
                           href="{{ route('StatisticsOutreaches', ['region' => 'all', 'encoding' => $page[6]]) }}"
                           @elseif(isset($_GET['startDate']) && isset($_GET['endDate']))
                           href="{{ route('StatisticsOutreaches', ['region' => 'all', 'encoding' => $page[6], 'startDate' => $_GET['startDate'], 'endDate' => $_GET['endDate']]) }}"
                           @else
                           href="{{ route('StatisticsOutreaches', ['region' => 'all', 'encoding' => 'all']) }}"
                           @endif
                           role="tab">Все</a>
                    </li>
                    @foreach($regions as $region)
                        @if($region->id !== 0)
                            <li class="nav-item">
                                <a class="nav-link{{ $page[7] == $region->encoding ? ' active' : '' }}"
                                   @if($page[6] !== 'all' && isset($_GET['startDate']) && isset($_GET['endDate']))
                                   href="{{ route('StatisticsOutreaches', ['region' => $region->encoding, 'encoding' => $page[6], 'startDate' => $_GET['startDate'], 'endDate' => $_GET['endDate']]) }}"
                                   @elseif($page[6] !== 'all')
                                   href="{{ route('StatisticsOutreaches', ['region' => $region->encoding, 'encoding' => $page[6]]) }}"
                                   @else
                                   href="{{ route('StatisticsOutreaches', ['region' => $region->encoding, 'encoding' => 'all']) }}"
                                   @endif
                                   role="tab">{{ $region->encoding }}</a>
                            </li>
                        @endif
                    @endforeach
                    @if($page[7] === 'all')
                        <li class="nav-item">
                            <a class="btn btn-success"
                               href="#"
                               data-target="#results"
                               data-toggle="modal"
                               role="tab">Результаты опроса</a>
                        </li>
                    @endif
                </ul>
            </div>
        @endif
    </div>
    @foreach($data as $datum)
        @php
            if(is_int($datum->type1)){
                $avg_type1_answers += $datum->type1;
                $counts_type1_answers += $datum->count;
                $regions_avg_type1_answers[$datum->id_region] += $datum->type1;
                $regions_counts_type1_answers[$datum->id_region] += $datum->count;
            }
            if(is_int($datum->type2)){
                $avg_type2_answers += $datum->type2;
                $counts_type2_answers += $datum->count;
                $regions_avg_type2_answers[$datum->id_region] += $datum->type2;
                $regions_counts_type2_answers[$datum->id_region] += $datum->count;
            }
        @endphp
    @endforeach
    <div class="col-lg-12 alert alert-success wd-100p-force text-center">
        Средний уровень знаний ПРЕ
        ТЕСТ: {{ $avg_type1_answers !== 0 ? round($avg_type1_answers * 100 / $counts_type1_answers, 2) : 0 }}%<br>
        Средний уровень знаний ПОСТ
        ТЕСТ: {{ $avg_type2_answers !== 0 ? round($avg_type2_answers * 100 / $counts_type2_answers, 2) : 0 }}%<br>
        Всего очных мероприятий: {{ $all_events }}<br>
    </div>
    <div class="text-center card card-body tx-white-8 bg-info bd-0"
         style="display: block; flex-direction: row; cursor: pointer;" id="filterDate">
        <i class="fa fa-calendar"></i>&nbsp;
        <span>Укажите период</span> <i class="fa fa-caret-down"></i>
    </div>
    <a
            class="btn btn-primary btn-block mg-b-10"
            href="{{ route('StatisticsOutreaches', ['encoding' => $page[6], 'region' => $page[6]]) }}"
    >
        Сбросить
    </a>
    <table class="table table-hover table-bordered table-primary mg-b-0">
        <thead>
        <tr>
            <td>#</td>
            <td>Дата</td>
            <td>Аутрич-сотрудник</td>
            <td>Волонтер</td>
            <td>Регион</td>
            <td>Тема опросника</td>
            <td>ПРЕ тест</td>
            <td>ПОСТ тест</td>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $datum)
            <tr>
                <td>{{ $datum->id }}</td>
                <td>{{ $datum->date }}</td>
                <td>{{ $datum->outreach }}</td>
                <td>{{ $datum->volunteer }}</td>
                <td>{{ $datum->region }}</td>
                <td>{{ $datum->questionnaire_encoding }}</td>
                <td>{{ $datum->type1 !== 'NULL'? round($datum->type1 * 100 / $datum->count, 2).'%' : $datum->type1 }}</td>
                <td>{{ $datum->type2 !== 'NULL'? round($datum->type2 * 100 / $datum->count, 2).'%' : $datum->type2 }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td>#</td>
            <td>Дата</td>
            <td>Аутрич-сотрудник</td>
            <td>Волонтер</td>
            <td>Регион</td>
            <td>Тема опросника</td>
            <td>ПРЕ тест</td>
            <td>ПОСТ тест</td>
        </tr>
        </tfoot>
    </table>
    <div id="results" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content tx-size-sm">
                <div class="modal-header pd-x-20">
                    <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Результат опроса</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <table class="table table-hover table-bordered table-primary mg-b-0">
                        <thead>
                        <tr>
                            <td>#</td>
                            <td>Регион</td>
                            <td>Мероприятий</td>
                            <td>ПРЕ ТЕСТ</td>
                            <td>ПОСТ ТЕСТ</td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($regions as $region)
                            @if($regions_avg_type1_answers[$region->id] <> 0 || $regions_avg_type2_answers[$region->id] <> 0)
                                @php($j++)
                                <tr>
                                    <td>{{ $j }}</td>
                                    <td>{{ $region->encoding }}</td>
                                    <td>{{ $return_eventsAll[$region->id] }}</td>
                                    <td>
                                        {{ $regions_avg_type1_answers[$region->id] !== 0 ? round($regions_avg_type1_answers[$region->id] * 100 / $regions_counts_type1_answers[$region->id], 2) . '%' : 0 }}
                                    </td>
                                    <td>
                                        {{ $regions_avg_type2_answers[$region->id] !== 0 ? round($regions_avg_type2_answers[$region->id] * 100 / $regions_counts_type2_answers[$region->id], 2) . '%' : 0 }}
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        @foreach($regions as $region)
                            @if($regions_avg_type1_answers[$region->id] === 0 && $regions_avg_type2_answers[$region->id] === 0)
                                @php($j++)
                                <tr>
                                    <td>{{ $j }}</td>
                                    <td>{{ $region->encoding }}</td>
                                    <td>
                                        НЕТ ДАННЫХ
                                    </td>
                                    <td>
                                        НЕТ ДАННЫХ
                                    </td>
                                    <td>
                                        НЕТ ДАННЫХ
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <td>#</td>
                            <td>Регион
                            <td>Мероприятий</td>
                            <td>ПРЕ ТЕСТ</td>
                            <td>ПОСТ ТЕСТ</td>
                        </tr>
                        </tfoot>
                    </table>
                </div><!-- modal-body -->
            </div>
        </div><!-- modal-dialog -->
    </div>
    {{--@if(isset($_GET['startDate']) && isset($_GET['endDate']) && isset($_GET['region']))--}}
    {{--{{ $data->fragment('paginator')->appends(['region' => $_GET['region'], 'startDate' => $_GET['startDate'], 'endDate' => $_GET['endDate']])->links('vendor.pagination.blue') }}--}}
    {{--@elseif(isset($_GET['startDate']) && isset($_GET['endDate']))--}}
    {{--{{ $data->fragment('paginator')->appends(['startDate' => $_GET['startDate'], 'endDate' => $_GET['endDate']])->links('vendor.pagination.blue') }}--}}
    {{--@elseif(isset($_GET['region']))--}}
    {{--{{ $data->fragment('paginator')->appends(['region' => $_GET['region']])->links('vendor.pagination.blue') }}--}}
    {{--@else--}}
    {{--{{ $data->fragment('paginator')->links('vendor.pagination.blue') }}--}}
    {{--@endif--}}
@endsection
