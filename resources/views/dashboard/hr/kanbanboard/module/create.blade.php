<x-kanbandashboardlayout>
    @section('title', 'Create Module')

    <div class="container-fluid p-4">
        <!-- Header & Breadcrumb -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                @if ($project)
                    <div class="alert alert-info" style="font-size: 1.1em;">
                        <i class="bi bi-info-circle me-2"></i>
                        Creating module for project: <span class="text-primary">
                            <strong>{{ $project->pro_name }}</strong></span>
                    </div>
                @endif
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
                        <form action="{{ route('modulostore') }}" method="POST" enctype="multipart/form-data"
                            id="moduloForm">
                            @csrf

                            <!-- Hidden project field -->
                            <input type="hidden" name="mod_project" id="mod_project"
                                value="{{ $project ? $project->pro_id : '' }}">

                            <!-- Project Info Section -->
                            @if ($project)
                                <div class="section-header mb-4">
                                    <h6 class="section-title">Project Information</h6>
                                    <div class="section-divider"></div>
                                </div>

                                <div class="card bg-light border-0 mb-4">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>Project Name:</strong> {{ $project->pro_name }}<br>
                                                <strong>Client:</strong>
                                                {{ $project->client ? $project->client->cl_name : 'N/A' }}<br>
                                                <strong>Project Head:</strong>
                                                {{ $project->projectHead ? $project->projectHead->fullname : 'N/A' }}
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Project Lead:</strong>
                                                {{ $project->projectLead ? $project->projectLead->fullname : 'N/A' }}<br>
                                                <strong>Deadline:</strong>
                                                {{ \Carbon\Carbon::parse($project->pro_deadline)->format('M d, Y') }}<br>
                                                <strong>Access:</strong>
                                                {{ $project->pro_accessmod ? 'Private' : 'Public' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Module Info Section -->
                            <div class="section-header mb-4">
                                <h6 class="section-title">Module Info</h6>
                                <div class="section-divider"></div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mod_name" class="form-label required">Module Name</label>
                                        <input type="text"
                                            class="form-control @error('mod_name') is-invalid @enderror" id="mod_name"
                                            name="mod_name" value="{{ old('mod_name') }}"
                                            placeholder="Enter module name">
                                        @error('mod_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mod_deadline" class="form-label required">Module Deadline</label>
                                        <input type="date"
                                            class="form-control @error('mod_deadline') is-invalid @enderror"
                                            id="mod_deadline" name="mod_deadline" value="{{ old('mod_deadline') }}">
                                        @error('mod_deadline')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="mod_desc" class="form-label required">Module Description</label>
                                        <textarea class="form-control @error('mod_desc') is-invalid @enderror" id="mod_desc" name="mod_desc" rows="3"
                                            placeholder="Enter module description">{{ old('mod_desc') }}</textarea>
                                        @error('mod_desc')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Access Mode Field -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label required">Access Mode</label>
                                        <div class="border rounded p-3 bg-light">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            name="mod_accessmod" id="access_public" value="0"
                                                            {{ old('mod_accessmod') == '0' ? 'checked' : '' }}>
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
                                                            name="mod_accessmod" id="access_private" value="1"
                                                            {{ old('mod_accessmod') == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="access_private">
                                                            <span class="fw-semibold">Private</span>
                                                            <small class="d-block text-muted">Restricted access
                                                                only</small>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @error('mod_accessmod')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Module Avatar Section -->
                            <div class="section-header mb-4">
                                <h6 class="section-title">Module Avatar</h6>
                                <div class="section-divider"></div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="mod_avater" class="form-label">Module Avatar (Single Image)</label>
                                        <div class="file-input-wrapper">
                                            <input type="file"
                                                class="form-control file-input @error('mod_avater') is-invalid @enderror"
                                                id="mod_avater" name="mod_avater" accept=".jpg,.jpeg,.png">
                                            <div class="file-info">
                                                <span class="file-placeholder">No File chosen</span>
                                            </div>
                                        </div>
                                        <div class="form-text mt-2">
                                            Allowed Format: JPG, JPEG, PNG only. Max size: 5MB
                                        </div>
                                        @error('mod_avater')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Module Attachments Section -->
                            <div class="section-header mb-4">
                                <h6 class="section-title">Module Attachments</h6>
                                <div class="section-divider"></div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="mod_attachment" class="form-label">Module Attachments (Multiple
                                            Files)</label>
                                        <div class="file-input-wrapper">
                                            <input type="file"
                                                class="form-control file-input @error('mod_attachment') is-invalid @enderror"
                                                id="mod_attachment" name="mod_attachment[]"
                                                accept=".jpg,.jpeg,.png,.pdf,.ppt,.pptx,.csv,.xlsx,.xls,.doc,.docx"
                                                multiple>
                                            <div class="file-info">
                                                <span class="file-placeholder">No Files chosen</span>
                                            </div>
                                        </div>
                                        <div class="form-text mt-2">
                                            Allowed Files: JPG, JPEG, PNG, PDF, PPT, CSV, Excel, Word documents. Max
                                            size: 10MB per file
                                        </div>
                                        @error('mod_attachment')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror

                                        <!-- Selected files preview -->
                                        <div id="selectedFiles" class="selected-files mt-3" style="display: none;">
                                            <label class="sub-label">Selected Files:</label>
                                            <div id="filesList" class="files-list"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Project Members Section -->
                            <div class="section-header mb-4">
                                <h6 class="section-title">Team Members</h6>
                                <div class="section-divider"></div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label required">Select Team Members</label>

                                        @if ($project && count($projectMembers) > 0)
                                            <div class="alert alert-info mb-3">
                                                <i class="bi bi-info-circle me-2"></i>
                                                Showing members from project: <strong>{{ $project->pro_name }}</strong>
                                            </div>

                                            <!-- Dropdown with Checkboxes -->
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
                                                    @foreach ($projectMembers as $member)
                                                        <li class="dropdown-checkbox-item">
                                                            <div class="form-check">
                                                                <input class="form-check-input member-checkbox-input"
                                                                    type="checkbox" name="mod_member[]"
                                                                    value="{{ $member->emp_id }}"
                                                                    id="member_{{ $member->emp_id }}"
                                                                    {{ in_array($member->emp_id, old('mod_member', [])) ? 'checked' : '' }}>
                                                                <label
                                                                    class="form-check-label d-flex align-items-center w-100"
                                                                    for="member_{{ $member->emp_id }}">
                                                                    <img src="{{ $member->image ? asset('employee_images/' . $member->image) : asset('images/admin_default.jpg') }}"
                                                                        class="employee-avatar me-3"
                                                                        style="width: 40px; height: 40px; border-radius: 50%;">
                                                                    <div class="flex-grow-1">
                                                                        <div class="fw-semibold">
                                                                            {{ $member->fullname }}</div>
                                                                        <small
                                                                            class="text-muted">{{ $member->employee_id }}
                                                                            • {{ $member->email }}</small>
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
                                                No project members found or no project selected.
                                            </div>
                                        @endif

                                        @error('mod_member')
                                            <div class="error-message">{{ $message }}</div>
                                        @enderror

                                        <div class="selected-members-section mt-3">
                                            <label class="sub-label">Selected Team Members</label>
                                            <div id="selected_members" class="selected-members-list">
                                                <!-- Selected members will appear here -->
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
                    <small class="d-block text-muted">Send module details to all team members</small>
                </label>
            </div>
        </div>
    </div>
</div>

                            <!-- Form Actions -->
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-plus-lg me-2"></i>Create Module
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Reuse the same CSS styles from project create form */
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

        /* Keep dropdown open on click */
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

        /* Custom dropdown button styling */
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
    </style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize selected members set
        let selectedMembers = new Set();
        const selectedMembersDiv = document.getElementById('selected_members');
        const dropdownText = document.getElementById('dropdownText');
        const selectAllCheckbox = document.getElementById('selectAllMembers');
        const memberCheckboxes = document.querySelectorAll('.member-checkbox-input');

        // Initialize from old form data - FIXED SYNTAX
        @if(old('mod_member'))
            const oldMembers = {!! json_encode(old('mod_member')) !!};
            if (Array.isArray(oldMembers)) {
                oldMembers.forEach(memberId => {
                    const memberIdStr = memberId.toString();
                    selectedMembers.add(memberIdStr);
                    const checkbox = document.querySelector(`.member-checkbox-input[value="${memberIdStr}"]`);
                    if (checkbox) {
                        checkbox.checked = true;
                    }
                });
            }
        @endif

        // Additional click handling for labels
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

        // Update dropdown text and selected members display
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

        // Update selected members display
        function updateSelectedMembersDisplay() {
            if (!selectedMembersDiv) return;

            selectedMembersDiv.innerHTML = '';

            if (selectedMembers.size === 0) {
                selectedMembersDiv.innerHTML = '<div class="text-muted">No members selected</div>';
                return;
            }

            // Get member details and create cards
            selectedMembers.forEach(memberId => {
                const checkbox = document.querySelector(`.member-checkbox-input[value="${memberId}"]`);

                if (checkbox) {
                    const label = checkbox.parentElement.querySelector('.form-check-label');
                    if (label) {
                        const avatar = label.querySelector('.employee-avatar')?.src || '';
                        const name = label.querySelector('.fw-semibold')?.textContent || '';
                        const employeeId = (label.querySelector('.text-muted')?.textContent.split('•')[0] || '').trim();

                        const memberCard = document.createElement('div');
                        memberCard.className = 'member-card';
                        memberCard.innerHTML = `
                            <img src="${avatar}" class="member-avatar" alt="${name}">
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
                        // Fallback if label not found
                        createFallbackCard(memberId);
                    }
                } else {
                    // Fallback if checkbox not found
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

        // Update select all checkbox state
        function updateSelectAllCheckbox() {
            if (!selectAllCheckbox) return;

            if (selectedMembers.size === 0) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            } else if (selectedMembers.size === memberCheckboxes.length) {
                selectAllCheckbox.checked = true;
                selectAllCheckbox.indeterminate = false;
            } else {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = true;
            }
        }

        // Member checkbox handling
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

        // Select all functionality
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                memberCheckboxes.forEach(checkbox => {
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

        // Global function to remove member
        window.removeMember = function(memberId) {
            selectedMembers.delete(memberId);

            // Uncheck the checkbox
            const checkbox = document.querySelector(`.member-checkbox-input[value="${memberId}"]`);
            if (checkbox) {
                checkbox.checked = false;
            }

            updateSelectionDisplay();
        };

        // Prevent dropdown from closing when clicking inside
        const dropdownMenu = document.querySelector('.dropdown-checkbox-menu');
        if (dropdownMenu) {
            dropdownMenu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }

        // File input display for avatar
        const avatarInput = document.getElementById('mod_avater');
        const avatarInfo = document.querySelector('#mod_avater + .file-info .file-placeholder');

        if (avatarInput && avatarInfo) {
            avatarInput.addEventListener('change', function() {
                if (this.files.length > 0) {
                    avatarInfo.textContent = this.files[0].name;
                } else {
                    avatarInfo.textContent = 'No File chosen';
                }
            });
        }

        // File input display for attachments
        const attachmentInput = document.getElementById('mod_attachment');
        const attachmentInfo = document.querySelector('#mod_attachment + .file-info .file-placeholder');
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
                    attachmentInfo.textContent = 'No Files chosen';
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
        window.removeSelectedFile = function(index) {
            const dt = new DataTransfer();
            const input = document.getElementById('mod_attachment');

            for (let i = 0; i < input.files.length; i++) {
                if (i !== index) {
                    dt.items.add(input.files[i]);
                }
            }

            input.files = dt.files;
            input.dispatchEvent(new Event('change'));
        };

        // Form validation
        document.getElementById('moduloForm').addEventListener('submit', function(e) {
            const today = new Date().toISOString().split('T')[0];
            const deadline = document.getElementById('mod_deadline').value;

            if (deadline && deadline < today) {
                e.preventDefault();
                Toastify({
                    text: "Module deadline cannot be in the past.",
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
                    text: "Please add at least one module member.",
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

        // Initialize display - add delay to ensure DOM is ready
        setTimeout(() => {
            updateSelectionDisplay();
        }, 100);
    });
</script>
</x-kanbandashboardlayout>
