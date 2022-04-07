@extends('layouts.admin.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Configurar {{ $model->name }}</div>
                    <div class="card-body">
                        @include('components.errors-and-messages')
                        <form method="POST" action="{{ route('setting.company.update',$model->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            @include('setting.companies.partials.fields')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('js')
    <script>
        $(document).ready(function() {
            var can_send_credentials = $('#inputCanSendCredentials').val();
            var send_overdue_act = $('#inputNotifyOverdueAct').val();
            var send_deadline = $('#inputNotifyDeadline').val();

            if (can_send_credentials === "1") {
                $("#senCredentialUser").prop( "checked", true );
            } else {
                $("#senCredentialUser").prop('checked', false);
                $('#senCredentialUser').val(0)
            }

            $('#senCredentialUser').change(function(){
                if(!$(this).prop('checked')){
                    $('#senCredentialUser').val(0)
                }else{
                    $('#senCredentialUser').val(1)
                }
            });

            if (send_overdue_act === "1") {
                $("#sendOverdue").prop( "checked", true );
            } else {
                $("#sendOverdue").prop('checked', false);
                $('#sendOverdue').val(0)
            }

            $('#sendOverdue').change(function(){
                if(!$(this).prop('checked')){
                    $('#sendOverdue').val(0)
                }else{
                    $('#sendOverdue').val(1)
                }
            });

            if (send_deadline === "1") {
                $("#sendDeadline").prop( "checked", true );
            } else {
                $("#sendDeadline").prop('checked', false);
                $('#sendDeadline').val(0)
            }

            $('#sendDeadline').change(function(){
                if(!$(this).prop('checked')){
                    $('#sendDeadline').val(0)
                }else{
                    $('#sendDeadline').val(1)
                }
            });

        });
    </script>
@endpush
