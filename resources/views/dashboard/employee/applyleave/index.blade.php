<x-layout>
    @section('title', 'Apply Leave')

    <div class="container-fluid p-3">
        <x-message />

        <div class="leave-planner-container">
            <!-- Header Section -->
            <div class="" style="background-color: #f8fafc;">
                <h4 class="p-2 fw-medium fs-5">
                    <i class="bi bi-calendar-x me-2"></i>My Leaves
                </h4>
            </div>

         <!-- Employee Info Section -->
<div class="row p-3 bg-light rounded m-3">

    <!-- Profile -->
    <div class="d-flex align-items-center mb-3">
        <div class="col-auto">
            <img src="{{ auth()->user()->employee->image ? '/employee_images/' . auth()->user()->employee->image : '/images/admin_default.jpg' }}"
                class="rounded-circle"
                style="width: 60px; height: 60px; object-fit: cover;">
        </div>

        <div class="col ms-3">
            <h5 class="mb-1 fw-bold">{{ auth()->user()->name }}</h5>
            <p class="mb-0 text-muted">
                {{ auth()->user()->employee->departmentid->dep_name ?? 'No Department' }}
            </p>
        </div>
    </div>

    <hr>

    <!-- Leave Balance -->
    <div>
        <h6 class="fw-bold mb-2">Leave Balance</h6>



      @if(isset($leaveTypes) && count($leaveTypes) > 0)
@php
        $totalLeave = collect($leaveTypes)->sum('total');
        $totalRemaining = collect($leaveTypes)->sum('remaining');
    @endphp

            <div class="p-2 mb-2 border rounded bg-white">
          <div class="d-flex justify-content-between align-items-center mb-2 px-1">

    <span class="fw-semibold text-dark">
        Yearly Leave Count
    </span>

    <small class="fw-semibold text-dark">
        {{ $totalRemaining }} / {{ $totalLeave }}
    </small>

</div>

<!-- Optional thin progress -->
<div class="progress mb-3" style="height:4px; background:#e9ecef;">
    <div class="progress-bar bg-primary"
        style="width: {{ $totalLeave > 0 ? ($totalRemaining / $totalLeave) * 100 : 0 }}%">
    </div>
</div></div>

  <div class="row g-2">

    @foreach($leaveTypes as $leave)
        <div class="col-md-4 col-sm-6">

            <div class="p-2 border rounded bg-white h-100">

                <!-- Title + Count -->
                <div class="d-flex justify-content-between align-items-center small mb-1">
                    <span class="fw-semibold text-dark">{{ $leave['name'] }}</span>

                    <span class="
                        {{ $leave['remaining'] == 0 ? 'text-danger' : ($leave['remaining'] < ($leave['total']/2) ? 'text-warning' : 'text-success') }}">
                        {{ $leave['remaining'] }}/{{ $leave['total'] }}
                    </span>
                </div>

                <!-- Progress -->
                <div class="progress" style="height:4px; background:#f1f1f1;">
                    <div class="progress-bar
                        {{ $leave['remaining'] == 0 ? 'bg-danger' : ($leave['remaining'] < ($leave['total']/2) ? 'bg-warning' : 'bg-success') }}"
                        style="width: {{ $leave['total'] > 0 ? ($leave['remaining'] / $leave['total']) * 100 : 0 }}%">
                    </div>
                </div>

            </div>

        </div>
    @endforeach

</div>

@else

    <!-- ❌ NO LEAVE TYPES -->
    <div class="text-center py-4 text-muted">
        <i class="fas fa-exclamation-circle fa-2x mb-2"></i>
        <p class="mb-0 fw-semibold">No leave types assigned</p>
        <small>Please contact admin</small>
    </div>

@endif

    </div>

</div>

            <!-- Filters and Add Button -->
            <div class="row justify-content-between align-items-center px-3 pb-3">
                <div class="col-md-6">
                    <div class="row ">
                        <!-- Date Range Filter -->
                        <div class="col-auto">
                            <span class="form-label">Show Entries</span>
                            <select id="entriesPerPage" class="form-select form-control">
                                <option value="5" selected>5</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                        </div>
                        <div class="col-auto mt-4">
                            <div class="dropdown">
                                <button class="dropdown-toggle control-select" type="button" id="dateRangeDropdown"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <span id="dateRangeText">All Dates</span>
                                </button>
                                <ul class="dropdown-menu date-range-dropdown p-2" aria-labelledby="dateRangeDropdown"
                                    style="width: 300px;">
                                    <li>
                                        <input type="text" id="dateRangePicker" class="form-control form-control-sm"
                                            placeholder="Select date range">
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <div class="col-auto mt-4">
                            <div class="dropdown">
                                <button class="dropdown-toggle control-select" type="button" id="statusDropdown"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <span id="statusFilterText">All Status</span>
                                </button>
                                <ul class="dropdown-menu status-dropdown p-2" aria-labelledby="statusDropdown"
                                    style="width: 150px;">
                                    <li>
                                        <select id="statusFilter" class="form-select form-select-sm" size="5"
                                            style="width: 100%; border: none;">
                                            <option value="">All Status</option>
                                            <option value="2">Pending</option>
                                            <option value="1">Approved</option>
                                            <option value="3">Rejected</option>
                                        </select>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Clear Filters Button -->
                        <div class="col-auto mt-4" id="clearFiltersBtnContainer" style="display: none;">
                            <button class="btn btn-outline-secondary" id="clearFiltersBtn">
                                Clear Filters
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 text-end">
                    <div class="row justify-content-end g-2">

                        <div class="col-auto" style="margin-top: 1.3em;">
                            <button class="action-button primary" id="addLeave">
                                <i class="bi bi-plus-circle me-1"></i> Apply Leave
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employee Leaves Table -->
            <div class="table-responsive p-3">
                <table class="table table-bordered table-hover">
                    <thead class="text-center">
                        <tr>
                            <th>Leave Type</th>
                            <th>Type</th>
                            <th>Duration</th>
                            <th>Applied Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="employeeLeaveTableBody">
                        <!-- Employee leave data will be populated here -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination Controls -->
            <div class="row justify-content-between align-items-center p-3">
                <div class="col-auto">
                    <span id="leaveShowingInfo">Showing 1 to 5 of 0 entries</span>
                </div>
                <div class="col-auto">
                    <div class="pagination-controls d-flex align-items-center">
                        <button class="pagination-button" id="prevLeavePage" disabled>
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <span class="page-info mx-2" id="leavePageInfo">Page 1 of 1</span>
                        <button class="pagination-button" id="nextLeavePage" disabled>
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Date View Modal for Multiple Days -->
        <div class="modal fade" id="dateViewModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title fw-bold text-dark">
                            <i class="bi bi-calendar-range me-2"></i>Duration Date View
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card bg-light border-0">
                                    <div class="card-body py-3">
                                        <h6 class="card-title fw-semibold mb-0">
                                            Leave Applied Data:
                                            <span id="totalDaysCount" class="">0</span> Days
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="fw-semibold mb-3 text-dark d-flex align-items-center">
                                    <i class="bi bi-calendar-plus me-2 text-success"></i>Leave Applied Dates
                                </h6>
                                <div class="card">
                                    <div class="card-body p-3">
                                        <div id="appliedDatesList"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <h6 class="fw-semibold mb-3 text-dark d-flex align-items-center">
                                    <i class="bi bi-calendar-x me-2 text-warning"></i>Holidays & Weekends
                                </h6>
                                <div class="card">
                                    <div class="card-body p-3">
                                        <div id="holidaysList"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Add Leave Modal -->
        <div class="modal fade" id="leaveModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg leavelen">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="leaveModalTitle">Apply Leave</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="leaveForm" class="p-2" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="leaveId" name="leave_id">
                            <input type="hidden" id="availableDates" name="available_dates">

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Employee Name</label>
                                    <input type="text" class="form-control" value="{{ auth()->user()->name }}"
                                        readonly style="background-color: #e9e7e7;">
                                    <input type="hidden" name="employee_id"
                                        value="{{ auth()->user()->employee->emp_id ?? '' }}">
                                </div>

                                <div class="col-md-6">
                                    <label for="leaveType" class="form-label">Leave Type</label>
                                    <select id="leaveType" class="form-select" name="leave_type_id" required>
                                        <option value="">Select Leave Type</option>
                                        <!-- Leave types will be populated dynamically -->
                                    </select>

                                    <small id="leaveTypeInfo" class="text-danger ms-2 mt-3"
                                        style="display:none; color:#6c757d; font-size: 14px"></small>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Select Duration </label>
                                    <div class="d-flex flex-wrap gap-3 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="duration"
                                                id="fullDay" value="1" checked>
                                            <label class="form-check-label" for="fullDay">Full Day</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="duration"
                                                id="multipleDays" value="2">
                                            <label class="form-check-label" for="multipleDays">Multiple Days</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="duration"
                                                id="firsthalf" value="3">
                                            <label class="form-check-label" for="firsthalf">First Half</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="duration"
                                                id="secondhalf" value="4">
                                            <label class="form-check-label" for="secondhalf">Second Half</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Single Date Field -->
                                <div class="col-md-6" id="singleDateField">
                                    <label for="leaveDate" class="form-label">Date </label>
                                    <input type="date" id="leaveDate" class="form-control" name="date"
                                        required>
                                    <div id="holidayInfoSingle" class="mt-3 small text-success"></div>
                                    <div id="dayoffInfoSingle" class="mt-1 small text-warning"></div>
                                </div>

                                <!-- Date Range Fields -->
                                <div class="col-md-6 d-none" id="dateRangeFields">
                                    <label for="selectedDateRange" class="form-label">Date Range</label>
                                    <p class="" id="openDateRangePicker">
                                        <input type="text" class="form-control" id="selectedDateRange" readonly
                                            placeholder="Select date range" width="100">
                                    </p>

                                    <div id="holidayInfoRange" class="mt-3 small text-success"></div>
                                    <div id="dayoffInfoRange" class="mt-1 small text-warning"></div>

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
                                                    <span></span>
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
                                                    <span></span>
                                                    <button class="month-nav-btn" id="nextMonth"><i
                                                            class="bi bi-chevron-right"></i></button>
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
                            </div>

                            <div class="mb-4">
                                <label for="leaveReason" class="form-label">Reason for absence </label>
                                <textarea id="leaveReason" class="form-control" name="reason" rows="3" required
                                    placeholder="e.g. Feeling not well"></textarea>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="leaveFile" class="form-label">Attachment (Optional)</label>
                                    <input type="file" id="leaveFile" class="form-control" name="file">
                                    <small class="text-muted">You can upload one file (image, document, CSV,
                                        etc.)</small>
                                    <div id="filePreviewContainer" class="mt-2"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="saveLeave">
                            Apply
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Leave Modal -->
        <div class="modal fade" id="editLeaveModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg leavelen">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Leave</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editLeaveForm" class="p-2" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="editLeaveId" name="leave_id">
                            <input type="hidden" id="editEmployeeId" name="employee_id">
                            <input type="hidden" id="editDurationType" name="duration">

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Employee Name</label>
                                    <input type="text" id="editLeaveEmployee" class="form-control" readonly
                                        style="background-color: #e9e7e7;">
                                </div>

                                <div class="col-md-6">
                                    <label for="editLeaveType" class="form-label">Leave Type</label>
                                    <select id="editLeaveType" class="form-select" name="leave_type_id" required>
                                        <option value="">Select Leave Type</option>
                                    </select>

                                    <small id="editLeaveTypeInfo" class="text-danger ms-2 mt-3"
                                        style="display:none; color:#6c757d; font-size: 14px"></small>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Select Duration </label>
                                    <div class="d-flex flex-wrap gap-3 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="edit_duration"
                                                id="editFullDay" value="1">
                                            <label class="form-check-label" for="editFullDay">Full Day</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="edit_duration"
                                                id="editMultipleDays" value="2">
                                            <label class="form-check-label" for="editMultipleDays">Multiple
                                                Days</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="edit_duration"
                                                id="editFirstHalf" value="3">
                                            <label class="form-check-label" for="editFirstHalf">First Half</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="edit_duration"
                                                id="editSecondHalf" value="4">
                                            <label class="form-check-label" for="editSecondHalf">Second Half</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Edit Single Date Field -->
                                <div class="col-md-6" id="editSingleDateField">
                                    <label for="editLeaveDate" class="form-label">Date </label>
                                    <input type="date" id="editLeaveDate" class="form-control" name="date">
                                    <div id="editHolidayInfoSingle" class="mt-3 small text-success"></div>
                                    <div id="editDayoffInfoSingle" class="mt-1 small text-warning"></div>
                                </div>

                                <!-- Edit Date Range Fields -->
                                <div class="col-md-6 d-none" id="editDateRangeFields">
                                    <label for="editSelectedDateRange" class="form-label">Date Range</label>
                                    <p class="" id="editOpenDateRangePicker">
                                        <input type="text" class="form-control" id="editSelectedDateRange"
                                            readonly placeholder="Select date range" width="100">
                                    </p>

                                    <div id="editHolidayInfoRange" class="mt-3 small text-success"></div>
                                    <div id="editDayoffInfoRange" class="mt-1 small text-warning"></div>

                                    <input type="hidden" id="editDateRangeFrom" name="date_range_from">
                                    <input type="hidden" id="editDateRangeTo" name="date_range_to">
                                    <!-- Edit Date Range Picker Container -->
                                    <div class="date-range-picker-container mt-2 d-none"
                                        id="editDateRangePickerContainer">
                                        <span id="editDateRangeDisplay" style="display: none;"></span>
                                        <div class="date-range-calendar">
                                            <div class="month-container">
                                                <div class="month-header" id="editMonth1Header">
                                                    <button type="button" class="month-nav-btn" id="editPrevMonth">
                                                        <i class="bi bi-chevron-left"></i>
                                                    </button>
                                                    <span></span>
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
                                                <div class="days-grid" id="editMonth1Days"></div>
                                            </div>

                                            <div class="month-container">
                                                <div class="month-header" id="editMonth2Header">
                                                    <span></span>
                                                    <button class="month-nav-btn" id="editNextMonth"><i
                                                            class="bi bi-chevron-right"></i></button>
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
                                                <div class="days-grid" id="editMonth2Days"></div>
                                            </div>
                                        </div>

                                        <div class="date-range-footer border-0">
                                            <button type="button"
                                                class="btn btn-outline-secondary text-start btn-sm edit-cancel-btn">Cancel</button>
                                            <button type="button"
                                                class="btn btn-sm btn-primary edit-apply-btn">Apply</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="editLeaveReason" class="form-label">Reason for absence </label>
                                <textarea id="editLeaveReason" class="form-control" name="reason" rows="3" required
                                    placeholder="e.g. Feeling not well"></textarea>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="editLeaveFile" class="form-label">Attachment (Optional)</label>
                                    <input type="file" id="editLeaveFile" class="form-control" name="file">
                                    <small class="text-muted">You can upload one file (image, document, CSV,
                                        etc.)</small>
                                    <div id="editFilePreviewContainer" class="mt-2"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="updateLeave">
                            Update Leave
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- View Status Modal -->
        <div class="modal fade" id="viewStatusModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog viewmodal">
                <div class="modal-content border-0">
                    <div class="modal-header bg-light">
                        <div>
                            <h5 class="modal-title fw-bold text-dark">Leave Details</h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <img id="viewEmployeeImage" src="" class="rounded-circle me-3"
                                style="width: 60px; height: 60px; object-fit: cover;">
                            <div>
                                <h6 class="mb-1 fw-bold" id="viewEmployeeName"></h6>
                                <p class="mb-1 text-muted" id="viewEmployeeDept"></p>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded">
                                    <p class="small text-muted mb-1">Leave Type</p>
                                    <p class="mb-0 fw-bold" id="viewLeaveType"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded">
                                    <p class="small text-muted mb-1">Duration</p>
                                    <p class="mb-0 fw-bold" id="viewLeaveDuration"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded">
                                    <p class="small text-muted mb-1">Date</p>
                                    <p class="mb-0 fw-bold" id="viewLeaveDates"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded">
                                    <p class="small text-muted mb-1">Status</p>
                                    <p class="mb-0"><span id="viewCurrentStatus" class="badge"></span></p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-bold mb-2 d-flex align-items-center">
                                <i class="bi bi-chat-square-text me-2"></i> Reason
                            </h6>
                            <div class="p-3 bg-light rounded">
                                <p class="mb-0" id="viewLeaveReason"></p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <h6 class="fw-bold mb-2 d-flex align-items-center">
                                <i class="bi bi-paperclip me-2"></i> Attachments
                            </h6>
                            <div id="viewLeaveFiles" class="d-flex flex-wrap gap-3 mt-3">
                                <!-- Files will be populated here -->
                            </div>
                            <div id="noFilesMessage" class="text-center py-3 bg-light rounded">
                                <i class="bi bi-file-earmark-x text-muted" style="font-size: 2rem;"></i>
                                <p class="text-muted mb-0">No attachments found</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">Close</button>
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
            // Initialize variables
            let currentLeavePage = 1;
            let entriesPerPage = 5;
            let activeFilters = {
                dateRange: '',
                status: ''
            };

            // Flatpickr for single date fields
            const leaveDatePicker = flatpickr("#leaveDate", {
                dateFormat: "Y-m-d",
                defaultDate: "today"
            });

            const editLeaveDatePicker = flatpickr("#editLeaveDate", {
                dateFormat: "Y-m-d"
            });

            // DateRangePicker class
            class DateRangePicker {
                constructor(config) {
                    this.currentDate = new Date();
                    this.selectedRange = {
                        start: null,
                        end: null
                    };
                    this.config = config;
                    this.init();
                }

                init() {
                    this.render();
                    this.setupEventListeners();
                }

                render() {
                    const month1 = new Date(this.currentDate);
                    this.renderMonth(month1, this.config.month1Header, this.config.month1Days);

                    const month2 = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() + 1, 1);
                    this.renderMonth(month2, this.config.month2Header, this.config.month2Days);

                    this.updateRangeDisplay();
                }

                renderMonth(date, headerId, daysId) {
                    const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct",
                        "Nov", "Dec"
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
                            "yyyy-MM-dd");
                        day.textContent = i;

                        if (i === today.getDate() && date.getMonth() === today.getMonth() && date
                            .getFullYear() === today.getFullYear()) {
                            day.classList.add('today');
                        }

                        const currentDate = new Date(date.getFullYear(), date.getMonth(), i);
                        if (this.selectedRange.start && this.selectedRange.end) {
                            if (currentDate >= this.selectedRange.start && currentDate <= this.selectedRange
                                .end) {
                                day.classList.add('in-range');
                            }
                            if (this.formatDate(currentDate, 'yyyy-MM-dd') === this.formatDate(this
                                    .selectedRange.start, 'yyyy-MM-dd')) {
                                day.classList.add('selected');
                            }
                            if (this.formatDate(currentDate, 'yyyy-MM-dd') === this.formatDate(this
                                    .selectedRange.end, 'yyyy-MM-dd')) {
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
                    document.getElementById(this.config.month1Days).addEventListener('click', (e) => this
                        .handleDayClick(e));
                    document.getElementById(this.config.month2Days).addEventListener('click', (e) => this
                        .handleDayClick(e));

                    document.getElementById(this.config.prevMonth).addEventListener('click', (e) => {
                        e.preventDefault();
                        this.currentDate.setMonth(this.currentDate.getMonth() - 1);
                        this.render();
                    });

                    document.getElementById(this.config.nextMonth).addEventListener('click', (e) => {
                        e.preventDefault();
                        this.currentDate.setMonth(this.currentDate.getMonth() + 1);
                        this.render();
                    });

                    document.querySelector(this.config.cancelBtn).addEventListener('click', () => {
                        this.selectedRange = {
                            start: null,
                            end: null
                        };
                        document.getElementById(this.config.input).value = '';
                        document.getElementById(this.config.container).classList.add('d-none');
                        this.render();
                    });

                    document.querySelector(this.config.applyBtn).addEventListener('click', () => {
                        if (this.selectedRange.start && this.selectedRange.end) {
                            const formattedRange =
                                `${this.formatDate(this.selectedRange.start, 'dd-MM-yyyy')} To ${this.formatDate(this.selectedRange.end, 'dd-MM-yyyy')}`;
                            document.getElementById(this.config.input).value = formattedRange;
                            document.getElementById(this.config.container).classList.add('d-none');

                            document.getElementById(this.config.dateFrom).value = this.formatDate(this
                                .selectedRange.start, 'yyyy-MM-dd');
                            document.getElementById(this.config.dateTo).value = this.formatDate(this
                                .selectedRange.end, 'yyyy-MM-dd');

                            const fromDate = this.formatDate(this.selectedRange.start, 'yyyy-MM-dd');
                            const toDate = this.formatDate(this.selectedRange.end, 'yyyy-MM-dd');
                            const employeeId = document.querySelector('input[name="employee_id"]')
                                .value;

                            // STEP 2: Enhanced holiday checking with multiple types
                            checkForUnavailableDatesInRange(employeeId, fromDate, toDate,
                                'holidayInfoRange');
                        }
                    });

                    document.getElementById(this.config.openPicker).addEventListener('click', () => {
                        const picker = document.getElementById(this.config.container);
                        picker.classList.toggle('d-none');
                    });
                }

                handleDayClick(e) {
                    if (e.target.classList.contains('day') && !e.target.classList.contains('disabled')) {
                        const day = parseInt(e.target.textContent);
                        const monthHeader = e.target.closest('.month-container').querySelector(
                            '.month-header span').textContent;
                        const [monthName, year] = monthHeader.split(' ');
                        const monthIndex = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep",
                            "Oct", "Nov", "Dec"
                        ].indexOf(monthName);
                        const date = new Date(year, monthIndex, day);

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

                updateRangeDisplay() {
                    if (this.selectedRange.start && this.selectedRange.end) {
                        document.getElementById(this.config.display).textContent =
                            `${this.formatDate(this.selectedRange.start, 'dd-MM-yyyy')} To ${this.formatDate(this.selectedRange.end, 'dd-MM-yyyy')}`;
                    } else if (this.selectedRange.start) {
                        document.getElementById(this.config.display).textContent =
                            `${this.formatDate(this.selectedRange.start, 'dd-MM-yyyy')}`;
                    } else {
                        document.getElementById(this.config.display).textContent = 'Select date range';
                    }
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

            // Initialize picker for Add modal
            const addLeaveDateRangePicker = new DateRangePicker({
                month1Header: "month1Header",
                month1Days: "month1Days",
                month2Header: "month2Header",
                month2Days: "month2Days",
                prevMonth: "prevMonth",
                nextMonth: "nextMonth",
                cancelBtn: ".cancel-btn",
                applyBtn: ".apply-btn",
                openPicker: "openDateRangePicker",
                input: "selectedDateRange",
                container: "dateRangePickerContainer",
                display: "dateRangeDisplay",
                dateFrom: "date_range_from",
                dateTo: "date_range_to"
            });

            // Initialize picker for Edit modal
            const editLeaveDateRangePicker = new DateRangePicker({
                month1Header: "editMonth1Header",
                month1Days: "editMonth1Days",
                month2Header: "editMonth2Header",
                month2Days: "editMonth2Days",
                prevMonth: "editPrevMonth",
                nextMonth: "editNextMonth",
                cancelBtn: ".edit-cancel-btn",
                applyBtn: ".edit-apply-btn",
                openPicker: "editOpenDateRangePicker",
                input: "editSelectedDateRange",
                container: "editDateRangePickerContainer",
                display: "editDateRangeDisplay",
                dateFrom: "editDateRangeFrom",
                dateTo: "editDateRangeTo"
            });

            // Filter date range picker
            const filterDateRangePicker = flatpickr("#dateRangePicker", {
                mode: "range",
                dateFormat: "Y-m-d",
                onClose: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length === 2) {
                        const fromDate = formatDateForDisplay(selectedDates[0]);
                        const toDate = formatDateForDisplay(selectedDates[1]);
                        document.getElementById('dateRangeText').textContent =
                            `${fromDate} to ${toDate}`;
                        activeFilters.dateRange = {
                            from: formatDateForStorage(selectedDates[0]),
                            to: formatDateForStorage(selectedDates[1])
                        };
                        applyFilters();
                    } else if (selectedDates.length === 0) {
                        document.getElementById('dateRangeText').textContent = 'All Dates';
                        activeFilters.dateRange = '';
                        applyFilters();
                    }
                }
            });

            // DOM elements
            const leaveModal = new bootstrap.Modal(document.getElementById('leaveModal'));
            const editLeaveModal = new bootstrap.Modal(document.getElementById('editLeaveModal'));
            const viewStatusModal = new bootstrap.Modal(document.getElementById('viewStatusModal'));
            const addLeaveBtn = document.getElementById('addLeave');
            const saveLeaveBtn = document.getElementById('saveLeave');
            const updateLeaveBtn = document.getElementById('updateLeave');
            const prevLeavePageBtn = document.getElementById('prevLeavePage');
            const nextLeavePageBtn = document.getElementById('nextLeavePage');
            const leavePageInfo = document.getElementById('leavePageInfo');
            const leaveShowingInfo = document.getElementById('leaveShowingInfo');
            const entriesPerPageSelect = document.getElementById('entriesPerPage');
            const clearFiltersBtn = document.getElementById('clearFiltersBtn');
            const clearFiltersBtnContainer = document.getElementById('clearFiltersBtnContainer');

            // Filter elements
            const statusFilter = document.getElementById('statusFilter');
            const statusFilterText = document.getElementById('statusFilterText');

            // Duration radio buttons
            const fullDayRadio = document.getElementById('fullDay');
            const multipleDaysRadio = document.getElementById('multipleDays');

            // Initialize the leave planner
            initLeavePlanner();

            if (addLeaveBtn) {
                addLeaveBtn.addEventListener('click', openLeaveModal);
            }

            function initLeavePlanner() {
                setupEventListeners();
                fetchLeaves();
            }

            function setupEventListeners() {
                // Save leave
                saveLeaveBtn.addEventListener('click', validateAndSaveLeave);

                // Update leave
                updateLeaveBtn.addEventListener('click', updateLeave);

                // Pagination controls
                prevLeavePageBtn.addEventListener('click', goToPrevLeavePage);
                nextLeavePageBtn.addEventListener('click', goToNextLeavePage);

                // Entries per page change
                entriesPerPageSelect.addEventListener('change', function() {
                    entriesPerPage = parseInt(this.value);
                    currentLeavePage = 1;
                    fetchLeaves();
                });

                // Duration type change
                fullDayRadio.addEventListener('change', toggleDateFields);
                multipleDaysRadio.addEventListener('change', toggleDateFields);
                document.getElementById('firsthalf').addEventListener('change', toggleDateFields);
                document.getElementById('secondhalf').addEventListener('change', toggleDateFields);

                // Edit duration type change
                document.querySelectorAll('input[name="edit_duration"]').forEach(radio => {
                    radio.addEventListener('change', toggleEditDateFields);
                });

                // Filter changes
                statusFilter.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    if (selectedOption.value === '') {
                        statusFilterText.textContent = 'All Status';
                        activeFilters.status = '';
                    } else {
                        statusFilterText.textContent = selectedOption.textContent;
                        activeFilters.status = selectedOption.value;
                    }
                    applyFilters();
                    checkFiltersStatus();
                });

                // Clear filters button
                clearFiltersBtn.addEventListener('click', clearAllFilters);

                // Update leave type info display
                document.getElementById('leaveType').addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const infoElement = document.getElementById('leaveTypeInfo');

                    if (selectedOption && selectedOption.value) {
                        const days = selectedOption.dataset.days;
                        const remaining = selectedOption.dataset.remaining;
                        const startDate = selectedOption.dataset.start;
                        const endDate = selectedOption.dataset.end;

                        infoElement.style.display = 'block';
                        infoElement.textContent =
                            `Available: ${remaining}/${days} days | Valid from ${formatDateForDisplay(startDate)} to ${formatDateForDisplay(endDate)}`;

                        if (parseInt(remaining) <= 0) {
                            infoElement.style.color = 'red';
                            infoElement.textContent += ' - Cannot assign leave, no remaining days';
                        } else {
                            infoElement.style.color = '#6c757d';
                        }
                    } else {
                        infoElement.style.display = 'none';
                    }
                });

                // Update edit leave type info display
                document.getElementById('editLeaveType').addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const infoElement = document.getElementById('editLeaveTypeInfo');

                    if (selectedOption && selectedOption.value) {
                        const days = selectedOption.dataset.days;
                        const remaining = selectedOption.dataset.remaining;
                        const startDate = selectedOption.dataset.start;
                        const endDate = selectedOption.dataset.end;

                        infoElement.style.display = 'block';
                        infoElement.textContent =
                            `Available: ${remaining}/${days} days | Valid from ${formatDateForDisplay(startDate)} to ${formatDateForDisplay(endDate)}`;

                        if (parseInt(remaining) <= 0) {
                            infoElement.style.color = 'red';
                            infoElement.textContent += ' - Cannot assign leave, no remaining days';
                        } else {
                            infoElement.style.color = '#6c757d';
                        }
                    } else {
                        infoElement.style.display = 'none';
                    }
                });

                // Event delegation for dynamic elements
                document.addEventListener('click', function(e) {
                    // View leave details
                    if (e.target.classList.contains('view-leave')) {
                        const leaveId = e.target.getAttribute('data-id');
                        viewLeave(leaveId);
                    }
                    // Edit leave
                    if (e.target.classList.contains('edit-leave')) {
                        const leaveId = e.target.getAttribute('data-id');
                        editLeave(leaveId);
                    }
                    // Delete leave
                    if (e.target.classList.contains('delete-leave')) {
                        const leaveId = e.target.getAttribute('data-id');
                        deleteLeave(leaveId);
                    }

                    // Add this new event handler for view-dates button
                    if (e.target.classList.contains('view-dates')) {
                        const leaveId = e.target.getAttribute('data-id');
                        viewLeaveDates(leaveId);
                    }
                });

                // Add this function to view leave dates
                function viewLeaveDates(leaveId) {
                    fetch(`/dashboard/employees/leave/dates/${leaveId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const dateViewModal = new bootstrap.Modal(document.getElementById(
                                    'dateViewModal'));
                                const totalDaysCount = document.getElementById('totalDaysCount');
                                const appliedDatesList = document.getElementById('appliedDatesList');
                                const holidaysList = document.getElementById('holidaysList');

                                // Clear previous content
                                appliedDatesList.innerHTML = '';
                                holidaysList.innerHTML = '';

                                // Set total days count (only working days, excluding holidays/weekends)
                                const workingDays = data.dates.filter(date => date.type === 'working-day');
                                totalDaysCount.textContent = workingDays.length;

                                // Separate applied dates from holidays/weekends
                                const appliedDates = [];
                                const holidayDates = [];

                                data.dates.forEach(dateInfo => {
                                    if (dateInfo.type === 'working-day') {
                                        appliedDates.push(dateInfo);
                                    } else {
                                        holidayDates.push(dateInfo);
                                    }
                                });

                                // Display applied dates (only working days)
                                if (appliedDates.length > 0) {
                                    appliedDates.forEach(dateInfo => {
                                        const dateItem = document.createElement('div');
                                        dateItem.className =
                                            'applied-date d-flex align-items-center py-2';
                                        dateItem.innerHTML = `
                            <i class="bi bi-calendar-check text-success me-2"></i>
                            <span>${dateInfo.formatted_date}</span>
                        `;
                                        appliedDatesList.appendChild(dateItem);
                                    });
                                } else {
                                    appliedDatesList.innerHTML = `
                        <div class="text-center py-3 text-muted">
                            <i class="bi bi-calendar-x me-2"></i>No applied dates
                        </div>
                    `;
                                }

                                // Display holidays and weekends
                                if (holidayDates.length > 0) {
                                    holidayDates.forEach(dateInfo => {
                                        const holidayItem = document.createElement('div');
                                        holidayItem.className =
                                            'holiday-date d-flex align-items-center py-2';

                                        let holidayType = '';
                                        let iconClass = '';

                                        if (dateInfo.type === 'weekend') {
                                            holidayType = 'Weekend Holiday';
                                            iconClass = 'bi bi-emoji-sunglasses text-warning';
                                        } else if (dateInfo.type === 'dayoff') {
                                            holidayType = 'Scheduled Day Off';
                                            iconClass = 'bi bi-clock text-info';
                                        } else if (dateInfo.holiday_type === 'Local Holiday') {
                                            holidayType = 'Local Holiday';
                                            iconClass = 'bi bi-flag text-danger';
                                        } else if (dateInfo.holiday_type === 'Religious Holiday') {
                                            holidayType = 'Religious Holiday';
                                            iconClass = 'bi bi-geo-alt text-primary';
                                        }

                                        let content = `
                            <i class="${iconClass} me-2"></i>
                            <span>${dateInfo.formatted_date}</span>
                        `;

                                        if (dateInfo.type === 'weekend') {
                                            content +=
                                                ` - <span class="text-muted">${holidayType}</span>`;
                                        } else if (dateInfo.holiday_name) {
                                            content +=
                                                ` - ${dateInfo.holiday_name} <span class="text-muted">(${holidayType})</span>`;
                                        }

                                        holidayItem.innerHTML = content;
                                        holidaysList.appendChild(holidayItem);
                                    });
                                } else {
                                    holidaysList.innerHTML = `
                        <div class="text-center py-3 text-muted">
                            <i class="bi bi-emoji-smile me-2"></i>No holidays in this period
                        </div>
                    `;
                                }

                                dateViewModal.show();
                            } else {
                                showToast(data.message, 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching leave dates:', error);
                            showToast('An error occurred', 'error');
                        });
                }

                // Add modal date change listeners
                const employeeId = document.querySelector('input[name="employee_id"]').value;
                document.getElementById('leaveDate').addEventListener('change', function() {
                    // STEP 2: Enhanced holiday checking with multiple types
                    checkForUnavailableOnDate(employeeId, this.value, 'holidayInfoSingle');
                });

                // Edit modal date change listeners
                document.getElementById('editLeaveModal').addEventListener('show.bs.modal', function() {
                    const employeeId = document.getElementById('editEmployeeId').value;
                    const dateInput = document.getElementById('editLeaveDate');

                    if (dateInput && employeeId) {
                        dateInput.addEventListener('change', function() {
                            // STEP 2: Enhanced holiday checking with multiple types
                            checkForUnavailableOnDate(employeeId, this.value,
                                'editHolidayInfoSingle');
                        });
                    }
                });
            }

            // Pagination functions
            function goToPrevLeavePage() {
                if (currentLeavePage > 1) {
                    currentLeavePage--;
                    fetchLeaves();
                }
            }

            function goToNextLeavePage() {
                currentLeavePage++;
                fetchLeaves();
            }

            function toggleDateFields() {
                const durationType = document.querySelector('input[name="duration"]:checked').value;

                // Hide all date fields first
                document.getElementById('singleDateField').classList.add('d-none');
                document.getElementById('dateRangeFields').classList.add('d-none');

                // Show the appropriate date field based on selection
                if (durationType === '2') { // Multiple days
                    document.getElementById('dateRangeFields').classList.remove('d-none');
                } else { // Full day, first half, or second half
                    document.getElementById('singleDateField').classList.remove('d-none');
                }
            }

            function toggleEditDateFields() {
                const durationType = document.querySelector('input[name="edit_duration"]:checked').value;

                // Hide all date fields first
                document.getElementById('editSingleDateField').classList.add('d-none');
                document.getElementById('editDateRangeFields').classList.add('d-none');

                // Show the appropriate date field based on selection
                if (durationType === '2') { // Multiple days
                    document.getElementById('editDateRangeFields').classList.remove('d-none');
                } else { // Full day, first half, or second half
                    document.getElementById('editSingleDateField').classList.remove('d-none');
                }

                // Update the hidden duration field
                document.getElementById('editDurationType').value = durationType;
            }

            function openLeaveModal() {
                document.getElementById('leaveForm').reset();
                document.getElementById('leaveId').value = '';
                document.getElementById('leaveModalTitle').textContent = 'Apply Leave';

                leaveDatePicker.setDate(new Date());
                document.getElementById('selectedDateRange').value = '';
                document.getElementById('date_range_from').value = '';
                document.getElementById('date_range_to').value = '';
                document.getElementById('singleDateField').classList.remove('d-none');
                document.getElementById('dateRangeFields').classList.add('d-none');
                fullDayRadio.checked = true;
                document.getElementById('leaveType').innerHTML = '<option value="">Select Leave Type</option>';
                document.getElementById('leaveTypeInfo').textContent = '';
                document.getElementById('filePreviewContainer').innerHTML = '';
                document.getElementById('holidayInfoSingle').innerHTML = '';
                document.getElementById('holidayInfoRange').innerHTML = '';
                document.getElementById('dayoffInfoSingle').innerHTML = '';
                document.getElementById('dayoffInfoRange').innerHTML = '';

                // Load leave types for the current user
                const employeeId = document.querySelector('input[name="employee_id"]').value;
                if (employeeId) {
                    fetchLeaveTypes(employeeId);
                }

                leaveModal.show();
            }

            function fetchLeaveTypes(employeeId) {
                fetch(`/dashboard/employees/leave/types/${employeeId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        const leaveTypeSelect = document.getElementById('leaveType');
                        leaveTypeSelect.innerHTML = '<option value="">Select Leave Type</option>';

                        if (Array.isArray(data) && data.length > 0) {
                            data.forEach(leaveType => {
                                const option = document.createElement('option');
                                option.value = leaveType.id;
                                option.textContent = leaveType.text;
                                option.dataset.days = leaveType.leave_days;
                                option.dataset.remaining = leaveType.remaining_days;
                                option.dataset.start = leaveType.start_date;
                                option.dataset.end = leaveType.end_date;
                                leaveTypeSelect.appendChild(option);
                            });

                            if (data.length > 0) {
                                const firstOption = leaveTypeSelect.options[1];
                                const infoElement = document.getElementById('leaveTypeInfo');

                                infoElement.textContent =
                                    `Available: ${firstOption.dataset.remaining}/${firstOption.dataset.days} days | Valid from ${formatDateForDisplay(firstOption.dataset.start)} to ${formatDateForDisplay(firstOption.dataset.end)}`;

                                if (parseInt(firstOption.dataset.remaining) <= 0) {
                                    infoElement.style.color = 'red';
                                    infoElement.textContent += ' - Cannot assign leave, no remaining days';
                                } else {
                                    infoElement.style.color = '#6c757d';
                                }
                            }
                        } else {
                            const option = document.createElement('option');
                            option.value = '';
                            option.textContent = 'No leave types available for this employee';
                            leaveTypeSelect.appendChild(option);
                            document.getElementById('leaveTypeInfo').textContent = '';
                            showToast('No leave types available for this employee', 'info');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching leave types:', error);
                        showToast('Error loading leave types', 'error');
                    });
            }

         // STEP 1: Enhanced validation with auto-overwrite for duplicates and date range validation
async function validateAndSaveLeave() {
    const saveBtn = document.getElementById('saveLeave');

    // Prevent multiple clicks
    if (saveBtn.disabled) {
        return;
    }

    const originalText = saveBtn.innerHTML;
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Applying...';

    try {
        const leaveTypeSelect = document.getElementById('leaveType');
        const selectedOption = leaveTypeSelect.options[leaveTypeSelect.selectedIndex];
        const durationType = document.querySelector('input[name="duration"]:checked').value;
        const employeeId = document.querySelector('input[name="employee_id"]').value;

        if (!selectedOption || !selectedOption.value) {
            showToast('Please select a leave type', 'error');
            return;
        }

        // Get leave type details
        const remainingDays = parseInt(selectedOption.dataset.remaining);
        const startDate = selectedOption.dataset.start;
        const endDate = selectedOption.dataset.end;

        // STEP 2: Validate selected date against leave type's date range
        if (durationType === '2') { // Multiple days
            const fromDateInput = document.getElementById('date_range_from').value;
            const toDateInput = document.getElementById('date_range_to').value;

            if (!fromDateInput || !toDateInput) {
                showToast('Please select a valid date range', 'error');
                return;
            }

            // Convert to Date objects
            const selectedStartDate = new Date(fromDateInput);
            const selectedEndDate = new Date(toDateInput);
            const leaveTypeStart = new Date(startDate);
            const leaveTypeEnd = new Date(endDate);

            // Validate date range against leave type validity
            if (selectedStartDate < leaveTypeStart) {
                const formattedStartDate = formatDateForDisplay(startDate);
                showToast(`Leave not applicable for selected dates. Only applicable from ${formattedStartDate}`, 'error');
                return;
            }

            if (selectedEndDate > leaveTypeEnd) {
                const formattedEndDate = formatDateForDisplay(endDate);
                showToast(`Leave not applicable for selected dates. Only applicable until ${formattedEndDate}`, 'error');
                return;
            }

            const start = new Date(fromDateInput);
            const end = new Date(toDateInput);
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;

            if (diffDays > remainingDays) {
                showToast(
                    `Cannot assign leave - requested ${diffDays} days exceeds remaining ${remainingDays} days`,
                    'error');
                return;
            }

            // STEP 3: Enhanced check for holidays, Sundays, day-offs, and existing leaves
            const dates = getDatesBetween(start, end);
            let availableDates = [];
            const unavailableDates = [];
            let duplicateDates = [];

            for (const date of dates) {
                const result = await isDateUnavailable(date, employeeId);
                if (result.unavailable) {
                    unavailableDates.push({
                        date: date,
                        reason: result.reason,
                        holidayName: result.holidayName
                    });
                } else {
                    availableDates.push(date);
                }
            }

            // Check for duplicate leaves among available dates
            const duplicateCheck = await checkForDuplicateLeaves(employeeId, availableDates);
            if (duplicateCheck.hasDuplicates) {
                duplicateDates = duplicateCheck.duplicateDates;

                // Auto-overwrite duplicates without asking
                showToast(`${duplicateDates.length} existing leave(s) will be overwritten`, 'info');

                // Remove duplicates from available dates (they'll be processed separately)
                availableDates = availableDates.filter(date => !duplicateDates.includes(date));
            }

            // If no available dates after filtering, show error
            if (availableDates.length === 0 && duplicateDates.length === 0) {
                showToast(
                    'No available dates in the selected range (all dates are holidays, Sundays, day-offs, or have existing leaves)',
                    'error');
                return;
            }

            // If some dates are unavailable, show info message
            if (unavailableDates.length > 0) {
                const unavailableDateStrings = unavailableDates.map(ud => {
                    const formattedDate = formatDateForDisplay(ud.date);
                    return `${formattedDate} (${ud.reason}${ud.holidayName ? ': ' + ud.holidayName : ''})`;
                });

                showToast(`Skipping unavailable dates: ${unavailableDateStrings.join(', ')}`,
                    'info');
            }

            // Combine available dates and duplicate dates (duplicates will be overwritten)
            const allDatesToProcess = [...availableDates, ...duplicateDates];

            // Update the date range to only include processable dates
            if (allDatesToProcess.length > 0) {
                const firstAvailable = new Date(allDatesToProcess[0]);
                const lastAvailable = new Date(allDatesToProcess[allDatesToProcess.length - 1]);

                document.getElementById('date_range_from').value = formatDateForStorage(
                    firstAvailable);
                document.getElementById('date_range_to').value = formatDateForStorage(
                    lastAvailable);

                // Update the display
                const fromDisplay = formatDateForDisplay(firstAvailable);
                const toDisplay = formatDateForDisplay(lastAvailable);
                document.getElementById('selectedDateRange').value =
                    `${fromDisplay} to ${toDisplay}`;

                // Store all dates (including duplicates for overwriting) in a hidden field
                document.getElementById('availableDates').value = JSON.stringify(allDatesToProcess);
            }
        }
        else { // For first half, second half, or full day
            const date = document.getElementById('leaveDate').value;
            if (!date) {
                showToast('Please select a valid date', 'error');
                return;
            }

            // Validate single date against leave type date range
            const selectedDate = new Date(date);
            const leaveTypeStart = new Date(startDate);
            const leaveTypeEnd = new Date(endDate);

            if (selectedDate < leaveTypeStart) {
                const formattedStartDate = formatDateForDisplay(startDate);
                showToast(`Leave not applicable for this date. Only applicable from ${formattedStartDate}`, 'error');
                return;
            }

            if (selectedDate > leaveTypeEnd) {
                const formattedEndDate = formatDateForDisplay(endDate);
                showToast(`Leave not applicable for this date. Only applicable until ${formattedEndDate}`, 'error');
                return;
            }

            // Enhanced check for holiday, Sunday, day-off, or existing leave
            const result = await isDateUnavailable(date, employeeId);
            if (result.unavailable) {
                const formattedDate = formatDateForDisplay(date);
                showToast(
                    `Cannot assign leave on ${formattedDate} (${result.reason}${result.holidayName ? ': ' + result.holidayName : ''})`,
                    'error');
                return;
            }

            // Check for duplicate leave
            const duplicateCheck = await checkForDuplicateLeaves(employeeId, [date]);
            if (duplicateCheck.hasDuplicates) {
                // Auto-overwrite without asking
                showToast('Existing leave will be overwritten', 'info');
            }

            // For first half or second half, we count as 0.5 days against the balance
            if ((durationType === '3' || durationType === '4') && remainingDays < 0.5) {
                showToast('Cannot assign leave - not enough remaining days for this leave type',
                    'error');
                return;
            }

            // Check if no remaining days (for full day)
            if (durationType === '1' && remainingDays <= 0) {
                showToast('Cannot assign leave - no remaining days available for this leave type',
                    'error');
                return;
            }

            // Store the single date
            document.getElementById('availableDates').value = JSON.stringify([date]);
        }

        // Check shifts before saving
        const canProceed = await checkShifts();
        if (canProceed) {
            await saveLeave();
        }

    } catch (error) {
        console.error('Validation error:', error);
        showToast('Error validating leave application', 'error');
    } finally {
        // Re-enable button after a short delay
        setTimeout(() => {
            saveBtn.disabled = false;
            saveBtn.innerHTML = 'Apply';
        }, 2000);
    }
}
            async function checkForDuplicateLeaves(employeeId, dates) {
                try {
                    const response = await fetch('/dashboard/employees/leave/check-duplicates', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            employee_id: employeeId,
                            dates: dates
                        })
                    });

                    const data = await response.json();
                    return data;
                } catch (error) {
                    console.error('Error checking duplicates:', error);
                    return {
                        hasDuplicates: false,
                        duplicateDates: []
                    };
                }
            }

            async function saveLeave() {
                const formData = new FormData(document.getElementById('leaveForm'));
                const file = document.getElementById('leaveFile').files[0];

                // Collect selected dates
                let dates = [];
                const multipleDaysRadio = document.getElementById('multipleDays');

                if (multipleDaysRadio.checked) {
                    // Use the filtered available dates
                    const availableDates = document.getElementById('availableDates').value;
                    if (availableDates) {
                        dates = JSON.parse(availableDates);
                    }
                } else {
                    const singleDate = formData.get('date');
                    if (singleDate) {
                        dates = [singleDate];
                    }
                }

                if (dates.length === 0) {
                    showToast('Please select valid date(s)', 'error');
                    return;
                }

                const employeeId = formData.get('employee_id');

                // Build FormData for request
                const fileData = new FormData();

                // Add single file if exists
                if (file) {
                    fileData.append('file', file);
                }

                // Other fields
                fileData.append('employee_id', employeeId);
                fileData.append('leave_type_id', formData.get('leave_type_id'));
                fileData.append('duration', formData.get('duration'));
                fileData.append('reason', formData.get('reason'));
                fileData.append('available_dates', JSON.stringify(dates));
                fileData.append('_token', '{{ csrf_token() }}');

                if (multipleDaysRadio.checked) {
                    fileData.append('date_range_from', document.getElementById('date_range_from').value);
                    fileData.append('date_range_to', document.getElementById('date_range_to').value);
                } else {
                    fileData.append('date', formData.get('date'));
                }

                try {
                    const response = await fetch('/dashboard/employees/leave/stores', {
                        method: 'POST',
                        body: fileData,
                        headers: {
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        showToast(data.message, 'success');
                        document.getElementById('leaveModal').querySelector('.btn-close').click();
                        fetchLeaves();
                    } else {
                        showToast(data.message || 'Something went wrong', 'error');
                    }
                } catch (error) {
                    console.error('Save Leave Error:', error);
                    showToast(error.message || 'An error occurred', 'error');
                }
            }

            function fetchLeaves() {
                const requestData = {
                    dateRange: activeFilters.dateRange,
                    status: activeFilters.status
                };

                fetch('/dashboard/employees/leave/data', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(requestData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        renderEmployeeLeaves(data.employee_leaves || []);
                    })
                    .catch(error => {
                        console.error('Error fetching leaves:', error);
                    });
            }

            function renderEmployeeLeaves(leaves) {
                const tableBody = document.getElementById('employeeLeaveTableBody');
                tableBody.innerHTML = '';

                // Filter leaves for current user only
                const loggedInUserEmpId = {{ auth()->user()->employee->emp_id ?? 'null' }};
                const userLeaves = leaves.filter(leave => leave.employee_id === loggedInUserEmpId);

                if (userLeaves.length === 0) {
                    tableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-4">
                        No leaves found for your account
                    </td>
                </tr>
            `;
                    return;
                }

                userLeaves.forEach(leave => {
                    const row = createLeaveRow(leave);
                    tableBody.appendChild(row);
                });

                updatePaginationInfo(userLeaves.length);
            }

            // Update the createLeaveRow function to include View Dates button for multiple days
            function createLeaveRow(leave) {
                const row = document.createElement('tr');

                // Get the correct data for each column
                const leaveType = leave.leavetype?.leavetype_name_text || 'Absent';
                const durationType = getDurationTypeText(leave.select_duration);
                const appliedDate = formatDateWithDay(leave.created_at);
                const status = getStatusDisplay(leave);

                // Handle duration display differently for multiple days
                let durationDisplay = '';
                if (leave.select_duration == 2) {
                    // For multiple days, show day count and view button in Duration column
                    const fromDate = formatDateWithDay(leave.leavedaterange_from);
                    const toDate = formatDateWithDay(leave.leavedaterange_to);
                    const dayCount = leave.days_count || calculateDayCount(leave.leavedaterange_from, leave
                        .leavedaterange_to);

                    durationDisplay = `
            <div class="d-flex align-items-center justify-content-between">
                <span>${fromDate} to ${toDate}</span>
                <span class="badge bg-secondary ms-2">${dayCount} days</span>
            </div>
            <button class="btn btn-sm btn-outline-primary view-dates mt-1" data-id="${leave.leave_id}">
                <i class="bi bi-calendar-week me-1"></i> View Dates
            </button>
        `;
                } else {
                    // For single day leaves, show normal date
                    durationDisplay = formatDateWithDay(leave.leavedate_no);
                }

                // Actions based on status
                let actionsHtml = '';
                if (leave.leave_status == 2) { // Pending
                    actionsHtml = `
            <div class="dropdown">
                <button class="btn p-1" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-three-dots-vertical"></i>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <button class="dropdown-item edit-leave" data-id="${leave.leave_id}">
                            <i class="bi bi-pencil-fill text-primary me-2"></i> Edit
                        </button>
                    </li>
                    <li>
                        <button class="dropdown-item view-leave" data-id="${leave.leave_id}">
                            <i class="bi bi-eye-fill text-info me-2"></i> View
                        </button>
                    </li>
                    <li>
                        <button class="dropdown-item delete-leave" data-id="${leave.leave_id}">
                            <i class="bi bi-x-circle text-danger me-2"></i> Cancel
                        </button>
                    </li>
                </ul>
            </div>
        `;
                } else { // Approved or Rejected
                    actionsHtml = `
            <div class="dropdown">
                <button class="btn p-1" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-three-dots-vertical"></i>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <button class="dropdown-item view-leave" data-id="${leave.leave_id}">
                            <i class="bi bi-eye-fill text-info me-2"></i> View
                        </button>
                    </li>
                </ul>
            </div>
        `;
                }

                row.innerHTML = `
        <td>
            <span class="fw-medium leavebadge">
                ${leaveType}
            </span>
        </td>
        <td>${durationType}</td>
        <td>${durationDisplay}</td>
        <td>${appliedDate}</td>
        <td>${status}</td>
        <td>${actionsHtml}</td>
    `;

                return row;
            }
            // Helper function to calculate day count between two dates
            function calculateDayCount(fromDate, toDate) {
                const start = new Date(fromDate);
                const end = new Date(toDate);
                const diffTime = Math.abs(end - start);
                return Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            }

            function getDurationTypeText(duration) {
                switch (duration) {
                    case 1:
                        return 'Full Day';
                    case 2:
                        return 'Multiple Days';
                    case 3:
                        return 'First Half';
                    case 4:
                        return 'Second Half';
                    default:
                        return 'N/A';
                }
            }

            function getStatusDisplay(leave) {
                return `
            <div class="d-flex align-items-center">
                <span class="rounded-circle me-2 mt-1 ${leave.leave_status == 2 ? 'bg-warning' : (leave.leave_status == 1 ? 'bg-success' : 'bg-danger')}"
                    style="width: 10px; height: 10px; display: inline-block;"></span>
                <span>${leave.leave_status == 2 ? 'Pending' : (leave.leave_status == 1 ? 'Approved' : 'Rejected')}</span>
            </div>
        `;
            }

            function editLeave(leaveId) {
                fetch(`/dashboard/employees/leave/edits/${leaveId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const leave = data.leave;
                            document.getElementById('editLeaveForm').reset();
                            document.getElementById('editLeaveId').value = leave.leave_id;
                            document.getElementById('editEmployeeId').value = leave.employee_id;
                            document.getElementById('editLeaveEmployee').value = leave.employees.fullname;

                            // Set the duration type
                            document.getElementById('editDurationType').value = leave.select_duration;

                            // Set the appropriate radio button
                            const durationRadio = document.getElementById(
                                `edit${getDurationTypeName(leave.select_duration)}`);
                            if (durationRadio) {
                                durationRadio.checked = true;
                            }

                            // Fetch leave types for this employee
                            fetchLeaveTypesForEdit(leave.employee_id, leave.leave_type_id);

                            // Handle different duration types
                            if (leave.select_duration == 2) {
                                // For multiple days, show date range
                                document.getElementById('editLeaveDate').value = '';

                                // Set date range values
                                document.getElementById('editDateRangeFrom').value = leave.leavedaterange_from;
                                document.getElementById('editDateRangeTo').value = leave.leavedaterange_to;

                                // Set the date range display
                                const fromDate = editformatDateForDisplay(new Date(leave.leavedaterange_from));
                                const toDate = editformatDateForDisplay(new Date(leave.leavedaterange_to));
                                document.getElementById('editSelectedDateRange').value =
                                    `${fromDate} To ${toDate}`;

                                // Initialize date range picker with existing values
                                if (window.editDateRangePicker) {
                                    window.editDateRangePicker.selectedRange = {
                                        start: new Date(leave.leavedaterange_from),
                                        end: new Date(leave.leavedaterange_to)
                                    };
                                    window.editDateRangePicker.render();
                                }
                            } else {
                                // For single day leaves
                                document.getElementById('editLeaveDate').value = formatDateForStorage(new Date(
                                    leave.leavedate_no));
                                document.getElementById('editDateRangeFrom').value = '';
                                document.getElementById('editDateRangeTo').value = '';
                                document.getElementById('editSelectedDateRange').value = '';
                            }

                            // Toggle the date fields based on duration type
                            toggleEditDateFields();

                            document.getElementById('editLeaveReason').value = leave.reason_forleave;

                            // Display existing files
                            const filePreviewContainer = document.getElementById('editFilePreviewContainer');
                            filePreviewContainer.innerHTML = '';

                            if (leave.leave_file) {
                                const file = JSON.parse(leave.leave_file);
                                if (file) {
                                    const fileTitle = document.createElement('p');
                                    fileTitle.className = 'small fw-bold mb-2';
                                    fileTitle.textContent = 'Current Attachment:';
                                    filePreviewContainer.appendChild(fileTitle);

                                    const fileItem = document.createElement('div');
                                    fileItem.className =
                                        'border rounded p-2 d-flex align-items-center existing-file';

                                    // File icon
                                    const fileIcon = document.createElement('i');
                                    fileIcon.className = getFileIconClass(file.name);
                                    fileItem.appendChild(fileIcon);

                                    // File name
                                    const fileName = document.createElement('span');
                                    fileName.className = 'ms-2 small';
                                    fileName.textContent = file.name;
                                    fileItem.appendChild(fileName);

                                    // For images, create a preview
                                    if (file.type && file.type.startsWith('image/')) {
                                        const imgPreview = document.createElement('img');
                                        imgPreview.src = file.url;
                                        imgPreview.style.maxWidth = '50px';
                                        imgPreview.style.maxHeight = '50px';
                                        imgPreview.className = 'ms-2';
                                        imgPreview.onclick = (e) => {
                                            e.preventDefault();
                                            Swal.fire({
                                                imageUrl: file.url,
                                                imageAlt: file.name,
                                                showConfirmButton: false,
                                                background: 'transparent',
                                                backdrop: `rgba(0,0,0,0.8) url("${file.url}") center left no-repeat`
                                            });
                                        };
                                        fileItem.appendChild(imgPreview);
                                    }

                                    // Remove button
                                    const removeBtn = document.createElement('button');
                                    removeBtn.className = 'btn btn-sm btn-link text-danger ms-2';
                                    removeBtn.innerHTML = '<i class="bi bi-trash"></i>';
                                    removeBtn.onclick = (e) => {
                                        e.preventDefault();
                                        // Mark file for deletion
                                        const deleteInput = document.createElement('input');
                                        deleteInput.type = 'hidden';
                                        deleteInput.name = 'delete_file';
                                        deleteInput.value = '1';
                                        document.getElementById('editLeaveForm').appendChild(deleteInput);
                                        fileItem.remove();
                                    };
                                    fileItem.appendChild(removeBtn);

                                    filePreviewContainer.appendChild(fileItem);
                                }
                            }
                            editLeaveModal.show();
                        } else {
                            showToast(data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('An error occurred', 'error');
                    });
            }

            function editformatDateForDisplay(date) {
                if (!date) return '';
                const d = new Date(date);
                const day = String(d.getDate()).padStart(2, '0');
                const month = String(d.getMonth() + 1).padStart(2, '0');
                const year = d.getFullYear();
                return `${day}-${month}-${year}`;
            }

            // Helper function to get duration type name
            function getDurationTypeName(durationType) {
                switch (durationType) {
                    case 1:
                        return 'FullDay';
                    case 2:
                        return 'MultipleDays';
                    case 3:
                        return 'FirstHalf';
                    case 4:
                        return 'SecondHalf';
                    default:
                        return 'FullDay';
                }
            }

            function fetchLeaveTypesForEdit(employeeId, selectedLeaveTypeId) {
                fetch(`/dashboard/employees/leave/types/${employeeId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        const leaveTypeSelect = document.getElementById('editLeaveType');
                        leaveTypeSelect.innerHTML = '<option value="">Select Leave Type</option>';

                        if (Array.isArray(data) && data.length > 0) {
                            data.forEach(leaveType => {
                                const option = document.createElement('option');
                                option.value = leaveType.id;
                                option.textContent = leaveType.text;
                                option.dataset.days = leaveType.leave_days;
                                option.dataset.remaining = leaveType.remaining_days;
                                option.dataset.start = leaveType.start_date;
                                option.dataset.end = leaveType.end_date;

                                // Select the current leave type
                                if (leaveType.id == selectedLeaveTypeId) {
                                    option.selected = true;
                                }

                                leaveTypeSelect.appendChild(option);
                            });

                            // Show the selected leave type info
                            const selectedOption = leaveTypeSelect.options[leaveTypeSelect.selectedIndex];
                            const infoElement = document.getElementById('editLeaveTypeInfo');

                            if (selectedOption && selectedOption.value) {
                                infoElement.style.display = 'block';
                                infoElement.textContent =
                                    `Available: ${selectedOption.dataset.remaining}/${selectedOption.dataset.days} days | Valid from ${formatDateForDisplay(selectedOption.dataset.start)} to ${formatDateForDisplay(selectedOption.dataset.end)}`;

                                if (parseInt(selectedOption.dataset.remaining) <= 0) {
                                    infoElement.style.color = 'red';
                                    infoElement.textContent += ' - Cannot assign leave, no remaining days';
                                } else {
                                    infoElement.style.color = '#6c757d';
                                }
                            } else {
                                infoElement.style.display = 'none';
                            }
                        } else {
                            const option = document.createElement('option');
                            option.value = '';
                            option.textContent = 'No leave types available for this employee';
                            leaveTypeSelect.appendChild(option);
                            document.getElementById('editLeaveTypeInfo').textContent = '';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching leave types:', error);
                        showToast('Error loading leave types', 'error');
                    });
            }

            function updateLeave() {
                const updateBtn = document.getElementById('updateLeave');
                const originalText = updateBtn.innerHTML;

                // Disable button to prevent multiple clicks
                updateBtn.disabled = true;
                updateBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Updating...';

                const formData = new FormData(document.getElementById('editLeaveForm'));
                const leaveId = document.getElementById('editLeaveId').value;

                fetch(`/dashboard/employees/leave/updates/${leaveId}`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast(data.message, 'success');
                            editLeaveModal.hide();
                            fetchLeaves();
                        } else {
                            showToast(data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('An error occurred', 'error');
                    })
                    .finally(() => {
                        // Re-enable button
                        updateBtn.disabled = false;
                        updateBtn.innerHTML = originalText;
                    });
            }

            function viewLeave(leaveId) {
                fetch(`/dashboard/employees/leave/show/${leaveId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const leave = data.leave;
                            let datesDisplay = formatDateWithDay(leave.leavedate_no || leave
                                .leavedaterange_from);

                            if (leave.select_duration == 2) {
                                const fromDate = formatDateWithDay(leave.leavedaterange_from);
                                const toDate = formatDateWithDay(leave.leavedaterange_to);
                                datesDisplay = `${fromDate} to ${toDate}`;
                            }

                            document.getElementById('viewEmployeeName').textContent = leave.employees.fullname;
                            document.getElementById('viewEmployeeDept').textContent = leave.employees
                                .departmentid ? leave.employees.departmentid.dep_name : 'No Department';

                            const employeeImage = document.getElementById('viewEmployeeImage');
                            employeeImage.src = leave.employees.image ? '/employee_images/' + leave.employees
                                .image : '/images/admin_default.jpg';
                            employeeImage.alt = leave.employees.fullname;

                            document.getElementById('viewLeaveType').textContent = leave.leavetype
                                ?.leavetype_name_text || 'Absent';

                            let durationText = '';
                            if (leave.select_duration == 1) {
                                durationText = 'Full Day';
                            } else if (leave.select_duration == 2) {
                                durationText = 'Multiple Days';
                            } else if (leave.select_duration == 3) {
                                durationText = 'First Half';
                            } else if (leave.select_duration == 4) {
                                durationText = 'Second Half';
                            } else {
                                durationText = 'N/A';
                            }

                            document.getElementById('viewLeaveDuration').textContent = durationText;
                            document.getElementById('viewLeaveDates').textContent = datesDisplay;
                            document.getElementById('viewLeaveReason').textContent = leave.reason_forleave ||
                                'Not specified';

                            const statusBadge = document.getElementById('viewCurrentStatus');
                            statusBadge.textContent = leave.leave_status == 2 ? 'Pending' : (leave
                                .leave_status == 1 ? 'Approved' : 'Rejected');
                            statusBadge.className = 'badge ' + (leave.leave_status == 2 ? 'badge-pending' : (
                                leave.leave_status == 1 ? 'badge-approved' : 'badge-rejected'));

                            const filesContainer = document.getElementById('viewLeaveFiles');
                            const noFilesMessage = document.getElementById('noFilesMessage');
                            filesContainer.innerHTML = '';

                            if (leave.leave_file) {
                                const file = JSON.parse(leave.leave_file);
                                if (file) {
                                    noFilesMessage.style.display = 'none';

                                    const fileCard = document.createElement('div');
                                    fileCard.className = 'file-card';

                                    const filePreview = document.createElement('div');
                                    filePreview.className = 'file-card-img';

                                    if (file.type && file.type.startsWith('image/')) {
                                        const img = document.createElement('img');
                                        img.src = file.url;
                                        img.style.maxWidth = '100%';
                                        img.style.maxHeight = '100%';
                                        img.style.objectFit = 'contain';
                                        img.onclick = () => {
                                            Swal.fire({
                                                imageUrl: file.url,
                                                imageAlt: file.name,
                                                showConfirmButton: false,
                                                background: 'transparent',
                                                backdrop: `rgba(0,0,0,0.8) url("${file.url}") center left no-repeat`
                                            });
                                        };
                                        filePreview.appendChild(img);
                                    } else {
                                        const fileIcon = document.createElement('i');
                                        fileIcon.className = getFileIconClass(file.name);
                                        filePreview.appendChild(fileIcon);
                                    }

                                    const fileBody = document.createElement('div');
                                    fileBody.className = 'file-card-body text-center';

                                    const fileName = document.createElement('div');
                                    fileName.className = 'file-name';
                                    fileName.textContent = file.name;

                                    fileBody.appendChild(fileName);
                                    fileCard.appendChild(filePreview);
                                    fileCard.appendChild(fileBody);

                                    fileCard.onclick = () => {
                                        if (file.type && file.type.startsWith('image/')) {
                                            Swal.fire({
                                                imageUrl: file.url,
                                                imageAlt: file.name,
                                                showConfirmButton: false,
                                                background: 'transparent',
                                                backdrop: `rgba(0,0,0,0.8) url("${file.url}") center left no-repeat`
                                            });
                                        } else {
                                            window.open(file.url, '_blank');
                                        }
                                    };

                                    filesContainer.appendChild(fileCard);
                                } else {
                                    noFilesMessage.style.display = 'block';
                                }
                            } else {
                                noFilesMessage.style.display = 'block';
                            }

                            viewStatusModal.show();
                        } else {
                            showToast(data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('An error occurred', 'error');
                    });
            }

            function deleteLeave(leaveId) {
                Swal.fire({
                    title: 'Cancel Leave?',
                    text: "Are you sure you want to cancel this leave?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, cancel it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/dashboard/employees/leave/delete/${leaveId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    showToast('Leave cancelled successfully', 'success');
                                    fetchLeaves();
                                } else {
                                    showToast(data.message, 'error');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                showToast('An error occurred', 'error');
                            });
                    }
                });
            }

            function applyFilters() {
                fetchLeaves();
                checkFiltersStatus();
            }

            function checkFiltersStatus() {
                const hasActiveFilters = activeFilters.dateRange || activeFilters.status;
                clearFiltersBtnContainer.style.display = hasActiveFilters ? 'block' : 'none';
            }

            function clearAllFilters() {
                statusFilter.value = '';
                statusFilterText.textContent = 'All Status';
                document.getElementById('dateRangeText').textContent = 'All Dates';
                filterDateRangePicker.clear();

                activeFilters = {
                    dateRange: '',
                    status: ''
                };

                clearFiltersBtnContainer.style.display = 'none';
                fetchLeaves();
            }

            function updatePaginationInfo(totalEntries) {
                const start = (currentLeavePage - 1) * entriesPerPage + 1;
                const end = Math.min(currentLeavePage * entriesPerPage, totalEntries);
                const totalPages = Math.ceil(totalEntries / entriesPerPage);

                leaveShowingInfo.textContent = `Showing ${start} to ${end} of ${totalEntries} entries`;
                leavePageInfo.textContent = `Page ${currentLeavePage} of ${totalPages}`;

                prevLeavePageBtn.disabled = currentLeavePage === 1;
                nextLeavePageBtn.disabled = currentLeavePage === totalPages || totalEntries === 0;
            }

            // STEP 2: Enhanced holiday checking with multiple types - FIXED DISPLAY
            async function checkForUnavailableOnDate(employeeId, date, containerId) {
                if (!date) {
                    document.getElementById(containerId).innerHTML = '';
                    return;
                }

                try {
                    const response = await fetch(
                        `/dashboard/employees/holidays/checks?date=${date}&employee_id=${employeeId}`);
                    const data = await response.json();

                    const container = document.getElementById(containerId);
                    if (data.is_holiday && data.reasons && data.reasons.length > 0) {
                        // Remove duplicates and format nicely
                        const uniqueReasons = [...new Set(data.reasons)];
                        const uniqueHolidayNames = data.holiday_names ? [...new Set(data.holiday_names)] : [];

                        let message = `${uniqueReasons.join(', ')}`;
                        if (uniqueHolidayNames.length > 0) {
                            // Only add holiday names if they provide additional information
                            const formattedNames = uniqueHolidayNames.filter(name =>
                                !uniqueReasons.includes(name)
                            );
                            if (formattedNames.length > 0) {
                                message += ` - ${formattedNames.join(', ')}`;
                            }
                        }
                        message += ' (Leave may not be approved)';

                        container.innerHTML =
                            `<div class="text-warning small"><i class="bi bi-exclamation-triangle"></i> ${message}</div>`;
                    } else {
                        container.innerHTML = '';
                    }
                } catch (error) {
                    console.error('Error checking unavailable date:', error);
                }
            }

            // Enhanced function to check for all unavailable dates in range - FIXED DISPLAY
            async function checkForUnavailableDatesInRange(employeeId, startDate, endDate, containerId) {
                if (!startDate || !endDate) {
                    document.getElementById(containerId).innerHTML = '';
                    return;
                }

                try {
                    const response = await fetch(
                        `/dashboard/employees/holidays/employee?employee_id=${employeeId}&start=${startDate}&end=${endDate}`
                    );
                    const unavailableDates = await response.json();

                    const container = document.getElementById(containerId);
                    if (Array.isArray(unavailableDates) && unavailableDates.length > 0) {
                        let html =
                            '<div class="text-warning small"><i class="bi bi-exclamation-triangle"></i> Unavailable dates in range:';
                        html += '<ul class="mb-0 ps-3">';

                        unavailableDates.forEach(item => {
                            const date = new Date(item.date);
                            const formattedDate = date.toLocaleDateString('en-US', {
                                day: '2-digit',
                                month: 'short',
                                year: 'numeric'
                            });

                            // Format reasons nicely without duplicates
                            let displayReason = item.reason || item.type;
                            if (item.name && item.name !== displayReason) {
                                displayReason += ` - ${item.name}`;
                            }

                            html += `<li>${formattedDate} - ${displayReason}</li>`;
                        });

                        html += '</ul><small class="text-muted">(Leave may not be approved)</small></div>';
                        container.innerHTML = html;
                    } else {
                        container.innerHTML = '';
                    }
                } catch (error) {
                    console.error('Error checking unavailable dates:', error);
                }
            }

            // Also update the isDateUnavailable function to return cleaner data
            async function isDateUnavailable(date, employeeId) {
                // Basic date validation
                if (!date || isNaN(new Date(date).getTime())) {
                    return {
                        unavailable: true,
                        reason: 'Invalid date'
                    };
                }

                // Check if it's Sunday (0 = Sunday)
                const dateObj = new Date(date);
                if (dateObj.getDay() === 0) {
                    return {
                        unavailable: true,
                        reason: 'Sunday'
                    };
                }

                // Check if it's a holiday, day-off, or any other unavailable type
                try {
                    const holidayResponse = await fetch(
                        `/dashboard/employees/holidays/checks?date=${date}&employee_id=${employeeId}`);

                    if (!holidayResponse.ok) {
                        throw new Error(`Holiday check failed: ${holidayResponse.status}`);
                    }

                    const holidayData = await holidayResponse.json();

                    if (holidayData.is_holiday) {
                        // Remove duplicates from reasons
                        const uniqueReasons = [...new Set(holidayData.reasons)];
                        const uniqueHolidayNames = holidayData.holiday_names ? [...new Set(holidayData
                            .holiday_names)] : [];

                        return {
                            unavailable: true,
                            reason: uniqueReasons.join(', '),
                            holidayName: uniqueHolidayNames.join(', ')
                        };
                    }
                } catch (error) {
                    console.error('Error checking holiday:', error);
                    // Continue with other checks even if holiday check fails
                }

                return {
                    unavailable: false
                };
            }
            async function checkShifts() {
                const employeeId = document.querySelector('input[name="employee_id"]').value;
                const duration = document.querySelector('input[name="duration"]:checked').value;

                if (duration == 1 || duration == 3 || duration == 4) {
                    // Single day check
                    const date = document.getElementById('leaveDate').value;
                    if (!date) return true;

                    try {
                        const response = await fetch(
                            `/dashboard/employees/shift/check?employee_id=${employeeId}&date=${date}`);
                        const data = await response.json();

                        if (!data.exists) {
                            showToast(`Cannot assign leave - no shift assigned for ${data.formatted_date}`,
                                'error');
                            return false;
                        }
                        return true;
                    } catch (error) {
                        console.error('Error checking shift:', error);
                        showToast('Error checking shift assignment', 'error');
                        return false;
                    }
                } else {
                    // Multiple days check
                    const fromDate = document.getElementById('date_range_from').value;
                    const toDate = document.getElementById('date_range_to').value;

                    if (!fromDate || !toDate) return true;

                    try {
                        const dates = getDatesBetween(new Date(fromDate), new Date(toDate));
                        let missingDates = [];

                        for (const date of dates) {
                            const response = await fetch(
                                `/dashboard/employees/shift/check?employee_id=${employeeId}&date=${date}`);
                            const data = await response.json();

                            if (!data.exists) {
                                missingDates.push(data.formatted_date);
                            }
                        }

                        if (missingDates.length > 0) {
                            showToast(`Cannot assign leave - no shift assigned for: ${missingDates.join(', ')}`,
                                'error');
                            return false;
                        }
                        return true;
                    } catch (error) {
                        console.error('Error checking shifts:', error);
                        showToast('Error checking shift assignments', 'error');
                        return false;
                    }
                }
            }

            function getDatesBetween(startDate, endDate) {
                const dates = [];
                let currentDate = new Date(startDate);

                while (currentDate <= endDate) {
                    dates.push(formatDateForStorage(currentDate));
                    currentDate.setDate(currentDate.getDate() + 1);
                }

                return dates;
            }

            function formatDateForStorage(date) {
                return date.toISOString().split('T')[0];
            }

            function formatDateForDisplay(dateString) {
                const options = {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                };
                return new Date(dateString).toLocaleDateString(undefined, options);
            }

            function formatDateWithDay(dateStr) {
                const date = new Date(dateStr);
                const options = {
                    weekday: 'long'
                };

                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const year = date.getFullYear();
                const weekday = date.toLocaleDateString('en-US', options);

                return `${day}-${month}-${year} (${weekday})`;
            }

            function getFileIconClass(filename) {
                const extension = filename.split('.').pop().toLowerCase();
                const iconClasses = {
                    'pdf': 'bi bi-file-earmark-pdf-fill text-danger',
                    'xls': 'bi bi-file-earmark-excel-fill text-success',
                    'xlsx': 'bi bi-file-earmark-excel-fill text-success',
                    'jpg': 'bi bi-file-image-fill text-info',
                    'jpeg': 'bi bi-file-image-fill text-info',
                    'png': 'bi bi-file-image-fill text-info',
                    'csv': 'bi bi-file-earmark-text-fill text-success',
                };

                return iconClasses[extension] || 'bi bi-file-earmark-fill text-secondary';
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
        /* Your existing CSS styles remain the same */
        .leave-planner-container {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
        }

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
            background: white;
            border: 1px solid #ced4da;
            border-radius: 6px;
            padding: 0.375rem 0.75rem;
            cursor: pointer;
        }

        .table {
            margin-bottom: 0;
        }

        .badge {
            font-weight: 500;
            padding: 0.35em 0.65em;
            font-size: 0.75em;
        }

        .bg-warning {
            background-color: #ffc003 !important;
        }

        .bg-success {
            background-color: #28a745 !important;
        }

        .bg-danger {
            background-color: #dc3545 !important;
        }

        .bg-secondary {
            background-color: #6c757d !important;
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

        .leavelen {
            max-width: 1200px;
        }

        .form-check-input:checked {
            background-color: #4dabf7;
            border-color: #4dabf7;
        }

        /* Date Range Picker Styles */
        .date-range-picker-container {
            position: absolute;
            z-index: 1000;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            width: 600px;
        }

        .date-range-calendar {
            display: flex;
            gap: 20px;
        }

        .month-container {
            width: 280px;
        }

        .month-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 5px 0;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .month-nav-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px;
            border-radius: 4px;
        }

        .month-nav-btn:hover {
            background-color: #f1f3f5;
        }

        .weekdays {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            text-align: center;
            font-size: 0.75rem;
            color: #6c757d;
            margin-bottom: 5px;
        }

        .days-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
        }

        .day {
            padding: 5px;
            text-align: center;
            cursor: pointer;
            border-radius: 4px;
            font-size: 0.875rem;
        }

        .day:hover {
            background-color: #f1f3f5;
        }

        .day.disabled {
            color: #adb5bd;
            cursor: not-allowed;
        }

        .day.today {
            font-weight: bold;
            color: #4dabf7;
        }

        .day.selected {
            color: #f00606;
        }

        .day.in-range {
            background-color: #bae2ff;
        }

        .date-range-footer {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #e9ecef;
        }

        .viewmodal {
            max-width: 800px;
        }

        /* Status badges */
        .badge-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .badge-approved {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-rejected {
            background-color: #f8d7da;
            color: #721c24;
        }

        /* File cards */
        .file-card {
            width: 120px;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.2s;
        }

        .file-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .file-card-img {
            height: 80px;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
        }

        .file-card-body {
            padding: 0.5rem;
            background: white;
        }

        .file-name {
            font-size: 0.75rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</x-layout>
