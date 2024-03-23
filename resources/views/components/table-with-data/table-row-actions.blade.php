<td class="align-middle text-center">
    @if(!empty($entityId) && is_numeric($entityId) && !empty($destroyRouteName))
        <form action="{{ route($destroyRouteName, $entityId) }}" method="POST">
            @if(!empty($showRouteName))
                <a class="btn btn-info btn-sm" href="{{ route($showRouteName, $entityId) }}" style="margin-bottom: 0; padding-left: 12px; padding-right: 12px;"><i class="fas fa-eye"></i></a>
            @endif
            @if(!empty($editRouteName))
                <a class="btn btn-primary btn-sm" href="{{ route($editRouteName, $entityId) }}" style="margin-bottom: 0; padding-left: 12px; padding-right: 12px;"><i class="fas fa-edit"></i></a>
            @endif

            @csrf
            @method('DELETE')

            <button type="submit" class="btn btn-danger btn-sm" style="margin-bottom: 0; padding-left: 12px; padding-right: 12px;"><i class="fas fa-trash"></i></button>
        </form>
    @endif
</td>
