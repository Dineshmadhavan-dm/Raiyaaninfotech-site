<x-layout>
    @section('title', 'Employee View')
    <div class="container-fluid py-4 px-4">
        <!-- Header & Breadcrumb -->


        <!-- Employee Profile Header -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center">
                        {{-- <img src="{{ $personal->image ? asset('employee_images/' . $personal->image) : asset('images/admin_default.jpg') }}"
                            alt="Profile Image" class="img-fluid rounded-circle" width="120px">

 --}}



                        @php
                            $user = Auth::user();
                        @endphp

                        @if ($user->categorie == 2)
                            {{-- Employee --}}
                            @php
                                $employee = $user->employee;
                            @endphp
                            <img src="{{ $employee && $employee->image
                                ? asset('employee_images/' . $employee->image)
                                : asset('images/admin_default.jpg') }}"
                                alt="{{ $employee ? $employee->fullname : $user->name }}"
                                class="img-fluid rounded-circle me-2" width="120">
                        @elseif ($user->categorie == 3)
                            @php
                                $employee = $user->employee;
                            @endphp
                            {{-- Admin --}}
                            <img src="{{ $employee && $employee->image
                                ? asset('employee_images/' . $employee->image)
                                : asset('images/admin_default.jpg') }}"
                                alt="{{ $employee ? $employee->fullname : $user->name }}"
                                class="img-fluid rounded-circle me-2" width="120">
                        @elseif ($user->categorie == 1)
                            {{-- Super Admin --}}
                            <img src="{{ $user->image ? asset('admin_images/' . $user->image) : asset('images/admin_default.jpg') }}"
                                alt="{{ $user->name }}" class="img-fluid rounded-circle me-2" width="120">
                        @endif






                    </div>
                    <div class="col-md-10">
                        <h2 class="mb-3">{{ $personal->employee->fullname ?? $personal->name }}</h2>
                        <div class="d-flex flex-wrap gap-3 mb-3 align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-person-badge"></i>
                                <label class="mb-0">Designation:</label>
                                <span class="badge bg-primary p-2">
                                    {{ $personal->employee->Designationid->des_name }}
                                </span>
                            </div>
                            <div class="d-flex align-items-center gap-2 ms-4">
                                <i class="bi bi-diagram-3"></i>
                                <label class="mb-0">Department:</label>
                                <span class="badge bg-secondary p-2">
                                    {{ $personal->employee->Departmentid->dep_name }}
                                </span>
                            </div>
                            <div class="d-flex align-items-center gap-2 ms-4">
                                <i class="bi bi-person-gear"></i>
                                <label class="mb-0">Role:</label>
                                <span class=" p-2">
                                    @forelse ($personal->roles as $role)
                                        @php
                                            $roleName = $role->name;
                                        @endphp
                                        <span @class([
                                            ' px-3 py-2',
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
                                        @if (!$loop->last)
                                            <span>|</span>
                                        @endif
                                    @empty
                                        No role assigned
                                    @endforelse
                                </span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <p class="mb-1"><i class="bi bi-envelope me-2"></i>
                                    {{ $personal->employee->email_company }}</p>
                            </div>
                            <div class="col-md-3">
                                <p class="mb-1"><i class="bi bi-phone me-2"></i>
                                    {{ $personal->employee->personal_mobile ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-3">
                                <p class="mb-1"><i class="bi bi-calendar me-2"></i> DOB:
                                    {{ \Carbon\Carbon::parse($personal->employee->dob)->format('d-m-Y') }}</p>
                            </div>
                            <div class="col-md-3">
                                <p class="mb-1">
                                    <i
                                        class="bi bi-gender-{{ $personal->employee->gender == 0 ? 'male' : 'female' }} me-2"></i>
                                    Gender:
                                    <span
                                        class="badge bg-{{ $personal->employee->gender == 0 ? 'info' : 'pink' }} p-2">
                                        {{ $personal->employee->gender == 0 ? 'Male' : 'Female' }}
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
                                @if ($personal->employee)
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted small mb-1">Father's Name</label>
                                                <p class="mb-0">{{ $personal->employee->fathername ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted small mb-1">Marital Status</label>
                                                <p class="mb-0">
                                                    <span
                                                        class="badge bg-{{ $personal->employee->marital_status == 1 ? 'success' : 'info' }} text-capitalize p-2">
                                                        {{ $personal->employee->marital_status == 1 ? 'Married' : 'Single' }}

                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted small mb-1">PAN Card</label>
                                                <p class="mb-0">{{ $personal->employee->pancard_no }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted small mb-1">Aadhaar Number</label>
                                                <p class="mb-0">{{ $personal->employee->aadhaar_no }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted small mb-1">Address</label>
                                                <p class="mb-0">{{ $personal->employee->address }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted small mb-1">Blood Group</label>
                                                <p class="mb-0">
                                                    {{ $personal->employee->bloodGroupid->bloodgroup_name ?? '' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-info">No employee details found</div>
                                @endif
                            </div>
                        </div>

                        <!-- Emergency Contact Card -->
                        @if ($personal->employee)
                            <div class="card mt-4">
                                <div class="card-header bg-white">
                                    <h5 class="card-title mb-0"><i class="bi bi-telephone-plus me-2"></i>Emergency
                                        Contact
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label text-muted small mb-1">Contact Person</label>
                                                <p class="mb-0">
                                                    {{ $personal->employee->c_person_emergency ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label text-muted small mb-1">Relation</label>
                                                <p class="mb-0">
                                                    {{ $personal->employee->relationShipid->relationship_name ?? '' }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label text-muted small mb-1">Contact Number</label>
                                                <p class="mb-0">
                                                    {{ $personal->employee->emergency_contact ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Family Details Tab -->
                    <div class="tab-pane fade" id="v-pills-family" role="tabpanel">
                        <div class="card">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0"><i class="bi bi-people-fill me-2"></i>Family Members</h5>
                            </div>
                            <div class="card-body">
                                @foreach ($personal->employee->families as $family)
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
                                @foreach ($personal->employee->educations as $education)
                                    <div class="border-bottom pb-3 mb-3">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small mb-1">Qualification</label>
                                                <p class="mb-0 fw-bold">
                                                    {{ $education->Qualificationid->qua_name ?? '' }}</p>
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
                                        <p class="mb-0">{{ $personal->employee->Designationid->des_name }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small mb-1">Department</label>
                                        <p class="mb-0">{{ $personal->employee->Departmentid->dep_name }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small mb-1">Job Probation</label>
                                        <p class="mb-0">
                                            {{ \Carbon\Carbon::parse($personal->employee->dojprovision_from_date)->format('d-m-Y') }}
                                            <span class=" fw-semibold px-3"> To</span>
                                            {{ \Carbon\Carbon::parse($personal->employee->provision_to_date)->format('d-m-Y') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small mb-1">Location</label>
                                        <p class="mb-0">{{ $personal->employee->Branchid->branch_name }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small mb-1">Annual CTC</label>
                                        <p class="mb-0">{{ $personal->employee->cur_annual_ctc }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex gap-5">
                                            <div>
                                                <label class="form-label text-muted small mb-1">In Month</label>
                                                <p class="mb-0 ">{{ $personal->employee->inmonth }}</p>
                                            </div>
                                            <div>
                                                <label class="form-label text-muted small mb-1">Job Type</label>
                                                <p class="mb-0 fw-bold">
                                                    {{ $personal->employee->jobTypeid->jobtype_name }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>


                        <!-- Past Employment -->
                        <div class="card">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0"><i class="bi bi-building me-2"></i>Past Employment</h5>
                            </div>
                            <div class="card-body">
                                @foreach ($personal->employee->pastemps as $past)
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
                                            @if ($past->employed_as == 'Experienced')
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
                                                    <p class="mb-0">{{ $past->past_from_date }} to
                                                        {{ $past->past_to_date }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted small mb-1">UAN Status</label>
                                                    <p class="mb-0">
                                                        {{ $past->has_uan == 'Yes' ? 'Yes (' . $past->uan_number . ')' : 'No' }}
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
                                @foreach ($personal->employee->prorefs as $ref)
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

                    <!-- Roles & Permissions Tab -->



                </div>
            </div>
        </div>
    </div>

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
