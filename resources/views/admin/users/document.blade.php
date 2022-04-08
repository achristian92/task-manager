@extends('layouts.admin.app')
@section('content')
    @component('components.list')
        @slot('title','Documentos de '.$user->name.' ['. count($documents).']')
        @slot('actions')@endslot
        @slot('table')
            <table class="table align-items-center table-flush border-bottom-0" id="dtUserDocument">
                <thead class="thead-light">
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Descripción</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($documents as $document)
                    <tr>
                        <td>{{ $document->id }}</td>
                        <td>{{ bsw_date_short($document->created_at,'d/m/y',true) }}</td>
                        <td>{{ $document->type }}</td>
                        <td>{{ $document->name }}</td>
                        <td>
                            <a href="{{ $document->url_file }}"  target="_blank" class="ml-2">
                                <i class="fa fa-download mr-1"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endslot
    @endcomponent
@endsection


