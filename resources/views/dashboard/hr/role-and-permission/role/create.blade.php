<x-layout>


    @section('title', 'Role')

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
                        <li class="breadcrumb-item active text-primary" aria-current="page">Role Create</li>
                    </ol>
                </nav>
            </div>
        </div>

        <x-message />

        {{-- content area --}}


        <div class="card border-0 shadow-sm mt-4">


            <div class=" text-end p-4">
                <a href="{{ route('roles.index') }}" class=" btn btn-outline-primary">
                    <i class="bi bi-arrow-left-circle me-2"></i>Back</a>

            </div>

            <div class="card-body p-4">


                <form action="{{ route('roles.store') }}" method="post">
                    @csrf

                    <div class="mb-3">
                        <label for="
                    " class="form-label fw-medium">Role Name</label>
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
