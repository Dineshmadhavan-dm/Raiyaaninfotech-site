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

    .badge-returned {
        background: #6b7280;
        color: white;
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 500;
        display: inline-block;
    }

    .badge-scrap {
        background: #dc2626;
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
        background: var(--ra-primary);
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

    /* Filter Sections */
    .filter-section {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 0px 20px;
        margin-bottom: 24px;
        transition: all 0.3s ease;
    }

    .simple-filter {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 15px 20px;
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .simple-filter-left {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .simple-filter-right {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .simple-filter-label {
        font-size: 13px;
        font-weight: 500;
        color: #374151;
        margin: 0;
    }

    .filter-input, .form-select {
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        padding: 8px 12px;
        font-size: 13px;
    }









    /* Wizard Tabs */
    .wizard-tabs {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 6px;
        margin-bottom: 24px;
        display: flex;
        gap: 8px;
    }

    .wizard-tab {
        flex: 1;
        padding: 12px 20px;
        border-radius: 6px;
        font-weight: 500;
        font-size: 14px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: transparent;
        border: none;
        color: #6b7280;
    }

    .wizard-tab i {
        margin-right: 8px;
        font-size: 16px;
    }



    .wizard-tab.active {
        background: var(--ra-primary);
        color: white;
        box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
    }

    .wizard-tab.active i {
        color: white;
    }

    .tab-badge {
        display: inline-block;
        margin-left: 8px;
        padding: 2px 6px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 500;
        background: rgba(0,0,0,0.1);
    }

    .wizard-tab.active .tab-badge {
        background: rgba(255,255,255,0.2);
        color: white;
    }

    /* Tab Content */
    .tab-content {
        display: none;
        animation: fadeIn 0.4s ease;
    }

    .tab-content.active {
        display: block;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
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
        cursor: pointer;
    }

    .custom-pagination .page-item.active .page-link {
        z-index: 3;
        color: #fff;
        background-color: var(--ra-primary);
        border-color: var(--ra-primary);
        cursor: default;
    }

    .custom-pagination .page-item.disabled .page-link {
        color: #9ca3af;
        pointer-events: none;
        cursor: not-allowed;
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
        border-color: var(--ra-primary);
        outline: none;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .history-table {
        font-size: 13px;
    }

    .history-table td, .history-table th {
        padding: 10px;
        vertical-align: middle;
    }

    /* Section headers inside tabs */
    .section-header {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e5e7eb;
    }

    .section-header i {
        margin-right: 10px;
    }

    /* Per page selector */.per-page-selector ,.returned-per-page, .scrapped-per-page {
        width: auto;
  appearance: none !important;
  -webkit-appearance: none !important;
  -moz-appearance: none !important;
  background-image: none !important;
}

    /* Loading animation */


    /* Items per page controls */
    .items-per-page {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .items-per-page select {
        width: 80px;
    }

    /* Pagination controls */
    .pagination-controls {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .page-nav-btn {
        padding: 6px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        background: white;
        font-size: 13px;
        font-weight: 500;
        color: #374151;
        cursor: pointer;
        transition: all 0.2s;
    }

    .page-nav-btn:hover:not(:disabled) {
        background: #f3f4f6;
        border-color: #d1d5db;
    }

    .page-nav-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .page-number {
        padding: 6px 10px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        background: white;
        font-size: 13px;
        font-weight: 500;
        color: #374151;
        cursor: pointer;
        transition: all 0.2s;
        min-width: 36px;
        text-align: center;
    }

    .page-number.active {
        background: var(--ra-primary);
        color: white;
        border-color: var(--ra-primary);
        cursor: default;
    }

    .page-number:hover:not(.active) {
        background: #f3f4f6;
        border-color: #d1d5db;
    }

    .page-ellipsis {
        padding: 6px 4px;
        font-size: 13px;
        color: #6b7280;
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
        <div>
            <span class="text-muted small">
                <i class="bi bi-info-circle me-1"></i> Total Items: {{ $activeAssignments->total() + $returnedItems->count() + $scrappedItems->count() }}
            </span>
        </div>
    </div>

    <!-- Wizard Tabs -->
    <div class="wizard-tabs">
        <button class="wizard-tab active" data-tab="assigned">
            <i class="bi bi-check-circle-fill"></i> Currently Assigned
            <span class="tab-badge">{{ $activeAssignments->total() }}</span>
        </button>
        <button class="wizard-tab" data-tab="returned">
            <i class="bi bi-arrow-return-left"></i> Returned Items
            <span class="tab-badge">{{ $returnedCount }}</span>
        </button>
        <button class="wizard-tab" data-tab="scrapped">
            <i class="bi bi-exclamation-triangle-fill"></i> Scrapped Items
            <span class="tab-badge">{{ $scrappedCount }}</span>
        </button>
    </div>

    <!-- Filter Section - For Assigned Tab (Full Filter) -->
    <div class="filter-section" id="filterSection">
        <form method="GET" action="{{ route('accessories.index') }}" id="filterForm">
           <div class="row g-3 align-items-end">

    <!-- LEFT SIDE -->
    <div class="col-md-8 d-flex gap-3 align-items-end">

        <!-- Show -->
        <div>
            <div class="items-per-page">
          <span class="simple-filter-label">
                       Show:
                    </span>
            <select name="per_page" class="form-select per-page-selector">
                <option value="3" {{ request('per_page') == 3 ? 'selected' : '' }}>3 cards</option>
                <option value="6" {{ request('per_page') == 6 ? 'selected' : '' }}>6 cards</option>
                <option value="12" {{ request('per_page') == 12 ? 'selected' : '' }}>12 cards</option>
                <option value="24" {{ request('per_page') == 24 ? 'selected' : '' }}>24 cards</option>
            </select>
        </div>
</div>
    </div>

    <!-- RIGHT SIDE -->
    <div class="col-md-4 d-flex justify-content-end gap-2">

          <div class="custom-pagination">
                {{ $activeAssignments->appends(['per_page' => request('per_page'), 'item' => request('item'), 'item_type' => request('item_type')])->links('pagination::bootstrap-4') }}
            </div>
    </div>

</div>
        </form>
    </div>

    <!-- TAB 1: ASSIGNED ITEMS -->
    <div id="assigned-tab" class="tab-content active">
        @if($activeAssignments->count() > 0)
            <div class="row g-4" id="assignedItemsContainer">
                @foreach($activeAssignments as $assignment)
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
                                            0 => '🆕 New',
                                            1 => '🔄 Refurbished'
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

            <div class="entries-info">
                Showing {{ $activeAssignments->firstItem() }} to {{ $activeAssignments->lastItem() }}
                of {{ $activeAssignments->total() }} active accessories
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-box-seam"></i>
                <h4>No Active Assignments</h4>
                <p>You don't have any items currently assigned to you.</p>
            </div>
        @endif
    </div>

    <!-- TAB 2: RETURNED ITEMS -->
    <div id="returned-tab" class="tab-content">
        <div class="simple-filter" id="returnedFilterSection">
            <div class="simple-filter-left">
                <div class="items-per-page">
                    <span class="simple-filter-label">
                       Show:
                    </span>
                    <select class="form-select returned-per-page" style="width: 80px;">
                        <option value="3"  selected>3 cards</option>
                        <option value="6">6 cards</option>
                        <option value="12">12 cards</option>
                        <option value="24">24 cards</option>
                        <option value="all">All</option>
                    </select>
                </div>
                <div class="entries-info" style="margin: 0; display: inline-block;">
                    Total: <span id="returnedTotal">{{ $returnedItems->count() }}</span> items
                </div>
            </div>
            <div class="simple-filter-right">
                <div class="pagination-controls" id="returnedPagination">
                    <button class="page-nav-btn" id="returnedPrevBtn" disabled>
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <div id="returnedPageNumbers" class="d-flex gap-1"></div>
                    <button class="page-nav-btn" id="returnedNextBtn">
                         <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>

        @if($returnedItems->count() > 0)
            <div class="row g-4" id="returnedItemsContainer">
                @foreach($returnedItems as $returned)
                    <div class="col-md-6 col-xl-4 returned-item" data-item-id="{{ $returned->id }}">
                        <div class="card accessory-card">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="item-icon-wrapper">
                                            <i class="bi bi-laptop"></i>
                                        </div>
                                        <div>
                                            <h5 class="item-title mb-1">{{ $returned->item->item_name }}</h5>
                                            <span class="item-code">{{ $returned->item->item_code }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="badge-returned">
                                            <i class="bi bi-arrow-return-left me-1"></i> Returned
                                        </span>
                                    </div>
                                </div>

                                <div class="info-row">
                                    <div class="info-label">Item Type</div>
                                    @php
                                        $typeMap = [
                                            0 => '🆕 New',
                                            1 => '🔄 Refurbished'
                                        ];
                                    @endphp
                                    <div class="info-value">
                                        {{ $typeMap[$returned->item->item_type] ?? 'Standard' }}
                                    </div>
                                </div>

                                <div class="info-row">
                                    <div class="info-label">Department</div>
                                    <div class="info-value">{{ $returned->department->dep_name ?? 'N/A' }}</div>
                                </div>

                                <div class="info-row">
                                    <div class="info-label">Assigned Date</div>
                                    <div class="info-value">{{ \Carbon\Carbon::parse($returned->assigned_date)->format('d M Y') }}</div>
                                </div>

                                <div class="info-row">
                                    <div class="info-label">Returned Date</div>
                                    <div class="info-value">{{ \Carbon\Carbon::parse($returned->return_date)->format('d M Y') }}</div>
                                </div>

                                @if($returned->item->brand || $returned->item->model_number)
                                    <div class="info-row">
                                        <div class="info-label">Details</div>
                                        <div class="info-value">
                                            {{ $returned->item->brand ?? '' }} {{ $returned->item->model_number ?? '' }}
                                        </div>
                                    </div>
                                @endif

                                @if($returned->remarks)
                                    <div class="alert alert-light mt-2 p-2 small border">
                                        <i class="bi bi-chat-dots me-1"></i> {{ $returned->remarks }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-arrow-return-left"></i>
                <h4>No Returned Items</h4>
                <p>You haven't returned any items yet.</p>
            </div>
        @endif
    </div>

    <!-- TAB 3: SCRAPPED ITEMS -->
    <div id="scrapped-tab" class="tab-content">
        <div class="simple-filter" id="scrappedFilterSection">
            <div class="simple-filter-left">
                <div class="items-per-page">
                    <span class="simple-filter-label">
                       Show:
                    </span>
                    <select class="form-select scrapped-per-page" style="width: 80px;">
                        <option value="3"  selected>3 cards</option>
                        <option value="6">6 cards</option>
                        <option value="12">12 cards</option>
                        <option value="24">24 cards</option>
                        <option value="all">All</option>
                    </select>
                </div>
                <div class="entries-info" style="margin: 0; display: inline-block;">
                    Total: <span id="scrappedTotal">{{ $scrappedItems->count() }}</span> items
                </div>
            </div>
            <div class="simple-filter-right">
                <div class="pagination-controls" id="scrappedPagination">
                    <button class="page-nav-btn" id="scrappedPrevBtn" disabled>
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <div id="scrappedPageNumbers" class="d-flex gap-1"></div>
                    <button class="page-nav-btn" id="scrappedNextBtn">
                       <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>

        @if($scrappedItems->count() > 0)
            <div class="row g-4" id="scrappedItemsContainer">
                @foreach($scrappedItems as $scrap)
                    <div class="col-md-6 col-xl-4 scrapped-item" data-item-id="{{ $scrap->id }}">
                        <div class="card accessory-card">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="item-icon-wrapper">
                                            <i class="bi bi-trash"></i>
                                        </div>
                                        <div>
                                            <h5 class="item-title mb-1">{{ $scrap->item->item_name }}</h5>
                                            <span class="item-code">{{ $scrap->item->item_code }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="badge-scrap">
                                            <i class="bi bi-exclamation-triangle-fill me-1"></i> Scrapped
                                        </span>
                                    </div>
                                </div>

                                <div class="info-row">
                                    <div class="info-label">Item Type</div>
                                    @php
                                        $typeMap = [
                                            0 => '🆕 New',
                                            1 => '🔄 Refurbished'
                                        ];
                                    @endphp
                                    <div class="info-value">
                                        {{ $typeMap[$scrap->item->item_type] ?? 'Standard' }}
                                    </div>
                                </div>

                                <div class="info-row">
                                    <div class="info-label">Scrap Date</div>
                                    <div class="info-value">{{ \Carbon\Carbon::parse($scrap->created_at)->format('d M Y') }}</div>
                                </div>

                                <div class="info-row">
                                    <div class="info-label">Issue Description</div>
                                    <div class="info-value small">{{ $scrap->issue_description ?? 'No description' }}</div>
                                </div>

                                @if($scrap->item->brand || $scrap->item->model_number)
                                    <div class="info-row">
                                        <div class="info-label">Details</div>
                                        <div class="info-value">
                                            {{ $scrap->item->brand ?? '' }} {{ $scrap->item->model_number ?? '' }}
                                        </div>
                                    </div>
                                @endif

                                @if($scrap->remarks)
                                    <div class="alert alert-light mt-2 p-2 small border">
                                        <i class="bi bi-chat-dots me-1"></i> {{ $scrap->remarks }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-exclamation-triangle"></i>
                <h4>No Scrapped Items</h4>
                <p>No items have been scrapped from your assignments.</p>
            </div>
        @endif
    </div>
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
                            <option value="1">🔧 Service</option>
                            <option value="2">⬆️ Upgrade</option>
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
                    <button type="submit" class="btn btn-primary" id="submitMaintenanceBtn">
                        <i class="bi bi-check-circle me-1"></i> Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Tab switching functionality
    $('.wizard-tab').click(function() {
        var tabId = $(this).data('tab');

        $('.wizard-tab').removeClass('active');
        $(this).addClass('active');

        $('.tab-content').removeClass('active');
        $('#' + tabId + '-tab').addClass('active');

        localStorage.setItem('activeAccessoryTab', tabId);

        if (tabId === 'assigned') {
            $('#filterSection').slideDown(300);
        } else {
            $('#filterSection').slideUp(300);
        }
    });

    var savedTab = localStorage.getItem('activeAccessoryTab');
    if (savedTab && (savedTab === 'assigned' || savedTab === 'returned' || savedTab === 'scrapped')) {
        $('.wizard-tab[data-tab="' + savedTab + '"]').click();
    }

    // Handle per page change for Assigned tab
    $('.per-page-selector').change(function() {
        $('#filterForm').submit();
    });

    // ==================== RETURNED ITEMS PAGINATION ====================
    var returnedCurrentPage = 1;
    var returnedPerPage = 3;
    var returnedItems = $('.returned-item');
    var returnedTotalItems = returnedItems.length;
    var returnedTotalPages = Math.ceil(returnedTotalItems / returnedPerPage);

    function updateReturnedPagination() {
        var start = (returnedCurrentPage - 1) * returnedPerPage;
        var end = start + returnedPerPage;

        returnedItems.hide();
        returnedItems.slice(start, end).show();

        $('#returnedPrevBtn').prop('disabled', returnedCurrentPage === 1);
        $('#returnedNextBtn').prop('disabled', returnedCurrentPage === returnedTotalPages);

        generateReturnedPageNumbers();
    }

    function generateReturnedPageNumbers() {
        var pageNumbersHtml = '';
        var maxVisible = 5;
        var startPage = Math.max(1, returnedCurrentPage - Math.floor(maxVisible / 2));
        var endPage = Math.min(returnedTotalPages, startPage + maxVisible - 1);

        if (endPage - startPage + 1 < maxVisible) {
            startPage = Math.max(1, endPage - maxVisible + 1);
        }

        if (startPage > 1) {
            pageNumbersHtml += '<div class="page-number" data-page="1">1</div>';
            if (startPage > 2) {
                pageNumbersHtml += '<div class="page-ellipsis">...</div>';
            }
        }

        for (var i = startPage; i <= endPage; i++) {
            var activeClass = i === returnedCurrentPage ? 'active' : '';
            pageNumbersHtml += `<div class="page-number ${activeClass}" data-page="${i}">${i}</div>`;
        }

        if (endPage < returnedTotalPages) {
            if (endPage < returnedTotalPages - 1) {
                pageNumbersHtml += '<div class="page-ellipsis">...</div>';
            }
            pageNumbersHtml += `<div class="page-number" data-page="${returnedTotalPages}">${returnedTotalPages}</div>`;
        }

        $('#returnedPageNumbers').html(pageNumbersHtml);
    }

    $('#returnedPrevBtn').click(function() {
        if (returnedCurrentPage > 1) {
            returnedCurrentPage--;
            updateReturnedPagination();
        }
    });

    $('#returnedNextBtn').click(function() {
        if (returnedCurrentPage < returnedTotalPages) {
            returnedCurrentPage++;
            updateReturnedPagination();
        }
    });

    $(document).on('click', '#returnedPageNumbers .page-number', function() {
        var page = parseInt($(this).data('page'));
        if (!isNaN(page) && page !== returnedCurrentPage) {
            returnedCurrentPage = page;
            updateReturnedPagination();
        }
    });

    $('.returned-per-page').change(function() {
        returnedPerPage = parseInt($(this).val());
        if (isNaN(returnedPerPage)) {
            returnedPerPage = returnedTotalItems;
        }
        returnedTotalPages = Math.ceil(returnedTotalItems / returnedPerPage);
        returnedCurrentPage = 1;
        updateReturnedPagination();
    });

    // Initialize returned pagination
    if (returnedTotalItems > 0) {
        updateReturnedPagination();
    }

    // ==================== SCRAPPED ITEMS PAGINATION ====================
    var scrappedCurrentPage = 1;
    var scrappedPerPage = 3;
    var scrappedItems = $('.scrapped-item');
    var scrappedTotalItems = scrappedItems.length;
    var scrappedTotalPages = Math.ceil(scrappedTotalItems / scrappedPerPage);

    function updateScrappedPagination() {
        var start = (scrappedCurrentPage - 1) * scrappedPerPage;
        var end = start + scrappedPerPage;

        scrappedItems.hide();
        scrappedItems.slice(start, end).show();

        $('#scrappedPrevBtn').prop('disabled', scrappedCurrentPage === 1);
        $('#scrappedNextBtn').prop('disabled', scrappedCurrentPage === scrappedTotalPages);

        generateScrappedPageNumbers();
    }

    function generateScrappedPageNumbers() {
        var pageNumbersHtml = '';
        var maxVisible = 5;
        var startPage = Math.max(1, scrappedCurrentPage - Math.floor(maxVisible / 2));
        var endPage = Math.min(scrappedTotalPages, startPage + maxVisible - 1);

        if (endPage - startPage + 1 < maxVisible) {
            startPage = Math.max(1, endPage - maxVisible + 1);
        }

        if (startPage > 1) {
            pageNumbersHtml += '<div class="page-number" data-page="1">1</div>';
            if (startPage > 2) {
                pageNumbersHtml += '<div class="page-ellipsis">...</div>';
            }
        }

        for (var i = startPage; i <= endPage; i++) {
            var activeClass = i === scrappedCurrentPage ? 'active' : '';
            pageNumbersHtml += `<div class="page-number ${activeClass}" data-page="${i}">${i}</div>`;
        }

        if (endPage < scrappedTotalPages) {
            if (endPage < scrappedTotalPages - 1) {
                pageNumbersHtml += '<div class="page-ellipsis">...</div>';
            }
            pageNumbersHtml += `<div class="page-number" data-page="${scrappedTotalPages}">${scrappedTotalPages}</div>`;
        }

        $('#scrappedPageNumbers').html(pageNumbersHtml);
    }

    $('#scrappedPrevBtn').click(function() {
        if (scrappedCurrentPage > 1) {
            scrappedCurrentPage--;
            updateScrappedPagination();
        }
    });

    $('#scrappedNextBtn').click(function() {
        if (scrappedCurrentPage < scrappedTotalPages) {
            scrappedCurrentPage++;
            updateScrappedPagination();
        }
    });

    $(document).on('click', '#scrappedPageNumbers .page-number', function() {
        var page = parseInt($(this).data('page'));
        if (!isNaN(page) && page !== scrappedCurrentPage) {
            scrappedCurrentPage = page;
            updateScrappedPagination();
        }
    });

    $('.scrapped-per-page').change(function() {
        scrappedPerPage = parseInt($(this).val());
        if (isNaN(scrappedPerPage)) {
            scrappedPerPage = scrappedTotalItems;
        }
        scrappedTotalPages = Math.ceil(scrappedTotalItems / scrappedPerPage);
        scrappedCurrentPage = 1;
        updateScrappedPagination();
    });

    // Initialize scrapped pagination
    if (scrappedTotalItems > 0) {
        updateScrappedPagination();
    }



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

    $('#maintenanceModal').on('hidden.bs.modal', function() {
        $('#maintenanceRequestForm')[0].reset();
        $('.is-invalid').removeClass('is-invalid');
        $('.error-msg').remove();
    });

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

    $('#maintenanceRequestForm input, #maintenanceRequestForm textarea, #maintenanceRequestForm select').on('change keyup', function() {
        validateField($(this));
    });

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
        var submitBtn = $('#submitMaintenanceBtn');
        var originalText = submitBtn.html();

        submitBtn.html('<span class="loading-spinner me-2"></span> Submitting...');
        submitBtn.prop('disabled', true);

        Swal.fire({
            title: 'Submit Request?',
            text: "You're about to submit a maintenance request for this item",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: 'var(--ra-primary)',
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
                                confirmButtonColor: 'var(--ra-primary)',
                                timer: 2000
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
                            submitBtn.html(originalText);
                            submitBtn.prop('disabled', false);
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Something went wrong. Please try again.',
                            confirmButtonColor: '#dc3545'
                        });
                        submitBtn.html(originalText);
                        submitBtn.prop('disabled', false);
                    }
                });
            } else {
                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);
            }
        });
    });
});

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
                historyHtml += '<thead style="position: sticky; top: 0; background: white; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">';
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
                        <td><span class="badge ${type.class}">${type.text}</span></td>
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
                    confirmButtonColor: 'var(--ra-primary)',
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
                    confirmButtonColor: 'var(--ra-primary)'
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
