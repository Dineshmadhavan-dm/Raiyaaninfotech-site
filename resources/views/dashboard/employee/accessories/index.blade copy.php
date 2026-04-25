<x-layout>
@section('title', 'My Accessories')

<style>
    /* Simple Corporate Styles */
    .accessory-card {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: #ffffff;
        transition: all 0.3s ease;
    }

    .accessory-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }

    .badge-assigned {
        background: #10b981;
        color: white;
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 500;
        display: inline-block;
    }

     .badge-active {
        background: #1089b9;
        color: white;
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 500;
        display: inline-block;
    }

    .item-icon-wrapper {
        width: 50px;
        height: 50px;
        background: #f3f4f6;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
    }

    .item-icon-wrapper i {
        font-size: 24px;
        color: #6b7280;
    }

    .item-title {
        font-size: 16px;
        font-weight: 600;
        color: #111827;
        margin-bottom: 4px;
    }

    .item-code {
        font-size: 11px;
        color: #6b7280;
        font-family: monospace;
        background: #f9fafb;
        padding: 2px 6px;
        border-radius: 4px;
    }

    .info-row {
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid #f3f4f6;
    }

    .info-label {
        font-size: 10px;
        font-weight: 600;
        color: #6b7280;
        margin-bottom: 4px;
        text-transform: uppercase;
    }

    .info-value {
        font-size: 13px;
        font-weight: 500;
        color: #111827;
    }

    /* Maintenance Tracking Styles */
    .maintenance-stats {
        background: #f0fdf4;
        border: 1px solid #dcfce7;
        border-radius: 6px;
        padding: 8px 12px;
        margin-bottom: 12px;
    }

    .maintenance-stat-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 6px;
    }

    .maintenance-stat-item:last-child {
        margin-bottom: 0;
    }

    .stat-label {
        font-size: 11px;
        font-weight: 600;
        color: #166534;
        text-transform: uppercase;
    }

    .stat-value {
        font-size: 12px;
        font-weight: 500;
        color: #14532d;
    }

    .status-badge {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 500;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-completed {
        background: #d1fae5;
        color: #065f46;
    }

    .status-in-progress {
        background: #dbeafe;
        color: #1e40af;
    }

    .btn-maintenance {
        background: #2563eb;
        border: none;
        padding: 8px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        color: white;
        flex: 1;
    }

    .btn-maintenance:disabled {
        background: #9ca3af;
        cursor: not-allowed;
    }

    .btn-maintenance:hover:not(:disabled) {
        background: #1d4ed8;
    }

    .btn-view-history {
        background: #f3f4f6;
        border: 1px solid #e5e7eb;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        color: #374151;
        transition: all 0.2s;
    }

    .btn-view-history:hover {
        background: #e5e7eb;
        border-color: #d1d5db;
    }

    /* Filter Section */
    .filter-section {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 24px;
    }

    .filter-input, .form-select {
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        padding: 8px 12px;
        font-size: 13px;
    }

    .filter-input:focus, .form-select:focus {
        border-color: #2563eb;
        outline: none;
    }

    .btn-filter {
        background: #2563eb;
        border: none;
        border-radius: 6px;
        padding: 8px 20px;
        font-size: 13px;
        font-weight: 500;
        color: white;
    }

    .btn-filter:hover {
        background: #1d4ed8;
    }

    .btn-reset {
        background: #f3f4f6;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        padding: 8px 20px;
        font-size: 13px;
        font-weight: 500;
        color: #374151;
    }

    .btn-reset:hover {
        background: #e5e7eb;
    }

    /* Pagination */
    .custom-pagination {
        margin-top: 30px;
        display: flex;
        justify-content: center;
    }

    .custom-pagination nav {
        display: inline-block;
    }

    .custom-pagination .pagination {
        display: flex;
        padding-left: 0;
        list-style: none;
        border-radius: 6px;
        gap: 5px;
        margin: 0;
    }

    .custom-pagination .page-item {
        display: inline-block;
    }

    .custom-pagination .page-link {
        position: relative;
        display: block;
        padding: 8px 14px;
        line-height: 1.25;
        color: #374151;
        background-color: #fff;
        border: 1px solid #e5e7eb;
        text-decoration: none;
        font-size: 13px;
        border-radius: 6px;
        transition: all 0.2s;
    }

    .custom-pagination .page-link:hover {
        background-color: #f3f4f6;
        border-color: #e5e7eb;
        color: #1d4ed8;
    }

    .custom-pagination .page-item.active .page-link {
        z-index: 3;
        color: #fff;
        background-color: #2563eb;
        border-color: #2563eb;
    }

    .custom-pagination .page-item.disabled .page-link {
        color: #9ca3af;
        pointer-events: none;
        cursor: auto;
        background-color: #fff;
        border-color: #e5e7eb;
    }

    .entries-info {
        color: #6b7280;
        font-size: 13px;
        margin-top: 16px;
        text-align: center;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
    }

    .empty-state i {
        font-size: 64px;
        color: #d1d5db;
        margin-bottom: 16px;
    }

    .empty-state h4 {
        font-size: 18px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }

    .empty-state p {
        color: #6b7280;
        font-size: 14px;
    }

    /* Modal Styles */
    .modal-content {
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }

    .modal-header {
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
        padding: 16px 20px;
    }

    .modal-header h5 {
        font-weight: 600;
        color: #111827;
    }

    .form-control, .form-select {
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        padding: 8px 12px;
        font-size: 13px;
    }

    .form-control:focus, .form-select:focus {
        border-color: #2563eb;
        outline: none;
    }

    .per-page-selector {
        width: auto;
        display: inline-block;
        margin-left: 8px;
    }

    .history-table {
        font-size: 13px;
    }

    .history-table td, .history-table th {
        padding: 10px;
        vertical-align: middle;
    }
</style>

<div class="container-fluid p-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('emphome') }}" class="text-decoration-none">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active text-primary">My Accessories</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <form method="GET" action="{{ route('accessories.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold mb-1">Search Item</label>
                <input type="text" name="item" value="{{ request('item') }}"
                       class="form-control filter-input" placeholder="Search by item name...">
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold mb-1">Item Type</label>
                <select name="item_type" class="form-select">
                    <option value="">All Types</option>
                    <option value="new" {{ request('item_type') == 'new' ? 'selected' : '' }}>New</option>
                    <option value="refurbished" {{ request('item_type') == 'refurbished' ? 'selected' : '' }}>Refurbished</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label fw-semibold mb-1">Show</label>
                <select name="per_page" class="form-select per-page-selector" onchange="this.form.submit()">
                    <option value="3" {{ request('per_page') == 3 ? 'selected' : '' }}>3 cards</option>
                    <option value="6" {{ request('per_page') == 6 ? 'selected' : '' }}>6 cards</option>
                    <option value="12" {{ request('per_page') == 12 ? 'selected' : '' }}>12 cards</option>
                    <option value="24" {{ request('per_page') == 24 ? 'selected' : '' }}>24 cards</option>
                </select>
            </div>

            <div class="col-md-3">
                <button type="submit" class="btn btn-filter me-2">
                    <i class="bi bi-search me-1"></i> Filter
                </button>
                <a href="{{ route('accessories.index') }}" class="btn btn-reset">
                    <i class="bi bi-arrow-repeat me-1"></i> Reset
                </a>
            </div>
        </form>
    </div>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="fw-bold mb-0">
        <i class="bi bi-box-seam me-2"></i>My Accessories
    </h6>

    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" id="toggleScrap">
        <label class="form-check-label small" for="toggleScrap">
            Show Scrapped Items
        </label>
    </div>
</div>

<div class="scrap-card mb-3" id="scrapSection">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="fw-semibold text-danger small">
            <i class="bi bi-exclamation-circle me-1"></i>
            Scrapped
        </span>
        <span class="badge bg-danger small">{{ $scrappedItems->count() }}</span>
    </div>

    @if($scrappedItems->count() > 0)
        <div class="scrap-list">
            @foreach($scrappedItems as $s)
                <div class="scrap-item">
                    <div>
                        <div class="fw-semibold small">
                            {{ $s->item->item_name }}
                        </div>
                        <div class="text-muted" style="font-size:11px;">
                            {{ $s->item->item_code }}
                        </div>
                    </div>

                    <div class="text-end">
                        <span class="badge bg-danger" style="font-size:10px;">Scrap</span>
                        <div class="text-muted" style="font-size:10px;">
                            {{ \Carbon\Carbon::parse($s->created_at)->format('d M') }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center text-muted small py-2">
            No scrapped items
        </div>
    @endif
</div>

<style>
  .scrap-card {
    background: #fff5f5;
    border: 1px solid #fecaca;
    border-radius: 8px;
    padding: 10px;
}

/* 🔥 SCROLL AFTER ~5 ITEMS */
.scrap-list {
    max-height: 220px;   /* approx 5 items */
    overflow-y: auto;
}

/* Thin scrollbar (optional) */
.scrap-list::-webkit-scrollbar {
    width: 4px;
}
.scrap-list::-webkit-scrollbar-thumb {
    background: #fca5a5;
    border-radius: 10px;
}

.scrap-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 6px 8px;
    border-bottom: 1px solid #fee2e2;
}

.scrap-item:last-child {
    border-bottom: none;
}

#scrapSection {
    display: none;
}
</style>

<script>
    $(document).ready(function(){

    // 🔹 Load saved state
    let showScrap = localStorage.getItem('showScrap') === 'true';

    if(showScrap){
        $('#toggleScrap').prop('checked', true);
        $('#scrapSection').show();
    } else {
        $('#scrapSection').hide();
    }

    // 🔹 Toggle change
    $('#toggleScrap').change(function(){
        let isOn = $(this).is(':checked');

        localStorage.setItem('showScrap', isOn);

        if(isOn){
            $('#scrapSection').stop(true,true).slideDown();
        } else {
            $('#scrapSection').stop(true,true).slideUp();
        }

        // 🔥 Optional label change
        $(this).next('label').text(
            isOn ? 'Hide Scrapped Items' : 'Show Scrapped Items'
        );
    });

});
</script>

    <!-- Cards Grid -->
    @if($assignments->count() > 0)
        <div class="row g-4">
            @foreach($assignments as $assignment)
                <div class="col-md-6 col-xl-4">
                    <div class="card accessory-card">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="item-icon-wrapper">
                                        <i class="bi bi-laptop"></i>
                                    </div>
                                    <div>
                                        <h5 class="item-title mb-1">{{ $assignment->item->item_name }}</h5>
                                        <span class="item-code">{{ $assignment->item->item_code }}</span>
                                    </div>
                                </div>
                                <div>
                                      <span class="badge-active">
                                    <i class="bi bi-check-circle-fill me-1"></i> Active
                                </span>
                                <span class="badge-assigned">
                                    <i class="bi bi-check-circle-fill me-1"></i> Assigned
                                </span>
                                </div>
                            </div>

                            <!-- MAINTENANCE TRACKING SECTION -->
                            <div class="maintenance-stats">
                                <div class="maintenance-stat-item">
                                    <span class="stat-label">
                                        <i class="bi bi-bar-chart-steps me-1"></i>Total Maintenance
                                    </span>
                                    <span class="stat-value">
                                        <strong>{{ $assignment->maintenance_count }}</strong> time(s)
                                    </span>
                                </div>

                                @if($assignment->last_tracking_id)
                                <div class="maintenance-stat-item">
                                    <span class="stat-label">
                                        <i class="bi bi-upc-scan me-1"></i>Last Tracking ID
                                    </span>
                                    <span class="stat-value">
                                        <code style="font-size: 11px;">{{ $assignment->last_tracking_id }}</code>
                                    </span>
                                </div>

                                <div class="maintenance-stat-item">
                                    <span class="stat-label">
                                        <i class="bi bi-calendar3 me-1"></i>Last Request
                                    </span>
                                    <span class="stat-value">
                                        {{ \Carbon\Carbon::parse($assignment->last_maintenance_date)->format('d M Y') }}
                                    </span>
                                </div>

                               @php
$statusMap = [
    0 => ['Pending', 'status-pending'],
    1 => ['Completed', 'status-completed'],
];

$status = $statusMap[$assignment->last_maintenance_status] ?? ['Unknown',''];
@endphp

<div class="maintenance-stat-item">
    <span class="stat-label">
        <i class="bi bi-info-circle me-1"></i>Last Status
    </span>
    <span class="stat-value">
        <span class="status-badge {{ $status[1] }}">
            {{ $status[0] }}
        </span>
    </span>
</div>
                                @else
                                <div class="maintenance-stat-item">
                                    <span class="stat-label text-muted">
                                        <i class="bi bi-info-circle me-1"></i>Maintenance History
                                    </span>
                                    <span class="stat-value text-muted">
                                        No maintenance records
                                    </span>
                                </div>
                                @endif
                            </div>

                            <div class="info-row">
                                <div class="info-label">Item Type</div>
                               @php
$typeMap = [
    0 => 'New',
    1 => 'Refurbished'
];
@endphp

<div class="info-value">
    {{ $typeMap[$assignment->item->item_type] ?? 'Standard' }}
</div>
                            </div>

                            <div class="info-row">
                                <div class="info-label">Department</div>
                                <div class="info-value">{{ $assignment->department->dep_name ?? 'N/A' }}</div>
                            </div>

                            <div class="info-row">
                                <div class="info-label">Assigned Date</div>
                                <div class="info-value">{{ \Carbon\Carbon::parse($assignment->assigned_date)->format('d M Y') }}</div>
                            </div>

                            @if($assignment->item->brand || $assignment->item->model_number)
                                <div class="info-row">
                                    <div class="info-label">Details</div>
                                    <div class="info-value">
                                        {{ $assignment->item->brand ?? '' }} {{ $assignment->item->model_number ?? '' }}
                                    </div>
                                </div>
                            @endif

                            @if($assignment->remarks)
                                <div class="alert alert-light mt-2 p-2 small border">
                                    <i class="bi bi-chat-dots me-1"></i> {{ $assignment->remarks }}
                                </div>
                            @endif

                            <div class="d-flex gap-2 mt-3">
                                @if($assignment->has_pending_maintenance)
                                    <button type="button"
                                            class="btn btn-maintenance"
                                            disabled
                                            style="background: #9ca3af;">
                                        <i class="bi bi-clock-history me-2"></i>Pending Request
                                    </button>
                                @else
                                    <button type="button"
                                            class="btn btn-maintenance"
                                            data-bs-toggle="modal"
                                            data-bs-target="#maintenanceModal"
                                            data-item-id="{{ $assignment->item_id }}"
                                            data-item-name="{{ $assignment->item->item_name }}"
                                            data-item-code="{{ $assignment->item->item_code }}">
                                        <i class="bi bi-tools me-2"></i>Request Maintenance
                                    </button>
                                @endif

                                @if($assignment->maintenance_count > 0)
                                    <button type="button"
                                            class="btn-view-history"
                                            onclick="viewMaintenanceHistory({{ $assignment->item_id }}, '{{ addslashes($assignment->item->item_name) }}')">
                                        <i class="bi bi-clock-history me-1"></i>History
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="custom-pagination">
            {{ $assignments->links('pagination::bootstrap-4') }}
        </div>

        <div class="entries-info">
            Showing {{ $assignments->firstItem() }} to {{ $assignments->lastItem() }}
            of {{ $assignments->total() }} accessories
        </div>
    @else
        <div class="empty-state">
            <i class="bi bi-box-seam"></i>
            <h4>No Accessories Assigned</h4>
            <p>You don't have any items assigned to you yet.</p>
            @if(request('item') || request('item_type'))
                <a href="{{ route('accessories.index') }}" class="btn btn-primary mt-3">
                    <i class="bi bi-arrow-repeat me-2"></i>Clear Filters
                </a>
            @endif
        </div>
    @endif
</div>

<!-- Maintenance Request Modal -->
<div class="modal fade" id="maintenanceModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-tools me-2"></i>Request Maintenance
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="maintenanceRequestForm">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold mb-2">Item Information</label>
                        <div class="bg-light p-3 rounded">
                            <div class="mb-1">
                                <strong>Item Name:</strong>
                                <span id="modalItemName" class="text-primary"></span>
                            </div>
                            <div>
                                <strong>Item Code:</strong>
                                <span id="modalItemCode" class="text-muted"></span>
                            </div>
                        </div>
                        <input type="hidden" name="item_id" id="modalItemId">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold mb-2">
                            Issue Description <span class="text-danger">*</span>
                        </label>
                        <textarea name="issue_description" class="form-control"
                                  rows="4" placeholder="Please describe the issue in detail..."></textarea>
                        <small class="text-muted">Minimum 5 characters</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold mb-2">
                            Maintenance Type <span class="text-danger">*</span>
                        </label>
                       <select name="maintenance_type" class="form-select">
    <option value="">Select Type</option>
    <option value="1">Service</option>
    <option value="2">Upgrade</option>
</select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold mb-2">Additional Remarks</label>
                        <textarea name="remarks" class="form-control"
                                  rows="2" placeholder="Any additional information..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Handle modal data population
    $('#maintenanceModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var itemId = button.data('item-id');
        var itemName = button.data('item-name');
        var itemCode = button.data('item-code');

        var modal = $(this);
        modal.find('#modalItemId').val(itemId);
        modal.find('#modalItemName').text(itemName);
        modal.find('#modalItemCode').text(itemCode);
    });

    // Reset form when modal is closed
    $('#maintenanceModal').on('hidden.bs.modal', function() {
        $('#maintenanceRequestForm')[0].reset();
        $('.is-invalid').removeClass('is-invalid');
        $('.error-msg').remove();
    });

    // Form validation
    function validateField(field) {
        var value = field.val().trim();
        var name = field.attr('name');

        field.removeClass('is-invalid');
        field.closest('.mb-3').find('.error-msg').remove();

        if (name === 'issue_description') {
            if (!value) {
                showError(field, 'Please describe the issue');
                return false;
            }
            if (value.length < 5) {
                showError(field, 'Please provide at least 5 characters');
                return false;
            }
            if (value.length > 500) {
                showError(field, 'Maximum 500 characters allowed');
                return false;
            }
        }

        if (name === 'maintenance_type' && !value) {
            showError(field, 'Please select a maintenance type');
            return false;
        }

        if (name === 'remarks' && value && value.length > 300) {
            showError(field, 'Maximum 300 characters allowed');
            return false;
        }

        return true;
    }

    function showError(field, message) {
        field.addClass('is-invalid');
        field.closest('.mb-3').append('<div class="text-danger small error-msg mt-1">' + message + '</div>');
    }

    // Live validation
    $('#maintenanceRequestForm input, #maintenanceRequestForm textarea, #maintenanceRequestForm select').on('change keyup', function() {
        validateField($(this));
    });

    // Form submission
    $('#maintenanceRequestForm').on('submit', function(e) {
        e.preventDefault();

        var isValid = true;
        $('#maintenanceRequestForm textarea, #maintenanceRequestForm select').each(function() {
            if (!validateField($(this))) {
                isValid = false;
            }
        });

        if (!isValid) return;

        var formData = $(this).serialize();

        Swal.fire({
            title: 'Submit Request?',
            text: "You're about to submit a maintenance request for this item",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, submit it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("employee.maintenance.request") }}',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                confirmButtonColor: '#2563eb'
                            }).then(() => {
                                $('#maintenanceModal').modal('hide');
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: response.message,
                                confirmButtonColor: '#dc3545'
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Something went wrong. Please try again.',
                            confirmButtonColor: '#dc3545'
                        });
                    }
                });
            }
        });
    });
});

// View maintenance history function
function viewMaintenanceHistory(itemId, itemName) {
    Swal.fire({
        title: 'Loading...',
        text: 'Please wait while we fetch maintenance history',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: '{{ route("employee.maintenance.history") }}',
        type: 'GET',
        data: { item_id: itemId },
        success: function(response) {
            Swal.close();

            if (response.status && response.data.length > 0) {
                let historyHtml = '<div style="max-height: 500px; overflow-y: auto;">';
                historyHtml += '<table class="table table-sm history-table">';
                historyHtml += '<thead style="position: sticky; top: 0; background: white;">';
                historyHtml += '<tr>';
                historyHtml += '<th>Tracking ID</th>';
                historyHtml += '<th>Date</th>';
                historyHtml += '<th>Type</th>';
                historyHtml += '<th>Status</th>';
                historyHtml += '<th>Issue</th>';
                historyHtml += '</tr>';
                historyHtml += '</thead><tbody>';



                response.data.forEach(function(maintenance) {
                    let trackingId = 'MNT-' + String(maintenance.id).padStart(6, '0');
                    let date = new Date(maintenance.created_at).toLocaleDateString('en-GB', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric'
                    });


                                        let typeMap = {
   0: { text: 'Scrap', class: 'bg-danger' },
    1: { text: 'Service', class: 'bg-primary' },
    2: { text: 'Upgrade', class: 'bg-info' }
};

let type = typeMap[maintenance.maintenance_type] || { text: 'Unknown', class: 'bg-secondary' };

                    let statusClass = '';
                    let statusText = maintenance.status;

                    if (maintenance.status === 0) {
                        statusClass = 'status-pending';
                        statusText = 'Pending';
                    } else if (maintenance.status === 1) {
                        statusClass = 'status-completed';
                        statusText = 'Completed';
                    }

                    let issueText = maintenance.issue_description;
                    if (issueText.length > 50) {
                        issueText = issueText.substring(0, 50) + '...';
                    }

                    historyHtml += `<tr>
                        <td><code style="font-size: 11px;">${trackingId}</code></td>
                        <td>${date}</td>

<td>
    <span class="badge ${type.class}">
        ${type.text}
    </span>
</td>
                        <td><span class="status-badge ${statusClass}">${statusText}</span></td>
                        <td title="${maintenance.issue_description.replace(/"/g, '&quot;')}">${issueText}</td>
                    </tr>`;
                });

                historyHtml += '</tbody></table></div>';

                Swal.fire({
                    title: `<i class="bi bi-clock-history me-2"></i>Maintenance History - ${itemName}`,
                    html: historyHtml,
                    icon: '',
                    width: '900px',
                    confirmButtonColor: '#2563eb',
                    confirmButtonText: 'Close',
                    customClass: {
                        popup: 'history-popup'
                    }
                });
            } else {
                Swal.fire({
                    title: 'No History',
                    html: '<i class="bi bi-inbox" style="font-size: 48px; color: #9ca3af;"></i><br><p class="mt-2">No maintenance records found for this item</p>',
                    icon: 'info',
                    confirmButtonColor: '#2563eb'
                });
            }
        },
        error: function() {
            Swal.close();
            Swal.fire({
                title: 'Error!',
                text: 'Failed to load maintenance history. Please try again.',
                icon: 'error',
                confirmButtonColor: '#dc3545'
            });
        }
    });
}



</script>

</x-layout>
