<x-layout>
    @section('title', 'Manage Columns')

    <div class="container-fluid py-4 px-4 animate__animated animate__fadeIn">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-0 fw-semibold text-gradient">
                    <i class="bi bi-columns-gap me-2"></i> Manage Columns
                </h4>
                <p class="text-muted mb-0 small mt-3">Drag names to reorder them. Check/uncheck to show/hide columns.</p>
            </div>
            <div>
                <a href="{{ route('emplist') }}" class="btn btn-outline-primary hover-scale">
                    <i class="bi bi-arrow-left-circle me-2"></i> Back
                </a>
            </div>
        </div>

        <x-message />

        <!-- Current Column Order -->
        <div class="card border-0 shadow-lg rounded-4 mb-4">
            <div class="card-body p-4">
                <h5 class="mb-3 fw-semibold">Current Column Order</h5>
                <div class="d-flex flex-wrap gap-2" id="columnOrderContainer">
                    @php
                        $visibleCols = $savedPrefs['visible_columns'] ?? [];
                        $orderedCols = $savedPrefs['column_order'] ?? [];
                        $mergedCols = array_unique(array_merge($orderedCols, $visibleCols));
                    @endphp

                    @foreach ($mergedCols as $col)
                        @php
                            $label = '';
                            foreach ($allColumns as $category => $columns) {
                                if (isset($columns[$col])) {
                                    $label = $columns[$col];
                                    break;
                                }
                            }
                        @endphp
                        @if ($label && in_array($col, $visibleCols))
                            <span class="badge bg-primary p-2 draggable-column" style=" cursor: pointer;"
                                data-column="{{ $col }}">
                                {{ $label }}
                                <input type="hidden" name="column_order[]" value="{{ $col }}">
                            </span>
                        @endif
                    @endforeach


                </div>
            </div>
        </div>

        <!-- Column Management Sections -->
        <form action="{{ route('save.columns') }}" method="POST" id="columnsForm">
            @csrf

            <div class="row p-3">
                <div class="mb-5">
                    <h5 class="fw-semibold">Column Visibility Settings</h5>
                </div>



                <!-- Personal Information -->
                <div class="col-md-2 mb-4">
                    <div class="rounded-4 h-100">
                        <div class="card-header bg-light">
                            <h6 class="fw-semibold mb-0">Personal Information</h6>
                        </div>
                        <div class="card-body mt-4">
                            @foreach ($allColumns['personal'] as $key => $label)
                                <div class="form-check mb-2">
                                    <input class="form-check-input column-toggle" type="checkbox"
                                        name="visible_columns[]" value="{{ $key }}"
                                        id="{{ $key }}Toggle"
                                        {{ in_array($key, $savedPrefs['visible_columns']) ? 'checked' : '' }}>
                                    <label class="form-check-label"
                                        for="{{ $key }}Toggle">{{ $label }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Family Details -->
                <div class="col-md-2 mb-4">
                    <div class="rounded-4 h-100">
                        <div class="card-header bg-light">
                            <h6 class="fw-semibold mb-0">Family Details</h6>
                        </div>
                        <div class="card-body mt-4">
                            @foreach ($allColumns['family'] as $key => $label)
                                <div class="form-check mb-2">
                                    <input class="form-check-input column-toggle" type="checkbox"
                                        name="visible_columns[]" value="{{ $key }}"
                                        id="{{ $key }}Toggle"
                                        {{ in_array($key, $savedPrefs['visible_columns']) ? 'checked' : '' }}>
                                    <label class="form-check-label"
                                        for="{{ $key }}Toggle">{{ $label }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Education Details -->
                <div class="col-md-2 mb-4">
                    <div class="rounded-4 h-100">
                        <div class="card-header bg-light">
                            <h6 class="fw-semibold mb-0">Education Details</h6>
                        </div>
                        <div class="card-body mt-4">
                            @foreach ($allColumns['education'] as $key => $label)
                                <div class="form-check mb-2">
                                    <input class="form-check-input column-toggle" type="checkbox"
                                        name="visible_columns[]" value="{{ $key }}"
                                        id="{{ $key }}Toggle"
                                        {{ in_array($key, $savedPrefs['visible_columns']) ? 'checked' : '' }}>
                                    <label class="form-check-label"
                                        for="{{ $key }}Toggle">{{ $label }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Past Employment -->
                <div class="col-md-2 mb-4">
                    <div class="rounded-4 h-100">
                        <div class="card-header bg-light">
                            <h6 class="fw-semibold mb-0">Past Employment</h6>
                        </div>
                        <div class="card-body mt-4">
                            @foreach ($allColumns['past_employment'] as $key => $label)
                                <div class="form-check mb-2">
                                    <input class="form-check-input column-toggle" type="checkbox"
                                        name="visible_columns[]" value="{{ $key }}"
                                        id="{{ $key }}Toggle"
                                        {{ in_array($key, $savedPrefs['visible_columns']) ? 'checked' : '' }}>
                                    <label class="form-check-label"
                                        for="{{ $key }}Toggle">{{ $label }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Current Employment -->
                <div class="col-md-2 mb-4">
                    <div class="rounded-4 h-100">
                        <div class="card-header bg-light">
                            <h6 class="fw-semibold mb-0">Current Employment</h6>
                        </div>
                        <div class="card-body mt-4">
                            @foreach ($allColumns['current_employment'] as $key => $label)
                                <div class="form-check mb-2">
                                    <input class="form-check-input column-toggle" type="checkbox"
                                        name="visible_columns[]" value="{{ $key }}"
                                        id="{{ $key }}Toggle"
                                        {{ in_array($key, $savedPrefs['visible_columns']) ? 'checked' : '' }}>
                                    <label class="form-check-label"
                                        for="{{ $key }}Toggle">{{ $label }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- References -->
                <div class="col-md-2 mb-4">
                    <div class="rounded-4 h-100">
                        <div class="card-header bg-light">
                            <h6 class="fw-semibold mb-0">References</h6>
                        </div>
                        <div class="card-body mt-4">
                            @foreach ($allColumns['references'] as $key => $label)
                                <div class="form-check mb-2">
                                    <input class="form-check-input column-toggle" type="checkbox"
                                        name="visible_columns[]" value="{{ $key }}"
                                        id="{{ $key }}Toggle"
                                        {{ in_array($key, $savedPrefs['visible_columns']) ? 'checked' : '' }}>
                                    <label class="form-check-label"
                                        for="{{ $key }}Toggle">{{ $label }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-end mt-4">
                    <div>
                        <button type="button" class="btn btn-secondary" id="resetDefaultsBtn">
                            RESET
                        </button>

                        <button type="submit" class="btn btn-primary" id="saveColumnsBtn">
                            SAVE
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Include SortableJS for drag and drop functionality -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Store the original/default column order and visibility
            const defaultVisible = @json($defaultVisible);
            const allColumns = @json($allColumns);
            let sortable;

            // Initialize sortable for column ordering
            function initializeSortable() {
                sortable = new Sortable(document.getElementById('columnOrderContainer'), {
                    animation: 150,
                    ghostClass: 'bg-light',
                    onEnd: function() {
                        updateColumnOrderInputs();
                    }
                });
            }
            initializeSortable();

            // Function to update the hidden inputs with current column order
            function updateColumnOrderInputs() {
                const container = document.getElementById('columnOrderContainer');
                const columns = container.querySelectorAll('.draggable-column');
                const form = document.getElementById('columnsForm');

                // Remove existing order inputs
                const existingInputs = form.querySelectorAll('input[name="column_order[]"]');
                existingInputs.forEach(input => input.remove());

                // Add new order inputs based on current order
                columns.forEach(column => {
                    const columnName = column.getAttribute('data-column');
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'column_order[]';
                    input.value = columnName;
                    form.appendChild(input);
                });
            }

            // Function to rebuild the column order display based on checked checkboxes
            function rebuildColumnOrderDisplay() {
                const container = document.getElementById('columnOrderContainer');
                const form = document.getElementById('columnsForm');

                const checkedColumns = Array.from(document.querySelectorAll('.column-toggle:checked'))
                    .map(checkbox => checkbox.value);

                container.innerHTML = '';

                // Combine current saved order and any newly checked columns
                const currentOrder = @json($savedPrefs['column_order'] ?? $defaultVisible);
                const orderedSet = new Set(currentOrder);

                // Add any newly checked columns not in original order
                checkedColumns.forEach(col => {
                    if (!orderedSet.has(col)) {
                        currentOrder.push(col); // append new columns to end
                    }
                });

                // Rebuild display with all checked columns in updated order
                currentOrder.forEach(col => {
                    if (checkedColumns.includes(col)) {
                        // Find the column label
                        let label = '';
                        for (const category in allColumns) {
                            if (allColumns[category][col]) {
                                label = allColumns[category][col];
                                break;
                            }
                        }

                        if (label) {
                            const badge = document.createElement('span');
                            badge.className = 'badge bg-primary p-2 draggable-column';
                            badge.setAttribute('data-column', col);
                            badge.innerHTML =
                                `${label}<input type="hidden" name="column_order[]" value="${col}">`;
                            container.appendChild(badge);
                        }
                    }
                });

                // Reinitialize Sortable
                if (sortable) sortable.destroy();
                initializeSortable();

                updateColumnOrderInputs();
            }

            // Set initial column order
            updateColumnOrderInputs();

            // Handle checkbox changes to show/hide columns
            document.querySelectorAll('.column-toggle').forEach(checkbox => {
                checkbox.addEventListener('change', rebuildColumnOrderDisplay);
            });

            // Enhanced Reset to Defaults functionality with SweetAlert
            document.getElementById('resetDefaultsBtn').addEventListener('click', function() {
                Swal.fire({
                    title: 'Reset Columns?',
                    text: "This will reset all columns to default settings!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, reset to defaults!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading indicator
                        Swal.fire({
                            title: 'Resetting...',
                            html: 'Please wait while we reset your columns',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // 1. Reset checkboxes
                        document.querySelectorAll('.column-toggle').forEach(checkbox => {
                            checkbox.checked = false;
                        });

                        // Check only the default columns
                        defaultVisible.forEach(col => {
                            const checkbox = document.getElementById(`${col}Toggle`);
                            if (checkbox) checkbox.checked = true;
                        });

                        // 2. Rebuild the column display
                        rebuildColumnOrderDisplay();

                        // 3. Send AJAX request to reset in database
                        fetch('{{ route('reset.columns') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    reset: true
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire(
                                        'Reset Complete!',
                                        'All columns have been reset to default settings.',
                                        'success'
                                    );
                                } else {
                                    throw new Error('Reset failed');
                                }
                            })
                            .catch(error => {
                                Swal.fire(
                                    'Error!',
                                    'Failed to reset columns. Please try again.',
                                    'error'
                                );
                                console.error('Error:', error);
                            });
                    }
                });
            });
        });
    </script>
</x-layout>
