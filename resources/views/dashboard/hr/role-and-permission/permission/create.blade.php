<x-layout>


    @section('title', 'Permission')

    <div class="container-fluid p-4">
        <!-- Header & Breadcrumb -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 mt-2">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dhome') }}" class="text-decoration-none text-muted">
                                <i class="bi bi-people me-2"></i>Employment
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-primary" aria-current="page">Permission Create</li>
                    </ol>
                </nav>
            </div>
        </div>

        <x-message />

        {{-- content area --}}


        <div class="card border-0 shadow-sm mt-4">



            <div class=" text-end p-4">
                <a href="{{ route('permissions.index') }}" class=" btn btn-outline-primary"> <i
                        class="bi bi-arrow-left-circle me-2"></i>Back</a>
            </div>


            <div class="card-body p-4">

                {{-- pemrission note --}}
                <h5 class="alert-heading "> <i class="bi bi-info-circle-fill me-3 text-danger"></i>Permission
                    Guidelines</h5>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 bg-white rounded border">
                            <h6 class="fw-semibold mb-2 text-primary">
                                <i class="bi bi-shield-lock me-2"></i>Parent Permissions
                            </h6>
                            <p class="mb-1 small">Format: <code>[resource] [action]</code></p>
                            <p class="mb-0 small text-muted">Example: <span
                                    class="badge bg-info bg-opacity-10 text-danger">role create</span></p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 bg-white rounded border">
                            <h6 class="fw-semibold mb-2 text-primary">
                                <i class="bi bi-shield-plus me-2"></i>Child Permissions
                            </h6>
                            <p class="mb-1 small">Format: <code>[parent]->[child action]</code></p>
                            <p class="mb-0 small text-muted">Example: <span
                                    class="badge bg-info bg-opacity-10 text-danger">role->role
                                    create</span></p>
                        </div>
                    </div>
                </div>
                {{-- pemrission note --}}




                <form action="{{ route('permissions.store') }}" method="post" class="mt-3">
                    @csrf

                    <div class="mb-3">
                        <label for="
                    " class="form-label fw-medium">Permission Name</label>
                        <input type="text" class="form-control" name=" name">


                        @error('name')
                            <p class="text-danger fade-out" id="name-error">{{ $message }}</p>
                        @enderror

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                // Automatically fade out error messages after 2 seconds
                                const errorElements = document.querySelectorAll('.fade-out');
                                errorElements.forEach(element => {
                                    setTimeout(() => {
                                        element.style.display = 'none';
                                    }, 2000);
                                });
                            });
                        </script>

                        <style>
                            .fade-out {
                                animation: fadeOut 2s ease-in forwards;
                            }

                            @keyframes fadeOut {
                                0% {
                                    opacity: 1;
                                }

                                100% {
                                    opacity: 0;
                                    display: none;
                                }
                            }
                        </style>
                    </div>

                    <div class="text-end">

                        <button type="submit" class=" btn btn-primary">Add</button>

                    </div>

                </form>

            </div>
        </div>
    </div>


</x-layout>
