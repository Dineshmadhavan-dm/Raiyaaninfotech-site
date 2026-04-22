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
    <option value="0" {{ request('action')==='0'?'selected':'' }}>Assigned</option>
    <option value="1" {{ request('action')==='1'?'selected':'' }}>Returned</option>
    <option value="2" {{ request('action')==='2'?'selected':'' }}>Scrap</option>
    <option value="3" {{ request('action')==='3'?'selected':'' }}>Service</option>
    <option value="4" {{ request('action')==='4'?'selected':'' }}>Upgrade</option>
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
<th>Date</th>
<th class="">View</th>
</tr>
</thead>

<tbody>
@forelse($histories as $index => $history)
<tr>
<td>{{ $histories->firstItem() + $index }}</td>
<td>{{ $history->item->item_name ?? '-' }}</td>
@php
$map = [
    0 => ['Assigned','bg-warning text-dark'],
    1 => ['Returned','bg-success'],
    2 => ['Scrap','bg-danger'],
    3 => ['Service','bg-primary'],
    4 => ['Upgrade','bg-info'],
];
$action = $map[$history->action_type] ?? ['Unknown','bg-secondary'];
@endphp

<td>
    <span class="badge px-3 py-2 {{ $action[1] }}">
        {{ $action[0] }}
    </span>
</td>
<td>{{ $history->action_date ? \Carbon\Carbon::parse($history->action_date)->format('d-m-Y') : '-' }}</td>
<td class="">
<button class="btn btn-sm btn-outline-info view-history"
data-bs-toggle="modal"
data-bs-target="#viewModal"
data-item="{{ $history->item->item_name }}"
data-action="{{ $history->action_type }}"
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

</div>

<div class="row">

<div class="col-md-6">
<div class="card border-0 shadow-sm rounded-4 p-3 mb-3">

<div class="d-flex justify-content-between ">

<h6 class="fw-bold text-primary">Item Info</h6>

<span class="fw-bold" id="b_action"></span></div>

<p><b>Item:</b> <span id="b_item"></span></p>
<p><b>Action:</b> <span id="b_action_text"></span></p>
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
</div>

</div>
</div>
</div>

<script>
$(document).on('click','.view-history',function(){

    let action = $(this).data('action');

    let map = {
        0: {text:'Assigned', class:'bg-warning text-dark'},
        1: {text:'Returned', class:'bg-success'},
        2: {text:'Scrap', class:'bg-danger'},
        3: {text:'Service', class:'bg-primary'},
        4: {text:'Upgrade', class:'bg-info'}
    };

    let data = map[action] ?? {text:'Unknown', class:'bg-secondary'};

    $('#b_item').text($(this).data('item'));
    $('#b_action_text').text(data.text);
    $('#b_date').text($(this).data('date'));

    let el = $('#b_action');
    el.removeClass()
      .addClass('badge px-3 py-2 ' + data.class)
      .text(data.text);
});
</script>

</x-layout>
