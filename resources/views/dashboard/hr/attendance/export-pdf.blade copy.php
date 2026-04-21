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
            margin-bottom: 20px;
            border-bottom: 2px solid #1a56db;
            padding-bottom: 15px;
        }

        .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #1a56db;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .report-title {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
            margin-top: 8px;
        }

        .report-info {
            display: flex;
            justify-content: space-between;
            margin-top: 12px;
            font-size: 9px;
            color: #666;
            flex-wrap: wrap;
        }

        .info-box {
            background: #f8f9fa;
            padding: 6px 12px;
            border-radius: 4px;
            margin-bottom: 5px;
            border-left: 3px solid #1a56db;
        }

        /* Employee Card */
        .employee-card {
            margin-bottom: 25px;
            page-break-inside: avoid;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
        }

        .employee-header {
            background: #1a56db;
            color: white;
            padding: 10px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .employee-name {
            font-size: 13px;
            font-weight: bold;
        }

        .employee-details {
            font-size: 10px;
            opacity: 0.9;
        }

        .employee-id {
            background: rgba(255,255,255,0.2);
            padding: 3px 8px;
            border-radius: 15px;
        }

        /* Month Section */
        .month-section {
            margin: 15px 15px;
        }

        .month-title {
            font-size: 12px;
            font-weight: bold;
            background: #f0f4fc;
            padding: 8px 12px;
            border-left: 4px solid #1a56db;
            margin-bottom: 10px;
        }

        /* Summary Table */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .summary-table th {
            background: #e8edf5;
            padding: 8px 10px;
            text-align: left;
            font-weight: 600;
            font-size: 9px;
            border: 1px solid #ddd;
        }

        .summary-table td {
            padding: 6px 10px;
            border: 1px solid #ddd;
            font-size: 9px;
        }

        .summary-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }

        .total-row {
            background-color: #e8edf5 !important;
            font-weight: bold;
        }

        /* Status Colors */
        .present { color: #10b981; }
        .absent { color: #ef4444; }
        .late { color: #f59e0b; }
        .holiday { color: #8b5cf6; }
        .dayoff { color: #6b7280; }
        .leave { color: #f97316; }

        /* Footer */
        .footer {
            margin-top: 25px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 8px;
            color: #999;
            text-align: center;
        }

        /* Grand Total Section */
        .grand-total {
            margin-top: 20px;
            background: #f0f4fc;
            padding: 12px 15px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .grand-total-item {
            font-size: 10px;
        }

        .grand-total-value {
            font-weight: bold;
            font-size: 12px;
            color: #1a56db;
        }

        @media print {
            body {
                padding: 0;
            }
            .employee-card {
                break-inside: avoid;
            }
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <div class="company-name">RAIYANN INFOTECH</div>
        <div class="report-title">{{ $title }}</div>
        <div class="report-info">
            <div class="info-box">
                <strong>Department:</strong> {{ $department_name }}
            </div>
            <div class="info-box">
                <strong>Period:</strong> {{ $report_period }}
            </div>
            <div class="info-box">
                <strong>Generated:</strong> {{ $report_generated_date }}
            </div>
        </div>
    </div>

    @foreach($monthly_data as $employeeData)
        @php
            $employee = $employeeData['employee'];
            $months = $employeeData['months'];
        @endphp

        <div class="employee-card">
            <!-- Employee Header -->
            <div class="employee-header">
                <div>
                    <span class="employee-name">{{ $employee->fullname ?? $employee->emp_id }}</span>
                    <div class="employee-details">
                        {{ $employee->Departmentid->dep_name ?? 'No Department' }} |
                        {{ $employee->designation->des_name ?? 'No Designation' }}
                    </div>
                </div>
                <div>
                    <span class="employee-id">ID: {{ $employee->employee_id ?? 'N/A' }}</span>
                </div>
            </div>

            <!-- Month-wise Summary Tables -->
            @foreach($months as $monthData)
                @php
                    $summary = $monthData['summary'];
                    // Calculate total days from summary (present + late + absent + holiday + dayoff + leaves)
                    $totalDaysFromSummary = $summary['present'] + $summary['late'] + $summary['absent'] +
                                            $summary['holiday'] + $summary['dayoff'] +
                                            $summary['privilege_leave'] + $summary['casual_leave'] + $summary['sick_leave'];

                  $actualDaysInRange = isset($monthData['total_days_in_range']) ? $monthData['total_days_in_range'] : $monthData['days_in_month'];
$isPartialMonth = ($actualDaysInRange != $monthData['days_in_month']);
$displayDaysText = $isPartialMonth ? $actualDaysInRange . ' days (selected range)' : $monthData['days_in_month'] . ' days';
                @endphp

                <div class="month-section">
                    <div class="month-title">
                        Month: {{ $monthData['month_year'] }} [{{ $displayDaysText }}]
                    </div>

                    <table class="summary-table">
                        <thead>
                            <tr>
                                <th width="30%">S.No</th>
                                <th width="50%">Attendance Type</th>
                                <th width="20%" class="text-right">No. of Days</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Present</td>
                                <td class="text-right font-bold present">{{ $summary['present'] }}</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Late</td>
                                <td class="text-right font-bold late">{{ $summary['late'] }}</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Absent</td>
                                <td class="text-right font-bold absent">{{ $summary['absent'] }}</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Day Off</td>
                                <td class="text-right font-bold dayoff">{{ $summary['dayoff'] }}</td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Holidays</td>
                                <td class="text-right font-bold holiday">{{ $summary['holiday'] }}</td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>Privilege Leave</td>
                                <td class="text-right font-bold leave">{{ $summary['privilege_leave'] }}</td>
                            </tr>

                            <tr>
                                <td>7</td>
                                <td>Casual Leave</td>
                                <td class="text-right font-bold leave">{{ $summary['casual_leave'] }}</td>
                            </tr>


                            <tr>
                                <td>8</td>
                                <td>Sick Leave</td>
                                <td class="text-right font-bold leave">{{ $summary['sick_leave'] }}</td>
                            </tr>

                            <tr>
                                <td colspan="2" class="font-bold">Total</td>
                                <td class="text-right font-bold">{{ $totalDaysFromSummary }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
    @endforeach

    <!-- Grand Total Footer -->
    <div class="grand-total">
        <div class="grand-total-item">
            <strong>Total Employees:</strong>
            <span class="grand-total-value">{{ $total_employees }}</span>
        </div>
        <div class="grand-total-item">
            <strong>Report Period:</strong>
            <span class="grand-total-value">{{ $start_date }} to {{ $end_date }} ({{ $date_range_days }} days)</span>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div>This is a system generated report from Raiyann Infotech HR Management System</div>
        <div>For any discrepancies, please contact HR Department</div>
    </div>

</body>
</html>
