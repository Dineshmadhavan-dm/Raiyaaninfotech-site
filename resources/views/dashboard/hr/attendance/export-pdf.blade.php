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
            padding: 15px;
            background: #fff;
        }

        /* Header Styles - Corporate Classic */
        .header {
            margin-bottom: 20px;
            border-bottom: 3px solid #1a56db;
            padding-bottom: 12px;
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .logo-section {
            flex-shrink: 0;
        }

        .company-logo {
            max-height: 60px;
            max-width: 180px;
            object-fit: contain;
        }

        .title-section {
            text-align: right;
            flex: 1;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #1a56db;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }

        .report-title {
            font-size: 13px;
            font-weight: bold;
            color: #2c3e50;
            margin-top: 4px;
        }

        .report-info {
            display: flex;
            justify-content: space-between;
            margin-top: 12px;
            flex-wrap: wrap;
            gap: 8px;
        }

        .info-box {
            background: #f8fafc;
            padding: 6px 12px;
            border-radius: 4px;
            border-left: 3px solid #1a56db;
            font-size: 9px;
        }

        .info-box strong {
            color: #1a56db;
        }

        /* Employee Card - Classic Box */
        .employee-card {
            margin-bottom: 20px;
            page-break-inside: avoid;
            break-inside: avoid;
            border: 1px solid #cbd5e1;
            background: #fff;
        }

        /* Force page break only when necessary */
        .page-break {
            page-break-before: always;
            break-before: page;
        }

        .employee-header {
            background: #1e40af;
            color: white;
            padding: 8px 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .employee-info {
            flex: 1;
        }

        .employee-name {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .employee-details {
            font-size: 8px;
            opacity: 0.9;
        }

        .employee-id-badge {
            background: #3b82f6;
            padding: 3px 8px;
            font-size: 8px;
            font-weight: bold;
            border-radius: 3px;
        }

        /* Month Section */
        .month-section {
            margin: 12px;
        }

        .month-title {
            font-size: 11px;
            font-weight: bold;
            background: #f1f5f9;
            padding: 5px 10px;
            border-left: 4px solid #1e40af;
            margin-bottom: 8px;
            color: #1e293b;
        }

        /* Summary Table - Classic Grid */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            font-size: 9px;
        }

        .summary-table th {
            background: #e2e8f0;
            padding: 6px 8px;
            text-align: left;
            font-weight: bold;
            color: #1e293b;
            border: 1px solid #cbd5e1;
            font-size: 9px;
        }

        .summary-table td {
            padding: 5px 8px;
            border: 1px solid #cbd5e1;
            font-size: 9px;
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

        /* Status Colors */
        .present { color: #10b981; font-weight: bold; }
        .absent { color: #ef4444; font-weight: bold; }
        .late { color: #f59e0b; font-weight: bold; }
        .holiday { color: #8b5cf6; font-weight: bold; }
        .dayoff { color: #6b7280; font-weight: bold; }
        .leave { color: #f97316; font-weight: bold; }

        /* Summary Stats Bar */
        .summary-stats-short {
            margin-top: 6px;
            padding: 5px 10px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            font-size: 8px;
            text-align: center;
        }

        .summary-stats-short .stat-label {
            color: #64748b;
        }

        .summary-stats-short .stat-value {
            font-weight: bold;
            margin-right: 6px;
        }

        .summary-heading {
            font-weight: bold;
            margin-bottom: 4px;
            font-size: 9px;
            color: #1e40af;
        }

        /* Grand Total Section - Classic Footer */
        .grand-total {
            margin-top: 20px;
            background: #f1f5f9;
            padding: 10px 15px;
            border: 1px solid #cbd5e1;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .grand-total-item {
            font-size: 9px;
        }

        .grand-total-item strong {
            color: #1e293b;
        }

        .grand-total-value {
            font-weight: bold;
            font-size: 11px;
            color: #1e40af;
            margin-left: 6px;
        }

        /* Footer */
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #cbd5e1;
            font-size: 7px;
            color: #64748b;
            text-align: center;
        }

        .footer p {
            margin: 2px 0;
        }

        /* Print Optimizations */
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            .employee-card {
                break-inside: avoid;
                page-break-inside: avoid;
            }
            .summary-table th, .summary-table td {
                border-color: #000 !important;
            }
        }
    </style>
</head>
<body>

    <!-- Corporate Classic Header -->
    <div class="header">
        <div class="header-top">
            <div class="title-section">
                @if(isset($company_logo) && $company_logo)
                <div class="logo-section">
                    <img src="{{ $company_logo }}" class="company-logo" alt="Company Logo">
                </div>
                @endif
                @if(isset($company_name) && $company_name)
                <div class="company-name">{{ strtoupper($company_name) }}</div>
                @else
                <div class="company-name">RAIYAAN INFOTECH</div>
                @endif
                <div class="report-title">{{ $title }}</div>
            </div>
        </div>

        <div class="report-info">



          <div class="info-box">
                <strong>Total Employees:</strong> {{ $total_employees }}
            </div>
            <div class="info-box">
                <strong>Department:</strong> {{ $department_name }}
            </div>
            <div class="info-box">
                <strong>Report Period:</strong> {{ $report_period }}
            </div>
            <div class="info-box">
                <strong>Generated On:</strong> {{ $report_generated_date }}
            </div>
            <div class="info-box">
                <strong>Total Days:</strong> {{ $total_days ?? $date_range_days }} Days
            </div>
        </div>
    </div>

    <!-- Employee Data Loop -->
    @foreach($monthly_data as $index => $employeeData)
        @php
            $employee = $employeeData['employee'];
            $months = $employeeData['months'];
        @endphp

        <div class="employee-card">
            <!-- Employee Header -->
            <div class="employee-header">
                <div class="employee-info">
                    <div class="employee-name">{{ $employee->fullname ?? $employee->emp_name ?? $employee->name ?? 'Employee' }}</div>
                    <div class="employee-details">
                        {{ $employee->Departmentid->dep_name ?? 'No Department' }}
                        @if(isset($employee->Designationid->des_name))
                        | {{ $employee->Designationid->des_name }}
                        @endif
                    </div>
                </div>
                <div class="employee-id-badge">
                    ID: {{ $employee->employee_id ?? $employee->emp_id ?? 'N/A' }}
                </div>
            </div>

            <!-- Month-wise Tables -->
            @foreach($months as $monthData)
                @php
                    $summary = $monthData['summary'];

                    // FIXED: Total days should equal actual days in range, not sum of attendance types
                    // The actual days in the selected period for this month
                    $actualDaysInRange = isset($monthData['total_days_in_range']) ? $monthData['total_days_in_range'] : $monthData['days_in_month'];
                    $isPartialMonth = ($actualDaysInRange != $monthData['days_in_month']);
                    $displayDaysText = $isPartialMonth ? $actualDaysInRange . ' days (selected range)' : $monthData['days_in_month'] . ' days';

                    // FIXED: Working days = Present + Late + Holiday + DayOff (not absent or leaves)
                    $workingDays = $summary['present'] + $summary['late'] + $summary['holiday'] + $summary['dayoff'];
                    $attendancePercentage = $workingDays > 0 ? round(($summary['present'] + $summary['late']) / $workingDays * 100, 1) : 0;

                    // FIXED: Total from summary should equal actual days in range
                    // Ensure consistency by using actual days for total
                    $totalDaysToDisplay = $actualDaysInRange;
                @endphp

                <div class="month-section">
                    <div class="month-title">
                        MONTH: {{ $monthData['month_year'] }} | {{ $displayDaysText }}
                        @if(!$isPartialMonth)
                        | Attendance Rate: {{ $attendancePercentage }}% (based on Present/Late out of Working Days)
                        @endif
                    </div>

                    <table class="summary-table">
                        <thead>
                            <tr>
                                <th width="8%">S.No</th>
                                <th width="52%">Attendance Type</th>
                                <th width="20%" class="text-right">Days</th>
                                <th width="20%" class="text-center">Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">1</td>
                                <td>Present</td>
                                <td class="text-right present">{{ $summary['present'] }}</td>
                                <td class="text-center">✓</td>
                            </tr>
                            <tr>
                                <td class="text-center">2</td>
                                <td>Late Arrival</td>
                                <td class="text-right late">{{ $summary['late'] }}</td>
                                <td class="text-center">⚠</td>
                            </tr>
                            <tr>
                                <td class="text-center">3</td>
                                <td>Absent</td>
                                <td class="text-right absent">{{ $summary['absent'] }}</td>
                                <td class="text-center">✗</td>
                            </tr>
                            <tr>
                                <td class="text-center">4</td>
                                <td>Day Off</td>
                                <td class="text-right dayoff">{{ $summary['dayoff'] }}</td>
                                <td class="text-center">◯</td>
                            </tr>
                            <tr>
                                <td class="text-center">5</td>
                                <td>Holidays</td>
                                <td class="text-right holiday">{{ $summary['holiday'] }}</td>
                                <td class="text-center">★</td>
                            </tr>
                            <tr>
                                <td class="text-center">6</td>
                                <td>Privilege Leave</td>
                                <td class="text-right leave">{{ $summary['privilege_leave'] }}</td>
                                <td class="text-center">PL</td>
                            </tr>
                            <tr>
                                <td class="text-center">7</td>
                                <td>Casual Leave</td>
                                <td class="text-right leave">{{ $summary['casual_leave'] }}</td>
                                <td class="text-center">CL</td>
                            </tr>
                            <tr>
                                <td class="text-center">8</td>
                                <td>Sick Leave</td>
                                <td class="text-right leave">{{ $summary['sick_leave'] }}</td>
                                <td class="text-center">SL</td>
                            </tr>
                            <tr style="background-color: #f1f5f9;">
                                <td colspan="2" class="font-bold text-right">TOTAL</td>
                                <!-- FIXED: Use actual days in range, not sum of attendance types -->
                                <td class="text-right font-bold">{{ $totalDaysToDisplay }}</td>
                                <td class="text-center">{{ $totalDaysToDisplay }} days</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Summary Stats - Short Summary with Heading -->
                    <div class="summary-stats-short">
                        <div class="summary-heading">Short Summary</div>
                        <div class="summary-content">
                            <span class="stat-label">Present:</span> <span class="stat-value present">{{ $summary['present'] }}</span> |
                            <span class="stat-label">Late:</span> <span class="stat-value late">{{ $summary['late'] }}</span> |
                            <span class="stat-label">Absent:</span> <span class="stat-value absent">{{ $summary['absent'] }}</span> |
                            <span class="stat-label">Leaves:</span> <span class="stat-value leave">{{ $summary['privilege_leave'] + $summary['casual_leave'] + $summary['sick_leave'] }}</span> |
                            <span class="stat-label">Holidays:</span> <span class="stat-value holiday">{{ $summary['holiday'] }}</span> |
                            <span class="stat-label">Day Off:</span> <span class="stat-value dayoff">{{ $summary['dayoff'] }}</span> |
                            <span class="stat-label">Working Days:</span> <span class="stat-value">{{ $workingDays }}</span> |
                            <span class="stat-label">Attendance %:</span> <span class="stat-value">{{ $attendancePercentage }}%</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>


    @endforeach



    <!-- Footer -->
    <div class="footer">
        <p>This is a system generated report from {{ $company_name ?? 'Raiyaan Infotech' }} HR Management System</p>
        <p>For any discrepancies, please contact HR Department | Report Generated: {{ $report_generated_date }}</p>
        <p>© {{ date('Y') }} {{ $company_name ?? 'Raiyaan Infotech' }}. All rights reserved.</p>
    </div>

</body>
</html>
