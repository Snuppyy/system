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
            dropdownParent: $("#organizationAddModal"),
            width: '100%'
        });

        $('#send').click(function () {
            $('#organization_send').submit();
        });
    </script>
@endsection


@section('header')
    <div class="sh-breadcrumb">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{ route('index') }}">INTILISH v3.1</a>
            <a class="breadcrumb-item"  href="{{ route('settings') }}">Настройки</a>
            <span class="breadcrumb-item active">Организации</span>
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
                <h2>Организации</h2>
            </div><!-- sh-pagetitle-left-title -->
        </div><!-- sh-pagetitle-left -->

    </div><!-- sh-pagetitle -->
@endsection

@section('content')
    <div class="btn btn-success btn-block" data-toggle="modal" data-target="#organizationAddModal">ДОБАВИТЬ ОРГАНИЗАЦИЮ</div>
    <table class="table table-primary table-bordered table-hover">
        <thead>
        <th>
            #
        </th>
        <th>
            кодировка
        </th>
        <th>
            название
        </th>
        <th>
            адрес
        </th>
        <th>
            управление
        </th>
        </thead>
        <tbody>
            @forelse($organizations as $organization)
                <tr>
                    <td>
                        {{ $organization->id }}
                    </td>
                    <td>
                        {{ $organization->encoding }}
                    </td>
                    <td>
                        {{ $organization->name }}
                    </td>
                    <td>
                        {{ $organization->address }}
                    </td>
                    <td>
                        @if($organization->author == auth()->user()->id || in_array(auth()->user()->id, $organization->alternate))
                            <a href="" class="icon ion-edit text-warning"></a>
                            <a href="" class="icon ion-trash-a text-danger"></a>
                        @else
                            НЕТ ПРАВ
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
            кодировка
        </th>
        <th>
            название
        </th>
        <th>
            адрес
        </th>
        <th>
            управление
        </th>
        </tfoot>
    </table>
    <div class="modal fade" id="organizationAddModal" role="dialog" aria-labelledby="organizationAddModalLabel" aria-hidden="true">
        <div class="modal-dialog wd-100p modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="organizationAddModalLabel">Создание организации: <span id="range"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="row" id="organization_send" enctype="multipart/form-data" method="post" action="{{ route('organizationsSet') }}">
                        {{ csrf_field() }}
                        <div class="form-group col-6">
                            <label for="name_ru" class="col-form-label">Название организации:</label>
                            <input id="name_ru" class="form-control" name="name_ru" placeholder="Название организации на русском" required>
                            <input id="name_uz" class="form-control" name="name_uz" placeholder="Название организации на узбекском">
                            <input id="name_en" class="form-control" name="name_en" placeholder="Название организации на английском">
                        </div>
                        <div class="form-group col-6">
                            <label for="address_ru" class="col-form-label">Адрес организации:</label>
                            <input id="address_ru" class="form-control" name="address_ru" placeholder="Адрес организации на русском" required>
                            <input id="address_uz" class="form-control" name="address_uz" placeholder="Адрес организации на узбекском">
                            <input id="address_en" class="form-control" name="address_en" placeholder="Адрес организации на английском">
                        </div>
                        <div class="form-group col-2">
                            <label for="encoding" class="col-form-label">Кодировка:</label>
                            <input id="encoding" class="form-control" name="encoding" required>
                        </div>
                        <div class="form-group col-10">
                            <label for="alternate" class="col-form-label">Соавторы:</label>
                            <select id="alternate" class="form-control select2 sel2 wd-100p-force"
                                    data-placeholder="Выбрите пользователей"
                                    multiple
                                    name="alternate[]">
                                @foreach($users as $user)
                                    @if($user->id !== auth()->user()->id)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-success" id="send">Создать организацию</button>
                </div>
            </div>
        </div>
    </div>
@endsection