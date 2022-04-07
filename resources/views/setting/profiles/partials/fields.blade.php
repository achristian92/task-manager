<div class="form-group row">
    <label class="col-sm-2 col-form-label">Nombres</label>
    <div class="col-sm-4">
        @include('components.form.text', ['name' => 'name'])
    </div>
    <label class="col-sm-2 col-form-label">Apellidos</label>
    <div class="col-sm-4">
        @include('components.form.text', ['name' => 'last_name'])
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label">DNI</label>
    <div class="col-sm-4">
        @include('components.form.text', ['name' => 'nro_document'])
    </div>
    <label class="col-sm-2 col-form-label">Email</label>
    <div class="col-sm-4">
        @include('components.form.email', ['name' => 'email', 'readonly' => 1])
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label">Password</label>
    <div class="col-sm-10">
        <input type="password"
               name="password"
               class="form-control"
               placeholder="Nueva contraseña">
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label">Imagen</label>
    <div class="col-sm-10">
        <input type="file" class="form-control-file" name="attachment_image" id="image">
    </div>
</div>
<div class="row">
    <button class="btn btn-primary ml-2" type="submit">Actualizar</button>
</div>
