@extends('layouts.app')


Проверка доступа по правообладанию и взятию.
Сука как же так можно обосрать два сука дня из за друзей которые прилетают к ней. Это пиздец.
Не будет секса, а с 1 марта что???)) Блять я получаю 3кк, из которых 50% за квартиру. Что кушать? Как прожить за 1.5кк в месяц?????
Чот я в жопе. Нужно думать, что то делать и решать.

@section('styles')
    <link href="{{ asset('lib/datatables/jquery.dataTables.css') }}" rel="stylesheet">
@endsection

@section('scripts')
    <script src="{{ asset('lib/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('lib/datatables-responsive/dataTables.responsive.js') }}"></script>
@endsection

@section('scriptsFooter')
    <script>
        $(function() {
            'use strict';

            $('table').DataTable({
                language: {
                    searchPlaceholder: 'Search...',
                    sSearch: '',
                    lengthMenu: '_MENU_ items/page',
                }
            });

            // Select2
            $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });

        });

    </script>
@endsection

@section('content')
    <table class="table table-primary table-bordered table-hover display" style="width:100%">
        <thead class="tx-center-force">
        <th>
            #
        </th>
        <th>
            Сотрудник
        </th>
        <th>
            Дата
        </th>
        <th>
            Клиенты
        </th>
        </thead>

        <tbody>
        @foreach($data as $id => $datum)
            <tr>
                <td>{{ $id }}</td>
                <td>{{ $datum['user'] }}</td>
                <td>{{ $datum['date']->format('Y-m-d') }}</td>
                <td nowrap>
                @foreach($datum['clients'] as $client)
                    {{ $client }}<br>
                @endforeach
                </td>
            </tr>
        @endforeach
        </tbody>

        <tfoot class="tx-center-force">
        <th>
            #
        </th>
        <th>
            Сотрудник
        </th>
        <th>
            Дата
        </th>
        <th>
            Клиенты
        </th>
        </tfoot>
    </table>
@endsection