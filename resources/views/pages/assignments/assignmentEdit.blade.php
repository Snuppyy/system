@extends('layouts.app')

@section('styles')
    <link href="{{ asset('lib/select2/dist/css/select2.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('lib/datetimepicker/daterangepicker.css') }}"/>
    <style>
        .select2-container--default .select2-results__option[aria-disabled=true] {
            display: none;
        }
    </style>
@endsection

@section('scripts')
    <script src="{{ asset('lib/select2/dist/js/select2.full.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/moment/locale/ru.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/moment/moment-timezone.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/datetimepicker/daterangepicker.js') }}"></script>
@endsection

@section('scriptsFooter')
    <script>
        $('.select2').select2();
        $('.selectUsers').each(function (index) {
            $position = $(this).val();
            $('.responsibilities[data-position="' + $position + '"]').removeAttr('disabled');
            $('.select2').select2();
        });
    </script>
@endsection

@section('header')
    <div class="sh-breadcrumb">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{ route('index') }}">INTILISH v3.1</a>
            <span class="breadcrumb-item">Поручения</span>
            <span class="breadcrumb-item active">Поручение №{{ $assignment->id }}</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <div class="sh-pagetitle {{ $assignment->background }}">

        <div class="input-group">
            <!-- search block -->
        </div><!-- input-group -->

        <div class="sh-pagetitle-left">
            <div class="sh-pagetitle-icon {{ $assignment->textColor }}"><i class="icon ion-hammer"></i></div>
            <div class="sh-pagetitle-title">
                <h2 class="{{ $assignment->textColor }}">Поручение №{{ $assignment->id }}</h2>
            </div><!-- sh-pagetitle-left-title -->
        </div><!-- sh-pagetitle-left -->

    </div><!-- sh-pagetitle -->
@endsection

@section('content')
    <form class="row" action="{{ route('assignments-editSave', ['id' => $assignment->id]) }}" method="POST">
        @csrf
        <div class="form-group col-12">
            <label for="mark" class="col-form-label">Метка:</label>
            <input id="mark" class="form-control" name="mark" required value="{{ $assignment->mark }}">
        </div>

        <div class="form-group col-6">
            <label for="start" class="col-form-label">Начало:</label>
            <input id="start" class="form-control" name="start" required value="{{ $assignment->start }}">
        </div>

        <div class="form-group col-6">
            <label for="end" class="col-form-label">Конец:</label>
            <input id="end" class="form-control" name="end" required value="{{ $assignment->end }}">
        </div>

        @if($assignment->administrants)
            <label for="" class="col-12 col-form-label">Исполнители:</label>
            @foreach($assignment->administrants as $user => $responsibility)
                <div class="form-group col-12">
                    <select name="administrantsUsers[]" class="select2 form-control selectUsers">
                        @foreach($users as $userName)
                            <option {{ $userName->position == $user ? 'selected' : '' }} value="{{ $userName->position }}">{{ $userName->name }}</option>
                        @endforeach
                    </select>
                    <select name="administrantsResponsibilities[]" class="select2 form-control selectResponsibilities">
                        @foreach($responsibilities as $position => $responsibilitiesData)
                            @foreach($responsibilitiesData as $responsibilityData)
                                <option {{ $responsibilityData->id == $responsibility && $position == $user ? 'selected' : '' }} disabled
                                        data-position="{{ $position }}" class="responsibilities"
                                        value="{{ $responsibilityData->id }}">{{ $responsibilityData->name }}</option>
                            @endforeach
                        @endforeach
                    </select>
                </div>
            @endforeach
        @endif

        @if($assignment->helpers)
            <label for="" class="col-12 col-form-label">Помощники:</label>
            @foreach($assignment->helpers as $user => $responsibility)
                <div class="form-group col-12">
                    <select name="helpersUsers[]" class="select2 form-control selectUsers">
                        @foreach($users as $userName)
                            <option {{ $userName->position == $user ? 'selected' : '' }} value="{{ $userName->position }}">{{ $userName->name }}</option>
                        @endforeach
                    </select>
                    <select name="helpersResponsibilities[]" class="select2 form-control selectResponsibilities">
                        @foreach($responsibilities as $position => $responsibilitiesData)
                            @foreach($responsibilitiesData as $responsibilityData)
                                <option {{ $responsibilityData->id == $responsibility ? 'selected' : '' }} disabled
                                        data-position="{{ $position }}" class="responsibilities"
                                        value="{{ $responsibilityData->id }}">{{ $responsibilityData->name }}</option>
                            @endforeach
                        @endforeach
                    </select>
                </div>
            @endforeach
        @endif

        @if($assignment->supervisors)
            <label for="" class="col-12 col-form-label">Супервайзеры:</label>
            @foreach($assignment->supervisors as $user => $responsibility)
                <div class="form-group col-12">
                    <select name="supervisorsUsers[]" class="select2 form-control selectUsers">
                        @foreach($users as $userName)
                            <option {{ $userName->position == $user ? 'selected' : '' }} value="{{ $userName->position }}">{{ $userName->name }}</option>
                        @endforeach
                    </select>
                    <select name="supervisorsResponsibilities[]" class="select2 form-control selectResponsibilities">
                        @foreach($responsibilities as $position => $responsibilitiesData)
                            @foreach($responsibilitiesData as $responsibilityData)
                                <option {{ $responsibilityData->id == $responsibility ? 'selected' : '' }} disabled
                                        data-position="{{ $position }}" class="responsibilities"
                                        value="{{ $responsibilityData->id }}">{{ $responsibilityData->name }}</option>
                            @endforeach
                        @endforeach
                    </select>
                </div>
            @endforeach
        @endif
{{--        {{ dd($assignment) }}--}}
        <div class="form-group col-12">
            <label for="comment" class="col-form-label">Текст поручения:</label>
            <input id="comment" class="form-control" name="comment" required value="{{ $assignment->comment }}">
        </div>
        <button class="btn btn-warning btn-block">Отредактировать</button>
    </form>
@endsection
