<x-layout>
    @section('title', 'Promotion Create')
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
                        <li class="breadcrumb-item active text-primary" aria-current="page">Promotion</li>
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
                <form action="{{ route('prostore') }}" method="POST" enctype="multipart/form-data" id="promotionForm">
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
                                <label for="emp_email" class="form-label">Employee Email</label>
                                <input type="email" class="form-control" id="emp_email" name="emp_email" readonly
                                    value="{{ old('emp_email') }}">
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

                            <h5 class="mt-4 mb-3 border-bottom pb-2">Promotion Information</h5>
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
                                <label for="percentage_increase" class="form-label">Percentage Increase</label>
                                <div class="input-group">
                                    <input type="text" class="form-control percentage_increase"
                                        id="percentage_increase" name="percentage_increase"
                                        pattern="^(100(\.00?)?|[0-9]{1,2}(\.\d{1,2})?)$"
                                        value="{{ old('percentage_increase') }}">
                                    <span class="input-group-text">%</span>
                                </div>

                            </div>

                            <div class="mb-3">
                                <label for="supporting_documents" class="form-label">Supporting Documents</label>
                                <input type="file" class="form-control" id="supporting_documents"
                                    name="supporting_documents">

                            </div>

                            <div class="mb-3">
                                <label for="additional_comments" class="form-label">Additional Comments</label>
                                <textarea class="form-control" id="additional_comments" name="additional_comments" rows="3">{{ old('additional_comments') }}</textarea>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">
                            <div class="mb-5 text-center">
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
                                <input type="text" class="form-control" id="full_name" name="full_name_display"
                                    readonly value="{{ old('full_name_display') }}">
                            </div>

                            <div class="mb-3">
                                <label for="date_of_hire" class="form-label">Date of Hire</label>
                                <input type="date" class="form-control" id="date_of_hire" name="date_of_hire"
                                    readonly value="{{ old('date_of_hire') }}">

                            </div>

                            <div class="mb-3">
                                <label for="proposed_new_salary" class="form-label proposed_new_salary">Proposed New
                                    Salary (₹)</label>
                                <input type="text" class="form-control proposed_new_salary"
                                    id="proposed_new_salary" name="proposed_new_salary"
                                    value="{{ old('proposed_new_salary') }}">

                            </div>

                            <div class="mb-3">
                                <label for="reason_for_promotion" class="form-label">Reason for Promotion</label>
                                <textarea class="form-control" id="reason_for_promotion" name="reason_for_promotion" rows="3">{{ old('reason_for_promotion') }}</textarea>

                            </div>
                        </div>
                    </div>

                    <!-- Signature and Submit -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5 class="mt-4 mb-3 border-bottom pb-2">Manager Approval</h5>







                            <!-- Find the Manager Name section and replace it with this -->
                            <div class="mb-3">
                                <label for="manager_name" class="form-label">Manager Name</label>
                                <input type="text" class="form-control" id="manager_name" name="manager_name"
                                    value="{{ old('manager_name') }}" readonly>

                            </div>


                            <div class="mb-3">
                                <label for="date_of_signature" class="form-label">Date of Signature</label>
                                <input type="date" class="form-control" id="date_of_signature"
                                    name="date_of_signature" value="{{ old('date_of_signature') }}">

                            </div>
                        </div>
                        <div class="col-md-6 align-content-center">
                            <div class="mb-3 mt-5">
                                <label class="form-label">Manager Signature</label>
                                <div id="signature-pad" class="signature-pad border rounded"
                                    style="height: 150px; background-color: #f8f9fa;">
                                    <canvas id="signature-canvas" style="width: 100%; height: 100%;"></canvas>
                                    <i class="bi bi-x-circle text-danger" id="clear-signature"></i>
                                </div>
                                <input type="hidden" id="manager_signature" name="manager_signature"
                                    value="{{ old('manager_signature') }}">

                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary px-4">Add</button>
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


        /* designation search */
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

        .designation-item {
            cursor: pointer;
        }

        .designation-item:hover {
            background-color: #f8f9fa;
        }
    </style>


    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Signature Pad
            const canvas = document.getElementById('signature-canvas');
            const signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgb(248, 249, 250)',
                penColor: 'rgb(0, 0, 0)'
            });

            // Adjust canvas size
            function resizeCanvas() {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext('2d').scale(ratio, ratio);
                signaturePad.clear();
            }
            resizeCanvas();
            window.addEventListener('resize', resizeCanvas);

            // Clear signature
            document.getElementById('clear-signature').addEventListener('click', function() {
                signaturePad.clear();
                document.getElementById('manager_signature').value = '';
            });

            // Employee Search Functionality
            const searchInput = document.getElementById('employee_search');
            const searchButton = document.getElementById('search_button');
            const resultsList = document.getElementById('employee_results');

            // Search employees
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

            // Fill employee details
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

                // Add the date of hire (dojprovision_from_date)
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

            // Percentage input handling
            function handlePercentageInput(e) {
                let value = this.value;

                // Allow only numbers and decimal point
                value = value.replace(/[^0-9.]/g, '');

                // Ensure only one decimal point
                const decimalSplit = value.split('.');
                if (decimalSplit.length > 2) {
                    value = decimalSplit[0] + '.' + decimalSplit[1];
                }

                // Limit to 2 decimal places
                if (decimalSplit.length > 1) {
                    value = decimalSplit[0] + '.' + decimalSplit[1].slice(0, 2);
                }

                // Ensure value doesn't exceed 100
                if (parseFloat(value) > 100) {
                    value = '100';
                }

                this.value = value;
            }

            function handlePercentageBlur() {
                if (this.value) {
                    // Format to 2 decimal places
                    if (this.value.includes('.')) {
                        const parts = this.value.split('.');
                        this.value = `${parts[0]}.${parts[1].padEnd(2, '0').slice(0, 2)}`;
                    } else {
                        this.value = `${this.value}.00`;
                    }
                }
            }

            // Initialize percentage inputs
            document.querySelectorAll('.percentage_increase').forEach(input => {
                input.addEventListener('input', handlePercentageInput);
                input.addEventListener('blur', handlePercentageBlur);
            });

            // Currency formatting
            function formatCTC(e) {
                const input = e.target;
                let value = input.value.replace(/[^\d.]/g, '');

                // Remove existing formatting if present
                value = value.replace(/₹|,/g, '');

                if (value === '') {
                    input.value = '';
                    return;
                }

                // Handle decimal points
                if ((value.match(/\./g) || []).length > 1) {
                    value = value.substring(0, value.lastIndexOf('.'));
                }

                // Format with Indian numbering system
                let parts = value.split('.');
                let integerPart = parts[0];
                let decimalPart = parts.length > 1 ? '.' + parts[1].substring(0, 2) : '';

                // Format integer part with commas
                integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ',');

                input.value = '₹' + integerPart + decimalPart;

                // Maintain cursor position
                const cursorPos = input.selectionStart;
                const originalLength = input.value.length;
                input.setSelectionRange(cursorPos, cursorPos);
            }

            // Initialize CTC fields
            document.querySelectorAll('.proposed_new_salary').forEach(input => {
                input.addEventListener('input', formatCTC);
                input.addEventListener('blur', formatCTC);
            });

            // Form submission
            document.getElementById('promotionForm').addEventListener('submit', function(e) {
                if (signaturePad.isEmpty()) {
                    e.preventDefault();
                    alert('Please provide a signature before submitting.');
                } else {
                    // Convert signature to data URL and store in hidden input
                    document.getElementById('manager_signature').value = signaturePad.toDataURL();
                }
            });
        });
    </script>
</x-layout>
