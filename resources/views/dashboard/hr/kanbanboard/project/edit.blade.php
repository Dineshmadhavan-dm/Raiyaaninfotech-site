<x-kanbandashboardlayout>
    @section('title', 'Edit Project')

    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-2">Edit Project</h5>
            </div>

            <a href="{{ route('projectlist') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left-circle me-2"></i>Back
            </a>
        </div>

        <x-message />

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form action="{{ route('project.update', $project->pro_id) }}" method="POST"
                            enctype="multipart/form-data" id="projectForm">
                            @csrf
                            @method('PUT')

                            @php
                                $existingMembers = [];
                                if (isset($project->pro_member) && !empty($project->pro_member)) {
                                    $rawData = $project->pro_member;
                                    $cleanedData = trim($rawData, ' "');
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
                                                    $existingMembers[] = (string) $cleanNested;
                                                }
                                            }
                                        } elseif (is_numeric($cleanMember) && $cleanMember > 0) {
                                            $existingMembers[] = (string) $cleanMember;
                                        }
                                    }

                                    $existingMembers = array_unique($existingMembers);
                                    $existingMembers = array_filter($existingMembers);
                                }
                            @endphp

                            <div class="section-header mb-4">
                                <h6 class="section-title">Project Info</h6>
                                <div class="section-divider"></div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pro_name" class="form-label required">Project Name</label>
                                        <input type="text"
                                            class="form-control @error('pro_name') is-invalid @enderror" id="pro_name"
                                            name="pro_name" value="{{ old('pro_name', $project->pro_name) }}"
                                            placeholder="">
                                        @error('pro_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pro_deadline" class="form-label required">Project Deadline</label>
                                        <input type="date"
                                            class="form-control @error('pro_deadline') is-invalid @enderror"
                                            id="pro_deadline" name="pro_deadline"
                                            value="{{ old('pro_deadline', \Carbon\Carbon::parse($project->pro_deadline)->format('Y-m-d')) }}">
                                        @error('pro_deadline')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="pro_desc" class="form-label required">Project Description</label>
                                        <textarea class="form-control @error('pro_desc') is-invalid @enderror" id="pro_desc" name="pro_desc" rows="3"
                                            placeholder="">{{ old('pro_desc', $project->pro_desc) }}</textarea>
                                        @error('pro_desc')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label required">Access Mode</label>
                                        <div class="border rounded p-3 bg-light">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            name="pro_accessmod" id="access_public" value="0"
                                                            {{ old('pro_accessmod', $project->pro_accessmod) == '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="access_public">
                                                            <span class="fw-semibold">Public</span>
                                                            <small class="d-block text-muted">Visible to all team
                                                                members</small>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            name="pro_accessmod" id="access_private" value="1"
                                                            {{ old('pro_accessmod', $project->pro_accessmod) == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="access_private">
                                                            <span class="fw-semibold">Private</span>
                                                            <small class="d-block text-muted">Restricted access
                                                                only</small>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @error('pro_accessmod')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pro_status" class="form-label required">Project Status</label>
                                        <select class="form-control @error('pro_status') is-invalid @enderror"
                                            id="pro_status" name="pro_status">
                                            <option value="0"
                                                {{ old('pro_status', $project->pro_status) == 0 ? 'selected' : '' }}>
                                                Created</option>
                                            <option value="1"
                                                {{ old('pro_status', $project->pro_status) == 1 ? 'selected' : '' }}>On
                                                Progress</option>
                                            <option value="2"
                                                {{ old('pro_status', $project->pro_status) == 2 ? 'selected' : '' }}>
                                                Completed</option>
                                        </select>
                                        @error('pro_status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                        <div class="status-dates mt-2">
                                            @if ($project->pro_onprogress)
                                                <small class="text-muted">
                                                    <i class="bi bi-play-circle me-1"></i>
                                                    Started:
                                                    {{ \Carbon\Carbon::parse($project->pro_onprogress)->format('M d, Y') }}
                                                </small>
                                            @endif
                                            @if ($project->pro_complete)
                                                <br>
                                                <small class="text-muted">
                                                    <i class="bi bi-check-circle me-1"></i>
                                                    Completed:
                                                    {{ \Carbon\Carbon::parse($project->pro_complete)->format('M d, Y') }}
                                                </small>
                                            @endif
                                            @if ($project->pro_overdue > 0)
                                                <br>
                                                <small class="text-danger">
                                                    <i class="bi bi-clock me-1"></i>
                                                    Overdue: {{ $project->overdue_text }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="section-header mb-4">
                                <h6 class="section-title">PROJECT AVATAR</h6>
                                <div class="section-divider"></div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="pro_avater" class="form-label">PROJECT AVATAR (SINGLE
                                            IMAGE)</label>

                                        @if ($project->pro_avater)
                                            <div class="current-attachments-section mb-3">
                                                <label class="sub-label">Current Avatar</label>
                                                <div class="current-attachments-list d-flex flex-wrap gap-3">
                                                    @php
                                                        $avatarFileName = basename($project->pro_avater);
                                                        $fileExtension = strtolower(
                                                            pathinfo($avatarFileName, PATHINFO_EXTENSION),
                                                        );
                                                        $fileUrl = asset($project->pro_avater);
                                                        $isImage = in_array($fileExtension, [
                                                            'jpg',
                                                            'jpeg',
                                                            'png',
                                                            'gif',
                                                            'bmp',
                                                            'webp',
                                                        ]);
                                                    @endphp

                                                    <div class="current-file-item row p-3 ms-1 rounded border">
                                                        <div class="col-auto mb-2">
                                                            @if ($isImage)
                                                                <img src="{{ $fileUrl }}"
                                                                    class="attachment-preview"
                                                                    style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;"
                                                                    alt="{{ $avatarFileName }}"
                                                                    onerror="this.src='{{ asset('images/admin_default.jpg') }}'">
                                                            @else
                                                                <div class="file-icon">
                                                                    <i class="bi bi-file-image text-primary"
                                                                        style="font-size: 4em;"></i>
                                                                </div>
                                                            @endif
                                                        </div>

                                                        <div class="col-auto">
                                                            <div
                                                                class="current-file-actions d-flex flex-column gap-2 w-100">
                                                                <a href="{{ $fileUrl }}" target="_blank"
                                                                    class="btn btn-sm btn-outline-primary">
                                                                    View
                                                                </a>

                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        name="remove_avater" id="remove_avater"
                                                                        value="1">
                                                                    <label
                                                                        class="form-check-label text-danger fw-semibold small"
                                                                        for="remove_avater">
                                                                        Remove
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="alert alert-info">
                                                <i class="bi bi-info-circle me-2"></i>
                                                No avatar set for this project.
                                            </div>
                                        @endif

                                        <div class="file-input-wrapper">
                                            <input type="file"
                                                class="form-control file-input @error('pro_avater') is-invalid @enderror"
                                                id="pro_avater" name="pro_avater" accept=".jpg,.jpeg,.png">
                                            <div class="file-info">
                                                <span class="file-placeholder">
                                                    @if ($project->pro_avater)
                                                        Choose new file to replace current avatar
                                                    @else
                                                        Choose avatar image
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-text mt-2">
                                            Allowed Format: JPG, JPEG, PNG only.
                                        </div>
                                        @error('pro_avater')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="section-header mb-4">
                                <h6 class="section-title">PROJECT ATTACHMENTS</h6>
                                <div class="section-divider"></div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="pro_attachment" class="form-label">PROJECT ATTACHMENTS (MULTIPLE
                                            FILES)</label>

                                        @if ($project->pmtsImages && $project->pmtsImages->count() > 0)
                                            <div class="current-attachments-section mb-3">
                                                <label class="sub-label">Current Attachments</label>
                                                <div class="current-attachments-list d-flex flex-wrap gap-3">
                                                    @foreach ($project->pmtsImages as $attachment)
                                                        @php
                                                            $fileExtension = strtolower(
                                                                pathinfo(
                                                                    $attachment->pmtsimage_name,
                                                                    PATHINFO_EXTENSION,
                                                                ),
                                                            );
                                                            $fileName = $attachment->pmtsimage_name;
                                                            $fileUrl = asset('project_attachments/' . $fileName);
                                                            $isImage = in_array($fileExtension, [
                                                                'jpg',
                                                                'jpeg',
                                                                'png',
                                                                'gif',
                                                                'bmp',
                                                                'webp',
                                                            ]);
                                                        @endphp

                                                        <div class="current-file-item row ms-1 p-3 rounded border">
                                                            <div class="col-auto mb-2">
                                                                @if ($isImage)
                                                                    <img src="{{ $fileUrl }}"
                                                                        class="attachment-preview"
                                                                        style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px;"
                                                                        alt="{{ $fileName }}"
                                                                        onerror="this.src='{{ asset('images/admin_default.jpg') }}'">
                                                                @else
                                                                    <div class="file-icon">
                                                                        @if ($fileExtension === 'pdf')
                                                                            <i class="bi bi-file-pdf text-danger "
                                                                                style="font-size: 3.8em; margin-left: -.2em;"></i>
                                                                        @elseif(in_array($fileExtension, ['doc', 'docx']))
                                                                            <i
                                                                                class="bi bi-file-word text-primary"style="font-size: 3.8em; margin-left: -.2em;"></i>
                                                                        @elseif(in_array($fileExtension, ['xls', 'xlsx', 'csv']))
                                                                            <i class="bi bi-file-excel text-success "
                                                                                style="font-size: 3.8em; margin-left: -.2em;"></i>
                                                                        @elseif(in_array($fileExtension, ['ppt', 'pptx']))
                                                                            <i
                                                                                class="bi bi-file-ppt text-warning "style="font-size: 3.8em; margin-left: -.2em;"></i>
                                                                        @else
                                                                            <i class="bi bi-file-earmark "></i>
                                                                        @endif
                                                                    </div>
                                                                @endif
                                                            </div>

                                                            <div class="col-auto">
                                                                <div
                                                                    class="current-file-actions d-flex flex-column gap-1 w-100">
                                                                    <a href="{{ $fileUrl }}" target="_blank"
                                                                        class="btn btn-sm btn-outline-primary ">
                                                                        View
                                                                    </a>

                                                                    <div class="form-check">
                                                                        <input class="form-check-input"
                                                                            type="checkbox"
                                                                            name="remove_attachments[]"
                                                                            value="{{ $attachment->pmts_id }}"
                                                                            id="remove_attachment_{{ $attachment->pmts_id }}">
                                                                        <label
                                                                            class="form-check-label text-danger fw-semibold small"
                                                                            for="remove_attachment_{{ $attachment->pmts_id }}">
                                                                            Remove
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @else
                                            <div class="alert alert-info">
                                                <i class="bi bi-info-circle me-2"></i>
                                                No attachments found for this project.
                                            </div>
                                        @endif

                                        <div class="file-input-wrapper">
                                            <input type="file"
                                                class="form-control file-input @error('pro_attachment') is-invalid @enderror"
                                                id="pro_attachment" name="pro_attachment[]"
                                                accept=".jpg,.jpeg,.png,.pdf,.ppt,.pptx,.csv,.xlsx,.xls,.doc,.docx"
                                                multiple>
                                            <div class="file-info">
                                                <span class="file-placeholder">
                                                    Choose additional files
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-text mt-2">
                                            Allowed Files: JPG, JPEG, PNG, PDF, PPT, CSV, Excel, Word documents.
                                        </div>
                                        @error('pro_attachment')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror

                                        <div id="selectedFiles" class="selected-files mt-3" style="display: none;">
                                            <label class="sub-label">New Selected Files:</label>
                                            <div id="filesList" class="files-list"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="section-header mb-4">
                                <h6 class="section-title">Project Team</h6>
                                <div class="section-divider"></div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label required">Client</label>
                                        <input type="hidden" name="pro_client" id="pro_client"
                                            value="{{ old('pro_client', $project->pro_client) }}">
                                        <div class="search-wrapper">
                                            <div class="input-group">
                                                <input type="text" class="form-control search-input"
                                                    id="client_search" placeholder="Search client by name or email">
                                                <button class="btn search-btn" type="button"
                                                    id="client_search_button">
                                                    <i class="bi bi-search"></i>
                                                </button>
                                            </div>
                                            <ul id="client_results" class="search-results"></ul>

                                            <div class="selected-employee" id="client_display"
                                                style="{{ old('pro_client', $project->pro_client) ? 'display: block;' : 'display: none;' }}">
                                                <div class="selected-employee-info">
                                                    <img src="{{ $project->client && $project->client->cl_image ? asset('client_images/' . $project->client->cl_image) : asset('images/admin_default.jpg') }}"
                                                        class="employee-avatar" id="client_image">
                                                    <div class="employee-details">
                                                        <div class="employee-name" id="client_name">
                                                            {{ $project->client ? $project->client->cl_name : '' }}
                                                        </div>
                                                        <div class="employee-id" id="client_details">
                                                            {{ $project->client ? 'CL-' . $project->client->cl_id . ' - ' . $project->client->cl_email : '' }}
                                                        </div>
                                                    </div>
                                                    <button type="button" class="btn-remove-employee"
                                                        onclick="clearEmployeeSelection('client')">
                                                        <i class="bi bi-x"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        @error('pro_client')
                                            <div class="error-message">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label required">Project Manager</label>
                                        <input type="hidden" name="pro_head" id="pro_head"
                                            value="{{ old('pro_head', $project->pro_head) }}">
                                        <div class="search-wrapper">
                                            <div class="input-group">
                                                <input type="text" class="form-control search-input"
                                                    id="head_search" placeholder="Search head by name or ID">
                                                <button class="btn search-btn" type="button"
                                                    id="head_search_button">
                                                    <i class="bi bi-search"></i>
                                                </button>
                                            </div>
                                            <ul id="head_results" class="search-results"></ul>

                                            <div class="selected-employee" id="head_display"
                                                style="{{ old('pro_head', $project->pro_head) ? 'display: block;' : 'display: none;' }}">
                                                <div class="selected-employee-info">
                                                    <img src="{{ $project->projectHead && $project->projectHead->image ? asset('employee_images/' . $project->projectHead->image) : asset('images/admin_default.jpg') }}"
                                                        class="employee-avatar" id="head_image">
                                                    <div class="employee-details">
                                                        <div class="employee-name" id="head_name">
                                                            {{ $project->projectHead ? $project->projectHead->fullname : '' }}
                                                        </div>
                                                        <div class="employee-id" id="head_details">
                                                            {{ $project->projectHead ? $project->projectHead->employee_id . ' - ' . ($project->projectHead->Departmentid ? $project->projectHead->Departmentid->dep_name : 'No Department') : '' }}
                                                        </div>
                                                    </div>
                                                    <button type="button" class="btn-remove-employee"
                                                        onclick="clearEmployeeSelection('head')">
                                                        <i class="bi bi-x"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        @error('pro_head')
                                            <div class="error-message">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label required">Project Lead</label>
                                        <input type="hidden" name="pro_lead" id="pro_lead"
                                            value="{{ old('pro_lead', $project->pro_lead) }}">
                                        <div class="search-wrapper">
                                            <div class="input-group">
                                                <input type="text" class="form-control search-input"
                                                    id="lead_search" placeholder="Search lead by name or ID">
                                                <button class="btn search-btn" type="button"
                                                    id="lead_search_button">
                                                    <i class="bi bi-search"></i>
                                                </button>
                                            </div>
                                            <ul id="lead_results" class="search-results"></ul>

                                            <div class="selected-employee" id="lead_display"
                                                style="{{ old('pro_lead', $project->pro_lead) ? 'display: block;' : 'display: none;' }}">
                                                <div class="selected-employee-info">
                                                    <img src="{{ $project->projectLead && $project->projectLead->image ? asset('employee_images/' . $project->projectLead->image) : asset('images/admin_default.jpg') }}"
                                                        class="employee-avatar" id="lead_image">
                                                    <div class="employee-details">
                                                        <div class="employee-name" id="lead_name">
                                                            {{ $project->projectLead ? $project->projectLead->fullname : '' }}
                                                        </div>
                                                        <div class="employee-id" id="lead_details">
                                                            {{ $project->projectLead ? $project->projectLead->employee_id . ' - ' . ($project->projectLead->Departmentid ? $project->projectLead->Departmentid->dep_name : 'No Department') : '' }}
                                                        </div>
                                                    </div>
                                                    <button type="button" class="btn-remove-employee"
                                                        onclick="clearEmployeeSelection('lead')">
                                                        <i class="bi bi-x"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        @error('pro_lead')
                                            <div class="error-message">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="section-header mb-4">
                                <h6 class="section-title">Project Members</h6>
                                <div class="section-divider"></div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label required">Select Team Members</label>

                                        @if ($employees && count($employees) > 0)
                                            <div class="dropdown-checkbox-wrapper">
                                                <button
                                                    class="btn btn-outline-secondary dropdown-toggle w-100 text-start"
                                                    type="button" id="membersDropdown" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <span id="dropdownText">Select team members...</span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-checkbox-menu p-3"
                                                    aria-labelledby="membersDropdown"
                                                    style="width: 100%; max-height: 400px; overflow-y: auto;">
                                                    <div
                                                        class="d-flex justify-content-between align-items-center mb-2">
                                                        <small class="text-muted">Select/Deselect All</small>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="selectAllMembers">
                                                            <label class="form-check-label small"
                                                                for="selectAllMembers">
                                                                Select All
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <hr class="my-2">
                                                    @foreach ($employees as $employee)
                                                        <li class="dropdown-checkbox-item" id="employee_{{ $employee->emp_id }}">
                                                            <div class="form-check">
                                                                <input class="form-check-input member-checkbox-input"
                                                                    type="checkbox" name="pro_member[]"
                                                                    value="{{ $employee->emp_id }}"
                                                                    id="member_{{ $employee->emp_id }}"
                                                                    {{ in_array($employee->emp_id, old('pro_member', $existingMembers)) ? 'checked' : '' }}>
                                                                <label
                                                                    class="form-check-label d-flex align-items-center w-100"
                                                                    for="member_{{ $employee->emp_id }}">
                                                                    <img src="{{ $employee->image ? asset('employee_images/' . $employee->image) : asset('images/admin_default.jpg') }}"
                                                                        class="employee-avatar me-3"
                                                                        style="width: 40px; height: 40px; border-radius: 50%;">
                                                                    <div class="flex-grow-1">
                                                                        <div class="fw-semibold">
                                                                            {{ $employee->fullname }}</div>
                                                                        <small
                                                                            class="text-muted">{{ $employee->employee_id }}
                                                                            • {{ $employee->email }}</small>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                        </li>
                                                        @if (!$loop->last)
                                                            <hr class="my-2">
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @else
                                            <div class="alert alert-warning">
                                                <i class="bi bi-exclamation-triangle me-2"></i>
                                                No employees found.
                                            </div>
                                        @endif

                                        @error('pro_member')
                                            <div class="error-message">{{ $message }}</div>
                                        @enderror

                                        <div class="selected-members-section mt-3">
                                            <label class="sub-label">Selected Team Members</label>
                                            <div id="selected_members" class="selected-members-list">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="section-header mb-4">
    <h6 class="section-title">Email Notification</h6>
    <div class="section-divider"></div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="form-group">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="send_email" name="send_email" value="1">
                <label class="form-check-label" for="send_email">
                    <span class="fw-semibold">Send Email Notification</span>
                    <small class="d-block text-muted">Send project details to project head, lead, and all team members</small>
                </label>
            </div>
        </div>
    </div>
</div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-2"></i>Update Project
                                </button>
                                <a href="{{ route('projectlist') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-lg me-2"></i>Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-switch .form-check-input {
    height: 24px;
    width: 48px;
    cursor: pointer;
}
.form-switch .form-check-input:checked {
    background-color: #3498db;
    border-color: #3498db;
}
.form-switch .form-check-input:focus {
    box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
}
        .status-dates {
            background: #f8f9fa;
            padding: 0.5rem;
            border-radius: 4px;
            border-left: 3px solid #007bff;
        }

        .status-dates small {
            font-size: 0.8rem;
        }

        .avatar-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }

        .attachment-item {
            border: 1px solid #e9ecef;
            border-radius: 8px;
        }

        .file-icon {
            width: 40px;
            text-align: center;
        }

        .selected-files {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
        }

        .files-list {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .file-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.5rem;
            background: white;
            border-radius: 4px;
            border: 1px solid #e9ecef;
        }

        .file-name {
            font-weight: 500;
            color: #2c3e50;
        }

        .file-size {
            color: #6c757d;
            font-size: 0.8rem;
        }

        .btn-remove-file {
            background: none;
            border: none;
            color: #dc3545;
            cursor: pointer;
            padding: 0.25rem;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.08);
        }

        .card-body {
            padding: 2rem;
        }

        .section-header {
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-label.required::after {
            content: " *";
            color: #dc3545;
        }

        .sub-label {
            font-weight: 500;
            color: #6c757d;
            margin-bottom: 0.75rem;
            font-size: 0.85rem;
            display: block;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
        }

        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.1);
        }

        .file-input-wrapper {
            position: relative;
        }

        .file-input {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-info {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
            background: #f8f9fa;
        }

        .file-input-wrapper:hover .file-info {
            border-color: #3498db;
            background: #f1f8ff;
        }

        .file-placeholder {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .form-text {
            color: #6c757d;
            font-size: 0.8rem;
        }

        .search-wrapper {
            position: relative;
        }

        .input-group {
            position: relative;
        }

        .search-input {
            border-right: none;
            border-radius: 8px 0 0 8px;
        }

        .search-btn {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-left: none;
            border-radius: 0 8px 8px 0;
            color: #6c757d;
        }

        .search-btn:hover {
            background: #e9ecef;
            color: #495057;
        }

        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
            margin-top: 2px;
        }

        .employee-item {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f8f9fa;
            cursor: pointer;
            display: flex;
            align-items: center;
        }

        .employee-item:hover {
            background: #f8f9fa;
        }

        .employee-item:last-child {
            border-bottom: none;
        }

        .employee-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 0.75rem;
        }

        .employee-info {
            flex: 1;
        }

        .employee-name {
            font-weight: 500;
            color: #2c3e50;
            font-size: 0.9rem;
            margin-bottom: 0.1rem;
        }

        .employee-meta {
            color: #6c757d;
            font-size: 0.8rem;
        }

        .employee-email {
            color: #3498db;
            font-size: 0.75rem;
            margin-top: 0.1rem;
        }

        .selected-employee {
            margin-top: 0.75rem;
        }

        .selected-employee-info {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            position: relative;
        }

        .btn-remove-employee {
            background: none;
            border: none;
            color: #dc3545;
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            top: 8px;
            right: 8px;
            width: 24px;
            height: 24px;
            font-size: 1.7rem;
        }

        .employee-details {
            flex: 1;
            margin-left: 0.75rem;
        }

        .employee-name {
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.9rem;
            margin-bottom: 0.1rem;
        }

        .employee-id {
            color: #6c757d;
            font-size: 0.8rem;
        }

        .btn-remove-employee:hover {
            background: rgba(220, 53, 69, 0.1);
        }

        .selected-members-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1rem;
        }

        .selected-members-list {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 0.5rem;
        }

        .member-card {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            position: relative;
            min-width: 220px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .member-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .btn-remove-member {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 22px;
            height: 22px;
            border: none;
            border-radius: 50%;
            background-color: transparent;
            color: #dc3545;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
            transition: all 0.2s ease;
        }

        .btn-remove-member:hover {
            background-color: rgba(220, 53, 69, 0.1);
            transform: scale(1.1);
            color: #c82333;
        }

        .member-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 0.75rem;
        }

        .member-info {
            flex: 1;
        }

        .member-name {
            font-weight: 600;
            font-size: 0.95rem;
            color: #2c3e50;
        }

        .member-id {
            font-size: 0.8rem;
            color: #6c757d;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e9ecef;
        }

        .btn {
            border-radius: 8px;
            font-weight: 500;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3498db, #2980b9);
            border: none;
        }

        .btn-primary:hover {
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        }

        .btn-secondary {
            background: #6c757d;
            border: none;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .error-message {
            color: #dc3545;
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }

        .is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            display: block;
            color: #dc3545;
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }

        #membersDropdown {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            background: white;
            color: #495057;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #membersDropdown:hover {
            border-color: #3498db;
            background: white;
        }

        #membersDropdown:focus {
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.1);
            border-color: #3498db;
        }

        .dropdown-checkbox-menu {
            padding: 1rem !important;
        }

        .dropdown-checkbox-menu .form-check-input {
            cursor: pointer;
        }

        .dropdown-checkbox-menu .form-check-label {
            cursor: pointer;
            width: 100%;
        }

        .dropdown-checkbox-menu {
            pointer-events: auto !important;
        }

        .dropdown-checkbox-menu * {
            pointer-events: auto !important;
        }

        .employee-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .member-department {
            color: #3498db;
            font-size: 0.8rem;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 1.5rem;
            }

            .selected-members-list {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let selectedMembers = new Set();
            const selectedMembersDiv = document.getElementById('selected_members');
            const dropdownText = document.getElementById('dropdownText');
            const selectAllCheckbox = document.getElementById('selectAllMembers');
            const memberCheckboxes = document.querySelectorAll('.member-checkbox-input');

            @if (!empty($existingMembers))
                const existingMembers = {!! json_encode($existingMembers) !!};

                memberCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
                selectedMembers.clear();

                existingMembers.forEach(memberId => {
                    const memberIdStr = memberId.toString();
                    selectedMembers.add(memberIdStr);

                    const checkbox = document.querySelector(
                        `.member-checkbox-input[value="${memberIdStr}"]`);
                    if (checkbox) {
                        checkbox.checked = true;
                    }
                });
            @endif

            @if (old('pro_member'))
                const oldMembers = {!! json_encode(old('pro_member')) !!};

                selectedMembers.clear();
                memberCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });

                oldMembers.forEach(memberId => {
                    const memberIdStr = memberId.toString();
                    selectedMembers.add(memberIdStr);
                    const checkbox = document.querySelector(
                        `.member-checkbox-input[value="${memberIdStr}"]`);
                    if (checkbox) {
                        checkbox.checked = true;
                    }
                });
            @endif

            document.querySelectorAll('.dropdown-checkbox-item .form-check-label').forEach(label => {
                label.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const checkbox = this.querySelector('.member-checkbox-input');
                    if (checkbox) {
                        checkbox.checked = !checkbox.checked;
                        checkbox.dispatchEvent(new Event('change'));
                    }
                });
            });

            function updateExcludedEmployees() {
                const projectHead = document.getElementById('pro_head').value;
                const projectLead = document.getElementById('pro_lead').value;

                const excludeIds = [];
                if (projectHead) excludeIds.push(projectHead);
                if (projectLead) excludeIds.push(projectLead);

                memberCheckboxes.forEach(checkbox => {
                    const listItem = checkbox.closest('.dropdown-checkbox-item');
                    if (excludeIds.includes(checkbox.value)) {
                        listItem.style.display = 'none';
                        if (checkbox.checked) {
                            checkbox.checked = false;
                            selectedMembers.delete(checkbox.value);
                        }
                    } else {
                        listItem.style.display = 'block';
                    }
                });

                updateSelectionDisplay();
            }

            function updateSelectionDisplay() {
                const selectedCount = selectedMembers.size;

                if (selectedCount === 0) {
                    dropdownText.textContent = 'Select team members...';
                } else if (selectedCount === 1) {
                    dropdownText.textContent = '1 member selected';
                } else {
                    dropdownText.textContent = `${selectedCount} members selected`;
                }

                updateSelectedMembersDisplay();
                updateSelectAllCheckbox();
            }

            function updateSelectedMembersDisplay() {
                if (!selectedMembersDiv) return;

                selectedMembersDiv.innerHTML = '';

                if (selectedMembers.size === 0) {
                    selectedMembersDiv.innerHTML = '<div class="text-muted">No members selected</div>';
                    return;
                }

                selectedMembers.forEach(memberId => {
                    const checkbox = document.querySelector(`.member-checkbox-input[value="${memberId}"]`);

                    if (checkbox) {
                        const label = checkbox.parentElement.querySelector('.form-check-label');
                        if (label) {
                            const avatar = label.querySelector('.employee-avatar')?.src ||
                                '{{ asset('images/admin_default.jpg') }}';
                            const name = label.querySelector('.fw-semibold')?.textContent ||
                                'Unknown Member';
                            const employeeIdText = label.querySelector('.text-muted')?.textContent || '';
                            const employeeId = employeeIdText.split('•')[0]?.trim() || 'Unknown ID';

                            const memberCard = document.createElement('div');
                            memberCard.className = 'member-card';
                            memberCard.innerHTML = `
                                <img src="${avatar}" class="member-avatar" alt="${name}" onerror="this.src='{{ asset('images/admin_default.jpg') }}'">
                                <div class="member-info">
                                    <div class="member-name">${name}</div>
                                    <div class="member-id">${employeeId}</div>
                                </div>
                                <button type="button" class="btn-remove-member" onclick="removeMember('${memberId}')">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            `;
                            selectedMembersDiv.appendChild(memberCard);
                        } else {
                            createFallbackCard(memberId);
                        }
                    } else {
                        createFallbackCard(memberId);
                    }
                });
            }

            function createFallbackCard(memberId) {
                const memberCard = document.createElement('div');
                memberCard.className = 'member-card';
                memberCard.innerHTML = `
                    <img src="{{ asset('images/admin_default.jpg') }}" class="member-avatar" alt="Member">
                    <div class="member-info">
                        <div class="member-name">Member ID: ${memberId}</div>
                        <div class="member-id">Details not found</div>
                    </div>
                    <button type="button" class="btn-remove-member" onclick="removeMember('${memberId}')">
                        <i class="bi bi-x-lg"></i>
                    </button>
                `;
                selectedMembersDiv.appendChild(memberCard);
            }

            function updateSelectAllCheckbox() {
                if (!selectAllCheckbox) return;

                const visibleCheckboxes = Array.from(memberCheckboxes).filter(cb =>
                    cb.closest('.dropdown-checkbox-item').style.display !== 'none'
                );

                if (selectedMembers.size === 0) {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = false;
                } else if (selectedMembers.size === visibleCheckboxes.length) {
                    selectAllCheckbox.checked = true;
                    selectAllCheckbox.indeterminate = false;
                } else {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = true;
                }
            }

            memberCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    if (this.checked) {
                        selectedMembers.add(this.value);
                    } else {
                        selectedMembers.delete(this.value);
                    }
                    updateSelectionDisplay();
                });
            });

            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    const visibleCheckboxes = Array.from(memberCheckboxes).filter(cb =>
                        cb.closest('.dropdown-checkbox-item').style.display !== 'none'
                    );

                    visibleCheckboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                        if (this.checked) {
                            selectedMembers.add(checkbox.value);
                        } else {
                            selectedMembers.delete(checkbox.value);
                        }
                    });
                    updateSelectionDisplay();
                });
            }

            window.removeMember = function(memberId) {
                selectedMembers.delete(memberId);

                const checkbox = document.querySelector(`.member-checkbox-input[value="${memberId}"]`);
                if (checkbox) {
                    checkbox.checked = false;
                }

                updateSelectionDisplay();
            };

            const dropdownMenu = document.querySelector('.dropdown-checkbox-menu');
            if (dropdownMenu) {
                dropdownMenu.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }

            setTimeout(() => {
                updateSelectionDisplay();
                updateExcludedEmployees();
            }, 100);

            const avatarInput = document.getElementById('pro_avater');
            const avatarInfo = document.querySelector('#pro_avater + .file-info .file-placeholder');

            if (avatarInput && avatarInfo) {
                avatarInput.addEventListener('change', function() {
                    if (this.files.length > 0) {
                        avatarInfo.textContent = this.files[0].name;
                    } else {
                        avatarInfo.textContent =
                            '{{ $project->pro_avater ? 'Choose new file to replace current avatar' : 'No File chosen' }}';
                    }
                });
            }

            const attachmentInput = document.getElementById('pro_attachment');
            const attachmentInfo = document.querySelector('#pro_attachment + .file-info .file-placeholder');
            const selectedFilesDiv = document.getElementById('selectedFiles');
            const filesListDiv = document.getElementById('filesList');
            if (attachmentInput && attachmentInfo && selectedFilesDiv && filesListDiv) {
                attachmentInput.addEventListener('change', function() {
                    if (this.files.length > 0) {
                        attachmentInfo.textContent = `${this.files.length} file(s) selected`;
                        selectedFilesDiv.style.display = 'block';
                        filesListDiv.innerHTML = '';

                        Array.from(this.files).forEach((file, index) => {
                            const fileItem = document.createElement('div');
                            fileItem.className = 'file-item';
                            fileItem.innerHTML = `
                    <div>
                        <div class="file-name">${file.name}</div>
                        <div class="file-size">${formatFileSize(file.size)}</div>
                    </div>
                    <button type="button" class="btn-remove-file" onclick="removeSelectedFile(${index})">
                        <i class="bi bi-x"></i>
                    </button>
                `;
                            filesListDiv.appendChild(fileItem);
                        });
                    } else {
                        attachmentInfo.textContent = 'Choose additional files';
                        selectedFilesDiv.style.display = 'none';
                    }
                });
            }

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            window.removeSelectedFile = function(index) {
                const dt = new DataTransfer();
                const input = document.getElementById('pro_attachment');

                for (let i = 0; i < input.files.length; i++) {
                    if (i !== index) {
                        dt.items.add(input.files[i]);
                    }
                }

                input.files = dt.files;
                input.dispatchEvent(new Event('change'));
            };

            const removeAvatarCheckbox = document.getElementById('remove_avater');
            if (removeAvatarCheckbox) {
                removeAvatarCheckbox.addEventListener('change', function() {
                    if (avatarInput) {
                        if (this.checked) {
                            avatarInput.disabled = true;
                            if (avatarInfo) avatarInfo.textContent = 'Avatar will be removed';
                        } else {
                            avatarInput.disabled = false;
                            if (avatarInfo) avatarInfo.textContent =
                                'Choose new file to replace current avatar';
                        }
                    }
                });
            }

            const statusSelect = document.getElementById('pro_status');
            if (statusSelect) {
                statusSelect.addEventListener('change', function() {
                    if (this.value == 2) {
                        if (confirm(
                                'Are you sure you want to mark this project as completed? This will set the completion date and calculate overdue days.'
                            )) {
                        } else {
                            this.value = '{{ old('pro_status', $project->pro_status) }}';
                        }
                    }
                });
            }

      // Replace the setupEmployeeSearch function with this updated version
function setupEmployeeSearch(searchInputId, searchButtonId, resultsListId,
    hiddenInputId, displayDivId, imageId, nameId, detailsId, selectionType, searchType = 'employee') {

    const searchInput = document.getElementById(searchInputId);
    const searchButton = document.getElementById(searchButtonId);
    const resultsList = document.getElementById(resultsListId);
    const hiddenInput = document.getElementById(hiddenInputId);
    const displayDiv = document.getElementById(displayDivId);
    const displayImage = document.getElementById(imageId);
    const displayName = document.getElementById(nameId);
    const displayDetails = document.getElementById(detailsId);

    // Function to restore selection for client specifically
    if (searchType === 'client' && hiddenInput.value) {
        restoreClientSelection(hiddenInput.value);
    }

    function restoreClientSelection(clientId) {
        if (!clientId) return;

        fetch(`{{ route('project.search') }}?term=&type=client`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            const client = data.find(item => item.id == clientId);
            if (client) {
                displayImage.src = client.image ?
                    '/client_images/' + client.image :
                    '/images/admin_default.jpg';
                displayName.textContent = client.fullname;
                displayDetails.textContent = `CL-${client.id} - ${client.email}`;
                displayDiv.style.display = 'block';
            }
        })
        .catch(error => console.error('Error restoring client:', error));
    }

    function searchEmployees() {
        const searchTerm = searchInput.value.trim();
        const excludeIds = [];

        if (searchType === 'employee') {
            if (selectionType === 'head') {
                const leadValue = document.getElementById('pro_lead').value;
                if (leadValue) excludeIds.push(leadValue);
            } else if (selectionType === 'lead') {
                const headValue = document.getElementById('pro_head').value;
                if (headValue) excludeIds.push(headValue);
            }
        }

        if (searchTerm.length < 1) {
            resultsList.style.display = 'none';
            return;
        }

        let url = `{{ route('project.search') }}?term=${encodeURIComponent(searchTerm)}&type=${searchType}`;
        if (excludeIds.length > 0) {
            url += `&exclude_ids=${excludeIds.join(',')}`;
        }

        fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => {
                if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                return response.json();
            })
            .then(data => {
                resultsList.innerHTML = '';

                if (data.length === 0) {
                    resultsList.innerHTML = '<li class="employee-item">No results found</li>';
                } else {
                    data.forEach(item => {
                        const isClient = searchType === 'client';
                        const itemId = isClient ? item.id : item.emp_id;
                        const fullname = isClient ? item.fullname : item.fullname;
                        const employeeId = isClient ? item.employee_id : item.employee_id;
                        const department = isClient ? 'Client' : (item.department?.dep_name || 'No Department');
                        const email = isClient ? item.email : '';
                        const image = item.image ?
                            (isClient ? '/client_images/' + item.image : '/employee_images/' + item.image) :
                            '/images/admin_default.jpg';

                        const listItem = document.createElement('li');
                        listItem.className = 'employee-item';
                        listItem.innerHTML = `
                            <img src="${image}" class="employee-avatar">
                            <div class="employee-info">
                                <div class="employee-name">${fullname}</div>
                                <div class="employee-meta">${employeeId} - ${department}</div>
                                ${isClient ? `<div class="employee-email">${item.email}</div>` : ''}
                            </div>
                        `;
                        listItem.addEventListener('click', () => {
                            hiddenInput.value = itemId;
                            displayImage.src = image;
                            displayName.textContent = fullname;

                            if (isClient) {
                                displayDetails.textContent = `${employeeId} - ${email}`;
                            } else {
                                displayDetails.textContent = `${employeeId} - ${department}`;
                            }

                            displayDiv.style.display = 'block';
                            resultsList.style.display = 'none';
                            searchInput.value = '';

                            if (searchType === 'employee') {
                                updateExcludedEmployees();
                            }
                        });
                        resultsList.appendChild(listItem);
                    });
                }
                resultsList.style.display = 'block';
            })
            .catch(error => {
                console.error('Search error:', error);
                resultsList.innerHTML =
                    `<li class="employee-item text-danger">Error: ${error.message}</li>`;
                resultsList.style.display = 'block';
            });
    }

    searchInput.addEventListener('input', searchEmployees);
    searchButton.addEventListener('click', searchEmployees);

    document.addEventListener('click', function(e) {
        if (!resultsList.contains(e.target) && e.target !== searchInput && e.target !== searchButton) {
            resultsList.style.display = 'none';
        }
    });
}
            setupEmployeeSearch(
                'client_search', 'client_search_button', 'client_results',
                'pro_client', 'client_display', 'client_image', 'client_name', 'client_details', 'single',
                'client'
            );

            setupEmployeeSearch(
                'lead_search', 'lead_search_button', 'lead_results',
                'pro_lead', 'lead_display', 'lead_image', 'lead_name', 'lead_details', 'single', 'employee'
            );

            setupEmployeeSearch(
                'head_search', 'head_search_button', 'head_results',
                'pro_head', 'head_display', 'head_image', 'head_name', 'head_details', 'single', 'employee'
            );

            const projectForm = document.getElementById('projectForm');
            if (projectForm) {
                projectForm.addEventListener('submit', function(e) {
                    const today = new Date().toISOString().split('T')[0];
                    const deadline = document.getElementById('pro_deadline').value;

                    if (deadline && deadline < today) {
                        e.preventDefault();
                        Toastify({
            text: "Project deadline cannot be in the past.",
            duration: 4000,
            close: true,
            gravity: "top",
            position: "right",
            backgroundColor: "#EF4444",
            offset: {
                y: 65
            },
            style: {
                boxShadow: "0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)",
                borderRadius: "8px",
                fontFamily: "'Inter', sans-serif",
                fontSize: "14px",
                width: "300px"
            },
            stopOnFocus: true,
        }).showToast();
                        return;
                    }

                    if (selectedMembers.size === 0) {
                        e.preventDefault();

                         Toastify({
            text: "Please add at least one project member.",
            duration: 4000,
            close: true,
            gravity: "top",
            position: "right",
            backgroundColor: "#EF4444",
            offset: {
                y: 65
            },
            style: {
                boxShadow: "0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)",
                borderRadius: "8px",
                fontFamily: "'Inter', sans-serif",
                fontSize: "14px",
                width: "300px"
            },
            stopOnFocus: true,
        }).showToast();
                        return;
                    }
                });
            }

            window.clearEmployeeSelection = function(type) {
                const hiddenInput = document.getElementById(`pro_${type}`);
                const displayDiv = document.getElementById(`${type}_display`);

                if (hiddenInput) hiddenInput.value = '';
                if (displayDiv) displayDiv.style.display = 'none';

                if (type === 'head' || type === 'lead') {
                    updateExcludedEmployees();
                }
            };
        });
    </script>
</x-kanbandashboardlayout>
