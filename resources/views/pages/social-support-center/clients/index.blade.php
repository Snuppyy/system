@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('lib/datatables/jquery.dataTables.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/select2/css/select2.min.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('lib/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('lib/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>
@endsection

@section('scriptsFooter')
    <script>
        $('table').DataTable({
            responsive: true,
            language: {
                "processing": "Подождите...",
                "searchPlaceholder": "Поиск:",
                "search": "",
                "lengthMenu": "Показать _MENU_ записей",
                "info": "Записи с _START_ до _END_ из _TOTAL_ записей",
                "infoEmpty": "Записи с 0 до 0 из 0 записей",
                "infoFiltered": "(отфильтровано из _MAX_ записей)",
                "infoPostFix": "",
                "loadingRecords": "Загрузка записей...",
                "zeroRecords": "Записи отсутствуют.",
                "emptyTable": "В таблице отсутствуют данные",
                "paginate": {
                    "first": "Первая",
                    "previous": "Предыдущая",
                    "next": "Следующая",
                    "last": "Последняя"
                },
                "aria": {
                    "sortAscending": ": активировать для сортировки столбца по возрастанию",
                    "sortDescending": ": активировать для сортировки столбца по убыванию"
                }
            }
        });

        $('.dataTables_length select').select2({
            minimumResultsForSearch: Infinity,
            width: '100'
        });
    </script>
@endsection

@section('header')
    <div class="sh-breadcrumb">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{ route('index') }}">INTILISH v3.1</a>
            <span class="breadcrumb-item">ЦСП</span>
            <span class="breadcrumb-item active">Клиенты</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <div class="sh-pagetitle">

        <div class="input-group">
            <!-- search block -->
        </div><!-- input-group -->

        <div class="sh-pagetitle-left">
            <div class="sh-pagetitle-icon"><i class="icon ion-ios-bookmarks-outline"></i></div>
            <div class="sh-pagetitle-title">
                <h2>Клиенты</h2>
            </div><!-- sh-pagetitle-left-title -->
        </div><!-- sh-pagetitle-left -->

    </div><!-- sh-pagetitle -->
@endsection

@section('content')
    <div class="col-12">

        <table class="table table-responsive table-hover table-bordered">
            <thead>
            <tr class="text-center align-middle">
                <td>
                    #
                </td>
                <td>
                    Дата регистрации
                </td>
                <td>
                    Ф.И.О
                </td>
                <td>
                    Пол
                </td>
                <td>
                    Телефон
                </td>
                <td>
                    Код клиента
                </td>
                <td>
                    Перенаправление
                </td>
                <td>
                    Статус
                </td>
                <td>
                    Управление
                </td>
            </tr>
            </thead>

            <tbody>
            @forelse($clients as $client)
                <tr>
                    <td>
                        {{ $client->id }}
                    </td>
                    <td>
                        {{ $client->datetime }}
                    </td>
                    <td>
                        {{ $client->k_name }}
                    </td>
                    <td>
                        {{ $client->sex }}
                    </td>
                    <td>
                        {{ $client->phone_main }}
                    </td>
                    <td>
                        {{ $client->encoding }}
                    </td>
                    <td>
                        {{ $client->redirect }}
                    </td>
                    <td>
                        {{ $client->criterion }}
                    </td>
                    <td class="text-center">
                        <a href="javascript:void(0)"><span class="icon ion-edit tx-warning"></span></a>
                        <a href="javascript:void(0)"><span class="icon ion-android-delete tx-danger"></span></a>
                    </td>
                </tr>
            @empty

            @endforelse
            </tbody>

            <tfoot>
            <tr class="text-center align-middle">
                <td>
                    #
                </td>
                <td>
                    Дата регистрации
                </td>
                <td>
                    Ф.И.О
                </td>
                <td>
                    Пол
                </td>
                <td>
                    Телефон
                </td>
                <td>
                    Код клиента
                </td>
                <td>
                    Перенаправление
                </td>
                <td>
                    Статус
                </td>
                <td>
                    Управление
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
@endsection