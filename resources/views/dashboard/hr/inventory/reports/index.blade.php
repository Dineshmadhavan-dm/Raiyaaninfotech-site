<x-layout>
    @section('title', 'Report Management')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet" />

    <style>
        .report-tab {
            transition: all 0.3s ease;
        }
        .report-tab .nav-link {
            color: #6c757d;
            border: none;
            padding: 12px 24px;
            font-weight: 500;
            border-radius: 10px;
            margin: 0 4px;
        }
        .report-tab .nav-link.active {
            background: linear-gradient(135deg, #2c7da0 0%, #1f5e7a 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(44,125,160,0.3);
        }
        .report-tab .nav-link:hover:not(.active) {
            background: #f0f4f8;
            color: #2c7da0;
        }
        .filter-section {
            background: #f8f9fa;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .filter-title {
            font-size: 14px;
            font-weight: 600;
            color: #2c7da0;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .filter-title i {
            font-size: 18px;
        }
        .btn-export {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s;
        }
        .btn-export:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(40,167,69,0.3);
        }
        .card-header-custom {
            background: linear-gradient(135deg, #2c7da0 0%, #1f5e7a 100%);
            color: white;
            border-radius: 12px 12px 0 0 !important;
            padding: 15px 20px;
        }
        .select2-container .select2-selection--multiple {
            min-height: 42px;
            border-radius: 8px;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #2c7da0;
            color: white;
            border: none;
            border-radius: 20px;
            padding: 2px 8px;
        }
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            padding: 10px 12px;
        }
        .form-control:focus, .form-select:focus {
            border-color: #2c7da0;
            box-shadow: 0 0 0 0.2rem rgba(44,125,160,0.25);
        }
        .range-group {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .range-group .form-control {
            flex: 1;
        }
    </style>

    <div class="container-fluid p-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">📊 Report Management</h3>
                <p class="text-muted mb-0">Generate and export custom reports for inventory management</p>
            </div>
            <div class="text-muted">
                <i class="bi bi-calendar3 me-1"></i> {{ now()->format('d M Y, h:i A') }}
            </div>
        </div>

        <!-- Report Type Tabs -->
        <ul class="nav nav-pills report-tab mb-4" id="reportTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#itemsTab" type="button" role="tab">
                    <i class="bi bi-box-seam me-2"></i>Items Report
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#assignmentsTab" type="button" role="tab">
                    <i class="bi bi-arrow-left-right me-2"></i>Assignments Report
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#maintenanceTab" type="button" role="tab">
                    <i class="bi bi-tools me-2"></i>Maintenance Report
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#categoriesTab" type="button" role="tab">
                    <i class="bi bi-tags me-2"></i>Categories Report
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content">

            <!-- ==================== ITEMS REPORT TAB ==================== -->
            <div class="tab-pane fade show active" id="itemsTab" role="tabpanel">
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                    <div class="card-header-custom">
                        <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i>Inventory Items Report</h5>
                    </div>
                    <div class="card-body p-4">
                        <form id="itemsReportForm" method="GET" action="{{ route('reports.export-items') }}" target="_blank">

                            <!-- Filter Section -->
                            <div class="filter-section">
                                <div class="filter-title">
                                    <i class="bi bi-funnel"></i>
                                    <span>Filter Options</span>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Categories</label>
                                        <select name="category_ids[]" class="form-select items-category-select" multiple>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Select multiple categories (optional)</small>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Item Type</label>
                                        <select name="item_type" class="form-select">
                                            <option value="">All Types</option>
                                            <option value="0">New</option>
                                            <option value="1">Refurbished</option>
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label fw-semibold">Search</label>
                                        <input type="text" name="search" class="form-control" placeholder="Item name, code, or serial number...">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Purchase Date Range</label>
                                        <div class="range-group">
                                            <input type="date" name="purchase_date_from" class="form-control" placeholder="From">
                                            <span>to</span>
                                            <input type="date" name="purchase_date_to" class="form-control" placeholder="To">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Cost Range (₹)</label>
                                        <div class="range-group">
                                            <input type="number" name="cost_min" class="form-control" placeholder="Min Cost" step="0.01">
                                            <span>to</span>
                                            <input type="number" name="cost_max" class="form-control" placeholder="Max Cost" step="0.01">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sort Section -->
                            <div class="filter-section">
                                <div class="filter-title">
                                    <i class="bi bi-sort-down"></i>
                                    <span>Sort Options</span>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Sort By</label>
                                        <select name="sort_by" class="form-select">
                                            <option value="item_name">Item Name</option>
                                            <option value="item_code">Item Code</option>
                                            <option value="purchase_date">Purchase Date</option>
                                            <option value="purchase_cost">Cost</option>
                                            <option value="category_id">Category</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Sort Order</label>
                                        <select name="sort_order" class="form-select">
                                            <option value="asc">Ascending (A-Z / Oldest First)</option>
                                            <option value="desc">Descending (Z-A / Newest First)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Preview Info -->
                            <div class="alert alert-info bg-light border-0 rounded-3 d-flex align-items-center gap-3">
                                <i class="bi bi-info-circle-fill fs-4 text-info"></i>
                                <div>
                                    <strong>Report Preview:</strong> This report will include item details, category breakdown, cost analysis, and quantity summary.
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-export btn-lg">
                                    <i class="bi bi-file-pdf me-2"></i>Generate Items PDF Report
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- ==================== ASSIGNMENTS REPORT TAB ==================== -->
            <div class="tab-pane fade" id="assignmentsTab" role="tabpanel">
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                    <div class="card-header-custom">
                        <h5 class="mb-0"><i class="bi bi-arrow-left-right me-2"></i>Inventory Assignments Report</h5>
                    </div>
                    <div class="card-body p-4">
                        <form id="assignmentsReportForm" method="GET" action="{{ route('reports.export-assignments') }}" target="_blank">

                            <!-- Filter Section -->
                            <div class="filter-section">
                                <div class="filter-title">
                                    <i class="bi bi-funnel"></i>
                                    <span>Filter Options</span>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Assignment Status</label>
                                        <select name="status" class="form-select">
                                            <option value="">All</option>
                                            <option value="0">Assigned</option>
                                            <option value="1">Returned</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Condition Status</label>
                                        <select name="condition_status" class="form-select">
                                            <option value="">All</option>
                                            <option value="1">Active</option>
                                            <option value="0">Scrap</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Search</label>
                                        <input type="text" name="search" class="form-control" placeholder="Item name or employee name...">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Employees</label>
                                        <select name="employee_ids[]" class="form-select assignments-employee-select" multiple>
                                            @foreach($employees as $emp)
                                                <option value="{{ $emp->emp_id }}">{{ $emp->fullname }} ({{ $emp->employee_id }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Departments</label>
                                        <select name="department_ids[]" class="form-select assignments-dept-select" multiple>
                                            @foreach($departments as $dept)
                                                <option value="{{ $dept->dep_id }}">{{ $dept->dep_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Items</label>
                                        <select name="item_ids[]" class="form-select assignments-item-select" multiple>
                                            @foreach($items as $item)
                                                <option value="{{ $item->id }}">{{ $item->item_name }} ({{ $item->item_code }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Assigned Date Range</label>
                                        <div class="range-group">
                                            <input type="date" name="assigned_date_from" class="form-control" placeholder="From">
                                            <span>to</span>
                                            <input type="date" name="assigned_date_to" class="form-control" placeholder="To">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Return Date Range</label>
                                        <div class="range-group">
                                            <input type="date" name="return_date_from" class="form-control" placeholder="From">
                                            <span>to</span>
                                            <input type="date" name="return_date_to" class="form-control" placeholder="To">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sort Section -->
                            <div class="filter-section">
                                <div class="filter-title">
                                    <i class="bi bi-sort-down"></i>
                                    <span>Sort Options</span>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Sort By</label>
                                        <select name="sort_by" class="form-select">
                                            <option value="assigned_date">Assigned Date</option>
                                            <option value="return_date">Return Date</option>
                                            <option value="status">Status</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Sort Order</label>
                                        <select name="sort_order" class="form-select">
                                            <option value="desc">Newest First</option>
                                            <option value="asc">Oldest First</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info bg-light border-0 rounded-3 d-flex align-items-center gap-3">
                                <i class="bi bi-info-circle-fill fs-4 text-info"></i>
                                <div>
                                    <strong>Report Preview:</strong> This report will include assignment details, employee information, department breakdown, and status summary.
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-export btn-lg">
                                    <i class="bi bi-file-pdf me-2"></i>Generate Assignments PDF Report
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- ==================== MAINTENANCE REPORT TAB ==================== -->
            <div class="tab-pane fade" id="maintenanceTab" role="tabpanel">
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                    <div class="card-header-custom">
                        <h5 class="mb-0"><i class="bi bi-tools me-2"></i>Maintenance Report</h5>
                    </div>
                    <div class="card-body p-4">
                        <form id="maintenanceReportForm" method="GET" action="{{ route('reports.export-maintenances') }}" target="_blank">

                            <!-- Filter Section -->
                            <div class="filter-section">
                                <div class="filter-title">
                                    <i class="bi bi-funnel"></i>
                                    <span>Filter Options</span>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Maintenance Type</label>
                                        <select name="maintenance_type" class="form-select">
                                            <option value="">All Types</option>
                                            <option value="1">Service</option>
                                            <option value="2">Upgrade</option>
                                            <option value="0">Scrap</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Status</label>
                                        <select name="status" class="form-select">
                                            <option value="">All</option>
                                            <option value="0">Pending</option>
                                            <option value="1">Completed</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Search</label>
                                        <input type="text" name="search" class="form-control" placeholder="Item name, issue description, vendor...">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Items</label>
                                        <select name="item_ids[]" class="form-select maintenance-item-select" multiple>
                                            @foreach($items as $item)
                                                <option value="{{ $item->id }}">{{ $item->item_name }} ({{ $item->item_code }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Start Date Range</label>
                                        <div class="range-group">
                                            <input type="date" name="start_date_from" class="form-control" placeholder="From">
                                            <span>to</span>
                                            <input type="date" name="start_date_to" class="form-control" placeholder="To">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Cost Range (₹)</label>
                                        <div class="range-group">
                                            <input type="number" name="cost_min" class="form-control" placeholder="Min Cost" step="0.01">
                                            <span>to</span>
                                            <input type="number" name="cost_max" class="form-control" placeholder="Max Cost" step="0.01">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sort Section -->
                            <div class="filter-section">
                                <div class="filter-title">
                                    <i class="bi bi-sort-down"></i>
                                    <span>Sort Options</span>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Sort By</label>
                                        <select name="sort_by" class="form-select">
                                            <option value="start_date">Start Date</option>
                                            <option value="end_date">End Date</option>
                                            <option value="cost">Cost</option>
                                            <option value="status">Status</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Sort Order</label>
                                        <select name="sort_order" class="form-select">
                                            <option value="desc">Newest First</option>
                                            <option value="asc">Oldest First</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info bg-light border-0 rounded-3 d-flex align-items-center gap-3">
                                <i class="bi bi-info-circle-fill fs-4 text-info"></i>
                                <div>
                                    <strong>Report Preview:</strong> This report will include maintenance records, cost analysis, type breakdown, and status summary.
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-export btn-lg">
                                    <i class="bi bi-file-pdf me-2"></i>Generate Maintenance PDF Report
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- ==================== CATEGORIES REPORT TAB ==================== -->
            <div class="tab-pane fade" id="categoriesTab" role="tabpanel">
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                    <div class="card-header-custom">
                        <h5 class="mb-0"><i class="bi bi-tags me-2"></i>Categories Report</h5>
                    </div>
                    <div class="card-body p-4">
                        <form id="categoriesReportForm" method="GET" action="{{ route('reports.export-categories') }}" target="_blank">

                            <!-- Filter Section -->
                            <div class="filter-section">
                                <div class="filter-title">
                                    <i class="bi bi-funnel"></i>
                                    <span>Filter Options</span>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Search Category</label>
                                        <input type="text" name="search" class="form-control" placeholder="Category name...">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Items Filter</label>
                                        <select name="has_items" class="form-select">
                                            <option value="">All Categories</option>
                                            <option value="yes">With Items Only</option>
                                            <option value="no">Empty Categories Only</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Sort Section -->
                            <div class="filter-section">
                                <div class="filter-title">
                                    <i class="bi bi-sort-down"></i>
                                    <span>Sort Options</span>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Sort By</label>
                                        <select name="sort_by" class="form-select">
                                            <option value="category_name">Category Name</option>
                                            <option value="items_count">Number of Items</option>
                                            <option value="created_at">Date Created</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Sort Order</label>
                                        <select name="sort_order" class="form-select">
                                            <option value="asc">Ascending (A-Z)</option>
                                            <option value="desc">Descending (Z-A)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info bg-light border-0 rounded-3 d-flex align-items-center gap-3">
                                <i class="bi bi-info-circle-fill fs-4 text-info"></i>
                                <div>
                                    <strong>Report Preview:</strong> This report will include category details, item count per category, and total value summary.
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-export btn-lg">
                                    <i class="bi bi-file-pdf me-2"></i>Generate Categories PDF Report
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 for multiple selects
            $('.items-category-select').select2({
                placeholder: 'Select categories',
                allowClear: true,
                width: '100%'
            });

            $('.assignments-employee-select').select2({
                placeholder: 'Select employees',
                allowClear: true,
                width: '100%'
            });

            $('.assignments-dept-select').select2({
                placeholder: 'Select departments',
                allowClear: true,
                width: '100%'
            });

            $('.assignments-item-select').select2({
                placeholder: 'Select items',
                allowClear: true,
                width: '100%'
            });

            $('.maintenance-item-select').select2({
                placeholder: 'Select items',
                allowClear: true,
                width: '100%'
            });
        });
    </script>

</x-layout>
