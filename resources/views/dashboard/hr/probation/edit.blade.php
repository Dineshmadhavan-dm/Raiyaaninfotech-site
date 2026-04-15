<x-layout>
    @section('title', 'Edit Probation')
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
                            <a href="{{ route('probationlist') }}" class="text-decoration-none text-muted">Probation</a>
                        </li>
                        <li class="breadcrumb-item active text-primary" aria-current="page">Edit Probation</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('probationlist') }}" class="btn btn-outline-primary d-flex align-items-center">
                <i class="bi bi-arrow-left-circle me-2"></i>Back
            </a>
        </div>

        <x-message />
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('probation.update', $probation->probation_id) }}" method="POST"
                    enctype="multipart/form-data" id="probationForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="employee_id" id="employee_id"
                        value="{{ old('employee_id', $probation->employee_id) }}">
                    <input type="hidden" name="department_id" id="department_id"
                        value="{{ old('department_id', $probation->department) }}">
                    <input type="hidden" name="designation_id" id="designation_id"
                        value="{{ old('designation_id', $probation->designation) }}">

                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="employee_id" class="form-label">Employee Id</label>
                                <input type="text" class="form-control" id="employee_id_display" readonly
                                    value="{{ old('employee_id_display', $probation->employee->employee_id ?? '') }}">
                            </div>

                            <div class="mb-3">
                                <label for="employee_email" class="form-label">Employee Email</label>
                                <input type="email" class="form-control" id="employee_email" name="employee_email"
                                    readonly
                                    value="{{ old('employee_email', $probation->employee->email_company ?? '') }}">
                            </div>

                            <div class="mb-3">
                                <label for="department" class="form-label">Department</label>
                                <input type="text" class="form-control" id="department" name="department_display"
                                    readonly
                                    value="{{ old('department_display', $probation->departmentRelation->dep_name ?? '') }}">
                            </div>

                            <div class="mb-3">
                                <label for="designation" class="form-label">Designation</label>
                                <input type="text" class="form-control" id="designation" name="designation_display"
                                    readonly
                                    value="{{ old('designation_display', $probation->designationRelation->des_name ?? '') }}">
                            </div>

                            <h5 class="mt-4 mb-3 border-bottom pb-2">Probation Information</h5>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="probation_from" class="form-label">Probation From</label>
                                    <input type="date" class="form-control" id="probation_from" name="probation_from"
                                        value="{{ old('probation_from', $probation->probation_from ? $probation->probation_from->format('Y-m-d') : '') }}"
                                        readonly>
                                    @error('probation_from')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="probation_to" class="form-label">Probation To</label>
                                    <input type="date" class="form-control" id="probation_to" name="probation_to"
                                        value="{{ old('probation_to', $probation->probation_to ? $probation->probation_to->format('Y-m-d') : '') }}"
                                        readonly>
                                    @error('probation_to')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Find this section in your edit blade and update it -->
                            <div class="mb-3">
                                <label for="supervisor_name" class="form-label">Supervisor Name</label>
                                <input type="text" class="form-control" id="supervisor_name" name="supervisor_name"
                                    value="{{ old('supervisor_name', $probation->supervisor_name) }}" readonly>
                                @error('supervisor_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Supervisor Evaluation Form -->
                            <h5 class="mt-4 mb-3 border-bottom pb-2">Supervisor evaluation form</h5>

                            <div class="table-responsive mb-4">
                                <table class="table table-bordered">
                                    <thead class="table-primary">
                                        <tr class="text-center">
                                            <th width="25%">Probation Review</th>
                                            <th width="15%" class="text-center">Not Satisfied</th>
                                            <th width="15%" class="text-center">Somewhat Satisfied</th>
                                            <th width="15%" class="text-center">Satisfied</th>
                                            <th width="30%">Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Knowledge -->
                                        <tr>
                                            <td class="fw-bold">Knowledge</td>
                                            <td class="text-center">
                                                <input type="radio" name="knowledge_score" value="1"
                                                    class="form-check-input"
                                                    {{ old('knowledge_score', $probation->knowledge_score) == '1' ? 'checked' : '' }}>
                                            </td>
                                            <td class="text-center">
                                                <input type="radio" name="knowledge_score" value="2"
                                                    class="form-check-input"
                                                    {{ old('knowledge_score', $probation->knowledge_score) == '2' ? 'checked' : '' }}>
                                            </td>
                                            <td class="text-center">
                                                <input type="radio" name="knowledge_score" value="3"
                                                    class="form-check-input"
                                                    {{ old('knowledge_score', $probation->knowledge_score) == '3' ? 'checked' : '' }}>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm"
                                                    name="knowledge_notes"
                                                    value="{{ old('knowledge_notes', $probation->knowledge_notes) }}">
                                            </td>
                                        </tr>

                                        <!-- Application of Skills -->
                                        <tr>
                                            <td class="fw-bold">Application of Skills</td>
                                            <td class="text-center">
                                                <input type="radio" name="skills_score" value="1"
                                                    class="form-check-input"
                                                    {{ old('skills_score', $probation->skills_score) == '1' ? 'checked' : '' }}>
                                            </td>
                                            <td class="text-center">
                                                <input type="radio" name="skills_score" value="2"
                                                    class="form-check-input"
                                                    {{ old('skills_score', $probation->skills_score) == '2' ? 'checked' : '' }}>
                                            </td>
                                            <td class="text-center">
                                                <input type="radio" name="skills_score" value="3"
                                                    class="form-check-input"
                                                    {{ old('skills_score', $probation->skills_score) == '3' ? 'checked' : '' }}>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm"
                                                    name="skills_notes"
                                                    value="{{ old('skills_notes', $probation->skills_notes) }}">
                                            </td>
                                        </tr>

                                        <!-- Quality of Work -->
                                        <tr>
                                            <td class="fw-bold">Quality of Work</td>
                                            <td class="text-center">
                                                <input type="radio" name="quality_score" value="1"
                                                    class="form-check-input"
                                                    {{ old('quality_score', $probation->quality_score) == '1' ? 'checked' : '' }}>
                                            </td>
                                            <td class="text-center">
                                                <input type="radio" name="quality_score" value="2"
                                                    class="form-check-input"
                                                    {{ old('quality_score', $probation->quality_score) == '2' ? 'checked' : '' }}>
                                            </td>
                                            <td class="text-center">
                                                <input type="radio" name="quality_score" value="3"
                                                    class="form-check-input"
                                                    {{ old('quality_score', $probation->quality_score) == '3' ? 'checked' : '' }}>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm"
                                                    name="quality_notes"
                                                    value="{{ old('quality_notes', $probation->quality_notes) }}">
                                            </td>
                                        </tr>

                                        <!-- Productivity -->
                                        <tr>
                                            <td class="fw-bold">Productivity</td>
                                            <td class="text-center">
                                                <input type="radio" name="productivity_score" value="1"
                                                    class="form-check-input"
                                                    {{ old('productivity_score', $probation->productivity_score) == '1' ? 'checked' : '' }}>
                                            </td>
                                            <td class="text-center">
                                                <input type="radio" name="productivity_score" value="2"
                                                    class="form-check-input"
                                                    {{ old('productivity_score', $probation->productivity_score) == '2' ? 'checked' : '' }}>
                                            </td>
                                            <td class="text-center">
                                                <input type="radio" name="productivity_score" value="3"
                                                    class="form-check-input"
                                                    {{ old('productivity_score', $probation->productivity_score) == '3' ? 'checked' : '' }}>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm"
                                                    name="productivity_notes"
                                                    value="{{ old('productivity_notes', $probation->productivity_notes) }}">
                                            </td>
                                        </tr>

                                        <!-- Team Work -->
                                        <tr>
                                            <td class="fw-bold">Team Work</td>
                                            <td class="text-center">
                                                <input type="radio" name="teamwork_score" value="1"
                                                    class="form-check-input"
                                                    {{ old('teamwork_score', $probation->teamwork_score) == '1' ? 'checked' : '' }}>
                                            </td>
                                            <td class="text-center">
                                                <input type="radio" name="teamwork_score" value="2"
                                                    class="form-check-input"
                                                    {{ old('teamwork_score', $probation->teamwork_score) == '2' ? 'checked' : '' }}>
                                            </td>
                                            <td class="text-center">
                                                <input type="radio" name="teamwork_score" value="3"
                                                    class="form-check-input"
                                                    {{ old('teamwork_score', $probation->teamwork_score) == '3' ? 'checked' : '' }}>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm"
                                                    name="teamwork_notes"
                                                    value="{{ old('teamwork_notes', $probation->teamwork_notes) }}">
                                            </td>
                                        </tr>

                                        <!-- Punctuality -->
                                        <tr>
                                            <td class="fw-bold">Punctuality</td>
                                            <td class="text-center">
                                                <input type="radio" name="punctuality_score" value="1"
                                                    class="form-check-input"
                                                    {{ old('punctuality_score', $probation->punctuality_score) == '1' ? 'checked' : '' }}>
                                            </td>
                                            <td class="text-center">
                                                <input type="radio" name="punctuality_score" value="2"
                                                    class="form-check-input"
                                                    {{ old('punctuality_score', $probation->punctuality_score) == '2' ? 'checked' : '' }}>
                                            </td>
                                            <td class="text-center">
                                                <input type="radio" name="punctuality_score" value="3"
                                                    class="form-check-input"
                                                    {{ old('punctuality_score', $probation->punctuality_score) == '3' ? 'checked' : '' }}>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm"
                                                    name="punctuality_notes"
                                                    value="{{ old('punctuality_notes', $probation->punctuality_notes) }}">
                                            </td>
                                        </tr>

                                        <!-- Dependability -->
                                        <tr>
                                            <td class="fw-bold">Dependability</td>
                                            <td class="text-center">
                                                <input type="radio" name="dependability_score" value="1"
                                                    class="form-check-input"
                                                    {{ old('dependability_score', $probation->dependability_score) == '1' ? 'checked' : '' }}>
                                            </td>
                                            <td class="text-center">
                                                <input type="radio" name="dependability_score" value="2"
                                                    class="form-check-input"
                                                    {{ old('dependability_score', $probation->dependability_score) == '2' ? 'checked' : '' }}>
                                            </td>
                                            <td class="text-center">
                                                <input type="radio" name="dependability_score" value="3"
                                                    class="form-check-input"
                                                    {{ old('dependability_score', $probation->dependability_score) == '3' ? 'checked' : '' }}>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm"
                                                    name="dependability_notes"
                                                    value="{{ old('dependability_notes', $probation->dependability_notes) }}">
                                            </td>
                                        </tr>

                                        <!-- Communication -->
                                        <tr>
                                            <td class="fw-bold">Communication</td>
                                            <td class="text-center">
                                                <input type="radio" name="communication_score" value="1"
                                                    class="form-check-input"
                                                    {{ old('communication_score', $probation->communication_score) == '1' ? 'checked' : '' }}>
                                            </td>
                                            <td class="text-center">
                                                <input type="radio" name="communication_score" value="2"
                                                    class="form-check-input"
                                                    {{ old('communication_score', $probation->communication_score) == '2' ? 'checked' : '' }}>
                                            </td>
                                            <td class="text-center">
                                                <input type="radio" name="communication_score" value="3"
                                                    class="form-check-input"
                                                    {{ old('communication_score', $probation->communication_score) == '3' ? 'checked' : '' }}>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm"
                                                    name="communication_notes"
                                                    value="{{ old('communication_notes', $probation->communication_notes) }}">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">
                            <div class="text-center" style="margin-bottom: 2.1em;">
                                <img src="{{ $probation->employee->image ? asset('employee_images/' . $probation->employee->image) : asset('images/admin_default.jpg') }}"
                                    alt="" class="avatar-rounded" id="employee_image">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Search Employee</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="employee_search"
                                        placeholder="Search by name or ID"
                                        value="{{ old('employee_search', $probation->employee->fullname ?? '') }}">
                                    <button class="btn btn-outline-secondary" type="button" id="search_button">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                                <ul id="employee_results" class="list-group mt-2" style="display: none;"></ul>
                            </div>

                            <div class="mb-3">
                                <label for="full_name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" readonly
                                    value="{{ old('full_name', $probation->employee->fullname ?? '') }}">
                                @error('full_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="date_of_joined" class="form-label">Date of Joined</label>
                                <input type="date" class="form-control" id="date_of_joined" name="date_of_joined"
                                    readonly
                                    value="{{ old('date_of_joined', $probation->date_of_joined ? $probation->date_of_joined->format('Y-m-d') : '') }}">
                                @error('date_of_joined')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="supporting_documents" class="form-label">Supporting Documents</label>
                                <input type="file" class="form-control" id="supporting_documents"
                                    name="supporting_documents">

                                @error('supporting_documents')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                                @if ($probation->supporting_documents)
                                    @php
                                        $filePath = asset($probation->supporting_documents);
                                        $extension = strtolower(
                                            pathinfo($probation->supporting_documents, PATHINFO_EXTENSION),
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
                                <label for="comments" class="form-label">Comments</label>
                                <textarea class="form-control" id="comments" name="comments" rows="3">{{ old('comments', $probation->comments) }}</textarea>
                                @error('comments')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row mb-3 card p-3  shadow-sm">
                                <div class="col-md-12 mt-3">
                                    <label for="overall_rating" class="form-label">Overall Rating</label>
                                    <div class="d-flex gap-4 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="overall_rating"
                                                id="not_satisfied" value="1"
                                                {{ old('overall_rating', $probation->overall_rating) == '1' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="not_satisfied">Not Satisfied</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="overall_rating"
                                                id="somewhat_satisfied" value="2"
                                                {{ old('overall_rating', $probation->overall_rating) == '2' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="somewhat_satisfied">Somewhat
                                                Satisfied</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="overall_rating"
                                                id="satisfied" value="3"
                                                {{ old('overall_rating', $probation->overall_rating) == '3' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="satisfied">Satisfied</label>
                                        </div>
                                    </div>
                                    @error('overall_rating')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12 mt-3">
                                    <label for="appropriate_option" class="form-label">With the above evaluation,
                                        choose the appropriate option for the employee.</label>
                                    <div class="gap-4">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="radio" name="appropriate_option"
                                                id="confirmed" value="1"
                                                {{ old('appropriate_option', $probation->appropriate_option) == '1' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="confirmed">This employee has
                                                completed the probation period and confirmed</label>
                                        </div>
                                        <div class="form-check mt-4">
                                            <input class="form-check-input" type="radio" name="appropriate_option"
                                                id="extended" value="2"
                                                {{ old('appropriate_option', $probation->appropriate_option) == '2' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="extended">This employee's probation
                                                is to be extended</label>
                                        </div>
                                        <div class="form-check mt-4">
                                            <input class="form-check-input" type="radio" name="appropriate_option"
                                                id="terminated" value="3"
                                                {{ old('appropriate_option', $probation->appropriate_option) == '3' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="terminated">This employee has been
                                                terminated</label>
                                        </div>
                                    </div>
                                    @error('appropriate_option')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Signature and Submit -->
                    <div class="row mt-4">
                        <h5 class="mt-4 mb-3 border-bottom pb-2">HR Acknowledgement</h5>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="date_of_evaluation" class="form-label">Date of Probation</label>
                                <input type="date" class="form-control" id="date_of_evaluation"
                                    name="date_of_evaluation"
                                    value="{{ old('date_of_evaluation', $probation->date_of_evaluation ? $probation->date_of_evaluation->format('Y-m-d') : '') }}">
                                @error('date_of_evaluation')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="mb-3">
                                <label class="form-label">HR Signature</label>
                                <div id="hr-signature-pad" class="signature-pad border rounded"
                                    style="height: 150px; background-color: #f8f9fa; position:relative;">
                                    <canvas id="hr-signature-canvas" style="width: 100%; height: 100%;"></canvas>
                                    <i class="bi bi-x-circle text-danger" id="clear-hr-signature"
                                        style="position:absolute; top:5px; right:5px; cursor:pointer;"></i>
                                </div>

                                <!-- Hidden fields -->
                                <input type="hidden" id="hr_signature_data" name="hr_signature_data"
                                    value="">
                                <input type="hidden" id="hr_signature_delete" name="hr_signature_delete"
                                    value="0">

                                @if ($probation->hr_signature)
                                    @php $hrFilePath = asset($probation->hr_signature); @endphp
                                    <div class="mt-2">
                                        <small>Current HR Signature:</small>
                                        <div class="mt-1">
                                            <img id="existing_hr_signature_preview" src="{{ $hrFilePath }}"
                                                alt="hr_signature"
                                                style="max-width: 500px; height: auto; border: 1px solid #ddd; border-radius: 6px;">
                                        </div>
                                    </div>
                                @endif
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

        #hr-signature-canvas {
            background-color: #f8f9fa;
        }

        #clear-hr-signature {
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

        /* Table Styling */
        .table {
            border-collapse: separate;
            border-spacing: 0;
            background-color: #fff;
        }

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
            transform: scale(1.2);
            margin-top: 2px;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize HR Signature Pad
            const hrCanvas = document.getElementById('hr-signature-canvas');
            const hrSignaturePad = new SignaturePad(hrCanvas, {
                backgroundColor: '#ffffff',
                penColor: 'rgb(0, 0, 0)'
            });
            const hrDataField = document.getElementById('hr_signature_data');
            const hrDeleteField = document.getElementById('hr_signature_delete');
            const clearHrButton = document.getElementById('clear-hr-signature');
            const existingHrPreview = document.getElementById('existing_hr_signature_preview');

            const form = document.getElementById('probationForm');

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

            resizeCanvas(hrCanvas, hrSignaturePad);

            window.addEventListener('resize', function() {
                resizeCanvas(hrCanvas, hrSignaturePad);
            });

            // Clear HR Signature
            clearHrButton.addEventListener('click', function() {
                hrSignaturePad.clear();
                hrDataField.value = "";
                hrDeleteField.value = "1";
                if (existingHrPreview) existingHrPreview.style.display = "none";
            });

            // When user starts drawing → reset delete flag
            function markAsNew(signaturePad, deleteField, existingPreview) {
                deleteField.value = "0";
                if (existingPreview) existingPreview.style.display = "none";
            }

            hrCanvas.addEventListener("mousedown", () => markAsNew(hrSignaturePad, hrDeleteField,
                existingHrPreview));
            hrCanvas.addEventListener("touchstart", () => markAsNew(hrSignaturePad, hrDeleteField,
                existingHrPreview));

            // Before submit → save base64 if drawn
            form.addEventListener("submit", function() {
                if (!hrSignaturePad.isEmpty()) {
                    hrDataField.value = hrSignaturePad.toDataURL();
                    hrDeleteField.value = "0";
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

                fetch(`{{ route('probation.search') }}?term=${encodeURIComponent(searchTerm)}`, {
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
                                    <br>
                                    <small class="text-info">Supervisor: ${employee.supervisor_display || 'Not assigned'}</small>
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
                // Fill basic employee information
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

                // STEP 2: Auto-populate supervisor name (readonly) - use only name without role
                document.getElementById('supervisor_name').value = employee.supervisor_name ||
                    'No supervisor assigned';

                // Date fields
                if (employee.dojprovision_from_date) {
                    const hireDate = new Date(employee.dojprovision_from_date);
                    const formattedDate = hireDate.toISOString().split('T')[0];
                    document.getElementById('date_of_joined').value = formattedDate;
                    document.getElementById('probation_from').value = formattedDate;
                } else {
                    document.getElementById('date_of_joined').value = '';
                    document.getElementById('probation_from').value = '';
                }

                if (employee.provision_to_date) {
                    const probationEndDate = new Date(employee.provision_to_date);
                    const formattedDate = probationEndDate.toISOString().split('T')[0];
                    document.getElementById('probation_to').value = formattedDate;
                } else {
                    document.getElementById('probation_to').value = '';
                }

                // Update employee image
                const img = document.getElementById('employee_image');
                img.src = employee.image ? '/employee_images/' + employee.image : '/images/admin_default.jpg';
                img.alt = employee.fullname;

                // Clear search and hide results
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
        });
    </script>
</x-layout>
