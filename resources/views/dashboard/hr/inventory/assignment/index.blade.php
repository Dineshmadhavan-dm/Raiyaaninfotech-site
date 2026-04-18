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

                            <td>{{ $a->assigned_date }}</td>

                            <td class="">

                                <!-- VIEW -->
                                <button class="btn btn-sm btn-info view-btn"
                                    data-item="{{ $a->item->item_name }}"

                                    data-emp="{{ $a->employee->fullname }}"
                                    data-status="{{ $a->status }}"
                                    data-date="{{ $a->assigned_date }}"
                                    data-return="{{ $a->return_date }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#viewModal">
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

    <!-- VIEW MODAL -->
    <div class="modal fade" id="viewModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5>Assignment Details</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p><b>Item:</b> <span id="v_item"></span></p>
                    <p><b>Employee:</b> <span id="v_emp"></span></p>
                    <p><b>Status:</b> <span id="v_status"></span></p>
                    <p><b>Assigned:</b> <span id="v_date"></span></p>
                    <p><b>Returned:</b> <span id="v_return"></span></p>
                </div>

            </div>
        </div>
    </div>

<script>
// VIEW
$(document).on('click','.view-btn',function(){
    $('#v_item').text($(this).data('item'));
    $('#v_emp').text($(this).data('emp'));
    $('#v_status').text($(this).data('status'));
    $('#v_date').text($(this).data('date'));
    $('#v_return').text($(this).data('return') || 'Not returned');
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
