@extends('layouts.admin.app')
@section('content')
    @component('components.list')
        @slot('title','Lista de Usuarios ['. count($users).']')
        @slot('actions')
            @include('components.btn-export',['route'=>  route('admin.users.export')])
            @include('components.btn-create',['url'=>  route('admin.users.create')])
        @endslot
        @slot('table')
            <table class="table align-items-center table-flush border-bottom-0" id="dtUsers">
                <thead class="thead-light">
                <tr>
                    <th>Usuario</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @each('admin.users.partials.row', $users,'user')
                </tbody>
            </table>
        @endslot
    @endcomponent
@endsection
