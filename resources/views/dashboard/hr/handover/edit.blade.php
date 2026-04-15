<x-layout>
    @section('title', 'Edit Employee Handover Form')
    <div class="container-fluid py-4 px-4">
        <!-- Header & Breadcrumb -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-2">Edit Employee Handover Form</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0">
                        <li class="breadcrumb-item">
                            <a href="#" class="text-decoration-none text-muted">
                                <i class="bi bi-people-fill me-2"></i>Employment
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('handoverlist') }}" class="text-decoration-none text-muted">Handover</a>
                        </li>
                        <li class="breadcrumb-item active text-primary" aria-current="page">Edit Handover</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('handoverlist') }}" class="btn btn-outline-primary d-flex align-items-center">
                <i class="bi bi-arrow-left-circle me-2"></i>Back
            </a>
        </div>

        <x-message />
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('handover.update', $handover->handover_id) }}" method="POST"
                    enctype="multipart/form-data" id="handoverForm">
                    @csrf
                    @method('PUT')

                    <!-- Handing Over Employee Section -->
                    <div class="row mb-4">
                        <h5 class="mb-3 border-bottom pb-2">Handed over by</h5>
                        <input type="hidden" name="handover_employee_id" id="handover_employee_id"
                            value="{{ old('handover_employee_id', $handover->handover_employee_id) }}">
                        <input type="hidden" name="handover_department_id" id="handover_department_id"
                            value="{{ old('handover_department_id', $handover->handover_department) }}">
                        <input type="hidden" name="handover_designation_id" id="handover_designation_id"
                            value="{{ old('handover_designation_id', $handover->handover_designation) }}">

                        {{-- left side --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="handover_employee_id_display" class="form-label">Employee Id</label>
                                <input type="text" class="form-control" id="handover_employee_id_display" readonly
                                    value="{{ old('handover_employee_id_display', $handover->handoverEmployee->employee_id ?? '') }}">
                            </div>

                            <div class="mb-3">
                                <label for="handover_employee_email" class="form-label">Employee Email</label>
                                <input type="email" class="form-control" id="handover_employee_email"
                                    name="handover_employee_email" readonly
                                    value="{{ old('handover_employee_email', $handover->handover_employee_email) }}">
                            </div>

                            <div class="mb-3">
                                <label for="handover_department" class="form-label">Department</label>
                                <input type="text" class="form-control" id="handover_department"
                                    name="handover_department_display" readonly
                                    value="{{ old('handover_department_display', $handover->handoverDepartmentRelation->dep_name ?? '') }}">
                            </div>

                            <div class="mb-3">
                                <label for="handover_designation" class="form-label">Designation</label>
                                <input type="text" class="form-control" id="handover_designation"
                                    name="handover_designation_display" readonly
                                    value="{{ old('handover_designation_display', $handover->handoverDesignationRelation->des_name ?? '') }}">
                            </div>
                        </div>

                        {{-- right side   --}}
                        <div class="col-md-6">
                            <div class="text-center mb-5">
                                <img src="{{ $handover->handoverEmployee->image ? asset('employee_images/' . $handover->handoverEmployee->image) : asset('images/admin_default.jpg') }}"
                                    alt="" class="avatar-rounded" id="handover_employee_image"
                                    style="width: 120px; height: 120px;">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Search the Name</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="handover_employee_search"
                                        placeholder="Search by name or ID"
                                        value="{{ old('handover_employee_search', $handover->handoverEmployee->fullname ?? '') }}">
                                    <button class="btn btn-outline-secondary" type="button"
                                        id="handover_search_button">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                                <ul id="handover_employee_results" class="list-group mt-2" style="display: none;"></ul>
                            </div>

                            <div class="mb-3">
                                <label for="handover_full_name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="handover_full_name"
                                    name="handover_full_name" readonly
                                    value="{{ old('handover_full_name', $handover->handoverEmployee->fullname ?? '') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Taken Over by Section -->
                    <div class="row mb-4">
                        <h5 class="mb-3 border-bottom pb-2">Taken over by</h5>
                        <input type="hidden" name="takeover_employee_id" id="takeover_employee_id"
                            value="{{ old('takeover_employee_id', $handover->takeover_employee_id) }}">
                        <input type="hidden" name="takeover_department_id" id="takeover_department_id"
                            value="{{ old('takeover_department_id', $handover->takeover_department) }}">
                        <input type="hidden" name="takeover_designation_id" id="takeover_designation_id"
                            value="{{ old('takeover_designation_id', $handover->takeover_designation) }}">

                        {{-- left side --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="takeover_employee_id_display" class="form-label">Employee Id</label>
                                <input type="text" class="form-control" id="takeover_employee_id_display" readonly
                                    value="{{ old('takeover_employee_id_display', $handover->takeoverEmployee->employee_id ?? '') }}">
                            </div>

                            <div class="mb-3">
                                <label for="takeover_employee_email" class="form-label">Employee Email</label>
                                <input type="email" class="form-control" id="takeover_employee_email"
                                    name="takeover_employee_email" readonly
                                    value="{{ old('takeover_employee_email', $handover->takeover_employee_email) }}">
                            </div>

                            <div class="mb-3">
                                <label for="takeover_department" class="form-label">Department</label>
                                <input type="text" class="form-control" id="takeover_department"
                                    name="takeover_department_display" readonly
                                    value="{{ old('takeover_department_display', $handover->takeoverDepartmentRelation->dep_name ?? '') }}">
                            </div>

                            <div class="mb-3">
                                <label for="takeover_designation" class="form-label">Designation</label>
                                <input type="text" class="form-control" id="takeover_designation"
                                    name="takeover_designation_display" readonly
                                    value="{{ old('takeover_designation_display', $handover->takeoverDesignationRelation->des_name ?? '') }}">
                            </div>

                            <!-- Reason for Handover Section -->
                            <div class="mb-3">
                                <label class="form-label">Reason for handover</label>
                                <div class="d-flex flex-wrap gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="reason"
                                            id="reason_vacation" value="1"
                                            {{ old('reason', $handover->reason) == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="reason_vacation">Vacation</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="reason"
                                            id="reason_end" value="2"
                                            {{ old('reason', $handover->reason) == '2' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="reason_end">End of Employment</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="reason"
                                            id="reason_transfer" value="3"
                                            {{ old('reason', $handover->reason) == '3' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="reason_transfer">Transfer</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="reason"
                                            id="reason_termination" value="4"
                                            {{ old('reason', $handover->reason) == '4' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="reason_termination">Termination</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="reason"
                                            id="reason_other_radio" value="0"
                                            {{ old('reason', $handover->reason) == '0' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="reason_other_radio">Other</label>
                                    </div>
                                </div>
                                @error('reason')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- right side --}}
                        <div class="col-md-6">
                            <div class="text-center mb-5">
                                <img src="{{ $handover->takeoverEmployee->image ? asset('employee_images/' . $handover->takeoverEmployee->image) : asset('images/admin_default.jpg') }}"
                                    alt="" class="avatar-rounded" id="takeover_employee_image"
                                    style="width: 120px; height: 120px;">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Search the Name</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="takeover_employee_search"
                                        placeholder="Search by name or ID"
                                        value="{{ old('takeover_employee_search', $handover->takeoverEmployee->fullname ?? '') }}">
                                    <button class="btn btn-outline-secondary" type="button"
                                        id="takeover_search_button">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                                <ul id="takeover_employee_results" class="list-group mt-2" style="display: none;">
                                </ul>
                            </div>

                            <div class="mb-3">
                                <label for="takeover_full_name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="takeover_full_name"
                                    name="takeover_full_name" readonly
                                    value="{{ old('takeover_full_name', $handover->takeoverEmployee->fullname ?? '') }}">
                            </div>

                            <!-- Other Reason Input (Shown only when "Other" is selected) -->
                            <div id="other_reason_container"
                                style="display: {{ old('reason', $handover->reason) == '0' ? 'block' : 'none' }};">
                                <label for="reason_other_text" class="form-label">Please specify the reason</label>
                                <input type="text" class="form-control" id="reason_other_text"
                                    name="reason_other" value="{{ old('reason_other', $handover->reason_other) }}"
                                    placeholder="Enter the reason for handover">
                                @error('reason_other')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Task Details Section -->
                    <h5 class="mb-3 border-bottom pb-2">Task Details</h5>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered" id="tasksTable">
                            <thead class="table-primary">
                                <tr class="text-center">
                                    <th width="5%">Sr. No</th>
                                    <th width="10%">Task No</th>
                                    <th width="30%">Task name</th>
                                    <th width="15%">Priority</th>
                                    <th width="15%">Status</th>
                                    <th width="15%">Due Date</th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $tasks = old('tasks', $handover->tasks ? json_decode($handover->tasks, true) : []);
                                    $taskCount = count($tasks) > 0 ? count($tasks) : 1;
                                @endphp

                                @if ($taskCount > 0)
                                    @foreach ($tasks as $index => $task)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm"
                                                    name="tasks[{{ $index }}][task_no]"
                                                    value="{{ $task['task_no'] ?? '' }}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm"
                                                    name="tasks[{{ $index }}][task_name]"
                                                    value="{{ $task['task_name'] ?? '' }}">
                                            </td>
                                            <td>
                                                <select class="form-select form-select-sm"
                                                    name="tasks[{{ $index }}][priority]">
                                                    <option value="Low"
                                                        {{ ($task['priority'] ?? '') == 'Low' ? 'selected' : '' }}>Low
                                                    </option>
                                                    <option value="Medium"
                                                        {{ ($task['priority'] ?? '') == 'Medium' ? 'selected' : '' }}>
                                                        Medium</option>
                                                    <option value="High"
                                                        {{ ($task['priority'] ?? '') == 'High' ? 'selected' : '' }}>
                                                        High</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-select form-select-sm"
                                                    name="tasks[{{ $index }}][status]">
                                                    <option value="Not Started"
                                                        {{ ($task['status'] ?? '') == 'Not Started' ? 'selected' : '' }}>
                                                        Not Started</option>
                                                    <option value="In Progress"
                                                        {{ ($task['status'] ?? '') == 'In Progress' ? 'selected' : '' }}>
                                                        In Progress</option>
                                                    <option value="Completed"
                                                        {{ ($task['status'] ?? '') == 'Completed' ? 'selected' : '' }}>
                                                        Completed</option>
                                                    <option value="In UAT"
                                                        {{ ($task['status'] ?? '') == 'In UAT' ? 'selected' : '' }}>In
                                                        UAT</option>
                                                    <option value="Over Due"
                                                        {{ ($task['status'] ?? '') == 'Over Due' ? 'selected' : '' }}>
                                                        Over Due</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="date" class="form-control form-control-sm"
                                                    name="tasks[{{ $index }}][due_date]"
                                                    value="{{ $task['due_date'] ?? '' }}">
                                            </td>
                                            <td class="text-center">
                                                @if ($index === 0)
                                                    <button type="button"
                                                        class="btn btn-sm btn-primary add-task-row">
                                                        <i class="bi bi-plus"></i>
                                                    </button>
                                                @else
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger remove-task-row">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-center">1</td>
                                        <td><input type="text" class="form-control form-control-sm"
                                                name="tasks[0][task_no]"></td>
                                        <td><input type="text" class="form-control form-control-sm"
                                                name="tasks[0][task_name]"></td>
                                        <td>
                                            <select class="form-select form-select-sm" name="tasks[0][priority]">
                                                <option value="Low">Low</option>
                                                <option value="Medium">Medium</option>
                                                <option value="High">High</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-select form-select-sm" name="tasks[0][status]">
                                                <option value="Not Started">Not Started</option>
                                                <option value="In Progress">In Progress</option>
                                                <option value="Completed">Completed</option>
                                                <option value="In UAT">In UAT</option>
                                                <option value="Over Due">Over Due</option>
                                            </select>
                                        </td>
                                        <td><input type="date" class="form-control form-control-sm"
                                                name="tasks[0][due_date]"></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-primary add-task-row">
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- Documents Section -->
                    <h5 class="mb-3 border-bottom pb-2">If any other documents have to handover</h5>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="other_documents" class="form-label">Other Documents</label>
                                <div class="input-group">
                                    <input type="file" class="form-control" id="other_documents"
                                        name="other_documents">
                                </div>

                                @error('other_documents')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                                @if ($handover->other_documents)
                                    @php
                                        $filePath = asset($handover->other_documents);
                                        $extension = strtolower(
                                            pathinfo($handover->other_documents, PATHINFO_EXTENSION),
                                        );
                                    @endphp

                                    <div class="mt-2">
                                        <small>Current Other Document:</small>
                                        <div class="mt-1">
                                            @if (in_array($extension, ['jpg', 'jpeg', 'png']))
                                                <img src="{{ $filePath }}" alt="Other Document"
                                                    style="max-width: 200px; height: auto; border: 1px solid #ddd; border-radius: 6px;">
                                            @elseif ($extension === 'pdf')
                                                <div>
                                                    <a href="{{ $filePath }}" target="_blank"
                                                        class="btn btn-sm btn-outline-primary">
                                                        📄 View PDF
                                                    </a>
                                                </div>
                                                <embed src="{{ $filePath }}" type="application/pdf"
                                                    width="50%" height="200px" class="mt-2" />
                                            @else
                                                <a href="{{ $filePath }}" target="_blank"
                                                    class="btn btn-sm btn-outline-secondary">
                                                    Download File
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="resignation_documents" class="form-label">Resignation Document</label>
                                <div class="input-group">
                                    <input type="file" class="form-control" id="resignation_documents"
                                        name="resignation_documents">
                                </div>

                                @error('resignation_documents')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                                @if ($handover->resignation_documents)
                                    @php
                                        $filePath = asset($handover->resignation_documents);
                                        $extension = strtolower(
                                            pathinfo($handover->resignation_documents, PATHINFO_EXTENSION),
                                        );
                                    @endphp

                                    <div class="mt-2">
                                        <small>Current Resignation Document:</small>
                                        <div class="mt-1">
                                            @if (in_array($extension, ['jpg', 'jpeg', 'png']))
                                                <img src="{{ $filePath }}" alt="Resignation Document"
                                                    style="max-width: 200px; height: auto; border: 1px solid #ddd; border-radius: 6px;">
                                            @elseif ($extension === 'pdf')
                                                <div>
                                                    <a href="{{ $filePath }}" target="_blank"
                                                        class="btn btn-sm btn-outline-primary">
                                                        📄 View PDF
                                                    </a>
                                                </div>
                                                <embed src="{{ $filePath }}" type="application/pdf"
                                                    width="50%" height="200px" class="mt-2" />
                                            @else
                                                <a href="{{ $filePath }}" target="_blank"
                                                    class="btn btn-sm btn-outline-secondary">
                                                    Download File
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Signatures Section -->
                    <h5 class="mb-3 border-bottom pb-2">Signatures</h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="handover_date" class="form-label">Handover Date</label>
                                <input type="date" class="form-control" id="handover_date" name="handover_date"
                                    value="{{ old('handover_date', $handover->handover_date ? $handover->handover_date->format('Y-m-d') : '') }}"
                                    required>
                                @error('handover_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Handover Employee Signature</label>
                                <div id="handover-signature-pad" class="signature-pad border rounded"
                                    style="height: 150px; background-color: #f8f9fa; position:relative;">
                                    <canvas id="handover-signature-canvas"
                                        style="width: 100%; height: 100%;"></canvas>
                                    <i class="bi bi-x-circle text-danger" id="clear-handover-signature"
                                        style="position:absolute; top:5px; right:5px; cursor:pointer;"></i>
                                </div>
                                <input type="hidden" id="handover_signature_data" name="handover_signature_data"
                                    value="">
                                <input type="hidden" id="handover_signature_delete" name="handover_signature_delete"
                                    value="0">

                                @if ($handover->handover_signature)
                                    @php $handoverFilePath = asset($handover->handover_signature); @endphp
                                    <div class="mt-2">
                                        <small>Current Handover Signature:</small>
                                        <div class="mt-1">
                                            <img id="existing_handover_signature_preview"
                                                src="{{ $handoverFilePath }}" alt="handover_signature"
                                                style="max-width: 500px; height: auto; border: 1px solid #ddd; border-radius: 6px;">
                                        </div>
                                    </div>
                                @endif
                                @error('handover_signature')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Takeover Employee Signature</label>
                                <div id="takeover-signature-pad" class="signature-pad border rounded"
                                    style="height: 150px; background-color: #f8f9fa; position:relative;">
                                    <canvas id="takeover-signature-canvas"
                                        style="width: 100%; height: 100%;"></canvas>
                                    <i class="bi bi-x-circle text-danger" id="clear-takeover-signature"
                                        style="position:absolute; top:5px; right:5px; cursor:pointer;"></i>
                                </div>
                                <input type="hidden" id="takeover_signature_data" name="takeover_signature_data"
                                    value="">
                                <input type="hidden" id="takeover_signature_delete" name="takeover_signature_delete"
                                    value="0">

                                @if ($handover->takeover_signature)
                                    @php $takeoverFilePath = asset($handover->takeover_signature); @endphp
                                    <div class="mt-2">
                                        <small>Current Takeover Signature:</small>
                                        <div class="mt-1">
                                            <img id="existing_takeover_signature_preview"
                                                src="{{ $takeoverFilePath }}" alt="takeover_signature"
                                                style="max-width: 500px; height: auto; border: 1px solid #ddd; border-radius: 6px;">
                                        </div>
                                    </div>
                                @endif
                                @error('takeover_signature')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary px-4">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        input:read-only {
            background-color: #e5e5e5;
            cursor: not-allowed;
        }

        .form-control[type="file"]:not(:disabled):not([readonly]) {
            background-color: #fff;
        }

        .avatar-rounded {
            border-radius: 50%;
            width: 120px;
            height: 120px;
            object-fit: cover;
            border: 2px solid #dee2e6;
        }

        .signature-pad {
            position: relative;
        }

        .signature-canvas {
            background-color: #f8f9fa;
        }

        #clear-handover-signature,
        #clear-takeover-signature {
            position: absolute;
            right: 10px;
            top: 10px;
            font-size: 1.2em;
            cursor: pointer;
            z-index: 10;
        }

        #handover_employee_results,
        #takeover_employee_results {
            position: absolute;
            z-index: 1000;
            width: 100%;
            max-height: 300px;
            overflow-y: auto;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .employee-item {
            cursor: pointer;
            padding: 10px;
        }

        .employee-item:hover {
            background-color: #f8f9fa;
        }

        .employee-item img {
            width: 40px;
            height: 40px;
            object-fit: cover;
        }

        /* Table Styling */
        .table th {
            background-color: #4A90E2;
            color: #ffffff;
            font-weight: 600;
            text-align: center;
            vertical-align: middle;
            padding: 10px;
        }

        .table td {
            border: 1px solid #dee2e6;
            vertical-align: middle;
            padding: 8px;
        }

        .form-check-input {
            margin-right: 5px;
        }

        .form-check {
            margin-right: 15px;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Signature Pads
            const handoverCanvas = document.getElementById('handover-signature-canvas');
            const handoverSignaturePad = new SignaturePad(handoverCanvas, {
                backgroundColor: 'rgb(255, 255, 255)',
                penColor: 'rgb(0, 0, 0)'
            });

            const takeoverCanvas = document.getElementById('takeover-signature-canvas');
            const takeoverSignaturePad = new SignaturePad(takeoverCanvas, {
                backgroundColor: 'rgb(255, 255, 255)',
                penColor: 'rgb(0, 0, 0)'
            });

            // Hidden fields for signatures
            const handoverDataField = document.getElementById('handover_signature_data');
            const handoverDeleteField = document.getElementById('handover_signature_delete');
            const takeoverDataField = document.getElementById('takeover_signature_data');
            const takeoverDeleteField = document.getElementById('takeover_signature_delete');

            // Existing signature previews
            const existingHandoverPreview = document.getElementById('existing_handover_signature_preview');
            const existingTakeoverPreview = document.getElementById('existing_takeover_signature_preview');

            // Adjust canvas sizes
            function resizeCanvas(canvas, signaturePad) {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext('2d').scale(ratio, ratio);
                if (signaturePad.isEmpty()) {
                    signaturePad.clear();
                }
            }

            resizeCanvas(handoverCanvas, handoverSignaturePad);
            resizeCanvas(takeoverCanvas, takeoverSignaturePad);

            window.addEventListener('resize', function() {
                resizeCanvas(handoverCanvas, handoverSignaturePad);
                resizeCanvas(takeoverCanvas, takeoverSignaturePad);
            });

            // Clear signatures
            document.getElementById('clear-handover-signature').addEventListener('click', function() {
                handoverSignaturePad.clear();
                handoverDataField.value = "";
                handoverDeleteField.value = "1";
                if (existingHandoverPreview) existingHandoverPreview.style.display = "none";
            });

            document.getElementById('clear-takeover-signature').addEventListener('click', function() {
                takeoverSignaturePad.clear();
                takeoverDataField.value = "";
                takeoverDeleteField.value = "1";
                if (existingTakeoverPreview) existingTakeoverPreview.style.display = "none";
            });

            // When user starts drawing → reset delete flag
            function markAsNew(signaturePad, deleteField, existingPreview) {
                deleteField.value = "0";
                if (existingPreview) existingPreview.style.display = "none";
            }

            handoverCanvas.addEventListener("mousedown", () => markAsNew(handoverSignaturePad, handoverDeleteField,
                existingHandoverPreview));
            handoverCanvas.addEventListener("touchstart", () => markAsNew(handoverSignaturePad, handoverDeleteField,
                existingHandoverPreview));

            takeoverCanvas.addEventListener("mousedown", () => markAsNew(takeoverSignaturePad, takeoverDeleteField,
                existingTakeoverPreview));
            takeoverCanvas.addEventListener("touchstart", () => markAsNew(takeoverSignaturePad, takeoverDeleteField,
                existingTakeoverPreview));

            // Show/hide other reason input based on radio selection
            const reasonRadios = document.querySelectorAll('input[name="reason"]');
            const otherReasonContainer = document.getElementById('other_reason_container');

            function toggleOtherReason() {
                const otherSelected = document.getElementById('reason_other_radio').checked;
                otherReasonContainer.style.display = otherSelected ? 'block' : 'none';

                if (!otherSelected) {
                    document.getElementById('reason_other_text').value = '';
                }
            }

            reasonRadios.forEach(radio => {
                radio.addEventListener('change', toggleOtherReason);
            });

            // Initialize based on current selection
            toggleOtherReason();

            // Employee Search Functionality
            function setupEmployeeSearch(searchInputId, searchButtonId, resultsListId, employeeImageId,
                employeeIdDisplayId, employeeIdHiddenId, employeeEmailId,
                departmentId, departmentHiddenId, designationId, designationHiddenId, fullNameId) {

                const searchInput = document.getElementById(searchInputId);
                const searchButton = document.getElementById(searchButtonId);
                const resultsList = document.getElementById(resultsListId);

                function searchEmployees() {
                    const searchTerm = searchInput.value.trim();

                    if (searchTerm.length < 1) {
                        resultsList.style.display = 'none';
                        return;
                    }

                    fetch(`{{ route('handover.search') }}?term=${encodeURIComponent(searchTerm)}`, {
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
                                resultsList.innerHTML =
                                    '<li class="list-group-item disabled">No employees found</li>';
                            } else {
                                data.forEach(employee => {
                                    const item = document.createElement('li');
                                    item.className = 'list-group-item employee-item';
                                    item.innerHTML = `
                                    <div class="d-flex align-items-center">
                                        <img src="${employee.image ? '/employee_images/' + employee.image : '/images/admin_default.jpg'}"
                                             class="rounded-circle me-3" width="40" height="40">
                                        <div>
                                            <h6 class="mb-0">${employee.fullname}</h6>
                                            <small class="text-muted">${employee.employee_id} - ${employee.department?.dep_name || 'No Department'}</small>
                                        </div>
                                    </div>
                                `;
                                    item.addEventListener('click', () => fillEmployeeDetails(employee,
                                        employeeImageId,
                                        employeeIdDisplayId, employeeIdHiddenId,
                                        employeeEmailId,
                                        departmentId, departmentHiddenId, designationId,
                                        designationHiddenId, fullNameId));
                                    resultsList.appendChild(item);
                                });
                            }
                            resultsList.style.display = 'block';
                        })
                        .catch(error => {
                            console.error('Search error:', error);
                            resultsList.innerHTML =
                                `<li class="list-group-item text-danger">Error: ${error.message}</li>`;
                            resultsList.style.display = 'block';
                        });
                }

                function fillEmployeeDetails(employee, employeeImageId, employeeIdDisplayId, employeeIdHiddenId,
                    employeeEmailId, departmentId, departmentHiddenId,
                    designationId, designationHiddenId, fullNameId) {

                    document.getElementById(employeeIdDisplayId).value = employee.employee_id;
                    document.getElementById(employeeIdHiddenId).value = employee.emp_id;
                    document.getElementById(employeeEmailId).value = employee.email;
                    document.getElementById(departmentId).value = employee.department ? employee.department
                        .dep_name : '';
                    document.getElementById(departmentHiddenId).value = employee.department ? employee.department
                        .dep_id : '';
                    document.getElementById(designationId).value = employee.designation ? employee.designation
                        .des_name : '';
                    document.getElementById(designationHiddenId).value = employee.designation ? employee.designation
                        .des_id : '';
                    document.getElementById(fullNameId).value = employee.fullname;

                    const img = document.getElementById(employeeImageId);
                    img.src = employee.image ? '/employee_images/' + employee.image : '/images/admin_default.jpg';
                    img.alt = employee.fullname;

                    searchInput.value = '';
                    resultsList.style.display = 'none';
                }

                // Event listeners for employee search
                searchInput.addEventListener('input', searchEmployees);
                searchButton.addEventListener('click', searchEmployees);

                document.addEventListener('click', function(e) {
                    if (!resultsList.contains(e.target) && e.target !== searchInput && e.target !==
                        searchButton) {
                        resultsList.style.display = 'none';
                    }
                });
            }

            // Setup both employee search functionalities
            setupEmployeeSearch(
                'handover_employee_search', 'handover_search_button', 'handover_employee_results',
                'handover_employee_image', 'handover_employee_id_display', 'handover_employee_id',
                'handover_employee_email', 'handover_department', 'handover_department_id',
                'handover_designation', 'handover_designation_id', 'handover_full_name'
            );

            setupEmployeeSearch(
                'takeover_employee_search', 'takeover_search_button', 'takeover_employee_results',
                'takeover_employee_image', 'takeover_employee_id_display', 'takeover_employee_id',
                'takeover_employee_email', 'takeover_department', 'takeover_department_id',
                'takeover_designation', 'takeover_designation_id', 'takeover_full_name'
            );

            // Task table functionality
            let taskRowCount = {{ $taskCount }};

            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('add-task-row') ||
                    e.target.closest('.add-task-row')) {
                    addTaskRow();
                }

                if (e.target.classList.contains('remove-task-row') ||
                    e.target.closest('.remove-task-row')) {
                    const row = e.target.closest('tr');
                    if (document.querySelectorAll('#tasksTable tbody tr').length > 1) {
                        row.remove();
                        updateSerialNumbers();
                        taskRowCount = document.querySelectorAll('#tasksTable tbody tr').length;
                    }
                }
            });

            function addTaskRow() {
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                <td class="text-center">${taskRowCount + 1}</td>
                <td><input type="text" class="form-control form-control-sm" name="tasks[${taskRowCount}][task_no]"></td>
                <td><input type="text" class="form-control form-control-sm" name="tasks[${taskRowCount}][task_name]"></td>
                <td>
                    <select class="form-select form-select-sm" name="tasks[${taskRowCount}][priority]">
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                    </select>
                </td>
                <td>
                    <select class="form-select form-select-sm" name="tasks[${taskRowCount}][status]">
                        <option value="Not Started">Not Started</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Completed">Completed</option>
                        <option value="In UAT">In UAT</option>
                        <option value="Over Due">Over Due</option>
                    </select>
                </td>
                <td><input type="date" class="form-control form-control-sm" name="tasks[${taskRowCount}][due_date]"></td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger remove-task-row">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;
                document.querySelector('#tasksTable tbody').appendChild(newRow);
                taskRowCount++;
                updateSerialNumbers();
            }

            function updateSerialNumbers() {
                const rows = document.querySelectorAll('#tasksTable tbody tr');
                rows.forEach((row, index) => {
                    row.cells[0].textContent = index + 1;
                });
            }

            // Form submission
            document.getElementById('handoverForm').addEventListener('submit', function(e) {
                // Validate reason_other if "Other" is selected
                const otherReasonSelected = document.getElementById('reason_other_radio').checked;
                const otherReasonText = document.getElementById('reason_other_text').value;

                if (otherReasonSelected && !otherReasonText.trim()) {
                    e.preventDefault();
                    alert('Please specify the reason for handover when selecting "Other".');
                    document.getElementById('reason_other_text').focus();
                    return;
                }

                // Convert signatures to data URLs and store in hidden inputs
                if (!handoverSignaturePad.isEmpty()) {
                    handoverDataField.value = handoverSignaturePad.toDataURL();
                    handoverDeleteField.value = "0";
                }

                if (!takeoverSignaturePad.isEmpty()) {
                    takeoverDataField.value = takeoverSignaturePad.toDataURL();
                    takeoverDeleteField.value = "0";
                }
            });
        });
    </script>
</x-layout>
