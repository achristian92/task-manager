@extends('layouts.admin.app')
@section('content')
    @component('components.list')
        @slot('title','Historial ['. count($histories).']')
        @slot('actions')@endslot
        @slot('table')
            <table class="table align-items-center table-flush border-bottom-0" id="dtUserHistory">
                <thead class="thead-light">
                <tr>
                    <th>ID</th>
                    <th>Descripcion</th>
                </tr>
                </thead>
                <tbody>
                @foreach($histories as $history)
                    <tr>
                        <td width="2%"> <i class="fa fa-fingerprint mr-1"></i> {{ $history->id }}</td>
                        <td>
                            {{ $history->description }} <br>
                            <small>
                                <i class="far fa-hand-pointer mr-1 ml-1"></i> {{ $history->type }} |
                                <i class="far fa-user mr-1 ml-1"></i> {{ $history->user_full_name }} |
                                <i class="far fa-clock mr-1 ml-1"></i> {{ \Carbon\Carbon::parse($history->created_at)->format('d/m/y h:i') }}
                            </small>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endslot
    @endcomponent
@endsection
