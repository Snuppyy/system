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
            }, cb);

            $('#filterDate').on('apply.daterangepicker', function (ev, picker) {
                window.location.href = "{{ route('ActivityStatisticsProject', ['project' => $project]) }}?startDate=" + picker.startDate.format('YYYY-MM-DD') + "&endDate=" + picker.endDate.format('YYYY-MM-DD');
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
{{--    <a href="{{ route('ActivityStatisticsClients', ['project' => $project, 'filter' => 'year']) }}"--}}
{{--       class="btn btn-warning btn-block mg-b-10">--}}
{{--        Статитиска по мероприятиям--}}
{{--    </a>--}}

    <div class="text-center card card-body tx-white-8 bg-info bd-0"
         style="display: block; flex-direction: row; cursor: pointer;" id="filterDate">
        <i class="fa fa-calendar"></i>&nbsp;
        <span>Укажите период</span> <i class="fa fa-caret-down"></i>
    </div>
    <a
            class="btn btn-primary btn-block mg-b-10"
            href="{{ route('ActivityStatisticsProject', ['project' => $project]) }}"
    >
        Сбросить
    </a>

    <h4 class="text-center wd-100p mg-20">Статистика по сотрудникам</h4>
    <table class="table table-primary table-bordered table-hover">
        <thead>
        <th>
            #
        </th>
        <th>
            сотрудник
        </th>
        <th>
            регион
        </th>
        <th>
            должность
        </th>
        <th>
            общая выработка по времени
        </th>
        </thead>
        <tbody>
        @foreach($usersInfo as $region => $users)
            @foreach($users as $id => $data)
                @forelse($activitiesUsers as $activitiesUser)
                    @if($activitiesUser->id === $id)
                        <tr class="{{ $activitiesUser->status === 0 ? 'bg-delicate' : '' }}" style="cursor: pointer"
                            onclick="location.href='{{ route('activity-profile', ['id' => $activitiesUser->id]) }}{{ $project <> 4 ? '?project='.$project : '' }}'">
                            <td>
                                {{ $activitiesUser->id }}
                            </td>
                            <td nowrap>
                                {{ $activitiesUser->name }}
                            </td>
                            <td>
                                {{ $data['region'] }}
                            </td>
                            <td>
                                {{ $data['position'] }}
                            </td>
                            <td>
                                {{ $activitiesUser->sum }}
                            </td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="8" class="text-center">
                            НЕТ ДАННЫХ
                        </td>
                    </tr>
                @endforelse
            @endforeach
        @endforeach
        </tbody>
        <tfoot>
        <th>
            #
        </th>
        <th>
            сотрудник
        </th>
        <th>
            регион
        </th>
        <th>
            должность
        </th>
        <th>
            общая выработка по времени
        </th>
        </tfoot>
    </table>
@endsection
