<x-kanbandashboardlayout>
    @section('title', 'Edit Subtask')

    <div class="container-fluid p-4">
        <!-- Header & Breadcrumb -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-2">Edit Subtask</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dhome') }}" class="text-decoration-none text-muted">
                                <i class="bi bi-house me-2"></i>Home
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('subtasklist') }}" class="text-decoration-none text-muted">
                                <i class="bi bi-check2-square me-2"></i>Subtask
                            </a>
                        </li>
                        <li class="breadcrumb-item active">Edit Subtask</li>
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
                        <form action="{{ route('subtaskupdate', $subtask->stask_id) }}" method="POST"
                            enctype="multipart/form-data" id="subtaskForm">
                            @csrf
                            @method('PUT')

                            <!-- Task Info Section -->
                            @if ($subtask->task)
                                <div class="section-header mb-4">
                                    <h6 class="section-title">Task Information</h6>
                                    <div class="section-divider"></div>
                                </div>

                                <div class="card bg-light border-0 mb-4">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>Task Name:</strong> {{ $subtask->task->task_name }}<br>
                                                <strong>Modulo:</strong>
                                                {{ $subtask->task->modulo ? $subtask->task->modulo->mod_name : 'N/A' }}<br>
                                                <strong>Project:</strong>
                                                {{ $subtask->task->modulo && $subtask->task->modulo->project ? $subtask->task->modulo->project->pro_name : 'N/A' }}
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Task Deadline:</strong>
                                                {{ \Carbon\Carbon::parse($subtask->task->task_deadline)->format('M d, Y') }}<br>
                                                <strong>Priority:</strong> <span
                                                    class="badge {{ $subtask->task->priority_class }}">{{ $subtask->task->priority_text }}</span><br>
                                                <strong>Access:</strong>
                                                {{ $subtask->task->task_accessmod ? 'Private' : 'Public' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Subtask Info Section -->
                            <div class="section-header mb-4">
                                <h6 class="section-title">Subtask Info</h6>
                                <div class="section-divider"></div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="stask_name" class="form-label required">Subtask Name</label>
                                        <input type="text"
                                            class="form-control @error('stask_name') is-invalid @enderror"
                                            id="stask_name" name="stask_name"
                                            value="{{ old('stask_name', $subtask->stask_name) }}"
                                            placeholder="Enter subtask name">
                                        @error('stask_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="stask_deadline" class="form-label required">Subtask Deadline</label>
                                        <input type="date"
                                            class="form-control @error('stask_deadline') is-invalid @enderror"
                                            id="stask_deadline" name="stask_deadline"
                                            value="{{ old('stask_deadline', \Carbon\Carbon::parse($subtask->stask_deadline)->format('Y-m-d')) }}">
                                        @error('stask_deadline')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="stask_desc" class="form-label required">Subtask Description</label>
                                        <textarea class="form-control @error('stask_desc') is-invalid @enderror"
                                            id="stask_desc" name="stask_desc" rows="3"
                                            placeholder="Enter subtask description">{{ old('stask_desc', $subtask->stask_desc) }}</textarea>
                                        @error('stask_desc')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Status Field -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="stask_status" class="form-label required">Subtask Status</label>
                                        <select class="form-control @error('stask_status') is-invalid @enderror"
                                            id="stask_status" name="stask_status">
                                            <option value="0" {{ old('stask_status', $subtask->stask_status) == 0 ? 'selected' : '' }}>Created</option>
                                            <option value="1" {{ old('stask_status', $subtask->stask_status) == 1 ? 'selected' : '' }}>On Progress</option>
                                            <option value="2" {{ old('stask_status', $subtask->stask_status) == 2 ? 'selected' : '' }}>Completed</option>
                                        </select>
                                        @error('stask_status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                        <!-- Status Dates Display -->
                                        <div class="status-dates mt-2">
                                            @if ($subtask->stask_onprogress)
                                                <small class="text-muted">
                                                    <i class="bi bi-play-circle me-1"></i>
                                                    Started:
                                                    {{ \Carbon\Carbon::parse($subtask->stask_onprogress)->format('M d, Y') }}
                                                </small>
                                            @endif
                                            @if ($subtask->stask_complete)
                                                <br>
                                                <small class="text-muted">
                                                    <i class="bi bi-check-circle me-1"></i>
                                                    Completed:
                                                    {{ \Carbon\Carbon::parse($subtask->stask_complete)->format('M d, Y') }}
                                                </small>
                                            @endif
                                            @if ($subtask->stask_overdue > 0)
                                                <br>
                                                <small class="text-danger">
                                                    <i class="bi bi-clock me-1"></i>
                                                    Overdue: {{ $subtask->overdue_text }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Access Mode Field -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label required">Access Mode</label>
                                        <div class="border rounded p-3 bg-light">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            name="stask_accessmod" id="access_public" value="0" {{ old('stask_accessmod', $subtask->stask_accessmod) == '0' ? 'checked' : '' }}>
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
                                                            name="stask_accessmod" id="access_private" value="1" {{ old('stask_accessmod', $subtask->stask_accessmod) == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="access_private">
                                                            <span class="fw-semibold">Private</span>
                                                            <small class="d-block text-muted">Restricted access
                                                                only</small>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @error('stask_accessmod')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Subtask Priority Field -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label required">Subtask Priority</label>
                                        <div class="border rounded p-3 bg-light">
                                            <div class="row">
                                                <!-- Low -->
                                                <div class="col-md-4">
                                                    <div class="form-check">
                                                        <input
                                                            class="form-check-input @error('stask_priority') is-invalid @enderror"
                                                            type="radio" name="stask_priority" id="priority_low"
                                                            value="1" {{ old('stask_priority', $subtask->stask_priority) == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="priority_low">
                                                            <span class="fw-semibold">Low</span>
                                                            <small class="d-block text-muted">Minor importance</small>
                                                        </label>
                                                    </div>
                                                </div>

                                                <!-- Medium -->
                                                <div class="col-md-4">
                                                    <div class="form-check">
                                                        <input
                                                            class="form-check-input @error('stask_priority') is-invalid @enderror"
                                                            type="radio" name="stask_priority" id="priority_medium"
                                                            value="2" {{ old('stask_priority', $subtask->stask_priority) == '2' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="priority_medium">
                                                            <span class="fw-semibold">Medium</span>
                                                            <small class="d-block text-muted">Normal urgency</small>
                                                        </label>
                                                    </div>
                                                </div>

                                                <!-- High -->
                                                <div class="col-md-4">
                                                    <div class="form-check">
                                                        <input
                                                            class="form-check-input @error('stask_priority') is-invalid @enderror"
                                                            type="radio" name="stask_priority" id="priority_high"
                                                            value="3" {{ old('stask_priority', $subtask->stask_priority) == '3' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="priority_high">
                                                            <span class="fw-semibold">High</span>
                                                            <small class="d-block text-muted">Requires quick
                                                                attention</small>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @error('stask_priority')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Subtask Avatar Section -->
                            <div class="section-header mb-4">
                                <h6 class="section-title">Subtask Avatar</h6>
                                <div class="section-divider"></div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="stask_avater" class="form-label">Subtask Avatar (Single
                                            Image)</label>

                                        <!-- Current Avatar Display -->
                                        @if ($subtask->stask_avater)
                                            <div class="current-attachments-section mb-3">
                                                <label class="sub-label">Current Avatar</label>
                                                <div class="current-attachments-list d-flex flex-wrap gap-3">
                                                    @php
                                                        $avatarFileName = basename($subtask->stask_avater);
                                                        $fileExtension = strtolower(pathinfo($avatarFileName, PATHINFO_EXTENSION));
                                                        $fileUrl = asset($subtask->stask_avater);
                                                        $isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']);
                                                    @endphp

                                                    <div class="current-file-item row p-3 ms-1 rounded border">
                                                        <div class="col-auto mb-2">
                                                            @if ($isImage)
                                                                <!-- Show image preview for avatar -->
                                                                <img src="{{ $fileUrl }}" class="attachment-preview"
                                                                    style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;"
                                                                    alt="{{ $avatarFileName }}"
                                                                    onerror="this.src='{{ asset('images/admin_default.jpg') }}'">
                                                            @else
                                                                <!-- File icon for non-image files (shouldn't happen for avatar) -->
                                                                <div class="file-icon">
                                                                    <i class="bi bi-file-image text-primary"
                                                                        style="font-size: 4em;"></i>
                                                                </div>
                                                            @endif
                                                        </div>

                                                        <div class="col-auto">
                                                            <!-- Action buttons -->
                                                            <div
                                                                class="current-file-actions d-flex flex-column gap-2 w-100">
                                                                <!-- View Button -->
                                                                <a href="{{ $fileUrl }}" target="_blank"
                                                                    class="btn btn-sm btn-outline-primary">
                                                                    View
                                                                </a>

                                                                <!-- Remove Checkbox -->
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
                                                No avatar set for this subtask.
                                            </div>
                                        @endif

                                        <!-- File Input for New Avatar -->
                                        <div class="file-input-wrapper">
                                            <input type="file"
                                                class="form-control file-input @error('stask_avater') is-invalid @enderror"
                                                id="stask_avater" name="stask_avater" accept=".jpg,.jpeg,.png">
                                            <div class="file-info">
                                                <span class="file-placeholder">
                                                    @if ($subtask->stask_avater)
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
                                        @error('stask_avater')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Subtask Attachments Section -->
                            <div class="section-header mb-4">
                                <h6 class="section-title">Subtask Attachments</h6>
                                <div class="section-divider"></div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="stask_attachment" class="form-label">Subtask Attachments (Multiple
                                            Files)</label>

                                        <!-- Current Attachments Display -->
                                        @if ($subtask->pmtsImages && $subtask->pmtsImages->count() > 0)
                                            <div class="current-attachments-section mb-3">
                                                <label class="sub-label">Current Attachments</label>
                                                <div class="current-attachments-list d-flex flex-wrap gap-3">
                                                    @foreach ($subtask->pmtsImages as $attachment)
                                                        @php
                                                            $fileExtension = strtolower(pathinfo($attachment->pmtsimage_name, PATHINFO_EXTENSION));
                                                            $fileName = $attachment->pmtsimage_name;
                                                            $fileUrl = asset('subtask_attachments/' . $fileName);
                                                            $isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']);
                                                        @endphp

                                                        <div class="current-file-item row ms-1 p-3 rounded border">
                                                            <div class="col-auto mb-2">
                                                                @if ($isImage)
                                                                    <!-- Show image preview for image files -->
                                                                    <img src="{{ $fileUrl }}" class="attachment-preview"
                                                                        style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px;"
                                                                        alt="{{ $fileName }}"
                                                                        onerror="this.src='{{ asset('images/admin_default.jpg') }}'">
                                                                @else
                                                                    <!-- File icon for non-image files -->
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
                                                                <!-- Action buttons -->
                                                                <div
                                                                    class="current-file-actions d-flex flex-column gap-1 w-100">
                                                                    <!-- View Button -->
                                                                    <a href="{{ $fileUrl }}" target="_blank"
                                                                        class="btn btn-sm btn-outline-primary">
                                                                        View
                                                                    </a>

                                                                    <!-- Remove Checkbox -->
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
                                                No attachments found for this subtask.
                                            </div>
                                        @endif

                                        <!-- File Input for New Attachments -->
                                        <div class="file-input-wrapper">
                                            <input type="file"
                                                class="form-control file-input @error('stask_attachment') is-invalid @enderror"
                                                id="stask_attachment" name="stask_attachment[]"
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
                                        @error('stask_attachment')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror

                                        <!-- Selected files preview -->
                                        <div id="selectedFiles" class="selected-files mt-3" style="display: none;">
                                            <label class="sub-label">New Selected Files:</label>
                                            <div id="filesList" class="files-list"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Assigned Person Section -->
                            <div class="section-header mb-4">
                                <h6 class="section-title">Assigned Person</h6>
                                <div class="section-divider"></div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="stask_assignedto" class="form-label required">Assign To</label>

                                        @if ($subtask->task && count($availableMembers) > 0)
                                            <div class="alert alert-info mb-3">
                                                <i class="bi bi-info-circle me-2"></i>
                                                @if($subtask->task->task_member)
                                                    Showing members from task: <strong>{{ $subtask->task->task_name }}</strong>
                                                @else
                                                    Showing members from modulo: <strong>{{ $subtask->task->modulo->mod_name ?? 'N/A' }}</strong>
                                                @endif
                                            </div>

                                            <select class="form-control @error('stask_assignedto') is-invalid @enderror"
                                                id="stask_assignedto" name="stask_assignedto">
                                                <option value="">Select a person to assign...</option>
                                                @foreach ($availableMembers as $member)
                                                    <option value="{{ $member->emp_id }}"
                                                        {{ old('stask_assignedto', $subtask->stask_assignedto) == $member->emp_id ? 'selected' : '' }}>
                                                        {{ $member->fullname }} ({{ $member->employee_id }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            <div class="alert alert-warning">
                                                <i class="bi bi-exclamation-triangle me-2"></i>
                                                No available members found for assignment.
                                            </div>
                                        @endif

                                        @error('stask_assignedto')
                                            <div class="error-message">{{ $message }}</div>
                                        @enderror

                                        <div class="selected-member-section mt-3">
                                            <label class="sub-label">Selected Assign to</label>
                                            <div id="selected_member" class="selected-member-display">
                                                <!-- Selected member will appear here -->
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
                    <small class="d-block text-muted">Send subtask details to assigned person</small>
                </label>
            </div>
        </div>
    </div>
</div>
                            <!-- Form Actions -->
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-2"></i>Update Subtask
                                </button>
                                <a href="{{ route('subtasklist') }}" class="btn btn-secondary">
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
            const assignedToSelect = document.getElementById('stask_assignedto');
            const selectedMemberDiv = document.getElementById('selected_member');

            // Initialize selected member display
            updateSelectedMemberDisplay(assignedToSelect.value);

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

            // File input display for avatar
            const avatarInput = document.getElementById('stask_avater');
            const avatarInfo = document.querySelector('#stask_avater + .file-info .file-placeholder');

            if (avatarInput && avatarInfo) {
                avatarInput.addEventListener('change', function () {
                    if (this.files.length > 0) {
                        avatarInfo.textContent = this.files[0].name;
                    } else {
                        avatarInfo.textContent = '{{ $subtask->stask_avater ? 'Choose new file to replace current avatar' : 'No File chosen' }}';
                    }
                });
            }

            // File input display for attachments
            const attachmentInput = document.getElementById('stask_attachment');
            const attachmentInfo = document.querySelector('#stask_attachment + .file-info .file-placeholder');
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

            // Format file size
            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            // Global function to remove selected file
            window.removeSelectedFile = function (index) {
                const dt = new DataTransfer();
                const input = document.getElementById('stask_attachment');

                for (let i = 0; i < input.files.length; i++) {
                    if (i !== index) {
                        dt.items.add(input.files[i]);
                    }
                }

                input.files = dt.files;
                input.dispatchEvent(new Event('change'));
            };

            // Handle remove avatar checkbox
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

            // Status change handler
            const statusSelect = document.getElementById('stask_status');
            if (statusSelect) {
                statusSelect.addEventListener('change', function () {
                    if (this.value == 2) {
                        if (confirm('Are you sure you want to mark this subtask as completed? This will set the completion date and calculate overdue days.')) {
                            // Proceed with form submission
                        } else {
                            this.value = '{{ old('stask_status', $subtask->stask_status) }}';
                        }
                    }
                });
            }






              document.getElementById('subtaskForm').addEventListener('submit', function (e) {
                const today = new Date().toISOString().split('T')[0];
                const deadline = document.getElementById('stask_deadline').value;

                if (deadline && deadline < today) {
                    e.preventDefault();

                      Toastify({
            text: "Subtask deadline cannot be in the past.",
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

                const assignedTo = document.getElementById('stask_assignedto').value;
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

        });
    </script>
</x-kanbandashboardlayout>
