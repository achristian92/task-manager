@extends('layouts.admin.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Mi Cuenta</div>
                    <div class="card-body">
                        @include('components.errors-and-messages')
                        <form method="POST" action="{{ route('setting.profile.update',$model->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            @include('setting.profiles.partials.fields')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
