<x-layout>
    @section('title', 'Role Permissions')

    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dhome') }}" class="text-decoration-none text-muted">
                                <i class="bi bi-people me-2"></i>Employment
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-primary" aria-current="page">Role Permissions</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('roles.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left-circle me-2"></i>Back
                </a>
            </div>
        </div>

        <x-message />

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-2 rounded me-3">
                        <i class="bi bi-shield-lock text-primary fs-5"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-semibold">Role: <span class="fw-normal">{{ $role->name }}</span></h4>
                        <small class="text-muted">Manage permissions for this role</small>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('givepermission', $role->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row mb-4">
                        <div class="col-12 text-end">
                            <div class="form-check form-switch d-inline-block">
                                <input class="form-check-input" type="checkbox" role="switch" id="toggleAll">
                                <label class="form-check-label fw-medium" for="toggleAll">
                                    Allow all privileges
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-borderless table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="fw-semibold text-uppercase small py-3">Module</th>
                                    <th class="text-center fw-semibold text-uppercase small py-3">View</th>
                                    <th class="text-center fw-semibold text-uppercase small py-3">Create</th>
                                    <th class="text-center fw-semibold text-uppercase small py-3">Edit</th>
                                    <th class="text-center fw-semibold text-uppercase small py-3">Delete</th>
                                    <th class="text-center fw-semibold text-uppercase small py-3">All</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $currentParent = null; @endphp

                                @foreach ($grouped as $resource => $permissionData)
                                    @php
                                        $isChild = str_contains($resource, '->');
                                        $parentResource = $isChild ? explode('->', $resource)[0] : $resource;
                                        $childResource = $isChild ? explode('->', $resource)[1] : $resource;
                                        $parentId = str_replace(' ', '-', strtolower($parentResource));
                                        $rowId = $isChild ? 'child-' . $parentId : 'parent-' . $parentId;

                                        if (!$isChild && $parentResource !== $currentParent) {
                                            $currentParent = $parentResource;
                                            $hasChildren = isset($hierarchyMap[$parentResource]);
                                        }
                                    @endphp

                                    @if (!$isChild && $parentResource === $currentParent)
                                        {{-- Parent Row --}}
                                        <tr id="parent-{{ $parentId }}"
                                            class="border-top border-2 border-light parent-row">
                                            <td class="fw-bold text-capitalize">
                                                @if ($hasChildren)
                                                    <i class="bi bi-caret-down-fill toggle-children me-2 text-primary"
                                                        data-parent="{{ $parentId }}" style="cursor: pointer"></i>
                                                @else
                                                    <i class="bi bi-folder-fill me-2 text-primary"></i>
                                                @endif
                                                {{ $parentResource }}
                                            </td>
                                            @foreach (['view', 'create', 'edit', 'delete'] as $action)
                                                <td class="text-center">
                                                    @php
                                                        $found = false;
                                                        foreach ($permissionData as $data) {
                                                            if ($data['action'] === $action) {
                                                                $found = true;
                                                                break;
                                                            }
                                                        }
                                                    @endphp
                                                    @if ($found)
                                                        <div class="form-check d-inline-block">
                                                            <input type="checkbox"
                                                                class="form-check-input parent-checkbox"
                                                                data-parent="{{ $parentResource }}"
                                                                data-action="{{ $action }}"
                                                                @checked($data['checked'] ?? false) name="permissions[]"
                                                                value="{{ $parentResource }} {{ $action }}">
                                                        </div>
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                            <td class="text-center">
                                                <div class="form-check d-inline-block">
                                                    <input type="checkbox" class="form-check-input parent-all-checkbox"
                                                        data-parent="{{ $parentResource }}">
                                                </div>
                                            </td>
                                        </tr>
                                    @endif

                                    @if ($isChild && $parentResource === $currentParent)
                                        {{-- Child Row --}}
                                        <tr id="child-{{ $parentId }}" class="child-row" style="display: none;"
                                            data-parent="{{ $parentId }}">
                                            <td class="text-capitalize ps-4">
                                                <i class="bi bi-arrow-return-right me-2 text-muted"></i>
                                                {{ $childResource }}
                                            </td>
                                            @foreach (['view', 'create', 'edit', 'delete'] as $action)
                                                <td class="text-center">
                                                    @php
                                                        $found = false;
                                                        $permissionName = '';
                                                        $checked = false;
                                                        foreach ($permissionData as $data) {
                                                            if ($data['action'] === $action) {
                                                                $found = true;
                                                                $permissionName = $data['permission']->name;
                                                                $checked = $data['checked'];
                                                                break;
                                                            }
                                                        }
                                                    @endphp
                                                    @if ($found)
                                                        <div class="form-check d-inline-block">
                                                            <input type="checkbox"
                                                                class="form-check-input child-checkbox"
                                                                data-parent="{{ $parentResource }}"
                                                                data-action="{{ $action }}"
                                                                @checked($checked) name="permissions[]"
                                                                value="{{ $permissionName }}">
                                                        </div>
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                            <td class="text-center">
                                                <div class="form-check d-inline-block">
                                                    <input type="checkbox" class="form-check-input child-all-checkbox"
                                                        data-parent="{{ $parentResource }}"
                                                        data-resource="{{ $resource }}">
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-primary px-4 py-2">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle child rows when clicking on parent caret icon
            document.querySelectorAll('.toggle-children').forEach(icon => {
                icon.addEventListener('click', function() {
                    const parentId = this.dataset.parent;
                    const childRows = document.querySelectorAll(
                        `tr.child-row[data-parent="${parentId}"]`);
                    const parentRow = document.getElementById(`parent-${parentId}`);

                    // Toggle icon
                    if (this.classList.contains('bi-caret-down-fill')) {
                        this.classList.remove('bi-caret-down-fill');
                        this.classList.add('bi-caret-right-fill');
                    } else {
                        this.classList.remove('bi-caret-right-fill');
                        this.classList.add('bi-caret-down-fill');
                    }

                    // Toggle child rows
                    childRows.forEach(row => {
                        if (row.style.display === 'none') {
                            row.style.display = 'table-row';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            });

            // Global toggle all checkbox
            const toggleAll = document.getElementById('toggleAll');
            const allCheckboxes = document.querySelectorAll('input[type="checkbox"]:not(#toggleAll)');

            // Toggle all permissions
            toggleAll.addEventListener('change', function() {
                const isChecked = this.checked;
                allCheckboxes.forEach(checkbox => {
                    checkbox.checked = isChecked;
                    checkbox.indeterminate = false;
                });
            });

            // Parent "All" checkbox functionality
            document.querySelectorAll('.parent-all-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const parent = this.dataset.parent;
                    const isChecked = this.checked;

                    // Update all child checkboxes for this parent
                    const childCheckboxes = document.querySelectorAll(
                        `.child-checkbox[data-parent="${parent}"]`);
                    childCheckboxes.forEach(cb => {
                        cb.checked = isChecked;
                        cb.indeterminate = false;
                    });

                    // Update all parent action checkboxes
                    const parentCheckboxes = document.querySelectorAll(
                        `.parent-checkbox[data-parent="${parent}"]`);
                    parentCheckboxes.forEach(cb => {
                        cb.checked = isChecked;
                        cb.indeterminate = false;
                    });

                    updateToggleAllState();
                });
            });

            // Child "All" checkbox functionality
            document.querySelectorAll('.child-all-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const parent = this.dataset.parent;
                    const resource = this.dataset.resource;
                    const isChecked = this.checked;

                    // Get all checkboxes in this row
                    const row = this.closest('tr');
                    const rowCheckboxes = row.querySelectorAll('.child-checkbox');

                    // Update all checkboxes in this row
                    rowCheckboxes.forEach(cb => {
                        cb.checked = isChecked;
                        cb.indeterminate = false;
                    });

                    // Update parent checkbox states
                    updateParentCheckboxState(parent);
                    updateToggleAllState();
                });
            });

            // Individual child checkbox change handler
            document.querySelectorAll('.child-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const parent = this.dataset.parent;
                    updateParentCheckboxState(parent);
                    updateToggleAllState();
                });
            });

            // Individual parent checkbox change handler
            document.querySelectorAll('.parent-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const parent = this.dataset.parent;
                    const action = this.dataset.action;
                    const isChecked = this.checked;

                    // Update all child checkboxes with this action
                    const childCheckboxes = document.querySelectorAll(
                        `.child-checkbox[data-parent="${parent}"][data-action="${action}"]`);
                    childCheckboxes.forEach(cb => {
                        cb.checked = isChecked;
                        cb.indeterminate = false;
                    });

                    // Update parent "All" checkbox state
                    updateParentAllCheckboxState(parent);
                    updateToggleAllState();
                });
            });

            // Update parent checkbox state based on children
            function updateParentCheckboxState(parentResource) {
                const actions = ['view', 'create', 'edit', 'delete'];

                // Update each action checkbox
                actions.forEach(action => {
                    const actionCheckboxes = document.querySelectorAll(
                        `.child-checkbox[data-parent="${parentResource}"][data-action="${action}"]`);

                    if (actionCheckboxes.length > 0) {
                        const checkedCount = Array.from(actionCheckboxes).filter(cb => cb.checked).length;
                        const parentActionCheckbox = document.querySelector(
                            `.parent-checkbox[data-parent="${parentResource}"][data-action="${action}"]`
                        );

                        if (parentActionCheckbox) {
                            parentActionCheckbox.checked = checkedCount === actionCheckboxes.length;
                            parentActionCheckbox.indeterminate = checkedCount > 0 && checkedCount <
                                actionCheckboxes.length;
                        }
                    }
                });

                // Update parent "All" checkbox
                updateParentAllCheckboxState(parentResource);
            }

            // Update parent "All" checkbox state
            function updateParentAllCheckboxState(parentResource) {
                const parentAllCheckbox = document.querySelector(
                    `.parent-all-checkbox[data-parent="${parentResource}"]`);
                const childCheckboxes = document.querySelectorAll(
                    `.child-checkbox[data-parent="${parentResource}"]`);

                if (childCheckboxes.length > 0) {
                    const checkedCount = Array.from(childCheckboxes).filter(cb => cb.checked).length;
                    parentAllCheckbox.checked = checkedCount === childCheckboxes.length;
                    parentAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < childCheckboxes.length;
                }
            }

            // Update the global "Toggle All" state
            function updateToggleAllState() {
                const checkedCount = Array.from(allCheckboxes).filter(cb => cb.checked).length;
                const indeterminateCount = Array.from(allCheckboxes).filter(cb => cb.indeterminate).length;

                if (checkedCount === allCheckboxes.length) {
                    toggleAll.checked = true;
                    toggleAll.indeterminate = false;
                } else if (checkedCount === 0 && indeterminateCount === 0) {
                    toggleAll.checked = false;
                    toggleAll.indeterminate = false;
                } else {
                    toggleAll.checked = false;
                    toggleAll.indeterminate = true;
                }
            }

            // Initialize states on page load
            document.querySelectorAll('.parent-all-checkbox').forEach(checkbox => {
                const parent = checkbox.dataset.parent;
                updateParentCheckboxState(parent);
            });
            updateToggleAllState();
        });
    </script>

    <style>
        .form-check-input:indeterminate {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .form-check-input:indeterminate:after {
            content: "";
            display: block;
            position: relative;
            width: 12px;
            height: 2px;
            background-color: white;
            left: 2px;
            top: 5px;
        }

        .child-row {
            background-color: #f8f9fa;
        }

        .toggle-children {
            transition: transform 0.2s ease;
        }
    </style>
</x-layout>
