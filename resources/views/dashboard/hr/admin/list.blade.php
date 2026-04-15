<x-layout>
    @section('title', 'Administrators')

    <div class="container-fluid py-4 px-4">
        <!-- Header & Breadcrumb -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0">
                        <li class="breadcrumb-item">
                            <a href="#" class="text-decoration-none text-muted">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-primary" aria-current="page">Administrators Management</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card border-0 shadow-lg rounded-4">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <div class="text-end mb-3">


                        <a href="{{ route('admineditcolumn') }}">
                            <button id="editcolumnBtn" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-columns-gap me-1"></i> Edit Columns
                            </button>
                        </a>
                        <button id="filterToggleBtn" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-funnel-fill me-1"></i> Filters
                            <i class="bi bi-chevron-down ms-1 filter-arrow"></i>
                        </button>
                        <div class="btn-group" id="bulkActionButtons" style="display: none;">
                            <button type="button" class="btn btn-sm btn-outline-danger" id="bulkDeleteBtn">
                                <i class="bi bi-trash me-2"></i>BulkDelete
                            </button>
                        </div>
                    </div>

                    <div id="filterPanel" class="bg-light rounded mb-3">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <input type="text" class="form-control searchfilter" id="filterId"
                                    placeholder="Enter ID">
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control searchfilter" id="filterName"
                                    placeholder="Enter name">
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control searchfilter" id="filterEmail"
                                    placeholder="Enter email">
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control searchfilter" id="filterRole"
                                    placeholder="Enter Role">
                            </div>
                        </div>
                    </div>

                    <table id="basic-datatables" class="display table table-bordered table-hover">
                        <thead class="bg-light rounded text-center">
                            <tr>
                                @foreach ($visibleColumns as $column)
                                    {{-- @if ($column === 'checkbox')
                                        <th><input type="checkbox" id="selectAll"></th> --}}
                                    @if ($column === 'serial')
                                        <th>#</th>
                                    @elseif($column === 'image')
                                        <th>Image</th>
                                    @elseif($column === 'name')
                                        <th>Name</th>
                                    @elseif($column === 'email')
                                        <th>Email</th>
                                    @elseif($column === 'department')
                                        <th>Department</th>
                                    @elseif($column === 'role')
                                        <th>Roles</th>
                                    @elseif($column === 'action')
                                        <th>Action</th>
                                    @endif
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr class="text-center align-middle">
                                    @foreach ($visibleColumns as $column)
                                        {{-- @if ($column === 'checkbox')
                                            <td class="align-middle">

                                                <input type="checkbox" class="empCheckbox" value="{{ $user->id }}">
                                            </td> --}}
                                        @if ($column === 'serial')
                                            <td class="align-middle">{{ $user->employee->emp_id }}</td>
                                        @elseif($column === 'image')
                                            <td class="align-middle" width="200px">
                                                @if ($user->employee)
                                                    <a href="{{ route('adminempshow', $user->id ?? '') }}"> <img
                                                            src="{{ $user->employee->image ? asset('employee_images/' . $user->employee->image) : asset('images/admin_default.jpg') }}"
                                                            alt="{{ $user->employee->fullname }}"
                                                            class="img-fluid mx-auto d-block" width="70px"></a>
                                                @else
                                                    <a href="{{ route('adminempshow', $user->id ?? '') }}">
                                                        <img src="{{ asset('images/admin_default.jpg') }}"
                                                            alt="Default image" class="img-fluid mx-auto d-block"
                                                            width="70px"></a>
                                                @endif
                                            </td>
                                        @elseif($column === 'name')
                                            <td class="align-middle" width="280px">
                                                {{ $user->employee->fullname ?? 'N/A' }}
                                            </td>
                                        @elseif($column === 'email')
                                            <td class="align-middle" width="380px">
                                                {{ $user->email ?? 'not create' }}
                                            </td>
                                        @elseif($column === 'department')
                                            <td class="align-middle">
                                                {{ $user->employee->Departmentid->dep_name }}
                                            </td>
                                        @elseif($column === 'role')
                                            <td class="align-middle">
                                                <div class="d-flex flex-wrap justify-content-center gap-2">
                                                    @forelse ($user->roles as $role)
                                                        @php
                                                            $roleName = $role->name;
                                                        @endphp
                                                        <span @class([
                                                            'rounded-pill px-3 py-2',
                                                            'badgecolor',
                                                            'badge',
                                                            'bg-danger' => strtolower($roleName) === 'super admin',
                                                            'bg-primary' => strtolower($roleName) === 'admin',
                                                            'bg-info text-dark' => strtolower($roleName) === 'moderator',
                                                            'bg-success' => strtolower($roleName) === 'editor',
                                                            'bg-secondary' => strtolower($roleName) === 'viewer',
                                                            'bg-warning text-dark' => strtolower($roleName) === 'client',
                                                            'bg-dark' => !in_array(strtolower($roleName), [
                                                                'super admin',
                                                                'admin',
                                                                'moderator',
                                                                'editor',
                                                                'viewer',
                                                                'client',
                                                            ]),
                                                        ])>
                                                            {{ $roleName }}
                                                        </span>
                                                    @empty
                                                        <span class="badge bg-light text-dark rounded-pill px-3 py-2">No
                                                            roles assigned</span>
                                                    @endforelse

                                                </div>
                                            </td>
                                        @elseif($column === 'action')
                                            <td class="align-middle" width="380px">
                                                <div class="d-flex justify-content-center gap-2">


                                                    @can('admin->admin view')
                                                        <a href="{{ route('adminempshow', $user->id ?? '') }}"
                                                            class="btn btn-sm btn-icon btn-soft-info hover-grow"
                                                            data-bs-toggle="tooltip" data-bs-placement="top">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    @endcan
                                                    {{--
                                                    <button
                                                        class="btn btn-sm btn-icon btn-soft-warning hover-grow edit-admin-btn"
                                                        data-bs-toggle="modal" data-bs-target="#editAdminModal"
                                                        data-user-id="{{ $user->id }}"
                                                        data-emp-id="{{ $user->employee->emp_id ?? '' }}"
                                                        data-bs-placement="top">
                                                        <i class="bi bi-pencil-fill"></i>
                                                    </button> --}}
                                                </div>
                                            </td>
                                        @endif
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <x-actionbtn />
        <!-- Edit Admin Modal -->
        <div class="modal fade" id="editAdminModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 shadow-lg">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold">Edit Roles</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="editAdminForm" method="post">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <input type="hidden" id="edit_id" name="id">

                            <div class="text-center mb-3">
                                <img id="current_image" src="" class="img-thumbnail rounded-circle"
                                    width="120">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="edit_name" disabled>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" id="edit_email" disabled>
                            </div>

                            {{-- <div class="mb-3">
                                <label class="form-label">Current Role</label>
                                <input type="text" class="form-control" id="edit_current_role" readonly>
                            </div> --}}

                            <div class="multi-select-container mb-3">
                                <label class="form-label">Select New Roles</label>
                                <div class="multi-select-dropdown">
                                    <button type="button" class="multi-select-toggle" aria-expanded="false">
                                        <span class="selected-options-text">Select roles...</span>
                                        <svg class="dropdown-arrow" width="16" height="16"
                                            viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
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

                                        @if (auth()->user()->hasRole('Super admin'))
                                            {{-- Super admin sees all roles except "Super admin" --}}
                                            @foreach ($roles as $role)
                                                @unless ($role === 'Super admin')
                                                    <div class="multi-select-option">
                                                        <input type="checkbox" id="role-{{ $loop->index }}"
                                                            name="roles[]" value="{{ $role }}"
                                                            class="form-check-input role-checkbox">
                                                        <label for="role-{{ $loop->index }}">{{ $role }}</label>
                                                    </div>
                                                @endunless
                                            @endforeach
                                        @elseif (auth()->user()->hasRole('Admin'))
                                            {{-- Admin sees all roles except "Super admin" and "Admin" --}}
                                            @foreach ($roles as $role)
                                                @unless (in_array($role, ['Super admin', 'Admin']))
                                                    <div class="multi-select-option">
                                                        <input type="checkbox" id="role-{{ $loop->index }}"
                                                            name="roles[]" value="{{ $role }}"
                                                            class="form-check-input role-checkbox">
                                                        <label for="role-{{ $loop->index }}">{{ $role }}</label>
                                                    </div>
                                                @endunless
                                            @endforeach
                                        @else
                                            {{-- Other users (if needed) --}}
                                            @foreach ($roles as $role)
                                                @unless (in_array($role, ['Super admin', 'Admin', 'Other Restricted Role']))
                                                    <div class="multi-select-option">
                                                        <input type="checkbox" id="role-{{ $loop->index }}"
                                                            name="roles[]" value="{{ $role }}"
                                                            class="form-check-input role-checkbox">
                                                        <label for="role-{{ $loop->index }}">{{ $role }}</label>
                                                    </div>
                                                @endunless
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                <div class="text-danger" id="edit-error-roles"></div>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update Roles</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Initialize multi-select dropdown
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

            // Edit admin button click handler
            $('.edit-admin-btn').on('click', function() {
                const userId = $(this).data('user-id');

                // Show loading state
                $('#editAdminModal').modal('show');
                $('#editAdminForm').find('button[type="submit"]').prop('disabled', true);
                $('.modal-body').addClass('loading-state');

                // Fetch admin data
                $.ajax({
                    url: `/dashboard/admin/${userId}/edit`,
                    type: "GET",
                    success: function(response) {
                        // Set basic fields
                        $('#edit_id').val(response.user.id);
                        $('#edit_name').val(response.user.name);
                        $('#edit_email').val(response.user.email);
                        $('#edit_current_role').val(response.user.current_roles[0] ||
                            'No role assigned');

                        // Set image
                        const imageUrl = response.user.image ?
                            `/employee_images/${response.user.image}` :
                            `/images/admin_default.jpg`;
                        $('#current_image').attr('src', imageUrl);

                        // Reset all checkboxes
                        $('.role-checkbox').prop('checked', false);

                        // Check the roles that this admin has
                        if (response.user.current_roles && response.user.current_roles.length >
                            0) {
                            response.user.current_roles.forEach(function(role) {
                                $(`.role-checkbox[value="${role}"]`).prop('checked',
                                    true);
                            });
                        }

                        // Update selected text
                        updateSelectedText();

                        // Remove loading state
                        $('#editAdminForm').find('button[type="submit"]').prop('disabled',
                            false);
                        $('.modal-body').removeClass('loading-state');
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Failed to fetch admin data'
                        });
                        $('#editAdminModal').modal('hide');
                    }
                });
            });

            // Edit admin form submission
            $('#editAdminForm').submit(function(e) {
                e.preventDefault();
                const adminId = $('#edit_id').val();
                const formData = $(this).serialize();

                $.ajax({
                    url: `/dashboard/admin/${adminId}`,
                    type: "POST",
                    data: formData,
                    headers: {
                        'X-HTTP-Method-Override': 'PUT'
                    },
                    beforeSend: function() {
                        clearEditErrors();
                        $('#editAdminForm button[type="submit"]').prop('disabled', true).html(
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...'
                        );
                    },
                    success: function(response) {
                        $('#editAdminModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    },
                    error: function(xhr) {
                        $('#editAdminForm button[type="submit"]').prop('disabled', false).html(
                            'Update Roles');
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('#edit-error-' + key).text(value[0]);
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: xhr.responseJSON.message || 'Something went wrong'
                            });
                        }
                    }
                });
            });

            function clearEditErrors() {
                $('#edit-error-roles').text('');
            }

            // Initialize DataTable
            var table = $('#basic-datatables').DataTable({
                responsive: true,
                paging: true,
                searching: true,
                ordering: true,
                dom: '<"row mb-2"<"col-md-6"B><"col-md-6 text-end"f>>rt<"bottom d-flex justify-content-between "lip><"clear">',
                buttons: [{
                        extend: 'csvHtml5',
                        text: '<i class="bi bi-file-earmark-spreadsheet"></i> CSV',
                        className: 'btn btn-sm btn-outline-primary me-1'
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="bi bi-file-earmark-excel"></i> Excel',
                        className: 'btn btn-sm btn-outline-primary me-1'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="bi bi-file-earmark-pdf"></i> PDF',
                        className: 'btn btn-sm btn-outline-primary me-1'
                    }
                ]
            });

            // Toggle filter panel
            $('#filterToggleBtn').click(function() {
                const panel = $('#filterPanel');
                const arrow = $(this).find('.filter-arrow');
                panel.toggleClass('show');
                arrow.toggleClass('rotated');
            });

            // Auto-search functionality
            let searchTimeout;
            $('.searchfilter').on('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    table.columns(1).search($('#filterId').val());
                    table.columns(3).search($('#filterName').val());
                    table.columns(4).search($('#filterEmail').val());
                    table.columns(5).search($('#filterRole').val());
                    table.draw();
                }, 500);
            });
















            // Toggle select all checkboxes
            $('#selectAll').on('click', function() {
                $('.empCheckbox').prop('checked', this.checked);
                toggleBulkActionButtons();
            });

            // Toggle bulk action buttons when any checkbox is clicked
            $(document).on('change', '.empCheckbox', function() {
                var allChecked = $('.empCheckbox:checked').length === $('.empCheckbox').length;
                $('#selectAll').prop('checked', allChecked);
                toggleBulkActionButtons();
            });

            // Show/hide bulk action buttons based on selection
            function toggleBulkActionButtons() {
                if ($('.empCheckbox:checked').length > 0) {
                    $('#bulkActionButtons').show();
                } else {
                    $('#bulkActionButtons').hide();
                }
            }

            // Bulk delete action
            $('#bulkDeleteBtn').on('click', function() {
                var selectedIds = [];
                $('.empCheckbox:checked').each(function() {
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length === 0) {
                    Swal.fire('Error', 'Please select at least one admin to delete', 'error');
                    return;
                }

                Swal.fire({
                    title: "Are you sure?",
                    text: "This will mark the selected users as deleted!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, mark as deleted!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('adminbulkdelete') }}',
                            type: 'POST',
                            data: {
                                ids: selectedIds,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        title: "Success!",
                                        text: "Users marked as deleted",
                                        icon: "success",
                                        timer: 2000,
                                        showConfirmButton: false
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                } else {
                                    Swal.fire("Error", response.message ||
                                        "Operation failed", "error");
                                }
                            },
                            error: function(xhr) {
                                Swal.fire("Error", xhr.responseJSON?.message ||
                                    "Something went wrong", "error");
                            }
                        });
                    }
                });
            });
        });
    </script>

    <style>
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

        .loading-state {
            position: relative;
            min-height: 200px;
        }

        .loading-state::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .loading-state::before {
            content: 'Loading...';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1;
        }

        #filterPanel {
            transition: max-height 0.4s ease, padding 0.4s ease, opacity 0.4s ease;
            overflow: hidden;
            max-height: 0;
            opacity: 0;
            padding-top: 0;
            padding-bottom: 0;
        }

        #filterPanel.show {
            max-height: 200px;
            opacity: 1;
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        .filter-arrow {
            transition: transform 0.3s ease;
        }

        .filter-arrow.rotated {
            transform: rotate(180deg);
        }
    </style>
</x-layout>
