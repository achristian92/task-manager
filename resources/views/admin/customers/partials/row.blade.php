<tr>
    <th scope="row">
        <div class="media align-items-center">
            <a href="#" class="avatar rounded-circle mr-3">
                <img alt="Image placeholder" src="{{ $customer->src_logo }}">
            </a>
            <div class="media-body">
                <span class="name mb-0 text-sm">{{ $customer->name }}</span>
                <h6 class="mb-0 text-muted">
                    @if ($customer->contact_name)
                        <i class="far fa-user ml-1"></i> {{ $customer->contact_name }}
                    @endif
                    @if ($customer->contact_telephone)
                        <i class="fas fa-phone-alt ml-1"></i> {{ $customer->contact_telephone }}
                    @endif
                    @if ($customer->contact_email)
                        <i class="far fa-envelope ml-1"></i> {{ $customer->contact_email }}
                    @endif
                </h6>
            </div>
        </div>
    </th>
    <td>{{ $customer->ruc }}</td>
    <td>@include('components.status', ['is_active' => $customer->is_active])</td>
    <td class="text-right">
        @include('components.row-show', ['route' => route('admin.customers.show',$customer->id)])
        @include('components.row-edit', ['route' => route('admin.customers.edit',$customer->id)])
        <div class="dropdown">
            <a class="btn btn-sm btn-icon-only text-light ml-1" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-ellipsis-v"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="dropdown-item" onClick="javascript: return confirm('¿Estas seguro de elimarlo?');">
                        <i class="fas fa-trash-alt text-danger"></i>
                        <span>Eliminar</span>
                    </button>
                </form>
            </div>
        </div>
    </td>
</tr>
