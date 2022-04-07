@extends('layouts.admin.app')
@section('content')
    @component('components.list')
        @slot('title','Lista de Clientes ['. count($customers).']')
        @slot('actions')
            @include('components.btn-create',['url'=>  route('admin.customers.create')])
            @includeWhen(!Auth::user()->isSuperAdmin(),'admin.customers.partials.actions')
        @endslot
        @slot('table')
            <table class="table align-items-center table-flush border-bottom-0" id="dtCustomers">
                <thead class="thead-light">
                <tr>
                    <th>Cliente</th>
                    <th>RUC</th>
                    <th>Estado</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @each('admin.customers.partials.row', $customers,'customer')
                </tbody>
            </table>
        @endslot
    @endcomponent
@endsection


