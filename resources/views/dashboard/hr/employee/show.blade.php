<x-layout>
    @section('title', 'Employee View')
    <div class="container-fluid py-4 px-4">
        <!-- Header & Breadcrumb -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0">
                        <li class="breadcrumb-item">
                            <a href="#" class="text-decoration-none text-muted">
                                <i class="bi bi-people-fill me-2"></i>Employment
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-primary" aria-current="page">Employee Details</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('companyemail.index') }}" class="btn btn-outline-primary ">
                    <i class="bi bi-arrow-left-circle me-2"></i>Login Email
                </a>
                <a href="{{ route('emplist') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left-circle me-2"></i>Back
                </a>
            </div>
        </div>

        <!-- Employee Profile Header -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center">
                        <img src="{{ $personal->image ? asset('employee_images/' . $personal->image) : asset('images/admin_default.jpg') }}"
                            alt="Profile Image" class="img-fluid rounded-circle" width="120px">
                    </div>
                    <div class="col-md-10">
                        <h2 class="mb-3">{{ $personal->fullname }}</h2>
                        <div class="d-flex flex-wrap gap-3 mb-3 align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-person-badge"></i>
                                <label class="mb-0">Designation:</label>
                                <span class="badge bg-primary p-2">
                                    {{ $personal->Designationid->des_name ?? '' }}
                                </span>
                            </div>
                            <div class="d-flex align-items-center gap-2 ms-4">
                                <i class="bi bi-diagram-3"></i>
                                <label class="mb-0">Department:</label>
                                <span class="badge bg-secondary p-2">
                                    {{ $personal->Departmentid->dep_name  ?? ''}}
                                </span>
                            </div>
                            <div class="d-flex align-items-center gap-2 ms-4">
                                <i class="bi bi-person-gear"></i>
                                <label class="mb-0">Role:</label>
                                <span class="badge bg-primary p-2">




                                    {{ optional($personal->user)->getRoleNames()?->implode(', ') ?? 'Not yet' }}

                                </span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <p class="mb-1"><i class="bi bi-envelope me-2"></i>

                                    @if ($personal->email_company == '')
                                        <span class=" badge bg-danger p-2"> Not Yet</span>
                                    @else
                                        {{ $personal->email_company }}
                                    @endif





                                </p>
                            </div>
                            <div class="col-md-3">
                                <p class="mb-1"><i class="bi bi-phone me-2"></i> {{ $personal->personal_mobile }}</p>
                            </div>
                            <div class="col-md-3">
                                <p class="mb-1"><i class="bi bi-calendar me-2"></i> DOB:
                                    {{ \Carbon\Carbon::parse($personal->dob)->format('d-m-Y') }}</p>
                            </div>
                            <div class="col-md-3">
                                <p class="mb-1">
                                    <i class="bi bi-gender-{{ $personal->gender == 0 ? 'male' : 'female' }} me-2"></i>
                                    Gender:
                                    <span class="badge bg-{{ $personal->gender == 0 ? 'info' : 'pink' }} p-2">
                                        {{ $personal->gender == 0 ? 'Male' : 'Female' }}
                                    </span>
                                </p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Tabs -->
        <div class="row">
            <div class="col-md-3">
                <div class="nav flex-column nav-pills shadow" id="v-pills-tab" role="tablist"
                    aria-orientation="vertical">
                    <button class="nav-link active" id="v-pills-personal-tab" data-bs-toggle="pill"
                        data-bs-target="#v-pills-personal" type="button" role="tab">Personal Details</button>
                    <button class="nav-link" id="v-pills-family-tab" data-bs-toggle="pill"
                        data-bs-target="#v-pills-family" type="button" role="tab">Family</button>
                    <button class="nav-link" id="v-pills-education-tab" data-bs-toggle="pill"
                        data-bs-target="#v-pills-education" type="button" role="tab">Education</button>
                    <button class="nav-link" id="v-pills-employment-tab" data-bs-toggle="pill"
                        data-bs-target="#v-pills-employment" type="button" role="tab">Employment</button>
                    <button class="nav-link" id="v-pills-references-tab" data-bs-toggle="pill"
                        data-bs-target="#v-pills-references" type="button" role="tab">References</button>
                    <!-- Add this with the other nav-pills buttons -->
                    <button class="nav-link" id="v-pills-leave-tab" data-bs-toggle="pill"
                        data-bs-target="#v-pills-leave" type="button" role="tab">Leave</button>
                </div>
            </div>
            <div class="col-md-9">
                <div class="tab-content" id="v-pills-tabContent">
                    <!-- Personal Details Tab -->
                    <div class="tab-pane fade show active" id="v-pills-personal" role="tabpanel">
                        <div class="card">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0"><i class="bi bi-person-lines-fill me-2"></i>Personal
                                    Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted small mb-1">Father's Name</label>
                                            <p class="mb-0">{{ $personal->fathername }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted small mb-1">Marital Status</label>
                                            <p class="mb-0">
                                                <span
                                                    class="badge bg-{{ $personal->marital_status == 1 ? 'success' : 'info' }} text-capitalize p-2">
                                                    {{ $personal->marital_status == 1 ? 'Married' : 'Single' }}

                                                </span>
                                            </p>
                                        </div>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted small mb-1">PAN Card</label>
                                            <p class="mb-0">{{ $personal->pancard_no }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted small mb-1">Aadhaar Number</label>
                                            <p class="mb-0">{{ $personal->aadhaar_no }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted small mb-1">Address</label>
                                            <p class="mb-0">{{ $personal->address }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted small mb-1">Blood Group</label>
                                            <p class="mb-0">
                                                {{ $personal->bloodGroupid->bloodgroup_name ?? '' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Emergency Contact Card -->
                        <div class="card mt-4">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0"><i class="bi bi-telephone-plus me-2"></i>Emergency Contact
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label text-muted small mb-1">Contact Person</label>
                                            <p class="mb-0">{{ $personal->c_person_emergency }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label text-muted small mb-1">Relation</label>
                                            <p class="mb-0">
                                                {{ $personal->relationShipid->relationship_name ?? '' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label text-muted small mb-1">Contact Number</label>
                                            <p class="mb-0">{{ $personal->emergency_contact }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Family Details Tab -->
                    <div class="tab-pane fade" id="v-pills-family" role="tabpanel">
                        <div class="card">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0"><i class="bi bi-people-fill me-2"></i>Family Members</h5>
                            </div>
                            <div class="card-body">
                                @foreach ($personal->families as $family)
                                    @isset($family->relationShipid->relationship_name)
                                        <div class="border-bottom pb-3 mb-3">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="form-label text-muted small mb-1">Name</label>
                                                    <p class="mb-0">{{ $family->fa_name }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label text-muted small mb-1">Relationship</label>
                                                    <p class="mb-0">
                                                        {{ $family->relationShipid->relationship_name ?? 'No relation' }}
                                                    </p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label text-muted small mb-1">Occupation</label>
                                                    <p class="mb-0">{{ $family->fa_occupation }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endisset
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Education Details Tab -->
                    <div class="tab-pane fade" id="v-pills-education" role="tabpanel">
                        <div class="card">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0"><i class="bi bi-book me-2"></i>Education Details</h5>
                            </div>
                            <div class="card-body">
                                @foreach ($personal->educations as $education)
                                    <div class="border-bottom pb-3 mb-3">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small mb-1">Qualification</label>
                                                <p class="mb-0 fw-bold">
                                                    {{ $education->Qualificationid->qua_name ?? '' }}
                                                </p>
                                                <p class="small text-muted">{{ $education->specialization }}</p>
                                            </div>
                                            <div class="col-md-6 text-end">
                                                <label class="form-label text-dark small mb-1">percentage</label>
                                                <span
                                                    class="badge bg-primary p-2">{{ $education->percentage_grade }}%</span>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small mb-1">Institution</label>
                                                <p class="mb-0">{{ $education->name_of_institution }}</p>
                                                <p class="small text-muted">{{ $education->edu_location }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small mb-1">Duration</label>
                                                <p class="mb-0">
                                                    {{ \Carbon\Carbon::parse($education->edu_from_date)->format('d-m-Y') }}
                                                    to
                                                    {{ \Carbon\Carbon::parse($education->edu_to_date)->format('d-m-Y') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Employment Details Tab -->
                    <div class="tab-pane fade" id="v-pills-employment" role="tabpanel">
                        <!-- Current Employment -->
                        <div class="card mb-4">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0"><i class="bi bi-briefcase-fill me-2"></i>Current
                                    Employment</h5>
                            </div>
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small mb-1">Designation</label>
                                        <p class="mb-0">{{ $personal->Designationid->des_name ?? '' }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small mb-1">Department</label>
                                        <p class="mb-0">{{ $personal->Departmentid->dep_name ?? '' }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small mb-1">Job Probation</label>
                                        <p class="mb-0">
                                            {{ \Carbon\Carbon::parse($personal->dojprovision_from_date)->format('d-m-Y') }}
                                            <span class=" fw-semibold px-3"> To</span>
                                            {{ \Carbon\Carbon::parse($personal->provision_to_date)->format('d-m-Y') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small mb-1">Location</label>
                                        <p class="mb-0">{{ $personal->Branchid->branch_name  ?? ''}}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small mb-1">Annual CTC</label>
                                        <p class="mb-0">{{ $personal->cur_annual_ctc }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex gap-5">
                                            <div>
                                                <label class="form-label text-muted small mb-1">In Month</label>
                                                <p class="mb-0 ">{{ $personal->inmonth }}</p>
                                            </div>
                                            <div>
                                                <label class="form-label text-muted small mb-1">Job Type</label>
                                                <p class="mb-0 fw-bold">{{ $personal->jobTypeid->jobtype_name ?? '' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Past Employment -->
                        <!-- Past Employment -->
                        <div class="card">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0"><i class="bi bi-building me-2"></i>Past Employment</h5>
                            </div>
                            <div class="card-body">
                                @foreach ($personal->pastemps as $past)
                                    @php

                                        // Parse the from and to dates
                                        $from = \Carbon\Carbon::parse($past->past_from_date);
                                        $to = \Carbon\Carbon::parse($past->past_to_date);

                                        // Get total months between dates
                                        $totalMonths = $from->diffInMonths($to);

                                        // Convert months to years and months
                                        $years = floor($totalMonths / 12);
                                        $months = $totalMonths % 12;
                                    @endphp

                                    <div class="border-bottom pb-3 mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div>
                                                <label> Employee Status:</label>
                                                <span
                                                    class="badge bg-{{ $past->employed_as == 1 ? 'primary' : 'secondary' }} ms-1  p-2">
                                                    {{ $past->employed_as == 1 ? 'Experience' : 'New' }}
                                                </span>
                                            </div>
                                            @if ($past->employed_as == 1)
                                                <span class="text-dark small">Previous Experience <p
                                                        class="mb-0 fw-bold">



                                                        {{ $years }} Years {{ $months }} Months


                                                    </p>
                                                </span>
                                            @else
                                                <span class="text-dark small">First Job</span>
                                            @endif
                                        </div>

                                        @if ($past->employed_as == 1)
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label
                                                        class="form-label text-muted small mb-1">Organization</label>
                                                    <p class="mb-0 fw-bold">{{ $past->past_organisation_name }}</p>
                                                    <p class="small text-muted">{{ $past->past_location }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted small mb-1">Role</label>
                                                    <p class="mb-0">{{ $past->past_role }}</p>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted small mb-1">Designation</label>
                                                    <p class="mb-0">{{ $past->past_designation }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted small mb-1">Department</label>
                                                    <p class="mb-0">{{ $past->past_department }}</p>
                                                </div>

                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted small mb-1">Duration</label>
                                                    <p class="mb-0">
                                                        {{ \Carbon\Carbon::parse($past->past_from_date)->format('d-m-Y') }}
                                                        to
                                                        {{ \Carbon\Carbon::parse($past->past_to_date)->format('d-m-Y') }}
                                                    </p>

                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted small mb-1">UAN Status</label>
                                                    <p class="mb-0">
                                                        {{ $past->has_uan == 1 ? 'Yes (' . $past->uan_number . ')' : 'No' }}
                                                    </p>
                                                </div>


                                                <div class=" text-end">
                                                    <label class="form-label text-muted small mb-1">Annual CTC</label>
                                                    <p class="mb-0">{{ $past->past_annual_ctc }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- References Tab -->
                    <div class="tab-pane fade" id="v-pills-references" role="tabpanel">
                        <div class="card">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0"><i class="bi bi-person-check me-2"></i>Professional
                                    References</h5>
                            </div>
                            <div class="card-body">
                                @foreach ($personal->prorefs as $ref)
                                    <div class="border-bottom pb-3 mb-3">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label text-muted small mb-1">Name</label>
                                                <p class="mb-0 fw-bold">{{ $ref->ref_name }}</p>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label text-muted small mb-1">Organization</label>
                                                <p class="mb-0">{{ $ref->ref_organization_name }}</p>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label text-muted small mb-1">Designation</label>
                                                <p class="mb-0">{{ $ref->ref_designation }}</p>
                                            </div>

                                            <div class="col-md-3 ">
                                                <label class="form-label text-muted small mb-1">Email</label>
                                                <p class="mb-0   fw-medium">{{ $ref->email_ref }}</p>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small mb-1">Contact</label>
                                                <p class="mb-0">{{ $ref->mobile_no }}</p>
                                            </div>

                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>


                    <!-- Leave Details Tab -->
                    <!-- Replace the entire Leave Details Tab content with this code -->
                    <div class="tab-pane fade" id="v-pills-leave" role="tabpanel">
                        <div class="card">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0"><i class="bi bi-calendar-x me-2"></i>Leave Information
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <!-- Leave Summary Cards -->
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <h6 class="mb-3 text-muted text-uppercase small fw-bold">Leave Balance Summary
                                        </h6>
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <div class="card bg-primary bg-opacity-10 border-0 h-100">
                                                    <div class="card-body text-center p-3">
                                                        <div class="text-primary mb-2">
                                                            <i class="bi bi-calendar-check fs-4"></i>
                                                        </div>
                                                        <h5 class="card-title mb-1" id="total-leaves">0</h5>
                                                        <p class="card-text small text-muted mb-0">Total Leaves</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <div class="card bg-success bg-opacity-10 border-0 h-100">
                                                    <div class="card-body text-center p-3">
                                                        <div class="text-success mb-2">
                                                            <i class="bi bi-check-circle fs-4"></i>
                                                        </div>
                                                        <h5 class="card-title mb-1" id="available-leaves">0</h5>
                                                        <p class="card-text small text-muted mb-0">Available</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <div class="card bg-danger bg-opacity-10 border-0 h-100">
                                                    <div class="card-body text-center p-3">
                                                        <div class="text-danger mb-2">
                                                            <i class="bi bi-clock-history fs-4"></i>
                                                        </div>
                                                        <h5 class="card-title mb-1" id="used-leaves">0</h5>
                                                        <p class="card-text small text-muted mb-0">Used</p>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <!-- Leave Breakdown -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <h6 class="mb-3 text-muted text-uppercase small fw-bold">Leave Breakdown</h6>
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Leave Type</th>
                                                        <th class="text-center">Total</th>
                                                        <th class="text-center">Available</th>
                                                        <th class="text-center">Used</th>

                                                    </tr>
                                                </thead>
                                                <tbody id="leaveSummary">
                                                    <tr>
                                                        <td colspan="5" class="text-center py-4">
                                                            <div class="spinner-border spinner-border-sm text-primary"
                                                                role="status">
                                                                <span class="visually-hidden">Loading...</span>
                                                            </div>
                                                            <span class="ms-2">Loading leave data...</span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const employeeId = {{ $personal->emp_id }};

                            // Fetch leave data when the Leave tab is clicked
                            document.getElementById('v-pills-leave-tab').addEventListener('click', function() {
                                fetchLeaveData(employeeId);
                            });

                            function fetchLeaveData(employeeId) {
                                // Show loading state
                                document.getElementById('leaveSummary').innerHTML = `
            <tr>
                <td colspan="5" class="text-center py-4">
                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <span class="ms-2">Loading leave data...</span>
                </td>
            </tr>
        `;

                                // Fetch leave summary
                                fetch(`/dashboard/employees/leave/summary/${employeeId}`)
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            renderLeaveSummary(data.summary);
                                            updateSummaryCards(data.summary);
                                        } else {
                                            document.getElementById('leaveSummary').innerHTML = `
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox me-2"></i>No leave data available
                            </td>
                        </tr>
                    `;
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error fetching leave summary:', error);
                                        document.getElementById('leaveSummary').innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center py-4 text-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>Error loading leave data
                        </td>
                    </tr>
                `;
                                    });
                            }

                            function updateSummaryCards(summary) {
                                let totalLeaves = 0;
                                let availableLeaves = 0;
                                let usedLeaves = 0;

                                summary.forEach(item => {
                                    totalLeaves += parseInt(item.total_days) || 0;
                                    availableLeaves += parseInt(item.available_days) || 0;
                                    usedLeaves += parseInt(item.used_days) || 0;
                                });

                                document.getElementById('total-leaves').textContent = totalLeaves;
                                document.getElementById('available-leaves').textContent = availableLeaves;
                                document.getElementById('used-leaves').textContent = usedLeaves;

                            }

                            function renderLeaveSummary(summary) {
                                const summaryContainer = document.getElementById('leaveSummary');
                                summaryContainer.innerHTML = '';

                                if (!summary || summary.length === 0) {
                                    summaryContainer.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">
                        <i class="bi bi-inbox me-2"></i>No leave data available
                    </td>
                </tr>
            `;
                                    return;
                                }

                                summary.forEach(item => {
                                    const total = parseInt(item.total_days) || 0;
                                    const used = parseInt(item.used_days) || 0;
                                    const available = parseInt(item.available_days) || 0;
                                    const usagePercentage = total > 0 ? Math.round((used / total) * 100) : 0;

                                    const row = document.createElement('tr');
                                    row.innerHTML = `
                <td>
                    <span class="fw-semibold">${item.leave_type || 'N/A'}</span>
                </td>
                <td class="text-center">${total}</td>
                <td class="text-center">
                    <span class="badge bg-success bg-opacity-10 text-success border-0 " style="font-size:1em;">${available}</span>
                </td>
                <td class="text-center">
                    <span class="badge bg-danger bg-opacity-20 text-white  border-0 " style="font-size:1em;">${used}</span>
                </td>

            `;
                                    summaryContainer.appendChild(row);
                                });

                                // Initialize tooltips
                                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                                tooltipTriggerList.map(function(tooltipTriggerEl) {
                                    return new bootstrap.Tooltip(tooltipTriggerEl);
                                });
                            }
                        });
                    </script>


                </div>
            </div>
        </div>
    </div>
    <style>
        .loading-placeholder {
            color: #6c757d;
            font-style: italic;
        }

        .error-message {
            color: #dc3545;
            font-weight: 500;
        }

        .no-data {
            color: #6c757d;
            text-align: center;
            padding: 2rem;
            font-style: italic;
        }
    </style>
    <style>
        .nav-pills {
            background-color: #d6d4d446;


        }

        .nav-pills .nav-link {
            border-radius: 0rem;
            margin-bottom: 0.5rem;
            color: #495057;
            font-weight: 500;
            text-align: left;
        }

        .nav-pills .nav-link.active {
            background-color: var(--ra-primary-set);
            color: white;
        }

        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 0.5rem;
        }

        .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1rem 1.25rem;
        }

        .form-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge.bg-pink {
            background-color: #ff69b4;
            color: white;
        }
    </style>

</x-layout>
