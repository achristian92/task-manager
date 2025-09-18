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
        <th><strong>USUARIOS ASIGNADOS</strong></th>
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
            <td>
                {{
                  $customer->users->pluck('name')
                    ->merge(\App\Repositories\Users\User::where('can_check_all_customers', true)->where('is_active',true)->where('company_id',$customer->company_id)->pluck('name'))
                    ->unique()
                    ->sort()
                    ->join(', ')
                }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
