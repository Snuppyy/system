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

        .badge-purple {
            color: #fff;
            background-color: #8024FE;
        }

        tr {
            cursor: pointer;
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
            <div class="sh-pagetitle-title mg-r-30-force">
                <span>Профиль - {{ $user->name  }} {{ $user->position_name }}</span>
                <h2>Деятельность</h2>
            </div><!-- sh-pagetitle-left-title -->

            <div id="filterMenu" class="wd-100p-force">
                <div class="pd-10-force text-center card card-body tx-white-8 bg-info bd-0 col-5 float-left mg-t-5-force mg-b-10-force"
                     style="display: block; flex-direction: row; cursor: pointer;" id="filterDate">
                    <i class="fa fa-calendar"></i>&nbsp;
                    <span>Укажите период</span> <i class="fa fa-caret-down"></i>
                </div>
                <div class="btn-group col-7 pd-r-0-force  float-right">
                    @foreach($positions as $positionData)
                        <button class="pd-10-force btn btn-info btn-block filterProject mg-t-5-force">{{ $projects[$positionData->project] }}</button>
                    @endforeach
                </div>
                <div class="btn-group wd-100p-force pd-r-0-force float-right mg-b-10">
                    @if(auth()->user()->role <= 2)
                        <a class="btn btn-info btn-block mg-t-5-force"
                           href="{{ route('ActivityDump', ['id' => $user->id]) }}">
                            Архивировать
                        </a>
                        <a class="btn btn-success btn-block mg-t-5-force"
                           href="{{ route('ActivityVerification', ['user' => $user->id, 'project' => 'all', 'start' => $filter[0], 'end' => $filter[1]]) }}">
                            Верифицировать
                        </a>
                    @endif
                    <a class="btn btn-warning btn-block mg-t-5-force"
                       id="edit"
                       href="JavaScript:void(0)">
                        Редактировать
                    </a>
                    <a class="btn btn-success btn-block mg-t-5-force hidden-xs-up"
                       id="save"
                       href="JavaScript:void(0)">
                        Сохранить
                    </a>
                    <a class="btn btn-primary btn-block mg-t-5-force"
                       data-toggle="modal" data-target="#reportCardModal"
                       href="JavaScript:void(0)">
                        Акты выполненных работ
                    </a>
                </div>

            </div>
        </div><!-- sh-pagetitle-left -->

    </div><!-- sh-pagetitle -->
@endsection

@section('scriptsFooter')
    <script src="{{ asset('lib/fullcalendar/locale/ru.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/moment/moment.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/moment/locale/ru.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/datetimepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('lib/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('lib/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script>

        function sectoTime(s) {
            var fm = [
                Math.floor(s / 60 / 60), // HOURS
                Math.floor(s / 60) % 60, // MINUTES
                s % 60 // SECONDS
            ];
            return $.map(fm, function (v, i) {
                return ((v < 10) ? '0' : '') + v;
            }).join(':');
        }

        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

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

        function zebra() {
            $('table tbody tr').removeClass('bg-white');
            color = '';
            $('table tbody tr').each(function (row) {
                $(this).find('td:eq(5)').each(function (cell) {
                    var check = true;
                    var nextRow = row + 1;
                    var currentDate = $(this).html();
                    var nextDate = $('table tbody tr:eq(' + nextRow + ') td:eq(5)').html();

                    if (currentDate === nextDate) {
                        $('table tbody tr:eq(' + row + ')').addClass(color);
                    } else if (currentDate !== nextDate) {
                        $('table tbody tr:eq(' + row + ')').addClass(color);
                        if (color === '') color = 'bg-white';
                        else color = ''
                    }
                });
            });
        }


        table = $('.datatable').on('init.dt', function () {
            zebra();
            $('#list_filter label input').addClass('col-3');
            $('#list_filter label').addClass('row').append('<div id="diff" class="alert alert-success col-9 mg-0-force text-center pd-t-10 pd-b-10 diff-stats">{{ $diff->find(2)->sum ?? '00:00:00' }} / {{ $diff->find(1)->sum ?? '00:00:00' }}</div>');
        }).DataTable({
            responsive: true,
            "paging": false,
            "autoWidth": false,
            "order": [[5, "asc"], [6, "asc"], [7, "asc"]],
            language: {
                searchPlaceholder: 'Поиск..',
                sSearch: '',
            }
        });

        $('.dataTables_length select').select2({minimumResultsForSearch: Infinity});


        table.on('draw.dt', function () {
            var statusTrue = 0;
            var statusFalse = 0;

            $('table#list tbody tr').each(function (row) {
                var status = $(this).children('td:eq(8)').data('verification');
                var time = $(this).children('td:eq(8)').data('time');
                statusTrue += status == true ? parseInt(time) : 0;
                statusFalse += status == false ? parseInt(time) : 0;
            });


                $('#diff').text(sectoTime(statusTrue) + ' / ' + sectoTime(statusFalse));

            zebra();
        });

        $.fn.dataTable.ext.search.push(
            function (settings, data, dataIndex, rowData, counter) {
                var values = [];

                $('.filterProject.btn-primary').each(function (i) {
                    values[i] = $(this).text();
                });

                if (values.includes(data[2]) || values.length == 0) {
                    return true;
                }
                return false;
            }
        );

        $('.filterProject').click(function () {
            $(this).toggleClass('btn-primary');
            $(this).toggleClass('btn-info');
            table.draw();
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

            $year1 = moment().format('YYYY');
            $year2 = moment().format('YYYY');
            $year3 = moment().format('YYYY');

            if (moment().format('M') == 1) {
                $year1 = moment().format('YYYY')-1;
                $year2 = moment().format('YYYY')-1;
            } else if (moment().format('M') == 2){
                $year1 = moment().format('YYYY')-1;
            }

            let $month1 = moment().month(moment().format('M') - 3).format('MMMM') + ' ' + $year1;
            let $month2 = moment().month(moment().format('M') - 2).format('MMMM') + ' ' + $year2;
            let $month3 = moment().month(moment().format('M') - 1).format('MMMM') + ' ' + $year3;

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
                    [capitalizeFirstLetter($month1)]: [moment().month(moment().format('M') - 3).year($year1).startOf('month'), moment().month(moment().format('M') - 3).year($year1).endOf('month')],
                    [capitalizeFirstLetter($month2)]: [moment().month(moment().format('M') - 2).year($year2).startOf('month'), moment().month(moment().format('M') - 2).year($year2).endOf('month')],
                    [capitalizeFirstLetter($month3)]: [moment().month(moment().format('M') - 1).year($year3).startOf('month'), moment().month(moment().format('M') - 1).year($year3).endOf('month')],
                }
            }, cb);

            $('#filterDate').on('apply.daterangepicker', function (ev, picker) {
                window.location.href = "{{ route('ActivityUser', ['id' => $user->id]) }}?startDate=" + picker.startDate.format('YYYY-MM-DD') + "&endDate=" + picker.endDate.format('YYYY-MM-DD'){!! is_numeric($_GET['project']) ? '+"&project='.$_GET['project'].'"' : ''  !!};
            });
        });

        $('table#list tbody').on('click', 'tr', function () {
            if (window.event.shiftKey) {
                var params = 'toolbar=no,menubar=no';
                var id = $(this).data('id');
                window.open('{{ route('ActivityStatistic', ['id' => '']) }}/' + id, '_blank', params)
            }
        });

        $('#edit').click(function () {
            $(this).addClass('hidden-xs-up');
            $('#save').removeClass('hidden-xs-up');
            $('.copy').removeClass('hidden-xs-up');
            $('table#list').addClass('unselectable');
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        $('table#list tbody').on('dblclick', '[editable="true"] td', function () {
            if (!$('#save').hasClass('hidden-xs-up')) {
                $(this).parent().addClass('edited');
                $value = $(this).text();
                $this = $(this);
                console.log($(this).attr('class'));
                if ($(this).hasClass('textarea')) {
                    $(this).html('<textarea rows="6" class="form-control editable">' + $value + '</textarea>');
                } else if ($(this).hasClass('date')) {
                    $(this).html('<input class="form-control editable date" value="' + $value + '">');
                } else if ($(this).hasClass('start')) {
                    $(this).html('<input class="form-control editable time start" value="' + $value + '">');
                } else if ($(this).hasClass('end')) {
                    $(this).html('<input class="form-control editable time end" value="' + $value + '">');
                } else if ($(this).hasClass('responsibility')) {
                    $.ajax({
                        type: 'get',
                        url: '{{ route('activityGetAssignments') }}',
                        data: 'project=' + $this.data('project') + '&user=' + $this.data('user') + '&assignment=' + $this.data('assignment'),
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

                $('.editable.responsibility').select2();

                $('.editable.date').datetimepicker({
                    format: 'Y-m-d',
                    step: 1,
                    mask: '____-__-__',
                    timepicker: false
                });

                $(this).children().focus();
            }

        });

        $('table#list tbody').on('blur', '.editable', function () {
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

        $('table#list tbody').on('change', '.select2.editable', function () {
            $val = $(this).val();
            $elem = $(this).parent();
            $user = $('option[value="' + $val + '"]').text();
            if ($(this).hasClass('responsibility')) {
                $elem.html($user).attr('edited', 'true');
                $elem.attr('data-responsibility', $val);
                $elem.attr('data-position', $(this).data('position'));
            }
            $('[data-toggle="tooltip"]').tooltip();
        });

        $('table#list tbody').on('click', '.copy', function () {
            $(this).parents('tr').removeAttr('editable').clone().attr('editable', 'true').insertAfter($(this).parents('tr')).children('.id').html('').parents('tr').removeAttr('id').addClass('bg-white-7').find('.delete').removeClass('hidden-xs-up').parents('tr').addClass('edited').find('.notClone').remove();
        });

        $('table#list tbody').on('click', '.delete', function () {
            $(this).parents('tr').remove();
        });

        $('#save').click(function () {
            $clone = $('#list tr.edited').clone();
            $('#verificationModal').find('.containerVerification').html($clone);
            $('#verificationModal').find('.noModal').remove();
            $('#countTd').html($('#list td[edited]').length);
            $('#countTr').html($('#list tr.edited').length);
            if ($('#list tr.edited').length == 0) {
                $(this).addClass('hidden-xs-up');
                $('#edit').removeClass('hidden-xs-up');
                $('.copy').addClass('hidden-xs-up');
                $('table#list').removeClass('unselectable');
            } else {
                $('#verificationModal').modal('show');
                $('[data-toggle="tooltip"]').tooltip();
            }
        });

        $('#verificationComplite').click(function () {
            $('#verificationTable .edited').each(function (index, element) {

                $id = $(element).children('.id').text();
                $user = $(element).children('.user').attr('data-user');
                $comment = $(element).children('.textarea').text();
                $date = $(element).children('.date').text();
                $start = $(element).children('.start').text();
                $end = $(element).children('.end').text();
                $responsibility = $(element).children('.responsibility').data('responsibility');
                $assignment = $(element).children('.responsibility').data('assignment');
                $position = $(element).children('.responsibility').data('position');

                $.ajax({
                    type: 'POST',
                    async: false,
                    url: '{{ route('activitySave') }}',
                    data: 'id=' + $id + '&user=' + $user + '&comment=' + $comment + '&date=' + $date + '&start=' + $start + '&end=' + $end + '&responsibility=' + $responsibility + '&assignment=' + $assignment + '&position=' + $position,
                    success: function (data) {
                        location.reload();
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            });

        });
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

    <div class="col-lg-12 wd-100p-force">

        <table id="list" class="table display responsive nowrap datatable">
            <thead>
            <tr>
                <th>№</th>
                <th>Сотрудник</th>
                <th>Проект</th>
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
            @php
                function timeToSec($his) {
                    $t = explode(':', $his);
                    $h = $t[0] * 60 * 60;
                    $i = $t[1] * 60;
                    $s = $t[2];
                    return $h + $i + $s;
                }
                $badge[1] = 'badge-primary';
                $badge[2] = 'badge-success';
                $badge[3] = 'badge-secondary';
                $badge[4] = 'badge-dark';
                $badge[5] = 'badge-light';
                $badge[6] = 'badge-warning';
                $badge[7] = 'badge-info';
                $badge[8] = 'badge-danger';
                $badge[9] = 'badge-purple';

                \App\ExportTempExcel::where('author', Auth::user()->id)->delete();
            @endphp
            @foreach($activities as $activity)
                @php
                    \App\ExportTempExcel::create([
                        'id' => $activity->id,
                        'author' => Auth::user()->id,
                        'user' => $activity->user_name,
                        'comment' => $activity->comment,
                        'date' => $activity->date->format('Y-m-d'),
                        'start' => $activity->start,
                        'end' => $activity->end,
                        'result' => $activity->diff
                    ]);
                @endphp
                <tr {{ $activity->status === 1 ? 'editable=true' : '' }} id="activity-{{ $activity->id }}"
                    data-assignment="{{$activity->assignment_id }}"
                    data-id="{{$activity->id }}">
                    <td class="id">{{ $activity->id }}</td>
                    <td data-old="{{ $activity->user_name }}" class="user" data-user="{{ $activity->user_id }}"
                        nowrap>{{ $activity->user_name }}</td>
                    <td nowrap><span
                                class="badge {{ $badge[$activity->project] }}">{{ $projects[$activity->project] }}</span>
                    </td>
                    <td data-old="{{ $responsibilitiesNames[$activity->project][$dataResponsibilities[$activity->id]] }}"
                        class="responsibility" data-user="{{ $activity->user_id }}"
                        data-assignment="{{ $activity->assignment_id }}" data-project="{{ $activity->project }}">
                        {{ $responsibilitiesNames[$activity->project][$dataResponsibilities[$activity->id]] }}
                    </td>
                    <td data-old="{{ $activity->comment }}" class="textarea">{{ $activity->comment }}</td>
                    <td data-old="{{ $activity->date->format('Y-m-d') }}" class="date"
                        nowrap>{{ $activity->date->format('Y-m-d') }}</td>
                    <td data-old="{{ $activity->start }}" class="start" nowrap>{{ $activity->start }}</td>
                    <td data-old="{{ $activity->end }}" class="end" nowrap>{{ $activity->end }}</td>
                    <td class="diff" data-verification="{{ $activity->status > 1 ? 'true' : 'false' }}"
                        data-time="{{ timeToSec($activity->diff) }}"
                        nowrap>{{ $activity->diff }}</td>
                    <td class="noModal" nowrap>
                        @if($activity->status === 1)
                            @if(Auth::user()->role <= 2)
                                <a href="{{ route('activity-profile-supervision', ['id' => $activity->id, 'project' => $activity->project]) }}"
                                   class="notClone fa fa-calendar-check text-info"></a>
                            @endif
                            <a href="{{ route('activity-profile-edit', ['id' => $activity->id, 'project' => $activity->project]) }}"
                               class="notClone fa fa-pencil-square text-warning"></a>
                            <a href="{{ route('activity-profile-delete', ['id' => $activity->id, 'project' => $activity->project]) }}"
                               class="notClone fa fa-trash text-danger"></a>
                        @else
                            @if(Auth::user()->id === 56 || Auth::user()->id === 9)
                                <a href="{{ route('activity-profile-notVerification', ['id' => $activity->id]) }}"
                                   class="notClone fa fa-calendar-check text-success"></a>
                            @else
                                <a href="javascript:void(0);" class="notClone fa fa-calendar-check text-success"></a>
                            @endif
                        @endif
                        <a href="javascript:void(0)"
                           class="fa fa-copy text-info copy hidden-xs-up"></a>
                        <a href="javascript:void(0)"
                           class="fa fa-trash text-danger delete hidden-xs-up"></a>
                        @if(auth()->user()->role <= 2)
                            <a href="{{ route('assignments-get', ['id' => $activity->assignment_id]) }}"
                               class="pd-l-3-force pd-r-3-force" style="background-color: yellow; color: black;">П</a>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <th>№</th>
                <th>Сотрудник</th>
                <th>Проект</th>
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

        <!-- Modal -->
        <div class="modal fade" id="reportCardModal" tabindex="-1" role="dialog" aria-labelledby="reportCardModalLabel"
             aria-hidden="true">
            <div class="modal-dialog col-12 wd-100p-force" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reportCardModalLabel">Акты выполненных работ</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-primary table-hover table-bordered" style="zoom: 0.6;">
                            <tbody>
                            <tr>
                                @for($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, $dayAllDiff['month'], $dayAllDiff['year']); $i++)
                                    <td style="text-align: center; font-weight: bolder;">{{ $i }}</td>
                                @endfor
                            </tr>
                            <tr>
                                @for($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, $dayAllDiff['month'], $dayAllDiff['year']); $i++)
                                    <td>{{ $dayAllDiff[$i] ?? 'N/A' }}</td>
                                @endfor
                            </tr>
                            </tbody>
                        </table>
                        <div class="accordion">
                            @foreach($positions as $positionData)
                                @php
                                    $types[1] = 'Профильная деятельность';
                                    $types[2] = 'Административная деятельность';
                                    $types[3] = 'Методическая деятельность';
                                    $types[4] = 'Хозяйственная деятельность';
                                    $types[5] = 'Волонтерство';

                                    $secondsDiff = timeToSec($typesDiff[$positionData->project][1]) + timeToSec($typesDiff[$positionData->project][2]) + timeToSec($typesDiff[$positionData->project][3]) + timeToSec($typesDiff[$positionData->project][4]) + timeToSec($typesDiff[$positionData->project][5]);

                                    $hours = floor($secondsDiff / 3600);
                                    $mins = floor($secondsDiff / 60 % 60);
                                    $secs = floor($secondsDiff % 60);

                                    $timeDiff = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

                                @endphp
                                <div class="card">
                                    <div class="card-header">
                                        <h2 class="mb-0 text-center">
                                            <button class="btn btn-link" type="button" data-toggle="collapse"
                                                    data-target="#collapse{{ $positionData->project }}"
                                                    aria-expanded="true"
                                                    aria-controls="collapse{{ $positionData->project }}">
                                                Проект: {{ $projects[$positionData->project] }}<br>
                                                Выработка: {{ $timeDiff }}
                                            </button>
                                        </h2>
                                    </div>

                                    <div id="collapse{{ $positionData->project }}" class="collapse">
                                        <div class="card-body">
                                            <table class="table table-primary table-hover table-bordered"
                                                   style="zoom: 0.6;">
                                                <tbody>
                                                <tr>
                                                    @for($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, $dayDiff[$positionData->project]['month'], $dayDiff[$positionData->project]['year']); $i++)
                                                        <td style="text-align: center; font-weight: bolder;">{{ $i }}</td>
                                                    @endfor
                                                </tr>
                                                <tr>
                                                    @for($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, $dayDiff[$positionData->project]['month'], $dayDiff[$positionData->project]['year']); $i++)
                                                        <td>{{ $dayDiff[$positionData->project][$i] ?? 'N/A' }}</td>
                                                    @endfor
                                                </tr>
                                                </tbody>
                                            </table>
                                            <table class="table table-primary table-hover table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>Функциональная обязанность</th>
                                                    <th>Общая выработка</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                @foreach($responsibilities[$positionData->project] as $responsibility)
                                                    @php
                                                        if($responsibility->type !== $tempType){
                                                            echo '<tr class="tx-bold"><td class="tx-center">'.$types[$responsibility->type].'</td><td class="tx-center">'.$typesDiff[$positionData->project][$responsibility->type].'</td></tr>';
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
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
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
    </div>
@endsection