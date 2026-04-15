{{-- @php
    $hasSettingAccess =
        auth()->user()->can('setting view') ||
        auth()->user()->can('setting->applogo view') ||
        auth()->user()->can('setting->favicon view') ||
        auth()->user()->can('setting->sitecontrol view') ||
        auth()->user()->can('setting->themecolor view');

@endphp --}}
<x-tabnav>
    @section('title', 'Web Logo')
    <div class="main-container ">
        <!-- Vertical Tabs Navigation -->
        <div class="vertical-tabs ">
            {{-- @if ($hasSettingAccess) --}}
            <h6 class="fw-bold text-center mb-3 head">Setting</h6>
            @if (auth()->user()->hasRole('Super admin'))
                {{-- @can('setting->applogo view') --}}
                <a href="{{ route('applogo') }}" onclick="switchTab('tab1', event)">
                    <div class="tab-header {{ request()->routeIs('applogo') ? 'active' : '' }}">
                        <h1>Web App Logo</h1>
                    </div>
                </a>
                {{-- @endcan --}}
                {{-- @can('setting->favicon view') --}}
                <a href="{{ route('favicon') }}" onclick="switchTab('tab2', event)">
                    <div class="tab-header {{ request()->routeIs('favicon') ? 'active' : '' }}">
                        <h2>Favicon</h2>
                    </div>
                </a>
                {{-- @endcan
                @can('setting->sitecontrol view') --}}
                <a href="{{ route('sitecontrol') }}" onclick="switchTab('tab3', event)">
                    <div class="tab-header {{ request()->routeIs('sitecontrol') ? 'active' : '' }}">
                        <h3>Maintenance</h3>
                    </div>
                </a>
            @endif
            {{-- @endcan --}}

            <a href="{{ route('theme') }}" onclick="switchTab('tab4', event)">
                <div class="tab-header {{ request()->routeIs('theme') ? 'active' : '' }}">
                    <h3>Theme Color</h3>
                </div>
            </a>
            <a href="{{ route('cookies') }}" onclick="switchTab('tab5', event)">
                <div class="tab-header {{ request()->routeIs('cookies') ? 'active' : '' }}">
                    <h3>Cookies & Sessions</h3>
                </div>
            </a>
            {{-- @endif --}}
        </div>

        <!-- Tab Content Areas -->
        <div class="tab-content">
            <div id="tab1" class="tab-pane {{ request()->routeIs('applogo') ? 'active' : '' }}">
                <div
                    class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
                    <div>
                        <h3 class="fw-bold mb-2">Web Logo</h3>
                        <p class="text-muted mb-0">Customize your website's visual appearance</p>
                    </div>
                    <span class="badge bg-primary bg-opacity-10 text-primary mt-2 mt-md-0">
                        <i class="bi bi-stars me-1"></i> Recommended
                    </span>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-body p-4">
                    <form class="needs-validation" action="{{ route('applogo_post') }}" novalidate
                        enctype="multipart/form-data">
                        @csrf
                        @method('patch')
                        <!-- Logo Upload Section -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold d-block mb-3">
                                <i class="bi bi-image me-2"></i> Website Logo
                            </label>

                            <div class="d-flex flex-column flex-lg-row gap-4">
                                <!-- Logo Preview -->
                                <div class="logo-upload-area rounded-3 border border-2 border-dashed p-4 text-center"
                                    id="logoDropzone">
                                    <div id="logoPreview"
                                        class="d-flex flex-column align-items-center justify-content-center h-100">





                                        @if (isset($settingsapplogo) && $settingsapplogo->weblogo)
                                            <img src="{{ asset('weblogo/' . $settingsapplogo->weblogo) }}"
                                                alt="Current Logo" class="img-fluid" width="70px">
                                        @endif

                                        <input type="hidden" id="uploaded_logo" name="weblogo">

                                        <i class="bi bi-cloud-arrow-up fs-1 text-muted mb-2"></i>
                                        <span class="text-muted mb-2">Drag & drop your logo here</span>
                                        <span class="text-muted small">or</span>
                                        <button type="button" class="btn btn-sm btn-outline-primary mt-2 px-3"
                                            onclick="document.getElementById('upload_image1').click()">
                                            <i class="bi bi-upload me-1"></i> Browse Files
                                        </button>
                                    </div>
                                    <input type="file" class="d-none" id="upload_image1" name="weblogo"
                                        accept="image/png, image/jpeg">
                                </div>

                                <!-- Logo Guidelines -->
                                <div class="flex-grow-1">
                                    <div class="alert alert-light bg-light bg-opacity-25 border-0 rounded-3">
                                        <h6 class="fw-semibold mb-2"><i class="bi bi-lightbulb me-2"></i>Logo Guidelines
                                        </h6>
                                        <ul class="small mb-0 ps-3">
                                            <li>Optimal dimensions: 100×100 pixels</li>
                                            <li>Transparent background recommended</li>
                                            <li>JPG , JPEG or PNG format preferred</li>
                                            <li>Max file size: 2MB</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Website Name Section -->
                        <div class="mb-4">
                            <label for="webname" class="form-label fw-semibold">
                                <i class="bi bi-fonts me-2"></i> Website Name
                            </label>
                            <div class="input-group has-validation">
                                <input type="text" class="form-control" id="webname" name="webname"
                                    placeholder="e.g. My Awesome App" required
                                    value="{{ isset($settingsapplogo) ? $settingsapplogo->webname : old('webname') }}">
                                <div class="invalid-feedback">
                                    Please provide a website name.
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-end pt-2">
                            <button type="reset" class=" btn btn-sm btn-secondary me-3">Reset</button>
                            <button type="button" id="submit_form"
                                class="btn btn-primary btn-sm px-4 py-2 fw-semibold">
                                {{ isset($settingsapplogo) && $settingsapplogo->weblogo ? 'Update' : 'Add' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>



        <!-- Rest of your styles and scripts remain the same -->
        <style>
            .logo-upload-area {
                min-height: 180px;
                width: 100%;
                max-width: 320px;
                background-color: rgba(245, 245, 245, 0.5);
                transition: all 0.2s ease;
                cursor: pointer;
            }

            .logo-upload-area:hover {
                background-color: rgba(245, 245, 245, 0.8);
                border-color: var(--bs-primary) !important;
            }

            #logoPreview img {
                max-width: 100%;
                max-height: 140px;
                object-fit: contain;
            }

            .card {
                border-radius: 16px !important;
            }

            .form-control-lg {
                border-radius: 10px !important;
                padding: 12px 16px;
            }

            .input-group-text {
                border-radius: 10px 0 0 10px !important;
            }
        </style>



        <div class="modal fade" id="modal_crop" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div id="flash-message-container" class="mt-2 ms-3"></div>

                    <p class=" text-danger  mt-2 ms-3"> Logo size : width 500 / height 500 pixels </p>

                    <div class="modal-header">


                        <h1 class="modal-title fs-5" id="exampleModalLabel">Crop and Save Image</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="img-container">
                            <div class="row">
                                <div class="col-md-8">
                                    <img src="" id="sample_image" />
                                </div>
                                <div class="col-md-4">
                                    <div class="preview"></div>

                                    <div class="btnbar  mt-3  " style="margin-left: 6em">

                                        <button type="button" id="zoom_in" class="btn   btn-sm btn-success"> <i
                                                class=" bi  bi-plus"></i></button>
                                        <button type="button" id="zoom_out" class=" btn btn-sm btn-success "> <i
                                                class=" bi  bi-dash"></i>
                                        </button>
                                    </div>



                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" id="crop_and_upload" class="btn  btn-success">
                            Crop and Save
                        </button>
                    </div>
                </div>
            </div>
        </div>






        <style>
            img {
                display: block;
                max-width: 100%;
            }

            .preview {
                overflow: hidden;
                width: 150px;
                height: 150px;
                margin: 10px;
                border: 1px solid #1a70c7;
            }

            .modal-lg {
                max-width: 670px !important;
            }
        </style>


        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Reset functionality
                const resetBtn = document.querySelector('button[type="reset"]');
                const originalLogo =
                    "{{ isset($settingsapplogo->weblogo) ? asset('weblogo/' . $settingsapplogo->weblogo) : '' }}";

                resetBtn.addEventListener('click', function() {
                    document.getElementById('upload_image1').value = '';
                    resetLogoPreview();
                });

                function resetLogoPreview() {
                    const logoPreview = document.getElementById('logoPreview');
                    let htmlContent = '';

                    if (originalLogo) {
                        htmlContent = `<img src="${originalLogo}" alt="Current Logo" class="img-fluid" width="70px">`;
                    }

                    htmlContent += `
                <i class="bi bi-cloud-arrow-up fs-1 text-muted mb-2"></i>
                <span class="text-muted mb-2">Drag & drop your logo here</span>
                <span class="text-muted small">or</span>
                <button type="button" class="btn btn-sm btn-outline-primary mt-2 px-3"
                        onclick="document.getElementById('upload_image1').click()">
                    <i class="bi bi-upload me-1"></i> Browse Files
                </button>
            `;

                    logoPreview.innerHTML = htmlContent;
                }

                // File upload and preview
                document.getElementById('upload_image1').addEventListener('change', function(e) {
                    const preview = document.getElementById('logoPreview');
                    const file = e.target.files[0];

                    if (file) {
                        // Validate file type
                        const validTypes = ['image/png', 'image/jpeg', 'image/jpg'];
                        if (!validTypes.includes(file.type)) {
                            Swal.fire({
                                text: 'Only PNG, JPG, and JPEG images are allowed!',
                                icon: 'error',
                            });
                            this.value = '';
                            return;
                        }

                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.innerHTML =
                                `<img src="${e.target.result}" alt="Logo Preview" class="img-fluid">`;
                        }
                        reader.readAsDataURL(file);
                    }
                });

                // Drag and drop functionality
                const dropzone = document.getElementById('logoDropzone');

                dropzone.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    dropzone.style.borderColor = 'var(--bs-primary)';
                    dropzone.style.backgroundColor = 'rgba(13, 110, 253, 0.05)';
                });

                dropzone.addEventListener('dragleave', () => {
                    dropzone.style.borderColor = '';
                    dropzone.style.backgroundColor = '';
                });

                dropzone.addEventListener('drop', (e) => {
                    e.preventDefault();
                    dropzone.style.borderColor = '';
                    dropzone.style.backgroundColor = '';

                    if (e.dataTransfer.files.length) {
                        document.getElementById('upload_image1').files = e.dataTransfer.files;
                        const event = new Event('change');
                        document.getElementById('upload_image1').dispatchEvent(event);
                    }
                });

                // Cropper.js implementation
                var $modal = $('#modal_crop');
                var logo_img = document.getElementById('sample_image');
                var cropper;
                var currentFileType = '';

                $('#upload_image1').change(function(event) {
                    var files = event.target.files;
                    if (files && files.length > 0) {
                        var file = files[0];
                        currentFileType = file.type;

                        // Validate file type again
                        var validTypes = ['image/png', 'image/jpeg', 'image/jpg'];
                        if (!validTypes.includes(file.type)) {
                            Swal.fire({
                                text: 'Only PNG, JPG, and JPEG images are allowed!',
                                icon: 'error',
                            });
                            $(this).val('');
                            return;
                        }

                        var done = function(url) {
                            logo_img.src = url;
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
                    cropper = new Cropper(logo_img, {
                        aspectRatio: 500 / 500,
                        viewMode: 1,
                        preview: '.preview',
                        dragMode: 'move',
                        cropBoxResizable: false,
                        cropBoxMovable: true,

                    });

                    $('#zoom_in').click(function() {
                        if (cropper) {
                            cropper.zoom(0.1);
                        }
                    });

                    $('#zoom_out').click(function() {
                        if (cropper) {
                            cropper.zoom(-0.1);
                        }
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

                        fillColor: currentFileType === 'image/png' ? 'transparent' : '#ffffff',
                        imageSmoothingEnabled: true,
                        imageSmoothingQuality: 'high',
                    });

                    // Use appropriate format based on original image type
                    var mimeType = currentFileType === 'image/png' ? 'image/png' : 'image/jpeg';

                    canvas.toBlob(function(blob) {
                        var reader = new FileReader();
                        reader.readAsDataURL(blob);
                        reader.onloadend = function() {
                            var base64data = reader.result;
                            $('#submit_form').data('cropped_image', base64data);
                            $modal.modal('hide');

                            // Update preview immediately
                            $('#logoPreview').html(
                                `<img src="${base64data}" alt="Logo Preview" class="img-fluid">`
                            );
                        };
                    }, mimeType, currentFileType === 'image/png' ? undefined : 0.92);
                });

                // Form submission
                $('#submit_form').click(function(e) {
                    e.preventDefault();
                    const croppedImage = $('#submit_form').data('cropped_image');
                    const name = $('#webname').val();
                    const isUpdate = {{ isset($settingsapplogo) ? 'true' : 'false' }};

                    // Validation
                    if (!name) {
                        Swal.fire({
                            text: 'Please provide a website name.',
                            icon: 'warning',
                        });
                        return;
                    }

                    if (!isUpdate && !croppedImage) {
                        Swal.fire({
                            text: 'Please upload and crop an image.',
                            icon: 'warning',
                        });
                        return;
                    }

                    $.ajax({
                        url: '{{ route('applogo_post') }}',
                        method: 'PATCH',
                        data: {
                            weblogo: croppedImage || null,
                            webname: name,
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    text: response.message,
                                    icon: "success",
                                }).then(() => location.reload());
                            } else {
                                Swal.fire({
                                    text: response.message,
                                    icon: "error",
                                });
                            }
                        },
                        error: function(xhr) {
                            var errors = xhr.responseJSON.errors;
                            if (errors) {
                                var errorMessage = '';
                                for (var key in errors) {
                                    errorMessage += '<p class="text-danger">' + errors[key].join(
                                        ', ') + '</p>';
                                }
                                $('#flash-message-container').html(errorMessage);
                            } else {
                                Swal.fire({
                                    text: "An error occurred while saving.",
                                    icon: "error",
                                });
                            }
                        },
                    });
                });

                // Form validation
                (function() {
                    'use strict'
                    const forms = document.querySelectorAll('.needs-validation')

                    Array.from(forms).forEach(form => {
                        form.addEventListener('submit', event => {
                            if (!form.checkValidity()) {
                                event.preventDefault()
                                event.stopPropagation()
                            }

                            form.classList.add('was-validated')
                        }, false)
                    })
                })()
            });
        </script>

    </div>

</x-tabnav>
