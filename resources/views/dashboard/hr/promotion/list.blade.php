<x-layout>
    @section('title', 'Promotion List')
    <div class="container-fluid py-4 px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-2">Promotion List</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0">
                        <li class="breadcrumb-item">
                            <a href="#" class="text-decoration-none text-muted">
                                <i class="bi bi-people-fill me-2"></i>Employment
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-primary" aria-current="page">Promotion</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('procreate') }}" class="btn btn-primary d-flex align-items-center">
                <i class="bi bi-plus-circle me-2"></i>Add Promotion
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
                    <table class="table table-hover" id="promotionTable">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Employee email</th>
                                <th>Current Designation</th>
                                <th>Proposed Designation</th>
                                <th>Percentage Increase</th>
                                <th>Proposed Salary</th>
                                <th>Manager</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="promotionTableBody">
                            @forelse($promotions as $promotion)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $promotion->employeeid->image ? asset('employee_images/' . $promotion->employeeid->image) : asset('images/admin_default.jpg') }}"
                                                class="rounded-circle me-1" width="40" height="40">
                                            <div>
                                                <h6 class="mb-0">{{ $promotion->employeeid->fullname }}</h6>
                                                <small
                                                    class="text-muted">{{ $promotion->employeeid->employee_id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $promotion->employeeid->email_company ?? 'not yet' }}</td>
                                    <td>{{ $promotion->currentDesignation->des_name ?? 'not yet' }}</td>
                                    <td>{{ $promotion->proposedDesignation->des_name ?? 'not yet' }}</td>
                                    <td>{{ $promotion->percentage_increase ? $promotion->percentage_increase . '%' : 'not yet' }}
                                    </td>

                                    <td>{{ $promotion->proposed_new_salary ?? 'not yet' }}</td>
                                    <td>{{ $promotion->manager_name ?? 'not yet' }}</td>
                                    <td>{{ $promotion->date_of_signature->format('d-m-Y') ?? 'not yet' }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('proshow', $promotion->promotion_id) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('proedit', $promotion->promotion_id) }}"
                                                class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-danger delete-promotion"
                                                data-id="{{ $promotion->promotion_id }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="bi bi-inbox display-4 text-muted d-block mb-2"></i>
                                        <p class="text-muted">No promotions found.</p>
                                        <a href="{{ route('procreate') }}" class="btn btn-primary">
                                            <i class="bi bi-plus-circle me-2"></i>Create Promotion
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
                        <span id="promotionShowingInfo">
                            Showing 1 to {{ count($promotions) > 5 ? 5 : count($promotions) }}
                            of {{ count($promotions) }} entries
                        </span>
                    </div>
                    <div class="col-auto">
                        <div class="pagination-controls d-flex align-items-center">
                            <button class="pagination-button" id="prevPromotionPage" disabled>
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <span class="page-info mx-2" id="promotionPageInfo">
                                Page 1 of {{ ceil(count($promotions) / 5) }}
                            </span>
                            <button class="pagination-button" id="nextPromotionPage"
                                {{ count($promotions) <= 5 ? 'disabled' : '' }}>
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
            // Get all promotions data
            const promotions = @json($promotions);
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
            const prevPromotionPageBtn = document.getElementById('prevPromotionPage');
            const nextPromotionPageBtn = document.getElementById('nextPromotionPage');
            const promotionPageInfo = document.getElementById('promotionPageInfo');
            const promotionShowingInfo = document.getElementById('promotionShowingInfo');

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
            prevPromotionPageBtn.addEventListener('click', function() {
                if (currentPage > 1) {
                    currentPage--;
                    applyFilters();
                }
            });

            nextPromotionPageBtn.addEventListener('click', function() {
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
                let filteredData = [...promotions];

                // Apply employee filter
                if (currentEmployeeFilter) {
                    filteredData = filteredData.filter(promotion =>
                        promotion.employeeid && promotion.employeeid.emp_id == currentEmployeeFilter
                    );
                }

                // Apply department filter
                // Apply department filter
                if (currentDepartmentFilter) {
                    filteredData = filteredData.filter(promotion =>
                        promotion.employeeid &&
                        promotion.employeeid.cur_department &&
                        promotion.employeeid.cur_department == currentDepartmentFilter
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

            function updateTable(data, totalItems, startIndex, endIndex) {
                const tableBody = document.getElementById('promotionTableBody');
                tableBody.innerHTML = '';

                if (data.length === 0) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="bi bi-inbox display-4 text-muted d-block mb-2"></i>
                                <p class="text-muted">No promotions found.</p>
                                <a href="{{ route('procreate') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-2"></i>Create Promotion
                                </a>
                            </td>
                        </tr>
                    `;
                    return;
                }

                data.forEach(promotion => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="${promotion.employeeid.image ? '/employee_images/' + promotion.employeeid.image : '/images/admin_default.jpg'}"
                                    class="rounded-circle me-1" width="40" height="40">
                                <div>
                                    <h6 class="mb-0">${promotion.employeeid.fullname}</h6>
                                    <small class="text-muted">${promotion.employeeid.employee_id}</small>
                                </div>
                            </div>
                        </td>
                        <td>${promotion.employeeid.email_company || 'not yet'}</td>
                    <td>${promotion.current_designation ? promotion.current_designation.des_name : 'not yet'}</td>
<td>${promotion.proposed_designation ? promotion.proposed_designation.des_name : 'not yet'}</td>

                        <td>${promotion.percentage_increase ? promotion.percentage_increase + '%' : 'not yet'}</td>
                        <td>${promotion.proposed_new_salary || 'not yet'}</td>
                        <td>${promotion.manager_name || 'not yet'}</td>
                        <td>${promotion.date_of_signature ? new Date(promotion.date_of_signature).toLocaleDateString('en-GB') : 'not yet'}</td>
                        <td>
                            <div class="btn-group">
                                <a href="/dashboard/employees/promotion/${promotion.promotion_id}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="/dashboard/employees/promotion/${promotion.promotion_id}/edit" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                  <button class="btn btn-sm btn-outline-danger delete-promotion"
                                                data-id="${promotion.promotion_id }">
                                                <i class="bi bi-trash"></i>
                                            </button>
                            </div>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });

                if (totalItems === 0) {
                    promotionShowingInfo.textContent = 'Showing 0 to 0 of 0 entries';
                } else {
                    promotionShowingInfo.textContent =
                        `Showing ${startIndex + 1} to ${endIndex} of ${totalItems} entries`;
                }
            }

            function updatePaginationControls(totalItems) {
                totalPages = Math.ceil(totalItems / currentEntriesPerPage);
                prevPromotionPageBtn.disabled = currentPage <= 1;
                nextPromotionPageBtn.disabled = currentPage >= totalPages;

                if (totalPages === 0) {
                    promotionPageInfo.textContent = 'Page 0 of 0';
                } else {
                    promotionPageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
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



            document.querySelectorAll('.delete-promotion').forEach(button => {
                button.addEventListener('click', function() {
                    const promotionId = this.getAttribute('data-id');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Promotion has been deleted!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Send AJAX request to delete the promotion
                            fetch(`/dashboard/employees/promotion/delete/${promotionId}`, {
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
                                            'Promotion has been deleted.',
                                            'success'
                                        ).then(() => {
                                            // Remove the row from the table
                                            this.closest('tr').remove();

                                            // Check if table is empty and show message
                                            if (document.querySelectorAll(
                                                    '#promotionTableBody tr')
                                                .length === 0) {
                                                location.reload();
                                            }
                                        });
                                    } else {
                                        Swal.fire(
                                            'Error!',
                                            'There was a problem deleting the promotion.',
                                            'error'
                                        );
                                    }
                                })
                                .catch(error => {
                                    Swal.fire(
                                        'Error!',
                                        'There was a problem deleting the promotion.',
                                        'error'
                                    );
                                });
                        }
                    });
                });
            });
        });
    </script>
</x-layout>
