<x-layout>
@section('title','Inventory History')

<div class="container-fluid p-4">

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Inventory History</h3>
        <small class="text-muted">Complete audit log of all inventory actions</small>
    </div>
    <div>
        <button class="btn btn-outline-secondary" onclick="window.location.reload()">
            <i class="bi bi-arrow-repeat"></i> Refresh
        </button>
    </div>
</div>

<!-- Advanced Filters Card -->
<div class="card shadow-sm rounded-4 mb-4 border-0">
    <div class="card-body">
        <form method="GET" action="{{ route('inventory.history.index') }}" id="filterForm">
            <div class="row g-3">

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Module</label>
                    <select name="module" class="form-select">
                        <option value="">All Modules</option>
                        <option value="item" {{ request('module') == 'item' ? 'selected' : '' }}>📦 Item</option>
                        <option value="assignment" {{ request('module') == 'assignment' ? 'selected' : '' }}>🔄 Assignment</option>
                        <option value="maintenance" {{ request('module') == 'maintenance' ? 'selected' : '' }}>🔧 Maintenance</option>
                        <option value="category" {{ request('module') == 'category' ? 'selected' : '' }}>📁 Category</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Action</label>
                    <select name="action" class="form-select">
                        <option value="">All Actions</option>
                        <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>✨ Created</option>
                        <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>✏️ Updated</option>
                        <option value="assigned" {{ request('action') == 'assigned' ? 'selected' : '' }}>📤 Assigned</option>
                        <option value="returned" {{ request('action') == 'returned' ? 'selected' : '' }}>📥 Returned</option>
                        <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>🗑️ Deleted</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Sub Action</label>
                    <select name="sub_action" class="form-select">
                        <option value="">All</option>
                        <option value="service" {{ request('sub_action') == 'service' ? 'selected' : '' }}>🔧 Service</option>
                        <option value="upgrade" {{ request('sub_action') == 'upgrade' ? 'selected' : '' }}>⬆️ Upgrade</option>
                        <option value="scrap" {{ request('sub_action') == 'scrap' ? 'selected' : '' }}>🗑️ Scrap</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Employee</label>
                    <select name="employee_id" class="form-select">
                        <option value="">All Employees</option>
                        @foreach($employees ?? [] as $emp)
                            <option value="{{ $emp->emp_id }}" {{ request('employee_id') == $emp->emp_id ? 'selected' : '' }}>
                                {{ $emp->fullname }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Date From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Date To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Per Page</label>
                    <select name="per_page" class="form-select">
                         <option value="3" {{ request('per_page', 3) == 3 ? 'selected' : '' }}>3</option>
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>

                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-search"></i> Apply Filters
                    </button>
                    <a href="{{ route('inventory.history.index') }}" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-eraser"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Stats Summary -->
@php
    $stats = [
        'total' => $histories->total(),
        'created' => $histories->where('action', 'created')->count(),
        'updated' => $histories->where('action', 'updated')->count(),
        'assigned' => $histories->where('action', 'assigned')->count(),
        'returned' => $histories->where('action', 'returned')->count(),
    ];
@endphp

<div class="row g-3 mb-4">
    <div class="col-md-2">
        <div class="card border-0 shadow-sm rounded-4 bg-gradient-primary text-white">
            <div class="card-body text-center py-3">
                <h4 class="mb-0 fw-bold">{{ $stats['total'] }}</h4>
                <small>Total Activities</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card border-0 shadow-sm rounded-4 bg-success bg-opacity-10">
            <div class="card-body text-center py-3">
                <h4 class="mb-0 fw-bold text-success">{{ $stats['created'] }}</h4>
                <small class="text-muted">✨ Created</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card border-0 shadow-sm rounded-4 bg-info bg-opacity-10">
            <div class="card-body text-center py-3">
                <h4 class="mb-0 fw-bold text-info">{{ $stats['updated'] }}</h4>
                <small class="text-muted">✏️ Updated</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card border-0 shadow-sm rounded-4 bg-warning bg-opacity-10">
            <div class="card-body text-center py-3">
                <h4 class="mb-0 fw-bold text-warning">{{ $stats['assigned'] }}</h4>
                <small class="text-muted">📤 Assigned</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card border-0 shadow-sm rounded-4 bg-secondary bg-opacity-10">
            <div class="card-body text-center py-3">
                <h4 class="mb-0 fw-bold text-secondary">{{ $stats['returned'] }}</h4>
                <small class="text-muted">📥 Returned</small>
            </div>
        </div>
    </div>
</div>

<!-- Helper functions for displaying names -->
@php
    function getMaintenanceTypeName($type) {
        return match($type) {
            0 => 'Scrap',
            1 => 'Service',
            2 => 'Upgrade',
            default => 'Unknown'
        };
    }

    function getMaintenanceTypeIcon($type) {
        return match($type) {
            0 => '🗑️',
            1 => '🔧',
            2 => '⬆️',
            default => '❓'
        };
    }

    function getMaintenanceTypeColor($type) {
        return match($type) {
            0 => 'danger',
            1 => 'primary',
            2 => 'warning',
            default => 'secondary'
        };
    }

    function getStatusName($status, $module = null) {
        // For assignment status
        if ($module === 'assignment') {
            return match($status) {
                0 => 'Assigned',
                1 => 'Returned',
                default => 'Unknown'
            };
        }

        // For maintenance status
        if ($module === 'maintenance') {
            return match($status) {
                0 => 'Pending',
                1 => 'Completed',
                default => 'Unknown'
            };
        }

        return $status ?? 'N/A';
    }
    function getConditionStatusName($status) {
    return match($status) {
        0 => 'Scrap',
        1 => 'Active',
        default => 'Unknown'
    };
}

function getConditionStatusColor($status) {
    return match($status) {
        0 => 'danger',
        1 => 'success',
        default => 'secondary'
    };
}

function getConditionStatusIcon($status) {
    return match($status) {
        0 => '🗑️',
        1 => '✅',
        default => '❓'
    };
}


    // ✅ NEW: Item Type helper functions
    function getItemTypeName($type) {
        return match($type) {
            0 => 'New',
            1 => 'Refurbished',
            default => 'Unknown'
        };
    }

    function getItemTypeColor($type) {
        return match($type) {
            0 => 'info',
            1 => 'warning',
            default => 'secondary'
        };
    }

    function getItemTypeIcon($type) {
        return match($type) {
            0 => '🆕',
            1 => '🔄',
            default => '❓'
        };
    }
    function getStatusColor($status, $module = null) {
        if ($module === 'assignment') {
            return match($status) {
                0 => 'warning',
                1 => 'success',
                default => 'secondary'
            };
        }

        if ($module === 'maintenance') {
            return match($status) {
                0 => 'warning',
                1 => 'success',
                default => 'secondary'
            };
        }

        return 'secondary';
    }
@endphp

<!-- Timeline Activities -->
<div class="timeline-container">

@forelse($histories as $history)
    @php
        // Get category name properly
        $categoryName = 'N/A';
        if ($history->module == 'category' && $history->category) {
            $categoryName = $history->category->category_name;
        } elseif ($history->module == 'item' && $history->item && $history->item->category) {
            $categoryName = $history->item->category->category_name;
        }

        // Get item name
        $itemName = $history->item->item_name ?? 'N/A';

        // Get action icon and color
        $actionConfig = match($history->action) {
            'created' => ['icon' => '✨', 'color' => 'success', 'bg' => 'success'],
            'updated' => ['icon' => '✏️', 'color' => 'info', 'bg' => 'info'],
            'assigned' => ['icon' => '📤', 'color' => 'warning', 'bg' => 'warning'],
            'returned' => ['icon' => '📥', 'color' => 'secondary', 'bg' => 'secondary'],
            'deleted' => ['icon' => '🗑️', 'color' => 'danger', 'bg' => 'danger'],
            default => ['icon' => '📝', 'color' => 'dark', 'bg' => 'dark'],
        };

        // Sub action styling
        $subActionConfig = match($history->sub_action) {
            'service' => ['icon' => '🔧', 'color' => 'primary'],
            'upgrade' => ['icon' => '⬆️', 'color' => 'purple'],
            'scrap' => ['icon' => '🗑️', 'color' => 'danger'],
            default => null,
        };
    @endphp

    <div class="timeline-item mb-4 animate__animated animate__fadeInUp">
        <div class="card border-0 shadow-sm rounded-4 hover-shadow transition-all">
            <div class="card-body p-4">

                <!-- Header -->
                <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-3">
                        <div class="timeline-icon bg-{{ $actionConfig['bg'] }}-subtle rounded-circle p-2">
                            <span class="fs-4">{{ $actionConfig['icon'] }}</span>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">
                                @if($history->item)
                                    <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#itemModal{{ $history->item_id }}">
                                        {{ $itemName }}
                                    </a>
                                @else
                                    {{ $itemName }}
                                @endif
                            </h5>
                            <div class="d-flex gap-3 flex-wrap">
                                <span class="badge bg-{{ $actionConfig['bg'] }} px-3 py-1">
                                    {{ $actionConfig['icon'] }} {{ ucfirst($history->action) }}
                                </span>
                                @if($history->sub_action)
                                    <span class="badge bg-{{ $subActionConfig['color'] ?? 'secondary' }} px-3 py-1">
                                        {{ $subActionConfig['icon'] ?? '' }} {{ ucfirst($history->sub_action) }}
                                    </span>
                                @endif
                                <span class="badge bg-light text-dark px-3 py-1">
                                    📁 {{ ucfirst($history->module) }}
                                </span>
                                @if($categoryName != 'N/A')
                                    <span class="badge bg-light text-dark px-3 py-1">
                                        🏷️ {{ $categoryName }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="text-muted small">
                            <i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::parse($history->action_date)->format('d M Y') }}
                        </div>
                        <div class="text-muted small">
                            <i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($history->action_date)->format('h:i A') }}
                        </div>
                    </div>
                </div>

                <!-- Employee Info (Improved with employee details) -->
                @if($history->employee)
                <div class="mb-3 p-3 bg-light rounded-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-person-circle fs-5"></i>
                        <div>
                            <strong>{{ $history->employee->fullname ?? 'Unknown Employee' }}</strong>
                            @if($history->employee->employee_id)
                                <small class="text-muted">(ID: {{ $history->employee->employee_id }})</small>
                            @endif
                            @if($history->employee->cur_department && $history->employee->department)
                                <small class="text-muted ms-2">
                                    <i class="bi bi-building"></i> {{ $history->employee->department->dep_name ?? '' }}
                                </small>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <hr>
<!-- Data Comparison -->
<div class="row g-3">
    @if($history->new_data)
    <div class="col-md-6">
        <div class="data-card new-data p-3 rounded-3 border-start border-4 border-success">
            <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-check-circle-fill text-success"></i>
                <h6 class="fw-bold mb-0">New Data</h6>
            </div>
            <div class="data-grid">
                @foreach($history->new_data as $key => $value)
                    @if(!in_array($key, ['id', 'created_at', 'updated_at', 'delete_status']))
                        <div class="data-row">
                            <span class="data-label">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                            <span class="data-value">
                                @if($key == 'maintenance_type')
                                    <span class="badge bg-{{ getMaintenanceTypeColor($value) }}">
                                        {{ getMaintenanceTypeIcon($value) }} {{ getMaintenanceTypeName($value) }}
                                    </span>
                                @elseif($key == 'status')
                                    @php
                                        $module = $history->module ?? null;
                                        $statusText = getStatusName($value, $module);
                                        $statusColor = getStatusColor($value, $module);
                                    @endphp
                                    <span class="badge bg-{{ $statusColor }}">
                                        {{ $statusText }}
                                    </span>
                                @elseif($key == 'condition_status')
                                    <span class="badge bg-{{ getConditionStatusColor($value) }}">
                                        {{ getConditionStatusIcon($value) }} {{ getConditionStatusName($value) }}
                                    </span>
                                @elseif($key == 'item_type')
                                    <span class="badge bg-{{ getItemTypeColor($value) }}">
                                        {{ getItemTypeIcon($value) }} {{ getItemTypeName($value) }}
                                    </span>
                                @elseif($key == 'employee_id')
                                    {{ $history->employee->fullname ?? $value }}
                                @elseif($key == 'department_id')
                                    {{ $history->getNewDepartmentName() ?? $value }}
                                @elseif($key == 'item_id' && $history->item)
                                    {{ $history->item->item_name ?? $value }}
                                @elseif($key == 'category_id')
                                    {{ $history->getNewCategoryName() ?? $value }}
                                @else
                                    {{ $value ?? '-' }}
                                @endif
                            </span>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    @endif

    @if($history->old_data)
    <div class="col-md-6">
        <div class="data-card old-data p-3 rounded-3 border-start border-4 border-danger">
            <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-x-circle-fill text-danger"></i>
                <h6 class="fw-bold mb-0">Old Data</h6>
            </div>
            <div class="data-grid">
                @foreach($history->old_data as $key => $value)
                    @if(!in_array($key, ['id', 'created_at', 'updated_at', 'delete_status']))
                        <div class="data-row">
                            <span class="data-label">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                            <span class="data-value">
                                @if($key == 'maintenance_type')
                                    <span class="badge bg-{{ getMaintenanceTypeColor($value) }}">
                                        {{ getMaintenanceTypeIcon($value) }} {{ getMaintenanceTypeName($value) }}
                                    </span>
                                @elseif($key == 'status')
                                    @php
                                        $module = $history->module ?? null;
                                        $statusText = getStatusName($value, $module);
                                        $statusColor = getStatusColor($value, $module);
                                    @endphp
                                    <span class="badge bg-{{ $statusColor }}">
                                        {{ $statusText }}
                                    </span>
                                @elseif($key == 'condition_status')
                                    <span class="badge bg-{{ getConditionStatusColor($value) }}">
                                        {{ getConditionStatusIcon($value) }} {{ getConditionStatusName($value) }}
                                    </span>
                                @elseif($key == 'item_type')
                                    <span class="badge bg-{{ getItemTypeColor($value) }}">
                                        {{ getItemTypeIcon($value) }} {{ getItemTypeName($value) }}
                                    </span>
                                @elseif($key == 'employee_id')
                                    {{ $history->employee->fullname ?? $value }}
                                @elseif($key == 'department_id')
                                    {{ $history->getOldDepartmentName() ?? $value }}
                                @elseif($key == 'item_id' && $history->item)
                                    {{ $history->item->item_name ?? $value }}
                                @elseif($key == 'category_id')
                                    {{ $history->getOldCategoryName() ?? $value }}
                                @else
                                    {{ $value ?? '-' }}
                                @endif
                            </span>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
                <!-- Additional details for maintenance records -->
                @if($history->module == 'maintenance' && $history->maintenance)
                <div class="mt-3 p-3 bg-info bg-opacity-10 rounded-3">
                    <div class="d-flex flex-wrap gap-3">
                        <div>
                            <small class="text-muted">Maintenance Type:</small>
                            <div>
                                <span class="badge bg-{{ getMaintenanceTypeColor($history->maintenance->maintenance_type) }}">
                                    {{ getMaintenanceTypeIcon($history->maintenance->maintenance_type) }} {{ getMaintenanceTypeName($history->maintenance->maintenance_type) }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <small class="text-muted">Status:</small>
                            <div>
                                <span class="badge bg-{{ getStatusColor($history->maintenance->status, 'maintenance') }}">
                                    {{ getStatusName($history->maintenance->status, 'maintenance') }}
                                </span>
                            </div>
                        </div>
                        @if($history->maintenance->cost)
                        <div>
                            <small class="text-muted">Cost:</small>
                            <div class="fw-bold">${{ number_format($history->maintenance->cost, 2) }}</div>
                        </div>
                        @endif
                        @if($history->maintenance->vendor_name)
                        <div>
                            <small class="text-muted">Vendor:</small>
                            <div>{{ $history->maintenance->vendor_name }}</div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Additional details for assignment records -->
                @if($history->module == 'assignment' && $history->assignment)
                <div class="mt-3 p-3 bg-warning bg-opacity-10 rounded-3">
                    <div class="d-flex flex-wrap gap-3">
                        <div>
                            <small class="text-muted">Assignment Status:</small>
                            <div>
                                <span class="badge bg-{{ getStatusColor($history->assignment->status, 'assignment') }}">
                                    {{ getStatusName($history->assignment->status, 'assignment') }}
                                </span>
                            </div>
                        </div>
                        @if($history->assignment->assigned_date)
                        <div>
                            <small class="text-muted">Assigned Date:</small>
                            <div>{{ \Carbon\Carbon::parse($history->assignment->assigned_date)->format('d M Y') }}</div>
                        </div>
                        @endif
                        @if($history->assignment->return_date)
                        <div>
                            <small class="text-muted">Return Date:</small>
                            <div>{{ \Carbon\Carbon::parse($history->assignment->return_date)->format('d M Y') }}</div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>

    <!-- Item Modal -->
<!-- Item Modal -->
@if($history->item)
<div class="modal fade" id="itemModal{{ $history->item_id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">Item Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Item Name:</strong> {{ $history->item->item_name }}</p>
                        <p><strong>Item Code:</strong> {{ $history->item->item_code }}</p>
                        <p><strong>Category:</strong>
                            <span class="badge bg-secondary">
                                {{ $history->item->category->category_name ?? 'N/A' }}
                            </span>
                        </p>
                        <p><strong>Brand:</strong> {{ $history->item->brand ?? 'N/A' }}</p>
                        <p><strong>Model:</strong> {{ $history->item->model_number ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Serial Number:</strong> {{ $history->item->serial_number ?? 'N/A' }}</p>
                        <p><strong>Purchase Cost:</strong> ${{ number_format($history->item->purchase_cost ?? 0, 2) }}</p>
                        <p><strong>Warranty Expiry:</strong> {{ $history->item->warranty_expiry ?? 'N/A' }}</p>
                        <p><strong>Item Type:</strong>
                            <span class="badge bg-{{ getItemTypeColor($history->item->item_type) }}">
                                {{ getItemTypeIcon($history->item->item_type) }} {{ getItemTypeName($history->item->item_type) }}
                            </span>
                        </p>
                        <p><strong>Status:</strong>
                            <span class="badge bg-{{ $history->item->delete_status ? 'success' : 'danger' }}">
                                {{ $history->item->delete_status ? 'Active' : 'Deleted' }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endif



@empty
    <div class="text-center py-5">
        <i class="bi bi-inbox fs-1 text-muted"></i>
        <p class="text-muted mt-3">No history records found</p>
    </div>
@endforelse

</div>

<!-- Pagination -->
<div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div class="text-muted small">
        Showing {{ $histories->firstItem() ?? 0 }} to {{ $histories->lastItem() ?? 0 }} of {{ $histories->total() }} results
    </div>
    <div>
        {{ $histories->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
</div>

</div>

<style>
/* Timeline Styling */
.timeline-container {
    position: relative;
}

.timeline-container::before {
    content: '';
    position: absolute;
    left: 35px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, #0d6efd, #0dcaf0);
}

.timeline-item {
    position: relative;
    margin-left: 20px;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: -8px;
    top: 30px;
    width: 16px;
    height: 16px;
    background: #0d6efd;
    border: 3px solid white;
    border-radius: 50%;
    z-index: 1;
    box-shadow: 0 0 0 2px #0d6efd;
}

.timeline-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Hover Effects */
.hover-shadow {
    transition: all 0.3s ease;
}

.hover-shadow:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
}

.transition-all {
    transition: all 0.3s ease;
}

/* Data Grid Styling */
.data-grid {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.data-row {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    padding: 4px 0;
    border-bottom: 1px solid #f0f0f0;
}

.data-label {
    font-weight: 600;
    color: #6c757d;
    font-size: 0.85rem;
}

.data-value {
    color: #212529;
    font-size: 0.9rem;
    word-break: break-word;
    text-align: right;
    max-width: 60%;
}

.new-data, .old-data {
    background: #f8f9fa;
}

/* Badge Colors */
.bg-purple {
    background-color: #6f42c1;
    color: white;
}

.bg-success-subtle {
    background-color: #d1e7dd;
}

.bg-info-subtle {
    background-color: #cff4fc;
}

.bg-warning-subtle {
    background-color: #fff3cd;
}

.bg-danger-subtle {
    background-color: #f8d7da;
}

/* Gradient Card */
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Responsive */
@media (max-width: 768px) {
    .timeline-container::before {
        left: 20px;
    }

    .timeline-item {
        margin-left: 10px;
    }

    .timeline-item::before {
        left: -13px;
    }

    .data-row {
        flex-direction: column;
        gap: 4px;
    }

    .data-value {
        text-align: left;
        max-width: 100%;
    }
}

/* Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate__animated {
    animation-duration: 0.5s;
}

.animate__fadeInUp {
    animation-name: fadeInUp;
}
</style>

<!-- Add Font Awesome/Bootstrap Icons if not already included -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</x-layout>
