<x-layout>
    @section('title', 'Edit Resignation')
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
                        <li class="breadcrumb-item">
                            <a href="{{ route('resignationlist') }}"
                                class="text-decoration-none text-muted">Resignation</a>
                        </li>
                        <li class="breadcrumb-item active text-primary" aria-current="page">Edit Resignation</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('resignationlist') }}" class="btn btn-outline-primary d-flex align-items-center">
                <i class="bi bi-arrow-left-circle me-2"></i>Back
            </a>
        </div>

        <x-message />
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('resignation.update', $resignation->resignation_id) }}" method="POST"
                    enctype="multipart/form-data" id="resignationForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="employee_id" id="employee_id"
                        value="{{ old('employee_id', $resignation->employee_id) }}">
                    <input type="hidden" name="department_id" id="department_id"
                        value="{{ old('department_id', $resignation->department) }}">
                    <input type="hidden" name="designation_id" id="designation_id"
                        value="{{ old('designation_id', $resignation->designation) }}">

                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="employee_id" class="form-label">Employee Id</label>
                                <input type="text" class="form-control" id="employee_id_display" readonly
                                    value="{{ old('employee_id_display', $resignation->employee->employee_id ?? '') }}">
                            </div>

                            <div class="mb-3">
                                <label for="employee_email" class="form-label">Employee Email</label>
                                <input type="email" class="form-control" id="employee_email" name="employee_email"
                                    readonly value="{{ old('employee_email', $resignation->employee->email_company) }}">
                            </div>

                            <div class="mb-3">
                                <label for="department" class="form-label">Department</label>
                                <input type="text" class="form-control" id="department" name="department_display"
                                    readonly
                                    value="{{ old('department_display', $resignation->departmentRelation->dep_name ?? '') }}">
                            </div>

                            <div class="mb-3">
                                <label for="designation" class="form-label">Designation</label>
                                <input type="text" class="form-control" id="designation" name="designation_display"
                                    readonly
                                    value="{{ old('designation_display', $resignation->designationRelation->des_name ?? '') }}">
                            </div>

                            <h5 class="mt-4 mb-3 border-bottom pb-2">Resignation Information</h5>

                            <div class="mb-3">
                                <div class="d-flex align-items-center">
                                    <label class="form-label mb-0 me-3">Employee Resignation is</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="is_voluntary"
                                            id="voluntary" value="1"
                                            {{ old('is_voluntary', $resignation->is_voluntary) == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="voluntary">Voluntary</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="is_voluntary"
                                            id="involuntary" value="0"
                                            {{ old('is_voluntary', $resignation->is_voluntary) == '0' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="involuntary">Involuntary</label>
                                    </div>
                                </div>
                                @error('is_voluntary')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="d-flex align-items-center">
                                    <label class="form-label mb-0 me-3">Does have Notice Period</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="has_notice_period"
                                            id="has_notice_yes" value="1"
                                            {{ old('has_notice_period', $resignation->has_notice_period) == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="has_notice_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="has_notice_period"
                                            id="has_notice_no" value="0"
                                            {{ old('has_notice_period', $resignation->has_notice_period) == '0' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="has_notice_no">No</label>
                                    </div>
                                </div>
                                @error('has_notice_period')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div id="notice_period_fields"
                                style="display: {{ old('has_notice_period', $resignation->has_notice_period) == '1' ? 'block' : 'none' }};">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="notice_start_date" class="form-label">Notice Start Date</label>
                                        <input type="date" class="form-control" id="notice_start_date"
                                            name="notice_start_date"
                                            value="{{ old('notice_start_date', $resignation->notice_start_date ? $resignation->notice_start_date->format('Y-m-d') : '') }}">
                                        @error('notice_start_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="notice_end_date" class="form-label">Notice End Date</label>
                                        <input type="date" class="form-control" id="notice_end_date"
                                            name="notice_end_date"
                                            value="{{ old('notice_end_date', $resignation->notice_end_date ? $resignation->notice_end_date->format('Y-m-d') : '') }}"
                                            {{ old('has_notice_period', $resignation->has_notice_period) == '1' ? '' : 'disabled' }}>
                                        @error('notice_end_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Notice Period Duration</label>
                                    <input type="text" class="form-control" name="notice_period_duration"
                                        value="{{ old('notice_period', $resignation->notice_period) }}"
                                        id="notice_period_duration" readonly>
                                </div>

                            </div>

                            <div id="no_notice_period_fields"
                                style="display: {{ old('has_notice_period', $resignation->has_notice_period) == '0' ? 'block' : 'none' }};">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="last_working_day" class="form-label">Last Working Day</label>
                                        <input type="date" class="form-control" id="last_working_day"
                                            name="last_working_day"
                                            value="{{ old('last_working_day', $resignation->last_working_day ? $resignation->last_working_day->format('Y-m-d') : '') }}">
                                        @error('last_working_day')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="resignation_document" class="form-label">Resignation Document</label>
                                <input type="file" class="form-control" id="resignation_document"
                                    name="resignation_document">

                                @error('resignation_document')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                                @if ($resignation->resignation_document)
                                    @php
                                        $filePath = asset($resignation->resignation_document);
                                        $extension = strtolower(
                                            pathinfo($resignation->resignation_document, PATHINFO_EXTENSION),
                                        );
                                    @endphp

                                    <div class="mt-2">
                                        <small>Current Supporting Document:</small>

                                        <div class="mt-1">
                                            @if (in_array($extension, ['jpg', 'jpeg', 'png']))
                                                <img src="{{ $filePath }}" alt="Supporting Document"
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

                            <div class="mb-3">
                                <label class="form-label">Can the employee be rehired?</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="can_be_rehired"
                                            id="rehire_yes" value="1"
                                            {{ old('can_be_rehired', $resignation->can_be_rehired) == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="rehire_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="can_be_rehired"
                                            id="rehire_no" value="0"
                                            {{ old('can_be_rehired', $resignation->can_be_rehired) == '0' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="rehire_no">No</label>
                                    </div>
                                </div>
                                @error('can_be_rehired')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">
                            <div class="text-center" style="margin-bottom: 2.1em;">
                                <img src="{{ $resignation->employee->image ? asset('employee_images/' . $resignation->employee->image) : asset('images/admin_default.jpg') }}"
                                    alt="" class="avatar-rounded" id="employee_image">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Search Employee</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="employee_search"
                                        placeholder="Search by name or ID"
                                        value="{{ old('employee_search', $resignation->employee->fullname ?? '') }}">
                                    <button class="btn btn-outline-secondary" type="button" id="search_button">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                                <ul id="employee_results" class="list-group mt-2" style="display: none;"></ul>
                            </div>

                            <div class="mb-3">
                                <label for="full_name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" readonly
                                    value="{{ old('full_name', $resignation->employee->fullname ?? '') }}">
                                @error('full_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="date_of_hire" class="form-label">Date of Hire</label>
                                <input type="date" class="form-control" id="date_of_hire" name="date_of_hire"
                                    readonly
                                    value="{{ old('date_of_hire', $resignation->employee->dojprovision_from_date ?? '') }}">
                                @error('date_of_hire')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Why she/he filing for a resignation?</label>
                                <div class="d-flex flex-wrap gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="resignation_reason"
                                            id="reason_salary" value="1"
                                            {{ old('resignation_reason', $resignation->resignation_reason) == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="reason_salary">Salary</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="resignation_reason"
                                            id="reason_better_opportunity" value="2"
                                            {{ old('resignation_reason', $resignation->resignation_reason) == '2' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="reason_better_opportunity">Better
                                            opportunity</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="resignation_reason"
                                            id="reason_moving_location" value="3"
                                            {{ old('resignation_reason', $resignation->resignation_reason) == '3' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="reason_moving_location">Moving to a new
                                            location</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="resignation_reason"
                                            id="reason_focus_studies" value="4"
                                            {{ old('resignation_reason', $resignation->resignation_reason) == '4' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="reason_focus_studies">Will focus on
                                            studies</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="resignation_reason"
                                            id="reason_not_happy_job" value="5"
                                            {{ old('resignation_reason', $resignation->resignation_reason) == '5' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="reason_not_happy_job">Not happy with the
                                            job</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="resignation_reason"
                                            id="reason_personal" value="6"
                                            {{ old('resignation_reason', $resignation->resignation_reason) == '6' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="reason_personal">Personal reasons</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="resignation_reason"
                                            id="reason_retirement" value="7"
                                            {{ old('resignation_reason', $resignation->resignation_reason) == '7' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="reason_retirement">Retirement</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="resignation_reason"
                                            id="reason_other" value="0"
                                            {{ old('resignation_reason', $resignation->resignation_reason) == '0' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="reason_other">Other</label>
                                    </div>
                                </div>

                                <div id="reason_other_details_container"
                                    style="display: {{ old('resignation_reason', $resignation->resignation_reason) == '0' ? 'block' : 'none' }};">
                                    <label for="reason_other_details" class="form-label mt-2">Others Please
                                        specify</label>
                                    <textarea class="form-control" id="reason_other_details" name="reason_other_details" rows="2">{{ old('reason_other_details', $resignation->reason_other_details) }}</textarea>
                                    @error('reason_other_details')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="reason_details" class="form-label">Reason of Resignation</label>
                                <textarea class="form-control" id="reason_details" name="reason_details" rows="3">{{ old('reason_details', $resignation->reason_details) }}</textarea>
                                @error('reason_details')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3" id="rehire_conditions_container"
                                style="display: {{ old('can_be_rehired', $resignation->can_be_rehired) == '1' ? 'block' : 'none' }};">
                                <label for="rehire_conditions" class="form-label">Rehire Conditions</label>
                                <textarea class="form-control" id="rehire_conditions" name="rehire_conditions" rows="3">{{ old('rehire_conditions', $resignation->rehire_conditions) }}</textarea>
                                @error('rehire_conditions')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Signature and Submit -->
                    <div class="row mt-4">
                        <h5 class="mt-4 mb-3 border-bottom pb-2">Acknowledgement</h5>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="date_of_resignation" class="form-label">Date of Resignation</label>
                                <input type="date" class="form-control" id="date_of_resignation"
                                    name="date_of_resignation"
                                    value="{{ old('date_of_resignation', $resignation->date_of_resignation ? $resignation->date_of_resignation->format('Y-m-d') : '') }}">
                                @error('date_of_resignation')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-check mb-3" style="margin-left: .8em;">
                            <input class="form-check-input" type="checkbox" id="acknowledgement"
                                onchange="document.getElementById('acknowledgement_value').value = this.checked ? 1 : 0"
                                {{ old('acknowledgement', $resignation->acknowledgement) ? 'checked' : '' }}>
                            <input type="hidden" name="acknowledgement" id="acknowledgement_value"
                                value="{{ old('acknowledgement') ? 1 : 0 }}">
                            <label class="form-check-label" for="acknowledgement">
                                I, the employee named above, acknowledge that this notice was discussed with me and
                                I understand and accept its contents.
                            </label>
                            @error('acknowledgement')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="row">
                            <!-- Employee Signature -->
                            <div class="col-md-6 align-content-center">
                                <div class="mb-3">
                                    <label class="form-label">Employee Signature</label>
                                    <div id="employee-signature-pad" class="signature-pad border rounded"
                                        style="height: 150px; background-color: #f8f9fa; position:relative;">
                                        <canvas id="employee-signature-canvas"
                                            style="width: 100%; height: 100%;"></canvas>
                                        <i class="bi bi-x-circle text-danger" id="clear-employee-signature"
                                            style="position:absolute; top:5px; right:5px; cursor:pointer;"></i>
                                    </div>

                                    <!-- Hidden fields -->
                                    <input type="hidden" id="employee_signature_data" name="employee_signature_data"
                                        value="">
                                    <input type="hidden" id="employee_signature_delete"
                                        name="employee_signature_delete" value="0">

                                    @if ($resignation->employee_signature)
                                        @php $employeeFilePath = asset($resignation->employee_signature); @endphp
                                        <div class="mt-2">
                                            <small>Current Employee Signature:</small>
                                            <div class="mt-1">
                                                <img id="existing_employee_signature_preview"
                                                    src="{{ $employeeFilePath }}" alt="employee_signature"
                                                    style="max-width: 500px; height: auto; border: 1px solid #ddd; border-radius: 6px;">
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Management Signature -->
                            <div class="col-md-6 align-content-center">
                                <div class="mb-3">
                                    <label class="form-label">Management Signature</label>
                                    <div id="management-signature-pad" class="signature-pad border rounded"
                                        style="height: 150px; background-color: #f8f9fa; position:relative;">
                                        <canvas id="management-signature-canvas"
                                            style="width: 100%; height: 100%;"></canvas>
                                        <i class="bi bi-x-circle text-danger" id="clear-management-signature"
                                            style="position:absolute; top:5px; right:5px; cursor:pointer;"></i>
                                    </div>

                                    <!-- Hidden fields -->
                                    <input type="hidden" id="management_signature_data"
                                        name="management_signature_data" value="">
                                    <input type="hidden" id="management_signature_delete"
                                        name="management_signature_delete" value="0">

                                    @if ($resignation->management_signature)
                                        @php $managementFilePath = asset($resignation->management_signature); @endphp
                                        <div class="mt-2">
                                            <small>Current Management Signature:</small>
                                            <div class="mt-1">
                                                <img id="existing_management_signature_preview"
                                                    src="{{ $managementFilePath }}" alt="management_signature"
                                                    style="max-width: 500px; height: auto; border: 1px solid #ddd; border-radius: 6px;">
                                            </div>
                                        </div>
                                    @endif
                                </div>
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
            width: 150px;
            height: 150px;
            object-fit: cover;
        }

        .signature-pad {
            position: relative;
        }

        #employee-signature-canvas,
        #management-signature-canvas {
            background-color: #f8f9fa;
        }

        #clear-employee-signature,
        #clear-management-signature {
            position: absolute;
            right: 10px;
            top: 10px;
            font-size: 1.2em;
            cursor: pointer;
        }

        #employee_results {
            position: absolute;
            z-index: 1000;
            width: 47%;
            max-height: 300px;
            overflow-y: auto;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .employee-item {
            cursor: pointer;
        }

        .employee-item:hover {
            background-color: #f8f9fa;
        }

        .employee-item img {
            width: 40px;
            height: 40px;
            object-fit: cover;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Employee Signature Pad
            const employeeCanvas = document.getElementById('employee-signature-canvas');
            const employeeSignaturePad = new SignaturePad(employeeCanvas, {
                backgroundColor: '#ffffff',
                penColor: 'rgb(0, 0, 0)'
            });
            const employeeDataField = document.getElementById('employee_signature_data');
            const employeeDeleteField = document.getElementById('employee_signature_delete');
            const clearEmployeeButton = document.getElementById('clear-employee-signature');
            const existingEmployeePreview = document.getElementById('existing_employee_signature_preview');

            // Initialize Management Signature Pad
            const managementCanvas = document.getElementById('management-signature-canvas');
            const managementSignaturePad = new SignaturePad(managementCanvas, {
                backgroundColor: '#ffffff',
                penColor: 'rgb(0, 0, 0)'
            });
            const managementDataField = document.getElementById('management_signature_data');
            const managementDeleteField = document.getElementById('management_signature_delete');
            const clearManagementButton = document.getElementById('clear-management-signature');
            const existingManagementPreview = document.getElementById('existing_management_signature_preview');

            const form = document.getElementById('resignationForm');

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

            resizeCanvas(employeeCanvas, employeeSignaturePad);
            resizeCanvas(managementCanvas, managementSignaturePad);

            window.addEventListener('resize', function() {
                resizeCanvas(employeeCanvas, employeeSignaturePad);
                resizeCanvas(managementCanvas, managementSignaturePad);
            });

            // Clear Employee Signature
            clearEmployeeButton.addEventListener('click', function() {
                employeeSignaturePad.clear();
                employeeDataField.value = "";
                employeeDeleteField.value = "1";
                if (existingEmployeePreview) existingEmployeePreview.style.display = "none";
            });

            // Clear Management Signature
            clearManagementButton.addEventListener('click', function() {
                managementSignaturePad.clear();
                managementDataField.value = "";
                managementDeleteField.value = "1";
                if (existingManagementPreview) existingManagementPreview.style.display = "none";
            });

            // When user starts drawing → reset delete flag
            function markAsNew(signaturePad, deleteField, existingPreview) {
                deleteField.value = "0";
                if (existingPreview) existingPreview.style.display = "none";
            }

            employeeCanvas.addEventListener("mousedown", () => markAsNew(employeeSignaturePad, employeeDeleteField,
                existingEmployeePreview));
            employeeCanvas.addEventListener("touchstart", () => markAsNew(employeeSignaturePad, employeeDeleteField,
                existingEmployeePreview));

            managementCanvas.addEventListener("mousedown", () => markAsNew(managementSignaturePad,
                managementDeleteField,
                existingManagementPreview));
            managementCanvas.addEventListener("touchstart", () => markAsNew(managementSignaturePad,
                managementDeleteField,
                existingManagementPreview));

            // Before submit → save base64 if drawn
            form.addEventListener("submit", function() {
                if (!employeeSignaturePad.isEmpty()) {
                    employeeDataField.value = employeeSignaturePad.toDataURL();
                    employeeDeleteField.value = "0";
                }
                if (!managementSignaturePad.isEmpty()) {
                    managementDataField.value = managementSignaturePad.toDataURL();
                    managementDeleteField.value = "0";
                }
            });

            // Employee Search Functionality
            const searchInput = document.getElementById('employee_search');
            const searchButton = document.getElementById('search_button');
            const resultsList = document.getElementById('employee_results');

            function searchEmployees() {
                const searchTerm = searchInput.value.trim();
                if (searchTerm.length < 1) {
                    resultsList.style.display = 'none';
                    return;
                }

                fetch(`{{ route('employees.search') }}?term=${encodeURIComponent(searchTerm)}`, {
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
                                            <small class="text-muted">${employee.employee_id} - ${employee.department.dep_name}</small>
                                        </div>
                                    </div>
                                `;
                                item.addEventListener('click', () => fillEmployeeDetails(employee));
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

            function fillEmployeeDetails(employee) {
                document.getElementById('employee_id_display').value = employee.employee_id;
                document.getElementById('employee_id').value = employee.emp_id;
                document.getElementById('employee_email').value = employee.email;
                document.getElementById('department').value = employee.department ? employee.department.dep_name :
                    '';
                document.getElementById('department_id').value = employee.department ? employee.department.dep_id :
                    '';
                document.getElementById('designation').value = employee.designation ? employee.designation
                    .des_name : '';
                document.getElementById('designation_id').value = employee.designation ? employee.designation
                    .des_id : '';
                document.getElementById('full_name').value = employee.fullname;

                if (employee.dojprovision_from_date) {
                    const hireDate = new Date(employee.dojprovision_from_date);
                    const formattedDate = hireDate.toISOString().split('T')[0];
                    document.getElementById('date_of_hire').value = formattedDate;
                } else {
                    document.getElementById('date_of_hire').value = '';
                }

                const img = document.getElementById('employee_image');
                img.src = employee.image ? '/employee_images/' + employee.image : '/images/admin_default.jpg';
                img.alt = employee.fullname;

                searchInput.value = '';
                resultsList.style.display = 'none';
            }

            searchInput.addEventListener('input', searchEmployees);
            searchButton.addEventListener('click', searchEmployees);
            document.addEventListener('click', function(e) {
                if (!resultsList.contains(e.target) && e.target !== searchInput && e.target !==
                    searchButton) {
                    resultsList.style.display = 'none';
                }
            });

            // Show/hide notice period fields
            const hasNoticeYes = document.getElementById('has_notice_yes');
            const hasNoticeNo = document.getElementById('has_notice_no');
            const noticePeriodFields = document.getElementById('notice_period_fields');
            const noNoticePeriodFields = document.getElementById('no_notice_period_fields');

            function toggleNoticePeriodFields() {
                noticePeriodFields.style.display = hasNoticeYes.checked ? 'block' : 'none';
                noNoticePeriodFields.style.display = hasNoticeNo.checked ? 'block' : 'none';

                if (!hasNoticeYes.checked) {
                    document.getElementById('notice_start_date').value = '';
                    document.getElementById('notice_end_date').value = '';
                }

                if (!hasNoticeNo.checked) {
                    document.getElementById('last_working_day').value = '';

                }
            }

            hasNoticeYes.addEventListener('change', toggleNoticePeriodFields);
            hasNoticeNo.addEventListener('change', toggleNoticePeriodFields);

            // Get date inputs
            const noticeStartDate = document.getElementById('notice_start_date');
            const noticeEndDate = document.getElementById('notice_end_date');

            // Function to set minimum end date based on start date
            function setEndDateMin() {
                if (noticeStartDate.value) {
                    // Enable end date input
                    noticeEndDate.disabled = false;

                    // Set minimum date to start date
                    noticeEndDate.min = noticeStartDate.value;

                    // If current end date is before start date, clear it
                    if (noticeEndDate.value && new Date(noticeEndDate.value) < new Date(noticeStartDate.value)) {
                        noticeEndDate.value = '';
                    }
                } else {
                    // Disable end date if no start date selected
                    noticeEndDate.disabled = true;
                    noticeEndDate.value = '';
                }
            }

            // Add event listener to start date
            noticeStartDate.addEventListener('change', setEndDateMin);

            // Initialize on page load if start date already has a value
            if (noticeStartDate.value) {
                setEndDateMin();
            }

            // Calculate notice period duration



            function calculateNoticePeriod() {
                const startDate = new Date(noticeStartDate.value);
                const endDate = new Date(noticeEndDate.value);

                if (startDate && endDate && startDate <= endDate) {
                    const diffTime = Math.abs(endDate - startDate);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                    let years = Math.floor(diffDays / 365);
                    let remainingDays = diffDays % 365;
                    let months = Math.floor(remainingDays / 30);
                    let days = remainingDays % 30;

                    // Handle cases where months calculation might be 12
                    if (months === 12) {
                        years += 1;
                        months = 0;
                    }

                    let duration = '';
                    if (years > 0) {
                        duration += `${years} year${years > 1 ? 's' : ''}`;
                    }
                    if (months > 0) {
                        if (duration) duration += ', ';
                        duration += `${months} month${months > 1 ? 's' : ''}`;
                    }
                    if (days > 0) {
                        if (duration) duration += ', ';
                        duration += `${days} day${days > 1 ? 's' : ''}`;
                    }

                    document.getElementById('notice_period_duration').value = duration || '0 days';
                } else {
                    document.getElementById('notice_period_duration').value = '';
                }
            }

            // function calculateNoticePeriod() {
            //     const startDate = new Date(noticeStartDate.value);
            //     const endDate = new Date(noticeEndDate.value);

            //     if (startDate && endDate && startDate <= endDate) {
            //         const diffTime = Math.abs(endDate - startDate);
            //         const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

            //         const months = Math.floor(diffDays / 30);
            //         const days = diffDays % 30;

            //         let duration = '';
            //         if (months > 0) {
            //             duration += `${months} month${months > 1 ? 's' : ''}`;
            //         }
            //         if (days > 0) {
            //             if (duration) duration += ', ';
            //             duration += `${days} day${days > 1 ? 's' : ''}`;
            //         }

            //         document.getElementById('notice_period_duration').value = duration || '0 days';
            //     } else {
            //         document.getElementById('notice_period_duration').value = '';
            //     }
            // }

            document.getElementById('notice_start_date').addEventListener('change', calculateNoticePeriod);
            document.getElementById('notice_end_date').addEventListener('change', calculateNoticePeriod);

            // Initialize on page load
            toggleNoticePeriodFields();

            // Show/hide rehire conditions based on selection
            const rehireYes = document.getElementById('rehire_yes');
            const rehireNo = document.getElementById('rehire_no');
            const rehireConditionsContainer = document.getElementById('rehire_conditions_container');

            function toggleRehireConditions() {
                rehireConditionsContainer.style.display = rehireYes.checked ? 'block' : 'none';
                if (!rehireYes.checked) {
                    document.getElementById('rehire_conditions').value = '';
                }
            }

            rehireYes.addEventListener('change', toggleRehireConditions);
            rehireNo.addEventListener('change', toggleRehireConditions);

            // Initialize on page load
            toggleRehireConditions();

            // Show/hide other reason details
            function toggleReasonOtherDetails() {
                const reasonOther = document.getElementById('reason_other');
                const reasonOtherDetailsContainer = document.getElementById('reason_other_details_container');

                reasonOtherDetailsContainer.style.display = reasonOther.checked ? 'block' : 'none';
                if (!reasonOther.checked) {
                    document.getElementById('reason_other_details').value = '';
                }
            }

            // Add event listeners to all resignation reason radio buttons
            const reasonRadios = document.querySelectorAll('input[name="resignation_reason"]');
            reasonRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    toggleReasonOtherDetails();
                });
            });

            // Initialize on page load
            toggleReasonOtherDetails();
        });
    </script>
</x-layout>
