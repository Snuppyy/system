@extends('layouts.app')

@section('content')
    <table class="table table-primary table-bordered table-hover">
        <thead class="tx-center-force">
        <tr>
            <th style="vertical-align: middle;" rowspan="2" >
                Индикатор
            </th>
            <th colspan="2">
                Общее
            </th>
            <th colspan="2">
                Ташкент
            </th>
            <th colspan="2">
                Ташкентская область
            </th>
            <th colspan="2">
                Нац. Офис
            </th>
            <th colspan="2">
                Бухара
            </th>
            <th colspan="2">
                Бухарский Офис
            </th>
        </tr>
        <tr>
            <th>
                дата мероприятий
            </th>
            <th>
                уникальные клиенты
            </th>
            <th>
                дата мероприятий
            </th>
            <th>
                уникальные клиенты
            </th>
            <th>
                дата мероприятий
            </th>
            <th>
                уникальные клиенты
            </th>
            <th>
                дата мероприятий
            </th>
            <th>
                уникальные клиенты
            </th>
            <th>
                дата мероприятий
            </th>
            <th>
                уникальные клиенты
            </th>
            <th>
                дата мероприятий
            </th>
            <th>
                уникальные клиенты
            </th>
        </tr>
        </thead>

        <tbody>
        @foreach($data as $indicator)
            <tr>
                <td>{{ $indicator['name'] }}</td>
                <td nowrap>
                    @foreach($indicator['all']['actions'] as $key => $val)
                        {{ $val }}<br>
                    @endforeach
                </td>
                <td nowrap>
                    @foreach($indicator['alll']['clients'] as $key => $val)
                        {{ $key }}<br>
                    @endforeach
                </td>
                <td nowrap>
                    @foreach($indicator[2]['actions'] as $key => $val)
                        {{ $key }}<br>
                    @endforeach
                </td>
                <td nowrap>
                    @foreach($indicator[2]['clients'] as $key => $val)
                        {{ $key }}<br>
                    @endforeach
                </td>
                <td nowrap>
                    @foreach($indicator[1]['actions'] as $key => $val)
                        {{ $key }}<br>
                    @endforeach
                </td>
                <td nowrap>
                    @foreach($indicator[1]['clients'] as $key => $val)
                        {{ $key }}<br>
                    @endforeach
                </td>
                <td nowrap>
                    @foreach($indicator[4]['actions'] as $key => $val)
                        {{ $key }}<br>
                    @endforeach
                </td>
                <td nowrap>
                    @foreach($indicator[4]['clients'] as $key => $val)
                        {{ $key }}<br>
                    @endforeach
                </td>
                <td nowrap>
                    @foreach($indicator[3]['actions'] as $key => $val)
                        {{ $key }}<br>
                    @endforeach
                </td>
                <td nowrap>
                    @foreach($indicator[3]['clients'] as $key => $val)
                        {{ $key }}<br>
                    @endforeach
                </td>
                <td nowrap>
                    @foreach($indicator[5]['actions'] as $key => $val)
                        {{ $key }}<br>
                    @endforeach
                </td>
                <td nowrap>
                    @foreach($indicator[5]['clients'] as $key => $val)
                        {{ $key }}<br>
                    @endforeach
                </td>
            </tr>
        @endforeach
        </tbody>

        <tfoot class="tx-center-force">
        <tr>
            <th style="vertical-align: middle;" rowspan="2" >
                Индикатор
            </th>
            <th colspan="2">
                Общее
            </th>
            <th colspan="2">
                Ташкент
            </th>
            <th colspan="2">
                Ташкентская область
            </th>
            <th colspan="2">
                Нац. Офис
            </th>
            <th colspan="2">
                Бухара
            </th>
            <th colspan="2">
                Бухарский Офис
            </th>
        </tr>
        <tr>
            <th>
                дата мероприятий
            </th>
            <th>
                уникальные клиенты
            </th>
            <th>
                дата мероприятий
            </th>
            <th>
                уникальные клиенты
            </th>
            <th>
                дата мероприятий
            </th>
            <th>
                уникальные клиенты
            </th>
            <th>
                дата мероприятий
            </th>
            <th>
                уникальные клиенты
            </th>
            <th>
                дата мероприятий
            </th>
            <th>
                уникальные клиенты
            </th>
            <th>
                дата мероприятий
            </th>
            <th>
                уникальные клиенты
            </th>
        </tr>
        </tfoot>
    </table>
@endsection