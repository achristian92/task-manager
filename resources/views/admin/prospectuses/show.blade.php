@extends('layouts.admin.app')
@section('content')
    <div class="row mb-4">
        <div class="col-md-6 offset-6 text-right">
            <a href="" class="btn btn-primary btn-icon">
                <span class="btn-inner--icon"><i class="ni ni-fat-add"></i></span>
                <span class="btn-inner--text">Convertir a cliente</span>
            </a>
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
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-tabs mb-3" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#services" role="tab"
                       aria-controls="home" aria-selected="true">Servicios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="order-tab" data-toggle="tab" href="#follow" role="tab"
                       aria-controls="order" aria-selected="false">Seguimiento</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="services" role="tabpanel" aria-labelledby="home-tab">
                    <div class="row mb-2 mt-2">
                        <div class="col text-left">
                            <h2>Lista de servicios</h2>
                        </div>
                        <div class="col text-right">
                            @include('components.btn-create',['url'=>  route('admin.prospectuses.create')])
                        </div>
                    </div>
                    <table class="table align-items-center table-flush border-bottom-0" id="dtImboxBasic">
                        <thead class="thead-light">
                        <tr>
                            <th style="width: 10px;">Fecha</th>
                            <th>Servicio | Cliente</th>
                            <th>Estado</th>
                            <th>Planeado</th>
                            <th>Duración</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                        </thead>
                        <tbody data-v-2b599dd9=""><tr data-v-2b599dd9="" class="odd"><td data-v-2b599dd9="" style="width: 10px;" class="sorting_1">04/06</td> <td data-v-2b599dd9=""><a data-v-2b599dd9="" class="font-weight-bold">AUDITORIA 2023</a> <span data-v-2b599dd9="" class="text-success ml-2">(Nuevo)</span> <br data-v-2b599dd9=""> <span data-v-2b599dd9="" class="h6 text-muted"><i data-v-2b599dd9="" class="far fa-building mr-1"></i> CENTRO ESPAÑOL |
                    <i data-v-2b599dd9="" class="far fa-user mr-1 ml-1"></i> Anaveli Silva |
                    <i data-v-2b599dd9="" class="far fa-flag mr-1 ml-1"></i> <span data-v-2b599dd9="">Normal</span> <!----> <!----> <span data-v-2b599dd9=""><i data-v-2b599dd9="" class="ni ni-tag ml-2 mr-1" style="color: rgb(192, 56, 43);"></i>
                        CONTABILIDAD-GESTION
                    </span></span></td> <td data-v-2b599dd9=""><button data-v-2b599dd9="" type="button" class="btn btn-sm btn-outline-success">
                                    Completado
                                    <span data-v-2b599dd9="" style="display: none;"><i data-v-2b599dd9="" class="fas fa-bell ml-1 text-yellow"></i></span></button></td> <td data-v-2b599dd9="">
                                00:00 h
                            </td> <td data-v-2b599dd9=""><div data-v-2b599dd9="">04:30 h</div></td> <td data-v-2b599dd9="" class="text-right"><div data-v-2b599dd9="" class="dropdown"><a data-v-2b599dd9="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-sm btn-icon-only text-light"><i data-v-2b599dd9="" class="fas fa-ellipsis-v"></i></a> <div data-v-2b599dd9="" class="dropdown-menu dropdown-menu-right dropdown-menu-arrow"><a data-v-2b599dd9="" href="" class="dropdown-item"><span data-v-2b599dd9="">Detalle</span></a> <a data-v-2b599dd9="" href="" class="dropdown-item"><span data-v-2b599dd9="">Eliminar</span></a></div></div></td></tr><tr data-v-2b599dd9="" class="even"><td data-v-2b599dd9="" style="width: 10px;" class="sorting_1">04/06</td> <td data-v-2b599dd9=""><a data-v-2b599dd9="" class="font-weight-bold">ELABORACION EEFF</a> <span data-v-2b599dd9="" class="text-success ml-2">(Nuevo)</span> <br data-v-2b599dd9=""> <span data-v-2b599dd9="" class="h6 text-muted"><i data-v-2b599dd9="" class="far fa-building mr-1"></i> CENTRO ESPAÑOL |
                    <i data-v-2b599dd9="" class="far fa-user mr-1 ml-1"></i> Anaveli Silva |
                    <i data-v-2b599dd9="" class="far fa-flag mr-1 ml-1"></i> <span data-v-2b599dd9="">Normal</span> <!----> <!----> <span data-v-2b599dd9=""><i data-v-2b599dd9="" class="ni ni-tag ml-2 mr-1" style="color: rgb(192, 56, 43);"></i>
                        CONTABILIDAD-GESTION
                    </span></span></td> <td data-v-2b599dd9=""><button data-v-2b599dd9="" type="button" class="btn btn-sm btn-outline-success">
                                    Completado
                                    <span data-v-2b599dd9="" style="display: none;"><i data-v-2b599dd9="" class="fas fa-bell ml-1 text-yellow"></i></span></button></td> <td data-v-2b599dd9="">
                                00:00 h
                            </td> <td data-v-2b599dd9=""><div data-v-2b599dd9="">02:30 h</div></td> <td data-v-2b599dd9="" class="text-right"><div data-v-2b599dd9="" class="dropdown"><a data-v-2b599dd9="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-sm btn-icon-only text-light"><i data-v-2b599dd9="" class="fas fa-ellipsis-v"></i></a> <div data-v-2b599dd9="" class="dropdown-menu dropdown-menu-right dropdown-menu-arrow"><a data-v-2b599dd9="" href="" class="dropdown-item"><span data-v-2b599dd9="">Detalle</span></a> <a data-v-2b599dd9="" href="" class="dropdown-item"><span data-v-2b599dd9="">Eliminar</span></a></div></div></td></tr><tr data-v-2b599dd9="" class="odd"><td data-v-2b599dd9="" style="width: 10px;" class="sorting_1">04/06</td> <td data-v-2b599dd9=""><a data-v-2b599dd9="" class="font-weight-bold">REGISTRO DE COMPRAS</a> <span data-v-2b599dd9="" class="text-success ml-2">(Nuevo)</span> <br data-v-2b599dd9=""> <span data-v-2b599dd9="" class="h6 text-muted"><i data-v-2b599dd9="" class="far fa-building mr-1"></i> ONTIER PERU S.A... |
                    <i data-v-2b599dd9="" class="far fa-user mr-1 ml-1"></i> Anaveli Silva |
                    <i data-v-2b599dd9="" class="far fa-flag mr-1 ml-1"></i> <span data-v-2b599dd9="">Normal</span> <!----> <!----> <span data-v-2b599dd9=""><i data-v-2b599dd9="" class="ni ni-tag ml-2 mr-1" style="color: rgb(192, 56, 43);"></i>
                        CONTABILIDAD-GESTION
                    </span></span></td> <td data-v-2b599dd9=""><button data-v-2b599dd9="" type="button" class="btn btn-sm btn-outline-success">
                                    Completado
                                    <span data-v-2b599dd9="" style="display: none;"><i data-v-2b599dd9="" class="fas fa-bell ml-1 text-yellow"></i></span></button></td> <td data-v-2b599dd9="">
                                00:00 h
                            </td> <td data-v-2b599dd9=""><div data-v-2b599dd9="">02:30 h</div></td> <td data-v-2b599dd9="" class="text-right"><div data-v-2b599dd9="" class="dropdown"><a data-v-2b599dd9="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-sm btn-icon-only text-light"><i data-v-2b599dd9="" class="fas fa-ellipsis-v"></i></a> <div data-v-2b599dd9="" class="dropdown-menu dropdown-menu-right dropdown-menu-arrow"><a data-v-2b599dd9="" href="" class="dropdown-item"><span data-v-2b599dd9="">Detalle</span></a> <a data-v-2b599dd9="" href="" class="dropdown-item"><span data-v-2b599dd9="">Eliminar</span></a></div></div></td></tr><tr data-v-2b599dd9="" class="even"><td data-v-2b599dd9="" style="width: 10px;" class="sorting_1">04/06</td> <td data-v-2b599dd9=""><a data-v-2b599dd9="" class="font-weight-bold">REGISTRO DE VENTAS</a> <span data-v-2b599dd9="" class="text-success ml-2">(Nuevo)</span> <br data-v-2b599dd9=""> <span data-v-2b599dd9="" class="h6 text-muted"><i data-v-2b599dd9="" class="far fa-building mr-1"></i> ONTIER PERU S.A... |
                    <i data-v-2b599dd9="" class="far fa-user mr-1 ml-1"></i> Anaveli Silva |
                    <i data-v-2b599dd9="" class="far fa-flag mr-1 ml-1"></i> <span data-v-2b599dd9="">Normal</span> <!----> <!----> <span data-v-2b599dd9=""><i data-v-2b599dd9="" class="ni ni-tag ml-2 mr-1" style="color: rgb(192, 56, 43);"></i>
                        CONTABILIDAD-GESTION
                    </span></span></td> <td data-v-2b599dd9=""><button data-v-2b599dd9="" type="button" class="btn btn-sm btn-outline-success">
                                    Completado
                                    <span data-v-2b599dd9="" style="display: none;"><i data-v-2b599dd9="" class="fas fa-bell ml-1 text-yellow"></i></span></button></td> <td data-v-2b599dd9="">
                                00:00 h
                            </td> <td data-v-2b599dd9=""><div data-v-2b599dd9="">02:30 h</div></td> <td data-v-2b599dd9="" class="text-right"><div data-v-2b599dd9="" class="dropdown"><a data-v-2b599dd9="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-sm btn-icon-only text-light"><i data-v-2b599dd9="" class="fas fa-ellipsis-v"></i></a> <div data-v-2b599dd9="" class="dropdown-menu dropdown-menu-right dropdown-menu-arrow"><a data-v-2b599dd9="" href="" class="dropdown-item"><span data-v-2b599dd9="">Detalle</span></a> <a data-v-2b599dd9="" href="" class="dropdown-item"><span data-v-2b599dd9="">Eliminar</span></a></div></div></td></tr><tr data-v-2b599dd9="" class="odd"><td data-v-2b599dd9="" style="width: 10px;" class="sorting_1">05/06</td> <td data-v-2b599dd9=""><a data-v-2b599dd9="" class="font-weight-bold">CONCILIACION BANCARIA</a> <span data-v-2b599dd9="" class="text-success ml-2">(Nuevo)</span> <br data-v-2b599dd9=""> <span data-v-2b599dd9="" class="h6 text-muted"><i data-v-2b599dd9="" class="far fa-building mr-1"></i> ONTIER PERU S.A... |
                    <i data-v-2b599dd9="" class="far fa-user mr-1 ml-1"></i> Anaveli Silva |
                    <i data-v-2b599dd9="" class="far fa-flag mr-1 ml-1"></i> <span data-v-2b599dd9="">Normal</span> <!----> <!----> <span data-v-2b599dd9=""><i data-v-2b599dd9="" class="ni ni-tag ml-2 mr-1" style="color: rgb(192, 56, 43);"></i>
                        CONTABILIDAD-GESTION
                    </span></span></td> <td data-v-2b599dd9=""><button data-v-2b599dd9="" type="button" class="btn btn-sm btn-outline-success">
                                    Completado
                                    <span data-v-2b599dd9="" style="display: none;"><i data-v-2b599dd9="" class="fas fa-bell ml-1 text-yellow"></i></span></button></td> <td data-v-2b599dd9="">
                                00:00 h
                            </td> <td data-v-2b599dd9=""><div data-v-2b599dd9="">09:00 h</div></td> <td data-v-2b599dd9="" class="text-right"><div data-v-2b599dd9="" class="dropdown"><a data-v-2b599dd9="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-sm btn-icon-only text-light"><i data-v-2b599dd9="" class="fas fa-ellipsis-v"></i></a> <div data-v-2b599dd9="" class="dropdown-menu dropdown-menu-right dropdown-menu-arrow"><a data-v-2b599dd9="" href="" class="dropdown-item"><span data-v-2b599dd9="">Detalle</span></a> <a data-v-2b599dd9="" href="" class="dropdown-item"><span data-v-2b599dd9="">Eliminar</span></a></div></div></td></tr><tr data-v-2b599dd9="" class="even"><td data-v-2b599dd9="" style="width: 10px;" class="sorting_1">06/06</td> <td data-v-2b599dd9=""><a data-v-2b599dd9="" class="font-weight-bold">REGISTRO DE COMPRAS</a> <span data-v-2b599dd9="" class="text-success ml-2">(Nuevo)</span> <br data-v-2b599dd9=""> <span data-v-2b599dd9="" class="h6 text-muted"><i data-v-2b599dd9="" class="far fa-building mr-1"></i> CONSULTORES DE... |
                    <i data-v-2b599dd9="" class="far fa-user mr-1 ml-1"></i> Anaveli Silva |
                    <i data-v-2b599dd9="" class="far fa-flag mr-1 ml-1"></i> <span data-v-2b599dd9="">Normal</span> <!----> <!----> <span data-v-2b599dd9=""><i data-v-2b599dd9="" class="ni ni-tag ml-2 mr-1" style="color: rgb(192, 56, 43);"></i>
                        CONTABILIDAD-GESTION
                    </span></span></td> <td data-v-2b599dd9=""><button data-v-2b599dd9="" type="button" class="btn btn-sm btn-outline-success">
                                    Completado
                                    <span data-v-2b599dd9="" style="display: none;"><i data-v-2b599dd9="" class="fas fa-bell ml-1 text-yellow"></i></span></button></td> <td data-v-2b599dd9="">
                                00:00 h
                            </td> <td data-v-2b599dd9=""><div data-v-2b599dd9="">02:30 h</div></td> <td data-v-2b599dd9="" class="text-right"><div data-v-2b599dd9="" class="dropdown"><a data-v-2b599dd9="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-sm btn-icon-only text-light"><i data-v-2b599dd9="" class="fas fa-ellipsis-v"></i></a> <div data-v-2b599dd9="" class="dropdown-menu dropdown-menu-right dropdown-menu-arrow"><a data-v-2b599dd9="" href="" class="dropdown-item"><span data-v-2b599dd9="">Detalle</span></a> <a data-v-2b599dd9="" href="" class="dropdown-item"><span data-v-2b599dd9="">Eliminar</span></a></div></div></td></tr><tr data-v-2b599dd9="" class="odd"><td data-v-2b599dd9="" style="width: 10px;" class="sorting_1">06/06</td> <td data-v-2b599dd9=""><a data-v-2b599dd9="" class="font-weight-bold">CONCILIACION BANCARIA</a> <span data-v-2b599dd9="" class="text-success ml-2">(Nuevo)</span> <br data-v-2b599dd9=""> <span data-v-2b599dd9="" class="h6 text-muted"><i data-v-2b599dd9="" class="far fa-building mr-1"></i> ONTIER PERU S.A... |
                    <i data-v-2b599dd9="" class="far fa-user mr-1 ml-1"></i> Anaveli Silva |
                    <i data-v-2b599dd9="" class="far fa-flag mr-1 ml-1"></i> <span data-v-2b599dd9="">Normal</span> <!----> <!----> <span data-v-2b599dd9=""><i data-v-2b599dd9="" class="ni ni-tag ml-2 mr-1" style="color: rgb(51, 152, 219);"></i>
                        CONTABILIDAD-CONSULTAS
                    </span></span></td> <td data-v-2b599dd9=""><button data-v-2b599dd9="" type="button" class="btn btn-sm btn-outline-success">
                                    Completado
                                    <span data-v-2b599dd9="" style="display: none;"><i data-v-2b599dd9="" class="fas fa-bell ml-1 text-yellow"></i></span></button></td> <td data-v-2b599dd9="">
                                00:00 h
                            </td> <td data-v-2b599dd9=""><div data-v-2b599dd9="">03:00 h</div></td> <td data-v-2b599dd9="" class="text-right"><div data-v-2b599dd9="" class="dropdown"><a data-v-2b599dd9="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-sm btn-icon-only text-light"><i data-v-2b599dd9="" class="fas fa-ellipsis-v"></i></a> <div data-v-2b599dd9="" class="dropdown-menu dropdown-menu-right dropdown-menu-arrow"><a data-v-2b599dd9="" href="" class="dropdown-item"><span data-v-2b599dd9="">Detalle</span></a> <a data-v-2b599dd9="" href="" class="dropdown-item"><span data-v-2b599dd9="">Eliminar</span></a></div></div></td></tr><tr data-v-2b599dd9="" class="even"><td data-v-2b599dd9="" style="width: 10px;" class="sorting_1">06/06</td> <td data-v-2b599dd9=""><a data-v-2b599dd9="" class="font-weight-bold">CONCILIACION BANCARIA</a> <span data-v-2b599dd9="" class="text-success ml-2">(Nuevo)</span> <br data-v-2b599dd9=""> <span data-v-2b599dd9="" class="h6 text-muted"><i data-v-2b599dd9="" class="far fa-building mr-1"></i> ONTIER PERU S.A... |
                    <i data-v-2b599dd9="" class="far fa-user mr-1 ml-1"></i> Anaveli Silva |
                    <i data-v-2b599dd9="" class="far fa-flag mr-1 ml-1"></i> <span data-v-2b599dd9="">Normal</span> <!----> <!----> <span data-v-2b599dd9=""><i data-v-2b599dd9="" class="ni ni-tag ml-2 mr-1" style="color: rgb(192, 56, 43);"></i>
                        CONTABILIDAD-GESTION
                    </span></span></td> <td data-v-2b599dd9=""><button data-v-2b599dd9="" type="button" class="btn btn-sm btn-outline-success">
                                    Completado
                                    <span data-v-2b599dd9="" style="display: none;"><i data-v-2b599dd9="" class="fas fa-bell ml-1 text-yellow"></i></span></button></td> <td data-v-2b599dd9="">
                                00:00 h
                            </td> <td data-v-2b599dd9=""><div data-v-2b599dd9="">04:30 h</div></td> <td data-v-2b599dd9="" class="text-right"><div data-v-2b599dd9="" class="dropdown"><a data-v-2b599dd9="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-sm btn-icon-only text-light"><i data-v-2b599dd9="" class="fas fa-ellipsis-v"></i></a> <div data-v-2b599dd9="" class="dropdown-menu dropdown-menu-right dropdown-menu-arrow"><a data-v-2b599dd9="" href="" class="dropdown-item"><span data-v-2b599dd9="">Detalle</span></a> <a data-v-2b599dd9="" href="" class="dropdown-item"><span data-v-2b599dd9="">Eliminar</span></a></div></div></td></tr><tr data-v-2b599dd9="" class="odd"><td data-v-2b599dd9="" style="width: 10px;" class="sorting_1">06/06</td> <td data-v-2b599dd9=""><a data-v-2b599dd9="" class="font-weight-bold">REGISTRO DE VENTAS</a> <span data-v-2b599dd9="" class="text-success ml-2">(Nuevo)</span> <br data-v-2b599dd9=""> <span data-v-2b599dd9="" class="h6 text-muted"><i data-v-2b599dd9="" class="far fa-building mr-1"></i> ENCOSSA S.A.C. |
                    <i data-v-2b599dd9="" class="far fa-user mr-1 ml-1"></i> Anaveli Silva |
                    <i data-v-2b599dd9="" class="far fa-flag mr-1 ml-1"></i> <span data-v-2b599dd9="">Normal</span> <!----> <!----> <span data-v-2b599dd9=""><i data-v-2b599dd9="" class="ni ni-tag ml-2 mr-1" style="color: rgb(192, 56, 43);"></i>
                        CONTABILIDAD-GESTION
                    </span></span></td> <td data-v-2b599dd9=""><button data-v-2b599dd9="" type="button" class="btn btn-sm btn-outline-success">
                                    Completado
                                    <span data-v-2b599dd9="" style="display: none;"><i data-v-2b599dd9="" class="fas fa-bell ml-1 text-yellow"></i></span></button></td> <td data-v-2b599dd9="">
                                00:00 h
                            </td> <td data-v-2b599dd9=""><div data-v-2b599dd9="">01:00 h</div></td> <td data-v-2b599dd9="" class="text-right"><div data-v-2b599dd9="" class="dropdown"><a data-v-2b599dd9="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-sm btn-icon-only text-light"><i data-v-2b599dd9="" class="fas fa-ellipsis-v"></i></a> <div data-v-2b599dd9="" class="dropdown-menu dropdown-menu-right dropdown-menu-arrow"><a data-v-2b599dd9="" href="" class="dropdown-item"><span data-v-2b599dd9="">Detalle</span></a> <a data-v-2b599dd9="" href="" class="dropdown-item"><span data-v-2b599dd9="">Eliminar</span></a></div></div></td></tr></tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="follow" role="tabpanel" aria-labelledby="order-tab">
                    <div class="row">
                        <div class="col">
                            <h2>Lista de actividades</h2>
                        </div>
                        <div class="col text-right">
                            @include('components.btn-create',['url'=>  route('admin.prospectuses.create')])
                        </div>
                    </div>
                    <div class="container py-2 mt-4 mb-4">

                        <div class="row">
                            <!-- timeline item 1 left dot -->
                            <div class="col-auto text-center flex-column d-none d-sm-flex">
                                <div class="row h-50">
                                    <div class="col">&nbsp;</div>
                                    <div class="col">&nbsp;</div>
                                </div>
                                <h5 class="m-2">
                                    <span class="badge badge-pill bg-light border">&nbsp;</span>
                                </h5>
                                <div class="row h-50">
                                    <div class="col border-right">&nbsp;</div>
                                    <div class="col">&nbsp;</div>
                                </div>
                            </div>
                            <!-- timeline item 1 event content -->
                            <div class="col py-2">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="float-right text-muted">Mes, Mayo 01th 2024 10:30 AM</div>
                                        <h4 class="card-title">Día 3 Comunicación por llamada</h4>
                                        <p class="card-text">Reunión para conversar y convertir al prospecto en un cliente mensual, firmado de contrato</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- timeline item 1 left dot -->
                            <div class="col-auto text-center flex-column d-none d-sm-flex">
                                <div class="row h-50">
                                    <div class="col">&nbsp;</div>
                                    <div class="col">&nbsp;</div>
                                </div>
                                <h5 class="m-2">
                                    <span class="badge badge-pill bg-light border">&nbsp;</span>
                                </h5>
                                <div class="row h-50">
                                    <div class="col border-right">&nbsp;</div>
                                    <div class="col">&nbsp;</div>
                                </div>
                            </div>
                            <!-- timeline item 1 event content -->
                            <div class="col py-2">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="float-right text-muted">Mes, Marzo 20th 2024 9:30 AM</div>
                                        <h4 class="card-title">Día 2 Reunión de oportunidad</h4>
                                        <p class="card-text">Reunión para escuchar los problemas actuales del cliente. Enviar propuesta de trabajo posterior. Enviar comunicación a Luis Flores luis.flores@inkapower.pe</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- timeline item 1 left dot -->
                            <div class="col-auto text-center flex-column d-none d-sm-flex">
                                <div class="row h-50">
                                    <div class="col">&nbsp;</div>
                                    <div class="col">&nbsp;</div>
                                </div>
                                <h5 class="m-2">
                                    <span class="badge badge-pill bg-light border">&nbsp;</span>
                                </h5>
                                <div class="row h-50">
                                    <div class="col border-right">&nbsp;</div>
                                    <div class="col">&nbsp;</div>
                                </div>
                            </div>
                            <!-- timeline item 1 event content -->
                            <div class="col py-2">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="float-right text-muted">Mes, Marzo 9th 2024 7:00 AM</div>
                                        <h4 class="card-title">Día 1 Llenado de información</h4>
                                        <p class="card-text">Comunicación de acercamiento con el cliente, llamar en 10 días.</p>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">





        <!--container-->
    </div>
{{--    <div class="row">--}}
{{--        <div class="col-xl-3 col-md-6">--}}
{{--            <div class="card card-stats">--}}
{{--                <!-- Card body -->--}}
{{--                <div class="card-body">--}}
{{--                    <div class="row">--}}
{{--                        <div class="col">--}}
{{--                            <h5 class="card-title text-uppercase text-muted mb-0">Act. completadas</h5>--}}
{{--                            <span class="h2 font-weight-bold mb-0">--}}
{{--                                    {{ $resume['qtyCompleted'] }}--}}
{{--                                    <span class="text-success">/</span>--}}
{{--                                    {{ $resume['total'] }}</span>--}}
{{--                        </div>--}}
{{--                        <div class="col-auto">--}}
{{--                            <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">--}}
{{--                                <i class="fas fa-list"></i>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="col-xl-3 col-md-6">--}}
{{--            <div class="card card-stats">--}}
{{--                <!-- Card body -->--}}
{{--                <div class="card-body">--}}
{{--                    <div class="row">--}}
{{--                        <div class="col">--}}
{{--                            <h5 class="card-title text-uppercase text-muted mb-0"># Trabajadores</h5>--}}
{{--                            <span class="h2 font-weight-bold mb-0">{{ $qtyUsers }}</span>--}}
{{--                        </div>--}}
{{--                        <div class="col-auto">--}}
{{--                            <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">--}}
{{--                                <i class="fas fa-users"></i>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="col-xl-3 col-md-6">--}}
{{--            <div class="card card-stats">--}}
{{--                <!-- Card body -->--}}
{{--                <div class="card-body">--}}
{{--                    <div class="row">--}}
{{--                        <div class="col">--}}
{{--                            <h5 class="card-title text-uppercase text-muted mb-0">Estimado / Real</h5>--}}
{{--                            <span class="h2 font-weight-bold mb-0">--}}
{{--                                    {{ $timeWorked }}--}}
{{--                                    <span class="text-info">/</span>--}}
{{--                                    {{ $timeReal }}</span>--}}
{{--                        </div>--}}
{{--                        <div class="col-auto">--}}
{{--                            <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">--}}
{{--                                <i class="fas fa-stopwatch"></i>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="col-xl-3 col-md-6">--}}
{{--            <div class="card card-stats">--}}
{{--                <!-- Card body -->--}}
{{--                <div class="card-body">--}}
{{--                    <div class="row">--}}
{{--                        <div class="col">--}}
{{--                            <h5 class="card-title text-uppercase text-muted mb-0">Progreso</h5>--}}
{{--                            <span class="h2 font-weight-bold mb-0">{{ $progress }}%</span>--}}
{{--                        </div>--}}
{{--                        <div class="col-auto">--}}
{{--                            <div class="icon icon-shape bg-gradient-light text-white rounded-circle shadow">--}}
{{--                                <i class="ni ni-chart-bar-32"></i>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <div class="card">--}}
{{--        <div class="card-body">--}}
{{--            <div class="row">--}}
{{--                <div class="col-md-6">--}}
{{--                    <div class="row justify-content-center">--}}
{{--                        <div class="col-md-9">--}}
{{--                            <progress-fusionchart :p_progress="{{ json_encode($progress) }}"></progress-fusionchart>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-md-6">--}}
{{--                    <resume-fusionchart :p_resume="{{ json_encode($resume) }}"></resume-fusionchart>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <hr>--}}
{{--            <div class="row">--}}
{{--                <div class="col-md-12">--}}
{{--                    <tags-history :p_history="{{ json_encode($tagHistory) }}"></tags-history>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <br>--}}
{{--            <div class="row">--}}
{{--                <div class="col-md-12">--}}
{{--                    <admin-imbox-basic :p_activities="{{ json_encode($activities) }}"></admin-imbox-basic>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <activity-show></activity-show>--}}
{{--        </div>--}}
{{--    </div>--}}
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
