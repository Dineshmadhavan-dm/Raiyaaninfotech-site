<x-layout>
    @section('title', 'Handover List')
    <div class="container-fluid py-4 px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-2">Handover List</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0">
                        <li class="breadcrumb-item">
                            <a href="#" class="text-decoration-none text-muted">
                                <i class="bi bi-people-fill me-2"></i>Employment
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-primary" aria-current="page">Handover</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('handovercreate') }}" class="btn btn-primary d-flex align-items-center">
                <i class="bi bi-plus-circle me-2"></i>Add Handover
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
                            <button class="dropdown-toggle control-select" type="button" id="reasonDropdownFilter"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <span id="reasonFilterText">All Reasons</span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="reasonDropdownFilter">
                                <li><a class="dropdown-item" href="#" data-value="">All Reasons</a></li>
                                <li><a class="dropdown-item" href="#" data-value="1">Vacation</a></li>
                                <li><a class="dropdown-item" href="#" data-value="3">Transfer</a></li>
                                <li><a class="dropdown-item" href="#" data-value="2">End of Employment</a></li>
                                <li><a class="dropdown-item" href="#" data-value="0">Other</a></li>
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
                    <table class="table table-hover" id="handoverTable">
                        <thead>
                            <tr>
                                <th>Handing Over Employee</th>
                                <th>Taking Over Employee</th>
                                <th>Reason</th>
                                <th>Handover Date</th>
                                <th>Tasks</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="handoverTableBody">
                            @forelse($handovers as $handover)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $handover->handoverEmployee->image ? asset('employee_images/' . $handover->handoverEmployee->image) : asset('images/admin_default.jpg') }}"
                                                class="rounded-circle me-1" width="40" height="40">
                                            <div>
                                                <h6 class="mb-0">{{ $handover->handoverEmployee->fullname }}</h6>
                                                <small
                                                    class="text-muted">{{ $handover->handoverEmployee->employee_id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $handover->takeoverEmployee->image ? asset('employee_images/' . $handover->takeoverEmployee->image) : asset('images/admin_default.jpg') }}"
                                                class="rounded-circle me-1" width="40" height="40">
                                            <div>
                                                <h6 class="mb-0">{{ $handover->takeoverEmployee->fullname }}</h6>
                                                <small
                                                    class="text-muted">{{ $handover->takeoverEmployee->employee_id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            class="badge p-2
        @if ($handover->reason == 2) bg-danger   {{-- End of Employment --}}
        @elseif($handover->reason == 3) bg-info {{-- Transfer --}}
        @elseif($handover->reason == 1) bg-success {{-- Vacation --}}
        @elseif($handover->reason == 0) bg-secondary {{-- Other --}}
        @else bg-dark @endif">

                                            {{-- Show "Other" text if chosen --}}
                                            @if ($handover->reason == 0 && $handover->reason_other)
                                                {{ $handover->reason_other }}
                                            @else
                                                {{ $handover->handover_reason_text }}
                                            @endif
                                        </span>
                                    </td>

                                    <td>{{ $handover->handover_date->format('d-m-Y') ?? 'N/A' }}</td>
                                    <td>
                                        @if ($handover->tasks && count(json_decode($handover->tasks, true)) > 0)
                                            {{ count(json_decode($handover->tasks, true)) }} tasks
                                        @else
                                            No tasks
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('handover.show', $handover->handover_id) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('handover.edit', $handover->handover_id) }}"
                                                class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-danger delete-handover"
                                                data-id="{{ $handover->handover_id }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="bi bi-inbox display-4 text-muted d-block mb-2"></i>
                                        <p class="text-muted">No handover records found.</p>
                                        <a href="{{ route('handovercreate') }}" class="btn btn-primary">
                                            <i class="bi bi-plus-circle me-2"></i>Create Handover
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
                        <span id="handoverShowingInfo">
                            Showing 1 to {{ count($handovers) > 5 ? 5 : count($handovers) }}
                            of {{ count($handovers) }} entries
                        </span>
                    </div>
                    <div class="col-auto">
                        <div class="pagination-controls d-flex align-items-center">
                            <button class="pagination-button" id="prevHandoverPage" disabled>
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <span class="page-info mx-2" id="handoverPageInfo">
                                Page 1 of {{ ceil(count($handovers) / 5) }}
                            </span>
                            <button class="pagination-button" id="nextHandoverPage"
                                {{ count($handovers) <= 5 ? 'disabled' : '' }}>
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
            // Get all handovers data
            const handovers = @json($handovers);
            const employees = @json($employees);
            const departments = @json($departments);

            // Filter elements
            const employeeFilterSelect = document.getElementById('employeeFilterSelect');
            const employeeFilterText = document.getElementById('employeeFilterText');
            const departmentFilterSelect = document.getElementById('departmentFilterSelect');
            const departmentFilterText = document.getElementById('departmentFilterText');
            const reasonFilterItems = document.querySelectorAll('#reasonDropdownFilter ~ ul a');
            const reasonFilterText = document.getElementById('reasonFilterText');
            const entriesPerPageSelect = document.getElementById('entriesPerPage');
            const clearFiltersBtn = document.getElementById('clearFiltersBtn');
            const clearFiltersBtnContainer = document.getElementById('clearFiltersBtnContainer');

            // Pagination elements
            const prevHandoverPageBtn = document.getElementById('prevHandoverPage');
            const nextHandoverPageBtn = document.getElementById('nextHandoverPage');
            const handoverPageInfo = document.getElementById('handoverPageInfo');
            const handoverShowingInfo = document.getElementById('handoverShowingInfo');

            // Initialize pagination and filters
            let currentEmployeeFilter = '';
            let currentDepartmentFilter = '';
            let currentReasonFilter = '';
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

            // Reason filter
            // Reason filter
            reasonFilterItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentReasonFilter = this.getAttribute('data-value');
                    reasonFilterText.textContent = this.textContent;
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
            prevHandoverPageBtn.addEventListener('click', function() {
                if (currentPage > 1) {
                    currentPage--;
                    applyFilters();
                }
            });

            nextHandoverPageBtn.addEventListener('click', function() {
                if (currentPage < totalPages) {
                    currentPage++;
                    applyFilters();
                }
            });

            // Clear filters button
            clearFiltersBtn.addEventListener('click', function() {
                employeeFilterSelect.value = '';
                departmentFilterSelect.value = '';
                reasonFilterItems[0].click(); // Select "All Reasons"
                currentEmployeeFilter = '';
                currentDepartmentFilter = '';
                currentReasonFilter = '';
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
                let filteredData = [...handovers];

                // Apply employee filter
                if (currentEmployeeFilter) {
                    filteredData = filteredData.filter(handover =>
                        (handover.handover_employee && handover.handover_employee.emp_id ==
                            currentEmployeeFilter) ||
                        (handover.takeover_employee && handover.takeover_employee.emp_id ==
                            currentEmployeeFilter)
                    );
                }

                // Apply department filter
                if (currentDepartmentFilter) {
                    filteredData = filteredData.filter(handover =>
                        (handover.handover_employee && handover.handover_employee.cur_department &&
                            handover.handover_employee.cur_department == currentDepartmentFilter) ||
                        (handover.takeover_employee && handover.takeover_employee.cur_department &&
                            handover.takeover_employee.cur_department == currentDepartmentFilter)
                    );
                }

                // Apply reason filter
                if (currentReasonFilter !== '') {
                    filteredData = filteredData.filter(handover =>
                        String(handover.reason) === String(currentReasonFilter)
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
                const tableBody = document.getElementById('handoverTableBody');
                tableBody.innerHTML = '';

                if (data.length === 0) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="bi bi-inbox display-4 text-muted d-block mb-2"></i>
                                <p class="text-muted">No handover records found.</p>
                                <a href="{{ route('handovercreate') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-2"></i>Create Handover
                                </a>
                            </td>
                        </tr>
                    `;
                    return;
                }

                data.forEach(handover => {
                    // Safely access nested properties with fallbacks
                    const handoverEmployeeImage = handover.handover_employee && handover.handover_employee
                        .image ?
                        '/employee_images/' + handover.handover_employee.image :
                        '/images/admin_default.jpg';

                    const handoverEmployeeName = handover.handover_employee && handover.handover_employee
                        .fullname ?
                        handover.handover_employee.fullname :
                        'N/A';

                    const handoverEmployeeId = handover.handover_employee && handover.handover_employee
                        .employee_id ?
                        handover.handover_employee.employee_id :
                        'N/A';

                    const takeoverEmployeeImage = handover.takeover_employee && handover.takeover_employee
                        .image ?
                        '/employee_images/' + handover.takeover_employee.image :
                        '/images/admin_default.jpg';

                    const takeoverEmployeeName = handover.takeover_employee && handover.takeover_employee
                        .fullname ?
                        handover.takeover_employee.fullname :
                        'N/A';

                    const takeoverEmployeeId = handover.takeover_employee && handover.takeover_employee
                        .employee_id ?
                        handover.takeover_employee.employee_id :
                        'N/A';

                    const handoverDate = handover.handover_date ?
                        formatDateToDDMMYY(handover.handover_date) :
                        'N/A';

                    const taskCount = handover.tasks && Array.isArray(handover.tasks) ?
                        handover.tasks.length :
                        (handover.tasks ? Object.keys(JSON.parse(handover.tasks)).length : 0);

                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="${handoverEmployeeImage}"
                                    class="rounded-circle me-1" width="40" height="40">
                                <div>
                                    <h6 class="mb-0">${handoverEmployeeName}</h6>
                                    <small class="text-muted">${handoverEmployeeId}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="${takeoverEmployeeImage}"
                                    class="rounded-circle me-1" width="40" height="40">
                                <div>
                                    <h6 class="mb-0">${takeoverEmployeeName}</h6>
                                    <small class="text-muted">${takeoverEmployeeId}</small>
                                </div>
                            </div>
                        </td>
                     <td>
    <span class="badge p-2 ${
        handover.reason == 2 ? 'bg-danger' :      // End of Employment
        handover.reason == 3 ? 'bg-info' :        // Transfer
        handover.reason == 1 ? 'bg-success' :     // Vacation
        'bg-secondary'                            // Other / Unknown
    }">
        ${
            handover.reason == 0 && handover.reason_other
                ? handover.reason_other
                : (handover.reason == 1 ? 'Vacation' :
                   handover.reason == 2 ? 'End of Employment' :
                   handover.reason == 3 ? 'Transfer' :
                   'Other')
        }
    </span>
</td>

                        <td>${handoverDate}</td>
                        <td>
                            ${taskCount} task${taskCount !== 1 ? 's' : ''}
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="/dashboard/employees/handover/${handover.handover_id}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="/dashboard/employees/handover/${handover.handover_id}/edit" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-danger delete-handover"
                                    data-id="${handover.handover_id}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });

                if (totalItems === 0) {
                    handoverShowingInfo.textContent = 'Showing 0 to 0 of 0 entries';
                } else {
                    handoverShowingInfo.textContent =
                        `Showing ${startIndex + 1} to ${endIndex} of ${totalItems} entries`;
                }
            }

            function updatePaginationControls(totalItems) {
                totalPages = Math.ceil(totalItems / currentEntriesPerPage);
                prevHandoverPageBtn.disabled = currentPage <= 1;
                nextHandoverPageBtn.disabled = currentPage >= totalPages;

                if (totalPages === 0) {
                    handoverPageInfo.textContent = 'Page 0 of 0';
                } else {
                    handoverPageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
                }
            }

            function toggleClearFiltersButton() {
                if (currentEmployeeFilter || currentDepartmentFilter || currentReasonFilter !== '') {
                    clearFiltersBtnContainer.style.display = 'block';
                } else {
                    clearFiltersBtnContainer.style.display = 'none';
                }
            }

            // Initialize the table with all data
            applyFilters();

            // Delete handover functionality
            document.addEventListener('click', function(e) {
                if (e.target.closest('.delete-handover')) {
                    const button = e.target.closest('.delete-handover');
                    const handoverId = button.getAttribute('data-id');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Handover record will be deleted!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Send AJAX request to delete the handover
                            fetch(`/dashboard/employees/handover/delete/${handoverId}`, {
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
                                            'Handover record has been deleted.',
                                            'success'
                                        ).then(() => {
                                            // Remove the row from the table
                                            button.closest('tr').remove();

                                            // Check if table is empty and show message
                                            if (document.querySelectorAll(
                                                    '#handoverTableBody tr')
                                                .length === 0) {
                                                location.reload();
                                            }
                                        });
                                    } else {
                                        Swal.fire(
                                            'Error!',
                                            'There was a problem deleting the handover record.',
                                            'error'
                                        );
                                    }
                                })
                                .catch(error => {
                                    Swal.fire(
                                        'Error!',
                                        'There was a problem deleting the handover record.',
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
