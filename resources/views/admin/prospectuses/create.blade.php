@extends('layouts.admin.app')
@section('content')
    @component('components.form.form')
        @slot('title','Nuevo Prospecto')
        @slot('content')
            @include('components.errors-and-messages')
            <form method="POST" action="{{route('admin.prospectuses.store')}}" enctype="multipart/form-data">
                @include('admin.prospectuses.partials.fields',[
                'back'=> route('admin.prospectuses.index')
                ])
            </form>
        @endslot
    @endcomponent
@endsection
