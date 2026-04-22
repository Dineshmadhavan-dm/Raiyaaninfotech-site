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
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
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
        .badge-assigned {
            background-color: #ffc107;
            color: #856404;
            padding: 2px 6px;
            border-radius: 12px;
            font-size: 7px;
            font-weight: bold;
            display: inline-block;
        }
        .badge-returned {
            background-color: #28a745;
            color: white;
            padding: 2px 6px;
            border-radius: 12px;
            font-size: 7px;
            font-weight: bold;
            display: inline-block;
        }
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
        .summary-table th, .summary-table td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
        }
        .summary-table th {
            background-color: #e9ecef;
            font-weight: bold;
            font-size: 9px;
        }
        .text-right {
            text-align: right;
        }
        .assignments-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .assignments-table th {
            background-color: #2c7da0;
            color: white;
            padding: 8px 6px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
        }
        .assignments-table td {
            border: 1px solid #ddd;
            padding: 6px;
            font-size: 8px;
        }
        .assignments-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            text-align: center;
            font-size: 8px;
            color: #999;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="header">
        @if($company_logo)
            <img src="{{ $company_logo }}" class="company-logo">
        @endif
        <div class="company-name">{{ $company_name }}</div>
        <div class="report-title">{{ $title }}</div>
        <div class="report-info">Filter: {{ $filter_description }}</div>
        <div class="report-info">Generated On: {{ $report_generated_date }}</div>
    </div>

    <div class="summary-container">
        <div class="summary-card">
            <h4>Total Assignments</h4>
            <div class="value">{{ $total_assignments }}</div>
        </div>
        <div class="summary-card">
            <h4>Currently Assigned</h4>
            <div class="value">{{ $assigned_count }}</div>
        </div>
        <div class="summary-card">
            <h4>Returned</h4>
            <div class="value">{{ $returned_count }}</div>
        </div>
    </div>

    <div class="sub-summary">
        <h4>Summary by Department</h4>
        <table class="summary-table">
            <thead>
                <tr><th>Department</th><th class="text-right">Total</th><th class="text-right">Assigned</th><th class="text-right">Returned</th></tr>
            </thead>
            <tbody>
                @foreach($department_summary as $deptName => $summary)
                <tr>
                    <td>{{ $deptName }}</td>
                    <td class="text-right">{{ $summary['total'] }}</td>
                    <td class="text-right">{{ $summary['assigned'] }}</td>
                    <td class="text-right">{{ $summary['returned'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="sub-summary">
        <h4>Assignment Details</h4>
        <table class="assignments-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Item Name</th>
                    <th>Department</th>
                    <th>Employee</th>
                    <th>Status</th>
                    <th>Assigned Date</th>
                    <th>Return Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($assignments as $index => $a)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $a->item->item_name ?? '-' }}</td>
                    <td>{{ $a->department->dep_name ?? '-' }}</td>
                    <td>{{ $a->employee->fullname ?? '-' }}</td>
                    <td class="text-center">
                    <span class="{{ $a->status == 0 ? 'badge-assigned' : 'badge-returned' }}">
    {{ $a->status == 0 ? 'Assigned' : 'Returned' }}
</span>
                    </td>
                    <td>{{ $a->assigned_date ? \Carbon\Carbon::parse($a->assigned_date)->format('d-m-Y') : '-' }}</td>
                    <td>{{ $a->return_date ? \Carbon\Carbon::parse($a->return_date)->format('d-m-Y') : '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>{{ $company_name }} - Inventory Management System</p>
    </div>
</body>
</html>
