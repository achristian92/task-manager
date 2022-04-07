@csrf

<input type="text" id="inputCanCheckAllCustomers" value="{{$model->can_check_all_customers ? "1" : "0"}}" hidden>
<input type="text" id="inputCanCheckByAll" value="{{$model->can_be_check_all ? "1" : "0"}}" hidden>
<h3>Información</h3>
<div class="row">
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-control-label">Nombres</label>
                    @include('components.form.text',['name' => 'name', 'required'=> 1])
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-control-label">Apellidos</label>
                    @include('components.form.text',['name' => 'last_name', 'required'=> 1])
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-control-label">NRO DOCUMENTO</label>
                    @include('components.form.text',['name' => 'nro_document'])
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-control-label">Correo electrónico</label>
                    @include('components.form.email',['name' => 'email', 'required'=> 1])
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="selectRoles" class="form-control-label">Roles</label>
                    <select class="js-roles-multiple custom-select"
                            id="selectRoles"
                            name="roles[]"
                            multiple="multiple"
                            required>
                        @foreach($roles as $rol)
                            <option value="{{ $rol->id }}"
                                @if(isset($rolesIDSUser) && in_array($rol->id,$rolesIDSUser))
                                selected="selected"
                                @endif>
                                {{ $rol->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="image">Selecciona una imagen (opcional)</label>
            <div class="card" style="width: 5rem;">
                <img class="card-img-top" src="{{$model->urlImg()}}" alt="Card image cap" >
            </div>
            <br>
            <input type="file" class="form-control-file" name="attachment_image" id="image">
        </div>
    </div>
</div>
<hr>
<h3>Asignaciones</h3>
<div class="row">
    <div class="col-md-12">
        <div class="custom-control custom-checkbox mb-3">
            <input class="custom-control-input can_check_all_customers"
                   id="checkAllCustomers"
                   name="can_check_all_customers"
                   type="checkbox"
                   value="{{$model->can_check_all_customers }}"
            />
            <label class="custom-control-label" for="checkAllCustomers">Trabajar en todos los clientes</label>
        </div>
    </div>
</div>
<div class="row" id="selectMultipleCustomers" style="display: none">
    <div class="col-md-12">
        <div class="form-group">
            <label class="form-control-label" for="formSelectCustomers">Trabajar solo con...</label>
            <select class="js-customer-select-multiple" id="formSelectCustomers" name="customers[]" multiple="multiple">
                @foreach($customers as $customer)
                    <option value="{{$customer->id}}"
                            @if(isset($customerIDSUser) && in_array($customer->id,$customerIDSUser))
                            selected="selected"
                        @endif>
                        {{$customer->name}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="custom-control custom-checkbox mb-3">
            <input class="custom-control-input can_check_all"
                   id="checkByAll"
                   name="can_be_check_all"
                   type="checkbox"
            />
            <label class="custom-control-label"
                   for="checkByAll">
                Monitoreado por admin y/o supervisores
            </label>
        </div>
    </div>
</div>
<div class="row" id="selectMultipleAdminORSuper" style="display: none">
    <div class="col-md-12">
        <div class="form-group">
            <label class="form-control-label" for="exampleFormControlSelect3">Monitoreado solo por...</label>
            <select class="js-admin-super-multiple" id="exampleFormControlSelect3" name="superviseBy[]" multiple="multiple">
                @foreach($users as $user)
                    <option value="{{$user['id']}}"
                            @if(isset($superviseIDSUser) && in_array($user['id'],$superviseIDSUser))
                            selected="selected"
                            @endif>
                        {{$user['name']}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

@unless ($model->name)
    <span class="h5 text-muted">
        <i class="ni ni-email-83  mr-2"></i> Le enviaremos un correo con sus credenciales.
    </span>
    <br>
@endunless
@if ($model->password_plain)
    <div class="form-group">
        <label class="form-control-label">Contraseña</label>
        <input type="text" class="form-control" value="{{$model->password_plain}}">
    </div>
@endif
<br>
<button class="btn btn-primary" type="submit">Guardar</button>
<a href="{{ $back }}" class="btn btn-secondary" type="submit">Regresar</a>
