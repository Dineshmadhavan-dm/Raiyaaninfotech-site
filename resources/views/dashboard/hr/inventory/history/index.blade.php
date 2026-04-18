<x-layout>
    @section('title', 'Inventory History')

    <div class="container-fluid p-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-2">Inventory History</h3>
                <nav>
                    <ol class="breadcrumb mb-0 mt-2">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dhome') }}" class="text-muted text-decoration-none">
                                Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-primary">History</li>
                    </ol>
                </nav>
            </div>
        </div>


        <form method="GET" class="row mb-3">

    <div class="col-md-3">
        <input type="text" name="item" value="{{ request('item') }}"
               class="form-control" placeholder="Item Name">
    </div>

    <div class="col-md-3">
        <select name="action" class="form-control">
            <option value="">All Action</option>
            <option value="assigned" {{ request('action')=='assigned'?'selected':'' }}>Assigned</option>
            <option value="returned" {{ request('action')=='returned'?'selected':'' }}>Returned</option>
            <option value="maintenance" {{ request('action')=='maintenance'?'selected':'' }}>Maintenance</option>
        </select>
    </div>

    <div class="col-md-2">
        <select name="status" class="form-control">
            <option value="">All Status</option>
            <option value="available">Available</option>
            <option value="assigned">Assigned</option>
            <option value="maintenance">Maintenance</option>
        </select>
    </div>

    <div class="col-md-4">
        <button class="btn btn-primary">Filter</button>
        <a href="{{ route('inventory.history.index') }}" class="btn btn-secondary">Reset</a>
    </div>

</form>
        <!-- Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body">




                <div class="d-flex mt-2 mb-2">
                    <form method="GET" class="mb-3 d-flex align-items-center gap-2">

    <label>Show</label>

    <select name="per_page" onchange="this.form.submit()" class="form-control form-control-sm">
        <option value="5" {{ request('per_page')==5?'selected':'' }}>5</option>
        <option value="10" {{ request('per_page')==10?'selected':'' }}>10</option>
        <option value="20" {{ request('per_page')==20?'selected':'' }}>20</option>
    </select>

    <span>entries</span>

    <input type="hidden" name="item" value="{{ request('item') }}">
    <input type="hidden" name="action" value="{{ request('action') }}">
    <input type="hidden" name="status" value="{{ request('status') }}">

</form>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Item</th>
                                <th>Action</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th class="text-end">View</th>
                            </tr>
                        </thead>

                        <tbody id="historyTableBody">
                           @forelse($histories as $index => $history)
<tr>
    <td>{{ $histories->firstItem() + $index }}</td>
    <td>{{ $history->item->item_name ?? '-' }}</td>
    <td class="text-capitalize">{{ $history->action_type }}</td>

    <td>
        <span class="badge bg-info">
            {{ $history->new_status }}
        </span>
    </td>

    <td>{{ \Carbon\Carbon::parse($history->action_date)->format('Y-m-d') }}</td>

    <td class="text-end">
        <button class="btn btn-sm btn-outline-info view-history"
            data-bs-toggle="modal"
            data-bs-target="#viewModal"
            data-item="{{ $history->item->item_name }}"
            data-action="{{ $history->action_type }}"
            data-old="{{ $history->old_status }}"
            data-new="{{ $history->new_status }}"
            data-notes="{{ $history->notes }}"
            data-date="{{ $history->action_date }}">
            <i class="bi bi-eye"></i>
        </button>
    </td>
</tr>

@empty
<tr>
    <td colspan="6" class="text-center text-muted">No records found</td>
</tr>
@endforelse
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-between mt-3">

    <div>
        Showing {{ $histories->firstItem() }} to {{ $histories->lastItem() }}
        of {{ $histories->total() }} entries
    </div>

    <div>
        {{ $histories->links() }}
    </div>

</div>
                </div>


            </div>
        </div>
    </div>

    <!-- VIEW MODAL -->
    <div class="modal fade" id="viewModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5>History Details</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p><strong>Item:</strong> <span id="v_item"></span></p>
                    <p><strong>Action:</strong> <span id="v_action"></span></p>
                    <p><strong>Old Status:</strong> <span id="v_old"></span></p>
                    <p><strong>New Status:</strong> <span id="v_new"></span></p>
                    <p><strong>Notes:</strong> <span id="v_notes"></span></p>
                    <p><strong>Date:</strong> <span id="v_date"></span></p>
                </div>

            </div>
        </div>
    </div>



</x-layout>
