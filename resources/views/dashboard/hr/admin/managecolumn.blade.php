<x-layout>
    @section('title', 'Manage Columns')

    <!-- Add animate.css and custom styles -->


    <div class="container-fluid py-4 px-4 animate__animated animate__fadeIn">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-0 fw-semibold">
                    <i class="bi bi-columns-gap me-2"></i> Column Manager
                </h4>
                <p class="text-muted mb-0 small mt-3">Drag names to reorder them. Check/uncheck to show/hide columns.
                </p>
            </div>
            <div>
                <a href="{{ route('adminlist') }}" class="btn btn-outline-primary hover-scale">
                    <i class="bi bi-arrow-left-circle me-2"></i> Back
                </a>
            </div>
        </div>

        <x-message />

        <!-- Current Column Order Section -->
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-3">
                <div class="p-3 rounded-3">
                    <div class="d-flex flex-wrap gap-2" id="activeColumnsDisplay">
                        @foreach ($visibleColumns as $col)
                            <span
                                class="badge bg-primary text-white border px-3 py-2 rounded-2 d-flex align-items-center draggable-column"
                                data-column="{{ $col }}">
                                <span class="handle">{{ $allColumns[$col] ?? $col }}</span>
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Column Management Section -->

        <div class="">
            <ul id="sortable-columns" class="list-unstyled">
                <h6 class="mb-0 fw-semibold px-4 pt-3">System Columns</h6>
                @foreach ($orderedColumns as $key => $name)
                    <li class="list-group-item d-flex justify-content-between align-items-center px-5 mt-2 column-item"
                        data-column="{{ $key }}">
                        <div class="d-flex align-items-center">
                            <input class="form-check-input column-checkbox" type="checkbox"
                                id="column-{{ $key }}" value="{{ $key }}"
                                {{ in_array($key, $visibleColumns) ? 'checked' : '' }}>
                            <span class="fw-medium ms-2 mt-1">{{ $name }}</span>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex justify-content-end mt-4 gap-3">
            <button class="btn btn-secondary hover-scale" id="resetDefaults">
                Reset
            </button>
            <button class="btn btn-primary hover-scale" id="saveColumns">
                Update
            </button>
        </div>
    </div>

    <!-- JavaScript Libraries -->


    <script>
        $(function() {
            // Define error display function
            function showError(message) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: message,
                    confirmButtonColor: '#0d6efd'
                });
            }

            // Initialize sortable for the column list
            $("#sortable-columns").sortable({
                handle: ".handle",
                placeholder: "sortable-placeholder",
                cursor: "move",
                opacity: 0.8,
                tolerance: "pointer",
                start: function(e, ui) {
                    ui.item.addClass('animate__animated animate__pulse bg-light');
                    $(".sortable-placeholder").height(ui.item.height());
                },
                stop: function(e, ui) {
                    ui.item.removeClass('animate__animated animate__pulse bg-light')
                        .addClass('animate__animated animate__fadeIn');
                    setTimeout(() => {
                        ui.item.removeClass('animate__animated animate__fadeIn');
                    }, 500);
                    rebuildColumnOrderDisplay();
                }
            }).disableSelection();

            // Initialize sortable for the active columns display
            $("#activeColumnsDisplay").sortable({
                handle: ".handle",
                placeholder: "sortable-placeholder",
                cursor: "move",
                opacity: 0.8,
                tolerance: "pointer",
                start: function(e, ui) {
                    ui.item.addClass('animate__animated animate__pulse bg-light');
                    $(".sortable-placeholder").height(ui.item.height());
                },
                stop: function(e, ui) {
                    ui.item.removeClass('animate__animated animate__pulse bg-light')
                        .addClass('animate__animated animate__fadeIn');
                    setTimeout(() => {
                        ui.item.removeClass('animate__animated animate__fadeIn');
                    }, 500);
                    updateColumnOrder();
                }
            }).disableSelection();

            // Toggle column visibility
            $(document).on('change', '.column-checkbox', function() {
                rebuildColumnOrderDisplay();
            });

            // Rebuild the column order display
            function rebuildColumnOrderDisplay() {
                const visibleColumns = [];
                const columnOrder = [];

                // Get all columns in their current order
                $("#sortable-columns li").each(function() {
                    columnOrder.push($(this).data('column'));
                });

                // Get all checked columns
                columnOrder.forEach(col => {
                    if ($(`#column-${col}`).is(':checked')) {
                        visibleColumns.push(col);
                    }
                });

                // Update active columns display
                const displayContainer = $('#activeColumnsDisplay');
                displayContainer.empty();

                visibleColumns.forEach(col => {
                    const name = $(`li[data-column="${col}"]`).find('span.fw-medium').text();
                    displayContainer.append(`
                    <span class="badge bg-primary text-white border px-3 py-2 rounded-2 d-flex align-items-center draggable-column" data-column="${col}">
                        <span class="handle">${name}</span>
                    </span>
                `);
                });
            }

            // Update column order based on active display
            function updateColumnOrder() {
                const newOrder = [];
                $("#activeColumnsDisplay .draggable-column").each(function() {
                    newOrder.push($(this).data('column'));
                });

                // Reorder checkboxes to match
                const $sortableList = $("#sortable-columns");
                $sortableList.find('li').sort((a, b) => {
                    const aCol = $(a).data('column');
                    const bCol = $(b).data('column');
                    return newOrder.indexOf(aCol) - newOrder.indexOf(bCol);
                }).appendTo($sortableList);
            }

            // Save columns configuration
            $("#saveColumns").click(function() {
                const $btn = $(this);


                const visibleColumns = [];
                const columnOrder = [];

                // Get all checked columns
                $(".column-checkbox:checked").each(function() {
                    visibleColumns.push($(this).val());
                });

                // Get current order of all columns
                $("#sortable-columns li").each(function() {
                    columnOrder.push($(this).data('column'));
                });

                $.ajax({
                    url: "{{ route('admin.save-columns') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        columns: visibleColumns,
                        order: columnOrder
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Preferences Saved',
                                text: 'Your column configuration has been updated',
                                showConfirmButton: false,
                                timer: 1500,
                                background: '#f8f9fa',
                            }).then(() => {
                                window.location.reload(); // Reload to see changes
                            });
                        } else {
                            showError(response.message || 'Failed to save columns');
                        }
                    },
                    error: function(xhr) {
                        let message = 'Unable to save columns';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        showError(message);
                    },

                });
            });

            $("#resetDefaults").click(function() {
                const $btn = $(this);
                $btn.prop('disabled', true);

                Swal.fire({
                    title: 'Reset to Defaults?',
                    text: "This will restore the original column configuration",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#0d6efd',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, reset it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('admin.save-columns') }}",
                            method: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                reset: true,
                                // Explicitly send default columns
                                columns: ['checkbox', 'serial', 'image', 'name', 'email',
                                    'department',
                                    'role',
                                    'action'
                                ],
                                order: ['checkbox', 'serial', 'image', 'name', 'email',
                                    'department',
                                    'role',
                                    'action'
                                ]
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        title: 'Reset Complete!',
                                        text: 'Columns have been reset to defaults',
                                        icon: 'success',
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                }
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Failed to reset columns',
                                    icon: 'error'
                                });
                            },
                            complete: function() {
                                $btn.prop('disabled', false);
                            }
                        });
                    } else {
                        $btn.prop('disabled', false);
                    }
                });
            });
            // Initial display setup
            rebuildColumnOrderDisplay();
        });
    </script>
    <style>
        .handle {
            cursor: grab;
        }


        .badge {
            font-size: 0.75rem;
            font-weight: 500;
            transition: transform 0.2s;
        }
    </style>
</x-layout>
