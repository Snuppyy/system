<table class="table table-hover table-bordered table-primary mg-b-0">
    <tbody>
    @foreach($data as $datum)
        <tr>
            <td>{{ $datum->date }}</td>
            <td>{{ $datum->outreach }}</td>
            <td>{{ $datum->volunteer }}</td>
            <td>{{ $datum->region }}</td>
            <td>{{ $datum->questionnaire_encoding }}</td>
            <td>{{ $datum->type1 !== 'NULL'? round($datum->type1 * 100 / $datum->count, 2).'%' : $datum->type1 }}</td>
            <td>{{ $datum->type2 !== 'NULL'? round($datum->type2 * 100 / $datum->count, 2).'%' : $datum->type2 }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
