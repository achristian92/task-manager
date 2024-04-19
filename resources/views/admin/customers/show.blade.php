@extends('layouts.admin.app')
@section('content')
    <div class="row">
        <div class="col-md-6 offset-6 text-right">
            @include('components.datepicker',['name' => 'datepickercustomer'])
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="mb-0 text-primary">
                                @include('components.back',['route' => route('admin.customers.index')])
                                {{ $customer->name }}
                            </h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="text-muted">Estado</label><br>
                                @include('components.status', ['is_active' => $customer->is_active])
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="text-muted">Documento</label>
                                <h5>RUC: {{ $customer->ruc }}</h5>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="text-muted">Dirección</label>
                                <h5>{{ $customer->address }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="text-muted">Contacto</label>
                                <h5>{{ $customer->contact_name }}</h5>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="text-muted">Teléfonos</label>
                                <h5>{{ $customer->contact_telephone }}</h5>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="text-muted">Email</label>
                                <h5>{{ $customer->contact_email }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="text-muted">Link reseña</label>
                            <h5><a href="{{ $customer->review_link }}" target="_blank">{{ $customer->review_link }}</a></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="font-weight-bold">Documentos</h5>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="button" data-toggle="modal" data-target="#customerFileFormModal" class="btn btn-sm btn-primary btn-icon">
                                <i class="fa fa-plus mr-2"></i> Subir
                            </button>
                        </div>
                    </div>
                    <div tabindex="1" >
                        <table class="table table-flush border-bottom-0">
                            <thead class="thead-light">
                            <tr class="alegra_table_thead">
                                <th>Nombre</th>
                                <th width="5%"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($customer->files as $file)
                                <tr>
                                    <td>
                                        <span class="{{ $file->is_main ? 'text-danger' : '' }}"  style="white-space: break-spaces;">{{ $file->name }}</span>
                                    </td>
                                    <td class="text-right" width="5%">
                                        <a href="{{$file->src_file}}" target="_blank"><i class="fa fa-download"></i></a>
                                        <a href="{{ route('admin.customers.files.delete',[$customer->id,$file->id]) }}" class="text-danger ml-2" onClick="javascript: return confirm('¿Estás seguro de elimarlo?');"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td>
                                        Aún no cuentas con registros
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Act. completadas</h5>
                            <span class="h2 font-weight-bold mb-0">
                                    {{ $resume['qtyCompleted'] }}
                                    <span class="text-success">/</span>
                                    {{ $resume['total'] }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                                <i class="fas fa-list"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0"># Trabajadores</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $qtyUsers }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Estimado / Real</h5>
                            <span class="h2 font-weight-bold mb-0">
                                    {{ $timeWorked }}
                                    <span class="text-info">/</span>
                                    {{ $timeReal }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                                <i class="fas fa-stopwatch"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Progreso</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $progress }}%</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-gradient-light text-white rounded-circle shadow">
                                <i class="ni ni-chart-bar-32"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="row justify-content-center">
                        <div class="col-md-9">
                            <progress-fusionchart :p_progress="{{ json_encode($progress) }}"></progress-fusionchart>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <resume-fusionchart :p_resume="{{ json_encode($resume) }}"></resume-fusionchart>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <tags-history :p_history="{{ json_encode($tagHistory) }}"></tags-history>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <admin-imbox-basic :p_activities="{{ json_encode($activities) }}"></admin-imbox-basic>
                </div>
            </div>
            <activity-show></activity-show>
        </div>
    </div>
    <customers-file-form :p_customer_id="{{ $customer->id }}"></customers-file-form>
@endsection
@push('js')
    <script type="text/javascript">
        $(document).ready( function () {

            var base_url = '{!! url()->current() !!}';

            let date = '{!! request('yearAndMonth') ?: now()->format('Y-m') !!}'

            $("#datepickercustomer")
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

