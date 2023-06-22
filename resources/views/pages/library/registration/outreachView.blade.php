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

@endsection

@section('header')
    <div class="sh-breadcrumb">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{ route('index') }}">INTILISH v3.1</a>
            <span class="breadcrumb-item">Список</span>
            <span class="breadcrumb-item active">Аутрич-сотрудники / ассистенты</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <div class="sh-pagetitle">

        <div class="input-group">
            <!-- search block -->
        </div><!-- input-group -->

        <div class="sh-pagetitle-left">
            <div class="sh-pagetitle-icon"><i class="icon ion-ios-bookmarks-outline"></i></div>
            <div class="sh-pagetitle-title">
                <h2>Аутрич-сотрудники / ассистенты</h2>
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
    <table class="table table-hover table-primary table-bordered">
        <thead>
        <th>#</th>
        <th>Регион</th>
        <th>Фамилия Имя</th>
        <th>Должность</th>
        <th>Статус</th>
        <th>Управление</th>
        </thead>
        <tbody>
        @foreach($outreaches as $outreach)
            <tr>
                <td>{{ $outreach->id }}</td>
                <td>{{ $outreach->region }}</td>
                <td>{{ $outreach->f_name . ' ' . $outreach->s_name }}</td>
                <td>{{ $outreach->assistant === 1 ? 'Ассистент' : 'Аутрич-сотрудник' }}</td>
                <td>{{ $outreach->status === 1 ? 'АКТИВЕН' : 'УВОЛЕН' }}</td>
                <td>
                    @if($outreach->status === 1)
                    <a href="{{ route('registration-outreach-dismiss', ['id' => $outreach->id]) }}"
                       class="fa fa-close text-danger"> УВОЛЕН</a>
                    @else
                        <a href="{{ route('registration-outreach-recruit', ['id' => $outreach->id]) }}"
                           class="fa fa-check text-success"> АКТИВЕН</a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <th>#</th>
        <th>Регион</th>
        <th>Фамилия Имя</th>
        <th>Должность</th>
        <th>Статус</th>
        <th>Управление</th>
        </tfoot>
    </table>
@endsection
