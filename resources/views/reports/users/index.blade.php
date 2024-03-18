<table class="table table-bordered">
    <thead>
    <tr>
        <th><strong>NOMBRES</strong></th>
        <th><strong>APELLIDOS</strong></th>
        <th><strong>NRO DOC</strong></th>
        <th><strong>EMAIL</strong></th>
        <th><strong>PASSWORD</strong></th>
        <th><strong>ULTIMO LOGIN</strong></th>
        <th><strong>ESTADO</strong></th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
        <tr>
            <td>{{ $user['name'] }}</td>
            <td>{{ $user['last_name'] }}</td>
            <td>{{ $user['nro_document'] }}</td>
            <td>{{ $user['email'] }}</td>
            <td>{{ $user['raw_password'] }}</td>
            <td>{{ $user['last_login'] }}</td>
            <td>@include('components.status',[ 'is_active' => $user->is_active ])</td>
        </tr>
    @endforeach
    </tbody>
</table>
