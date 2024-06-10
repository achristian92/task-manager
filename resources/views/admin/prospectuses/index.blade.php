@extends('layouts.admin.app')
@section('content')
    @component('components.list')
        @slot('title','Lista de Prospectos ['. count($prospectuses).']')
        @slot('actions')
            @includeWhen(Auth::user()->isAdmin(),'admin.prospectuses.partials.actions')
            @include('components.btn-create',['url'=>  route('admin.prospectuses.create')])
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
                @each('admin.prospectuses.partials.row', $prospectuses,'customer')
                </tbody>
            </table>
        @endslot
    @endcomponent
    @include('admin.prospectuses.partials.import')
@endsection


