@extends('layouts.admin.app')
@section('content')
<div class="row">
    <div class="col-md-6">
        <h3>PANEL DE CONTROL GENERAL</h3>
    </div>
    <div class="col-md-6">
        @include('components.datepicker',['name' => 'dtpickerDashboard'])
    </div>
</div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <progress-fusionchart p_progress="{{ $progress }}"></progress-fusionchart>
                </div>
                <div class="col-md-6">
                    <resume-fusionchart :p_resume="{{ json_encode($resume) }}"></resume-fusionchart>
                </div>
            </div>
            @include('admin.dashboard.partials.paginate')
            <div class="row">
                <div class="col-md-6">
                    <customers-more-hours :p_total_hours="{{ json_encode($custMoreHours)}}"></customers-more-hours>
                </div>
                <div class="col-md-6">
                    <customers-less-hours :p_total_hours="{{ json_encode($custLessHours)}}"></customers-less-hours>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-6">
                    <users-more-hours :p_total_hours="{{ json_encode($usuMoreHours)}}"></users-more-hours>
                </div>
                <div class="col-md-6">
                    <users-less-hours :p_total_hours="{{ json_encode($usuLessHours)}}"></users-less-hours>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-6">
                    <tags-history :p_history="{{ json_encode($tagHistory) }}"></tags-history>
                </div>
                <div class="col-md-6">
                    <tags-percentage :p_percentage="{{ json_encode($tagPercentage) }}"></tags-percentage>
                </div>
            </div>

        </div>
    </div>
@endsection
@push('js')
    <script type="text/javascript">
        $(document).ready( function () {
            let base_url = '{!! url()->current() !!}';

            let date = '{!! request('yearAndMonth') ?: now()->format('Y-m') !!}'

            $("#dtpickerDashboard")
                .datetimepicker({
                    format: "MM/YYYY",
                    locale: "es",
                    defaultDate:date,
                    icons: {
                        time: "fa fa-clock-o",
                        date: "fa fa-calendar",
                        up: "fa fa-arrow-up",
                        down: "fa fa-arrow-down",
                        previous: "fa fa-chevron-left",
                        next: "fa fa-chevron-right",
                        today: "fa fa-clock-o",
                        clear: "fa fa-trash-o"
                    }
                })
                .on("dp.change", function (e) {
                    window.location.href = base_url+"?yearAndMonth="+e.date.format('YYYY-MM');
                });


            $('#qtypaginate').change(function() {
                window.location.href = base_url+'?yearAndMonth='+date+'&qtyShow='+$(this).val();
            });
        } );
    </script>
@endpush
