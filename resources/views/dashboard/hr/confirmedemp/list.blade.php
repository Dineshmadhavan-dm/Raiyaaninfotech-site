<x-layout>
    @section('title', 'Confirmed Employees')
    <div class="container-fluid py-4 px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-2">Confirmed Employees List</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0">
                        <li class="breadcrumb-item">
                            <a href="#" class="text-decoration-none text-muted">
                                <i class="bi bi-people-fill me-2"></i>Employment
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-primary" aria-current="page">Confirmed Employees</li>
                    </ol>
                </nav>
            </div>

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
                    <table class="table table-hover" id="confirmedTable">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Department</th>
                                <th>Designation</th>
                                <th>Supervisor</th>
                                <th>Probation Period</th>

                                <th>Confirmation Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="confirmedTableBody">
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

                                    <td>{{ $probation->date_of_evaluation->format('d-m-Y') ?? 'N/A' }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <!-- Only show eye button for confirmed employees -->
                                            <a href="{{ route('confirmedshow', $probation->probation_id) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="bi bi-inbox display-4 text-muted d-block mb-2"></i>
                                        <p class="text-muted">No confirmed employees found.</p>
                                        <a href="{{ route('probationlist') }}" class="btn btn-primary">
                                            <i class="bi bi-arrow-left me-2"></i>Go to Probation List
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
                        <span id="confirmedShowingInfo">
                            Showing 1 to {{ count($probations) > 5 ? 5 : count($probations) }}
                            of {{ count($probations) }} entries
                        </span>
                    </div>
                    <div class="col-auto">
                        <div class="pagination-controls d-flex align-items-center">
                            <button class="pagination-button" id="prevConfirmedPage" disabled>
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <span class="page-info mx-2" id="confirmedPageInfo">
                                Page 1 of {{ ceil(count($probations) / 5) }}
                            </span>
                            <button class="pagination-button" id="nextConfirmedPage"
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
            // Get all confirmed employees data
            const confirmedEmployees = @json($probations);
            const employees = @json($employees);
            const departments = @json($departments);

            // Filter elements
            const employeeFilterSelect = document.getElementById('employeeFilterSelect');
            const employeeFilterText = document.getElementById('employeeFilterText');
            const departmentFilterSelect = document.getElementById('departmentFilterSelect');
            const departmentFilterText = document.getElementById('departmentFilterText');
            const entriesPerPageSelect = document.getElementById('entriesPerPage');
            const clearFiltersBtn = document.getElementById('clearFiltersBtn');
            const clearFiltersBtnContainer = document.getElementById('clearFiltersBtnContainer');

            // Pagination elements
            const prevConfirmedPageBtn = document.getElementById('prevConfirmedPage');
            const nextConfirmedPageBtn = document.getElementById('nextConfirmedPage');
            const confirmedPageInfo = document.getElementById('confirmedPageInfo');
            const confirmedShowingInfo = document.getElementById('confirmedShowingInfo');

            // Initialize pagination and filters
            let currentEmployeeFilter = '';
            let currentDepartmentFilter = '';
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

            entriesPerPageSelect.addEventListener('change', function() {
                currentEntriesPerPage = parseInt(this.value);
                currentPage = 1;
                applyFilters();
            });

            // Pagination button event listeners
            prevConfirmedPageBtn.addEventListener('click', function() {
                if (currentPage > 1) {
                    currentPage--;
                    applyFilters();
                }
            });

            nextConfirmedPageBtn.addEventListener('click', function() {
                if (currentPage < totalPages) {
                    currentPage++;
                    applyFilters();
                }
            });

            // Clear filters button
            clearFiltersBtn.addEventListener('click', function() {
                employeeFilterSelect.value = '';
                departmentFilterSelect.value = '';
                currentEmployeeFilter = '';
                currentDepartmentFilter = '';
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
                let filteredData = [...confirmedEmployees];

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
                const tableBody = document.getElementById('confirmedTableBody');
                tableBody.innerHTML = '';

                if (data.length === 0) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="bi bi-inbox display-4 text-muted d-block mb-2"></i>
                                <p class="text-muted">No confirmed employees found.</p>
                                <a href="{{ route('probationlist') }}" class="btn btn-primary">
                                    <i class="bi bi-arrow-left me-2"></i>Go to Probation List
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

                        <td>${evaluationDate}</td>
                        <td>
                            <div class="btn-group">
                                <a href="/dashboard/employees/confirmedshow/${probation.probation_id}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });

                if (totalItems === 0) {
                    confirmedShowingInfo.textContent = 'Showing 0 to 0 of 0 entries';
                } else {
                    confirmedShowingInfo.textContent =
                        `Showing ${startIndex + 1} to ${endIndex} of ${totalItems} entries`;
                }
            }

            function updatePaginationControls(totalItems) {
                totalPages = Math.ceil(totalItems / currentEntriesPerPage);
                prevConfirmedPageBtn.disabled = currentPage <= 1;
                nextConfirmedPageBtn.disabled = currentPage >= totalPages;

                if (totalPages === 0) {
                    confirmedPageInfo.textContent = 'Page 0 of 0';
                } else {
                    confirmedPageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
                }
            }

            function toggleClearFiltersButton() {
                if (currentEmployeeFilter || currentDepartmentFilter) {
                    clearFiltersBtnContainer.style.display = 'block';
                } else {
                    clearFiltersBtnContainer.style.display = 'none';
                }
            }

            // Initialize the table with all data
            applyFilters();
        });
    </script>
</x-layout>
