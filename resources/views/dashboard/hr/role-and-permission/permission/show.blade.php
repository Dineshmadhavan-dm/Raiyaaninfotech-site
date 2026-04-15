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
                        <li class="breadcrumb-item active text-primary" aria-current="page">Permission Show</li>
                    </ol>
                </nav>
            </div>
        </div>

        <x-message />

        {{-- content area --}}


        <div class="card border-0 shadow-sm mt-4">

            <div class="card-header">

                <h5>Permission</h5>

                <div class=" text-end">
                    <a href="{{ route('permissions.index') }}" class=" btn btn-info">Back</a>
                </div>
            </div>
        </div>
        <div class="card-body p-4">


            <div class="row">
                <div class="col-3">Permission Name</div>
                <div class="col-3">{{ $permission->name }}</div>
            </div>




        </div>
    </div>
    </div>


</x-layout>
