@extends('layouts.admin.app')
@section('content')
    <customers-show :c_customer_id="{{ $customer_id }}"></customers-show>
@endsection
@push('js')
    <script type="text/javascript">
        $(document).ready( function () {

            setTimeout(() => {
                $('#dtTableImbox').DataTable({
                    "dom": '<"top"fl>rt<"bottom"ip>',
                    language: {
                        "url": "{{ URL::asset('datatables.json') }}"
                    },
                });
            }, 3000)



        } );
    </script>
@endpush
@push('styles')
    <style>
        #dtTableImbox_filter {
            float: left !important;
        }

        #dtTableImbox_filter input {
            width: 400px;
            outline: 0px solid #aaa;
        }

        #dtTableImbox_length {
            float: right !important;
        }
        #dtTableImbox_length label {
            display:flex;
            justify-content: center;
            align-items: center;
        }
    </style>
@endpush


