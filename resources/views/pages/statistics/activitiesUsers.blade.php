@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('lib/datetimepicker/daterangepicker.css') }}"/>
    <style>
        .disabled {
            color: currentColor;
            cursor: not-allowed;
            opacity: 0.5;
            text-decoration: none;
        }

        .dataTables_wrapper {
            width: 100%;
        }
    </style>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('lib/moment/moment.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/moment/locale/ru.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/datetimepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('lib/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('lib/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script>
        $(function () {
            table = $('.datatable').DataTable({
                "paging": false,
                "autoWidth": false,
                responsive: true,
                language: {
                    searchPlaceholder: 'Поиск..',
                    sSearch: '',
                }
            });

            $.fn.dataTable.ext.search.push(
                function( settings, data, dataIndex ) {
                    var values = [];
                    $('.filterProject.btn-primary').each(function (i){
                        values[i] = $(this).text();
                    });

                    if (values.every(i => data[2].includes(i))) {
                        return true;
                    }
                    return false;
                }
            );

            $('.filterProject').click(function () {
                $(this).toggleClass('btn-primary');
                $(this).toggleClass('btn-info');
                table.draw();
            });


                    @if(isset($_GET['startDate']) && isset($_GET['startDate']))
            var start = moment("{{ $_GET['startDate'] }}");
            var end = moment("{{ $_GET['endDate'] }}");
            cb(start, end);

            @endif

            function cb(start, end) {
                $('#filterDate span').html(start.format('YYYY MMMM DD') + ' - ' + end.format('YYYY MMMM DD'));
            }

            $('#filterDate').daterangepicker({
                @if(isset($_GET['startDate']) && isset($_GET['startDate']))
                startDate: start,
                endDate: end,
                @endif
                opens: 'center',
                locale: {
                    "format": "YYYY-MM-DD",
                    "separator": " - ",
                    "applyLabel": "Принять",
                    "cancelLabel": "Отменить",
                    "fromLabel": "От",
                    "toLabel": "До",
                    "customRangeLabel": "Период",
                    "daysOfWeek": [
                        "Вс",
                        "Пн",
                        "Вт",
                        "Ср",
                        "Чт",
                        "Пт",
                        "Сб"
                    ],
                    "monthNames": [
                        "Январь",
                        "Февраль",
                        "Март",
                        "Апрель",
                        "Май",
                        "Июнь",
                        "Июль",
                        "Август",
                        "Сентябрь",
                        "Октябрь",
                        "Ноябрь",
                        "Декабрь"
                    ],
                    "firstDay": 1
                },
            }, cb);

            $('#filterDate').on('apply.daterangepicker', function (ev, picker) {
                window.location.href = "{{ route('ActivityStatisticsProject', ['project' => $project]) }}?startDate=" + picker.startDate.format('YYYY-MM-DD') + "&endDate=" + picker.endDate.format('YYYY-MM-DD');
            });
        });
    </script>
@endsection


@section('header')
    <div class="sh-breadcrumb">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{ route('index') }}">INTILISH v3.1</a>
            <a class="breadcrumb-item" href="{{ route('index') }}">Главная</a>
            <span class="breadcrumb-item active">Статистика по деятельности</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <div class="sh-pagetitle">

        <div class="sh-pagetitle-left">
            <div class="sh-pagetitle-icon"><i class="icon ion-stats-bars"></i></div>
            <div class="sh-pagetitle-title">
                <h2>Статистика по деятельности</h2>
            </div><!-- sh-pagetitle-left-title -->

            <div id="filterMenu" class="wd-100p-force float-right">
                <div class="btn-group wd-100p-force">
                    @foreach($projects as $project)
                        <button class="btn btn-info btn-block filterProject mg-t-5-force">{{ $project->encoding }}</button>
                    @endforeach
                </div>
            </div>
        </div><!-- sh-pagetitle-left -->

    </div><!-- sh-pagetitle -->
@endsection

@section('content')
    <h4 class="text-center wd-100p mg-20">Статистика по сотрудникам</h4>
    <table class="col-12 table table-primary table-bordered table-hover datatable" style="width:100%">
        <thead>
        <th>
            #
        </th>
        <th>
            сотрудник
        </th>
        <th>
            должности
        </th>
        </thead>
        <tbody>
        @forelse($users as $user)
            <tr class="{{ $user->status === 0 ? 'bg-delicate' : '' }}" style="cursor: pointer"
                onclick="location.href='{{ route('ActivityUser', ['user' => $user->id]) }}'">
                <td>
                    {{ $user->id }}
                </td>
                <td nowrap>
                    {{ $user->name_ru }}
                </td>
                <td>
                    @foreach($user->position as $position)
                        {{ $positions[$position] }}<br>
                    @endforeach
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center">
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
            сотрудник
        </th>
        <th>
            должности
        </th>
        </tfoot>
    </table>
@endsection
