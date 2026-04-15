<x-layout>
    @section('title', 'Employee ')
    <div class="container-fluid py-4 px-4">
        <!-- Header & Breadcrumb -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 mt-2">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dhome') }}" class="text-decoration-none text-muted">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-primary" aria-current="page">Employment</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">

                <a href="{{ route('empcreate') }}" class=" text-decoration-none">
                    <button class="btn btn-outline-primary shadow d-flex align-items-center">
                        <i class="bi bi-plus-lg me-2"></i> Add Employee
                    </button>
                </a>
                <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal"
                    data-bs-target="#importModal">
                    <i class="bi bi-upload me-1"></i> Import Employees
                </button>
            </div>
        </div>



        <!-- Import Modal -->
        <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="importModalLabel">Import Employees</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            Download the template file and fill in your employee data.
                            Ensure all required fields are completed.
                        </div>

                        <form action="{{ route('employee.import') }}" method="POST" enctype="multipart/form-data"
                            id="importForm">
                            @csrf

                            <div class="mb-3">
                                <label for="file" class="form-label">Select Excel File</label>
                                <input type="file" class="form-control" id="file" name="file" accept=".xlsx,.xls,.csv"
                                    required>
                                <div class="form-text">Only Excel files (.xlsx, .xls) or CSV files</div>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ asset('templates/employee_dumb_data.xlsx') }}" download
                                        class="btn btn-outline-primary">
                                        <i class="bi bi-download me-1"></i> Download Template
                                    </a>

                                    <button type="submit" class="btn btn-primary" id="importSubmit">
                                        <i class="bi bi-upload me-1"></i> Import Employees
                                    </button>
                                </div>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>


        <script>
            document.getElementById('importForm').addEventListener('submit', function (e) {
                const submitBtn = document.getElementById('importSubmit');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-arrow-repeat spinner"></i> Importing...';
            });
        </script>

        <!--  Table -->
        <div class="card border-0 shadow-lg rounded-4">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <!-- Search and Filter Header -->
                    <div class="text-end mb-3">

                        <a href="{{ route('employeditcolumn') }}"> <button id="editcolumnBtn"
                                class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-columns-gap me-1"></i> Edit
                                Columns
                            </button></a>


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
                                <input type="text" class="form-control searchfilter" id="filterName"
                                    placeholder="Search name...">
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control searchfilter" id="filterDoj"
                                    placeholder="Search join date...">
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control searchfilter" id="filterEmail"
                                    placeholder="Search email...">
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control searchfilter" id="filterMobile"
                                    placeholder="Search mobile...">
                            </div>
                        </div>
                    </div>

                    <x-message />







                    <table id="basic-datatables" class="display table table-bordered table-hover">


                        <!-- In your table header -->
                        <thead class="bg-light rounded text-center">
                            <tr>

                                @foreach ($visibleColumns as $col)
                                    @if ($col === 'checkbox')
                                        <th><input type="checkbox" id="selectAll"></th>
                                    @else
                                        <th>{{ Str::upper($columnNames[$col] ?? $col) }}</th>
                                    @endif
                                @endforeach
                            </tr>
                        </thead>

                        <!-- In your table body -->
                        <tbody>
                            @foreach ($personal as $index => $det)
                                <tr class="text-center align-middle">
                                    @foreach ($visibleColumns as $col)
                                        @if ($col === 'checkbox')
                                            <td class="align-middle">
                                                <input type="checkbox" class="empCheckbox" value="{{ $det->emp_id ?? '' }}">
                                            </td>
                                        @elseif($col === 'serial')
                                            <td class="align-middle">{{ $index + 1 }}</td>
                                        @elseif($col === 'emp_id')
                                            <td class="align-middle">{{ $det->employee_id }}</td>
                                        @elseif($col === 'image')
                                            <td class="align-middle">
                                                <a href="{{ route('empshow', $det->emp_id ?? '') }}">
                                                    <img src="{{ !empty($det->image) ? asset('employee_images/' . $det->image) : asset('images/admin_default.jpg') }}"
                                                        alt="{{ $det->fullname ?? 'Employee' }}" class="img-fluid mx-auto d-block"
                                                        width="70px"></a>
                                            </td>
                                        @elseif($col === 'name')
                                            <td class="align-middle">{{ $det->fullname ?? 'Empty' }}</td>
                                        @elseif($col === 'fathername')
                                            <td>{{ $det->fathername ?? 'Empty' }}</td>
                                        @elseif($col === 'address')
                                            <td>{{ $det->address ?? 'Empty' }}</td>
                                        @elseif($col === 'personal_email')
                                            <td>{{ $det->personal_email ?? 'Empty' }}</td>
                                        @elseif($col === 'personal_mobile')
                                            <td>{{ $det->personal_mobile ?? 'Empty' }}</td>
                                        @elseif($col === 'blood_group')
                                            <td>{{ $det->bloodGroupid->bloodgroup_name ?? 'Empty' }}</td>
                                        @elseif($col === 'gender')
                                            <td>{{ $det->gender ?? 'Empty' }}</td>
                                        @elseif($col === 'marital_status')
                                            <td>{{ $det->marital_status ?? 'Empty' }}</td>
                                        @elseif($col === 'dob')
                                            <td>{{ $det->dob ?? 'Empty' }}</td>
                                        @elseif($col === 'pancard_no')
                                            <td>{{ $det->pancard_no ?? 'Empty' }}</td>
                                        @elseif($col === 'aadhaar_no')
                                            <td>{{ $det->aadhaar_no ?? 'Empty' }}</td>
                                        @elseif($col === 'contact_person')
                                            <td>{{ $det->c_personal_emergency ?? 'Empty' }}</td>
                                        @elseif($col === 'relation')
                                            <td>{{ $det->relationShipid->relationship_name ?? 'Empty' }}</td>
                                        @elseif($col === 'emergency_contact')
                                            <td>{{ $det->emergency_contact ?? 'Empty' }}</td>
                                        @elseif($col === 'family_member_name' && isset($det->families) && count($det->families) > 0)
                                            <td>
                                                @foreach ($det->families as $family)
                                                    {{ $family->fa_name }}<br>
                                                @endforeach
                                            </td>
                                        @elseif($col === 'family_relation' && isset($det->families) && count($det->families) > 0)
                                            <td>
                                                @foreach ($det->families as $family)
                                                    {{ $family->relationShipid->relationship_name }}<br>
                                                @endforeach
                                            </td>
                                        @elseif($col === 'occupation' && isset($det->families) && count($det->families) > 0)
                                            <td>
                                                @foreach ($det->families as $family)
                                                    {{ $family->fa_occupation }}<br>
                                                @endforeach
                                            </td>
                                        @elseif($col === 'qualification' && isset($det->educations) && count($det->educations) > 0)
                                            <td>
                                                @foreach ($det->educations as $edu)
                                                    {{ $edu->qualification }}<br>
                                                @endforeach
                                            </td>
                                        @elseif($col === 'institution_name' && isset($det->educations) && count($det->educations) > 0)
                                            <td>
                                                @foreach ($det->educations as $edu)
                                                    {{ $edu->name_of_institution }}<br>
                                                @endforeach
                                            </td>
                                        @elseif($col === 'education_location' && isset($det->educations) && count($det->educations) > 0)
                                            <td>
                                                @foreach ($det->educations as $edu)
                                                    {{ $edu->edu_location }}<br>
                                                @endforeach
                                            </td>
                                        @elseif($col === 'education_from_date' && isset($det->educations) && count($det->educations) > 0)
                                            <td>
                                                @foreach ($det->educations as $edu)
                                                    {{ $edu->edu_from_date }}<br>
                                                @endforeach
                                            </td>
                                        @elseif($col === 'education_to_date' && isset($det->educations) && count($det->educations) > 0)
                                            <td>
                                                @foreach ($det->educations as $edu)
                                                    {{ $edu->edu_to_date }}<br>
                                                @endforeach
                                            </td>
                                        @elseif($col === 'specialization' && isset($det->educations) && count($det->educations) > 0)
                                            <td>
                                                @foreach ($det->educations as $edu)
                                                    {{ $edu->specialization }}<br>
                                                @endforeach
                                            </td>
                                        @elseif($col === 'percentage' && isset($det->educations) && count($det->educations) > 0)
                                            <td>
                                                @foreach ($det->educations as $edu)
                                                    {{ $edu->percentage_grade }}<br>
                                                @endforeach
                                            </td>
                                        @elseif($col === 'past_employed_as' && isset($det->pastemps) && count($det->pastemps) > 0)
                                            <td>
                                                @foreach ($det->pastemps as $past)
                                                    {{ $past->employed_as }}<br>
                                                @endforeach
                                            </td>
                                        @elseif($col === 'past_org_name' && isset($det->pastemps) && count($det->pastemps) > 0)
                                            <td>
                                                @foreach ($det->pastemps as $past)
                                                    {{ $past->past_organisation_name }}<br>
                                                @endforeach
                                            </td>
                                        @elseif($col === 'past_location' && isset($det->pastemps) && count($det->pastemps) > 0)
                                            <td>
                                                @foreach ($det->pastemps as $past)
                                                    {{ $past->past_location }}<br>
                                                @endforeach
                                            </td>
                                        @elseif($col === 'past_designation' && isset($det->pastemps) && count($det->pastemps) > 0)
                                            <td>
                                                @foreach ($det->pastemps as $past)
                                                    {{ $past->past_designation }}<br>
                                                @endforeach
                                            </td>
                                        @elseif($col === 'past_department' && isset($det->pastemps) && count($det->pastemps) > 0)
                                            <td>
                                                @foreach ($det->pastemps as $past)
                                                    {{ $past->past_department }}<br>
                                                @endforeach
                                            </td>
                                        @elseif($col === 'past_role' && isset($det->pastemps) && count($det->pastemps) > 0)
                                            <td>
                                                @foreach ($det->pastemps as $past)
                                                    {{ $past->past_role }}<br>
                                                @endforeach
                                            </td>
                                        @elseif($col === 'past_annual_ctc' && isset($det->pastemps) && count($det->pastemps) > 0)
                                            <td>
                                                @foreach ($det->pastemps as $past)
                                                    {{ $past->past_annual_ctc }}<br>
                                                @endforeach
                                            </td>
                                        @elseif($col === 'past_from_date' && isset($det->pastemps) && count($det->pastemps) > 0)
                                            <td>
                                                @foreach ($det->pastemps as $past)
                                                    {{ $past->past_from_date }}<br>
                                                @endforeach
                                            </td>
                                        @elseif($col === 'past_to_date' && isset($det->pastemps) && count($det->pastemps) > 0)
                                            <td>
                                                @foreach ($det->pastemps as $past)
                                                    {{ $past->past_to_date }}<br>
                                                @endforeach
                                            </td>
                                        @elseif($col === 'has_uan' && isset($det->pastemps) && count($det->pastemps) > 0)
                                            <td>
                                                @foreach ($det->pastemps as $past)
                                                    {{ $past->has_uan }}<br>
                                                @endforeach
                                            </td>
                                        @elseif($col === 'uan_number' && isset($det->pastemps) && count($det->pastemps) > 0)
                                            <td>
                                                @foreach ($det->pastemps as $past)
                                                    {{ $past->uan_number }}<br>
                                                @endforeach
                                            </td>
                                        @elseif($col === 'current_department')
                                            <td>

                                                @if ($det->Departmentid->dep_name == '')
                                                    <span><i class=" bi bi-plus-circle"></i></span>
                                                @else
                                                    {{ $det->Departmentid->dep_name ?? ''}}
                                                @endif








                                            </td>
                                        @elseif($col === 'current_designation')
                                            <td>

                                           @if (!$det->Designationid || empty($det->Designationid->des_name))
    <span class="badge bg-danger p-1">Not yet</span>
@else
    {{ $det->Designationid->des_name ?? '' }}
@endif



                                            </td>
                                        @elseif($col === 'current_location')
                                            <td>{{ $det->Branchid->branch_name ?? 'Empty' }}</td>
                                        @elseif($col === 'jobtype')
                                            <td>{{ $det->Jobtypeid->jobtype_name ?? 'Empty' }}</td>
                                        @elseif($col === 'current_annual_ctc')
                                            <td>{{ $det->cur_annual_ctc ?? 'Empty' }}</td>
                                        @elseif($col === 'company_email')
                                            <td>

                                                @if ($det->email_company == '')
                                                    <a href="{{ route('companyemail.create', ['employee' => $det->emp_id]) }}">
                                                        <span><i class="bi bi-plus-circle"></i></span>
                                                    </a>
                                                @else
                                                    @if ($det->login_access == 1)
                                                        <a class=" text-decoration-none"
                                                            href="{{ route('companyemail.edit', ['employee' => $det->emp_id]) }}">
                                                            <span class=" text-success fw-medium">
                                                                {{ $det->email_company }} <span class=" badge text-success fw-bold">[Login
                                                                    Access]</span></span>
                                                        </a>
                                                    @else
                                                        <a class=" text-decoration-none"
                                                            href="{{ route('companyemail.edit', ['employee' => $det->emp_id]) }}">
                                                            <span class=" text-danger   fw-medium">
                                                                {{ $det->email_company }} <span class=" badge text-danger fw-bold">[Login
                                                                    Denied]</span></span>
                                                        </a>
                                                    @endif
                                                @endif




                                            </td>
                                        @elseif($col === 'doj')
                                            <td>

                                                @if ($det->dojprovision_from_date == '')
                                                    <span class=" badge bg-danger p-1"> Not yet</span>
                                                @else
                                                    {{ \Carbon\Carbon::parse($det->dojprovision_from_date)->format('d-m-Y') }}
                                                @endif




                                            </td>
                                        @elseif($col === 'ref_name' && isset($det->prorefs) && count($det->prorefs) > 0)
                                            <td>
                                                @foreach ($det->prorefs as $ref)
                                                    {{ $ref->ref_name }}<br>
                                                @endforeach
                                            </td>
                                        @elseif($col === 'ref_org_name' && isset($det->prorefs) && count($det->prorefs) > 0)
                                            <td>
                                                @foreach ($det->prorefs as $ref)
                                                    {{ $ref->ref_organization_name }}<br>
                                                @endforeach
                                            </td>
                                        @elseif($col === 'ref_designation' && isset($det->prorefs) && count($det->prorefs) > 0)
                                            <td>
                                                @foreach ($det->prorefs as $ref)
                                                    {{ $ref->ref_designation }}<br>
                                                @endforeach
                                            </td>
                                        @elseif($col === 'ref_mobile' && isset($det->prorefs) && count($det->prorefs) > 0)
                                            <td>
                                                @foreach ($det->prorefs as $ref)
                                                    {{ $ref->mobile_no }}<br>
                                                @endforeach
                                            </td>
                                        @elseif($col === 'ref_email' && isset($det->prorefs) && count($det->prorefs) > 0)
                                            <td>
                                                @foreach ($det->prorefs as $ref)
                                                    {{ $ref->email_ref }}<br>
                                                @endforeach
                                            </td>
                                        @elseif($col === 'employee_data')
                                            <td class="align-middle">
                                                @if (isset($det->status_for_stepform) && $det->status_for_stepform == 6)
                                                    <span class="badge bg-success p-2">Complete</span>
                                                @else
                                                    <span class="badge bg-danger p-2">Incomplete</span>
                                                @endif
                                            </td>
                                        @elseif($col === 'action')
                                            <td class="align-middle">
                                                <div class="d-flex justify-content-center gap-2">
                                                    @can('hr->employee view')
                                                        <a href="{{ route('empshow', $det->emp_id ?? '') }}"
                                                            class="btn btn-sm btn-icon btn-soft-info hover-grow">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    @endcan @can('hr->employee edit')
                                                        <a href="{{ route('empedit', $det->emp_id ?? '') }}"
                                                            class="btn btn-sm btn-icon btn-soft-warning hover-grow">
                                                            <i class="bi bi-pencil-fill"></i>
                                                        </a>
                                                    @endcan @can('hr->employee delete')
                                                        <a href="javascript:void(0);" onclick="deleteemp({{ $det->emp_id ?? '' }})"
                                                            class="btn btn-sm btn-icon btn-soft-danger hover-grow">
                                                            <i class="bi bi-trash"></i>
                                                        </a>
                                                    @endcan
                                                </div>
                                            </td>
                                        @else
                                            <td>N/A</td>
                                        @endif
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>




                </div>
            </div>
        </div>




























        <script>
            function deleteemp(emp_id) {
                Swal.fire({
                    title: "Are you sure you want to delete?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('empdestroy') }}',
                            type: 'DELETE',
                            data: {
                                emp_id: emp_id,
                                _token: '{{ csrf_token() }}'
                            },
                            dataType: 'json',
                            success: function (response) {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "The employee has been removed.",
                                    icon: "success",
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.href = '{{ route('emplist') }}';
                                });
                            },
                            error: function () {
                                Swal.fire({
                                    title: "Error!",
                                    text: "Something went wrong. Try again.",
                                    icon: "error"
                                });
                            }
                        });
                    }
                });
            }
        </script>
        <script>
            $(document).ready(function () {
                var table = $('#basic-datatables').DataTable({
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
                            // columns: [1, 3, 4, 5, 6, 7, 8, 9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,],
                            rows: function (idx, data, node) {
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
                            // columns: [1, 3, 4, 5, 6, 7, 8, 9],
                            rows: function (idx, data, node) {
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
                            // columns: [1, 3, 4, 5, 6, 7, 8, 9],
                            rows: function (idx, data, node) {
                                return $(node).find('.empCheckbox').is(':checked') || $(
                                    '#selectAll').is(':checked');
                            }
                        }
                    },
                        // {
                        //     extend: 'print',
                        //     text: '<i class="bi bi-printer"></i> Print',
                        //     className: 'btn btn-sm btn-primary me-1',
                        //     exportOptions: {
                        //         columns: ':not(:first-child)',
                        //         rows: function(idx, data, node) {
                        //             return $(node).find('.empCheckbox').is(':checked') || $(
                        //                 '#selectAll').is(':checked');
                        //         }
                        //     }
                        // },
                    ]
                });

                // Toggle select all checkboxes
                $('#selectAll').on('click', function () {
                    $('.empCheckbox').prop('checked', this.checked);
                    toggleBulkActionButtons();
                });

                // Toggle bulk action buttons when any checkbox is clicked
                $(document).on('change', '.empCheckbox', function () {
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
                $('#bulkDeleteBtn').on('click', function () {
                    var selectedIds = [];
                    $('.empCheckbox:checked').each(function () {
                        selectedIds.push($(this).val());
                    });

                    if (selectedIds.length === 0) {
                        Swal.fire('Error', 'Please select at least one employee to delete', 'error');
                        return;
                    }

                    Swal.fire({
                        title: "Are you sure you want to delete selected employees?",
                        text: "This action cannot be undone!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Yes, delete them!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '{{ route('empbulkdelete') }}',
                                type: 'DELETE',
                                data: {
                                    emp_ids: selectedIds,
                                    _token: '{{ csrf_token() }}'
                                },
                                dataType: 'json',
                                success: function (response) {
                                    Swal.fire({
                                        title: "Deleted!",
                                        text: "Selected employees have been removed.",
                                        icon: "success",
                                        timer: 2000,
                                        showConfirmButton: false
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                },
                                error: function () {
                                    Swal.fire({
                                        title: "Error!",
                                        text: "Something went wrong. Try again.",
                                        icon: "error"
                                    });
                                }
                            });
                        }
                    });
                });



                // Toggle filter panel with animation and rotate chevron`
                $('#filterToggleBtn').click(function () {
                    const panel = $('#filterPanel');
                    const arrow = $(this).find('.filter-arrow');

                    panel.toggleClass('show');
                    arrow.toggleClass('rotated');
                });

                // Auto-search functionality
                let searchTimeout;
                $('.searchfilter').on('input', function () {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        table.columns(2).search($('#filterName').val()); // Name column
                        table.columns(3).search($('#filterDoj').val()); // Date of Join column
                        table.columns(4).search($('#filterEmail').val()); // Email column
                        table.columns(5).search($('#filterMobile').val()); // Mobile column
                        table.draw();
                    }, 500);
                });
            });
        </script>
    </div>

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
    <x-actionbtn />

</x-layout>
