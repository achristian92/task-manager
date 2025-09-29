<table class="table table-bordered">
    <thead>
    <tr>
        <th><strong>EMPRESA</strong></th>
        <th><strong>RUC</strong></th>
        <th><strong>DIRECCIÓN</strong></th>
        <th><strong>ESTADO</strong></th>
        <th><strong>CONTACTO - NOMBRE</strong></th>
        <th><strong>CONTACTO - CORREO</strong></th>
        <th><strong>CONTACTO - TELÉF.</strong></th>
        <th><strong>USUARIO ENCARGADO</strong></th>
    </tr>
    </thead>
    <tbody>
    @foreach($customers as $customer)
        <tr>
            <td>{{ $customer->name }}</td>
            <td>{{ $customer->ruc }}</td>
            <td>{{ $customer->address }}</td>
            <td>@include('components.status', ['is_active' => $customer->is_active])</td>
            <td>{{ $customer->contact_name }}</td>
            <td>{{ $customer->contact_email }}</td>
            <td>{{ $customer->contact_telephone }}</td>
            <td>{{ $customer->user->fullname ?? 'N/A'}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
