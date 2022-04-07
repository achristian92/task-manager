@if(isset($is_active))
    @if($is_active)
        <span class='badge badge-success'>Activo</span>
    @else
        <span class='badge badge-danger'>Inactivo</span>
    @endif
@endif
