<x-layout>
    @section('title', 'Holiday Calendar')

    <div class="container-fluid p-4">
        <x-message />

        <div class="holiday-container">
            <!-- Header Section -->
            <div class="row justify-content-between align-items-center mb-4" style="margin-top: 2em">
                <h4 class="mb-0 fw-semibold fs-5"><i class="bi bi-calendar-event me-2"></i> Holiday Calendar</h4>

                @if (isset($department) && $department)
                    <div class="col-auto">
                        <span class="badge bg-primary p-2">
                            <i class="bi bi-building me-1"></i>
                            Department: {{ $department->dep_name }}
                        </span>
                    </div>
                @endif
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
                                            <th>Department</th>
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

            // Pagination variables for list view
            let currentPage = 1;
            let entriesPerPage = 5;
            let filteredHolidays = [];

            // Initialize the calendar
            initCalendar();

            function initCalendar() {
                fetchHolidays().then(() => {
                    renderView();
                    updatePeriodDisplay();
                });
            }

            // Fetch holidays from the server (only for employee's department)
       async function fetchHolidays() {
    try {
        // Fetch for entire year instead of limited range
        const startDate = new Date(currentYear, 0, 1).toISOString().split('T')[0]; // Jan 1
        const endDate = new Date(currentYear, 11, 31).toISOString().split('T')[0]; // Dec 31

        const params = new URLSearchParams({
            start: startDate,
            end: endDate,
        });

        const response = await fetch(`{{ route('empholidayget') }}?${params.toString()}`, {
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
        holidays = Array.isArray(data) ? data.map(h => ({
            id: h.id,
            name: h.name,
            date: h.date,
            type: h.type_id || h.type,
            type_name: h.type_name || (h.type_id === 1 ? 'Local' : h.type_id === 2 ?
                'National' : h.type_id === 3 ? 'Religious' : 'Other'),
            department: h.holiday_department,
        })) : [];

        return holidays;
    } catch (error) {
        console.error('Error fetching holidays:', error);
        holidays = [];
        return holidays;
    }
}
            // Render Month View
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
                                }
                            });

                            date++;
                        }

                        row.appendChild(cell);
                    }

                    calendarBody.appendChild(row);
                }
            }

            // Render Week View
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
                            const holidayEvent = e.target.closest('.week-holiday-event');
                            if (holidayEvent) {
                                const holidayId = parseInt(holidayEvent.dataset.holidayId);
                                const holiday = holidays.find(h => h.id === holidayId);
                                if (holiday) showHolidayDetails(holiday);
                            }
                        });

                        row.appendChild(cell);
                    }

                    weekBody.appendChild(row);
                }
            }

            // Render Day View
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
                        const holidayEvent = e.target.closest('.day-holiday-event');
                        if (holidayEvent) {
                            const holidayId = parseInt(holidayEvent.dataset.holidayId);
                            const holiday = holidays.find(h => h.id === holidayId);
                            if (holiday) showHolidayDetails(holiday);
                        }
                    });

                    row.appendChild(eventCell);
                    dayBody.appendChild(row);
                }
            }

            // Render List View
            // Render List View
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

                        // Get department names - FIXED VERSION
                        let departmentNames = 'All Departments';
                        if (holiday.department && holiday.department !== 'all') {
                            const depIds = holiday.department.split(',');
                            // Create department name mapping
                            const departmentMap = {
                                @foreach ($departments as $dept)
                                    '{{ $dept->dep_id }}': '{{ $dept->dep_name }}',
                                @endforeach
                            };

                            departmentNames = depIds.map(depId => {
                                return departmentMap[depId] || `Department ${depId}`;
                            }).join(', ');
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
                <td>${departmentNames}</td>
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
            // Update pagination controls
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

            // Show holiday details

            function showHolidayDetails(holiday) {
                document.getElementById('holidayDetailsTitle').textContent = holiday.name;

                // Get department names - FIXED VERSION
                let departmentNames = 'All Departments';
                if (holiday.department && holiday.department !== 'all') {
                    const depIds = holiday.department.split(',');
                    // Create department name mapping
                    const departmentMap = {
                        @foreach ($departments as $dept)
                            '{{ $dept->dep_id }}': '{{ $dept->dep_name }}',
                        @endforeach
                    };

                    departmentNames = depIds.map(depId => {
                        return departmentMap[depId] || `Department ${depId}`;
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
                            periodDisplay.textContent = `${monthNames[currentMonth]} ${currentYear}`;
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

            function getHolidayTypeBadgeClass(type) {
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

            // Event listeners for filters
            document.getElementById('list-filter').addEventListener('change', function() {
                currentPage = 1;
                if (currentView === 'list') {
                    renderListView();
                }
            });

            document.getElementById('entries-per-page').addEventListener('change', function() {
                entriesPerPage = parseInt(this.value);
                currentPage = 1;
                renderListView();
            });

            // Click handlers for holiday indicators
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
            });

            function renderView() {
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
            }
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

        .details-holiday {
            max-width: 600px;
        }

        /* Pagination styles */
        .pagination .page-item.active .page-link {
            background-color: #0d6efd;
            color: #fff;
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
