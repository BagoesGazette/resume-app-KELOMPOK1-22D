@isset($edit)
    <a  href="{{ $edit }}" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
        <i class="fa fa-edit"></i>
    </a>
@endisset

@isset($destroy)
    <a class="btn btn-danger text-white" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete" onclick="Delete(this.id)" id="{{ $destroy }}"> <i class="fa fa-trash"></i></a>
@endisset