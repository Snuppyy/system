@extends('layouts.app')

@section('styles')
    <link href="{{ asset('lib/jquery.switcher/switcher.min.css') }}" rel="stylesheet">
    <style>
        .switcher.default {
            float: right;
        }
    </style>
@endsection

@section('scripts')
    <script src="{{ asset('lib/jquery.switcher/switcher.js') }}"></script>
@endsection

@section('scriptsFooter')
    <script>
        $(function () {
            $('input.switcher').switcher({
                style: "default",
                selected: false,
                language: "ru",
                disabled: false
            });
        });
    </script>
@endsection

@section('header')
    <div class="sh-breadcrumb">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{ route('index') }}">INTILISH v3.1</a>
            <span class="breadcrumb-item">Психология</span>
            <span class="breadcrumb-item">Опросники</span>
            <span class="breadcrumb-item active">Результаты опросника Басса-Дарки</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <div class="sh-pagetitle">

        <div class="input-group">
            <!-- search block -->
        </div><!-- input-group -->

        <div class="sh-pagetitle-left">
            <div class="sh-pagetitle-icon"><i class="icon ion-help"></i></div>
            <div class="sh-pagetitle-title">
                <h2>Результаты опросника Басса-Дарки</h2>
            </div><!-- sh-pagetitle-left-title -->
        </div><!-- sh-pagetitle-left -->

    </div><!-- sh-pagetitle -->
@endsection

@section('content')
    <table class="table table-bordered table-hover table-info">
        <thead>
        <th>Клиент</th>
        <th colspan="2">Физическая агрессия</th>
        <th colspan="2">Косвенная агрессия</th>
        <th colspan="2">Раздражение</th>
        <th colspan="2">Негативизм</th>
        <th colspan="2">Обида</th>
        <th colspan="2">Подозрительность</th>
        <th colspan="2">Вербальная агрессия</th>
        <th colspan="2">Чувство вины</th>
        <th colspan="2">Враждебность</th>
        <th colspan="2">Агрессивность</th>
        </thead>
        <tbody>
        @forelse($data as $id => $datum)

            <tr>
                <td>{{ $datum['name'] }}</td>
                <td>{{ $datum[1]['results']['Физическая агрессия'] ?? 'N/A' }}</td>
                <td>{{ $data[$id][2]['results']['Физическая агрессия'] ?? 'N/A' }}</td>
                <td>{{ $datum[1]['results']['Косвенная агрессия'] ?? 'N/A' }}</td>
                <td>{{ $data[$id][2]['results']['Косвенная агрессия'] ?? 'N/A' }}</td>
                <td>{{ $datum[1]['results']['Раздражение'] ?? 'N/A' }}</td>
                <td>{{ $data[$id][2]['results']['Раздражение'] ?? 'N/A' }}</td>
                <td>{{ $datum[1]['results']['Негативизм'] ?? 'N/A' }}</td>
                <td>{{ $data[$id][2]['results']['Негативизм'] ?? 'N/A' }}</td>
                <td>{{ $datum[1]['results']['Обида'] ?? 'N/A' }}</td>
                <td>{{ $data[$id][2]['results']['Обида'] ?? 'N/A' }}</td>
                <td>{{ $datum[1]['results']['Подозрительность'] ?? 'N/A' }}</td>
                <td>{{ $data[$id][2]['results']['Подозрительность'] ?? 'N/A' }}</td>
                <td>{{ $datum[1]['results']['Вербальная агрессия'] ?? 'N/A' }}</td>
                <td>{{ $data[$id][2]['results']['Вербальная агрессия'] ?? 'N/A' }}</td>
                <td>{{ $datum[1]['results']['Чувство вины'] ?? 'N/A' }}</td>
                <td>{{ $data[$id][2]['results']['Чувство вины'] ?? 'N/A' }}</td>
                <td>{{ $datum[1]['results']['Обида'] + $datum[1]['results']['Подозрительность'] ?? 'N/A' }}</td>
                <td>{{ $data[$id][2]['results']['Обида'] + $datum[2]['results']['Подозрительность'] ?? 'N/A' }}</td>
                <td>{{ $datum[1]['results']['Физическая агрессия'] + $datum[1]['results']['Раздражение'] + $datum[1]['results']['Вербальная агрессия'] ?? 'N/A' }}</td>
                <td>{{ $data[$id][2]['results']['Физическая агрессия'] + $datum[2]['results']['Раздражение'] + $datum[2]['results']['Вербальная агрессия'] ?? 'N/A' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="16" align="center">НЕТ ДАННЫХ</td>
            </tr>
        @endforelse
        </tbody>
        <tfoot>
        <th>Клиент</th>
        <th colspan="2">Физическая агрессия</th>
        <th colspan="2">Косвенная агрессия</th>
        <th colspan="2">Раздражение</th>
        <th colspan="2">Негативизм</th>
        <th colspan="2">Обида</th>
        <th colspan="2">Подозрительность</th>
        <th colspan="2">Вербальная агрессия</th>
        <th colspan="2">Чувство вины</th>
        <th colspan="2">Враждебность</th>
        <th colspan="2">Агрессивность</th>
        </tfoot>
    </table>
@endsection
