<x-layout>
@section('title','Add Inventory Item')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


<style>

    #docName {
    max-width: 180px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    text-align: center;
}

    .file-remove {
    position: absolute;
    top: 8px;
    right: 8px;
    background: #dc3545;
    color: #fff;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    text-align: center;
    line-height: 20px;
    font-weight: bold;
    cursor: pointer;
    z-index: 10;
}
.form-control, .form-select{
    min-height: 45px;
    font-size: 14px;
    transition: all 0.2s ease;
}
.form-control:focus, .form-select:focus{
    border-color: #28a745;
    box-shadow: 0 0 0 0.15rem rgba(40,167,69,0.15);
}
textarea.form-control{
    min-height: 100px;
}

.upload-box {
    border: 2px dashed #cbd5e1;
    border-radius: 10px;
    height: 130px;
    width: 230px;
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8fafc;
}

.upload-box:hover {
    border-color: #28a745;
    background: #f1fff5;
}

.upload-placeholder {
    text-align: center;
    color: #6c757d;
}

.upload-placeholder i {
    font-size: 30px;
    display: block;
    margin-bottom: 5px;
}



.preview-img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}


.action-card{
    position: sticky;

}
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
                            <a href="{{ route('inventory.index') }}" class="text-decoration-none text-dark">
                                Inventory
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-primary">Create</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('inventory.index') }}" class="btn btn-outline-primary d-flex align-items-center">
                <i class="bi bi-arrow-left-circle me-2"></i>Back
            </a>
        </div>



<div class="d-flex justify-content-center">
    <div class="card border-0 shadow-sm p-4 w-100" style="max-width:1100px;">

<h5 class="mb-4 fw-bold text-success">Inventory Create</h5>

<form id="itemForm">
@csrf

<div class="row g-4">

<div class="col-12">
    <div class="bg-light rounded-3 p-3 mb-2">
        <h6 class="fw-bold text-primary mb-0">Basic Information</h6>
    </div>
</div>

<div class="col-md-4">
<label class="form-label">Item Name <span class="text-danger">*</span></label>
<input type="text" name="item_name" placeholder="Enter item name" class="form-control">
</div>





<div class="col-md-4">
<label class="form-label">Item Code <span class="text-danger">*</span></label>
<input type="text" name="item_code" readonly class="form-control bg-light" placeholder="Auto generated">

<input type="hidden" id="last_id" value="{{ $lastId }}">
</div>


<div class="col-md-4">
<label class="form-label">Category <span class="text-danger">*</span></label>
<select name="category_id" class="form-select select2-category">
<option value="">Select category</option>
@foreach($categories as $cat)
<option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
@endforeach
</select>
</div>



<div class="col-12 mt-3">
    <div class="bg-light rounded-3 p-3 mb-2">
        <h6 class="fw-bold text-primary mb-0">Item Details</h6>
    </div>
</div>

<div class="col-md-4">
    <label class="form-label">Brand</label>
<input type="text" name="brand" placeholder="Brand (e.g. Dell, HP)" class="form-control">
</div>

<div class="col-md-4">
      <label class="form-label">Model Number</label>
<input type="text" name="model_number" placeholder="Model number" class="form-control">
</div>

<div class="col-md-4">
      <label class="form-label">Serial Number</label>
<input type="text" name="serial_number" placeholder="Serial number" class="form-control">
</div>

<div class="col-12 mt-3">
    <div class="bg-light rounded-3 p-3 mb-2">
        <h6 class="fw-bold text-primary mb-0">Purchase Details</h6>
    </div>
</div>

<div class="col-md-4">
    <label class="form-label">Purchase Date</label>
<input type="date" name="purchase_date" class="form-control">
</div>

<div class="col-md-4">
      <label class="form-label">Cost</label>
<input type="number" name="purchase_cost" placeholder="Purchase cost" class="form-control">
</div>

<div class="col-md-4">
    <label class="form-label">Vendor Name</label>
<input type="text" name="vendor_name" placeholder="Vendor name" class="form-control">
</div>

<div class="col-md-4">
    <label class="form-label">Invoice Number</label>
<input type="text" name="invoice_number" placeholder="Invoice number" class="form-control">
</div>

<div class="col-md-4">
       <label class="form-label">Warranty Expiry</label>
<input type="date" name="warranty_expiry" class="form-control">
</div>

<div class="col-12 mt-3">
    <div class="bg-light rounded-3 p-3 mb-2">
        <h6 class="fw-bold text-primary mb-0">Stock Information</h6>
    </div>
</div>

<div class="col-md-4">
       <label class="form-label">Quantity</label>
<input type="number" name="quantity" placeholder="Total quantity" class="form-control">
</div>

<div class="col-md-4">
       <label class="form-label">Avaliable Stock</label>
<input type="number" name="available_stock" placeholder="Available stock" class="form-control">
</div>

<div class="col-md-4">
     <label class="form-label">Status</label>
<select name="status" class="form-select select2-status">
<option value="">Select status</option>
<option value="available">Available</option>
<option value="assigned">Assigned</option>
<option value="maintenance">Maintenance</option>
<option value="damaged">Damaged</option>
</select>
</div>

<div class="col-12 mt-3 ">
    <div class="bg-light rounded-3 p-3 mb-2">
        <h6 class="fw-bold text-primary mb-0">Media</h6>
    </div>
</div>




<div class="col-md-6">
<label class="form-label">Item Image</label>

<input type="hidden" name="item_image" id="cropped_image_input">

<div class="upload-box position-relative">

    <!-- ❌ Remove button -->
    <span id="removeImage" class="file-remove d-none">×</span>

<img id="preview_image" class="preview-img d-none">
    <div class="upload-placeholder" id="image_placeholder">
        <i class="bi bi-image"></i>
        <p>No Image</p>
    </div>

</div>

<div class="mt-2">
    <button type="button" class="btn btn-sm btn-primary"
            onclick="$('#upload_image').click()">
        Choose Image
    </button>


</div>

<input type="file" id="upload_image" accept="image/*" hidden>
</div>


<div class="col-md-6  mb-3">
<label class="form-label">Document</label>

<div class="upload-box position-relative">

    <span id="removeDoc" class="file-remove d-none">×</span>

    <div class="upload-placeholder" id="doc_placeholder">
        <i class="bi bi-file-earmark-text"></i>
        <p>No File</p>
    </div>

    <div id="docName" class="small text-success"></div>

</div>

<div class="mt-2">
    <button type="button" class="btn btn-sm btn-primary"
            onclick="$('#docInput').click()">
        Choose File
    </button>


</div>

<input type="file" id="docInput" name="document_file"
       accept=".pdf,.doc,.docx" hidden>
</div>

<div class="col-12 mt-3">
    <div class="bg-light rounded-3 p-3 mb-2">
        <h6 class="fw-bold text-primary mb-0">Additional Info</h6>
    </div>
</div>
<div class="col-md-6">
     <label class="form-label">Description</label>
<textarea name="description" placeholder="Enter description" class="form-control"></textarea>
</div>

<div class="col-md-6">
     <label class="form-label">Remark</label>
<textarea name="remarks" placeholder="Enter remarks" class="form-control"></textarea>
</div>

</div>





</div>



<!-- RIGHT SIDE ACTION CARD -->
<div class="col-lg-3">

<div class="card  border-0 shadow-sm ms-4 p-4 action-card">

<h6 class="fw-bold mb-3 text-primary">Actions</h6>
<div class="d-flex gap-3">
    <button type="submit" class="btn btn-success flex-fill py-2">
       Add
    </button>

    <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary flex-fill py-2">
        Cancel
    </a>
</div>
</div>

</div>
</form>


<div class="modal fade" id="cropModal">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content">

<div class="modal-header">
    <h5>Crop Image</h5>
    <button class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
    <div class="d-flex justify-content-center">
        <div class="border rounded overflow-hidden" style="width:100%; height:400px;">
            <img id="sample_image" class="w-100 h-100" style="object-fit:contain;">
        </div>
    </div>
</div>

<div class="modal-footer justify-content-between">

    <div class="d-flex gap-2">
        <button type="button" id="zoom_in" class="btn btn-outline-primary">
            <i class="bi bi-zoom-in"></i>
        </button>

        <button type="button" id="zoom_out" class="btn btn-outline-primary">
            <i class="bi bi-zoom-out"></i>
        </button>
    </div>

    <div class="d-flex gap-2">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button id="crop_btn" class="btn btn-success">Crop</button>
    </div>

</div>

</div>
</div>
</div>


</div>
</div>


<script>
let cropper;
let croppedImage = '';
let zoomLevel = 1;
const maxZoom = 2;
const minZoom = 0.5;

$('#upload_image').change(function(e){
    let file = e.target.files[0];
    if(!file) return;

    if(!file.type.startsWith('image/')){
        alert('Only images allowed');
        $(this).val('');
        return;
    }

    let reader = new FileReader();

    reader.onload = function(e){
        $('#sample_image').attr('src', e.target.result);
        $('#cropModal').modal('show');
    };

    reader.readAsDataURL(file);
});

$('#cropModal').on('shown.bs.modal', function(){

    cropper = new Cropper(document.getElementById('sample_image'), {
        aspectRatio: NaN,
        viewMode: 1,
        autoCropArea: 0.8,
        preview: '.preview',
        dragMode: 'move',
        cropBoxResizable: true,
        cropBoxMovable: true
    });

    $('#zoom_in').off().click(function(){
        if(zoomLevel < maxZoom){
            cropper.zoom(0.1);
            zoomLevel += 0.1;
        }
    });

    $('#zoom_out').off().click(function(){
        if(zoomLevel > minZoom){
            cropper.zoom(-0.1);
            zoomLevel -= 0.1;
        }
    });

}).on('hidden.bs.modal', function(){

    if(cropper){
        cropper.destroy();
        cropper = null;
    }

    zoomLevel = 1;
});

$('#crop_btn').click(function(){

   let canvas = cropper.getCroppedCanvas();

    canvas.toBlob(function(blob){

        let reader = new FileReader();

        reader.onloadend = function(){

            croppedImage = reader.result;

            $('#cropped_image_input').val(croppedImage);

              $('#preview_image')
    .attr('src', croppedImage)
    .removeClass('d-none');

$('#image_placeholder').hide();

            $('#removeImage').removeClass('d-none');

            $('#cropModal').modal('hide');
        };

        reader.readAsDataURL(blob);

    });
});

$('#removeImage').click(function(){


 $('#preview_image').attr('src','').addClass('d-none');
    $('#image_placeholder').show();

    $('#cropped_image_input').val('');
    $('#upload_image').val('');

    $(this).addClass('d-none');
});

$('#docInput').change(function(){

    let file = this.files[0];
    if(!file) return;

    let allowedTypes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ];

    if(!allowedTypes.includes(file.type)){
        alert('Only PDF, DOC, DOCX allowed');
        $(this).val('');
        return;
    }

    $('#docName').html(`<i class="bi bi-file-earmark text-success"></i> ${file.name}`);

    $('#doc_placeholder').hide();
    $('#removeDoc').removeClass('d-none');
});

$('#removeDoc').click(function(){

    $('#docName').text('');
    $('#doc_placeholder').show();

    $('#docInput').val('');

    $(this).addClass('d-none');
});
</script>

<script>

$(document).ready(function(){

    // Category dropdown
    $('.select2-category').select2({
        placeholder: "Select category",
        allowClear: true,
        width: '100%'
    });

    // Status dropdown
    $('.select2-status').select2({
        placeholder: "Select status",
        allowClear: true,
        width: '100%'
    });

});

function showError(input, message){

    input.addClass('is-invalid');

    // 🔥 Handle Select2
    if(input.hasClass('select2-category') || input.hasClass('select2-status')){
        input.next('.select2-container').find('.select2-selection')
            .addClass('is-invalid');
    }

    if(input.closest('div').find('.error-msg').length === 0){

        let error = $('<div class="text-danger small mt-1 error-msg">'+message+'</div>');
        input.closest('div').append(error);

        setTimeout(()=>{
            error.fadeOut(500, function(){ $(this).remove(); });

            input.removeClass('is-invalid');

            // remove select2 border
            if(input.hasClass('select2-category') || input.hasClass('select2-status')){
                input.next('.select2-container').find('.select2-selection')
                    .removeClass('is-invalid');
            }

        },3000);
    }
}

function clearFieldError(input){

    input.removeClass('is-invalid');
    input.closest('div').find('.error-msg').remove();

    // 🔥 remove select2 border
    if(input.hasClass('select2-category') || input.hasClass('select2-status')){
        input.next('.select2-container').find('.select2-selection')
            .removeClass('is-invalid');
    }
}
function scrollToField(el){
    $('html, body').animate({
        scrollTop: el.offset().top - 120
    }, 400);
}

function validateField(input){

    let val = input.val().trim();
    let name = input.attr('name');

    clearFieldError(input);

    let nameRegex = /^[a-zA-Z0-9 ]+$/;
    let alphaRegex = /^[a-zA-Z ]+$/;

    if(name === 'item_name'){
        if(val=='' || val.length < 5 || val.length > 30 || !nameRegex.test(val)){
            showError(input,'Item name 5-30 chars (letters & numbers)');
            return false;
        }
    }

    if(name === 'item_code'){
        if(val==''){
            showError(input,'Item code required');
            return false;
        }
    }

    if(name === 'category_id'){
        if(val==''){
            showError(input,'Category required');
            return false;
        }
    }

    if(name === 'brand'){
        if(val!='' && (val.length < 5 || val.length > 30)){
            showError(input,'Brand 5-30 chars');
            return false;
        }
    }

    if(name === 'model_number'){
        if(val!='' && val.length > 40){
            showError(input,'Max 40 chars');
            return false;
        }
    }

    if(name === 'serial_number'){
        if(val!='' && val.length > 40){
            showError(input,'Max 40 chars');
            return false;
        }
    }

    if(name === 'vendor_name'){
        if(val!='' && (!alphaRegex.test(val) || val.length < 5 || val.length > 30)){
            showError(input,'Vendor 5-30 letters only');
            return false;
        }
    }

    if(name === 'invoice_number'){
        if(val!='' && val.length > 40){
            showError(input,'Max 40 chars');
            return false;
        }
    }

    if(name === 'description'){
        if(val!='' && (val.length < 5 || val.length > 100)){
            showError(input,'5-30 chars required');
            return false;
        }
    }

    if(name === 'warranty_expiry'){

    let purchase_date = $('input[name="purchase_date"]').val();

    if(val !== '' && purchase_date !== ''){

        let p = new Date(purchase_date);
        let w = new Date(val);

        if(w <= p){
            showError(input,'Warranty must be after purchase date');
            return false;
        }
    }
}

    if(name === 'remarks'){
        if(val!='' && (val.length < 5 || val.length > 100)){
            showError(input,'5-30 chars required');
            return false;
        }
    }

    return true;
}
$('input[name="purchase_date"], input[name="warranty_expiry"]').on('change', function(){

    validateField($('input[name="purchase_date"]'));
    validateField($('input[name="warranty_expiry"]'));

});

$('input, textarea, select').on('keyup change', function(){
    validateField($(this));
});

$('#itemForm').submit(function(e){
    e.preventDefault();

    let valid = true;
    let firstError = null;

    $('input, textarea, select').each(function(){

        let fieldValid = validateField($(this));

        if(!fieldValid && valid){
            firstError = $(this);
            valid = false;
        }

    });

    let purchase_date = $('input[name="purchase_date"]').val();
    let warranty = $('input[name="warranty_expiry"]').val();

    if(warranty && purchase_date){
        let p = new Date(purchase_date);
        let w = new Date(warranty);

        if(w <= p){
            let field = $('input[name="warranty_expiry"]');
            showError(field,'Warranty must be after purchase date');

            if(valid){
                firstError = field;
                valid = false;
            }
        }
    }

    if(!valid){
        scrollToField(firstError);
        return;
    }

    let formData = new FormData(this);
  formData.append('item_image', croppedImage);

    $.ajax({
        url:'{{ route("inventory.store") }}',
        type:'POST',
        data:formData,
        contentType:false,
        processData:false,
        success:function(res){
            if(res.status){
                Swal.fire('Success','Inventory item created successfully','success').then(()=>{
                    window.location.href='{{ route("inventory.index") }}';
                });
            }
        }
    });

});



$(document).ready(function(){

    let lastId = parseInt($('#last_id').val()) || 0;

    let next = lastId + 1;

    let year = new Date().getFullYear();

    let code = 'ITM-' + year + '-' + String(next).padStart(4,'0');

    $('input[name="item_code"]').val(code);

});
</script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</x-layout>
