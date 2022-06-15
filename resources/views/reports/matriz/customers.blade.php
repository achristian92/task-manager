<table class="table table-bordered">
    <thead>
    <tr>
        <th><strong>CLIENTES</strong></th>
    </tr>
    </thead>
    <tbody>
    @foreach($customers as $customer)
        <tr>
            <td>{{ $customer }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
