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
                window.location.href = "{{ route('StatisticsClientsOPT', ['region' => $page[7]]) }}?startDate=" + picker.startDate.format('YYYY-MM-DD') + "&endDate=" + picker.endDate.format('YYYY-MM-DD');
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
                <h2>Результаты первичной оценки</h2>
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
                           href="{{ route('StatisticsClientsOPT', ['region' => 'all', 'startDate' => $_GET['startDate'], 'endDate' => $_GET['endDate']]) }}"
                           @else
                           href="{{ route('StatisticsClientsOPT', ['region' => 'all']) }}"
                           @endif
                           role="tab">Все</a>
                    </li>
                    @foreach($regions as $region)
                        @if($region->id !== 0)
                            <li class="nav-item">
                                <a class="nav-link{{ $page[7] == $region->encoding ? ' active' : '' }}"
                                   @if(isset($_GET['startDate']) && isset($_GET['endDate']))
                                   href="{{ route('StatisticsClientsOPT', ['region' => $region->encoding, 'startDate' => $_GET['startDate'], 'endDate' => $_GET['endDate']]) }}"
                                   @else
                                   href="{{ route('StatisticsClientsOPT', $region->encoding) }}"
                                   @endif
                                   role="tab">{{ $region->encoding }}</a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
    <a class="btn btn-success btn-block"
       href="javascript:void(0);"
       data-target="#results"
       data-toggle="modal"
       role="tab">Результаты анкет</a>
    <div class="col-lg-12 alert alert-success wd-100p-force text-center">
        Всего анкет: {{ $data['count'] }}<br>
    </div>
    <div class="text-center card card-body tx-white-8 bg-info bd-0"
         style="display: block; flex-direction: row; cursor: pointer;" id="filterDate">
        <i class="fa fa-calendar"></i>&nbsp;
        <span>Укажите период</span> <i class="fa fa-caret-down"></i>
    </div>
    <a
            class="btn btn-primary btn-block mg-b-10"
            href="{{ route('StatisticsClientsOPT', ['region' => $page[7]]) }}"
    >
        Сбросить
    </a>
    <table class="table table-hover table-bordered table-primary">
        <thead>
        <th>#</th>
        <th>регион</th>
        <th>Автор</th>
        <th>Клиент</th>
        <th>Место</th>
        </thead>
        <tbody>
        @foreach($data['data'] as $datum)
            <tr>
                <td>{{ $datum->id }}</td>
                <td>{{ $datum->region }}</td>
                <td>{{ $datum->author }}</td>
                <td>{{ $datum->s_name . ' ' . $datum->f_name }}</td>
                <td>{{ $datum->place }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <th>#</th>
        <th>регион</th>
        <th>Автор</th>
        <th>Клиент</th>
        <th>Место</th>
        </tfoot>
    </table>

    <div id="results" class="modal fade">
        <div class="modal-dialog modal-lg" style="width: 100%">
            <div class="modal-content tx-size-sm">
                <div class="modal-header pd-x-20">
                    <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Результат анкет</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <table class="table table-hover table-bordered table-primary mg-b-0">
                        <thead>
{{--                        <th>#</th>--}}
                        <th>Утверждение</th>
                        <th>ответы</th>
<th>количество</th>
<th>процент</th>
                        </thead>
                        <tbody>
                        @foreach($counts as $question => $datas)
                            @php $temp = ''; @endphp
                            @php $i = 0; @endphp
                            @php $tempTR = ''; @endphp
                            @php $countsRows = count($datas); @endphp
                            @foreach($datas as $key => $value)
                                @php
                                    $val = $value->count ? $value->count : $value;
                                @endphp
                                @if($temp === '')
                                    @php
                                        if($countsRows == 2 && $question <> 'sex'){
                                            $temp .= '<td>';
                                            $temp .= $key == '0' ? __('opt.not') : __('opt.yes');
                                            $temp .= '</td><td>'.$val.'</td><td>'.round((($val / $data['count'])*100), 1).'%</td>';
                                        } else {
                                            $temp .= '<td>'.__('opt.'.$question.'_'.$key).'</td><td>'.$val.'</td><td>'.round((($val / $data['count'])*100), 1).'%</td>';
                                        }
                                    @endphp
                                @else
                                    @php
                                        $tempTR .= '<tr><td>';
                                        if($countsRows == 2 && $question <> 'sex'){
                                            $tempTR .= $key == '0' ? __('opt.not') : __('opt.yes');
                                        } else {
                                            $tempTR .= __('opt.'.$question.'_'.$key);
                                        }
                                        $tempTR .= '</td><td>'.$val.'</td><td>'.round((($val / $data['count'])*100), 1).'%</td></tr>';
                                    @endphp
                                @endif
                                @php
                                    $i++;
                                @endphp
                            @endforeach
                            <tr>
                                <td rowspan="{{ $i }}">
                                    @lang('opt.'.$question)
                                </td>
                                {!! $temp !!}
                            </tr>
                            {!! $tempTR !!}
                        @endforeach
                        </tbody>
                        <tfoot>
{{--                        <th>#</th>--}}
                        <th>Утверждение</th>
                        <th>ответы</th>
                        <th>количество</th>
<th>процент</th>
                        </tfoot>
                    </table>
                </div><!-- modal-body -->
            </div>
        </div><!-- modal-dialog -->
    </div>
@endsection
