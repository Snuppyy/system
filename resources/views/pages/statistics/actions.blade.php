@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('lib/datetimepicker/daterangepicker.css') }}"/>
    <link href="{{ asset('lib/datatables/jquery.dataTables.css') }}" rel="stylesheet">
    <style>
        .dataTables_wrapper{
            width: 100%;
        }
    </style>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('lib/moment/moment.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/moment/locale/ru.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/datetimepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('lib/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('lib/datatables-responsive/dataTables.responsive.js') }}"></script>
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
                <h2>Сводка по мероприятиям</h2>
            </div><!-- sh-pagetitle-left-title -->
        </div><!-- sh-pagetitle-left -->

    </div><!-- sh-pagetitle -->
@endsection

@section('scriptsFooter')
    <script type="text/javascript">
        $(function () {

            $('.datatable').DataTable({
                "paging": false,
                "autoWidth": false,
                responsive: true,
                language: {
                    searchPlaceholder: 'Поиск..',
                    sSearch: '',
                }
            });

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
                window.location.href = "{{ route('actions') }}?startDate=" + picker.startDate.format('YYYY-MM-DD') + "&endDate=" + picker.endDate.format('YYYY-MM-DD');
            });
        });
    </script>
@endsection

@section('content')
    <div class="text-center card card-body tx-white-8 bg-info bd-0"
         style="display: block; flex-direction: row; cursor: pointer;" id="filterDate">
        <i class="fa fa-calendar"></i>&nbsp;
        <span>Укажите период</span> <i class="fa fa-caret-down"></i>
    </div>
    <a
            class="btn btn-primary btn-block mg-b-10"
            href="{{ route('actions') }}"
    >
        Сбросить
    </a>
    <h3 class="tx-center col-12 mg-t-50">Общее количество мероприятий</h3>
    <table class="table table-hover table-bordered table-primary mg-b-0">
        <thead>
        <tr>
            <td>Кол-во мероприятий</td>
            <td>Кол-во аутрич-сотрудников</td>
            <td>Кол-во ассистентов</td>
            <td>Кол-во волонтеров</td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>{{ $actionsAll->actions }}</td>
            <td>{{ $actionsAllOutreach }}</td>
            <td>{{ $actionsAllAssistant }}</td>
            <td>{{ $actionsAll->volunteers }}</td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
            <td>Кол-во мероприятий</td>
            <td>Кол-во аутрич-сотрудников</td>
            <td>Кол-во ассистентов</td>
            <td>Кол-во волонтеров</td>
        </tr>
        </tfoot>
    </table>

    <h3 class="tx-center col-12 mg-t-50">Общее количество вебинаров</h3>
    <table class="table table-hover table-bordered table-primary mg-b-0">
        <thead>
        <tr>
            <td>Кол-во мероприятий</td>
            <td>Кол-во аутрич-сотрудников</td>
            <td>Кол-во ассистентов</td>
            <td>Кол-во волонтеров</td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>{{ $actionsWebinar->actions }}</td>
            <td>{{ $actionsWebinarOutreach }}</td>
            <td>{{ $actionsWebinarAssistant }}</td>
            <td>{{ $actionsWebinar->volunteers }}</td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
            <td>Кол-во мероприятий</td>
            <td>Кол-во аутрич-сотрудников</td>
            <td>Кол-во ассистентов</td>
            <td>Кол-во волонтеров</td>
        </tr>
        </tfoot>
    </table>

    <h3 class="tx-center col-12 mg-t-50">Общее количество семинаров</h3>
    <table class="table table-hover table-bordered table-primary mg-b-0">
        <thead>
        <tr>
            <td>Кол-во мероприятий</td>
            <td>Кол-во аутрич-сотрудников</td>
            <td>Кол-во ассистентов</td>
            <td>Кол-во волонтеров</td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>{{ $actionsNoWebinar->actions }}</td>
            <td>{{ $actionsNoWebinarOutreach }}</td>
            <td>{{ $actionsNoWebinarAssistant }}</td>
            <td>{{ $actionsNoWebinar->volunteers }}</td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
            <td>Кол-во мероприятий</td>
            <td>Кол-во аутрич-сотрудников</td>
            <td>Кол-во ассистентов</td>
            <td>Кол-во волонтеров</td>
        </tr>
        </tfoot>
    </table>

    <h3 class="tx-center col-12 mg-t-50">Очные мероприятия по темам</h3>
    <table class="table table-hover table-bordered table-primary mg-b-0">
        <thead>
        <tr>
            <td>Тема мероприятия</td>
            <td>Кол-во мероприятий</td>
            <td>Кол-во аутрич-сотрудников</td>
            <td>Кол-во ассистентов</td>
            <td>Кол-во волонтеров</td>
        </tr>
        </thead>
        <tbody>
        @foreach($actionsThemes as $actionsTheme)
            <tr>
                <td>{{ $actionsTheme->encoding }}</td>
                <td>{{ $actionsCount[$actionsTheme->id] }}</td>
                <td>{{ round($actionsTheme->outreach/2, 0) }}</td>
                <td>{{ round($actionsTheme->assistant/2, 0) }}</td>
                <td>{{ round($actionsTheme->volunteers, 0) }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td>Тема мероприятия</td>
            <td>Кол-во мероприятий</td>
            <td>Кол-во аутрич-сотрудников</td>
            <td>Кол-во ассистентов</td>
            <td>Кол-во волонтеров</td>
        </tr>
        </tfoot>
    </table>

    <h3 class="tx-center col-12 mg-t-50">Сводка по мероприятиям</h3>
    <table class="datatable col-12 table table-hover table-bordered table-primary mg-b-0"  style="width:100%">
        <thead>
        <tr>
            <td>Дата</td>
            <td>Вебинар</td>
            <td>Тема</td>
            <td>Регион</td>
            <td>Кол-во аутрич сотрудников</td>
            <td>Кол-во ассистентов</td>
            <td>Кол-во волонтеров</td>
        </tr>
        </thead>
        <tbody>
        @foreach($actionsRegionsAll as $data)
            <tr>
                <td>{{ $data->date->format('Y-m-d') }}</td>
                <td>{{ $data->webinar ? 'ДА' : 'НЕТ' }}</td>
                <td>{{ $data->questionnaire }}</td>
                <td>{{ $data->region }}</td>
                <td>{{ round($data->outreach/2, 0) }}</td>
                <td>{{ round($data->assistant/2, 0) }}</td>
                <td>{{ round($data->volunteer, 0) }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td>Дата</td>
            <td>Вебинар</td>
            <td>Тема</td>
            <td>Регион</td>
            <td>Кол-во аутрич сотрудников</td>
            <td>Кол-во ассистентов</td>
            <td>Кол-во волонтеров</td>
        </tr>
        </tfoot>
    </table>

    <h3 class="tx-center col-12 mg-t-50">Сводка по анкентируемым</h3>
    <table class="datatable col-12 table table-hover table-bordered table-primary mg-b-0"  style="width:100%">
        <thead>
        <tr>
            <td>Дата</td>
            <td>Вебинар</td>
            <td>Тема</td>
            <td>Регион</td>
            <td>Аутрич сотрудник с ПРЕ и ПОСТ</td>
        </tr>
        </thead>
        <tbody>
        @foreach($actionsOutreachesAll as $data)
            <tr>
                <td>{{ $data->date->format('Y-m-d') }}</td>
                <td>{{ $data->webinar ? 'ДА' : 'НЕТ' }}</td>
                <td>{{ $data->questionnaire }}</td>
                <td>{{ $data->region }}</td>
                <td>{{ $data->outreach }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td>Дата</td>
            <td>Вебинар</td>
            <td>Тема</td>
            <td>Регион</td>
            <td>Аутрич сотрудник с ПРЕ и ПОСТ</td>
        </tr>
        </tfoot>
    </table>

@endsection
