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
    data-category="{{ $item->category->category_name ?? '-' }}"
    data-brand="{{ $item->brand }}"
    data-model="{{ $item->model_number }}"
    data-serial="{{ $item->serial_number }}"
    data-purchase_date="{{ $item->purchase_date }}"
    data-cost="{{ $item->purchase_cost }}"
    data-vendor="{{ $item->vendor_name }}"
    data-invoice="{{ $item->invoice_number }}"
    data-warranty="{{ $item->warranty_expiry }}"
    data-qty="{{ $item->quantity }}"
    data-stock="{{ $item->available_stock }}"
    data-status="{{ $item->status }}"
    data-desc="{{ $item->description }}"
    data-remarks="{{ $item->remarks }}"
    data-image="{{ asset('inventory_images/'.$item->item_image) }}"
    data-doc="{{ asset('inventory_docs/'.$item->document_file) }}"
    data-bs-toggle="modal"
    data-bs-target="#billModal">
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

<div class="modal fade" id="billModal">
<div class="modal-dialog modal-xl">
<div class="modal-content border-0 shadow-lg rounded-4 p-4">

<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
    <div>
        <h4 class="fw-bold mb-0">Inventory Invoice</h4>
        <small class="text-muted">System Generated</small>
    </div>
    <span class="badge bg-success px-3 py-2" id="b_status"></span>
</div>

<div class="row">

<!-- LEFT SIDE -->
<div class="col-md-8">

    <!-- BASIC -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-3">
        <h6 class="fw-bold mb-3 text-primary">Item Info</h6>

        <div class="row">
            <div class="col-md-6">
                <p><b>Name:</b> <span id="b_name"></span></p>
                <p><b>Code:</b> <span id="b_code"></span></p>
                <p><b>Category:</b> <span id="b_category"></span></p>
            </div>

            <div class="col-md-6">
                <p><b>Brand:</b> <span id="b_brand"></span></p>
                <p><b>Model:</b> <span id="b_model"></span></p>
                <p><b>Serial:</b> <span id="b_serial"></span></p>
            </div>
        </div>
    </div>

    <!-- PURCHASE -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-3">
        <h6 class="fw-bold mb-3 text-primary">Purchase Details</h6>

        <div class="row">
            <div class="col-md-4"><b>Date:</b> <span id="b_purchase_date"></span></div>
            <div class="col-md-4"><b>Vendor:</b> <span id="b_vendor"></span></div>
            <div class="col-md-4"><b>Invoice:</b> <span id="b_invoice"></span></div>
        </div>
    </div>

    <!-- STOCK TABLE -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-3">
        <h6 class="fw-bold mb-3 text-primary">Stock</h6>

        <table class="table table-borderless text-center align-middle">
            <thead class="bg-light rounded">
                <tr>
                    <th>Qty</th>
                    <th>Available</th>
                    <th>Cost (₹)</th>
                </tr>
            </thead>
            <tbody>
                <tr class="fw-bold">
                    <td id="b_qty"></td>
                    <td id="b_stock"></td>
                    <td>₹ <span id="b_cost"></span></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- DESCRIPTION -->
    <div class="card border-0 shadow-sm rounded-4 p-3">
        <h6 class="fw-bold text-primary">Notes</h6>
        <p id="b_desc" class="mb-2"></p>
        <p id="b_remarks" class="text-muted"></p>
    </div>

</div>

<!-- RIGHT SIDE -->
<div class="col-md-4">

    <!-- IMAGE -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-3 text-center">
        <h6 class="fw-bold text-primary mb-2">Item Image</h6>
        <img id="b_image" class="img-fluid rounded-3" style="max-height:200px; object-fit:cover;">
    </div>

    <!-- DOCUMENT -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-3 text-center">
        <h6 class="fw-bold text-primary mb-2">Document</h6>

        <a id="b_doc" target="_blank" class="btn btn-outline-primary btn-sm">
            View Document
        </a>
    </div>

    <!-- SUMMARY -->
    <div class="card border-0 shadow-sm rounded-4 p-3 text-center bg-light">
        <h6 class="fw-bold text-primary">Total</h6>
        <h3 class="fw-bold">₹ <span id="b_cost_total"></span></h3>
        <small>Warranty till <span id="b_warranty"></span></small>
    </div>

</div>

</div>

<!-- FOOTER -->
<div class="text-end mt-4">
    <button onclick="window.print()" class="btn btn-success">
        🖨 Print
    </button>
</div>

</div>
</div>
</div>

<script>
// VIEW

function formatINR(value){
    return new Intl.NumberFormat('en-IN').format(value);
}
$(document).on('click','.view-btn',function(){

    $('#b_name').text($(this).data('name'));
    $('#b_code').text($(this).data('code'));
    $('#b_category').text($(this).data('category'));
    $('#b_status').text($(this).data('status'));

    $('#b_brand').text($(this).data('brand'));
    $('#b_model').text($(this).data('model'));
    $('#b_serial').text($(this).data('serial'));

    $('#b_purchase_date').text($(this).data('purchase_date'));
    $('#b_vendor').text($(this).data('vendor'));
    $('#b_invoice').text($(this).data('invoice'));

    $('#b_qty').text($(this).data('qty'));
    $('#b_stock').text($(this).data('stock'));
  let cost = $(this).data('cost');

$('#b_cost').text(formatINR(cost));


    $('#b_warranty').text($(this).data('warranty'));

    $('#b_desc').text($(this).data('desc'));
    $('#b_remarks').text($(this).data('remarks'));

  $('#b_cost_total').text(formatINR(cost));

    // IMAGE
    $('#b_image').attr('src', $(this).data('image'));

    // DOCUMENT
    let doc = $(this).data('doc');

    if(doc){
        $('#b_doc').attr('href', doc).show();
    }else{
        $('#b_doc').hide();
    }

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
