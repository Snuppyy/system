@extends('layouts.app')

@section('scripts')
    <script src="{{ asset('lib/select2/dist/js/select2.full.js') }}"></script>
    {{--    <script type="text/javascript" src="{{ asset('lib/moment/moment.js') }}"></script>--}}
    <script type="text/javascript" src="{{ asset('lib/moment/locale/ru.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/moment/moment-timezone.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/datetimepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('lib/uploader/dist/js/jquery.dm-uploader.min.js') }}"></script>
    <script src="{{ asset('lib/uploader/dist/js/functions.js') }}"></script>
    <script src="{{ asset('lib/jquery.switcher/switcher.js') }}"></script>
    <script src="{{ asset('lib/parsleyjs/parsley.js') }}"></script>
@endsection

@section('styles')
    <link href="{{ asset('lib/select2/dist/css/select2.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('lib/datetimepicker/daterangepicker.css') }}"/>
    <link href="{{ asset('lib/uploader/dist/css/jquery.dm-uploader.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/jquery.switcher/switcher.min.css') }}" rel="stylesheet">
    {{--    <link href="{{ asset('lib/fullcalendarYear/fullcalendar.css') }}" rel="stylesheet">--}}

    <style>
        .select2-results .select2-disabled, .select2-results__option[aria-disabled=true] {
            display: none;
        }

        #files {
            overflow-y: scroll !important;
            min-height: 320px;
        }

        @media (min-width: 768px) {
            #files {
                min-height: 0;
            }
        }

        .dm-uploader {
            border: 0.25rem dashed #A5A5C7;
            text-align: center;
        }

        .dm-uploader.active {
            border-color: red;
            border-style: solid;
        }

        #assignments {
            width: 100%;
        }

        body.modal-open {
            overflow: hidden;
        }

        .select2-results__option[aria-selected=true] {
            display: none;
        }
    </style>
@endsection



@section('header')
    <div class="sh-breadcrumb">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{ route('index') }}">INTILISH v3.1</a>
            <span class="breadcrumb-item">Поручения</span>
            <span class="breadcrumb-item active">Календарь поручений</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <div class="sh-pagetitle">

        <div class="input-group">
            <!-- search block -->
        </div><!-- input-group -->

        <div class="sh-pagetitle-left">
            <div class="sh-pagetitle-icon"><i class="icon ion-ios-calendar-outline"></i></div>
            <div class="sh-pagetitle-title">
                <h2>Календарь поручений</h2>
            </div><!-- sh-pagetitle-left-title -->
        </div><!-- sh-pagetitle-left -->

    </div><!-- sh-pagetitle -->
@endsection

@section('scriptsFooter')
    <script src="{{ asset('lib/fullcalendar/locale/ru.js') }}"></script>
    <script src="{{ asset('lib/fullcalendar/scheduler.min.js') }}"></script>

    {{--    <script src="{{ asset('lib/fullcalendarYear/fullcalendar.js') }}"></script>--}}
    <script type="text/html" id="files-template">
        <li class="list-group-item text-muted">
            <div class="media-body mb-1">
                <p class="mb-2">
                    <strong>%%filename%%</strong> - Статус: <span class="text-muted">Подождите..</span>
                </p>
                <div class="progress mb-2">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                         role="progressbar"
                         style="width: 0%"
                         aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
                <hr class="mt-1 mb-1"/>
            </div>
        </li>
    </script>
    <script>
        $('.switcher').switcher();

        $('form.form-layout').parsley();

        $("#assignments_send").on('submit', function (e) {
            e.preventDefault();
            var form = $(this);

            form.parsley().validate();

            if (form.parsley().isValid()) {
                alert('valid');
            }
        });

        function formatOption (value) {
            var $colors = [];

            $colors['Автор'] = 'background-color: #c6dcff; border-color: #0062ff;';
            $colors['Роль Исполнителя'] = 'background-color: #ff9e9e; border-color: #ff0000;';
            $colors['Роль Помощника'] = 'background-color: #9effaa; border-color: #00b716;';
            $colors['Роль Супервайзера'] = 'background-color: #f9f59a; border-color: #fff200;';

            var $value = $(
                '<span style="'+$colors[value.text]+'">' + value.text + '</span>'
            );
            return $value;
        };

        $('.select2').select2({
            width: '100%',
            dropdownParent: $("#createAssignment")
        });

        $('.select2.users').select2({
            width: '100%',
            dropdownParent: $("#createAssignment")
        });

        $('.select2.assignment').select2({
            width: '100%',
            dropdownParent: $("#createAssignment"),
            templateResult: formatOption
        });

        $('.select2.responsibility').select2({
            width: '100%',
            dropdownParent: $("#selectResponsibility")
        });

        $('#drag-and-drop-zone').dmUploader({
            url: '{{ route('upload-files-assignment') }}',
            maxFileSize: 5000000,
            extraData: {
                _token: '{{ csrf_token() }}'
            },
            method: 'POST',
            onDragEnter: function () {
                // Happens when dragging something over the DnD area
                this.addClass('active');
            },
            onDragLeave: function () {
                // Happens when dragging something OUT of the DnD area
                this.removeClass('active');
            },
            onNewFile: function (id, file) {
                ui_multi_add_file(id, file);
            },
            onBeforeUpload: function (id) {
                ui_multi_update_file_status(id, 'uploading', 'Загрузка...');
                ui_multi_update_file_progress(id, 0, '', true);
            },
            onUploadCanceled: function (id) {
                // Happens when a file is directly canceled by the user.
                ui_multi_update_file_status(id, 'warning', 'Отменено пользователем!');
                ui_multi_update_file_progress(id, 0, 'warning', false);
            },
            onUploadProgress: function (id, percent) {
                // Updating file progress
                ui_multi_update_file_progress(id, percent);
            },
            onUploadSuccess: function (id, data) {
                data = JSON.parse(data);
                $('#files').append('<input name="files[]" type="hidden" value="' + data.path + '">');
                ui_multi_update_file_status(id, 'success', 'Загрузка завершена!');
                ui_multi_update_file_progress(id, 100, 'success', false);
            },
            onUploadError: function (id, xhr, status, message) {
                ui_multi_update_file_status(id, 'danger', message);
                ui_multi_update_file_progress(id, 0, 'danger', false);
            }
        });

        $('#assignments').fullCalendar({
            header: {
                left: 'today agendaWeek month year',
                center: 'title',
                right: 'listYear prev next'
            },
            views: {
                year: {
                    type: 'basic',
                    dateIncrement: {years: 1},
                    slotDuration: {months: 1},
                    duration: {years: 1},
                    visibleRange: function (currentDate) {
                        return {
                            start: currentDate.clone().startOf('year'),
                            end: currentDate.clone().endOf("year")
                        };
                    }
                }
            },
            dayRender: function (date, cell) {
                var today = moment();
                if (date.format('DDD') == today.format('DDD')) {
                    cell.css("background-color", "#fff79c");
                } else if (date.month() % 2 == 0) {
                    cell.css("background-color", "#fffde6");
                }
                $('td.fc-day-top[data-date="' + cell.data('date') + '"]').children('span').prepend(date.format('MMMM') + ' - ');
            },
            events: {
                url: '{{ route('assignments-getAll', ['project' => $project]) }}',
                method: 'GET',
                extraParams: {
                    data: {"_token": "{{ csrf_token() }}"}
                },
            },
            selectable: true,
            eventLimit: true,
            select: function (start, end) {
                if(start.format('HH') == '00'){
                    start.set({h: 9});
                }
                if(end.format('HH') == '00'){
                    end.set({h: -7});
                }
                $('#range').text(start.format('YYYY-MM-DD HH:mm:ss') + ' - ' + end.format('YYYY-MM-DD HH:mm:ss'));
                $('#start').val(start.format('YYYY-MM-DD HH:mm:ss'));
                $('#end').val(end.format('YYYY-MM-DD HH:mm:ss'));
                $('.hidden-options').hide();
                $('.hidden-options').each(function (index, obj) {
                    $(this).hide();
                    var data_start = moment($(this).data('start')).tz("Asia/Tashkent");
                    var data_end = moment($(this).data('end')).tz("Asia/Tashkent");
                    if(start.format('YYYY-MM-DD') >= data_start.format('YYYY-MM-DD') && start.format('YYYY-MM-DD') <= data_end.format('YYYY-MM-DD')){
                        $(this).show();
                        console.log(start.format('YYYY-MM-DD') >= data_start.format('YYYY-MM-DD'));
                    }
                });
                $('#createAssignment').modal('show');
            },
        });

        $('#send').click(function () {
            $('#assignments_send').parsley().validate();
            if ($('#assignments_send').parsley().isValid()) {
                console.log($('#assignments_send').serializeArray());
                var start = $('#assignments_send #start').val();
                var end = $('#assignments_send #end').val();
                var title = $('#assignments_send #mark').val();
                eventData = {
                    title: title,
                    start: start,
                    end: end,
                    backgroundColor: '#d6e5ff',
                    borderColor: '#005dff',
                    textColor: '#000'
                };
                $('#assignments_send').get(0).submit();
                $('#assignments').fullCalendar('renderEvent', eventData, false);
                $('#assignments').fullCalendar('unselect');
                $('#createAssignment').modal('hide');
                jQuery('#assignments_send').get(0).reset()
            }
            ;
        });

        $('.select2.users').on('select2:select', function (e) {
            localStorage.selectedValues = $(this).val();
            var text = localStorage.selectedValues.match(/([Ё-ёА-Яа-я ]+)/)[0];
            var val = $('option:contains(' + text + '):first').data('val');
            $('#userSpan').text(text);
            $('.modal-backdrop').attr('style', 'z-index: 1050 !important;');
            if ($('[data-val="' + val + '"]').is(':selected')) {
                var type = $(this).attr('id');
                $('#responsibility').html('');
                $.ajax({
                    type: 'POST',
                    async: false,
                    url: '{{ route('getResponsibility', ['project' => $project]) }}',
                    data: '_token={{ csrf_token() }}&id=' + val + '&type=' + type,
                    success: function (data) {
                        $('#responsibility').append(data);
                        $('#selectResponsibility').modal('show');
                    }
                });
            }
        });

        $('#selectedResponsibility').click(function () {
            if($('#responsibility').val()) {
                $value = $('#responsibility').val();
                $values = $('#responsibility').val().split('_');
                var type = $('#responsibility option:selected').data('type');
                var text = localStorage.selectedValues.match(/([Ё-ёА-Яа-я ]+)/)[0];
                console.log(text);
                $('option:contains(' + text + '):first', '#' + type).val($value);
                $('#selectResponsibility').modal('hide');
                $('.modal-backdrop').removeAttr('style');
            }
        });

        $('#selectResponsibility').on('hidden.bs.modal', function () {
            $("body").addClass("modal-open");
        }).on("hidden", function () {
            $("body").removeClass("modal-open")
        });
    </script>
@endsection

@section('content')
    <div id="assignments"></div>
    <div class="modal fade" id="createAssignment" role="dialog" aria-labelledby="createAssignmentLabel"
         aria-hidden="true">
        <div class="modal-dialog wd-100p modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Добавление поручения: <span id="range"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="row" id="assignments_send" enctype="multipart/form-data" method="post"
                          action="{{ route('assignments-create') }}">
                        <div class="hidden-xs-up">
                            <div class="form-group col-6">
                                <label for="mark" class="col-form-label">Начало:</label>
                                <input class="form-control" id="start" name="start" required>
                            </div>
                            <div class="form-group col-6">
                                <label for="mark" class="col-form-label">Конец:</label>
                                <input class="form-control" id="end" name="end" required>
                            </div>
                        </div>
                        {{ csrf_field() }}
                        <div class="form-group col-12">
                            <label for="assignment" class="col-form-label">Выберите основное поручение:</label>
                            <select id="assignment" class="form-control select2 sel2 wd-100p-force assignment"
                                    name="assignment">
                                <option></option>
{{--                                <optgroup label="Автор">--}}
{{--                                    @foreach($author as $datum)--}}
{{--                                        <option style="background-color: #c6dcff; border-color: #0062ff;" class="author hidden-options" data-start="{{ $datum->start }}" data-end="{{ $datum->end }}" value="{{ $datum->id }}">{{ $datum->mark }}</option>--}}
{{--                                    @endforeach--}}
{{--                                </optgroup>--}}
                                <optgroup label="Роль Исполнителя">

                                    @foreach($administrants as $administrant)
                                        <option style="background-color: #ff9e9e; border-color: #ff0000;" class="administrant hidden-options" data-start="{{ $administrant->start }}" data-end="{{ $administrant->end }}" value="{{ $administrant->id }}">{{ $administrant->mark }}</option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Роль Помощника">
                                    @foreach($helpers as $helper)
                                        <option style="background-color: #9effaa; border-color: #00b716;" class="helper hidden-options" data-start="{{ $helper->start }}" data-end="{{ $helper->end }}" value="{{ $helper->id }}">{{ $helper->mark }}</option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Роль Супервайзера">
                                    @foreach($supervisors as $supervisor)
                                        <option style="background-color: #f9f59a; border-color: #fff200;" class="supervisor hidden-options" data-start="{{ $supervisor->start }}" data-end="{{ $supervisor->end }}" value="{{ $supervisor->id }}">{{ $supervisor->mark }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                        <div class="form-group col-12">
                            <label for="mark" class="col-form-label">Метка:</label>
                            <input id="mark" class="form-control" name="mark" required>
                        </div>
                        <div class="form-group col-1">
                            <label for="project" class="col-form-label">Проект:</label>
                            <input class="form-control" value="{{ \App\Project::find($project)->encoding }}" readonly required>
                                <input type="hidden" name="project" value="{{ $project }}">
                        </div>

                        <div class="form-group col-1">
                            <label for="service" class="col-form-label">Услуга:</label>
                            <input id="service" class="switcher" type="checkbox" name="service" value="1"
                                   data-style="default"
                                   data-selected="false"
                                   data-language="ru"
                                   data-disabled="false"/>
                        </div>

                        <div class="form-group col-2">
                            <label for="prison" class="col-form-label">Выберите место:</label>
                            <select id="prison" class="form-control select2 sel2 wd-100p-force"
                                    name="prison">
                                <option></option>
                                @foreach($prisons as $prison)
                                    <option value="{{ $prison->id }}">{{ $prison->encoding }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-2">
                            <label for="type" class="col-form-label">Выберите тип поручения:</label>
                            <select id="type" class="form-control select2 sel2 wd-100p-force"
                                    name="type">
                                <option></option>
                                <option value="once">Выбор одного клиента</option>
                                <option value="multiple">Выбор нескольких клиентов</option>
                                <option value="to">Поездка туда</option>
                                <option value="of">Поездка обратно</option>
                            </select>
                        </div>

                        <div class="form-group col-6 sel2">
                            <label for="administrants" class="col-form-label">Исполнители:</label>
                            <select id="administrants" class="form-control select2 wd-100p-force users"
                                    name="administrants[]" multiple="multiple" required>
                                @foreach($users as $region => $positionsUsers)
                                    <optgroup label="{{ $region }}">
                                        @foreach($positionsUsers as $arrayUsers)
                                            @foreach($arrayUsers as $id => $name)
                                                <option data-val="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        @endforeach
                                    </optgroup>
                                @endforeach
                                @if(auth()->user()->role <= 2)
                                        <option data-val="0">Все сотрудники</option>
                                @endif
                            </select>
                        </div>
                        <div class="form-group col-6">
                            <label for="helpers" class="col-form-label">Помощники:</label>
                            <select id="helpers" class="form-control select2 sel2 wd-100p-force users"
                                    name="helpers[]" multiple="multiple">
                                @foreach($users as $region => $positionsUsers)
                                    <optgroup label="{{ $region }}">
                                        @foreach($positionsUsers as $arrayUsers)
                                            @foreach($arrayUsers as $id => $name)
                                                <option data-val="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        @endforeach
                                    </optgroup>
                                @endforeach
                                    @if(auth()->user()->role <= 2)
                                        <option data-val="0">Все сотрудники</option>
                                    @endif
                            </select>
                        </div>

                        <div class="form-group col-6">
                            <label for="supervisors" class="col-form-label">Супервайзеры:</label>
                            <select id="supervisors" class="form-control select2 sel2 wd-100p-force users"
                                    name="supervisors[]" multiple="multiple">
                                @foreach($users as $region => $positionsUsers)
                                    <optgroup label="{{ $region }}">
                                        @foreach($positionsUsers as $arrayUsers)
                                            @foreach($arrayUsers as $id => $name)
                                                <option data-val="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        @endforeach
                                    </optgroup>
                                @endforeach
                                    @if(auth()->user()->role <= 2)
                                        <option data-val="0">Все сотрудники</option>
                                    @endif
                            </select>
                        </div>

                        <div class="form-group col-12">
                            <label for="text" class="col-form-label">Текст поручения:</label>
                            <textarea class="form-control" id="text" name="text" rows="10" required></textarea>
                        </div>

                        <div class="col-lg-12 mg-b-25">
                            <label for="supervisors" class="col-form-label">Документы:</label>
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <!-- Our markup, the important part here! -->
                                    <div id="drag-and-drop-zone" class="dm-uploader p-5">
                                        <h3 class="mb-5 mt-5 text-muted">Перетаскивать файлы сюда</h3>

                                        <div class="btn btn-primary btn-block mb-5">
                                            <span>Открыть файловый проводник</span>
                                            <input type="file" title='Click to add Files'/>
                                        </div>
                                    </div><!-- /uploader -->
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <div class="card h-100">
                                        <div class="card-header">
                                            Список файлов
                                        </div>

                                        <ul class="list-unstyled p-2 d-flex flex-column col" id="files">
                                            <li class="text-muted text-center empty">Нет загруженных файлов</li>
                                        </ul>
                                    </div>
                                </div>
                            </div><!-- /file list -->
                        </div><!-- col-12 -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-success" id="send">Отправить поручение</button>
                </div>
            </div>
        </div>
    </div>
    <div style="z-index: 1060 !important;" data-backdrop="false" class="modal fade" id="selectResponsibility" role="dialog"
         aria-labelledby="selectResponsibilityLabel" aria-hidden="true">
        <div class="modal-dialog wd-100p modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="selectResponsibilityLabel">Выберите функциональную обязанность: <span
                                id="userSpan"></span></h5>
                </div>
                <div class="modal-body">
                    <div class="form-group col-12">
                        <select id="responsibility" class="form-control select2 sel2 wd-100p-force responsibility" name="responsibility">
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="selectedResponsibility">Выбрать</button>
                </div>
            </div>
        </div>
    </div>
@endsection
