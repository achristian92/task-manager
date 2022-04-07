<input type="text" id="inputCanSendCredentials" value="{{$model->send_credentials ? "1" : "0"}}" hidden>
<input type="text" id="inputNotifyOverdueAct" value="{{$model->send_overdue ? "1" : "0"}}" hidden>
<input type="text" id="inputNotifyDeadline" value="{{$model->notify_deadline ? "1" : "0"}}" hidden>

<div class="form-group row">
    <label class="col-sm-2 col-form-label">Empresa</label>
    <div class="col-sm-10">
        @include('components.form.text', ['name' => 'name'])
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label">Dirección</label>
    <div class="col-sm-10">
        @include('components.form.text', ['name' => 'address'])
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label">Ruc</label>
    <div class="col-sm-4">
        @include('components.form.text', ['name' => 'ruc'])
    </div>
    <label class="col-sm-4 col-form-label">Horas de trabajo (mes)</label>
    <div class="col-sm-2">
        @include('components.form.text', ['name' => 'hours'])
    </div>
</div>
<div class="form-check">
    <input type="checkbox"
           class="form-check-input"
           id="senCredentialUser"
           name="send_credentials"
           value="{{ $model->send_credentials}}"
    />
    <label class="form-check-label" for="senCredentialUser">Enviar credenciales a los usuarios</label>
</div>
<div class="form-check">
    <input type="checkbox"
           class="form-check-input"
           id="sendOverdue"
           name="send_overdue"
           value="{{ $model->send_overdue }}"
    />
    <label class="form-check-label" for="sendOverdue">Notificar actividades no realizadas</label>
</div>
<div class="form-check">
    <input type="checkbox"
           class="form-check-input"
           id="sendDeadline"
           name="notify_deadline"
           value="{{ $model->notify_deadline }}"
    />
    <label class="form-check-label" for="sendDeadline">Notificar actividades con fecha límite</label>
</div>
<br>
<div class="form-group row">
    <label class="col-sm-2 col-form-label">Logo</label>
    <div class="col-sm-10">
        <input type="file" class="form-control-file" name="attachment_image" id="image">
    </div>
</div>
<div class="row">
    <button class="btn btn-primary ml-2" type="submit">Actualizar</button>
</div>
