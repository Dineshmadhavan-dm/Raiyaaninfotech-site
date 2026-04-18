<x-layout>
    @section('title', 'Inventory Assignments')

    <div class="container-fluid p-4">

        <!-- Header -->
        <div class="d-flex justify-content-between mb-4">
            <h3>Inventory Assignments</h3>

            <a href="{{ route('inventory.assignments.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Assign Item
            </a>
        </div>
<form method="GET" class="row mb-3">

    <div class="col-md-3">
        <input type="text" name="item" value="{{ request('item') }}"
               class="form-control" placeholder="Item Name">
    </div>

    <div class="col-md-3">
        <input type="text" name="employee" value="{{ request('employee') }}"
               class="form-control" placeholder="Employee Name">
    </div>

    <div class="col-md-2">
        <select name="status" class="form-control">
            <option value="">All Status</option>
            <option value="assigned" {{ request('status')=='assigned'?'selected':'' }}>Assigned</option>
            <option value="returned" {{ request('status')=='returned'?'selected':'' }}>Returned</option>
        </select>
    </div>

    <div class="col-md-4">
        <button class="btn btn-primary">Filter</button>
        <a href="{{ route('inventory.assignments.index') }}" class="btn btn-secondary">Reset</a>
    </div>

</form>
        <!-- Table -->
        <div class="card shadow-sm">
            <div class="card-body">

<div class="d-flex  mb-3">

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
    <input type="hidden" name="employee" value="{{ request('employee') }}">
    <input type="hidden" name="status" value="{{ request('status') }}">

</form></div>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Item</th>
                            <th>Department</th>
                            <th>Employee</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th class="">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($assignments as $i => $a)
                        <tr>
                          <td>{{ $assignments->firstItem() + $i }}</td>
                            <td>{{ $a->item->item_name ?? '-' }}</td>
                             <td>{{ $a->department->dep_name ?? '-' }}</td>
                            <td>{{ $a->employee->fullname ?? '-' }}</td>

                            <td>
                                <span class="badge p-2 {{ $a->status == 'assigned' ? 'bg-warning':'bg-success' }}">
                                    {{ $a->status }}
                                </span>
                            </td>

                          <td>
{{ $a->assigned_date ? \Carbon\Carbon::parse($a->assigned_date)->format('d-m-Y') : '-' }}
</td>

                            <td class="">

                                <!-- VIEW -->
                               <button class="btn btn-sm btn-info view-btn"

    data-item="{{ $a->item->item_name }}"
    data-code="{{ $a->item->item_code }}"
    data-image="{{ asset('inventory_images/'.$a->item->item_image) }}"

    data-dept="{{ $a->department->dep_name }}"
    data-emp="{{ $a->employee->fullname }}"

    data-status="{{ $a->status }}"
  data-date="{{ \Carbon\Carbon::parse($a->assigned_date)->format('d-m-Y') }}"
data-return="{{ $a->return_date ? \Carbon\Carbon::parse($a->return_date)->format('d-m-Y') : '' }}"
    data-remarks="{{ $a->remarks }}"

    data-bs-toggle="modal"
    data-bs-target="#billModal">

    <i class="bi bi-eye"></i>
</button>

                                <!-- RETURN -->
                                @if($a->status == 'assigned')
                                <button class="btn btn-sm btn-success return-btn"
                                        data-id="{{ $a->id }}">
                                    <i class="bi bi-arrow-return-left"></i>
                                </button>
                                @endif

                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
                <div class="d-flex justify-content-between mt-3">

    <div>
        Showing {{ $assignments->firstItem() }} to {{ $assignments->lastItem() }}
        of {{ $assignments->total() }} entries
    </div>

    <div>
        {{ $assignments->links() }}
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
        <h4 class="fw-bold mb-0">Assignment Invoice</h4>
        <small class="text-muted">Inventory Tracking</small>
    </div>
    <span class="badge px-3 py-2" id="b_status"></span>
</div>

<div class="row">

<!-- LEFT -->
<div class="col-md-8">

    <!-- ITEM INFO -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-3">
        <h6 class="fw-bold text-primary mb-3">Item Details</h6>

        <div class="row">
            <div class="col-md-6">
                <p><b>Item:</b> <span id="b_item"></span></p>
                <p><b>Code:</b> <span id="b_code"></span></p>
            </div>

            <div class="col-md-6">
                <p><b>Department:</b> <span id="b_dept"></span></p>
                <p><b>Employee:</b> <span id="b_emp"></span></p>
            </div>
        </div>
    </div>

    <!-- DATES -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-3">
        <h6 class="fw-bold text-primary mb-3">Assignment Info</h6>

        <div class="row">
            <div class="col-md-6">
                <p><b>Assigned Date:</b> <span id="b_date"></span></p>
            </div>

            <div class="col-md-6">
                <p><b>Return Date:</b> <span id="b_return"></span></p>
            </div>
        </div>
    </div>

    <!-- REMARKS -->
    <div class="card border-0 shadow-sm rounded-4 p-3">
        <h6 class="fw-bold text-primary">Remarks</h6>
        <p id="b_remarks" class="text-muted"></p>
    </div>

</div>

<!-- RIGHT -->
<div class="col-md-4">

    <!-- IMAGE -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-3 text-center">
        <h6 class="fw-bold text-primary mb-2">Item Image</h6>
        <img id="b_image" class="img-fluid rounded-3" style="max-height:200px; object-fit:cover;">
    </div>

    <!-- STATUS CARD -->
    <div class="card border-0 shadow-sm rounded-4 p-3 text-center bg-light">
        <h6 class="fw-bold text-primary">Status</h6>
        <h4 id="b_status_text"></h4>
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
$(document).on('click','.view-btn',function(){

    $('#b_item').text($(this).data('item'));
    $('#b_code').text($(this).data('code'));
    $('#b_dept').text($(this).data('dept'));
    $('#b_emp').text($(this).data('emp'));

    let status = $(this).data('status');

    $('#b_status_text').text(status.toUpperCase());
    $('#b_status').text(status);

    // badge color
    if(status === 'assigned'){
        $('#b_status').removeClass().addClass('badge bg-warning px-3 py-2');
    }else{
        $('#b_status').removeClass().addClass('badge bg-success px-3 py-2');
    }

    $('#b_date').text($(this).data('date'));
    $('#b_return').text($(this).data('return') || 'Not Returned');

    $('#b_remarks').text($(this).data('remarks') || '-');

    // IMAGE
    $('#b_image').attr('src', $(this).data('image'));

});

$(document).on('click','.return-btn',function(){

    let id = $(this).data('id');

    Swal.fire({
        title:'Return Item?',
        icon:'warning',
        showCancelButton:true
    }).then((res)=>{

        if(res.isConfirmed){

            let url = "{{ route('inventory.assignments.return', ['id' => 'ID']) }}";
            url = url.replace('ID', id);

            $.post(url,{
                _token:'{{ csrf_token() }}'
            },function(resp){

                if(resp.status){
                    Swal.fire('Returned','Item returned','success').then(()=>{
                        location.reload();
                    });
                }else{
                    Swal.fire('Error',resp.message,'error');
                }

            });

        }

    });

});
</script>

</x-layout>
