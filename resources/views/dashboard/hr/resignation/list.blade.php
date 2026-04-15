<x-layout>
    @section('title', 'Resignation List')
    <div class="container-fluid py-4 px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-2">Resignation List</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0">
                        <li class="breadcrumb-item">
                            <a href="#" class="text-decoration-none text-muted">
                                <i class="bi bi-people-fill me-2"></i>Employment
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-primary" aria-current="page">Resignation</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('resignationcreate') }}" class="btn btn-primary d-flex align-items-center">
                <i class="bi bi-plus-circle me-2"></i>Add Resignation
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
                            <button class="dropdown-toggle control-select" type="button"
                                id="resignationTypeDropdownFilter" data-bs-toggle="dropdown" aria-expanded="false">
                                <span id="resignationTypeFilterText">All Types</span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="resignationTypeDropdownFilter">
                                <li><a class="dropdown-item" href="#" data-value="">All Types</a></li>
                                <li><a class="dropdown-item" href="#" data-value="1">Voluntary</a></li>
                                <li><a class="dropdown-item" href="#" data-value="0">Involuntary</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-auto" style="margin-top: 1.3em;">
                        <div class="dropdown">
                            <button class="dropdown-toggle control-select" type="button"
                                id="noticePeriodDropdownFilter" data-bs-toggle="dropdown" aria-expanded="false">
                                <span id="noticePeriodFilterText">All Notice Periods</span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="noticePeriodDropdownFilter">
                                <li><a class="dropdown-item" href="#" data-value="">All Notice Periods</a>
                                </li>
                                <li><a class="dropdown-item" href="#" data-value="1">With Notice Period</a>
                                </li>
                                <li><a class="dropdown-item" href="#" data-value="0">Without Notice Period</a>
                                </li>
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
                    <table class="table table-hover" id="resignationTable">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Employee Email</th>
                                <th>Department</th>
                                <th>Designation</th>
                                <th>Resignation Type</th>
                                <th>Notice Period</th>
                                <th>Resignation Date</th>
                                <th>Can Be Rehired</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="resignationTableBody">
                            @forelse($resignations as $resignation)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $resignation->employee->image ? asset('employee_images/' . $resignation->employee->image) : asset('images/admin_default.jpg') }}"
                                                class="rounded-circle me-1" width="40" height="40">
                                            <div>
                                                <h6 class="mb-0">{{ $resignation->employee->fullname }}</h6>
                                                <small
                                                    class="text-muted">{{ $resignation->employee->employee_id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $resignation->employee->email_company ?? 'N/A' }}</td>
                                    <td>{{ $resignation->departmentRelation->dep_name ?? 'N/A' }}</td>
                                    <td>{{ $resignation->designationRelation->des_name ?? 'N/A' }}</td>
                                    <td>
                                        <span
                                            class="badge p-1 {{ $resignation->is_voluntary ? 'bg-info' : 'bg-danger' }}">
                                            {{ $resignation->is_voluntary ? 'Voluntary' : 'Involuntary' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span
                                            class="badge p-1 {{ $resignation->has_notice_period ? 'bg-success' : 'bg-warning' }}">
                                            {{ $resignation->has_notice_period ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                    <td>{{ $resignation->date_of_resignation->format('d-m-Y') ?? 'N/A' }}</td>
                                    <td>
                                        <span
                                            class="badge p-1 {{ $resignation->can_be_rehired ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $resignation->can_be_rehired ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('resignation.show', $resignation->resignation_id) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('resignation.edit', $resignation->resignation_id) }}"
                                                class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            @can('hr->resignation delete')
                                                <button class="btn btn-sm btn-outline-danger delete-resignation"
                                                    data-id="{{ $resignation->resignation_id }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="bi bi-inbox display-4 text-muted d-block mb-2"></i>
                                        <p class="text-muted">No resignations found.</p>
                                        <a href="{{ route('resignationcreate') }}" class="btn btn-primary">
                                            <i class="bi bi-plus-circle me-2"></i>Create Resignation
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
                        <span id="resignationShowingInfo">
                            Showing 1 to {{ count($resignations) > 5 ? 5 : count($resignations) }}
                            of {{ count($resignations) }} entries
                        </span>
                    </div>
                    <div class="col-auto">
                        <div class="pagination-controls d-flex align-items-center">
                            <button class="pagination-button" id="prevResignationPage" disabled>
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <span class="page-info mx-2" id="resignationPageInfo">
                                Page 1 of {{ ceil(count($resignations) / 5) }}
                            </span>
                            <button class="pagination-button" id="nextResignationPage"
                                {{ count($resignations) <= 5 ? 'disabled' : '' }}>
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
        const userCanDeleteResignation = @json(auth()->user()->can('hr->resignation delete'));
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get all resignations data
            const resignations = @json($resignations);
            const employees = @json($employees);
            const departments = @json($departments);

            // Filter elements
            const employeeFilterSelect = document.getElementById('employeeFilterSelect');
            const employeeFilterText = document.getElementById('employeeFilterText');
            const departmentFilterSelect = document.getElementById('departmentFilterSelect');
            const departmentFilterText = document.getElementById('departmentFilterText');
            const resignationTypeFilterItems = document.querySelectorAll('#resignationTypeDropdownFilter ~ ul a');
            const resignationTypeFilterText = document.getElementById('resignationTypeFilterText');
            const noticePeriodFilterItems = document.querySelectorAll('#noticePeriodDropdownFilter ~ ul a');
            const noticePeriodFilterText = document.getElementById('noticePeriodFilterText');
            const entriesPerPageSelect = document.getElementById('entriesPerPage');
            const clearFiltersBtn = document.getElementById('clearFiltersBtn');
            const clearFiltersBtnContainer = document.getElementById('clearFiltersBtnContainer');

            // Pagination elements
            const prevResignationPageBtn = document.getElementById('prevResignationPage');
            const nextResignationPageBtn = document.getElementById('nextResignationPage');
            const resignationPageInfo = document.getElementById('resignationPageInfo');
            const resignationShowingInfo = document.getElementById('resignationShowingInfo');

            // Initialize pagination and filters
            let currentEmployeeFilter = '';
            let currentDepartmentFilter = '';
            let currentResignationTypeFilter = '';
            let currentNoticePeriodFilter = '';
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

            // Resignation type filter
            resignationTypeFilterItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentResignationTypeFilter = this.getAttribute('data-value');
                    resignationTypeFilterText.textContent = this.textContent;
                    currentPage = 1;
                    applyFilters();
                    toggleClearFiltersButton();
                });
            });

            // Notice period filter
            noticePeriodFilterItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentNoticePeriodFilter = this.getAttribute('data-value');
                    noticePeriodFilterText.textContent = this.textContent;
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
            prevResignationPageBtn.addEventListener('click', function() {
                if (currentPage > 1) {
                    currentPage--;
                    applyFilters();
                }
            });

            nextResignationPageBtn.addEventListener('click', function() {
                if (currentPage < totalPages) {
                    currentPage++;
                    applyFilters();
                }
            });

            // Clear filters button
            clearFiltersBtn.addEventListener('click', function() {
                employeeFilterSelect.value = '';
                departmentFilterSelect.value = '';
                resignationTypeFilterItems[0].click(); // Select "All Types"
                noticePeriodFilterItems[0].click(); // Select "All Notice Periods"
                currentEmployeeFilter = '';
                currentDepartmentFilter = '';
                currentResignationTypeFilter = '';
                currentNoticePeriodFilter = '';
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
                let filteredData = [...resignations];

                // Apply employee filter
                if (currentEmployeeFilter) {
                    filteredData = filteredData.filter(resignation =>
                        resignation.employee && resignation.employee.emp_id == currentEmployeeFilter
                    );
                }

                // Apply department filter
                if (currentDepartmentFilter) {
                    filteredData = filteredData.filter(resignation =>
                        resignation.employee &&
                        resignation.employee.cur_department &&
                        resignation.employee.cur_department == currentDepartmentFilter
                    );
                }

                // Apply resignation type filter
                if (currentResignationTypeFilter !== '') {
                    filteredData = filteredData.filter(resignation =>
                        resignation.is_voluntary == currentResignationTypeFilter
                    );
                }

                // Apply notice period filter
                if (currentNoticePeriodFilter !== '') {
                    filteredData = filteredData.filter(resignation =>
                        resignation.has_notice_period == currentNoticePeriodFilter
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
                const tableBody = document.getElementById('resignationTableBody');
                tableBody.innerHTML = '';

                if (data.length === 0) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="bi bi-inbox display-4 text-muted d-block mb-2"></i>
                                <p class="text-muted">No resignations found.</p>
                                <a href="{{ route('resignationcreate') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-2"></i>Create Resignation
                                </a>
                            </td>
                        </tr>
                    `;
                    return;
                }

                data.forEach(resignation => {
                    // Safely access nested properties with fallbacks
                    const employeeImage = resignation.employee && resignation.employee.image ?
                        '/employee_images/' + resignation.employee.image :
                        '/images/admin_default.jpg';

                    const employeeName = resignation.employee && resignation.employee.fullname ?
                        resignation.employee.fullname :
                        'N/A';

                    const employeeId = resignation.employee && resignation.employee.employee_id ?
                        resignation.employee.employee_id :
                        'N/A';

                    const employeeEmail = resignation.employee.email_company ?
                        resignation.employee.email_company :
                        'N/A';

                    const departmentName = resignation.department_relation && resignation
                        .department_relation.dep_name ?
                        resignation.department_relation.dep_name :
                        (resignation.departmentRelation && resignation.departmentRelation.dep_name ?
                            resignation.departmentRelation.dep_name :
                            'N/A');

                    const designationName = resignation.designation_relation && resignation
                        .designation_relation.des_name ?
                        resignation.designation_relation.des_name :
                        (resignation.designationRelation && resignation.designationRelation.des_name ?
                            resignation.designationRelation.des_name :
                            'N/A');

                    const resignationDate = resignation.date_of_resignation ?
                        formatDateToDDMMYY(resignation.date_of_resignation) :
                        'N/A';

                    const row = document.createElement('tr');

                    let deleteButton = '';
                    if (userCanDeleteResignation) {
                        deleteButton = `
        <button class="btn btn-sm btn-outline-danger delete-resignation"
            data-id="${resignation.resignation_id}">
            <i class="bi bi-trash"></i>
        </button>
    `;
                    }
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
                        <td>${employeeEmail}</td>
                        <td>${departmentName}</td>
                        <td>${designationName}</td>
                        <td>
                            <span class="badge p-1 ${resignation.is_voluntary ? 'bg-info' : 'bg-danger'}">
                                ${resignation.is_voluntary ? 'Voluntary' : 'Involuntary'}
                            </span>
                        </td>
                        <td>
                            <span class="badge p-1 ${resignation.has_notice_period ? 'bg-success' : 'bg-warning'}">
                                ${resignation.has_notice_period ? 'Yes' : 'No'}
                            </span>
                        </td>
                        <td>${resignationDate}</td>
                        <td>
                            <span class="badge p-1 ${resignation.can_be_rehired ? 'bg-success' : 'bg-secondary'}">
                                ${resignation.can_be_rehired ? 'Yes' : 'No'}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="/dashboard/employees/resignation/${resignation.resignation_id}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="/dashboard/employees/resignation/${resignation.resignation_id}/edit" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                 ${deleteButton}
                            </div>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });

                if (totalItems === 0) {
                    resignationShowingInfo.textContent = 'Showing 0 to 0 of 0 entries';
                } else {
                    resignationShowingInfo.textContent =
                        `Showing ${startIndex + 1} to ${endIndex} of ${totalItems} entries`;
                }
            }

            function updatePaginationControls(totalItems) {
                totalPages = Math.ceil(totalItems / currentEntriesPerPage);
                prevResignationPageBtn.disabled = currentPage <= 1;
                nextResignationPageBtn.disabled = currentPage >= totalPages;

                if (totalPages === 0) {
                    resignationPageInfo.textContent = 'Page 0 of 0';
                } else {
                    resignationPageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
                }
            }

            function toggleClearFiltersButton() {
                if (currentEmployeeFilter || currentDepartmentFilter ||
                    currentResignationTypeFilter !== '' || currentNoticePeriodFilter !== '') {
                    clearFiltersBtnContainer.style.display = 'block';
                } else {
                    clearFiltersBtnContainer.style.display = 'none';
                }
            }

            // Initialize the table with all data
            applyFilters();

            // Delete resignation functionality
            document.addEventListener('click', function(e) {
                if (e.target.closest('.delete-resignation')) {
                    const button = e.target.closest('.delete-resignation');
                    const resignationId = button.getAttribute('data-id');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Resignation record will be deleted!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Send AJAX request to delete the resignation
                            fetch(`/dashboard/employees/resignation/delete/${resignationId}`, {
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
                                            'Resignation record has been deleted.',
                                            'success'
                                        ).then(() => {
                                            // Remove the row from the table
                                            button.closest('tr').remove();

                                            // Check if table is empty and show message
                                            if (document.querySelectorAll(
                                                    '#resignationTableBody tr')
                                                .length === 0) {
                                                location.reload();
                                            }
                                        });
                                    } else {
                                        Swal.fire(
                                            'Error!',
                                            'There was a problem deleting the resignation record.',
                                            'error'
                                        );
                                    }
                                })
                                .catch(error => {
                                    Swal.fire(
                                        'Error!',
                                        'There was a problem deleting the resignation record.',
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
