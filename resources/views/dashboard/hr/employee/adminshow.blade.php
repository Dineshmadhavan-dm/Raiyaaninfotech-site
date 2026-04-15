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
            <a href="{{ route('adminlist') }}" class="btn btn-outline-primary d-flex align-items-center">
                <i class="bi bi-arrow-left-circle me-2"></i>Back
            </a>
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
                        <h2 class="mb-3">{{ $personal->employee->fullname ?? $personal->name }}</h2>
                        <div class="d-flex flex-wrap gap-3 mb-3 align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-person-badge"></i>
                                <label class="mb-0">Designation:</label>
                                <span class="badge bg-primary p-2">
                                    {{ $personal->employee->Designationid->des_name ?? 'N/A' }}
                                </span>
                            </div>
                            <div class="d-flex align-items-center gap-2 ms-4">
                                <i class="bi bi-diagram-3"></i>
                                <label class="mb-0">Department:</label>
                                <span class="badge bg-secondary p-2">
                                    {{ $personal->employee->Departmentid->dep_name ?? 'N/A' }}
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

                    <button class="nav-link active" id="v-pills-employment-tab" data-bs-toggle="pill"
                        data-bs-target="#v-pills-employment" type="button" role="tab">Employment</button>

                    <button class="nav-link" id="v-pills-roles-tab" data-bs-toggle="pill"
                        data-bs-target="#v-pills-roles" type="button" role="tab">
                        Roles & Permissions
                    </button>
                </div>
            </div>
            <div class="col-md-9">
                <div class="tab-content" id="v-pills-tabContent">



                    <!-- Employment Details Tab -->
                    <div class="tab-pane fade show active" id="v-pills-employment" role="tabpanel">
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
                                        <p class="mb-0">{{ $personal->employee->Designationid->des_name ?? '' }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small mb-1">Department</label>
                                        <p class="mb-0">{{ $personal->employee->Departmentid->dep_name ?? '' }}</p>
                                    </div>

                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small mb-1">Location</label>
                                        <p class="mb-0">{{ $personal->employee->Branchid->branch_name }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small mb-1">Annual CTC</label>
                                        <p class="mb-0"> {{ $personal->employee->cur_annual_ctc }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small mb-1">Date of Joining</label>
                                        <p class="mb-0">
                                            {{ \Carbon\Carbon::parse($personal->employee->dojprovision_from_date)->format('d-m-Y') }}
                                            <span class=" fw-semibold px-3"> To</span>
                                            {{ \Carbon\Carbon::parse($personal->employee->provision_to_date)->format('d-m-Y') }}
                                        </p>
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
                                                    <p class="mb-0">{{ $past->past_from_date }} to
                                                        {{ $past->past_to_date }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted small mb-1">UAN Status</label>
                                                    <p class="mb-0">
                                                        {{ $past->has_uan == 1 ? "Yes [$past->uan_number]" : 'No' }}
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


                    <!-- Roles & Permissions Tab -->

                    <div class="tab-pane fade" id="v-pills-roles" role="tabpanel">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-0 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 p-2 rounded me-3">
                                        <i class="bi bi-shield-lock text-primary fs-5"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0 fw-semibold">
                                            Assigned Roles:
                                            <span class="fw-normal">
                                                @forelse ($personal->roles as $role)
                                                    {{ $role->name }}
                                                    @if (!$loop->last)
                                                        ,
                                                    @endif
                                                @empty
                                                    No role assigned
                                                @endforelse
                                            </span>
                                        </h4>
                                        <small class="text-muted">Permissions for this employee (from roles and direct
                                            assignments)</small>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body p-4">
                                @if (!empty($grouped))
                                    <div class="table-responsive">
                                        <table class="table table-borderless table-hover align-middle">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th width="30%">Module/Permission</th>
                                                    <th class="text-center">View</th>
                                                    <th class="text-center">Create</th>
                                                    <th class="text-center">Edit</th>
                                                    <th class="text-center">Delete</th>
                                                    {{-- <th class="text-center">Other</th> --}}
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $currentParent = null; @endphp
                                                @foreach ($grouped as $resource => $permissionData)
                                                    @php
                                                        $isChild = str_contains($resource, '->');

                                                        if ($isChild) {
                                                            [$parent, $child] = explode('->', $resource);
                                                        } else {
                                                            $parent = $resource;
                                                            $child = null;
                                                        }
                                                    @endphp

                                                    @if (!$isChild)
                                                        <tr class="border-top border-2 border-light">
                                                            <td class="fw-bold text-capitalize">
                                                                <i class="bi bi-folder-fill me-2 text-primary"></i>
                                                                {{ str_replace('_', ' ', $parent) }}
                                                            </td>
                                                            @foreach (['view', 'create', 'edit', 'delete'] as $action)
                                                                <td class="text-center">
                                                                    @php
                                                                        $perm = collect($permissionData)->first(
                                                                            function ($item) use ($action) {
                                                                                return $item['action'] === $action ||
                                                                                    ($action === '*' &&
                                                                                        !in_array($item['action'], [
                                                                                            'view',
                                                                                            'create',
                                                                                            'edit',
                                                                                            'delete',
                                                                                        ]));
                                                                            },
                                                                        );
                                                                    @endphp
                                                                    @if ($perm)
                                                                        <span class="rolecheck"> <i
                                                                                class="bi bi-check-square-fill   text-primary"></i></span>
                                                                    @else
                                                                        <span class="roleuncheck"><i
                                                                                class="bi bi-square"></i></span>
                                                                    @endif
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    @else
                                                        <tr>
                                                            <td class="text-capitalize ps-4">
                                                                <i
                                                                    class="bi bi-arrow-return-right me-2 text-muted"></i>
                                                                {{ str_replace('_', ' ', $child) }}
                                                            </td>
                                                            @foreach (['view', 'create', 'edit', 'delete'] as $action)
                                                                <td class="text-center">
                                                                    @php
                                                                        $perm = collect($permissionData)->first(
                                                                            function ($item) use ($action) {
                                                                                return $item['action'] === $action ||
                                                                                    ($action === '*' &&
                                                                                        !in_array($item['action'], [
                                                                                            'view',
                                                                                            'create',
                                                                                            'edit',
                                                                                            'delete',
                                                                                        ]));
                                                                            },
                                                                        );
                                                                    @endphp
                                                                    @if ($perm)
                                                                        <span class="rolecheck"> <i
                                                                                class="bi bi-check-square-fill   text-primary"></i></span>
                                                                    @else
                                                                        <span class="roleuncheck"><i
                                                                                class="bi bi-square"></i></span>
                                                                    @endif
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <div class="empty-state-icon bg-soft-primary rounded-circle mb-4">
                                            <i class="bi bi-shield-slash fs-1 text-primary"></i>
                                        </div>
                                        <h4>No Access Permissions Assigned</h4>
                                        <p class="text-muted">This employee currently has no permissions assigned.</p>
                                        <a href="{{ route('roles.index') }}" class="btn btn-outline-primary me-2">
                                            <i class="bi bi-person-gear me-1"></i> Assign Role
                                        </a>
                                        <a href="{{ route('permissions.index') }}" class="btn btn-primary">
                                            <i class="bi bi-shield-plus me-1"></i> Manage Permissions
                                        </a>
                                    </div>
                                @endif

                                @if ($personal->permissions->isNotEmpty())
                                    <div class="mt-4">
                                        <h5 class="mb-3">
                                            <i class="bi bi-star-fill text-warning me-2"></i>
                                            Directly Assigned Permissions
                                        </h5>
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach ($personal->permissions as $permission)
                                                <span class="badge bg-info text-dark">
                                                    {{ $permission->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                        <p class="text-muted small mt-2">
                                            These permissions are assigned directly to the user, not through a role.
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

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
