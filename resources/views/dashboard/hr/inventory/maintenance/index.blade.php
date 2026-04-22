<x-layout>
@section('title', 'Inventory Maintenance')

<div class="container-fluid p-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3>Maintenance Management</h3>
        </div>
        <a href="{{ route('inventory.maintenance.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add Maintenance
        </a>
    </div>

    <form method="GET" class="row mb-3">
        <div class="col-md-3">
            <input type="text" name="item" value="{{ request('item') }}"
                   class="form-control" placeholder="Item Name">
        </div>

        <div class="col-md-3">
           <select name="type" class="form-control">
    <option value="">All Type</option>
    <option value="0" {{ request('type')==='0' ? 'selected' : '' }}>Scrap</option>
    <option value="1" {{ request('type')==='1' ? 'selected' : '' }}>Service</option>
    <option value="2" {{ request('type')==='2' ? 'selected' : '' }}>Upgrade</option>
</select>
        </div>

        <div class="col-md-2">
           <select name="status" class="form-control">
    <option value="">All Status</option>
    <option value="0" {{ request('status')==='0' ? 'selected' : '' }}>Pending</option>
    <option value="1" {{ request('status')==='1' ? 'selected' : '' }}>Complete</option>
</select>
        </div>

        <div class="col-md-4">
            <button class="btn btn-primary">Filter</button>
            <a href="{{ route('inventory.maintenance.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <!-- Table -->
    <div class="card shadow-sm">
        <div class="card-body">

            <div class="d-flex">
                <form method="GET" class="mb-3 d-flex align-items-center gap-2">
                    <label>Show</label>
                    <select name="per_page" onchange="this.form.submit()" class="form-control form-control-sm">
                        <option value="5" {{ request('per_page')==5?'selected':'' }}>5</option>
                        <option value="10" {{ request('per_page')==10?'selected':'' }}>10</option>
                        <option value="20" {{ request('per_page')==20?'selected':'' }}>20</option>
                    </select>
                    <span>entries</span>

                    <!-- keep filters -->
                    <input type="hidden" name="item" value="{{ request('item') }}">
                    <input type="hidden" name="type" value="{{ request('type') }}">
                    <input type="hidden" name="status" value="{{ request('status') }}">
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Item</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Cost</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($maintenances as $index => $m)
                        <tr>
                            <td>{{ $maintenances->firstItem() + $index }}</td>
                            <td>{{ $m->item->item_name ?? '-' }}</td>
 <td>
    <span class="badge p-2
        {{ $m->maintenance_type == 0 ? 'bg-danger' :
           ($m->maintenance_type == 1 ? 'bg-primary' : 'bg-info') }}">

        {{ $m->maintenance_type == 0 ? 'Scrap' :
           ($m->maintenance_type == 1 ? 'Service' : 'Upgrade') }}
    </span>
</td>
<td>
    <span class="badge p-2 {{ $m->status == 1 ? 'bg-success':'bg-warning' }}">
        {{ $m->status == 1 ? 'Complete' : 'Pending' }}
    </span>
</td>
                            <td>{{ $m->cost ? '₹ '.number_format($m->cost, 0, '.', ',') : '-' }}</td>
                            <td>{{ $m->start_date ? \Carbon\Carbon::parse($m->start_date)->format('d-m-Y') : '-' }}</td>
                            <td>
                                <!-- View Button -->
                                <button class="btn btn-sm btn-info view-btn"
                                    data-id="{{ $m->id }}"
                                    data-item="{{ $m->item->item_name ?? '-' }}"
                                  data-image="{{
    (!empty($m->item->item_image) && file_exists(public_path('inventory_images/'.$m->item->item_image)))
    ? asset('inventory_images/'.$m->item->item_image)
    : asset('images/placeholder.jpg')
}}"
                                    data-type="{{ $m->maintenance_type }}"
                                    data-desc="{{ $m->issue_description }}"
                                    data-status="{{ $m->status }}"
                                    data-cost="{{ number_format($m->cost,0,'.',',') }}"
                                    data-vendor="{{ $m->vendor_name }}"
                                    data-date="{{ $m->start_date ? \Carbon\Carbon::parse($m->start_date)->format('d-m-Y') : '' }}"
                                    data-end="{{ $m->end_date ? \Carbon\Carbon::parse($m->end_date)->format('d-m-Y') : '' }}"
                                    data-remarks="{{ $m->remarks }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#billModal">
                                    <i class="bi bi-eye"></i>
                                </button>

                                <!-- Edit Button - Only show for pending maintenance -->
                                @if($m->status == 0)
                                <a href="{{ route('inventory.maintenance.edit', $m->id) }}"
                                   class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endif


                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-between mt-3">
                    <div>
                        Showing {{ $maintenances->firstItem() }} to {{ $maintenances->lastItem() }}
                        of {{ $maintenances->total() }} entries
                    </div>
                    <div>
                        {{ $maintenances->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- VIEW MODAL -->
<div class="modal fade" id="billModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border-0 shadow-lg rounded-4 p-4">
            <!-- HEADER -->
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                <div>
                    <h4 class="fw-bold mb-0">Maintenance Invoice</h4>
                    <small class="text-muted">Inventory Maintenance</small>
                </div>

            </div>

            <div class="row">
                <!-- LEFT -->
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm rounded-4 p-3 mb-3">
                        <h6 class="fw-bold text-primary">Item Info</h6>
                        <p><b>Item:</b> <span id="b_item"></span></p>
                        <p><b>Type:</b> <span id="b_type"></span></p>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 p-3 mb-3">
                        <h6 class="fw-bold text-primary">Maintenance Details</h6>
                        <p><b>Issue:</b> <span id="b_desc"></span></p>
                        <p><b>Vendor:</b> <span id="b_vendor"></span></p>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 p-3 mb-3">
                        <h6 class="fw-bold text-primary">Dates</h6>
                        <p><b>Start:</b> <span id="b_date"></span></p>
                        <p><b>End:</b> <span id="b_end"></span></p>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 p-3">
                        <h6 class="fw-bold text-primary">Remarks</h6>
                        <p id="b_remarks"></p>
                    </div>
                </div>

                <!-- RIGHT -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 mb-3 text-center">
                        <h6 class="fw-bold text-primary">Item Image</h6>
                        <img id="b_image" class="img-fluid rounded-3" style="max-height:200px; object-fit:cover;">
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 p-3 text-center bg-light">
                        <h6 class="fw-bold text-primary">Total Cost</h6>
                        <h3>₹ <span id="b_cost"></span></h3>
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

<script>
    // VIEW BUTTON
    $(document).on('click', '.view-btn', function(){
        $('#b_item').text($(this).data('item'));
        $('#b_type').text($(this).data('type'));
        $('#b_desc').text($(this).data('desc'));
        $('#b_vendor').text($(this).data('vendor'));
        $('#b_date').text($(this).data('date'));
        $('#b_end').text($(this).data('end') || 'Not Completed');
        $('#b_cost').text($(this).data('cost'));
        $('#b_remarks').text($(this).data('remarks') || '-');
        $('#b_image').attr('src', $(this).data('image'));


    });


</script>

</x-layout>
