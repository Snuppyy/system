@extends('layouts.app')

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
                <h2>Сводка по аутрич-сотрудникам</h2>
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
                    "Сегодня": [moment(), moment()],
                    "Вчера": [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    "Последние 7 дней": [moment().subtract(6, 'days'), moment()],
                    "Последние 30 дней": [moment().subtract(29, 'days'), moment()],
                    "Этот месяц": [moment().startOf('month'), moment().endOf('month')],
                    "Предыдущий месяц": [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);

            $('#filterDate').on('apply.daterangepicker', function (ev, picker) {
                window.location.href = "{{ route('outreachesAll', ['type' => $type]) }}&startDate=" + picker.startDate.format('YYYY-MM-DD') + "&endDate=" + picker.endDate.format('YYYY-MM-DD');
            });
        });
    </script>
@endsection

@section('content')
    <div class="col-12">
        <div class="row">
            <a class=" {!! $type != 'webinar' & $type != 'seminar' ? 'active' : '' !!} btn btn-success col-4 btn-block mg-t-8-force" href="{{ route('outreachesAll', ['startDate' => $_GET['startDate'], 'endDate' => $_GET['endDate']]) }}">Общее</a>
            <a class=" {!! $type == 'seminar' ? 'active' : '' !!} btn btn-success col-4 btn-block mg-t-8-force" href="{{ route('outreachesAll', ['type' => 'seminar', 'startDate' => $_GET['startDate'], 'endDate' => $_GET['endDate']]) }}">Семинары</a>
            <a class=" {!! $type == 'webinar' ? 'active' : '' !!} btn btn-success col-4 btn-block" href="{{ route('outreachesAll', ['type' => 'webinar', 'startDate' => $_GET['startDate'], 'endDate' => $_GET['endDate']]) }}">Вебинары</a>
        </div>
    </div>
    <div class="text-center card card-body tx-white-8 bg-info bd-0"
         style="display: block; flex-direction: row; cursor: pointer;" id="filterDate">
        <i class="fa fa-calendar"></i>&nbsp;
        <span>Укажите период</span> <i class="fa fa-caret-down"></i>
    </div>
    <a
            class="btn btn-primary btn-block mg-b-10"
            href="{{ route('outreachesAll') }}"
    >
        Сбросить
    </a>
    <table class="table table-hover table-bordered table-primary mg-b-0">
        <thead>
        <tr>
            <td>#</td>
            <td>Регион</td>
            <td>Аутрич-сотрудник</td>
            <td>Организация</td>
            <td>Ассистент</td>
            <td>Кол-во мероприятий</td>
        </tr>
        </thead>
        <tbody>
        @php($i = 0)
        @foreach($outreaches as $outreach)
            @php($i++)
            <tr>
                <td>{{ $i }}</td>
                <td>{{ $outreach->encoding }}</td>
                <td>{{ $outreach->outreach }}</td>
                <td>{{ $outreach->organization }}</td>
                <td>{{ $outreach->assistant === 1 ? 'Да' : 'Нет' }}</td>
                <td>{{ $outreach->count }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td>#</td>
            <td>Регион</td>
            <td>Аутрич-сотрудник</td>
            <td>Организация</td>
            <td>Ассистент</td>
            <td>Кол-во мероприятий</td>
        </tr>
        </tfoot>
    </table>
@endsection
