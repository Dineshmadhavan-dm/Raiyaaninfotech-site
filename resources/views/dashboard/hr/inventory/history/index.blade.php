<x-layout>
@section('title', 'Inventory History')

<div class="container-fluid p-4">

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
        <input type="text" name="item" value="{{ request('item') }}" class="form-control" placeholder="Item Name">
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
            <option value="available" {{ request('status')=='available'?'selected':'' }}>Available</option>
            <option value="assigned" {{ request('status')=='assigned'?'selected':'' }}>Assigned</option>
            <option value="maintenance" {{ request('status')=='maintenance'?'selected':'' }}>Maintenance</option>
        </select>
    </div>

    <div class="col-md-4">
        <button class="btn btn-primary">Filter</button>
        <a href="{{ route('inventory.history.index') }}" class="btn btn-secondary">Reset</a>
    </div>

</form>

<div class="card border-0 shadow-sm">
<div class="card-body">

<div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
    <form method="GET" class="d-flex align-items-center gap-2">
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
<th class="">View</th>
</tr>
</thead>

<tbody>
@forelse($histories as $index => $history)
<tr>
<td>{{ $histories->firstItem() + $index }}</td>
<td>{{ $history->item->item_name ?? '-' }}</td>
<td class="text-capitalize">{{ $history->action_type }}</td>
<td>
<span class="badge p-2
    {{ $history->new_status == 'available' ? 'bg-success' :
       ($history->new_status == 'assigned' ? 'bg-warning text-dark' :
       ($history->new_status == 'maintenance' ? 'bg-info' : 'bg-secondary')) }}">
    {{ $history->new_status }}
</span>
</td>
<td>{{ $history->action_date ? \Carbon\Carbon::parse($history->action_date)->format('d-m-Y') : '-' }}</td>
<td class="">
<button class="btn btn-sm btn-outline-info view-history"
data-bs-toggle="modal"
data-bs-target="#viewModal"
data-item="{{ $history->item->item_name }}"
data-action="{{ $history->action_type }}"
data-old="{{ $history->old_status }}"
data-new="{{ $history->new_status }}"
data-notes="{{ $history->notes }}"
data-date="{{ \Carbon\Carbon::parse($history->action_date)->format('d-m-Y') }}">
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
</div>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
<div>
Showing {{ $histories->firstItem() }} to {{ $histories->lastItem() }}
of {{ $histories->total() }} entries
</div>

<div>
{{ $histories->onEachSide(1)->links('pagination::bootstrap-5') }}
</div>
</div>

</div>
</div>
</div>

<div class="modal fade" id="viewModal">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content border-0 shadow-lg rounded-4 p-4">

<div class="d-flex justify-content-between border-bottom pb-3 mb-3">
<div>
<h4 class="fw-bold mb-0">History Invoice</h4>
<small class="text-muted">Inventory Activity</small>
</div>
<span class="fw-bold" id="b_action"></span>
</div>

<div class="row">

<div class="col-md-6">
<div class="card border-0 shadow-sm rounded-4 p-3 mb-3">
<h6 class="fw-bold text-primary">Item Info</h6>
<p><b>Item:</b> <span id="b_item"></span></p>
<p><b>Action:</b> <span id="b_action_text"></span></p>
</div>
</div>

<div class="col-md-6">
<div class="card border-0 shadow-sm rounded-4 p-3 mb-3">
<h6 class="fw-bold text-primary">Status Change</h6>
<p><b>Old:</b> <span id="b_old"></span></p>
<p><b>New:</b> <span id="b_new"></span></p>
</div>
</div>

<div class="col-12">
<div class="card border-0 shadow-sm rounded-4 p-3 mb-3">
<h6 class="fw-bold text-primary">Notes</h6>
<p id="b_notes"></p>
</div>
</div>

<div class="col-12">
<div class="card border-0 shadow-sm rounded-4 p-3 text-center bg-light">
<h6 class="fw-bold text-primary">Date</h6>
<h5 id="b_date"></h5>
</div>
</div>

</div>

<div class="text-end mt-3">
<button onclick="window.print()" class="btn btn-success">🖨 Print</button>
</div>

</div>
</div>
</div>

<script>
$(document).on('click','.view-history',function(){

    let action = $(this).data('action');
    let newStatus = $(this).data('new');

    $('#b_item').text($(this).data('item'));
    $('#b_action_text').text(action.charAt(0).toUpperCase() + action.slice(1));
    $('#b_old').text($(this).data('old'));
    $('#b_new').text(newStatus);
    $('#b_notes').text($(this).data('notes') || '-');
    $('#b_date').text($(this).data('date'));

    let el = $('#b_action');

    el.text(newStatus);
    el.removeClass();

    if(newStatus === 'available'){
        el.addClass('fw-bold text-success');
    }
    else if(newStatus === 'assigned'){
        el.addClass('fw-bold text-warning');
    }
    else if(newStatus === 'maintenance'){
        el.addClass('fw-bold text-info');
    }
    else{
        el.addClass('fw-bold text-secondary');
    }

});
</script>

</x-layout>
