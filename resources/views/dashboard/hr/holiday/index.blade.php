<x-layout>
    @section('title', 'Holiday')

    <div class="container-fluid p-4">
        <x-message />

        <div class="holiday-container">
            <!-- Header Section -->
            <div class="row justify-content-between align-items-center mb-4" style="margin-top: 2em">
                <h4 class="mb-0 fw-semibold fs-5"><i class="bi bi-calendar-event me-2"></i> Holiday Calendar</h4>

                <div class="col-auto mt-4">
                    <div class="row justify-content-start">
                        <div class="col-auto" style=" margin-top: 1.3em;">
                            <div class="dropdown">
                                <button class="dropdown-toggle control-select" type="button" id="departmentDropdown"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <span id="departmentFilterText">All Departments</span>
                                </button>
                                <ul class="dropdown-menu department-dropdown p-2" aria-labelledby="departmentDropdown"
                                    style="width: 200px;">
                                    <li>
                                        <input type="text" class="form-control form-control-sm mb-2"
                                            placeholder="Search departments..." id="departmentSearch">
                                    </li>
                                    <li>
                                        <select id="departmentFilter" class="form-select form-select-sm" size="8"
                                            style="width: 100%; border: none;">
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
                            <button type="button" class="btn btn-light p-2" id="clearFiltersBtn"
                                style="display: none;">
                                Clear Filters
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-auto mt-4">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMultiHolidayModal">
                        <i class="bi bi-plus-circle me-2"></i> Add Holidays
                    </button>
                </div>
            </div>

            <!-- View Selector and Navigation -->
            <div class="row mb-4 justify-content-between align-items-center" style="margin-top: 3.5em">
                <div class="col-md-auto">
                    <div class="d-flex align-items-center gap-2">
                        <button class="btn btn-sm btn-outline-secondary rounded-circle p-2" id="prev-period">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" id="today">
                            Today
                        </button>
                        <button class="btn btn-sm btn-outline-secondary rounded-circle p-2" id="next-period">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>

                <div class="col-md-auto">
                    <h5 class="mb-0 me-3 fw-semibold" id="current-period-display">July 2025</h5>
                </div>

                <div class="col-md-auto">
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-primary view-btn active" data-view="month">
                            <i class="bi bi-calendar-month me-1"></i> Month
                        </button>
                        <button class="btn btn-outline-primary view-btn" data-view="week">
                            <i class="bi bi-calendar-week me-1"></i> Week
                        </button>
                        <button class="btn btn-outline-primary view-btn" data-view="day">
                            <i class="bi bi-calendar-day me-1"></i> Day
                        </button>
                        <button class="btn btn-outline-primary view-btn" data-view="list">
                            <i class="bi bi-list-ul me-1"></i> List
                        </button>
                    </div>
                </div>
            </div>

            <!-- Calendar Views -->
            <div class="calendar-views-container position-relative" style="min-height: 600px;">
                <!-- Month View -->
                <div class="calendar-view active-view" id="month-view">
                    <div class="table-responsive rounded-3">
                        <table class="table table-bordered mb-0" id="holiday-calendar">
                            <thead>
                                <tr class="bg-light">
                                    <th class="text-center p-3">Mon</th>
                                    <th class="text-center p-3">Tue</th>
                                    <th class="text-center p-3">Wed</th>
                                    <th class="text-center p-3">Thu</th>
                                    <th class="text-center p-3">Fri</th>
                                    <th class="text-center p-3">Sat</th>
                                    <th class="text-center p-3">Sun</th>
                                </tr>
                            </thead>
                            <tbody id="calendar-body" class="calendar-transition">
                                <!-- Calendar days will be populated here by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Week View -->
                <div class="calendar-view" id="week-view">
                    <div class="table-responsive rounded-3">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr class="bg-light">
                                    <th class="text-center p-2" style="width: 80px;">Time</th>
                                    <th class="text-center p-2">Monday</th>
                                    <th class="text-center p-2">Tuesday</th>
                                    <th class="text-center p-2">Wednesday</th>
                                    <th class="text-center p-2">Thursday</th>
                                    <th class="text-center p-2">Friday</th>
                                    <th class="text-center p-2">Saturday</th>
                                    <th class="text-center p-2">Sunday</th>
                                </tr>
                            </thead>
                            <tbody id="week-body" class="calendar-transition">
                                <!-- Week view will be populated here -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Day View -->
                <div class="calendar-view" id="day-view">
                    <div class="table-responsive rounded-3">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr class="bg-light">
                                    <th class="text-center p-2" style="width: 80px;">Time</th>
                                    <th class="text-center p-2" id="day-view-date">Monday, July 1, 2025</th>
                                </tr>
                            </thead>
                            <tbody id="day-body" class="calendar-transition">
                                <!-- Day view will be populated here -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- List View -->
                <!-- List View -->
                <div class="calendar-view" id="list-view">
                    <div class="card border-0">
                        <div class="card-header bg-light border-0 py-3">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h5 class="mb-0 fw-semibold">Holiday List</h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="row justify-content-end">
                                        <div class="col-auto">
                                            <select class="form-select form-select-sm" id="list-filter">
                                                <option value="all">All Holidays</option>
                                                <option value="local">Local Holidays</option>
                                                <option value="national">National Holidays</option>
                                                <option value="religious">Religious Holidays</option>
                                            </select>
                                        </div>
                                        <div class="col-auto">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">Show</span>
                                                <select class="form-select" id="entries-per-page">
                                                    <option value="5" selected>5</option>
                                                    <option value="10">10</option>
                                                    <option value="25">25</option>
                                                    <option value="50">50</option>
                                                    <option value="75">75</option>
                                                    <option value="100">100</option>
                                                </select>
                                                <span class="input-group-text">entries</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">Name</th>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th class="pe-4 text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="list-body" class="calendar-transition">
                                        <!-- List view will be populated here -->
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-between align-items-center p-3 border-top">
                                <div class="text-muted small" id="showing-entries">
                                    Showing 1 to 5 of 0 entries
                                </div>
                                <nav>
                                    <ul class="pagination pagination-sm mb-0" id="pagination">
                                        <li class="page-item disabled">
                                            <a class="page-link" href="#" id="prev-page">Previous</a>
                                        </li>
                                        <li class="page-item active">
                                            <a class="page-link" href="#" data-page="1">1</a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link" href="#" id="next-page">Next</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Single Holiday Modal -->
        <div class="modal fade" id="addSingleHolidayModal" tabindex="-1"
            aria-labelledby="addSingleHolidayModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered single-holiday-form">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fs-5 fw-semibold" id="addSingleHolidayModalLabel">Add Holiday</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form id="single-holiday-form" data-edit-id="">
                            <input type="hidden" id="route-update-template"
                                value="{{ route('holiday.update', ['id' => '__ID__']) }}">
                            <input type="hidden" id="route-store" value="{{ route('holidaystore') }}">
                            @csrf

                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="dropdown">
                                        <label class="form-label fw-semibold">Department </label>
                                        <button class="dropdown-toggle control-select" type="button"
                                            id="singleholiday-DepDropdown" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <span id="singleholiday-DepFilterText">All Departments</span>
                                        </button>
                                        <ul class="dropdown-menu department-dropdown p-2"
                                            aria-labelledby="singleholiday-DepDropdown" style="width: 300px;">
                                            <li>
                                                <input type="text" class="form-control form-control-sm mb-2"
                                                    placeholder="Search departments..." id="singleholiday-DepSearch">
                                            </li>
                                            <li class="d-flex justify-content-between px-2 mb-2">
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    id="singleholidaySelectAllDepartments">Select All</button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                                    id="singleholidayDeselectAllDepartments">Deselect All</button>
                                            </li>
                                            <li>
                                                <select id="singleholiday-DepFilter"
                                                    class="form-select form-select-sm" size="8" multiple
                                                    name="holiday_department[]">
                                                    @foreach ($departments as $department)
                                                        <option value="{{ $department->dep_id }}" class="  pb-2">
                                                            {{ $department->dep_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </li>
                                        </ul>
                                    </div>
                                </div>


                            </div>
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Date </label>
                                    <input type="date" class="form-control " id="single-holiday-date"
                                        name="holiday_date" required readonly>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Holiday Type</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="holiday-type-search"
                                            placeholder="Search holiday type..." name="holidaytype_name">
                                        <input type="hidden" id="holiday-type-id" name="holiday_type">
                                    </div>
                                    <ul id="holiday-type-list" class="list-group holiday-type-dropdown">
                                        <!-- Search results will appear here -->
                                    </ul>
                                </div>
                                <div class=" col-md-4">
                                    <label class="form-label fw-semibold">Occasion</label>
                                    <input type="text" class="form-control " id="single-holiday-occasion"
                                        name="holiday_occasion" placeholder="Enter occasion" required>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer border-0 pt-0 justify-content-end">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="button" class="btn btn-primary" id="saveSingleHoliday">
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Multiple Holidays Modal -->
        <div class="modal fade" id="addMultiHolidayModal" tabindex="-1" aria-labelledby="addMultiHolidayModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered holiday-form">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fs-5 fw-semibold" id="addMultiHolidayModalLabel">Add Multiple Holidays
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form id="multi-holiday-form">
                            @csrf
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="dropdown">
                                        <label class="form-label fw-semibold">Department </label>
                                        <button class="dropdown-toggle control-select" type="button"
                                            id="multiholiday-DepDropdown" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <span id="multiholiday-DepFilterText">All Departments</span>
                                        </button>
                                        <ul class="dropdown-menu department-dropdown p-2"
                                            aria-labelledby="multiholiday-DepDropdown" style="width: 300px;">
                                            <li>
                                                <input type="text" class="form-control form-control-sm mb-2"
                                                    placeholder="Search departments..." id="multiholiday-DepSearch">
                                            </li>
                                            <li class="d-flex justify-content-between px-2 mb-2">
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    id="multiholidaySelectAllDepartments">Select All</button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                                    id="multiholidayDeselectAllDepartments">Deselect All</button>
                                            </li>
                                            <li>
                                                <select id="multiholiday-DepFilter" class="form-select form-select-sm"
                                                    size="8" multiple name="holiday_department[]">
                                                    @foreach ($departments as $department)
                                                        <option value="{{ $department->dep_id }}" class="  pb-2">
                                                            {{ $department->dep_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </li>
                                        </ul>
                                    </div>
                                </div>


                            </div>

                            <div id="multiHolidayEntriesContainer">
                                <!-- Initial holiday entry -->
                                <div class="holiday-entry mb-4">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label fw-semibold">Date </label>
                                                <input type="date" class="form-control "
                                                    name="multi_holiday_date[]" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label fw-semibold">Occasion </label>
                                                <input type="text" class="form-control "
                                                    name="multi_holiday_occasion[]" placeholder="Enter occasion"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label fw-semibold">Holiday Type</label>
                                                <div class="input-group">
                                                    <input type="text"
                                                        class="form-control multi-holiday-type-search"
                                                        placeholder="Search holiday type..."
                                                        name="multi_holiday_type_name[]">
                                                    <input type="hidden" class="multi-holiday-type-id"
                                                        name="multi_holiday_type[]">
                                                </div>
                                                <ul class="holiday-type-dropdown list-group multi-holiday-type-list"
                                                    id="multiple-type-list">
                                                    <!-- Search results will appear here -->
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                style="margin-top: 2.5em" id="addMultiHolidayEntry">
                                                <i class="bi bi-plus"></i> Add
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer border-0 pt-0 justify-content-end">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="button" class="btn btn-primary" id="saveMultiHolidays">
                            Save All
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Holiday Details Modal -->
        <div class="modal fade" id="holidayDetailsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered details-holiday">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fs-5 fw-semibold" id="holidayDetailsTitle">Holiday Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-1" id="holiday-details-content">
                        <!-- Details will be populated here -->
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-outline-secondary btn-sm"
                            data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger btn-sm" id="delete-holiday">Delete</button>
                        <button type="button" class="btn btn-primary btn-sm" id="edit-holiday">Edit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentDate = new Date();
            let currentMonth = currentDate.getMonth();
            let currentYear = currentDate.getFullYear();
            let currentDay = currentDate.getDate();
            let currentWeekStart = getWeekStartDate(currentDate);
            let currentView = 'month';
            let holidays = [];
            let holidayEntryCount = 1;
            let clickedDate = null;
            let editingHolidayId = null;



            // New filter variables
            const departmentFilterElement = document.getElementById('departmentFilter');


            let departmentFilter = 'all';


            // Initialize the calendar
            initCalendar();

            function initCalendar() {
                fetchHolidays().then(() => {
                    renderView();
                    updatePeriodDisplay();
                });
            }

            // Fetch holidays from the server with filters
        async function fetchHolidays() {
    try {
        // For month view, fetch 3 months (previous, current, next)
        const startDate = new Date(currentYear, currentMonth - 1, 1);
        const endDate = new Date(currentYear, currentMonth + 2, 0);

        // For week view, fetch 2 weeks
        if (currentView === 'week') {
            startDate.setDate(currentWeekStart.getDate() - 7);
            endDate.setDate(currentWeekStart.getDate() + 14);
        }

        // For day view, fetch 7 days
        if (currentView === 'day') {
            startDate.setDate(currentDay - 3);
            endDate.setDate(currentDay + 3);
        }

        // For list view, fetch 1 year
        if (currentView === 'list') {
            startDate.setFullYear(currentYear - 1);
            endDate.setFullYear(currentYear + 1);
        }

        const start = startDate.toISOString().split('T')[0];
        const end = endDate.toISOString().split('T')[0];

        // Add filter parameters to the request
        const params = new URLSearchParams({
            start: start,
            end: end,
            department: departmentFilter,
        });

        const response = await fetch(`{{ route('holidayget') }}?${params.toString()}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const data = await response.json();

        // Process the data to ensure consistent structure
        holidays = Array.isArray(data) ? data.map(h => {
            // Determine department string
            let department = h.department || h.holiday_department || '';

            // Determine type name
            let typeName = h.type_name;
            if (!typeName) {
                const typeId = parseInt(h.type || h.type_id);
                switch (typeId) {
                    case 1: typeName = 'Local'; break;
                    case 2: typeName = 'National'; break;
                    case 3: typeName = 'Religious'; break;
                    default: typeName = 'Other';
                }
            }

            return {
                id: h.id,
                name: h.name || h.occasion,
                date: h.date,
                type: h.type || h.type_id,
                type_name: typeName,
                department: department,
                holiday_department: h.holiday_department || department,
            };
        }) : [];

        console.log('Fetched holidays:', holidays); // Debug log
        return holidays;
    } catch (error) {
        console.error('Error fetching holidays:', error);
        holidays = [];
        return holidays;
    }
}
       // Add event listeners for filters
departmentFilterElement.addEventListener('change', function() {
    departmentFilter = this.value === 'all' ? 'all' : this.value;
    updateClearButtonVisibility();

    // Update the dropdown text
    const selectedOption = this.options[this.selectedIndex];
    let displayText = "All Departments";
    if (this.value !== "all") {
        displayText = selectedOption.textContent.trim();
    }
    document.getElementById('departmentFilterText').textContent = displayText;

    // Clear current view and re-render
    fetchHolidays().then(() => {
        renderView();
        updatePeriodDisplay();
    });
});

            // Clear filters button
            document.getElementById('clearFiltersBtn').addEventListener('click', function() {
                departmentFilter = 'all';


                // Reset dropdown selections
                document.getElementById('departmentFilter').value = 'all';


                // Update filter text displays
                document.getElementById('departmentFilterText').textContent = 'All Departments';


                // Hide clear button
                this.style.display = 'none';

                // Refresh data
                fetchHolidays().then(() => renderView());
            });

            // Function to update clear button visibility
            function updateClearButtonVisibility() {
                const clearBtn = document.getElementById('clearFiltersBtn');
                if (departmentFilter !== 'all') {
                    clearBtn.style.display = 'block';
                } else {
                    clearBtn.style.display = 'none';
                }
            }

            // Add search functionality for filters
            document.getElementById('departmentSearch').addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const options = document.getElementById('departmentFilter').options;

                for (let i = 0; i < options.length; i++) {
                    const option = options[i];
                    option.style.display = option.text.toLowerCase().includes(searchTerm) ? '' : 'none';
                }
            });


            // Save single holiday
            document.getElementById('saveSingleHoliday').addEventListener('click', async function() {
                const form = document.getElementById('single-holiday-form');
                const formData = new FormData(form);
                const editingId = form.getAttribute('data-edit-id');

                // Validate holiday type
                if (!document.getElementById('holiday-type-id').value) {
                    showToast('Please select a valid holiday type', 'error');
                    return;
                }

                // Add selected departments
                ['Dep'].forEach(prefix => {
                    const select = document.getElementById(`singleholiday-${prefix}Filter`);
                    if (select) {
                        Array.from(select.selectedOptions).forEach((opt, index) => {
                            formData.append(`holiday_${prefix.toLowerCase()}[${index}]`,
                                opt.value);
                        });
                    }
                });

                const url = editingId ?
                    `{{ route('holiday.update', ['id' => '__ID__']) }}`.replace('__ID__', editingId) :
                    '{{ route('holidaystore') }}';

                if (editingId) {
                    formData.append('_method', 'PUT');
                }

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Request failed');
                    }

                    showToast('Holiday saved successfully!', 'success');
                    window.location.reload();
                } catch (error) {
                    console.error('Error:', error);
                    showToast('Error: ' + error.message, 'error');
                }
            });

            document.getElementById('edit-holiday').addEventListener('click', function() {
                const holidayId = this.dataset.holidayId;
                const holiday = holidays.find(h => h.id == holidayId);

                if (holiday) {
                    // Reset form
                    const form = document.getElementById('single-holiday-form');
                    form.reset();

                    // Set basic fields
                    document.getElementById('single-holiday-date').value = holiday.date;
                    document.getElementById('single-holiday-occasion').value = holiday.name;
                    document.getElementById('holiday-type-search').value = holiday.type_name || '';
                    document.getElementById('holiday-type-id').value = holiday.type;

                    // Set edit ID
                    form.setAttribute('data-edit-id', holiday.id);

                    // Set department selections
                    const depSelect = document.getElementById('singleholiday-DepFilter');
                    if (holiday.department) {
                        const deptIds = holiday.department.split(',');
                        Array.from(depSelect.options).forEach(option => {
                            option.selected = deptIds.includes(option.value);
                        });
                        updateSelectedText('singleholiday-DepFilter', 'singleholiday-DepFilterText');
                    }



                    // Show modal
                    $('#holidayDetailsModal').modal('hide');
                    $('#addSingleHolidayModal').modal('show');
                }
            });

            // Update the saveMultiHolidays function

            document.getElementById('saveMultiHolidays').addEventListener('click', async function() {
                const form = document.getElementById('multi-holiday-form');
                const formData = new FormData(form);
                const saveBtn = this;

                // Validate holiday types
                const holidayTypeInputs = form.querySelectorAll('.holiday-type-id');
                let allTypesValid = true;

                holidayTypeInputs.forEach(input => {
                    if (!input.value) {
                        allTypesValid = false;
                        const searchInput = input.previousElementSibling;
                        searchInput.classList.add('is-invalid');

                        if (!searchInput.nextElementSibling.querySelector(
                                '.invalid-feedback')) {
                            const errorDiv = document.createElement('div');
                            errorDiv.className = 'invalid-feedback';
                            errorDiv.textContent = 'Please select a valid holiday type';
                            searchInput.parentNode.appendChild(errorDiv);
                        }
                    }
                });

                if (!allTypesValid) {
                    showToast('Please select valid holiday types for all entries', 'error');
                    return;
                }

                try {
                    saveBtn.innerHTML = '<i class="bi bi-arrow-repeat me-2 spinner"></i> Saving...';
                    saveBtn.disabled = true;

                    const response = await fetch('{{ route('holidaymultiple') }}', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (data.success) {
                        showToast('Holidays saved successfully!', 'success');

                        // Reset form and UI
                        saveBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i> Saved!';
                        saveBtn.classList.add('btn-success');

                        // Wait a bit before closing and reloading
                        setTimeout(() => {
                            $('#addMultiHolidayModal').modal('hide');
                            form.reset();
                            fetchHolidays().then(() => renderView());

                            // Reset button state after modal is closed
                            setTimeout(() => {
                                saveBtn.innerHTML = 'Save All';
                                saveBtn.classList.remove('btn-success');
                                saveBtn.disabled = false;
                            }, 300);
                        }, 1500);
                    } else {
                        throw new Error(data.message || 'Failed to save holidays');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showToast('Error: ' + error.message, 'error');
                    saveBtn.innerHTML = 'Save All';
                    saveBtn.disabled = false;
                }
            });

            // Delete holiday
            document.getElementById('delete-holiday').addEventListener('click', function() {
                const holidayId = this.dataset.holidayId;
                const deleteBtn = this;
                deleteBtn.innerHTML = '<i class="bi bi-trash me-2"></i> Deleting...';
                deleteBtn.disabled = true;

                fetch(`{{ route('holidaylist') }}/${holidayId}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast('Holiday deleted successfully!', 'success');
                            deleteBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i> Deleted!';
                            setTimeout(() => {
                                location.reload();
                            }, 100);
                        } else {
                            showToast('Error: ' + data.message, 'error');
                            deleteBtn.innerHTML = '<i class="bi bi-trash me-2"></i> Delete';
                            deleteBtn.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('An error occurred while deleting the holiday', 'error');
                        deleteBtn.innerHTML = '<i class="bi bi-trash me-2"></i> Delete';
                        deleteBtn.disabled = false;
                    });
            });

            // Add multiple holiday entries
            document.getElementById('addMultiHolidayEntry').addEventListener('click', function() {
                holidayEntryCount++;

                const newEntry = document.createElement('div');
                newEntry.className = 'holiday-entry mb-4';
                newEntry.innerHTML = `
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label fw-semibold">Date </label>
                        <input type="date" class="form-control" name="multi_holiday_date[]" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label fw-semibold">Occasion </label>
                        <input type="text" class="form-control" name="multi_holiday_occasion[]" placeholder="Enter occasion" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group position-relative">
                        <label class="form-label fw-semibold">Holiday Type</label>
                        <div class="input-group">
                            <input type="text" class="form-control multi-holiday-type-search"
                                placeholder="Search holiday type..."
                                name="multi_holiday_type_name[]">
                            <input type="hidden" class="multi-holiday-type-id"
                                name="multi_holiday_type[]">
                        </div>
                        <ul class="list-group holiday-type-dropdown multi-holiday-type-list"
                            style="position: absolute; z-index: 1000; width: 100%; max-height: 200px; overflow-y: auto; display: none;">
                        </ul>
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-multi-holiday" style="margin-top: 2.5em;">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        `;

                document.getElementById('multiHolidayEntriesContainer').appendChild(newEntry);

                // Initialize the holiday type search for this new entry
                initHolidayTypeSearchForEntry(newEntry);
            });

            // Remove holiday entry
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-multi-holiday') || e.target.closest(
                        '.remove-multi-holiday')) {
                    const btn = e.target.classList.contains('remove-multi-holiday') ? e.target : e.target
                        .closest('.remove-multi-holiday');
                    const entry = btn.closest('.holiday-entry');

                    if (document.querySelectorAll('.holiday-entry').length > 1) {
                        entry.remove();
                        holidayEntryCount--;
                    }
                }
            });

            function updateSelectedText(selectId, textId) {
                const select = document.getElementById(selectId);
                const selected = Array.from(select.selectedOptions);
                const textElement = document.getElementById(textId);

                if (selected.length === 0) {
                    textElement.textContent = textId.includes('Dep') ? 'All Departments' : '';

                } else if (selected.length > 1) {
                    textElement.textContent = `${selected.length} selected`;
                } else {
                    textElement.textContent = selected.map(opt => opt.text).join(', ');
                }
            }

            // Initialize dropdown functionality
            function initDropdowns() {
                // Department dropdown
                document.getElementById('singleholiday-DepFilter').addEventListener('change', function() {
                    updateSelectedText('singleholiday-DepFilter', 'singleholiday-DepFilterText');
                });

                document.getElementById('singleholidaySelectAllDepartments').addEventListener('click', function() {
                    const select = document.getElementById('singleholiday-DepFilter');
                    Array.from(select.options).forEach(opt => opt.selected = true);
                    updateSelectedText('singleholiday-DepFilter', 'singleholiday-DepFilterText');
                });

                document.getElementById('singleholidayDeselectAllDepartments').addEventListener('click',
                    function() {
                        const select = document.getElementById('singleholiday-DepFilter');
                        Array.from(select.options).forEach(opt => opt.selected = false);
                        updateSelectedText('singleholiday-DepFilter', 'singleholiday-DepFilterText');
                    });

                document.getElementById('singleholiday-DepSearch').addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const options = document.getElementById('singleholiday-DepFilter').options;

                    for (let i = 0; i < options.length; i++) {
                        const option = options[i];
                        option.style.display = option.text.toLowerCase().includes(searchTerm) ? '' : 'none';
                    }
                });



                // Multiple holiday dropdowns (similar setup)
                // Department
                document.getElementById('multiholiday-DepFilter').addEventListener('change', function() {
                    updateSelectedText('multiholiday-DepFilter', 'multiholiday-DepFilterText');
                });

                document.getElementById('multiholidaySelectAllDepartments').addEventListener('click', function() {
                    const select = document.getElementById('multiholiday-DepFilter');
                    Array.from(select.options).forEach(opt => opt.selected = true);
                    updateSelectedText('multiholiday-DepFilter', 'multiholiday-DepFilterText');
                });

                document.getElementById('multiholidayDeselectAllDepartments').addEventListener('click', function() {
                    const select = document.getElementById('multiholiday-DepFilter');
                    Array.from(select.options).forEach(opt => opt.selected = false);
                    updateSelectedText('multiholiday-DepFilter', 'multiholiday-DepFilterText');
                });

                document.getElementById('multiholiday-DepSearch').addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const options = document.getElementById('multiholiday-DepFilter').options;

                    for (let i = 0; i < options.length; i++) {
                        const option = options[i];
                        option.style.display = option.text.toLowerCase().includes(searchTerm) ? '' : 'none';
                    }
                });



            }

            // Initialize dropdowns when page loads
            initDropdowns();



            // Update renderMonthView to handle shift types
            async function renderMonthView(month, year) {
                const calendarBody = document.getElementById('calendar-body');
                calendarBody.innerHTML = '';

                const firstDay = new Date(year, month, 1);
                const lastDay = new Date(year, month + 1, 0);
                const daysInMonth = lastDay.getDate();
                const startingDay = firstDay.getDay();
                const adjustedStartingDay = startingDay === 0 ? 6 : startingDay - 1;

                let date = 1;
                let rows = Math.ceil((adjustedStartingDay + daysInMonth) / 7);

                // Set fixed height for all cells
                const cellHeight = '100px';

                for (let i = 0; i < rows; i++) {
                    const row = document.createElement('tr');
                    row.className = 'calendar-row';

                    for (let j = 0; j < 7; j++) {
                        const cell = document.createElement('td');
                        cell.className = 'calendar-day position-relative p-2';
                        cell.style.height = cellHeight;
                        cell.style.overflow = 'hidden';
                        cell.style.position = 'relative';

                        if (i === 0 && j < adjustedStartingDay) {
                            const prevMonthDays = new Date(year, month, 0).getDate();
                            const prevDate = prevMonthDays - (adjustedStartingDay - j - 1);
                            cell.innerHTML = `<div class="day-number text-muted opacity-50">${prevDate}</div>`;
                            cell.classList.add('other-month-day');
                        } else if (date > daysInMonth) {
                            cell.innerHTML =
                                `<div class="day-number text-muted opacity-50">${date - daysInMonth}</div>`;
                            cell.classList.add('other-month-day');
                            date++;
                        } else {
                            const dayNumber = document.createElement('div');
                            dayNumber.className = 'day-number fw-semibold mb-1';
                            dayNumber.textContent = date;
                            cell.appendChild(dayNumber);

                            const today = new Date();
                            if (date === today.getDate() && month === today.getMonth() && year === today
                                .getFullYear()) {
                                cell.classList.add('today-cell');
                                dayNumber.classList.add('today-number');
                            }

                            const currentDate = new Date(year, month, date);
                            const dateStr = formatDateForInput(currentDate);

                            const dayHolidays = holidays ? holidays.filter(h => {
                                const holidayDate = new Date(h.date);
                                return holidayDate.toDateString() === currentDate.toDateString();
                            }) : [];

                            if (dayHolidays.length > 0) {
                                cell.classList.add('holiday-cell');

                                const holidayContainer = document.createElement('div');
                                holidayContainer.className = 'holiday-container';
                                holidayContainer.style.position = 'absolute';
                                holidayContainer.style.top = '1px';
                                holidayContainer.style.bottom = '5px';
                                holidayContainer.style.left = '5px';
                                holidayContainer.style.right = '5px';
                                holidayContainer.style.overflowY = 'auto';

                                dayHolidays.forEach(holiday => {
                                    const holidayIndicator = document.createElement('div');
                                    holidayIndicator.className = 'holiday-indicator rounded-2 p-1 mb-1';
                                    holidayIndicator.setAttribute('data-holiday-id', holiday.id);

                                    const badgeClass = getHolidayTypeBadgeClass(holiday.type);
                                    holidayIndicator.classList.add(
                                        `${badgeClass}-light`,
                                        `text-${badgeClass.replace('bg-', '')}-dark`
                                    );

                                    const holidayName = holiday.name.length > 11 ?
                                        holiday.name.substring(0, 11) + '...' :
                                        holiday.name;

                                    holidayIndicator.innerHTML = `
                            <div class="holiday-name fw-medium text-truncate">${holidayName}</div>
                            <small class="holiday-type">
                                ${holiday.type_name || (holiday.type === '1' ? 'Local' :
                                    holiday.type === '2' ? 'National' :
                                    holiday.type === '3' ? 'Religious' : 'Other')}
                            </small>
                        `;

                                    holidayContainer.appendChild(holidayIndicator);
                                });

                                cell.appendChild(holidayContainer);
                            }

                            cell.addEventListener('click', function() {
                                if (dayHolidays.length > 0) {
                                    showHolidayDetails(dayHolidays[0]);
                                } else {
                                    clickedDate = currentDate;
                                    document.getElementById('single-holiday-date').value = dateStr;
                                    $('#addSingleHolidayModal').modal('show');
                                }
                            });

                            date++;
                        }

                        row.appendChild(cell);
                    }

                    calendarBody.appendChild(row);
                }
            }




            // Update renderWeekView to handle shift types
            async function renderWeekView(startDate) {
                const weekBody = document.getElementById('week-body');
                weekBody.innerHTML = '';

                for (let hour = 8; hour <= 18; hour++) {
                    const row = document.createElement('tr');
                    row.className = 'week-row';

                    const timeCell = document.createElement('td');
                    timeCell.className = 'text-center align-middle text-muted small';
                    timeCell.textContent = `${hour}:00`;
                    row.appendChild(timeCell);

                    for (let i = 0; i < 7; i++) {
                        const dayDate = new Date(startDate);
                        dayDate.setDate(startDate.getDate() + i);

                        const cell = document.createElement('td');
                        cell.className = 'week-day-cell position-relative p-1';

                        const today = new Date();
                        if (dayDate.toDateString() === today.toDateString()) {
                            cell.classList.add('today-cell');
                        }

                        const dayHolidays = holidays ? holidays.filter(h => {
                            const holidayDate = new Date(h.date);
                            return holidayDate.toDateString() === dayDate.toDateString();
                        }) : [];

                        if (dayHolidays.length > 0) {
                            cell.innerHTML = dayHolidays.map(h => {
                                const badgeClass = getHolidayTypeBadgeClass(h.type);
                                const bgClass =
                                    `${badgeClass}-light text-${badgeClass.replace('bg-', '')}-dark`;

                                return `
                        <div class="week-holiday-event ${bgClass} p-1 mb-1 rounded-2 small"
                             data-holiday-id="${h.id}">
                            ${h.name.length > 20 ? h.name.substring(0, 20) + '...' : h.name}
                        </div>
                    `;
                            }).join('');
                        }

                        cell.addEventListener('click', function(e) {
                            if (!e.target.classList.contains('week-holiday-event')) {
                                clickedDate = dayDate;
                                document.getElementById('single-holiday-date').value =
                                    formatDateForInput(dayDate);
                                $('#addSingleHolidayModal').modal('show');
                            }
                        });

                        row.appendChild(cell);
                    }

                    weekBody.appendChild(row);
                }
            }




            // Update renderDayView to handle shift types
            async function renderDayView(date) {
                const dayBody = document.getElementById('day-body');
                dayBody.innerHTML = '';

                document.getElementById('day-view-date').textContent = date.toLocaleDateString(undefined, {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });

                const dayHolidays = holidays ? holidays.filter(h => {
                    const holidayDate = new Date(h.date);
                    return holidayDate.toDateString() === date.toDateString();
                }) : [];

                for (let hour = 8; hour <= 18; hour++) {
                    const row = document.createElement('tr');
                    row.className = 'day-row';

                    const timeCell = document.createElement('td');
                    timeCell.className = 'text-center align-middle text-muted small';
                    timeCell.textContent = `${hour}:00`;
                    row.appendChild(timeCell);

                    const eventCell = document.createElement('td');
                    eventCell.className = 'day-event-cell position-relative p-1';

                    const now = new Date();
                    if (hour === now.getHours() && date.toDateString() === now.toDateString()) {
                        eventCell.classList.add('current-hour-cell');
                    }

                    if (hour === 12) {
                        if (dayHolidays.length > 0) {
                            dayHolidays.forEach(h => {
                                const badgeClass = getHolidayTypeBadgeClass(h.type);
                                const bgClass =
                                    `${badgeClass}-light text-${badgeClass.replace('bg-', '')}-dark`;

                                // Get the proper type name
                                let typeName = h.type_name ||
                                    (h.type === '1' ? 'Local' :
                                        h.type === '2' ? 'National' :
                                        h.type === '3' ? 'Religious' : 'Other');

                                const holidayDiv = document.createElement('div');
                                holidayDiv.className =
                                    `day-holiday-event ${bgClass} p-2 mb-2 rounded-3`;
                                holidayDiv.setAttribute('data-holiday-id', h.id);
                                holidayDiv.innerHTML = `
                        <strong>${h.name}</strong><br>
                        <small>${typeName}</small>
                    `;

                                eventCell.appendChild(holidayDiv);
                            });
                        }
                    }

                    eventCell.addEventListener('click', function(e) {
                        if (!e.target.classList.contains('day-holiday-event') &&
                            !e.target.closest('.day-holiday-event')) {
                            clickedDate = date;
                            document.getElementById('single-holiday-date').value =
                                formatDateForInput(date);
                            $('#addSingleHolidayModal').modal('show');
                        }
                    });

                    row.appendChild(eventCell);
                    dayBody.appendChild(row);
                }
            }
            // Add these variables at the top with your other variables
            let currentPage = 1;
            let entriesPerPage = 5;
            let filteredHolidays = [];

            // Add this event listener with your other event listeners
            document.getElementById('entries-per-page').addEventListener('change', function() {
                entriesPerPage = parseInt(this.value);
                currentPage = 1;
                renderListView();
            });

            // Update your renderListView function
            function renderListView() {
                const listBody = document.getElementById('list-body');
                listBody.classList.add('fade-out');

                setTimeout(() => {
                    listBody.innerHTML = '';

                    const filter = document.getElementById('list-filter').value;
                    filteredHolidays = [...holidays];

                    if (filter !== 'all') {
                        const typeMap = {
                            'local': 1,
                            'national': 2,
                            'religious': 3
                        };
                        const typeToFilter = typeMap[filter];

                        filteredHolidays = holidays.filter(h => {
                            const holidayType = typeof h.type === 'string' ? parseInt(h.type) : h
                                .type;
                            return holidayType === typeToFilter;
                        });
                    }

                    // Sort by date
                    filteredHolidays.sort((a, b) => new Date(a.date) - new Date(b.date));

                    // Pagination logic
                    const totalEntries = filteredHolidays.length;
                    const totalPages = Math.ceil(totalEntries / entriesPerPage);
                    const startIndex = (currentPage - 1) * entriesPerPage;
                    const endIndex = Math.min(startIndex + entriesPerPage, totalEntries);
                    const paginatedHolidays = filteredHolidays.slice(startIndex, endIndex);

                    // Update showing entries text
                    document.getElementById('showing-entries').textContent =
                        `Showing ${startIndex + 1} to ${endIndex} of ${totalEntries} entries`;

                    // Update pagination controls
                    updatePaginationControls(totalPages);

                    if (paginatedHolidays.length === 0) {
                        listBody.innerHTML =
                            `<tr><td colspan="4" class="text-center py-4 text-muted">No holidays found</td></tr>`;
                        return;
                    }

                    paginatedHolidays.forEach(holiday => {
                        const row = document.createElement('tr');
                        row.className = 'list-row';

                        const typeBadgeClass = getHolidayTypeBadgeClass(holiday.type);
                        let typeDisplayName;
                        if (holiday.type_name) {
                            typeDisplayName = holiday.type_name;
                        } else {
                            const typeNum = typeof holiday.type === 'string' ? parseInt(holiday
                                .type) : holiday.type;
                            switch (typeNum) {
                                case 1:
                                    typeDisplayName = 'Local';
                                    break;
                                case 2:
                                    typeDisplayName = 'National';
                                    break;
                                case 3:
                                    typeDisplayName = 'Religious';
                                    break;
                                default:
                                    typeDisplayName = 'Other';
                            }
                        }

                        row.innerHTML = `
                <td class="ps-4 fw-medium">${holiday.name}</td>
                <td>${new Date(holiday.date).toLocaleDateString(undefined, {
                    weekday: 'short',
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                })}</td>
                <td><span class="badge p-2 ${typeBadgeClass}">${typeDisplayName}</span></td>
                <td class="pe-4 text-end">
                    <div class="btn-group btn-group-sm" role="group">
                        <button class="btn btn-primary edit-holiday-btn" data-id="${holiday.id}">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-danger delete-holiday-btn" data-id="${holiday.id}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </td>
            `;
                        listBody.appendChild(row);
                    });

                    listBody.classList.remove('fade-out');
                    listBody.classList.add('fade-in');

                    setTimeout(() => {
                        listBody.classList.remove('fade-in');
                    }, 300);
                }, 200);
            }
            // Add this function to update pagination controls
            function updatePaginationControls(totalPages) {
                const pagination = document.getElementById('pagination');
                pagination.innerHTML = '';

                // Previous button
                const prevLi = document.createElement('li');
                prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
                prevLi.innerHTML = `<a class="page-link" href="#" id="prev-page">Previous</a>`;
                pagination.appendChild(prevLi);

                // Page numbers
                const maxVisiblePages = 5;
                let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
                let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

                if (endPage - startPage + 1 < maxVisiblePages) {
                    startPage = Math.max(1, endPage - maxVisiblePages + 1);
                }

                if (startPage > 1) {
                    const firstLi = document.createElement('li');
                    firstLi.className = 'page-item';
                    firstLi.innerHTML = `<a class="page-link" href="#" data-page="1">1</a>`;
                    pagination.appendChild(firstLi);

                    if (startPage > 2) {
                        const ellipsisLi = document.createElement('li');
                        ellipsisLi.className = 'page-item disabled';
                        ellipsisLi.innerHTML = `<span class="page-link">...</span>`;
                        pagination.appendChild(ellipsisLi);
                    }
                }

                for (let i = startPage; i <= endPage; i++) {
                    const pageLi = document.createElement('li');
                    pageLi.className = `page-item ${i === currentPage ? 'active' : ''}`;
                    pageLi.innerHTML = `<a class="page-link" href="#" data-page="${i}">${i}</a>`;
                    pagination.appendChild(pageLi);
                }

                if (endPage < totalPages) {
                    if (endPage < totalPages - 1) {
                        const ellipsisLi = document.createElement('li');
                        ellipsisLi.className = 'page-item disabled';
                        ellipsisLi.innerHTML = `<span class="page-link">...</span>`;
                        pagination.appendChild(ellipsisLi);
                    }

                    const lastLi = document.createElement('li');
                    lastLi.className = 'page-item';
                    lastLi.innerHTML = `<a class="page-link" href="#" data-page="${totalPages}">${totalPages}</a>`;
                    pagination.appendChild(lastLi);
                }

                // Next button
                const nextLi = document.createElement('li');
                nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
                nextLi.innerHTML = `<a class="page-link" href="#" id="next-page">Next</a>`;
                pagination.appendChild(nextLi);

                // Add event listeners
                document.querySelectorAll('.page-link').forEach(link => {
                    if (link.id === 'prev-page') {
                        link.addEventListener('click', function(e) {
                            e.preventDefault();
                            if (currentPage > 1) {
                                currentPage--;
                                renderListView();
                            }
                        });
                    } else if (link.id === 'next-page') {
                        link.addEventListener('click', function(e) {
                            e.preventDefault();
                            if (currentPage < totalPages) {
                                currentPage++;
                                renderListView();
                            }
                        });
                    } else if (link.dataset.page) {
                        link.addEventListener('click', function(e) {
                            e.preventDefault();
                            currentPage = parseInt(this.dataset.page);
                            renderListView();
                        });
                    }
                });
            }

            // Add this event listener with your other event listeners
            document.getElementById('list-filter').addEventListener('change', function() {
                currentPage = 1;
                if (currentView === 'list') {
                    renderListView();
                }
            });

            function getHolidayTypeBadgeClass(type) {
                // Convert type to number if it's a string
                const typeNum = typeof type === 'string' ? parseInt(type) : type;

                switch (typeNum) {
                    case 1: // Local Holiday
                        return 'bg-primary';
                    case 2: // National Holiday
                        return 'bg-success';
                    case 3: // Religious Holiday
                        return 'bg-warning';
                    default:
                        return 'bg-secondary';
                }
            }

            function showHolidayDetails(holiday) {
                document.getElementById('holidayDetailsTitle').textContent = holiday.name;
                document.getElementById('delete-holiday').dataset.holidayId = holiday.id;
                document.getElementById('edit-holiday').dataset.holidayId = holiday.id;

                // Get department names
                let departmentNames = 'All Departments';
                if (holiday.department) {
                    const depIds = holiday.department.split(',');
                    departmentNames = depIds.map(depId => {
                        const dep = @json($departments->pluck('dep_name', 'dep_id')->toArray())[depId];
                        return dep || `Department ${depId}`;
                    }).join(', ');
                }


                // Set badge class
                const typeBadgeClass = holiday.type === "1" ? 'bg-primary' :
                    holiday.type === "2" ? 'bg-success' : 'bg-warning';

                // Update modal content
                document.getElementById('holiday-details-content').innerHTML = `
            <div class="d-flex align-items-center mb-3">
                <div class="flex-shrink-0">
                    <i class="bi bi-calendar-event fs-1 text-muted"></i>
                </div>
                <div class="flex-grow-1 ms-3 p-3">
                    <span class="badge ${typeBadgeClass} mb-2 p-2">
                        ${holiday.type_name || (holiday.type === "1" ? 'Local' : holiday.type === "2" ? 'National' : 'Religious')}
                    </span>
                    <p class="mb-0 text-muted">
                        <i class="bi bi-calendar-date me-2"></i>
                        ${new Date(holiday.date).toLocaleDateString('en-US', {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        })}
                    </p>
                    <p class="mb-0 text-muted"><i class="bi bi-building me-2"></i>Department: ${departmentNames}</p>

                </div>
            </div>
        `;

                $('#holidayDetailsModal').modal('show');
            }

            // Helper functions
            function getWeekStartDate(date) {
                const day = date.getDay();
                const diff = date.getDate() - day + (day === 0 ? -6 : 1);
                return new Date(date.setDate(diff));
            }

            function updatePeriodDisplay() {
                const monthNames = ["January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"
                ];

                const periodDisplay = document.getElementById('current-period-display');
                periodDisplay.classList.add('fade-out');

                setTimeout(() => {
                    switch (currentView) {
                        case 'month':
                            periodDisplay.textContent =
                                `${monthNames[currentMonth]} ${currentYear}`;
                            break;
                        case 'week':
                            const weekEnd = new Date(currentWeekStart);
                            weekEnd.setDate(weekEnd.getDate() + 6);
                            periodDisplay.textContent =
                                `${currentWeekStart.toLocaleDateString(undefined, { month: 'short', day: 'numeric' })} -
                        ${weekEnd.toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' })}`;
                            break;
                        case 'day':
                            const dayDate = new Date(currentYear, currentMonth, currentDay);
                            periodDisplay.textContent =
                                dayDate.toLocaleDateString(undefined, {
                                    weekday: 'long',
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric'
                                });
                            break;
                        case 'list':
                            periodDisplay.textContent = 'All Holidays';
                            break;
                    }

                    periodDisplay.classList.remove('fade-out');
                    periodDisplay.classList.add('fade-in');

                    setTimeout(() => {
                        periodDisplay.classList.remove('fade-in');
                    }, 300);
                }, 150);
            }

            function formatDateForInput(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            // Event listeners for view changes, navigation, etc.
            document.querySelectorAll('.view-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const newView = this.dataset.view;
                    if (currentView === newView) return;

                    currentView = newView;

                    document.querySelectorAll('.view-btn').forEach(b => b.classList.remove(
                        'active'));
                    this.classList.add('active');

                    const currentActiveView = document.querySelector('.active-view');
                    currentActiveView.classList.remove('active-view');
                    currentActiveView.classList.add('view-exit');

                    setTimeout(() => {
                        currentActiveView.classList.add('d-none');
                        currentActiveView.classList.remove('view-exit');

                        const newViewElement = document.getElementById(
                            `${currentView}-view`);
                        newViewElement.classList.remove('d-none');
                        newViewElement.classList.add('view-enter');

                        setTimeout(() => {
                            newViewElement.classList.add('active-view');
                            newViewElement.classList.remove('view-enter');
                        }, 50);

                        renderView();
                        updatePeriodDisplay();
                    }, 300);
                });
            });

            document.getElementById('prev-period').addEventListener('click', function() {
                switch (currentView) {
                    case 'month':
                        currentMonth--;
                        if (currentMonth < 0) {
                            currentMonth = 11;
                            currentYear--;
                        }
                        break;
                    case 'week':
                        currentWeekStart.setDate(currentWeekStart.getDate() - 7);
                        break;
                    case 'day':
                        currentDay--;
                        if (currentDay < 1) {
                            currentMonth--;
                            if (currentMonth < 0) {
                                currentMonth = 11;
                                currentYear--;
                            }
                            currentDay = new Date(currentYear, currentMonth + 1, 0).getDate();
                        }
                        break;
                }
                renderView();
                updatePeriodDisplay();
            });

            document.getElementById('next-period').addEventListener('click', function() {
                switch (currentView) {
                    case 'month':
                        currentMonth++;
                        if (currentMonth > 11) {
                            currentMonth = 0;
                            currentYear++;
                        }
                        break;
                    case 'week':
                        currentWeekStart.setDate(currentWeekStart.getDate() + 7);
                        break;
                    case 'day':
                        currentDay++;
                        const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
                        if (currentDay > daysInMonth) {
                            currentDay = 1;
                            currentMonth++;
                            if (currentMonth > 11) {
                                currentMonth = 0;
                                currentYear++;
                            }
                        }
                        break;
                }
                renderView();
                updatePeriodDisplay();
            });

            document.getElementById('today').addEventListener('click', function() {
                const today = new Date();
                currentMonth = today.getMonth();
                currentYear = today.getFullYear();
                currentDay = today.getDate();
                currentWeekStart = getWeekStartDate(new Date());

                if (currentView === 'day') {
                    renderDayView(today);
                } else {
                    renderView();
                }
                updatePeriodDisplay();
            });

            document.getElementById('list-filter').addEventListener('change', function() {
                if (currentView === 'list') {
                    renderListView();
                }
            });

            // Click handlers for holiday indicators and buttons
            document.addEventListener('click', function(e) {
                if (e.target.closest('.holiday-indicator')) {
                    const holidayId = parseInt(e.target.closest('.holiday-indicator').dataset.holidayId);
                    const holiday = holidays.find(h => h.id === holidayId);
                    if (holiday) showHolidayDetails(holiday);
                }

                if (e.target.closest('.week-holiday-event')) {
                    const holidayId = parseInt(e.target.closest('.week-holiday-event').dataset.holidayId);
                    const holiday = holidays.find(h => h.id === holidayId);
                    if (holiday) showHolidayDetails(holiday);
                }

                if (e.target.closest('.day-holiday-event')) {
                    const holidayId = parseInt(e.target.closest('.day-holiday-event').dataset.holidayId);
                    const holiday = holidays.find(h => h.id === holidayId);
                    if (holiday) showHolidayDetails(holiday);
                }
                if (e.target.closest('.edit-holiday-btn')) {
                    const btn = e.target.closest('.edit-holiday-btn');
                    const holidayId = parseInt(btn.dataset.id);
                    const holiday = holidays.find(h => h.id === holidayId);

                    if (holiday) {
                        // Reset the form first
                        document.getElementById('single-holiday-form').reset();

                        // Set basic fields
                        document.getElementById('single-holiday-date').value = holiday.date;
                        document.getElementById('single-holiday-occasion').value = holiday.name;

                        // Changed from .val() to .value for plain JavaScript
                        document.getElementById('holiday-type-search').value = holiday.type_name || '';
                        document.getElementById('holiday-type-id').value = holiday.type;

                        // Set departments
                        const depSelect = document.getElementById('singleholiday-DepFilter');
                        if (holiday.department) {
                            const selectedDeps = holiday.department.split(',').map(Number);
                            Array.from(depSelect.options).forEach(opt => {
                                opt.selected = selectedDeps.includes(parseInt(opt.value));
                            });
                        }
                        updateSelectedText('singleholiday-DepFilter', 'singleholiday-DepFilterText');



                        // Set the edit ID and update button text
                        document.getElementById('single-holiday-form').setAttribute('data-edit-id', holiday
                            .id);
                        document.getElementById('saveSingleHoliday').textContent = 'Update';

                        // Show the edit modal
                        $('#holidayDetailsModal').modal('hide');
                        $('#addSingleHolidayModal').modal('show');
                    }
                }
                if (e.target.closest('.delete-holiday-btn')) {
                    const btn = e.target.closest('.delete-holiday-btn');
                    const holidayId = parseInt(btn.dataset.id);
                    const holiday = holidays.find(h => h.id === holidayId);

                    if (holiday) {
                        Swal.fire({
                            title: 'Are you sure?',
                            text: `You are about to delete "${holiday.name}"`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                btn.innerHTML =
                                    '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Deleting...';

                                fetch(`{{ route('holidaylist') }}/${holidayId}`, {
                                        method: 'DELETE',
                                        headers: {
                                            'Accept': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector(
                                                'meta[name="csrf-token"]').content,
                                            'X-Requested-With': 'XMLHttpRequest'
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            showToast('Holiday deleted successfully!',
                                                'success');
                                            fetchHolidays().then(() => renderView());
                                        } else {
                                            showToast('Error: ' + data.message, 'error');
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        showToast(
                                            'An error occurred while deleting the holiday',
                                            'error');
                                    })
                                    .finally(() => {
                                        btn.innerHTML =
                                            '<i class="bi bi-trash me-2"></i> Delete';
                                    });
                            }
                        });
                    }
                }
            });

            // Modal reset handlers
            $('#addSingleHolidayModal').on('hidden.bs.modal', function() {
                document.getElementById('single-holiday-form').reset();
                clickedDate = null;
                editingHolidayId = null;
                const saveBtn = document.getElementById('saveSingleHoliday');
                saveBtn.innerHTML = 'Save';
                saveBtn.classList.remove('btn-success');
                saveBtn.disabled = false;

                // Reset dropdowns
                const depSelect = document.getElementById('singleholiday-DepFilter');
                Array.from(depSelect.options).forEach(opt => opt.selected = false);
                updateSelectedText('singleholiday-DepFilter', 'singleholiday-DepFilterText');


            });

            $('#addMultiHolidayModal').on('hidden.bs.modal', function() {
                document.getElementById('multi-holiday-form').reset();
                const container = document.getElementById('multiHolidayEntriesContainer');
                container.innerHTML = container.children[0].outerHTML;
                const saveBtn = document.getElementById('saveMultiHolidays');
                saveBtn.innerHTML = 'Save All';
                saveBtn.classList.remove('btn-success');
                saveBtn.disabled = false;
                holidayEntryCount = 1;

                // Reset dropdowns
                const depSelect = document.getElementById('multiholiday-DepFilter');
                Array.from(depSelect.options).forEach(opt => opt.selected = false);
                updateSelectedText('multiholiday-DepFilter', 'multiholiday-DepFilterText');


            });

         function renderView() {
    // Clear the view first
    clearCurrentView();

    // Show loading state
    showLoading();

    // Fetch data and then render
    fetchHolidays().then(() => {
        switch (currentView) {
            case 'month':
                renderMonthView(currentMonth, currentYear);
                break;
            case 'week':
                renderWeekView(currentWeekStart);
                break;
            case 'day':
                renderDayView(new Date(currentYear, currentMonth, currentDay));
                break;
            case 'list':
                renderListView();
                break;
        }
        hideLoading();
    });
}

function clearCurrentView() {
    const views = ['calendar-body', 'week-body', 'day-body', 'list-body'];
    views.forEach(id => {
        const element = document.getElementById(id);
        if (element) element.innerHTML = '';
    });
}

function showLoading() {
    const container = document.querySelector('.calendar-views-container');
    if (!container.querySelector('.loading-overlay')) {
        const overlay = document.createElement('div');
        overlay.className = 'loading-overlay position-absolute top-0 left-0 w-100 h-100 d-flex align-items-center justify-content-center';
        overlay.style.background = 'rgba(255,255,255,0.8)';
        overlay.style.zIndex = '1000';
        overlay.innerHTML = '<div class="spinner-border text-primary"></div>';
        container.appendChild(overlay);
    }
}

function hideLoading() {
    const overlay = document.querySelector('.loading-overlay');
    if (overlay) overlay.remove();
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

            // Initialize holiday type search for single holiday modal
            function initHolidayTypeSearch() {
                // Single holiday modal
                $('#holiday-type-search').on('input', function() {
                    const searchTerm = $(this).val();
                    if (searchTerm.length < 0) {
                        $('#holiday-type-list').hide().empty();
                        return;
                    }

                    $.ajax({
                        url: '{{ route('holidaytype.search') }}',
                        method: 'GET',
                        data: {
                            search: searchTerm
                        },
                        success: function(response) {
                            const $list = $('#holiday-type-list');
                            $list.empty();

                            if (response.length > 0) {
                                response.forEach(function(type) {
                                    $list.append(
                                        `<li class="list-group-item holiday-type-item"
                                  data-id="${type.holidaytype_id}"
                                  data-name="${type.holidaytype_name}">
                                    ${type.holidaytype_name}
                                </li>`
                                    );
                                });
                                $list.show();
                            } else {
                                $list.append(
                                    `<li class="list-group-item disabled">No results found</li>`
                                ).show();
                            }
                        }
                    });
                });

                // Click handler for single modal
                $(document).on('click', '#holiday-type-list .holiday-type-item', function() {
                    const id = $(this).data('id');
                    const name = $(this).data('name');
                    $('#holiday-type-search').val(name);
                    $('#holiday-type-id').val(id);
                    $('#holiday-type-list').hide().empty();
                });

                // Close when clicking outside
                $(document).on('click', function(e) {
                    if (!$(e.target).closest('#holiday-type-search').length &&
                        !$(e.target).closest('#holiday-type-list').length) {
                        $('#holiday-type-list').hide();
                    }
                });
            }

            // Initialize holiday type search for multiple holiday entries
            function initMultiHolidayTypeSearch() {
                $(document).on('input', '.multi-holiday-type-search', function() {
                    const searchTerm = $(this).val();
                    const $list = $(this).closest('.form-group').find('.multi-holiday-type-list');

                    if (searchTerm.length < 0) {
                        $list.hide().empty();
                        return;
                    }

                    $.ajax({
                        url: '{{ route('holidaytype.search') }}',
                        method: 'GET',
                        data: {
                            search: searchTerm
                        },
                        success: function(response) {
                            $list.empty();

                            if (response.length > 0) {
                                response.forEach(function(type) {
                                    $list.append(
                                        `<li class="list-group-item multi-holiday-type-item"
                                  data-id="${type.holidaytype_id}"
                                  data-name="${type.holidaytype_name}">
                                    ${type.holidaytype_name}
                                </li>`
                                    );
                                });
                                $list.show();
                            } else {
                                $list.append(
                                    `<li class="list-group-item disabled">No results found</li>`
                                ).show();
                            }
                        }
                    });
                });

                // Click handler for multiple entries
                $(document).on('click', '.multi-holiday-type-item', function() {
                    const $inputGroup = $(this).closest('.form-group');
                    const id = $(this).data('id');
                    const name = $(this).data('name');

                    $inputGroup.find('.multi-holiday-type-search').val(name);
                    $inputGroup.find('.multi-holiday-type-id').val(id);
                    $inputGroup.find('.multi-holiday-type-list').hide().empty();
                });

                // Close when clicking outside
                $(document).on('click', function(e) {
                    if (!$(e.target).closest('.multi-holiday-type-search').length &&
                        !$(e.target).closest('.multi-holiday-type-list').length) {
                        $('.multi-holiday-type-list').hide();
                    }
                });
            }

            function initHolidayTypeSearchForEntry(entryElement) {
                const searchInput = entryElement.querySelector('.multi-holiday-type-search');
                const typeList = entryElement.querySelector('.multi-holiday-type-list');
                const typeIdInput = entryElement.querySelector('.multi-holiday-type-id');

                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value;
                    if (searchTerm.length < 1) {
                        typeList.style.display = 'none';
                        return;
                    }

                    $.ajax({
                        url: '{{ route('holidaytype.search') }}',
                        method: 'GET',
                        data: {
                            search: searchTerm
                        },
                        success: function(response) {
                            typeList.innerHTML = '';

                            if (response.length > 0) {
                                response.forEach(function(type) {
                                    const item = document.createElement('li');
                                    item.className =
                                        'list-group-item multi-holiday-type-item';
                                    item.dataset.id = type.holidaytype_id;
                                    item.dataset.name = type.holidaytype_name;
                                    item.textContent = type.holidaytype_name;
                                    typeList.appendChild(item);
                                });
                                typeList.style.display = 'block';
                            } else {
                                const item = document.createElement('li');
                                item.className = 'list-group-item disabled';
                                item.textContent = 'No results found';
                                typeList.appendChild(item);
                                typeList.style.display = 'block';
                            }
                        }
                    });
                });

                // Click handler for type items
                typeList.addEventListener('click', function(e) {
                    if (e.target.classList.contains('multi-holiday-type-item')) {
                        searchInput.value = e.target.dataset.name;
                        typeIdInput.value = e.target.dataset.id;
                        typeList.style.display = 'none';
                    }
                });

                // Close when clicking outside
                document.addEventListener('click', function(e) {
                    if (!e.target.closest('.form-group.position-relative')) {
                        typeList.style.display = 'none';
                    }
                });
            }

            // Initialize both search functionalities
            initHolidayTypeSearch();
            initMultiHolidayTypeSearch();

            // At the end of your DOMContentLoaded event listener
            document.querySelectorAll('.holiday-entry').forEach(entry => {
                initHolidayTypeSearchForEntry(entry);
            });
        });
    </script>


    <style>
        .holiday-container {
            background-color: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .calendar-day {
            height: 100px;
            vertical-align: top;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid #f0f0f0 !important;
        }

        .calendar-day:hover {
            background-color: #f8faff !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        .other-month-day {
            background-color: #f9f9f9;
        }

        .today-cell {
            background-color: #e6f2ff !important;
            position: relative;
        }

        .today-number {
            color: #0d6efd;
        }

        .holiday-indicator {
            font-size: 1rem;
            margin-bottom: 2px;
            transition: all 0.2s;
            cursor: pointer;
        }

        .holiday-name {
            font-weight: 500;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .holiday-type {
            font-size: 0.65rem;
            display: block;
            opacity: 0.8;
        }

        .bg-primary-light {
            background-color: #e0f0ff !important;
        }

        .text-primary-dark {
            color: #0a58ca !important;
        }

        .bg-success-light {
            background-color: #d1fae5 !important;
        }

        .text-success-dark {
            color: #059669 !important;
        }

        .bg-warning-light {
            background-color: #fef3c7 !important;
        }

        .text-warning-dark {
            color: #b45309 !important;
        }

        .bg-secondary-light {
            background-color: #e9ecef !important;
        }

        .text-secondary-dark {
            color: #495057 !important;
        }

        .calendar-views-container {
            overflow: hidden;
        }

        .calendar-view {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            opacity: 0;
            transform: translateY(10px);
            transition: none;
        }

        .holiday-entry {
            transition: all 0.3s ease;
        }

        .remove-holiday {
            transition: all 0.3s ease;
        }

        .calendar-view.active-view {
            position: relative;
            opacity: 1;
            transform: translateY(0);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .calendar-view.view-enter {
            opacity: 0;
            transform: translateY(10px);
        }

        .calendar-view.view-exit {
            opacity: 0;
            transform: translateY(-10px);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .calendar-row,
        .week-row,
        .day-row,
        .list-row {
            opacity: 0;
            transform: translateY(10px);
            animation: fadeInUp 0.4s ease forwards;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .calendar-row:nth-child(1) {
            animation-delay: 0.1s;
        }

        .calendar-row:nth-child(2) {
            animation-delay: 0.15s;
        }

        .calendar-row:nth-child(3) {
            animation-delay: 0.2s;
        }

        .calendar-row:nth-child(4) {
            animation-delay: 0.25s;
        }

        .calendar-row:nth-child(5) {
            animation-delay: 0.3s;
        }

        .calendar-row:nth-child(6) {
            animation-delay: 0.35s;
        }

        .current-hour-cell {
            background-color: #f0f7ff !important;
            position: relative;
        }

        .current-hour-cell::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background-color: #0d6efd;
            border-radius: 3px;
        }

        .btn-outline-primary {
            border-color: #dee2e6;
        }

        .btn-outline-primary:hover {
            border-color: #dee2e6;
        }

        .view-btn.active {
            background-color: #0d6efd;
            color: white;
        }

        #holiday-calendar td:nth-child(6),
        #holiday-calendar td:nth-child(7) {
            /* background-color: #f9f9f9; */
        }

        @media (max-width: 768px) {
            .calendar-day {
                height: 70px;
                font-size: 0.8rem;
            }

            .holiday-indicator {
                font-size: 0.65rem;
            }

            .holiday-name {
                font-size: 0.7rem;
            }
        }

        .holiday-form {
            max-width: 1200px;
        }

        .details-holiday {
            max-width: 600px;
        }

        .single-holiday-form {
            max-width: 1200px;
        }

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

        /* Add to your existing CSS */
        .no-shifts-day {
            opacity: 0.6;
            background-color: #f8f9fa !important;
        }

        .no-shifts-day.dayoff-shift {
            background-color: #fff8e1 !important;
        }

        .no-shifts-day .day-number {
            color: #adb5bd !important;
        }

        .dayoff-shift .day-number {
            color: #ffa000 !important;
        }

        .is-invalid {
            border-color: #dc3545 !important;
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 0.875em;
            margin-top: 0.25rem;
        }




        #holiday-type-list {
            position: absolute;
            z-index: 1000;
            width: 47%;
            max-height: 200px;
            overflow-y: auto;
            display: none;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        #multiple-type-list {
            position: absolute;
            z-index: 1000;
            width: 23%;
            max-height: 200px;
            overflow-y: auto;
            display: none;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }



        .holiday-type-item,
        .multi-holiday-type-item {
            cursor: pointer;
        }

        .holiday-type-item:hover,
        .multi-holiday-type-item:hover {
            background-color: #f8f9fa;
        }

        /* Pagination styles */
        .pagination .page-item.active .page-link {
            background-color: #0d6efd;
            color: #fff;
            /* border-color: #0d6efd; */
        }

        .pagination .page-link {
            color: #0d6efd;
            border-radius: 4px;
            margin: 0 2px;
        }

        .pagination .page-link:hover {
            background-color: #e9ecef;
        }

        #showing-entries {
            font-size: 0.875rem;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card-header .row>div {
                margin-bottom: 10px;
            }

            #entries-per-page {
                width: 70px;
            }
        }
    </style>
</x-layout>
