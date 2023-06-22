@extends('layouts.app')

@section('styles')
    <link href="{{ asset('lib/ionRangeSlider/css/ion.rangeSlider.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/ionRangeSlider/css/ion.rangeSlider.skinNice.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/daterangepicker/daterangepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/jquery.steps/jquery.steps.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/jstree/dist/themes/default/style.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/multi-select/css/multi-select.css') }}" rel="stylesheet">

@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('lib/select2/js/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/daterangepicker/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/daterangepicker/daterangepicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/parsleyjs/parsley.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/jquery.steps/jquery.steps.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/jstree/dist/jstree.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/multi-select/js/jquery.multi-select.js') }}"></script>

    <script>
        // $('.select2').select2({
        //     width: '100%'
        // });

        $('#project').steps({
            headerTag: 'h3',
            bodyTag: 'section',
            autoFocus: true,
            titleTemplate: '<span class="number">#index#</span> <span class="title">#title#</span>',
            cssClass: 'wizard step-equal-width',
        });

        var status = true;

        var startDateObjective,
            endDateObjective,
            startDateTask,
            endDateTask,
            startDateEvent,
            endDateEvent;

        const toast = swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });

        var $locale = {
            "format": "YYYY-MM-DD HH:mm",
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
        };

        $('input[name="dateRange"]').daterangepicker({
            timePicker24Hour: true,
            timePicker: true,
            autoUpdateInput: false,
            locale: $locale
        }, function (start_date, end_date) {
            this.element.val(start_date.format('YYYY-MM-DD HH:mm') + ' - ' + end_date.format('YYYY-MM-DD HH:mm'));
        });

        $('input[name="dateRange"]').on('apply.daterangepicker', function (ev, picker) {
            startDateObjective = moment(picker.startDate._d);
            endDateObjective = moment(picker.endDate._d);
            startDateTask = moment(picker.startDate._d);
            endDateTask = moment(picker.endDate._d);
            startDateEvent = moment(picker.startDate._d);
            endDateEvent = moment(picker.endDate._d);
        });


        //Objective

        $(".addObjectiveButton").on("click", function () {
            if ($('.mainBlock').parsley().validate()) {
                var copyElem = $('#pasteBlockObjective').clone().removeClass('hidden-xs-up').removeAttr('id');
                var thisCloneElem = $('.addedBlock').append(copyElem);
                var index = $('.addedBlock .objectiveBlock:last').index();
                thisCloneElem.find('.objectiveNameWrapper:last')
                    .attr('id', 'objectiveNameWrapper' + index);
                thisCloneElem.find('.form-control:last')
                    .attr('data-parsley-class-handler', '#objectiveNameWrapper' + index)
                    .attr('data-parsley-errors-container', '#objectiveNameWrapperErrorContainer' + index);
                thisCloneElem.find('.objectiveNameWrapperErrorContainer:last')
                    .attr('id', 'objectiveNameWrapperErrorContainer' + index);

                $(".addedBlock .objectiveBlock:last .objective").ionRangeSlider({
                    type: "double",
                    min: +moment(startDateObjective).format("X"),
                    max: +moment(endDateObjective).format("X"),
                    prettify: function (num) {
                        var m = moment(num, "X").locale("ru");
                        return m.format("Do MMMM, HH:mm");
                    },
                    onFinish: function (data) {
                        startDateTask = moment(data.from, "X");
                        endDateTask = moment(data.to, "X");
                        startDateEvent = moment(data.from, "X");
                        endDateEvent = moment(data.to, "X");
                    },
                });

                toast({
                    type: 'success',
                    title: 'Цель успешно создана'
                })
            };
        });

        $(".addedBlock").on("click", '.deleteObjectiveButton', function () {
            var $this = $(this);
            swal({
                title: 'Удаление цели',
                text: "Вы уверены что хотите удалить цель?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Да',
                cancelButtonText: 'Нет',
                showCloseButton: true,
                focusConfirm: false,
            }).then((result) => {
                if (result.value) {
                    $this.parents('.objectiveBlock').remove();
                    swal(
                        'Удаление цели',
                        'Цель успешно удалена',
                        'success'
                    );
                }
            })
        });

        //Task

        $(".addedBlock").on("click", '.addTaskButton', function () {
            var $this = $(this);
            if ($('.addedBlock').parsley().validate()) {
                var copyElem = $('#pasteBlockTask').clone().removeClass('hidden-xs-up').removeAttr('id');
                var thisCloneElem = $this.parents('.objectiveBlock').find('.taskBlocks').append(copyElem);
                var index = $this.parents('.objectiveBlock').find('.taskBlocks').children('.taskBlock:last').index();
                thisCloneElem.find('.taskNameWrapper:last')
                    .attr('id', 'taskNameWrapper' + index);
                thisCloneElem.find('.form-control:last')
                    .attr('data-parsley-class-handler', '#taskNameWrapper' + index)
                    .attr('data-parsley-errors-container', '#taskNameWrapperErrorContainer' + index);
                thisCloneElem.find('.taskNameWrapperErrorContainer:last')
                    .attr('id', 'taskNameWrapperErrorContainer' + index);

                $(".addedBlock .taskBlock:last .task").ionRangeSlider({
                    type: "double",
                    min: +startDateTask.format("X"),
                    max: +endDateTask.format("X"),
                    prettify: function (num) {
                        var m = moment(num, "X").locale("ru");
                        return m.format("Do MMMM, HH:mm");
                    },
                    onFinish: function (data) {
                        startDateEvent = moment(data.from, "X");
                        endDateEvent = moment(data.to, "X");
                    },
                });

                toast({
                    type: 'success',
                    title: 'Задача создана'
                })
            }
            ;
        });

        $(".addedBlock").on("click", '.deleteTaskButton', function () {
            var $this = $(this);
            swal({
                title: 'Удаление задачи',
                text: "Вы уверены что хотите удалить задачу?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Да',
                cancelButtonText: 'Нет',
                showCloseButton: true,
                focusConfirm: false,
            }).then((result) => {
                if (result.value) {
                    $this.parents('.taskBlock').remove();
                    swal(
                        'Удаление задачи',
                        'Задача успешно удалена',
                        'success'
                    );
                }
            })
        });

        //Event

        $(".addedBlock").on("click", '.addEventButton', function () {
            var $this = $(this);
            if ($('.addedBlock').parsley().validate()) {
                var copyElem = $('#pasteBlockEvent').clone().removeClass('hidden-xs-up').removeAttr('id');
                var thisCloneElem = $this.parents('.taskBlock').find('.eventBlocks').append(copyElem);
                var index = $this.parents('.taskBlock').find('.eventBlocks').children('.eventBlock:last').index();
                thisCloneElem.find('.eventNameWrapper:last')
                    .attr('id', 'eventNameWrapper' + index);
                thisCloneElem.find('.form-control:last')
                    .attr('data-parsley-class-handler', '#eventNameWrapper' + index)
                    .attr('data-parsley-errors-container', '#eventNameWrapperErrorContainer' + index);
                thisCloneElem.find('.eventNameWrapperErrorContainer:last')
                    .attr('id', 'eventNameWrapperErrorContainer' + index);

                $(".addedBlock .eventBlock:last .event").ionRangeSlider({
                    type: "double",
                    min: +startDateEvent.format("X"),
                    max: +endDateEvent.format("X"),
                    prettify: function (num) {
                        var m = moment(num, "X").locale("ru");
                        return m.format("Do MMMM, HH:mm");
                    }
                });

                toast({
                    type: 'success',
                    title: 'Мероприятия успешно создано'
                })
            }
            ;
        });

        $(".addedBlock").on("click", '.deleteEventButton', function () {
            var $this = $(this);
            swal({
                title: 'Удаление мероприятия',
                text: "Вы уверены что хотите удалить мерпориятие?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Да',
                cancelButtonText: 'Нет',
                showCloseButton: true,
                focusConfirm: false,
            }).then((result) => {
                if (result.value) {
                    $this.parents('.eventBlock').remove();
                    swal(
                        'Удаление мероприятия',
                        'Мероприятие успешно удалено',
                        'success'
                    );
                }
            })
        });

        var source = [
            {
                "id": 1,
                "text": "DIRECTOR PROJECT",
                "children": [{
                    "id": 2, "text": "asdsad", "type": "staff-2"
                }, {
                    "id": 3, "text": "Суботин, Никитина"
                }, {
                    "id": 4, "text": "Влад"
                }, {
                    "id": 5, "text": "Влад"
                }, {
                    "id": 6, "text": "Суботин"
                }, {
                    "id": 7, "text": "Никитина"
                }, {
                    "id": 8, "text": "Влад"
                }, {
                    "id": 9, "text": "123123123"
                }]
            }, {
                "id": 10,
                "text": "DIRECTOR FILIAL",
                "children": [{
                    "id": 12, "text": "asdsad", "type": "staff-2"
                }, {
                    "id": 13, "text": "Суботин, Никитина"
                }, {
                    "id": 14, "text": "Влад"
                }, {
                    "id": 15, "text": "Влад"
                }, {
                    "id": 16, "text": "Суботин"
                }, {
                    "id": 17, "text": "Никитина"
                }, {
                    "id": 18, "text": "Влад"
                }, {
                    "id": 19, "text": "123123123"
                }]
            },
        ];

        $('#staff').jstree({
            "core": {
                "animation": 0,
                "check_callback": true,
                "themes": {"stripes": true},
                'data': source
            },
            "types": {
                "#": {
                    "icon": "icon ion-person",
                    "max_children": 3,
                    "max_depth": 4,
                    "valid_children": ["root"]
                },
                "root": {
                    "icon": "icon ion-person",
                    "valid_children": ["default"]
                },
                "default": {
                    "icon": "icon ion-person",
                    "valid_children": ["default", "staff-1"]
                },
                "staff-1": {
                    "icon": "icon ion-person",
                    "valid_children": ["staff-2"]
                },
                "staff-2": {
                    "icon": "icon ion-person",
                    "valid_children": ["staff-3"]
                },
                "staff-3": {
                    "icon": "icon ion-person",
                    "valid_children": ["staff-4"]
                },
                "staff-4": {
                    "icon": "icon ion-person",
                    "valid_children": ["default"]
                }
            },
            "plugins": [
                "contextmenu", "dnd", "search",
                "state", "types", "wholerow"
            ],
            "contextmenu": {
                "items": function ($node) {
                    var tree = $("#staff").jstree(true);
                    return {
                        "Add": {
                            "separator_before": false,
                            "separator_after": false,
                            "label": "Добавить должность",
                            "icon": "icon ion-person-stalker",
                            "action": function (obj) {
                                swal({
                                    title: "Добавление должности",
                                    text: 'Загрузка...',
                                    showLoaderOnConfirm: true,
                                    allowOutsideClick: false,
                                    showCloseButton: true,
                                    showCancelButton: true,
                                    width: '100%',
                                    onOpen: function () {
                                        swal.showLoading();
                                        return new Promise((resolve) => {
                                            $.ajax({
                                                type: "POST",
                                                data: {_token: '{{ csrf_token() }}'},
                                                url: "{{ route('getStaff') }}",
                                                success: function (html) {
                                                    swal.hideLoading();
                                                    $('#swal2-content').html(html);
                                                    // $('.select2').select2();
                                                },
                                                error: function (error) {
                                                    $('#swal2-content').html(error);
                                                }
                                            });
                                        });
                                    },
                                    preConfirm: function () {
                                        if (!$('#selectPosition').parsley().validate()) return false;
                                        var value = $('#selectPosition').parsley().fields[0].value;
                                        $node = tree.create_node($node, {text: value, type: 'default'});
                                        tree.deselect_all();
                                        tree.select_node($node);
                                    },
                                });
                            }
                        },
                        "Create": {
                            "separator_before": true,
                            "separator_after": true,
                            "label": "Создать должность",
                            "icon": "icon ion-person-add",
                            "action": function (obj) {
                                swal({
                                    title: "Создание должности",
                                    text: 'Загрузка...',
                                    showLoaderOnConfirm: true,
                                    allowOutsideClick: false,
                                    showCloseButton: true,
                                    showCancelButton: true,
                                    width: '100%',
                                    onOpen: function () {
                                        swal.showLoading();
                                        return new Promise((resolve) => {
                                            $.ajax({
                                                type: "POST",
                                                data: {_token: '{{ csrf_token() }}'},
                                                url: "{{ route('createStaffGetForm') }}",
                                                success: function (html) {
                                                    swal.hideLoading();
                                                    $('#swal2-content').html(html);
                                                    // $('.select2').select2();
                                                    $('#functional').multiSelect();
                                                },
                                                error: function (error) {
                                                    $('#swal2-content').html(error);
                                                }
                                            });
                                        });
                                    },
                                    preConfirm: function () {
                                        if (!$('#createPosition').parsley().validate()) return false;
                                        var value = $('#createPosition').parsley().fields[0].value;
                                        $node = tree.create_node($node, {text: value, type: 'default'});
                                        tree.deselect_all();
                                        tree.select_node($node);
                                    },
                                });
                            }
                        },
                        "Delete": {
                            "separator_before": false,
                            "separator_after": false,
                            "label": "Удалить сотрудника",
                            "icon": "icon ion-trash-a",
                            "action": function (obj) {
                                tree.delete_node($node);
                            }
                        },
                        "Properties": {
                            "separator_before": false,
                            "separator_after": false,
                            "label": "Настройки",
                            "icon": "icon ion-navicon-round",
                            "action": false,
                            "submenu": {
                                "Type": {
                                    "seperator_before": false,
                                    "seperator_after": false,
                                    "label": "Изменить тип",
                                    "icon": "icon ion-clipboard",
                                    action: function (obj) {
                                        alert('type');
                                    }
                                },
                                "Root": {
                                    "seperator_before": false,
                                    "seperator_after": false,
                                    "label": "Сделать Директором",
                                    "icon": "icon ion-pound",
                                    action: function (obj) {
                                        alert('root');
                                    }
                                },
                                "Action": {
                                    "seperator_before": false,
                                    "seperator_after": false,
                                    "label": "Привязать к мероприятию",
                                    "icon": "icon ion-calendar",
                                    action: function (obj) {
                                        alert('Action');
                                    }
                                },
                                "Time": {
                                    "seperator_before": false,
                                    "seperator_after": false,
                                    "label": "Изменить процентную ставку",
                                    "icon": "icon ion-calculator",
                                    action: function (obj) {
                                        alert('Time');
                                    }
                                }
                            }
                        }
                    };
                }
            }
        });
    </script>
@endsection

@section('header')
    <div class="sh-breadcrumb">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{ route('index') }}">INTILISH v3.1</a>
            <a class="breadcrumb-item" href="{{ route('settings') }}">Настройки</a>
            <a class="breadcrumb-item" href="{{ route('projects') }}">Проекты</a>
            <span class="breadcrumb-item active">Создание проекта</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <div class="sh-pagetitle">

        <div class="input-group">
            <!-- search block -->
        </div><!-- input-group -->

        <div class="sh-pagetitle-left">
            <div class="sh-pagetitle-icon"><i class="icon ion-ios-albums-outline"></i></div>
            <div class="sh-pagetitle-title">
                <span>Управление</span>
                <h2>Создание проекта</h2>
            </div><!-- sh-pagetitle-left-title -->
        </div><!-- sh-pagetitle-left -->

    </div><!-- sh-pagetitle -->
@endsection

@section('content')
    <div class="col-lg-12 wd-100p-force">
        <div id="project">
            <h3>Создание проекта</h3>
            <section>
                <div class="mainBlock row" data-parsley-validate>

                    <div class="col-lg-10 col-md-10">
                        <div class="form-layout" id="nameWrapper">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">
                                    Название проекта
                                    <span class="tx-danger">*</span>
                                </label>

                                <input class="form-control"
                                       name="name"
                                       required
                                       placeholder="Введите название проекта"
                                       data-parsley-class-handler="#nameWrapper"
                                       data-parsley-errors-container="#nameWrapperErrorContainer"
                                       data-parsley-trigger="keyup">

                                <div id="nameWrapperErrorContainer"></div>

                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-2">
                        <div class="form-layout" id="idWrapper">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">
                                    Кодировка проекта
                                    <span class="tx-danger">*</span>

                                </label>

                                <input class="form-control"
                                       required
                                       placeholder="Укажите кодировку проекта"
                                       data-parsley-class-handler="#idWrapper"
                                       data-parsley-errors-container="#idWrapperErrorContainer"
                                       data-parsley-trigger="keyup">

                                <div id="idWrapperErrorContainer"></div>

                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6">
                        <div class="form-layout" id="organizationWrapper">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">
                                    Организация
                                    <span class="tx-danger">*</span>

                                </label>

                                <select class="form-control select2"
                                        required
                                        data-placeholder="Выберите организацию"
                                        data-parsley-class-handler="#organizationWrapper"
                                        data-parsley-errors-container="#organizationWrapperErrorContainer"
                                        data-parsley-trigger="change"
                                        name="organization"
                                >
                                    <option label="Выберите организацию"></option>
                                    @foreach($organizations as $organization)
                                        <option value="{{ $organization->id }}">{{ $organization->name }}</option>
                                    @endforeach
                                </select>

                                <div id="organizationWrapperErrorContainer"></div>

                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6">
                        <div class="form-layout" id="dateRangeWrapper">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">
                                    Период реализации проекта
                                    <span class="tx-danger">*</span>

                                </label>

                                <input class="form-control"
                                       required
                                       name="dateRange"
                                       type="text"
                                       placeholder="Укажите период реализации проекта"
                                       data-parsley-class-handler="#dateRangeWrapper"
                                       data-parsley-errors-container="#dateRangeWrapperErrorContainer"
                                       data-parsley-trigger="keyup">

                                <div id="dateRangeWrapperErrorContainer"></div>

                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12">
                        <div class="form-layout" id="descriptionWrapper">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">
                                    Описание проекта
                                    <span class="tx-danger">*</span>

                                </label>

                                <textarea class="form-control"
                                          rows="10"
                                          required
                                          placeholder="Максимально подробно опишите проект"
                                          data-parsley-class-handler="#descriptionWrapper"
                                          data-parsley-errors-container="#descriptionWrapperErrorContainer"
                                          data-parsley-trigger="keyup"></textarea>

                                <div id="descriptionWrapperErrorContainer"></div>

                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 mg-b-10">
                        <button class="btn btn-outline-info btn-block addObjectiveButton">
                            <i class="fa fa-plus mg-r-10"></i>
                            Создать цель проекта

                        </button>
                    </div>

                </div>

                <div class="addedBlock row" data-parsley-validate>

                </div>

                <div class="col-lg-12 col-md-12 objectiveBlock hidden-xs-up" id="pasteBlockObjective">
                    <div class="card bg-gray-100">
                        <div class="btn btn-outline-danger deleteObjectiveButton">
                            Удалить цель проекта
                        </div>

                        <div class="card-body">
                            <div class="form-layout objectiveNameWrapper">
                                <div class="form-group mg-b-10-force">
                                    <label class="form-control-label">
                                        Название цели проекта
                                        <span class="tx-danger">*</span>

                                    </label>
                                    <input class="form-control"
                                           required
                                           type="text"
                                           placeholder="Укажите название цели проекта"
                                           data-parsley-trigger="keyup">

                                    <div class="objectiveNameWrapperErrorContainer"></div>

                                </div>

                                <div class="form-group mg-b-10-force">
                                    <input class="objective" required>

                                </div>
                            </div>

                            <button class="btn btn-outline-indigo btn-block mg-b-10 addTaskButton">
                                <i class="fa fa-plus mg-r-10"></i>
                                Создать задачу цели

                            </button>

                            <div class="taskBlocks">


                            </div>

                        </div>

                    </div>

                </div>

                <div class="card bg-gray-200 taskBlock hidden-xs-up" id="pasteBlockTask">
                    <div class="btn btn-outline-danger deleteTaskButton">
                        Удалить задачу цели
                    </div>

                    <div class="card-body">
                        <div class="form-layout taskNameWrapper">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">
                                    Название задачи
                                    <span class="tx-danger">*</span>

                                </label>
                                <input class="form-control"
                                       type="text"
                                       placeholder="Укажите название задачи цели"
                                       required
                                       data-parsley-trigger="keyup">

                                <div class="taskNameWrapperErrorContainer"></div>

                            </div>

                            <div class="form-group mg-b-10-force">
                                <input class="task">

                            </div>

                        </div>

                        <button class="btn btn-outline-purple btn-block mg-b-10 addEventButton">
                            <i class="fa fa-plus mg-r-10"></i>
                            Создать мероприятие

                        </button>

                        <div class="eventBlocks">


                        </div>
                    </div>

                </div>

                <div class="card bg-gray-300 eventBlock hidden-xs-up" id="pasteBlockEvent">
                    <div class="btn btn-outline-danger deleteEventButton">
                        Удалить мероприятие
                    </div>

                    <div class="card-body">
                        <div class="form-layout eventNameWrapper">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">
                                    Навазние мероприятие задачи:
                                    <span class="tx-danger">*</span>

                                </label>
                                <input class="form-control"
                                       type="text"
                                       placeholder="Укажите название мероприятие задачи"
                                       required
                                       data-parsley-trigger="keyup">

                                <div class="eventNameWrapperErrorContainer"></div>

                            </div>

                            <div class="form-group mg-b-10-force">
                                <input class="event">

                            </div>

                        </div>

                    </div>
                </div>
            </section>
            <h3>Формирование стафа</h3>
            <section>
                <div id="staff"></div>
            </section>
            <h3>Запуск проекта</h3>
            <section>
                <h3>Вы уверены что хотите создать проект?</h3>
            </section>
        </div>
    </div>
@endsection