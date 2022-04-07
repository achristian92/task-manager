@extends('layouts.admin.app')
@section('content')
    @component('components.form.form')
        @slot('title','Editar Usuario')
        @slot('content')
            @include('components.errors-and-messages')
            <form method="POST" action="{{route('admin.users.update',$model->id)}}" enctype="multipart/form-data">
                @method('PUT')
                @include('admin.users.partials.fields',[
                'back'=> route('admin.users.index')
                ])
            </form>
        @endslot
    @endcomponent
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            $('.js-roles-multiple').select2();
            $('.js-admin-super-multiple').select2();
            $('.js-customer-select-multiple').select2();

            // Asign
            var monitored_all = $('#inputCanCheckByAll').val();

            if (monitored_all === "0") {
                $('#selectMultipleAdminORSuper').show();
                $("#checkByAll").prop( "checked", false );
                $('#checkByAll').val(0)

            } else {
                $("#checkByAll").prop('checked', true);
                $('#selectMultipleAdminORSuper').hide();
                $('#checkByAll').val(1)
            }

            $('.can_check_all').change(function(){
                if (!$(this).prop('checked')){
                    $('#selectMultipleAdminORSuper').show();
                    $('#checkByAll').val(0)
                } else {
                    $('#selectMultipleAdminORSuper').hide();
                    $('#checkByAll').val(1)
                }
            });

            // Customer
            let check_all_customers = $('#inputCanCheckAllCustomers').val();
            if (check_all_customers === "0") {
                $('#selectMultipleCustomers').show();
                $("#checkAllCustomers").prop( "checked", false );
                $('#checkAllCustomers').val(0)
            } else {
                $("#checkAllCustomers").prop('checked', true);
                $('#selectMultipleCustomers').hide();
                $('#checkAllCustomers').val(1)
            }

            $('.can_check_all_customers').change(function(){
                if(!$(this).prop('checked')){
                    $('#selectMultipleCustomers').show();
                    $('#checkAllCustomers').val(0)
                }else{
                    $('#selectMultipleCustomers').hide();
                    $('#checkAllCustomers').val(1)
                }
            });

        });

    </script>
@endpush
