@csrf
<h3>Empresa</h3>
<div class="row">
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-control-label">Nombre</label>
                    @include('components.form.text',['name' => 'name'])
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-control-label">Ruc</label>
                    @include('components.form.text',['name' => 'ruc'])
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-9">
                <div class="form-group">
                    <label class="form-control-label">Dirección</label>
                    @include('components.form.text',['name' => 'address'])
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-control-label">Máx. horas mensual(ej: 01:20)</label>
                    @include('components.form.text',['name' => 'hours'])
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-12">
                <label class="form-control-label">Link reseña</label>
                @include('components.form.text',['name' => 'review_link'])
            </div>
        </div>
        <h3>Contacto</h3>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-control-label">Correo</label>
                    @include('components.form.email', ['name' => 'contact_email'])
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-control-label">Nombre</label>
                    @include('components.form.text',['name' => 'contact_name'])
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-control-label">Número teléfono</label>
                    @include('components.form.text',['name' => 'contact_telephone'])
                </div>
            </div>
        </div>
        <div class="form-check">
            <input type="hidden" name="limit_notify" value="0">
            <input type="checkbox"
                   class="form-check-input"
                   id="notifyLimitHours"
                   name="limit_notify"
                   value="1" {{ $model->limit_notify || old('limit_notify',0) === 1 ? 'checked' : '' }}
            />
            <label class="form-check-label" for="notifyLimitHours">Notificar exceso de horas mensuales</label>
        </div>
        <div class="form-check">
            <input type="hidden" name="limit_hours" value="0">
            <input type="checkbox"
                   class="form-check-input"
                   id="limitHours"
                   name="limit_hours"
                   value="1" {{ $model->limit_hours || old('limit_hours',0) === 1 ? 'checked' : '' }}
            />
            <label class="form-check-label" for="limitHours">Limitar actividades por horas mensuales</label>
        </div>

        <br>
    </div>
    <div class="col md-4">
        <div class="form-group">
            <label for="image">Seleccionar un logo (opcional)</label>
            <div class="card" style="width: 7rem;">
                <img class="card-img-top" src="{{ $model->src_logo }}" alt="Card image cap" >
            </div>
            <br>
            <input type="file" class="form-control-file" name="attachment_image" id="image">
        </div>
    </div>
</div>

<button class="btn btn-primary" type="submit">Guardar</button>
<a href="{{ $back }}" class="btn btn-secondary" type="submit">Regresar</a>
