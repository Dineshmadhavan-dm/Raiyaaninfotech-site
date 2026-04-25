<x-layout>
    @section('title', 'Report Management')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        :root {
            --primary: var(--ra-primary-set);
            --primary-dark: #3a56d4;
            --secondary: #6c757d;
            --success: #10b981;
            --light: #f8fafc;
            --dark: #1e293b;
            --border: #e2e8f0;
        }

        .wizard-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 1.5rem;
        }

        .wizard-header {
            background: white;
            padding: 1.5rem 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            border: 1px solid var(--border);

        }

        .wizard-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.25rem;
        }

        .wizard-subtitle {
            color: var(--secondary);
            font-size: 0.875rem;
        }

        .wizard-steps {
            display: flex;
            gap: 0.5rem;
            background: white;
            padding: 0.75rem;
            margin-bottom: 1.5rem;
            border: 1px solid var(--border);

        }

        .step {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1.25rem;
            background: var(--light);
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid transparent;

        }

        .step:hover {
            background: #f1f5f9;
        }

        .step.active {
            background: var(--primary);
            border-color: var(--primary);
        }

        .step.active .step-number {
            background: rgba(255,255,255,0.2);
            color: white;
        }

        .step.active .step-title {
            color: white;
        }

        .step.active .step-desc {
            color: rgba(255,255,255,0.8);
        }

        .step-number {
            width: 36px;
            height: 36px;
            background: white;

            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
            color: var(--primary);
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        .step-info {
            flex: 1;
        }

        .step-title {
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--dark);
            margin-bottom: 0.125rem;
        }

        .step-desc {
            font-size: 0.75rem;
            color: var(--secondary);
        }

        .report-card {
            background: white;
            border: 1px solid var(--border);
            overflow: hidden;

            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border);
            background: white;
        }

        .card-header h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--dark);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.25rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        .form-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--secondary);
        }

        .form-label i {
            margin-right: 0.375rem;
            font-size: 0.75rem;
        }

        .form-control, .form-select {
            padding: 0.625rem 0.875rem;
            border: 1px solid var(--border);
            font-size: 0.875rem;
            transition: all 0.2s;
            background: white;
        }

        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67,97,238,0.1);
        }

        .select2-container--default .select2-selection--multiple {
            border: 1px solid var(--border);
            padding: 0.25rem;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.25rem 0.625rem;
            font-size: 0.75rem;
        }

        .action-buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
        }

        .btn {
            padding: 0.625rem 1.5rem;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }

        .btn-prev {
            background: white;
            border: 1px solid var(--border);
            color: var(--secondary);
        }

        .btn-prev:hover {
            background: var(--light);
            border-color: var(--secondary);
        }

        .btn-next, .btn-export {
            background: var(--primary);
            color: white;
        }

        .btn-next:hover, .btn-export:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .btn-export {
            background: var(--success);
            padding: 0.75rem 2rem;
        }

        .btn-export:hover {
            background: #0d9488;
        }


        .date-range-group {
            display: flex;
            gap: 0.75rem;
            align-items: center;
        }

        .date-range-group .form-control {
            flex: 1;
        }

        .date-range-group span {
            color: var(--secondary);
            font-size: 0.75rem;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            .form-group.full-width {
                grid-column: span 1;
            }
            .wizard-steps {
                flex-direction: column;
            }
            .step {
                padding: 0.75rem;
            }
            .date-range-group {
                flex-direction: column;
            }
        }
    </style>

    <div class="wizard-container">
        <div class="wizard-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="wizard-title">📊 Inventory Report Generator</div>
                    <div class="wizard-subtitle">Generate custom reports with advanced filtering</div>
                </div>
                <div class="text-muted small">
                    <i class="bi bi-calendar3 me-1"></i>{{ now()->format('d M Y, h:i A') }}
                </div>
            </div>
        </div>

        <div class="wizard-steps">
            <div class="step active" data-step="items">
                <div class="step-number">1</div>
                <div class="step-info">
                    <div class="step-title">Items Report</div>
                    <div class="step-desc">Inventory items & stock analysis</div>
                </div>
            </div>
            <div class="step" data-step="assignments">
                <div class="step-number">2</div>
                <div class="step-info">
                    <div class="step-title">Assignments Report</div>
                    <div class="step-desc">Item allocation tracking</div>
                </div>
            </div>
            <div class="step" data-step="maintenance">
                <div class="step-number">3</div>
                <div class="step-info">
                    <div class="step-title">Maintenance Report</div>
                    <div class="step-desc">Service & repair records</div>
                </div>
            </div>
            <div class="step" data-step="categories">
                <div class="step-number">4</div>
                <div class="step-info">
                    <div class="step-title">Categories Report</div>
                    <div class="step-desc">Category wise item summary</div>
                </div>
            </div>
        </div>

        <!-- Items Report -->
        <div class="step-content" id="step-items" style="display: block;">
            <div class="report-card">
                <div class="card-header">
                    <h3><i class="bi bi-box-seam"></i> Items Report Configuration</h3>
                </div>
                <div class="card-body">
                    <form id="itemsReportForm" method="GET" action="{{ route('reports.export-items') }}" target="_blank">
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label"><i class="bi bi-tags"></i> Categories</label>
                                <select name="category_ids[]" class="form-control items-category-select" multiple>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label"><i class="bi bi-layers"></i> Item Type</label>
                                <select name="item_type" class="form-select">
                                    <option value="">All Types</option>
                                    <option value="0">New</option>
                                    <option value="1">Refurbished</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label"><i class="bi bi-calendar-plus"></i> From Date</label>
                                <input type="date" name="from_date" class="form-control" id="items_from_date">
                            </div>

                            <div class="form-group">
                                <label class="form-label"><i class="bi bi-calendar-x"></i> To Date</label>
                                <input type="date" name="to_date" class="form-control" id="items_to_date">
                            </div>

                            <div class="form-group">
                                <label class="form-label"><i class="bi bi-sort-down"></i> Sort By</label>
                                <select name="sort_by" class="form-select">
                                    <option value="item_name">Item Name</option>
                                    <option value="item_code">Item Code</option>
                                    <option value="purchase_date">Purchase Date</option>
                                    <option value="purchase_cost">Cost</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label"><i class="bi bi-arrow-down-up"></i> Sort Order</label>
                                <select name="sort_order" class="form-select">
                                    <option value="asc">Ascending</option>
                                    <option value="desc">Descending</option>
                                </select>
                            </div>
                        </div>



                        <div class="action-buttons">
                            <div></div>
                            <div>
                                <button type="button" class="btn btn-next" data-next="assignments">Next →</button>
                                <button type="submit" class="btn btn-export ms-2"><i class="bi bi-file-pdf me-2"></i>Export PDF</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Assignments Report -->
        <div class="step-content" id="step-assignments" style="display: none;">
            <div class="report-card">
                <div class="card-header">
                    <h3><i class="bi bi-arrow-left-right"></i> Assignments Report Configuration</h3>
                </div>
                <div class="card-body">
                    <form id="assignmentsReportForm" method="GET" action="{{ route('reports.export-assignments') }}" target="_blank">
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label"><i class="bi bi-check-circle"></i> Assignment Status</label>
                                <select name="status" class="form-select">
                                    <option value="">All</option>
                                    <option value="0">Assigned</option>
                                    <option value="1">Returned</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label"><i class="bi bi-heart"></i> Condition Status</label>
                                <select name="condition_status" class="form-select">
                                    <option value="">All</option>
                                    <option value="1">Active</option>
                                    <option value="0">Scrap</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label"><i class="bi bi-building"></i> Department</label>
                                <select name="department_id" class="form-select" id="report_department">
                                    <option value="">All Departments</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->dep_id }}">{{ $dept->dep_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label"><i class="bi bi-people"></i> Employee</label>
                                <select name="employee_ids[]" class="form-control assignments-employee-select" multiple id="report_employee">
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label"><i class="bi bi-calendar-plus"></i> From Date</label>
                                <input type="date" name="from_date" class="form-control" id="assignments_from_date">
                            </div>

                            <div class="form-group">
                                <label class="form-label"><i class="bi bi-calendar-x"></i> To Date</label>
                                <input type="date" name="to_date" class="form-control" id="assignments_to_date">
                            </div>

                            <div class="form-group">
                                <label class="form-label"><i class="bi bi-sort-down"></i> Sort By</label>
                                <select name="sort_by" class="form-select">
                                    <option value="assigned_date">Assigned Date</option>
                                    <option value="return_date">Return Date</option>
                                    <option value="status">Status</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label"><i class="bi bi-arrow-down-up"></i> Sort Order</label>
                                <select name="sort_order" class="form-select">
                                    <option value="desc">Newest First</option>
                                    <option value="asc">Oldest First</option>
                                </select>
                            </div>
                        </div>



                        <div class="action-buttons">
                            <button type="button" class="btn btn-prev" data-prev="items">← Previous</button>
                            <div>
                                <button type="button" class="btn btn-next" data-next="maintenance">Next →</button>
                                <button type="submit" class="btn btn-export ms-2"><i class="bi bi-file-pdf me-2"></i>Export PDF</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Maintenance Report -->
        <div class="step-content" id="step-maintenance" style="display: none;">
            <div class="report-card">
                <div class="card-header">
                    <h3><i class="bi bi-tools"></i> Maintenance Report Configuration</h3>
                </div>
                <div class="card-body">
                    <form id="maintenanceReportForm" method="GET" action="{{ route('reports.export-maintenances') }}" target="_blank">
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label"><i class="bi bi-wrench"></i> Maintenance Type</label>
                                <select name="maintenance_type" class="form-select">
                                    <option value="">All Types</option>
                                    <option value="1">Service</option>
                                    <option value="2">Upgrade</option>
                                    <option value="0">Scrap</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label"><i class="bi bi-clock"></i> Status</label>
                                <select name="status" class="form-select">
                                    <option value="">All</option>
                                    <option value="0">Pending</option>
                                    <option value="1">Completed</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label"><i class="bi bi-calendar-plus"></i> From Date</label>
                                <input type="date" name="from_date" class="form-control" id="maintenance_from_date">
                            </div>

                            <div class="form-group">
                                <label class="form-label"><i class="bi bi-calendar-x"></i> To Date</label>
                                <input type="date" name="to_date" class="form-control" id="maintenance_to_date">
                            </div>

                            <div class="form-group">
                                <label class="form-label"><i class="bi bi-sort-down"></i> Sort By</label>
                                <select name="sort_by" class="form-select">
                                    <option value="start_date">Start Date</option>
                                    <option value="end_date">End Date</option>
                                    <option value="cost">Cost</option>
                                    <option value="status">Status</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label"><i class="bi bi-arrow-down-up"></i> Sort Order</label>
                                <select name="sort_order" class="form-select">
                                    <option value="desc">Newest First</option>
                                    <option value="asc">Oldest First</option>
                                </select>
                            </div>
                        </div>



                        <div class="action-buttons">
                            <button type="button" class="btn btn-prev" data-prev="assignments">← Previous</button>
                            <div>
                                <button type="button" class="btn btn-next" data-next="categories">Next →</button>
                                <button type="submit" class="btn btn-export ms-2"><i class="bi bi-file-pdf me-2"></i>Export PDF</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Categories Report -->
        <div class="step-content" id="step-categories" style="display: none;">
            <div class="report-card">
                <div class="card-header">
                    <h3><i class="bi bi-tags"></i> Categories Report Configuration</h3>
                </div>
                <div class="card-body">
                    <form id="categoriesReportForm" method="GET" action="{{ route('reports.export-categories') }}" target="_blank">
                        <div class="form-grid">
                            <div class="form-group full-width">
                                <label class="form-label"><i class="bi bi-tags"></i> Categories</label>
                                <select name="category_ids[]" class="form-control categories-category-select" multiple>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label"><i class="bi bi-calendar-plus"></i> From Date</label>
                                <input type="date" name="from_date" class="form-control" id="categories_from_date">
                            </div>

                            <div class="form-group">
                                <label class="form-label"><i class="bi bi-calendar-x"></i> To Date</label>
                                <input type="date" name="to_date" class="form-control" id="categories_to_date">
                            </div>

                            <div class="form-group">
                                <label class="form-label"><i class="bi bi-sort-down"></i> Sort By</label>
                                <select name="sort_by" class="form-select">
                                    <option value="category_name">Category Name</option>
                                    <option value="items_count">Number of Items</option>
                                    <option value="created_at">Date Created</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label"><i class="bi bi-arrow-down-up"></i> Sort Order</label>
                                <select name="sort_order" class="form-select">
                                    <option value="asc">Ascending</option>
                                    <option value="desc">Descending</option>
                                </select>
                            </div>
                        </div>


                        <div class="action-buttons">
                            <button type="button" class="btn btn-prev" data-prev="maintenance">← Previous</button>
                            <div>
                                <button type="submit" class="btn btn-export"><i class="bi bi-file-pdf me-2"></i>Export PDF</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
    $(document).ready(function() {
        $('.items-category-select').select2({ placeholder: 'Select categories', allowClear: true, width: '100%' });
        $('.assignments-employee-select').select2({ placeholder: 'Select employees', allowClear: true, width: '100%' });
        $('.categories-category-select').select2({ placeholder: 'Select categories', allowClear: true, width: '100%' });

        $('#report_department').on('change', function() {
            let depId = $(this).val();
            let employeeSelect = $('#report_employee');

            employeeSelect.html('<option value="">All Employees</option>').trigger('change');

            if (!depId) return;

            let url = "/dashboard/employees/inventory-assignments/get-employees/" + depId;

            $.get(url, function(res) {
                let options = '';
                if (res.length === 0) {
                    options = '<option disabled>No employees found</option>';
                } else {
                    res.forEach(function(emp) {
                        options += `<option value="${emp.emp_id}">${emp.fullname}</option>`;
                    });
                }
                employeeSelect.html(options).trigger('change');
            });
        });

        function showStep(stepId) {
            $('.step-content').hide();
            $('#step-' + stepId).fadeIn(200);
            $('.step').removeClass('active');
            $('.step[data-step="' + stepId + '"]').addClass('active');
            const steps = ['items', 'assignments', 'maintenance', 'categories'];
            const currentIndex = steps.indexOf(stepId);
            $('.step').removeClass('completed');
            for(let i = 0; i < currentIndex; i++) {
                $('.step[data-step="' + steps[i] + '"]').addClass('completed');
            }
        }

        $('.btn-next').on('click', function() {
            const next = $(this).data('next');
            if(next) showStep(next);
        });

        $('.btn-prev').on('click', function() {
            const prev = $(this).data('prev');
            if(prev) showStep(prev);
        });

        $('.step').on('click', function() {
            const step = $(this).data('step');
            showStep(step);
        });
    });
    </script>
</x-layout>
