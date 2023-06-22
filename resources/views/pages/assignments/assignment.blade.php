@extends('layouts.app')

@section('styles')

@endsection

@section('scripts')

@endsection

@section('scriptsFooter')
    <script>

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
                <h2 class="{{ $assignment->textColor }}">Поручение №{{ $assignment->id }}</h2><br>
                <h2 class="{{ $assignment->textColor }}">Автор: {{ $assignment->author_name }}</h2>
            </div><!-- sh-pagetitle-left-title -->

        </div><!-- sh-pagetitle-left -->
    </div><!-- sh-pagetitle -->
@endsection

@section('content')
    {{--    @if(auth()->user()->role === 1)--}}
    {{--        <a href="{{ route('assignments-edit', ['id' => $assignment->id]) }}" class="btn btn-danger btn-block mg-b-10">--}}
    {{--            Отредактировать поручение--}}
    {{--        </a>--}}
    {{--    @elseif($assignment->author === auth()->user()->id)--}}
    {{--        <a href="{{ route('assignments-edit', ['id' => $assignment->id]) }}" class="btn btn-danger btn-block mg-b-10">--}}
    {{--            Отредактировать поручение--}}
    {{--        </a>--}}
    {{--    @endif--}}
    @php
        $administrants_name = explode(',', $assignment->administrants_name);
        $helpers_name = explode(',', $assignment->helpers_name);
        $supervisors_name = explode(',', $assignment->supervisors_name);
    @endphp

    <div class="col col-lg-12">
        @php
            if($assignment->end >= now() && $assignment->start <= now()){
                $progressLabelBefore = now()->diffForHumans($assignment->end);
                $progressLabel = $assignment->start.' - '.$assignment->end.' : АКТУАЛЬНО';
                $diffDateAssignment = $assignment->start->diffInSeconds($assignment->end);
                $diffDateNow = $assignment->start->diffInSeconds(now());
                $progressValue = round(100 / ($diffDateAssignment / $diffDateNow));
                $progressValue <= 33 ? $progressColor = 'bg-success' : '';
                $progressValue > 33 && $progressValue <= 66 ? $progressColor = 'bg-info' : '';
                $progressValue > 66 && $progressValue <= 99 ? $progressColor = 'bg-warning' : '';
            } elseif($assignment->end >= now() && $assignment->start >= now()) {
                $progressValue = 0;
                $progressLabel = $assignment->start.' - '.$assignment->end.' : НЕАКТУАЛЬНО';
                $progressColor = 'bg-secondary';
            } else {
                $progressValue = 100;
                $progressLabel = $assignment->start.' - '.$assignment->end.' : ПРОСРОЧЕНО';
                $progressColor = 'bg-danger';
            }
        @endphp
        <div id="filterMenu" class="wd-100p-force">
            <div class="btn-group wd-100p-force pd-r-0-force float-right mg-b-10">
                <div class="btn-group col-12">
                    <a href="javascript:void(0);" class="btn btn-danger btn-block mg-t-5-force">
                        Отклонить
                    </a>
                    <a href="javascript:void(0);" class="btn btn-warning btn-block mg-t-5-force">
                        Аннулировать
                    </a>
                    <a href="javascript:void(0);" class="btn btn-success btn-block mg-t-5-force">
                        Завершить
                    </a>
                </div>
            </div>
        </div>
        <h2 class="tx-center">Метка
            поручения: {{ $assignment->mark }}</h2>
        @if($assignmentMain)
            <h3 class="tx-center">Основное поручение: <a
                        href="{{ route('assignments-get', ['id' => $assignmentMain->id]) }}">{{ $assignmentMain->mark }}</a>
            </h3>
        @endif
        <div class="tx-center wd-100p-force">{{ $progressLabel }}</div>
        @if($progressLabelBefore)
            <div class="tx-center wd-100p-force">{{ $progressLabelBefore }} конца поручения</div>
        @endif
        <div class="progress mg-b-20">
            <div class="progress-bar {{ $progressColor }} progress-bar-striped progress-bar-animated" role="progressbar"
                 style="width: {{ $progressValue }}%"
                 aria-valuenow="{{ $progressValue }}" aria-valuemin="0" aria-valuemax="100">{{ $progressValue }}%
            </div>
        </div>

        <h3>Исполнители:</h3>
        <h4>
            <ul>
                @foreach($assignment->administrants_array as $name => $responsibility)
                    <li>
                        {{ $name }}
                        <ul>
                            <li>
                                {{ $responsibility }}
                            </li>
                        </ul>
                    </li>
                @endforeach
            </ul>
        </h4>
    </div>
    @if(!is_null($assignment->helpers_array))
        <div class="col col-lg-12">
            <h3>Помощники:</h3>
            <h4>
                <ul>
                    @foreach($assignment->helpers_array as $name => $responsibility)
                        <li>
                            {{ $name }}
                            <ul>
                                <li>
                                    {{ $responsibility }}
                                </li>
                            </ul>
                        </li>
                    @endforeach
                </ul>
            </h4>
        </div>
    @endif
    @if(!is_null($assignment->supervisors_array))
        <div class="col col-lg-12">
            <h3>Супервайзеры:</h3>
            <h4>
                <ul>
                    @foreach($assignment->supervisors_array as $name => $responsibility)
                        <li>
                            {{ $name }}
                            <ul>
                                <li>
                                    {{ $responsibility }}
                                </li>
                            </ul>
                        </li>
                    @endforeach
                </ul>
            </h4>
        </div>
    @endif
    <div class="row row-xs mg-t-20 col-lg-12">
        @foreach($assignment->files as $file)
            @php
                $i++;
                $ext = pathinfo($file, PATHINFO_EXTENSION);
                if($ext === 'jpg' || $ext === 'jpeg'){
                    $fileIcon = '-image';
                } elseif($ext === 'pdf'){
                    $fileIcon = '-pdf';
                } elseif($ext === 'xls' || $ext === 'xlsx'){
                    $fileIcon = '-excel';
                } elseif($ext === 'doc' || $ext === 'docx'){
                    $fileIcon = '-word';
                } elseif($ext === 'rar' || $ext === 'zip'){
                    $fileIcon = '-archive';
                }
            @endphp
            <div data-file-type="image" data-file-author="5" data-file-directory=""
                 class="file col-6 col-sm-4 col-lg-3 col-xl-2">
                <a href="{{ route('assignments-download', ['id' => $assignment->id, 'key' => $i-1]) }}"
                   class="file-manager-icon">
                    <h6>Документ №{{ $i }}</h6>
                    <div>
                        <i class="far fa-file{{ $fileIcon }}"></i>
                    </div><!-- file-manager-icon -->
                    <h6>Основная версия</h6>
                </a>
            </div><!-- col -->
        @endforeach
    </div>
    <div class="col col-lg-12 mg-t-20">
        <blockquote class="blockquote">
            <p class="mb-0">{{ $assignment->text }}</p>
        </blockquote>
    </div>
@endsection
