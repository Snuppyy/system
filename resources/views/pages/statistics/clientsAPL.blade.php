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
                window.location.href = "{{ route('StatisticsClientsAPL', ['region' => $page[7]]) }}?startDate=" + picker.startDate.format('YYYY-MM-DD') + "&endDate=" + picker.endDate.format('YYYY-MM-DD');
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
                <h2>Результаты анкет по приверженности</h2>
            </div><!-- sh-pagetitle-left-title -->
        </div><!-- sh-pagetitle-left -->

    </div><!-- sh-pagetitle -->
@endsection

@section('content')
    <div class="col-12">
        <div class="pd-10 bd mg-b-20">
            <ul class="nav nav-pills flex-column flex-md-row justify-content-center" role="tablist">
                <li class="nav-item">
                    <a class="nav-link{{ $page[7] === 'all' ? ' active' : '' }}"
                       @if(isset($_GET['startDate']) && isset($_GET['endDate']))
                       href="{{ route('StatisticsClientsAPL', ['region' => 'all', 'startDate' => $_GET['startDate'], 'endDate' => $_GET['endDate']]) }}"
                       @else
                       href="{{ route('StatisticsClientsAPL', ['region' => 'all']) }}"
                       @endif
                       role="tab">Все</a>
                </li>
                @foreach($regions as $region)
                    @if($region->id !== 0)
                        <li class="nav-item">
                            <a class="nav-link{{ $page[7] == $region->encoding ? ' active' : '' }}"
                               @if(isset($_GET['startDate']) && isset($_GET['endDate']))
                               href="{{ route('StatisticsClientsAPL', ['region' => $region->encoding, 'startDate' => $_GET['startDate'], 'endDate' => $_GET['endDate']]) }}"
                               @else
                               href="{{ route('StatisticsClientsAPL', $region->encoding) }}"
                               @endif
                               role="tab">{{ $region->encoding }}</a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
    <div class="col-lg-12 alert alert-success wd-100p-force text-center">
        Всего анкет: {{ $data['count']['all'] }}<br>
        ПРЕ: {{ $data['count'][1] }}<br>
        ПОСТ: {{ $data['count'][2] }}<br>
    </div>
    <div class="text-center card card-body tx-white-8 bg-info bd-0"
         style="display: block; flex-direction: row; cursor: pointer;" id="filterDate">
        <i class="fa fa-calendar"></i>&nbsp;
        <span>Укажите период</span> <i class="fa fa-caret-down"></i>
    </div>
    <a
            class="btn btn-primary btn-block mg-b-10"
            href="{{ route('StatisticsClientsAPL', ['region' => $page[7]]) }}"
    >
        Сбросить
    </a>
    <table class="table table-hover table-bordered table-primary">
        <thead>
        <th width="5%">#</th>
        <th width="85%">Вопрос</th>
        <th width="5%">ПРЕ</th>
        <th width="5%">ПОСТ</th>
        </thead>
        <tbody>
        @php($i = 1)
        @foreach($questions as $question_id => $datum)
            <tr>
                <td class="">{{ $i }}</td>
                <td colspan="2">{{ $datum['name'] }}</td>
            </tr>
            @foreach($datum as $answer_id => $answers)
                @if($answer_id <> 'name')
                    <tr>
                        <td colspan="2">{{ $answers }}</td>
                        <td>{{ $data[$question_id][$answer_id][1] ?? 0  }}
                            / {{ round((($data[$question_id][$answer_id][1] / $data['count'][1])*100), 1) }}%
                        </td>
                        <td>{{ $data[$question_id][$answer_id][2] ?? 0  }}
                            / {{ round((($data[$question_id][$answer_id][2] / $data['count'][2])*100), 1) }}%
                        </td>
                    </tr>
                @endif
            @endforeach
            <tr>
                <td colspan="2">Не знаю</td>
                <td>{{ $data[$question_id][0][1] ?? 0 }}
                    / {{ round((($data[$question_id][0][1] / $data['count'][1])*100), 1) }}%
                </td>
                <td>{{ $data[$question_id][0][2] ?? 0 }}
                    / {{ round((($data[$question_id][0][2] / $data['count'][2])*100), 1) }}%
                </td>
            </tr>
            @php($i++)
        @endforeach
        </tbody>
        <tfoot>
        <th width="5%">#</th>
        <th width="85%">Вопрос</th>
        <th width="5%">ПРЕ</th>
        <th width="5%">ПОСТ</th>
        </tfoot>
    </table>
@endsection
