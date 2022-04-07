@extends('layouts.admin.app')
@section('content')
    @component('components.list')
        @slot('title','Seguimiento de Usuarios ['. count($tracks).']')
        @slot('actions')
            <div class="form-group offset-md-8 col-md-4 align-content-center">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                    </div>
                    <input class="form-control" type="text" id="datepickertrack">
                </div>
            </div>
        @endslot
        @slot('filters')@endslot
        @slot('table')
            <table class="table align-items-center table-flush border-bottom-0" id="dtTracks">
                <thead class="thead-light">
                <tr>
                    <th>Usuario</th>
                    <th>Vencidos</th>
                    <th>Completados</th>
                    <th>Estimado/Real</th>
                    <th>Progreso</th>
                    <th>Desempeño</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                @each('admin.tracks.partials.row', $tracks,'track')
                </tbody>
            </table>
        @endslot
    @endcomponent
{{--    <tracks></tracks>--}}
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready( function () {
            let base_url = '{!! url()->current() !!}';

            let date = '{!! request('yearAndMonth') ?: now()->format('Y-m') !!}'

            $("#datepickertrack")
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
        } );
    </script>
@endpush
