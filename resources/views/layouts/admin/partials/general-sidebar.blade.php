<li class="nav-item">
    <a class="nav-link {{isActiveRoute(2,"dashboard")}}" href="{{route('admin.dashboard.index')}}">
        <img src="{{ asset('img/icons/dashboard.png') }}" class="mr-2" width="14px" alt="Dashboard" >
        <span class="nav-link-text">Dashboard</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{isActiveRoute(2,"customers")}}" href="{{route('admin.customers.index')}}">
        <img src="{{ asset('img/icons/Clientes.png') }}" class="mr-2" width="18px" alt="Customers" >
        <span class="nav-link-text">Clientes</span>
    </a>
</li>
{{--<li class="nav-item">--}}
{{--    <a class="nav-link {{isActiveRoute(2,"prospectuses")}}" href="{{route('admin.prospectuses.index')}}">--}}
{{--        <img src="{{ asset('img/icons/Clientes.png') }}" class="mr-2" width="18px" alt="Customers" >--}}
{{--        <span class="nav-link-text">Prospectos</span>--}}
{{--    </a>--}}
{{--</li>--}}
@if ($userCurrent->isAdmin())
    <li class="nav-item">
        <a class="nav-link {{isActiveRoute(2,"users")}}" href="{{route('admin.users.index')}}">
            <img src="{{ asset('img/icons/Usuarios.png') }}" class="mr-2" width="16px" alt="Users" >
            <span class="nav-link-text">Usuarios</span>
        </a>
    </li>
@endif
<li class="nav-item">
    <a class="nav-link {{isActiveRoute(2,"tags")}}" href="{{route('admin.tags.index')}}">
        <img src="{{ asset('img/icons/etiquetas.png') }}" class="mr-2" width="16px" alt="Tags" >
        <span class="nav-link-text">Etiquetas</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{isActiveRoute(2,"workplans")}}" href="{{route('admin.workplans.index')}}">
        <img src="{{ asset('img/icons/Planes de trabajo.png') }}" class="mr-2" width="16px" alt="Workplans" >
        <span class="nav-link-text mr-2">Planes de trabajo</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{isActiveRoute(2,"imbox")}}" href="{{route('admin.imbox.index','typeTab=today')}}" >
        <img src="{{ asset('img/icons/actividades.png') }}" class="mr-2" width="16px" alt="imbox">
        <span class="nav-link-text"> Actividades </span>
        @if ($overdue['general'] > 0)
            <span class="badge badge-pill badge-danger" style="font-size: 90%; margin-left: 10px">{{$overdue['general']}}</span>
        @endif
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{isActiveRoute(2,"tracks")}}" href="{{route('admin.tracks.index')}}">
        <img src="{{ asset('img/icons/seguimiento.png') }}" class="mr-2" width="16px" alt="customers" >
        <span class="nav-link-text">Seguimiento</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{isActiveRoute(2,"reports")}}" href="{{route('admin.reports.index')}}">
        <img src="{{ asset('img/icons/Reportes.png') }}" class="mr-2" width="14px" alt="customers" >
        <span class="nav-link-text">Reportes</span>
    </a>
</li>
{{--@if ($userCurrent->isAdmin())--}}
{{--    <li class="nav-item">--}}
{{--        <a class="nav-link {{isActiveRoute(2,"documents")}}" href="{{route('admin.documents.index')}}">--}}
{{--            <img src="{{ asset('img/icons/Documentos.png') }}" class="mr-2" width="16px" alt="customers" >--}}
{{--            <span class="nav-link-text">Documentos</span>--}}
{{--        </a>--}}
{{--    </li>--}}
{{--    <li class="nav-item">--}}
{{--        <a class="nav-link {{isActiveRoute(2,"histories")}}" href="{{route('admin.history.index')}}">--}}
{{--            <img src="{{ asset('img/icons/Historial.png') }}" class="mr-2" width="16px" alt="customers" >--}}
{{--            <span class="nav-link-text">Historial</span>--}}
{{--        </a>--}}
{{--    </li>--}}
{{--@endif--}}
