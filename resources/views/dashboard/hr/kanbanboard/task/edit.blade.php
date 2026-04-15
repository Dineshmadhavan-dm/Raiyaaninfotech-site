<x-kanbandashboardlayout>
    @section('title', 'Edit Task')

    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-2">Edit Task</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dhome') }}" class="text-decoration-none text-muted">
                                <i class="bi bi-house me-2"></i>Home
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('tasklist') }}" class="text-decoration-none text-muted">
                                <i class="bi bi-list-task me-2"></i>Task
                            </a>
                        </li>
                        <li class="breadcrumb-item active">Edit Task</li>
                    </ol>
                </nav>
            </div>

            <a href="{{ route('tasklist') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left-circle me-2"></i>Back to List
            </a>
        </div>

        <x-message />

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form action="{{ route('taskupdate', $task->task_id) }}" method="POST"
                            enctype="multipart/form-data" id="taskForm">
                            @csrf
                            @method('PUT')

                            @if ($task->modulo)
                                <div class="section-header mb-4">
                                    <h6 class="section-title">Modulo Information</h6>
                                    <div class="section-divider"></div>
                                </div>

                                <div class="card bg-light border-0 mb-4">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>Modulo Name:</strong> {{ $task->modulo->mod_name }}<br>
                                                <strong>Project:</strong>
                                                {{ $task->modulo->project ? $task->modulo->project->pro_name : 'N/A' }}<br>
                                                <strong>Client:</strong>
                                                {{ $task->modulo->project && $task->modulo->project->client ? $task->modulo->project->client->cl_name : 'N/A' }}
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Modulo Deadline:</strong>
                                                {{ \Carbon\Carbon::parse($task->modulo->mod_deadline)->format('M d, Y') }}<br>
                                                <strong>Access:</strong>
                                                {{ $task->modulo->mod_accessmod ? 'Private' : 'Public' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="section-header mb-4">
                                <h6 class="section-title">Task Info</h6>
                                <div class="section-divider"></div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="task_name" class="form-label required">Task Name</label>
                                        <input type="text" class="form-control @error('task_name') is-invalid @enderror"
                                            id="task_name" name="task_name"
                                            value="{{ old('task_name', $task->task_name) }}"
                                            placeholder="Enter task name">
                                        @error('task_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="task_deadline" class="form-label required">Task Deadline</label>
                                        <input type="date"
                                            class="form-control @error('task_deadline') is-invalid @enderror"
                                            id="task_deadline" name="task_deadline"
                                            value="{{ old('task_deadline', \Carbon\Carbon::parse($task->task_deadline)->format('Y-m-d')) }}">
                                        @error('task_deadline')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="task_desc" class="form-label required">Task Description</label>
                                        <textarea class="form-control @error('task_desc') is-invalid @enderror"
                                            id="task_desc" name="task_desc" rows="3"
                                            placeholder="Enter task description">{{ old('task_desc', $task->task_desc) }}</textarea>
                                        @error('task_desc')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="task_status" class="form-label required">Task Status</label>
                                        <select class="form-control @error('task_status') is-invalid @enderror"
                                            id="task_status" name="task_status">
                                            <option value="0" {{ old('task_status', $task->task_status) == 0 ? 'selected' : '' }}>Created</option>
                                            <option value="1" {{ old('task_status', $task->task_status) == 1 ? 'selected' : '' }}>On Progress</option>
                                            <option value="2" {{ old('task_status', $task->task_status) == 2 ? 'selected' : '' }}>Completed</option>
                                        </select>
                                        @error('task_status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                        <div class="status-dates mt-2">
                                            @if ($task->task_onprogress)
                                                <small class="text-muted">
                                                    <i class="bi bi-play-circle me-1"></i>
                                                    Started:
                                                    {{ \Carbon\Carbon::parse($task->task_onprogress)->format('M d, Y') }}
                                                </small>
                                            @endif
                                            @if ($task->task_complete)
                                                <br>
                                                <small class="text-muted">
                                                    <i class="bi bi-check-circle me-1"></i>
                                                    Completed:
                                                    {{ \Carbon\Carbon::parse($task->task_complete)->format('M d, Y') }}
                                                </small>
                                            @endif
                                            @if ($task->task_overdue > 0)
                                                <br>
                                                <small class="text-danger">
                                                    <i class="bi bi-clock me-1"></i>
                                                    Overdue: {{ $task->overdue_text }}
                                                </small>
                                            @endif
                                        </div>
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
                                                            name="task_accessmod" id="access_public" value="0" {{ old('task_accessmod', $task->task_accessmod) == '0' ? 'checked' : '' }}>
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
                                                            name="task_accessmod" id="access_private" value="1" {{ old('task_accessmod', $task->task_accessmod) == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="access_private">
                                                            <span class="fw-semibold">Private</span>
                                                            <small class="d-block text-muted">Restricted access
                                                                only</small>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @error('task_accessmod')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label required">Task Priority</label>
                                        <div class="border rounded p-3 bg-light">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-check">
                                                        <input
                                                            class="form-check-input @error('task_priority') is-invalid @enderror"
                                                            type="radio" name="task_priority" id="priority_low"
                                                            value="1" {{ old('task_priority', $task->task_priority) == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="priority_low">
                                                            <span class="fw-semibold">Low</span>
                                                            <small class="d-block text-muted">Minor importance</small>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-check">
                                                        <input
                                                            class="form-check-input @error('task_priority') is-invalid @enderror"
                                                            type="radio" name="task_priority" id="priority_medium"
                                                            value="2" {{ old('task_priority', $task->task_priority) == '2' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="priority_medium">
                                                            <span class="fw-semibold">Medium</span>
                                                            <small class="d-block text-muted">Normal urgency</small>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-check">
                                                        <input
                                                            class="form-check-input @error('task_priority') is-invalid @enderror"
                                                            type="radio" name="task_priority" id="priority_high"
                                                            value="3" {{ old('task_priority', $task->task_priority) == '3' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="priority_high">
                                                            <span class="fw-semibold">High</span>
                                                            <small class="d-block text-muted">Requires quick
                                                                attention</small>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @error('task_priority')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="section-header mb-4">
                                <h6 class="section-title">Task Avatar</h6>
                                <div class="section-divider"></div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="task_avater" class="form-label">Task Avatar (Single Image)</label>

                                        @if ($task->task_avater)
                                            <div class="current-attachments-section mb-3">
                                                <label class="sub-label">Current Avatar</label>
                                                <div class="current-attachments-list d-flex flex-wrap gap-3">
                                                    @php
                                                        $avatarFileName = basename($task->task_avater);
                                                        $fileExtension = strtolower(pathinfo($avatarFileName, PATHINFO_EXTENSION));
                                                        $fileUrl = asset($task->task_avater);
                                                        $isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']);
                                                    @endphp

                                                    <div class="current-file-item row p-3 ms-1 rounded border">
                                                        <div class="col-auto mb-2">
                                                            @if ($isImage)
                                                                <img src="{{ $fileUrl }}" class="attachment-preview"
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
                                                                        name="remove_avater" id="remove_avater" value="1">
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
                                                No avatar set for this task.
                                            </div>
                                        @endif

                                        <div class="file-input-wrapper">
                                            <input type="file"
                                                class="form-control file-input @error('task_avater') is-invalid @enderror"
                                                id="task_avater" name="task_avater" accept=".jpg,.jpeg,.png">
                                            <div class="file-info">
                                                <span class="file-placeholder">
                                                    @if ($task->task_avater)
                                                        Choose new file to replace current avatar
                                                    @else
                                                        Choose avatar image
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-text mt-2">
                                            Allowed Format: JPG, JPEG, PNG only. Max size: 5MB
                                        </div>
                                        @error('task_avater')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="section-header mb-4">
                                <h6 class="section-title">Task Attachments</h6>
                                <div class="section-divider"></div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="task_attachment" class="form-label">Task Attachments (Multiple
                                            Files)</label>

                                        @if ($task->pmtsImages && $task->pmtsImages->count() > 0)
                                            <div class="current-attachments-section mb-3">
                                                <label class="sub-label">Current Attachments</label>
                                                <div class="current-attachments-list d-flex flex-wrap gap-3">
                                                    @foreach ($task->pmtsImages as $attachment)
                                                        @php
                                                            $fileExtension = strtolower(pathinfo($attachment->pmtsimage_name, PATHINFO_EXTENSION));
                                                            $fileName = $attachment->pmtsimage_name;
                                                            $fileUrl = asset('task_attachments/' . $fileName);
                                                            $isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']);
                                                        @endphp

                                                        <div class="current-file-item row ms-1 p-3 rounded border">
                                                            <div class="col-auto mb-2">
                                                                @if ($isImage)
                                                                    <img src="{{ $fileUrl }}" class="attachment-preview"
                                                                        style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px;"
                                                                        alt="{{ $fileName }}"
                                                                        onerror="this.src='{{ asset('images/admin_default.jpg') }}'">
                                                                @else
                                                                    <div class="file-icon">
                                                                        @if ($fileExtension === 'pdf')
                                                                            <i class="bi bi-file-pdf text-danger"
                                                                                style="font-size: 3.8em; margin-left: -.2em;"></i>
                                                                        @elseif(in_array($fileExtension, ['doc', 'docx']))
                                                                            <i class="bi bi-file-word text-primary"
                                                                                style="font-size: 3.8em; margin-left: -.2em;"></i>
                                                                        @elseif(in_array($fileExtension, ['xls', 'xlsx', 'csv']))
                                                                            <i class="bi bi-file-excel text-success"
                                                                                style="font-size: 3.8em; margin-left: -.2em;"></i>
                                                                        @elseif(in_array($fileExtension, ['ppt', 'pptx']))
                                                                            <i class="bi bi-file-ppt text-warning"
                                                                                style="font-size: 3.8em; margin-left: -.2em;"></i>
                                                                        @else
                                                                            <i class="bi bi-file-earmark"></i>
                                                                        @endif
                                                                    </div>
                                                                @endif
                                                            </div>

                                                            <div class="col-auto">
                                                                <div
                                                                    class="current-file-actions d-flex flex-column gap-1 w-100">
                                                                    <a href="{{ $fileUrl }}" target="_blank"
                                                                        class="btn btn-sm btn-outline-primary">
                                                                        View
                                                                    </a>

                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox"
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
                                                No attachments found for this task.
                                            </div>
                                        @endif

                                        <div class="file-input-wrapper">
                                            <input type="file"
                                                class="form-control file-input @error('task_attachment') is-invalid @enderror"
                                                id="task_attachment" name="task_attachment[]"
                                                accept=".jpg,.jpeg,.png,.pdf,.ppt,.pptx,.csv,.xlsx,.xls,.doc,.docx"
                                                multiple>
                                            <div class="file-info">
                                                <span class="file-placeholder">
                                                    Choose additional files
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-text mt-2">
                                            Allowed Files: JPG, JPEG, PNG, PDF, PPT, CSV, Excel, Word documents. Max
                                            size: 10MB per file
                                        </div>
                                        @error('task_attachment')
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
                                <h6 class="section-title">Assigned Person</h6>
                                <div class="section-divider"></div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="task_assignedto" class="form-label required">Assign To</label>

                                        @if ($task->modulo && count($moduloMembers) > 0)
                                            <div class="alert alert-info mb-3">
                                                <i class="bi bi-info-circle me-2"></i>
                                                Showing members from modulo: <strong>{{ $task->modulo->mod_name }}</strong>
                                            </div>

                                            <select class="form-control @error('task_assignedto') is-invalid @enderror"
                                                id="task_assignedto" name="task_assignedto">
                                                <option value="">Select a assign to...</option>
                                                @foreach ($moduloMembers as $member)
                                                    <option value="{{ $member->emp_id }}" {{ old('task_assignedto', $task->task_assignedto) == $member->emp_id ? 'selected' : '' }}>
                                                        {{ $member->fullname }} ({{ $member->employee_id }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            <div class="alert alert-warning">
                                                <i class="bi bi-exclamation-triangle me-2"></i>
                                                No modulo members found.
                                            </div>
                                        @endif

                                        @error('task_assignedto')
                                            <div class="error-message">{{ $message }}</div>
                                        @enderror

                                        <div class="selected-member-section mt-3">
                                            <label class="sub-label">Selected Assign to</label>
                                            <div id="selected_member" class="selected-member-display">
                                                @if($task->task_assignedto)
                                                    @php
                                                        $assignedMember = $moduloMembers->firstWhere('emp_id', $task->task_assignedto);
                                                    @endphp
                                                    @if($assignedMember)
                                                        <div class="member-card">
                                                            <div class="member-info">
                                                                <div class="member-name">{{ $assignedMember->fullname }}</div>
                                                                <div class="member-id">{{ $assignedMember->employee_id }}</div>
                                                            </div>
                                                            <button type="button" class="btn-remove-member"
                                                                onclick="removeAssignedMember()">
                                                                <i class="bi bi-x-lg"></i>
                                                            </button>
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="text-muted">No assign to selected</div>
                                                @endif
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
                <input class="form-check-input" type="checkbox" id="send_email" name="send_email" value="1" >
                <label class="form-check-label" for="send_email">
                    <span class="fw-semibold">Send Email Notification</span>
                    <small class="d-block text-muted">Send task details to assigned person</small>
                </label>
            </div>
        </div>
    </div>
</div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-2"></i>Update Task
                                </button>
                                <a href="{{ route('tasklist') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle me-2"></i>Cancel
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

        .selected-member-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1rem;
        }

        .selected-member-display {
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
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            min-width: 250px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .member-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .btn-remove-member {
            background: none;
            border: none;
            color: #dc3545;
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .btn-remove-member:hover {
            background-color: rgba(220, 53, 69, 0.1);
            transform: scale(1.1);
            color: #c82333;
        }

        .member-info {
            flex: 1;
        }

        .member-name {
            font-weight: 600;
            font-size: 1rem;
            color: #2c3e50;
            margin-bottom: 0.25rem;
        }

        .member-id {
            font-size: 0.85rem;
            color: #6c757d;
            font-weight: 500;
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

        .current-attachments-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .current-attachments-list {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .current-file-item {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .attachment-preview {
            border-radius: 6px;
            object-fit: cover;
        }

        .current-file-actions {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const assignedToSelect = document.getElementById('task_assignedto');
            const selectedMemberDiv = document.getElementById('selected_member');

            assignedToSelect.addEventListener('change', function () {
                updateSelectedMemberDisplay(this.value);
            });

            function updateSelectedMemberDisplay(memberId) {
                if (!selectedMemberDiv) return;

                selectedMemberDiv.innerHTML = '';

                if (!memberId) {
                    selectedMemberDiv.innerHTML = '<div class="text-muted">No assign to selected</div>';
                    return;
                }

                const selectedOption = assignedToSelect.querySelector(`option[value="${memberId}"]`);
                if (selectedOption) {
                    const memberText = selectedOption.textContent;
                    const match = memberText.match(/(.+)\s+\((.+)\)/);
                    const memberName = match ? match[1].trim() : memberText;
                    const memberId = match ? match[2].trim() : '';

                    const memberCard = document.createElement('div');
                    memberCard.className = 'member-card';
                    memberCard.innerHTML = `
                        <div class="member-info">
                            <div class="member-name">${memberName}</div>
                            <div class="member-id">${memberId}</div>
                        </div>
                        <button type="button" class="btn-remove-member" onclick="removeAssignedMember()">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    `;
                    selectedMemberDiv.appendChild(memberCard);
                }
            }

            window.removeAssignedMember = function () {
                assignedToSelect.value = '';
                updateSelectedMemberDisplay('');
            };

            const avatarInput = document.getElementById('task_avater');
            const avatarInfo = document.querySelector('#task_avater + .file-info .file-placeholder');

            if (avatarInput && avatarInfo) {
                avatarInput.addEventListener('change', function () {
                    if (this.files.length > 0) {
                        avatarInfo.textContent = this.files[0].name;
                    } else {
                        avatarInfo.textContent = '{{ $task->task_avater ? 'Choose new file to replace current avatar' : 'No File chosen' }}';
                    }
                });
            }

            const attachmentInput = document.getElementById('task_attachment');
            const attachmentInfo = document.querySelector('#task_attachment + .file-info .file-placeholder');
            const selectedFilesDiv = document.getElementById('selectedFiles');
            const filesListDiv = document.getElementById('filesList');

            if (attachmentInput && attachmentInfo && selectedFilesDiv && filesListDiv) {
                attachmentInput.addEventListener('change', function () {
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

            window.removeSelectedFile = function (index) {
                const dt = new DataTransfer();
                const input = document.getElementById('task_attachment');

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
                removeAvatarCheckbox.addEventListener('change', function () {
                    if (avatarInput) {
                        if (this.checked) {
                            avatarInput.disabled = true;
                            if (avatarInfo) avatarInfo.textContent = 'Avatar will be removed';
                        } else {
                            avatarInput.disabled = false;
                            if (avatarInfo) avatarInfo.textContent = 'Choose new file to replace current avatar';
                        }
                    }
                });
            }

            const statusSelect = document.getElementById('task_status');
            if (statusSelect) {
                statusSelect.addEventListener('change', function () {
                    if (this.value == 2) {
                        if (confirm('Are you sure you want to mark this task as completed? This will set the completion date and calculate overdue days.')) {
                        } else {
                            this.value = '{{ old('task_status', $task->task_status) }}';
                        }
                    }
                });
            }


               document.getElementById('taskForm').addEventListener('submit', function (e) {
                const today = new Date().toISOString().split('T')[0];
                const deadline = document.getElementById('task_deadline').value;

                if (deadline && deadline < today) {
                    e.preventDefault();

                            Toastify({
            text: "Task deadline cannot be in the past.",
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

                const assignedTo = document.getElementById('task_assignedto').value;
                if (!assignedTo) {
                    e.preventDefault();

                            Toastify({
            text: "Please select an assigned person.",
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


            updateSelectedMemberDisplay(assignedToSelect.value);
        });
    </script>
</x-kanbandashboardlayout>
