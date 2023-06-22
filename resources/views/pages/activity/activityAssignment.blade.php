@extends('layouts.app')

@section('styles')
    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/jquery.switcher/switcher.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/uploader/dist/css/jquery.dm-uploader.min.css') }}" rel="stylesheet">

    <style>
        .sel2 .parsley-errors-list.filled {
            margin-top: 42px;
            margin-bottom: -60px;
        }

        .sel2 .parsley-errors-list:not(.filled) {
            display: none;
        }

        .sel2 .parsley-errors-list.filled + span.select2 {
            margin-bottom: 30px;
        }

        .sel2 .parsley-errors-list.filled + span.select2 span.select2-selection--single {
            background: #FAEDEC !important;
            border: 1px solid #E85445;
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
    </style>

@endsection

@section('scripts')
    <script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('lib/datetimepicker/jquery.datetimepicker.full.js') }}"></script>
    <script src="{{ asset('lib/mask/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('lib/parsleyjs/parsley.js') }}"></script>
    <script src="{{ asset('lib/jquery.switcher/switcher.js') }}"></script>
    <script src="{{ asset('lib/uploader/dist/js/jquery.dm-uploader.min.js') }}"></script>
    <script src="{{ asset('lib/uploader/dist/js/functions.js') }}"></script>

@endsection

@section('scriptsFooter')
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

        $(function () {
            'use strict';

            $('.select2').select2();

            $('form.form-layout').parsley();

            $('.timepicker').datetimepicker({
                format: 'H:i',
                step: 1,
                mask: '__:__',
                datepicker: false
            });

            $('#assignment').change(function () {
                var $this = $("option:selected", this);
                if($this.data('service') == 1){
                    window.location.href = "activity/assignment/" + $this.val();
                }
            });
        })
    </script>
@endsection

@section('header')
    <div class="sh-breadcrumb">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{ route('index') }}">INTILISH v3.1</a>
            <span class="breadcrumb-item">Деятельность</span>
            <span class="breadcrumb-item active">Регистрация деятельности</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <div class="sh-pagetitle">

        <div class="input-group">
            <!-- search block -->
        </div><!-- input-group -->

        <div class="sh-pagetitle-left">
            <div class="sh-pagetitle-icon"><i class="far ion-ios-box-outline"></i></div>
            <div class="sh-pagetitle-title">
                <h2>Регистрация деятельности - {{ $assignment->mark }}</h2>
                <h2>{{ $user_name }} - {{ $date }}</h2>
            </div><!-- sh-pagetitle-left-title -->
        </div><!-- sh-pagetitle-left -->

    </div><!-- sh-pagetitle -->
@endsection

@section('content')
    <form autocomplete="off" action="{{ route('activityAdd') }}" method="post"
          class="form-layout col-lg-12">
        {{ csrf_field() }}
        <input type="hidden" name="assignment" value="{{ $assignment->id }}">
        <input type="hidden" name="user" value="{{ $user_id }}">
        <input type="hidden" name="position" value="{{ $position }}">
        <div class="row mg-b-25">
            <div class="col-lg-6">
                <div class="form-group mg-b-10-force">
                    <label for="organization" class="form-control-label">Дата: <span
                                class="tx-danger">*</span></label>
                    <input readonly class="form-control" id="date" type="text" lang="ru" name="date"
                           data-parsley-pattern="^[0-9]{4}[-]{1}[0-9]{2}[-]{1}[0-9]{2}$"
                           data-parsley-trigger="change" value="{{ $date }}" required>
                </div>
            </div><!-- col-4 -->

            <div class="col-lg-3">
                <div class="form-group mg-b-10-force">
                    <label for="start" class="form-control-label">Время начала: <span
                                class="tx-danger">*</span></label>
                    <input class="form-control timepicker" id="start" type="text" lang="ru" name="start"
                           data-parsley-pattern="^[0-9]{2}[:]{1}[0-9]{2}$"
                           data-parsley-trigger="change" required>
                </div>
            </div><!-- col-4 -->

            <div class="col-lg-3">
                <div class="form-group mg-b-10-force">
                    <label for="end" class="form-control-label">Время конца: <span
                                class="tx-danger">*</span></label>
                    <input class="form-control timepicker" id="end" type="text" lang="ru" name="end"
                           data-parsley-pattern="^[0-9]{2}[:]{1}[0-9]{2}$"
                           data-parsley-trigger="change" required>
                </div>
            </div><!-- col-4 -->

            @if(auth()->user()->role <= 3)
                <div class="col-lg-12">
                    <div class="form-group mg-b-10-force sel2">
                        <label for="users" class="form-control-label">Пакетный ввод: </label>
                        <select id="users" class="form-control select2 sel2 wd-100p-force"
                                data-placeholder="Выберите сотрудника"
                                multiple
                                name="users[]">
                            <option label="Выберите сотрудников"></option>
                            @foreach($users as $userFore)
                                @if($userFore->id != $user_id)
                                    <option value="{{ $userFore->id }}">{{ $userFore->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div><!-- col-4 -->
            @endif

            @if($assignment->type)
            <div class="col-lg-12">
                <div class="form-group mg-b-10-force sel2">
                    <label for="clients" class="form-control-label">Клиент{{ $assignment->type === 'multiple' ? 'ы' : '' }}: <span
                                class="tx-danger">*</span></label>
                    <select id="clients" class="form-control select2 sel2 wd-100p-force"
                            data-placeholder="Выберите клиент{{ $assignment->type === 'multiple' ? 'ов' : 'а' }}"
                            required
                            {{ $assignment->type }}
                            name="clients[]">
                        <option label="Выберите клиент{{ $assignment->type === 'multiple' ? 'ов' : 'а' }}"></option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->f_name . ' ' . $client->s_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div><!-- col-4 -->
            @endif

            <div class="col-lg-12">
                <div class="form-group mg-b-10-force">
                    <label for="comment" class="form-control-label">Комментарии: <span
                                class="tx-danger">*</span></label>
                    <textarea class="form-control" id="comment" name="comment" required></textarea>
                </div>
            </div><!-- col-4 -->

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

            <div class="form-layout-footer col-lg-12">
                <button class="btn btn-success btn-block">Зарегистрировать</button>
            </div><!-- form-layout-footer -->
        </div><!-- row -->
    </form><!-- form-layout -->
@endsection
