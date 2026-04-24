<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', 'Segoe UI', Arial, sans-serif;
            font-size: 9px;
            line-height: 1.4;
            color: #333;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #2c7da0;
        }

        .company-logo {
            max-height: 50px;
            margin-bottom: 8px;
        }

        .company-name {
            font-size: 16px;
            font-weight: bold;
            color: #2c7da0;
            margin-bottom: 5px;
        }

        .report-title {
            font-size: 14px;
            font-weight: bold;
            margin: 8px 0 5px;
        }

        .report-info {
            font-size: 8px;
            color: #666;
            margin-bottom: 3px;
        }

        /* ========== TABLE-BASED CARD LAYOUT (DOM PDF COMPATIBLE) ========== */
       .card-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 12px;   /* controls GAP between cards */
}

.card-table td {
    padding: 0;
}

/* make cards look like dashboard */
.summary-card {
    background: #ffffff;
    border-radius: 12px;
    padding: 14px;
    border: 1px solid #e5e7eb;

    /* soft shadow like UI */
    box-shadow: 0 3px 8px rgba(0,0,0,0.05);
}

        .card-table td:first-child {
            padding-left: 0;
        }

        .card-table td:last-child {
            padding-right: 0;
        }


        .summary-card h4 {
            font-size: 10px;
            color: #6c757d;
            margin-bottom: 10px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .summary-card .value {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }


        .sub-summary {
            margin-bottom: 20px;
        }

        .sub-summary h4 {
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #2c7da0;
            border-left: 3px solid #2c7da0;
            padding-left: 8px;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .summary-table th,
        .summary-table td {
            border: 1px solid #ddd;
            padding: 5px 8px;
            text-align: left;
        }

        .summary-table th {
            background-color: #e9ecef;
            font-weight: bold;
            font-size: 8px;
        }

        .summary-table td {
            font-size: 8px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .data-table th {
            background-color: #2c7da0;
            color: white;
            padding: 6px 5px;
            text-align: left;
            font-size: 8px;
            font-weight: bold;
        }

        .data-table td {
            border: 1px solid #ddd;
            padding: 5px;
            font-size: 7px;
        }

        .data-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 12px;
            font-size: 7px;
            font-weight: bold;
        }

        .badge-assigned { background-color: #ffc107; color: #333; }
        .badge-returned { background-color: #28a745; color: white; }
        .badge-active { background-color: #17a2b8; color: white; }
        .badge-scrap { background-color: #dc3545; color: white; }
        .badge-pending { background-color: #fd7e14; color: white; }
        .badge-complete { background-color: #28a745; color: white; }
        .badge-new { background-color: #28a745; color: white; }
        .badge-refurbished { background-color: #17a2b8; color: white; }
        .badge-service { background-color: #3498db; color: white; }
        .badge-upgrade { background-color: #9b59b6; color: white; }
        .badge-created { background-color: #28a745; color: white; }
        .badge-updated { background-color: #fd7e14; color: white; }
        .badge-deleted { background-color: #dc3545; color: white; }

        .text-right { text-align: right; }
        .text-center { text-align: center; }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            text-align: center;
            font-size: 7px;
            color: #999;
            border-top: 1px solid #eee;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        @if($company_logo)
            <img src="{{ $company_logo }}" class="company-logo" alt="Logo">
        @endif
        <div class="company-name">{{ $company_name }}</div>
        <div class="report-title">{{ $title }}</div>
        <div class="report-info">Filter: {{ $filter_description }}</div>
        <div class="report-info">Sort By: {{ ucfirst(str_replace('_', ' ', $sort_by)) }} ({{ $sort_order == 'asc' ? 'Ascending' : 'Descending' }})</div>
        <div class="report-info">Generated On: {{ $report_generated_date }}</div>
    </div>

    @if($report_type == 'items')
        <!-- ==================== ITEMS REPORT ==================== -->

        <!-- 5 CARDS USING TABLE (DOM PDF COMPATIBLE) -->
        <table class="card-table">
            <tr>
                <td width="25%">
                    <div class="summary-card card-1">
                        <h4>Total Items</h4>
                        <div class="value">{{ $total_items }}</div>
                    </div>
                </td>

               <td width="25%">
                    <div class="summary-card card-3">
                        <h4>New item</h4>
                        <div class="value">{{$new_items_count}}</div>
                    </div>
                </td>

                <td width="25%">
                    <div class="summary-card card-3">
                        <h4>Refurbished item</h4>
                        <div class="value">{{$refurbished_items_count}}</div>
                    </div>
                </td>


                <td width="25%">
                    <div class="summary-card card-2">
                        <h4>Total Quantity</h4>
                        <div class="value">{{ number_format($total_quantity) }}</div>
                    </div>
                </td>

            </tr>
        </table>


        <table class="card-table">
            <tr>

 <td width="25%">
                    <div class="summary-card card-3">
                        <h4>Total Cost</h4>
                        <div class="value">₹ {{ number_format($total_cost, 2) }}</div>
                    </div>
                </td>



                <td width="25%">
                    <div class="summary-card card-5">
                        <h4>Categories</h4>
                        <div class="value">{{ count($categories_summary) }}</div>
                    </div>
                </td>


                 <td width="25%"></td>
                 <td width="25%"></td>
            </tr>
        </table>


        <!-- Category Summary -->
        @if(count($categories_summary) > 0)
        <div class="sub-summary">
            <h4>Summary by Category</h4>
            <table class="summary-table">
                <thead>
                    <tr><th>Category</th><th class="text-right">Items</th><th class="text-right">Quantity</th><th class="text-right">Total Cost (₹)</th></tr>
                </thead>
                <tbody>
                    @foreach($categories_summary as $catName => $sum)
                    <tr><td>{{ $catName }}</td><td class="text-right">{{ $sum['count'] }}</td><td class="text-right">{{ number_format($sum['total_quantity']) }}</td><td class="text-right">₹ {{ number_format($sum['total_cost'], 2) }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Item Type Summary -->
        <div class="sub-summary">
            <h4>Summary by Item Type</h4>
            <table class="summary-table">
                <thead><tr><th>Type</th><th class="text-right">Count</th><th class="text-right">Quantity</th><th class="text-right">Total Cost (₹)</th></tr></thead>
                <tbody>
                    @foreach($item_type_summary as $type => $sum)
                    @if($sum['count'] > 0)
                    <tr><td>{{ ucfirst($type) }}</td><td class="text-right">{{ $sum['count'] }}</td><td class="text-right">{{ number_format($sum['total_quantity']) }}</td><td class="text-right">₹ {{ number_format($sum['total_cost'], 2) }}</td></tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Items Details Table -->
        <div class="sub-summary">
            <h4>Item Details</h4>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th><th>Item Name</th><th>Item Code</th><th>Category</th><th>Type</th>
                        <th>Brand</th><th>Model</th><th>Serial</th><th>Qty</th><th>Cost (₹)</th>
                        <th>Purchase Date</th><th>Vendor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item->item_name }}</td>
                        <td>{{ $item->item_code }}</td>
                        <td>{{ $item->category->category_name ?? '-' }}</td>
                        <td class="text-center"><span class="badge {{ $item->item_type == 1 ? 'badge-refurbished' : 'badge-new' }}">{{ $item->item_type == 1 ? 'Refurbished' : 'New' }}</span></td>
                        <td>{{ $item->brand ?? '-' }}</td>
                        <td>{{ $item->model_number ?? '-' }}</td>
                        <td>{{ $item->serial_number ?? '-' }}</td>
                        <td class="text-right">{{ number_format($item->quantity) }}</td>
                        <td class="text-right">₹ {{ number_format($item->purchase_cost, 2) }}</td>
                        <td>{{ $item->purchase_date ? \Carbon\Carbon::parse($item->purchase_date)->format('d-m-Y') : '-' }}</td>
                        <td>{{ $item->vendor_name ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    @elseif($report_type == 'assignments')
        <!-- ==================== ASSIGNMENTS REPORT ==================== -->

        <!-- 5 CARDS USING TABLE (DOM PDF COMPATIBLE) -->
        <table class="card-table">
            <tr>
                <td width="20%">
                    <div class="summary-card card-1">
                        <h4>Total Assignments</h4>
                        <div class="value">{{ $total_assignments }}</div>
                    </div>
                </td>
                <td width="20%">
                    <div class="summary-card card-2">
                        <h4>Assigned</h4>
                        <div class="value">{{ $assigned_count }}</div>
                    </div>
                </td>
                <td width="20%">
                    <div class="summary-card card-3">
                        <h4>Returned</h4>
                        <div class="value">{{ $returned_count }}</div>
                    </div>
                </td>
                <td width="20%">
                    <div class="summary-card card-4">
                        <h4>Active Items</h4>
                        <div class="value">{{ $active_count }}</div>
                    </div>
                </td>
                <td width="20%">
                    <div class="summary-card card-5">
                        <h4>Scrap Items</h4>
                        <div class="value">{{ $scrap_count }}</div>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Department Summary -->
        @if(count($department_summary) > 0)
        <div class="sub-summary">
            <h4>Summary by Department</h4>
            <table class="summary-table">
                <thead><tr><th>Department</th><th class="text-right">Total</th><th class="text-right">Assigned</th><th class="text-right">Returned</th></tr></thead>
                <tbody>
                    @foreach($department_summary as $deptName => $sum)
                    <tr><td>{{ $deptName }}</td><td class="text-right">{{ $sum['total'] }}</td><td class="text-right">{{ $sum['assigned'] }}</td><td class="text-right">{{ $sum['returned'] }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Assignments Details Table -->
        <div class="sub-summary">
            <h4>Assignment Details</h4>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th><th>Item</th><th>Employee</th><th>Department</th><th>Condition</th><th>Status</th><th>Assigned Date</th><th>Return Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assignments as $index => $a)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $a->item->item_name ?? '-' }}</td>
                        <td>{{ $a->employee->fullname ?? '-' }}</td>
                        <td>{{ $a->department->dep_name ?? '-' }}</td>
                        <td class="text-center"><span class="badge {{ $a->condition_status == 1 ? 'badge-active' : 'badge-scrap' }}">{{ $a->condition_status == 1 ? 'Active' : 'Scrap' }}</span></td>
                        <td class="text-center"><span class="badge {{ $a->status == 0 ? 'badge-assigned' : 'badge-returned' }}">{{ $a->status == 0 ? 'Assigned' : 'Returned' }}</span></td>
                        <td>{{ $a->assigned_date ? \Carbon\Carbon::parse($a->assigned_date)->format('d-m-Y') : '-' }}</td>
                        <td>{{ $a->return_date ? \Carbon\Carbon::parse($a->return_date)->format('d-m-Y') : '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    @elseif($report_type == 'maintenances')
        <!-- ==================== MAINTENANCE REPORT ==================== -->

        <!-- 5 CARDS USING TABLE (DOM PDF COMPATIBLE) -->
        <table class="card-table">
            <tr>
                <td width="20%">
                    <div class="summary-card card-1">
                        <h4>Total Records</h4>
                        <div class="value">{{ $total_maintenances }}</div>
                    </div>
                </td>
                <td width="20%">
                    <div class="summary-card card-2">
                        <h4>Total Cost</h4>
                        <div class="value">₹ {{ number_format($total_cost, 2) }}</div>
                    </div>
                </td>
                <td width="20%">
                    <div class="summary-card card-3">
                        <h4>Pending</h4>
                        <div class="value">{{ $status_summary['pending'] }}</div>
                    </div>
                </td>
                <td width="20%">
                    <div class="summary-card card-4">
                        <h4>Completed</h4>
                        <div class="value">{{ $status_summary['complete'] }}</div>
                    </div>
                </td>
                <td width="20%">
                    <div class="summary-card card-5">
                        <h4>Completion Rate</h4>
                        <div class="value">{{ $total_maintenances > 0 ? round(($status_summary['complete'] / $total_maintenances) * 100, 1) : 0 }}%</div>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Maintenance Type Summary -->
        <div class="sub-summary">
            <h4>Summary by Type</h4>
            <table class="summary-table">
                <thead><tr><th>Type</th><th class="text-right">Count</th><th class="text-right">Total Cost (₹)</th></tr></thead>
                <tbody>
                    @foreach($type_summary as $type => $sum)
                    @if($sum['count'] > 0)
                    <tr><td>{{ ucfirst($type) }}</td><td class="text-right">{{ $sum['count'] }}</td><td class="text-right">₹ {{ number_format($sum['total_cost'], 2) }}</td></tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Maintenance Details Table -->
        <div class="sub-summary">
            <h4>Maintenance Details</h4>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th><th>Item</th><th>Issue</th><th>Type</th><th>Cost (₹)</th><th>Vendor</th><th>Start Date</th><th>End Date</th><th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($maintenances as $index => $m)
                    @php
                        $typeLabels = ['Scrap', 'Service', 'Upgrade'];
                        $typeClass = match($m->maintenance_type) { 0 => 'badge-scrap', 1 => 'badge-service', 2 => 'badge-upgrade', default => '' };
                    @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $m->item->item_name ?? '-' }}</td>
                        <td>{{ Str::limit($m->issue_description, 40) }}</td>
                        <td><span class="badge {{ $typeClass }}">{{ $typeLabels[$m->maintenance_type] ?? '-' }}</span></td>
                        <td class="text-right">₹ {{ number_format($m->cost, 2) }}</td>
                        <td>{{ $m->vendor_name ?? '-' }}</td>
                        <td>{{ $m->start_date ? \Carbon\Carbon::parse($m->start_date)->format('d-m-Y') : '-' }}</td>
                        <td>{{ $m->end_date ? \Carbon\Carbon::parse($m->end_date)->format('d-m-Y') : '-' }}</td>
                        <td><span class="badge {{ $m->status == 0 ? 'badge-pending' : 'badge-complete' }}">{{ $m->status == 0 ? 'Pending' : 'Complete' }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>



        @elseif($report_type == 'categories')
        <!-- ==================== CATEGORIES REPORT ==================== -->

        <!-- 5 CARDS USING TABLE (DOM PDF COMPATIBLE) -->
        <table class="card-table">
            <tr>
                <td width="20%">
                    <div class="summary-card card-1">
                        <h4>Total Categories</h4>
                        <div class="value">{{ $total_categories }}</div>
                    </div>
                </td>
                <td width="20%">
                    <div class="summary-card card-2">
                        <h4>Total Items</h4>
                        <div class="value">{{ number_format($total_items) }}</div>
                    </div>
                </td>
                <td width="20%">
                    <div class="summary-card card-3">
                        <h4>Total Value</h4>
                        <div class="value">₹ {{ number_format($total_cost, 2) }}</div>
                    </div>
                </td>
                <td width="20%">

                </td>
                <td width="20%">

                </td>
            </tr>
        </table>

        <!-- Categories Details Table -->
        <div class="sub-summary">
            <h4>Category Details</h4>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th><th>Category Name</th><th>Description</th><th class="text-right">Items Count</th><th class="text-right">Total Quantity</th><th class="text-right">Total Value (₹)</th><th>Created Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $index => $cat)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $cat->category_name }}</td>
                        <td>{{ Str::limit($cat->description, 50) ?? '-' }}</td>
                        <td class="text-right">{{ $cat->items->count() }}</td>
                        <td class="text-right">{{ number_format($cat->items->sum('quantity')) }}</td>
                        <td class="text-right">₹ {{ number_format($cat->items->sum('purchase_cost'), 2) }}</td>
                        <td>{{ $cat->created_at ? $cat->created_at->format('d-m-Y') : '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Categories with Most Items -->
        @if($categories->count() > 0)
        <div class="sub-summary">
            <h4>Top 5 Categories by Item Count</h4>
            <table class="summary-table">
                <thead><tr><th>Category</th><th class="text-right">Items Count</th><th class="text-right">Total Value (₹)</th></tr></thead>
                <tbody>
                    @foreach($categories->sortByDesc(function($cat) { return $cat->items->count(); })->take(5) as $cat)
                    <tr><td>{{ $cat->category_name }}</td><td class="text-right">{{ $cat->items->count() }}</td><td class="text-right">₹ {{ number_format($cat->items->sum('purchase_cost'), 2) }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
@endif





    <div class="footer">
        <p>This is a system-generated report. For any discrepancies, please contact the inventory department.</p>
        <p>{{ $company_name }} - Inventory Management System</p>
    </div>

</body>
</html>
