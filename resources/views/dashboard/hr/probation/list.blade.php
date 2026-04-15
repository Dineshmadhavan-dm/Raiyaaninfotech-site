<x-layout>
    @section('title', 'Probation List')
    <div class="container-fluid py-4 px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-2">Probation List</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0">
                        <li class="breadcrumb-item">
                            <a href="#" class="text-decoration-none text-muted">
                                <i class="bi bi-people-fill me-2"></i>Employment
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-primary" aria-current="page">Probation</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('probationcreate') }}" class="btn btn-primary d-flex align-items-center">
                <i class="bi bi-plus-circle me-2"></i>Add Probation
            </a>
        </div>

        <x-message />

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
                            <button class="dropdown-toggle control-select" type="button" id="employeeDropdownFilter"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <span id="employeeFilterText">All Employees</span>
                            </button>
                            <ul class="dropdown-menu employee-dropdown p-2" aria-labelledby="employeeDropdownFilter"
                                style="width: 250px;">
                                <li>
                                    <input type="text" class="form-control form-control-sm mb-2"
                                        placeholder="Search employees..." id="employeeSearchFilter">
                                </li>
                                <li>
                                    <select id="employeeFilterSelect" class="form-select form-select-sm" size="8"
                                        style="width: 100%; border: none;">
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
                            <button class="dropdown-toggle control-select" type="button" id="departmentDropdownFilter"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <span id="departmentFilterText">All Departments</span>
                            </button>
                            <ul class="dropdown-menu department-dropdown p-2" aria-labelledby="departmentDropdownFilter"
                                style="width: 200px;">
                                <li>
                                    <input type="text" class="form-control form-control-sm mb-2"
                                        placeholder="Search departments..." id="departmentSearchFilter">
                                </li>
                                <li>
                                    <select id="departmentFilterSelect" class="form-select form-select-sm"
                                        size="8" style="width: 100%; border: none;">
                                        <option value="">All Departments</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->dep_id }}">
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
                            <button class="dropdown-toggle control-select" type="button" id="ratingDropdownFilter"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <span id="ratingFilterText">All Ratings</span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="ratingDropdownFilter">
                                <li><a class="dropdown-item" href="#" data-value="">All Ratings</a></li>
                                <li><a class="dropdown-item" href="#" data-value="1">Not Satisfied</a></li>
                                <li><a class="dropdown-item" href="#" data-value="2">Somewhat Satisfied</a></li>
                                <li><a class="dropdown-item" href="#" data-value="3">Satisfied</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-auto" style="margin-top: 1.3em;">
                        <div class="dropdown">
                            <button class="dropdown-toggle control-select" type="button" id="optionDropdownFilter"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <span id="optionFilterText">All Options</span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="optionDropdownFilter">
                                <li><a class="dropdown-item" href="#" data-value="">All Options</a></li>
                                <li><a class="dropdown-item" href="#" data-value="1">Confirmed</a></li>
                                <li><a class="dropdown-item" href="#" data-value="2">Extended</a></li>
                                <li><a class="dropdown-item" href="#" data-value="3">Terminated</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-auto" style="margin-top: 1.3em; display: none;" id="clearFiltersBtnContainer">
                        <button class="btn btn-outline-dark" id="clearFiltersBtn">
                            <i class="bi bi-x-circle me-1"></i> Clear Filters
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover" id="probationTable">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Department</th>
                                <th>Designation</th>
                                <th>Supervisor</th>
                                <th>Probation Period</th>
                                <th>Overall Rating</th>
                                <th>Appropriate Option</th>
                                <th>Evaluation Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="probationTableBody">
                            @forelse($probations as $probation)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $probation->employee->image ? asset('employee_images/' . $probation->employee->image) : asset('images/admin_default.jpg') }}"
                                                class="rounded-circle me-1" width="40" height="40">
                                            <div>
                                                <h6 class="mb-0">{{ $probation->employee->fullname }}</h6>
                                                <small
                                                    class="text-muted">{{ $probation->employee->employee_id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $probation->departmentRelation->dep_name ?? 'N/A' }}</td>
                                    <td>{{ $probation->designationRelation->des_name ?? 'N/A' }}</td>
                                    <td>{{ $probation->supervisor_name ?? 'N/A' }}</td>
                                    <td>
                                        @if ($probation->probation_from && $probation->probation_to)
                                            {{ \Carbon\Carbon::parse($probation->probation_from)->format('d-m-Y') }} to
                                            {{ \Carbon\Carbon::parse($probation->probation_to)->format('d-m-Y') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        <span
                                            class="badge p-1
                                            @if ($probation->overall_rating == 1) bg-danger
                                            @elseif($probation->overall_rating == 2) bg-warning
                                            @elseif($probation->overall_rating == 3) bg-success
                                            @else bg-secondary @endif">
                                            {{ $probation->getOverallRatingTextAttribute() }}
                                        </span>
                                    </td>
                                    <td>
                                        <span
                                            class="badge p-1
                                            @if ($probation->appropriate_option == 1) bg-success
                                            @elseif($probation->appropriate_option == 2) bg-warning
                                            @elseif($probation->appropriate_option == 3) bg-danger
                                            @else bg-secondary @endif">
                                            {{ $probation->getAppropriateOptionTextAttribute() }}
                                        </span>
                                    </td>
                                    <td>{{ $probation->date_of_evaluation->format('d-m-Y') ?? 'N/A' }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('probation.show', $probation->probation_id) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('probation.edit', $probation->probation_id) }}"
                                                class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-danger delete-probation"
                                                data-id="{{ $probation->probation_id }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="bi bi-inbox display-4 text-muted d-block mb-2"></i>
                                        <p class="text-muted">No probation records found.</p>
                                        <a href="{{ route('probationcreate') }}" class="btn btn-primary">
                                            <i class="bi bi-plus-circle me-2"></i>Create Probation
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Controls -->
                <div class="row justify-content-between align-items-center p-3">
                    <div class="col-auto">
                        <span id="probationShowingInfo">
                            Showing 1 to {{ count($probations) > 5 ? 5 : count($probations) }}
                            of {{ count($probations) }} entries
                        </span>
                    </div>
                    <div class="col-auto">
                        <div class="pagination-controls d-flex align-items-center">
                            <button class="pagination-button" id="prevProbationPage" disabled>
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <span class="page-info mx-2" id="probationPageInfo">
                                Page 1 of {{ ceil(count($probations) / 5) }}
                            </span>
                            <button class="pagination-button" id="nextProbationPage"
                                {{ count($probations) <= 5 ? 'disabled' : '' }}>
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .table th {
            border-top: none;
            font-weight: 600;
            color: #6c757d;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table td {
            vertical-align: middle;
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
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get all probations data
            const probations = @json($probations);
            const employees = @json($employees);
            const departments = @json($departments);

            // Filter elements
            const employeeFilterSelect = document.getElementById('employeeFilterSelect');
            const employeeFilterText = document.getElementById('employeeFilterText');
            const departmentFilterSelect = document.getElementById('departmentFilterSelect');
            const departmentFilterText = document.getElementById('departmentFilterText');
            const ratingFilterItems = document.querySelectorAll('#ratingDropdownFilter ~ ul a');
            const ratingFilterText = document.getElementById('ratingFilterText');
            const optionFilterItems = document.querySelectorAll('#optionDropdownFilter ~ ul a');
            const optionFilterText = document.getElementById('optionFilterText');
            const entriesPerPageSelect = document.getElementById('entriesPerPage');
            const clearFiltersBtn = document.getElementById('clearFiltersBtn');
            const clearFiltersBtnContainer = document.getElementById('clearFiltersBtnContainer');

            // Pagination elements
            const prevProbationPageBtn = document.getElementById('prevProbationPage');
            const nextProbationPageBtn = document.getElementById('nextProbationPage');
            const probationPageInfo = document.getElementById('probationPageInfo');
            const probationShowingInfo = document.getElementById('probationShowingInfo');

            // Initialize pagination and filters
            let currentEmployeeFilter = '';
            let currentDepartmentFilter = '';
            let currentRatingFilter = '';
            let currentOptionFilter = '';
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

            // Rating filter
            ratingFilterItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentRatingFilter = this.getAttribute('data-value');
                    ratingFilterText.textContent = this.textContent;
                    currentPage = 1;
                    applyFilters();
                    toggleClearFiltersButton();
                });
            });

            // Option filter
            optionFilterItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentOptionFilter = this.getAttribute('data-value');
                    optionFilterText.textContent = this.textContent;
                    currentPage = 1;
                    applyFilters();
                    toggleClearFiltersButton();
                });
            });

            entriesPerPageSelect.addEventListener('change', function() {
                currentEntriesPerPage = parseInt(this.value);
                currentPage = 1;
                applyFilters();
            });

            // Pagination button event listeners
            prevProbationPageBtn.addEventListener('click', function() {
                if (currentPage > 1) {
                    currentPage--;
                    applyFilters();
                }
            });

            nextProbationPageBtn.addEventListener('click', function() {
                if (currentPage < totalPages) {
                    currentPage++;
                    applyFilters();
                }
            });

            // Clear filters button
            clearFiltersBtn.addEventListener('click', function() {
                employeeFilterSelect.value = '';
                departmentFilterSelect.value = '';
                ratingFilterItems[0].click(); // Select "All Ratings"
                optionFilterItems[0].click(); // Select "All Options"
                currentEmployeeFilter = '';
                currentDepartmentFilter = '';
                currentRatingFilter = '';
                currentOptionFilter = '';
                currentPage = 1;
                applyFilters();
                toggleClearFiltersButton();
            });

            // Search functionality for dropdowns
            document.getElementById('employeeSearchFilter').addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const options = document.querySelectorAll('#employeeFilterSelect option');

                for (let i = 0; i < options.length; i++) {
                    const option = options[i];
                    const text = option.text.toLowerCase();
                    option.style.display = text.includes(searchTerm) ? '' : 'none';
                }
            });

            document.getElementById('departmentSearchFilter').addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const options = document.querySelectorAll('#departmentFilterSelect option');

                for (let i = 0; i < options.length; i++) {
                    const option = options[i];
                    const text = option.text.toLowerCase();
                    option.style.display = text.includes(searchTerm) ? '' : 'none';
                }
            });

            function applyFilters() {
                let filteredData = [...probations];

                // Apply employee filter
                if (currentEmployeeFilter) {
                    filteredData = filteredData.filter(probation =>
                        probation.employee && probation.employee.emp_id == currentEmployeeFilter
                    );
                }

                // Apply department filter
                if (currentDepartmentFilter) {
                    filteredData = filteredData.filter(probation =>
                        probation.employee &&
                        probation.employee.cur_department &&
                        probation.employee.cur_department == currentDepartmentFilter
                    );
                }

                // Apply rating filter
                if (currentRatingFilter !== '') {
                    filteredData = filteredData.filter(probation =>
                        probation.overall_rating == currentRatingFilter
                    );
                }

                // Apply option filter
                if (currentOptionFilter !== '') {
                    filteredData = filteredData.filter(probation =>
                        probation.appropriate_option == currentOptionFilter
                    );
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

            // Helper function to format the date as DD-MM-YY
            function formatDateToDDMMYY(dateString) {
                const date = new Date(dateString);
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const year = String(date.getFullYear()); // Get last 2 digits of year

                return `${day}-${month}-${year}`;
            }

            function updateTable(data, totalItems, startIndex, endIndex) {
                const tableBody = document.getElementById('probationTableBody');
                tableBody.innerHTML = '';

                if (data.length === 0) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="bi bi-inbox display-4 text-muted d-block mb-2"></i>
                                <p class="text-muted">No probation records found.</p>
                                <a href="{{ route('probationcreate') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-2"></i>Create Probation
                                </a>
                            </td>
                        </tr>
                    `;
                    return;
                }

                data.forEach(probation => {
                    // Safely access nested properties with fallbacks
                    const employeeImage = probation.employee && probation.employee.image ?
                        '/employee_images/' + probation.employee.image :
                        '/images/admin_default.jpg';

                    const employeeName = probation.employee && probation.employee.fullname ?
                        probation.employee.fullname :
                        'N/A';

                    const employeeId = probation.employee && probation.employee.employee_id ?
                        probation.employee.employee_id :
                        'N/A';

                    const departmentName = probation.department_relation && probation
                        .department_relation.dep_name ?
                        probation.department_relation.dep_name :
                        (probation.departmentRelation && probation.departmentRelation.dep_name ?
                            probation.departmentRelation.dep_name :
                            'N/A');

                    const designationName = probation.designation_relation && probation
                        .designation_relation.des_name ?
                        probation.designation_relation.des_name :
                        (probation.designationRelation && probation.designationRelation.des_name ?
                            probation.designationRelation.des_name :
                            'N/A');

                    const probationFrom = probation.probation_from ?
                        formatDateToDDMMYY(probation.probation_from) :
                        'N/A';

                    const probationTo = probation.probation_to ?
                        formatDateToDDMMYY(probation.probation_to) :
                        'N/A';

                    const evaluationDate = probation.date_of_evaluation ?
                        formatDateToDDMMYY(probation.date_of_evaluation) :
                        'N/A';

                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="${employeeImage}"
                                    class="rounded-circle me-1" width="40" height="40">
                                <div>
                                    <h6 class="mb-0">${employeeName}</h6>
                                    <small class="text-muted">${employeeId}</small>
                                </div>
                            </div>
                        </td>
                        <td>${departmentName}</td>
                        <td>${designationName}</td>
                        <td>${probation.supervisor_name || 'N/A'}</td>
                        <td>${probationFrom} to ${probationTo}</td>
                        <td>
                            <span class="badge p-1
                                ${probation.overall_rating == 1 ? 'bg-danger' :
                                  probation.overall_rating == 2 ? 'bg-warning' :
                                  probation.overall_rating == 3 ? 'bg-success' : 'bg-secondary'}">
                                ${probation.overall_rating == 1 ? 'Not Satisfied' :
                                  probation.overall_rating == 2 ? 'Somewhat Satisfied' :
                                  probation.overall_rating == 3 ? 'Satisfied' : 'Not Rated'}
                            </span>
                        </td>
                        <td>
                            <span class="badge p-1
                                ${probation.appropriate_option == 1 ? 'bg-success' :
                                  probation.appropriate_option == 2 ? 'bg-warning' :
                                  probation.appropriate_option == 3 ? 'bg-danger' : 'bg-secondary'}">
                                ${probation.appropriate_option == 1 ? 'Confirmed' :
                                  probation.appropriate_option == 2 ? 'Extended' :
                                  probation.appropriate_option == 3 ? 'Terminated' : 'Not Decided'}
                            </span>
                        </td>
                        <td>${evaluationDate}</td>
                        <td>
                            <div class="btn-group">
                                <a href="/dashboard/employees/probation/${probation.probation_id}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="/dashboard/employees/probation/${probation.probation_id}/edit" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-danger delete-probation"
                                    data-id="${probation.probation_id}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });

                if (totalItems === 0) {
                    probationShowingInfo.textContent = 'Showing 0 to 0 of 0 entries';
                } else {
                    probationShowingInfo.textContent =
                        `Showing ${startIndex + 1} to ${endIndex} of ${totalItems} entries`;
                }
            }

            function updatePaginationControls(totalItems) {
                totalPages = Math.ceil(totalItems / currentEntriesPerPage);
                prevProbationPageBtn.disabled = currentPage <= 1;
                nextProbationPageBtn.disabled = currentPage >= totalPages;

                if (totalPages === 0) {
                    probationPageInfo.textContent = 'Page 0 of 0';
                } else {
                    probationPageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
                }
            }

            function toggleClearFiltersButton() {
                if (currentEmployeeFilter || currentDepartmentFilter ||
                    currentRatingFilter !== '' || currentOptionFilter !== '') {
                    clearFiltersBtnContainer.style.display = 'block';
                } else {
                    clearFiltersBtnContainer.style.display = 'none';
                }
            }

            // Initialize the table with all data
            applyFilters();

            // Delete probation functionality
            document.addEventListener('click', function(e) {
                if (e.target.closest('.delete-probation')) {
                    const button = e.target.closest('.delete-probation');
                    const probationId = button.getAttribute('data-id');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Probation record will be deleted!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Send AJAX request to delete the probation
                            fetch(`/dashboard/employees/probation/delete/${probationId}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire(
                                            'Deleted!',
                                            'Probation record has been deleted.',
                                            'success'
                                        ).then(() => {
                                            // Remove the row from the table
                                            button.closest('tr').remove();

                                            // Check if table is empty and show message
                                            if (document.querySelectorAll(
                                                    '#probationTableBody tr')
                                                .length === 0) {
                                                location.reload();
                                            }
                                        });
                                    } else {
                                        Swal.fire(
                                            'Error!',
                                            'There was a problem deleting the probation record.',
                                            'error'
                                        );
                                    }
                                })
                                .catch(error => {
                                    Swal.fire(
                                        'Error!',
                                        'There was a problem deleting the probation record.',
                                        'error'
                                    );
                                });
                        }
                    });
                }
            });
        });
    </script>
</x-layout>
