@extends('layouts.app')

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
                    window.location.href = "{{ route('questionnaire-view-opu_001') }}?startDate=" + picker.startDate.format('YYYY-MM-DD') + "&endDate=" + picker.endDate.format('YYYY-MM-DD');
                @else
                    window.location.href = "{{ route('questionnaire-view-opu_001', ['region' => $_GET['region']]) }}&startDate=" + picker.startDate.format('YYYY-MM-DD') + "&endDate=" + picker.endDate.format('YYYY-MM-DD');
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
            <span class="breadcrumb-item active">Опросники клиентов</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <div class="sh-pagetitle">

        <div class="input-group">
            <!-- search block -->
        </div><!-- input-group -->

        <div class="sh-pagetitle-left">
            <div class="sh-pagetitle-icon"><i class="icon ion-ios-help-outline"></i></div>
            <div class="sh-pagetitle-title">
                <h2>Введенные анкеты для клиентов</h2>
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
    @if(Auth::user()->region->id === 0)
        <div class="col-12">
            <div class="pd-10 bd mg-b-20">
                <ul class="nav nav-pills flex-column flex-md-row justify-content-center" role="tablist">
                    <li class="nav-item"><a class="nav-link{{ !isset($_GET['region']) ? ' active' : ''}}"
                                            @if(isset($_GET['startDate']) && isset($_GET['endDate']))
                                            href="{{ route('questionnaire-view-opu_001', ['startDate' => $_GET['startDate'], 'endDate' => $_GET['endDate']]) }}"
                                            @else
                                            href="{{ route('questionnaire-view-opu_001') }}"
                                            @endif
                                            role="tab">Все</a></li>
                    @foreach($regions as $region)
                        @if($region->id !== 0)
                            <li class="nav-item"><a
                                        class="nav-link{{ ((isset($_GET['region']) ? $_GET['region'] : '') == $region->encoding ? ' active' : '') }}"
                                        @if(isset($_GET['startDate']) && isset($_GET['endDate']))
                                        href="{{ route('questionnaire-view-opu_001', ['region' => $region->encoding, 'startDate' => $_GET['startDate'], 'endDate' => $_GET['endDate']]) }}"
                                        @elseif(isset($_GET['startDate']) && isset($_GET['endDate']))
                                        href="{{ route('questionnaire-view-opu_001', ['region' => $region->encoding, 'startDate' => $_GET['startDate'], 'endDate' => $_GET['endDate']]) }}"
                                        @else
                                        href="{{ route('questionnaire-view-opu_001', ['region' => $region->encoding]) }}"
                                        @endif
                                        role="tab">{{ $region->encoding }}</a></li>

                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
    <div class="col-lg-12 alert alert-success wd-100p-force text-center">
        Всего: {{ $data->total() }} записей<br>
        Без загруженных сканированных копий: {{ $noScan }}
    </div>
    <a href="{{ route('questionnaire-view-opu_001', ['filter' => 'duplicates']) }}" class="text-center card card-body btn btn-danger bg-danger"
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
            @if(isset($_GET['region'])))
            href="{{ route('questionnaire-view-opu_001', ['region' => $_GET['region']]) }}"
            @else
            href="{{ route('questionnaire-view-opu_001') }}"
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
            <td>Дата</td>
            <td>Автор</td>
            <td>Кабинет доверия</td>
            <td>Код клиента</td>
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
                    {{ \Carbon\Carbon::parse($value->date)->format('Y-m-d') }}
                </td>
                <td>
                    {{ $value->author }}
                </td>
                <td>
                    {{ $value->drop_inCenter }}
                </td>
                <td>
                    {{ $value->encoding }}
                </td>
                <td>
                    {{ $value->f_name . ' ' . $value->s_name }}
                </td>
                @if(($value->author_id === auth()->user()->id || auth()->user()->role <= 2) && ($value->status === 1))
                    @if(!$value->scan)
                        <td>
                            <form action="{{ route('upload-documents', ['type' => 'opu', 'id' => $value->id]) }}"
                                  method="POST" enctype="multipart/form-data" class="scanUploader">
                                @csrf
                                <a href="{{ route('questionnaire-get-opu_001', $value->id) }}#tb"
                                   class="fa fa-pencil-square text-warning" target="_blank"></a>
                                <a href="{{ route('questionnaire-delete-opu_001', $value->id) }}"
                                   class="fa fa-trash text-danger"></a>
                                <input accept=".jpg, .jpeg, .pdf" name="file" type="file" class="upload"
                                       onchange="$(this).parent('form').submit()">
                                <a href="javascript:void(0)" onclick="$(this).siblings('input').trigger('click')"
                                   class="fa fa-file-image-o text-danger"></a>
                            </form>
                        </td>
                    @else
                        <td>
                            <a href="{{ route('questionnaire-get-opu_001', $value->id) }}#tb"
                               class="fa fa-pencil-square text-warning" target="_blank"></a>
                            <a href="{{ route('questionnaire-delete-opu_001', $value->id) }}"
                               class="fa fa-trash text-danger"></a>
                            <a href="{{ $value->scan }}"
                               class="fa fa-file-image-o text-success preview"></a>
                        </td>
                    @endif
                @elseif($value->status !== 1)
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
            <td>Дата</td>
            <td>Автор</td>
            <td>Кабинет доверия</td>
            <td>Код клиента</td>
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
