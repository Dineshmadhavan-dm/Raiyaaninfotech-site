<x-layout>
    @section('title', 'Inventory Categories')

    <div class="container-fluid p-4">

        <!-- Header -->
        <div class="d-flex justify-content-between mb-4">
            <h3>Inventory Categories</h3>

            <a href="{{ route('inventory.categories.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add Category
            </a>
        </div>


        <form method="GET" action="{{ route('inventory.categories.index') }}" class="row mb-3">

    <div class="col-md-2">
        <input type="text" name="category_name" value="{{ request('category_name') }}"
               class="form-control" placeholder="Category Name">
    </div>



    <div class="col-md-4">
        <button class="btn btn-primary">Filter</button>
        <a href="{{ route('inventory.categories.index') }}" class="btn btn-secondary">Reset</a>
    </div>

</form>

        <!-- Table -->
        <div class="card shadow-sm">
            <div class="card-body">


        <div class="d-flex  mb-3">

            <form method="GET" action="{{ route('inventory.categories.index') }}" class="d-flex align-items-center gap-2">




                <label class="mb-0">Show</label>

                <select name="per_page" class="form-control form-control-sm" onchange="this.form.submit()">
                    <option value="5" {{ request('per_page')==5?'selected':'' }}>5</option>
                    <option value="10" {{ request('per_page')==10?'selected':'' }}>10</option>
                    <option value="20" {{ request('per_page')==20?'selected':'' }}>20</option>
                    <option value="50" {{ request('per_page')==50?'selected':'' }}>50</option>
                </select>

                <span>entries</span>

                {{-- keep filters --}}
                <input type="hidden" name="category_name" value="{{ request('category_name') }}">


            </form>

        </div>

                <table class="table table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>Category Name</th>
            <th>Description</th>
            <th class="text-end">Actions</th>
        </tr>
    </thead>

    <tbody>
        @foreach($categories as $i => $cat)
        <tr>
            <td>{{ $categories->firstItem() + $i }}</td>
            <td>{{ $cat->category_name }}</td>
            <td>{{ $cat->description ?? '-' }}</td>

            <td class="text-end">

                <a href="{{ route('inventory.categories.edit', $cat->id) }}"
                   class="btn btn-sm btn-warning">
                    <i class="bi bi-pencil"></i>
                </a>

                <button class="btn btn-sm btn-danger delete-btn"
                        data-id="{{ $cat->id }}">
                    <i class="bi bi-trash"></i>
                </button>

            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="d-flex justify-content-between align-items-center mt-3">

    <div>
        Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }}
        of {{ $categories->total() }} entries
    </div>

    <div>
        {{ $categories->links('pagination::bootstrap-5') }}
    </div>

</div>

            </div>
        </div>
    </div>

<script>
$(document).on('click','.delete-btn',function(){

    let id = $(this).data('id');

    Swal.fire({
        title:'Delete Category?',
        icon:'warning',
        showCancelButton:true
    }).then((res)=>{

        if(res.isConfirmed){

            $.ajax({
               url: "{{ route('inventory.categories.destroy', ':id') }}".replace(':id', id),
                type:'DELETE',
                data:{ _token:'{{ csrf_token() }}' },
                success:function(resp){
                    if(resp.status){
                        Swal.fire('Deleted','Success','success').then(()=>{
                            location.reload();
                        });
                    }
                }
            });

        }

    });

});
</script>

</x-layout>
