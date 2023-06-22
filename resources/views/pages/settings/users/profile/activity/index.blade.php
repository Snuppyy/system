@extends('layouts.app')
@php
    //$_SERVER['HTTP_X_REAL_IP'] !== '172.16.1.25' ?  abort(403) : "";/
@endphp
@section('scripts')
    <script src="{{ asset('lib/select2/dist/js/select2.full.js') }}"></script>
    <script src="{{ asset('lib/datetimepicker/jquery.datetimepicker.full.js') }}"></script>
    <script src="{{ asset('lib/mask/jquery.mask.min.js') }}"></script>
    {{--    <script type="text/javascript" src="{{ asset('lib/moment/moment.js') }}"></script>--}}
    <script type="text/javascript" src="{{ asset('lib/moment/locale/ru.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/moment/moment-timezone.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/datetimepicker/daterangepicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/bootstrap/bootstrap.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/jquery.steps/jquery.steps.js') }}"></script>

@endsection

@section('styles')
    <link href="{{ asset('lib/select2/dist/css/select2.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('lib/datetimepicker/daterangepicker.css') }}"/>
    <link href="{{ asset('lib/jquery.steps/jquery.steps.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet">
    <style>
        #activities {
            width: 100%;
        }

        .unselectable {
            -webkit-touch-callout: none; /* iOS Safari */
            -webkit-user-select: none; /* Chrome/Safari/Opera */
            -khtml-user-select: none; /* Konqueror */
            -moz-user-select: none; /* Firefox */
            -ms-user-select: none; /* Internet Explorer/Edge */
            user-select: none;
            /* Non-prefixed version, currently
                                   not supported by any browser */
        }

        td[edited] {
            background: #c9c9c9;
        }

        .select2-close-mask {
            z-index: 2099;
        }

        .select2-container {
            z-index: 3051;
        }
    </style>
@endsection

@section('header')
    <div class="sh-breadcrumb">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{ route('index') }}">INTILISH v3.1</a>
            <a class="breadcrumb-item" href="{{ route('edit-profile', ['id' => $user->id]) }}">Профиль</a>
            <span class="breadcrumb-item active">Деятельность</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <div class="sh-pagetitle">

        <div class="input-group">
            <!-- search block -->
        </div><!-- input-group -->

        <div class="sh-pagetitle-left">
            <div class="sh-pagetitle-icon"><i class="icon ion-ios-contact-outline"></i></div>
            <div class="sh-pagetitle-title">
                <span>Профиль - {{ $user->name  }} {{ $user->position_name }}</span>
                <h2>Деятельность</h2>
            </div><!-- sh-pagetitle-left-title -->
        </div><!-- sh-pagetitle-left -->

    </div><!-- sh-pagetitle -->
@endsection

@section('scriptsFooter')
    <script src="{{ asset('lib/fullcalendar/locale/ru.js') }}"></script>

    <script>

        $('#project').steps({
            headerTag: 'h3',
            bodyTag: 'section',
            enableAllSteps: true,
            enableKeyNavigation: false,
            enablePagination: false,
            autoFocus: true,
            titleTemplate: '<span class="number">#index#</span> <span class="title">#title#</span>',
            cssClass: 'wizard step-equal-width',
            // startIndex: 1,
        });

        $('#activities').fullCalendar({
            header: {
                left: 'today agendaDay agendaWeek month prev next',
                center: 'title',
                right: 'listYear'
            },
            events: {
                url: '{{ route('getAllActivities', ['id' => $user->id]) }}',
                method: 'GET',
                extraParams: {
                    data: {"_token": "{{ csrf_token() }}"}
                },
            },
            eventRender: function (data, element) {
                $(element).popover({
                    title: 'Комментарий:',
                    content: data.description,
                    placement: 'top',
                    trigger: 'hover',
                    container: 'body'
                });
            },
            minTime: "07:00:00",
            maxTime: "19:00:00",
            slotDuration: '00:30:00',
            eventLimit: true,
            defaultView: 'agendaWeek'
        });

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
                showButtonPanel: true,
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
                    "Январь 2020": [moment().month(0).year(2020).startOf("month"), moment().month(0).year(2020).endOf("month")],
                    "Февраль 2020": [moment().month(1).year(2020).startOf("month"), moment().month(1).year(2020).endOf("month")],
                    "Март 2020": [moment().month(2).year(2020).startOf("month"), moment().month(2).year(2020).endOf("month")],
                }
            }, cb);

            $('#filterDate').on('apply.daterangepicker', function (ev, picker) {
                window.location.href = "{{ route('activity-profile', ['id' => $user->id]) }}?startDate=" + picker.startDate.format('YYYY-MM-DD') + "&endDate=" + picker.endDate.format('YYYY-MM-DD'){!! is_numeric($_GET['project']) ? '+"&project='.$_GET['project'].'"' : ''  !!};
            });
        });

        @if(auth()->user()->role <= 3)

        $('#edit').click(function () {
            $(this).addClass('hidden-xs-up');
            $('#save').removeClass('hidden-xs-up');
            $('.copy').removeClass('hidden-xs-up');
            $('table').addClass('unselectable');
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function diffTime(firstDate, secondDate) {
            let getDate = (string) => new Date(0, 0, 0, string.split(':')[0], string.split(':')[1]);
            let different = (getDate(secondDate) - getDate(firstDate));
            let differentRes, hours, minuts;
            if (different > 0) {
                differentRes = different;
                hours = Math.floor((differentRes % 86400000) / 3600000);
                minuts = Math.round(((differentRes % 86400000) % 3600000) / 60000);
            } else {
                differentRes = Math.abs((getDate(firstDate) - getDate(secondDate)));
                hours = Math.floor(24 - (differentRes % 86400000) / 3600000);
                minuts = Math.round(60 - ((differentRes % 86400000) % 3600000) / 60000);
            }
            if (hours < 10) {
                hours = '0' + hours;
            }
            if (minuts < 10) {
                minuts = '0' + minuts;
            }
            return hours + ':' + minuts + ':00';
        }

        $('tbody').on('dblclick', '[editable="true"] td', function () {
            if (!$('#save').hasClass('hidden-xs-up')) {
                $(this).parent().addClass('edited');
                $value = $(this).text();
                $this = $(this);
                if ($(this).attr('class') == 'textarea') {
                    $(this).html('<textarea rows="6" class="form-control editable">' + $value + '</textarea>');
                } else if ($(this).attr('class') == 'date') {
                    $(this).html('<input class="form-control editable date" value="' + $value + '">');
                } else if ($(this).attr('class') == 'start') {
                    $(this).html('<input class="form-control editable time start" value="' + $value + '">');
                } else if ($(this).attr('class') == 'end') {
                    $(this).html('<input class="form-control editable time end" value="' + $value + '">');
                } else if ($(this).attr('class') == 'select') {
                    $user = $(this).siblings('.user').data('user');
                    $date = $(this).siblings('.date').text();
                    $assignment = $(this).data('assignment');
                    $.ajax({
                        type: 'GET',
                        async: false,
                        url: '{{ route('activityGetAssignments') }}',
                        data: 'user=' + $user + '&date=' + $date + '&assignment=' + $assignment,
                        success: function (data) {
                            $this.html(data);
                        }
                    });
                } else if ($(this).attr('class') == 'user') {
                    $user = $(this).context.dataset.user;
                    $assignment = $(this).siblings('.select').data('assignment');
                    $.ajax({
                        type: 'GET',
                        async: false,
                        url: '{{ route('activityGetUsers') }}',
                        data: 'assignment=' + $assignment + '&user=' + $user,
                        success: function (data) {
                            $this.html(data);
                        }
                    });
                }

                $('.editable.time').datetimepicker({
                    format: 'H:i:s',
                    step: 1,
                    mask: '__:__',
                    datepicker: false
                });

                $('.editable.date').datetimepicker({
                    format: 'Y-m-d',
                    step: 1,
                    mask: '____-__-__',
                    timepicker: false,
                    minDate: '2019/10/01',
                    maxDate: '2019/12/31'
                });

                $('.select2').select2();

                $(this).children().focus();
            }

        });

        $('tbody').on('blur', '.editable', function () {
            $value = $(this).val();
            $elem = $(this).parent();
            $old = $elem.data('old');
            if ($value != $old) {
                if ($(this).hasClass('start')) {
                    $elem.siblings('.diff').html(diffTime($value, $elem.siblings('.end').text()));
                    $elem.html($value).attr('edited', 'true');
                } else if ($(this).hasClass('end')) {
                    $elem.siblings('.diff').html(diffTime($elem.siblings('.start').text(), $value));
                    $elem.html($value).attr('edited', 'true');
                } else if (!$(this).hasClass('select2')) {
                    $elem.html($value).attr('edited', 'true');
                }
                $elem.attr('data-toggle', 'tooltip').attr('data-html', 'true').attr('title', $old);
                $('[data-toggle="tooltip"]').tooltip();
            } else {
                $elem.html($value);
            }
        });

        $('tbody').on('change', '.select2.editable', function () {
            $val = $(this).val();
            $responsibility = $('option[value="' + $val + '"]').data('responsibility');
            $assignment = $('option[value="' + $val + '"]').data('assignment-temp');
            $elem = $(this).parent();
            $user = $('option[value="' + $val + '"]').text();
            if ($(this).hasClass('assignments')) {
                $elem.html($responsibility).attr('edited', 'true');
                $elem.attr('data-assignment', $assignment);
                $elem.attr('data-responsibility', $val);
            } else if ($(this).hasClass('users')) {
                $elem.html($user).attr('edited', 'true');
                $elem.attr('data-user', $val);
            }
            $('[data-toggle="tooltip"]').tooltip();
        });

        $('tbody').on('click', '.copy', function () {
            $(this).parents('tr').removeAttr('editable').clone().attr('editable', 'true').insertAfter($(this).parents('tr')).children('.id').html('').parents('tr').removeAttr('id').addClass('bg-white-7').find('.delete').removeClass('hidden-xs-up').parents('tr').addClass('edited').find('.notClone').remove();
        });

        $('tbody').on('click', '.delete', function () {
            $(this).parents('tr').remove();
        });

        $('#save').click(function () {
            $clone = $('#mainTable tr.edited').clone();
            $('#verificationModal').find('.containerVerification').html($clone);
            $('#verificationModal').find('.noModal').remove();
            $('#countTd').html($('#mainTable td[edited]').length);
            $('#countTr').html($('#mainTable tr.edited').length);
            $('#verificationModal').modal('show');
            $('[data-toggle="tooltip"]').tooltip();
        });

        $('#verificationComplite').click(function () {
            $('#verificationTable .edited').each(function (index, element) {

                $id = $(element).children('.id').text();
                $user = $(element).children('.user').attr('data-user');
                $assignment = $(element).children('.select').attr('data-assignment');
                $responsibility = $(element).children('.select').attr('data-responsibility');
                $comment = $(element).children('.textarea').text();
                $date = $(element).children('.date').text();
                $start = $(element).children('.start').text();
                $end = $(element).children('.end').text();

                $.ajax({
                    type: 'POST',
                    async: false,
                    url: '{{ route('activitySave') }}',
                    data: 'id=' + $id + '&user=' + $user + '&assignment=' + $assignment + '&responsibility=' + $responsibility + '&comment=' + $comment + '&date=' + $date + '&start=' + $start + '&end=' + $end,
                    success: function (data) {
                        console.log(data);
                        location.reload();
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            });

        });

        @endif

    </script>
@endsection

@section('content')
    @if(isset($_GET['startDate']) && isset($_GET['startDate']))
        @php
            session([
            'startDate' => $_GET['startDate'],
            'endDate' => $_GET['endDate'],
            ]);
        @endphp
    @endif
{{--    @if(auth()->user()->id == 9 || auth()->user()->id == 37 || auth()->user()->id == 98 || auth()->user()->id == 5)--}}
{{--        <div class="btn-block btn-group mg-0-force pd-0-force">--}}
{{--            <a href="{{ route('activity-profile', ['user' => $user->id]) }}" class="btn btn-primary col-lg-6">ГФ-ТБ</a>--}}
{{--            <a href="?project=6" class="btn btn-primary col-lg-6">Nova</a>--}}
{{--            <a href="?project=7" class="btn btn-primary col-lg-6">TB-SERVIS</a>--}}
{{--        </div>--}}
{{--    @endif--}}
    <a class="btn btn-primary btn-block mg-b-10 fixed-top"
       style="left: 240px; top: 150px; padding-right: 240px"
       href="{{ route('ActivityStatisticsProject', ['project' => $_GET['project']  ?? $position->project]) }}">
        Назад
    </a>
    <div class="text-center card card-body tx-white-8 bg-info bd-0"
         style="display: block; flex-direction: row; cursor: pointer;" id="filterDate">
        <i class="fa fa-calendar"></i>&nbsp;
        <span>Укажите период</span> <i class="fa fa-caret-down"></i>
    </div>
    <a class="btn btn-primary btn-block mg-b-10"
       href="{{ route('activity-profile', ['id' => $user->id]) }}{!! is_numeric($_GET['project']) ? '?project='.$_GET['project'] : '' !!}">
        Сбросить
    </a>
    <div class="col-lg-12 wd-100p-force">
        <div id="project">
            <h3>Листинг</h3>
            <section>
                <div class="alert alert-success wd-100p tx-center">{{ $user->name  }} - {{ $user->name == 'Елена Девятова' ? 'Национальный менеджер' :$position->position }}
                    - {{ $position->encoding }}</div>
                <div class="alert alert-success wd-100p tx-center">Общая выработка: {{ $timeDiff }}</div>
                <button id="save" class="btn btn-success fixed-top btn-block hidden-xs-up"
                        style="left: 240px; top: 100px; padding-right: 240px">Сохранить
                </button>
                <button id="edit" class="btn btn-warning fixed-top btn-block"
                        style="left: 240px; top: 100px; padding-right: 240px">Редактировать
                </button>
                <table id="mainTable" class="table table-primary table-hover table-bordered">
                    <thead>
                    <tr>
                        <th>№</th>
                        <th>Сотрудник</th>
                        <th>Функциональная обязанность</th>
                        <th>Комментарий</th>
                        <th>Дата</th>
                        <th>Начало</th>
                        <th>Конец</th>
                        <th>Выработка</th>
                        <th>Управление</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($activities as $activity)
                        <tr {{ $activity->status === 1 ? 'editable=true' : '' }} id="activity-{{ $activity->id }}">
                            <td class="id">{{ $activity->id }}</td>
                            <td data-old="{{ $activity->user_name }}" class="user" data-user="{{ $activity->user_id }}"
                                nowrap>{{ $activity->user_name }}</td>
                            <td data-old="{{ $responsibilitiesNames[$activity->responsibility_a_id ?? $activity->responsibility_h_id ?? $activity->responsibility_s_id] }}" class="select"
                                data-assignment="{{ $activity->assignment_id }}">{{ $responsibilitiesNames[$activity->responsibility_a_id ?? $activity->responsibility_h_id ?? $activity->responsibility_s_id] }}</td>
                            <td data-old="{{ $activity->comment }}" class="textarea">{{ $activity->comment }}</td>
                            <td data-old="{{ $activity->date->format('Y-m-d') }}" class="date"
                                nowrap>{{ $activity->date->format('Y-m-d') }}</td>
                            <td data-old="{{ $activity->start }}" class="start" nowrap>{{ $activity->start }}</td>
                            <td data-old="{{ $activity->end }}" class="end" nowrap>{{ $activity->end }}</td>
                            <td class="diff"
                                nowrap>{{ $activity->diff < 0 ? date('H:i:s', strtotime('18:00:00')-strtotime(str_replace('-', '', $activity->diff))) : $activity->diff }}</td>
                            <td class="noModal" nowrap>
                                @if($activity->status === 1)
                                    <a href="{{ route('activity-profile-edit', ['id' => $activity->id, 'project' => $position->project]) }}"
                                       class="notClone fa fa-pencil-square text-warning"></a>
                                    <a href="{{ route('activity-profile-delete', ['id' => $activity->id, 'project' => $position->project]) }}"
                                       class="notClone fa fa-trash text-danger"></a>
                                @endif
                                @if(auth()->user()->role <= 3)
                                    <a href="{{ $activity->status === 1 ? route('activity-profile-supervision', $activity->id) : 'javascript:void(0)' }}"
                                       class="notClone fa fa-calendar-check {{ $activity->status === 1 ? 'text-info' : 'text-success' }}"></a>
                                    <a href="javascript:void(0)"
                                       class="fa fa-copy text-info copy hidden-xs-up"></a>
                                    <a href="javascript:void(0)"
                                       class="fa fa-trash text-danger delete hidden-xs-up"></a>
                                    @if($activity->assignment_status === 1)
                                        <a href="{{ route('activity-assignment-supervision', $activity->assignment_id) }}"
                                           class="notClone fa fa-calendar-check text-warning"></a>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>№</th>
                        <th>Сотрудник</th>
                        <th>Функциональная обязанность</th>
                        <th>Комментарий</th>
                        <th>Дата</th>
                        <th>Начало</th>
                        <th>Конец</th>
                        <th>Выработка</th>
                        <th>Управление</th>
                    </tr>
                    </tfoot>
                </table>
            </section>
            <h3>Функциональные обязанности</h3>
            <section>
                @if($_GET['startDate'] && $_GET['endDate'])
                    @php
                        $start = \Carbon\Carbon::parse($_GET['startDate']);
                        $end = \Carbon\Carbon::parse($_GET['endDate']);

                        $diffDate = $start->diffInDays($end)+1;
                        $diffDays = $diffDate - 15;
                    @endphp
                    <div style="overflow-x: auto;">
                        <table class="table table-primary table-hover table-bordered" id="table" cellspacing="0"
                               width="100%" style="overflow-x: visible">
                            <thead>
                            <tr>
                                <th rowspan="2">ФИО</th>
                                <th rowspan="2">Должность</th>
                                <th colspan="{{ $diffDate }}">Числа месяца</th>
                            </tr>
                            <tr>
                                @php
                                    for ($i = 1; $i <= $diffDate; $i++){
                                        echo '<th>'.$i.'</th>';
                                    }
                                @endphp
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->position_name }}</td>
                                @for($j = 1; $j <= $diffDate; $j++)
                                    @php
                                        $return = explode(':', $timeSheet[$j]);
                                    @endphp
                                    <td nowrap>{{ $return[0] ? $return[0].':'.$return[1] : 'В/П' }}</td>
                                @endfor
                            </tr>
                            </tbody>
                        </table>
                    </div>
                @endif
                    <div class="alert alert-success wd-100p tx-center">{{ $user->name  }} - {{ $position->position }}
                        - {{ $position->encoding }}</div>
                <div class="alert alert-success wd-100p tx-center">Общая выработка: {{ $timeDiff }}</div>
                <table class="table table-primary table-hover table-bordered">
                    <thead>
                    <tr>
                        <th>Функциональная обязанность</th>
                        <th>Общая выработка</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $types[1] = 'Профильная деятельность';
                        $types[2] = 'Административная деятельность';
                        $types[3] = 'Методическая деятельность';
                        $types[4] = 'Хозяйственная деятельность';
                        $types[5] = 'Волонтерство';
                    @endphp

                    @foreach($responsibilities as $responsibility)
                        @php
                            if($responsibility->type !== $tempType){
                                echo '<tr class="tx-bold"><td class="tx-center">'.$types[$responsibility->type].'</td><td class="tx-center">'.$typesDiff[$responsibility->type].'</td></tr>';
                                $tempType = $responsibility->type;
                            }
                        @endphp
                        <tr>
                            <td>{{ $responsibility->name }}</td>
                            <td>{{ $responsibility->diff ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>Функциональная обязанность</th>
                        <th>Общая выработка</th>
                    </tr>
                    </tfoot>
                </table>
            </section>
            <h3>Календарь деятельности</h3>
            <section>
                <div id="activities"></div>
            </section>
        </div>

        <div class="bg-black-7 modal fade bd-example-modal-lg" id="verificationModal" role="dialog"
             data-backdrop="false" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered wd-100p" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="exampleModalCenterTitle">Подтвердите изменение</h2>
                    </div>
                    <div class="modal-body">
                        <h5>
                            Было изменено: <span id="countTr"></span> строк(и) и <span id="countTd"></span> ячеек(и)
                        </h5>
                        <table id="verificationTable" class="table table-primary table-hover table-bordered">
                            <thead>
                            <tr>
                                <th>№</th>
                                <th>Сотрудник</th>
                                <th>Функциональная обязанность</th>
                                <th>Комментарий</th>
                                <th>Дата</th>
                                <th>Начало</th>
                                <th>Конец</th>
                                <th>Выработка</th>
                            </tr>
                            </thead>
                            <tbody class="containerVerification">

                            </tbody>
                            <tfoot>
                            <tr>
                                <th>№</th>
                                <th>Сотрудник</th>
                                <th>Функциональная обязанность</th>
                                <th>Комментарий</th>
                                <th>Дата</th>
                                <th>Начало</th>
                                <th>Конец</th>
                                <th>Выработка</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="verificationClose">
                            Отменить сохранения
                        </button>
                        <button type="button" class="btn btn-primary" id="verificationComplite">Сохранить изменения
                        </button>
                    </div>
                </div>
            </div>
        </div>
@endsection