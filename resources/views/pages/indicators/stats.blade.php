<table class="table table-hover table-bordered" id="indicators">
    <thead class="bg-info">
    <tr>
        <th>#</th>
        <th>Рейтинг</th>
        <th>Индикатор</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $id => $value)
        <tr>
            <td>{{ $id }}</td>
            <td>{{ $value }}</td>
            <td>{{ $indicators->find($id)->name }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
