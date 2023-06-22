@extends('layouts.app')

@section('header')
    <div class="sh-breadcrumb">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{ route('index') }}">INTILISH v3.1</a>
            <span class="breadcrumb-item active">Профиль</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <div class="sh-pagetitle">

        <div class="input-group">
            <!-- search block -->
        </div><!-- input-group -->

        <div class="sh-pagetitle-left">
            <div class="sh-pagetitle-icon"><i class="icon ion-ios-contact-outline"></i></div>
            <div class="sh-pagetitle-title">
                <span>Редактирование профиля</span>
                <h2>Профиль</h2>
            </div><!-- sh-pagetitle-left-title -->
        </div><!-- sh-pagetitle-left -->

    </div><!-- sh-pagetitle -->
@endsection

@section('scriptsFooter')
    @if(session('error') || $errors->any())
        <script>
            $('#modal').modal('show');
        </script>
    @endif
@endsection

@section('content')
    <div class="col-3">
        <div class="card bd-primary">
            <div class="card-header bg-primary tx-white">
                Фотография профиля
            </div><!-- card-header -->
            <div class="card-body tx-center">
                <img src="{{ $user->avatar ? $user->avatar : asset('img/avatar/no.png') }}"
                     class="wd-100 rounded-circle" alt="">
                <div class="mg-t-20">
                    <input type="file" class="hidden-xl-down" accept="image/png, image/jpeg">
                    <a href="javascript:void(0);" onclick="$('input[type=file]').click();"
                       class="btn btn-primary pd-x-20 mg-r-2">Изменить</a>
                </div>
            </div><!-- card-body -->
        </div><!-- card -->

        <div class="card bd-primary mg-t-20">
            <div class="card-header bg-primary tx-white">
                Участие в проектах
            </div><!-- card-header -->
            <div class="list-group list-group-flush list-group-skills">
                <div class="list-group-item">
                    <span>INT-001_00-UZ</span>
                    <a href="">&times;</a>
                </div><!-- list-group-item -->
                <div class="list-group-item">
                    <span>INT-002_00-UZ</span>
                    <a href="">&times;</a>
                </div><!-- list-group-item -->
                <div class="list-group-item">
                    <span>INT-003_00-UZ</span>
                    <a href="">&times;</a>
                </div><!-- list-group-item -->
            </div><!-- list-group -->
        </div><!-- card -->

        <div class="card bd-primary mg-t-20">
            <div class="card-header bg-primary tx-white">Уведомления</div>
            <div class="card-body">
                <div class="d-flex">
                    <div class="ckbox mg-t-2 mg-r-15">
                        <input type="checkbox" checked>
                        <span></span>
                    </div><!-- ckbox -->
                    <span>Уведомление по адресу электронной почты, при упоминании меня</span>
                </div>

                <div class="d-flex mg-t-10">
                    <div class="ckbox mg-t-2 mg-r-15">
                        <input type="checkbox" checked>
                        <span></span>
                    </div><!-- ckbox -->
                    <span>Уведомление по адресу электронной почты, при обращении ко мне</span>
                </div>
            </div><!-- card-body -->
        </div><!-- card -->

    </div><!-- col-3 -->
    <div class="col-9">

        <div class="card bd-primary">
            <div class="card-header bg-primary tx-white">
                Информация при авторизации
            </div><!-- card-header -->
            <div class="card-body">
                <div class="form-group row align-items-center">
                    <label class="mg-b-0 col-3 tx-right">Адрес электронной почты</label>
                    <div class="col-9">
                        <input readonly type="email" class="form-control" placeholder="Enter email"
                               value="{{ $user->email }}">
                    </div><!-- col-8 -->
                </div><!-- form-group -->
                <div class="form-group row align-items-center mg-b-0">
                    <label class="mg-b-0 col-3 tx-right">Пароль</label>
                    <div class="col-6">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @else
                            <a id="showModal" href="javascript:void(0);" data-toggle="modal" data-target="#modal">Сменить
                                пароль</a>
                        @endif
                    </div><!-- col-8 -->
                </div><!-- form-group -->
            </div><!-- card-body -->
        </div><!-- card -->

        <div class="card bd-primary mg-t-20">
            <div class="card-header bg-primary tx-white">
                Персональная информация
            </div><!-- card-header -->
            <div class="card-body">
                <div class="form-group row align-items-center">
                    <label class="mg-b-0 col-3 tx-right">Имя на русском языке</label>
                    <div class="col-9">
                        <input type="text" class="form-control" placeholder="Введите имя на русском языке"
                               value="{{ $user->name_ru }}" readonly>
                    </div><!-- col-8 -->
                </div><!-- form-group -->
                <div class="form-group row align-items-center">
                    <label class="mg-b-0 col-3 tx-right">Имя на узбекском языке</label>
                    <div class="col-9">
                        <input type="text" class="form-control" placeholder="Введите имя на узбекском языке"
                               value="{{ $user->name_uz }}" readonly>
                    </div><!-- col-8 -->
                </div><!-- form-group -->
                <div class="form-group row align-items-center">
                    <label class="mg-b-0 col-3 tx-right">Имя на английском языке</label>
                    <div class="col-9">
                        <input type="text" class="form-control" placeholder="Введите имя на английском языке"
                               value="{{ $user->name_en }}" readonly>
                    </div><!-- col-8 -->
                </div><!-- form-group -->
                <div class="form-group row align-items-center">
                    <label class="mg-b-0 col-3 tx-right">Место работы</label>
                    <div class="col-9">
                        <input type="text" class="form-control" placeholder="Укажите место работы"
                               value="{{ $organization->name_ru }}" readonly>
                    </div><!-- col-8 -->
                </div><!-- form-group -->
                <div class="form-group row align-items-center">
                    <label class="mg-b-0 col-3 tx-right">Должность</label>
                    <div class="col-9">
                        <input type="text" class="form-control" placeholder="Укажите должность"
                               value="{{ $position->name_ru }}" readonly>
                    </div><!-- col-8 -->
                </div><!-- form-group -->
                <div class="form-group row">
                    <label class="mg-b-0 col-3 tx-right mg-t-10">О себе</label>
                    <div class="col-9">
                        <textarea class="form-control" rows="4"
                                  placeholder="Напишите краткое описание о вас ...">{{ $user->about }}</textarea>
                    </div><!-- col-8 -->
                </div><!-- form-group -->
            </div><!-- card-body -->
        </div><!-- card -->
    </div><!-- col-9 -->
    <!-- BASIC MODAL -->

    <div id="modal" class="modal fade">
        <div class="modal-dialog modal-dialog-vertical-center" role="document">
            <div class="modal-content bd-0 tx-14">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Смена пароля</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="edit_password" method="POST"
                      action="{{ route('edit-password', $user->id) }}">
                    <div class="modal-body pd-b-25  pd-l-25  pd-r-25 pd-t-0">
                        <p class="">Данное поле предназначено исключительно для смены пароля Вашего аккаунта в ИЭС
                            версии 3.1</p>
                        {{ csrf_field() }}
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if(Auth::user()->role !== 1)
                            <div class="form-group{{ $errors->has('current-password') ? ' has-danger is-invalid' : '' }}">
                                <label for="current-password" class="control-label col-12">Старый пароль</label>

                                <div class="col-12">
                                    <input id="current-password" type="password"
                                           class="form-control{{ $errors->has('current-password') ? ' is-invalid' : '' }}"
                                           name="current-password" required autofocus>

                                    @if ($errors->has('current-password'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('current-password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div class="form-group{{ $errors->has('new-password') ? ' has-danger' : '' }}">
                            <label for="new-password" class="control-label col-12">Новый пароль пароль</label>

                            <div class="col-12">
                                <input id="new-password" type="password"
                                       class="form-control{{ $errors->has('new-password') ? ' is-invalid' : '' }}"
                                       name="new-password"
                                       required>

                                @if ($errors->has('new-password'))
                                    <span class="help-block text-danger">
                                        {{ $errors->first('new-password') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if(Auth::user()->role !== 1)
                            <div class="form-group">
                                <label for="new-password_confirmation" class="control-label col-12">Подтвердите новый
                                    пароль</label>
                                <div class="col-12">
                                    <input id="new-password_confirmation" type="password" class="form-control"
                                           name="new-password_confirmation" required>
                                </div>
                            </div>
                        @endif

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success pd-x-20">
                            Изменить
                        </button>
                        <button type="button" class="btn btn-secondary pd-x-20" data-dismiss="modal">Закрыть</button>
                    </div>
                </form>
            </div>
        </div><!-- modal-dialog -->
    </div><!-- modal -->
@endsection