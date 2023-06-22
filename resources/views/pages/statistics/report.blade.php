@extends('layouts.app')

@section('styles')
    {{--    {{ abort(404) }}--}}
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
    <script>

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('td.editor').dblclick(function () {
            if (!$(this).hasClass('datetime')) {
                $(this).html('<input class="form-control" type="number">');
                $(this).children('input').focus()
            } else {
                $(this).html('<input class="form-control" id="datetime">');
                $('#datetime').datetimepicker({
                    format: 'Y-m-d H:i',
                    step: 1,
                    mask: '____-__-__ __:__',
                    timepicker: true
                }).focus();
                $(this).children('input').focus()
            }
        });

        $('td.editor').on('blur', 'input', function () {
            $value = $(this).val();
            $type = $(this).parent().data('type');
            $region = $(this).parents('tr').data('region');
            $date = $(this).parents('tr').data('date');
            $editor = $(this).parents('tr').children("td:contains('N/A')").length;
            $this = $(this);
            $.ajax({
                type: 'POST',
                async: false,
                url: '{{ route('reportSave') }}',
                data: '&type=' + $type + '&value=' + $value + '&region=' + $region + '&date=' + $date + '&editor=' + $editor,
                success: function (data) {
                    if ($editor == 0) location.reload();
                    $this.parent().attr('class', 'editor success');
                    $this.parent().html(data);
                }
            });
        });

        var sum = {};
        $('tbody tr').each(function (index, el) {
            $el = $(el).children();

            for (var i = 2; i < $el.length; i++) {
                // console.log($(el).children('td').eq(i).text());
                var val = $(el).children('td').eq(i).html();
                sum[i] += [val];
                // sum[i] += parseInt($(el).children('td').eq(i).text());
                // console.log($(el).children()[i]);
            }

            console.log(sum);
        });
    </script>
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
        <a href="{{ route('report', ['year' => '2019', 'month' => 'quarter1']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == 'quarter1' ? 'active' : '' }}">1 квартал</a>
        <a href="{{ route('report', ['year' => '2019', 'month' => 'quarter2']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == 'quarter2' ? 'active' : '' }}">2 квартал</a>
        <a href="{{ route('report', ['year' => '2019', 'month' => 'half1']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == 'half1' ? 'active' : '' }}">1 полугодие</a>
        <a href="{{ route('report', ['year' => '2019', 'month' => 'quarter3']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == 'quarter3' ? 'active' : '' }}">3 квартал</a>
        <a href="{{ route('report', ['year' => '2019', 'month' => 'quarter4']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == 'quarter4' ? 'active' : '' }}">4 квартал</a>
        <a href="{{ route('report', ['year' => '2019', 'month' => 'half2']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == 'half2' ? 'active' : '' }}">2 полугодие</a>
        <a href="{{ route('report', ['year' => '2019', 'month' => 'year']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-2] == '2019' ? 'active' : '' }}">2019 год</a>
    </div>
    <div class="btn-group wd-100p">
        <a href="{{ route('report', ['year' => '2018', 'month' => '11']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '11' ? 'active' : '' }}">Ноябрь 2018</a>
        <a href="{{ route('report', ['year' => '2018', 'month' => '12']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '12' ? 'active' : '' }}">Декабрь 2018</a>
        <a href="{{ route('report', ['year' => '2019', 'month' => '01']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '01' ? 'active' : '' }}">Январь</a>
        <a href="{{ route('report', ['year' => '2019', 'month' => '02']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '02' ? 'active' : '' }}">Февраль</a>
        <a href="{{ route('report', ['year' => '2019', 'month' => '03']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '03' ? 'active' : '' }}">Март</a>
        <a href="{{ route('report', ['year' => '2019', 'month' => '04']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '04' ? 'active' : '' }}">Апрель</a>
        <a href="{{ route('report', ['year' => '2019', 'month' => '05']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '05' ? 'active' : '' }}">Май</a>
        <a href="{{ route('report', ['year' => '2019', 'month' => '06']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '06' ? 'active' : '' }}">Июнь</a>
        <a href="{{ route('report', ['year' => '2019', 'month' => '07']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '07' ? 'active' : '' }}">Июль</a>
        <a href="{{ route('report', ['year' => '2019', 'month' => '08']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '08' ? 'active' : '' }}">Август</a>
        <a href="{{ route('report', ['year' => '2019', 'month' => '09']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '09' ? 'active' : '' }}">Сентябрь</a>
        <a href="{{ route('report', ['year' => '2019', 'month' => '10']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '10' ? 'active' : '' }}">Октябрь</a>
        <a href="{{ route('report', ['year' => '2019', 'month' => '11']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '11' ? 'active' : '' }}">Ноябрь</a>
        <a href="{{ route('report', ['year' => '2019', 'month' => '12']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '12' ? 'active' : '' }}">Декабрь</a>
    </div>

    <div class="btn-group wd-100p">
        <a href="{{ route('report', ['year' => '2020', 'month' => 'quarter1']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == 'quarter1' ? 'active' : '' }}">1 квартал</a>
        <a href="{{ route('report', ['year' => '2020', 'month' => 'quarter2']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == 'quarter2' ? 'active' : '' }}">2 квартал</a>
        <a href="{{ route('report', ['year' => '2020', 'month' => 'half1']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == 'half1' ? 'active' : '' }}">1 полугодие</a>
        <a href="{{ route('report', ['year' => '2020', 'month' => 'quarter3']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == 'quarter3' ? 'active' : '' }}">3 квартал</a>
        <a href="{{ route('report', ['year' => '2020', 'month' => 'quarter4']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == 'quarter4' ? 'active' : '' }}">4 квартал</a>
        <a href="{{ route('report', ['year' => '2020', 'month' => 'half2']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == 'half2' ? 'active' : '' }}">2 полугодие</a>
        <a href="{{ route('report', ['year' => '2020', 'month' => 'year']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-2] == '2020' ? 'active' : '' }}">2020 год</a>
    </div>
    <div class="btn-group wd-100p">
        <a href="{{ route('report', ['year' => '2020', 'month' => '01']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '01' ? 'active' : '' }}">Январь</a>
        <a href="{{ route('report', ['year' => '2020', 'month' => '02']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '02' ? 'active' : '' }}">Февраль</a>
        <a href="{{ route('report', ['year' => '2020', 'month' => '03']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '03' ? 'active' : '' }}">Март</a>
        <a href="{{ route('report', ['year' => '2020', 'month' => '04']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '04' ? 'active' : '' }}">Апрель</a>
        <a href="{{ route('report', ['year' => '2020', 'month' => '05']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '05' ? 'active' : '' }}">Май</a>
        <a href="{{ route('report', ['year' => '2020', 'month' => '06']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '06' ? 'active' : '' }}">Июнь</a>
        <a href="{{ route('report', ['year' => '2020', 'month' => '07']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '07' ? 'active' : '' }}">Июль</a>
        <a href="{{ route('report', ['year' => '2020', 'month' => '08']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '08' ? 'active' : '' }}">Август</a>
        <a href="{{ route('report', ['year' => '2020', 'month' => '09']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '09' ? 'active' : '' }}">Сентябрь</a>
        <a href="{{ route('report', ['year' => '2020', 'month' => '10']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '10' ? 'active' : '' }}">Октябрь</a>
        <a href="{{ route('report', ['year' => '2020', 'month' => '11']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '11' ? 'active' : '' }}">Ноябрь</a>
        <a href="{{ route('report', ['year' => '2020', 'month' => '12']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '12' ? 'active' : '' }}">Декабрь</a>
    </div>
    <div class="btn-group wd-100p">
        <a href="{{ route('report', ['year' => '2021', 'month' => 'quarter1']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == 'quarter1' ? 'active' : '' }}">1 квартал</a>
        <a href="{{ route('report', ['year' => '2021', 'month' => 'quarter2']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == 'quarter2' ? 'active' : '' }}">2 квартал</a>
        <a href="{{ route('report', ['year' => '2021', 'month' => 'half1']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == 'half1' ? 'active' : '' }}">1 полугодие</a>
        <a href="{{ route('report', ['year' => '2021', 'month' => 'quarter3']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == 'quarter3' ? 'active' : '' }}">3 квартал</a>
        <a href="{{ route('report', ['year' => '2021', 'month' => 'quarter4']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == 'quarter4' ? 'active' : '' }}">4 квартал</a>
        <a href="{{ route('report', ['year' => '2021', 'month' => 'half2']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == 'half2' ? 'active' : '' }}">2 полугодие</a>
        <a href="{{ route('report', ['year' => '2021', 'month' => 'year']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-2] == '2021' ? 'active' : '' }}">2021 год</a>
    </div>
    <div class="btn-group wd-100p">
        <a href="{{ route('report', ['year' => '2021', 'month' => '01']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '01' ? 'active' : '' }}">Январь</a>
        <a href="{{ route('report', ['year' => '2021', 'month' => '02']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '02' ? 'active' : '' }}">Февраль</a>
        <a href="{{ route('report', ['year' => '2021', 'month' => '03']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '03' ? 'active' : '' }}">Март</a>
        <a href="{{ route('report', ['year' => '2021', 'month' => '04']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '04' ? 'active' : '' }}">Апрель</a>
        <a href="{{ route('report', ['year' => '2021', 'month' => '05']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '05' ? 'active' : '' }}">Май</a>
        <a href="{{ route('report', ['year' => '2021', 'month' => '06']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '06' ? 'active' : '' }}">Июнь</a>
        <a href="{{ route('report', ['year' => '2021', 'month' => '07']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '07' ? 'active' : '' }}">Июль</a>
        <a href="{{ route('report', ['year' => '2021', 'month' => '08']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '08' ? 'active' : '' }}">Август</a>
        <a href="{{ route('report', ['year' => '2021', 'month' => '09']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '09' ? 'active' : '' }}">Сентябрь</a>
        <a href="{{ route('report', ['year' => '2021', 'month' => '10']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '10' ? 'active' : '' }}">Октябрь</a>
        <a href="{{ route('report', ['year' => '2021', 'month' => '11']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '11' ? 'active' : '' }}">Ноябрь</a>
        <a href="{{ route('report', ['year' => '2021', 'month' => '12']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '12' ? 'active' : '' }}">Декабрь</a>
    </div>
    <div class="btn-group wd-100p">
        <a href="{{ route('report', ['year' => '2022', 'month' => 'quarter1']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == 'quarter1' ? 'active' : '' }}">1 квартал</a>
        <a href="{{ route('report', ['year' => '2022', 'month' => 'quarter2']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == 'quarter2' ? 'active' : '' }}">2 квартал</a>
        <a href="{{ route('report', ['year' => '2022', 'month' => 'half1']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == 'half1' ? 'active' : '' }}">1 полугодие</a>
        <a href="{{ route('report', ['year' => '2022', 'month' => 'quarter3']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == 'quarter3' ? 'active' : '' }}">3 квартал</a>
        <a href="{{ route('report', ['year' => '2022', 'month' => 'quarter4']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == 'quarter4' ? 'active' : '' }}">4 квартал</a>
        <a href="{{ route('report', ['year' => '2022', 'month' => 'half2']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == 'half2' ? 'active' : '' }}">2 полугодие</a>
        <a href="{{ route('report', ['year' => '2022', 'month' => 'year']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-2] == '2022' ? 'active' : '' }}">2022 год</a>
    </div>
    <div class="btn-group wd-100p">
        <a href="{{ route('report', ['year' => '2022', 'month' => '01']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '01' ? 'active' : '' }}">Январь</a>
        <a href="{{ route('report', ['year' => '2022', 'month' => '02']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '02' ? 'active' : '' }}">Февраль</a>
        <a href="{{ route('report', ['year' => '2022', 'month' => '03']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '03' ? 'active' : '' }}">Март</a>
        <a href="{{ route('report', ['year' => '2022', 'month' => '04']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '04' ? 'active' : '' }}">Апрель</a>
        <a href="{{ route('report', ['year' => '2022', 'month' => '05']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '05' ? 'active' : '' }}">Май</a>
        <a href="{{ route('report', ['year' => '2022', 'month' => '06']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '06' ? 'active' : '' }}">Июнь</a>
        <a href="{{ route('report', ['year' => '2022', 'month' => '07']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '07' ? 'active' : '' }}">Июль</a>
        <a href="{{ route('report', ['year' => '2022', 'month' => '08']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '08' ? 'active' : '' }}">Август</a>
        <a href="{{ route('report', ['year' => '2022', 'month' => '09']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '09' ? 'active' : '' }}">Сентябрь</a>
        <a href="{{ route('report', ['year' => '2022', 'month' => '10']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '10' ? 'active' : '' }}">Октябрь</a>
        <a href="{{ route('report', ['year' => '2022', 'month' => '11']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '11' ? 'active' : '' }}">Ноябрь</a>
        <a href="{{ route('report', ['year' => '2022', 'month' => '12']) }}"
           class="btn btn-secondary wd-100p {{ $page[count($page)-1] == '12' ? 'active' : '' }}">Декабрь</a>
    </div>
    {{--    @if($_SERVER['HTTP_X_REAL_IP'] === '172.16.1.25')--}}
    {{--                {{ dd($countQualityReturn['02-AN']) }}--}}
    {{--    @endif--}}


    <table class="table table-bordered table-hover">
        <thead>
        <th>Регион</th>
        <th>Время предоставления</th>
        <th>Протокол мониторинга сетей</th>
        <th>Количество аутрич на мониторинге сетей</th>
        <th>Анкеты по определению качества услуг</th>
        <th>Протокол вебинара</th>
        <th>Количество вебинаров</th>
        <th>Количество аутрич-сотрудников на вебинаре</th>
        <th>Количество ассистентов на вебинаре</th>
        <th>Количество волонтеров на вебинаре</th>
        <th>Количество участников СПИД Центра на вебинаре</th>
        <th>Протокол семинара</th>
        <th>Количество зарегистрированных семинаров</th>
        <th>Количество аутрич-сотрудников на семинаре</th>
        <th>Количество ассистентов на семинаре</th>
        <th>Количество волонтеров на семинаре</th>
        <th>Количество участников СПИД Центра на семинаре</th>
        <th>Количество анкет пре/пост</th>
        <th>Отчеты по мониторингу мест продажи</th>
        <th>Протокол встречи с сотрудниками ОЦС</th>
        <th>Ежемесячный программный отчет</th>
        </thead>

        <tbody>
        @foreach($regions as $region)
            <tr class="text-center"
                data-region="{{ $region->id }}"
                data-date="{{ $page[count($page)-2] . '-' . $page[count($page)-1] . '-01' }}">
                <td>
                    @if(auth()->user()->role <= 2 || auth()->user()->id == 4)
                        @if(!is_null($blockes[$region->encoding][1]) && !is_null($blockes[$region->encoding][2]) )
                            <a href="{{ route('reportBlock', ['region' => $region->id, 'year' => $page[count($page)-2], 'month' => $page[count($page)-1]]) }}"
                               class="badge badge-warning">зафиксировано частично</a><br>
                        @elseif(!is_null($blockes[$region->encoding][1]))
                            <a href="{{ route('reportBlock', ['region' => $region->id, 'year' => $page[count($page)-2], 'month' => $page[count($page)-1]]) }}"
                               class="badge badge-info">зафиксировать</a><br>
                        @elseif(!is_null($blockes[$region->encoding][2]))
                            <a href="JavaScript:void(0)" class="badge badge-success">зафиксировано</a><br>
                        @else
                            <a href="JavaScript:void(0)" class="badge badge-danger">нет данных</a><br>
                        @endif
                    @endif
                    {{ $region->encoding }}
                </td>
                <td data-type="complete" class="editor datetime" nowrap>
                    {{ $saveDataReturn[$region->id]['complete'] ?? 'Не определено' }}
                </td>
                <td data-type="miovisitions"
                    class="success editor {{ $saveDataReturn[$region->id]['miovisitions'] ?? 'empty' }}">
                    {{ $saveDataReturn[$region->id]['miovisitions'] ?? 'N/A' }}
                </td>
                <td class="success {{ $countQualityOutreachesReturn[$region->encoding] ?? 'empty' }}">
                    {{ $countQualityOutreachesReturn[$region->encoding] ?? 'N/A' }}
                </td>
                <td class="success {{ $countQualityReturn[$region->encoding]['all'] ?? 'empty' }} {{ $countQualityReturn[$region->encoding]['scan'] === $countQualityReturn[$region->encoding]['all'] ? 'success' : 'nope' }}">
                    {{ checkNull($countQualityReturn[$region->encoding]['all']) === checkNull($countQualityReturn[$region->encoding]['scan']) ? ($countQualityReturn[$region->encoding]['scan'] ?? 'N/A') : $countQualityReturn[$region->encoding]['all'] . ' / ' . $countQualityReturn[$region->encoding]['noScan'] }}
                </td>
                <td data-type="webinar" class="success editor {{ $saveDataReturn[$region->id]['webinar'] ?? 'empty' }}">
                    {{ $saveDataReturn[$region->id]['webinar'] ?? 'N/A' }}
                </td>
                <td class="success {{ $countWebinar[$region->id] ?? 'empty' }}">
                    {{ $countWebinar[$region->id] ?? 'N/A' }}
                </td>
                <td class="success {{ $countAnswersWebinarOutreachReturn[$region->encoding] ?? 'empty' }}">
                    {{ $countAnswersWebinarOutreachReturn[$region->encoding] ?? 'N/A' }}
                </td>
                <td class="success">
                    {{ $countAnswersWebinarAssistantReturn[$region->encoding] ?? 'N/A' }}
                </td>
                <td class="success">
                    {{ $countAnswersWebinarVolunteerReturn[$region->encoding] ?? 'N/A' }}
                </td>
                <td class="success">
                    {{ $countAnswersWebinarHIVReturn[$region->encoding] ?? 'N/A' }}
                </td>
                <td data-type="seminar" class="success editor {{ $saveDataReturn[$region->id]['seminar'] ?? 'empty' }}">
                    {{ $saveDataReturn[$region->id]['seminar'] ?? 'N/A' }}
                </td>
                <td class="success {{ $countSeminar[$region->id] ?? 'empty' }}">
                    {{ $countSeminar[$region->id] ?? 'N/A' }}
                </td>
                <td class="success {{ $countAnswersSeminarOutreachReturn[$region->encoding] ?? 'empty' }}">
                    {{ $countAnswersSeminarOutreachReturn[$region->encoding] ?? 'N/A' }}
                </td>
                <td class="success">
                    {{ $countAnswersSeminarAssistantReturn[$region->encoding] ?? 'N/A' }}
                </td>
                <td class="success">
                    {{ $countAnswersSeminarVolunteerReturn[$region->encoding] ?? 'N/A' }}
                </td>
                <td class="success">
                    {{ $countAnswersSeminarHIVReturn[$region->encoding] ?? 'N/A' }}
                </td>
                <td {!! $countAnswersReturn[$region->encoding]['all'] ?? 'class="empty"' !!} {!! $countAnswersReturn[$region->encoding]['scan'] === $countAnswersReturn[$region->encoding]['all'] ? 'class="success"' : 'class="nope"' !!}>
                    {!! checkNull($countAnswersReturn[$region->encoding]['all']) === checkNull($countAnswersReturn[$region->encoding]['scan']) ? '<a href="'.(route('downloadZip', ['document' => 'answers', 'region' => $region->id, 'year' => $page[6], 'month' => $page[7]])).'">'.($countAnswersReturn[$region->encoding]['scan'] ?? 'N/A').'</a>' : $countAnswersReturn[$region->encoding]['all'] . ' / ' . $countAnswersReturn[$region->encoding]['noScan'] !!}
                </td>
                <td class="success" {{ $countVisitionsReturn[$region->encoding]['all'] ?? 'class="empty"' }} {{ $countVisitionsReturn[$region->encoding]['scan'] === $countVisitionsReturn[$region->encoding]['all'] ? 'class="success"' : 'class="nope"' }}>
                    {!! checkNull($countVisitionsReturn[$region->encoding]['all']) === checkNull($countVisitionsReturn[$region->encoding]['scan']) ? '<a href="'.(route('downloadZip', ['document' => 'mio', 'region' => $region->id, 'year' => $page[6], 'month' => $page[7]])).'">'.($countVisitionsReturn[$region->encoding]['scan'] ?? 'N/A').'</a>' : $countVisitionsReturn[$region->encoding]['all'] . ' / ' . $countVisitionsReturn[$region->encoding]['noScan'] !!}
                </td>
                <td data-type="meetings"
                    class="success editor {{ $saveDataReturn[$region->id]['meetings'] ?? 'empty' }}">
                    {{ $saveDataReturn[$region->id]['meetings'] ?? 'N/A' }}
                </td>
                <td data-type="report_month"
                    class="success editor {{ $saveDataReturn[$region->id]['report_month'] ?? 'empty' }}">
                    {{ $saveDataReturn[$region->id]['report_month'] ?? 'N/A' }}
                </td>
            </tr>
        @endforeach
        <tr class="bg-info tx-center vertical">
            <td colspan="2" rowspan="2">ИТОГО:</td>
            <td>
                {{ $saveDataReturn['all']['miovisitions'] ?? 'N/A' }}
            </td>
            <td>
                {{ $countQualityOutreachesReturn['all'] ?? 'N/A' }}
            </td>
            <td>
                {{ $countQualityReturn['all'] }}
            </td>
            <td>
                {{ $saveDataReturn['all']['webinar'] ?? 'N/A' }}
            </td>
            <td>
                {{ $countWebinar['all'] ?? 'N/A' }}
            </td>
            <td>
                {{ $countAnswersWebinarOutreachReturn['all'] ?? 'N/A' }}
            </td>
            <td>
                {{ $countAnswersWebinarAssistantReturn['all'] ?? 'N/A' }}
            </td>
            <td>
                {{ $countAnswersWebinarVolunteerReturn['all'] ?? 'N/A' }}
            </td>
            <td>
                {{ $countAnswersWebinarHIVReturn['all'] ?? 'N/A' }}
            </td>
            <td>
                {{ $saveDataReturn['all']['seminar'] ?? 'N/A' }}
            </td>
            <td>
                {{ $countSeminar['all'] ?? 'N/A' }}
            </td>
            <td>
                {{ $countAnswersSeminarOutreachReturn['all'] ?? 'N/A' }}
            </td>
            <td>
                {{ $countAnswersSeminarAssistantReturn['all'] ?? 'N/A' }}
            </td>
            <td>
                {{ $countAnswersSeminarVolunteerReturn['all'] ?? 'N/A' }}
            </td>
            <td>
                {{ $countAnswersSeminarHIVReturn['all'] ?? 'N/A' }}
            </td>
            <td>
                {{ $countAnswersReturn['all'] }}
            </td>
            <td>
                {{ $countVisitionsReturn['all'] }}
            </td>
            <td>
                {{ $saveDataReturn['all']['meetings'] ?? 'N/A' }}
            </td>
            <td>
                {{ $saveDataReturn['all']['report_month'] ?? 'N/A' }}
            </td>
        </tr>
        <tr class="bg-info tx-center">
            <td>
                -
            </td>
            <td>
                {{ $uniqueOutreach }}
            </td>
            <td>
                -
            </td>
            <td>
                -
            </td>
            <td>
                -
            </td>
            <td>
                {{ $uniqueOutreachWebinar }}
            </td>
            <td>
                {{ $uniqueAssistentWebinar }}
            </td>
            <td>
                {{ $uniqueVolunteerWebinar }}
            </td>
            <td>
                {{ $countAnswersWebinarHIVReturn['all'] ?? 'N/A' }}
            </td>
            <td>
                -
            </td>
            <td>
                -
            </td>
            <td>
                {{ $uniqueOutreachSeminar }}
            </td>
            <td>
                {{ $uniqueAssistentSeminar }}
            </td>
            <td>
                {{ $uniqueVolunteerSeminar }}
            </td>
            <td>
                {{ $countAnswersSeminarHIVReturn['all'] ?? 'N/A' }}
            </td>
            <td>
                -
            </td>
            <td>
                {{ $uniqueVisitions }}
            </td>
            <td>
                -
            </td>
            <td>
                -
            </td>
        </tr>
        </tbody>

        <tfoot>
        <th>Регион</th>
        <th>Время предоставления</th>
        <th>Протокол мониторинга сетей</th>
        <th>Количество аутрич на мониторинге сетей</th>
        <th>Анкеты по определению качества услуг</th>
        <th>Протокол вебинара</th>
        <th>Количество вебинаров</th>
        <th>Количество аутрич-сотрудников на вебинаре</th>
        <th>Количество ассистентов на вебинаре</th>
        <th>Количество волонтеров на вебинаре</th>
        <th>Количество участников СПИД Центра на вебинаре</th>
        <th>Протокол семинара</th>
        <th>Количество зарегистрированных семинаров</th>
        <th>Количество аутрич-сотрудников на семинаре</th>
        <th>Количество ассистентов на семинаре</th>
        <th>Количество волонтеров на семинаре</th>
        <th>Количество участников СПИД Центра на семинаре</th>
        <th>Количество анкет пре/пост</th>
        <th>Отчеты по мониторингу мест продажи</th>
        <th>Протокол встречи с сотрудниками ОЦС</th>
        <th>Ежемесячный программный отчет</th>
        </tfoot>
    </table>
@endsection
