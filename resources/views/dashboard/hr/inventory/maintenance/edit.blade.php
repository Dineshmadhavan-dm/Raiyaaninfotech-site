<x-layout>
@section('title','Edit Maintenance')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

<style>
.select2-selection.is-invalid {
    border: 1px solid #dc3545 !important;
}

.select2-container .select2-selection--single {
    height: 45px !important;
    border: 1px solid #ced4da;
    border-radius: 6px;
    padding: 6px 10px;
    display: flex;
    align-items: center;
}

.select2-container .select2-selection__rendered {
    line-height: normal !important;
    padding-left: 0 !important;
}

.select2-container .select2-selection__arrow {
    height: 45px !important;
    right: 10px;
}

.employee-details-card {
    background: #f8f9fa;
    border-left: 4px solid #0d6efd;
}
</style>

<div class="container-fluid p-4">

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 mt-2">
                <li class="breadcrumb-item">
                    <a href="{{ route('dhome') }}" class="text-decoration-none text-dark">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('inventory.maintenance.index') }}" class="text-decoration-none text-dark">Inventory Maintenance</a>
                </li>
                <li class="breadcrumb-item active text-primary">Edit</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('inventory.maintenance.index') }}" class="btn btn-outline-primary d-flex align-items-center">
        <i class="bi bi-arrow-left-circle me-2"></i>Back
    </a>
</div>

<form id="maintenanceForm">
@csrf
@method('PUT')

<div class="row">

<div class="col-lg-9">
<div class="card shadow-sm border-0 p-4">

<div class="bg-light rounded-3 p-3 mb-3">
    <h6 class="fw-bold text-primary mb-0">Edit Maintenance Details</h6>
</div>

<div class="row g-3">

<!-- ITEM (Read-only for edit) -->
<div class="col-md-6">
    <label>Item <span class="text-danger">*</span></label>
    <input type="text" class="form-control" value="{{ $maintenance->item->item_name }} ({{ $maintenance->item->item_code }})" readonly disabled>
    <input type="hidden" name="item_id" value="{{ $maintenance->item_id }}">
</div>

<!-- Assigned Employee Details -->
<div class="col-12">
    <div class="card employee-details-card">
        <div class="card-body py-3">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="bi bi-person-badge fs-2 text-primary"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="mb-1 text-primary">Item Assignment Details:</h6>
                    @php
                        $assignment = \App\Models\InventoryAssignment::where('item_id', $maintenance->item_id)
                            ->where('status', 'assigned')
                            ->with(['employee', 'department'])
                            ->first();
                    @endphp
                    @if($assignment)
                        <p class="mb-1"><strong>Assigned To:</strong> {{ $assignment->employee->fullname ?? 'N/A' }}</p>
                        <p class="mb-1"><strong>Department:</strong> {{ $assignment->department->dep_name ?? 'N/A' }}</p>
                        <p class="mb-0"><strong>Assigned Date:</strong> {{ $assignment->assigned_date ?? 'N/A' }}</p>
                        <input type="hidden" name="employee_id" value="{{ $assignment->employee_id }}">
                    @else
                        <p class="mb-0 text-warning">⚠️ This item is not currently assigned to any employee</p>
                        <input type="hidden" name="employee_id" value="">
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- TYPE -->
<div class="col-md-6">
    <label>Type <span class="text-danger">*</span></label>
    <select name="maintenance_type" class="form-select select2">
        <option value="">Select Type</option>
       <option value="0" {{ $maintenance->maintenance_type == 0 ? 'selected' : '' }}>Scrap</option>
<option value="1" {{ $maintenance->maintenance_type == 1 ? 'selected' : '' }}>Service</option>
<option value="2" {{ $maintenance->maintenance_type == 2 ? 'selected' : '' }}>Upgrade</option>
    </select>
</div>

<!-- STATUS -->
<div class="col-md-6">
    <label>Status <span class="text-danger">*</span></label>
    <select name="status" class="form-select select2">
      <option value="0" {{ $maintenance->status == 0 ? 'selected' : '' }}>Pending</option>
<option value="1" {{ $maintenance->status == 1 ? 'selected' : '' }}>Complete</option>
    </select>
</div>

<!-- ISSUE -->
<div class="col-md-6">
    <label>Issue <span class="text-danger">*</span></label>
    <textarea name="issue_description" class="form-control" placeholder="Enter issue description" rows="3">{{ $maintenance->issue_description }}</textarea>
</div>

<!-- COST -->
<div class="col-md-6">
    <label>Cost <span class="text-danger">*</span></label>
    <input type="number" step="0.01" name="cost" class="form-control" value="{{ $maintenance->cost }}" placeholder="Enter cost">
</div>

<!-- VENDOR -->
<div class="col-md-6">
    <label>Vendor Name <span class="text-danger">*</span></label>
    <input type="text" name="vendor_name" class="form-control" value="{{ $maintenance->vendor_name }}" placeholder="Enter vendor name">
</div>

<!-- START DATE -->
<div class="col-md-6">
    <label>Start Date <span class="text-danger">*</span></label>
    <input type="date" name="start_date" class="form-control" value="{{ $maintenance->start_date }}">
</div>

<!-- END DATE (only show if completed) -->
<div class="col-md-6" id="endDateContainer" style="{{ $maintenance->status == 'completed' ? 'display:block' : 'display:none' }}">
    <label>End Date</label>
    <input type="date" name="end_date" class="form-control" value="{{ $maintenance->end_date }}">
</div>

<!-- REMARK -->
<div class="col-12">
    <label>Remarks</label>
    <textarea name="remarks" class="form-control" placeholder="Optional remarks" rows="2">{{ $maintenance->remarks }}</textarea>
</div>



<!-- DOCUMENT -->
<div class="col-md-6">
    <label>Attachment (PDF/DOC)</label>

    <!-- Existing File -->
    @if($maintenance->document)
    <div class="mb-2">
        <a href="{{ asset('maintenance_docs/'.$maintenance->document) }}"
           target="_blank" class="btn btn-sm btn-success">
           <i class="bi bi-file-earmark-text"></i> View Current File
        </a>
    </div>
    @endif

    <div class="upload-box border rounded-3 p-3 text-center position-relative">

        <span id="removeDoc" class="position-absolute top-0 end-0 m-2 text-danger fw-bold d-none" style="cursor:pointer;">×</span>

        <div id="doc_placeholder">
            <i class="bi bi-file-earmark-text fs-2 text-secondary"></i>
            <p class="mb-0 small text-muted">No file selected</p>
        </div>

        <div id="docName" class="small text-success mt-2"></div>
    </div>

    <button type="button" class="btn btn-sm btn-primary mt-2"
            onclick="$('#docInput').click()">
        Choose File
    </button>

    <input type="file" id="docInput" name="document_file"
           accept=".pdf,.doc,.docx" hidden>
</div>
</div>

</div>
</div>

<!-- RIGHT ACTION CARD -->
<div class="col-lg-3">
    <div class="card shadow-sm border-0 p-4 position-sticky" style="top:100px;">
        <h6 class="fw-bold text-primary mb-3">Actions</h6>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">Update</button>
            <a href="{{ route('inventory.maintenance.index') }}" class="btn btn-outline-secondary w-100">Cancel</a>
        </div>
    </div>
</div>

</div>

</form>
</div>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function(){
    $('.select2').select2({
        placeholder: "Select option",
        allowClear: true,
        width: '100%'
    });

    // Show/hide end date based on status
    $('select[name="status"]').on('change', function(){
        if($(this).val() == 1){
            $('#endDateContainer').slideDown();
        } else {
            $('#endDateContainer').slideUp();
        }
    });
});

// Validation Functions
function showError(input,msg){
    input.addClass('is-invalid');
    if(input.next('.select2-container').length){
        input.next('.select2-container').find('.select2-selection').addClass('is-invalid');
    }
    if(input.closest('div').find('.error-msg').length === 0){
        input.closest('div').append('<div class="text-danger small error-msg">'+msg+'</div>');
    }
}

function clearError(input){
    input.removeClass('is-invalid');
    input.closest('div').find('.error-msg').remove();
    if(input.next('.select2-container').length){
        input.next('.select2-container').find('.select2-selection').removeClass('is-invalid');
    }
}

function validate(input){
    let val = input.val()?.trim();
    let name = input.attr('name');
    clearError(input);

    if(name == 'maintenance_type' && !val){
        showError(input,'Type required');
        return false;
    }

    if(name == 'status' && !val){
        showError(input,'Status required');
        return false;
    }

    if(name == 'issue_description'){
        if(!val){
            showError(input,'Issue required');
            return false;
        }
        if(val.length < 5 || val.length > 500){
            showError(input,'Issue must be 5–500 characters');
            return false;
        }
    }

    if(name == 'cost' && !val){
        showError(input,'Cost required');
        return false;
    }

    if(name == 'vendor_name'){
        if(!val){
            showError(input,'Vendor required');
            return false;
        }
        if(val.length < 2){
            showError(input,'Vendor name must be at least 2 characters');
            return false;
        }
    }

    if(name == 'start_date' && !val){
        showError(input,'Start date required');
        return false;
    }

    return true;
}

$('input, textarea, select').on('keyup change', function(){
    validate($(this));
});
$('#maintenanceForm').submit(function(e){
    e.preventDefault();

    let valid = true;
    $('input, textarea, select').each(function(){
        if($(this).attr('name') && !$(this).prop('disabled') && !validate($(this))) {
            valid = false;
        }
    });

    if(!valid) return;

    // ✅ IMPORTANT: Use FormData
    let formData = new FormData(this);

    $.ajax({
        url: '{{ route("inventory.maintenance.update", $maintenance->id) }}',
        type: 'POST',
        data: formData,
        contentType: false,   // ✅ required
        processData: false,   // ✅ required
        success: function(res){
            if(res.status){
                Swal.fire('Success','Maintenance updated successfully','success').then(()=>{
                    window.location.href = '{{ route("inventory.maintenance.index") }}';
                });
            }
        },
        error: function(xhr){
            let errors = xhr.responseJSON?.errors;
            if(errors){
                let errorMsg = Object.values(errors).flat().join('\n');
                Swal.fire('Error', errorMsg, 'error');
            } else {
                Swal.fire('Error', 'Something went wrong', 'error');
            }
        }
    });
});


$('#docInput').change(function(){

    let file = this.files[0];
    if(!file) return;

    let allowed = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ];

    if(!allowed.includes(file.type)){
        Swal.fire('Error','Only PDF, DOC, DOCX allowed','error');
        $(this).val('');
        return;
    }

    if(file.size > 2 * 1024 * 1024){
        Swal.fire('Error','File must be less than 2MB','error');
        $(this).val('');
        return;
    }

    $('#docName').html(`<i class="bi bi-file-earmark text-success"></i> ${file.name}`);
    $('#doc_placeholder').hide();
    $('#removeDoc').removeClass('d-none');
});

$('#removeDoc').click(function(){
    $('#docInput').val('');
    $('#docName').html('');
    $('#doc_placeholder').show();
    $(this).addClass('d-none');
});
</script>

</x-layout>
