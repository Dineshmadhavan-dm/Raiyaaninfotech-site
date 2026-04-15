<x-layout>
    @section('title', 'Termination Create')
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
                        <li class="breadcrumb-item active text-primary" aria-current="page">Termination</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('terminationlist') }}" class="btn btn-outline-primary d-flex align-items-center">
                <i class="bi bi-arrow-left-circle me-2"></i>Back
            </a>
        </div>

        <x-message />
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('terminationstore') }}" method="POST" enctype="multipart/form-data"
                    id="terminationForm">
                    @csrf
                    <input type="hidden" name="employee_id" id="employee_id" value="{{ old('employee_id') }}">
                    <input type="hidden" name="department_id" id="department_id" value="{{ old('department_id') }}">
                    <input type="hidden" name="designation_id" id="designation_id" value="{{ old('designation_id') }}">
                    <input type="hidden" name="proposed_designation_id" id="proposed_designation_id"
                        value="{{ old('proposed_designation_id') }}">

                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="employee_id" class="form-label">Employee Id</label>
                                <input type="text" class="form-control" id="employee_id_display" readonly
                                    value="{{ old('employee_id_display') }}">
                            </div>

                            <div class="mb-3">
                                <label for="employee_email" class="form-label">Employee Email</label>
                                <input type="email" class="form-control" id="employee_email" name="employee_email"
                                    readonly value="{{ old('employee_email') }}">
                            </div>

                            <div class="mb-3">
                                <label for="department" class="form-label">Department</label>
                                <input type="text" class="form-control" id="department" name="department_display"
                                    readonly value="{{ old('department_display') }}">
                            </div>

                            <div class="mb-3">
                                <label for="designation" class="form-label">Designation</label>
                                <input type="text" class="form-control" id="designation" name="designation_display"
                                    readonly value="{{ old('designation_display') }}">
                            </div>

                            <h5 class="mt-4 mb-3 border-bottom pb-2">Termination Information</h5>

                            <div class="mb-3">
                                <label for="proposed_designation" class="form-label">Proposed Designation</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="proposed_designation"
                                        name="proposed_designation_display" placeholder="Search designations"
                                        autocomplete="off" value="{{ old('proposed_designation_display') }}">
                                    <button class="btn btn-outline-secondary" type="button"
                                        id="designation_search_button">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                                <ul id="designation_results" class="list-group mt-2"
                                    style="display: none; max-height: 200px; overflow-y: auto;"></ul>

                            </div>


                            <div class="mb-3">
                                <div class="d-flex align-items-center">
                                    <label class="form-label mb-0 me-3">Employee termination is</label>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="termination_type"
                                            id="voluntary" value="1"
                                            {{ old('termination_type') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="voluntary">Voluntary</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="termination_type"
                                            id="involuntary" value="0"
                                            {{ old('termination_type') == '0' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="involuntary">Involuntary</label>
                                    </div>
                                </div>



                                @error('termination_type')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>





                            <div class="mb-3">
                                <label for="reason_for_termination" class="form-label">Reason for Termination</label>
                                <textarea class="form-control" id="reason_for_termination" name="reason_for_termination" rows="3">{{ old('reason_for_termination') }}</textarea>
                                @error('reason_for_termination')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="mb-3">
                                <label class="form-label">Can the employee be rehired?</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="can_be_rehired"
                                            id="rehire_yes" value="1"
                                            {{ old('can_be_rehired') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="rehire_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="can_be_rehired"
                                            id="rehire_no" value="0"
                                            {{ old('can_be_rehired') == '0' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="rehire_no">No</label>
                                    </div>
                                </div>
                                @error('can_be_rehired')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3" id="rehire_conditions_container" style="display: none;">
                                <label for="rehire_conditions" class="form-label">Under the following
                                    circumstances</label>
                                <textarea class="form-control" id="rehire_conditions" name="rehire_conditions" rows="3">{{ old('rehire_conditions') }}</textarea>
                                @error('rehire_conditions')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>


                        </div>





                        <!-- Right Column -->
                        <div class="col-md-6">
                            <div class=" text-center" style=" margin-bottom: 2.1em;">
                                <img src="{{ asset('images/admin_default.jpg') }}" alt=""
                                    class="avatar-rounded" id="employee_image">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Search Employee</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="employee_search"
                                        placeholder="Search by name or ID" value="{{ old('employee_search') }}">
                                    <button class="btn btn-outline-secondary" type="button" id="search_button">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                                <ul id="employee_results" class="list-group mt-2" style="display: none;"></ul>
                            </div>

                            <div class="mb-3">
                                <label for="full_name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" readonly
                                    value="{{ old('full_name') }}">
                                @error('full_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="date_of_hire" class="form-label">Date of Hire</label>
                                <input type="date" class="form-control" id="date_of_hire" name="date_of_hire"
                                    readonly value="{{ old('date_of_hire') }}">
                                @error('date_of_hire')
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
                            </div>

                            <div class="mb-3">
                                <label for="employee_statement" class="form-label">Employee's statement</label>
                                <textarea class="form-control" id="employee_statement" name="employee_statement" rows="3">{{ old('employee_statement') }}</textarea>
                                @error('employee_statement')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>










                            <div class="mb-3">
                                <label class="form-label">Was he/she willing to handover his/her task?</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="confirm_termination"
                                            id="confirm_termination_yes" value="1"
                                            {{ old('confirm_termination') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="confirm_termination_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="confirm_termination"
                                            id="confirm_termination_no" value="0"
                                            {{ old('confirm_termination') == '0' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="confirm_termination_no">No</label>
                                    </div>
                                </div>
                                @error('confirm_termination')
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
                                <label for="termination_date" class="form-label">Date of Termination </label>
                                <input type="date" class="form-control" id="termination_date"
                                    name="termination_date" value="{{ old('termination_date') }}">
                                @error('termination_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>


                        </div>


                        <div class="form-check mb-3" style="margin-left: .8em;">
                            <input class="form-check-input" type="checkbox" id="acknowledgement"
                                onchange="document.getElementById('acknowledgement_value').value = this.checked ? 1 : 0"
                                {{ old('acknowledgement') ? 'checked' : '' }}>
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
                            <div class="col-md-6 align-content-center">
                                <div class="mb-3">
                                    <label class="form-label">Employee Signature</label>
                                    <div id="employee-signature-pad" class="signature-pad border rounded"
                                        style="height: 150px; background-color: #f8f9fa;">
                                        <canvas id="employee-signature-canvas"
                                            style="width: 100%; height: 100%;"></canvas>
                                        <i class="bi bi-x-circle text-danger" id="clear-employee-signature"></i>
                                    </div>
                                    <input type="hidden" id="employee_signature" name="employee_signature"
                                        value="{{ old('employee_signature') }}">
                                    @error('employee_signature')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 align-content-center">
                                <div class="mb-3 ">
                                    <label class="form-label">Manager Signature</label>
                                    <div id="manager-signature-pad" class="signature-pad border rounded"
                                        style="height: 150px; background-color: #f8f9fa;">
                                        <canvas id="manager-signature-canvas"
                                            style="width: 100%; height: 100%;"></canvas>
                                        <i class="bi bi-x-circle text-danger" id="clear-manager-signature"></i>
                                    </div>
                                    <input type="hidden" id="manager_signature" name="manager_signature"
                                        value="{{ old('manager_signature') }}">
                                    @error('manager_signature')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary px-4">Submit</button>
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

        .signature-canvas {
            background-color: #f8f9fa;
        }

        #clear-employee-signature,
        #clear-manager-signature {
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
            // Initialize Signature Pads
            const employeeCanvas = document.getElementById('employee-signature-canvas');
            const employeeSignaturePad = new SignaturePad(employeeCanvas, {
                backgroundColor: 'rgb(248, 249, 250)',
                penColor: 'rgb(0, 0, 0)'
            });

            const managerCanvas = document.getElementById('manager-signature-canvas');
            const managerSignaturePad = new SignaturePad(managerCanvas, {
                backgroundColor: 'rgb(248, 249, 250)',
                penColor: 'rgb(0, 0, 0)'
            });

            // Adjust canvas sizes
            function resizeCanvas(canvas, signaturePad) {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext('2d').scale(ratio, ratio);
                signaturePad.clear();
            }

            resizeCanvas(employeeCanvas, employeeSignaturePad);
            resizeCanvas(managerCanvas, managerSignaturePad);

            window.addEventListener('resize', function() {
                resizeCanvas(employeeCanvas, employeeSignaturePad);
                resizeCanvas(managerCanvas, managerSignaturePad);
            });

            // Clear signatures
            document.getElementById('clear-employee-signature').addEventListener('click', function() {
                employeeSignaturePad.clear();
                document.getElementById('employee_signature').value = '';
            });

            document.getElementById('clear-manager-signature').addEventListener('click', function() {
                managerSignaturePad.clear();
                document.getElementById('manager_signature').value = '';
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

            // Event listeners for employee search
            searchInput.addEventListener('input', searchEmployees);
            searchButton.addEventListener('click', searchEmployees);

            document.addEventListener('click', function(e) {
                if (!resultsList.contains(e.target) && e.target !== searchInput && e.target !==
                    searchButton) {
                    resultsList.style.display = 'none';
                }
            });
            // Designation Search Functionality
            const designationInput = document.getElementById('proposed_designation');
            const designationResults = document.getElementById('designation_results');
            let designations = [];

            // Fetch designations from server
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

            // Search designations
            function searchDesignations() {
                const searchTerm = designationInput.value.trim().toLowerCase();

                if (searchTerm.length < 1) {
                    designationResults.style.display = 'none';
                    return;
                }

                // Filter designations based on search term
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

            // Event listeners for designation search
            designationInput.addEventListener('input', searchDesignations);

            // Hide designation results when clicking elsewhere
            document.addEventListener('click', function(e) {
                if (!designationResults.contains(e.target) && e.target !== designationInput) {
                    designationResults.style.display = 'none';
                }
            });

            // Fetch designations on page load
            fetchDesignations();

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

            // Form submission
            document.getElementById('terminationForm').addEventListener('submit', function(e) {




                if (managerSignaturePad.isEmpty()) {
                    e.preventDefault();
                    alert('Please provide a manager signature before submitting.');
                } else {
                    // Convert signatures to data URLs and store in hidden inputs
                    if (!employeeSignaturePad.isEmpty()) {
                        document.getElementById('employee_signature').value = employeeSignaturePad
                            .toDataURL();
                    }
                    document.getElementById('manager_signature').value = managerSignaturePad.toDataURL();
                }
            });
        });
    </script>
</x-layout>
