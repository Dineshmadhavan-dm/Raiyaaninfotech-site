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
                        <li class="breadcrumb-item active text-primary" aria-current="page">Role Show</li>
                    </ol>
                </nav>
            </div>
        </div>

        <x-message />

        {{-- content area --}}


        <div class="card border-0 shadow-sm mt-4">

            <div class="card-header">

                <h5>Role</h5>

                <div class=" text-end">
                    <a href="{{ route('roles.index') }}" class=" btn btn-info">Back</a>
                </div>
            </div>
        </div>
        <div class="card-body p-4">


            <div class="row">
                <div class="col-3">Role Name</div>
                <div class="col-3">{{ $role->name }}</div>
            </div>




        </div>
    </div>
    </div>


</x-layout>
