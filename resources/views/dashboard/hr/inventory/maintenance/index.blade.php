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
            <option value="repair" {{ request('type')=='repair'?'selected':'' }}>Repair</option>
            <option value="service" {{ request('type')=='service'?'selected':'' }}>Service</option>
        </select>
    </div>

    <div class="col-md-2">
        <select name="status" class="form-control">
            <option value="">All Status</option>
            <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
            <option value="completed" {{ request('status')=='completed'?'selected':'' }}>Completed</option>
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
                                <th class="">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($maintenances as $index => $m)
                            <tr>
                               <td>{{ $maintenances->firstItem() + $index }}</td>
                                <td>{{ $m->item->item_name ?? '-' }}</td>
                                <td>{{ $m->maintenance_type }}</td>
                                <td>
                                    <span class="badge p-2 {{ $m->status == 'completed' ? 'bg-success':'bg-warning' }}">
                                        {{ $m->status }}
                                    </span>
                                </td>
                                <td>{{ $m->cost ?? '-' }}</td>
                                <td>{{ $m->start_date }}</td>

                                <td class="">
                                    <button class="btn btn-sm btn-info view-btn"
                                            data-item="{{ $m->item->item_name ?? '-' }}"
                                            data-type="{{ $m->maintenance_type }}"
                                            data-desc="{{ $m->issue_description }}"
                                            data-status="{{ $m->status }}"
                                            data-cost="{{ $m->cost }}"
                                            data-vendor="{{ $m->vendor_name }}"
                                            data-date="{{ $m->start_date }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#viewModal">
                                        <i class="bi bi-eye"></i>
                                    </button>

                                    @if($m->status == 'pending')
                                    <button class="btn btn-sm btn-success complete-btn"
                                            data-id="{{ $m->id }}">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
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
    <div class="modal fade" id="viewModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5>Maintenance Details</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p><b>Item:</b> <span id="v_item"></span></p>
                    <p><b>Type:</b> <span id="v_type"></span></p>
                    <p><b>Description:</b> <span id="v_desc"></span></p>
                    <p><b>Status:</b> <span id="v_status"></span></p>
                    <p><b>Cost:</b> <span id="v_cost"></span></p>
                    <p><b>Vendor:</b> <span id="v_vendor"></span></p>
                    <p><b>Date:</b> <span id="v_date"></span></p>
                </div>

            </div>
        </div>
    </div>

    <script>
        // VIEW
        $(document).on('click','.view-btn',function(){
            $('#v_item').text($(this).data('item'));
            $('#v_type').text($(this).data('type'));
            $('#v_desc').text($(this).data('desc'));
            $('#v_status').text($(this).data('status'));
            $('#v_cost').text($(this).data('cost'));
            $('#v_vendor').text($(this).data('vendor'));
            $('#v_date').text($(this).data('date'));
        });

        // COMPLETE
      $(document).on('click','.complete-btn',function(){

    let id = $(this).data('id');

    Swal.fire({
        title:'Complete Maintenance?',
        icon:'question',
        showCancelButton:true
    }).then((result)=>{

        if(result.isConfirmed){

            let url = "{{ route('inventory.maintenance.complete', ['id'=>'ID']) }}";
            url = url.replace('ID', id);

            $.post(url,{
                _token:'{{ csrf_token() }}'
            },function(res){

                if(res.status){
                    Swal.fire('Done','Completed','success').then(()=>{
                        location.reload();
                    });
                }else{
                    Swal.fire('Error',res.message,'error');
                }

            });

        }

    });

});
    </script>

</x-layout>
