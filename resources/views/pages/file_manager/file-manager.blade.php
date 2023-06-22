@extends('layouts.app')

@section('header')
    <div class="sh-breadcrumb">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{ route('index') }}">INTILISH v3.1</a>
            <span class="breadcrumb-item">Инструменты</span>
            <span class="breadcrumb-item active">Файловый менеджер</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <div class="sh-pagetitle">

        <div class="input-group">
            <!-- search block -->
        </div><!-- input-group -->

        <div class="sh-pagetitle-left">
            <div class="sh-pagetitle-icon"><i class="icon icon ion-ios-folder-outline"></i></div>
            <div class="sh-pagetitle-title">
                <h2>Файловый менеджер</h2>
            </div><!-- sh-pagetitle-left-title -->
        </div><!-- sh-pagetitle-left -->

    </div><!-- sh-pagetitle -->
@endsection

@section('scriptsFooter')
    <script>
        $(function () {
            'use strict';

            $('.type').on('click', function () {
                $('.file').addClass('hidden-xs-up');
                $('.file[data-file-type="' + $(this).data('view-type') + '"]').removeClass('hidden-xs-up');
                $('.type').removeClass('active');
                $(this).addClass('active');
                if ($(this).data('view-type') === 'all') {
                    $('.file').removeClass('hidden-xs-up');
                }
            });

            $('.access').on('click', function () {
                if ($(this).data('view-directory')) {
                    $('.file').addClass('hidden-xs-up');
                    $('.file[data-file-directory="' + $(this).data('view-directory') + '"]').removeClass('hidden-xs-up');
                } else {
                    $('.file').addClass('hidden-xs-up');
                    $('.file[data-file-author="' + $(this).data('view-author') + '"]').removeClass('hidden-xs-up');
                }
                $('.access').removeClass('active');
                $(this).addClass('active');
                if ($(this).data('view-author') === 'all') {
                    $('.file').removeClass('hidden-xs-up');
                }
            });
        })
    </script>
@endsection

@section('content')
    <div id="fileManagerLeft" class="col-md-5 col-lg-4 mg-b-20 mg-md-b-0 d-none d-md-block">
        <label class="tx-11 tx-uppercase tx-medium">Типы файлов</label>

        <ul class="nav nav-file-manager">
            <li class="nav-item">
                <a href="javascript:void(0);" data-view-type="1" class="type nav-link">Регламентирующие документы организации</a>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0);" data-view-type="2" class="type nav-link">Регламентирующие документы субпроекта</a>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0);" data-view-type="3" class="type nav-link">Кадровые документы субпроекта</a>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0);" data-view-type="4" class="type nav-link">Финансовые документы субпроекта</a>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0);" data-view-type="5" class="type nav-link with-sub-second">Программные документы субпроекта</a>
                <ul class="nav-sub-second">
                    <li class="nav-item">
                        <a href="javascript:void(0);" data-view-type="all" class="type nav-link with-sub-third active">Анкеты для аутрич-сотрудников</a>
                    </li><!-- nav-item -->
                </ul>
            </li><!-- nav-item -->
            <li class="nav-item">
                <a href="javascript:void(0);" data-view-type="audio" class="type nav-link">Объяснительные</a>
            </li>
        </ul>

        <br>

        <label class="tx-11 tx-uppercase tx-medium">Доступные мне</label>
        <nav class="nav nav-file-manager mg-b-20">
            <a href="javascript:void(0);" data-view-author="all" class="access nav-link active">Все файлы</a>
            @if(auth()->user()->role == 1)
                @php($dirs = Storage::allDirectories('public/users/'))
                @foreach($dirs as $key => $value)
                    @php($dir = explode('/', $value))
                    @if(count($dir) === 3)
                        <a href="javascript:void(0);" data-view-author="{{ $dir[2] }}"
                           class="access nav-link">{{ \App\User::find($dir[2])->name_ru }}</a>
                    @else
                        <a href="javascript:void(0);" data-view-author="{{ $dir[2] }}"
                           data-view-directory="{{ $dir[3] }}" class="access nav-link">{{ $dir[3] }}</a>
                    @endif
                @endforeach
            @endif
        </nav>
        <a href="" class="btn btn-secondary btn-block">Создать папку</a>
    </div><!-- col-3 -->

    <div class="col-md-7 col-lg-8">
        <div class="file-manager-header">
            <div>
                <button href="javascript:void(0);" class="btn btn-outline-primary"><i class="icon ion-trash-b"></i>
                </button>
                <a href="javascript:void(0);" class="btn btn-success bd-transparent pd-sm-x-15">Загрузить файлы</a>
            </div>
            <div>
                <a id="showLeft" href="" class="btn btn-outline-secondary btn-icon d-md-none">
                    <div><i class="icon ion-navicon-round"></i></div>
                </a>
            </div>
        </div><!-- d-flex -->

        <div class="row row-xs mg-t-20">
            @php($files = Storage::allFiles('public/users/'))
            @php(sort($files))
            @foreach($files as $key => $value)
                <?php
                $author = explode('/', $value);
                count($author) === 5 ? $directory = $author[3] : $directory = '';
                $file = pathinfo($value);
                if ($file['extension'] === "xlsx" || $file['extension'] === "docx" || $file['extension'] === "pptx") {
                    $classExtension = 'ion-ios-paper-outline';
                    $type = 'doc';
                } elseif ($file['extension'] === "pdf" || $file['extension'] === "png" || $file['extension'] === "jpg" || $file['extension'] === "jpeg") {
                    $classExtension = 'ion-image';
                    $type = 'image';
                } elseif ($file['extension'] === "mp3") {
                    $classExtension = 'ion-ios-volume-high';
                    $type = 'audio';
                } elseif ($file['extension'] === "mp4" || $file['extension'] === "avi") {
                    $classExtension = 'ion-ios-film-outline';
                    $type = 'video';
                } else {
                    $classExtension = 'ion-ios-help-outline';
                    $type = 'unknown';
                }
                ?>
                <div data-file-type="{{ $type }}" data-file-author="{{ $author[2] }}"
                     data-file-directory="{{ $directory }}" class="file col-6 col-sm-4 col-lg-3 col-xl-2">
                    <a href="{{ Storage::url($value) }}" class="file-manager-icon">
                        <div>
                            <i class="icon {{ $classExtension }}"></i>
                        </div><!-- file-manager-icon -->
                        <h6>{{ $file['basename'] }}</h6>
                    </a>
                </div><!-- col -->
            @endforeach
        </div><!-- row -->
    </div><!-- col-9 -->
@endsection
