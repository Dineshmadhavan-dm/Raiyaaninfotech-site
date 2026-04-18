<x-layout>
    @section('title', 'Inventory Items')

    <div class="container-fluid p-4">

        <!-- Header -->
        <div class="d-flex justify-content-between mb-4">
            <h3>Inventory Items</h3>

            <a href="{{ route('inventory.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add Item
            </a>
        </div>


        <form method="GET" action="{{ route('inventory.index') }}" class="row mb-3">

    <div class="col-md-3">
        <input type="text" name="item_name" value="{{ request('item_name') }}"
               class="form-control" placeholder="Item Name">
    </div>

    <div class="col-md-2">
        <input type="text" name="item_code" value="{{ request('item_code') }}"
               class="form-control" placeholder="Item Code">
    </div>

    <div class="col-md-2">
        <select name="category_id" class="form-control">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}"
                    {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->category_name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2">
        <select name="status" class="form-control">
            <option value="">All Status</option>
            <option value="available" {{ request('status')=='available'?'selected':'' }}>Available</option>
            <option value="assigned" {{ request('status')=='assigned'?'selected':'' }}>Assigned</option>
            <option value="damaged" {{ request('status')=='damaged'?'selected':'' }}>Damaged</option>
        </select>
    </div>

    <div class="col-md-3">
        <button class="btn btn-primary">Filter</button>
        <a href="{{ route('inventory.index') }}" class="btn btn-secondary">Reset</a>
    </div>

</form>

        <!-- Table -->
        <div class="card shadow-sm">
            <div class="card-body">


<div class="d-flex  mb-3">

    <form method="GET" action="{{ route('inventory.index') }}" class="d-flex align-items-center gap-2">

 <label class="mb-0">Show</label>

        <select name="per_page" class="form-control form-control-sm" onchange="this.form.submit()">
            <option value="5" {{ request('per_page')==5?'selected':'' }}>5</option>
            <option value="10" {{ request('per_page')==10?'selected':'' }}>10</option>
            <option value="20" {{ request('per_page')==20?'selected':'' }}>20</option>
            <option value="50" {{ request('per_page')==50?'selected':'' }}>50</option>
        </select>

        <span>entries</span>


        {{-- KEEP FILTER VALUES --}}
        <input type="hidden" name="item_name" value="{{ request('item_name') }}">
        <input type="hidden" name="item_code" value="{{ request('item_code') }}">
        <input type="hidden" name="category_id" value="{{ request('category_id') }}">
        <input type="hidden" name="status" value="{{ request('status') }}">



    </form>

</div>


                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Item Name</th>
                            <th>Item Code</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th class="">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($items as $i => $item)
                        <tr>
                          <td>{{ $items->firstItem() + $i }}</td>
                            <!-- IMAGE -->
                           <td>
    <img src="{{ asset('inventory_images/'.$item->item_image) }}"
         width="40" height="40"
         class="rounded-circle"
         style="object-fit: cover;">
</td>

                            <td>{{ $item->item_name }}</td>
                            <td>{{ $item->item_code }}</td>
                            <td>{{ $item->category->category_name ?? '-' }}</td>

                            <td>
                                <span class="badge p-2
                                    {{ $item->status == 'available' ? 'bg-success' :
                                       ($item->status == 'assigned' ? 'bg-warning' : 'bg-danger') }}">
                                    {{ $item->status }}
                                </span>
                            </td>

                            <td class="">

                                <!-- VIEW -->
                                <button class="btn btn-sm btn-info view-btn"
                                    data-name="{{ $item->item_name }}"
                                    data-code="{{ $item->item_code }}"
                                    data-status="{{ $item->status }}"
                                    data-desc="{{ $item->description }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#viewModal">
                                    <i class="bi bi-eye"></i>
                                </button>

                                <!-- EDIT -->
                                <a href="{{ route('inventory.edit',$item->id) }}"
                                   class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <!-- DELETE -->
                                <button class="btn btn-sm btn-danger delete-btn"
                                        data-id="{{ $item->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>


                <div class="d-flex justify-content-between align-items-center mt-3">

    <div>
        Showing {{ $items->firstItem() }} to {{ $items->lastItem() }}
        of {{ $items->total() }} entries
    </div>

    <div>
        {{ $items->links() }}
    </div>

</div>

            </div>
        </div>
    </div>

<!-- VIEW MODAL -->
<div class="modal fade" id="viewModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5>Item Details</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p><b>Name:</b> <span id="v_name"></span></p>
                <p><b>Code:</b> <span id="v_code"></span></p>
                <p><b>Status:</b> <span id="v_status"></span></p>
                <p><b>Description:</b> <span id="v_desc"></span></p>
            </div>

        </div>
    </div>
</div>

<script>
// VIEW
$(document).on('click','.view-btn',function(){
    $('#v_name').text($(this).data('name'));
    $('#v_code').text($(this).data('code'));
    $('#v_status').text($(this).data('status'));
    $('#v_desc').text($(this).data('desc'));
});

// DELETE
$(document).on('click','.delete-btn',function(){

    let id = $(this).data('id');

    Swal.fire({
        title:'Delete Item?',
        icon:'warning',
        showCancelButton:true
    }).then((res)=>{

        if(res.isConfirmed){

            $.ajax({
                   url: "{{ route('inventory.destroy', ':id') }}".replace(':id', id),
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
