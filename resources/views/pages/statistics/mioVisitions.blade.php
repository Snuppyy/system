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
            <span class="breadcrumb-item active">Сводка по мониторинговым визитам</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <div class="sh-pagetitle">

        <div class="input-group">
            <!-- search block -->
        </div><!-- input-group -->

        <div class="sh-pagetitle-left">
            <div class="sh-pagetitle-icon"><i class="icon ion-ios-gear-outline"></i></div>
            <div class="sh-pagetitle-title">
                <h2>Сводка по мониторинговым визитам</h2>
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
                window.location.href = "{{ route('mioVisitions') }}?startDate=" + picker.startDate.format('YYYY-MM-DD') + "&endDate=" + picker.endDate.format('YYYY-MM-DD');
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
            href="{{ route('mioVisitions') }}"
    >
        Сбросить
    </a>
    <table class="table table-hover table-bordered table-primary mg-b-0">
        <thead>
        <tr>
            <td>Регион</td>
            <td>Наименование объекта</td>
            <td>Адрес объекта</td>
            <td>Закуп</td>
        </tr>
        </thead>
        <tbody>
        @foreach($mioVisitions as $mioVisition)
            <tr>
                <td>{{ $mioVisition->encoding }}</td>
                <td>{{ $mioVisition->name }}</td>
                <td>{{ $mioVisition->address }}</td>
                <td>
                    @if($miovisition->procurementSyringes2 === 1 || $miovisition->procurementSyringes5 === 1 || $miovisition->procurementSyringes10 === 1 || $miovisition->procurementDoily === 1 || $miovisition->procurementCondomsM === 1 || $miovisition->procurementCondomsW === 1 || $miovisition->procurementHivBlood === 1 || $miovisition->procurementHivSpittle === 1)
                        ЕСТЬ
                    @else
                        НЕТ
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td>Регион</td>
            <td>Наименование объекта</td>
            <td>Адрес объекта</td>
            <td>Закуп</td>
        </tr>
        </tfoot>
    </table>
@endsection
