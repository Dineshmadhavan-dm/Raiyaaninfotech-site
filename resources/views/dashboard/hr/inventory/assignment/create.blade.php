<x-layout>
@section('title','Assign Inventory')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>



<style>

.select2-selection.is-invalid {
    border: 1px solid #dc3545 !important;
}

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

 <div class="d-flex justify-content-between align-items-center mb-4">
            <div>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 mt-2">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dhome') }}" class="text-decoration-none text-dark">
                               Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('inventory.assignments.index') }}" class="text-decoration-none text-dark">
                                Inventory Assignment
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-primary">Create</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('inventory.assignments.index') }}" class="btn btn-outline-primary d-flex align-items-center">
                <i class="bi bi-arrow-left-circle me-2"></i>Back
            </a>
        </div>


<form id="assignForm">
@csrf

<div class="row">

<!-- 🔥 LEFT FORM -->
<div class="col-lg-9">

<div class="card shadow-sm border-0 p-4">

<div class="col-12 mt-3 ">
    <div class="bg-light rounded-3 p-3 mb-2">
        <h6 class="fw-bold text-primary mb-0">Assignment Create</h6>
    </div>
</div>
<div class="row g-3 mt-3">



<!-- DEPARTMENT -->
<div class="col-md-6">
<label>Department <span class=" text-danger">*</span></label>
<select name="department_id" id="department" class="form-select select2">
<option value="">Select Department</option>
@foreach($departments as $dep)
<option value="{{ $dep->dep_id }}">{{ $dep->dep_name }}</option>
@endforeach
</select>
</div>

<!-- EMPLOYEE -->
<div class="col-md-6">
<label>Employee <span class=" text-danger">*</span></label>
<select name="employee_id" id="employee" class="form-select select2">
<option value="">Select Employee</option>
</select>
</div>


<!-- ITEM -->
<div class="col-md-6">
<label>Item  <span class=" text-danger">*</span></label>
<select name="item_id" class="form-select select2">
<option value="">Select Item</option>
@foreach($items as $item)
<option value="{{ $item->id }}">{{ $item->item_name }}</option>
@endforeach
</select>
</div>

<!-- DATE -->
<div class="col-md-6">
<label>Assigned Date <span class=" text-danger">*</span></label>
<input type="date" name="assigned_date" class="form-control">
</div>

<!-- REMARK -->
<div class="col-12">
<label>Remarks</label>
<textarea name="remarks" class="form-control"></textarea>
</div>

</div>

</div>
</div>

<!-- 🔥 RIGHT ACTION CARD -->
<div class="col-lg-3">

<div class="card shadow-sm border-0 p-4 position-sticky" style="top:100px;">

<h6 class="fw-bold text-primary mb-3">Actions</h6>

<div class="d-flex gap-2">
<button type="submit" class="btn btn-primary w-100">Assign</button>

<a href="{{ route('inventory.assignments.index') }}" class="btn btn-outline-secondary w-100">
Cancel
</a>
</div>

</div>

</div>

</div>
</form>
</div>




<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function(){

    // ✅ Select2 init
    $('.select2').select2({
        placeholder: "Select option",
        allowClear: true,
        width: '100%'
    });

    // 🔥 ===============================
    // ✅ DEPARTMENT → EMPLOYEE
    // 🔥 ===============================
    $('#department').on('change', function(){

        let depId = $(this).val();

        $('#employee').val(null).trigger('change');

        if(!depId){
            $('#employee').html('<option value="">Select Employee</option>').trigger('change');
            return;
        }

        $('#employee').html('<option value="">Loading...</option>');

        let url = "/dashboard/employees/inventory-assignments/get-employees/" + depId;

        $.get(url, function(res){

            let options = '<option value="">Select Employee</option>';

            if(res.length === 0){
                options += '<option>No employees found</option>';
            }

            res.forEach(emp=>{
                options += `<option value="${emp.emp_id}">${emp.fullname}</option>`;
            });

            $('#employee').html(options).trigger('change.select2');
        });

    });

    // 🔥 ===============================
    // ✅ ITEM ALREADY ASSIGNED CHECK
    // 🔥 ===============================
    $('select[name="item_id"]').on('change', function(){

        let itemId = $(this).val();
        let input = $(this);

        clearError(input);

        if(!itemId) return;

        let url = "/dashboard/employees/inventory-assignments/check-item/" + itemId;

        $.get(url, function(res){

            if(res.assigned){
                showError(input,'This item is already assigned ❌');
            }

        });

    });

});
</script>

<script>

function showError(input,msg){

    input.addClass('is-invalid');

    if(input.next('.select2-container').length){
        input.next('.select2-container').find('.select2-selection')
            .addClass('is-invalid');
    }

    if(input.closest('div').find('.error-msg').length === 0){
        input.closest('div').append('<div class="text-danger small error-msg">'+msg+'</div>');
    }
}

function clearError(input){
    input.removeClass('is-invalid');
    input.closest('div').find('.error-msg').remove();

    if(input.next('.select2-container').length){
        input.next('.select2-container').find('.select2-selection')
            .removeClass('is-invalid');
    }
}

function validate(input){

    let val = input.val();
    let name = input.attr('name');

    clearError(input);

    // ✅ REQUIRED FIELDS
    if(name=='item_id' && !val){
        showError(input,'Item required'); return false;
    }

    if(name=='department_id' && !val){
        showError(input,'Department required'); return false;
    }

    if(name=='employee_id' && !val){
        showError(input,'Employee required'); return false;
    }

    if(name=='assigned_date' && !val){
        showError(input,'Date required'); return false;
    }

    // 🔥 REMARKS VALIDATION
    if(name=='remarks'){
        if(val && (val.length < 5 || val.length > 300)){
            showError(input,'Remarks must be 5 to 300 characters');
            return false;
        }
    }

    return true;
}

// 🔥 LIVE VALIDATION
$('select,input,textarea').on('change keyup',function(){
    validate($(this));
});

// 🔥 SUBMIT
$('#assignForm').submit(function(e){
    e.preventDefault();

    let valid = true;

    $('select,input,textarea').each(function(){
        if(!validate($(this))) valid=false;
    });

    let itemInvalid = $('select[name="item_id"]').hasClass('is-invalid');

    if(!valid || itemInvalid) return;

    $.post('{{ route("inventory.assignments.store") }}',
        $(this).serialize()
    )
    .done(function(res){

        if(res.status){
            Swal.fire('Success', res.message, 'success').then(()=>{
                window.location.href='{{ route("inventory.assignments.index") }}';
            });
        } else {
            Swal.fire('Error', res.message, 'error');
        }

    })
    .fail(function(xhr){

        // 🔥 Validation error (422)
        if(xhr.status === 422){
            let errors = xhr.responseJSON.errors;
            let msg = Object.values(errors).map(e => e[0]).join('\n');
            Swal.fire('Validation Error', msg, 'warning');
        } else {
            console.log(xhr.responseText);
            Swal.fire('Error','Server error occurred','error');
        }

    });
});
</script>
</x-layout>
