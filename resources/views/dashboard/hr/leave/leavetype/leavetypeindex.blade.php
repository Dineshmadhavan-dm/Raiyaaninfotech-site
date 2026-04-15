<x-layout>
    @section('title', 'Add Leave')

    <div class="container-fluid p-3">
        <x-message />

        <div class="leave-planner-container bg-white">
            <!-- Header Section -->
            <div class="" style="background-color: #f8fafc;">
                <div class="d-flex justify-content-between align-items-center p-2">
                    <h4 class="fw-medium fs-5 mb-0">
                        <i class="bi bi-calendar-x me-2"></i>Add Leave
                    </h4>

                </div>
            </div>

            <div class="row justify-content-between px-3 pb-3">
                <!-- Filter Section -->
                <div class="col-auto p-3">
                    <div class="row justify-content-start">
                        <div class="col-auto">
                            <span class="form-label">Show Entries</span>
                            <select id="entriesPerPage" class="form-select form-control">
                                <option value="5" selected>5</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="75">75</option>
                                <option value="100">100</option>
                            </select>
                        </div>

                        <div class="col-auto" style="margin-top: 1.3em;">
                            <div class="dropdown">
                                <button class="dropdown-toggle control-select" type="button"
                                    id="employeeDropdownFilter" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span id="employeeFilterText">All Employees</span>
                                </button>
                                <ul class="dropdown-menu employee-dropdown p-2" aria-labelledby="employeeDropdownFilter"
                                    style="width: 250px;">
                                    <li>
                                        <input type="text" class="form-control form-control-sm mb-2"
                                            placeholder="Search employees..." id="employeeSearchFilter">
                                    </li>
                                    <li>
                                        <select id="employeeFilterSelect" class="form-select form-select-sm"
                                            size="8" style="width: 100%; border: none;">
                                            <option value="">All Employees</option>
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->emp_id }}">
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-2">
                                                            <img src="{{ asset('employee_images/' . $employee->image) }}"
                                                                alt="" class="avatar rounded-circle"
                                                                style="width: 30px; height: 30px; object-fit: cover;">
                                                        </div>
                                                        <div>
                                                            <p class="mb-0 fw-bold">{{ $employee->fullname }}</p>
                                                            <p class="mb-0 small text-muted">
                                                                {{ $employee->Departmentid->dep_name ?? 'No Department' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </option>
                                            @endforeach
                                        </select>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-auto" style="margin-top: 1.3em;">
                            <div class="dropdown">
                                <button class="dropdown-toggle control-select" type="button"
                                    id="departmentDropdownFilter" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span id="departmentFilterText">All Departments</span>
                                </button>
                                <ul class="dropdown-menu department-dropdown p-2"
                                    aria-labelledby="departmentDropdownFilter" style="width: 200px;">
                                    <li>
                                        <input type="text" class="form-control form-control-sm mb-2"
                                            placeholder="Search departments..." id="departmentSearchFilter">
                                    </li>
                                    <li>
                                        <select id="departmentFilterSelect" class="form-select form-select-sm"
                                            size="8" style="width: 100%; border: none;">
                                            <option value="">All Departments</option>
                                            @foreach ($departments as $department)
                                                <option value="{{ $department->dep_name }}">
                                                    {{ $department->dep_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-auto" style="margin-top: 1.3em;">
                            <div class="dropdown">
                                <button class="dropdown-toggle control-select" type="button"
                                    id="leaveTypeDropdownFilter" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span id="leaveTypeFilterText">All Leave Types</span>
                                </button>
                                <ul class="dropdown-menu leave-type-dropdown p-2"
                                    aria-labelledby="leaveTypeDropdownFilter" style="width: 200px;">
                                    <li>
                                        <input type="text" class="form-control form-control-sm mb-2"
                                            placeholder="Search leave types..." id="leaveTypeSearchFilter">
                                    </li>
                                    <li>
                                        <select id="leaveTypeFilterSelect" class="form-select form-select-sm"
                                            size="8" style="width: 100%; border: none;">
                                            <option value="">All Leave Types</option>
                                            <option value="Privilege Leave (PL)">Privilege Leave (PL)</option>
                                            <option value="Casual Leave (CL)">Casual Leave (CL)</option>
                                            <option value="Sick Leave (SL)">Sick Leave (SL)</option>
                                            <option value="Maternity Leave (ML)">Maternity Leave (ML)</option>
                                            <option value="Compensatory Off (Comp-off)">Compensatory Off (Comp-off)
                                            </option>
                                            <option value="Marriage Leave">Marriage Leave</option>
                                            <option value="Paternity Leave">Paternity Leave </option>
                                            <option value="Bereavement Leave">Bereavement Leave (BL)</option>
                                            <option value="Unpaid Leave">Unpaid Leave (UL)</option>
                                        </select>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-auto" style="margin-top: 1.3em; display: none;"
                            id="clearFiltersBtnContainer">
                            <button class="btn btn-outline-dark" id="clearFiltersBtn">
                                <i class="bi bi-x-circle me-1"></i> Clear Filters
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-auto d-flex align-items-center gap-2" style="margin-top: 1.3em;">
                    <a href="{{ route('leavetype.manage.columns') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-columns-gap me-1"></i> Manage Columns
                    </a>

                    <button class="btn btn-primary" id="addLeaveType">
                        <i class="bi bi-plus-circle me-1"></i> Add Leave
                    </button>
                </div>

            </div>

            <!-- Leave Type Table -->
            <div class="table-responsive p-3">
                <table class="table table-bordered table-hover">
                    <thead class="text-center">
                        <tr>
                            @foreach ($columns as $column)
                                @if (in_array($column['key'], $visibleColumns))
                                    <th>{{ $column['label'] }}</th>
                                @endif
                            @endforeach
                        </tr>
                    </thead>
                    <tbody id="leaveTypeTableBody">
                        @forelse ($groupedleaveTypes as $employeeId => $leaves)
                            @php
                                // Skip if no employee record exists
                                if (!$leaves->first()->employee) {
                                    continue;
                                }

                                $employee = $leaves->first()->employee;
                                $department = $leaves->first()->department;

                                // Initialize all leave types to N/A
                                $leaveData = [
                                    'PL' => 'N/A',
                                    'CL' => 'N/A',
                                    'SL' => 'N/A',
                                    'UL' => 'N/A',
                                    'ML' => 'N/A',
                                    'Comp-off' => 'N/A',
                                    'MAL' => 'N/A',
                                    'PAL' => 'N/A',
                                    'BL' => 'N/A',
                                ];

                                $totalDays = 0;
                                $durations = [];

                                foreach ($leaves as $leave) {
                                    // Map leave type IDs to their names
                                    $typeMap = [
                                        1 => 'PL',
                                        2 => 'CL',
                                        3 => 'SL',
                                        4 => 'MAL',
                                        5 => 'Comp-off',
                                        6 => 'ML',
                                        7 => 'PAL',
                                        8 => 'BL',
                                        9 => 'UL',
                                    ];

                                    $type = $typeMap[$leave->leavetype_name_id] ?? null;
                                    if ($type) {
                                        $leaveData[$type] = $leave->leave_days;
                                        $totalDays += $leave->leave_days;
                                    }

                                    // Collect all durations
                                    if ($leave->leave_start_from && $leave->leave_end_to) {
                                        $durations[] =
                                            date('M d, Y', strtotime($leave->leave_start_from)) .
                                            ' - ' .
                                            date('M d, Y', strtotime($leave->leave_end_to));
                                    }
                                }

                                // Format duration display
                                $durationDisplay = !empty($durations)
                                    ? implode('<br>', array_unique($durations))
                                    : 'N/A';
                            @endphp
                            <tr>
                                @foreach ($columns as $column)
                                    @if (in_array($column['key'], $visibleColumns))
                                        @switch($column['key'])
                                            @case('image')
                                                <td>
                                                    <a href="#" data-id="{{ $employeeId }}" class="view-leave-type">
                                                        <img src="{{ !empty($employee->image) ? asset('employee_images/' . $employee->image) : asset('images/admin_default.jpg') }}"
                                                            alt="{{ $employee->fullname ?? 'Employee' }}"
                                                            class="img-fluid mx-auto d-block rounded-circle" width="50px">
                                                    </a>
                                                </td>
                                            @break

                                            @case('employee')
                                                <td>{{ $employee->fullname }}</td>
                                            @break

                                            @case('department')
                                                <td>{{ $department->dep_name ?? 'N/A' }}</td>
                                            @break

                                            @case('PL')
                                                <td class="text-center  ">{{ $leaveData['PL'] }}</td>
                                            @break

                                            @case('CL')
                                                <td class="text-center ">{{ $leaveData['CL'] }}</td>
                                            @break

                                            @case('SL')
                                                <td class="text-center ">{{ $leaveData['SL'] }}</td>
                                            @break

                                            @case('UL')
                                                <td class="text-center ">{{ $leaveData['UL'] }}</td>
                                            @break

                                            @case('ML')
                                                <td class="text-center ">{{ $leaveData['ML'] }}</td>
                                            @break

                                            @case('Comp-off')
                                                <td class="text-center ">{{ $leaveData['Comp-off'] }}</td>
                                            @break

                                            @case('MAL')
                                                <td class="text-center ">{{ $leaveData['MAL'] }}</td>
                                            @break

                                            @case('PAL')
                                                <td class="text-center ">{{ $leaveData['PAL'] }}</td>
                                            @break

                                            @case('BL')
                                                <td class="text-center ">{{ $leaveData['BL'] }}</td>
                                            @break

                                            @case('duration')
                                                <td>{!! $durationDisplay !!}</td>
                                            @break

                                            @case('total_days')
                                                <td class=" fw-medium">{{ $totalDays }}</td>
                                            @break

                                            @case('action')
                                                <td class="text-center">
                                                    <button class="btn btn-sm text-info view-leave-type"
                                                        data-id="{{ $employeeId }}">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm text-primary edit-leave-type"
                                                        data-id="{{ $employeeId }}">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-sm text-danger delete-leave-type"
                                                        data-id="{{ $employeeId }}">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                            @break
                                        @endswitch
                                    @endif
                                @endforeach
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ count($visibleColumns) }}" class="text-center">No leave records found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="row justify-content-between align-items-center p-3">
                    <div class="col-auto">
                        <span id="leaveShowingInfo">
                            Showing 1 to {{ count($groupedleaveTypes) > 5 ? 5 : count($groupedleaveTypes) }}
                            of {{ count($groupedleaveTypes) }} entries
                        </span>
                    </div>
                    <div class="col-auto">
                        <div class="pagination-controls d-flex align-items-center">
                            <button class="pagination-button" id="prevLeavePage" disabled>
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <span class="page-info mx-2" id="leavePageInfo">
                                Page 1 of {{ ceil(count($groupedleaveTypes) / 5) }}
                            </span>
                            <button class="pagination-button" id="nextLeavePage"
                                {{ count($groupedleaveTypes) <= 5 ? 'disabled' : '' }}>
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- View Modal -->
            <div class="modal fade" id="viewLeaveTypeModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content border-0">
                        <!-- Modal Header -->
                        <div class="modal-header bg-gradient-primary text-white">
                            <h5 class="modal-title fw-semibold">Leave Details</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <!-- Modal Body -->
                        <div class="modal-body p-4">
                            <!-- Employee Profile Section -->
                            <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-3">
                                <div class="flex-shrink-0">
                                    <img id="viewEmployeeImage" src="" alt="Employee"
                                        class="rounded-circle border border-3 border-white shadow-sm" width="80"
                                        height="80">
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h4 id="viewEmployeeName" class="mb-1 fw-semibold"></h4>
                                    <div class="d-flex flex-wrap gap-2">
                                        <span class="badge bg-primary bg-opacity-10  p-1 text-primary"
                                            id="viewEmployeeDepartment"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Leave Summary Card -->
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 fw-semibold">Leave Summary</h6>
                                    <span class="badge bg-white text-dark p-2 fs-6">Total: <span
                                            id="viewTotalDays">0</span>
                                        days</span>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th class="ps-4">Leave Type</th>
                                                    <th class="text-center">Days</th>
                                                    <th>Duration</th>
                                                </tr>
                                            </thead>
                                            <tbody id="viewLeaveTypeDetails">
                                                <!-- Leave details will be populated here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Leave Calendar & Duration Section -->
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center justify-content-center p-3">
                                                <div class="text-center">
                                                    <div class="position-relative mb-3">
                                                        <i class="bi bi-calendar-range fs-1 text-primary"></i>
                                                    </div>
                                                    <h6 class="fw-semibold mb-1">Leave Duration</h6>
                                                    <p class="mb-0 text-muted" id="viewLeaveDuration">No duration selected
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add/Edit Modal -->
            <!-- Add/Edit Modal -->
            <div class="modal fade" id="leaveTypeModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="leaveModalTitle">Add Leave</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="leaveTypeForm" class="p-2">
                                @csrf
                                <input type="hidden" id="leaveTypeId" name="leavetype_id">

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label">Departments</label>
                                        <div class="dropdown" id="departmentDropdownContainer">
                                            <button class="dropdown-toggle control-select" type="button"
                                                id="modalDepartmentDropdown" data-bs-toggle="dropdown"
                                                aria-expanded="false" disabled>
                                                <span id="modalDepartmentFilterText">Select Departments</span>
                                            </button>
                                            <ul class="dropdown-menu department-dropdown p-2"
                                                aria-labelledby="modalDepartmentDropdown"
                                                style="width: 100%; max-height: 300px; overflow-y: auto;">
                                                <li>
                                                    <input type="text" class="form-control form-control-sm mb-2"
                                                        placeholder="Search departments..." id="modalDepartmentSearch">
                                                </li>
                                                <li class="d-flex justify-content-between px-2 mb-2">
                                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                                        id="modalSelectAllDepartments">Select All</button>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                                        id="modalDeselectAllDepartments">Deselect All</button>
                                                </li>
                                                <li>
                                                    <select id="modalDepartmentFilter" class="form-select form-select-sm"
                                                        size="8" style="width: 100%; border: none;" multiple
                                                        disabled>
                                                        @foreach ($departments as $department)
                                                            <option value="{{ $department->dep_id }}">
                                                                {{ $department->dep_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </li>
                                            </ul>
                                        </div>
                                        <input type="hidden" name="department_name_id[]" id="modalSelectedDepartments">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Employees</label>
                                        <div class="dropdown" id="employeeDropdownContainer">
                                            <button class="dropdown-toggle control-select" type="button"
                                                id="modalEmployeeDropdown" data-bs-toggle="dropdown"
                                                aria-expanded="false" disabled>
                                                <span id="modalEmployeeFilterText">Select Employees</span>
                                            </button>
                                            <ul class="dropdown-menu employee-dropdown p-2"
                                                aria-labelledby="modalEmployeeDropdown"
                                                style="width: 100%; max-height: 300px; overflow-y: auto;">
                                                <li>
                                                    <input type="text" class="form-control form-control-sm mb-2"
                                                        placeholder="Search employees..." id="modalEmployeeSearch">
                                                </li>
                                                <li class="d-flex justify-content-between px-2 mb-2">
                                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                                        id="modalSelectAllEmployees">Select All</button>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                                        id="modalDeselectAllEmployees">Deselect All</button>
                                                </li>
                                                <li>
                                                    <select id="modalEmployeeFilter" class="form-select form-select-sm"
                                                        size="8" multiple style="width: 100%; border: none;"
                                                        disabled>
                                                        @foreach ($employees as $employee)
                                                            <option value="{{ $employee->emp_id }}">
                                                                {{ $employee->fullname }}</option>
                                                        @endforeach
                                                    </select>
                                                </li>
                                            </ul>
                                        </div>
                                        <input type="hidden" name="employee_name_id[]" id="modalSelectedEmployees">
                                    </div>
                                </div>

                                <!-- Rest of the modal content remains the same -->
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <label class="form-label">Leave Type</label>
                                        <div class="dropdown">
                                            <button class="dropdown-toggle control-select" type="button"
                                                id="modalLeaveTypeDropdown" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <span id="modalLeaveTypeFilterText">Select Leave Types</span>
                                            </button>
                                            <ul class="dropdown-menu leave-type-dropdown p-2"
                                                aria-labelledby="modalLeaveTypeDropdown"
                                                style="width: 100%; max-height: 300px; overflow-y: auto;">
                                                <li>
                                                    <input type="text" class="form-control form-control-sm mb-2"
                                                        placeholder="Search leave types..." id="modalLeaveTypeSearch">
                                                </li>
                                                <li class="d-flex justify-content-between px-2 mb-2">
                                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                                        id="modalSelectAllLeaveTypes">Select All</button>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                                        id="modalDeselectAllLeaveTypes">Deselect All</button>
                                                </li>
                                                <li>
                                                    <div class="leave-type-options"
                                                        style="max-height: 200px; overflow-y: auto;">
                                                        <div class="form-check">
                                                            <input class="form-check-input modal-leave-type-checkbox"
                                                                type="checkbox" value="1" id="modalLeaveType1"
                                                                name="leavetype_name_id[]">
                                                            <label class="form-check-label"
                                                                for="modalLeaveType1">Privilege
                                                                Leave (PL)</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input modal-leave-type-checkbox"
                                                                type="checkbox" value="2" id="modalLeaveType2"
                                                                name="leavetype_name_id[]">
                                                            <label class="form-check-label" for="modalLeaveType2">Casual
                                                                Leave
                                                                (CL)</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input modal-leave-type-checkbox"
                                                                type="checkbox" value="3" id="modalLeaveType3"
                                                                name="leavetype_name_id[]">
                                                            <label class="form-check-label" for="modalLeaveType3">Sick
                                                                Leave
                                                                (SL)</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input modal-leave-type-checkbox"
                                                                type="checkbox" value="4" id="modalLeaveType4"
                                                                name="leavetype_name_id[]">
                                                            <label class="form-check-label"
                                                                for="modalLeaveType4">Maternity
                                                                Leave (ML)</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input modal-leave-type-checkbox"
                                                                type="checkbox" value="5" id="modalLeaveType5"
                                                                name="leavetype_name_id[]">
                                                            <label class="form-check-label"
                                                                for="modalLeaveType5">Compensatory
                                                                Off (Comp-off)</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input modal-leave-type-checkbox"
                                                                type="checkbox" value="6" id="modalLeaveType6"
                                                                name="leavetype_name_id[]">
                                                            <label class="form-check-label" for="modalLeaveType6">Marriage
                                                                Leave</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input modal-leave-type-checkbox"
                                                                type="checkbox" value="7" id="modalLeaveType7"
                                                                name="leavetype_name_id[]">
                                                            <label class="form-check-label"
                                                                for="modalLeaveType7">Paternity
                                                                Leave</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input modal-leave-type-checkbox"
                                                                type="checkbox" value="8" id="modalLeaveType8"
                                                                name="leavetype_name_id[]">
                                                            <label class="form-check-label"
                                                                for="modalLeaveType8">Bereavement
                                                                Leave</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input modal-leave-type-checkbox"
                                                                type="checkbox" value="9" id="modalLeaveType9"
                                                                name="leavetype_name_id[]">
                                                            <label class="form-check-label" for="modalLeaveType9">Unpaid
                                                                Leave (UL)</label>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <input type="hidden" name="leavetype_name_id[]" id="modalSelectedLeaveTypes">
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <label class="form-label">Duration (Selection of Leave)</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="form-group">
                                                <label>From</label>
                                                <input type="text" class="form-control datepicker" id="fromDate"
                                                    name="leave_start_from" placeholder="Select From Date">
                                            </div>
                                            <div class="form-group">
                                                <label>To</label>
                                                <input type="text" class="form-control datepicker" id="toDate"
                                                    name="leave_end_to" placeholder="Select To Date">
                                            </div>
                                            <button type="button" class="btn btn-outline-dark btn-sm mt-3"
                                                id="clearDates">
                                                Clear
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Days Inputs for Selected Leave Types -->
                                <div id="leaveTypeDaysContainer" class="mb-4">
                                    <!-- This will be populated with days inputs for each selected leave type -->
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="saveLeaveType">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Include Flatpickr CSS and JS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize the modal
                const viewLeaveTypeModal = new bootstrap.Modal(document.getElementById('viewLeaveTypeModal'));
                const leaveTypeModal = new bootstrap.Modal(document.getElementById('leaveTypeModal'));
                let employees = @json($employees);
                let groupedleaveTypes = @json($groupedleaveTypes);

                // Initialize date pickers
                flatpickr("#fromDate", {
                    dateFormat: "Y-m-d",
                    allowInput: true,
                    onChange: function(selectedDates, dateStr, instance) {
                        if (selectedDates.length > 0) {
                            toDatePicker.set('minDate', selectedDates[0]);
                        }
                    }
                });

                const toDatePicker = flatpickr("#toDate", {
                    dateFormat: "Y-m-d",
                    allowInput: true
                });

                // Clear dates button
                document.getElementById('clearDates').addEventListener('click', function() {
                    document.getElementById('fromDate').value = '';
                    document.getElementById('toDate').value = '';
                });

                // Filter elements
                const employeeFilterSelect = document.getElementById('employeeFilterSelect');
                const employeeFilterText = document.getElementById('employeeFilterText');
                const departmentFilterSelect = document.getElementById('departmentFilterSelect');
                const departmentFilterText = document.getElementById('departmentFilterText');
                const leaveTypeFilterSelect = document.getElementById('leaveTypeFilterSelect');
                const leaveTypeFilterText = document.getElementById('leaveTypeFilterText');
                const entriesPerPageSelect = document.getElementById('entriesPerPage');
                const clearFiltersBtn = document.getElementById('clearFiltersBtn');
                const clearFiltersBtnContainer = document.getElementById('clearFiltersBtnContainer');

                // Pagination elements
                const prevLeavePageBtn = document.getElementById('prevLeavePage');
                const nextLeavePageBtn = document.getElementById('nextLeavePage');
                const leavePageInfo = document.getElementById('leavePageInfo');
                const leaveShowingInfo = document.getElementById('leaveShowingInfo');

                // Initialize pagination and filters
                let currentEmployeeFilter = '';
                let currentDepartmentFilter = '';
                let currentLeaveTypeFilter = '';
                let currentEntriesPerPage = parseInt(entriesPerPageSelect.value);
                let currentPage = 1;
                let totalPages = 1;

                // Add event listeners for filters
                employeeFilterSelect.addEventListener('change', function() {
                    currentEmployeeFilter = this.value;
                    currentPage = 1;
                    applyFilters();
                    toggleClearFiltersButton();
                });

                departmentFilterSelect.addEventListener('change', function() {
                    currentDepartmentFilter = this.value;
                    currentPage = 1;
                    applyFilters();
                    toggleClearFiltersButton();
                });

                leaveTypeFilterSelect.addEventListener('change', function() {
                    currentLeaveTypeFilter = this.value;
                    currentPage = 1;
                    applyFilters();
                    toggleClearFiltersButton();
                });

                entriesPerPageSelect.addEventListener('change', function() {
                    currentEntriesPerPage = parseInt(this.value);
                    currentPage = 1;
                    applyFilters();
                });

                // Pagination button event listeners
                prevLeavePageBtn.addEventListener('click', function() {
                    if (currentPage > 1) {
                        currentPage--;
                        applyFilters();
                    }
                });

                nextLeavePageBtn.addEventListener('click', function() {
                    if (currentPage < totalPages) {
                        currentPage++;
                        applyFilters();
                    }
                });

                // Clear filters button
                clearFiltersBtn.addEventListener('click', function() {
                    employeeFilterSelect.value = '';
                    departmentFilterSelect.value = '';
                    leaveTypeFilterSelect.value = '';
                    currentEmployeeFilter = '';
                    currentDepartmentFilter = '';
                    currentLeaveTypeFilter = '';
                    currentPage = 1;
                    applyFilters();
                    toggleClearFiltersButton();
                });

                function applyFilters() {
                    // Convert grouped leave types into an array of employee entries
                    let employeeEntries = [];
                    Object.entries(groupedleaveTypes).forEach(([employeeId, leaves]) => {
                        if (leaves.length > 0 && leaves[0].employee) {
                            employeeEntries.push({
                                employee: leaves[0].employee,
                                department: leaves[0].department,
                                leaves: leaves,
                                leaveTypes: leaves.map(leave => ({
                                    id: leave.leavetype_name_id,
                                    name: getLeaveTypeName(leave.leavetype_name_id),
                                    days: leave.leave_days
                                })),
                                leave_start_from: leaves[0].leave_start_from,
                                leave_end_to: leaves[0].leave_end_to,
                                total_days: leaves.reduce((sum, leave) => sum + leave.leave_days, 0)
                            });
                        }
                    });

                    let filteredData = [...employeeEntries];

                    // Apply employee filter
                    if (currentEmployeeFilter) {
                        filteredData = filteredData.filter(item =>
                            item.employee && item.employee.emp_id == currentEmployeeFilter
                        );
                    }

                    // Apply department filter
                    if (currentDepartmentFilter) {
                        filteredData = filteredData.filter(item =>
                            item.department && item.department.dep_name === currentDepartmentFilter
                        );
                    }

                    // Apply leave type filter
                    if (currentLeaveTypeFilter) {
                        filteredData = filteredData.filter(item => {
                            return item.leaves.some(leave => {
                                const typeName = getLeaveTypeName(leave.leavetype_name_id);
                                return typeName === currentLeaveTypeFilter;
                            });
                        });
                    }

                    // Calculate pagination
                    totalPages = Math.ceil(filteredData.length / currentEntriesPerPage);

                    if (currentPage > totalPages && totalPages > 0) {
                        currentPage = totalPages;
                    }

                    const startIndex = (currentPage - 1) * currentEntriesPerPage;
                    const endIndex = Math.min(startIndex + currentEntriesPerPage, filteredData.length);
                    const paginatedData = filteredData.slice(startIndex, endIndex);

                    updateTable(paginatedData, filteredData.length, startIndex, endIndex);
                    updatePaginationControls(filteredData.length);
                }

                // Function to open view modal
                function viewEmployeeLeaves(employeeId) {
                    const employeeLeaves = groupedleaveTypes[employeeId] || [];

                    if (employeeLeaves.length === 0 || !employeeLeaves[0].employee) {
                        showToast('No leave records found for this employee', 'error');
                        return;
                    }

                    const employee = employeeLeaves[0].employee;
                    const department = employeeLeaves[0].department;

                    document.getElementById('viewEmployeeName').textContent = employee.fullname;
                    document.getElementById('viewEmployeeDepartment').textContent = department?.dep_name ||
                        'No Department';

                    const employeeImage = document.getElementById('viewEmployeeImage');
                    employeeImage.src = employee.image ?
                        `/employee_images/${employee.image}` :
                        `/images/admin_default.jpg`;
                    employeeImage.alt = employee.fullname;

                    const leaveTypeMap = {
                        'PL': 'Privilege Leave (PL)',
                        'CL': 'Casual Leave (CL)',
                        'SL': 'Sick Leave (SL)',
                        'UL': 'Unpaid Leave (UL)',
                        'ML': 'Maternity Leave (ML)',
                        'Comp-off': 'Compensatory Off (Comp-off)',
                        'MAL': 'Marriage Leave(MAL)',
                        'PAL': 'Paternity Leave (PAL)',
                        'BL': 'Bereavement Leave (BL)'
                    };

                    const leaveData = {
                        'PL': {
                            name: leaveTypeMap['PL'],
                            days: 'N/A',
                            duration: 'N/A',
                            class: 'badge-pl'
                        },
                        'CL': {
                            name: leaveTypeMap['CL'],
                            days: 'N/A',
                            duration: 'N/A',
                            class: 'badge-cl'
                        },
                        'SL': {
                            name: leaveTypeMap['SL'],
                            days: 'N/A',
                            duration: 'N/A',
                            class: 'badge-sl'
                        },
                        'UL': {
                            name: leaveTypeMap['UL'],
                            days: 'N/A',
                            duration: 'N/A',
                            class: 'badge-ul'
                        },
                        'ML': {
                            name: leaveTypeMap['ML'],
                            days: 'N/A',
                            duration: 'N/A',
                            class: 'badge-ml'
                        },
                        'Comp-off': {
                            name: leaveTypeMap['Comp-off'],
                            days: 'N/A',
                            duration: 'N/A',
                            class: 'badge-comp'
                        },
                        'MAL': {
                            name: leaveTypeMap['MAL'],
                            days: 'N/A',
                            duration: 'N/A',
                            class: 'badge-mal'
                        },
                        'PAL': {
                            name: leaveTypeMap['PAL'],
                            days: 'N/A',
                            duration: 'N/A',
                            class: 'badge-pal'
                        },
                        'BL': {
                            name: leaveTypeMap['BL'],
                            days: 'N/A',
                            duration: 'N/A',
                            class: 'badge-bl'
                        }
                    };

                    let totalDays = 0;
                    let durations = [];

                    employeeLeaves.forEach(leave => {
                        const typeMap = {
                            1: 'PL',
                            2: 'CL',
                            3: 'SL',
                            4: 'MAL',
                            5: 'Comp-off',
                            6: 'ML',
                            7: 'PAL',
                            8: 'BL',
                            9: 'UL'
                        };

                        const type = typeMap[leave.leavetype_name_id];
                        if (type) {
                            leaveData[type].days = leave.leave_days;
                            totalDays += leave.leave_days;

                            if (leave.leave_start_from && leave.leave_end_to) {
                                const fromDate = formatDate(leave.leave_start_from);
                                const toDate = formatDate(leave.leave_end_to);
                                leaveData[type].duration = `${fromDate} - ${toDate}`;
                                durations.push(`${fromDate} - ${toDate}`);
                            }
                        }
                    });

                    const leaveDetailsBody = document.getElementById('viewLeaveTypeDetails');
                    leaveDetailsBody.innerHTML = '';

                    Object.entries(leaveData).forEach(([type, data]) => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                        <td>
                            <span class="leave-badge ${data.class}">${data.name}</span>
                        </td>
                        <td class="text-center">${data.days}</td>
                        <td>${data.duration}</td>
                    `;
                        leaveDetailsBody.appendChild(row);
                    });

                    document.getElementById('viewTotalDays').textContent = totalDays;
                    const uniqueDurations = [...new Set(durations)];
                    const durationDisplay = uniqueDurations.length > 0 ?
                        uniqueDurations.join('<br>') :
                        'No duration specified';
                    document.getElementById('viewLeaveDuration').innerHTML = durationDisplay;

                    viewLeaveTypeModal.show();
                }

                function addViewEventListeners() {
                    document.querySelectorAll('.view-leave-type').forEach(button => {
                        button.addEventListener('click', function() {
                            const employeeId = this.getAttribute('data-id');
                            viewEmployeeLeaves(employeeId);
                        });
                    });
                }

                function getLeaveTypeName(typeId) {
                    const types = {
                        1: 'Privilege Leave (PL)',
                        2: 'Casual Leave (CL)',
                        3: 'Sick Leave (SL)',
                        4: 'Maternity Leave (ML)',
                        5: 'Compensatory Off (Comp-off)',
                        6: 'Marriage Leave',
                        7: 'Paternity Leave',
                        8: 'Bereavement Leave',
                        9: 'UnPaid Leave (UL)'
                    };
                    return types[typeId] || 'Unknown Leave Type';
                }

                function updateTable(data, totalItems, startIndex, endIndex) {
                    const tableBody = document.getElementById('leaveTypeTableBody');
                    tableBody.innerHTML = '';

                    data.forEach(item => {
                        const leaveData = {
                            'PL': 'N/A',
                            'CL': 'N/A',
                            'SL': 'N/A',
                            'UL': 'N/A',
                            'ML': 'N/A',
                            'Comp-off': 'N/A',
                            'MAL': 'N/A',
                            'PAL': 'N/A',
                            'BL': 'N/A'
                        };

                        item.leaves.forEach(leave => {
                            const typeMap = {
                                1: 'PL',
                                2: 'CL',
                                3: 'SL',
                                4: 'MAL',
                                5: 'Comp-off',
                                6: 'ML',
                                7: 'PAL',
                                8: 'BL',
                                9: 'UL'
                            };
                            const type = typeMap[leave.leavetype_name_id];
                            if (type) {
                                leaveData[type] = leave.leave_days;
                            }
                        });

                        const duration = item.leave_start_from && item.leave_end_to ?
                            `${formatDate(item.leave_start_from)} - ${formatDate(item.leave_end_to)}` :
                            'N/A';

                        const row = document.createElement('tr');

                        @foreach ($columns as $column)
                            @if (in_array($column['key'], $visibleColumns))
                                @switch($column['key'])
                                    @case('image')
                                    row.innerHTML += `
                                        <td>
                                            <a href="#" data-id="${item.employee.emp_id}" class="view-leave-type">
                                                <img src="${item.employee.image ? `/employee_images/${item.employee.image}` : `/images/admin_default.jpg`}"
                                                    alt="${item.employee.fullname || 'Employee'}"
                                                    class="img-fluid mx-auto d-block rounded-circle" width="50px">
                                            </a>
                                        </td>
                                    `;
                                    @break

                                    @case('employee')
                                    row.innerHTML += `<td>${item.employee.fullname || 'N/A'}</td>`;
                                    @break

                                    @case('department')
                                    row.innerHTML += `<td>${item.department?.dep_name || 'N/A'}</td>`;
                                    @break

                                    @case('PL')
                                    row.innerHTML += `<td class="text-center">${leaveData['PL']}</td>`;
                                    @break

                                    @case('CL')
                                    row.innerHTML += `<td class="text-center">${leaveData['CL']}</td>`;
                                    @break

                                    @case('SL')
                                    row.innerHTML += `<td class="text-center">${leaveData['SL']}</td>`;
                                    @break

                                    @case('UL')
                                    row.innerHTML += `<td class="text-center">${leaveData['UL']}</td>`;
                                    @break

                                    @case('ML')
                                    row.innerHTML += `<td class="text-center">${leaveData['ML']}</td>`;
                                    @break

                                    @case('Comp-off')
                                    row.innerHTML += `<td class="text-center">${leaveData['Comp-off']}</td>`;
                                    @break

                                    @case('MAL')
                                    row.innerHTML += `<td class="text-center">${leaveData['MAL']}</td>`;
                                    @break

                                    @case('PAL')
                                    row.innerHTML += `<td class="text-center">${leaveData['PAL']}</td>`;
                                    @break

                                    @case('BL')
                                    row.innerHTML += `<td class="text-center">${leaveData['BL']}</td>`;
                                    @break

                                    @case('duration')
                                    row.innerHTML += `<td>${duration}</td>`;
                                    @break

                                    @case('total_days')
                                    row.innerHTML += `<td class=" fw-medium">${item.total_days}</td>`;
                                    @break

                                    @case('action')
                                    row.innerHTML += `
                                        <td class="text-center">
                                            <button class="btn btn-sm text-info view-leave-type"
                                                    data-id="${item.employee.emp_id}">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm text-primary edit-leave-type"
                                                    data-id="${item.employee.emp_id}">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm text-danger delete-leave-type"
                                                    data-id="${item.employee.emp_id}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    `;
                                    @break
                                @endswitch
                            @endif
                        @endforeach

                        tableBody.appendChild(row);
                    });

                    if (totalItems === 0) {
                        leaveShowingInfo.textContent = 'Showing 0 to 0 of 0 entries';
                    } else {
                        leaveShowingInfo.textContent =
                            `Showing ${startIndex + 1} to ${endIndex} of ${totalItems} entries`;
                    }

                    addEditEventListeners();
                    addDeleteEventListeners();
                    addViewEventListeners();
                }

                function formatDateTime(datetimeString) {
                    const date = new Date(datetimeString);
                    return date.toLocaleString('en-US', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                }

                function addEditEventListeners() {
                    document.querySelectorAll('.edit-leave-type').forEach(button => {
                        button.addEventListener('click', function() {
                            const employeeId = this.getAttribute('data-id');
                            editEmployeeLeaves(employeeId);
                        });
                    });
                }

                function updatePaginationControls(totalItems) {
                    totalPages = Math.ceil(totalItems / currentEntriesPerPage);
                    prevLeavePageBtn.disabled = currentPage <= 1;
                    nextLeavePageBtn.disabled = currentPage >= totalPages;

                    if (totalPages === 0) {
                        leavePageInfo.textContent = 'Page 0 of 0';
                    } else {
                        leavePageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
                    }
                }

                function toggleClearFiltersButton() {
                    if (currentEmployeeFilter || currentDepartmentFilter || currentLeaveTypeFilter) {
                        clearFiltersBtnContainer.style.display = 'block';
                    } else {
                        clearFiltersBtnContainer.style.display = 'none';
                    }
                }

                function formatDate(dateString) {
                    const date = new Date(dateString);
                    return date.toLocaleDateString('en-US', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric'
                    });
                }

                function addDeleteEventListeners() {
                    document.querySelectorAll('.delete-leave-type').forEach(button => {
                        button.addEventListener('click', function() {
                            const employeeId = this.getAttribute('data-id');
                            deleteLeaveType(employeeId);
                        });
                    });
                }

                // Initialize the table with all data
                applyFilters();

                // Edit Leave Type function




                function editEmployeeLeaves(employeeId) {
                    const employeeLeaves = groupedleaveTypes[employeeId] || [];

                    // Set the employee ID in the form
                    document.getElementById('leaveTypeId').value = employeeId;

                    const modalData = {
                        employee_id: employeeId,
                        employee_name: employeeLeaves[0]?.employee?.fullname || 'N/A',
                        department_id: employeeLeaves[0]?.department_name_id || '',
                        leaves: [],
                        leave_start_from: '',
                        leave_end_to: ''
                    };

                    // Create a map of existing leave types for this employee
                    const existingLeaveTypes = {};
                    employeeLeaves.forEach(leave => {
                        modalData.leaves.push({
                            leavetype_id: leave.leavetype_id,
                            leavetype_name_id: leave.leavetype_name_id,
                            leave_days: leave.leave_days
                        });
                        existingLeaveTypes[leave.leavetype_name_id] = leave.leave_days;

                        if (!modalData.leave_start_from) {
                            modalData.leave_start_from = leave.leave_start_from;
                            modalData.leave_end_to = leave.leave_end_to;
                        }
                    });

                    document.getElementById('leaveModalTitle').textContent =
                        `Edit Leaves for ${modalData.employee_name}`;

                    // Disable department and employee dropdowns in edit mode
                    document.getElementById('modalDepartmentDropdown').disabled = true;
                    document.getElementById('modalDepartmentFilter').disabled = true;
                    document.getElementById('modalEmployeeDropdown').disabled = true;
                    document.getElementById('modalEmployeeFilter').disabled = true;

                    // Visual indication that these fields are readonly
                    const departmentContainer = document.getElementById('departmentDropdownContainer');
                    const employeeContainer = document.getElementById('employeeDropdownContainer');

                    departmentContainer.classList.add('bg-light', 'pe-none');
                    employeeContainer.classList.add('bg-light', 'pe-none');

                    const employeeSelect = document.getElementById('modalEmployeeFilter');
                    Array.from(employeeSelect.options).forEach(option => {
                        option.selected = option.value == employeeId;
                    });
                    document.getElementById('modalEmployeeFilterText').textContent = modalData.employee_name;
                    document.getElementById('modalSelectedEmployees').value = employeeId;

                    const departmentSelect = document.getElementById('modalDepartmentFilter');
                    Array.from(departmentSelect.options).forEach(option => {
                        option.selected = option.value == modalData.department_id;
                    });
                    document.getElementById('modalDepartmentFilterText').textContent =
                        employeeLeaves[0]?.department?.dep_name || 'Select Department';
                    document.getElementById('modalSelectedDepartments').value = modalData.department_id;

                    // Initialize all leave type checkboxes and their values
                    document.querySelectorAll('.modal-leave-type-checkbox').forEach(checkbox => {
                        const leaveTypeId = checkbox.value;
                        checkbox.checked = existingLeaveTypes.hasOwnProperty(leaveTypeId);
                    });

                    updateModalLeaveTypeFilterText();

                    document.getElementById('fromDate').value = modalData.leave_start_from;
                    document.getElementById('toDate').value = modalData.leave_end_to;

                    // Initialize the days inputs with existing values or default to 1
                    updateLeaveTypeDaysInputs();

                    // For existing leave types, set their days values
                    modalData.leaves.forEach(leave => {
                        const daysInput = document.getElementById(`leaveDays-${leave.leavetype_name_id}`);
                        if (daysInput) {
                            daysInput.value = leave.leave_days;
                        }
                    });

                    leaveTypeModal.show();
                }



                // Update the save handler
                document.getElementById('saveLeaveType').addEventListener('click', function() {
                    const selectedDepartments = Array.from(document.querySelectorAll(
                        '#modalDepartmentFilter option:checked')).map(opt => opt.value);
                    const selectedEmployees = Array.from(document.querySelectorAll(
                        '#modalEmployeeFilter option:checked')).map(opt => opt.value);
                    const selectedLeaveTypes = Array.from(document.querySelectorAll(
                        '.modal-leave-type-checkbox:checked')).map(cb => cb.value);

                    // Validate selections
                    if (selectedEmployees.length === 0 || selectedLeaveTypes.length === 0) {
                        showToast('Please select at least one employee and one leave type', 'error');
                        return;
                    }

                    const fromDate = document.getElementById('fromDate').value;
                    const toDate = document.getElementById('toDate').value;
                    if (!fromDate || !toDate) {
                        showToast('Please select both From and To dates', 'error');
                        return;
                    }

                    // Validate days inputs
                    for (const leaveTypeId of selectedLeaveTypes) {
                        const daysInput = document.getElementById(`leaveDays-${leaveTypeId}`);
                        const days = daysInput ? Number(daysInput.value) : 0;
                        if (!days || isNaN(days) || days <= 0) {
                            showToast('Please enter valid days for all selected leave types', 'error');
                            return;
                        }
                    }

                    const employeeId = document.getElementById('leaveTypeId').value;
                    const isEditMode = !!employeeId;

                    // Build entries
                    const leaveTypeEntries = [];
                    const existingLeaves = isEditMode ? (groupedleaveTypes[employeeId] || []) : [];

                    selectedLeaveTypes.forEach(leaveTypeId => {
                        selectedEmployees.forEach(empId => {
                            // Use actual employee's department (fallback to first selected dep if needed)
                            const emp = employees.find(e => e.emp_id == empId);
                            const departmentId = emp?.departmentid?.dep_id ||
                                selectedDepartments[0] || null;

                            const existingLeave = existingLeaves.find(l => l
                                .leavetype_name_id == leaveTypeId);

                            leaveTypeEntries.push({
                                leavetype_id: existingLeave?.leavetype_id || null,
                                employee_name_id: empId,
                                department_name_id: departmentId,
                                leavetype_name_id: leaveTypeId,
                                leave_days: document.getElementById(
                                    `leaveDays-${leaveTypeId}`).value
                            });
                        });
                    });

                    // Prepare form data
                    const buildFormData = (overwriteFlag = false) => {
                        const fd = new FormData();
                        fd.append('leave_start_from', fromDate);
                        fd.append('leave_end_to', toDate);
                        fd.append('leave_type_entries', JSON.stringify(leaveTypeEntries));
                        if (isEditMode) fd.append('_method', 'PUT');
                        if (overwriteFlag) fd.append('overwrite', '1');
                        return fd;
                    };

                    const url = isEditMode ? `/dashboard/employees/leavetype/${employeeId}` :
                        '/dashboard/employees/leavetype';
                    const method = 'POST'; // always POST; Laravel will respect _method for PUT

                    const saveBtn = this;
                    const setSaving = (saving) => {
                        saveBtn.disabled = saving;
                        saveBtn.innerHTML = saving ?
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...' :
                            'Save';
                    };

                    const postOnce = (formData) => {
                        return fetch(url, {
                            method: method,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .content,
                                'Accept': 'application/json',
                            },
                            body: formData
                        });
                    };

                    const handleSuccess = (data) => {
                        if (data.success) {
                            showToast(data.message, 'success');
                            leaveTypeModal.hide();
                            setTimeout(() => window.location.reload(), 600);
                        } else if (data.exists) {
                            // Defensive: if backend returned 200 with exists=true
                            askOverwriteAndResend();
                        } else {
                            showToast(data.message || 'Operation failed', 'error');
                        }
                    };

                    const askOverwriteAndResend = () => {
                        Swal.fire({
                            title: 'Overwrite Leave Type?',
                            text: 'Some selected leave types already exist for the chosen employees. Do you want to overwrite them?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, Overwrite',
                            cancelButtonText: 'No, Cancel'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                setSaving(true);
                                postOnce(buildFormData(true))
                                    .then(r => r.json())
                                    .then(handleSuccess)
                                    .catch(() => showToast('Error saving (overwrite)', 'error'))
                                    .finally(() => setSaving(false));
                            }
                        });
                    };

                    setSaving(true);

                    // First attempt (without overwrite)
                    postOnce(buildFormData(false))
                        .then(async (response) => {
                            let data = {};
                            try {
                                data = await response.json();
                            } catch (_) {}

                            if (response.ok) {
                                handleSuccess(data);
                            } else {
                                // 409 from backend when duplicates found
                                if (data && data.exists) {
                                    askOverwriteAndResend();
                                } else {
                                    showToast(data?.message || 'Error saving leave type', 'error');
                                }
                            }
                        })
                        .catch(() => showToast('Network error saving leave type', 'error'))
                        .finally(() => setSaving(false));
                });


                function deleteLeaveType(employeeId) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Leave records for this employee as deleted!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`/dashboard/employees/leavetype/${employeeId}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                            .content,
                                        'Accept': 'application/json',
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        showToast(data.message, 'success');
                                        window.location.reload();
                                    } else {
                                        showToast(data.message, 'error');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    showToast('Error deleting leave type', 'error');
                                });
                        }
                    });
                }

                // Add Leave Type button click handler
                document.getElementById('addLeaveType').addEventListener('click', function() {
                    document.getElementById('leaveModalTitle').textContent = 'Add Leave';
                    document.getElementById('leaveTypeForm').reset();
                    document.getElementById('leaveTypeId').value = '';

                    // Enable department and employee dropdowns in add mode
                    document.getElementById('modalDepartmentDropdown').disabled = false;
                    document.getElementById('modalDepartmentFilter').disabled = false;
                    document.getElementById('modalEmployeeDropdown').disabled = false;
                    document.getElementById('modalEmployeeFilter').disabled = false;

                    // Remove visual indication
                    document.getElementById('departmentDropdownContainer').classList.remove('bg-light',
                        'pe-none');
                    document.getElementById('employeeDropdownContainer').classList.remove('bg-light',
                        'pe-none');
                    document.getElementById('modalSelectedDepartments').value = '';
                    document.getElementById('modalSelectedEmployees').value = '';
                    document.getElementById('modalSelectedLeaveTypes').value = '';
                    document.getElementById('modalDepartmentFilterText').textContent = 'Select Departments';
                    document.getElementById('modalEmployeeFilterText').textContent = 'Select Employees';
                    document.getElementById('modalLeaveTypeFilterText').textContent = 'Select Leave Types';
                    document.getElementById('leaveTypeDaysContainer').innerHTML = '';
                    document.getElementById('fromDate').value = '';
                    document.getElementById('toDate').value = '';

                    document.querySelectorAll('.modal-leave-type-checkbox').forEach(checkbox => {
                        checkbox.checked = false;
                    });

                    leaveTypeModal.show();
                });

                // Modal Department dropdown functionality
                document.getElementById('modalDepartmentSearch').addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const options = document.querySelectorAll('#modalDepartmentFilter option');

                    for (let i = 0; i < options.length; i++) {
                        const option = options[i];
                        const text = option.text.toLowerCase();
                        option.style.display = text.includes(searchTerm) ? '' : 'none';
                    }
                });

                document.getElementById('modalSelectAllDepartments').addEventListener('click', function() {
                    const options = document.querySelectorAll('#modalDepartmentFilter option');
                    options.forEach(option => {
                        option.selected = true;
                    });
                    updateModalDepartmentFilterText();
                });

                document.getElementById('modalDeselectAllDepartments').addEventListener('click', function() {
                    const options = document.querySelectorAll('#modalDepartmentFilter option');
                    options.forEach(option => {
                        option.selected = false;
                    });
                    updateModalDepartmentFilterText();
                });

                document.getElementById('modalDepartmentFilter').addEventListener('change',
                    updateModalDepartmentFilterText);

                document.getElementById('modalDepartmentFilter').addEventListener('change', function() {
                    const selectedDepartments = Array.from(this.selectedOptions).map(opt => opt.value);
                    const employeeSelect = document.getElementById('modalEmployeeFilter');

                    if (!employeeSelect) return;

                    Array.from(employeeSelect.options).forEach(option => {
                        option.selected = false;
                    });

                    if (selectedDepartments.length === 0) {
                        // if (selectedDepartments.length === 0 || selectedDepartments.includes('all')) {
                        Array.from(employeeSelect.options).forEach(option => {
                            option.style.display = '';
                        });
                    } else {
                        Array.from(employeeSelect.options).forEach(option => {
                            const employeeId = option.value;
                            if (!employeeId) return;

                            const employee = employees.find(e => e.emp_id == employeeId);
                            if (!employee) return;

                            const employeeDept = employee.departmentid ? employee.departmentid.dep_id :
                                null;

                            if (employeeDept && selectedDepartments.includes(employeeDept.toString())) {
                                option.style.display = '';
                            } else {
                                option.style.display = 'none';
                            }
                        });
                    }

                    updateModalEmployeeFilterText();
                });

                // Modal Employee dropdown functionality
                document.getElementById('modalEmployeeSearch').addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const options = document.querySelectorAll('#modalEmployeeFilter option');

                    for (let i = 0; i < options.length; i++) {
                        const option = options[i];
                        const text = option.text.toLowerCase();
                        option.style.display = text.includes(searchTerm) ? '' : 'none';
                    }
                });

                document.getElementById('modalSelectAllEmployees').addEventListener('click', function() {
                    const options = document.querySelectorAll('#modalEmployeeFilter option');
                    options.forEach(option => {
                        option.selected = true;
                    });
                    updateModalEmployeeFilterText();
                });

                document.getElementById('modalDeselectAllEmployees').addEventListener('click', function() {
                    const options = document.querySelectorAll('#modalEmployeeFilter option');
                    options.forEach(option => {
                        option.selected = false;
                    });
                    updateModalEmployeeFilterText();
                });

                document.getElementById('modalEmployeeFilter').addEventListener('change',
                    updateModalEmployeeFilterText);

                // Modal Leave Type checkbox change handler
                document.querySelectorAll('.modal-leave-type-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        updateModalLeaveTypeFilterText();
                        updateLeaveTypeDaysInputs();
                    });
                });

                // Modal Leave Type search functionality
                document.getElementById('modalLeaveTypeSearch').addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    document.querySelectorAll('.leave-type-options .form-check-label').forEach(label => {
                        const text = label.textContent.toLowerCase();
                        const checkbox = label.previousElementSibling;
                        const formCheck = label.parentElement;
                        formCheck.style.display = text.includes(searchTerm) ? '' : 'none';
                    });
                });

                // Modal Select All Leave Types
                document.getElementById('modalSelectAllLeaveTypes').addEventListener('click', function() {
                    document.querySelectorAll('.modal-leave-type-checkbox').forEach(checkbox => {
                        checkbox.checked = true;
                    });
                    updateModalLeaveTypeFilterText();
                    updateLeaveTypeDaysInputs();
                });

                // Modal Deselect All Leave Types
                document.getElementById('modalDeselectAllLeaveTypes').addEventListener('click', function() {
                    document.querySelectorAll('.modal-leave-type-checkbox').forEach(checkbox => {
                        checkbox.checked = false;
                    });
                    updateModalLeaveTypeFilterText();
                    updateLeaveTypeDaysInputs();
                });


                function updateLeaveTypeDaysInputs() {
                    const container = document.getElementById('leaveTypeDaysContainer');

                    // Save existing values before clearing
                    const existingValues = {};
                    document.querySelectorAll('[id^="leaveDays-"]').forEach(input => {
                        existingValues[input.id] = input.value;
                    });

                    // Clear the container
                    container.innerHTML = '';

                    const selectedCheckboxes = Array.from(document.querySelectorAll(
                        '.modal-leave-type-checkbox:checked'));

                    selectedCheckboxes.forEach(checkbox => {
                        const leaveTypeId = checkbox.value;
                        const leaveTypeName = checkbox.nextElementSibling.textContent;

                        const inputGroup = document.createElement('div');
                        inputGroup.className = 'row mb-2';
                        inputGroup.innerHTML = `
            <div class="col-md-12 d-flex align-items-center">
                <label class="form-label me-2" style="min-width: 150px;">${leaveTypeName}</label>
                <input type="number" id="leaveDays-${leaveTypeId}"
                       class="form-control" name="leave_days[${leaveTypeId}]"
                       min="1" style="width: 100px;">
                <span class="ms-2">days</span>
            </div>
        `;
                        container.appendChild(inputGroup);

                        // Restore existing value if it exists, otherwise set default to 1
                        const inputId = `leaveDays-${leaveTypeId}`;
                        document.getElementById(inputId).value = existingValues[inputId] || '1';
                    });
                }


                function updateModalLeaveTypeFilterText() {
                    const selectedCheckboxes = Array.from(document.querySelectorAll(
                        '.modal-leave-type-checkbox:checked'));
                    const filterText = document.getElementById('modalLeaveTypeFilterText');

                    if (selectedCheckboxes.length === 0) {
                        filterText.textContent = 'Select Leave Types';
                    } else if (selectedCheckboxes.length === 1) {
                        filterText.textContent = selectedCheckboxes[0].nextElementSibling.textContent;
                    } else {
                        filterText.textContent = `${selectedCheckboxes.length} leave types selected`;
                    }

                    document.getElementById('modalSelectedLeaveTypes').value = selectedCheckboxes.map(cb => cb.value)
                        .join(
                            ',');
                }

                function updateModalDepartmentFilterText() {
                    const selectedOptions = Array.from(document.querySelectorAll(
                        '#modalDepartmentFilter option:checked'));
                    const filterText = document.getElementById('modalDepartmentFilterText');

                    if (selectedOptions.length === 0) {
                        filterText.textContent = 'Select Departments';
                    } else if (selectedOptions.length === 1) {
                        filterText.textContent = selectedOptions[0].textContent;
                    } else {
                        filterText.textContent = `${selectedOptions.length} departments selected`;
                    }

                    document.getElementById('modalSelectedDepartments').value = selectedOptions.map(opt => opt.value)
                        .join(
                            ',');
                }

                function updateModalEmployeeFilterText() {
                    const selectedOptions = Array.from(document.querySelectorAll(
                        '#modalEmployeeFilter option:checked'));
                    const filterText = document.getElementById('modalEmployeeFilterText');

                    if (selectedOptions.length === 0) {
                        filterText.textContent = 'Select Employees';
                    } else if (selectedOptions.length === 1) {
                        filterText.textContent = selectedOptions[0].textContent;
                    } else {
                        filterText.textContent = `${selectedOptions.length} employees selected`;
                    }

                    document.getElementById('modalSelectedEmployees').value = selectedOptions.map(opt => opt.value)
                        .join(
                            ',');
                }

                function showToast(message, type = 'success') {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });

                    Toast.fire({
                        icon: type,
                        title: message
                    });
                }
            });
        </script>
        <style>
            .action-button {
                padding: 0.625rem 1.25rem;
                border-radius: 8px;
                font-weight: 500;
                font-size: 0.9375rem;
                display: flex;
                align-items: center;
                border: none;
                cursor: pointer;
                transition: all 0.2s;
            }

            .action-button.primary {
                background-color: #4dabf7;
                color: white;
            }

            .action-button.primary:hover {
                background-color: #339af0;
            }

            .control-select {
                display: flex;
                align-items: center;
                justify-content: space-between;
                width: 100%;
                text-align: left;
                padding: 0.5rem 1rem;
                background-color: white;
                border: 1px solid #ced4da;
                border-radius: 8px;
            }

            .dropdown-menu {
                padding: 0;
                border-radius: 8px;
                border: 1px solid #ced4da;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            }

            .leave-type-input-wrapper {
                background-color: #f8f9fa;
                padding: 15px;
                border-radius: 8px;
                border: 1px solid #dee2e6;
                margin-bottom: 15px;
            }

            .leave-type-options {
                max-height: 200px;
                overflow-y: auto;
                padding: 0 10px;
            }

            .leave-type-options .form-check {
                padding: 5px 25px;
            }

            .pagination-controls {
                display: flex;
                align-items: center;
                gap: 1rem;
            }

            .pagination-button {
                background: none;
                border: 1px solid #dee2e6;
                border-radius: 6px;
                padding: 0.5rem 1rem;
                cursor: pointer;
                transition: all 0.2s;
            }

            .pagination-button:hover:not(:disabled) {
                background-color: #f1f3f5;
            }

            .pagination-button:disabled {
                opacity: 0.5;
                cursor: not-allowed;
            }

            .page-info {
                font-size: 0.875rem;
                color: #495057;
            }

            .datepicker {
                padding: 0.375rem 0.75rem;
                border: 1px solid #ced4da;
                border-radius: 0.25rem;
            }

            #viewLeaveTypeModal .modal-content {
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            }

            #viewLeaveTypeModal .modal-header {
                background: var(--ra-primary-set);
                border-bottom: none;
                padding: 1.25rem 1.5rem;
            }

            #viewLeaveTypeModal .modal-body {
                padding: 1.5rem;
            }

            #viewLeaveTypeModal .modal-footer {
                border-top: 1px solid rgba(0, 0, 0, 0.05);
                padding: 1rem 1.5rem;
            }

            #viewLeaveTypeModal .card {
                border-radius: 10px;
                border: none;
            }

            #viewLeaveTypeModal .card-header {
                padding: 0.75rem 1.25rem;
                border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            }

            #viewLeaveTypeModal .table {
                margin-bottom: 0;
                font-size: 0.9rem;
            }

            #viewLeaveTypeModal .table th {
                border-top: none;
                font-weight: 500;
                font-size: 0.8rem;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                color: #6c757d;
                padding-top: 0.75rem;
                padding-bottom: 0.75rem;
            }

            #viewLeaveTypeModal .table td {
                vertical-align: middle;
                padding: 0.75rem 1rem;
            }

            #viewLeaveTypeModal .table tr:last-child td {
                border-bottom: none;
            }

            .leave-badge {
                display: inline-flex;
                align-items: center;
                padding: 0.35em 0.65em;
                font-size: 0.75em;
                font-weight: 500;
                line-height: 1;
                border-radius: 50px;
                gap: 0.5em;
            }

            .leave-badge i {
                font-size: 0.9em;
            }

            .badge-pl {
                background-color: rgba(25, 118, 210, 0.1);
                color: #1976d2;
            }

            .badge-cl {
                background-color: rgba(56, 142, 60, 0.1);
                color: #388e3c;
            }

            .badge-sl {
                background-color: rgba(245, 124, 0, 0.1);
                color: #f57c00;
            }

            .badge-ml {
                background-color: rgba(194, 24, 91, 0.1);
                color: #c2185b;
            }

            .badge-comp {
                background-color: rgba(0, 151, 167, 0.1);
                color: #0097a7;
            }

            .badge-mal {
                background-color: rgba(123, 31, 162, 0.1);
                color: #7b1fa2;
            }

            .badge-pal {
                background-color: rgba(57, 73, 171, 0.1);
                color: #3949ab;
            }

            .badge-bl {
                background-color: rgba(93, 64, 55, 0.1);
                color: #5d4037;
            }

            .badge-ul {
                background-color: rgba(69, 90, 100, 0.1);
                color: #455a64;
            }

            .status-badge {
                padding: 0.35em 0.65em;
                border-radius: 50px;
                font-size: 0.75em;
                font-weight: 500;
            }

            .status-approved {
                background-color: rgba(46, 125, 50, 0.1);
                color: #2e7d32;
            }

            .status-pending {
                background-color: rgba(251, 192, 45, 0.1);
                color: #fbc02d;
            }

            .status-rejected {
                background-color: rgba(211, 47, 47, 0.1);
                color: #d32f2f;
            }

            .avatar {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 40px;
                height: 40px;
            }

            .avatar-lg {
                width: 60px;
                height: 60px;
            }

            .avatar-title {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 100%;
                height: 100%;
            }

            /* Add this to your existing styles */
            #leaveTypeModal .bg-light .control-select {
                color: #212529 !important;
                /* Dark color for text */
                background-color: #f8f9fa !important;
                /* Light background */
                opacity: 1 !important;
                /* Ensure full opacity */
            }

            #leaveTypeModal .bg-light .dropdown-toggle::after {
                display: none;
                /* Hide the dropdown arrow */
            }
        </style>
    </x-layout>
