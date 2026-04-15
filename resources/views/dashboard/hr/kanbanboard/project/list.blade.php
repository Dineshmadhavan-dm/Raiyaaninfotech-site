<x-kanbandashboardlayout>
    <div class="mt-5">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 mt-2">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dhome') }}" class="text-decoration-none text-muted">
                                    <i class="bi bi-house me-2"></i>Home
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <i class="bi bi-diagram-3 me-2"></i>Project
                            </li>
                        </ol>
                    </nav>
                </div>
                <div>

                <a href="{{ route('dhome') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-left me-2"></i>Main Dashboard
                </a>

                    <a href="{{ route('projectcreate') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>Add Project
                    </a>
                </div>
            </div>

            <x-message />

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-3">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-auto mt-3 mt-md-0">
                            <div class="row">
                                <div class="col-auto mt-3 mt-md-0">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="globalSearch"
                                            placeholder="Search projects...">
                                        <span class="input-group-text bg-dark-subtle">
                                            <i class="bi bi-search text-muted"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="d-flex align-items-center">
                                <button class="btn btn-sm btn-secondary me-3" type="button" id="filterToggle">
                                    <i class="bi bi-funnel me-2"></i>Filters
                                </button>
                                <div class="d-flex flex-wrap gap-2 filter-container-hidden" id="filterContainer">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                            id="statusDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span id="statusFilterText">All Status</span>
                                        </button>
                                        <ul class="dropdown-menu p-2" style="min-width: 200px;"
                                            aria-labelledby="statusDropdown">
                                            <li>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="statusFilter"
                                                        id="statusAll" value="" checked>
                                                    <label class="form-check-label" for="statusAll">All Status</label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="statusFilter"
                                                        id="statusCreated" value="0">
                                                    <label class="form-check-label" for="statusCreated">Created</label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="statusFilter"
                                                        id="statusProgress" value="1">
                                                    <label class="form-check-label" for="statusProgress">On Progress</label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="statusFilter"
                                                        id="statusCompleted" value="2">
                                                    <label class="form-check-label" for="statusCompleted">Completed</label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                            id="projectDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span id="projectFilterText">All Projects</span>
                                        </button>
                                        <div class="dropdown-menu p-2" style="min-width: 250px;">
                                            <input type="text" class="form-control form-control-sm mb-2"
                                                placeholder="Search projects..." id="projectSearch">
                                            <div class="dropdown-scrollable"
                                                style="max-height: 200px; overflow-y: auto;">
                                                <div class="form-check mb-1">
                                                    <input class="form-check-input" type="radio" name="projectFilter"
                                                        id="projectAll" value="" checked>
                                                    <label class="form-check-label" for="projectAll">All Projects</label>
                                                </div>
                                                @foreach ($allProjects as $id => $name)
                                                    <div class="form-check mb-1">
                                                        <input class="form-check-input" type="radio" name="projectFilter"
                                                            id="project{{ $id }}" value="{{ $id }}">
                                                        <label class="form-check-label"
                                                            for="project{{ $id }}">{{ $name }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                            id="clientDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span id="clientFilterText">All Clients</span>
                                        </button>
                                        <div class="dropdown-menu p-2" style="min-width: 250px;">
                                            <input type="text" class="form-control form-control-sm mb-2"
                                                placeholder="Search clients..." id="clientSearch">
                                            <div class="dropdown-scrollable"
                                                style="max-height: 200px; overflow-y: auto;">
                                                <div class="form-check mb-1">
                                                    <input class="form-check-input" type="radio" name="clientFilter"
                                                        id="clientAll" value="" checked>
                                                    <label class="form-check-label" for="clientAll">All Clients</label>
                                                </div>
                                                @foreach ($clients as $client)
                                                    <div class="form-check mb-1 client-option"
                                                        data-client-id="{{ $client->cl_id }}">
                                                        <input class="form-check-input" type="radio" name="clientFilter"
                                                            id="client{{ $client->cl_id }}" value="{{ $client->cl_id }}">
                                                        <label class="form-check-label"
                                                            for="client{{ $client->cl_id }}">{{ $client->cl_name }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                            id="headDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span id="headFilterText">All Heads</span>
                                        </button>
                                        <div class="dropdown-menu p-2" style="min-width: 250px;">
                                            <input type="text" class="form-control form-control-sm mb-2"
                                                placeholder="Search heads..." id="headSearch">
                                            <div class="dropdown-scrollable"
                                                style="max-height: 200px; overflow-y: auto;">
                                                <div class="form-check mb-1">
                                                    <input class="form-check-input" type="radio" name="headFilter"
                                                        id="headAll" value="" checked>
                                                    <label class="form-check-label" for="headAll">All Heads</label>
                                                </div>
                                                @foreach ($projectHeads as $head)
                                                    <div class="form-check mb-1 head-option"
                                                        data-head-id="{{ $head->emp_id }}">
                                                        <input class="form-check-input" type="radio" name="headFilter"
                                                            id="head{{ $head->emp_id }}" value="{{ $head->emp_id }}">
                                                        <label class="form-check-label"
                                                            for="head{{ $head->emp_id }}">{{ $head->fullname }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                            id="accessDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span id="accessFilterText">All Access</span>
                                        </button>
                                        <ul class="dropdown-menu p-2" style="min-width: 200px;"
                                            aria-labelledby="accessDropdown">
                                            <li>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="accessFilter"
                                                        id="accessAll" value="" checked>
                                                    <label class="form-check-label" for="accessAll">All Access</label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="accessFilter"
                                                        id="accessPublic" value="public">
                                                    <label class="form-check-label" for="accessPublic">Public</label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="accessFilter"
                                                        id="accessPrivate" value="private">
                                                    <label class="form-check-label" for="accessPrivate">Private</label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                            id="dateDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span id="dateFilterText">All Dates</span>
                                        </button>
                                        <div class="dropdown-menu p-3" style="min-width: 280px;">
                                            <div class="mb-3">
                                                <label class="form-label small text-muted mb-1">Assigned Date</label>
                                                <input type="date" id="createdDateFilter"
                                                    class="form-control form-control-sm">
                                            </div>
                                            <div>
                                                <label class="form-label small text-muted mb-1">Due Date</label>
                                                <input type="date" id="deadlineDateFilter"
                                                    class="form-control form-control-sm">
                                            </div>
                                        </div>
                                    </div>
                                    <button class="btn btn-sm btn-outline-dark" id="clearFiltersBtn"
                                        style="display: none;">
                                        <i class="bi bi-x-circle me-1"></i> Clear
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="project-table-view">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                            <div class="d-flex align-items-center">
                                <span class="me-2">Show</span>
                                <select class="form-select form-select-sm w-auto" id="entriesPerPage">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                                <span class="ms-2">entries</span>
                            </div>
                            <div class="text-muted">
                                Showing <span id="showingStart">1</span> to <span id="showingEnd">5</span> of <span id="totalEntries">{{ count($projects) }}</span> entries
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table mb-0" id="projectTable">
                                <thead class="table-light">
                                    <tr>
                                        <th width="20%">Project Name</th>
                                        <th width="12%">Access Type</th>
                                        <th width="12%">Team Members</th>
                                        <th width="10%">Client</th>
                                        <th width="15%">Leadership</th>
                                        <th width="10%">Assigned Date</th>
                                        <th width="10%">Due Date</th>
                                        <th width="10%">Completed Date</th>
                                        <th width="7%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="projectTableBody">
                                    @forelse($projects as $project)
                                        @php
                                            $today = \Carbon\Carbon::now();
                                            $deadline = $project->pro_deadline ? \Carbon\Carbon::parse($project->pro_deadline) : null;
                                            $overdueDays = 0;
                                            $isOverdue = false;

                                            if ($deadline) {
                                                if ($project->pro_status === 2 && $project->pro_complete) {
                                                    $completionDate = $project->pro_complete ? \Carbon\Carbon::parse($project->pro_complete) : $today;
                                                    if ($completionDate->gt($deadline)) {
                                                        $overdueDays = $deadline->diffInDays($completionDate);
                                                        $isOverdue = true;
                                                    }
                                                } else {
                                                    if ($today->gt($deadline)) {
                                                        $overdueDays = $deadline->diffInDays($today);
                                                        $isOverdue = true;
                                                    }
                                                }
                                            }

                                            $projectAvatar = null;
                                            if ($project->pro_avater) {
                                                $projectAvatar = asset($project->pro_avater);
                                            } else {
                                                $pmtsImages = $project->pmtsImages;
                                                foreach ($pmtsImages as $pmtsImage) {
                                                    $extension = pathinfo($pmtsImage->pmtsimage_name, PATHINFO_EXTENSION);
                                                    if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                                                        $projectAvatar = asset('project_attachments/' . $pmtsImage->pmtsimage_name);
                                                        break;
                                                    }
                                                }
                                            }

                                            $projectFiles = [];
                                            $pmtsImages = $project->pmtsImages;
                                            if ($pmtsImages->count() > 0) {
                                                foreach ($pmtsImages as $pmtsImage) {
                                                    $projectFiles[] = [
                                                        'url' => asset('project_attachments/' . $pmtsImage->pmtsimage_name),
                                                        'name' => $pmtsImage->pmtsimage_name,
                                                        'type' => pathinfo($pmtsImage->pmtsimage_name, PATHINFO_EXTENSION),
                                                    ];
                                                }
                                            }

                                            $memberIds = [];
                                            $memberNames = [];
                                            try {
                                                if ($project->pro_member) {
                                                    $rawData = $project->pro_member;
                                                    if (is_string($rawData)) {
                                                        $cleanedData = trim($rawData);
                                                        if (!empty($cleanedData)) {
                                                            $cleanedData = trim($cleanedData, ' "');
                                                            $cleanedData = trim($cleanedData, '[]');
                                                            if (str_contains($cleanedData, '","')) {
                                                                $cleanedData = str_replace('\\"', '"', $cleanedData);
                                                                $cleanedData = trim($cleanedData, '[]');
                                                            }
                                                            $tempMembers = explode(',', $cleanedData);
                                                            foreach ($tempMembers as $member) {
                                                                $cleanMember = trim($member, ' "\\[]');
                                                                if (str_contains($cleanMember, ',')) {
                                                                    $nestedMembers = explode(',', $cleanMember);
                                                                    foreach ($nestedMembers as $nestedMember) {
                                                                        $cleanNested = trim($nestedMember, ' "\\[]');
                                                                        if (is_numeric($cleanNested) && $cleanNested > 0) {
                                                                            $memberIds[] = (int) $cleanNested;
                                                                        }
                                                                    }
                                                                } elseif (is_numeric($cleanMember) && $cleanMember > 0) {
                                                                    $memberIds[] = (int) $cleanMember;
                                                                }
                                                            }
                                                            $memberIds = array_unique($memberIds);
                                                        }
                                                    } elseif (is_array($rawData)) {
                                                        $memberIds = array_filter(array_map('intval', $rawData), function ($id) {
                                                            return $id > 0;
                                                        });
                                                    }
                                                }
                                            } catch (\Exception $e) {
                                                $memberIds = [];
                                            }

                                            if (!empty($memberIds)) {
                                                try {
                                                    $members = \App\Models\Employee::whereIn('emp_id', $memberIds)->get(['emp_id', 'fullname']);
                                                    $memberNames = $members->pluck('fullname')->toArray();
                                                } catch (\Exception $e) {
                                                    $memberNames = [];
                                                }
                                            }

                                            $overdueText = '';
                                            if ($overdueDays > 0) {
                                                if ($overdueDays < 30) {
                                                    $overdueText = $overdueDays . ' day' . ($overdueDays > 1 ? 's' : '');
                                                } else {
                                                    $months = floor($overdueDays / 30);
                                                    $remainingDays = $overdueDays % 30;
                                                    if ($remainingDays === 0) {
                                                        $overdueText = $months . ' month' . ($months > 1 ? 's' : '');
                                                    } else {
                                                        $overdueText = $months . ' month' . ($months > 1 ? 's' : '') . ' ' . $remainingDays . ' day' . ($remainingDays > 1 ? 's' : '');
                                                    }
                                                }
                                            }
                                        @endphp

                                        <tr class="project-row" data-project-id="{{ $project->pro_id }}"
                                            data-status="{{ $project->pro_status }}"
                                            data-created-date="{{ \Carbon\Carbon::parse($project->created_at)->format('Y-m-d') }}"
                                            data-deadline-date="{{ $deadline ? $deadline->format('Y-m-d') : '' }}"
                                            data-client="{{ $project->pro_client }}" data-head="{{ $project->pro_head }}"
                                            data-lead="{{ $project->pro_lead }}"
                                            data-access="{{ $project->pro_accessmod }}">
                                            <td>



                                           <a href="{{ route('modulolist') }}" style="text-decoration: none; color: inherit;">



                                                <div class="d-flex align-items-center">
                                                    @if($projectAvatar)
                                                        <img src="{{ $projectAvatar }}" class="rounded-circle me-3" width="45"
                                                            height="45" alt="{{ $project->pro_name }}">
                                                    @else
                                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                                            style="width: 45px; height: 45px;">
                                                            {{ substr($project->pro_name, 0, 1) }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-medium " style="font-size: 14px">
                                                            {{ $project->pro_name }}
                                                        </div>
                                                    </div>
                                                </div>
                                                </a>
                                            </td>
                                            <td>
                                                @if($project->pro_accessmod == 0)
                                                    <span
                                                        class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">
                                                        <i class="bi bi-globe me-1"></i>Public
                                                    </span>
                                                @else
                                                    <span
                                                        class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">
                                                        <i class="bi bi-lock me-1"></i>Private
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(!empty($memberIds))
                                                    <div class="team-members-overlap">
                                                        @php
                                                            $displayMembers = array_slice($memberIds, 0, 4);
                                                            $remainingCount = count($memberIds) - count($displayMembers);
                                                        @endphp
                                                        @foreach($displayMembers as $index => $memberId)
                                                            @php
                                                                $member = \App\Models\Employee::find($memberId);
                                                                $zIndex = 10 - $index;
                                                                $marginLeft = $index > 0 ? '-10px' : '0';
                                                            @endphp
                                                            @if($member)
                                                                <div class="team-member-overlap"
                                                                    style="z-index: {{ $zIndex }}; margin-left: {{ $marginLeft }};"
                                                                    data-bs-toggle="tooltip" title="{{ $member->fullname }}">
                                                                    @if($member->image)
                                                                        <img src="{{ asset('employee_images/' . $member->image) }}"
                                                                            alt="{{ $member->fullname }}"
                                                                            class="rounded-circle object-fit-cover" width="30" height="30">
                                                                    @else
                                                                        <div class="rounded-circle bg-secondary-member text-white d-flex align-items-center justify-content-center"
                                                                            style="width: 30px; height: 30px; font-size: 0.8rem; font-weight: 600;">
                                                                            {{ substr($member->fullname, 0, 1) }}
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                        @if($remainingCount > 0)
                                                            <div class="team-member-more-overlap"
                                                                style="z-index: 1; margin-left: -10px;" data-bs-toggle="tooltip"
                                                                title="{{ $remainingCount }} more members">
                                                                <div class="rounded-circle d-flex align-items-center justify-content-center"
                                                                    style="width: 30px; height: 30px; font-size: 0.8rem; font-weight: 600; background-color: #e9ecef;color: #6c757d;">
                                                                    +{{ $remainingCount }}
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-muted small">No members</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($project->client)
                                                    <div class="d-flex align-items-center">
                                                        @if($project->client->cl_image)
                                                            <img src="{{ asset('client_images/' . $project->client->cl_image) }}"
                                                                class="rounded-circle me-2" width="35" height="35"
                                                                alt="{{ $project->client->cl_name }}">
                                                        @else
                                                            <div class="rounded-circle bg-secondary-client text-white d-flex align-items-center justify-content-center me-2"
                                                                style="width: 30px; height: 30px; font-size: 0.8rem; font-weight: 600;">
                                                                {{ substr($project->client->cl_name, 0, 1) }}
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <div class="fw-semibold small">{{ $project->client->cl_name }}</div>
                                                            <div class="text-muted xsmall">{{ $project->client->cl_email }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <div class="mb-1">
                                                        <small class="  text-dark-emphasis d-block xsmall my-1">Project
                                                            Manager</small>
                                                        <div class="d-flex align-items-center">
                                                            @if($project->projectHead && $project->projectHead->image)
                                                                <img src="{{ asset('employee_images/' . $project->projectHead->image) }}"
                                                                    class="rounded-circle me-2" width="25" height="25"
                                                                    alt="{{ $project->projectHead->fullname }}">
                                                            @else
                                                                <div class="rounded-circle bg-secondary-lead text-white d-flex align-items-center justify-content-center me-2"
                                                                    style="width: 25px; height: 25px; font-size: 0.7rem; font-weight: 600;">
                                                                    {{ $project->projectHead ? substr($project->projectHead->fullname, 0, 1) : 'N' }}
                                                                </div>
                                                            @endif
                                                            <span
                                                                class="small">{{ $project->projectHead ? $project->projectHead->fullname : 'N/A' }}</span>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <small class="  text-dark-emphasis d-block xsmall my-1  ">Project
                                                            Lead</small>
                                                        <div class="d-flex align-items-center">
                                                            @if($project->projectLead && $project->projectLead->image)
                                                                <img src="{{ asset('employee_images/' . $project->projectLead->image) }}"
                                                                    class="rounded-circle me-2" width="25" height="25"
                                                                    alt="{{ $project->projectLead->fullname }}">
                                                            @else
                                                                <div class="rounded-circle bg-secondary-lead text-white d-flex align-items-center justify-content-center me-2"
                                                                    style="width: 25px; height: 25px; font-size: 0.7rem; font-weight: 600;">
                                                                    {{ $project->projectLead ? substr($project->projectLead->fullname, 0, 1) : 'N' }}
                                                                </div>
                                                            @endif
                                                            <span
                                                                class="small">{{ $project->projectLead ? $project->projectLead->fullname : 'N/A' }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <small>{{ \Carbon\Carbon::parse($project->created_at)->format('d-m-Y') }}</small>
                                            </td>
                                            <td>
                                                <small class="text-dark">
                                                    {{ $deadline ? $deadline->format('d-m-Y') : 'No deadline' }}
                                                </small>
                                            </td>
                                            <td>
                                                @if($project->pro_complete)
                                                    <div class="d-flex flex-column">
                                                        <small class="text-dark">
                                                            {{ \Carbon\Carbon::parse($project->pro_complete)->format('d-m-Y') }}
                                                        </small>
                                                        @if($isOverdue)
                                                            <span class="badge bg-danger mt-1" style="width: 70px">Overdue</span>
                                                        @else
                                                            <span class="badge bg-success mt-1" style="width: 70px">On-time</span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary" type="button"
                                                        data-bs-toggle="dropdown">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        @if(!empty($projectFiles))
                                                            <li class="border-1 border-bottom">
                                                                <button class="dropdown-item view-attachments"
                                                                    data-project-name="{{ $project->pro_name }}"
                                                                    data-project-attachments='{{ json_encode($projectFiles) }}'>
                                                                    <i class="bi bi-paperclip me-2"></i>View Attachments
                                                                </button>
                                                            </li>
                                                        @endif
                                                        <li class="border-1 border-bottom">
                                                            <button class="dropdown-item view-project"
                                                                data-project-id="{{ $project->pro_id }}"
                                                                data-project-name="{{ $project->pro_name }}"
                                                                data-project-description="{{ $project->pro_desc }}"
                                                                data-project-avatar="{{ $projectAvatar ? $projectAvatar : '' }}"
                                                                data-project-attachments="{{ json_encode($projectFiles) }}"
                                                                data-project-deadline="{{ $project->pro_deadline }}"
                                                                data-project-created="{{ $project->created_at }}"
                                                                data-project-status="{{ $project->pro_status }}"
                                                                data-project-status-text="{{ $project->status_text }}"
                                                                data-project-status-badge="{{ $project->status_badge_class }}"
                                                                data-project-onprogress="{{ $project->pro_onprogress }}"
                                                                data-project-complete="{{ $project->pro_complete }}"
                                                                data-project-overdue="{{ $overdueDays }}"
                                                                data-project-overdue-text="{{ $overdueText }}"
                                                                data-project-head="{{ $project->projectHead ? $project->projectHead->fullname : 'N/A' }}"
                                                                data-project-head-image="{{ $project->projectHead && $project->projectHead->image ? asset('employee_images/' . $project->projectHead->image) : '' }}"
                                                                data-project-lead="{{ $project->projectLead ? $project->projectLead->fullname : 'N/A' }}"
                                                                data-project-lead-image="{{ $project->projectLead && $project->projectLead->image ? asset('employee_images/' . $project->projectLead->image) : '' }}"
                                                                data-project-client="{{ $project->client ? $project->client->cl_name : 'N/A' }}"
                                                                data-project-client-email="{{ $project->client ? $project->client->cl_email : 'N/A' }}"
                                                                data-project-client-image="{{ $project->client && $project->client->cl_image ? asset('client_images/' . $project->client->cl_image) : asset('images/admin_default.jpg') }}"
                                                                data-project-members="{{ json_encode($memberNames) }}"
                                                                data-project-access="{{ $project->pro_accessmod }}"
                                                                data-bs-toggle="modal" data-bs-target="#projectModal">
                                                                <i class="bi bi-eye me-2"></i>View
                                                            </button>
                                                        </li>
                                                        <li class="border-1 border-bottom">
                                                            <a href="{{ route('project.edit', $project->pro_id) }}"
                                                                class="dropdown-item">
                                                                <i class="bi bi-pencil me-2"></i>Edit
                                                            </a>
                                                        </li>
                                                        <li class="border-1 border-bottom">
                                                            <button class="dropdown-item text-danger delete-project"
                                                                data-project-id="{{ $project->pro_id }}"
                                                                data-project-name="{{ $project->pro_name }}">
                                                                <i class="bi bi-trash me-2"></i>Delete
                                                            </button>
                                                        </li>
                                                        <li>
                                                            <a href="{{ route('modulocreate', $project->pro_id) }}"
                                                                class="dropdown-item">
                                                                <i class="bi bi-plus-lg me-2"></i>Add Module
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-5">
                                                <i class="bi bi-inbox display-4 text-muted"></i>
                                                <h5 class="mt-3 text-muted">No projects found</h5>
                                                <p class="text-muted">Get started by creating your first project.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-3 border-top">
                            <div class="text-muted" id="paginationInfo">
                                Showing 1 to 5 of {{ count($projects) }} entries
                            </div>
                            <nav>
                                <ul class="pagination pagination-sm mb-0" id="pagination">
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="projectModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header p-0 position-relative">
                        <div class="project-modal-header w-100" id="projectModalHeader"></div>
                    </div>
                    <div class="modal-body p-0" id="projectModalBody"></div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="attachmentsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-paperclip me-2"></i>
                            <h5 class="modal-title mb-0">Project Attachments</h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="project-info-section p-3 border-bottom">
                            <div class="d-flex align-items-center">
                                <div class="project-avatar me-3">
                                    <div class="project-avatar-placeholder">
                                        <i class="bi bi-folder"></i>
                                    </div>
                                </div>
                                <div class="project-details">
                                    <h6 class="mb-1" id="attachmentsProjectName">Project Name</h6>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-light text-dark me-2">
                                            <i class="bi bi-files me-1"></i>
                                            <span id="attachmentsCount">0</span> files
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="filter-section p-3 border-bottom">
                            <div class="row align-items-center">
                                <div class="col-md-6"></div>
                                <div class="col-md-6 text-md-end mt-2 mt-md-0">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <input type="radio" class="btn-check" name="attachmentFilter" id="filterAll"
                                            checked>
                                        <label class="btn btn-outline-secondary" for="filterAll">All</label>
                                        <input type="radio" class="btn-check" name="attachmentFilter" id="filterImages">
                                        <label class="btn btn-outline-secondary" for="filterImages">
                                            <i class="bi bi-image me-1"></i>Images
                                        </label>
                                        <input type="radio" class="btn-check" name="attachmentFilter"
                                            id="filterDocuments">
                                        <label class="btn btn-outline-secondary" for="filterDocuments">
                                            <i class="bi bi-file-earmark me-1"></i>Documents
                                        </label>
                                    </div>
                                    <button class="btn btn-sm btn-outline-primary" id="downloadAllAttachments">
                                        <i class="bi bi-download me-1"></i>Download All
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="attachments-container p-3">
                            <div class="row g-3" id="attachmentsGrid"></div>
                            <div class="empty-state text-center py-5 d-none" id="emptyAttachments">
                                <i class="bi bi-inbox display-4 text-muted mb-3"></i>
                                <h5 class="text-muted">No attachments found</h5>
                                <p class="text-muted">Try adjusting your search or filter</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>Delete Project
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete the project "<strong id="deleteProjectName"></strong>"?</p>
                        <p class="text-muted small">This action cannot be undone and all associated data will be
                            removed.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmDelete">
                            <i class="bi bi-trash me-1"></i>Delete Project
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-kanbandashboardlayout>

<x-actionbtn />

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<style>
    .avatar-title {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 600;
        width: 32px;
        height: 32px;
    }

    .team-members-overlap {
        display: flex;
        align-items: center;
    }

    .team-member-overlap {
        position: relative;
        transition: transform 0.2s ease, z-index 0.2s ease;
        border: 2px solid white;
        border-radius: 50%;
    }

    .team-member-overlap:hover {
        transform: translateY(-2px);
        z-index: 20 !important;
    }

    .team-member-overlap img {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        object-fit: cover;
        display: block;
    }

    .team-member-more-overlap {
        position: relative;
        border: 2px solid white;
        border-radius: 50%;
    }

    .team-member-more-overlap div {
        width: 30px;
        height: 30px;
        font-size: 0.7rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .bg-secondary-member,
    .bg-secondary-lead,
    .bg-secondary-client {
        background-color: #6291b8;
    }

    .xsmall {
        font-size: 0.7rem;
    }

    .dropdown-menu {
        border-radius: 0.5rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        border: none;
        padding: 0.5rem;
    }

    .file-icon-preview.file-image {
        color: #28a745 !important;
    }

    .file-icon-preview.file-pdf {
        color: #dc3545 !important;
    }

    .file-icon-preview.file-doc {
        color: #007bff !important;
    }

    .file-icon-preview.file-xls {
        color: #198754 !important;
    }

    .file-icon-preview.file-ppt {
        color: #fd7e14 !important;
    }

    .file-icon-preview.file-zip {
        color: #6f42c1 !important;
    }

    .file-icon-preview.file-default {
        color: #6c757d !important;
    }

    .attachments-preview {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .attachment-item-preview {
        width: 40px;
        height: 40px;
        border-radius: 6px;
        overflow: hidden;
        border: 1px solid #e9ecef;
        transition: transform 0.2s ease;
    }

    .attachment-item-preview:hover {
        transform: scale(1.1);
    }

    .attachment-thumbnail {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .file-icon-preview {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        color: #6c757d;
    }

    .attachment-more-preview {
        width: 40px;
        height: 40px;
        border-radius: 6px;
        background: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: 600;
        color: #6c757d;
        border: 1px dashed #dee2e6;
    }

    #attachmentsModal .modal-content {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }

    #attachmentsModal .modal-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        padding: 1rem 1.5rem;
    }

    #attachmentsModal .modal-body {
        max-height: 70vh;
        overflow-y: auto;
    }

    .project-info-section {
        background-color: #f8f9fa;
    }

    .project-avatar {
        width: 50px;
        height: 50px;
    }

    .project-avatar-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
    }

    .project-details h6 {
        font-weight: 600;
        color: #2c3e50;
    }

    .filter-section {
        background-color: #fff;
    }

    .attachments-container {
        background-color: #fff;
    }

    .attachment-card {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s ease;
        background: white;
        height: 100%;
    }

    .attachment-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-color: #007bff;
    }

    .attachment-preview {
        position: relative;
        height: 140px;
        overflow: hidden;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .attachment-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .attachment-icon {
        font-size: 2.5rem;
        color: #6c757d;
    }

    .attachment-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .attachment-card:hover .attachment-overlay {
        opacity: 1;
    }

    .attachment-actions {
        display: flex;
        gap: 0.5rem;
    }

    .attachment-info {
        padding: 0.75rem;
    }

    .attachment-name {
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
        word-break: break-all;
        line-height: 1.3;
    }

    .attachment-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .attachment-type {
        font-size: 0.75rem;
        color: #6c757d;
        text-transform: uppercase;
        font-weight: 500;
    }

    .attachment-size {
        font-size: 0.75rem;
        color: #6c757d;
    }

    .file-image {
        color: #28a745;
    }

    .file-pdf {
        color: #dc3545;
    }

    .file-doc {
        color: #007bff;
    }

    .file-xls {
        color: #198754;
    }

    .file-ppt {
        color: #fd7e14;
    }

    .file-zip {
        color: #6f42c1;
    }

    .file-default {
        color: #6c757d;
    }

    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .modal-lg {
        max-width: 70em;
    }

    .modal-title {
        font-weight: 600;
        font-size: 1.5rem;
    }

    .btn-close {
        filter: invert(0);
        opacity: 0.8;
    }

    .btn-close:hover {
        opacity: 1;
    }

    .project-details-container {
        padding: 0;
    }

    .project-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 2rem;
        border-bottom: 1px solid #dee2e6;
    }

    .project-title-section {
        margin-bottom: 1.5rem;
    }

    .project-title-main {
        font-size: 1.75rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 0.5rem;
        line-height: 1.2;
    }

    .project-description {
        color: #6c757d;
        font-size: 1rem;
        line-height: 1.5;
        margin: 0;
    }

    .project-meta-badges {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: center;
    }

    .project-info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        padding: 2rem;
    }

    .info-section {
        margin-bottom: 2rem;
    }

    .info-section-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #495057;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e9ecef;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f8f9fa;
    }

    .info-label {
        font-weight: 500;
        color: #6c757d;
        flex: 1;
    }

    .info-value {
        font-weight: 500;
        color: #2c3e50;
        text-align: left;
        flex: 1;
    }

    .team-section {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
    }

    .team-member {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem;
        border-radius: 8px;
        border: 1px solid #f1f3f4;
        transition: background-color 0.2s;
    }

    .team-member:hover {
        background-color: #f8f9fa;
        border-color: #e9ecef;
    }

    .team-member-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e9ecef;
        background: #f8f9fa;
    }

    .team-member-info {
        flex: 1;
    }

    .team-member-name {
        font-weight: 500;
        color: #2c3e50;
        margin-bottom: 0.25rem;
        font-size: 0.95rem;
    }

    .team-member-role {
        font-size: 0.875rem;
        color: #6c757d;
    }

    .dates-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .date-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: .9rem;
        text-align: center;
        border: 1px solid #e9ecef;
    }

    .date-label {
        font-size: 0.875rem;
        color: #6c757d;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .date-value {
        font-weight: 600;
        color: #2c3e50;
        font-size: 1rem;
    }

    .overdue-warning {
        color: #dc3545;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 0.5rem;
        font-size: 0.875rem;
        background: rgba(220, 53, 69, 0.1);
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
    }

    .status-badge-modal {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-created {
        background: #6c757d;
        color: rgb(255, 250, 250);
    }

    .status-onprogress {
        background: #ffc107;
        color: #000;
    }

    .status-completed {
        background: #198754;
        color: white;
    }

    .access-badge-modal {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .access-public {
        background: #d1ecf1;
        color: #0c5460;
        border: 1px solid #bee5eb;
    }

    .access-private {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .project-details-img {
        height: 250px;
        object-fit: cover;
        width: 100%;
        border-radius: 8px;
    }

    .detail-item {
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f0f0f0;
    }

    .detail-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 4px;
    }

    .detail-value {
        color: #6c757d;
    }

    .member-badge {
        background: #e9ecef;
        border-radius: 20px;
        padding: 6px 12px;
        margin: 4px;
        display: inline-block;
        font-size: 0.875rem;
    }

    .client-info {
        display: flex;
        align-items: center;
        margin-bottom: 8px;
    }

    .client-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 12px;
        border: 2px solid #e9ecef;
    }

    .actstrip {
        position: relative;
        padding: 15px 20px;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 12px;
        margin-bottom: 20px;
        border-left: 5px solid #007bff;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
    }

    .client-details {
        flex: 1;
    }

    .client-name {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 2px;
    }

    .client-email {
        color: #6c757d;
        font-size: 0.875rem;
    }

    .dropdown-item {
        padding: 0.5rem 1rem;
        border-radius: 0.25rem;
    }

    .dropdown-item:hover {
        background-color: #f8f9fa;
    }

    .project-details-header {
        display: flex;
        align-items: flex-start;
        margin-bottom: 1.5rem;
    }

    .project-image-modal {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 8px;
        margin-right: 1.5rem;
    }

    .project-info-modal {
        flex: 1;
    }

    .project-title-modal {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #2c3e50;
    }

    .project-description-modal {
        color: #6c757d;
        margin-bottom: 1rem;
    }

    .project-meta-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    .meta-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        border: 1px solid #e9ecef;
    }

    .meta-card-title {
        font-size: 0.875rem;
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .meta-card-content {
        font-size: 1rem;
        color: #2c3e50;
    }

    .team-members-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }

    .member-chip {
        background: #e9ecef;
        border-radius: 20px;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        color: #495057;
        border: 1px solid #dee2e6;
    }

    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-created {
        background: #6c757d;
        color: white;
    }

    .overdue-warning {
        color: #dc3545;
        font-weight: 600;
        background: rgba(220, 53, 69, 0.1);
        padding: 4px 8px;
        border-radius: 4px;
        border-left: 3px solid #dc3545;
    }

    .overdue-badge {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
    }

    .status-onprogress {
        background: #ffc107;
        color: #000;
    }

    .status-completed {
        background: #198754;
        color: white;
    }

    .access-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .access-public {
        background: #d1ecf1;
        color: #0c5460;
    }

    .access-private {
        background: #f8d7da;
        color: #721c24;
    }

    .dates-section {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 12px;
        margin-bottom: 15px;
    }

    .status-datebar {
        background: #f0efef;
        border-radius: 8px;
        padding: 7px;
    }

    .dates-section .row {
        margin-bottom: 8px;
    }

    .dates-section .row:last-child {
        margin-bottom: 0;
    }

    .team-leadership {
        border-top: 1px solid #e9ecef;
        padding-top: 15px;
    }

    .project-name-truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 200px;
    }

    .overdue-warning {
        color: #dc3545;
        font-weight: 600;
    }

    .status-dates-info {
        background: #e7f3ff;
        border-radius: 6px;
        padding: 8px 12px;
        margin-top: 10px;
        border-left: 4px solid #007bff;
    }

    .status-dates-info small {
        font-size: 0.8rem;
    }

    .project-modal-header {
        position: relative;
        height: 200px;
        overflow: hidden;
    }

    .project-modal-header-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .project-modal-header-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .project-modal-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, rgba(0, 0, 0, 0.3) 0%, rgba(0, 0, 0, 0.7) 100%);
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        padding: 1.5rem;
    }

    .project-modal-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: white;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
        margin-bottom: 0.5rem;
    }

    .btn-close-white {
        filter: invert(1);
        opacity: 0.8;
    }

    .btn-close-white:hover {
        opacity: 1;
    }

    #filterContainer {
        z-index: 10000;
    }

    .modal-body {
        padding-top: 1.5rem;
    }

    .modal-header.p-0 {
        border-bottom: none;
    }

    .project-description-container {
        position: relative;
    }

    .project-description-short,
    .project-description-full {
        word-wrap: break-word;
        line-height: 1.5;
    }

    .btn-read-more {
        color: #007bff !important;
        font-size: 0.875rem;
        margin-left: 4px;
        border: none;
        background: none;
        cursor: pointer;
        padding: 0;
    }

    .btn-read-more:hover {
        color: #0056b3 !important;
        text-decoration: underline !important;
    }

    .btn-read-more:focus {
        outline: none;
        box-shadow: none;
    }

    .bg-secondary-member,
    .bg-secondary-lead,
    .bg-secondary-client,
    .bg-secondary-vteam {
        background-color: #6291b8;
    }

    .project-modal-content {
        padding: 2rem;
    }

    .team-leadership-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
        background: #f8f9fa;
        padding: 1em;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }

    .team-member-initial {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #6291b8;
        color: white;
        font-weight: 600;
        font-size: 1rem;
    }

    .team-member-card {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem;
        border-radius: 8px;
        border: 1px solid #f1f3f4;
        transition: background-color 0.2s;
    }

    .team-member-card:hover {
        background-color: #f8f9fa;
        border-color: #e9ecef;
    }

    .attachments-section {
        margin-top: 1.5rem;
    }

    .attachments-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.35rem 0.65rem;
    }

    .filter-container-hidden {
        display: none !important;
        opacity: 0;
        transform: translateY(-10px);
        max-height: 0;
        overflow: hidden;
        transition: all 0.5s ease !important;
    }

    .filter-container-visible {
        display: flex !important;
        opacity: 1;
        transform: translateY(0);
        max-height: 500px;
        overflow: visible;
    }

    @media (max-width: 768px) {
        .project-info-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
            padding: 1.5rem;
        }

        .dates-grid {
            grid-template-columns: 1fr;
        }

        .project-header {
            padding: 1.5rem;
        }

        .project-title-main {
            font-size: 1.5rem;
        }

        .modal-header {
            padding: 1rem 1.5rem;
        }

        .info-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.25rem;
        }

        .info-value {
            text-align: left;
        }

        .project-meta-badges {
            gap: 0.5rem;
        }

        .team-section {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .project-info-grid {
            padding: 1rem;
        }

        .project-header {
            padding: 1rem;
        }

        .project-title-main {
            font-size: 1.25rem;
        }

        .status-badge-modal,
        .access-badge-modal {
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
        }

        .team-section {
            grid-template-columns: 1fr;
        }
    }

    .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 10px 50px rgba(0, 0, 0, 0.2);
        overflow: hidden;
    }

    .info-section:last-child {
        margin-bottom: 0;
    }

    .text-danger {
        color: #dc3545 !important;
    }

    .text-success {
        color: #198754 !important;
    }

    .bg-secondary {
        background-color: #e9ebec !important;
    }

    .bg-danger {
        background-color: #dc3545 !important;
    }

    .bg-warning {
        background-color: #fffaec !important;
    }

    .bg-info {
        background-color: #0dcaf0 !important;
    }

    .modal.fade .modal-dialog {
        transition: transform 0.3s ease-out;
    }

    .btn:focus,
    .form-control:focus,
    .form-select:focus {
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        border-color: #80bdff;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let activeFilters = {
            status: '',
            client: '',
            head: '',
            access: '',
            createdDate: '',
            deadlineDate: '',
            project: ''
        };

        let currentPage = 1;
        let entriesPerPage = 5;
        let filteredProjects = [];

        const filterToggle = document.getElementById('filterToggle');
        const filterContainer = document.getElementById('filterContainer');
        const clearFiltersBtn = document.getElementById('clearFiltersBtn');
        const globalSearch = document.getElementById('globalSearch');
        const clientSearch = document.getElementById('clientSearch');
        const headSearch = document.getElementById('headSearch');
        const projectSearch = document.getElementById('projectSearch');
        const createdDateFilter = document.getElementById('createdDateFilter');
        const deadlineDateFilter = document.getElementById('deadlineDateFilter');
        const entriesPerPageSelect = document.getElementById('entriesPerPage');
        const projectTableBody = document.getElementById('projectTableBody');
        const pagination = document.getElementById('pagination');
        const paginationInfo = document.getElementById('paginationInfo');
        const showingStart = document.getElementById('showingStart');
        const showingEnd = document.getElementById('showingEnd');
        const totalEntries = document.getElementById('totalEntries');

        function setupEventListeners() {
            if (filterToggle) {
                filterToggle.addEventListener('click', function () {
                    const isVisible = filterContainer.classList.contains('filter-container-visible');

                    if (!isVisible) {
                        filterContainer.classList.remove('filter-container-hidden');
                        filterContainer.classList.add('filter-container-visible');
                        filterContainer.offsetHeight;
                        reinitializeDropdowns();
                    } else {
                        filterContainer.classList.remove('filter-container-visible');
                        filterContainer.classList.add('filter-container-hidden');
                    }
                });
            }

            function reinitializeDropdowns() {
                const dropdownElements = filterContainer.querySelectorAll('.dropdown-toggle');
                dropdownElements.forEach(dropdown => {
                    new bootstrap.Dropdown(dropdown);
                });
            }

            document.querySelectorAll('input[name="statusFilter"]').forEach(radio => {
                radio.addEventListener('change', function () {
                    const statusFilterText = document.getElementById('statusFilterText');
                    const label = this.nextElementSibling.textContent;
                    statusFilterText.textContent = label;
                    activeFilters.status = this.value;
                    applyFilters();
                    checkFiltersStatus();
                });
            });

            document.querySelectorAll('input[name="projectFilter"]').forEach(radio => {
                radio.addEventListener('change', function () {
                    const projectFilterText = document.getElementById('projectFilterText');
                    const label = this.nextElementSibling.textContent;
                    projectFilterText.textContent = label;
                    activeFilters.project = this.value;
                    applyFilters();
                    checkFiltersStatus();
                });
            });

            document.querySelectorAll('input[name="clientFilter"]').forEach(radio => {
                radio.addEventListener('change', function () {
                    const clientFilterText = document.getElementById('clientFilterText');
                    const label = this.nextElementSibling.textContent;
                    clientFilterText.textContent = label;
                    activeFilters.client = this.value;
                    applyFilters();
                    checkFiltersStatus();
                });
            });

            document.querySelectorAll('input[name="headFilter"]').forEach(radio => {
                radio.addEventListener('change', function () {
                    const headFilterText = document.getElementById('headFilterText');
                    const label = this.nextElementSibling.textContent;
                    headFilterText.textContent = label;
                    activeFilters.head = this.value;
                    applyFilters();
                    checkFiltersStatus();
                });
            });

            document.querySelectorAll('input[name="accessFilter"]').forEach(radio => {
                radio.addEventListener('change', function () {
                    const accessFilterText = document.getElementById('accessFilterText');
                    const label = this.nextElementSibling.textContent;
                    accessFilterText.textContent = label;
                    activeFilters.access = this.value;
                    applyFilters();
                    checkFiltersStatus();
                });
            });

            if (clientSearch) {
                clientSearch.addEventListener('input', function () {
                    const searchTerm = this.value.toLowerCase();
                    const clientOptions = document.querySelectorAll('.client-option');

                    clientOptions.forEach(option => {
                        const label = option.querySelector('label').textContent.toLowerCase();
                        if (label.includes(searchTerm)) {
                            option.style.display = 'block';
                        } else {
                            option.style.display = 'none';
                        }
                    });
                });
            }

            if (headSearch) {
                headSearch.addEventListener('input', function () {
                    const searchTerm = this.value.toLowerCase();
                    const headOptions = document.querySelectorAll('.head-option');

                    headOptions.forEach(option => {
                        const label = option.querySelector('label').textContent.toLowerCase();
                        if (label.includes(searchTerm)) {
                            option.style.display = 'block';
                        } else {
                            option.style.display = 'none';
                        }
                    });
                });
            }

            if (projectSearch) {
                projectSearch.addEventListener('input', function () {
                    const searchTerm = this.value.toLowerCase();
                    const projectOptions = document.querySelectorAll('.dropdown-scrollable .form-check:not(:first-child)');

                    projectOptions.forEach(option => {
                        const label = option.querySelector('label').textContent.toLowerCase();
                        if (label.includes(searchTerm)) {
                            option.style.display = 'block';
                        } else {
                            option.style.display = 'none';
                        }
                    });
                });
            }

            if (createdDateFilter) {
                createdDateFilter.addEventListener('change', function () {
                    updateDateFilter();
                });
            }

            if (deadlineDateFilter) {
                deadlineDateFilter.addEventListener('change', function () {
                    updateDateFilter();
                });
            }

            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', clearAllFilters);
            }

            if (globalSearch) {
                globalSearch.addEventListener('input', function () {
                    applyFilters();
                });
            }

            if (entriesPerPageSelect) {
                entriesPerPageSelect.addEventListener('change', function () {
                    entriesPerPage = parseInt(this.value);
                    currentPage = 1;
                    updatePagination();
                });
            }

            document.addEventListener('click', function (e) {
                if (e.target.classList.contains('view-project') || e.target.closest('.view-project')) {
                    const button = e.target.classList.contains('view-project') ? e.target : e.target.closest('.view-project');
                    viewProject(button);
                }

                if (e.target.classList.contains('delete-project') || e.target.closest('.delete-project')) {
                    const button = e.target.classList.contains('delete-project') ? e.target : e.target.closest('.delete-project');
                    const projectId = button.getAttribute('data-project-id');
                    const projectName = button.getAttribute('data-project-name');
                    deleteProject(projectId, projectName);
                }

                if (e.target.classList.contains('view-attachments') || e.target.closest('.view-attachments')) {
                    const button = e.target.classList.contains('view-attachments') ? e.target : e.target.closest('.view-attachments');
                    viewAttachments(button);
                }

                if (e.target.classList.contains('download-attachment') || e.target.closest('.download-attachment')) {
                    const button = e.target.classList.contains('download-attachment') ? e.target : e.target.closest('.download-attachment');
                    downloadAttachment(button);
                }

                if (e.target.classList.contains('view-attachment') || e.target.closest('.view-attachment')) {
                    const button = e.target.classList.contains('view-attachment') ? e.target : e.target.closest('.view-attachment');
                    viewAttachment(button);
                }

                if (e.target.id === 'downloadAllAttachments') {
                    downloadAllAttachments();
                }

                if (e.target.classList.contains('page-link')) {
                    e.preventDefault();
                    const page = parseInt(e.target.getAttribute('data-page'));
                    if (page && page !== currentPage) {
                        currentPage = page;
                        updatePagination();
                    }
                }
            });

            const confirmDeleteBtn = document.getElementById('confirmDelete');
            if (confirmDeleteBtn) {
                confirmDeleteBtn.addEventListener('click', confirmDelete);
            }

            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }

        function updateDateFilter() {
            const dateFilterText = document.getElementById('dateFilterText');

            let dateText = 'All Dates';

            if (createdDateFilter.value && deadlineDateFilter.value) {
                dateText = 'Multiple Dates';
            } else if (createdDateFilter.value) {
                dateText = `Created: ${formatDateForDisplay(createdDateFilter.value)}`;
            } else if (deadlineDateFilter.value) {
                dateText = `Deadline: ${formatDateForDisplay(deadlineDateFilter.value)}`;
            }

            dateFilterText.textContent = dateText;

            activeFilters.createdDate = createdDateFilter.value;
            activeFilters.deadlineDate = deadlineDateFilter.value;

            applyFilters();
            checkFiltersStatus();
        }

        function applyFilters() {
            const searchTerm = globalSearch ? globalSearch.value.toLowerCase() : '';
            const projectRows = document.querySelectorAll('.project-row');

            filteredProjects = [];
            projectRows.forEach(row => {
                const cardStatus = row.getAttribute('data-status');
                const cardClient = row.getAttribute('data-client');
                const cardHead = row.getAttribute('data-head');
                const cardAccess = row.getAttribute('data-access');
                const cardCreatedDate = row.getAttribute('data-created-date');
                const cardDeadlineDate = row.getAttribute('data-deadline-date');
                const cardProjectId = row.getAttribute('data-project-id');
                const rowText = row.textContent.toLowerCase();

                let show = true;

                if (activeFilters.status !== '' && cardStatus !== activeFilters.status) {
                    show = false;
                }

                if (activeFilters.client && cardClient !== activeFilters.client) {
                    show = false;
                }

                if (activeFilters.head && cardHead !== activeFilters.head) {
                    show = false;
                }

                if (activeFilters.access !== '') {
                    const cardAccessText = cardAccess === '1' ? 'private' : 'public';
                    const filterAccessText = activeFilters.access.toLowerCase();
                    if (filterAccessText !== cardAccessText) {
                        show = false;
                    }
                }

                if (activeFilters.createdDate && cardCreatedDate !== activeFilters.createdDate) {
                    show = false;
                }

                if (activeFilters.deadlineDate && cardDeadlineDate !== activeFilters.deadlineDate) {
                    show = false;
                }

                if (activeFilters.project && cardProjectId !== activeFilters.project) {
                    show = false;
                }

                if (searchTerm !== '' && !rowText.includes(searchTerm)) {
                    show = false;
                }

                row.style.display = 'none';
                row.setAttribute('data-visible', show.toString());

                if (show) {
                    filteredProjects.push(row);
                }
            });

            totalEntries.textContent = filteredProjects.length;
            currentPage = 1;
            updatePagination();
            checkFiltersStatus();
        }

        function updatePagination() {
            const totalPages = Math.ceil(filteredProjects.length / entriesPerPage);
            const startIndex = (currentPage - 1) * entriesPerPage;
            const endIndex = Math.min(startIndex + entriesPerPage, filteredProjects.length);

            projectTableBody.querySelectorAll('.project-row').forEach(row => {
                row.style.display = 'none';
            });

            filteredProjects.forEach((row, index) => {
                if (index >= startIndex && index < endIndex) {
                    row.style.display = '';
                }
            });

            showingStart.textContent = filteredProjects.length > 0 ? startIndex + 1 : 0;
            showingEnd.textContent = endIndex;

            pagination.innerHTML = '';

            if (totalPages <= 1) {
                paginationInfo.textContent = `Showing ${showingStart.textContent} to ${showingEnd.textContent} of ${filteredProjects.length} entries`;
                return;
            }

            const maxVisiblePages = 5;
            let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
            let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

            if (endPage - startPage + 1 < maxVisiblePages) {
                startPage = Math.max(1, endPage - maxVisiblePages + 1);
            }

            if (currentPage > 1) {
                const prevLi = document.createElement('li');
                prevLi.className = 'page-item';
                prevLi.innerHTML = `<a class="page-link" href="#" data-page="${currentPage - 1}">Previous</a>`;
                pagination.appendChild(prevLi);
            }

            if (startPage > 1) {
                const firstLi = document.createElement('li');
                firstLi.className = 'page-item';
                firstLi.innerHTML = `<a class="page-link" href="#" data-page="1">1</a>`;
                pagination.appendChild(firstLi);

                if (startPage > 2) {
                    const ellipsisLi = document.createElement('li');
                    ellipsisLi.className = 'page-item disabled';
                    ellipsisLi.innerHTML = '<span class="page-link">...</span>';
                    pagination.appendChild(ellipsisLi);
                }
            }

            for (let i = startPage; i <= endPage; i++) {
                const pageLi = document.createElement('li');
                pageLi.className = `page-item ${i === currentPage ? 'active' : ''}`;
                pageLi.innerHTML = `<a class="page-link" href="#" data-page="${i}">${i}</a>`;
                pagination.appendChild(pageLi);
            }

            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    const ellipsisLi = document.createElement('li');
                    ellipsisLi.className = 'page-item disabled';
                    ellipsisLi.innerHTML = '<span class="page-link">...</span>';
                    pagination.appendChild(ellipsisLi);
                }

                const lastLi = document.createElement('li');
                lastLi.className = 'page-item';
                lastLi.innerHTML = `<a class="page-link" href="#" data-page="${totalPages}">${totalPages}</a>`;
                pagination.appendChild(lastLi);
            }

            if (currentPage < totalPages) {
                const nextLi = document.createElement('li');
                nextLi.className = 'page-item';
                nextLi.innerHTML = `<a class="page-link" href="#" data-page="${currentPage + 1}">Next</a>`;
                pagination.appendChild(nextLi);
            }

            paginationInfo.textContent = `Showing ${showingStart.textContent} to ${showingEnd.textContent} of ${filteredProjects.length} entries`;
        }

        function hasActiveFilters() {
            return activeFilters.status ||
                activeFilters.client ||
                activeFilters.head ||
                activeFilters.access ||
                activeFilters.createdDate ||
                activeFilters.deadlineDate ||
                activeFilters.project;
        }

        function checkFiltersStatus() {
            const hasActive = hasActiveFilters();
            if (clearFiltersBtn) {
                clearFiltersBtn.style.display = hasActive ? 'block' : 'none';
            }
        }

        function clearAllFilters() {
            document.querySelectorAll('input[type="radio"]').forEach(radio => {
                if (radio.value === '') {
                    radio.checked = true;
                }
            });

            document.getElementById('statusFilterText').textContent = 'All Status';
            document.getElementById('projectFilterText').textContent = 'All Projects';
            document.getElementById('clientFilterText').textContent = 'All Clients';
            document.getElementById('headFilterText').textContent = 'All Heads';
            document.getElementById('accessFilterText').textContent = 'All Access';
            document.getElementById('dateFilterText').textContent = 'All Dates';

            document.getElementById('createdDateFilter').value = '';
            document.getElementById('deadlineDateFilter').value = '';

            if (globalSearch) globalSearch.value = '';
            if (clientSearch) clientSearch.value = '';
            if (headSearch) headSearch.value = '';
            if (projectSearch) projectSearch.value = '';

            document.querySelectorAll('.client-option, .head-option, .dropdown-scrollable .form-check').forEach(option => {
                option.style.display = 'block';
            });

            activeFilters = {
                status: '',
                client: '',
                head: '',
                access: '',
                createdDate: '',
                deadlineDate: '',
                project: ''
            };

            filteredProjects = Array.from(document.querySelectorAll('.project-row'));
            filteredProjects.forEach(row => {
                row.style.display = '';
                row.setAttribute('data-visible', 'true');
            });

            totalEntries.textContent = filteredProjects.length;
            currentPage = 1;
            updatePagination();

            if (clearFiltersBtn) {
                clearFiltersBtn.style.display = 'none';
            }
        }

        function viewProject(button) {
            const projectId = button.getAttribute('data-project-id');
            const projectName = button.getAttribute('data-project-name');
            const projectDescription = button.getAttribute('data-project-description');
            const projectAvatar = button.getAttribute('data-project-avatar');
            const projectAttachments = JSON.parse(button.getAttribute('data-project-attachments') || '[]');
            const projectDeadline = button.getAttribute('data-project-deadline');
            const projectCreated = button.getAttribute('data-project-created');
            const projectStatus = button.getAttribute('data-project-status');
            const projectStatusText = button.getAttribute('data-project-status-text');
            const projectStatusBadge = button.getAttribute('data-project-status-badge');
            const projectOnProgress = button.getAttribute('data-project-onprogress');
            const projectComplete = button.getAttribute('data-project-complete');
            const projectOverdue = button.getAttribute('data-project-overdue');
            const projectOverdueText = button.getAttribute('data-project-overdue-text');
            const projectHead = button.getAttribute('data-project-head');
            const projectHeadImage = button.getAttribute('data-project-head-image');
            const projectLead = button.getAttribute('data-project-lead');
            const projectLeadImage = button.getAttribute('data-project-lead-image');
            const projectClient = button.getAttribute('data-project-client');
            const projectClientEmail = button.getAttribute('data-project-client-email');
            const projectClientImage = button.getAttribute('data-project-client-image');
            const projectMembers = JSON.parse(button.getAttribute('data-project-members') || '[]');
            const projectAccess = button.getAttribute('data-project-access');

            const createdDate = new Date(projectCreated);
            const deadlineDate = new Date(projectDeadline);
            const formattedCreatedDate = createdDate.toLocaleDateString('en-GB', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            }).replace(/\//g, '-');

            const formattedDeadlineDate = deadlineDate.toLocaleDateString('en-GB', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            }).replace(/\//g, '-');

            let onProgressDate = '';
            if (projectOnProgress && projectOnProgress !== 'null') {
                const onProgress = new Date(projectOnProgress);
                onProgressDate = onProgress.toLocaleDateString('en-GB', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                }).replace(/\//g, '-');
            }

            let completeDate = '';
            if (projectComplete && projectComplete !== 'null') {
                const complete = new Date(projectComplete);
                completeDate = complete.toLocaleDateString('en-GB', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                }).replace(/\//g, '-');
            }

            const accessClass = projectAccess === '1' ? 'access-private' : 'access-public';
            const accessText = projectAccess === '1' ? 'Private' : 'Public';

            let descriptionHTML = '';
            if (projectDescription && projectDescription.length > 15) {
                const shortDescription = projectDescription.substring(0, 15) + '...';
                descriptionHTML = `
            <div class="project-description-container">
                <span class="project-description-short">${shortDescription}</span>
                <span class="project-description-full" style="display: none;">${projectDescription}</span>
                <button class="btn-read-more btn btn-link p-0 text-decoration-none" onclick="toggleProjectDescription(this)">
                    <small>Read More</small>
                </button>
            </div>
        `;
            } else {
                descriptionHTML = `<span>${projectDescription || 'No description available'}</span>`;
            }

            const modalHeader = document.getElementById('projectModalHeader');
            modalHeader.innerHTML = '';

            if (projectAvatar) {
                modalHeader.innerHTML = `
            <img src="${projectAvatar}" class="project-modal-header-img" alt="${projectName}">
            <div class="project-modal-overlay">
                <div>
                    <h5 class="project-modal-title">${projectName}</h5>
                    <span class="project-modal-badge ${projectStatusBadge} p-1">${projectStatusText}</span>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        `;
            } else {
                modalHeader.innerHTML = `
            <div class="project-modal-header-placeholder">
                <i class="bi bi-diagram-3 text-white" style="font-size: 3rem;"></i>
            </div>
            <div class="project-modal-overlay">
                <div>
                    <h5 class="project-modal-title">${projectName}</h5>
                    <span class="project-modal-badge ${projectStatusBadge}">${projectStatusText}</span>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        `;
            }

            attachmentsHTML = `
        <div class="attachments-section">
            <div class="attachments-header">
                <h4 class="info-section-title mb-0">ATTACHMENTS</h4>
            </div>
            <div class="attachments-preview">
                ${projectAttachments.slice(0, 5).map((file, index) => {
                if (!file || !file.name) return '';
                const fileName = file.name;
                const fileExtension = fileName.includes('.') ? fileName.split('.').pop().toLowerCase() : '';
                let icon = 'bi-file-earmark';
                let colorClass = 'file-default';
                let isImage = false;

                if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExtension)) {
                    isImage = true;
                    icon = 'bi-image';
                    colorClass = 'file-image';
                } else if (fileExtension === 'pdf') {
                    icon = 'bi-file-earmark-pdf';
                    colorClass = 'file-pdf';
                } else if (['doc', 'docx'].includes(fileExtension)) {
                    icon = 'bi-file-earmark-word';
                    colorClass = 'file-doc';
                } else if (['xls', 'xlsx', 'csv'].includes(fileExtension)) {
                    icon = 'bi-file-earmark-excel';
                    colorClass = 'file-xls';
                } else if (['ppt', 'pptx'].includes(fileExtension)) {
                    icon = 'bi-file-earmark-ppt';
                    colorClass = 'file-ppt';
                } else if (['zip', 'rar', '7z'].includes(fileExtension)) {
                    icon = 'bi-file-earmark-zip';
                    colorClass = 'file-zip';
                }

                if (isImage && file.url) {
                    return `
                                <div class="attachment-item-preview">
                                    <img src="${file.url}" alt="${fileName}" class="attachment-thumbnail"
                                         data-bs-toggle="tooltip" title="${fileName}">
                                </div>
                            `;
                } else {
                    return `
                                <div class="attachment-item-preview">
                                    <div class="file-icon-preview ${colorClass}" data-bs-toggle="tooltip" title="${fileName}">
                                        <i class="bi ${icon}"></i>
                                    </div>
                                </div>
                            `;
                }
            }).join('')}
                ${projectAttachments.length > 5 ? `
                    <div class="attachment-more-preview" data-bs-toggle="tooltip" title="${projectAttachments.length - 5} more files">
                        +${projectAttachments.length - 5}
                    </div>
                ` : ''}
                ${projectAttachments.length > 0 ? `
                    <button class="btn btn-sm btn-outline-primary view-attachments"
                            data-project-name="${projectName}"
                            data-project-attachments='${JSON.stringify(projectAttachments)}'>
                        <i class="bi bi-eye me-1"></i>View Attachments
                    </button>
                ` : ''}
            </div>
        </div>
    `;

            let modalContent = `
        <div class="project-modal-content">
            <div class="project-info-grid">
                <div>
                    <div class="info-section">
                        <h4 class="info-section-title">PROJECT INFORMATION</h4>
                        <div class="info-item">
                            <span class="info-label">Project Description</span>
                            <span class="info-value">
                                ${descriptionHTML}
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Access Mode</span>
                            <span class="info-value">
                                <span class="project-modal-badge ${accessClass}  p-1">${accessText}</span>
                            </span>
                        </div>
                    </div>
                    <div class="info-section">
                        <h4 class="info-section-title">DATES</h4>
                        <div class="dates-grid">
                            <div class="date-card">
                                <div class="date-label">Created</div>
                                <div class="date-value">${formattedCreatedDate}</div>
                            </div>
                            <div class="date-card">
                                <div class="date-label">Deadline</div>
                                <div class="date-value">${formattedDeadlineDate}</div>
                            </div>
                            ${onProgressDate ? `
                            <div class="date-card">
                                <div class="date-label">On Progress</div>
                                <div class="date-value">${onProgressDate}</div>
                            </div>
                            ` : ''}
                            ${completeDate ? `
                            <div class="date-card">
                                <div class="date-label">Completed</div>
                                <div class="date-value ${projectOverdue > 0 ? 'text-danger' : 'text-success'}">${completeDate}</div>
                                ${projectOverdue > 0 ? `
                                <div class="overdue-warning">
                                    <i class="bi bi-exclamation-triangle"></i>
                                    Overdue: ${projectOverdueText}
                                </div>
                                ` : ''}
                            </div>
                            ` : ''}
                        </div>
                    </div>
                </div>
                <div>
                    <div class="info-section">
                        <h4 class="info-section-title">TEAM LEADERSHIP</h4>
                        <div class="team-leadership-item">
                            ${projectHeadImage ? `
                                <img src="${projectHeadImage}" class="team-member-avatar" alt="${projectHead}">
                            ` : `
                                <div class="team-member-initial">
                                    ${projectHead && projectHead !== 'N/A' ? projectHead.charAt(0).toUpperCase() : 'P'}
                                </div>
                            `}
                            <div class="team-member-info">
                                <div class="team-member-name">${projectHead}</div>
                                <div class="team-member-role">Project Manager</div>
                            </div>
                        </div>
                        <div class="team-leadership-item">
                            ${projectLeadImage ? `
                                <img src="${projectLeadImage}" class="team-member-avatar" alt="${projectLead}">
                            ` : `
                                <div class="team-member-initial">
                                    ${projectLead && projectLead !== 'N/A' ? projectLead.charAt(0).toUpperCase() : 'L'}
                                </div>
                            `}
                            <div class="team-member-info">
                                <div class="team-member-name">${projectLead}</div>
                                <div class="team-member-role">Project Lead</div>
                            </div>
                        </div>
                    </div>
                    <div class="info-section">
                        <h4 class="info-section-title">CLIENT INFORMATION</h4>
                        <div class="client-info-card">
                            <div class="team-leadership-item">
                                ${projectClientImage && projectClientImage.includes('client_images/') ?
                    `<img src="${projectClientImage}" class="team-member-avatar" alt="${projectClient}">` :
                    `<div class="team-member-initial">
                                        ${projectClient && projectClient !== 'N/A' ? projectClient.charAt(0).toUpperCase() : 'C'}
                                    </div>`
                }
                                <div class="team-member-info">
                                    <div class="team-member-name">${projectClient}</div>
                                    <div class="team-member-role">${projectClientEmail}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="info-section">
                    <h4 class="info-section-title">TEAM MEMBERS</h4>
                    <div class="team-section">
                        ${projectMembers.length > 0 ?
                    projectMembers.map(member => `
                                    <div class="team-member-card">
                                        <div class="team-member-initial">
                                            ${member.charAt(0).toUpperCase()}
                                        </div>
                                        <div class="team-member-info">
                                            <div class="team-member-name">${member}</div>
                                            <div class="team-member-role">Team Member</div>
                                        </div>
                                    </div>
                                `).join('') :
                    '<div class="text-muted">No team members assigned</div>'
                }
                    </div>
                </div>
                ${attachmentsHTML}
            </div>
        </div>
    `;

            document.getElementById('projectModalBody').innerHTML = modalContent;
        }

        function initAttachmentsModal() {
            const filterRadios = document.querySelectorAll('input[name="attachmentFilter"]');
            filterRadios.forEach(radio => {
                radio.addEventListener('change', function () {
                    filterAttachments();
                });
            });
        }

        function filterAttachments() {
            const activeFilter = document.querySelector('input[name="attachmentFilter"]:checked').id;
            const attachmentCards = document.querySelectorAll('.attachment-card-item');
            const emptyState = document.getElementById('emptyAttachments');

            let visibleCount = 0;

            attachmentCards.forEach(card => {
                const fileType = card.getAttribute('data-file-type');
                let matchesFilter = true;

                if (activeFilter === 'filterImages') {
                    matchesFilter = fileType === 'image';
                } else if (activeFilter === 'filterDocuments') {
                    matchesFilter = fileType === 'document';
                }

                if (matchesFilter) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (emptyState) {
                if (visibleCount === 0) {
                    emptyState.classList.remove('d-none');
                } else {
                    emptyState.classList.add('d-none');
                }
            }
        }

        window.viewAttachments = function (button) {
            const projectName = button.getAttribute('data-project-name');
            const attachments = JSON.parse(button.getAttribute('data-project-attachments') || '[]');

            document.getElementById('attachmentsProjectName').textContent = projectName;
            document.getElementById('attachmentsCount').textContent = attachments.length;

            const attachmentsGrid = document.getElementById('attachmentsGrid');
            attachmentsGrid.innerHTML = '';

            if (attachments.length === 0) {
                document.getElementById('emptyAttachments').classList.remove('d-none');
            } else {
                document.getElementById('emptyAttachments').classList.add('d-none');

                attachments.forEach((file, index) => {
                    const fileType = file.type ? file.type.toLowerCase() : '';
                    const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileType);
                    const fileCategory = isImage ? 'image' : 'document';

                    let fileIcon = 'bi-file-earmark';
                    let fileColor = 'file-default';

                    if (isImage) {
                        fileIcon = 'bi-image';
                        fileColor = 'file-image';
                    } else if (fileType === 'pdf') {
                        fileIcon = 'bi-file-earmark-pdf';
                        fileColor = 'file-pdf';
                    } else if (['doc', 'docx'].includes(fileType)) {
                        fileIcon = 'bi-file-earmark-word';
                        fileColor = 'file-doc';
                    } else if (['xls', 'xlsx'].includes(fileType)) {
                        fileIcon = 'bi-file-earmark-excel';
                        fileColor = 'file-xls';
                    } else if (['ppt', 'pptx'].includes(fileType)) {
                        fileIcon = 'bi-file-earmark-ppt';
                        fileColor = 'file-ppt';
                    } else if (['zip', 'rar', '7z'].includes(fileType)) {
                        fileIcon = 'bi-file-earmark-zip';
                        fileColor = 'file-zip';
                    }

                    const fileCard = document.createElement('div');
                    fileCard.className = 'col-xl-3 col-lg-4 col-md-6 attachment-card-item';
                    fileCard.setAttribute('data-file-name', file.name);
                    fileCard.setAttribute('data-file-type', fileCategory);

                    fileCard.innerHTML = `
                <div class="attachment-card">
                    <div class="attachment-preview">
                        ${isImage ?
                            `<img src="${file.url}" class="attachment-image" alt="${file.name}">` :
                            `<i class="attachment-icon ${fileIcon} ${fileColor}"></i>`
                        }
                        <div class="attachment-overlay">
                            <div class="attachment-actions">
                                <button class="btn btn-sm btn-light download-attachment"
                                        data-file-url="${file.url}"
                                        data-file-name="${file.name}"
                                        title="Download">
                                    <i class="bi bi-download"></i>
                                </button>
                                ${isImage ?
                            `<button class="btn btn-sm btn-light view-attachment"
                                        data-file-url="${file.url}"
                                        data-file-name="${file.name}"
                                        title="View">
                                    <i class="bi bi-eye"></i>
                                </button>` :
                            `<button class="btn btn-sm btn-light" disabled title="Preview not available">
                                    <i class="bi bi-eye"></i>
                                </button>`
                        }
                            </div>
                        </div>
                    </div>
                    <div class="attachment-info  text-center">
                        <span class="attachment-type ${fileColor}  ">
                            ${fileType.toUpperCase()}
                        </span>
                    </div>
                </div>
            `;

                    attachmentsGrid.appendChild(fileCard);
                });
            }

            attachmentsGrid.setAttribute('data-all-attachments', JSON.stringify(attachments));
            document.getElementById('filterAll').checked = true;

            const attachmentsModal = new bootstrap.Modal(document.getElementById('attachmentsModal'));
            attachmentsModal.show();
        };

        function downloadAttachment(button) {
            const fileUrl = button.getAttribute('data-file-url');
            const fileName = button.getAttribute('data-file-name');

            const link = document.createElement('a');
            link.href = fileUrl;
            link.download = fileName;
            link.target = '_blank';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function viewAttachment(button) {
            const fileUrl = button.getAttribute('data-file-url');
            const fileName = button.getAttribute('data-file-name');

            window.open(fileUrl, '_blank');
        }

        function downloadAllAttachments() {
            const attachmentsGrid = document.getElementById('attachmentsGrid');
            const attachments = JSON.parse(attachmentsGrid.getAttribute('data-all-attachments') || '[]');

            if (attachments.length === 0) {
                showToast('No attachments to download', 'warning');
                return;
            }

            const downloadBtn = document.getElementById('downloadAllAttachments');
            const originalText = downloadBtn.innerHTML;
            downloadBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Preparing...';
            downloadBtn.disabled = true;

            attachments.forEach((file, index) => {
                setTimeout(() => {
                    const link = document.createElement('a');
                    link.href = file.url;
                    link.download = file.name;
                    link.target = '_blank';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }, index * 100);
            });

            setTimeout(() => {
                downloadBtn.innerHTML = originalText;
                downloadBtn.disabled = false;
                showToast(`Started downloading ${attachments.length} files`, 'success');
            }, attachments.length * 100 + 500);
        }

        window.toggleProjectDescription = function (button) {
            const container = button.closest('.project-description-container');
            const shortDesc = container.querySelector('.project-description-short');
            const fullDesc = container.querySelector('.project-description-full');

            if (fullDesc.style.display === 'none') {
                shortDesc.style.display = 'none';
                fullDesc.style.display = 'inline';
                button.innerHTML = '<small>Read Less</small>';
            } else {
                shortDesc.style.display = 'inline';
                fullDesc.style.display = 'none';
                button.innerHTML = '<small>Read More</small>';
            }
        }

        function deleteProject(projectId, projectName) {
            document.getElementById('deleteProjectName').textContent = projectName;
            document.getElementById('confirmDelete').setAttribute('data-project-id', projectId);

            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }

        function confirmDelete() {
            const projectId = document.getElementById('confirmDelete').getAttribute('data-project-id');
            const projectName = document.getElementById('deleteProjectName').textContent;

            fetch(`/dashboard/project/delete/${projectId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    _method: 'DELETE'
                })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
                        deleteModal.hide();

                        showToast('Project deleted successfully', 'success');

                        const projectRow = document.querySelector(`.project-row[data-project-id="${projectId}"]`);
                        if (projectRow) {
                            projectRow.remove();
                        }

                        applyFilters();
                    } else {
                        showToast(data.message || 'Failed to delete project', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('An error occurred while deleting the project', 'error');
                });
        }

        function formatDateForDisplay(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        }

        function showToast(message, type = 'success') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            Toast.fire({
                icon: type,
                title: message
            });
        }

        setupEventListeners();
        initAttachmentsModal();
        filteredProjects = Array.from(document.querySelectorAll('.project-row'));
        updatePagination();
    });
</script>
