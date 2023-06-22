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

            $('.select2').select2();

            $('form.form-layout').parsley();

            window.ParsleyValidator
                .addValidator('mindate', function (value, requirement) {
                    // is valid date?
                    var timestamp = Date.parse(value),
                        minTs = Date.parse(requirement);

                    return isNaN(timestamp) ? false : timestamp > minTs;
                }, 32);

            $('.timepicker').datetimepicker({
                format: 'H:i',
                step: 1,
                mask: '__:__',
                datepicker: false
            });

            $('#date').datetimepicker({
                format: 'Y-m-d',
                step: 1,
                mask: '____-__-__',
                timepicker: false,
                minDate: '2019/10/01',
                maxDate: '2020/12/31'
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
                <h2>Регистрация деятельности - {{ $user->name_ru }}</h2>
            </div><!-- sh-pagetitle-left-title -->
        </div><!-- sh-pagetitle-left -->

    </div><!-- sh-pagetitle -->
@endsection

@section('content')
    <form autocomplete="off" action="{{ route('activityAddTemp') }}" method="post"
          class="form-layout col-lg-12">
        {{ csrf_field() }}
        <input type="hidden" value="{{ $user->id }}" name="user">
        <input type="hidden" value="{{ $position }}" name="position">
        <div class="row mg-b-25">
            <div class="col-lg-6">
                <div class="form-group mg-b-10-force sel2">
                    <label for="responsibility" class="form-control-label">Функциональная обязанность: <span
                                class="tx-danger">*</span></label>
                    <select id="responsibility" class="form-control select2 sel2 wd-100p-force"
                            data-placeholder="Выберите функциональную обязанность"
                            required
                            name="responsibility">
                        <option label="Выберите функциональную обязанность"></option>
                        @foreach($responsibilities as $responsibility)
                            <option value="{{ $responsibility->id }}">{{ $responsibility->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div><!-- col-4 -->


            <div class="col-lg-2">
                <div class="form-group mg-b-10-force">
                    <label for="organization" class="form-control-label">Дата: <span
                                class="tx-danger">*</span></label>
                    <input class="form-control" id="date" type="text" lang="ru" name="date"
                           data-parsley-mindate="2019-03-31"
                           data-parsley-pattern="^[0-9]{4}[-]{1}[0-9]{2}[-]{1}[0-9]{2}$"
                           data-parsley-trigger="change" value="{{ $date }}" required>
                </div>
            </div><!-- col-4 -->

            <div class="col-lg-2">
                <div class="form-group mg-b-10-force">
                    <label for="start" class="form-control-label">Время начала: <span
                                class="tx-danger">*</span></label>
                    <input class="form-control timepicker" id="start" type="text" lang="ru" name="start"
                           data-parsley-pattern="^[0-9]{2}[:]{1}[0-9]{2}$"
                           data-parsley-trigger="change" required>
                </div>
            </div><!-- col-4 -->

            <div class="col-lg-2">
                <div class="form-group mg-b-10-force">
                    <label for="end" class="form-control-label">Время конца: <span
                                class="tx-danger">*</span></label>
                    <input class="form-control timepicker" id="end" type="text" lang="ru" name="end"
                           data-parsley-pattern="^[0-9]{2}[:]{1}[0-9]{2}$"
                           data-parsley-trigger="change" required>
                </div>
            </div><!-- col-4 -->


            @if(auth()->user()->role <= 2)
                <div class="col-lg-12">
                    <div class="form-group mg-b-10-force sel2">
                        <label for="users" class="form-control-label">Для нескольких сотрудников:</label>
                        <select id="users" class="form-control select2 sel2 wd-100p-force"
                                data-placeholder="Выберите сотрудников"
                                name="users[]" multiple>
                            <option label="Выберите сотрудников"></option>
                            @foreach($users as $keys => $values)
                                @foreach($users[$keys] as $key => $value)
                                    <optgroup label="{{ $key }}">
                                        @foreach($users[$keys][$key] as $k => $v)
                                            <option value="{{ $k }}">{{ $v }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                </div><!-- col-4 -->
            @endif

            @if(auth()->user()->project->organization === 1)
                <div class="col-lg-12">
                    <div class="form-group mg-b-10-force sel2">
                        <label for="clients" class="form-control-label">Клиенты:</label>
                        <select id="clients" class="form-control select2 sel2 wd-100p-force"
                                data-placeholder="Выберите клиентов"
                                multiple name="clients[]">
                            <option label="Выберите клиентоа"></option>
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

            <div class="form-layout-footer col-lg-12">
                <button class="btn btn-success btn-block">Зарегистрировать</button>
            </div><!-- form-layout-footer -->
        </div><!-- row -->
    </form><!-- form-layout -->

    <table class="table table-primary table-bordered table-hover">
        <thead>
        <th>
            #
        </th>
        <th>
            сотрудник
        </th>
        <th>
            поручение
        </th>
        <th>
            дата
        </th>
        <th>
            время
        </th>
        <th>
            управление
        </th>
        </thead>
        <tbody>
        @forelse($activities as $activity)
            <tr>
                <td>
                    {{ $activity->id }}
                </td>
                <td>
                    {{ $activity->user }}
                </td>
                <td>
                    {{ $activity->assignment }}
                </td>
                <td>
                    {{ $activity->date->format('d-m-Y') }}
                </td>
                <td>
                    {{ $activity->start . ' - ' . $activity->end }}
                </td>
                <td nowrap>
                    @if($activity->status === 1)
                        <a href="{{ route('activity-profile-edit', ['id' => $activity->id, 'project' => auth()->user()->project->id]) }}"
                           class="fa fa-pencil-square text-warning"></a>
                        <a href="{{ route('activity-profile-delete', ['id' => $activity->id, 'project' => auth()->user()->project->id]) }}"
                           class="fa fa-trash text-danger"></a>
                    @endif
                    @if(auth()->user()->id === 5)
                        <a href="{{ route('activity-profile-supervision', $activity->id) }}"
                           class="fa fa-calendar-check {{ $activity->status === 1 ? 'text-info' : 'text-success' }}"></a>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">
                    НЕТ ДАННЫХ
                </td>
            </tr>
        @endforelse
        </tbody>
        <tfoot>
        <th>
            #
        </th>
        <th>
            сотрудник
        </th>
        <th>
            поручение
        </th>
        <th>
            дата
        </th>
        <th>
            время
        </th>
        <th>
            управление
        </th>
        </tfoot>
    </table>
@endsection
