<li class="nav-item">
    <a class="nav-link {{isActiveRoute(2,"my-workplans")}}" href="{{route('user.workplans.index','view=calendar')}}">
        <img src="{{ asset('img/icons/Planes de trabajo.png') }}" class="mr-2" width="15px" alt="customers" >
        <span class="nav-link-text">Mi Planeamiento</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{isActiveRoute(2,"my-tracks")}}" href="{{route('user.tracks.index')}}">
        <img src="{{ asset('img/icons/seguimiento.png') }}" class="mr-2" width="16px" alt="customers" >
        <span class="nav-link-text">Mi Productividad</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{isActiveRoute(2,"my-imbox")}}" href="{{route('user.imbox.index','typeTab=today')}}">
        <img src="{{ asset('img/icons/actividades.png') }}" class="mr-2" width="15px" alt="customers" >
        <span class="nav-link-text">Mis Actividades</span>
        @if ($overdue['own'] > 0)
            <span class="badge badge-pill badge-danger" style="font-size: 90%; margin-left: 10px">{{$overdue['own']}}</span>
        @endif
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{isActiveRoute(2,"my-reports")}}" href="{{route('user.reports.index')}}">
        <img src="{{ asset('img/icons/Reportes.png') }}" class="mr-2" width="15px" alt="customers" >
        <span class="nav-link-text">Mis Reportes</span>
    </a>
</li>
