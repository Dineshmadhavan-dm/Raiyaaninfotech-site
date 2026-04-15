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
                        <li class="breadcrumb-item active text-primary" aria-current="page">Permission</li>
                    </ol>
                </nav>
            </div>

            <div class="text-end">

                @can('permission create')
                    <a href="{{ route('permissions.create') }}" class=" btn btn-outline-primary"> <i
                            class="bi bi-plus-lg me-1"></i> Add Permission</a>
                @endcan

            </div>
        </div>

        <x-message />

        {{-- content area --}}


        <div class="card border-0 mt-4">



            <div class="card-header bg-white border-0 py-3">
                <div class="d-flex justify-content-end align-items-center">



                </div>

            </div>





            <div class="card-body p-4">







                <h5 class="alert-heading mb-2"> <i class="bi bi-info-circle-fill me-3 text-danger"></i>Permission
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




                <div class="table-responsive mt-2">
                    <table class="table table-hover table-bordered align-middle" id="basic-datatables">
                        <thead class="bg-light-primary text-center">
                            <tr>
                                <th scope="col" class="ps-4 rounded-start">ID</th>
                                <th scope="col">Permission Name</th>
                                <th scope="col" class="pe-4 rounded-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="  text-center  align-middle">
                            @foreach ($permission as $index => $per)
                                <tr class="animate-fade-in" style="animation-delay: {{ $index * 0.05 }}s">
                                    <td class="ps-4 fw-medium text-muted">{{ $per->id }}</td>
                                    <td>
                                        <span class="badge bg-primary-soft rounded-pill px-3 py-2">
                                            <i class="bi bi-shield-check me-2"></i>{{ $per->name }}
                                        </span>
                                    </td>
                                    <td class="pe-4">
                                        <div class="d-flex gap-2  justify-content-center">
                                            {{-- <a href="{{ route('permissions.show', $per->id) }}"
                                                class="btn btn-sm btn-icon btn-soft-info  hover-grow"
                                                data-bs-toggle="tooltip" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a> --}}

                                            <a href="{{ route('permissions.edit', $per->id) }}"
                                                class="btn btn-sm btn-icon btn-soft-warning  hover-grow"
                                                data-bs-toggle="tooltip" title="Edit Permission">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>

                                            <form action="{{ route('permissions.destroy', $per->id) }}" method="post"
                                                class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="btn btn-sm btn-icon btn-soft-danger   hover-grow delete-btn"
                                                    data-bs-toggle="tooltip" title="Delete Permission"
                                                    data-permission-name="{{ $per->name }}">
                                                    <i class="bi bi-trash"></i>
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

        <x-actionbtn />

        <script>
            document.addEventListener('DOMContentLoaded', function() {


                // Delete confirmation with SweetAlert
                document.querySelectorAll('.delete-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const permissionName = this.getAttribute('data-permission-name');
                        const form = this.closest('.delete-form');

                        Swal.fire({
                            title: 'Confirm Deletion',
                            html: `Are you sure you want to delete <strong>${permissionName}</strong>?`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, delete it!',
                            backdrop: 'rgba(0,0,0,0.4)'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                });
                // Initialize tooltips
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            });


            $(document).ready(function() {

                var table = $('#basic-datatables').DataTable({
                    responsive: true,
                    paging: true,
                    searching: true,
                    ordering: true,

                });
            });
        </script>





    </div>
    </div>


</x-layout>
