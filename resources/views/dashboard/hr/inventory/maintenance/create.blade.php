<x-layout>
@section('title','Add Maintenance')

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

.select2-container--default .select2-selection--single .select2-selection__arrow b {
    border-width: 6px 5px 0 5px;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    display: flex;
    align-items: center;
    justify-content: center;
}

.employee-details-card {
    background: #f8f9fa;
    border-left: 4px solid #0d6efd;
    transition: all 0.3s ease;
}

.employee-details-card:hover {
    background: #e9ecef;
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
                <li class="breadcrumb-item active text-primary">Create</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('inventory.maintenance.index') }}" class="btn btn-outline-primary d-flex align-items-center">
        <i class="bi bi-arrow-left-circle me-2"></i>Back
    </a>
</div>

<form id="maintenanceForm">
@csrf

<div class="row">

<!-- LEFT FORM -->
<div class="col-lg-9">

<div class="card shadow-sm border-0 p-4">

<div class="bg-light rounded-3 p-3 mb-3">
    <h6 class="fw-bold text-primary mb-0">Maintenance Details</h6>
</div>

<div class="row g-3">

<!-- ITEM -->
<div class="col-md-6">
    <label>Item <span class="text-danger">*</span></label>
    <select name="item_id" id="item_id" class="form-select select2">
        <option value="">Select Item</option>
        @foreach($items as $item)
        <option value="{{ $item->id }}">{{ $item->item_name }} ({{ $item->item_code }})</option>
        @endforeach
    </select>
</div>
<div class="col-12 mt-2" id="notAssignedBox" style="display:none;">
    <div class="card border-0 shadow-sm text-center p-4" style="background:#f9fafb;">
        <div class="mb-3">
            <i class="bi bi-exclamation-circle text-warning" style="font-size:40px;"></i>
        </div>
        <h5 class="fw-bold text-dark">Item Not Assigned</h5>
        <p class="text-muted mb-0">
            This item is not currently assigned to any employee
        </p>
    </div>
</div>

<!-- ASSIGNED EMPLOYEE DETAILS (Dynamic) -->
<div class="col-12" id="employeeDetailsContainer" style="display:none;">
    <div class="card employee-details-card mt-2 mb-2">
        <div class="card-body py-3">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="bi bi-person-badge fs-2 text-primary"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="mb-1 text-primary">Currently Assigned To:</h6>
                    <div id="employeeDetails">
                        <p class="mb-1"><strong>Name:</strong> <span id="emp_name">-</span></p>
                        <p class="mb-1"><strong>Department:</strong> <span id="emp_dept">-</span></p>
                        <p class="mb-0"><strong>Assigned Date:</strong> <span id="assigned_date">-</span></p>
                    </div>
                </div>
                <div>
                    <i class="bi bi-info-circle-fill text-info"></i>
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
    <option value="0">Scrap</option>
    <option value="1">Service</option>
    <option value="2">Upgrade</option>
</select>
</div>

<!-- ISSUE -->
<div class="col-md-6">
    <label>Issue <span class="text-danger">*</span></label>
    <textarea name="issue_description" class="form-control" placeholder="Enter issue description" rows="3"></textarea>
</div>

<!-- COST -->
<div class="col-md-6">
    <label>Cost <span class="text-danger">*</span></label>
    <input type="number" step="0.01" name="cost" class="form-control" placeholder="Enter cost">
</div>

<!-- VENDOR -->
<div class="col-md-6">
    <label>Vendor Name <span class="text-danger">*</span></label>
    <input type="text" name="vendor_name" class="form-control" placeholder="Enter vendor name">
</div>

<!-- DATE -->
<div class="col-md-6">
    <label>Start Date <span class="text-danger">*</span></label>
    <input type="date" name="start_date" class="form-control">
</div>

<!-- REMARK -->
<div class="col-12">
    <label>Remarks</label>
    <textarea name="remarks" class="form-control" placeholder="Optional remarks" rows="2"></textarea>
</div>

<!-- DOCUMENT -->
<div class="col-md-6">
    <label>Attachment (PDF/DOC)</label>

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

<!-- Hidden field for employee_id -->
<input type="hidden" name="employee_id" id="employee_id" value="">

</div>

</div>
</div>

<!-- RIGHT ACTION CARD -->
<div class="col-lg-3">
    <div class="card shadow-sm border-0 p-4 position-sticky" style="top:100px;">
        <h6 class="fw-bold text-primary mb-3">Actions</h6>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">Save</button>
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

 let hideTimer; // global

$('#item_id').on('change', function(){

    let itemId = $(this).val();

    if(!itemId){
        $('#employeeDetailsContainer').hide();
        $('#notAssignedBox').hide();
        $('#employee_id').val('');
        return;
    }

    $.get('/dashboard/employees/inventory-maintenance/check-assignment/' + itemId, function(res){

        if(res.assigned && res.assignment){

            clearTimeout(hideTimer); // stop timer

            $('#emp_name').text(res.assignment.employee?.fullname || 'N/A');
            $('#emp_dept').text(res.assignment.department?.dep_name || 'N/A');
            $('#assigned_date').text(res.assignment.assigned_date || 'N/A');

            $('#employee_id').val(res.assignment.employee_id);

            $('#employeeDetailsContainer').fadeIn();
            $('#notAssignedBox').hide();

        } else {

            $('#employeeDetailsContainer').hide();
            $('#employee_id').val('');

            let box = $('#notAssignedBox');

            box.stop(true,true).fadeIn();

            clearTimeout(hideTimer);

            hideTimer = setTimeout(function(){
                box.fadeOut();
            }, 3000);
        }

    });

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

    if(name == 'item_id' && !val){
        showError(input,'Item required');
        return false;
    }

    if(name == 'maintenance_type' && !val){
        showError(input,'Type required');
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
    if(name == 'document_file'){
    let file = $('#docInput')[0].files[0];
    if(file){
        let allowed = ['application/pdf','application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];

        if(!allowed.includes(file.type)){
            showError(input,'Invalid file type');
            return false;
        }
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
        showError(input,'Date required');
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
        if(!validate($(this))) valid = false;
    });

    if(!valid) return;

    let formData = new FormData(this);

    $.ajax({
        url: '{{ route("inventory.maintenance.store") }}',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(res){
            if(res.status){
                Swal.fire('Success','Maintenance added successfully','success').then(()=>{
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
