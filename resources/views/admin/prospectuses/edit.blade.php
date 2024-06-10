@extends('layouts.admin.app')
@section('content')
    @component('components.form.form')
        @slot('title','Editar Cliente')
        @slot('content')
            @include('components.errors-and-messages')
            <form method="POST" action="{{route('admin.customers.update',$model->id)}}" enctype="multipart/form-data">
                @method('PUT')
                @include('admin.customers.partials.fields',[
                'back'=> route('admin.customers.index')
                ])
            </form>
        @endslot
    @endcomponent
@endsection
