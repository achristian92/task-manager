@extends('layouts.admin.app')
@section('content')
    @component('components.list')
        @slot('title','Historial ['. count($history).']')
        @slot('actions')@endslot
        @slot('table')
            <table class="table align-items-center table-flush border-bottom-0" id="dtUserHistory">
                <thead class="thead-light">
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Descripción</th>
                </tr>
                </thead>
                <tbody>
                @foreach($history as $data)
                    <tr>
                        <td>{{ $data->id }}</td>
                        <td>{{ $data->user_full_name }}</td>
                        <td>{{ bsw_date_short($data->created_at,'d/m/y',true) }}</td>
                        <td>{{ $data->type }}</td>
                        <td>{{ $data->description }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endslot
    @endcomponent
@endsection


