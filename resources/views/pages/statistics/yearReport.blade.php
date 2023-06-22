@extends('layouts.app')

@section('styles')
    <link href="{{ asset('lib/select2/dist/css/select2.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('lib/datetimepicker/daterangepicker.css') }}"/>
    <link href="{{ asset('lib/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet">
    <style>
        .disabled {
            color: currentColor;
            cursor: not-allowed;
            opacity: 0.5;
            text-decoration: none;
        }

        .empty {
            background: #ff8282 !important;
        }

        .success {
            background: #82ff9b;
        }

        td.nope {
            background: #f5ff82 !important;
        }

        .unselectable {
            -webkit-touch-callout: none; /* iOS Safari */
            -webkit-user-select: none; /* Chrome/Safari/Opera */
            -khtml-user-select: none; /* Konqueror */
            -moz-user-select: none; /* Firefox */
            -ms-user-select: none; /* Internet Explorer/Edge */
            user-select: none;
            /* Non-prefixed version, currently
                                   not supported by any browser */
        }

        .table th, .table td {
            color: black !important;
            font-weight: bold !important;
        }
    </style>
@endsection

@section('scripts')
    <script src="{{ asset('lib/datetimepicker/jquery.datetimepicker.full.js') }}"></script>
    <script src="{{ asset('lib/mask/jquery.mask.min.js') }}"></script>
    {{--    <script type="text/javascript" src="{{ asset('lib/moment/moment.js') }}"></script>--}}
    <script type="text/javascript" src="{{ asset('lib/moment/locale/ru.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/moment/moment-timezone.js') }}"></script>
@endsection

@section('scriptsFooter')

@endsection


@section('header')
    <div class="sh-breadcrumb">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{ route('index') }}">INTILISH v3.1</a>
            <a class="breadcrumb-item" href="{{ route('index') }}">Главная</a>
            <span class="breadcrumb-item active">Статистика по деятельности региона</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <div class="sh-pagetitle">

        <div class="input-group">
            <!-- search block -->
        </div><!-- input-group -->

        <div class="sh-pagetitle-left">
            <div class="sh-pagetitle-icon"><i class="icon ion-stats-bars"></i></div>
            <div class="sh-pagetitle-title">
                <h2>Статистика по деятельности региона</h2>
            </div><!-- sh-pagetitle-left-title -->
        </div><!-- sh-pagetitle-left -->

    </div><!-- sh-pagetitle -->
@endsection

@section('content')
    @php
        $page = explode('/',url()->current());

    function checkNull($value) {
        if(is_null($value)) return false;
        else return $value;
    }
    @endphp
    <div class="btn-group wd-100p">
        <a href="{{ route('yearReport', ['year' => '2018', 'region' => auth()->user()->region->encoding]) }}"
           class="btn btn-secondary wd-100p">2018 год</a>
        <a href="{{ route('yearReport', ['year' => '2019', 'region' => auth()->user()->region->encoding]) }}"
           class="btn btn-secondary wd-100p">2019 год</a>
        <a href="{{ route('yearReport', ['year' => '2020', 'region' => auth()->user()->region->encoding]) }}"
           class="btn btn-secondary wd-100p">2020 год</a>
    </div>

    @if(auth()->user()->role <= 2)
        <div class="btn-group wd-100p">
            @foreach($regionsData as $region)
                <a href="{{ route('yearReport', ['year' => '2019', 'region' => $region->encoding]) }}"
                   class="btn btn-secondary wd-100p {{ $page[count($page)-1] == $region->encoding ? 'active' : '' }}">{{ $region->encoding }}</a>
            @endforeach
        </div>
    @endif

    @if($_SERVER['HTTP_X_REAL_IP'] === '172.16.1.25')
        {{--        {{ dd($countQualityReturn['02-AN']) }}--}}
    @endif


    <table class="table table-bordered table-hover">
        <thead>
        <th>Месяц</th>
        <th>Время предоставления</th>
        <th>Протокол мониторинга сетей</th>
        <th>Количество аутрич на мониторинге сетей</th>
        <th>Анкеты по определению качества услуг</th>
        <th>Протокол вебинара</th>
        <th>Количество вебинаров</th>
        <th>Количество аутрич-сотрудников на вебинаре</th>
        <th>Количество ассистентов на вебинаре</th>
        <th>Количество волонтеров на вебинаре</th>
        <th>Протокол семинара</th>
        <th>Количество зарегистрированных семинаров</th>
        <th>Количество аутрич-сотрудников на семинаре</th>
        <th>Количество ассистентов на семинаре</th>
        <th>Количество волонтеров на семинаре</th>
        <th>Количество анкет пре/пост</th>
        <th>Отчеты по мониторингу мест продажи</th>
        <th>Протокол встречи с сотрудниками ОЦС</th>
        <th>Ежемесячный программный отчет</th>
        </thead>

        <tbody>
        @foreach($regions as $id => $month)
            <tr style="{{ now()->month === $id ? 'border: 5px solid blue;' : '' }}"  class="text-center {{ $saveDataReturn[$id]['editor'] == 0 && $saveDataReturn[$id]['miovisitions'] && $saveDataReturn[$id]['webinar'] && $saveDataReturn[$id]['seminar'] && $saveDataReturn[$id]['meetings'] && $saveDataReturn[$id]['report_month'] ? 'success' : 'danger' }}"
                data-region="{{ $id }}"
                data-date="{{ $page[count($page)-2] . '-' . $page[count($page)-1] . '-01' }}">
                <td>{{ $month }}</td>
                <td data-type="complete" class="editor datetime" nowrap>
                    {{ $saveDataReturn[$id]['complete'] ?? 'Не определено' }}
                </td>
                <td data-type="miovisitions"
                    class="success editor {{ $saveDataReturn[$id]['miovisitions'] ?? 'empty' }}">
                    {{ $saveDataReturn[$id]['miovisitions'] ?? 'N/A' }}
                </td>
                <td class="success {{ $countQualityOutreachesReturn[$month] ?? 'empty' }}">
                    {{ $countQualityOutreachesReturn[$month] ?? 'N/A' }}
                </td>
                <td class="success {{ $countQualityReturn[$month]['all'] ?? 'empty' }} {{ $countQualityReturn[$month]['scan'] === $countQualityReturn[$month]['all'] ? 'success' : 'nope' }}">
                    {{ checkNull($countQualityReturn[$month]['all']) === checkNull($countQualityReturn[$month]['scan']) ? ($countQualityReturn[$month]['scan'] ?? 'N/A') : $countQualityReturn[$month]['all'] . ' / ' . $countQualityReturn[$month]['noScan'] }}
                </td>
                <td data-type="webinar" class="success editor {{ $saveDataReturn[$id]['webinar'] ?? 'empty' }}">
                    {{ $saveDataReturn[$id]['webinar'] ?? 'N/A' }}
                </td>
                <td class="success {{ $countWebinar[$id] ?? 'empty' }}">
                    {{ $countWebinar[$id] ?? 'N/A' }}
                </td>
                <td class="success {{ $countAnswersWebinarOutreachReturn[$month] ?? 'empty' }}">
                    {{ $countAnswersWebinarOutreachReturn[$month] ?? 'N/A' }}
                </td>
                <td class="success">
                    {{ $countAnswersWebinarAssistantReturn[$month] ?? 'N/A' }}
                </td>
                <td class="success">
                    {{ $countAnswersWebinarVolunteerReturn[$month] ?? 'N/A' }}
                </td>
                <td data-type="seminar" class="success editor {{ $saveDataReturn[$id]['seminar'] ?? 'empty' }}">
                    {{ $saveDataReturn[$id]['seminar'] ?? 'N/A' }}
                </td>
                <td class="success {{ $countSeminar[$id] ?? 'empty' }}">
                    {{ $countSeminar[$id] ?? 'N/A' }}
                </td>
                <td class="success {{ $countAnswersSeminarOutreachReturn[$month] ?? 'empty' }}">
                    {{ $countAnswersSeminarOutreachReturn[$month] ?? 'N/A' }}
                </td>
                <td class="success">
                    {{ $countAnswersSeminarAssistantReturn[$month] ?? 'N/A' }}
                </td>
                <td class="success">
                    {{ $countAnswersSeminarVolunteerReturn[$month] ?? 'N/A' }}
                </td>
                <td class="success {{ $countAnswersReturn[$month]['all'] ?? 'empty' }} {{ $countAnswersReturn[$month]['scan'] === $countAnswersReturn[$month]['all'] ? 'success' : 'nope' }}">
                    {{ checkNull($countAnswersReturn[$month]['all']) === checkNull($countAnswersReturn[$month]['scan']) ? ($countAnswersReturn[$month]['scan'] ?? 'N/A') : $countAnswersReturn[$month]['all'] . ' / ' . $countAnswersReturn[$month]['noScan'] }}
                </td>
                <td class="success {{ $countVisitionsReturn[$month]['all'] ?? 'empty' }} {{ $countVisitionsReturn[$month]['scan'] === $countVisitionsReturn[$month]['all'] ? 'success' : 'nope' }}">
                    {{ checkNull($countVisitionsReturn[$month]['all']) === checkNull($countVisitionsReturn[$month]['scan']) ? ($countVisitionsReturn[$month]['scan'] ?? 'N/A') : $countVisitionsReturn[$month]['all'] . ' / ' . $countVisitionsReturn[$month]['noScan'] }}
                </td>
                <td data-type="meetings"
                    class="success editor {{ $saveDataReturn[$id]['meetings'] ?? 'empty' }}">
                    {{ $saveDataReturn[$id]['meetings'] ?? 'N/A' }}
                </td>
                <td data-type="report_month"
                    class="success editor {{ $saveDataReturn[$id]['report_month'] ?? 'empty' }}">
                    {{ $saveDataReturn[$id]['report_month'] ?? 'N/A' }}
                </td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <th>Месяц</th>
        <th>Время предоставления</th>
        <th>Протокол мониторинга сетей</th>
        <th>Количество аутрич на мониторинге сетей</th>
        <th>Анкеты по определению качества услуг</th>
        <th>Протокол вебинара</th>
        <th>Количество вебинаров</th>
        <th>Количество аутрич-сотрудников на вебинаре</th>
        <th>Количество ассистентов на вебинаре</th>
        <th>Количество волонтеров на вебинаре</th>
        <th>Протокол семинара</th>
        <th>Количество зарегистрированных семинаров</th>
        <th>Количество аутрич-сотрудников на семинаре</th>
        <th>Количество ассистентов на семинаре</th>
        <th>Количество волонтеров на семинаре</th>
        <th>Количество анкет пре/пост</th>
        <th>Отчеты по мониторингу мест продажи</th>
        <th>Протокол встречи с сотрудниками ОЦС</th>
        <th>Ежемесячный программный отчет</th>
        </tfoot>
    </table>
@endsection
