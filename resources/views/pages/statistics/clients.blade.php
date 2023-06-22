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
    <script>
        var rows = $("#mainTable tr:gt(0)");
        var array = rows.map(function (i, e) {
            console.log(encodeURIComponent(e.cells[0]));
            return {
                с1: e.cells[0],
                с2: +e.cells[1],
                с3: +e.cells[2]
            };
        }).toArray();
        var msg = JSON.stringify(array);
        console.log(msg);
    </script>

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
                    "Сегодня": [moment(), moment()],
                    "Вчера": [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    "Последние 7 дней": [moment().subtract(6, 'days'), moment()],
                    "Последние 30 дней": [moment().subtract(29, 'days'), moment()],
                    "Этот месяц": [moment().startOf('month'), moment().endOf('month')],
                    "Предыдущий месяц": [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);

            $('#filterDate').on('apply.daterangepicker', function (ev, picker) {
                window.location.href = "{{ route('StatisticsClients', ['region' => $page[6]]) }}?startDate=" + picker.startDate.format('YYYY-MM-DD') + "&endDate=" + picker.endDate.format('YYYY-MM-DD');
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
                <h2>Результаты анкет для клиентов</h2>
            </div><!-- sh-pagetitle-left-title -->
        </div><!-- sh-pagetitle-left -->

    </div><!-- sh-pagetitle -->
@endsection

@section('content')
    @php
        $condition['answers'][1][1] = 'ВИЧ - это вирус иммунодефицита человека, который передаётся только от человека к человеку';
        $condition['answers'][1][2] = 'ВИЧ - последняя стадия СПИДа';
        $condition['answers'][1][3] = 'СПИД - это последняя стадия ВИЧ - инфекции';

        $condition['answers'][2][1] = 'Быстро погибает вне организма человека';
        $condition['answers'][2][2] = 'Передается через кровососущих насекомых';
        $condition['answers'][2][3] = 'Находится в воздухе и передается как грипп';
        $condition['answers'][2][4] = 'Находится только в жидкостях человеческого организма (крови, сперме)';

        $condition['answers'][3][1] = 'В плавательном бассейне';
        $condition['answers'][3][2] = 'При инъекции лекарства';
        $condition['answers'][3][3] = 'Через сидение унитаза';
        $condition['answers'][3][4] = 'При использовании общих шприцев / игл при употреблении наркотиков';
        $condition['answers'][3][5] = 'При поцелуе';
        $condition['answers'][3][6] = 'При незащищенном половом контакте';
        $condition['answers'][3][7] = 'При рукопожатии';
        $condition['answers'][3][8] = 'При сдаче крови';
        $condition['answers'][3][9] = 'При переливании крови';
        
        $condition['answers'][4][1] = 'Если совсем не заниматься сексом';
        $condition['answers'][4][2] = 'Если использовать противозачаточные таблетки';
        $condition['answers'][4][3] = 'Если использовать презервативы при каждом половом контакте';
        $condition['answers'][4][4] = 'Если избегать грязных мест общественного пользования';
        $condition['answers'][4][5] = 'Если не употреблять наркотики';
        $condition['answers'][4][6] = 'Если при инъекции использовать стерильные шприцы';
        $condition['answers'][4][7] = 'Если при инъекции использовать стерильные индивидуальные инструменты и материалы для приготовления раствора наркотика';

        $condition['answers'][5][1] = 'Половом контакте с использованием презерватива';
        $condition['answers'][5][2] = 'Половом контакте без презерватива';
        $condition['answers'][5][3] = 'Беспорядочных половых контактах';
        $condition['answers'][5][4] = 'Общении с больным СПИДом';
        $condition['answers'][5][5] = 'Использовании общего инструментария при инъекциях';

        $condition[1][1] = 'Клиенты которые удовлетворены количеством встреч с аутрич-сотрудником';
        $condition[1][2] = 'Клиенты которые хотели бы встречаться чаще с аутрич-сотрудником';
        $condition[1][3] = 'Клиенты которые хотели бы встречаться реже с аутрич-сотрудником';
        $condition[1][4] = 'Клиенты которым необходимо большее количество получаемых профилактических средств защиты';

        $condition[2][1] = 'Клиенты которые употребляют героин';
        $condition[2][2] = 'Клиенты которые употребляют кондитерский мак';
        $condition[2][3] = 'Клиенты которые употребляют маковая солома';
        $condition[2][4] = 'Клиенты которые употребляют КДЦ';
        $condition[2][5] = 'Клиенты которые употребляют опий';
        $condition[2][6] = 'Клиенты которые употребляют трамадол в/в';
        $condition[2][7] = 'Клиенты которые употребляют растворы таблеток';
        $condition[2][8] = 'Клиенты которые употребляют все понемногу';

        $condition[3][1] = 'Клиенты которые удовлетворены количеством получаемых шприцев';
        $condition[3][2] = 'Клиенты которым необходимо большее количество получаемых шприцев';
        $condition[3][3] = 'Средняя цифра необходимых шприцев';
        $condition[3][5] = 'Средняя цифра получаемых шприцев';

        $condition[4][1] = 'Клиенты которые удовлетворены количеством получаемых 2.00 шприцев';
        $condition[4][2] = 'Клиенты которым необходимо большее количество получаемых 2.00 шприцев';
        $condition[4][3] = 'Средняя цифра необходимых 2.00 шприцев';
        $condition[4][5] = 'Средняя цифра получаемых 2.00 шприцев';

        $condition[5][1] = 'Клиенты которые удовлетворены количеством получаемых 5.00 шприцев';
        $condition[5][2] = 'Клиенты которым необходимо большее количество получаемых 5.00 шприцев';
        $condition[5][3] = 'Средняя цифра необходимых 5.00 шприцев';
        $condition[5][5] = 'Средняя цифра получаемых 5.00 шприцев';

        $condition[6][1] = 'Клиенты которые удовлетворены количеством получаемых 10.00 шприцев';
        $condition[6][2] = 'Клиенты которым необходимо большее количество получаемых 10.00 шприцев';
        $condition[6][3] = 'Средняя цифра необходимых 10.00 шприцев';
        $condition[6][5] = 'Средняя цифра получаемых 10.00 шприцев';

        $condition[7][1] = 'Клиенты которые удовлетворены количеством получаемых салфеток';
        $condition[7][2] = 'Клиенты которым необходимо большее количество получаемых салфеток';
        $condition[7][3] = 'Средняя цифра необходимых салфеток';
        $condition[7][5] = 'Средняя цифра получаемых салфеток';

        $condition[8][1] = 'Клиенты которые удовлетворены количеством получаемых мужских презервативов';
        $condition[8][2] = 'Клиенты которым необходимо большее количество получаемых мужских презервативов';
        $condition[8][6] = 'Клиенты которым не нужны мужские презервативы';
        $condition[8][3] = 'Средняя цифра необходимых мужских презервативов';
        $condition[8][5] = 'Средняя цифра получаемых мужских презервативов';

        $condition[9][1] = 'Клиенты которые удовлетворены количеством получаемых женских презервативов';
        $condition[9][2] = 'Клиенты которым необходимо большее количество получаемых женских презервативов';
        $condition[9][3] = 'Средняя цифра необходимых женских презервативов';
        $condition[9][5] = 'Средняя цифра получаемых женских презервативов';

        $condition[10][1] = 'Клиенты которые проходили тест на ВИЧ 1 раз в год';
        $condition[10][2] = 'Клиенты которые проходили тест на ВИЧ 2 раз в год';
        $condition[10][3] = 'Клиенты которые проходили тест на ВИЧ 3 раз в год';
        $condition[10][4] = 'Клиенты которые проходили тест на ВИЧ за последние 12 месяцев';
        $condition[10][5] = 'Клиенты которые не проходили тест на ВИЧ за последние 12 месяцев';
        $condition[10][6] = 'Клиенты которым аутрич-сотрудник предлагал пройти тест на ВИЧ';
        $condition[10][7] = 'Клиенты которых аутрич-сотрудник сопровождал для прохождения теста на ВИЧ';
        $condition[10][8] = 'Клиенты которым аутрич-сотрудник предлагал и сопровождал их на прохождение теста на ВИЧ';
        $condition[10][9] = 'Клиенты которым аутрич-сотрудник не предлагал и не сопровождал их на прохождение теста на ВИЧ';
        $condition[10][10] = 'Клиенты которым аутрич-сотрудник не предлагал и не сопровождал их на прохождение теста на ВИЧ, но они его прошли самостоятельно за последние 12 месяцев';
        $condition[10][11] = 'Клиенты которым аутрич-сотрудник не предлагал и не сопровождал их на прохождение теста на ВИЧ, и они его не проходили за последние 12 месяцев';

        $condition[11][1] = 'Клиенты которые проходили флюорографическое обследование 1 раз в год';
        $condition[11][2] = 'Клиенты которые проходили флюорографическое обследование 2 раз в год';
        $condition[11][3] = 'Клиенты которые проходили флюорографическое обследование 3 раз в год';
        $condition[11][4] = 'Клиенты которые проходили флюорографическое обследование за последние 12 месяцев';
        $condition[11][5] = 'Клиенты которые не проходили флюорографическое обследование за последние 12 месяцев';
        $condition[11][6] = 'Клиенты которым аутрич-сотрудник предлагал пройти флюорографическое обследование';
        $condition[11][7] = 'Клиенты которых аутрич-сотрудник сопровождал для прохождения флюорографического обследования';
        $condition[11][8] = 'Клиенты которым аутрич-сотрудник предлагал и сопровождал их на прохождение флюорографического обследования';
        $condition[11][9] = 'Клиенты которым аутрич-сотрудник не предлагал и не сопровождал их на прохождение флюорографического обследования';
        $condition[11][10] = 'Клиенты которым аутрич-сотрудник не предлагал и не сопровождал их на прохождение флюорографического обследования, но они его прошли самостоятельно за последние 12 месяцев';
        $condition[11][11] = 'Клиенты которым аутрич-сотрудник не предлагал и не сопровождал их на прохождение флюорографического обследования, и они его не проходили за последние 12 месяцев';

        $condition[12][1] = 'Клиенты которые состоят на учете в Центре по борьбе со СПИДом';

        $condition[13][1] = 'Клиенты которые проходили тестирование на вич в период с '.$_GET['startDate'].' до '.$_GET['endDate'];
        $condition[13][2] = 'Клиенты которые проходили флюорографическое обследование в период с '.$_GET['startDate'].' до '.$_GET['endDate'];
        $condition[13][2] = 'Клиенты которые проходили флюорографическое обследование в период с '.$_GET['startDate'].' до '.$_GET['endDate'];

        $condition[14][1] = 'Клиенты которых сопровождали на тестирование на вич в период с '.$_GET['startDate'].' до '.$_GET['endDate'];
        $condition[14][2] = 'Клиенты которых сопровождали на флюорографическое обследование в период с '.$_GET['startDate'].' до '.$_GET['endDate'];
        $condition[15][1] = 'Количество человек, больных туберкулезом в настоящее время';
        $condition[15][2] = 'Количество человек, которые не знают больны ли они туберкулезом в настоящее время';
        $condition[15][3] = 'Количество человек, которые получают лечение от туберкулеза';
        $condition[15][4] = 'Количество человек, которые больны туберкулезом и не получают лечение от туберкулеза';
        $condition[15][5] = 'Количество человек, которым аутрич-сотрудник предлагал пройти скрининг-анкетирование на туберкулез';
        $condition[15][6] = 'Количество человек, которым аутрич-сотрудник не предлагал пройти скрининг на туберкулез';
        $condition[15][7] = 'Количество человек, у которых выявился риск наличия туберкулеза при прохождении скрининга на туберкулез';
        $condition[15][8] = 'Количество человек, которые не знают, выявился ли у них  риск наличия туберкулеза';
        $condition[15][9] = 'Количество человек, у которых не выявился риск наличия туберкулеза при прохождении скрининга на туберкулез';
    @endphp
    @if(auth()->user()->role === 10)
        <div class="btn-group wd-100p">
            <a href="{{ route('projectSwap', ['project' => 1]) }}"
               class="btn btn-warning wd-100p {{ Auth::user()->positions->project == 1 ? 'active' : '' }}">1 часть
                проекта</a>
            <a href="{{ route('projectSwap', ['project' => 2]) }}"
               class="btn btn-warning wd-100p {{ Auth::user()->positions->project == 2 ? 'active' : '' }}">2 часть
                проекта</a>
        </div>
    @endif
    <div class="btn-group wd-100p">
        <a href="{{ route('DownloadStatsClient', ['region' => $page[6], 'startDate' => $_GET['startDate'], 'endDate' => $_GET['endDate']]) }}"
           class="btn btn-warning wd-100p">Скачать статистику</a>
        <a href="{{ route('DownloadCommentsClient', ['region' => $page[6], 'startDate' => $_GET['startDate'], 'endDate' => $_GET['endDate']]) }}"
           class="btn btn-warning wd-100p">Скачать комментарии</a>
    </div>
    <div class="col-12">
        @if(Auth::user()->region->id === 0)
            <div class="pd-10 bd mg-b-20">
                <ul class="nav nav-pills flex-column flex-md-row justify-content-center" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link{{ $page[6] === 'all' ? ' active' : '' }}"
                           @if(isset($_GET['startDate']) && isset($_GET['endDate']))
                           href="{{ route('StatisticsClients', ['region' => 'all', 'startDate' => $_GET['startDate'], 'endDate' => $_GET['endDate']]) }}"
                           @else
                           href="{{ route('StatisticsClients', ['region' => 'all']) }}"
                           @endif
                           role="tab">Все</a>
                    </li>
                    @foreach($regions as $region)
                        @if($region->id !== 0)
                            <li class="nav-item">
                                <a class="nav-link{{ $page[6] == $region->encoding ? ' active' : '' }}"
                                   @if(isset($_GET['startDate']) && isset($_GET['endDate']))
                                   href="{{ route('StatisticsClients', ['region' => $region->encoding, 'startDate' => $_GET['startDate'], 'endDate' => $_GET['endDate']]) }}"
                                   @else
                                   href="{{ route('StatisticsClients', $region->encoding) }}"
                                   @endif
                                   role="tab">{{ $region->encoding }}</a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row">
            <a class="btn btn-success col-6 btn-block mg-t-8-force"
               href="javascript:void(0);"
               data-target="#results"
               data-toggle="modal"
               role="tab">Результаты опроса</a>

            <a class="btn btn-info col-6 btn-block"
               href="javascript:void(0);"
               data-target="#comments"
               data-toggle="modal"
               role="tab">Комментарии</a>
        </div>

    </div>
    <div class="col-lg-12 alert alert-success wd-100p-force text-center">
        Всего клиентов: {{ $count }}<br>
        Женщин: {{ $famale }}<br>
        Мужчин: {{ $male }}<br>
        Аутрич-сотрудников: {{ $outreaches }}<br>
    </div>

    <div class="text-center card card-body tx-white-8 bg-info bd-0"
         style="display: block; flex-direction: row; cursor: pointer;" id="filterDate">
        <i class="fa fa-calendar"></i>&nbsp;
        <span>Укажите период</span> <i class="fa fa-caret-down"></i>
    </div>
    <a
            class="btn btn-primary btn-block mg-b-10"
            href="{{ route('StatisticsClients', ['region' => $page[6]]) }}"
    >
        Сбросить
    </a>
    <table id="mainTable" class="table table-hover table-bordered table-primary mg-b-0">
        <thead>
        <tr>
            <td>Условие</td>
            <td>Количество</td>
            <td>Процент</td>
        </tr>
        </thead>
        <tbody>
        @if($count !== 0)
            @foreach($data as $key => $value)
                @if($key != 'answers')
                    @foreach($data[$key] as $k => $val)
                        @php($stat = 0)
                        @if($key === 9 && $k > 2000)
                            <tr>
                                <td>
                                    Клиенты которые проходили тест на ВИЧ в {{ $k }} году
                                </td>
                            @php($stat = 1)
                        @elseif($key === 10 && $k > 2000)
                            <tr>
                                <td>
                                    Клиенты которые проходили флюорографическое обследование в {{ $k }} году
                                </td>
                            @php($stat = 1)
                        @elseif($condition[$key][$k])
                            <tr>
                                <td>
                                    {{ $condition[$key][$k] }}
                                </td>
                                @php($stat = 1)
                                @endif
                                @if($stat === 1)
                                    @if(($key === 4 && $k === 3) || ($key === 5 && $k === 3) || ($key === 6 && $k === 3) || ($key === 7 && $k === 3) || ($key === 8 && $k === 3) || ($key === 9 && $k === 3))
                                        <td colspan="2">
                                            {{ $data[$key][$k] != 0 ? round($data[$key][$k] / $data[$key][4], 2) : 0 }}
                                        </td>
                                    @elseif(($key === 4 && $k === 5) || ($key === 5 && $k === 5) || ($key === 6 && $k === 5) || ($key === 7 && $k === 5) || ($key === 8 && $k === 5) || ($key === 9 && $k === 5))
                                        <td colspan="2">
                                            {{ round($data[$key][$k] / $count, 2) }}
                                        </td>
                                    @elseif(($key === 3 && $k === 3) || ($key === 3 && $k === 5))
                                        <td colspan="2">
                                            {{ $data[$key][$k] }}
                                        </td>
                                    @else
                                        <td>
                                            {{ $data[$key][$k] }}
                                        </td>
                                        <td>
                                            {{ round($data[$key][$k] * 100 / $count, 2) }}%
                                        </td>
                                    @endif
                            </tr>
                        @endif
                    @endforeach
                @endif
            @endforeach
        @endif
        </tbody>
        <tfoot>
        <tr>
            <td>Условие</td>
            <td>Количество</td>
            <td>Процент</td>
        </tr>
        </tfoot>
    </table>
    @if($count !== 0)
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
                                <td>№</td>
                                <td>Вопрос</td>
                                <td>Варианты ответов</td>
                                <td>Количество</td>
                                <td>Процент</td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td rowspan="3">
                                    1
                                </td>
                                <td rowspan="3">
                                    Выберите только правильные ответы:
                                </td>
                                <td>
                                    {{ $condition['answers'][1][1] }}
                                </td>
                                <td>
                                    {{ $data['answers'][1][1] }}
                                </td>
                                <td>
                                    {{ round($data['answers'][1][1] * 100 / $count, 2) }}%
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{ $condition['answers'][1][2] }}
                                </td>
                                <td>
                                    {{ $data['answers'][1][2] }}
                                </td>
                                <td>
                                    {{ round($data['answers'][1][2] * 100 / $count, 2) }}%
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{ $condition['answers'][1][3] }}
                                </td>
                                <td>
                                    {{ $data['answers'][1][3] }}
                                </td>
                                <td>
                                    {{ round($data['answers'][1][3] * 100 / $count, 2) }}%
                                </td>
                            </tr>

                            <tr>
                                <td rowspan="4">
                                    2
                                </td>
                                <td rowspan="4">
                                    Согласны ли Вы со следующими утверждениями, что ВИЧ:
                                </td>
                                <td>
                                    {{ $condition['answers'][2][1] }}
                                </td>
                                <td>
                                    {{ $data['answers'][2][1] }}
                                </td>
                                <td>
                                    {{ round($data['answers'][2][1] * 100 / $count, 2) }}%
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{ $condition['answers'][2][2] }}
                                </td>
                                <td>
                                    {{ $data['answers'][2][2] }}
                                </td>
                                <td>
                                    {{ round($data['answers'][2][2] * 100 / $count, 2) }}%
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{ $condition['answers'][2][3] }}
                                </td>
                                <td>
                                    {{ $data['answers'][2][3] }}
                                </td>
                                <td>
                                    {{ round($data['answers'][2][3] * 100 / $count, 2) }}%
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{ $condition['answers'][2][4] }}
                                </td>
                                <td>
                                    {{ $data['answers'][2][4] }}
                                </td>
                                <td>
                                    {{ round($data['answers'][2][4] * 100 / $count, 2) }}%
                                </td>
                            </tr>

                            <tr>
                                <td rowspan="9">
                                    3
                                </td>
                                <td rowspan="9">
                                    Как Вы думаете, можно ли заразиться ВИЧ-инфекцией:
                                </td>
                                <td>
                                    {{ $condition['answers'][3][1] }}
                                </td>
                                <td>
                                    {{ $data['answers'][3][1] }}
                                </td>
                                <td>
                                    {{ round($data['answers'][3][1] * 100 / $count, 2) }}%
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{ $condition['answers'][3][2] }}
                                </td>
                                <td>
                                    {{ $data['answers'][3][2] }}
                                </td>
                                <td>
                                    {{ round($data['answers'][3][2] * 100 / $count, 2) }}%
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{ $condition['answers'][3][3] }}
                                </td>
                                <td>
                                    {{ $data['answers'][3][3] }}
                                </td>
                                <td>
                                    {{ round($data['answers'][3][3] * 100 / $count, 2) }}%
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{ $condition['answers'][3][4] }}
                                </td>
                                <td>
                                    {{ $data['answers'][3][4] }}
                                </td>
                                <td>
                                    {{ round($data['answers'][3][4] * 100 / $count, 2) }}%
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{ $condition['answers'][3][5] }}
                                </td>
                                <td>
                                    {{ $data['answers'][3][5] }}
                                </td>
                                <td>
                                    {{ round($data['answers'][3][5] * 100 / $count, 2) }}%
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{ $condition['answers'][3][6] }}
                                </td>
                                <td>
                                    {{ $data['answers'][3][6] }}
                                </td>
                                <td>
                                    {{ round($data['answers'][3][6] * 100 / $count, 2) }}%
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{ $condition['answers'][3][7] }}
                                </td>
                                <td>
                                    {{ $data['answers'][3][7] }}
                                </td>
                                <td>
                                    {{ round($data['answers'][3][7] * 100 / $count, 2) }}%
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{ $condition['answers'][3][8] }}
                                </td>
                                <td>
                                    {{ $data['answers'][3][8] }}
                                </td>
                                <td>
                                    {{ round($data['answers'][3][8] * 100 / $count, 2) }}%
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{ $condition['answers'][3][9] }}
                                </td>
                                <td>
                                    {{ $data['answers'][3][9] }}
                                </td>
                                <td>
                                    {{ round($data['answers'][3][9] * 100 / $count, 2) }}%
                                </td>
                            </tr>

                            <tr>
                                <td rowspan="7">
                                    4
                                </td>
                                <td rowspan="7">
                                    На Ваш взгляд, можете ли Вы защитить себя от заражения ВИЧ-инфекции:
                                </td>
                                <td>
                                    {{ $condition['answers'][4][1] }}
                                </td>
                                <td>
                                    {{ $data['answers'][4][1] }}
                                </td>
                                <td>
                                    {{ round($data['answers'][4][1] * 100 / $count, 2) }}%
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{ $condition['answers'][4][2] }}
                                </td>
                                <td>
                                    {{ $data['answers'][4][2] }}
                                </td>
                                <td>
                                    {{ round($data['answers'][4][2] * 100 / $count, 2) }}%
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{ $condition['answers'][4][3] }}
                                </td>
                                <td>
                                    {{ $data['answers'][4][3] }}
                                </td>
                                <td>
                                    {{ round($data['answers'][4][3] * 100 / $count, 2) }}%
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{ $condition['answers'][4][4] }}
                                </td>
                                <td>
                                    {{ $data['answers'][4][4] }}
                                </td>
                                <td>
                                    {{ round($data['answers'][4][4] * 100 / $count, 2) }}%
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{ $condition['answers'][4][5] }}
                                </td>
                                <td>
                                    {{ $data['answers'][4][5] }}
                                </td>
                                <td>
                                    {{ round($data['answers'][4][5] * 100 / $count, 2) }}%
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{ $condition['answers'][4][6] }}
                                </td>
                                <td>
                                    {{ $data['answers'][4][6] }}
                                </td>
                                <td>
                                    {{ round($data['answers'][4][6] * 100 / $count, 2) }}%
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{ $condition['answers'][4][7] }}
                                </td>
                                <td>
                                    {{ $data['answers'][4][7] }}
                                </td>
                                <td>
                                    {{ round($data['answers'][4][7] * 100 / $count, 2) }}%
                                </td>
                            </tr>

                            <tr>
                                <td rowspan="5">
                                    5
                                </td>
                                <td rowspan="5">
                                    Как Вам кажется, увеличивается ли риск заразиться ВИЧ-инфекцией при:
                                </td>
                                <td>
                                    {{ $condition['answers'][5][1] }}
                                </td>
                                <td>
                                    {{ $data['answers'][5][1] }}
                                </td>
                                <td>
                                    {{ round($data['answers'][5][1] * 100 / $count, 2) }}%
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{ $condition['answers'][5][2] }}
                                </td>
                                <td>
                                    {{ $data['answers'][5][2] }}
                                </td>
                                <td>
                                    {{ round($data['answers'][5][2] * 100 / $count, 2) }}%
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{ $condition['answers'][5][3] }}
                                </td>
                                <td>
                                    {{ $data['answers'][5][3] }}
                                </td>
                                <td>
                                    {{ round($data['answers'][5][3] * 100 / $count, 2) }}%
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{ $condition['answers'][5][4] }}
                                </td>
                                <td>
                                    {{ $data['answers'][5][4] }}
                                </td>
                                <td>
                                    {{ round($data['answers'][5][4] * 100 / $count, 2) }}%
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{ $condition['answers'][5][5] }}
                                </td>
                                <td>
                                    {{ $data['answers'][5][5] }}
                                </td>
                                <td>
                                    {{ round($data['answers'][5][5] * 100 / $count, 2) }}%
                                </td>
                            </tr>

                            </tbody>
                            <tfoot>
                            <tr>
                                <td>№</td>
                                <td>Вопрос</td>
                                <td>Варианты ответов</td>
                                <td>Количество</td>
                                <td>Процент</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div><!-- modal-body -->
                </div>
            </div><!-- modal-dialog -->
        </div>
    @endif
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
                            <td>№</td>
                            <td>Вопрос</td>
                            <td>Варианты ответов</td>
                            <td>Количество</td>
                            <td>Процент</td>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                        <tr>
                            <td>№</td>
                            <td>Вопрос</td>
                            <td>Варианты ответов</td>
                            <td>Количество</td>
                            <td>Процент</td>
                        </tr>
                        </tfoot>
                    </table>
                </div><!-- modal-body -->
            </div>
        </div><!-- modal-dialog -->
    </div>

    <div id="comments" class="modal fade">
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
                            <td>Регион</td>
                            <td>Вопрос</td>
                            <td>Аутрич сотрудник</td>
                            <td>Клиент</td>
                            <td>Комментарий</td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($comments as $region => $array)
                            @foreach($array as $name => $values)
                                @foreach($values as $outreach => $volunteer)
                                    @foreach($volunteer as $volunteer_name => $answers)
                                        @foreach($answers as $key => $answer)
                                            <tr>
                                                <td>{{ $region }}</td>
                                                <td>{{ $name }}</td>
                                                <td>{{ $outreach }}</td>
                                                <td>{{ $volunteer_name }}</td>
                                                <td>{{ $answer }}</td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                @endforeach
                            @endforeach
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <td>Регион</td>
                            <td>Вопрос</td>
                            <td>Аутрич сотрудник</td>
                            <td>Клиент</td>
                            <td>Комментарий</td>
                        </tr>
                        </tfoot>
                    </table>
                </div><!-- modal-body -->
            </div>
        </div><!-- modal-dialog -->
    </div>
@endsection
