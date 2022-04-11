@include('components.btn-export',['route' => route('admin.customers.export')])
<button type="button"
        class="btn btn-sm btn-primary btn-icon"
        data-toggle="modal"
        data-target="#importModalCustomer">
    <span class="btn-inner--icon"><i class="ni ni-cloud-upload-96"></i></span>
    Importar
</button>
