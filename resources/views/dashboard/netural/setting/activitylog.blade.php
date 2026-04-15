<x-layout>
    @section('title', 'Activity Logs')
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
                        <li class="breadcrumb-item active text-primary" aria-current="page">Activity Logs</li>
                    </ol>
                </nav>
            </div>

        </div>

        <!-- Activity Logs Table -->
        <div class="card border-0 shadow-lg rounded-4">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <!-- Search and Filter Header -->
                    <div class="text-end mb-3">
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

                    <!-- Filter Panel (initially hidden) -->
                    <div id="filterPanel" class="bg-light rounded mb-3">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <input type="text" class="form-control searchfilter" id="filterUser"
                                    placeholder="Search user...">
                            </div>
                            <div class="col-md-3">
                                <select class="form-select searchfilter" id="filterActivity">
                                    <option value="">All Activities</option>
                                    <option value="login">Logins</option>
                                    <option value="logout">Logouts</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control searchfilter" id="filterDate"
                                    placeholder="Search date...">
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control searchfilter" id="filterLocation"
                                    placeholder="Search location...">
                            </div>
                        </div>
                    </div>

                    <!-- DataTable -->
                    <table id="activity-logs-table" class="display table table-bordered table-hover">
                        <thead class="bg-light rounded text-center">
                            <tr>
                                <th>
                                    <input type="checkbox" id="selectAll">
                                </th>
                                <th>#</th>
                                <th>User</th>
                                <th>Activity</th>
                                <th>Date|Time</th>
                                <th>IP Address</th>
                                <th>Location</th>
                                <th>Device</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($activities as $activity)
                                <tr class="text-center align-middle">
                                    <td class="align-middle">
                                        <input type="checkbox" class="empCheckbox" value="{{ $activity['id'] }}">
                                    </td>
                                    <td class="align-middle">{{ $loop->iteration }}</td>
                                    <td class="align-middle text-start">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <strong>{{ $activity['name'] }}</strong>
                                                <div class="text-muted small">{{ $activity['email'] }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <span
                                            class="badge bg-{{ $activity['activity_type'] === 'login' ? 'success' : 'danger' }} p-2">
                                            {{ ucfirst($activity['activity_type']) }}
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        @if ($activity['activity_type'] === 'login')
                                            {{ \Carbon\Carbon::parse($activity['login_time'])->format('M d, Y | h:i A') }}
                                        @else
                                            {{ \Carbon\Carbon::parse($activity['logout_time'])->format('M d, Y | h:i A') }}
                                        @endif
                                    </td>
                                    <td class="align-middle">{{ $activity['ip_address'] }}</td>
                                    <td class="align-middle">
                                        @php
                                            $locationParts = explode('|', $activity['location']);
                                            $country = $locationParts[0] ?? 'Unknown';
                                            $state = $locationParts[1] ?? 'Unknown';
                                            $city = $locationParts[2] ?? 'Unknown';
                                        @endphp

                                        <div class="d-flex flex-column">
                                            <span class="fw-bold">{{ $city }}</span>
                                            <span class="text-muted small">{{ $state }},
                                                {{ $country }}</span>
                                        </div>
                                    </td>
                                    <td class="align-middle text-start small">
                                        {{ $activity['device_info'] }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- DataTables CSS -->



        <!-- DataTables JS + Buttons -->



        <script>
            $(document).ready(function() {
                var table = $('#activity-logs-table').DataTable({
                    responsive: true,
                    paging: true,
                    searching: true,
                    ordering: true,
                    dom: '<"row mb-2"<"col-md-6"B><"col-md-6 text-end"f>>rt<"bottom d-flex justify-content-between "lip><"clear">',
                    buttons: [{
                            extend: 'csvHtml5',
                            text: '<i class="bi bi-file-earmark-spreadsheet"></i> CSV',
                            className: 'btn btn-sm btn-outline-primary me-1',
                            exportOptions: {
                                columns: [1, 2, 3, 4, 5, 6, 7],
                                rows: function(idx, data, node) {
                                    return $(node).find('.empCheckbox').is(':checked') || $(
                                        '#selectAll').is(':checked');
                                }
                            }
                        },
                        {
                            extend: 'excelHtml5',
                            text: '<i class="bi bi-file-earmark-excel"></i> Excel',
                            className: 'btn btn-sm btn-outline-primary me-1',
                            exportOptions: {
                                columns: [1, 2, 3, 4, 5, 6, 7],
                                rows: function(idx, data, node) {
                                    return $(node).find('.empCheckbox').is(':checked') || $(
                                        '#selectAll').is(':checked');
                                }
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            text: '<i class="bi bi-file-earmark-pdf"></i> PDF',
                            className: 'btn btn-sm btn-outline-primary me-1',
                            exportOptions: {
                                columns: [1, 2, 3, 4, 5, 6, 7],
                                rows: function(idx, data, node) {
                                    return $(node).find('.empCheckbox').is(':checked') || $(
                                        '#selectAll').is(':checked');
                                }
                            }
                        },

                    ]

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


                // Toggle filter panel with animation and rotate chevron
                $('#filterToggleBtn').click(function() {
                    const panel = $('#filterPanel');
                    const arrow = $(this).find('.filter-arrow');

                    panel.toggleClass('show');
                    arrow.toggleClass('rotated');
                });

                // Auto-search functionality
                let searchTimeout;
                $('.searchfilter').on('input change', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        // Search in user column (name and email)
                        table.columns(2).search($('#filterUser').val());
                        // Search in activity type column
                        table.columns(3).search($('#filterActivity').val());
                        // Search in date column
                        table.columns(4).search($('#filterDate').val());
                        // Search in location column
                        table.columns(5).search($('#filterLocation').val());
                        table.draw();
                    }, 500);
                });
            });
        </script>
        <style>
            .dt-button-down-arrow {
                font-size: 11px;
                margin-left: 10px;
            }

            .dt-button i {
                margin-right: 7px;
            }

            .buttons-columnVisibility {
                border-radius: 7px;
                padding: 5px;
                background-color: #222222;
                color: #fff;
            }
        </style>
        <style>
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
                /* adjust as per your content height */
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
    </div>
</x-layout>
