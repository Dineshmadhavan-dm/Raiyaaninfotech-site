<x-layout>
    @section('title', 'Company Email')

    <div class="container-fluid p-4">
        <!-- Header & Breadcrumb -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 mt-2">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dhome') }}" class="text-decoration-none text-muted">
                                <i class="bi bi-people me-2"></i>Employment
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-primary" aria-current="page">Company Email</li>
                    </ol>
                </nav>
            </div>
        </div>

        <x-message />

        <!-- Table Section -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <div class="text-end mb-3">
                        <button id="filterToggleBtn" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-funnel-fill me-1"></i> Filters
                            <i class="bi bi-chevron-down ms-1 filter-arrow"></i>
                        </button>
                    </div>

                    <div id="filterPanel" class="bg-light rounded mb-3">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <input type="text" class="form-control searchfilter" id="filterName"
                                    placeholder="Enter name">
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control searchfilter" id="filterEmail"
                                    placeholder="Enter email">
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control searchfilter" id="filterDate"
                                    placeholder="Enter Created Date">
                            </div>
                            <div class="col-md-3">
                                <select class="form-select  searchfilter" id="filterLogin">
                                    <option value="">All Login Acccess</option>
                                    <option value="access">Access</option>
                                    <option value="denied">Denied</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <table id="basic-datatables" class="display table table-bordered table-hover">
                        <thead class="bg-light rounded text-center">
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>FullName</th>
                                <th>Email</th>
                                <th>Created</th>
                                <th>Login Access</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $employee)
                                <tr class="text-center align-middle">
                                    <td class="align-middle">{{ $loop->iteration }}</td>
                                    <td class="align-middle">
                                        <a href="{{ route('empshow', $employee->emp_id ?? '') }}">
                                            <img src="{{ $employee->image ? asset('employee_images/' . $employee->image) : asset('images/admin_default.jpg') }}"
                                                alt="{{ $employee->fullname }}" class="img-fluid mx-auto d-block"
                                                width="70px"></a>
                                    </td>
                                    <td class="align-middle">{{ $employee->fullname }}</td>
                                    <td class="align-middle">
                                        @if ($employee->email_company == '')
                                            <span class="badge bg-danger p-1">Not yet</span>
                                        @else
                                            {{ $employee->email_company }}
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        @if ($employee->email_company == '')
                                            <span class="badge bg-danger p-1">Not yet</span>
                                        @else
                                            {{ Carbon\Carbon::parse($employee->created_at)->format('d M Y, h:i A') }}
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        @if (empty($employee->email_company))
                                            @if (empty($employee->login_access) || $employee->login_access === 0)
                                                <span class="badge bg-danger p-1">Denied</span>
                                            @else
                                                <span class="badge bg-success p-1">Access</span>
                                            @endif
                                        @else
                                            @if ($employee->login_access === 1)
                                                <span class="badge bg-success p-1">Access</span>
                                            @else
                                                <span class="badge bg-danger p-1">Denied</span>
                                            @endif
                                        @endif
                                    </td>

                                    <td class="align-middle">
                                        <div class="d-flex justify-content-center gap-2">



                                            @if ($employee->email_company)
                                                <a href="{{ route('companyemail.edit', $employee->emp_id) }}"
                                                    class="btn btn-sm btn-icon btn-soft-info hover-grow">
                                                    <i class="bi bi-pencil text-dark"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('companyemail.create', ['employee' => $employee->emp_id]) }}"
                                                    class="btn btn-sm btn-icon btn-soft-primary hover-grow"
                                                    title="Create email for employee">
                                                    <i class="bi bi-lock"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <x-actionbtn />

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#basic-datatables').DataTable({
                responsive: true,
                paging: true,
                searching: true,
                ordering: true,
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
                    table.columns(2).search($('#filterName').val());
                    table.columns(3).search($('#filterEmail').val());
                    table.columns(4).search($('#filterDate').val());
                    table.columns(5).search($('#filterLogin').val());
                    table.draw();
                }, 500);
            });
        });
    </script>
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
