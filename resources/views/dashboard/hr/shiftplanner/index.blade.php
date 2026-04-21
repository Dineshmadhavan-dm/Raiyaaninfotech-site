<x-layout>
    @section('title', 'Shift Planner')

    <div class="container-fluid p-3">
        <x-message />

        <div class="shift-planner-container">
            <!-- Header Section -->
            <div class="" style="background-color: #f8fafc;">
                <div class="row justify-content-between px-3 pb-3">
                    <h4 class="pb-3 pt-2 fw-medium fs-5"><i class="bi bi-calendar2-range me-2"></i>Shift Planner</h4>

                    <div class="col-auto">
                        <div class="row justify-content-start pb-3">
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
                                    <button class="dropdown-toggle control-select" type="button" id="employeeDropdown"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <span id="employeeFilterText" class="">All Employees</span>
                                    </button>
                                    <ul class="dropdown-menu employee-dropdown p-2" aria-labelledby="employeeDropdown"
                                        style="width: 250px;">
                                        <li>
                                            <input type="text" class="form-control form-control-sm mb-2"
                                                placeholder="Search employees..." id="employeeSearch">
                                        </li>
                                        <li>
                                            <select id="employeeFilter" class="form-select form-select-sm"
                                                size="8" style="width: 100%; border: none;">
                                                <option value="" class="mb-4">All Employees</option>
                                                @foreach ($employees as $employee)
                                                    <option value="{{ $employee->emp_id }}" class="mb-3 mt-2">
                                                        <div class="d-flex align-items-center">
                                                            <p class="mb-0 fw-bold">
                                                                <img src="{{ asset('employee_images/' . $employee->image) ? asset('employee_images/' . $employee->image) : asset('employee_images/' . $employee->image) }}"
                                                                    alt="">
                                                                {{ $employee->fullname }}
                                                            </p>
                                                            [{{ $employee->Departmentid->dep_name ?? 'No Department' }}]
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
                                        id="departmentDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span id="departmentFilterText">All Departments</span>
                                    </button>
                                    <ul class="dropdown-menu department-dropdown p-2"
                                        aria-labelledby="departmentDropdown" style="width: 200px;">
                                        <li>
                                            <input type="text" class="form-control form-control-sm mb-2"
                                                placeholder="Search departments..." id="departmentSearch">
                                        </li>
                                        <li>
                                            <select id="departmentFilter" class="form-select form-select-sm"
                                                size="8" style="width: 100%; border: none;">
                                                <option value="all" class="mb-4">All Departments</option>
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
                                    <button class="dropdown-toggle control-select" type="button" id="viewTypeDropdown"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <span id="viewTypeText">Weekly</span>
                                    </button>
                                    <ul class="dropdown-menu view-dropdown p-2" aria-labelledby="viewTypeDropdown"
                                        style="width: 150px;">
                                        <li>
                                            <select id="viewType" class="form-select form-select-sm" size="2"
                                                style="width: 100%; border: none;">
                                                <option value="weekly">Weekly</option>
                                                <option value="monthly">Monthly</option>
                                            </select>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-auto" style="margin-top: 1.3em;">
                        <button class="action-button primary" id="addBulkShift">
                            <i class="bi bi-plus-circle me-1"></i> Add Bulk Shift
                        </button>
                    </div>
                </div>
            </div>

            <!-- Calendar Display -->
            <div class="planner-calendar row  justify-content-between">
                <div class="col-auto p-3">
                    <span class="fw-bold p-1">Note:</span>
                    <span class="badge bg-success p-1">GS: General Shift</span>
                    <span class="badge bg-dark p-1">NS: Night Shift</span>
                    <span class="badge bg-warning p-1">MS: Early Morning Shift</span>
                    <span class="badge bg-secondary p-1">DO: Day Off</span>

                </div>


                <div class="col-auto ">

                    <div class="shift-status-container">
                        <div class="status-badge status-expired">
                            <i class="bi bi-circle-fill me-1"></i> Expired: <span id="expired-months"></span>
                        </div>
                        <div class="status-badge status-ongoing">
                            <i class="bi bi-circle-fill me-1"></i> Ongoing: <span id="ongoing-months"></span>
                        </div>
                        <div class="status-badge status-upcoming">
                            <i class="bi bi-circle-fill me-1"></i> Upcoming: <span id="upcoming-months"></span>
                        </div>
                    </div>


                </div>

                <!-- Date navigation -->
                <div class="row justify-content-center">
                    <div class="col-auto mb-4">
                        <div class="date-navigation w-25" id="weeklyDateNavigation">
                            <button class="nav-button" id="prevPeriod">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <span class="date-display fw-bold" id="currentPeriod">
                                {{ date('j M') }} - {{ date('j M', strtotime('+6 days')) }}
                            </span>
                            <button class="nav-button" id="nextPeriod">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Weekly View -->
                <div id="weeklyView" class="calendar-view">
                    <div class="week-header">
                        <div class="employee-header fw-bold fs-5 mt-5">Employee</div>
                        <div class="day-header">Sun</div>
                        <div class="day-header">Mon</div>
                        <div class="day-header">Tue</div>
                        <div class="day-header">Wed</div>
                        <div class="day-header">Thu</div>
                        <div class="day-header">Fri</div>
                        <div class="day-header">Sat</div>
                    </div>
                    <div class="week-body" id="weeklyCalendarBody">
                        <!-- Employee rows will be dynamically generated here -->
                    </div>
                </div>

                <!-- Monthly View -->
                <div id="monthlyView" class="calendar-view d-none">
                    <div class="month-header">
                        <div class="year-navigation">
                            <button class="nav-button me-4" id="prevYear">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <div class="year-title" id="yearTitle"></div>
                            <button class="nav-button ms-4" id="nextYear">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>

                        <div class="month-navigation">
                            <div class="months-container" id="monthsContainer">
                                <!-- Months will be dynamically generated here -->
                            </div>
                        </div>
                    </div>
                    <div class="month-scroll-container">
                        <div class="month-employee-column">
                            <div class="employee-header fw-bold fs-5">Employee</div>
                            <div class="employee-names" id="monthlyEmployeeNames"></div>
                        </div>
                        <div class="month-days-scroll">
                            <div class="month-days-header" id="monthDayHeaders"></div>
                            <div class="month-grid" id="monthlyCalendarBody"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination Controls -->
            <div class="pagination-controls">
                <button class="pagination-button" id="prevPage" disabled>
                    <i class="bi bi-chevron-left"></i>
                </button>
                <span class="page-info" id="pageInfo">Page 1 of 1</span>
                <button class="pagination-button" id="nextPage" disabled>
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>

        <!-- Single Shift Modal -->
        <div class="modal fade" id="shiftModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="shiftModalTitle">Shift Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="shiftForm">
                            @csrf
                            <input type="hidden" id="shiftId" name="shift_id">
                            <input type="hidden" id="shiftEmployee" name="employee_id">
                            <div class="mb-3">
                                {{-- <label for="shiftEmployee" class="form-label">Employee</label> --}}
                                <select id="shiftEmployee" class="form-select" disabled name="employee_id" required>
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->emp_id }}">{{ $employee->fullname }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="shiftDate" class="form-label">Date</label>
                                <input type="date" id="shiftDate" class="form-control" readonly name="date_no"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="shiftType" class="form-label">Shift Type</label>
                                <select id="shiftType" class="form-select" name="shift_type" required>
                                    <option value="gs">General Swift (9:00 - 18:00)</option>
                                    <option value="ns">Night Swift (22:00 - 6:00)</option>
                                    <option value="ms">Early Morning Swift (7:00 - 17:00)</option>
                                    <option value="holiday">Holiday (HO)</option>
                                    <option value="dayoff">Day Off</option>
                                </select>
                            </div>



                             <!-- Holiday Info Display -->
                    <div class="mb-3 d-none" id="holidayInfoContainer">
                        <div class="alert alert-info" style="background-color: #e8f4fd; border-color: #b8e1fc;">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-calendar-event me-2 fs-5"></i>
                                <div>
                                    <div class="fw-bold mb-1">Holiday Information</div>
                                    <div id="holidayOccasion" class="small">-</div>
                                    <div id="holidayType" class="small text-muted">-</div>
                                </div>
                            </div>
                        </div>
                    </div>

                            <!-- Swap Date -->
<div class="mb-3 d-none" id="swapDateContainer">
    <label class="form-label">Swap Date</label>
    <input type="date" id="swapDate" class="form-control">
</div>


                            <div class="mb-3">
                                <label for="shiftNotes" class="form-label">Notes</label>
                                <textarea id="shiftNotes" class="form-control" name="notes" rows="3"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger me-auto" id="deleteShift"
                            style="display: none;">
                            <i class="bi bi-trash me-1"></i> Delete
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="saveShift">
                            <i class="bi bi-save me-1"></i> Save Shift
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Shift Modal -->
        <div class="modal fade" id="bulkShiftModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog bulkshift modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Bulk Shifts</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="col-md-12">
                        <div class="alert alert-info" type="info" icon="info-circle">
                            <i class="fa fa-info-circle"></i>
                            The existing shift will be overridden. Sundays will automatically be set as Day Off.
                        </div>
                    </div>
                    <div class="modal-body">
                        <form id="bulkShiftForm">
                            @csrf
                            <input type="hidden" id="sundaysInRange" name="sundays">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="dropdown">
                                        <label class="mb-3">Departments</label>
                                        <button class="dropdown-toggle control-select" type="button"
                                            id="bulkDepartmentDropdown" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <span id="bulkDepartmentFilterText">All Departments</span>
                                        </button>
                                        <ul class="dropdown-menu department-dropdown p-2"
                                            aria-labelledby="bulkDepartmentDropdown" style="width: 300px;">
                                            <li>
                                                <input type="text" class="form-control form-control-sm mb-2"
                                                    placeholder="Search departments..." id="bulkDepartmentSearch">
                                            </li>
                                            <li class="d-flex justify-content-between px-2 mb-2">
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    id="bulkSelectAllDepartments">Select All</button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                                    id="bulkDeselectAllDepartments">Deselect All</button>
                                            </li>
                                            <li>
                                                <select id="bulkDepartmentFilter" class="form-select form-select-sm"
                                                    size="8" multiple>
                                                    <option value="all" data-admin="">All Departments</option>
                                                    @foreach ($departments as $department)
                                                        <option value="{{ $department->dep_name }}"
                                                            data-admin="{{ $department->admin_name }}">
                                                            {{ $department->dep_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-4 align-self-center">
                                    <label for="departmentadmin" class="form-label">Department Admin</label>
                                    <input type="text" name="department_admin" id="departmentadmin"
                                        class="form-control" readonly>
                                </div>

                                <div class="col-md-4">
                                    <div class="dropdown">
                                        <label class="mb-3">Employees</label>
                                        <button class="dropdown-toggle control-select" type="button"
                                            id="bulkEmployeeDropdown" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <span id="bulkEmployeeFilterText">All Employees</span>
                                        </button>
                                        <ul class="dropdown-menu employee-dropdown p-2"
                                            aria-labelledby="bulkEmployeeDropdown" style="width: 300px;">
                                            <li>
                                                <input type="text" class="form-control form-control-sm mb-2"
                                                    placeholder="Search employees..." id="bulkEmployeeSearch">
                                            </li>
                                            <li class="d-flex justify-content-between px-2 mb-2">
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    id="bulkSelectAllEmployees">Select All</button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                                    id="bulkDeselectAllEmployees">Deselect All</button>
                                            </li>
                                            <li>
                                                <select id="bulkEmployeeFilter" class="form-select form-select-sm"
                                                    size="8" style="width: 100%; border: none;" multiple
                                                    name="employee_ids[]">
                                                    @foreach ($employees as $employee)
                                                        <option value="{{ $employee->emp_id }}" class="mb-3 mt-2">
                                                            <div class="d-flex">
                                                                <div class="col-auto">
                                                                    <img src="{{ asset('employee_images/' . $employee->image) }}"
                                                                        alt="" class="avatar">
                                                                </div>
                                                                <div class="col-auto">
                                                                    <p class="mb-4">{{ $employee->fullname }}</p>
                                                                </div>
                                                            </div>
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="bulkShiftType" class="form-label">Shift Type</label>
                                    <select id="bulkShiftType" class="form-select" name="shift_type" required>
                                        <option value="gs">General Swift (9:00 - 18:00)</option>
                                        <option value="ns">Night Swift (22:00 - 6:00)</option>
                                        <option value="ms">Early Morning Swift (7:00 - 17:00)</option>
                                        <option value="dayoff">Day Off</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Assign Shift By</label>
                                    <div class="d-flex flex-wrap gap-3 mt-2">
                                        <div class="form-check">
                                            <input type="radio" class="form-check-input rounded-radio"
                                                name="assign_shift" id="date" value="date" checked>
                                            <label class="form-check-label" for="date">Date</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="radio" class="form-check-input rounded-radio"
                                                name="assign_shift" id="multiple" value="multiple">
                                            <label class="form-check-label" for="multiple">Multiple</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="radio" class="form-check-input rounded-radio"
                                                name="assign_shift" id="month" value="month">
                                            <label class="form-check-label" for="month">Month</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Single Date Field -->
                                <div class="col-md-4" id="singleDateField">
                                    <label for="singleDate" class="form-label">Date</label>
                                    <input type="text" id="singleDate" class="form-control datepicker"
                                        name="date_no" placeholder="Select date">
                                </div>

                                <!-- Date Range Fields -->
                                <div class="col-md-4 d-none" id="dateRangeFields">
                                    <label for="selectedDateRange" class="form-label">Date Range</label>
                                    <p class="" id="openDateRangePicker">
                                        <input type="text" class="form-control" id="selectedDateRange" readonly
                                            placeholder="Select date range" value="24-06-2025 To 24-06-2025"
                                            width="100">
                                    </p>
                                    <input type="hidden" id="date_range_from" name="date_range_from">
                                    <input type="hidden" id="date_range_to" name="date_range_to">
                                    <!-- Date Range Picker Container (hidden by default) -->
                                    <div class="date-range-picker-container mt-2 d-none"
                                        id="dateRangePickerContainer">
                                        <span id="dateRangeDisplay" style="display: none;"></span>
                                        <div class="date-range-calendar">
                                            <div class="month-container">
                                                <div class="month-header" id="month1Header">
                                                    <button type="button" class="month-nav-btn" id="prevMonth">
                                                        <i class="bi bi-chevron-left"></i>
                                                    </button>
                                                    <span>Jun 2025</span>
                                                </div>
                                                <div class="weekdays">
                                                    <span>Su</span>
                                                    <span>Mo</span>
                                                    <span>Tu</span>
                                                    <span>We</span>
                                                    <span>Th</span>
                                                    <span>Fr</span>
                                                    <span>Sa</span>
                                                </div>
                                                <div class="days-grid" id="month1Days"></div>
                                            </div>

                                            <div class="month-container">
                                                <div class="month-header" id="month2Header">
                                                    <span>Jul 2025</span>
                                                    <button class="month-nav-btn" id="nextMonth">
                                                        <i class="bi bi-chevron-right"></i>
                                                    </button>
                                                </div>
                                                <div class="weekdays">
                                                    <span>Su</span>
                                                    <span>Mo</span>
                                                    <span>Tu</span>
                                                    <span>We</span>
                                                    <span>Th</span>
                                                    <span>Fr</span>
                                                    <span>Sa</span>
                                                </div>
                                                <div class="days-grid" id="month2Days"></div>
                                            </div>
                                        </div>

                                        <div class="date-range-footer border-0">
                                            <button type="button"
                                                class="btn btn-outline-secondary text-start btn-sm cancel-btn">Cancel</button>
                                            <button type="button"
                                                class="btn btn-sm btn-primary apply-btn">Apply</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Month Field -->
                                <div class="col-md-4 d-none" id="monthField">
                                    <label for="monthYearPicker" class="form-label">Month-Year</label>
                                    <input type="text" id="monthYearPicker" class="form-control"
                                        name="month_year" placeholder="Select month">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="bulkNotes" class="form-label">Notes</label>
                                <textarea id="bulkNotes" class="form-control" name="notes" rows="3"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="saveBulkShifts">
                            Add
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .shift-tag.auto-dayoff {
                border: 2px dashed #6c757d !important;
                opacity: 0.8;
            }

            .shift-tag.auto-dayoff:hover::after {
                content: "Auto Day Off (Sunday)";
                position: absolute;
                bottom: -25px;
                left: 0;
                background: #6c757d;
                color: white;
                padding: 2px 5px;
                border-radius: 3px;
                font-size: 0.75rem;
                white-space: nowrap;
                z-index: 1000;
            }

            .day.sunday {
                background-color: #f8f9fa;
                color: #dc3545;
                font-weight: bold;
            }

            .day.sunday.in-range {
                background-color: #ffeaea;
            }

            .day.sunday.selected {
                background-color: #dc3545;
                color: white;
            }

            .dropdown.error-validation .dropdown-toggle {
                border-color: #dc3545;
                box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
            }

            .error-message {
                color: #dc3545;
                font-size: 0.875rem;
                margin-top: 0.25rem;
            }

            .fade-out {
                opacity: 0;
                transition: opacity 0.3s ease-out;
            }
        </style>



<script>
let attendanceData = @json($attendance);
let leaveData = @json($approvedLeaves);
</script>

<script>



function updateShiftTypeDropdown(type) {
    const select = document.getElementById('shiftType');

    // Reset all options
    Array.from(select.options).forEach(option => {
        option.style.display = '';
    });

    // 🟢 GS / MS / NS → hide holiday & dayoff
    if (type === 'gs' || type === 'ms' || type === 'ns') {
        Array.from(select.options).forEach(option => {
            if (option.value === 'holiday') {
                option.style.display = 'none';
            }
        });
    }

    // 🔵 DAYOFF → only dayoff
    if (type === 'dayoff') {
        Array.from(select.options).forEach(option => {
            if (option.value !== 'dayoff') {
                option.style.display = 'none';
            }
        });
    }

    // 🟡 HOLIDAY → only holiday
    if (type === 'holiday') {
        Array.from(select.options).forEach(option => {
            if (option.value !== 'holiday') {
                option.style.display = 'none';
            }
        });
    }
}

</script>
        <script>




            document.addEventListener('DOMContentLoaded', function() {
                // Initialize date pickers
                const singleDatePicker = flatpickr("#singleDate", {
                    dateFormat: "d-m-Y",
                    defaultDate: "today",
                    allowInput: true,
                    onChange: function(selectedDates, dateStr, instance) {
                        checkIfSundayAndUpdateUIBulk(dateStr);
                    }
                });

                // Calculate month ranges for each status
                function calculateShiftStatusRanges() {
                    const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                        "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                    ];

                    const currentDate = new Date();
                    const currentMonth = currentDate.getMonth();
                    const currentYear = currentDate.getFullYear();

                    // Get unique months from all shifts
                    const shiftMonths = {};

                    shifts.forEach(shift => {
                        const shiftDate = new Date(shift.date_no);
                        const month = shiftDate.getMonth();
                        const year = shiftDate.getFullYear();
                        const key = `${year}-${month}`;

                        if (!shiftMonths[key]) {
                            shiftMonths[key] = {
                                month: month,
                                year: year,
                                name: monthNames[month]
                            };
                        }
                    });

                    // Convert to array and sort by date
                    const allMonths = Object.values(shiftMonths).sort((a, b) => {
                        if (a.year !== b.year) return a.year - b.year;
                        return a.month - b.month;
                    });

                    // Categorize months
                    const expiredMonths = [];
                    const ongoingMonths = [];
                    const upcomingMonths = [];

                    allMonths.forEach(monthData => {
                        if (monthData.year < currentYear ||
                            (monthData.year === currentYear && monthData.month < currentMonth)) {
                            expiredMonths.push(monthData);
                        } else if (monthData.year === currentYear && monthData.month === currentMonth) {
                            ongoingMonths.push(monthData);
                        } else {
                            upcomingMonths.push(monthData);
                        }
                    });

                    // If no expired months with shifts, show previous months anyway
                    if (expiredMonths.length === 0) {
                        // Add at least one previous month to expired
                        let prevMonth = currentMonth - 1;
                        let prevYear = currentYear;

                        if (prevMonth < 0) {
                            prevMonth = 11;
                            prevYear = currentYear - 1;
                        }

                        expiredMonths.push({
                            month: prevMonth,
                            year: prevYear,
                            name: monthNames[prevMonth]
                        });
                    }

                    // Update the UI with the calculated month ranges
                    document.getElementById('expired-months').textContent = formatMonthRange(expiredMonths);
                    document.getElementById('ongoing-months').textContent = formatMonthRange(ongoingMonths);
                    document.getElementById('upcoming-months').textContent = formatMonthRange(upcomingMonths);
                }

                // Helper function to format month range for display
                function formatMonthRange(months) {
                    if (months.length === 0) return 'None';
                    if (months.length === 1) return `${months[0].name} ${months[0].year}`;

                    // Check if all months are in the same year
                    const sameYear = months.every(m => m.year === months[0].year);

                    if (sameYear) {
                        return `${months[0].name} - ${months[months.length - 1].name} ${months[0].year}`;
                    } else {
                        // Handle cross-year ranges
                        return `${months[0].name} ${months[0].year} - ${months[months.length - 1].name} ${months[months.length - 1].year}`;
                    }
                }


                class DateRangePicker {
                    constructor() {
                        this.currentDate = new Date();
                        this.selectedRange = {
                            start: new Date(),
                            end: new Date()
                        };
                        this.init();
                    }

                    init() {
                        this.render();
                        this.setupEventListeners();
                    }

                    render() {
                        const month1 = new Date(this.currentDate);
                        this.renderMonth(month1, 'month1Header', 'month1Days');

                        const month2 = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() + 1, 1);
                        this.renderMonth(month2, 'month2Header', 'month2Days');

                        this.updateRangeDisplay();
                    }

                    renderMonth(date, headerId, daysId) {
                        const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                        ];

                        const headerElement = document.querySelector(`#${headerId} span`);
                        headerElement.textContent = `${monthNames[date.getMonth()]} ${date.getFullYear()}`;

                        const firstDay = new Date(date.getFullYear(), date.getMonth(), 1).getDay();
                        const totalDays = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();

                        const daysGrid = document.getElementById(daysId);
                        daysGrid.innerHTML = '';

                        const prevMonthDays = new Date(date.getFullYear(), date.getMonth(), 0).getDate();
                        for (let i = 0; i < firstDay; i++) {
                            const day = document.createElement('div');
                            day.className = 'day disabled';
                            day.textContent = prevMonthDays - firstDay + i + 1;
                            daysGrid.appendChild(day);
                        }

                        const today = new Date();
                        for (let i = 1; i <= totalDays; i++) {
                            const day = document.createElement('div');
                            day.className = 'day';
                            day.dataset.date = this.formatDate(new Date(date.getFullYear(), date.getMonth(), i),
                                'dd-MM-yyyy');
                            day.textContent = i;

                            // Check if this day is Sunday
                            const dayOfWeek = new Date(date.getFullYear(), date.getMonth(), i).getDay();
                            if (dayOfWeek === 0) {
                                day.classList.add('sunday');
                            }

                            if (i === today.getDate() &&
                                date.getMonth() === today.getMonth() &&
                                date.getFullYear() === today.getFullYear()) {
                                day.classList.add('today');
                            }

                            const currentDate = new Date(date.getFullYear(), date.getMonth(), i);
                            if (this.selectedRange.start && this.selectedRange.end) {
                                if (currentDate >= this.selectedRange.start && currentDate <= this.selectedRange
                                    .end) {
                                    day.classList.add('in-range');
                                }
                                if (this.formatDate(currentDate) === this.formatDate(this.selectedRange.start)) {
                                    day.classList.add('selected');
                                }
                                if (this.formatDate(currentDate) === this.formatDate(this.selectedRange.end)) {
                                    day.classList.add('selected');
                                }
                            }

                            daysGrid.appendChild(day);
                        }

                        const daysShown = firstDay + totalDays;
                        const nextMonthDays = 42 - daysShown;
                        for (let i = 1; i <= nextMonthDays; i++) {
                            const day = document.createElement('div');
                            day.className = 'day disabled';
                            day.textContent = i;
                            daysGrid.appendChild(day);
                        }
                    }

                    setupEventListeners() {
                        document.getElementById('month1Days').addEventListener('click', (e) => this.handleDayClick(
                            e));
                        document.getElementById('month2Days').addEventListener('click', (e) => this.handleDayClick(
                            e));

                        document.getElementById('prevMonth').addEventListener('click', (e) => {
                            e.preventDefault();
                            this.currentDate.setMonth(this.currentDate.getMonth() - 1);
                            this.render();
                        });

                        document.getElementById('nextMonth').addEventListener('click', (e) => {
                            e.preventDefault();
                            this.currentDate.setMonth(this.currentDate.getMonth() + 1);
                            this.render();
                        });

                        document.querySelector('.cancel-btn').addEventListener('click', () => {
                            const today = new Date();
                            this.selectedRange = {
                                start: new Date(today),
                                end: new Date(today)
                            };
                            this.updateRangeDisplay();
                            document.getElementById('selectedDateRange').value =
                                `${this.formatDate(today, 'dd-MM-yyyy')} To ${this.formatDate(today, 'dd-MM-yyyy')}`;
                            document.getElementById('dateRangePickerContainer').classList.add('d-none');
                            this.render();
                        });

                        document.querySelector('.apply-btn').addEventListener('click', () => {
                            if (this.selectedRange.start && this.selectedRange.end) {
                                const formattedRange =
                                    `${this.formatDate(this.selectedRange.start, 'dd-MM-yyyy')} To ${this.formatDate(this.selectedRange.end, 'dd-MM-yyyy')}`;
                                this.updateRangeDisplay(formattedRange);
                                document.getElementById('selectedDateRange').value = formattedRange;
                                document.getElementById('dateRangePickerContainer').classList.add('d-none');

                                // Set the hidden input values for the form
                                document.getElementById('date_range_from').value = this.formatDate(this
                                    .selectedRange.start, 'yyyy-MM-dd');
                                document.getElementById('date_range_to').value = this.formatDate(this
                                    .selectedRange.end, 'yyyy-MM-dd');
                            }
                        });

                        document.getElementById('openDateRangePicker').addEventListener('click', () => {
                            const picker = document.getElementById('dateRangePickerContainer');
                            picker.classList.toggle('d-none');
                        });
                    }

                    handleDayClick(e) {
                        if (e.target.classList.contains('day') && !e.target.classList.contains('disabled')) {
                            const dateStr = e.target.dataset.date;
                            const [day, month, year] = dateStr.split('-');
                            const date = new Date(year, month - 1, day);

                            if (!this.selectedRange.start || this.selectedRange.end) {
                                this.selectedRange = {
                                    start: date,
                                    end: null
                                };
                            } else if (date < this.selectedRange.start) {
                                this.selectedRange = {
                                    start: date,
                                    end: this.selectedRange.start
                                };
                            } else {
                                this.selectedRange.end = date;
                            }

                            this.render();
                        }
                    }

                    updateRangeDisplay(text = null) {
                        if (!text) {
                            if (this.selectedRange.start && this.selectedRange.end) {
                                text =
                                    `${this.formatDate(this.selectedRange.start, 'dd-MM-yyyy')} To ${this.formatDate(this.selectedRange.end, 'dd-MM-yyyy')}`;
                            } else if (this.selectedRange.start) {
                                text =
                                    `${this.formatDate(this.selectedRange.start, 'dd-MM-yyyy')} To ${this.formatDate(this.selectedRange.start, 'dd-MM-yyyy')}`;
                            } else {
                                text = 'Select date range';
                            }
                        }
                        document.getElementById('dateRangeDisplay').textContent = text;
                    }

                    formatDate(date, format = 'dd-MM-yyyy') {
                        if (!date) return '';

                        const day = String(date.getDate()).padStart(2, '0');
                        const month = String(date.getMonth() + 1).padStart(2, '0');
                        const year = date.getFullYear();
                        const monthName = date.toLocaleString('default', {
                            month: 'short'
                        });

                        return format
                            .replace('dd', day)
                            .replace('MM', month)
                            .replace('MMM', monthName)
                            .replace('yyyy', year);
                    }
                }

                const dateRangePicker = new DateRangePicker();

                document.getElementById('bulkShiftModal').addEventListener('hidden.bs.modal', function() {
                    const shiftTypeSelect = document.getElementById('bulkShiftType');
                    Array.from(shiftTypeSelect.options).forEach(option => {
                        option.disabled = false;
                        option.style.display = '';
                    });
                    shiftTypeSelect.disabled = false;
                });

                const monthYearPicker = flatpickr("#monthYearPicker", {
                    plugins: [
                        new monthSelectPlugin({
                            shorthand: true,
                            dateFormat: "M Y",
                            altFormat: "F Y",
                            theme: "light"
                        })
                    ],
                    allowInput: true
                });

                // Current date tracking
                let currentDate = new Date();
                let currentView = 'weekly';
                let shifts = [];
                let employees = @json($employees);

                let currentPage = 1;
                let entriesPerPage = 5;

                // DOM elements
                const viewType = document.getElementById('viewType');
                const weeklyView = document.getElementById('weeklyView');
                const monthlyView = document.getElementById('monthlyView');
                const prevPeriodBtn = document.getElementById('prevPeriod');
                const nextPeriodBtn = document.getElementById('nextPeriod');
                const currentPeriodBtn = document.getElementById('currentPeriod');
                const employeeFilter = document.getElementById('employeeFilter');
                const departmentFilter = document.getElementById('departmentFilter');
                const entriesPerPageSelect = document.getElementById('entriesPerPage');
                const prevPageBtn = document.getElementById('prevPage');
                const nextPageBtn = document.getElementById('nextPage');
                const shiftModal = new bootstrap.Modal(document.getElementById('shiftModal'));
                const bulkShiftModal = new bootstrap.Modal(document.getElementById('bulkShiftModal'));
                const weeklyDateNavigation = document.getElementById('weeklyDateNavigation');

                // Initialize the planner
                initPlanner();

                function initPlanner() {
                    employees = employees.map(employee => {
                        // Make sure Departmentid exists and has dep_name
                        const departmentName = (employee.departmentid && employee.departmentid.dep_name) ?
                            employee.departmentid.dep_name :
                            'No Department';

                        return {
                            ...employee,
                            dep_name: departmentName, // Add this line to ensure dep_name is always available
                            image: employee.image ? '/employee_images/' + employee.image :
                                '/images/admin_default.jpg'
                        };
                    });
                    fetchShifts();
                    setupEventListeners();
                }

                function setupEventListeners() {
                    // View type toggle
                    viewType.addEventListener('change', function() {
                        currentView = this.value;
                        toggleView();
                        refreshCalendar();
                    });

                    // Navigation controls
                    prevPeriodBtn.addEventListener('click', navigateToPreviousPeriod);
                    nextPeriodBtn.addEventListener('click', navigateToNextPeriod);
                    currentPeriodBtn.addEventListener('click', navigateToCurrentPeriod);

                    // Filter controls
                    employeeFilter.addEventListener('change', function() {
                        const selectedOption = this.options[this.selectedIndex];
                        let displayText = "All Employees";

                        if (this.value) {
                            const tempDiv = document.createElement('div');
                            tempDiv.innerHTML = selectedOption.innerHTML;
                            const nameElement = tempDiv.querySelector('.fw-bold');
                            displayText = nameElement ? nameElement.textContent : selectedOption.text;
                        }

                        document.getElementById('employeeFilterText').textContent = displayText;
                        currentPage = 1;
                        refreshCalendar();
                    });

                    departmentFilter.addEventListener('change', function() {
                        const selectedOption = this.options[this.selectedIndex];
                        let displayText = "All Departments";

                        if (this.value !== "all") {
                            displayText = selectedOption.textContent.trim();
                        }

                        document.getElementById('departmentFilterText').textContent = displayText;
                        currentPage = 1;
                        refreshCalendar();
                    });

                    // View type change handler
                    viewType.addEventListener('change', function() {
                        const selectedOption = this.options[this.selectedIndex];
                        document.getElementById('viewTypeText').textContent = selectedOption.textContent.trim();
                        currentView = this.value;
                        toggleView();
                        refreshCalendar();
                    });

                    // Entries per page
                    entriesPerPageSelect.addEventListener('change', function() {
                        entriesPerPage = parseInt(this.value);
                        currentPage = 1;
                        refreshCalendar();
                    });

                    // Pagination controls
                    prevPageBtn.addEventListener('click', goToPrevPage);
                    nextPageBtn.addEventListener('click', goToNextPage);

                    // Add bulk shift button
                    document.getElementById('addBulkShift').addEventListener('click', openBulkShiftModal);

                    // Save shift
                    document.getElementById('saveShift').addEventListener('click', saveShift);

                    // Delete shift
                    document.getElementById('deleteShift').addEventListener('click', confirmDeleteShift);

                    // Save bulk shifts
                    document.getElementById('saveBulkShifts').addEventListener('click', prepareBulkShifts);

                    // Year navigation for monthly view
                    document.getElementById('prevYear').addEventListener('click', function() {
                        currentDate.setFullYear(currentDate.getFullYear() - 1);
                        refreshCalendar();
                    });

                    document.getElementById('nextYear').addEventListener('click', function() {
                        currentDate.setFullYear(currentDate.getFullYear() + 1);
                        refreshCalendar();
                    });

                    // Bulk shift assignment type change
                    document.querySelectorAll('input[name="assign_shift"]').forEach(radio => {
                        radio.addEventListener('change', function() {
                            document.getElementById('singleDateField').classList.add('d-none');
                            document.getElementById('dateRangeFields').classList.add('d-none');
                            document.getElementById('monthField').classList.add('d-none');

                            if (this.value === 'date') {
                                document.getElementById('singleDateField').classList.remove('d-none');
                            } else if (this.value === 'multiple') {
                                document.getElementById('dateRangeFields').classList.remove('d-none');
                            } else if (this.value === 'month') {
                                document.getElementById('monthField').classList.remove('d-none');
                            }
                        });
                    });

                    // Bulk modal select/deselect all functionality
                    document.getElementById('bulkSelectAllEmployees')?.addEventListener('click', function() {
                        const options = document.querySelectorAll('#bulkEmployeeFilter option');
                        options.forEach(option => {
                            option.selected = true;
                        });
                        updateBulkEmployeeFilterText();
                    });

                    document.getElementById('bulkDeselectAllEmployees')?.addEventListener('click', function() {
                        const options = document.querySelectorAll('#bulkEmployeeFilter option');
                        options.forEach(option => {
                            option.selected = false;
                        });
                        updateBulkEmployeeFilterText();
                    });

                    document.getElementById('bulkSelectAllDepartments')?.addEventListener('click', function() {
                        const options = document.querySelectorAll('#bulkDepartmentFilter option');
                        options.forEach(option => {
                            option.selected = true;
                        });
                        updateBulkDepartmentFilterText();
                    });

                    document.getElementById('bulkDeselectAllDepartments')?.addEventListener('click', function() {
                        const options = document.querySelectorAll('#bulkDepartmentFilter option');
                        options.forEach(option => {
                            option.selected = false;
                        });
                        updateBulkDepartmentFilterText();
                    });

                    // Update bulk employee filter text when selection changes
                    document.getElementById('bulkEmployeeFilter')?.addEventListener('change',
                        updateBulkEmployeeFilterText);

                    // Update bulk department filter text when selection changes
                    document.getElementById('bulkDepartmentFilter')?.addEventListener('change',
                        updateBulkDepartmentFilterText);

                    document.getElementById('bulkDepartmentFilter')?.addEventListener('change', function() {
                        const selectedOptions = Array.from(this.selectedOptions);
                        const adminInput = document.getElementById('departmentadmin');

                        if (selectedOptions.length > 0) {
                            // Find the first selected option that isn't "all"
                            const firstDeptOption = selectedOptions.find(opt => opt.value !== 'all');

                            if (firstDeptOption) {
                                adminInput.value = firstDeptOption.dataset.admin;
                            } else {
                                // Only "All Departments" is selected - clear or set a default message
                                adminInput.value = '';
                            }
                        } else {
                            // If nothing is selected, clear the field
                            adminInput.value = '';
                        }
                    });
                    // Department filter in bulk modal - filter employees based on selected departments
                    document.getElementById('bulkDepartmentFilter')?.addEventListener('change', function() {
                        const selectedDepartments = Array.from(this.selectedOptions).map(opt => opt.value);
                        const employeeSelect = document.getElementById('bulkEmployeeFilter');

                        if (!employeeSelect) return;

                        Array.from(employeeSelect.options).forEach(option => {
                            option.selected = false;
                        });

                        if (selectedDepartments.length === 0 || selectedDepartments.includes('all')) {
                            Array.from(employeeSelect.options).forEach(option => {
                                option.style.display = '';
                            });
                        } else {
                            Array.from(employeeSelect.options).forEach(option => {
                                const employeeId = option.value;
                                if (!employeeId) return;

                                const employee = employees.find(e => e.emp_id == employeeId);
                                // Use Departmentid.dep_name for filtering
                                const employeeDept = employee.departmentid ? employee.departmentid
                                    .dep_name : 'No Department';

                                if (employee && selectedDepartments.includes(employeeDept)) {
                                    option.style.display = '';
                                } else {
                                    option.style.display = 'none';
                                }
                            });
                        }

                        updateBulkEmployeeFilterText();
                    });
                    // Search functionality for bulk employee dropdown
                    document.getElementById('bulkEmployeeSearch')?.addEventListener('input', function() {
                        const searchTerm = this.value.toLowerCase();
                        const options = document.querySelectorAll('#bulkEmployeeFilter option');

                        for (let i = 0; i < options.length; i++) {
                            const option = options[i];
                            if (option.style.display === 'none') continue; // Skip already hidden options

                            const text = option.text.toLowerCase();
                            option.style.display = text.includes(searchTerm) ? '' : 'none';
                        }
                    });

                    // Search functionality for bulk department dropdown
                    document.getElementById('bulkDepartmentSearch')?.addEventListener('input', function() {
                        const searchTerm = this.value.toLowerCase();
                        const options = document.querySelectorAll('#bulkDepartmentFilter option');

                        for (let i = 0; i < options.length; i++) {
                            const option = options[i];
                            const text = option.text.toLowerCase();
                            option.style.display = text.includes(searchTerm) ? '' : 'none';
                        }
                    });

                    // Search functionality for employee dropdown
                    document.getElementById('employeeSearch').addEventListener('input', function() {
                        const searchTerm = this.value.toLowerCase();
                        const options = employeeFilter.options;

                        for (let i = 0; i < options.length; i++) {
                            const option = options[i];
                            const text = option.text.toLowerCase();
                            option.style.display = text.includes(searchTerm) ? '' : 'none';
                        }
                    });

                    // Search functionality for department dropdown
                    document.getElementById('departmentSearch').addEventListener('input', function() {
                        const searchTerm = this.value.toLowerCase();
                        const options = departmentFilter.options;

                        for (let i = 0; i < options.length; i++) {
                            const option = options[i];
                            const text = option.text.toLowerCase();
                            option.style.display = text.includes(searchTerm) ? '' : 'none';
                        }
                    });




                    // Reset when modal is hidden
                    document.getElementById('shiftModal').addEventListener('hidden.bs.modal', function() {
                        const shiftTypeSelect = document.getElementById('shiftType');
                        Array.from(shiftTypeSelect.options).forEach(option => {
                            option.disabled = false;
                            option.style.display = '';
                        });
                        shiftTypeSelect.disabled = false;
                        document.getElementById('shiftModalTitle').textContent = 'Shift Details';
                    });
                }

                function toggleView() {
                    if (currentView === 'weekly') {
                        weeklyView.classList.remove('d-none');
                        monthlyView.classList.add('d-none');
                        weeklyDateNavigation.style.display = 'flex';
                    } else {
                        weeklyView.classList.add('d-none');
                        monthlyView.classList.remove('d-none');
                        weeklyDateNavigation.style.display = 'none';
                    }
                }

                function navigateToPreviousPeriod() {
                    if (currentView === 'weekly') {
                        currentDate.setDate(currentDate.getDate() - 7);
                    } else {
                        currentDate.setMonth(currentDate.getMonth() - 1);
                    }
                    refreshCalendar();
                }

                function navigateToNextPeriod() {
                    if (currentView === 'weekly') {
                        currentDate.setDate(currentDate.getDate() + 7);
                    } else {
                        currentDate.setMonth(currentDate.getMonth() + 1);
                    }
                    refreshCalendar();
                }

                function navigateToCurrentPeriod() {
                    currentDate = new Date();
                    refreshCalendar();
                }

                function goToPrevPage() {
                    if (currentPage > 1) {
                        currentPage--;
                        refreshCalendar();
                    }
                }

                function goToNextPage() {
                    const {
                        totalPages
                    } = filterEmployees();
                    if (currentPage < totalPages) {
                        currentPage++;
                        refreshCalendar();
                    }
                }



                function fetchShifts() {
                    // Fetch shifts from server
                    fetch('shifts/get')
                        .then(response => response.json())
                        .then(data => {

                            shifts = data;
                            calculateShiftStatusRanges();
                            refreshCalendar();
                        })
                        .catch(error => {
                            console.error('Error fetching shifts:', error);
                            shifts = [];
                            calculateShiftStatusRanges();
                            refreshCalendar();
                        });
                }

                function refreshCalendar() {
                    if (currentView === 'weekly') {
                        renderWeekView(currentDate);
                    } else {
                        renderMonthView(currentDate);
                    }
                    updatePaginationControls();
                }

                function updatePaginationControls() {
                    const {
                        allEmployees,
                        totalPages
                    } = filterEmployees();
                    const pageInfo = document.getElementById('pageInfo');

                    prevPageBtn.disabled = currentPage <= 1;
                    nextPageBtn.disabled = currentPage >= totalPages || totalPages === 0;
                    pageInfo.textContent = `Page ${currentPage} of ${totalPages} (${allEmployees.length} employees)`;
                }

                function filterEmployees() {
                    let filtered = [...employees];

                    const department = departmentFilter.value;
                    if (department && department !== 'all') {
                        filtered = filtered.filter(employee => employee.dep_name === department);
                    }

                    const employeeId = employeeFilter.value;
                    if (employeeId) {
                        filtered = filtered.filter(employee => employee.emp_id == employeeId);
                    }

                    const startIndex = (currentPage - 1) * entriesPerPage;
                    const endIndex = startIndex + entriesPerPage;
                    const paginatedEmployees = filtered.slice(startIndex, endIndex);

                    return {
                        allEmployees: filtered,
                        paginatedEmployees: paginatedEmployees,
                        totalPages: Math.ceil(filtered.length / entriesPerPage)
                    };
                }

                function renderWeekView(date) {
                    const startOfWeek = getStartOfWeek(date);
                    const endOfWeek = getEndOfWeek(date);

                    const dayHeaders = weeklyView.querySelectorAll('.day-header');
                    const tempDate = new Date(startOfWeek);

                    dayHeaders.forEach((header, index) => {
                        const dayName = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'][index];
                        const dayNumber = tempDate.getDate();
                        const monthName = tempDate.toLocaleString('default', {
                            month: 'short'
                        });
                        header.innerHTML = `<span class="fs-4">${dayNumber}</span> ${dayName}<br>${monthName}`;
                        tempDate.setDate(tempDate.getDate() + 1);
                    });

                    const startDateStr = startOfWeek.toLocaleDateString('en-US', {
                        month: 'short',
                        day: 'numeric'
                    });
                    const endDateStr = endOfWeek.toLocaleDateString('en-US', {
                        month: 'short',
                        day: 'numeric'
                    });
                    document.getElementById('currentPeriod').textContent = `${startDateStr} - ${endDateStr}`;

                    const {
                        paginatedEmployees: filteredEmployees
                    } = filterEmployees();

                    let calendarBody = '';

                    filteredEmployees.forEach(employee => {
                        calendarBody += `<div class="employee-row" data-employee-id="${employee.emp_id}">`;

                        calendarBody += `
                            <div class="employee-info">
                                <div class="d-flex">
                                    <div class="me-2 mt-1">
                                        <img src="${employee.image}"
                                             class="avatar-img rounded-circle"
                                             style="width: 36px; height: 36px; object-fit: cover;">
                                    </div>
                                    <div>
                                        <div class="fw-bold">${employee.fullname}</div>
                                       <div class="small text-muted" style="margin-top: -2px">${employee.departmentid ? employee.departmentid.dep_name : 'No Department'}</div>

                                    </div>
                                </div>
                            </div>
                        `;

                        const weekStart = new Date(startOfWeek);
                        for (let i = 0; i < 7; i++) {
                            const currentDay = new Date(weekStart);
                            currentDay.setDate(weekStart.getDate() + i);
                            const dayString = formatDate(currentDay);

                            calendarBody += `<div class="day-cell" data-date="${dayString}">`;

                            const dayShifts = shifts.filter(shift =>
                                shift.employee_id == employee.emp_id && shift.date_no === dayString
                            );

                            dayShifts.forEach(shift => {
                                calendarBody += renderShiftTag(shift);
                            });

                            if (dayShifts.length === 0) {
                                calendarBody +=
                                    `<div class="add-shift" onclick="openShiftModalForCell(this)"><i class="bi bi-plus-circle"></i></div>`;
                            }

                            calendarBody += `</div>`;
                        }

                        calendarBody += `</div>`;
                    });

                    document.getElementById('weeklyCalendarBody').innerHTML = calendarBody;

                    document.querySelectorAll('.shift-tag').forEach(tag => {
                        tag.addEventListener('click', function() {
                            const shiftId = this.getAttribute('data-shift-id');
                            editShift(shiftId);
                        });
                    });
                }

                function renderShiftTag(shift) {
                    const shiftClass = getShiftClass(shift.shift_type);
                    const isAutoDayoff = shift.is_auto_dayoff;

                    return `
                        <div class="shift-tag ${shiftClass} ${isAutoDayoff ? 'auto-dayoff' : ''}"
                             data-shift-id="${shift.shift_id}"
                             data-shift-type="${shift.shift_type}"
                            title="${
    isAutoDayoff
    ? 'Auto-assigned Day Off (Sunday)'
    : (
        shift.shift_type == 4
        ? `${shift.occasion ?? 'Holiday'} (${shift.holiday_type ?? ''})`
        : getShiftFullName(shift.shift_type, shift)
      )
}">
                          ${getShiftAbbreviation(shift.shift_type, shift)}
                            ${shift.notes ? `<div class="shift-notes">${shift.notes}</div>` : ''}
                        </div>
                    `;
                }

                function getShiftClass(type) {
                    switch (type) {
                        case 0:
                            return 'gs';
                        case 1:
                            return 'ms';
                        case 2:
                            return 'ns';
                        case 3:
                            return 'dayoff';
                            case 4:
    return 'holiday';
                        default:
                            return '';
                    }
                }

                function renderMonthView(date) {
                    const year = date.getFullYear();
                    const currentMonth = date.getMonth();

                    document.getElementById('yearTitle').textContent = year;

                    const monthsContainer = document.getElementById('monthsContainer');
                    monthsContainer.innerHTML = '';

                    for (let i = 0; i < 12; i++) {
                        const monthName = new Date(year, i, 1).toLocaleString('default', {
                            month: 'short'
                        });
                        const monthTab = document.createElement('div');
                        monthTab.className = `month-tab ${i === currentMonth ? 'active' : ''}`;
                        monthTab.textContent = monthName;
                        monthTab.dataset.monthIndex = i;
                        monthTab.addEventListener('click', function() {
                            currentDate.setMonth(i);
                            refreshCalendar();
                        });
                        monthsContainer.appendChild(monthTab);
                    }

                    const daysInMonth = new Date(year, currentMonth + 1, 0).getDate();

                    let dayHeaders = '';
                    for (let day = 1; day <= daysInMonth; day++) {
                        const currentDate = new Date(year, currentMonth, day);
                        const dayName = currentDate.toLocaleDateString('en-US', {
                            weekday: 'short'
                        });
                        dayHeaders += `
                            <div class="day-header">
                                <div class="day-name">${dayName}</div>
                                <div class="day-number">${day}</div>
                            </div>
                        `;
                    }
                    document.getElementById('monthDayHeaders').innerHTML = dayHeaders;

                    const {
                        paginatedEmployees: filteredEmployees
                    } = filterEmployees();

                    let employeeNames = '';
                    filteredEmployees.forEach(employee => {
                        employeeNames += `
                            <div class="employee-name" data-employee-id="${employee.emp_id}">
                                <div class="d-flex align-items-center">
                                    <img src="${employee.image}"
                                         class="avatar-img rounded-circle me-2"
                                         style="width: 32px; height: 32px; object-fit: cover;">
                                    <div>
                                        <div class="fw-bold">${employee.fullname}</div>
                                                             <div class="small text-muted">${employee.departmentid ? employee.departmentid.dep_name : 'No Department'}</div>

                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    document.getElementById('monthlyEmployeeNames').innerHTML = employeeNames;

                    let calendarBody = '';
                    filteredEmployees.forEach(employee => {
                        calendarBody += `<div class="employee-row" data-employee-id="${employee.emp_id}">`;

                        for (let day = 1; day <= daysInMonth; day++) {
                            const currentDate = new Date(year, currentMonth, day);
                            const dateString = formatDate(currentDate);

                            calendarBody += `<div class="day-cell" data-date="${dateString}">`;

                            const dayShifts = shifts.filter(shift =>
                                shift.employee_id == employee.emp_id && shift.date_no === dateString
                            );

                            dayShifts.forEach(shift => {
                                calendarBody +=
                                    `<div class="shift-tag-month ${getShiftClass(shift.shift_type)} ${shift.is_auto_dayoff ? 'auto-dayoff' : ''}"
                                         data-shift-id="${shift.shift_id}"
                                         data-shift-type="${shift.shift_type}"
                                         title="${shift.is_auto_dayoff ? 'Auto-assigned Day Off (Sunday)' : getShiftFullName(shift.shift_type, shift)}">`;
                                calendarBody += `${getMonthViewAbbreviation(shift.shift_type)}`;
                                calendarBody += `</div>`;
                            });

                            if (dayShifts.length === 0) {
                                calendarBody +=
                                    `<div class="add-shift" onclick="openShiftModalForCell(this)"><i class="bi bi-plus-circle"></i></div>`;
                            }

                            calendarBody += `</div>`;
                        }

                        calendarBody += `</div>`;
                    });

                    document.getElementById('monthlyCalendarBody').innerHTML = calendarBody;

                    document.querySelectorAll('.shift-tag-month').forEach(tag => {
                        tag.addEventListener('click', function() {
                            const shiftId = this.getAttribute('data-shift-id');
                            editShift(shiftId);
                        });
                    });
                }

                function getMonthViewAbbreviation(type) {
                    switch (type) {
                        case 0:
                            return 'GS';
                        case 1:
                            return 'MS';
                        case 2:
                            return 'NS';
                        case 3:
                            return 'DO';
                            case 4:
    return 'HO';
                        default:
                            return type.substring(0, 2).toUpperCase();
                    }
                }

               function getShiftFullName(type, shift = null) {
                    switch (type) {
                        case 0:
                            return 'General Swift (9:00 - 18:00)';
                        case 1:
                            return 'Morning Swift (7:00 - 17:00)';
                        case 2:
                            return 'Night Swift (22:00 - 6:00)';
                        case 3:
                            return 'Day Off';
                            case 4:
            return shift?.occasion ?? 'Holiday'; // ✅ FIX
                        default:
                            return type;
                    }
                }

                function getShiftAbbreviation(type, shift = null) {
    switch (type) {
        case 0:
            return 'General Swift (9:00 - 18:00)';
        case 1:
            return 'Morning Swift (7:00 - 17:00)';
        case 2:
            return 'Night Swift (22:00 - 6:00)';
        case 3:
            return 'Day Off';
        case 4:
            return shift?.occasion ?? 'Holiday'; // 🔥 IMPORTANT
        default:
            return '';
    }
}

                window.openShiftModalForCell = function(cell) {
                    resetShiftForm();

                    const date = cell.closest('[data-date]').getAttribute('data-date');
                    document.getElementById('shiftDate').value = date;

                    const employeeId = cell.closest('[data-employee-id]')?.getAttribute('data-employee-id');
                    if (employeeId) {
                        document.getElementById('shiftEmployee').value = employeeId;
                    }
                    shiftModal.show();
                };

                function openBulkShiftModal() {
                    document.getElementById('bulkShiftForm').reset();
                    singleDatePicker.setDate(new Date());
                    monthYearPicker.clear();

                    document.getElementById('singleDateField').classList.remove('d-none');
                    document.getElementById('dateRangeFields').classList.add('d-none');
                    document.getElementById('monthField').classList.add('d-none');

                    document.getElementById('date').checked = true;
                    document.getElementById('multiple').checked = false;
                    document.getElementById('month').checked = false;

                    // Reset department and employee selections
                    document.querySelectorAll('#bulkDepartmentFilter option').forEach(option => {
                        option.selected = false;
                    });
                    document.querySelectorAll('#bulkEmployeeFilter option').forEach(option => {
                        option.selected = false;
                    });
                    updateBulkDepartmentFilterText();
                    updateBulkEmployeeFilterText();

                    bulkShiftModal.show();
                }
// Function to display holiday information in the modal
function displayHolidayInfo(shift) {
    const holidayInfoContainer = document.getElementById('holidayInfoContainer');
    const holidayOccasion = document.getElementById('holidayOccasion');
    const holidayType = document.getElementById('holidayType');

    if (parseInt(shift.shift_type) === 4) {
        holidayInfoContainer.classList.remove('d-none');

        holidayOccasion.innerHTML = `<strong>Occasion:</strong> ${shift.occasion || '-'}`;
        holidayType.innerHTML = `<strong>Holiday Type:</strong> ${shift.holiday_type || '-'}`;
    } else {
        holidayInfoContainer.classList.add('d-none');
    }
}

// Update the editShift function
function editShift(shiftId) {
    const shift = shifts.find(s => s.shift_id == shiftId);
    if (!shift) return;

    resetShiftForm();

    const shiftTypeMap = {
        0: 'gs',
        1: 'ms',
        2: 'ns',
        3: 'dayoff',
        4: 'holiday'
    };

    const selectedType = shiftTypeMap[shift.shift_type] || 'gs';

    document.getElementById('shiftId').value = shift.shift_id;
    document.getElementById('shiftEmployee').value = shift.employee_id;
    document.getElementById('shiftDate').value = shift.date_no;
    document.getElementById('shiftType').value = selectedType;
    document.getElementById('shiftNotes').value = shift.notes || '';

    document.getElementById('deleteShift').style.display = 'block';

    // Display holiday info if it's a holiday shift
    displayHolidayInfo(shift);

    // Controls dropdown options
    updateShiftTypeDropdown(selectedType);

    // Controls swap UI
    document.getElementById('shiftType').dispatchEvent(new Event('change'));

    shiftModal.show();
}

// Update the shiftType change event listener
document.getElementById('shiftType').addEventListener('change', function () {
    const type = this.value;
    const swap = document.getElementById('swapDateContainer');
    const holidayInfo = document.getElementById('holidayInfoContainer');

    swap.classList.add('d-none');

    if (type === 'holiday') {
        swap.classList.remove('d-none');

    } else {
        holidayInfo.classList.add('d-none');
    }

    if (type === 'dayoff') {
        swap.classList.remove('d-none');
    }
});

// Update modal hidden event listener
document.getElementById('shiftModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('swapDate').value = '';
    document.getElementById('swapDateContainer').classList.add('d-none');
    document.getElementById('holidayInfoContainer').classList.add('d-none');
    document.getElementById('holidayOccasion').innerHTML = '-';
    document.getElementById('holidayType').innerHTML = '-';
});

// Update resetShiftForm function
function resetShiftForm() {
    document.getElementById('shiftForm').reset();
    document.getElementById('shiftId').value = '';
    document.getElementById('deleteShift').style.display = 'none';
    document.getElementById('holidayInfoContainer').classList.add('d-none');
}



                function checkIfSundayAndUpdateUIBulk(dateStr) {
                    // Only apply restrictions for single date selection
                    const assignShiftType = document.querySelector('input[name="assign_shift"]:checked').value;

                    if (assignShiftType !== 'date') {
                        // For multiple and month selection, don't restrict the shift type
                        const shiftTypeSelect = document.getElementById('bulkShiftType');
                        Array.from(shiftTypeSelect.options).forEach(option => {
                            option.disabled = false;
                            option.style.display = '';
                        });
                        shiftTypeSelect.disabled = false;
                        return;
                    }

                    const [day, month, year] = dateStr.split('-');
                    const date = new Date(year, month - 1, day);
                    const isSunday = date.getDay() === 0;

                    const shiftTypeSelect = document.getElementById('bulkShiftType');

                    if (isSunday) {
                        // Disable all options except dayoff for single date selection
                        Array.from(shiftTypeSelect.options).forEach(option => {
                            if (option.value !== 'dayoff') {
                                option.disabled = true;
                                option.style.display = 'none';
                            } else {
                                option.disabled = false;
                                option.style.display = '';
                            }
                        });

                        // Set to dayoff
                        shiftTypeSelect.value = 'dayoff';
                        shiftTypeSelect.disabled = false;

                        Swal.fire({
                            icon: 'info',
                            title: 'Sunday Selected',
                            text: 'Sundays are automatically set as Day Off. The shift type has been set to Day Off.',
                            confirmButtonText: 'OK'
                        });
                    } else {
                        // Enable all options
                        Array.from(shiftTypeSelect.options).forEach(option => {
                            option.disabled = false;
                            option.style.display = '';
                        });
                        shiftTypeSelect.disabled = false;
                    }
                }

                // Add event listener for assign_shift radio buttons change
                document.querySelectorAll('input[name="assign_shift"]').forEach(radio => {
                    radio.addEventListener('change', function() {
                        // Reset shift type options when assignment type changes
                        const shiftTypeSelect = document.getElementById('bulkShiftType');
                        Array.from(shiftTypeSelect.options).forEach(option => {
                            option.disabled = false;
                            option.style.display = '';
                        });
                        shiftTypeSelect.disabled = false;

                        // If switching to single date, check if current date is Sunday
                        if (this.value === 'date') {
                            const currentDate = singleDatePicker.selectedDates[0];
                            if (currentDate) {
                                const formattedDate = singleDatePicker.formatDate(currentDate, "d-m-Y");
                                checkIfSundayAndUpdateUIBulk(formattedDate);
                            }
                        }

                        document.getElementById('singleDateField').classList.add('d-none');
                        document.getElementById('dateRangeFields').classList.add('d-none');
                        document.getElementById('monthField').classList.add('d-none');

                        if (this.value === 'date') {
                            document.getElementById('singleDateField').classList.remove('d-none');
                        } else if (this.value === 'multiple') {
                            document.getElementById('dateRangeFields').classList.remove('d-none');
                        } else if (this.value === 'month') {
                            document.getElementById('monthField').classList.remove('d-none');
                        }
                    });
                });




                // Update the prepareBulkShifts function
                function prepareBulkShifts() {
                    const assignShiftType = document.querySelector('input[name="assign_shift"]:checked').value;

                    // For single date assignment, check if it's Sunday
                    if (assignShiftType === 'date') {
                        const dateStr = document.getElementById('singleDate').value;
                        const [day, month, year] = dateStr.split('-');
                        const date = new Date(year, month - 1, day);
                        const isSunday = date.getDay() === 0;

                        // For single date, create an array with the date if it's Sunday, otherwise empty array
                        const sundays = isSunday ? [formatDate(date)] : [];
                        document.getElementById('sundaysInRange').value = JSON.stringify(sundays);
                    }
                    // For multiple and month assignments, identify Sundays
                    else if (assignShiftType === 'multiple' || assignShiftType === 'month') {
                        let dates = [];

                        if (assignShiftType === 'multiple') {
                            const fromDate = new Date(document.getElementById('date_range_from').value);
                            const toDate = new Date(document.getElementById('date_range_to').value);

                            // Generate all dates in the range
                            let currentDate = new Date(fromDate);
                            while (currentDate <= toDate) {
                                dates.push(new Date(currentDate));
                                currentDate.setDate(currentDate.getDate() + 1);
                            }
                        } else if (assignShiftType === 'month') {
                            const monthYear = document.getElementById('monthYearPicker').value;
                            const [monthName, year] = monthYear.split(' ');
                            const monthIndex = new Date(Date.parse(monthName + " 1, " + year)).getMonth();
                            const daysInMonth = new Date(year, monthIndex + 1, 0).getDate();

                            for (let day = 1; day <= daysInMonth; day++) {
                                dates.push(new Date(year, monthIndex, day));
                            }
                        }

                        // Identify Sundays
                        const sundays = dates.filter(date => date.getDay() === 0)
                            .map(date => formatDate(date));

                        // Store this information to send to server
                        document.getElementById('sundaysInRange').value = JSON.stringify(sundays);
                    } else {
                        // For other cases, send empty array
                        document.getElementById('sundaysInRange').value = JSON.stringify([]);
                    }

                    // Continue with the original save logic
                    saveBulkShifts();
                }

                function saveBulkShifts() {
                    const formData = new FormData(document.getElementById('bulkShiftForm'));
                    const employeeIds = document.getElementById('bulkEmployeeFilter').selectedOptions;

                    // Check if any employees are selected
                    if (employeeIds.length === 0) {
                        // Get the employee dropdown button
                        const employeeDropdown = document.getElementById('bulkEmployeeDropdown');
                        const dropdownContainer = employeeDropdown.closest('.dropdown');

                        // Add error class to container
                        dropdownContainer.classList.add('error-validation');

                        // Create or update error message
                        let errorElement = dropdownContainer.querySelector('.error-message');
                        if (!errorElement) {
                            errorElement = document.createElement('div');
                            errorElement.className = 'error-message';
                            errorElement.textContent = 'Please select at least one employee';
                            dropdownContainer.appendChild(errorElement);
                        }

                        // Remove error styling after 2 seconds with fade out
                        setTimeout(() => {
                            dropdownContainer.classList.add('fade-out');

                            // Remove elements after fade completes
                            setTimeout(() => {
                                dropdownContainer.classList.remove('error-validation', 'fade-out');
                                if (errorElement.parentNode) {
                                    errorElement.remove();
                                }
                            }, 300); // Match this with the transition duration
                        }, 2000);

                        return;
                    }

                    fetch('shifts/bulk-create', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                                'Accept': 'application/json',
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                fetchShifts();
                                bulkShiftModal.hide();
                                showToast(data.message, 'success');
                            } else {
                                showToast(data.message, 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showToast('An error occurred while saving bulk shifts', 'error');
                        });
                }
function saveShift() {

    let swapDate = document.getElementById('swapDate')?.value;

    if (swapDate === '' || swapDate === undefined) {
        swapDate = null;
    }

    const currentDate = document.getElementById('shiftDate').value;
    const employeeId = document.getElementById('shiftEmployee').value;
    const shiftType = document.getElementById('shiftType').value;

    if (swapDate && swapDate === currentDate) {
        showToast('Cannot swap same date', 'error');
        return;
    }

    if (swapDate) {

        const currentShift = shifts.find(s =>
            s.employee_id == employeeId &&
            s.date_no === currentDate
        );

        const swapShift = shifts.find(s =>
            s.employee_id == employeeId &&
            s.date_no === swapDate
        );

        const currentAttendance = attendanceData.find(a =>
            a.employee_id == employeeId &&
            a.attendancedate_no === currentDate
        );

        const swapAttendance = attendanceData.find(a =>
            a.employee_id == employeeId &&
            a.attendancedate_no === swapDate
        );

        const isAbsent =
            (currentAttendance && currentAttendance.attendance_type == 0) ||
            (swapAttendance && swapAttendance.attendance_type == 0);

        const isLeave =
            (leaveData?.[employeeId]?.[currentDate]) ||
            (leaveData?.[employeeId]?.[swapDate]);

        if (isAbsent || isLeave) {
            showToast('Leave / Absent cannot be swapped', 'error');
            return;
        }

        fetch('shifts/create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                employee_id: employeeId,
                date_no: currentDate,
                swap_date: swapDate,
                shift_type: shiftType
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById('swapDate').value = '';
                fetchShifts();
                shiftModal.hide();
                showToast('Shift swapped successfully', 'success');
            } else {
                showToast(data.message || 'Swap failed', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            showToast('Swap error', 'error');
        });

        return;
    }

    const shiftId = document.getElementById('shiftId').value;
    const formData = new FormData(document.getElementById('shiftForm'));

    const dateInput = document.getElementById('shiftDate').value;
    if (dateInput) {
        const dateParts = dateInput.split('-');
        if (dateParts.length === 3) {
            if (dateParts[0].length === 2 && dateParts[2].length === 4) {
                const formattedDate = `${dateParts[2]}-${dateParts[1]}-${dateParts[0]}`;
                formData.set('date_no', formattedDate);
            }
        }
    }

    const url = shiftId ? 'shifts/update' : 'shifts/create';
    const method = shiftId ? 'PUT' : 'POST';

    if (method === 'PUT') {
        formData.append('_method', 'PUT');
    }

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw err; });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            fetchShifts();
            shiftModal.hide();
            showToast(data.message, 'success');
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        let errorMessage = 'An error occurred while saving the shift';
        if (error.errors) {
            errorMessage = Object.values(error.errors).join('\n');
        } else if (error.message) {
            errorMessage = error.message;
        }
        showToast(errorMessage, 'error');
    });
}
                function confirmDeleteShift() {
                    const shiftId = document.getElementById('shiftId').value;

                    Swal.fire({
                        title: 'Delete Shift?',
                        text: "Are you sure you want to delete this shift?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            deleteShift(shiftId);
                        }
                    });
                }

                function deleteShift(shiftId) {
                    fetch('shifts/delete/' + shiftId, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                                'Accept': 'application/json',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                fetchShifts();
                                shiftModal.hide();
                                showToast(data.message, 'success');
                            } else {
                                showToast(data.message, 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showToast('An error occurred while deleting the shift', 'error');
                        });
                }

                function updateBulkEmployeeFilterText() {
                    const selectedOptions = Array.from(document.querySelectorAll('#bulkEmployeeFilter option:checked'));
                    const filterText = document.getElementById('bulkEmployeeFilterText');

                    if (selectedOptions.length === 0) {
                        filterText.textContent = 'All Employees';
                    } else if (selectedOptions.length === 1) {
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = selectedOptions[0].innerHTML;
                        const nameElement = tempDiv.querySelector('p');
                        filterText.textContent = nameElement ? nameElement.textContent : selectedOptions[0].text;
                    } else {
                        filterText.textContent = `${selectedOptions.length} employees selected`;
                    }
                }

                function updateBulkDepartmentFilterText() {
                    const selectedOptions = Array.from(document.querySelectorAll(
                        '#bulkDepartmentFilter option:checked'));
                    const filterText = document.getElementById('bulkDepartmentFilterText');

                    if (selectedOptions.length === 0 || (selectedOptions.length === 1 && selectedOptions[0].value ===
                            'all')) {
                        filterText.textContent = 'All Departments';
                    } else if (selectedOptions.length === 1) {
                        filterText.textContent = selectedOptions[0].textContent;
                    } else {
                        filterText.textContent = `${selectedOptions.length} departments selected`;
                    }
                }

                function getStartOfWeek(date) {
                    const day = date.getDay();
                    const diff = date.getDate() - day + (day === 0 ? -6 : 1);
                    return new Date(date.setDate(diff));
                }

                function getEndOfWeek(date) {
                    const startOfWeek = getStartOfWeek(date);
                    const endOfWeek = new Date(startOfWeek);
                    endOfWeek.setDate(startOfWeek.getDate() + 6);
                    return endOfWeek;
                }

                function formatDate(date) {
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day = String(date.getDate()).padStart(2, '0');
                    return `${year}-${month}-${day}`;
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
    </div>

    <!-- Include Flatpickr CSS and JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <!-- Include the month select plugin -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.9/dist/plugins/monthSelect/index.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.9/dist/plugins/monthSelect/style.css">

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
        .date-range-picker-container {
            width: 500px;

            /* border: 1px solid #e0e0e0; */
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            position: absolute;
        }


        .date-range-header {
            padding: 16px;
            border-bottom: 1px solid #e0e0e0;
            text-align: center;
            font-weight: 500;
            font-size: 16px;
            color: #333;
        }


        .date-range-calendar {
            display: flex;
            padding: 16px;
            background-color: #fff;
        }


        .month-container {
            width: 50%;
            padding: 0 8px;
        }

        .month-header {
            text-align: center;
            font-weight: 500;
            margin-bottom: 12px;
            color: #333;
        }

        .month-nav-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: #495057;
            font-size: 0.9rem;
            padding: 2px 5px;
            border-radius: 4px;
        }

        .month-nav-btn:hover {
            background-color: #f1f3f5;
        }



        .weekdays {
            display: flex;
            margin-bottom: 8px;
        }

        .weekdays span {
            width: 14.28%;
            text-align: center;
            font-size: 12px;
            color: #666;
            font-weight: normal;
        }

        .days-grid {
            display: flex;
            flex-wrap: wrap;
        }


        .day {
            width: 14.28%;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            cursor: pointer;
            border-radius: 4px;
            margin: 2px 0;
        }

        .day:hover {
            background-color: #f5f5f5;
        }

        .day.selected {
            color: #fff;
            background-color: #4285f4 !important;
        }

        .day.in-range {
            background-color: #e3f2fd;
        }

        .day.disabled {
            color: #ccc;
            cursor: not-allowed;
        }

        .day.today {
            font-weight: bold;

            color: #4285f4;
        }
        /* Holiday info styling */
#holidayInfoContainer .alert-info {
    background-color: #e8f4fd;
    border-left: 4px solid #0d6efd;
    padding: 12px 15px;
}

#holidayInfoContainer i.bi-calendar-event {
    color: #0d6efd;
}

#holidayInfoContainer #holidayOccasion {
    color: #0c5460;
    font-weight: 500;
}

#holidayInfoContainer #holidayType {
    color: #6c757d;
}

        .date-range-footer {
            display: flex;
            justify-content: flex-end;
            padding: 12px 16px;
            border-top: 1px solid #e0e0e0;
            gap: 8px;
        }

        .bulkshift {
            max-width: 85em;

        }

        /* Bulk Shift Modal */
        .bulk-shift-day {
            display: inline-block;
            width: 30px;
            height: 30px;
            line-height: 30px;
            text-align: center;
            margin: 2px;
            border-radius: 50%;
            cursor: pointer;
            border: 1px solid #dee2e6;
        }

        .bulk-shift-day.selected {
            background-color: #4dabf7;
            color: white;
            border-color: #4dabf7;
        }

        /* Year Navigation */
        .year-navigation {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        /* Main Container */
        .shift-planner-container {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
        }

        /* Header Styles */
        .planner-header {
            padding: 1.5rem;
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: center;
        }

        .planner-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
            margin-right: auto;
        }

        .planner-controls {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: center;
        }

        .control-group {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .control-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #495057;
        }

        .control-select:focus {
            border-color: #4dabf7;
            box-shadow: 0 0 0 3px rgba(77, 171, 247, 0.2);
            outline: none;
        }

        /* Date Navigation */
        .date-navigation {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background-color: #fff;
            border-radius: 8px;
            padding: 1rem;
        }

        .nav-button {
            background: none;
            border: 1px solid #ebebeb;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            color: #495057;
            cursor: pointer;
            transition: all 0.2s;
        }

        .nav-button:hover {
            background-color: #f1f3f5;
            color: #2c3e50;
        }

        .date-display {
            font-weight: 500;
            font-size: 0.9375rem;
            min-width: 160px;
            text-align: center;
        }

        /* Buttons */
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

        /* Weekly View */
        .calendar-view {
            background-color: #fff;
        }

        .week-header {
            display: grid;
            grid-template-columns: 200px repeat(7, 1fr);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .employee-header,
        .day-header {
            padding: 12px;
            text-align: center;
            font-weight: 500;
        }

        .employee-header {
            font-weight: 600;
            text-align: left;
            padding-left: 20px;
        }

        .day-header {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .day-header .fs-4 {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .week-body {
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            max-height: calc(100vh - 220px);
        }

        .employee-row {
            display: grid;
            grid-template-columns: 200px repeat(7, 1fr);
            border-bottom: 1px solid #e9ecef;
            transition: background-color 0.2s;
        }

        .employee-row:hover {
            background-color: #f8f9fa;
        }

        .employee-info {
            padding: 12px 20px;
            border-right: 1px solid #e9ecef;
            font-weight: 500;
            display: flex;
            align-items: center;
            position: sticky;
            left: 0;
            z-index: 2;
            background-color: #fff;
        }

        .day-cell {
            padding: 8px;
            min-height: 80px;
            border-right: 1px solid #e9ecef;
            position: relative;
            background-color: #fff;
        }

        /* Shift Tags */
        .shift-tag,
        .shift-tag-month {
            display: inline-block;
            padding: 6px 8px;
            border-radius: 6px;
            font-size: 0.8125rem;
            font-weight: 500;
            margin: 20px 0;
            cursor: pointer;
            width: 100%;
            box-sizing: border-box;
            text-align: center;
            transition: transform 0.1s, box-shadow 0.2s;
        }

        .shift-tag:hover,
        .shift-tag-month:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .shift-notes {
            font-size: 0.75rem;
            margin-top: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Shift Colors */
        /* Change your CSS to: */
        .shift-tag[data-shift-type="0"],
        .shift-tag-month[data-shift-type="0"] {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .shift-tag[data-shift-type="1"],
        .shift-tag-month[data-shift-type="1"] {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .shift-tag[data-shift-type="2"],
        .shift-tag-month[data-shift-type="2"] {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        .shift-tag[data-shift-type="3"],
        .shift-tag-month[data-shift-type="3"] {
            background-color: #e2e3e5;
            color: #383d41;
            border: 1px solid #d6d8db;
        }

        .add-shift {
            font-size: 1rem;
            color: #adb5bd;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            transition: color 0.2s;
        }

        .add-shift:hover {
            color: #4dabf7;
        }

        /* Monthly View */
        .month-header {
            padding: 12px 20px;
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 10;
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }

        .year-title {
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .month-navigation {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .months-container {
            display: flex;
            gap: 15px;
            overflow-x: auto;
            padding: 5px 0;
            scrollbar-width: none;
        }

        .months-container::-webkit-scrollbar {
            display: none;
        }

        /* Error validation styling */
        .dropdown.error-validation .dropdown-toggle {
            border-color: #dc3545 !important;

        }

        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            opacity: 1;
            transition: opacity 0.3s ease;
        }

        .fade-out .error-message {
            opacity: 0;
        }

        .fade-out .dropdown-toggle {
            border-color: #ced4da !important;
            box-shadow: none !important;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .month-tab {
            padding: 8px 15px;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.2s;
            border: 1px solid transparent;
        }

        .month-tab:hover {
            background-color: #e9ecef;
        }

        .month-tab.active {
            background-color: #4dabf7;
            color: white;
            border-color: #339af0;
        }

        .month-scroll-container {
            display: flex;
            overflow-x: auto;
            background-color: #fff;
        }

        .month-employee-column {
            width: 300px;
            min-width: 300px;
            position: sticky;
            left: 0;
            z-index: 5;
            background-color: #fff;
            border-right: 1px solid #e9ecef;
        }

        .month-employee-column .employee-header {
            padding: 12px 20px;
            text-align: left;
            font-weight: 600;
            border-bottom: 1px solid #e9ecef;
            background-color: #f8f9fa;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .employee-names {
            display: flex;
            flex-direction: column;
        }

        .employee-name {
            padding: 12px 20px;
            border-bottom: 1px solid #e9ecef;
            height: 84px;
            display: flex;
            align-items: center;
            font-weight: 500;
        }

        .month-days-scroll {
            flex: 1;
            min-width: 0;
            overflow-x: auto;
        }

        .month-days-header {
            display: flex;
            position: sticky;
            top: 0;
            z-index: 5;
            background-color: #f8f9fa;
        }

        .month-days-header .day-header {
            min-width: 40px;
            width: 40px;
            padding: 8px 5px;
            text-align: center;
            border-right: 1px solid #e9ecef;
            font-weight: 500;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .day-name {
            font-size: 0.75rem;
            color: #6c757d;
        }

        .day-number {
            font-size: 0.875rem;
            font-weight: 500;
        }

        .month-grid {
            display: flex;
            flex-direction: column;
            background-color: #fff;
        }

        .month-grid .employee-row {
            display: flex;
            border-bottom: 1px solid #e9ecef;
            min-height: 60px;
        }

        .month-grid .day-cell {
            min-width: 40px;
            width: 40px;
            padding: 3px;
            border-right: 1px solid #e9ecef;
            background-color: #fcfcfc;
            position: relative;
        }

        /* Modal */
        .modal-content {
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            border-bottom: 1px solid #e9ecef;
            padding: 1.25rem 1.5rem;
        }

        .modal-title {
            font-weight: 600;
            color: #2c3e50;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            border-top: 1px solid #e9ecef;
            padding: 1rem 1.5rem;
        }

        /* Pagination */
        .pagination-controls {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            padding: 1rem;
            border-top: 1px solid #e9ecef;
            background-color: #f8f9fa;
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

        /* Avatar */
        .avatar-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        /* Dropdown Styling */
        .dropdown-toggle.control-select {
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

        .dropdown-menu select {
            outline: none;
            box-shadow: none;
        }

        .dropdown-menu select:focus {
            border-color: transparent;
        }

        .dropdown-menu li:first-child {
            padding: 0.5rem;
            border-bottom: 1px solid #f1f3f5;
        }

        /* .employee-dropdown,
        .department-dropdown {
            transform: translate3d(0px, 38px, 0px) !important;
        } */

        /* Flatpickr customization */
        .flatpickr-input {
            background-color: #fff;
            border: 1px solid #ced4da;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            width: 100%;
        }

        /* Fix for Flatpickr date range picker */
        .flatpickr-calendar {
            width: auto !important;
            max-width: none !important;
        }

        .flatpickr-calendar.inline {
            margin-top: 10px;
        }

        .flatpickr-calendar .flatpickr-month {
            height: 34px;
        }

        .flatpickr-calendar .flatpickr-weekdays {
            height: 28px;
        }

        .flatpickr-calendar .flatpickr-days {
            padding: 5px;
        }

        .flatpickr-calendar .dayContainer {
            min-width: 0;
            max-width: none;
        }

        /* Ensure two months display side by side */
        .flatpickr-calendar.multiMonth .flatpickr-days .dayContainer+.dayContainer {
            border-left: 1px solid #e6e6e6;
            margin-left: -1px;
        }

        .flatpickr-month {
            height: 5px;
            max-width: 300px;
            border-radius: 8px 8px 0 0;
        }

        .flatpickr-weekdays {
            height: 40px;
        }

        .flatpickr-day {
            border-radius: 6px;
        }

        .flatpickr-day.selected {
            background: #4dabf7;
            border-color: #4dabf7;
        }

        .flatpickr-wrapper {
            position: relative;
            display: block !important;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .planner-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .planner-controls {
                width: 100%;
            }
        }

        @media (max-width: 992px) {
            .week-header {
                grid-template-columns: 160px repeat(7, 1fr);
            }

            .employee-info {
                padding: 12px 15px;
            }

            .month-employee-column {
                width: 160px;
                min-width: 160px;
            }
        }

        @media (max-width: 768px) {
            .planner-controls {
                flex-direction: column;
                align-items: stretch;
                gap: 0.75rem;
            }

            .control-group {
                width: 100%;
            }

            .control-select {
                width: 100%;
            }

            .date-navigation {
                justify-content: center;
                width: 100%;
            }

            .week-header {
                grid-template-columns: 120px repeat(7, 1fr);
            }

            .employee-info {
                padding: 10px 12px;
                font-size: 0.875rem;
            }

            .month-employee-column {
                width: 120px;
                min-width: 120px;
            }

            .employee-name {
                padding: 10px 12px;
                font-size: 0.875rem;
            }

            .month-days-header .day-header,
            .month-grid .day-cell {
                min-width: 50px;
                width: 50px;
            }
        }

        @media (max-width: 576px) {
            .planner-title {
                font-size: 1.5rem;
            }

            .week-header {
                grid-template-columns: 100px repeat(7, 1fr);
            }

            .day-header {
                font-size: 0.75rem;
                padding: 8px 4px;
            }

            .day-header .fs-4 {
                font-size: 1rem;
            }

            .employee-info {
                padding: 8px 10px;
                font-size: 0.8125rem;
            }

            .shift-tag,
            .shift-tag-month {
                font-size: 0.75rem;
                padding: 4px 6px;
            }

            .month-employee-column {
                width: 100px;
                min-width: 100px;
            }

            .employee-name {
                padding: 8px 10px;
                font-size: 0.8125rem;
            }

            .month-days-header .day-header,
            .month-grid .day-cell {
                min-width: 40px;
                width: 40px;
                padding: 4px;
            }
        }

        /* statrt */
        .shift-status-container {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            padding: 10px 20px;

        }

        .status-badge {
            display: flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.80rem;
            font-weight: 500;
        }

        .status-expired {
            background-color: #f8d7da;
            color: #721c24;
        }
.shift-tag.holiday {
    background-color: #ffe5e5; /* light red */
    color: #b30000;
    border: 1px solid #ffb3b3;
}

.shift-tag-month.holiday {
    background-color: #ffe5e5;
    color: #b30000;
    border: 1px solid #ffb3b3;
}
        .status-ongoing {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .status-upcoming {
            background-color: #d4edda;
            color: #155724;
        }
    </style>
</x-layout>
