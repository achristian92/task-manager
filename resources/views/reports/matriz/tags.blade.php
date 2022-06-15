<table class="table table-bordered">
    <thead>
    <tr>
        <th><strong>ETIQUETAS</strong></th>
    </tr>
    </thead>
    <tbody>
    @foreach($tags as $tag)
        <tr>
            <td>{{ $tag }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
