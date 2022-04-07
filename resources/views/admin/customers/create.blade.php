@extends('layouts.admin.app')
@section('content')
    @component('components.form.form')
        @slot('title','Nuevo Cliente')
        @slot('content')
            @include('components.errors-and-messages')
            <form method="POST" action="{{route('admin.customers.store')}}" enctype="multipart/form-data">
                @include('admin.customers.partials.fields',[
                'back'=> route('admin.customers.index')
                ])
            </form>
        @endslot
    @endcomponent
@endsection
