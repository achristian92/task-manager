<table class="table table-bordered">
    <thead>
    <tr>
        <th><strong>ID</strong></th>
        <th><strong>ETIQUETAS</strong></th>
    </tr>
    </thead>
    <tbody>
    @foreach($tags as $tag)
        <tr>
            <td>{{ $tag->id }}</td>
            <td>{{ $tag->name }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
