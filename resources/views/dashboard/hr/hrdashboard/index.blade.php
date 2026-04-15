<x-layout>

    @section('title', 'Dashboard')

    <div class="container-fluid p-4">

        <x-message />


        <div class="row g-4">


            <nav aria-label="breadcrumb" class="ms-1 mt-5">
                <ol class="breadcrumb bg-transparent p-0">
                    {{-- <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-primary"><i
                                class="bi bi-house-door me-1"></i>
                            Home</a></li> --}}
                    <li class="breadcrumb-item active text-muted db" aria-current="page ">Dashboard</li>
                </ol>
            </nav>


            <!-- Admins Card -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                    <div class="card-body p-4 position-relative">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-2 fw-semibold cardtx">No of Admins</p>
                                <h2 class="fw-bold mb-0">{{ $admincount->count() }}</h2>
                            </div>
                            <div class="bg-primary bg-opacity-10 p-3 rounded-3">
                                <i class="bi bi-person-check fs-4 text-primary"></i>
                            </div>
                        </div>
                        <div class="position-absolute bottom-0 end-0 w-100 overflow-hidden" style="height: 8px;">
                            <div class="bg-primary w-100 h-100 rounded-top-3" style="opacity: 0.3;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employees Card -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                    <div class="card-body p-4 position-relative">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-2 fw-semibold cardtx">No of Employees</p>
                                <h2 class="fw-bold mb-0">{{ $employeecount->count() }}</h2>

                            </div>
                            <div class="bg-success bg-opacity-10 p-3 rounded-3">
                                <i class="bi bi-people fs-4 text-success"></i>
                            </div>
                        </div>
                        <div class="position-absolute bottom-0 end-0 w-100 overflow-hidden" style="height: 8px;">
                            <div class="bg-success w-100 h-100 rounded-top-3" style="opacity: 0.3;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Platforms Card -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                    <div class="card-body p-4 position-relative">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-2 fw-semibold cardtx">No. of Branches</p>
                                <h2 class="fw-bold mb-0">{{ $branchcount->count() }}</h2>

                            </div>
                            <div class="bg-info bg-opacity-10 p-3 rounded-3">
                                <i class="bi bi-layers fs-4 text-info"></i>
                            </div>
                        </div>
                        <div class="position-absolute bottom-0 end-0 w-100 overflow-hidden" style="height: 8px;">
                            <div class="bg-info w-100 h-100 rounded-top-3" style="opacity: 0.3;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Departments Card -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                    <div class="card-body p-4 position-relative">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-2 fw-semibold cardtx">No of Departments</p>
                                <h2 class="fw-bold mb-0">{{ $depcount->count() }}</h2>

                            </div>
                            <div class="bg-primary bg-opacity-10 p-3 rounded-3">
                                <i class="bi bi-building fs-4 text-primary"></i>
                            </div>
                        </div>
                        <div class="position-absolute bottom-0 end-0 w-100 overflow-hidden" style="height: 8px;">
                            <div class="bg-primary w-100 h-100 rounded-top-3" style="opacity: 0.3;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>



</x-layout>
