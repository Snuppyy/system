@extends('layouts.app')

@section('styles')
    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/jquery.switcher/switcher.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/uploader/dist/css/jquery.dm-uploader.min.css') }}" rel="stylesheet">
@endsection

@section('scripts')
    <script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('lib/datetimepicker/jquery.datetimepicker.full.js') }}"></script>
    <script src="{{ asset('lib/mask/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('lib/parsleyjs/parsley.js') }}"></script>
    <script src="{{ asset('lib/jquery.switcher/switcher.js') }}"></script>
    <script src="{{ asset('lib/uploader/dist/js/jquery.dm-uploader.min.js') }}"></script>
@endsection

@section('scriptsFooter')
    <script>
        $(function () {
            'use strict';

            $('.select2').select2();

            $('#birthday').datetimepicker({
                format: 'Y-m-d',
                step: 1,
                timepicker: false
            });

            $('form.form-layout').parsley();

            $('#encoding').mask("AAAA000", {
                translation: {
                    'A': {
                        pattern: '^[А-Я]$'
                    }
                },
                placeholder: "AAAA000"
            });
        })
    </script>
@endsection

@section('header')
    <div class="sh-breadcrumb">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{ route('index') }}">INTILISH v3.1</a>
            <span class="breadcrumb-item">Формы</span>
            <span class="breadcrumb-item active">Создание нового опросника</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <div class="sh-pagetitle">

        <div class="input-group">
            <!-- search block -->
        </div><!-- input-group -->

        <div class="sh-pagetitle-left">
            <div class="sh-pagetitle-icon"><i class="icon ion-ios-help-outline"></i></div>
            <div class="sh-pagetitle-title">
                <h2>Создание нового опросника</h2>
            </div><!-- sh-pagetitle-left-title -->
        </div><!-- sh-pagetitle-left -->

    </div><!-- sh-pagetitle -->
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success text-center col-12">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger text-center col-12">{{ session('error') }}</div>
    @endif
    <form autocomplete="off" action="{{ route('registration-questionnaire-save') }}" method="post"
          class="form-layout col-lg-12">
        {{ csrf_field() }}
        <div class="row mg-b-25">
            @if(Auth::user()->role === 1)
                <div class="col-lg-5">
                    <div class="form-group mg-b-10-force sel2">
                        <label for="region" class="form-control-label">Регион: <span
                                    class="tx-danger">*</span></label>
                        <select class="form-control select2" data-placeholder="Выберите регион"
                                data-parsley-trigger="change" name="region" required>
                            <option label="Выберите регион"></option>
                            @foreach($regions as $region)
                                <option value="{{ $region->id }}" {{ $region->id === 11 ? 'selected' : '' }}>{{ $region->encoding }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @else
                <div class="col-lg-5">
                    <div class="form-group mg-b-10-force">
                        <label for="region" class="form-control-label">Регион: <span
                                    class="tx-danger">*</span></label>

                        <input id="region" class="form-control" type="text" name="region" required readonly
                               value="{{ Auth::user()->region->encoding }}">
                    </div>
                </div><!-- col-4 -->
            @endif

            <div class="col-lg-7">
                <div class="form-group mg-b-10-force">
                    <label for="name_ru" class="form-control-label">Название опросника: <span
                                class="tx-danger">*</span></label>
                    <input autofocus id="name_ru" class="form-control" type="text" name="name_ru" data-parsley-trigger="change"
                           value="{{ old('name_ru') }}" required>
                </div>
            </div><!-- col-6 -->

            <div class="form-layout-footer col-lg-12">
                <button class="btn btn-success btn-block">Зарегистрировать</button>
            </div><!-- form-layout-footer -->
        </div><!-- row -->
    </form><!-- form-layout -->
@endsection
