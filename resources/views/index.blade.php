@extends('layouts.app')

@php
    if(Auth::user()->role <= 3 || array_key_exists(2, Auth::user()->positions())) {
        $month = \Carbon\Carbon::now()->month + 2;
        $plannedAnswers[1] = 0;
        $plannedAnswers[2] = 18*$month*2;
        $plannedAnswers[3] = 20*$month*2;
        $plannedAnswers[4] = 14*$month*2;
        $plannedAnswers[5] = 20*$month*2;
        $plannedAnswers[6] = 13*$month*2;
        $plannedAnswers[7] = 15*$month*2;
        $plannedAnswers[8] = 46*$month*2;
        $plannedAnswers[9] = 20*$month*2;
        $plannedAnswers[10] = 7*$month*2;
        $plannedAnswers[11] = 0;
        $plannedAnswers[12] = 20*$month*2;
        $plannedAnswers[13] = 10*$month*2;
        $plannedAnswers[14] = 8*$month*2;

        $RVP = 10*$month;
        $OPU = 30*$month;
    }
@endphp

@section('styles')
    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('scriptsFooter')
    <script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('lib/chart.js/Chart.js') }}"></script>

    <script>
        $('.select2').select2();
        @if(Auth::user()->role <= 4 && array_key_exists(2, Auth::user()->positions()))
        $('.select2').on('change', function () {
            window.location = '{{ route('index') }}?region=' + $(this).val();
        });

        var dataForms = new Chart($('#dataForms'), {
            type: 'bar',
            data: {
                labels: [
                    'RVP',
                    'OPU',
                    'Анкеты аутрич сотрудников',
                ],
                datasets: [
                    {
                        label: 'Введенных форм',
                        data: [
                            '{{ $countMiO }}',
                            '{{ $countOPU }}',
                            '{{ $countAnswers }}'
                        ],
                        backgroundColor: ['#324463', '#F1C40F', '#E74C3C']
                    },
                    {
                        label: 'Запланированно',
                        data: [
                            {{ is_null($_GET['region']) && Auth::user()->role === 4 ? $RVP : $RVP*12 }},
                            {{ is_null($_GET['region']) && Auth::user()->role === 4 ? $OPU : $OPU*12 }},
                            {{ is_null($_GET['region']) && Auth::user()->role === 4 ? $plannedAnswers[$_GET['region'] ?? auth()->user()->region->id] : array_sum($plannedAnswers) }},
                        ],
                        backgroundColor: '#2ECC71'
                    }
                ]
            },
            options: {
                legend: {
                    display: false,
                    labels: {
                        display: false
                    }
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            fontSize: 10,
                        }
                    }],
                    xAxes: [{
                        ticks: {
                            beginAtZero: true,
                            fontSize: 11
                        }
                    }]
                }
            }
        });

        new Chart($('#miovisitions'), {
            type: 'horizontalBar',
            data: {!! json_encode($dataMio) !!},
            options: {
                legend: {
                    display: true,
                    labels: {
                        display: true
                    }
                },
                scales: {
                    yAxes: [{
                        stacked: true
                    }],
                    xAxes: [{
                        stacked: true
                    }]
                }
            }
        });

        new Chart($('#opu'), {
            type: 'horizontalBar',
            data: {!! json_encode($dataOPU) !!},
            options: {
                legend: {
                    display: true,
                    labels: {
                        display: true
                    }
                },
                scales: {
                    yAxes: [{
                        stacked: true
                    }],
                    xAxes: [{
                        stacked: true
                    }]
                }
            }
        });
        @endif
    </script>
@endsection


@section('header')
    <div class="sh-breadcrumb">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{ route('index') }}">INTILISH v3.1</a>
            <span class="breadcrumb-item active">Главная</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <div class="sh-pagetitle">

        <div class="input-group">
            <!-- search block -->
        </div><!-- input-group -->

        <div class="sh-pagetitle-left">
            <div class="sh-pagetitle-icon"><i class="icon ion-ios-home"></i></div>
            <div class="sh-pagetitle-title">
                <span>Вся сводка</span>
                <h2>Главная</h2>
            </div><!-- sh-pagetitle-left-title -->
        </div><!-- sh-pagetitle-left -->

    </div><!-- sh-pagetitle -->
@endsection

@section('content')
    @if(Auth::user()->role <= 4 && array_key_exists(2, Auth::user()->positions()))
        <div class="col-lg-6 mg-b-10">
            <div class="card bd-primary">
                <div class="card-header bg-primary tx-white align-middle"><span class="lead">Введенные данные</span>
                    @if(Auth::user()->region->id === 0)
                        <div class="float-right">
                            <div class="form-group mg-b-0-force">
                                <select class="form-control select2" data-placeholder="Выберите регион">
                                    <option label="Выберите регион"></option>
                                    @foreach($regions as $region)
                                        @if($region->id !== 1 && $region->id !== 11)
                                            <option {{ isset($_GET['region']) && $_GET['region'] == $region->id ? 'selected' : ''}} value="{{ $region->id }}">{{ $region->encoding }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="card-body pd-sm-30">
                    <canvas id="dataForms"></canvas>
                </div><!-- card-body -->
            </div><!-- card -->
        </div><!-- col-6 -->

        <div class="col-lg-6 mg-b-10">
            <div class="card bd-primary">
                <div class="card-header bg-primary tx-white align-middle"><span
                            class="lead">Мониторинговые визиты</span></div>
                <div class="card-body pd-sm-30">
                    <canvas id="miovisitions"></canvas>
                </div><!-- card-body -->
            </div><!-- card -->
        </div><!-- col-6 -->

        <div class="col-lg-6 mg-b-10">
            <div class="card bd-primary">
                <div class="card-header bg-primary tx-white align-middle"><span
                            class="lead">Оценка предоставленных услуг</span></div>
                <div class="card-body pd-sm-30">
                    <canvas id="opu"></canvas>
                </div><!-- card-body -->
            </div><!-- card -->
        </div><!-- col-6 -->

        @if(Auth::user()->role === 4 && array_key_exists(2, Auth::user()->positions()))
            <div class="col-lg-6 mg-b-10">
                <div class="card bd-primary">
                    <div class="card-header bg-primary tx-white align-middle"><span
                                class="lead">Анкетирование сотрудников</span></div>
                    <div class="card-body pd-sm-30">
                        <table class="table table-hover table-bordered table-primary mg-b-0">
                            <thead class="text-center">
                            <tr>
                                <td>Регион</td>
                                <td>Опросник</td>
                                <td>Месяц</td>
                                <td>Количество</td>
                                <td>Сумма</td>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($questRes as $key => $value)
                                @php($i = 0)
                                @php($sumArrays = 0)
                                @foreach($questRes[$key] as $ke => $val)
                                    @if($ke !== 'sum')
                                        @php($sumArrays += count($questRes[$key][$ke]))
                                    @endif
                                @endforeach
                                @foreach($questRes[$key] as $ke => $val)
                                    @if($ke !== 'sum')
                                        @php($j = 0)
                                        @foreach($questRes[$key][$ke] as $k => $v)
                                            @if($i === 0)
                                                <tr>
                                                    <td class="align-middle" rowspan="{{ $sumArrays }}">{{ $key }}</td>
                                                    <td class="align-middle">{{ $ke }}</td>
                                                    <td class="align-middle">{{ $k }}</td>
                                                    <td class="align-middle">{{ $v }}</td>
                                                    <td class="align-middle"
                                                        rowspan="{{ $sumArrays }}">{{ $questRes[$key]['sum'] }}</td>
                                                </tr>
                                                @php($i++)
                                            @elseif($j === 0)
                                                <tr>
                                                    <td class="align-middle"
                                                        rowspan="{{ count($questRes[$key][$ke]) }}">{{ $ke }}</td>
                                                    <td class="align-middle">{{ $k }}</td>
                                                    <td class="align-middle">{{ $v }}</td>
                                                </tr>
                                                @php($j++)
                                            @else
                                                <tr>
                                                    <td class="align-middle">{{ $k }}</td>
                                                    <td class="align-middle">{{ $v }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr class="text-center">
                                <td>Регион</td>
                                <td>Опросник</td>
                                <td>Месяц</td>
                                <td>Количество</td>
                                <td>Сумма</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div><!-- card-body -->
                </div><!-- card -->
            </div><!-- col-6 -->
        @endif
    @endif
@endsection