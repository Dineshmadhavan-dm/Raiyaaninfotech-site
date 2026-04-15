{{-- @php
    $hasSettingAccess =
        auth()->user()->can('setting view') ||
        auth()->user()->can('setting->applogo view') ||
        auth()->user()->can('setting->favicon view') ||
        auth()->user()->can('setting->sitecontrol view') ||
        auth()->user()->can('setting->themecolor view');

@endphp --}}
<x-tabnav>
    @section('title', 'Branches')
    <div class="main-container ">
        <!-- Vertical Tabs Navigation -->
        <div class="vertical-tabs ">
            {{-- @if ($hasSettingAccess) --}}
            <h6 class="fw-bold text-center mb-3 head">Other</h6>



            <a href="{{ route('designation') }}" onclick="switchTab('tab1', event)">
                <div class="tab-header {{ request()->routeIs('designation') ? 'active' : '' }}">
                    <h1><i class="bi bi-person-badge me-2"></i>Designation</h1>
                </div>
            </a>

            <a href="{{ route('department') }}" onclick="switchTab('tab2', event)">
                <div class="tab-header {{ request()->routeIs('department') ? 'active' : '' }}">
                    <h2><i class="bi bi-building me-2"></i>Department</h2>
                </div>
            </a>

            <a href="{{ route('branch') }}" onclick="switchTab('tab3', event)">
                <div class="tab-header {{ request()->routeIs('branch') ? 'active' : '' }}">
                    <h3><i class="bi bi-diagram-3 me-2"></i>Branches</h3>
                </div>
            </a>

            <a href="{{ route('qualification') }}" onclick="switchTab('tab4', event)">
                <div class="tab-header {{ request()->routeIs('qualification') ? 'active' : '' }}">
                    <h3><i class="bi bi-mortarboard me-2"></i>Qualification</h3>
                </div>
            </a>

            <a href="{{ route('bloodgroup') }}" onclick="switchTab('tab5', event)">
                <div class="tab-header {{ request()->routeIs('bloodgroup') ? 'active' : '' }}">
                    <h3><i class="bi bi-droplet-half me-2"></i>Bloodgroup</h3>
                </div>
            </a>

            <a href="{{ route('jobtype') }}" onclick="switchTab('tab6', event)">
                <div class="tab-header {{ request()->routeIs('jobtype') ? 'active' : '' }}">
                    <h3><i class="bi bi-briefcase me-2"></i>Job Type</h3>
                </div>
            </a>

            <a href="{{ route('relationship') }}" onclick="switchTab('tab7', event)">
                <div class="tab-header {{ request()->routeIs('relationship') ? 'active' : '' }}">
                    <h3><i class="bi bi-people me-2"></i>Relation</h3>
                </div>
            </a>

        </div>

        <!-- Tab Content Areas -->
        <div class="tab-content">
            <div id="tab3" class="tab-pane {{ request()->routeIs('branch') ? 'active' : '' }}">





                <!--  -->
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
                                    <li class="breadcrumb-item active text-primary" aria-current="page">Branch</li>
                                </ol>
                            </nav>
                        </div>
                    </div>

                    <x-message />

                    {{-- content area --}}
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <form
                                        action="{{ isset($editbranch) ? route('branchupdate', $editbranch->branch_id) : route('branchpost') }}"
                                        method="post">
                                        @csrf
                                        @if (isset($editbranch))
                                            @method('PUT')
                                        @endif

                                        <div class="form-group mb-3">
                                            <label for="branch_name" class="form-label">Branch Name</label>
                                            <input type="text" class="form-control" id="branch_name"
                                                name="branch_name"
                                                value="{{ isset($editbranch) ? $editbranch->branch_name : old('branch_name') }}">
                                            @error('branch_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="d-flex justify-content-end gap-2">
                                            @if (isset($editbranch))
                                                <a href="{{ route('branch') }}"
                                                    class="btn btn-secondary px-4">Cancel</a>
                                            @endif

                                            <button type="submit" class="btn btn-primary px-4" id="submit_form">
                                                {{ isset($editbranch) ? 'Update' : 'Add' }}
                                            </button>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mt-4">
                        <div class="card-body p-4">
                            <div class="table-responsive">
                                <table id="basic-datatables" class="display table table-bordered table-hover">
                                    <thead class="bg-light rounded text-center">
                                        <tr>
                                            <th>#</th>
                                            <th>Branch Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($branch as $it)
                                            <tr class="text-center ">
                                                <td class="align-middle">{{ $loop->iteration }}</td>
                                                <td class="">{{ $it->branch_name }}</td>
                                                <td class="align-middle">
                                                    <div class="d-flex justify-content-center gap-2">

                                                        <a href="{{ route('branchedit', $it->branch_id) }}"
                                                            class="btn btn-sm btn-icon btn-soft-warning  hover-grow"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="Edit branch">
                                                            <i class="bi bi-pencil "></i>
                                                        </a>

                                                        <form action="{{ route('branchdelete', $it->branch_id) }}"
                                                            method="POST" class="delete-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-sm btn-icon btn-soft-danger  hover-grow"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="Delete branch">
                                                                <i class="bi bi-trash "></i>
                                                            </button>
                                                        </form>


                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <x-actionbtn />
                <script>
                    $(document).ready(function() {
                        var table = $('#basic-datatables').DataTable({
                            responsive: true,
                            paging: true,
                            searching: true,
                            ordering: true,
                        });
                    });
                </script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {

                        const deleteForms = document.querySelectorAll('.delete-form');

                        deleteForms.forEach(form => {
                            form.addEventListener('submit', function(e) {
                                e.preventDefault();

                                Swal.fire({

                                    text: "Are you sure you want to delete this branch?'",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Yes, delete it!'
                                }).then((result) => {
                                    if (result.isConfirmed) {

                                        form.submit();
                                    }
                                });
                            });
                        });
                    });
                </script>
                <!--  -->






            </div>
        </div>






    </div>

</x-tabnav>
