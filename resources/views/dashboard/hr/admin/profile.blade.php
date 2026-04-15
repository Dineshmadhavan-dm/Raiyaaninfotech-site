<x-layout>

    @section('title', 'Admin Profile')

    <div class="container-fluid p-4">
        <!-- Header & Breadcrumb -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 mt-2">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dhome') }}" class="text-decoration-none text-muted">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-primary" aria-current="page">Profile</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('adminlist') }}" class="btn btn-outline-primary d-flex align-items-center">
                <i class="bi bi-arrow-left-circle me-2"></i>Back
            </a>
        </div>

        {{-- content area --}}
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex flex-column flex-md-row align-items-center gap-4 mb-4">
                            <div class="position-relative">
                                <div class="avatar-upload">
                                    <img src="{{ Auth::user()->image ? asset('admin_images/' . Auth::user()->image) : asset('images/admin_default.jpg') }}"
                                        alt="{{ Auth::user()->name }}"
                                        class="avatar-preview rounded-circle object-fit-cover"
                                        id="external_preview_image" width="150" height="150">
                                    <div class="avatar-edit">
                                        <input type="file" id="upload_image" name="image"
                                            accept=".png, .jpg, .jpeg" />
                                        <label for="upload_image"
                                            class="bg-primary rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-camera-fill text-white"></i>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="flex-grow-1">
                                <h4 class="mb-1">{{ $user->name }}</h4>
                                <p class="text-muted mb-2">{{ $user->email }}</p>
                                <div class="d-flex gap-2">

                                    <div class="d-flex flex-wrap justify-content-center  gap-2">
                                        @forelse($user->roles as $role)
                                            <span @class([
                                                'bg-opacity-70',
                                                'badgecolor',
                                                'badge',
                                                'bg-danger' => $role->name == 'Super admin',
                                                'bg-primary' => $role->name == 'Admin',
                                                'bg-info text-dark' => $role->name == 'moderator',
                                                'bg-success' => $role->name == 'editor',
                                                'bg-secondary' => $role->name == 'viewer',
                                                'bg-warning text-dark' => $role->name == 'client',
                                                'bg-dark' => !in_array(strtolower($role->name), [
                                                    'super admin',
                                                    'admin',
                                                    'moderator',
                                                    'editor',
                                                    'viewer',
                                                    'client',
                                                ]),
                                            ])>
                                                {{ $role->name }}
                                            </span>
                                        @empty
                                            <span class="badge bg-light text-dark ">No
                                                roles
                                                assigned</span>
                                        @endforelse
                                        @if (count($user->roles) > 3)
                                            <span class="badge bg-light text-dark"
                                                title="{{ $user->roles->slice(3)->pluck('name')->join(', ') }}">
                                                +{{ count($user->roles) - 3 }}
                                            </span>
                                        @endif
                                    </div>
                                    <span class="badge bg-success bg-opacity-10 text-success">Active</span>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('profilepost') }}" method="post" enctype="multipart/form-data"
                            id="imageUploadForm">
                            @csrf
                            @method('patch')

                            <input type="hidden" name="image" id="cropped_image_input">

                            <div class=" form-floating mb-3">
                                <input type="text" class="form-control " id="name" name="name"
                                    value="{{ $user->name }}" disabled>
                                <label for="name" class="form-label ">Full Name</label>
                            </div>

                            <div class="form-floating mb-4">
                                <input type="email" class="form-control " id="email" name="email"
                                    value="{{ $user->email }}" disabled>
                                <label for="email" class="form-label ">Email Address</label>
                                <div class="text-danger" id="error-email"></div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">

                                <button type="submit" class="btn btn-primary px-4" id="submit_form">
                                    <i class="bi bi-upload me-2"></i>Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Cropping Modal -->
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

        /* .form-control:disabled {
            background-color: #f8f9fa;
            opacity: 1;
        } */

        .card {
            border-radius: 12px;
            overflow: hidden;
        }

        .breadcrumb {
            padding: 0;
            background: transparent;
        }

        .badge {
            padding: 0.35em 0.65em;
            font-weight: 500;
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

                // Zoom Controls
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

                        // Update preview immediately
                        $('#external_preview_image').attr('src', croppedImageData);

                        $modal.modal('hide');

                        // Show success message
                        Toastify({
                            text: "Image cropped successfully! Click Update Profile to save.",
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "#28a745",
                        }).showToast();
                    };
                }, 'image/jpeg', 0.9);
            });

            $('#imageUploadForm').submit(function(event) {
                event.preventDefault();

                if (!$('#cropped_image_input').val()) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Please crop and save the image first!',
                        confirmButtonColor: '#3085d6',
                    });
                    return;
                }

                var formData = new FormData(this);

                // Show loading state
                $('#submit_form').prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Saving...'
                );

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Profile image updated successfully',
                            confirmButtonColor: '#3085d6',
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        $('#submit_form').prop('disabled', false).html(
                            '<i class="bi bi-upload me-2"></i>Update Profile');
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.error || 'Failed to update profile',
                            confirmButtonColor: '#3085d6',
                        });
                    }
                });
            });
        });

        // Image validation
        document.addEventListener("DOMContentLoaded", function() {
            const imageInput = document.getElementById("upload_image");
            const submitButton = document.getElementById("submit_form");

            function showErrorMessage(inputField, errorId, message) {
                const errorElement = document.getElementById(errorId);
                errorElement.innerHTML = `<span class="small">${message}</span>`;
                errorElement.style.display = "block";
                inputField.classList.add("is-invalid");

                // Auto-hide after 5 seconds
                setTimeout(() => {
                    errorElement.style.display = "none";
                    inputField.classList.remove("is-invalid");
                }, 5000);
            }

            function validateImage() {
                const file = imageInput.files[0];
                if (!file) return false;

                // Check file type
                const allowedTypes = ["image/jpeg", "image/png", "image/jpg"];
                if (!allowedTypes.includes(file.type)) {
                    showErrorMessage(imageInput, "error-carousel_img",
                        "Only JPG, JPEG, and PNG images are allowed.");
                    return false;
                }

                // Check file size (max 2MB)
                // if (file.size > 2 * 1024 * 1024) {
                //     showErrorMessage(imageInput, "error-carousel_img",
                //         "Image size must be less than 2MB.");
                //     return false;
                // }

                return true;
            }

            imageInput.addEventListener("change", function() {
                if (this.files && this.files[0]) {
                    if (!validateImage()) {
                        this.value = ""; // Clear invalid file
                    }
                }
            });
        });
    </script>
</x-layout>
