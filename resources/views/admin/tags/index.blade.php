@extends('layouts.admin.app')
@section('content')
    @component('components.list')
        @slot('title','Lista de Etiquetas ['. count($tags).']')
        @slot('actions')
            <tags-btn-add></tags-btn-add>
        @endslot
        @slot('table')
            <tags-list :p_tags="{{ json_encode($tags) }}"></tags-list>
        @endslot
    @endcomponent
    <tags-form></tags-form>
@endsection
