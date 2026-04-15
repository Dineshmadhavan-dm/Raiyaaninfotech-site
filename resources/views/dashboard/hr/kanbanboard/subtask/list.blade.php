<x-kanbandashboardlayout>
    <div class="mt-5">
        <div class="container-fluid px-4">
            <!-- Header & Breadcrumb -->
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
                                <i class="bi bi-check2-square me-2"></i>Subtask
                            </li>
                        </ol>
                    </nav>
                </div>

                <div>
                    <a href="{{ route('tasklist') }}" class="btn btn-outline-primary me-2">
                        <i class="bi bi-arrow-left-circle me-2"></i>Back To Task
                    </a>
                </div>
            </div>

            <x-message />

            <!-- Filter Section -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-3">
                    <div class="row justify-content-between px-3 pb-3">
                        <!-- Filter Controls -->
                        <div class="col-auto p-3">
                            <div class="row justify-content-start">
                                <!-- Status Filter -->
                                <div class="col-auto" style="margin-top: 1.3em;">
                                    <div class="dropdown">
                                        <button class="dropdown-toggle control-select" type="button" id="statusDropdown"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <span id="statusFilterText">All Status</span>
                                        </button>
                                        <ul class="dropdown-menu status-dropdown p-2" aria-labelledby="statusDropdown"
                                            style="width: 150px;">
                                            <li>
                                                <select id="statusFilter" class="form-select form-select-sm" size="5"
                                                    style="width: 100%; border: none;">
                                                    <option value="">All Status</option>
                                                    <option value="0">Created</option>
                                                    <option value="1">On Progress</option>
                                                    <option value="2">Completed</option>
                                                </select>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Priority Filter -->
                                <div class="col-auto" style="margin-top: 1.3em;">
                                    <div class="dropdown">
                                        <button class="dropdown-toggle control-select" type="button"
                                            id="priorityDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span id="priorityFilterText">All Priority</span>
                                        </button>
                                        <ul class="dropdown-menu priority-dropdown p-2"
                                            aria-labelledby="priorityDropdown" style="width: 150px;">
                                            <li>
                                                <select id="priorityFilter" class="form-select form-select-sm" size="5"
                                                    style="width: 100%; border: none;">
                                                    <option value="">All Priority</option>
                                                    <option value="1">Low</option>
                                                    <option value="2">Medium</option>
                                                    <option value="3">High</option>
                                                </select>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Task Filter -->
                                <div class="col-auto" style="margin-top: 1.3em;">
                                    <div class="dropdown">
                                        <button class="dropdown-toggle control-select" type="button" id="taskDropdown"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <span id="taskFilterText">All Tasks</span>
                                        </button>
                                        <ul class="dropdown-menu task-dropdown p-2" aria-labelledby="taskDropdown"
                                            style="width: 250px;">
                                            <li>
                                                <input type="text" class="form-control form-control-sm mb-2"
                                                    placeholder="Search tasks..." id="taskSearch">
                                            </li>
                                            <li>
                                                <select id="taskFilter" class="form-select form-select-sm" size="8"
                                                    style="width: 100%; border: none;">
                                                    <option value="">All Tasks</option>
                                                    @foreach ($subtasks->pluck('task')->unique() as $task)
                                                        @if ($task)
                                                            <option value="{{ $task->task_id }}">
                                                                {{ $task->task_name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Access Mode Filter -->
                                <div class="col-auto" style="margin-top: 1.3em;">
                                    <div class="dropdown">
                                        <button class="dropdown-toggle control-select" type="button" id="accessDropdown"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <span id="accessFilterText">All Access</span>
                                        </button>
                                        <ul class="dropdown-menu access-dropdown p-2" aria-labelledby="accessDropdown"
                                            style="width: 150px;">
                                            <li>
                                                <select id="accessFilter" class="form-select form-select-sm" size="5"
                                                    style="width: 100%; border: none;">
                                                    <option value="">All Access</option>
                                                    <option value="public">Public</option>
                                                    <option value="private">Private</option>
                                                </select>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Date Filter -->
                                <div class="col-auto" style="margin-top: 1.3em;">
                                    <div class="dropdown">
                                        <button class="dropdown-toggle control-select" type="button" id="dateDropdown"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <span id="dateFilterText">All Dates</span>
                                        </button>
                                        <ul class="dropdown-menu date-dropdown p-2" aria-labelledby="dateDropdown"
                                            style="width: 300px;">
                                            <li>
                                                <div class="mb-2">
                                                    <label class="small text-muted">Created Date</label>
                                                    <input type="date" id="createdDateFilter"
                                                        class="form-control form-control-sm">
                                                </div>
                                                <div>
                                                    <label class="small text-muted">Deadline Date</label>
                                                    <input type="date" id="deadlineDateFilter"
                                                        class="form-control form-control-sm">
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Clear Filters Button -->
                                <div class="col-auto" style="margin-top: 1.3em; display: none;"
                                    id="clearFiltersBtnContainer">
                                    <button class="btn btn-outline-dark" id="clearFiltersBtn">
                                        <i class="bi bi-x-circle me-1"></i> Clear Filters
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Subtasks Grid -->
                    <div class="row" id="subtasksGrid">
                        @forelse($subtasks as $subtask)
                            @php
                                // Calculate overdue properly with POSITIVE values
                                $today = \Carbon\Carbon::now();
                                $deadline = $subtask->stask_deadline
                                    ? \Carbon\Carbon::parse($subtask->stask_deadline)
                                    : null;

                                $overdueDays = 0;
                                $overdueText = 'On Time';
                                $isOverdue = false;

                                if ($deadline) {
                                    if ($subtask->stask_status === 2 && $subtask->stask_complete) {
                                        // For completed subtasks: compare completion date with deadline
                                        $completionDate = $subtask->stask_complete
                                            ? \Carbon\Carbon::parse($subtask->stask_complete)
                                            : $today;

                                        // Calculate POSITIVE overdue days
                                        if ($completionDate->gt($deadline)) {
                                            $overdueDays = $deadline->diffInDays($completionDate); // POSITIVE value
                                            $isOverdue = true;
                                        }
                                    } else {
                                        // For ongoing subtasks: compare current date with deadline
                                        if ($today->gt($deadline)) {
                                            $overdueDays = $deadline->diffInDays($today); // POSITIVE value
                                            $isOverdue = true;
                                        }
                                    }

                                    // Format overdue text
                                    if ($overdueDays === 0) {
                                        $overdueText = 'On Time';
                                    } elseif ($overdueDays < 30) {
                                        $overdueText = $overdueDays . ' day' . ($overdueDays > 1 ? 's' : '') . ' overdue';
                                    } else {
                                        $months = floor($overdueDays / 30);
                                        $remainingDays = $overdueDays % 30;
                                        if ($remainingDays === 0) {
                                            $overdueText = $months . ' month' . ($months > 1 ? 's' : '') . ' overdue';
                                        } else {
                                            $overdueText =
                                                $months .
                                                ' month' .
                                                ($months > 1 ? 's' : '') .
                                                ' ' .
                                                $remainingDays .
                                                ' day' .
                                                ($remainingDays > 1 ? 's' : '') .
                                                ' overdue';
                                        }
                                    }
                                }

                                // Process attachments from PMTS
                                $subtaskFiles = [];
                                $pmtsImages = $subtask->pmtsImages;

                                if ($pmtsImages->count() > 0) {
                                    foreach ($pmtsImages as $pmtsImage) {
                                        $subtaskFiles[] = [
                                            'url' => asset('subtask_attachments/' . $pmtsImage->pmtsimage_name),
                                            'name' => $pmtsImage->pmtsimage_name,
                                            'type' => pathinfo($pmtsImage->pmtsimage_name, PATHINFO_EXTENSION),
                                        ];
                                    }
                                }

                                // Get subtask avatar
                                $subtaskAvatar = null;
                                if ($subtask->stask_avater) {
                                    $subtaskAvatar = asset($subtask->stask_avater);
                                } else {
                                    // Check if there are any PMTS images that could be used as avatar
                                    foreach ($pmtsImages as $pmtsImage) {
                                        $extension = pathinfo($pmtsImage->pmtsimage_name, PATHINFO_EXTENSION);
                                        if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                                            $subtaskAvatar = asset('subtask_attachments/' . $pmtsImage->pmtsimage_name);
                                            break;
                                        }
                                    }
                                }

                                // Parse member data
                                $memberNames = [];
                                try {
                                    if ($subtask->stask_member) {
                                        $memberData = $subtask->stask_member;
                                        $memberIds = [];

                                        if (is_string($memberData)) {
                                            $decoded = json_decode($memberData, true);
                                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                                $memberIds = $decoded;
                                            }
                                        } elseif (is_array($memberData)) {
                                            $memberIds = $memberData;
                                        }

                                        if (!empty($memberIds)) {
                                            $members = \App\Models\Employee::whereIn('emp_id', $memberIds)->get(
                                                ['fullname'],
                                            );
                                            $memberNames = $members->pluck('fullname')->toArray();
                                        }
                                    }
                                } catch (\Exception $e) {
                                    \Log::error('Error parsing subtask members: ' . $e->getMessage());
                                }
                            @endphp

                            <div class="col-xl-4 col-lg-6 col-md-6 mb-4 subtask-card"
                                data-subtask-id="{{ $subtask->stask_id }}"
                                data-status="{{ $subtask->stask_status }}"
                                data-priority="{{ $subtask->stask_priority }}"
                                data-created-date="{{ \Carbon\Carbon::parse($subtask->created_at)->format('Y-m-d') }}"
                                data-deadline-date="{{ $deadline ? $deadline->format('Y-m-d') : '' }}"
                                data-task="{{ $subtask->stask_task }}"
                                data-access="{{ $subtask->stask_accessmod }}"
                                data-overdue="{{ $overdueDays }}">

                                <div class="card border-0 shadow-sm h-100">
                                    <!-- Subtask Image -->
                                    <div class="project-image-container position-relative">
                                        @if ($subtaskAvatar)
                                            <img src="{{ $subtaskAvatar }}" class="card-img-top project-image"
                                                alt="{{ $subtask->stask_name }}" style="height: 150px; object-fit: cover;">
                                        @else
                                            <div class="card-img-top project-image-placeholder d-flex align-items-center justify-content-center"
                                                style="height: 150px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                <i class="bi bi-check2-square text-white" style="font-size: 3rem;"></i>
                                            </div>
                                        @endif
                                        <div class="project-overlay">
                                            <h5 class="project-title">{{ $subtask->stask_name }}</h5>
                                            <!-- Status Badge -->
                                            <span class="badge @if ($subtask->stask_status == 1 || $subtask->stask_status == 2) {{ $subtask->status_badge_class }} @endif position-absolute top-0 end-0 m-2">
                                                @if ($subtask->stask_status == 1 || $subtask->stask_status == 2)
                                                    {{ $subtask->status_text }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <!-- Subtask Info -->
                                        <div class="actstrip">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <small class="fw-medium">Project
                                                        <span class="badge {{ $subtask->task->modulo->project->pro_accessmod ? 'bg-warning' : 'bg-info' }}">
                                                            {{ $subtask->task->modulo->project->pro_accessmod ? 'Private' : 'Public' }}
                                                        </span>
                                                    </small>
                                                    <div class="project-name">
                                                        {{ $subtask->task && $subtask->task->modulo && $subtask->task->modulo->project ? $subtask->task->modulo->project->pro_name : 'N/A' }}
                                                    </div>
                                                </div>

                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                        type="button" data-bs-toggle="dropdown">
                                                        <i class="bi bi-three-dots"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('subtaskedit', $subtask->stask_id) }}">
                                                                <i class="bi bi-pencil me-2"></i>Edit
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <button class="dropdown-item text-danger delete-subtask"
                                                                data-subtask-id="{{ $subtask->stask_id }}"
                                                                data-subtask-name="{{ $subtask->stask_name }}">
                                                                <i class="bi bi-trash me-2"></i>Delete
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <!-- Module Reference -->
                                            <div class="mb-2">
                                                <small class="fw-medium">Module
                                                    <span class="badge {{ $subtask->task->modulo->mod_accessmod ? 'bg-warning' : 'bg-info' }}">
                                                        {{ $subtask->task->modulo->mod_accessmod ? 'Private' : 'Public' }}
                                                    </span>
                                                </small>
                                                <div class="project-name">
                                                    {{ $subtask->task && $subtask->task->modulo ? $subtask->task->modulo->mod_name : 'N/A' }}
                                                </div>
                                            </div>

                                            <!-- Task Reference -->
                                            <div class="mb-2">
                                                <small class="fw-medium">Task
                                                    <span class="badge {{ $subtask->task->task_accessmod ? 'bg-warning' : 'bg-info' }}">
                                                        {{ $subtask->task->task_accessmod ? 'Private' : 'Public' }}
                                                    </span>
                                                    <span class="badge {{ $subtask->task->priority_class }}">
                                                        {{ $subtask->task->priority_text }}
                                                    </span>
                                                </small>
                                                <div class="project-name">
                                                    {{ $subtask->task ? $subtask->task->task_name : 'N/A' }}
                                                </div>
                                            </div>

                                            <!-- Subtask Reference -->
                                            <div class="mb-2">
                                                <small class="fw-medium">Subtask
                                                    <span class="badge {{ $subtask->stask_accessmod ? 'bg-warning' : 'bg-info' }}">
                                                        {{ $subtask->stask_accessmod ? 'Private' : 'Public' }}
                                                    </span>
                                                    <span class="badge {{ $subtask->priority_class }}">
                                                        {{ $subtask->priority_text }}
                                                    </span>
                                                </small>
                                                <div class="project-name">
                                                    {{ $subtask->stask_name }}
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Subtask Details -->
                                        <div class="project-meta">
                                            <!-- Dates Section -->
                                            <div class="dates-section mb-3">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <small class="text-muted d-block">Created</small>
                                                        <small class="text-muted">
                                                            <i class="bi bi-calendar me-1"></i>
                                                            {{ \Carbon\Carbon::parse($subtask->created_at)->format('d-m-Y') }}
                                                        </small>
                                                    </div>
                                                    <div class="col-6">
                                                        <small class="text-muted d-block">Deadline</small>
                                                        <small class="fw-semibold {{ $isOverdue ? 'text-danger' : 'text-dark' }}">
                                                            <i class="bi bi-calendar-x me-1"></i>
                                                            {{ $deadline ? $deadline->format('d-m-Y') : 'No deadline' }}
                                                        </small>
                                                    </div>
                                                </div>

                                                <!-- Status Dates -->
                                                <div class="row mt-2 status-datebar">
                                                    <!-- On progress Column -->
                                                    <div class="col-md-6 col-12">
                                                        @if ($subtask->stask_onprogress)
                                                            <small class="text-muted d-block">On Progress</small>
                                                            <small class="fw-semibold text-info">
                                                                <i class="bi bi-play-circle me-1"></i>
                                                                {{ \Carbon\Carbon::parse($subtask->stask_onprogress)->format('d-m-Y') }}
                                                            </small>
                                                        @endif
                                                    </div>

                                                    <!-- Completed Column -->
                                                    <div class="col-md-6 col-12">
                                                        @if ($subtask->stask_complete)
                                                            <small class="text-muted d-block">Completed</small>
                                                            <small class="fw-semibold {{ $isOverdue ? 'text-danger' : 'text-success' }}">
                                                                <i class="bi bi-check-circle me-1"></i>
                                                                {{ \Carbon\Carbon::parse($subtask->stask_complete)->format('d-m-Y') }}
                                                            </small>
                                                            @if ($isOverdue)
                                                                <br>
                                                                <small class="text-danger ms-4">
                                                                    Overdue: {{ $overdueText }}
                                                                </small>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Team Members -->
                                            @if (!empty($memberNames))
                                                <div class="mb-2 mt-4">
                                                    <small class="text-muted">Team Members</small>
                                                    <div class="team-members-overlap mt-2">
                                                        @php
                                                            $displayMembers = array_slice($memberNames, 0, 4);
                                                            $remainingCount = count($memberNames) - count($displayMembers);
                                                        @endphp

                                                        @foreach ($displayMembers as $index => $memberName)
                                                            @php
                                                                $member = \App\Models\Employee::where('fullname', $memberName)->first();
                                                                $zIndex = 10 - $index;
                                                                $marginLeft = $index > 0 ? '-10px' : '0';
                                                            @endphp
                                                            @if ($member)
                                                                <div class="team-member-overlap"
                                                                    style="z-index: {{ $zIndex }}; margin-left: {{ $marginLeft }};"
                                                                    data-bs-toggle="tooltip" title="{{ $member->fullname }}">
                                                                    @if ($member->image)
                                                                        <img src="{{ asset('employee_images/' . $member->image) }}"
                                                                            alt="{{ $member->fullname }}"
                                                                            class="rounded-circle object-fit-cover" width="35" height="35">
                                                                    @else
                                                                        <!-- Grey initial placeholder -->
                                                                        <div class="rounded-circle bg-secondary-member text-white d-flex align-items-center justify-content-center"
                                                                            style="width: 35px; height: 35px; font-size: 0.8rem; font-weight: 600;">
                                                                            {{ substr($member->fullname, 0, 1) }}
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        @endforeach

                                                        @if ($remainingCount > 0)
                                                            <div class="team-member-more-overlap"
                                                                style="z-index: 1; margin-left: -10px;" data-bs-toggle="tooltip"
                                                                title="{{ $remainingCount }} more members">
                                                                <div class="rounded-circle bg-dark text-white d-flex align-items-center justify-content-center"
                                                                    style="width: 35px; height: 35px; font-size: 0.8rem; font-weight: 600;">
                                                                    +{{ $remainingCount }}
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @else
                                                <div class="mb-2 mt-4">
                                                    <small class="text-muted">Team Members</small>
                                                    <div class="mt-2">
                                                        <span class="text-muted small">No members assigned</span>
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Attachments Section -->
                                            @if (!empty($subtaskFiles))
                                                <div class="mb-3">
                                                    <small class="text-muted d-block mb-2">Attachments</small>
                                                    <div class="attachments-preview">
                                                        @php
                                                            $displayFiles = array_slice($subtaskFiles, 0, 3);
                                                            $remainingFiles = count($subtaskFiles) - count($displayFiles);
                                                        @endphp

                                                        @foreach ($displayFiles as $file)
                                                            <div class="attachment-item-preview">
                                                                @php
                                                                    $fileExtension = strtolower($file['type'] ?? '');
                                                                    $isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);

                                                                    // Determine icon and color based on file extension
                                                                    $icon = 'bi-file-earmark';
                                                                    $colorClass = 'file-default';

                                                                    if ($isImage) {
                                                                        $icon = 'bi-image';
                                                                        $colorClass = 'file-image';
                                                                    } elseif ($fileExtension === 'pdf') {
                                                                        $icon = 'bi-file-earmark-pdf';
                                                                        $colorClass = 'file-pdf';
                                                                    } elseif (in_array($fileExtension, ['doc', 'docx'])) {
                                                                        $icon = 'bi-file-earmark-word';
                                                                        $colorClass = 'file-doc';
                                                                    } elseif (in_array($fileExtension, ['xls', 'xlsx'])) {
                                                                        $icon = 'bi-file-earmark-excel';
                                                                        $colorClass = 'file-xls';
                                                                    } elseif (in_array($fileExtension, ['ppt', 'pptx'])) {
                                                                        $icon = 'bi-file-earmark-ppt';
                                                                        $colorClass = 'file-ppt';
                                                                    } elseif (in_array($fileExtension, ['zip', 'rar', '7z'])) {
                                                                        $icon = 'bi-file-earmark-zip';
                                                                        $colorClass = 'file-zip';
                                                                    }
                                                                @endphp

                                                                @if ($isImage)
                                                                    <img src="{{ $file['url'] }}" alt="{{ $file['name'] }}"
                                                                        class="attachment-thumbnail" data-bs-toggle="tooltip"
                                                                        title="{{ $file['name'] }}">
                                                                @else
                                                                    <div class="file-icon-preview {{ $colorClass }}"
                                                                        data-bs-toggle="tooltip" title="{{ $file['name'] }}">
                                                                        <i class="bi {{ $icon }}"></i>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endforeach

                                                        @if ($remainingFiles > 0)
                                                            <div class="attachment-more-preview" data-bs-toggle="tooltip"
                                                                title="{{ $remainingFiles }} more files">
                                                                +{{ $remainingFiles }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="card-footer bg-transparent border-top-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="col-auto">
                                                @if (!empty($subtaskFiles))
                                                    <button class="btn btn-sm btn-outline-primary view-attachments mt-1"
                                                        data-subtask-name="{{ $subtask->stask_name }}"
                                                        data-subtask-attachments='{{ json_encode($subtaskFiles) }}'>
                                                        <i class="bi bi-eye me-1"></i>View Attachments
                                                    </button>
                                                @endif
                                            </div>

                                            <div class="col-auto">
                                                <div class="d-flex gap-2">
                                                    <button class="btn btn-sm btn-outline-primary view-subtask"
                                                        data-subtask-id="{{ $subtask->stask_id }}"
                                                        data-subtask-name="{{ $subtask->stask_name }}"
                                                        data-subtask-description="{{ $subtask->stask_desc }}"
                                                        data-subtask-avatar="{{ $subtaskAvatar ? $subtaskAvatar : '' }}"
                                                        data-subtask-attachments='@json($subtaskFiles)'
                                                        data-subtask-deadline="{{ $subtask->stask_deadline }}"
                                                        data-subtask-created="{{ $subtask->created_at }}"
                                                        data-subtask-status="{{ $subtask->stask_status }}"
                                                        data-subtask-status-text="{{ $subtask->status_text }}"
                                                        data-subtask-status-badge="{{ $subtask->status_badge_class }}"
                                                        data-subtask-ongoing="{{ $subtask->stask_onprogress }}"
                                                        data-subtask-complete="{{ $subtask->stask_complete }}"
                                                        data-subtask-overdue="{{ $overdueDays }}"
                                                        data-subtask-overdue-text="{{ $overdueText }}"
                                                        data-subtask-task="{{ $subtask->task ? $subtask->task->task_name : 'N/A' }}"
                                                        data-subtask-modulo="{{ $subtask->task && $subtask->task->modulo ? $subtask->task->modulo->mod_name : 'N/A' }}"
                                                        data-subtask-project="{{ $subtask->task && $subtask->task->modulo && $subtask->task->modulo->project ? $subtask->task->modulo->project->pro_name : 'N/A' }}"
                                                        data-subtask-project-head="{{ $subtask->task && $subtask->task->modulo && $subtask->task->modulo->project && $subtask->task->modulo->project->projectHead ? $subtask->task->modulo->project->projectHead->fullname : 'N/A' }}"
                                                        data-subtask-project-lead="{{ $subtask->task && $subtask->task->modulo && $subtask->task->modulo->project && $subtask->task->modulo->project->projectLead ? $subtask->task->modulo->project->projectLead->fullname : 'N/A' }}"
                                                        data-subtask-client="{{ $subtask->task && $subtask->task->modulo && $subtask->task->modulo->project && $subtask->task->modulo->project->client ? $subtask->task->modulo->project->client->cl_name : 'N/A' }}"
                                                        data-subtask-client-email="{{ $subtask->task && $subtask->task->modulo && $subtask->task->modulo->project && $subtask->task->modulo->project->client ? $subtask->task->modulo->project->client->cl_email : 'N/A' }}"
                                                        data-subtask-client-image="{{ $subtask->task && $subtask->task->modulo && $subtask->task->modulo->project && $subtask->task->modulo->project->client && $subtask->task->modulo->project->client->cl_image ? asset('client_images/' . $subtask->task->modulo->project->client->cl_image) : asset('images/admin_default.jpg') }}"
                                                        data-subtask-members="{{ json_encode($memberNames) }}"
                                                        data-subtask-access="{{ $subtask->stask_accessmod }}"
                                                        data-subtask-priority="{{ $subtask->stask_priority }}"
                                                        data-subtask-priority-text="{{ $subtask->priority_text }}"
                                                        data-subtask-priority-class="{{ $subtask->priority_class }}"
                                                        data-bs-toggle="modal" data-bs-target="#subtaskModal">
                                                        <i class="bi bi-eye me-1"></i>View
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center py-5">
                                        <i class="bi bi-inbox display-4 text-muted"></i>
                                        <h5 class="mt-3 text-muted">No Subtasks Found</h5>
                                        <p class="text-muted">Get started by creating your first subtask.</p>
                                        <a href="{{ route('subtaskcreate') }}" class="btn btn-primary mt-2">
                                            <i class="bi bi-plus-lg me-1"></i>Create Subtask
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- Modern Navigation Controls -->
                    <div class="row justify-content-center align-items-center p-3">
                        <div class="col-auto">
                            <div class="modern-navigation d-flex align-items-center">
                                <button class="nav-arrow" id="prevSubtaskPage" disabled>
                                    <i class="bi bi-chevron-left"></i>
                                </button>
                                <div class="nav-dots mx-3" id="subtaskNavDots">
                                    <!-- Dots will be generated dynamically -->
                                </div>
                                <button class="nav-arrow" id="nextSubtaskPage">
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subtask Details Modal -->
        <div class="modal fade" id="subtaskModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header p-0 position-relative">
                        <!-- Subtask Image Header - Will be populated dynamically -->
                        <div class="project-modal-header w-100" id="subtaskModalHeader">
                            <!-- Content will be populated by JavaScript -->
                        </div>
                    </div>

                    <div class="modal-body" id="subtaskModalBody">
                        <!-- Content will be loaded dynamically -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Attachments Modal -->
        <div class="modal fade" id="attachmentsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-paperclip me-2"></i>
                            <h5 class="modal-title mb-0">Subtask Attachments</h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body p-0">
                        <!-- Subtask Info Section -->
                        <div class="project-info-section p-3 border-bottom">
                            <div class="d-flex align-items-center">
                                <div class="project-avatar me-3">
                                    <div class="project-avatar-placeholder">
                                        <i class="bi bi-folder"></i>
                                    </div>
                                </div>
                                <div class="project-details">
                                    <h6 class="mb-1" id="attachmentsSubtaskName">Subtask Name</h6>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-light text-dark me-2">
                                            <i class="bi bi-files me-1"></i>
                                            <span id="attachmentsCount">0</span> files
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filter & Search Section -->
                        <div class="filter-section p-3 border-bottom">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <!-- Search can be added here if needed -->
                                </div>
                                <div class="col-md-6 text-md-end mt-2 mt-md-0">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <input type="radio" class="btn-check" name="attachmentFilter" id="filterAll" checked>
                                        <label class="btn btn-outline-secondary" for="filterAll">All</label>

                                        <input type="radio" class="btn-check" name="attachmentFilter" id="filterImages">
                                        <label class="btn btn-outline-secondary" for="filterImages">
                                            <i class="bi bi-image me-1"></i>Images
                                        </label>

                                        <input type="radio" class="btn-check" name="attachmentFilter" id="filterDocuments">
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

                        <!-- Attachments Grid -->
                        <div class="attachments-container p-3">
                            <div class="row g-3" id="attachmentsGrid">
                                <!-- Attachments will be loaded dynamically -->
                            </div>

                            <!-- Empty State -->
                            <div class="empty-state text-center py-5 d-none" id="emptyAttachments">
                                <i class="bi bi-inbox display-4 text-muted mb-3"></i>
                                <h5 class="text-muted">No attachments found</h5>
                                <p class="text-muted">Try adjusting your search or filter</p>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteSubtaskModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>Delete Subtask
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete the subtask "<strong id="deleteSubtaskName"></strong>"?
                        </p>
                        <p class="text-muted small">This action cannot be undone and all associated data will be removed.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmSubtaskDelete">
                            <i class="bi bi-trash me-1"></i>Delete Subtask
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <x-actionbtn />
    </div>
</x-kanbandashboardlayout>

<!-- Include CSS and JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* ===== SUBTASK CARD STYLES ===== */
    .project-image-container {
        position: relative;
        overflow: hidden;
    }

    .project-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(transparent, rgba(0, 0, 0, 0.7));
        color: white;
        padding: 20px 15px 10px;
        text-align: center;
    }

    .project-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
    }

    .project-meta {
        border-top: 1px solid #e9ecef;
        padding-top: 12px;
        margin-top: 12px;
    }

    /* Blur effect for subtask images */
    .project-image-container .project-image {
        filter: blur(2px);
        transition: filter 0.3s ease;
    }

    .project-image-container:hover .project-image {
        filter: blur(0);
    }

    /* ===== ATTACHMENTS STYLES ===== */
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

    /* File Icon Preview Colors */
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

    /* Attachments Modal Styles */
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

    /* Attachment Card Styles */
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

    /* File Type Colors */
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

    /* Empty State */
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    /* ===== MODAL STYLES ===== */
    .modal-lg {
        max-width: 70em;
    }

    .modal-title {
        font-weight: 600;
        font-size: 1.5rem;
    }

    .btn-close {
        filter: invert(1);
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

    /* ===== BADGE STYLES ===== */
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

    .priority-badge-modal {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .priority-low {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .priority-medium {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffeaa7;
    }

    .priority-high {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    /* ===== EXISTING STYLES ===== */
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

    .actstrip::before {
        content: "";
        position: absolute;
        background: linear-gradient(180deg, #007bff 0%, #0056b3 100%);
        top: 50%;
        left: -10px;
        transform: translateY(-50%);
        width: 8px;
        height: 70%;
        border-radius: 0 6px 6px 0;
        box-shadow: 3px 0 12px rgba(0, 123, 255, 0.4);
        z-index: 2;
    }

    .actstrip::after {
        content: "";
        position: absolute;
        background: #e00000;
        top: 57%;
        left: -8px;
        transform: translateY(-50%);
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 3px solid #ffffff;
        box-shadow: 0 0 0 3px #ff6b6b;
        animation: pulse 2s infinite;
        z-index: 3;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(255, 41, 41, 0.7);
        }

        70% {
            box-shadow: 0 0 0 8px rgba(255, 107, 107, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(255, 107, 107, 0);
        }
    }

    /* Filter Styles */
    .control-select {
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        padding: 0.375rem 0.75rem;
        min-width: 150px;
        text-align: left;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .dropdown-menu {
        border-radius: 0.5rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        border: none;
        padding: 0.5rem;
    }

    .dropdown-item {
        padding: 0.5rem 1rem;
        border-radius: 0.25rem;
    }

    .dropdown-item:hover {
        background-color: #f8f9fa;
    }

    /* Modern Navigation Styles */
    .modern-navigation {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .nav-arrow {
        background: none;
        border: 2px solid #dee2e6;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        color: #6c757d;
    }

    .nav-arrow:hover:not(:disabled) {
        background-color: #f1f3f5;
        border-color: #007bff;
        color: #007bff;
    }

    .nav-arrow:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .nav-dots {
        display: flex;
        gap: 0.5rem;
    }

    .nav-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: #dee2e6;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .nav-dot.active {
        background-color: #007bff;
        transform: scale(1.2);
    }

    .nav-dot:hover:not(.active) {
        background-color: #adb5bd;
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
        width: 35px;
        height: 35px;
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
        width: 35px;
        height: 35px;
        font-size: 0.7rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Project Modal Header Styles */
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

    /* Adjust modal content spacing */
    .modal-body {
        padding-top: 1.5rem;
    }

    /* Ensure modal header has no padding */
    .modal-header.p-0 {
        border-bottom: none;
    }

    /* Read More/Less Styles */
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

    /* Project Modal Content Styles */
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

    /* ===== RESPONSIVE STYLES ===== */
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
        .access-badge-modal,
        .priority-badge-modal {
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
        }

        .team-section {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        console.log('DOM loaded - initializing subtask list');

        // Initialize variables
        let currentSubtaskPage = 1;
        const entriesPerPage = 3;
        let activeFilters = {
            status: '',
            priority: '',
            task: '',
            access: '',
            createdDate: '',
            deadlineDate: ''
        };

        // DOM elements
        const prevSubtaskPageBtn = document.getElementById('prevSubtaskPage');
        const nextSubtaskPageBtn = document.getElementById('nextSubtaskPage');
        const subtaskNavDots = document.getElementById('subtaskNavDots');
        const clearFiltersBtn = document.getElementById('clearFiltersBtn');
        const clearFiltersBtnContainer = document.getElementById('clearFiltersBtnContainer');

        // Initialize the subtask list
        initSubtaskList();

        function initSubtaskList() {
            console.log('Initializing subtask list');

            const subtaskCards = document.querySelectorAll('.subtask-card');
            console.log('Total subtask cards found:', subtaskCards.length);

            setupEventListeners();

            subtaskCards.forEach(card => {
                card.setAttribute('data-visible', 'true');
            });

            updateNavigation();
            checkFiltersStatus();
        }

        function setupEventListeners() {
            console.log('Setting up event listeners');

            // Pagination controls
            if (prevSubtaskPageBtn) {
                prevSubtaskPageBtn.addEventListener('click', goToPrevSubtaskPage);
            }
            if (nextSubtaskPageBtn) {
                nextSubtaskPageBtn.addEventListener('click', goToNextSubtaskPage);
            }

            // Filter elements
            const statusFilter = document.getElementById('statusFilter');
            const statusFilterText = document.getElementById('statusFilterText');
            const priorityFilter = document.getElementById('priorityFilter');
            const priorityFilterText = document.getElementById('priorityFilterText');
            const taskFilter = document.getElementById('taskFilter');
            const taskFilterText = document.getElementById('taskFilterText');
            const accessFilter = document.getElementById('accessFilter');
            const accessFilterText = document.getElementById('accessFilterText');
            const createdDateFilter = document.getElementById('createdDateFilter');
            const deadlineDateFilter = document.getElementById('deadlineDateFilter');
            const dateFilterText = document.getElementById('dateFilterText');

            // Filter changes
            if (statusFilter) {
                statusFilter.addEventListener('change', function () {
                    const selectedOption = this.options[this.selectedIndex];
                    if (selectedOption.value === '') {
                        statusFilterText.textContent = 'All Status';
                        activeFilters.status = '';
                    } else {
                        statusFilterText.textContent = selectedOption.textContent;
                        activeFilters.status = selectedOption.value;
                    }
                    applyFilters();
                    checkFiltersStatus();
                });
            }

            if (priorityFilter) {
                priorityFilter.addEventListener('change', function () {
                    const selectedOption = this.options[this.selectedIndex];
                    if (selectedOption.value === '') {
                        priorityFilterText.textContent = 'All Priority';
                        activeFilters.priority = '';
                    } else {
                        priorityFilterText.textContent = selectedOption.textContent;
                        activeFilters.priority = selectedOption.value;
                    }
                    applyFilters();
                    checkFiltersStatus();
                });
            }

            if (taskFilter) {
                taskFilter.addEventListener('change', function () {
                    const selectedOption = this.options[this.selectedIndex];
                    if (selectedOption.value === '') {
                        taskFilterText.textContent = 'All Tasks';
                        activeFilters.task = '';
                    } else {
                        taskFilterText.textContent = selectedOption.textContent;
                        activeFilters.task = selectedOption.value;
                    }
                    applyFilters();
                    checkFiltersStatus();
                });
            }

            if (accessFilter) {
                accessFilter.addEventListener('change', function () {
                    const selectedOption = this.options[this.selectedIndex];
                    if (selectedOption.value === '') {
                        accessFilterText.textContent = 'All Access';
                        activeFilters.access = '';
                    } else {
                        accessFilterText.textContent = selectedOption.textContent;
                        activeFilters.access = selectedOption.value;
                    }
                    applyFilters();
                    checkFiltersStatus();
                });
            }

            // Date filter changes
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

            // Search functionality for dropdowns
            const taskSearch = document.getElementById('taskSearch');

            if (taskSearch) {
                taskSearch.addEventListener('input', function () {
                    const searchTerm = this.value.toLowerCase();
                    const options = taskFilter.options;

                    for (let i = 0; i < options.length; i++) {
                        const option = options[i];
                        const text = option.textContent.toLowerCase();
                        option.style.display = text.includes(searchTerm) ? '' : 'none';
                    }
                });
            }

            // Clear filters button
            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', clearAllFilters);
            }

            // Event delegation for dynamic elements
            document.addEventListener('click', function (e) {
                // View subtask details
                if (e.target.classList.contains('view-subtask') || e.target.closest('.view-subtask')) {
                    const button = e.target.classList.contains('view-subtask') ? e.target : e.target
                        .closest('.view-subtask');
                    viewSubtask(button);
                }

                // Delete subtask
                if (e.target.classList.contains('delete-subtask') || e.target.closest('.delete-subtask')) {
                    const button = e.target.classList.contains('delete-subtask') ? e.target : e.target
                        .closest('.delete-subtask');
                    const subtaskId = button.getAttribute('data-subtask-id');
                    const subtaskName = button.getAttribute('data-subtask-name');
                    deleteSubtask(subtaskId, subtaskName);
                }

                // Navigation dots
                if (e.target.classList.contains('nav-dot')) {
                    const page = parseInt(e.target.getAttribute('data-page'));
                    goToSubtaskPage(page);
                }

                // View attachments
                if (e.target.classList.contains('view-attachments') || e.target.closest('.view-attachments')) {
                    const button = e.target.classList.contains('view-attachments') ? e.target : e.target
                        .closest('.view-attachments');
                    viewAttachments(button);
                }

                // Download single attachment
                if (e.target.classList.contains('download-attachment') || e.target.closest('.download-attachment')) {
                    const button = e.target.classList.contains('download-attachment') ? e.target : e.target
                        .closest('.download-attachment');
                    downloadAttachment(button);
                }

                // View attachment
                if (e.target.classList.contains('view-attachment') || e.target.closest('.view-attachment')) {
                    const button = e.target.classList.contains('view-attachment') ? e.target : e.target
                        .closest('.view-attachment');
                    viewAttachment(button);
                }

                // Download all attachments
                if (e.target.id === 'downloadAllAttachments') {
                    downloadAllAttachments();
                }
            });

            // Confirm delete
            const confirmSubtaskDeleteBtn = document.getElementById('confirmSubtaskDelete');
            if (confirmSubtaskDeleteBtn) {
                confirmSubtaskDeleteBtn.addEventListener('click', confirmSubtaskDelete);
            }
        }

        function updateDateFilter() {
            const createdDateFilter = document.getElementById('createdDateFilter');
            const deadlineDateFilter = document.getElementById('deadlineDateFilter');
            const dateFilterText = document.getElementById('dateFilterText');

            if (!createdDateFilter || !deadlineDateFilter || !dateFilterText) return;

            const createdDate = createdDateFilter.value;
            const deadlineDate = deadlineDateFilter.value;

            let dateText = 'All Dates';

            if (createdDate && deadlineDate) {
                dateText = 'Multiple Dates';
            } else if (createdDate) {
                dateText = `Created: ${formatDateForDisplay(createdDate)}`;
            } else if (deadlineDate) {
                dateText = `Deadline: ${formatDateForDisplay(deadlineDate)}`;
            }

            dateFilterText.textContent = dateText;

            activeFilters.createdDate = createdDate;
            activeFilters.deadlineDate = deadlineDate;

            applyFilters();
            checkFiltersStatus();
        }

        function applyFilters() {
            console.log('Applying filters:', activeFilters);
            const subtaskCards = document.querySelectorAll('.subtask-card');
            console.log('Total cards to filter:', subtaskCards.length);

            let visibleCount = 0;

            subtaskCards.forEach(card => {
                const cardStatus = card.getAttribute('data-status');
                const cardPriority = card.getAttribute('data-priority');
                const cardTask = card.getAttribute('data-task');
                const cardAccess = card.getAttribute('data-access');
                const cardCreatedDate = card.getAttribute('data-created-date');
                const cardDeadlineDate = card.getAttribute('data-deadline-date');

                let show = true;

                // Status filter
                if (activeFilters.status !== '' && cardStatus !== activeFilters.status) {
                    show = false;
                }

                // Priority filter
                if (activeFilters.priority && cardPriority !== activeFilters.priority) {
                    show = false;
                }

                // Task filter
                if (activeFilters.task && cardTask !== activeFilters.task) {
                    show = false;
                }

                // Access filter
                if (activeFilters.access !== '') {
                    const cardAccessText = cardAccess === '1' ? 'private' : 'public';
                    const filterAccessText = activeFilters.access.toLowerCase();
                    if (filterAccessText !== cardAccessText) {
                        show = false;
                    }
                }

                // Created date filter
                if (activeFilters.createdDate && cardCreatedDate !== activeFilters.createdDate) {
                    show = false;
                }

                // Deadline date filter
                if (activeFilters.deadlineDate && cardDeadlineDate !== activeFilters.deadlineDate) {
                    show = false;
                }

                // Store visibility as data attribute
                card.setAttribute('data-visible', show.toString());

                if (show) {
                    visibleCount++;
                }
            });

            console.log(`Visible cards after filtering: ${visibleCount}`);

            // Remove any existing "No Subtasks Found" message
            const subtasksGrid = document.getElementById('subtasksGrid');
            if (subtasksGrid) {
                const existingMessage = subtasksGrid.querySelector('.no-subtasks-message');
                if (existingMessage) {
                    existingMessage.remove();
                }
            }

            currentSubtaskPage = 1;
            updateNavigation();
            checkFiltersStatus();
        }

        function updateNavigation() {
            const subtaskCards = document.querySelectorAll('.subtask-card');
            const visibleCards = Array.from(subtaskCards).filter(card =>
                card.getAttribute('data-visible') === 'true'
            );

            const totalSubtasks = visibleCards.length;
            const totalPages = Math.ceil(totalSubtasks / entriesPerPage);

            console.log(`Navigation: ${totalSubtasks} visible subtasks, ${totalPages} pages`);

            // Update navigation buttons
            if (prevSubtaskPageBtn) {
                prevSubtaskPageBtn.disabled = currentSubtaskPage <= 1;
            }
            if (nextSubtaskPageBtn) {
                nextSubtaskPageBtn.disabled = currentSubtaskPage >= totalPages || totalPages === 0;
            }

            // Create navigation dots
            if (subtaskNavDots) {
                subtaskNavDots.innerHTML = '';
                for (let i = 1; i <= totalPages; i++) {
                    const dot = document.createElement('div');
                    dot.className = `nav-dot ${i === currentSubtaskPage ? 'active' : ''}`;
                    dot.setAttribute('data-page', i);
                    subtaskNavDots.appendChild(dot);
                }
            }

            // Calculate start and end indices for current page
            const startIndex = (currentSubtaskPage - 1) * entriesPerPage;
            const endIndex = Math.min(startIndex + entriesPerPage, totalSubtasks);

            console.log(`Showing cards ${startIndex} to ${endIndex - 1}`);

            // First, hide all cards
            subtaskCards.forEach(card => {
                card.style.display = 'none';
            });

            // Then, show only the visible cards for current page
            visibleCards.forEach((card, index) => {
                if (index >= startIndex && index < endIndex) {
                    card.style.display = 'block';
                }
            });

            // Show "No Subtasks Found" message if no visible cards
            const subtasksGrid = document.getElementById('subtasksGrid');
            if (subtasksGrid) {
                let noSubtasksMessage = subtasksGrid.querySelector('.no-subtasks-message');
                if (totalSubtasks === 0) {
                    if (!noSubtasksMessage) {
                        noSubtasksMessage = document.createElement('div');
                        noSubtasksMessage.className = 'col-12 no-subtasks-message';
                        noSubtasksMessage.innerHTML = `
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center py-5">
                                <i class="bi bi-inbox display-4 text-muted"></i>
                                <h5 class="mt-3 text-muted">No Subtasks Found</h5>
                            </div>
                        </div>`;
                        subtasksGrid.appendChild(noSubtasksMessage);
                    }
                } else {
                    if (noSubtasksMessage) noSubtasksMessage.remove();
                }
            }
        }

        function goToPrevSubtaskPage() {
            if (currentSubtaskPage > 1) {
                currentSubtaskPage--;
                updateNavigation();
            }
        }

        function goToNextSubtaskPage() {
            const subtaskCards = document.querySelectorAll('.subtask-card');
            const visibleCards = Array.from(subtaskCards).filter(card =>
                card.getAttribute('data-visible') === 'true'
            );
            const totalPages = Math.ceil(visibleCards.length / entriesPerPage);

            if (currentSubtaskPage < totalPages) {
                currentSubtaskPage++;
                updateNavigation();
            }
        }

        function goToSubtaskPage(page) {
            const subtaskCards = document.querySelectorAll('.subtask-card');
            const visibleCards = Array.from(subtaskCards).filter(card =>
                card.getAttribute('data-visible') === 'true'
            );
            const totalPages = Math.ceil(visibleCards.length / entriesPerPage);

            if (page >= 1 && page <= totalPages) {
                currentSubtaskPage = page;
                updateNavigation();
            }
        }

        function checkFiltersStatus() {
            const hasActiveFilters =
                activeFilters.status ||
                activeFilters.priority ||
                activeFilters.task ||
                activeFilters.access ||
                activeFilters.createdDate ||
                activeFilters.deadlineDate;

            if (clearFiltersBtnContainer) {
                clearFiltersBtnContainer.style.display = hasActiveFilters ? 'block' : 'none';
            }
        }

        function clearAllFilters() {
            console.log('Clearing all filters');

            // Reset filter values
            const statusFilter = document.getElementById('statusFilter');
            const statusFilterText = document.getElementById('statusFilterText');
            const priorityFilter = document.getElementById('priorityFilter');
            const priorityFilterText = document.getElementById('priorityFilterText');
            const taskFilter = document.getElementById('taskFilter');
            const taskFilterText = document.getElementById('taskFilterText');
            const accessFilter = document.getElementById('accessFilter');
            const accessFilterText = document.getElementById('accessFilterText');
            const dateFilterText = document.getElementById('dateFilterText');
            const createdDateFilter = document.getElementById('createdDateFilter');
            const deadlineDateFilter = document.getElementById('deadlineDateFilter');

            if (statusFilter) statusFilter.value = '';
            if (statusFilterText) statusFilterText.textContent = 'All Status';
            if (priorityFilter) priorityFilter.value = '';
            if (priorityFilterText) priorityFilterText.textContent = 'All Priority';
            if (taskFilter) taskFilter.value = '';
            if (taskFilterText) taskFilterText.textContent = 'All Tasks';
            if (accessFilter) accessFilter.value = '';
            if (accessFilterText) accessFilterText.textContent = 'All Access';
            if (dateFilterText) dateFilterText.textContent = 'All Dates';
            if (createdDateFilter) createdDateFilter.value = '';
            if (deadlineDateFilter) deadlineDateFilter.value = '';

            // Reset active filters
            activeFilters = {
                status: '',
                priority: '',
                task: '',
                access: '',
                createdDate: '',
                deadlineDate: ''
            };

            // Reset all cards to visible
            const subtaskCards = document.querySelectorAll('.subtask-card');
            subtaskCards.forEach(card => {
                card.setAttribute('data-visible', 'true');
            });

            // Remove any "No Subtasks Found" message
            const subtasksGrid = document.getElementById('subtasksGrid');
            if (subtasksGrid) {
                const noSubtasksMessage = subtasksGrid.querySelector('.no-subtasks-message');
                if (noSubtasksMessage) {
                    noSubtasksMessage.remove();
                }
            }

            // Hide clear filters button
            if (clearFiltersBtnContainer) {
                clearFiltersBtnContainer.style.display = 'none';
            }

            // Reset to first page and update navigation
            currentSubtaskPage = 1;
            updateNavigation();
        }

        function viewSubtask(button) {
            const subtaskId = button.getAttribute('data-subtask-id');
            const subtaskName = button.getAttribute('data-subtask-name');
            const subtaskDescription = button.getAttribute('data-subtask-description');
            const subtaskAvatar = button.getAttribute('data-subtask-avatar');
            const subtaskAttachments = JSON.parse(button.getAttribute('data-subtask-attachments') || '[]');
            const subtaskDeadline = button.getAttribute('data-subtask-deadline');
            const subtaskCreated = button.getAttribute('data-subtask-created');
            const subtaskStatus = button.getAttribute('data-subtask-status');
            const subtaskStatusText = button.getAttribute('data-subtask-status-text');
            const subtaskStatusBadge = button.getAttribute('data-subtask-status-badge');
            const subtaskOngoing = button.getAttribute('data-subtask-ongoing');
            const subtaskComplete = button.getAttribute('data-subtask-complete');
            const subtaskOverdue = button.getAttribute('data-subtask-overdue');
            const subtaskOverdueText = button.getAttribute('data-subtask-overdue-text');
            const subtaskTask = button.getAttribute('data-subtask-task');
            const subtaskModulo = button.getAttribute('data-subtask-modulo');
            const subtaskProject = button.getAttribute('data-subtask-project');
            const subtaskProjectHead = button.getAttribute('data-subtask-project-head');
            const subtaskProjectLead = button.getAttribute('data-subtask-project-lead');
            const subtaskClient = button.getAttribute('data-subtask-client');
            const subtaskClientEmail = button.getAttribute('data-subtask-client-email');
            const subtaskClientImage = button.getAttribute('data-subtask-client-image');
            const subtaskMembers = JSON.parse(button.getAttribute('data-subtask-members') || '[]');
            const subtaskAccess = button.getAttribute('data-subtask-access');
            const subtaskPriority = button.getAttribute('data-subtask-priority');
            const subtaskPriorityText = button.getAttribute('data-subtask-priority-text');
            const subtaskPriorityClass = button.getAttribute('data-subtask-priority-class');

            // Format dates
            const createdDate = new Date(subtaskCreated);
            const deadlineDate = new Date(subtaskDeadline);
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

            let ongoingDate = '';
            if (subtaskOngoing && subtaskOngoing !== 'null') {
                const ongoing = new Date(subtaskOngoing);
                ongoingDate = ongoing.toLocaleDateString('en-GB', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                }).replace(/\//g, '-');
            }

            let completeDate = '';
            if (subtaskComplete && subtaskComplete !== 'null') {
                const complete = new Date(subtaskComplete);
                completeDate = complete.toLocaleDateString('en-GB', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                }).replace(/\//g, '-');
            }

            // Access mode
            const accessClass = subtaskAccess === '1' ? 'access-private' : 'access-public';
            const accessText = subtaskAccess === '1' ? 'Private' : 'Public';

            // Priority
            const priorityClass = `priority-${subtaskPriorityText.toLowerCase()}`;

            // Handle subtask description with read more functionality
            let descriptionHTML = '';
            if (subtaskDescription && subtaskDescription.length > 15) {
                const shortDescription = subtaskDescription.substring(0, 15) + '...';
                descriptionHTML = `
                    <div class="subtask-description-container">
                        <span class="subtask-description-short">${shortDescription}</span>
                        <span class="subtask-description-full" style="display: none;">${subtaskDescription}</span>
                        <button class="btn-read-more btn btn-link p-0 text-decoration-none" onclick="toggleSubtaskDescription(this)">
                            <small>Read More</small>
                        </button>
                    </div>
                `;
            } else {
                descriptionHTML = `<span>${subtaskDescription || 'No description available'}</span>`;
            }

            // Build modal header content dynamically
            let modalHeaderContent = '';
            if (subtaskAvatar) {
                modalHeaderContent = `
                    <img src="${subtaskAvatar}" class="project-modal-header-img" alt="${subtaskName}">
                    <div class="project-modal-overlay d-flex align-items-start justify-content-between p-4">
                        <div>
                            <h5 class="project-modal-title text-white mb-1">${subtaskName}</h5>
                            <span class="badge status-badge-modal ${subtaskStatusBadge}">${subtaskStatusText}</span>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                `;
            } else {
                modalHeaderContent = `
                    <div class="project-modal-header-placeholder d-flex align-items-center justify-content-center">
                        <i class="bi bi-check2-square text-white" style="font-size: 3rem;"></i>
                    </div>
                    <div class="project-modal-overlay d-flex align-items-start justify-content-between p-4">
                        <div>
                            <h5 class="project-modal-title text-white mb-1">${subtaskName}</h5>
                            <span class="badge status-badge-modal ${subtaskStatusBadge}">${subtaskStatusText}</span>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                `;
            }

            // Build attachments section
            let attachmentsHTML = '';
            if (subtaskAttachments.length > 0) {
                attachmentsHTML = `
                    <div class="attachments-section">
                        <div class="attachments-header">
                            <h4 class="info-section-title mb-0">ATTACHMENTS</h4>
                        </div>
                        <div class="attachments-preview">
                            ${subtaskAttachments.slice(0, 5).map((file, index) => {
                                if (!file || !file.name) return '';

                                const fileName = file.name;
                                const fileExtension = fileName.includes('.') ? fileName.split('.').pop().toLowerCase() : '';

                                // Determine file type and icon
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
                            ${subtaskAttachments.length > 5 ? `
                                <div class="attachment-more-preview" data-bs-toggle="tooltip" title="${subtaskAttachments.length - 5} more files">
                                    +${subtaskAttachments.length - 5}
                                </div>
                            ` : ''}
                            <button class="btn btn-sm btn-outline-primary view-attachments"
                                data-subtask-name="${subtaskName}"
                                data-subtask-attachments='${JSON.stringify(subtaskAttachments)}'>
                                <i class="bi bi-eye me-1"></i>View Attachments
                            </button>
                        </div>
                    </div>
                `;
            }

            // Build modal body content
            let modalContent = `
                <div class="project-details-container">
                    <div class="project-info-grid">
                        <div>
                            <div class="info-section">
                                <h4 class="info-section-title">SUBTASK INFORMATION</h4>
                                <div class="info-item">
                                    <span class="info-label">Subtask Description</span>
                                    <span class="info-value">
                                        ${descriptionHTML}
                                    </span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Access Mode</span>
                                    <span class="info-value">
                                        <span class="access-badge-modal ${accessClass}">${accessText}</span>
                                    </span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Priority</span>
                                    <span class="info-value">
                                        <span class="priority-badge-modal ${priorityClass}">${subtaskPriorityText}</span>
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
                                    ${ongoingDate ? `
                                    <div class="date-card">
                                        <div class="date-label">On Progress</div>
                                        <div class="date-value">${ongoingDate}</div>
                                    </div>
                                    ` : ''}
                                    ${completeDate ? `
                                    <div class="date-card">
                                        <div class="date-label">Completed</div>
                                        <div class="date-value ${subtaskOverdue > 0 ? 'text-danger' : 'text-success'}">${completeDate}</div>
                                        ${subtaskOverdue > 0 ? `
                                        <div class="overdue-warning">
                                            <i class="bi bi-exclamation-triangle"></i>
                                            Overdue: ${subtaskOverdueText}
                                        </div>
                                        ` : ''}
                                    </div>
                                    ` : ''}
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="info-section">
                                <h4 class="info-section-title">PROJECT INFORMATION</h4>
                                <div class="info-item">
                                    <span class="info-label">Project Name</span>
                                    <span class="info-value">${subtaskProject}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Module Name</span>
                                    <span class="info-value">${subtaskModulo}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Task Name</span>
                                    <span class="info-value">${subtaskTask}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Project Manager</span>
                                    <span class="info-value">${subtaskProjectHead}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Project Lead</span>
                                    <span class="info-value">${subtaskProjectLead}</span>
                                </div>
                            </div>

                            <div class="info-section">
                                <h4 class="info-section-title">CLIENT INFORMATION</h4>
                                <div class="team-member">
                                    ${subtaskClientImage && subtaskClientImage.includes('client_images/') ?
                                        `<img src="${subtaskClientImage}" class="team-member-avatar" alt="${subtaskClient}">` :
                                        `<div class="team-member-avatar rounded-circle bg-secondary-client text-white d-flex align-items-center justify-content-center"
                                            style="width: 45px; height: 45px; font-size: 1rem; font-weight: 600;">
                                            ${subtaskClient && subtaskClient !== 'N/A' ? subtaskClient.charAt(0).toUpperCase() : 'C'}
                                        </div>`
                                    }
                                    <div class="team-member-info">
                                        <div class="team-member-name">${subtaskClient}</div>
                                        <div class="team-member-role">${subtaskClientEmail}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="info-section">
                        <h4 class="info-section-title">TEAM MEMBERS</h4>
                        <div class="team-section">
                            ${subtaskMembers.length > 0 ?
                                subtaskMembers.map(member => `
                                    <div class="team-member">
                                        <div class="team-member-avatar d-flex align-items-center justify-content-center bg-secondary-vteam text-white">
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
            `;

            // Update modal header and body
            document.getElementById('subtaskModalHeader').innerHTML = modalHeaderContent;
            document.getElementById('subtaskModalBody').innerHTML = modalContent;
        }

        // Initialize attachments modal functionality
        function initAttachmentsModal() {
            // Filter functionality
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

                // Apply filter only (search removed)
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

            // Show/hide empty state
            if (emptyState) {
                if (visibleCount === 0) {
                    emptyState.classList.remove('d-none');
                } else {
                    emptyState.classList.add('d-none');
                }
            }
        }

        // Update viewAttachments function to use new design
        window.viewAttachments = function (button) {
            const subtaskName = button.getAttribute('data-subtask-name');
            const attachments = JSON.parse(button.getAttribute('data-subtask-attachments') || '[]');

            // Update modal content
            document.getElementById('attachmentsSubtaskName').textContent = subtaskName;
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

                    // Determine file icon and color
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
                            <div class="attachment-info text-center">
                                <span class="attachment-type ${fileColor}">
                                    ${fileType.toUpperCase()}
                                </span>
                            </div>
                        </div>
                    `;

                    attachmentsGrid.appendChild(fileCard);
                });
            }

            // Store attachments for download all functionality
            attachmentsGrid.setAttribute('data-all-attachments', JSON.stringify(attachments));

            // Reset search and filters
            document.getElementById('filterAll').checked = true;

            // Show attachments modal
            const attachmentsModal = new bootstrap.Modal(document.getElementById('attachmentsModal'));
            attachmentsModal.show();
        };

        // Helper function to format file size
        function getFileSize(size) {
            if (!size) return 'N/A';

            if (size < 1024) {
                return size + ' B';
            } else if (size < 1048576) {
                return (size / 1024).toFixed(1) + ' KB';
            } else {
                return (size / 1048576).toFixed(1) + ' MB';
            }
        }

        function downloadAttachment(button) {
            const fileUrl = button.getAttribute('data-file-url');
            const fileName = button.getAttribute('data-file-name');

            // Create a temporary link to trigger download
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

            // Open image in new tab
            window.open(fileUrl, '_blank');
        }

        function downloadAllAttachments() {
            const attachmentsGrid = document.getElementById('attachmentsGrid');
            const attachments = JSON.parse(attachmentsGrid.getAttribute('data-all-attachments') || '[]');

            if (attachments.length === 0) {
                showToast('No attachments to download', 'warning');
                return;
            }

            // Show loading state
            const downloadBtn = document.getElementById('downloadAllAttachments');
            const originalText = downloadBtn.innerHTML;
            downloadBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Preparing...';
            downloadBtn.disabled = true;

            // Download each file
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

            // Reset button after all downloads
            setTimeout(() => {
                downloadBtn.innerHTML = originalText;
                downloadBtn.disabled = false;
                showToast(`Started downloading ${attachments.length} files`, 'success');
            }, attachments.length * 100 + 500);
        }

        // Add this global function to handle the read more toggle for subtask descriptions
        window.toggleSubtaskDescription = function (button) {
            const container = button.closest('.subtask-description-container');
            const shortDesc = container.querySelector('.subtask-description-short');
            const fullDesc = container.querySelector('.subtask-description-full');

            if (fullDesc.style.display === 'none') {
                // Show full description
                shortDesc.style.display = 'none';
                fullDesc.style.display = 'inline';
                button.innerHTML = '<small>Read Less</small>';
            } else {
                // Show short description
                shortDesc.style.display = 'inline';
                fullDesc.style.display = 'none';
                button.innerHTML = '<small>Read More</small>';
            }
        }

        function deleteSubtask(subtaskId, subtaskName) {
            document.getElementById('deleteSubtaskName').textContent = subtaskName;
            document.getElementById('confirmSubtaskDelete').setAttribute('data-subtask-id', subtaskId);

            const deleteModal = new bootstrap.Modal(document.getElementById('deleteSubtaskModal'));
            deleteModal.show();
        }

        function confirmSubtaskDelete() {
            const subtaskId = document.getElementById('confirmSubtaskDelete').getAttribute('data-subtask-id');
            const subtaskName = document.getElementById('deleteSubtaskName').textContent;

            fetch(`/dashboard/subtask/delete/${subtaskId}`, {
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
                        const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteSubtaskModal'));
                        deleteModal.hide();

                        showToast('Subtask deleted successfully', 'success');

                        // Remove the subtask card from DOM
                        const subtaskCard = document.querySelector(`.subtask-card[data-subtask-id="${subtaskId}"]`);
                        if (subtaskCard) {
                            subtaskCard.remove();
                        }

                        // Update navigation
                        setTimeout(() => {
                            currentSubtaskPage = 1;
                            updateNavigation();
                        }, 100);

                    } else {
                        showToast(data.message || 'Failed to delete subtask', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('An error occurred while deleting the subtask', 'error');
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

        // Initialize all cards as visible on page load
        window.addEventListener('load', function () {
            const subtaskCards = document.querySelectorAll('.subtask-card');
            subtaskCards.forEach(card => {
                card.setAttribute('data-visible', 'true');
                card.style.display = 'block';
            });
            updateNavigation();
        });

        // Initialize attachments modal
        initAttachmentsModal();
    });
</script>
