<x-layout>
    @section('title', 'Edit Company Email')

    <div class="container-fluid p-4">
        <!-- Header & Breadcrumb -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 mt-2">

                        <li class="breadcrumb-item">
                            <a href="{{ route('companyemail.index') }}" class="text-decoration-none text-muted">
                                Company Email
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-primary" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>

        <x-message />

        <!-- Employee Data Card -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-2 text-center">
                                <img id="employeeImage"
                                    src="{{ asset($employee->image ? 'employee_images/' . $employee->image : 'images/admin_default.jpg') }}"
                                    alt="Profile Image" class="img-fluid rounded-circle" width="120px">
                            </div>
                            <div class="col-md-8">
                                <h2 id="employeeName" class="mb-3">{{ $employee->fullname }}</h2>
                                <div class="d-flex flex-wrap gap-3 mb-3 align-items-center">
                                    <div>
                                        <i class="bi bi-people-fill me-2"></i>
                                        <label class="mb-0"> Employee ID :</label>

                                        <span class="fw-bold">{{ $employee->employee_id }}</span>
                                    </div>
                                    <div class="">
                                        <i class="bi bi-person-badge"></i>
                                        <label class="mb-0">Designation:</label>
                                        <span id="employeeDesignation"
                                            class="badge bg-primary p-2">{{ $employee->Designationid->des_name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="">
                                        <i class="bi bi-diagram-3"></i>
                                        <label class="mb-0">Department:</label>
                                        <span id="employeeDepartment"
                                            class="badge bg-secondary p-2">{{ $employee->Departmentid->dep_name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="">
                                        <i class="bi bi-person-gear"></i>
                                        <label class="mb-0">Roles:</label>
                                        <span id="employeeRole" class="badge bg-info p-2">
                                            {{ $employee->user && $employee->user->roles->count() > 0 ? $employee->user->roles->pluck('name')->join(', ') : 'N/A' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap gap-3 mb-3 align-items-center">

                                    <p for="">Form status:
                                        <span
                                            class="badge p-2 {{ $employee->status_for_stepform == 6 ? 'bg-success' : ($employee->status_for_stepform >= 1 ? 'bg-danger' : 'bg-secondary') }}">
                                            {{ $employee->status_for_stepform == 6 ? 'Complete' : ($employee->status_for_stepform >= 1 ? 'Incomplete' : 'Not Started') }}
                                        </span>
                                        @if ($employee->status_for_stepform >= 1 && $employee->status_for_stepform <= 5)
                                            <span>
                                                <a href="{{ route('empedit', $employee->emp_id) }}"
                                                    class="small  list-unstyled  text-decoration-none">
                                                    <p class="mt-1">
                                                        <i style=""
                                                            class="bi bi-hand-index-thumb me-2 bouncing-hand"></i>
                                                        Navigation Employee
                                                        Form
                                                    </p>
                                                </a>
                                            </span>
                                        @endif
                                        </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form id="emailForm" method="post"
                            action="{{ route('companyemail.update', $employee->emp_id) }}">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="employee_id" value="{{ $employee->emp_id }}">

                            <div class="form-group mb-3">
                                <label for="employee_id" class="form-label">Employee FullName</label>
                                <input type="text" class="form-control" id="fullname" name="fullname"
                                    value="{{ $employee->fullname }}" disabled>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">Login Access <span class="text-danger">*</span></label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="login_access"
                                            id="yes" value="1"
                                            {{ $employee->login_access == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="yes">Yes</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="login_access"
                                            id="no" value="0"
                                            {{ $employee->login_access != 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="no">No</label>
                                    </div>
                                </div>
                            </div>

                            <div id="emailFields">
                                <div class="form-group mb-3">
                                    <label for="email_company" class="form-label">Company Email</label>
                                    <input type="text" class="form-control" id="email_company" name="email_company"
                                        value="{{ $employee->email_company }}">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="form-group mb-3 position-relative">
                                    <label for="password_company" class="form-label">Company Password</label>
                                    <input type="password" class="form-control" id="password_company"
                                        name="password_company" placeholder="Leave blank to keep current password">
                                    <span class="position-absolute top-50 mt-3 end-0 translate-middle-y me-3"
                                        style="cursor: pointer;" id="togglePassword">
                                        <i class="bi bi-eye-slash-fill" id="toggleIcon"></i>
                                    </span>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <!-- Custom Multi-Select Dropdown for Roles -->
                                <div class="multi-select-container mb-3">
                                    <label class="form-label">Roles</label>
                                    <div class="multi-select-dropdown">
                                        <button type="button" class="multi-select-toggle" aria-expanded="false">
                                            <span class="selected-options-text">Select roles...</span>
                                            <svg class="dropdown-arrow" width="16" height="16"
                                                viewBox="0 0 16 16" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path d="M4 6L8 10L12 6" stroke="currentColor" stroke-width="1.5"
                                                    stroke-linecap="round" />
                                            </svg>
                                        </button>
                                        <div class="multi-select-options">
                                            <div class="multi-select-option select-all">
                                                <input type="checkbox" id="selectAllRoles" class="form-check-input">
                                                <label for="selectAllRoles">Select all</label>
                                            </div>
                                            <div class="divider"></div>
                                            @foreach ($allRoles as $role)
                                                <div class="multi-select-option">
                                                    <input type="checkbox" id="role-{{ $loop->index }}"
                                                        name="roles[]" value="{{ $role }}"
                                                        class="form-check-input role-checkbox"
                                                        {{ in_array($role, $currentRoles) ? 'checked' : '' }}>
                                                    <label for="role-{{ $loop->index }}">{{ $role }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="invalid-feedback" id="roles-error"></div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('emplist') }}" class="btn btn-secondary px-4">Cancel</a>
                                <button type="submit" class="btn btn-primary px-4" id="submitBtn">Update</button>
                            </div>
                        </form>
                    </div>


                    <div id="permissionsSection" class="mt-4" style="display: none;">
                        <div class="d-flex flex-wrap align-items-center ms-4 mb-3">
                            <h5 class="mb-0 me-2">Assigned Permissions For Role</h5>
                            <div id="selectedRolesBadges" class="d-flex flex-wrap gap-1"></div>
                        </div>
                        <div id="permissionsDisplay" class="p-3 bg-light rounded"></div>
                    </div>


                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            // Initialize - hide permissions section by default
            $('#permissionsSection').hide();

            // Check if any roles are pre-selected on page load
            if ($('.role-checkbox:checked').length > 0) {
                updateSelectedRolesAndPermissions();
            }

            // Handle individual role checkbox changes
            $('.role-checkbox').change(function() {
                updateSelectedRolesAndPermissions();
                updateSelectAllCheckbox();
            });

            // Handle "Select all" checkbox
            $('#selectAllRoles').change(function() {
                const isChecked = $(this).is(':checked');
                $('.role-checkbox').prop('checked', isChecked).trigger('change');
            });

            // Update the select all checkbox state
            function updateSelectAllCheckbox() {
                const totalRoles = $('.role-checkbox').length;
                const checkedRoles = $('.role-checkbox:checked').length;

                $('#selectAllRoles').prop('checked', checkedRoles === totalRoles);
                $('#selectAllRoles').prop('indeterminate', checkedRoles > 0 && checkedRoles < totalRoles);
            }

            // Main function to update roles and permissions
            function updateSelectedRolesAndPermissions() {
                const selectedRoles = getSelectedRoles();

                updateRolesBadges(selectedRoles);
                togglePermissionsSection(selectedRoles);

                if (selectedRoles.length > 0) {
                    fetchPermissionsForRoles(selectedRoles);
                }
            }

            // Get currently selected roles
            function getSelectedRoles() {
                const selectedRoles = [];
                $('.role-checkbox:checked').each(function() {
                    selectedRoles.push($(this).val());
                });
                return selectedRoles;
            }

            // Update the role badges display
            function updateRolesBadges(roles) {
                const badgesContainer = $('#selectedRolesBadges');
                badgesContainer.empty();

                // Always show individual role badges
                roles.forEach(role => {
                    badgesContainer.append(`
                    <span class="badge bg-primary me-1 mb-1">
                        ${role}
                        <button class="btn-close btn-close-white btn-close-xs ms-1 remove-role"
                                data-role="${role}" aria-label="Remove ${role}"></button>
                    </span>
                `);
                });

                // Add click handlers for remove buttons
                $('.remove-role').click(function(e) {
                    e.stopPropagation();
                    const roleToRemove = $(this).data('role');
                    $(`.role-checkbox[value="${roleToRemove}"]`).prop('checked', false).trigger('change');
                });
            }

            // Show/hide permissions section
            function togglePermissionsSection(roles) {
                if (roles.length > 0) {
                    $('#permissionsSection').show();
                } else {
                    $('#permissionsSection').hide();
                }
            }

            // Fetch permissions via AJAX
            function fetchPermissionsForRoles(roles) {
                $.ajax({
                    url: "{{ route('getPermissionsForRoles') }}",
                    type: "POST",
                    data: {
                        roles: roles,
                        _token: "{{ csrf_token() }}"
                    },
                    beforeSend: function() {
                        $('#permissionsDisplay').html(`
                        <div class="text-center py-3">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Loading permissions...</p>
                        </div>
                    `);
                    },
                    success: function(response) {
                        $('#permissionsDisplay').html(response.html);
                    },
                    error: function() {
                        $('#permissionsDisplay').html(`
                        <div class="alert alert-danger">
                            Failed to load permissions. Please try again.
                        </div>
                    `);
                    }
                });
            }
        });
    </script>
    <style>
        #selectedRolesBadges {
            max-width: 600px;
        }

        #selectedRolesBadges .badge {
            font-size: 0.75rem;
            padding: 0.35em 0.65em;
        }
    </style>
    <script>
        $(document).ready(function() {
            // Initialize custom multi-select dropdown
            const dropdown = $('.multi-select-dropdown');
            const toggle = dropdown.find('.multi-select-toggle');
            const selectAll = $('#selectAllRoles');
            const checkboxes = $('.multi-select-option input[name="roles[]"]');
            const selectedText = $('.selected-options-text');

            // Toggle dropdown
            toggle.on('click', function(e) {
                e.stopPropagation();
                dropdown.toggleClass('active');
            });

            // Close when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.multi-select-dropdown').length) {
                    dropdown.removeClass('active');
                }
            });

            // Select all functionality
            selectAll.on('change', function() {
                checkboxes.prop('checked', this.checked);
                updateSelectedText();
            });

            // Individual checkbox change
            checkboxes.on('change', function() {
                const allChecked = checkboxes.length === checkboxes.filter(':checked').length;
                selectAll.prop('checked', allChecked);
                updateSelectedText();
            });

            // Update selected text display
            function updateSelectedText() {
                const selectedOptions = checkboxes.filter(':checked')
                    .map(function() {
                        return $(this).next('label').text();
                    }).get();

                if (selectedOptions.length === 0) {
                    selectedText.text('Select roles...');
                } else if (selectedOptions.length === checkboxes.length) {
                    selectedText.text('All roles selected');
                } else if (selectedOptions.length > 2) {
                    selectedText.text(`${selectedOptions.length} roles selected`);
                } else {
                    selectedText.text(selectedOptions.join(', '));
                }
            }

            // Initialize selected text on page load
            updateSelectedText();

            // Password toggle functionality
            $('#togglePassword').click(function() {
                const passwordInput = $('#password_company');
                const icon = $('#toggleIcon');
                const isPassword = passwordInput.attr('type') === 'password';
                passwordInput.attr('type', isPassword ? 'text' : 'password');
                icon.toggleClass('bi-eye-fill bi-eye-slash-fill');
            });

            // Handle form submission
            $('#emailForm').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                var url = form.attr('action');

                // Clear previous errors
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        Toastify({
                            text: "Employee email credentials updated successfully",
                            duration: 2000,
                            close: false,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "#10B981",
                            offset: {
                                y: 65
                            },
                            stopOnFocus: true,
                        }).showToast();

                        setTimeout(function() {
                            window.location.href = "{{ route('emplist') }}";
                        }, 1000);
                    },
                    error: function(xhr) {
                        let errorMsg = "An error occurred";
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        } else if (xhr.status === 422) {
                            errorMsg = "Please fix the validation errors";
                        }

                        Toastify({
                            text: errorMsg,
                            duration: 3000,
                            backgroundColor: "#EF4444",
                            offset: {
                                y: 65
                            },
                            stopOnFocus: true,
                        }).showToast();

                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            for (let field in errors) {
                                let input = $(`[name="${field}"]`);
                                if (field === 'roles') {
                                    $('#roles-error').text(errors[field][0]);
                                } else {
                                    input.addClass('is-invalid');
                                    input.next('.invalid-feedback').text(errors[field][0]);
                                }
                            }
                        }
                    }
                });
            });
        });
    </script>
    <style>
        .permissions-container {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
        }

        .permissions-header {
            font-weight: 600;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #dee2e6;
        }

        .permissions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1rem;
        }

        .permission-category {
            background: white;
            border-radius: 8px;
            padding: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .category-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }

        .subcategories {
            margin-left: 0.5rem;
            border-left: 2px solid #eee;
            padding-left: 0.75rem;
        }

        .subcategory {
            margin-bottom: 0.5rem;
        }

        .subcategory-title {
            font-size: 0.9rem;
            font-weight: 500;
            color: #555;
            margin-bottom: 0.25rem;
        }

        .permission-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .permission-item {
            padding: 0.25rem 0;
            display: flex;
            align-items: center;
            font-size: 0.9rem;
        }

        /* Custom Multi-Select Dropdown Styles */
        .multi-select-container {
            position: relative;
            --primary-color: #6366f1;
            --border-color: #e5e7eb;
            --hover-bg: #f3f4f6;
        }

        .multi-select-dropdown {
            position: relative;
            width: 100%;
        }

        .multi-select-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 0.375rem;
            background-color: white;
            text-align: left;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .multi-select-toggle:hover {
            border-color: var(--primary-color);
        }

        .multi-select-dropdown.active .multi-select-options {
            display: block;
        }

        .multi-select-options {
            display: none;
            position: absolute;
            width: 100%;
            max-height: 300px;
            overflow-y: auto;
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 0.375rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            margin-top: 0.25rem;
        }

        .multi-select-option {
            padding: 0.5rem 1rem;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .multi-select-option:hover {
            background-color: var(--hover-bg);
        }

        .multi-select-option input {
            margin-right: 0.75rem;
        }

        .select-all {
            font-weight: 600;
            background-color: #f8f9fa;
        }

        .divider {
            height: 1px;
            background-color: var(--border-color);
            margin: 0.25rem 0;
        }

        .dropdown-arrow {
            transition: transform 0.2s ease;
        }

        .multi-select-dropdown.active .dropdown-arrow {
            transform: rotate(180deg);
        }
    </style>
</x-layout>
