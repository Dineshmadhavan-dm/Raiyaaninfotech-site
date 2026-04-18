<x-layout>
    @section('title', 'Daily Attendance')

    <div class="container-fluid p-3">
        <x-message />

        <div class="attendance-container">
            <!-- Header Section -->
            <div class="" style=" background-color: #f8fafc;">

                <div class=" row justify-content-between px-3 pb-3">
                    <h4 class="pb-3 pt-2 fw-medium fs-5"><i class="bi bi-calendar2-range me-2"></i>Daily Attendance</h4>

                    <div class="col-auto">
                        <div class="row justify-content-start">

                            <div class="col-auto" style=" margin-top: 1.3em;">
                                <div class="dropdown">
                                    <button class="dropdown-toggle control-select" type="button" id="monthDropdown"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <span id="monthText">{{ date('F Y') }}</span>
                                    </button>

                                    <ul class="dropdown-menu month-dropdown p-2" aria-labelledby="monthDropdown"
                                        style="width: 150px;">
                                        <li>
                                            <input type="text" id="monthPicker" class="form-control form-control-sm"
                                                placeholder="Select month">
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

            <!-- Attendance Legend -->
            <div class="row justify-content-end px-3 py-2">
                <div class="col-auto mb-4">
                    <span class="f-w-500 mr-1">Note:</span>
                    <i class="fas fa-star text-warning" title="Holiday"></i>
                    <i class="fas fa-arrow-right text-lightest f-11 mx-1"></i> Holiday &nbsp;|&nbsp;

                    <i class="fas fa-calendar-week text-red" title="Day Off"></i>
                    <i class="fas fa-arrow-right text-lightest f-11 mx-1"></i> Day Off &nbsp;|&nbsp;

                    <i class="fas fa-check text-success" title="Present"></i>
                    <i class="fas fa-arrow-right text-lightest f-11 mx-1"></i> Present &nbsp;|&nbsp;

                    <i class="fas fa-star-half-alt text-info" title="Half Day"></i> First Half
                    <i class="fas fa-star-half-alt  text-info-emphasis" style="  transform: scaleX(-1);"
                        title="Half Day"></i>
                    Second Half
                    <i class="fas fa-arrow-right text-lightest f-11 mx-1"></i> Half Day &nbsp;|&nbsp;

                    <i class="fas fa-exclamation-circle text-warning" title="Late"></i>
                    <i class="fas fa-arrow-right text-lightest f-11 mx-1"></i> Late &nbsp;|&nbsp;

                    <i class="fas fa-times text-danger" title="Absent"></i>
                    <i class="fas fa-arrow-right text-lightest f-11 mx-1"></i> Absent &nbsp;|&nbsp;

                    <i class="fas fa-plane-departure text-danger" title="On Leave"></i>
                    <i class="fas fa-arrow-right text-lightest f-11 mx-1"></i> On Leave
                </div>
            </div>

            <!-- Attendance Display -->
            <div class="attendance-table-container">
                <div class="attendance-header">
                    <div class="employee-column fw-bold fs-5">Employee</div>
                    <div class="days-header" id="daysHeader">
                        <!-- Days will be dynamically generated here -->
                    </div>
                    <div class="total-column fw-bold">Total <div style="font-size: 13px"><i
                                class="fas fa-check text-success" title="Present"></i> | <i
                                class="fas fa-star-half-alt text-info" title="Half Day"></i> | <i
                                class="fas fa-exclamation-circle text-warning" title="Late"></i></div>
                    </div>
                </div>
                <div class="attendance-body" id="attendanceBody">
                    <!-- Employee rows will be dynamically generated here -->
                </div>
            </div>

        </div>

        <!-- View Attendance Modal (Modern UI) -->
        <div class="modal fade" id="viewAttendanceModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div>
                            <h5 class="modal-title text-white fw-bold mb-1">Attendance Details</h5>
                            <p class="text-white-50 mb-0" id="viewAttendanceDate"></p>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <!-- Employee Info Card -->
                        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-circle" style="width: 60px; height: 60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                                            <span class="text-white fw-bold fs-4" id="viewEmployeeInitials"></span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="mb-1 fw-semibold" id="viewEmployeeName"></h5>
                                        <p class="text-muted mb-2" id="viewEmployeePosition"></p>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Time Tracking Cards -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; border-left: 4px solid #28a745;">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="card-title mb-0 fw-semibold">Clock In</h6>
                                            <i class="bi bi-clock-history text-success fs-4"></i>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle p-2 me-3">
                                                <i class="bi bi-door-open text-success"></i>
                                            </div>
                                            <div>
                                                <h4 class="mb-0 fw-bold" id="viewClockIn">--:-- --</h4>
                                                <small class="text-muted" id="viewClockInIp">IP: --.--.--.--</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; border-left: 4px solid #dc3545;">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="card-title mb-0 fw-semibold">Clock Out</h6>
                                            <i class="bi bi-clock text-danger fs-4"></i>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle p-2 me-3">
                                                <i class="bi bi-door-closed text-danger"></i>
                                            </div>
                                            <div>
                                                <h4 class="mb-0 fw-bold" id="viewClockOut">--:-- --</h4>
                                                <small class="text-muted" id="viewClockOutIp">IP: --.--.--.--</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Work Duration Card -->
                        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <div class="card-body text-center text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Total Worked Time</h6>
                                        <h2 class="mb-0 fw-bold" id="workedHours">0h 0m</h2>
                                    </div>
                                    <div class="work-time-circle">
                                        <svg width="80" height="80" viewBox="0 0 36 36" class="circular-chart">
                                            <path class="circle-bg"
                                                d="M18 2.0845
                                                a 15.9155 15.9155 0 0 1 0 31.831
                                                a 15.9155 15.9155 0 0 1 0 -31.831"
                                                fill="none"
                                                stroke="rgba(255,255,255,0.2)"
                                                stroke-width="3"/>
                                            <path class="circle"
                                                stroke-dasharray="0, 100"
                                                d="M18 2.0845
                                                a 15.9155 15.9155 0 0 1 0 31.831
                                                a 15.9155 15.9155 0 0 1 0 -31.831"
                                                fill="none"
                                                stroke="#ffffff"
                                                stroke-width="3"
                                                stroke-linecap="round"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Info -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                                    <div class="card-body">
                                        <h6 class="card-title fw-semibold mb-3">Work Location</h6>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                                <i class="bi bi-geo-alt text-primary"></i>
                                            </div>
                                            <div>

                                                <small class="text-muted" id="viewWorkFrom">Working from</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                                    <div class="card-body">
                                        <h6 class="card-title fw-semibold mb-3">Attendance Status</h6>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                                <i class="bi bi-clipboard-check text-warning"></i>
                                            </div>
                                            <div>
                                                <span class="badge fs-6" id="viewAttendanceStatus">--</span>
                                                <br>
                                                <small class="text-muted" id="viewHalfDayType"></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Attendance Modal -->
        <div class="modal fade" id="addAttendanceModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="modal-title text-white">Mark Attendance</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addAttendanceForm">
                            @csrf
                            <input type="hidden" id="addAttendanceEmployee" name="employee_id">
                            <input type="hidden" id="addAttendanceDate" name="attendancedate_no">

                            <div class="row justify-content-between mb-4">
                                <div class="employee-info-single col-md-7">
                                    <h6 id="addEmployeeName" class="mb-1 fw-semibold"></h6>
                                    <p id="addEmployeePosition" class="text-muted small mb-2"></p>
                                    <p id="addAttendanceDateDisplay" class="fw-bold"></p>
                                </div>
                                <div class="col-md-5">
                                    <span class="p-2 shift-badge" style="font-size: 10.5px" id="addShiftBadge">Not assigned</span>
                                </div>
                            </div>

                            <!-- Clock In Section -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Clock In <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-body-secondary"><i class="bi bi-clock"></i></span>
                                        <input type="text" id="addCheckInTime"
                                            class="form-control time-picker   bg-body-secondary  " name="clock_in"
                                            placeholder="Set time" readonly >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Clock In IP</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-body-secondary"><i class="bi bi-laptop"></i></span>
                                        <input type="text" id="addClockInIp" class="form-control  bg-body-secondary"
                                            name="clock_in_ip" readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- Clock Out Section -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Clock Out</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-body-secondary"><i class="bi bi-clock"></i></span>
                                        <input type="text" id="addCheckOutTime" class="form-control time-picker bg-body-secondary"
                                            name="clock_out" placeholder="Set time" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Clock Out IP</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-body-secondary"><i class="bi bi-laptop"></i></span>
                                        <input type="text" id="addClockOutIp" class="form-control bg-body-secondary"
                                            name="clock_out_ip" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Working From</label>
                                    <select class="form-select" name="attendance_workfrom" id="addWorkingFrom">
                                        <option value="home">Home</option>
                                        <option value="office">Office</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Attendance Status</label>
                                    <select id="addAttendanceStatus" class="form-select" name="attendance_type"
                                        required>
                                        <option value="1">Present</option>
                                        <option value="2">Late</option>
                                        <option value="3">Half Day</option>

                                    </select>
                                </div>
                            </div>

                            <!-- Half Day Options -->
                            <div class="row mb-3" id="addHalfDayOptions" style="display: none;">
                                <div class="col-12">
                                    <label class="form-label fw-medium">Half Day Type</label>
                                    <div class="d-flex gap-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="half_day_type"
                                                id="addFirstHalf" value="0" checked>
                                            <label class="form-check-label" for="addFirstHalf">
                                                <i class="bi bi-sunrise me-1"></i> First Half
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="half_day_type"
                                                id="addSecondHalf" value="1">
                                            <label class="form-check-label" for="addSecondHalf">
                                                <i class="bi bi-sunset me-1"></i> Second Half
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        {{-- <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button> --}}
                        <button type="button" class="btn btn-primary" id="saveaddAttendance">Mark</button>
                    </div>
                </div>
            </div>
        </div>

      <script>

const approvedLeaves = @json($approvedLeaves);
const authUser = @json(auth()->user());
const isEmployee = authUser.categorie == 2;

document.addEventListener('DOMContentLoaded', function() {
    const monthPicker = flatpickr("#monthPicker", {
        plugins: [
            new monthSelectPlugin({
                shorthand: true,
                dateFormat: "F Y",
                altFormat: "F Y",
                theme: "light"
            })
        ],
        allowInput: true,
        onChange: function(selectedDates, dateStr, instance) {
            document.getElementById('monthText').textContent = dateStr;
            currentDate = new Date(selectedDates[0]);
            refreshAttendance();
        }
    });

    const timePickerConfig = {
        enableTime: true,
        noCalendar: true,
        dateFormat: "h:i K",
        time_24hr: false,
        minuteIncrement: 15,
        disableMobile: true,
        defaultHour: new Date().getHours(),
        defaultMinute: new Date().getMinutes(),
        allowInput: false,
        clickOpens: true
    };

    let addClockInPicker, addClockOutPicker;

    let currentDate = new Date();
    let attendances = [];
    let employees = @json($employees);
    const shifts = @json($shifts ?? []);

    let currentPage = 1;
    let entriesPerPage = isEmployee ? 1 : 5;

    const prevPageBtn = document.getElementById('prevPage');
    const nextPageBtn = document.getElementById('nextPage');
    const pageInfo = document.getElementById('pageInfo');


    const viewAttendanceModal = document.getElementById('viewAttendanceModal') ? new bootstrap.Modal(
        document.getElementById('viewAttendanceModal')) : null;
    const addAttendanceModal = document.getElementById('addAttendanceModal') ? new bootstrap.Modal(
        document.getElementById('addAttendanceModal')) : null;

    let selectedEmployee = null;
    let selectedDate = '';
    let shiftTimes = { from: '09:00', to: '18:00' };

    initAttendanceTracker();

    function initAttendanceTracker() {
        employees = employees.map(employee => {
            const departmentName = (employee.departmentid && employee.departmentid.dep_name) ?
                employee.departmentid.dep_name :
                'No Department';

            return {
                ...employee,
                dep_name: departmentName,
                image: employee.image ? '/employee_images/' + employee.image :
                    '/images/admin_default.jpg'
            };
        });

        setupAttendanceTypeHandlers();

        fetchAttendances();
        setupEventListeners();
    }

    function setupAttendanceTypeHandlers() {
        const addAttendanceStatus = document.getElementById('addAttendanceStatus');
        if (addAttendanceStatus) {
            addAttendanceStatus.addEventListener('change', function() {
                handleAttendanceTypeChange('add');
            });
        }

        document.querySelectorAll('#addHalfDayOptions input[name="half_day_type"]').forEach(radio => {
            radio.addEventListener('change', function() {
                setHalfDayTimes('add', this.value);
            });
        });
    }

    function handleAttendanceTypeChange(modalType) {
        const prefix = modalType === 'add' ? 'add' : 'edit';
        const attendanceType = document.getElementById(prefix + 'AttendanceStatus').value;
        const halfDayOptions = document.getElementById(prefix + 'HalfDayOptions');
        const clockInInput = document.getElementById(prefix + 'CheckInTime');
        const clockOutInput = document.getElementById(prefix + 'CheckOutTime');

        shiftTimes = getShiftTimes(selectedEmployee.emp_id, selectedDate);

        const shiftData = shifts[selectedEmployee.emp_id] && shifts[selectedEmployee.emp_id][selectedDate];
const isSwapped = shiftData && shiftData.is_swaped == 1;

if (isSwapped) {
    const select = document.getElementById(prefix + 'AttendanceStatus');

    if (select.value === '3') {
        // 🚫 Prevent Half Day
        select.value = '1';
        return;
    }
}

        const now = new Date();
        const currentTime = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');

        if (attendanceType === '3') {
            halfDayOptions.style.display = 'block';
            const halfDayType = document.querySelector('#' + prefix + 'AttendanceForm input[name="half_day_type"]:checked');
            if (halfDayType) {
                setHalfDayTimes(prefix, halfDayType.value);
            }
        } else {
            halfDayOptions.style.display = 'none';

            if (attendanceType === '1' || attendanceType === '2') {
                clockInInput.value = formatTimeForDisplay(currentTime);
                clockOutInput.value = formatTimeForDisplay(shiftTimes.to);
            }
        }

        if (attendanceType === '0') {
            clockInInput.value = '';
            clockOutInput.value = '';
            clockInInput.disabled = true;
            clockOutInput.disabled = true;
            clockInInput.style.backgroundColor = '#f8f9fa';
            clockOutInput.style.backgroundColor = '#f8f9fa';
        } else {
            clockInInput.disabled = false;
            clockOutInput.disabled = false;
            clockInInput.style.backgroundColor = '';
            clockOutInput.style.backgroundColor = '';

            if ((attendanceType === '1' || attendanceType === '2') && !clockInInput.value) {
                clockInInput.value = formatTimeForDisplay(currentTime);
                clockOutInput.value = formatTimeForDisplay(shiftTimes.to);
            }
        }

        if (attendanceType === '1') {
            const clockInValue = clockInInput.value;
            if (clockInValue) {
                const clockIn24 = convertTo24Hour(clockInValue);
                const shiftStart24 = shiftTimes.from;

                if (compareTimes(clockIn24, shiftStart24) > 0) {
                    document.getElementById(prefix + 'AttendanceStatus').value = '2';
                }
            }
        }
    }
function setHalfDayTimes(prefix, halfDayType) {
    const now = new Date();
    const currentTime = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');

    let clockInTime, clockOutTime;
    const shiftStart24 = shiftTimes.from;
    const shiftEnd24 = shiftTimes.to;

    if (halfDayType === '0') {
        const midTime = calculateMidTime(currentTime, shiftEnd24);
        clockInTime = currentTime;
        clockOutTime = midTime;
    } else {
        const midTime = calculateMidTime(currentTime, shiftEnd24);
        clockInTime = midTime;
        clockOutTime = shiftEnd24;
    }

    document.getElementById(prefix + 'CheckInTime').value = formatTimeForDisplay(clockInTime);
    document.getElementById(prefix + 'CheckOutTime').value = formatTimeForDisplay(clockOutTime);

    if (prefix === 'add') {
        if (addClockInPicker) {
            addClockInPicker.setDate(formatTimeForDisplay(clockInTime));
        }
        if (addClockOutPicker) {
            addClockOutPicker.setDate(formatTimeForDisplay(clockOutTime));
        }
    }
}
    function getShiftTimes(employeeId, dateString) {
        let shiftFromTime = '09:00';
        let shiftToTime = '18:00';

        if (shifts[employeeId] && shifts[employeeId][dateString]) {
            const shift = shifts[employeeId][dateString];
            shiftFromTime = shift.shift_from_time || '09:00';
            shiftToTime = shift.shift_to_time || '18:00';
        }

        return {
            from: shiftFromTime,
            to: shiftToTime
        };
    }

    function calculateMidTime(startTime, endTime) {
        const [startHour, startMin] = startTime.split(':').map(Number);
        const [endHour, endMin] = endTime.split(':').map(Number);

        const startTotalMinutes = startHour * 60 + startMin;
        const endTotalMinutes = endHour * 60 + endMin;

        const midTotalMinutes = Math.floor((startTotalMinutes + endTotalMinutes) / 2);

        const midHour = Math.floor(midTotalMinutes / 60);
        const midMin = midTotalMinutes % 60;

        return `${midHour.toString().padStart(2, '0')}:${midMin.toString().padStart(2, '0')}`;
    }

    function formatTimeForDisplay(time24) {
        const [hours, minutes] = time24.split(':').map(Number);
        const ampm = hours >= 12 ? 'PM' : 'AM';
        const twelveHour = hours % 12 || 12;
        return twelveHour + ':' + minutes.toString().padStart(2, '0') + ' ' + ampm;
    }

    function convertTo24Hour(time12h) {
        if (!time12h) return '00:00';

        const [time, modifier] = time12h.split(' ');
        let [hours, minutes] = time.split(':').map(Number);

        if (hours === 12) {
            hours = 0;
        }

        if (modifier === 'PM') {
            hours = hours + 12;
        }

        return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
    }

    function compareTimes(time1, time2) {
        const [h1, m1] = time1.split(':').map(Number);
        const [h2, m2] = time2.split(':').map(Number);

        const total1 = h1 * 60 + m1;
        const total2 = h2 * 60 + m2;

        return total1 - total2;
    }

    function openAddAttendanceModal(employee, dateString) {
        selectedEmployee = employee;
        selectedDate = dateString;

        const parsedDate = new Date(dateString);
        const formattedDate = formatDisplayDate(parsedDate);

        document.getElementById('addEmployeeName').textContent = employee.fullname;
        document.getElementById('addEmployeePosition').textContent = employee.dep_name || 'No Position';
        document.getElementById('addAttendanceDateDisplay').textContent = 'Date: ' + formattedDate;

        document.getElementById('addAttendanceEmployee').value = employee.emp_id;
        document.getElementById('addAttendanceDate').value = dateString;

        shiftTimes = getShiftTimes(employee.emp_id, dateString);
        const shiftFromTime = shiftTimes.from;
        const shiftToTime = shiftTimes.to;

        let shiftName = 'Not assigned';
        let badgeClass = 'bg-secondary';

        if (shifts[employee.emp_id] && shifts[employee.emp_id][dateString]) {
            const shiftType = shifts[employee.emp_id][dateString].shift_type;

            const fromDisplay = formatTimeForDisplay(shiftFromTime);
            const toDisplay = formatTimeForDisplay(shiftToTime);

            switch (shiftType) {
                case 0:
                    shiftName = `General Shift(${fromDisplay}-${toDisplay})`;
                    badgeClass = 'bg-primary';
                    break;
                case 2:
                    shiftName = `Night Shift(${fromDisplay}-${toDisplay})`;
                    badgeClass = 'bg-dark';
                    break;
                case 1:
                    shiftName = `Morning Shift(${fromDisplay}-${toDisplay})`;
                    badgeClass = 'bg-warning text-dark';
                    break;
                case 3:
                    shiftName = 'Day Off';
                    badgeClass = 'bg-info';
                    break;
                default:
                    shiftName = `Shift(${fromDisplay}-${toDisplay})`;
                    badgeClass = 'bg-secondary';
            }
        }

        const shiftBadge = document.getElementById('addShiftBadge');
        shiftBadge.textContent = shiftName;
        shiftBadge.className = 'badge p-2 shift-badge ' + badgeClass;

        document.getElementById('addCheckInTime').value = '';
        document.getElementById('addCheckOutTime').value = '';
        document.getElementById('addWorkingFrom').value = 'office';
        document.getElementById('addAttendanceStatus').value = '1';
        document.getElementById('addHalfDayOptions').style.display = 'none';

        document.getElementById('addCheckInTime').disabled = false;
        document.getElementById('addCheckOutTime').disabled = false;
        document.getElementById('addCheckInTime').style.backgroundColor = '';
        document.getElementById('addCheckOutTime').style.backgroundColor = '';

        if (addClockInPicker) {
            addClockInPicker.destroy();
        }
        if (addClockOutPicker) {
            addClockOutPicker.destroy();
        }

        const now = new Date();
        const currentTime = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');

        document.getElementById('addCheckInTime').value = formatTimeForDisplay(currentTime);
        document.getElementById('addCheckOutTime').value = formatTimeForDisplay(shiftTimes.to);

        if (compareTimes(currentTime, shiftTimes.from) > 0) {
            document.getElementById('addAttendanceStatus').value = '2';
        } else {
            document.getElementById('addAttendanceStatus').value = '1';
        }

        addClockInPicker = flatpickr("#addCheckInTime", {
            ...timePickerConfig,
            defaultHour: now.getHours(),
            defaultMinute: now.getMinutes(),
             clickOpens: false, // Allow opening the picker

            onChange: function(selectedDates, dateStr) {
                const clockIn24 = convertTo24Hour(dateStr);
                const shiftStart24 = shiftTimes.from;

                const attendanceType = document.getElementById('addAttendanceStatus').value;
                if (attendanceType === '1' && compareTimes(clockIn24, shiftStart24) > 0) {
                    document.getElementById('addAttendanceStatus').value = '2';
                }
            }
        });

        addClockOutPicker = flatpickr("#addCheckOutTime", {
            ...timePickerConfig,
            defaultHour: parseInt(shiftTimes.to.split(':')[0]),
            defaultMinute: parseInt(shiftTimes.to.split(':')[1]),
              clickOpens: false, // Allow opening the picker
        });

        addClockInPicker.setDate(formatTimeForDisplay(currentTime));
        addClockOutPicker.setDate(formatTimeForDisplay(shiftTimes.to));


        const shiftData = shifts[employee.emp_id] && shifts[employee.emp_id][dateString];
const isSwapped = shiftData && shiftData.is_swaped == 1;

const attendanceSelect = document.getElementById('addAttendanceStatus');
const halfDayOption = attendanceSelect.querySelector('option[value="3"]');

if (isSwapped) {
    // 🚫 Hide Half Day option
    if (halfDayOption) {
        halfDayOption.style.display = 'none';
        halfDayOption.disabled = true;
    }

    // Force Present
    attendanceSelect.value = '1';
} else {
    // ✅ Show Half Day
    if (halfDayOption) {
        halfDayOption.style.display = 'block';
        halfDayOption.disabled = false;
    }
}

        fetch('/get-ip-address')
            .then(response => response.json())
            .then(data => {
                document.getElementById('addClockInIp').value = data.ip;
                document.getElementById('addClockOutIp').value = data.ip;
            })
            .catch(() => {
                document.getElementById('addClockInIp').value = 'Not available';
                document.getElementById('addClockOutIp').value = 'Not available';
            });

        if (addAttendanceModal) {
            addAttendanceModal.show();
        }
    }

    document.getElementById('saveaddAttendance').addEventListener('click', function() {
        const clockIn = document.getElementById('addCheckInTime').value;
        const clockOut = document.getElementById('addCheckOutTime').value;
        const attendanceType = document.getElementById('addAttendanceStatus').value;

        if (attendanceType === '0' && (clockIn || clockOut)) {
            showToast('Clock in/out times must be empty for absent status', 'error');
            return;
        }

        if (attendanceType !== '0' && !clockIn) {
            showToast('Clock In time is required', 'error');
            return;
        }

        if (attendanceType !== '0' && clockIn && clockOut) {
            const clockInTime = convertTo24Hour(clockIn);
            const clockOutTime = convertTo24Hour(clockOut);

            if (compareTimes(clockOutTime, clockInTime) <= 0) {
                showToast('Clock Out time must be after Clock In time', 'error');
                return;
            }
        }

        const formData = new FormData(document.getElementById('addAttendanceForm'));
        formData.set('attendance_type', attendanceType);
        formData.set('attendance_workfrom', document.getElementById('addWorkingFrom').value === 'office' ? '1' : '0');

        if (attendanceType === '3') {
            const halfDayType = document.querySelector('#addAttendanceForm input[name="half_day_type"]:checked');
            formData.set('half_day_type', halfDayType && halfDayType.value === '1' ? '1' : '0');
        } else {
            formData.set('half_day_type', '');
        }

        const saveBtn = document.getElementById('saveaddAttendance');
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';

        fetch('attendances/create', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    fetchAttendances();
                    if (addAttendanceModal) addAttendanceModal.hide();
                    showToast(data.message, 'success');
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred while saving the attendance', 'error');
            })
            .finally(() => {
                saveBtn.disabled = false;
                saveBtn.innerHTML = 'Save Attendance';
            });
    });

    function setupEventListeners() {
        if (isEmployee) {
            if (prevPageBtn) prevPageBtn.style.display = 'none';
            if (nextPageBtn) nextPageBtn.style.display = 'none';
            if (pageInfo) pageInfo.style.display = 'none';
        }


    }

    function goToPrevPage() {
        if (currentPage > 1) {
            currentPage--;
            refreshAttendance();
        }
    }

    function goToNextPage() {
        const { totalPages } = filterEmployees();
        if (currentPage < totalPages) {
            currentPage++;
            refreshAttendance();
        }
    }

    function fetchAttendances() {
        fetch('attendances/get')
            .then(response => response.json())
            .then(data => {
                attendances = data;
                refreshAttendance();
            })
            .catch(error => {
                console.error('Error fetching attendances:', error);
                attendances = [];
                refreshAttendance();
            });
    }



    function refreshAttendance() {
        renderAttendanceView(currentDate);
        updatePaginationControls();
    }

    function updatePaginationControls() {
        if (isEmployee) return;

        const { allEmployees, totalPages } = filterEmployees();

        if (prevPageBtn) prevPageBtn.disabled = currentPage <= 1;
        if (nextPageBtn) nextPageBtn.disabled = currentPage >= totalPages || totalPages === 0;
        if (pageInfo) pageInfo.textContent = 'Page ' + currentPage + ' of ' + totalPages + ' (' +
            allEmployees.length + ' employees)';
    }

    function filterEmployees() {
        let filtered = [...employees];

        if (isEmployee) {
            filtered = filtered.filter(employee => employee.emp_id == authUser.employeerole_id);
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

    function renderAttendanceView(date) {
        const year = date.getFullYear();
        const month = date.getMonth();
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        let daysHeader = '';
        for (let day = 1; day <= daysInMonth; day++) {
            const currentDate = new Date(year, month, day);
            const dayName = currentDate.toLocaleDateString('en-US', { weekday: 'short' });
            const dateString = formatDate(currentDate);
            const isWeekend = currentDate.getDay() === 0 || currentDate.getDay() === 6;

            let anyEmployeeHasShift = false;
            for (const empId in shifts) {
                if (shifts[empId][dateString]) {
                    anyEmployeeHasShift = true;
                    break;
                }
            }
const isHoliday = Object.values(shifts).some(emp =>
    emp[dateString] && emp[dateString].shift_type === 4
);
            daysHeader += '<div class="day-header ' + (isWeekend ? 'weekend' : '') + ' ' + (isHoliday ? 'holiday' : '') + '">';
            daysHeader += '<div class="day-name">' + dayName + '</div>';
            daysHeader += '<div class="day-number">' + day + '</div>';
            if (isHoliday) {
                daysHeader += '<div class="holiday-indicator"></div>';
            }
            daysHeader += '</div>';
        }
        document.getElementById('daysHeader').innerHTML = daysHeader;

        const { paginatedEmployees: filteredEmployees } = filterEmployees();
        let attendanceBody = '';

        filteredEmployees.forEach(employee => {
            attendanceBody += '<div class="employee-row" data-employee-id="' + employee.emp_id + '">';

            attendanceBody += '<div class="employee-info">';
            attendanceBody += '<div class="d-flex">';
            attendanceBody += '<div class="me-2">';
            attendanceBody += '<img src="' + employee.image + '" class="avatar-img rounded-circle" style="width: 36px; height: 36px; object-fit: cover;">';
            attendanceBody += '</div>';
            attendanceBody += '<div>';
            attendanceBody += '<div class="fw-bold">' + employee.fullname + '</div>';
            attendanceBody += '<div class="small text-muted">' + employee.dep_name + '</div>';
            attendanceBody += '</div>';
            attendanceBody += '</div>';
            attendanceBody += '</div>';

            let presentCount = 0;
            let absentCount = 0;
            let lateCount = 0;
            let halfDayCount = 0;
            let leaveCount = 0;
            let holidayCount = 0;
            let dayOffCount = 0;

        for (let day = 1; day <= daysInMonth; day++) {
    const currentDate = new Date(year, month, day);
    const dateString = formatDate(currentDate);

    const dayAttendance = attendances.find(att =>
        att.employee_id == employee.emp_id && att.attendancedate_no === dateString
    );

    const shiftData = shifts[employee.emp_id] && shifts[employee.emp_id][dateString];
    const isSwapped = shiftData && shiftData.is_swaped == 1;

    const isHoliday = shiftData && shiftData.shift_type === 4;
    const isDayOff = shiftData && shiftData.shift_type === 3;

    let statusClass = '';
    let statusAbbr = '';
    let title = '';

    const leave = approvedLeaves[employee.emp_id] && approvedLeaves[employee.emp_id][dateString];

    if (leave) {
        const leaveType = leave.leavetype ?
            getLeavetypeNameText(leave.leavetype.leavetype_name_id) : 'Absent';

        if (leave.select_duration == 3 || leave.select_duration == 4) {
            title = `${leaveType}\nReason: ${leave.reason_forleave || 'No reason provided'}\nDuration: ${leave.select_duration == 3 ? 'First Half' : 'Second Half'}`;

            if (leave.select_duration == 3) {
                statusAbbr = '<i class="fas fa-star-half-alt text-danger"></i>';
            } else {
                statusAbbr = '<i class="fas fa-star-half-alt text-danger" style="transform: scaleX(-1);"></i>';
            }
        } else {
            title = `${leaveType}\nReason: ${leave.reason_forleave || 'No reason provided'}\nDuration: Full Day`;
            statusAbbr = '<i class="fas fa-plane-departure text-danger"></i>';
        }

        statusClass = 'leave';
        leaveCount++;

    } else if (isHoliday) {

        statusAbbr = '<i class="fas fa-star text-warning"></i>';
        title = `${shiftData.holiday_type || ''} - ${shiftData.occasion || ''}`;
        statusClass = 'holiday';
        holidayCount++;

    } else if (isDayOff) {

        statusAbbr = '<i class="fas fa-calendar-week text-secondary" title="Day Off"></i>';
        title = 'Day Off';
        statusClass = 'day-off';
        dayOffCount++;

    } else if (dayAttendance) {

        statusClass = dayAttendance.attendance_type;
        title = getAttendanceTitle(dayAttendance.attendance_type);

        switch (dayAttendance.attendance_type.toString()) {
            case '1':
                statusAbbr = '<i class="fas fa-check text-success" title="Present"></i>';
                presentCount++;
                break;

            case '0':
                statusAbbr = '<i class="fas fa-times text-danger" title="Absent"></i>';
                absentCount++;
                break;

            case '2':
                statusAbbr = '<i class="fas fa-exclamation-circle text-warning" title="Late"></i>';
                lateCount++;
                break;

            case '3':
                if (dayAttendance.half_day_type == 0) {
                    statusAbbr = '<i class="fas fa-star-half-alt text-info" title="Half Day"></i>';
                } else {
                    statusAbbr = '<i class="fas fa-star-half-alt text-info-emphasis" style="transform: scaleX(-1);" title="Half Day"></i>';
                }
                halfDayCount++;
                break;

            case '4':
                statusAbbr = '<i class="fas fa-star text-warning" title="Holiday"></i>';
                holidayCount++;
                break;

            default:
                statusAbbr = '';
                title = 'No Attendance';
        }

    } else {

        if (shiftData) {
            statusAbbr = '<i class="bi bi-plus-circle pluseadd" title="Mark Attendance"></i>';
        } else {
            statusAbbr = '<i class="bi bi-dash-circle minusadd" title="No Shift Assigned" style="cursor: not-allowed;"></i>';
        }

        title = shiftData ? 'Mark Attendance' : 'No Shift Assigned';
    }
    // 🔴 OVERRIDE COLOR IF SWAPPED
if (isSwapped && statusAbbr) {

    statusAbbr = statusAbbr
        .replace('text-success', 'text-danger')
        .replace('text-warning', 'text-danger')
        .replace('text-info', 'text-danger')
        .replace('text-info-emphasis', 'text-danger')
        .replace('text-secondary', 'text-danger');
}

    attendanceBody += '<div class="day-cell ' + statusClass + '" data-date="' + dateString + '" data-has-shift="' + (shiftData ? 'true' : 'false') + '" title="' + title + '" onclick="handleAttendanceCellClick(this, \'' + employee.emp_id + '\', \'' + dateString + '\', ' + (dayAttendance ? true : false) + ', event)">';
    attendanceBody += statusAbbr;
    attendanceBody += '</div>';
}
            attendanceBody += '<div class="total-cell text-nowrap">';
            attendanceBody += '<span class="fw-medium" style="font-size:13px;">' + (presentCount + halfDayCount + lateCount) + ' / ' + daysInMonth + '</span>';
            attendanceBody += '</div>';
            attendanceBody += '</div>';
        });

        document.getElementById('attendanceBody').innerHTML = attendanceBody;
    }

    window.handleAttendanceCellClick = function(cell, employeeId, dateString, hasAttendance) {
        const hasShift = cell.dataset.hasShift === 'true';
        const isDayOff = hasShift && shifts[employeeId] && shifts[employeeId][dateString] && shifts[employeeId][dateString].shift_type === 3;

        if (cell.classList.contains('leave') || cell.classList.contains('holiday') || cell.classList.contains('day-off')) {
            event.stopPropagation();
            return;
        }

        if (!hasShift || isDayOff) {
            showToast(isDayOff ? "Day off Modifications are not permitted." : 'No shift assigned for this date', 'error');
            return;
        }

        const employee = employees.find(e => e.emp_id == employeeId);

        if (hasAttendance) {
            const attendance = attendances.find(att => att.employee_id == employeeId && att.attendancedate_no === dateString);
            showViewAttendanceModal(employee, attendance);
        } else {
            openAddAttendanceModal(employee, dateString);
        }
    };



    function getLeavetypeNameText(id) {
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
        return types[id] || 'Absent';
    }

    function showViewAttendanceModal(employee, attendance) {
        const names = employee.fullname.split(' ');
        const initials = names.map(n => n[0]).join('').toUpperCase();
        document.getElementById('viewEmployeeInitials').textContent = initials;

        document.getElementById('viewEmployeeName').textContent = employee.fullname;
        document.getElementById('viewEmployeePosition').textContent = employee.dep_name || 'No Department';


        const date = new Date(attendance.attendancedate_no);
        const options = {
            weekday: 'long',
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        };
        const formattedDate = date.toLocaleDateString('en-US', options).replace(/\//g, '-');
        document.getElementById('viewAttendanceDate').textContent = formatDateToDayMonthYear(formattedDate);

        const clockInTime = attendance.clock_in ? formatTime(attendance.clock_in) : '--:-- --';
        const clockOutTime = attendance.clock_out ? formatTime(attendance.clock_out) : '--:-- --';

        document.getElementById('viewClockIn').textContent = clockInTime;
        document.getElementById('viewClockOut').textContent = clockOutTime;

        document.getElementById('viewClockInIp').textContent = attendance.clock_in_ip ? `IP: ${attendance.clock_in_ip}` : 'IP: --.--.--.--';
        document.getElementById('viewClockOutIp').textContent = attendance.clock_out_ip ? `IP: ${attendance.clock_out_ip}` : 'IP: --.--.--.--';

        if (attendance.clock_in && attendance.clock_out) {
            const workedTime = calculateWorkedTime(attendance.clock_in, attendance.clock_out);
            document.getElementById('workedHours').textContent = workedTime.formatted;
        } else {
            document.getElementById('workedHours').textContent = '0h 0m';
        }


        document.getElementById('viewWorkFrom').textContent = attendance.attendance_workfrom === '1' ? 'Office' : 'Home';

        const statusText = getAttendanceTitle(attendance.attendance_type);
        const statusBadge = document.getElementById('viewAttendanceStatus');
        statusBadge.textContent = statusText;
        statusBadge.className = 'badge fs-6 ' + getStatusBadgeClass(attendance.attendance_type);

        if (attendance.attendance_type == '3') {
            document.getElementById('viewHalfDayType').textContent = attendance.half_day_type == 0 ? 'First Half' : 'Second Half';
        } else {
            document.getElementById('viewHalfDayType').textContent = '';
        }

        if (viewAttendanceModal) {
            viewAttendanceModal.show();
        }
    }

    function getStatusBadgeClass(status) {
        switch(status.toString()) {
            case '0': return 'bg-danger';
            case '1': return 'bg-success';
            case '2': return 'bg-warning text-dark';
            case '3': return 'bg-info';
            case '4': return 'bg-secondary';
            default: return 'bg-secondary';
        }
    }

    function formatDisplayDate(date) {
        const days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
        const dayName = days[date.getDay()];
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        return day + '-' + month + '-' + year + ' (' + dayName + ')';
    }

    function formatDateToDayMonthYear(dateString) {
        const [weekday, datePart] = dateString.split(',');
        if (!datePart) return dateString;
        const [month, day, year] = datePart.trim().split('-');
        return weekday + ', ' + day + '-' + month + '-' + year;
    }

    function formatTime(timeString) {
        if (!timeString) return '';
        const [hours, minutes] = timeString.split(':');
        const h = parseInt(hours);
        const ampm = h >= 12 ? 'PM' : 'AM';
        const twelveHour = h % 12 || 12;
        return twelveHour + ':' + minutes.padStart(2, '0') + ' ' + ampm;
    }

    function calculateWorkedTime(clockIn, clockOut) {
        const [inHours, inMinutes] = clockIn.split(':').map(Number);
        const [outHours, outMinutes] = clockOut.split(':').map(Number);

        const inTotal = inHours * 60 + inMinutes;
        const outTotal = outHours * 60 + outMinutes;

        let diffMinutes = outTotal - inTotal;
        if (diffMinutes < 0) {
            diffMinutes += 24 * 60;
        }

        const hours = Math.floor(diffMinutes / 60);
        const minutes = diffMinutes % 60;

        return {
            hours: hours,
            minutes: minutes,
            formatted: hours + 'h ' + minutes + 'm'
        };
    }

    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return year + '-' + month + '-' + day;
    }

    function getAttendanceTitle(attendanceType) {
        switch (attendanceType.toString()) {
            case '0':
                return 'Absent';
            case '1':
                return 'Present';
            case '2':
                return 'Late';
            case '3':
                return 'Half Day';
            default:
                return 'Unknown';
        }
    }

    function showToast(message, type = 'success') {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
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
        /* Your existing CSS styles remain the same */
        .attendance-container {
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

        .control-select {
            padding: 0.5rem 1rem;
            border: 1px solid #ced4da;
            border-radius: 8px;
            background-color: white;
            cursor: pointer;
        }

        .control-select:focus {
            border-color: #4dabf7;
            box-shadow: 0 0 0 3px rgba(77, 171, 247, 0.2);
            outline: none;
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

        /* Attendance Table */
        .attendance-table-container {
            background-color: #fff;
            overflow-x: auto;
        }

        .attendance-header {
            display: flex;
            position: sticky;
            top: 0;
            z-index: 10;
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }

        .employee-column {
            width: 250px;
            min-width: 250px;
            padding: 12px 20px;
            position: sticky;
            left: 0;
            z-index: 11;
            background-color: #f8f9fa;
            border-right: 1px solid #e9ecef;
        }

        .days-header {
            display: flex;
        }

        .day-header {
            width: 40px;
            min-width: 40px;
            padding: 8px 5px;
            text-align: center;
            border-right: 1px solid #e9ecef;
            font-weight: 500;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .day-header.weekend {
            background-color: #f1f3f5;
        }

        .day-name {
            font-size: 0.75rem;
            color: #6c757d;
        }

        .day-number {
            font-size: 0.875rem;
            font-weight: 500;
        }

        .total-column {
            width: 100px;
            min-width: 100px;
            padding: 12px;
            text-align: center;
            border-right: 1px solid #e9ecef;
            background-color: #f8f9fa;
        }

        .attendance-body {
            display: flex;
            flex-direction: column;
        }

        .employee-row {
            display: flex;
            border-bottom: 1px solid #e9ecef;
            min-height: 60px;
        }

        .employee-info {
            width: 250px;
            min-width: 250px;
            padding: 12px 20px;
            position: sticky;
            left: 0;
            z-index: 5;
            background-color: #fff;
            border-right: 1px solid #e9ecef;
            display: flex;
            align-items: center;
        }

        .day-cell {
            width: 40px;
            min-width: 40px;
            padding: 8px;
            border-right: 1px solid #e9ecef;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .day-cell:hover {
            background-color: #f8f9fa !important;
        }

        .day-cell i {
            font-size: 16px;
            display: block;
            width: 100%;
        }

        /* Present - Green */
        .day-cell.present i {
            color: #155724;
        }

        /* Absent - Red */
        .day-cell.absent i {
            color: #721c24;
        }

        /* Late - Yellow */
        .day-cell.late i {
            color: #856404;
        }

        /* Half Day - Blue */
        .day-cell.half-day i {
            color: #004085;
        }

        /* Leave - Gray */
        .day-cell.leave i {
            color: #383d41;
        }

        /* Holiday - Dark Green */
        .day-cell.holiday i {
            color: #0f5132;
        }

        /* Day Off - Light Gray */
        .day-cell.day-off i {
            color: #383d41;
        }

        .total-cell {
            width: 100px;
            min-width: 100px;
            padding: 12px;
            text-align: center;
            border-right: 1px solid #e9ecef;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 2px;
        }

        .pluseadd {
            color: rgba(0, 0, 0, 0.774)
        }

        .minusadd {
            color: rgba(0, 0, 0, 0.164)
        }

        .total-cell .small {
            font-size: 0.75rem;
            color: #495057;
        }

        /* Avatar */
        .avatar-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
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

        /* Modal Styles */
        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        }

        .circular-chart {
            width: 80px;
            height: 80px;
        }

        .circle-bg {
            fill: none;
        }

        .circle {
            fill: none;
            stroke: #ffffff;
            stroke-width: 3;
            stroke-linecap: round;
            animation: progress 1s ease-out forwards;
        }

        @keyframes progress {
            0% {
                stroke-dasharray: 0 100;
            }
        }

        .avatar-circle {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .btn-close-white {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        /* Responsive */
        @media (max-width: 992px) {

            .employee-column,
            .employee-info {
                width: 200px;
                min-width: 200px;
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

            .employee-column,
            .employee-info {
                width: 160px;
                min-width: 160px;
            }

            .day-header,
            .day-cell {
                width: 36px;
                min-width: 36px;
            }
        }

        @media (max-width: 576px) {

            .employee-column,
            .employee-info {
                width: 140px;
                min-width: 140px;
                padding: 8px 12px;
                font-size: 0.875rem;
            }

            .day-header,
            .day-cell {
                width: 32px;
                min-width: 32px;
                padding: 4px;
            }

            .day-name {
                font-size: 0.65rem;
            }

            .day-number {
                font-size: 0.75rem;
            }

            .total-column,
            .total-cell {
                width: 80px;
                min-width: 80px;
                padding: 8px;
            }
        }

        .flatpickr-calendar {
            width: 14em;
        }

        .time-picker {
            background-color: white;
            cursor: pointer;
        }

        .flatpickr-time input {
            color: #495057;
            font-weight: 500;
        }

        .flatpickr-time .numInputWrapper span.arrowUp:after {
            border-bottom-color: #4dabf7;
        }

        .flatpickr-time .numInputWrapper span.arrowDown:after {
            border-top-color: #4dabf7;
        }

        .flatpickr-time .flatpickr-am-pm {
            color: #4dabf7;
            font-weight: bold;
        }

        .flatpickr-time input:hover,
        .flatpickr-time .flatpickr-am-pm:hover,
        .flatpickr-time input:focus,
        .flatpickr-time .flatpickr-am-pm:focus {
            background: rgba(77, 171, 247, 0.1);
        }
    </style>
</x-layout>
