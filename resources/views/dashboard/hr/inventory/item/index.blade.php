<x-layout>
    @section('title', 'Inventory Items')

    <div class="container-fluid p-4">

        <!-- Header -->
       <!-- Add this button next to the "Add Item" button -->
<div class="d-flex justify-content-between mb-4">
    <h3>Inventory Items</h3>
    <div>
        <button type="button" class="btn btn-success me-2" id="exportInventoryBtn">
            <i class="bi bi-file-pdf"></i> Export PDF
        </button>
        <a href="{{ route('inventory.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add Item
        </a>
    </div>
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
  <select name="item_type" class="form-control">
    <option value="">All</option>
    <option value="0" {{ request('item_type')==='0' ? 'selected':'' }}>New</option>
    <option value="1" {{ request('item_type')==='1' ? 'selected':'' }}>Refurbished</option>
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
        <input type="hidden" name="item_type" value="{{ request('item_type') }}">



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
                            <th>Item Type</th>
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
     class="rounded-circle item-img"
     style="object-fit: cover;"
     onerror="this.onerror=null; this.src='/images/placeholder.jpg';">
</td>

                            <td>{{ $item->item_name }}</td>
                            <td>{{ $item->item_code }}</td>
                          @php
$colors = ['bg-primary','bg-success','bg-warning text-dark','bg-info','bg-danger','bg-dark'];

$catId = $item->category->id ?? 0;
$color = $colors[$catId % count($colors)];
@endphp

<td>
    <span class="badge p-2 {{ $color }}">
        {{ $item->category->category_name ?? '-' }}
    </span>
</td>
    <td>
    <span class="badge p-2
        {{ $item->item_type == 1 ? 'bg-info' : 'bg-primary' }}">
        {{ $item->item_type == 1 ? 'Refurbished' : 'New' }}
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
    data-item_type="{{ $item->item_type }}"
    data-desc="{{ $item->description }}"
    data-remarks="{{ $item->remarks }}"
data-image="{{
    (!empty($item->item_image) && file_exists(public_path('inventory_images/'.$item->item_image)))
    ? asset('inventory_images/'.$item->item_image)
    : asset('images/placeholder.jpg')
}}"
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
                    <th>Cost (₹)</th>
                </tr>
            </thead>
            <tbody>
                <tr class="fw-bold">
                    <td id="b_qty"></td>
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
    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
</div>

</div>
</div>
</div>







<!-- Export Inventory Modal -->
<div class="modal fade" id="exportInventoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Inventory Report (PDF)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Export Type - Multiple Categories / All -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Export Type</label>
                    <div class="d-flex gap-4 mt-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="export_type" id="exportAllRadio" value="all" checked>
                            <label class="form-check-label" for="exportAllRadio">All Items</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="export_type" id="exportCategoryRadio" value="category">
                            <label class="form-check-label" for="exportCategoryRadio">By Category</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="export_type" id="exportItemTypeRadio" value="item_type">
                            <label class="form-check-label" for="exportItemTypeRadio">By Item Type</label>
                        </div>
                    </div>
                </div>

                <!-- Categories Dropdown (shown when Category is selected) -->
                <div class="mb-3 d-none" id="exportCategoryDiv">
                    <label class="form-label fw-bold">Categories</label>
                    <div class="dropdown w-100">
                        <button class="form-select text-start" type="button" id="exportCategoryDropdownBtn" data-bs-toggle="dropdown" aria-expanded="false" style="background-color: white; text-align: left;">
                            <span id="exportCategoryText">Select Categories</span>
                        </button>
                        <ul class="dropdown-menu p-2 w-100" aria-labelledby="exportCategoryDropdownBtn" style="max-height: 300px; overflow-y: auto;">
                            <li>
                                <input type="text" class="form-control form-control-sm mb-2" placeholder="Search categories..." id="exportCategorySearchInput">
                            </li>
                            <li class="d-flex justify-content-between px-2 mb-2">
                                <button type="button" class="btn btn-sm btn-outline-primary" id="exportSelectAllCategories">Select All</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="exportDeselectAllCategories">Deselect All</button>
                            </li>
                            <li>
                                <select id="exportCategorySelectList" class="form-select form-select-sm" size="6" multiple style="border: none;">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Item Type Dropdown (shown when Item Type is selected) -->
                <div class="mb-3 d-none" id="exportItemTypeDiv">
                    <label class="form-label fw-bold">Item Type</label>
                    <select id="exportItemTypeSelect" class="form-select">
                        <option value="">Select Item Type</option>
                        <option value="new">New</option>
                        <option value="refurbished">Refurbished</option>
                    </select>
                </div>

                <!-- Sort By Option -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Sort By</label>
                    <select id="exportSortBy" class="form-select">
                        <option value="item_name">Item Name</option>
                        <option value="item_code">Item Code</option>
                        <option value="category">Category</option>
                        <option value="purchase_date">Purchase Date</option>
                        <option value="purchase_cost">Cost</option>
                    </select>
                </div>

                <!-- Sort Order -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Sort Order</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sort_order" id="sortAsc" value="asc" checked>
                            <label class="form-check-label" for="sortAsc">Ascending</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sort_order" id="sortDesc" value="desc">
                            <label class="form-check-label" for="sortDesc">Descending</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="exportFinalConfirmBtn">Generate PDF</button>
            </div>
        </div>
    </div>
</div>




<script>
// ============================================
// INVENTORY PDF EXPORT MODAL
// ============================================
(function() {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initExportModal);
    } else {
        initExportModal();
    }

    function initExportModal() {
        // Get DOM elements
        const exportAllRadio = document.getElementById('exportAllRadio');
        const exportCategoryRadio = document.getElementById('exportCategoryRadio');
        const exportItemTypeRadio = document.getElementById('exportItemTypeRadio');
        const exportCategoryDiv = document.getElementById('exportCategoryDiv');
        const exportItemTypeDiv = document.getElementById('exportItemTypeDiv');
        const exportBtn = document.getElementById('exportInventoryBtn');
        const confirmBtn = document.getElementById('exportFinalConfirmBtn');

        // Category dropdown elements
        const exportCategorySelect = document.getElementById('exportCategorySelectList');
        const exportCategoryText = document.getElementById('exportCategoryText');
        const exportCategorySearch = document.getElementById('exportCategorySearchInput');
        const exportSelectAllCategories = document.getElementById('exportSelectAllCategories');
        const exportDeselectAllCategories = document.getElementById('exportDeselectAllCategories');

        // Item type select
        const exportItemTypeSelect = document.getElementById('exportItemTypeSelect');

        // Toggle visibility based on export type
        if (exportAllRadio && exportCategoryRadio && exportItemTypeRadio) {
            exportAllRadio.addEventListener('change', function() {
                if (this.checked) {
                    exportCategoryDiv.classList.add('d-none');
                    exportItemTypeDiv.classList.add('d-none');
                }
            });

            exportCategoryRadio.addEventListener('change', function() {
                if (this.checked) {
                    exportCategoryDiv.classList.remove('d-none');
                    exportItemTypeDiv.classList.add('d-none');
                }
            });

            exportItemTypeRadio.addEventListener('change', function() {
                if (this.checked) {
                    exportCategoryDiv.classList.add('d-none');
                    exportItemTypeDiv.classList.remove('d-none');
                }
            });
        }

        // Update category button text
        function updateExportCategoryText() {
            if (!exportCategorySelect) return;
            const selected = Array.from(exportCategorySelect.selectedOptions);

            if (selected.length === 0) {
                exportCategoryText.textContent = 'Select Categories';
            } else if (selected.length === 1) {
                exportCategoryText.textContent = selected[0].textContent;
            } else {
                exportCategoryText.textContent = selected.length + ' categories selected';
            }
        }

        // Search categories
        if (exportCategorySearch && exportCategorySelect) {
            exportCategorySearch.addEventListener('input', function() {
                const term = this.value.toLowerCase();
                const options = exportCategorySelect.options;
                for (let i = 0; i < options.length; i++) {
                    const text = options[i].textContent.toLowerCase();
                    options[i].style.display = text.includes(term) ? '' : 'none';
                }
            });
        }

        // Select all categories
        if (exportSelectAllCategories && exportCategorySelect) {
            exportSelectAllCategories.addEventListener('click', function(e) {
                e.preventDefault();
                for (let i = 0; i < exportCategorySelect.options.length; i++) {
                    exportCategorySelect.options[i].selected = true;
                }
                updateExportCategoryText();
            });
        }

        // Deselect all categories
        if (exportDeselectAllCategories && exportCategorySelect) {
            exportDeselectAllCategories.addEventListener('click', function(e) {
                e.preventDefault();
                for (let i = 0; i < exportCategorySelect.options.length; i++) {
                    exportCategorySelect.options[i].selected = false;
                }
                updateExportCategoryText();
            });
        }

        if (exportCategorySelect) {
            exportCategorySelect.addEventListener('change', updateExportCategoryText);
        }

        // Open modal button
        if (exportBtn) {
            exportBtn.addEventListener('click', function() {
                const exportModal = new bootstrap.Modal(document.getElementById('exportInventoryModal'));
                exportModal.show();
            });
        }

        // Reset modal when opened
        const exportModalElement = document.getElementById('exportInventoryModal');
        if (exportModalElement) {
            exportModalElement.addEventListener('show.bs.modal', function() {
                // Reset to default
                if (exportAllRadio) exportAllRadio.checked = true;
                if (exportCategoryDiv) exportCategoryDiv.classList.add('d-none');
                if (exportItemTypeDiv) exportItemTypeDiv.classList.add('d-none');

                // Reset category selection
                if (exportCategorySelect) {
                    for (let i = 0; i < exportCategorySelect.options.length; i++) {
                        exportCategorySelect.options[i].selected = false;
                    }
                    updateExportCategoryText();
                }

                // Reset item type
                if (exportItemTypeSelect) exportItemTypeSelect.value = '';

                // Reset search
                if (exportCategorySearch) exportCategorySearch.value = '';

                // Reset sort order
                const sortAsc = document.getElementById('sortAsc');
                if (sortAsc) sortAsc.checked = true;

                const sortBy = document.getElementById('exportSortBy');
                if (sortBy) sortBy.value = 'item_name';
            });
        }

        // Generate PDF on confirm
        if (confirmBtn) {
            const newConfirmBtn = confirmBtn.cloneNode(true);
            confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);

            newConfirmBtn.addEventListener('click', function() {
                const exportType = document.querySelector('input[name="export_type"]:checked').value;
                let params = new URLSearchParams();

                params.append('export_type', exportType);

                // Category filter
                if (exportType === 'category' && exportCategorySelect) {
                    const selectedCategories = Array.from(exportCategorySelect.selectedOptions).map(opt => opt.value);
                    if (selectedCategories.length > 0) {
                        selectedCategories.forEach(id => {
                            params.append('category_ids[]', id);
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            text: 'Please select at least one category',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        return;
                    }
                }

                // Item type filter
                if (exportType === 'item_type' && exportItemTypeSelect) {
                    const itemType = exportItemTypeSelect.value;
                    if (!itemType) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            text: 'Please select an item type',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        return;
                    }
                    params.append('item_type', itemType);
                }

                // Sort by
                const sortBy = document.getElementById('exportSortBy');
                if (sortBy) {
                    params.append('sort_by', sortBy.value);
                }

                // Sort order
                const sortOrder = document.querySelector('input[name="sort_order"]:checked');
                if (sortOrder) {
                    params.append('sort_order', sortOrder.value);
                }

                // Close modal
                const exportModal = bootstrap.Modal.getInstance(document.getElementById('exportInventoryModal'));
                if (exportModal) exportModal.hide();

                // Download PDF
                window.location.href = "{{ route('inventory.export-pdf') }}?" + params.toString();
            });
        }

        // Initialize category text
        updateExportCategoryText();
    }
})();
</script>





<script>
// VIEW

function formatINR(value){
    return new Intl.NumberFormat('en-IN').format(value);
}
$(document).on('click','.view-btn',function(){

    $('#b_name').text($(this).data('name'));
    $('#b_code').text($(this).data('code'));
    $('#b_category').text($(this).data('category'));


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
