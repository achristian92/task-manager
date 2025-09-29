    <tr>
        <th scope="row">
            <div class="media align-items-center">
                <a href="#" class="avatar rounded-circle mr-3">
                    <img alt="Image placeholder" src="{{ $user->urlImg() }}">
                </a>
                <div class="media-body">
                    <span class="name mb-0 text-sm">{{$user->full_name }}</span>
                    <h6 class="mb-0 text-muted">{{ $user->lastLogin() }}</h6>
                </div>
            </div>
        </th>
        <td>{{$user->email}}</td>
        <td>{{ $user->roles->pluck('name')->implode(' | ') }}</td>
        <td class="text-center">{{ $user->clients->count() }}</td>
        <td>
            @include('components.status',[ 'is_active' => $user->is_active ])
        </td>
        <td class="text-right">
            @include('components.row-edit', ['route' => route('admin.users.edit',$user->id)])

            <div class="dropdown">
                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                    <a class="dropdown-item" href="{{ route('admin.users.credentials',$user->id) }}">
                        <i class="ni ni-send text-primary"></i>
                        <span>Enviar credenciales</span>
                    </a>

                    @unless ( Illuminate\Support\Facades\Auth::id() == $user->id)
                        @if($user->is_active)
                            <a class="dropdown-item" href="{{ route('admin.users.disable',$user->id) }}">
                                <i class="ni ni-button-power text-danger"></i>
                                <span>Desactivar</span>
                            </a>
                        @else
                            <a class="dropdown-item" href="{{ route('admin.users.enable',$user->id) }}">
                                <i class="ni ni-button-power text-success"></i>
                                <span>Activar</span>
                            </a>
                        @endif
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item" onClick="javascript: return confirm('¿Estas seguro de elimarlo?');">
                                <i class="fas fa-trash-alt text-danger"></i>
                                <span>Eliminar</span>
                            </button>
                        </form>
                    @endunless
{{--                    @if ($userv2->isAdmin())--}}
{{--                        <a class="dropdown-item" href="{{ route('admin.users.document',$user->id) }}">--}}
{{--                            <i class="ni ni-folder-17 text-primary"></i>--}}
{{--                            <span>Documentos</span>--}}
{{--                        </a>--}}
{{--                        <a class="dropdown-item" href="{{ route('admin.users.history',$user->id) }}">--}}
{{--                            <i class="ni ni-collection text-primary"></i>--}}
{{--                            <span>Historial</span>--}}
{{--                        </a>--}}
{{--                    @endif--}}
                </div>
            </div>
        </td>
    </tr>

