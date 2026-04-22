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
            font-size: 10px;
            line-height: 1.4;
            color: #333;
            padding: 20px;
        }

        /* Header Styles */
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #2c7da0;
        }

        .company-logo {
            max-height: 60px;
            margin-bottom: 10px;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #2c7da0;
            margin-bottom: 5px;
        }

        .report-title {
            font-size: 16px;
            font-weight: bold;
            margin: 10px 0 5px;
        }

        .report-info {
            font-size: 9px;
            color: #666;
            margin-bottom: 3px;
        }

        /* Summary Cards */
        .summary-container {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .summary-card {
            flex: 1;
            background: #f8f9fa;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
            border: 1px solid #e0e0e0;
        }

        .summary-card h4 {
            font-size: 11px;
            color: #666;
            margin-bottom: 5px;
        }

        .summary-card .value {
            font-size: 18px;
            font-weight: bold;
            color: #2c7da0;
        }

        .summary-card .label {
            font-size: 9px;
            color: #888;
        }

        /* Category Summary Table */
        .sub-summary {
            margin-bottom: 20px;
        }

        .sub-summary h4 {
            font-size: 12px;
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
            padding: 6px 8px;
            text-align: left;
        }

        .summary-table th {
            background-color: #e9ecef;
            font-weight: bold;
            font-size: 9px;
        }

        .summary-table td {
            font-size: 9px;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .items-table th {
            background-color: #2c7da0;
            color: white;
            padding: 8px 6px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
        }

        .items-table td {
            border: 1px solid #ddd;
            padding: 6px;
            font-size: 8px;
        }

        .items-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .items-table tr:hover {
            background-color: #f1f3f5;
        }

        /* Footer */
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            text-align: center;
            font-size: 8px;
            color: #999;
            border-top: 1px solid #eee;
        }

        /* Badge */
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 12px;
            font-size: 7px;
            font-weight: bold;
        }

        .badge-new {
            background-color: #28a745;
            color: white;
        }

        .badge-refurbished {
            background-color: #17a2b8;
            color: white;
        }

        /* Page break */
        .page-break {
            page-break-before: always;
        }

        /* Text alignment */
        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
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

    <!-- Main Summary Cards -->
    <div class="summary-container">
        <div class="summary-card">
            <h4>Total Items</h4>
            <div class="value">{{ $total_items }}</div>
            <div class="label">Inventory Items</div>
        </div>
        <div class="summary-card">
            <h4>Total Quantity</h4>
            <div class="value">{{ number_format($total_quantity) }}</div>
            <div class="label">Units in Stock</div>
        </div>
        <div class="summary-card">
            <h4>Total Cost</h4>
            <div class="value">₹ {{ number_format($total_cost, 2) }}</div>
            <div class="label">Purchase Value</div>
        </div>
        <div class="summary-card">
            <h4>Average Cost</h4>
            <div class="value">₹ {{ $total_items > 0 ? number_format($total_cost / $total_items, 2) : '0.00' }}</div>
            <div class="label">Per Item Average</div>
        </div>
    </div>

    <!-- Category Summary -->
    <div class="sub-summary">
        <h4>Summary by Category</h4>
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Category</th>
                    <th class="text-right">Items Count</th>
                    <th class="text-right">Total Quantity</th>
                    <th class="text-right">Total Cost (₹)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories_summary as $categoryName => $summary)
                <tr>
                    <td>{{ $categoryName }}</td>
                    <td class="text-right">{{ $summary['count'] }}</td>
                    <td class="text-right">{{ number_format($summary['total_quantity']) }}</td>
                    <td class="text-right">₹ {{ number_format($summary['total_cost'], 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Item Type Summary -->
    <div class="sub-summary">
        <h4>Summary by Item Type</h4>
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Item Type</th>
                    <th class="text-right">Items Count</th>
                    <th class="text-right">Total Quantity</th>
                    <th class="text-right">Total Cost (₹)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($item_type_summary as $type => $summary)
                @if($summary['count'] > 0)
                <tr>
                    <td>{{ ucfirst($type) }}</td>
                    <td class="text-right">{{ $summary['count'] }}</td>
                    <td class="text-right">{{ number_format($summary['total_quantity']) }}</td>
                    <td class="text-right">₹ {{ number_format($summary['total_cost'], 2) }}</td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Items Details Table -->
    <div class="sub-summary">
        <h4>Item Details</h4>
        <table class="items-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Item Name</th>
                    <th>Item Code</th>
                    <th>Category</th>
                    <th>Item Type</th>
                    <th>Brand</th>
                    <th>Model No.</th>
                    <th>Serial No.</th>
                    <th>Quantity</th>
                    <th>Cost (₹)</th>
                    <th>Purchase Date</th>
                    <th>Vendor</th>
                    <th>Warranty Expiry</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">
                        @if($item->item_image && file_exists(public_path('inventory_images/'.$item->item_image)))
                            ✓
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $item->item_name }}</td>
                    <td>{{ $item->item_code }}</td>
                    <td>{{ $item->category->category_name ?? '-' }}</td>
                   <td class="text-center">
    <span class="badge {{ $item->item_type == 1 ? 'badge-refurbished' : 'badge-new' }}">
        {{ $item->item_type == 1 ? 'Refurbished' : 'New' }}
    </span>
</td>
                    <td>{{ $item->brand ?? '-' }}</td>
                    <td>{{ $item->model_number ?? '-' }}</td>
                    <td>{{ $item->serial_number ?? '-' }}</td>
                    <td class="text-right">{{ number_format($item->quantity) }}</td>
                    <td class="text-right">₹ {{ number_format($item->purchase_cost, 2) }}</td>
                    <td>{{ $item->purchase_date ? \Carbon\Carbon::parse($item->purchase_date)->format('d-m-Y') : '-' }}</td>
                    <td>{{ $item->vendor_name ?? '-' }}</td>
                    <td>{{ $item->warranty_expiry ? \Carbon\Carbon::parse($item->warranty_expiry)->format('d-m-Y') : '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>This is a system-generated report. For any discrepancies, please contact the inventory department.</p>
        <p>RAIYAAN INFOTECH - Inventory Management System</p>
    </div>

</body>
</html>
