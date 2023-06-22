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
        $(function () {

            'use strict';

            $('#drag-and-drop-zone').dmUploader({
                url: '{{ route('upload-files-MiOVisitions') }}',
                maxFileSize: 5000000,
                extraData: {
                    _token: '{{ csrf_token() }}'
                },
                method: 'POST',
                onDragEnter: function () {
                    this.addClass('active');
                },
                onDragLeave: function () {
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
                    ui_multi_update_file_status(id, 'warning', 'Отменено пользователем!');
                    ui_multi_update_file_progress(id, 0, 'warning', false);
                },
                onUploadProgress: function (id, percent) {
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

            $('.select2').select2();

            $('[type="checkbox"]').switcher();

            $('form.form-layout').parsley();

            $('#datetime').datetimepicker({
                format: 'Y-m-d H:i',
                step: 1,
                mask: '____-__-__ __:__',
            });

            $('#phone').mask("+(aa8 bb) bbb-00-00", {
                translation: {
                    'a': {
                        pattern: '9',
                        fallback: '9'
                    },
                    'b': {
                        pattern: '^[0-9]$',
                        fallback: 'X'
                    }
                },
                placeholder: "+(998 00) 000-00-00"
            });

            $('input[checked]').switcher('setDisabled', false);

            $('.tools').on('change', function () {
                var data = $(this).data('data');
                if ($(this).is(':checked')) {
                    $('[data-data="' + data + '"]:eq(1)').switcher('setDisabled', false);
                } else {
                    $('[data-data="' + data + '"]:eq(1)').switcher('setDisabled', true).switcher('setValue', false);
                }
            });
        })
    </script>
@endsection

@section('header')
    <div class="sh-breadcrumb">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{ route('index') }}">INTILISH v3.1</a>
            <span class="breadcrumb-item">Мониторинг</span>
            <span class="breadcrumb-item active">Регистрация визита</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <div class="sh-pagetitle">

        <div class="input-group">
            <!-- search block -->
        </div><!-- input-group -->

        <div class="sh-pagetitle-left">
            <div class="sh-pagetitle-icon"><i class="icon ion-map"></i></div>
            <div class="sh-pagetitle-title">
                <h2>Регистрация визита в возможные места продажи</h2>
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
    <form autocomplete="off" action="{{ route('update-MiOVisitions', $miovisitions->id) }}" method="post" class="form-layout col-lg-12">
        {{ csrf_field() }}
        <div class="row mg-b-25">
            <div class="col-lg-8">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="form-control-label" for="datetime">Дата и время визита: <span
                                    class="tx-danger">*</span></label>
                        <input value="{{ \Carbon\Carbon::parse($miovisitions->datetime)->format('Y-m-d H:i') }}" class="form-control" id="datetime" type="text"
                               lang="ru" name="datetime"
                               data-parsley-pattern="^[0-9]{4}[-]{1}[0-9]{2}[-]{1}[0-9]{2} [0-9]{2}[:]{1}[0-9]{2}$"
                               data-parsley-trigger="change" required>
                    </div>
                </div><!-- col-12 -->
                <div class="col-lg-12">
                    <div class="form-group mg-b-10-force">
                        <label class="form-control-label" for="phone">Телефон объекта:</label>
                        <input value="{{ $miovisitions->phone }}" class="form-control" type="text" name="phone"
                               id="phone" data-parsley-trigger="change"
                               data-parsley-pattern="^[+]{1}[(]{1}[0-9]{3} [X0-9]{2}[)]{1} [X0-9]{3}[-]{1}[0-9]{2}[-]{1}[0-9]{2}$">
                    </div>
                </div><!-- col-12 -->
                <div class="col-lg-12">
                    <div class="form-group mg-b-10-force sel2">
                        <label class="form-control-label">Выберите сотрудника выполнившего визит: <span
                                    class="tx-danger">*</span></label>
                        <select class="form-control select2" data-placeholder="Выберите сотрудника"
                                data-parsley-trigger="change" name="user" required>
                            <option label="Выберите сотрудника"></option>
                            @foreach($staff as $user)
                                @if($miovisitions->user === $user->id)
                                    <option selected value="{{ $user->id }}">{{ $user->name_ru }}</option>
                                @else
                                    <option value="{{ $user->id }}">{{ $user->name_ru }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div><!-- col-12 -->
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="form-control-label" for="name">Наименование объекта: <span
                                    class="tx-danger">*</span></label>
                        <input value="{{ $miovisitions->name }}" class="form-control" name="name" id="name"
                               data-parsley-trigger="change" required>
                    </div>
                </div><!-- col-12 -->
                <div class="col-lg-12">
                    <div class="form-group mg-b-10-force sel2">
                        <label class="form-control-label">Выберите тип объекта: <span
                                    class="tx-danger">*</span></label>
                        <select class="form-control select2" data-placeholder="Выберите тип объекта"
                                data-parsley-trigger="change" name="type" required>
                            <option label="Выберите тип объекта"></option>
                            @foreach($types_pharmacies as $type_pharmacy)
                                @if($miovisitions->type === $type_pharmacy->id)
                                    <option selected
                                            value="{{ $type_pharmacy->id }}">{{ $type_pharmacy->name }}</option>
                                @else
                                    <option value="{{ $type_pharmacy->id }}">{{ $type_pharmacy->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div><!-- col-12 -->
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="form-control-label" for="address">Адрес объекта / Описание места продажи: <span
                                    class="tx-danger">*</span></label>
                        <input value="{{ $miovisitions->address }}" class="form-control" name="address" id="address"
                               data-parsley-trigger="change" required>
                    </div>
                </div><!-- col-12 -->
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="form-control-label" for="coordinates">Координаты объекта: <span
                                    class="tx-danger">*</span></label>
                        <input value="{{ $miovisitions->coordinates }}" class="form-control" type="text"
                               name="coordinates" id="coordinates"
                               data-parsley-trigger="change" data-mask="00.000000, 00.000000"
                               placeholder="00.000000, 00.000000"
                               data-parsley-pattern="^[0-9]{2}[.]{1}[0-9]{6}[,]{1} [0-9]{2}[.]{1}[0-9]{6}$" required>
                    </div>
                </div><!-- col-12 -->
                <div class="col-lg-12">
                    <div class="form-group mg-b-10-force">
                        <label class="form-control-label" for="comments">Комментарии: <span
                                    class="tx-danger">*</span></label>
                        <textarea class="form-control" name="comments" id="comments" rows="2"
                                  data-parsley-trigger="change" required>{{ $miovisitions->comments }}</textarea>
                    </div>
                </div><!-- col-12 -->
            </div>
            <div class="table-responsive mg-b-25 col-lg-4">
                <label class="form-control-label" for="comments">Информация о закупе: <span
                            class="tx-danger">*</span></label>
                <table class="table table-hover table-bordered table-primary mg-b-0">
                    <thead>
                    <tr>
                        <th class="text-center">
                            Инструментарий
                        </th>
                        <th class="text-center">
                            Наличие
                        </th>
                        <th class="text-center">
                            Закуп
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="text-md-center valign-middle">
                            Шприцы 2 мг
                        </td>
                        <td class="text-md-center valign-middle">
                            <label>
                                <input {{ $miovisitions->availabilitySyringes2 === 1 ? ' checked' : ''}} class="tools"
                                type="checkbox" name="availabilitySyringes2" data-data="Syringes2"
                                value="1" data-language="visit"/>
                            </label>
                        </td>
                        <td class="text-md-center valign-middle">
                            <label>
                                <input {{ $miovisitions->procurementSyringes2 === 1 ? ' checked' : ''}} type="checkbox"
                                name="procurementSyringes2" data-data="Syringes2"
                                value="1" data-language="visit" data-disabled="true"/>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-md-center valign-middle">
                            Шприцы 5 мг
                        </td>
                        <td class="text-md-center valign-middle">
                            <label>
                                <input {{ $miovisitions->availabilitySyringes5=== 1 ? ' checked' : ''}} class="tools"
                                type="checkbox" name="availabilitySyringes5" data-data="Syringes5"
                                value="1" data-language="visit"/>
                            </label>
                        </td>
                        <td class="text-md-center valign-middle">
                            <label>
                                <input {{ $miovisitions->procurementSyringes5=== 1 ? ' checked' : ''}} type="checkbox"
                                name="procurementSyringes5" data-data="Syringes5"
                                value="1" data-language="visit" data-disabled="true"/>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-md-center valign-middle">
                            Шприцы 10 мг
                        </td>
                        <td class="text-md-center valign-middle">
                            <label>
                                <input {{ $miovisitions->availabilitySyringes10 === 1 ? ' checked' : ''}} class="tools"
                                type="checkbox" name="availabilitySyringes10"
                                data-data="Syringes10" value="1" data-language="visit"/>
                            </label>
                        </td>
                        <td class="text-md-center valign-middle">
                            <label>
                                <input {{ $miovisitions->procurementSyringes10 === 1 ? ' checked' : ''}} type="checkbox"
                                name="procurementSyringes10" data-data="Syringes10"
                                value="1" data-language="visit" data-disabled="true"/>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-md-center valign-middle">
                            Спиртовые салфетки
                        </td>
                        <td class="text-md-center valign-middle">
                            <label>
                                <input {{ $miovisitions->availabilityDoily === 1 ? ' checked' : ''}} class="tools"
                                type="checkbox" name="availabilityDoily" data-data="Doily"
                                value="1" data-language="visit"/>
                            </label>
                        </td>
                        <td class="text-md-center valign-middle">
                            <label>
                                <input {{ $miovisitions->procurementDoily === 1 ? ' checked' : ''}} type="checkbox"
                                name="procurementDoily" data-data="Doily" value="1"
                                data-language="visit"
                                data-disabled="true"/>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-md-center valign-middle">
                            Мужские презервативы
                        </td>
                        <td class="text-md-center valign-middle">
                            <label>
                                <input {{ $miovisitions->availabilityCondomsM === 1 ? ' checked' : ''}} class="tools"
                                type="checkbox" name="availabilityCondomsM" data-data="CondomsM"
                                value="1" data-language="visit"/>
                            </label>
                        </td>
                        <td class="text-md-center valign-middle">
                            <label>
                                <input {{ $miovisitions->procurementCondomsM === 1 ? ' checked' : ''}} type="checkbox"
                                name="procurementCondomsM" data-data="CondomsM"
                                value="1" data-language="visit" data-disabled="true"/>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-md-center valign-middle">
                            Женские презервативы
                        </td>
                        <td class="text-md-center valign-middle">
                            <label>
                                <input {{ $miovisitions->availabilityCondomsW=== 1 ? ' checked' : ''}} class="tools"
                                type="checkbox" name="availabilityCondomsW" data-data="CondomsW"
                                value="1" data-language="visit"/>
                            </label>
                        </td>
                        <td class="text-md-center valign-middle">
                            <label>
                                <input {{ $miovisitions->procurementCondomsW=== 1 ? ' checked' : ''}} type="checkbox"
                                name="procurementCondomsW" data-data="CondomsW"
                                value="1" data-language="visit" data-disabled="true"/>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-md-center valign-middle">
                            Тест на ВИЧ - кровь
                        </td>
                        <td class="text-md-center valign-middle">
                            <label>
                                <input {{ $miovisitions->availabilityHivBlood=== 1 ? ' checked' : ''}} class="tools"
                                type="checkbox" name="availabilityHivBlood" data-data="HivBlood"
                                value="1" data-language="visit"/>
                            </label>
                        </td>
                        <td class="text-md-center valign-middle">
                            <label>
                                <input {{ $miovisitions->procurementHivBlood=== 1 ? ' checked' : ''}} type="checkbox"
                                name="procurementHivBlood" data-data="HivBlood"
                                value="1" data-language="visit" data-disabled="true"/>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-md-center valign-middle">
                            Тест на ВИЧ - слюна
                        </td>
                        <td class="text-md-center valign-middle">
                            <label>
                                <input {{ $miovisitions->availabilityHivSpittle=== 1 ? ' checked' : ''}} class="tools"
                                type="checkbox" name="availabilityHivSpittle"
                                data-data="HivSpittle" value="1" data-language="visit"/>
                            </label>
                        </td>
                        <td class="text-md-center valign-middle">
                            <label>
                                <input {{ $miovisitions->procurementHivSpittle=== 1 ? ' checked' : ''}} type="checkbox"
                                name="procurementHivSpittle" data-data="HivSpittle"
                                value="1" data-language="visit" data-disabled="true"/>
                            </label>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div><!-- col-4 -->
            <div class="col-lg-12 mg-b-25">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <!-- Our markup, the important part here! -->
                        <div id="drag-and-drop-zone" class="dm-uploader p-5">
                            <h3 class="mb-5 mt-5 text-muted">Перетаскивать файлы сюда</h3>

                            <div class="btn btn-primary btn-block mb-5">
                                <span>Открыть файловый проводник</span>
                                <input type="file" title='Click to add Files' accept="image/jpeg"/>
                            </div>
                        </div><!-- /uploader -->
                    </div>

                    <div class="col-md-6 col-sm-12">
                        <div class="card h-100">
                            <div class="card-header">
                                Список файлов
                            </div>

                            <ul class="list-unstyled p-2 d-flex flex-column col" id="files">
                                @if($miovisitions->files)
                                    @foreach($miovisitions->files as $file)
                                        @php
                                            $name = explode('/', $file);
                                        @endphp
                                        <li class="list-group-item text-muted">
                                            <div class="media-body mb-1">
                                                <p class="mb-2">
                                                    <strong><a href="{{ Storage::url($file) }}" target="_blank">{{ $name[4] }}</a></strong> - Статус: <span
                                                            class="status text-success">Загрузка завершена!</span>
                                                </p>
                                                <div class="progress mb-2">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                                         role="progressbar"
                                                         style="width: 100%"
                                                         aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                                    </div>
                                                </div>
                                                <hr class="mt-1 mb-1"/>
                                            </div>
                                            <input name="files[]" type="hidden" value="{{ $file }}">
                                        </li>
                                    @endforeach
                                @else
                                    <li class="text-muted text-center empty">Нет загруженных файлов</li>
                                @endif
                            </ul>
                        </div>
                    </div>

                </div><!-- /file list -->

            </div><!-- col-12 -->
            <div class="form-layout-footer col-lg-12">
                <button class="btn btn-success btn-block">Сохранить</button>
            </div><!-- form-layout-footer -->
        </div><!-- row -->
    </form><!-- form-layout -->
@endsection
