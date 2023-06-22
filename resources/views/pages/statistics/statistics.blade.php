@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('lib/datetimepicker/daterangepicker.css') }}"/>
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
                    "Сегодня": [moment(), moment()],
                    "Вчера": [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    "Последние 7 дней": [moment().subtract(6, 'days'), moment()],
                    "Последние 30 дней": [moment().subtract(29, 'days'), moment()],
                    "Этот месяц": [moment().startOf('month'), moment().endOf('month')],
                    "Предыдущий месяц": [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    "Первый квартал этого года": [moment().quarter(1).startOf("quarter"), moment().quarter(1).endOf("quarter")],
                    "Второй квартал этого года": [moment().quarter(2).startOf("quarter"), moment().quarter(2).endOf("quarter")],
                    "Третий квартал этого года": [moment().quarter(3).startOf("quarter"), moment().quarter(3).endOf("quarter")],
                    "Четвертый квартал этого года": [moment().quarter(4).startOf("quarter"), moment().quarter(4).endOf("quarter")],
                    "Первый квартал прошлого года": [moment().subtract(1, "year").quarter(1).startOf("quarter"), moment().subtract(1, "year").quarter(1).endOf("quarter")],
                    "Второй квартал прошлого года": [moment().subtract(1, "year").quarter(2).startOf("quarter"), moment().subtract(1, "year").quarter(2).endOf("quarter")],
                    "Третий квартал прошлого года": [moment().subtract(1, "year").quarter(3).startOf("quarter"), moment().subtract(1, "year").quarter(3).endOf("quarter")],
                    "Четвертый квартал прошлого года": [moment().subtract(1, "year").quarter(4).startOf("quarter"), moment().subtract(1, "year").quarter(4).endOf("quarter")],
                    "Этот год": [moment().startOf("year"), moment().endOf("year")],
                    "Прошлый год": [moment().subtract(1, "year").startOf("year"), moment().subtract(1, "year").endOf("year")],
                }
            }, cb);

            $('#filterDate').on('apply.daterangepicker', function (ev, picker) {
                window.location.href = "{{ route('StatisticsAll') }}?startDate=" + picker.startDate.format('YYYY-MM-DD') + "&endDate=" + picker.endDate.format('YYYY-MM-DD');
            });
        });
    </script>
@endsection

@section('header')
    <div class="sh-breadcrumb">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{ route('index') }}">INTILISH v3.1</a>
            <span class="breadcrumb-item">Сводка</span>
            <span class="breadcrumb-item active">Общая сводка</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <div class="sh-pagetitle">

        <div class="input-group">
            <!-- search block -->
        </div><!-- input-group -->

        <div class="sh-pagetitle-left">
            <div class="sh-pagetitle-icon"><i class="icon ion-stats-bars"></i></div>
            <div class="sh-pagetitle-title">
                <h2>Общая сводка</h2>
            </div><!-- sh-pagetitle-left-title -->
        </div><!-- sh-pagetitle-left -->

    </div><!-- sh-pagetitle -->
@endsection

@section('scriptsFooter')

@endsection

@section('content')
    <div class="text-center card card-body tx-white-8 bg-info bd-0"
         style="display: block; flex-direction: row; cursor: pointer;" id="filterDate">
        <i class="fa fa-calendar"></i>&nbsp;
        <span>Укажите период</span> <i class="fa fa-caret-down"></i>
    </div>
    <a
            class="btn btn-primary btn-block mg-b-10"
            href="{{ route('StatisticsAll') }}"
    >
        Сбросить
    </a>
    <div class="col-4">
        <table class="table table-hover table-bordered table-primary mg-b-0">
            <thead class="text-center">
            <tr>
                <td colspan="4">Мониторинговые визиты</td>
            </tr>
            <tr>
                <td>Регион</td>
                <td>Месяц</td>
                <td>Количество</td>
                <td>Сумма</td>
            </tr>
            </thead>
            <tbody>
            @foreach($mioRes as $key => $value)
                @php($i = 0)
                @if($key !== '01-TA' && $key !== '11-TV')
                    @foreach($mioRes[$key] as $k => $val)
                        @if($i === 0)
                            <tr>
                                <td class="align-middle" rowspan="{{ count($mioRes[$key])-1 }}">{{ $key }}</td>
                                <td>{{ $k === 'sum' ? 'N/A' : $k }}</td>
                                <td>{{ $val }}</td>
                                <td class="align-middle"
                                    rowspan="{{ count($mioRes[$key])-1 }}">{{ $mioRes[$key]['sum'] }}</td>
                            </tr>
                        @else
                            @if($k !== 'sum' && $k !== 'N/A')
                                @php(count($mioRes[$key])-2 === $i ? $class = 'font-weight-bold tx-danger' : $class = '')
                                <tr>
                                    <td class="{{ $class }}">{{ $k }}</td>
                                    <td class="{{ $class }}">{{ $val }}</td>
                                </tr>
                            @endif
                        @endif
                        @php($i++)
                    @endforeach
                @endif
            @endforeach
            </tbody>
            <tfoot>
            <tr class="text-center">
                <td>Регион</td>
                <td>Месяц</td>
                <td>Количество</td>
                <td>Сумма</td>
            </tr>
            </tfoot>
        </table>
    </div>

    <div class="col-4">
        <table class="table table-hover table-bordered table-primary mg-b-0">
            <thead class="text-center">
            <tr>
                <td colspan="4">Оценка предоставленных услуг</td>
            </tr>
            <tr>
                <td>Регион</td>
                <td>Месяц</td>
                <td>Количество</td>
                <td>Сумма</td>
            </tr>
            </thead>
            <tbody>
            @foreach($opuRes as $key => $value)
                @php($class = '')
                @php($i = 0)
                @if($key !== '01-TA' && $key !== '11-TV')
                    @foreach($opuRes[$key] as $k => $val)
                        @php($count = count($opuRes[$key])-1)
                        @if($i === 0)
                            <tr>
                                <td class="align-middle" rowspan="{{ count($opuRes[$key])-1 }}">{{ $key }}</td>
                                <td>{{ $k === 'sum' ? 'N/A' : $k }}</td>
                                <td>{{ $val }}</td>
                                <td class="align-middle"
                                    rowspan="{{ $count }}">{{ $opuRes[$key]['sum'] }}</td>
                            </tr>
                        @else
                            @php(count($opuRes[$key])-2 === $i ? $class = 'font-weight-bold tx-danger' : $class = '')
                            @if($k !== 'sum' && $k !== 'N/A')
                                <tr>
                                    <td class="{{ $class }}">{{ $k }}</td>
                                    <td class="{{ $class }}">{{ $val }}</td>
                                </tr>
                            @endif
                        @endif
                        @php($i++)
                    @endforeach
                @endif
            @endforeach
            </tbody>
            <tfoot>
            <tr class="text-center">
                <td>Регион</td>
                <td>Месяц</td>
                <td>Количество</td>
                <td>Сумма</td>
            </tr>
            </tfoot>
        </table>
    </div>

    <div class="col-4">
        <table class="table table-hover table-bordered table-primary mg-b-0">
            <thead class="text-center">
            <tr>
                <td colspan="5">Анкетирование сотрудников</td>
            </tr>
            <tr>
                <td>Регион</td>
                <td>Опросник</td>
                <td>Месяц</td>
                <td>Количество</td>
                <td>Сумма</td>
            </tr>
            </thead>
            <tbody>

            @foreach($questRes as $key => $value)
                @php($i = 0)
                @php($sumArrays = 0)
                @if($key !== '01-TA' && $key !== '11-TV')
                    @foreach($questRes[$key] as $ke => $val)
                        @if($ke !== 'sum')
                            @php($sumArrays += count($questRes[$key][$ke]))
                        @endif
                    @endforeach
                    @foreach($questRes[$key] as $ke => $val)
                        @if($ke !== 'sum')
                            @php($j = 0)
                            @foreach($questRes[$key][$ke] as $k => $v)
                                @if($i === 0)
                                    <tr>
                                        <td rowspan="{{ $sumArrays }}">{{ $key }}</td>
                                        <td>{{ $ke }}</td>
                                        <td>{{ $k }}</td>
                                        <td>{{ $v }}</td>
                                        <td rowspan="{{ $sumArrays }}">{{ $questRes[$key]['sum'] }}</td>
                                    </tr>
                                    @php($i++)
                                @elseif($j === 0)
                                    <tr>
                                        <td rowspan="{{ count($questRes[$key][$ke]) }}">{{ $ke }}</td>
                                        <td>{{ $k }}</td>
                                        <td>{{ $v }}</td>
                                    </tr>
                                    @php($j++)
                                @else
                                    @php($questRes[$key][$ke] === $j ? $class = 'font-weight-bold tx-danger' : $class = '')
                                    <tr>
                                        <td class="{{ $class }}">{{ $k }}</td>
                                        <td class="{{ $class }}">{{ $v }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                @endif
            @endforeach
            </tbody>
            <tfoot>
            <tr class="text-center">
                <td>Регион</td>
                <td>Опросник</td>
                <td>Месяц</td>
                <td>Количество</td>
                <td>Сумма</td>
            </tr>
            </tfoot>
        </table>
    </div>
@endsection
