<x-layout>
    @section('title', 'Edit Promotion')
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
                            <a href="{{ route('prolist') }}" class="text-decoration-none text-muted">Promotion</a>
                        </li>
                        <li class="breadcrumb-item active text-primary" aria-current="page">Edit Promotion</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('prolist') }}" class="btn btn-outline-primary d-flex align-items-center">
                <i class="bi bi-arrow-left-circle me-2"></i>Back
            </a>
        </div>

        <x-message />
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('proupdate', $promotion->promotion_id) }}" method="POST"
                    enctype="multipart/form-data" id="promotionForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="employee_id" id="employee_id"
                        value="{{ old('employee_id', $promotion->employee_id) }}">
                    <input type="hidden" name="department_id" id="department_id"
                        value="{{ old('department_id', $promotion->department) }}">
                    <input type="hidden" name="designation_id" id="designation_id"
                        value="{{ old('designation_id', $promotion->designation) }}">
                    <input type="hidden" name="proposed_designation_id" id="proposed_designation_id"
                        value="{{ old('proposed_designation_id', $promotion->proposed_designation) }}">

                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="employee_id" class="form-label">Employee Id</label>
                                <input type="text" class="form-control" id="employee_id_display" readonly
                                    value="{{ old('employee_id_display', $employee->employee_id ?? '') }}">
                            </div>

                            <div class="mb-3">
                                <label for="emp_email" class="form-label">Employee Email</label>
                                <input type="email" class="form-control" id="emp_email" name="emp_email" readonly
                                    value="{{ old('emp_email', $employee->email_company) }}">
                            </div>

                            <div class="mb-3">
                                <label for="department" class="form-label">Department</label>
                                <input type="text" class="form-control" id="department" name="department_display"
                                    readonly value="{{ old('department_display', $department->dep_name ?? '') }}">
                            </div>

                            <div class="mb-3">
                                <label for="designation" class="form-label">Designation</label>
                                <input type="text" class="form-control" id="designation" name="designation_display"
                                    readonly
                                    value="{{ old('designation_display', $currentDesignation->des_name ?? '') }}">
                            </div>

                            <h5 class="mt-4 mb-3 border-bottom pb-2">Promotion Information</h5>
                            <div class="mb-3">
                                <label for="proposed_designation" class="form-label">Proposed Designation</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="proposed_designation"
                                        name="proposed_designation_display" placeholder="Search designations"
                                        autocomplete="off"
                                        value="{{ old('proposed_designation_display', $proposedDesignation->des_name ?? '') }}">
                                    <button class="btn btn-outline-secondary" type="button"
                                        id="designation_search_button">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                                <ul id="designation_results" class="list-group mt-2"
                                    style="display: none; max-height: 200px; overflow-y: auto;"></ul>
                            </div>
                            <div class="mb-3">
                                <label for="percentage_increase" class="form-label">Percentage Increase</label>
                                <div class="input-group">
                                    <input type="text" class="form-control percentage_increase"
                                        id="percentage_increase" name="percentage_increase"
                                        pattern="^(100(\.00?)?|[0-9]{1,2}(\.\d{1,2})?)$"
                                        value="{{ old('percentage_increase', $promotion->percentage_increase) }}">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="supporting_documents" class="form-label">Supporting Documents</label>
                                <input type="file" class="form-control" id="supporting_documents"
                                    name="supporting_documents">
                                @if ($promotion->supporting_documents)
                                    @php
                                        $filePath = asset($promotion->supporting_documents);
                                        $extension = strtolower(
                                            pathinfo($promotion->supporting_documents, PATHINFO_EXTENSION),
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
                                                {{-- Optional: Inline embed for PDF preview --}}
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
                                <label for="additional_comments" class="form-label">Additional Comments</label>
                                <textarea class="form-control" id="additional_comments" name="additional_comments" rows="3">{{ old('additional_comments', $promotion->additional_comments) }}</textarea>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">
                            <div class="mb-5 text-center">
                                <img src="{{ $employee->image ? asset('employee_images/' . $employee->image) : asset('images/admin_default.jpg') }}"
                                    alt="" class="avatar-rounded" id="employee_image">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Search Employee</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="employee_search"
                                        placeholder="Search by name or ID"
                                        value="{{ old('employee_search', $employee->fullname ?? '') }}">
                                    <button class="btn btn-outline-secondary" type="button" id="search_button">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                                <ul id="employee_results" class="list-group mt-2" style="display: none;"></ul>
                            </div>

                            <div class="mb-3">
                                <label for="full_name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="full_name" name="full_name_display"
                                    readonly value="{{ old('full_name_display', $employee->fullname ?? '') }}">
                            </div>

                            <div class="mb-3">
                                <label for="date_of_hire" class="form-label">Date of Hire</label>
                                <input type="date" class="form-control" id="date_of_hire" name="date_of_hire"
                                    readonly
                                    value="{{ old('date_of_hire', $employee->dojprovision_from_date ? \Carbon\Carbon::parse($employee->dojprovision_from_date)->format('Y-m-d') : '') }}">
                            </div>

                            <div class="mb-3">
                                <label for="proposed_new_salary" class="form-label proposed_new_salary">Proposed New
                                    Salary (₹)</label>
                                <input type="text" class="form-control proposed_new_salary"
                                    id="proposed_new_salary" name="proposed_new_salary"
                                    value="{{ old('proposed_new_salary', $promotion->proposed_new_salary) }}">
                            </div>

                            <div class="mb-3">
                                <label for="reason_for_promotion" class="form-label">Reason for Promotion</label>
                                <textarea class="form-control" id="reason_for_promotion" name="reason_for_promotion" rows="3">{{ old('reason_for_promotion', $promotion->reason_for_promotion) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Signature and Submit -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5 class="mt-4 mb-3 border-bottom pb-2">Manager Approval</h5>
                            <div class="mb-3">
                                <label for="manager_name" class="form-label">Manager Name</label>
                                <input type="text" class="form-control" id="manager_name" name="manager_name"
                                    value="{{ old('manager_name', $promotion->manager_name) }}" readonly>

                            </div>
                            <div class="mb-3">
                                <label for="date_of_signature" class="form-label">Date of Signature</label>
                                <input type="date" class="form-control" id="date_of_signature"
                                    name="date_of_signature"
                                    value="{{ old('date_of_signature', $promotion->date_of_signature ? \Carbon\Carbon::parse($promotion->date_of_signature)->format('Y-m-d') : '') }}">



                            </div>
                        </div>
                        <div class="col-md-6 align-content-center">
                            <div class="mb-3 mt-5">
                                <label class="form-label">Manager Signature</label>
                                <div id="signature-pad" class="signature-pad border rounded"
                                    style="height: 150px; background-color: #f8f9fa; position:relative;">
                                    <canvas id="signature-canvas" style="width: 100%; height: 100%;"></canvas>
                                    <i class="bi bi-x-circle text-danger" id="clear-signature"
                                        style="position:absolute; top:5px; right:5px; cursor:pointer;"></i>
                                </div>

                                <!-- Hidden fields -->
                                <input type="hidden" id="manager_signature_data" name="manager_signature_data"
                                    value="">
                                <input type="hidden" id="manager_signature_delete" name="manager_signature_delete"
                                    value="0">

                                @if ($promotion->manager_signature)
                                    @php $filePath = asset($promotion->manager_signature); @endphp

                                    <div class="mt-2">
                                        <small>Current Manager Signature:</small>
                                        <div class="mt-1">
                                            <img id="existing_signature_preview" src="{{ $filePath }}"
                                                alt="manager_signature"
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
        /* Same styles as create.blade.php */
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

        #signature-canvas {
            background-color: #f8f9fa;
        }

        #clear-signature {
            position: absolute;
            right: 10px;
            top: 10px;
            font-size: 1.2em;
            cursor: pointer;
        }

        #employee_results,
        #designation_results {
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

        .employee-item,
        .designation-item {
            cursor: pointer;
        }

        .employee-item:hover,
        .designation-item:hover {
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
            // Initialize Signature Pad
            const canvas = document.getElementById('signature-canvas');
            const signaturePad = new SignaturePad(canvas, {
                backgroundColor: '#ffffff', // white background
                penColor: 'rgb(0, 0, 0)' // black ink
            });
            const dataField = document.getElementById('manager_signature_data');
            const deleteField = document.getElementById('manager_signature_delete');
            const clearButton = document.getElementById('clear-signature');
            const existingPreview = document.getElementById('existing_signature_preview');
            const form = document.getElementById('promotionForm');

            // Adjust canvas size for crisp drawing
            function resizeCanvas() {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext('2d').scale(ratio, ratio);

                // Clear any existing drawing that might be stretched
                if (signaturePad.isEmpty()) {
                    signaturePad.clear();
                }
            }
            resizeCanvas();
            window.addEventListener('resize', resizeCanvas);

            // Clear signature action
            clearButton.addEventListener('click', function() {
                signaturePad.clear();
                dataField.value = "";
                deleteField.value = "1"; // mark for deletion
                if (existingPreview) existingPreview.style.display = "none";
            });

            // When user starts drawing → reset delete flag
            function markAsNew() {
                deleteField.value = "0";
                if (existingPreview) existingPreview.style.display = "none";
            }
            canvas.addEventListener("mousedown", markAsNew);
            canvas.addEventListener("touchstart", markAsNew);

            // Before submit → save base64 if drawn
            form.addEventListener("submit", function() {
                if (!signaturePad.isEmpty()) {
                    dataField.value = signaturePad.toDataURL();
                    deleteField.value = "0";
                }
            });

            // =========================
            // Employee Search (Updated with supervisor logic)
            // =========================
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
                document.getElementById('employee_id_display').value = employee.employee_id;
                document.getElementById('employee_id').value = employee.emp_id;
                document.getElementById('emp_email').value = employee.email;
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
                document.getElementById('manager_name').value = employee.supervisor_name ||
                    'No supervisor assigned';

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

            // =========================
            // Designation Search (unchanged)
            // =========================
            const designationInput = document.getElementById('proposed_designation');
            const designationResults = document.getElementById('designation_results');
            let designations = [];

            function fetchDesignations() {
                fetch(`{{ route('designations.list') }}`, {
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
                        designations = data;
                    })
                    .catch(error => {
                        console.error('Error fetching designations:', error);
                    });
            }

            function searchDesignations() {
                const searchTerm = designationInput.value.trim().toLowerCase();
                if (searchTerm.length < 1) {
                    designationResults.style.display = 'none';
                    return;
                }

                const filteredDesignations = designations.filter(designation =>
                    designation.des_name.toLowerCase().includes(searchTerm) ||
                    (designation.des_id && designation.des_id.toString().includes(searchTerm))
                );

                designationResults.innerHTML = '';
                if (filteredDesignations.length === 0) {
                    designationResults.innerHTML =
                        '<li class="list-group-item disabled">No designations found</li>';
                } else {
                    filteredDesignations.forEach(designation => {
                        const item = document.createElement('li');
                        item.className = 'list-group-item designation-item';
                        item.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">${designation.des_name}</h6>
                            <small class="text-muted">ID: ${designation.des_id}</small>
                        </div>
                    </div>
                `;
                        item.addEventListener('click', function() {
                            designationInput.value = designation.des_name;
                            document.getElementById('proposed_designation_id').value = designation
                                .des_id;
                            designationResults.style.display = 'none';
                        });
                        designationResults.appendChild(item);
                    });
                }
                designationResults.style.display = 'block';
            }

            designationInput.addEventListener('input', searchDesignations);
            document.addEventListener('click', function(e) {
                if (!designationResults.contains(e.target) && e.target !== designationInput) {
                    designationResults.style.display = 'none';
                }
            });
            fetchDesignations();

            // =========================
            // Percentage input formatting
            // =========================
            function handlePercentageInput(e) {
                let value = this.value;
                value = value.replace(/[^0-9.]/g, '');
                const decimalSplit = value.split('.');
                if (decimalSplit.length > 2) value = decimalSplit[0] + '.' + decimalSplit[1];
                if (decimalSplit.length > 1) value = decimalSplit[0] + '.' + decimalSplit[1].slice(0, 2);
                if (parseFloat(value) > 100) value = '100';
                this.value = value;
            }

            function handlePercentageBlur() {
                if (this.value) {
                    if (this.value.includes('.')) {
                        const parts = this.value.split('.');
                        this.value = `${parts[0]}.${parts[1].padEnd(2, '0').slice(0, 2)}`;
                    } else {
                        this.value = `${this.value}.00`;
                    }
                }
            }

            document.querySelectorAll('.percentage_increase').forEach(input => {
                input.addEventListener('input', handlePercentageInput);
                input.addEventListener('blur', handlePercentageBlur);
            });

            // =========================
            // Currency formatting
            // =========================
            function formatCTC(e) {
                const input = e.target;
                let value = input.value.replace(/[^\d.]/g, '');
                value = value.replace(/₹|,/g, '');
                if (value === '') {
                    input.value = '';
                    return;
                }
                if ((value.match(/\./g) || []).length > 1) {
                    value = value.substring(0, value.lastIndexOf('.'));
                }
                let parts = value.split('.');
                let integerPart = parts[0];
                let decimalPart = parts.length > 1 ? '.' + parts[1].substring(0, 2) : '';
                integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                input.value = '₹' + integerPart + decimalPart;
            }

            document.querySelectorAll('.proposed_new_salary').forEach(input => {
                input.addEventListener('input', formatCTC);
                input.addEventListener('blur', formatCTC);
            });
        });
    </script>

</x-layout>
