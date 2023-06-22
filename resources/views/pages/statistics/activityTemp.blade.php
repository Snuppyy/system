@extends('layouts.blank')
@section('content')
    @php
        if($assignment->end >= now() && $assignment->start <= now() && $mainActivity->status == 1){
            $progressLabelBefore = now()->diffForHumans($assignment->end);
            $progressLabel = $assignment->start.' - '.$assignment->end.' : АКТУАЛЬНО';
            $diffDateAssignment = $assignment->start->diffInSeconds($assignment->end);
            $diffDateNow = $assignment->start->diffInSeconds(now());
            $progressValue = round(100 / ($diffDateAssignment / $diffDateNow));
            $progressValue <= 33 ? $progressColor = 'success' : '';
            $progressValue > 33 && $progressValue <= 66 ? $progressColor = 'info' : '';
            $progressValue > 66 && $progressValue <= 99 ? $progressColor = 'warning' : '';
        } elseif($assignment->end >= now() && $assignment->start >= now() && $mainActivity->status == 1) {
            $progressValue = 0;
            $progressLabel = $assignment->start.' - '.$assignment->end.' : НЕАКТУАЛЬНО';
            $progressColor = 'secondary';
        } elseif($mainActivity->status == 1) {
            $progressValue = 100;
            $progressLabel = $assignment->start.' - '.$assignment->end.' : ПРОСРОЧЕНО';
            $progressColor = 'danger';
        } else {
            $progressValue = 100;
            $progressLabel = $assignment->start.' - '.$assignment->end.' : ВЫПОЛНЕНО';
            $progressColor = 'success';
        }
    @endphp
    <div class="col col-lg-12 card pd-20 pd-sm-30 bd-{{ $progressColor }}">
        <h2 class="tx-center">Метка
            поручения: {{ $assignment->mark }}</h2>
        <div class="tx-center wd-100p-force">{{ $progressLabel }}</div>
        @if($progressLabelBefore)
            <div class="tx-center wd-100p-force">{{ $progressLabelBefore }} конца поручения</div>
        @endif

        <div class="progress mg-b-20">
            <div class="progress-bar bg-{{ $progressColor }} progress-bar-striped progress-bar-animated" role="progressbar"
                 style="width: {{ $progressValue }}%"
                 aria-valuenow="{{ $progressValue }}" aria-valuemin="0" aria-valuemax="100">{{ $progressValue }}%
            </div>
        </div>

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

        <hr>
        @php
            setlocale(LC_TIME, 'ru_RU.UTF-8');
        @endphp
        @foreach($activities as $activity)
            <div class="card pd-10 bd-primary mg-t-10">
                <p>{{ $activity->comment }}</p>

                <div class="media align-items-center">
                    <div class="media-body">
                        <h6 class="tx-inverse tx-14 mg-b-5">{{ $users->find($activity->user)->name_ru }}</h6>
                        <p class="tx-12 mg-b-0">{{ \Carbon\Carbon::parse($activity->created_at)->formatLocalized('%d %B, %R') }}</p>
                    </div><!-- media-body -->

                    <div class="float-right">{{ $activity->start . ' - ' . $activity->end }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="font-weight-bold">{{ date_diff(date_create($activity->end), date_create($activity->start))->format('%H:%I') }}</span></div>
                </div><!-- media -->
            </div><!-- card -->
        @endforeach
    </div>
@endsection
