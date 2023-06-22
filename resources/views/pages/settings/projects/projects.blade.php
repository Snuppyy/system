@extends('layouts.app')

@section('scripts')
    <script src="{{ asset('lib/select2/dist/js/select2.full.js') }}"></script>
@endsection

@section('styles')
    <link href="{{ asset('lib/select2/dist/css/select2.css') }}" rel="stylesheet">
    <style>

    </style>
@endsection

@section('scriptsFooter')
    <script>
        $('.select2').select2({
            dropdownParent: $("#projectAddModal"),
            width: '100%',
            templateSelection: function (data, container) {
                console.log($(this));
            }
        });

        $('#send').click(function () {
            $('#project_send').submit();
        });
    </script>
@endsection

@section('header')
    <div class="sh-breadcrumb">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{ route('index') }}">INTILISH v3.1</a>
            <a class="breadcrumb-item"  href="{{ route('settings') }}">Настройки</a>
            <span class="breadcrumb-item active">Проекты</span>
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
                <h2>Проекты</h2>
            </div><!-- sh-pagetitle-left-title -->
        </div><!-- sh-pagetitle-left -->

    </div><!-- sh-pagetitle -->
@endsection

@section('content')
    <a href="{{ route('projectsLogframe') }}" class="btn btn-success btn-block">СОЗДАТЬ ПРОЕКТ</a>
    <table class="table table-primary table-bordered table-hover">
        <thead>
        <th>
            #
        </th>
        <th>
            организация
        </th>
        <th>
            кодировка
        </th>
        <th>
            название
        </th>
        <th>
            начало
        </th>
        <th>
            конец
        </th>
        <th>
            управление
        </th>
        </thead>
        <tbody>
            @forelse($projects as $project)
                <tr class="{{ $project->status !== 1 ? 'bg-delicate' : '' }}">
                    <td>
                        {{ $project->id }}
                    </td>
                    <td>
                        {{ $project->organization_name }}
                    </td>
                    <td>
                        {{ $project->encoding }}
                    </td>
                    <td>
                        {{ $project->project_name }}
                    </td>
                    <td>
                        {{ $project->beginning }}
                    </td>
                    <td>
                        {{ $project->end }}
                    </td>
                    <td>
                        @if($project->author == auth()->user()->id || in_array(auth()->user()->id, $project->alternate))
                            <a href="" class="icon ion-edit text-warning"></a>
                            <a href="" class="icon ion-trash-a text-danger"></a>
                        @else
                            НЕТ ПРАВ
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                   <td colspan="7" class="text-center">
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
            организация
        </th>
        <th>
            кодировка
        </th>
        <th>
            название
        </th>
        <th>
            начало
        </th>
        <th>
            конец
        </th>
        <th>
            управление
        </th>
        </tfoot>
    </table>
@endsection