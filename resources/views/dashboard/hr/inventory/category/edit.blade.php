<x-layout>
@section('title','Update Category')

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
                            <a href="{{ route('inventory.categories.index') }}" class="text-decoration-none text-dark">
                                Inventory Category
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-primary">Edit</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('inventory.categories.index') }}" class="btn btn-outline-primary d-flex align-items-center">
                <i class="bi bi-arrow-left-circle me-2"></i>Back
            </a>
        </div>

<div class="row justify-content-center">

<!-- LEFT FORM -->
<div class="col-lg-6">

<div class="card border-0 shadow-sm p-4">


<div class="col-12 mt-3 ">
    <div class="bg-light rounded-3 p-3 mb-2">
        <h6 class="fw-bold text-primary mb-0">Category Edit</h6>
    </div>
</div>

<form id="categoryForm">
@csrf
@method('PUT')
<div class="row g-4 mt-3">

<div class="mt-2">
    <label class="form-label">
    Category Name <span class="text-danger">*</span>
</label>
<input type="text" name="category_name"
       class="form-control"
       placeholder="Enter category name" value="{{ $category->category_name }}">

<div id="category-check-msg" class="small mt-1"></div>
</div>


       <div class="mt-2">
        <label class="form-label">
    Description
</label>
<textarea name="description"
          class="form-control"
          placeholder="Enter description">{{ $category->description }}</textarea>
       </div>



</div>

</form>

</div>
</div>

<!-- RIGHT ACTION CARD -->
<div class="col-lg-3">

<div class="card border-0 shadow-sm p-4">

<h6 class="fw-bold mb-3 text-primary">Actions</h6>

<div class="d-flex gap-2">
    <button type="submit" form="categoryForm" class="btn btn-success w-100">
        Update
    </button>

    <a href="{{ route('inventory.categories.index') }}" class="btn btn-outline-secondary w-100">
        Cancel
    </a>
</div>

</div>

</div>

</div>
</div>

<script>
let checkTimeout;
let isDuplicate = false;

$('input[name="category_name"]').on('keyup', function(){

    let input = $(this);
    let val = input.val().trim();

    validateField(input);

    clearTimeout(checkTimeout);

    if(val.length < 3) {
        $('#category-check-msg').text('');
        isDuplicate = false;
        return;
    }

    checkTimeout = setTimeout(function(){
$.ajax({
    url: '{{ route("inventory.categories.check") }}',
    type: 'POST',
    data: {
        _token: '{{ csrf_token() }}',
        category_name: val,
        id: {{ $category->id }}
    },
    success: function(res){

        if(res.exists){
            isDuplicate = true;
            $('#category-check-msg').html('<span class="text-danger">Category already exists</span>');
            input.addClass('is-invalid');
        }else{
            isDuplicate = false;
            $('#category-check-msg').html('');
            input.removeClass('is-invalid');
        }

    }
});

    }, 500);

});

function showError(input, message){
    input.addClass('is-invalid');

    if(input.closest('div').find('.error-msg').length === 0){
        let error = $('<div class="text-danger small mt-1 error-msg">'+message+'</div>');
        input.closest('div').append(error);

        setTimeout(()=>{
            error.fadeOut(300, function(){ $(this).remove(); });
            input.removeClass('is-invalid');
        },2000);
    }
}

function clearError(input){
    input.removeClass('is-invalid');
    input.closest('div').find('.error-msg').remove();
}

function validateField(input){

    let val = input.val().trim();
    let name = input.attr('name');

    clearError(input);

    let alphaRegex = /^[A-Za-z ]+$/;

    if(name === 'category_name'){
        if(val === ''){
            showError(input,'Category name required');
            return false;
        }
        if(!alphaRegex.test(val)){
            showError(input,'Only letters allowed');
            return false;
        }
        if(val.length > 30){
            showError(input,'Max 30 characters');
            return false;
        }
    }

    if(name === 'description'){
        if(val !== ''){
            if(val.length < 5){
                showError(input,'Min 5 characters');
                return false;
            }
            if(val.length > 300){
                showError(input,'Max 300 characters');
                return false;
            }
        }
    }

    return true;
}

$('input[name="category_name"]').on('keypress', function(e){
    let char = String.fromCharCode(e.which);
    if(!/[A-Za-z ]/.test(char)){
        e.preventDefault();
    }
});

$('input, textarea').on('keyup change', function(){
    validateField($(this));
});

$('#categoryForm').submit(function(e){
    e.preventDefault();

    let valid = true;
    let firstError = null;

    $('input, textarea').each(function(){
        let ok = validateField($(this));

        if(!ok && valid){
            firstError = $(this);
            valid = false;
        }
    });
if(isDuplicate){
    let input = $('input[name="category_name"]');

    input.addClass('is-invalid');

    $('html, body').animate({
        scrollTop: input.offset().top - 100
    }, 300);

    return;
}

    if(!valid){
        $('html, body').animate({
            scrollTop: firstError.offset().top - 100
        }, 300);
        return;
    }

  $.ajax({
    url: '{{ route("inventory.categories.update", $category->id) }}',
    type: 'PUT',
    data: $(this).serialize(),
    success:function(res){
        if(res.status){
            Swal.fire('Success','Category updated','success').then(()=>{
                window.location.href='{{ route("inventory.categories.index") }}';
            });
        }
    }
});

});
</script>
</x-layout>
