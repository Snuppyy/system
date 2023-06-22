@extends('layouts.app')

@php
    $page = explode('/',url()->current());
@endphp

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('lib/datetimepicker/daterangepicker.css') }}"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@7/dist/sweetalert2.min.css">
    <style>
        .upload {
            display: none;
        }
    </style>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('lib/moment/moment.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/moment/locale/ru.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/datetimepicker/daterangepicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/EZView/EZView.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/EZView/draggable.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7/dist/sweetalert2.all.min.js"></script>
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
                @if(!isset($_GET['region']))
                    window.location.href = "{{ route('view-questionnaires', ['encoding' => $page[4]]) }}?startDate=" + picker.startDate.format('YYYY-MM-DD') + "&endDate=" + picker.endDate.format('YYYY-MM-DD');
                @else
                    window.location.href = "{{ route('view-questionnaires', ['encoding' => $page[4], 'region' => $_GET['region']]) }}&startDate=" + picker.startDate.format('YYYY-MM-DD') + "&endDate=" + picker.endDate.format('YYYY-MM-DD');
                @endif
            });

            const toast = Swal.mixin({
                toast: true,
                position: 'center',
                showConfirmButton: false,
                timer: 3000
            });

            $('.scanUploader').submit(function () {
                var size = $(this).find('input.upload')[0].files[0].size;
                var ext = $(this).find('input.upload')[0].files[0].name.split('.').pop();
                ext = ext.toLowerCase()
                if (size > 2097152) {
                    toast({
                        type: 'error',
                        title: 'Файл больше 2 мб'
                    });
                    return false;
                }
                if (ext !== 'pdf' && ext !== 'jpg' && ext !== 'jpeg') {
                    toast({
                        type: 'error',
                        title: 'Файл неверного формата'
                    });
                    return false;
                }
            });

            $('.preview').EZView();
        });
    </script>
@endsection

@section('header')
    <div class="sh-breadcrumb">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{ route('index') }}">INTILISH v3.1</a>
            <span class="breadcrumb-item">Опросники</span>
            <span class="breadcrumb-item active">Опросники аутрич-сотрудников</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <div class="sh-pagetitle">

        <div class="input-group">
            <!-- search block -->
        </div><!-- input-group -->

        <div class="sh-pagetitle-left">
            <div class="sh-pagetitle-icon"><i class="icon ion-ios-help-outline"></i></div>
            <div class="sh-pagetitle-title">
                <h2>Введенные анкеты для аутрич-сотрудников</h2>
            </div><!-- sh-pagetitle-left-title -->
        </div><!-- sh-pagetitle-left -->

    </div><!-- sh-pagetitle -->
@endsection

@section('content')
    @if(session('success'))
        <div class="col-lg-12 alert alert-success wd-100p-force text-center">
            {{ session('success') }}
        </div>
    @endif
    <div class="col-12">
        <div class="pd-10 bd mg-b-20">
            <ul class="nav nav-pills flex-column flex-md-row justify-content-center" role="tablist">
                <li class="nav-item"><a class="nav-link{{ $page[4] === 'all' ? ' active' : '' }}"
                                        @if(isset($_GET['region']) && isset($_GET['startDate']) && isset($_GET['endDate']))
                                        href="{{ route('view-questionnaires', ['encoding' => 'all', 'region' => $_GET['region'], 'startDate' => $_GET['startDate'], 'endDate' => $_GET['endDate']]) }}"
                                        @elseif(isset($_GET['startDate']) && isset($_GET['endDate']))
                                        href="{{ route('view-questionnaires', ['encoding' => 'all', 'startDate' => $_GET['startDate'], 'endDate' => $_GET['endDate']]) }}"
                                        @else
                                        href="{{ route('view-questionnaires', ['encoding' => 'all']) }}"
                                        @endif
                                        role="tab">Все</a></li>
                @foreach($questionnaires as $questionnaire)
                    <li class="nav-item"><a class="nav-link{{ $page[4] === $questionnaire->encoding ? ' active' : '' }}"
                                            @if(isset($_GET['region']) && isset($_GET['startDate']) && isset($_GET['endDate']))
                                            href="{{ route('view-questionnaires', ['encoding' => $questionnaire->encoding, 'region' => $_GET['region'], 'startDate' => $_GET['startDate'], 'endDate' => $_GET['endDate']]) }}"
                                            @elseif(isset($_GET['startDate']) && isset($_GET['endDate']))
                                            href="{{ route('view-questionnaires', ['encoding' => $questionnaire->encoding, 'startDate' => $_GET['startDate'], 'endDate' => $_GET['endDate']]) }}"
                                            @else
                                            href="{{ route('view-questionnaires', ['encoding' => $questionnaire->encoding]) }}"
                                            @endif
                                            role="tab">{{ $questionnaire->encoding }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
    @if(Auth::user()->region->id === 0)
        <div class="col-12">
            <div class="pd-10 bd mg-b-20">
                <ul class="nav nav-pills flex-column flex-md-row justify-content-center" role="tablist">
                    <li class="nav-item"><a class="nav-link{{ !isset($_GET['region']) ? ' active' : ''}}"
                                            @if(isset($_GET['startDate']) && isset($_GET['endDate']))
                                            href="{{ route('view-questionnaires', ['encoding' => $page[4], 'startDate' => $_GET['startDate'], 'endDate' => $_GET['endDate']]) }}"
                                            @else
                                            href="{{ route('view-questionnaires', ['encoding' => $page[4]]) }}"
                                            @endif
                                            role="tab">Все</a></li>
                    @foreach($regions as $region)
                        @if($region->id !== 0)
                            <li class="nav-item"><a
                                        class="nav-link{{ ((isset($_GET['region']) ? $_GET['region'] : '') == $region->encoding ? ' active' : '') }}"
                                        @if(isset($_GET['startDate']) && isset($_GET['endDate']))
                                        href="{{ route('view-questionnaires', ['encoding' => $page[4], 'region' => $region->encoding, 'startDate' => $_GET['startDate'], 'endDate' => $_GET['endDate']]) }}"
                                        @else
                                        href="{{ route('view-questionnaires', ['encoding' => $page[4], 'region' => $region->encoding]) }}"
                                        @endif
                                        role="tab">{{ $region->encoding }}</a></li>

                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
    <div class="col-lg-12 alert alert-success wd-100p-force text-center">
        Всего: {{ $data->total() }} анкет<br>
        Уникальных аутрич сотрудников: {{ $outreach }}<br>
        Количество вебинаров: {{ $webinar }} / Анкет: {{ $webinarAns }}<br>
        Количество семинаров: {{ $seminar }} / Анкет: {{ $seminarAns }}<br>
        Уникальных волонтеров: {{ $volunteer }}<br>
        Без загруженных сканированных копий: {{ $noScan }}
    </div>
    <a href="{{ route('view-questionnaires', ['encoding' => $page[4], 'filter' => 'duplicates']) }}" class="text-center card card-body btn btn-danger bg-danger"
       style="display: block; flex-direction: row; cursor: pointer;">
        Показать дубликаты
    </a>
    <div class="text-center card card-body tx-white-8 bg-info bd-0"
         style="display: block; flex-direction: row; cursor: pointer;" id="filterDate">
        <i class="fa fa-calendar"></i>&nbsp;
        <span>Укажите период</span> <i class="fa fa-caret-down"></i>
    </div>
    <a
            class="btn btn-primary btn-block mg-b-10"
            @if(isset($_GET['region']) && $page[4] !== 'all'))
            href="{{ route('view-questionnaires', ['encoding' => $page[4], 'region' => $_GET['region']]) }}"
            @elseif($page[4] !== 'all')
            href="{{ route('view-questionnaires', ['encoding' => $page[4]]) }}"
            @else
            href="{{ route('view-questionnaires', ['encoding' =>'all']) }}"
            @endif
    >
        Сбросить
    </a>
    <table class="table table-hover table-bordered table-primary mg-b-0">
        <thead>
        <tr>
            <td>#</td>
            @if(Auth::user()->region->id === 0)
                <td>Регион</td>
            @endif
            <td>Опросник</td>
            <td>Тип</td>
            <td>Дата</td>
            <td>Автор</td>
            <td>Аутрич-сотрудник</td>
            <td>Управление</td>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $value)
            <tr>
                <td>
                    {{ $value->id }}
                </td>
                @if(Auth::user()->region->id === 0)
                    <td>
                        {{ $value->region }}
                    </td>
                @endif
                <td>
                    {{ $value->questionnaire }}
                </td>
                <td>
                    @if($value->type === 1)
                        ПРЕ
                    @elseif($value->type === 2)
                        ПОСТ
                    @else
                        БЕЗ АНКЕТИРОВАНИЯ
                    @endif
                </td>
                <td>
                    {{ \Carbon\Carbon::parse($value->date)->format('Y-m-d') }}
                </td>
                <td>
                    {{ $value->author }}
                </td>
                <td>
                    {{ $value->outreach }}
                </td>

                @if(($value->author_id === auth()->user()->id || auth()->user()->role <= 2) && ($value->status == 1))
                    @if(!$value->scan)
                        <td>
                            <form action="{{ route('upload-documents', ['type' => 'questionnaires', 'id' => $value->id]) }}"
                                  method="POST" enctype="multipart/form-data" class="scanUploader">
                                @csrf
                                <a href="{{ route('get-questionnaires', ['encoding' => $value->questionnaire, 'id' =>  $value->id]) }}"
                                   class="fa fa-pencil-square text-warning"></a>
                                <a href="{{ route('delete-questionnaires', ['encoding' => $value->questionnaire, 'id' =>  $value->id]) }}"
                                   class="fa fa-trash text-danger"></a>
                                <input accept=".jpg, .jpeg, .pdf" name="file" type="file" class="upload"
                                       onchange="$(this).parent('form').submit()">
                                <a href="javascript:void(0)" onclick="$(this).siblings('input').trigger('click')"
                                   class="fa fa-file-image-o text-danger"></a>
                            </form>
                        </td>
                    @else
                        <td>
                            <a href="{{ route('get-questionnaires', ['encoding' => $value->questionnaire, 'id' =>  $value->id]) }}"
                               class="fa fa-pencil-square text-warning"></a>
                            <a href="{{ route('delete-questionnaires', ['encoding' => $value->questionnaire, 'id' =>  $value->id]) }}"
                               class="fa fa-trash text-danger"></a>
                            <a href="{{ $value->scan }}"
                               class="fa fa-file-image-o text-success preview"></a>
                        </td>
                    @endif
                @elseif($value->status > 1)
                    <td>
                        Запись прошла ревизию
                        <a href="{{ $value->scan }}"
                           class="fa fa-file-image-o text-success preview"></a>
                    </td>
                @else
                    <td>
                        Нет прав управлять записью
                    </td>
                @endif
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td>#</td>
            @if(Auth::user()->region->id === 0)
                <td>Регион</td>
            @endif
            <td>Опросник</td>
            <td>Тип</td>
            <td>Дата</td>
            <td>Автор</td>
            <td>Аутрич-сотрудник</td>
            <td>Управление</td>
        </tr>
        </tfoot>
    </table>
    @if(isset($_GET['startDate']) && isset($_GET['endDate']) && isset($_GET['region']) && isset($_GET['filter']))
        {{ $data->fragment('paginator')->appends(['region' => $_GET['region'], 'startDate' => $_GET['startDate'], 'endDate' => $_GET['endDate'], 'filter' => $_GET['filter']])->links('vendor.pagination.blue') }}
    @elseif(isset($_GET['startDate']) && isset($_GET['endDate']) && isset($_GET['filter']))
        {{ $data->fragment('paginator')->appends(['startDate' => $_GET['startDate'], 'endDate' => $_GET['endDate'], 'filter' => $_GET['filter']])->links('vendor.pagination.blue') }}
    @elseif(isset($_GET['startDate']) && isset($_GET['endDate']) && isset($_GET['region']))
        {{ $data->fragment('paginator')->appends(['region' => $_GET['region'], 'startDate' => $_GET['startDate'], 'endDate' => $_GET['endDate']])->links('vendor.pagination.blue') }}
    @elseif(isset($_GET['startDate']) && isset($_GET['endDate']))
        {{ $data->fragment('paginator')->appends(['startDate' => $_GET['startDate'], 'endDate' => $_GET['endDate']])->links('vendor.pagination.blue') }}
    @elseif(isset($_GET['region']) && isset($_GET['filter']))
        {{ $data->fragment('paginator')->appends(['region' => $_GET['region'], 'filter' => $_GET['filter']])->links('vendor.pagination.blue') }}
    @elseif(isset($_GET['region']))
        {{ $data->fragment('paginator')->appends(['region' => $_GET['region']])->links('vendor.pagination.blue') }}
    @elseif(isset($_GET['filter']))
        {{ $data->fragment('paginator')->appends(['filter' => $_GET['filter']])->links('vendor.pagination.blue') }}
    @else
        {{ $data->fragment('paginator')->links('vendor.pagination.blue') }}
    @endif
@endsection
