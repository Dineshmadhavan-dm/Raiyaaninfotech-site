<x-layout>
@section('title','Add Maintenance')

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
                            <a href="{{ route('inventory.maintenance.index') }}" class="text-decoration-none text-dark">
                                Inventory Maintenance</a>
                        </li>
                        <li class="breadcrumb-item active text-primary" >Create</li>
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

<!-- 🔥 LEFT FORM -->
<div class="col-lg-9">

<div class="card shadow-sm border-0 p-4">

<div class="bg-light rounded-3 p-3 mb-3">
    <h6 class="fw-bold text-primary mb-0">Maintenance Details</h6>
</div>

<div class="row g-3">

<!-- ITEM -->
<div class="col-md-6">
<label>Item <span class="text-danger">*</span></label>
<select name="item_id" class="form-select select2">
<option value="">Select Item</option>
@foreach($items as $item)
<option value="{{ $item->id }}">{{ $item->item_name }}</option>
@endforeach
</select>
</div>

<!-- TYPE -->
<div class="col-md-6">
<label>Type <span class="text-danger">*</span></label>
<select name="maintenance_type" class="form-select select2">
<option value="">Select Type</option>
<option value="repair">Repair</option>
<option value="service">Service</option>
<option value="upgrade">Upgrade</option>
</select>
</div>

<!-- ISSUE -->
<div class="col-md-6">
<label>Issue <span class="text-danger">*</span></label>
<textarea name="issue_description" class="form-control"
placeholder="Enter issue "></textarea>
</div>

<!-- COST -->
<div class="col-md-6">
<label>Cost <span class="text-danger">*</span></label>
<input type="number" name="cost" class="form-control"
placeholder="Enter cost">
</div>

<!-- VENDOR -->
<div class="col-md-6">
<label>Vendor Name<span class="text-danger">*</span></label>
<input type="text" name="vendor_name" class="form-control"
placeholder="Enter vendor name">
</div>

<!-- DATE -->
<div class="col-md-6">
<label>Date <span class="text-danger">*</span></label>
<input type="date" name="start_date" class="form-control">
</div>

<!-- REMARK -->
<div class="col-12">
<label>Remarks</label>
<textarea name="remarks" class="form-control"
placeholder="Optional"></textarea>
</div>

</div>

</div>
</div>

<!-- 🔥 RIGHT ACTION CARD -->
<div class="col-lg-3">

<div class="card shadow-sm border-0 p-4 position-sticky" style="top:100px;">

<h6 class="fw-bold text-primary mb-3">Actions</h6>

<div class="d-flex gap-2">
<button type="submit" class="btn btn-success w-100">Save</button>

<a href="{{ route('inventory.maintenance.index') }}" class="btn btn-outline-secondary w-100">
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

    $('.select2').select2({
        placeholder: "Select option",
        allowClear: true,
        width: '100%'
    });

});
</script>

<script>

// 🔥 ERROR UI
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

// 🔥 VALIDATION
function validate(input){

    let val = input.val()?.trim();
    let name = input.attr('name');

    clearError(input);

    let alphaRegex = /^[A-Za-z ]+$/;

    if(name=='item_id' && !val){
        showError(input,'Item required'); return false;
    }

    if(name=='maintenance_type' && !val){
        showError(input,'Type required'); return false;
    }

    if(name=='issue_description'){
        if(!val){
            showError(input,'Issue required'); return false;
        }
        if(val.length < 5 || val.length > 300){
            showError(input,'Issue must be 5–300 characters'); return false;
        }
    }

    if(name=='cost' && !val){
        showError(input,'Cost required'); return false;
    }

    if(name=='vendor_name'){
        if(!val){
            showError(input,'Vendor required'); return false;
        }
        if(!alphaRegex.test(val) || val.length < 3){
            showError(input,'Vendor letters only (min 3)'); return false;
        }
    }

    if(name=='start_date' && !val){
        showError(input,'Date required'); return false;
    }

    if(name=='remarks'){
        if(val && (val.length < 5 || val.length > 300)){
            showError(input,'Remarks must be 5–300 characters'); return false;
        }
    }

    return true;
}

// 🔥 LIVE VALIDATION
$('input, textarea, select').on('keyup change', function(){
    validate($(this));
});

// 🔥 SUBMIT
$('#maintenanceForm').submit(function(e){
    e.preventDefault();

    let valid = true;

    $('input, textarea, select').each(function(){
        if(!validate($(this))) valid=false;
    });

    if(!valid) return;

    $.post('{{ route("inventory.maintenance.store") }}',
        $(this).serialize(),
        function(res){

            if(res.status){
                Swal.fire('Success','Maintenance added','success').then(()=>{
                    window.location.href='{{ route("inventory.maintenance.index") }}';
                });
            }
        }
    );
});

</script>

</x-layout>
