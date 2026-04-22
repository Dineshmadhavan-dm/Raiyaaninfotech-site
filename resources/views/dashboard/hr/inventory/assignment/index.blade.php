<x-layout>
    @section('title', 'Inventory Assignments')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


    <style>
        /* 🔥 Fix Select2 height to match inputs */
.select2-container .select2-selection--single {
    height: 45px !important;
    border: 1px solid #ced4da;
    border-radius: 6px;
    padding: 6px 10px;
    display: flex;
    align-items: center;
}

/* Text alignment */
.select2-container .select2-selection__rendered {
    line-height: normal !important;
    padding-left: 0 !important;
}

/* Fix arrow container height */
.select2-container .select2-selection__arrow {
    height: 45px !important;
    right: 10px;
}

/* 🔥 Increase arrow size */
.select2-container--default .select2-selection--single .select2-selection__arrow b {
    border-width: 6px 5px 0 5px; /* bigger arrow */
}

/* Center arrow properly */
.select2-container--default .select2-selection--single .select2-selection__arrow {
    display: flex;
    align-items: center;
    justify-content: center;
}
    </style>
    <div class="container-fluid p-4">

        <div class="d-flex justify-content-between mb-4">
            <h3>Inventory Assignments</h3>
            <div>
                <button type="button" class="btn btn-success me-2" id="exportAssignmentBtn">
                    <i class="bi bi-file-pdf"></i> Export PDF
                </button>
                <a href="{{ route('inventory.assignments.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Assign Item
                </a>
            </div>
        </div>

        <form method="GET" class="row mb-3">
            <div class="col-md-3">
                <input type="text" name="item" value="{{ request('item') }}" class="form-control" placeholder="Item Name">
            </div>
            <div class="col-md-3">
                <input type="text" name="employee" value="{{ request('employee') }}" class="form-control" placeholder="Employee Name">
            </div>
            <div class="col-md-2">
              <select name="status" class="form-control">
    <option value="">All Status</option>
    <option value="0" {{ request('status')==='0'?'selected':'' }}>Assigned</option>
    <option value="1" {{ request('status')==='1'?'selected':'' }}>Returned</option>
</select>
            </div>
            <div class="col-md-4">
                <button class="btn btn-primary">Filter</button>
                <a href="{{ route('inventory.assignments.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex mb-3">
                    <form method="GET" class="mb-3 d-flex align-items-center gap-2">
                        <label>Show</label>
                        <select name="per_page" onchange="this.form.submit()" class="form-control form-control-sm">
                            <option value="5" {{ request('per_page')==5?'selected':'' }}>5</option>
                            <option value="10" {{ request('per_page')==10?'selected':'' }}>10</option>
                            <option value="20" {{ request('per_page')==20?'selected':'' }}>20</option>
                        </select>
                        <span>entries</span>
                        <input type="hidden" name="item" value="{{ request('item') }}">
                        <input type="hidden" name="employee" value="{{ request('employee') }}">
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    </form>
                </div>

                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Item</th>
                            <th>Department</th>
                            <th>Employee</th>
                            <th>Item Condition</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assignments as $i => $a)
                        <tr>
                            <td>{{ $assignments->firstItem() + $i }}</td>
                            <td>{{ $a->item->item_name ?? '-' }}</td>
                            <td>{{ $a->department->dep_name ?? '-' }}</td>
                            <td>{{ $a->employee->fullname ?? '-' }}</td>
                            @php
$conditionMap = [
    0 => ['Scrap','bg-danger'],
    1 => ['Active','bg-success'],
];

$c = $conditionMap[$a->condition_status] ?? ['Unknown','bg-secondary'];
@endphp

<td>
    <span class="badge p-2 {{ $c[1] }}">
        {{ $c[0] }}
    </span>
</td>
                           <td>
    <span class="badge p-2 {{ $a->status == 0 ? 'bg-warning' : 'bg-success' }}">
        {{ $a->status == 0 ? 'Assigned' : 'Returned' }}
    </span>
</td>
                            <td>{{ $a->assigned_date ? \Carbon\Carbon::parse($a->assigned_date)->format('d-m-Y') : '-' }}</td>
                            <td>
                                <button class="btn btn-sm btn-info view-btn"
                                    data-item="{{ $a->item->item_name }}"
                                    data-code="{{ $a->item->item_code }}"
                                 data-image="{{
    (!empty($a->item->item_image) && file_exists(public_path('inventory_images/'.$a->item->item_image)))
    ? asset('inventory_images/'.$a->item->item_image)
    : asset('images/placeholder.jpg')
}}"
                                    data-dept="{{ $a->department->dep_name }}"
                                    data-emp="{{ $a->employee->fullname }}"
                                    data-status="{{ $a->status }}"
                                    data-date="{{ \Carbon\Carbon::parse($a->assigned_date)->format('d-m-Y') }}"
                                    data-return="{{ $a->return_date ? \Carbon\Carbon::parse($a->return_date)->format('d-m-Y') : '' }}"
                                    data-remarks="{{ $a->remarks }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#billModal">
                                    <i class="bi bi-eye"></i>
                                </button>
                                @if($a->status == 0)
                                <button class="btn btn-sm btn-success return-btn" data-id="{{ $a->id }}">
                                    <i class="bi bi-arrow-return-left"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-between mt-3">
                    <div>Showing {{ $assignments->firstItem() }} to {{ $assignments->lastItem() }} of {{ $assignments->total() }} entries</div>
                    <div>{{ $assignments->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="billModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content border-0 shadow-lg rounded-4 p-4">
                <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                    <div>
                        <h4 class="fw-bold mb-0">Assignment Invoice</h4>
                        <small class="text-muted">Inventory Tracking</small>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="card border-0 shadow-sm rounded-4 p-3 mb-3">
                            <h6 class="fw-bold text-primary mb-3">Item Details</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><b>Item:</b> <span id="b_item"></span></p>
                                    <p><b>Code:</b> <span id="b_code"></span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><b>Department:</b> <span id="b_dept"></span></p>
                                    <p><b>Employee:</b> <span id="b_emp"></span></p>
                                </div>
                            </div>
                        </div>
                        <div class="card border-0 shadow-sm rounded-4 p-3 mb-3">
                            <h6 class="fw-bold text-primary mb-3">Assignment Info</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><b>Assigned Date:</b> <span id="b_date"></span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><b>Return Date:</b> <span id="b_return"></span></p>
                                </div>
                            </div>
                        </div>
                        <div class="card border-0 shadow-sm rounded-4 p-3">
                            <h6 class="fw-bold text-primary">Remarks</h6>
                            <p id="b_remarks" class="text-muted"></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm rounded-4 p-3 mb-3 text-center">
                            <h6 class="fw-bold text-primary mb-2">Item Image</h6>
                            <img id="b_image" class="img-fluid rounded-3" style="max-height:200px; object-fit:cover;">
                        </div>

                    </div>
                </div>
               <!-- FOOTER -->
<div class="text-end mt-4">
    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
</div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exportAssignmentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Export Assignment Report (PDF)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Export Type</label>
                        <div class="d-flex gap-4 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="export_type" id="exportAllRadio" value="all" checked>
                                <label class="form-check-label" for="exportAllRadio">All Assignments</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="export_type" id="exportStatusRadio" value="status">
                                <label class="form-check-label" for="exportStatusRadio">By Status</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="export_type" id="exportEmployeeRadio" value="employee">
                                <label class="form-check-label" for="exportEmployeeRadio">By Employee</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 d-none" id="exportStatusDiv">
                        <label class="form-label fw-bold">Status</label>
                        <select id="exportStatusSelect" class="form-select">
                            <option value="">Select Status</option>
                          <option value="0">Assigned</option>
<option value="1">Returned</option>
                        </select>
                    </div>

                    <div class="mb-3 d-none" id="exportEmployeeDiv">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Department <span class="text-danger">*</span></label>
                            <select id="exportEmployeeDepartmentSelect" class="form-select">
                                <option value="">Select Department</option>
                                @foreach($departments ?? [] as $dept)
                                    <option value="{{ $dept->dep_id }}">{{ $dept->dep_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Select Employee(s) <span class="text-danger">*</span></label>

                            <div>
                                 <select id="exportEmployeeSelect" class="form-select " multiple="multiple">
                            </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Sort By</label>
                        <select id="exportSortBy" class="form-select">
                            <option value="assigned_date">Assigned Date</option>
                            <option value="item_name">Item Name</option>
                            <option value="employee_name">Employee Name</option>
                            <option value="department_name">Department Name</option>
                            <option value="status">Status</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Sort Order</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sort_order" id="sortAsc" value="asc" checked>
                                <label class="form-check-label" for="sortAsc">Ascending</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sort_order" id="sortDesc" value="desc">
                                <label class="form-check-label" for="sortDesc">Descending</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="exportFinalConfirmBtn">Generate PDF</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).on('click','.view-btn',function(){
        $('#b_item').text($(this).data('item'));
        $('#b_code').text($(this).data('code'));
        $('#b_dept').text($(this).data('dept'));
        $('#b_emp').text($(this).data('emp'));


        $('#b_date').text($(this).data('date'));
        $('#b_return').text($(this).data('return') || 'Not Returned');
        $('#b_remarks').text($(this).data('remarks') || '-');
        $('#b_image').attr('src', $(this).data('image'));
    });

$(document).on('click','.return-btn',function(){
    let id = $(this).data('id');
    let today = new Date().toISOString().split('T')[0];

    // Create custom modal HTML
    let modalHtml = `
        <div class="modal fade" id="returnModal" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
                    <div class="modal-header" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; border: none;">
                        <h5 class="modal-title">
                            <i class="bi bi-arrow-return-left me-2"></i> Return Item
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" style="padding: 25px;">
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Return Date <span class="text-danger">*</span>
                            </label>
                            <input type="date" id="custom_return_date" class="form-control" value="${today}" style="border-radius: 8px; padding: 10px;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Remarks (Optional)</label>
                            <textarea id="custom_return_remarks" class="form-control" rows="3" placeholder="Enter return remarks..." style="border-radius: 8px;"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 1px solid #e5e7eb; padding: 15px 25px;">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 8px;">
                            <i class="bi bi-x-circle"></i> Cancel
                        </button>
                        <button type="button" id="confirmReturnBtn" class="btn btn-success" style="border-radius: 8px;">
                            <i class="bi bi-check-circle"></i> Confirm Return
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Remove existing modal if any
    if ($('#returnModal').length) {
        $('#returnModal').remove();
    }

    // Append modal to body
    $('body').append(modalHtml);

    // Show modal
    let returnModal = new bootstrap.Modal(document.getElementById('returnModal'));
    returnModal.show();

    // Handle confirm button click
    $('#confirmReturnBtn').on('click', function() {
        let returnDate = $('#custom_return_date').val();
        let returnRemarks = $('#custom_return_remarks').val();

        if (!returnDate) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please select a return date',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            return;
        }

        // Close modal
        returnModal.hide();

        // Show loading
        Swal.fire({
            title: 'Processing...',
            text: 'Please wait while we process the return',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        let url = "{{ url('dashboard/employees/inventory-assignments') }}/" + id + "/return";

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                return_date: returnDate,
                return_remarks: returnRemarks
            },
            success: function(resp) {
                if(resp.status){
                    Swal.fire({
                        title: 'Returned Successfully!',
                        text: resp.message || 'Item has been returned',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error!', resp.message || 'Something went wrong', 'error');
                }
            },
            error: function(xhr) {
                let errorMessage = 'An error occurred while processing the return';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                Swal.fire('Error!', errorMessage, 'error');
            }
        });
    });
});




   </script>

<script>
(function() {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initExportModal);
    } else {
        initExportModal();
    }

    function initExportModal() {
        const exportAllRadio = document.getElementById('exportAllRadio');
        const exportStatusRadio = document.getElementById('exportStatusRadio');
        const exportEmployeeRadio = document.getElementById('exportEmployeeRadio');
        const exportStatusDiv = document.getElementById('exportStatusDiv');
        const exportEmployeeDiv = document.getElementById('exportEmployeeDiv');
        const exportBtn = document.getElementById('exportAssignmentBtn');
        const confirmBtn = document.getElementById('exportFinalConfirmBtn');
        const exportStatusSelect = document.getElementById('exportStatusSelect');
        const exportEmployeeDepartmentSelect = document.getElementById('exportEmployeeDepartmentSelect');
        const exportEmployeeSelect = document.getElementById('exportEmployeeSelect');

        let employeeSelect2 = null;

        if (exportEmployeeSelect) {
            employeeSelect2 = $(exportEmployeeSelect).select2({
                dropdownParent: $('#exportAssignmentModal'),
                placeholder: 'Select Employees',
                allowClear: true,
                closeOnSelect: false
            });
        }

        if (exportAllRadio && exportStatusRadio && exportEmployeeRadio) {
            exportAllRadio.addEventListener('change', function() {
                if (this.checked) {
                    exportStatusDiv.classList.add('d-none');
                    exportEmployeeDiv.classList.add('d-none');
                }
            });

            exportStatusRadio.addEventListener('change', function() {
                if (this.checked) {
                    exportStatusDiv.classList.remove('d-none');
                    exportEmployeeDiv.classList.add('d-none');
                }
            });

            exportEmployeeRadio.addEventListener('change', function() {
                if (this.checked) {
                    exportStatusDiv.classList.add('d-none');
                    exportEmployeeDiv.classList.remove('d-none');
                }
            });
        }

        if (exportEmployeeDepartmentSelect) {
            $(exportEmployeeDepartmentSelect).on('change', function() {
                const depId = $(this).val();
                if (depId) {
                    $.ajax({
                        url: '/dashboard/employees/inventory-assignments/get-employees/' + depId,
                        type: 'GET',
                        success: function(response) {
                            let options = '';
                            $.each(response, function(key, emp) {
                                options += '<option value="' + emp.emp_id + '">' + emp.fullname + '</option>';
                            });
                            exportEmployeeSelect.innerHTML = options;
                            if (employeeSelect2) {
                                employeeSelect2.val(null).trigger('change');
                            }
                        },
                        error: function(xhr) {
                            console.log('Error loading employees:', xhr);
                        }
                    });
                } else {
                    exportEmployeeSelect.innerHTML = '';
                    if (employeeSelect2) {
                        employeeSelect2.val(null).trigger('change');
                    }
                }
            });
        }

        if (exportBtn) {
            exportBtn.addEventListener('click', function() {
                const exportModal = new bootstrap.Modal(document.getElementById('exportAssignmentModal'));
                exportModal.show();
            });
        }

        const exportModalElement = document.getElementById('exportAssignmentModal');
        if (exportModalElement) {
            exportModalElement.addEventListener('show.bs.modal', function() {
                if (exportAllRadio) exportAllRadio.checked = true;
                if (exportStatusDiv) exportStatusDiv.classList.add('d-none');
                if (exportEmployeeDiv) exportEmployeeDiv.classList.add('d-none');
                if (exportStatusSelect) exportStatusSelect.value = '';
                if (exportEmployeeDepartmentSelect) {
                    $(exportEmployeeDepartmentSelect).val(null).trigger('change');
                }
                if (exportEmployeeSelect) {
                    exportEmployeeSelect.innerHTML = '';
                    if (employeeSelect2) {
                        employeeSelect2.val(null).trigger('change');
                    }
                }
                const sortAsc = document.getElementById('sortAsc');
                if (sortAsc) sortAsc.checked = true;
                const sortBy = document.getElementById('exportSortBy');
                if (sortBy) sortBy.value = 'assigned_date';
            });
        }

        if (confirmBtn) {
            const newConfirmBtn = confirmBtn.cloneNode(true);
            confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);

            newConfirmBtn.addEventListener('click', function() {
                const exportType = document.querySelector('input[name="export_type"]:checked').value;
                let params = new URLSearchParams();
                params.append('export_type', exportType);

                if (exportType === 'status' && exportStatusSelect) {
                    const status = exportStatusSelect.value;
                    if (!status) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            text: 'Please select a status',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        return;
                    }
                    params.append('status', status);
                }

                if (exportType === 'employee' && exportEmployeeSelect) {
                    const employeeIds = $(exportEmployeeSelect).val();
                    if (!employeeIds || employeeIds.length === 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            text: 'Please select at least one employee',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        return;
                    }
                    employeeIds.forEach(id => {
                        params.append('employee_ids[]', id);
                    });
                }

                const sortBy = document.getElementById('exportSortBy');
                if (sortBy) {
                    params.append('sort_by', sortBy.value);
                }

                const sortOrder = document.querySelector('input[name="sort_order"]:checked');
                if (sortOrder) {
                    params.append('sort_order', sortOrder.value);
                }

                const exportModal = bootstrap.Modal.getInstance(document.getElementById('exportAssignmentModal'));
                if (exportModal) exportModal.hide();

                // FIXED URL - using full path instead of route name
                window.location.href = "/dashboard/employees/inventory-assignments/export-pdf?" + params.toString();
            });
        }
    }
})();
</script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</x-layout>
