<x-layout>
    @section('title', 'Create Client')

    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-2">Create New Client</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 mt-2">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dhome') }}" class="text-decoration-none text-muted">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('clients.index') }}" class="text-decoration-none text-muted">
                                Clients
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-primary">Create</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('clients.index') }}" class="btn btn-outline-primary d-flex align-items-center">
                <i class="bi bi-arrow-left-circle me-2"></i>Back
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form id="createClientForm">
                            @csrf


                               <input type="hidden" name="cl_image" id="cropped_image_input">
                            <div class="d-flex flex-column flex-md-row align-items-center gap-4 mb-4">
                                <div class="position-relative">
                                    <div class="avatar-upload">
                                        <img src="{{ asset('images/admin_default.jpg') }}"
                                            alt="Client Preview"
                                            class="avatar-preview rounded-circle object-fit-cover"
                                            id="external_preview_image" width="150" height="150">
                                        <div class="avatar-edit">
                                            <input type="file" id="upload_image" name="cl_image"
                                                accept=".png, .jpg, .jpeg" />
                                            <label for="upload_image"
                                                class="bg-primary rounded-circle d-flex align-items-center justify-content-center">
                                                <i class="bi bi-camera-fill text-white"></i>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h4 class="mb-1">Client Profile</h4>
                                    <p class="text-muted mb-2">Upload profile picture</p>
                                </div>
                            </div>

                            <input type="hidden" name="cl_image" id="cropped_image_input">

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="cl_name" name="cl_name" required>
                                <label for="cl_name">Full Name</label>
                                <div class="text-danger" id="error-cl_name"></div>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="cl_email" name="cl_email" required>
                                <label for="cl_email">Email Address</label>
                                <div class="text-danger" id="error-cl_email"></div>
                            </div>

                            <div class="form-floating mb-4">
                                <input type="password" class="form-control" id="cl_password" name="cl_password" required>
                                <label for="cl_password">Password</label>
                                <div class="text-danger" id="error-cl_password"></div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='{{ route('clients.index') }}'">
                                    Cancel
                                </button>
                                <button type="submit" class="btn btn-primary px-4" id="submit_form">
                                    <i class="bi bi-plus-circle me-2"></i>Create Client
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_crop" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold" id="cropModalLabel">Crop Profile Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">Image Size (500×500 pixels)</p>
                    <div class="img-container">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <div class="cropper-container border rounded overflow-hidden">
                                    <img src="" id="sample_image" class="img-fluid" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex flex-column align-items-center">
                                    <div class="preview-container mb-3">
                                        <div class="preview-label small text-muted mb-2">Preview</div>
                                        <div class="preview rounded-circle overflow-hidden border border-3 border-primary"
                                            style="width: 150px; height: 150px;"></div>
                                    </div>
                                    <div class="zoom-controls d-flex gap-2">
                                        <button type="button" id="zoom_in" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-zoom-in"></i>
                                        </button>
                                        <button type="button" id="zoom_out" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-zoom-out"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="crop_and_upload" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .avatar-upload {
            position: relative;
            width: 150px;
            height: 150px;
        }
        .modal-lg {
            max-width: 670px !important;
        }
        .avatar-preview {
            width: 100%;
            height: 100%;
            border: 3px solid #f0f2f5;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .avatar-edit {
            position: absolute;
            right: 10px;
            bottom: 10px;
        }
        .avatar-edit input {
            display: none;
        }
        .avatar-edit label {
            width: 36px;
            height: 36px;
            margin-bottom: 0;
            cursor: pointer;
            transition: all 0.3s;
        }
        .avatar-edit label:hover {
            transform: scale(1.1);
        }
        .cropper-container {
            height: 400px;
            background: #f8f9fa;
        }
        .preview {
            width: 100%;
            height: 100%;
            overflow: hidden;
        }
        .zoom-controls {
            margin-top: 1rem;
        }
        .card {
            border-radius: 12px;
            overflow: hidden;
        }
        .breadcrumb {
            padding: 0;
            background: transparent;
        }
    </style>

    <script>
        $(document).ready(function() {
            var $modal = $('#modal_crop');
            var user_image = document.getElementById('sample_image');
            var cropper;
            var croppedImageData = '';

            $('#upload_image').change(function(event) {
                var files = event.target.files;
                if (files && files.length > 0) {
                    var file = files[0];

                    var done = function(url) {
                        user_image.src = url;
                        $modal.modal('show');
                    };

                    var reader = new FileReader();
                    reader.onload = function(event) {
                        done(reader.result);
                    };
                    reader.readAsDataURL(file);
                }
            });

            $modal.on('shown.bs.modal', function() {
                cropper = new Cropper(user_image, {
                    aspectRatio: 1,
                    viewMode: 1,
                    preview: '.preview',
                    dragMode: 'move',
                    responsive: true,
                    cropBoxMovable: true,
                    cropBoxResizable: false,
                });

                $('#zoom_in').click(function() {
                    cropper.zoom(0.1);
                });

                $('#zoom_out').click(function() {
                    cropper.zoom(-0.1);
                });
            }).on('hidden.bs.modal', function() {
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
            });

            $('#crop_and_upload').click(function() {
                var canvas = cropper.getCroppedCanvas({
                    width: 500,
                    height: 500,
                });

                canvas.toBlob(function(blob) {
                    var reader = new FileReader();
                    reader.readAsDataURL(blob);
                    reader.onloadend = function() {
                        croppedImageData = reader.result;
                        $('#cropped_image_input').val(croppedImageData);
                        $('#external_preview_image').attr('src', croppedImageData);
                        $modal.modal('hide');

                        Toastify({
                            text: "Image cropped successfully!",
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "#28a745",
                        }).showToast();
                    };
                }, 'image/jpeg', 0.9);
            });

          $('#createClientForm').submit(function(event) {
    event.preventDefault();

    // Check if image is cropped
    var croppedImage = $('#cropped_image_input').val();
    if (!croppedImage) {
        Swal.fire({
            icon: 'warning',
            title: 'Image Required',
            text: 'Please upload and crop an image before creating the client.',
            confirmButtonColor: '#3085d6',
        });
        return;
    }

    // Prepare data for submission
    var formData = {
        _token: $('input[name="_token"]').val(),
        cl_name: $('#cl_name').val(),
        cl_email: $('#cl_email').val(),
        cl_password: $('#cl_password').val(),
        cl_image: croppedImage // This is the base64 string
    };

    console.log('Submitting data:', {
        cl_name: formData.cl_name,
        cl_email: formData.cl_email,
        has_image: formData.cl_image ? 'Yes' : 'No',
        image_length: formData.cl_image ? formData.cl_image.length : 0
    });

    $('#submit_form').prop('disabled', true).html(
        '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Creating...'
    );

    $.ajax({
        url: '{{ route("clients.store") }}',
        method: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            console.log('Success response:', response);
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Client created successfully',
                confirmButtonColor: '#3085d6',
            }).then(() => {
                window.location.href = '{{ route("clients.index") }}';
            });
        },
        error: function(xhr) {
            console.log('Error details:', {
                status: xhr.status,
                response: xhr.responseJSON,
                responseText: xhr.responseText
            });

            $('#submit_form').prop('disabled', false).html(
                '<i class="bi bi-plus-circle me-2"></i>Create Client'
            );

            if (xhr.status === 422) {
                var errors = xhr.responseJSON.errors;
                console.log('Validation errors:', errors);

                // Clear previous errors
                $('.text-danger').html('');
                $('.form-control').removeClass('is-invalid');

                // Display new errors
                $.each(errors, function(key, value) {
                    console.log('Error for', key, ':', value);
                    $('#' + key).addClass('is-invalid');
                    $('#error-' + key).html('<span class="small">' + value[0] + '</span>');
                });

                // If there's a generic error
                if (xhr.responseJSON.message) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: xhr.responseJSON.message,
                        confirmButtonColor: '#3085d6',
                    });
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Failed to create client',
                    confirmButtonColor: '#3085d6',
                });
            }
        }
    });
});
            document.addEventListener("DOMContentLoaded", function() {
                const imageInput = document.getElementById("upload_image");

                function showErrorMessage(inputField, errorId, message) {
                    const errorElement = document.getElementById(errorId);
                    errorElement.innerHTML = `<span class="small">${message}</span>`;
                    errorElement.style.display = "block";
                    inputField.classList.add("is-invalid");

                    setTimeout(() => {
                        errorElement.style.display = "none";
                        inputField.classList.remove("is-invalid");
                    }, 5000);
                }

                function validateImage() {
                    const file = imageInput.files[0];
                    if (!file) return false;

                    const allowedTypes = ["image/jpeg", "image/png", "image/jpg"];
                    if (!allowedTypes.includes(file.type)) {
                        showErrorMessage(imageInput, "error-cl_image",
                            "Only JPG, JPEG, and PNG images are allowed.");
                        return false;
                    }

                    return true;
                }

                imageInput.addEventListener("change", function() {
                    if (this.files && this.files[0]) {
                        if (!validateImage()) {
                            this.value = "";
                        }
                    }
                });
            });
        });
    </script>
</x-layout>
