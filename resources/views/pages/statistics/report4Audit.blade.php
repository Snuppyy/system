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
            <span class="breadcrumb-item active">Статистика по деятельности регионов</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <div class="sh-pagetitle">

        <div class="input-group">
            <!-- search block -->
        </div><!-- input-group -->

        <div class="sh-pagetitle-left">
            <div class="sh-pagetitle-icon"><i class="icon ion-stats-bars"></i></div>
            <div class="sh-pagetitle-title">
                <h2>Статистика по деятельности регионов</h2>
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
        <a href="{{ route('report4Audit', ['project' => 1, 'year' => '2018', 'month' => 'year']) }}"
           class="btn btn-warning wd-100p {{ $page[count($page)-4] == 1 ? 'active' : '' }}">1 часть проекта</a>
        <a href="{{ route('report4Audit', ['project' => 2, 'year' => '2019', 'month' => 'year']) }}"
           class="btn btn-warning wd-100p {{ $page[count($page)-4] == 2 ? 'active' : '' }}">2 часть проекта</a>
    </div>
    <div class="btn-group wd-100p">
        <a href="{{ route('report4Audit', ['project' => $page[count($page)-4], 'year' => '2018', 'month' => 'quarter1']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == 'quarter1' && $page[count($page)-2] == '2018' ? 'active' : '' }}">1 квартал</a>
        <a href="{{ route('report4Audit', ['project' => $page[count($page)-4], 'year' => '2018', 'month' => 'quarter2']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == 'quarter2' && $page[count($page)-2] == '2018' ? 'active' : '' }}">2 квартал</a>
        <a href="{{ route('report4Audit', ['project' => $page[count($page)-4], 'year' => '2018', 'month' => 'half1']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == 'half1' && $page[count($page)-2] == '2018' ? 'active' : '' }}">1 полугодие</a>
        <a href="{{ route('report4Audit', ['project' => $page[count($page)-4], 'year' => '2018', 'month' => 'quarter3']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == 'quarter3' && $page[count($page)-2] == '2018' ? 'active' : '' }}">3 квартал</a>
        <a href="{{ route('report4Audit', ['project' => $page[count($page)-4], 'year' => '2018', 'month' => 'quarter4']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == 'quarter4' && $page[count($page)-2] == '2018' ? 'active' : '' }}">4 квартал</a>
        <a href="{{ route('report4Audit', ['project' => $page[count($page)-4], 'year' => '2018', 'month' => 'half2']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == 'half2' && $page[count($page)-2] == '2018' ? 'active' : '' }}">2 полугодие</a>
        <a href="{{ route('report4Audit', ['project' => $page[count($page)-4], 'year' => '2018', 'month' => 'year']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == 'year' && $page[count($page)-2] == '2018' ? 'active' : '' }}">2018 год</a>
    </div>
    <div class="btn-group wd-100p">
        <a href="{{ route('report4Audit', ['project' => $page[count($page)-4], 'year' => '2018', 'month' => '01']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '01' && $page[count($page)-2] == '2018' ? 'active' : '' }}">Январь</a>
        <a href="{{ route('report4Audit', ['project' => $page[count($page)-4], 'year' => '2018', 'month' => '02']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '02' && $page[count($page)-2] == '2018' ? 'active' : '' }}">Февраль</a>
        <a href="{{ route('report4Audit', ['project' => $page[count($page)-4], 'year' => '2018', 'month' => '03']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '03' && $page[count($page)-2] == '2018' ? 'active' : '' }}">Март</a>
        <a href="{{ route('report4Audit', ['project' => $page[count($page)-4], 'year' => '2018', 'month' => '04']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '04' && $page[count($page)-2] == '2018' ? 'active' : '' }}">Апрель</a>
        <a href="{{ route('report4Audit', ['project' => $page[count($page)-4], 'year' => '2018', 'month' => '05']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '05' && $page[count($page)-2] == '2018' ? 'active' : '' }}">Май</a>
        <a href="{{ route('report4Audit', ['project' => $page[count($page)-4], 'year' => '2018', 'month' => '06']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '06' && $page[count($page)-2] == '2018' ? 'active' : '' }}">Июнь</a>
        <a href="{{ route('report4Audit', ['project' => $page[count($page)-4], 'year' => '2018', 'month' => '07']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '07' && $page[count($page)-2] == '2018' ? 'active' : '' }}">Июль</a>
        <a href="{{ route('report4Audit', ['project' => $page[count($page)-4], 'year' => '2018', 'month' => '08']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '08' && $page[count($page)-2] == '2018' ? 'active' : '' }}">Август</a>
        <a href="{{ route('report4Audit', ['project' => $page[count($page)-4], 'year' => '2018', 'month' => '09']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '09' && $page[count($page)-2] == '2018' ? 'active' : '' }}">Сентябрь</a>
        <a href="{{ route('report4Audit', ['project' => $page[count($page)-4], 'year' => '2018', 'month' => '10']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '10' && $page[count($page)-2] == '2018' ? 'active' : '' }}">Октябрь</a>
        <a href="{{ route('report4Audit', ['project' => $page[count($page)-4], 'year' => '2018', 'month' => '11']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '11' && $page[count($page)-2] == '2018' ? 'active' : '' }}">Ноябрь</a>
        <a href="{{ route('report4Audit', ['project' => $page[count($page)-4], 'year' => '2018', 'month' => '12']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '12' && $page[count($page)-2] == '2018' ? 'active' : '' }}">Декабрь</a>
    </div>
    <div class="btn-group wd-100p">
        <a href="{{ route('report4Audit', ['project' => $page[count($page)-4], 'year' => '2019', 'month' => 'quarter1']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == 'quarter1' && $page[count($page)-2] == '2019' ? 'active' : '' }}">1 квартал</a>
        <a href="{{ route('report4Audit', ['project' => $page[count($page)-4], 'year' => '2019', 'month' => 'quarter2']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == 'quarter2' && $page[count($page)-2] == '2019' ? 'active' : '' }}">2 квартал</a>
        <a href="{{ route('report4Audit', ['project' => $page[count($page)-4], 'year' => '2019', 'month' => 'half1']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == 'half1' && $page[count($page)-2] == '2019' ? 'active' : '' }}">1 полугодие</a>
        <a href="{{ route('report4Audit', ['project' => $page[count($page)-4], 'year' => '2019', 'month' => 'quarter3']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == 'quarter3' && $page[count($page)-2] == '2019' ? 'active' : '' }}">3 квартал</a>
        <a href="{{ route('report4Audit', ['project' => $page[count($page)-4], 'year' => '2019', 'month' => 'quarter4']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == 'quarter4' && $page[count($page)-2] == '2019' ? 'active' : '' }}">4 квартал</a>
        <a href="{{ route('report4Audit', ['project' => $page[count($page)-4], 'year' => '2019', 'month' => 'half2']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == 'half2' && $page[count($page)-2] == '2019' ? 'active' : '' }}">2 полугодие</a>
        <a href="{{ route('report4Audit', ['project' => $page[count($page)-4], 'year' => '2019', 'month' => 'year']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == 'year' && $page[count($page)-2] == '2019' ? 'active' : '' }}">2019 год</a>
    </div>
    <div class="btn-group wd-100p">
        <a href="{{ route('report4Audit', ['project' => $page[count($page)-4], 'year' => '2019', 'month' => '01']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '01' && $page[count($page)-2] == '2019' ? 'active' : '' }}">Январь</a>
        <a href="{{ route('report4Audit', ['project' => $page[count($page)-4], 'year' => '2019', 'month' => '02']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '02' && $page[count($page)-2] == '2019' ? 'active' : '' }}">Февраль</a>
        <a href="{{ route('report4Audit', ['project' => $page[count($page)-4], 'year' => '2019', 'month' => '03']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '03' && $page[count($page)-2] == '2019' ? 'active' : '' }}">Март</a>
        <a href="{{ route('report4Audit', ['project' => $page[count($page)-4], 'year' => '2019', 'month' => '04']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '04' && $page[count($page)-2] == '2019' ? 'active' : '' }}">Апрель</a>
        <a href="{{ route('report4Audit', ['project' => $page[count($page)-4], 'year' => '2019', 'month' => '05']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '05' && $page[count($page)-2] == '2019' ? 'active' : '' }}">Май</a>
        <a href="{{ route('report4Audit', ['project' => $page[count($page)-4], 'year' => '2019', 'month' => '06']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '06' && $page[count($page)-2] == '2019' ? 'active' : '' }}">Июнь</a>
        <a href="{{ route('report4Audit', ['project' => $page[count($page)-4], 'year' => '2019', 'month' => '07']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '07' && $page[count($page)-2] == '2019' ? 'active' : '' }}">Июль</a>
        <a href="{{ route('report4Audit', ['project' => $page[count($page)-4], 'year' => '2019', 'month' => '08']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '08' && $page[count($page)-2] == '2019' ? 'active' : '' }}">Август</a>
        <a href="{{ route('report4Audit', ['project' => $page[count($page)-4], 'year' => '2019', 'month' => '09']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '09' && $page[count($page)-2] == '2019' ? 'active' : '' }}">Сентябрь</a>
        <a href="{{ route('report4Audit', ['project' => $page[count($page)-4], 'year' => '2019', 'month' => '10']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '10' && $page[count($page)-2] == '2019' ? 'active' : '' }}">Октябрь</a>
        <a href="{{ route('report4Audit', ['project' => $page[count($page)-4], 'year' => '2019', 'month' => '11']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '11' && $page[count($page)-2] == '2019' ? 'active' : '' }}">Ноябрь</a>
        <a href="{{ route('report4Audit', ['project' => $page[count($page)-4], 'year' => '2019', 'month' => '12']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '12' && $page[count($page)-2] == '2019' ? 'active' : '' }}">Декабрь</a>
    </div>

    @if($_SERVER['HTTP_X_REAL_IP'] === '172.16.1.25')
        {{--        {{ dd($countQualityReturn['02-AN']) }}--}}
    @endif


    <table class="table table-bordered table-hover">
        <thead>
        <th>Регион</th>
{{--        <th>Время предоставления</th>--}}
{{--        <th>Протокол мониторинга сетей</th>--}}
        <th>Количество аутрич на мониторинге сетей</th>
        <th>Анкеты по определению качества услуг</th>
{{--        <th>Протокол вебинара</th>--}}
        <th>Количество аутрич-сотрудников на вебинаре</th>
        <th>Количество ассистентов на вебинаре</th>
        <th>Количество волонтеров на вебинаре</th>
{{--        <th>Протокол семинара</th>--}}
        <th>Количество аутрич-сотрудников на семинаре</th>
        <th>Количество ассистентов на семинаре</th>
        <th>Количество волонтеров на семинаре</th>
        <th>Количество анкет пре/пост</th>
        <th>Отчеты по мониторингу мест продажи</th>
{{--        <th>Протокол встречи с сотрудниками ОЦС</th>--}}
{{--        <th>Ежемесячный программный отчет</th>--}}
        </thead>

        <tbody>
        @foreach($regions as $region)
            <tr class="text-center {{ $saveDataReturn[$region->id]['editor'] == 0 && $saveDataReturn[$region->id]['miovisitions'] && $saveDataReturn[$region->id]['webinar'] && $saveDataReturn[$region->id]['seminar'] && $saveDataReturn[$region->id]['meetings'] && $saveDataReturn[$region->id]['report_month'] ? 'success' : 'danger' }}"
                data-region="{{ $region->id }}"
                data-date="{{ $page[count($page)-2] . '-' . $page[count($page)-1] . '-01' }}">
                <td>{{ $region->encoding }}</td>
{{--                <td data-type="complete" class="editor datetime" nowrap>--}}
{{--                    {{ $saveDataReturn[$region->id]['complete'] ?? 'Не определено' }}--}}
{{--                </td>--}}
{{--                <td data-type="miovisitions"--}}
{{--                    class="success editor {{ $saveDataReturn[$region->id]['miovisitions'] ?? 'empty' }}">--}}
{{--                    {{ $saveDataReturn[$region->id]['miovisitions'] ?? 'N/A' }}--}}
{{--                </td>--}}
                <td class="success {{ $countQualityOutreachesReturn[$region->encoding] ?? 'empty' }}">
                    {{ $countQualityOutreachesReturn[$region->encoding] ?? 'N/A' }}
                </td>
                <td class="success {{ $countQualityReturn[$region->encoding]['all'] ?? 'empty' }} {{ $countQualityReturn[$region->encoding]['scan'] === $countQualityReturn[$region->encoding]['all'] ? 'success' : 'nope' }}">
                    {{ checkNull($countQualityReturn[$region->encoding]['all']) === checkNull($countQualityReturn[$region->encoding]['scan']) ? ($countQualityReturn[$region->encoding]['scan'] ?? 'N/A') : $countQualityReturn[$region->encoding]['all'] . ' / ' . $countQualityReturn[$region->encoding]['noScan'] }}
                </td>
{{--                <td data-type="webinar" class="success editor {{ $saveDataReturn[$region->id]['webinar'] ?? 'empty' }}">--}}
{{--                    {{ $saveDataReturn[$region->id]['webinar'] ?? 'N/A' }}--}}
{{--                </td>--}}
                <td class="success {{ $countAnswersWebinarOutreachReturn[$region->encoding] ?? 'empty' }}">
                    {{ $countAnswersWebinarOutreachReturn[$region->encoding] ?? 'N/A' }}
                </td>
                <td class="success">
                    {{ $countAnswersWebinarAssistantReturn[$region->encoding] ?? 'N/A' }}
                </td>
                <td class="success">
                    {{ $countAnswersWebinarVolunteerReturn[$region->encoding] ?? 'N/A' }}
                </td>
{{--                <td data-type="seminar" class="success editor {{ $saveDataReturn[$region->id]['seminar'] ?? 'empty' }}">--}}
{{--                    {{ $saveDataReturn[$region->id]['seminar'] ?? 'N/A' }}--}}
{{--                </td>--}}
                <td class="success {{ $countAnswersSeminarOutreachReturn[$region->encoding] ?? 'empty' }}">
                    {{ $countAnswersSeminarOutreachReturn[$region->encoding] ?? 'N/A' }}
                </td>
                <td class="success">
                    {{ $countAnswersSeminarAssistantReturn[$region->encoding] ?? 'N/A' }}
                </td>
                <td class="success">
                    {{ $countAnswersSeminarVolunteerReturn[$region->encoding] ?? 'N/A' }}
                </td>
                <td class="success {{ $countAnswersReturn[$region->encoding]['all'] ?? 'empty' }} {{ $countAnswersReturn[$region->encoding]['scan'] === $countAnswersReturn[$region->encoding]['all'] ? 'success' : 'nope' }}">
                    {{ checkNull($countAnswersReturn[$region->encoding]['all']) === checkNull($countAnswersReturn[$region->encoding]['scan']) ? ($countAnswersReturn[$region->encoding]['scan'] ?? 'N/A') : $countAnswersReturn[$region->encoding]['all'] . ' / ' . $countAnswersReturn[$region->encoding]['noScan'] }}
                </td>
                <td class="success {{ $countVisitionsReturn[$region->encoding]['all'] ?? 'empty' }} {{ $countVisitionsReturn[$region->encoding]['scan'] === $countVisitionsReturn[$region->encoding]['all'] ? 'success' : 'nope' }}">
                    {{ checkNull($countVisitionsReturn[$region->encoding]['all']) === checkNull($countVisitionsReturn[$region->encoding]['scan']) ? ($countVisitionsReturn[$region->encoding]['scan'] ?? 'N/A') : $countVisitionsReturn[$region->encoding]['all'] . ' / ' . $countVisitionsReturn[$region->encoding]['noScan'] }}
                </td>
{{--                <td data-type="meetings"--}}
{{--                    class="success editor {{ $saveDataReturn[$region->id]['meetings'] ?? 'empty' }}">--}}
{{--                    {{ $saveDataReturn[$region->id]['meetings'] ?? 'N/A' }}--}}
{{--                </td>--}}
{{--                <td data-type="report_month"--}}
{{--                    class="success editor {{ $saveDataReturn[$region->id]['report_month'] ?? 'empty' }}">--}}
{{--                    {{ $saveDataReturn[$region->id]['report_month'] ?? 'N/A' }}--}}
{{--                </td>--}}
            </tr>
        @endforeach
        </tbody>

        <tfoot>
        <th>Регион</th>
{{--        <th>Время предоставления</th>--}}
{{--        <th>Протокол мониторинга сетей</th>--}}
        <th>Количество аутрич на мониторинге сетей</th>
        <th>Анкеты по определению качества услуг</th>
{{--        <th>Протокол вебинара</th>--}}
        <th>Количество аутрич-сотрудников на вебинаре</th>
        <th>Количество ассистентов на вебинаре</th>
        <th>Количество волонтеров на вебинаре</th>
{{--        <th>Протокол семинара</th>--}}
        <th>Количество аутрич-сотрудников на семинаре</th>
        <th>Количество ассистентов на семинаре</th>
        <th>Количество волонтеров на семинаре</th>
        <th>Количество анкет пре/пост</th>
        <th>Отчеты по мониторингу мест продажи</th>
{{--        <th>Протокол встречи с сотрудниками ОЦС</th>--}}
{{--        <th>Ежемесячный программный отчет</th>--}}
        </tfoot>
    </table>
@endsection
