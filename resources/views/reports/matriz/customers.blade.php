<table class="table table-bordered">
    <thead>
    <tr>
        <th><strong>ID</strong></th>
        <th><strong>CLIENTES</strong></th>
    </tr>
    </thead>
    <tbody>
    @foreach($customers as $customer)
        <tr>
            <td>{{ $customer->id }}</td>
            <td>{{ $customer->name }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
