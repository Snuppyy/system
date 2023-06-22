@extends('layouts.app')

@section('header')
    <div class="sh-breadcrumb">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{ route('index') }}">INTILISH v3.1</a>
            <span class="breadcrumb-item active">Индикаторы</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <div class="sh-pagetitle">

        <div class="input-group">
            <!-- search block -->
        </div><!-- input-group -->

        <div id="filterMenu" class="col-8">
            <div class="btn-group btn-block" role="group" aria-label="Basic example">
                <button class="mg-0-force btn btn-block btn-warning mg-b-10-force" id="save">Сохранить</button>
                <a href="{{ route('indicatorsStatistic') }}" class="mg-0-force btn btn-block btn-success mg-b-10-force" id="statistic">Статистика</a>
            </div>

        </div>

        <div class="sh-pagetitle-left col-4">
            <div class="sh-pagetitle-icon"><i class="icon ion-ios-bookmarks-outline"></i></div>
            <div class="sh-pagetitle-title">
                <h2>Индикаторы</h2>
            </div><!-- sh-pagetitle-left-title -->
        </div><!-- sh-pagetitle-left -->

    </div><!-- sh-pagetitle -->
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@7/dist/sweetalert2.min.css">
    <style>
        #indicators {
            background: linear-gradient(to top, #ff6f69, #6fc390);
            font-size: 90%;
        }

        #indicators.table td {
            color: black !important;
            font-weight: bold !important;
            padding: 5px;
        }
    </style>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7/dist/sweetalert2.all.min.js"></script>
@endsection

@section('scriptsFooter')
    <script>
        $(function () {
            'use strict';
            $('tbody').sortable();

            var saveIndicators = [];

            $('#save').click(function () {
                $('#indicators tbody tr').each(function (index) {
                    saveIndicators[index] = $(this).children('.id').text();
                });

                $.ajax({
                    url: '{{ route('indicatorsSave') }}',
                    type: "POST",
                    data: {indicators: saveIndicators},
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        Swal.fire(
                            'Сохранено',
                            'Нажмите "ОК" для закрытия окна',
                            'success'
                        )
                    }
                });
            });


        })
    </script>
@endsection

@section('content')
    <table class="table table-hover table-bordered" id="indicators">
        <thead class="bg-info">
        <tr>
            <th>#</th>
            <th>Индикатор</th>
        </tr>
        </thead>
        <tbody>
        @if($data)
            @foreach($data as $datum)
                <tr>
                    <td class="id">{{ $indicators->find($datum)->id }}</td>
                    <td>{{ $indicators->find($datum)->name }}</td>
                </tr>
            @endforeach
        @else
            @foreach($indicators as $indicator)
                <tr>
                    <td class="id">{{ $indicator->id }}</td>
                    <td>{{ $indicator->name }}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
@endsection
