<x-layout>
    @section('title', 'Role Management')

    <div class="container-fluid p-4 animate__animated animate__fadeIn">
        <!-- Header & Breadcrumb -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dhome') }}" class="text-decoration-none text-muted transition-all">
                                <i class="bi bi-people me-2"></i>Employment
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-primary" aria-current="page">Role Management</li>
                    </ol>
                </nav>
            </div>
            <div class="text-end">

                <a href="{{ route('roles.create') }}" class="btn btn-outline-primary hover-scale">
                    <i class="bi bi-plus-lg me-1"></i> Add Role
                </a>

            </div>

        </div>

        <x-message />

        <div class="card border-0 shadow-sm mt-4 animate__animated animate__fadeInUp">
            <div class="card-header bg-white border-0 py-3">
                <div class="d-flex justify-content-end align-items-center">





                </div>
            </div>



            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered  align-middle" id="basic-datatables">
                        <thead class="bg-light-primary shadow-sm text-center">
                            <tr>
                                <th scope="col" class="fw-semibold ps-4 rounded-start">ID</th>
                                <th scope="col" class="fw-semibold">Role Name</th>
                                <th scope="col" class="fw-semibold pe-4 rounded-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody class=" text-center align-middle">
                            @foreach ($role as $index => $per)
                                {{-- @if ($per->name == 'Super admin')
                                @else --}}
                                <tr class="animate-float-in" style="animation-delay: {{ $index * 0.05 }}s">
                                    <td class="ps-4 fw-medium text-muted">{{ $per->id }}</td>
                                    <td>
                                        <span class="badge bg-primary-soft rounded-pill px-3 py-2 text-primary">
                                            <i class="bi bi-person-badge me-2"></i>


                                            {{ $per->name }}




                                        </span>
                                    </td>
                                    <td class="pe-4 text-nowrap">
                                        <div class="d-flex gap-2 align-items-center justify-content-center">
                                            <!-- Manage Permissions -->
                                            @can('role->managerole view')
                                                <a href="{{ route('addpermission', $per->id) }}"
                                                    class="btn btn-sm btn-icon btn-soft-primary  hover-grow"
                                                    data-bs-toggle="tooltip" title="Manage Permissions">
                                                    <i class="bi bi-shield-lock"></i>
                                                </a>
                                            @endcan

                                            <!-- View Details -->
                                            {{-- <a href="{{ route('roles.show', $per->id) }}"
                                                class="btn btn-sm btn-icon btn-soft-info  hover-grow"
                                                data-bs-toggle="tooltip" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a> --}}

                                            <!-- Edit Role -->
                                            @can('role->role edit')
                                                <a href="{{ route('roles.edit', $per->id) }}"
                                                    class="btn btn-sm btn-icon btn-soft-warning  hover-grow"
                                                    data-bs-toggle="tooltip" title="Edit Role">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            @endcan
                                            @can('role->role delete')
                                                <!-- Delete Role -->
                                                <form action="{{ route('roles.destroy', $per->id) }}" method="post"
                                                    class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                        class="btn btn-sm btn-icon btn-soft-danger mt-3  hover-grow delete-btn"
                                                        data-bs-toggle="tooltip" title="Delete Role"
                                                        data-role-name="{{ $per->name }}">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endcan

                                        </div>
                                    </td>
                                </tr>
                                {{-- @endif --}}
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <x-actionbtn />

            {{-- <style>
                /* Animation Effects */
                .animate-float-in {
                    animation: floatIn 0.2s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
                    opacity: 0;
                    transform: translateY(10px);
                }

                @keyframes floatIn {
                    0% {
                        opacity: 0;
                        transform: translateY(10px);
                    }

                    100% {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                /* Hover Effects */
                .hover-grow {
                    transition: all 0.2s ease;
                }

                .hover-grow:hover {
                    transform: scale(1.15);
                }

                /* Modern Table Styling */
                .table-hover tbody tr {
                    transition: all 0.3s ease;
                    border-radius: 8px;
                }

                .table-hover tbody tr:hover {
                    background-color: rgba(59, 130, 246, 0.05);
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
                    transform: translateY(-2px);
                }

                .bg-light-primary {
                    background-color: rgba(59, 130, 246, 0.1);
                }

                .bg-primary-soft {
                    background-color: rgba(59, 130, 246, 0.1);
                    color: var(--ra-primary-set);
                    font-size: .8em;
                }

                .btn-icon {
                    width: 34px;
                    height: 34px;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                }

                .btn-soft-primary {
                    background-color: rgba(59, 130, 246, 0.1);

                    border: none;
                }

                .btn-soft-info {
                    background-color: rgba(6, 182, 212, 0.1);
                    color: #06b6d4;
                    border: none;
                }

                .btn-soft-warning {
                    background-color: rgba(234, 179, 8, 0.1);
                    color: #eab308;
                    border: none;
                }

                .btn-soft-danger {
                    background-color: rgba(239, 68, 68, 0.1);
                    color: #ef4444;
                    border: none;
                }

                .rounded-circle {
                    border-radius: 50% !important;
                }
            </style> --}}

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Initialize tooltips
                    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                    tooltipTriggerList.map(function(tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl);
                    });

                    // Delete confirmation with SweetAlert
                    document.querySelectorAll('.delete-btn').forEach(button => {
                        button.addEventListener('click', function() {
                            const roleName = this.getAttribute('data-role-name');
                            const form = this.closest('.delete-form');

                            Swal.fire({
                                title: 'Delete Role',
                                html: `Are you sure you want to delete <strong>${roleName}</strong>?`,
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#ef4444',
                                cancelButtonColor: '#6b7280',
                                confirmButtonText: 'Yes, delete it',
                                cancelButtonText: 'Cancel',
                                backdrop: 'rgba(0,0,0,0.4)',
                                showClass: {
                                    popup: 'animate__animated animate__fadeInDown animate__faster'
                                },
                                hideClass: {
                                    popup: 'animate__animated animate__fadeOutUp animate__faster'
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    form.submit();
                                }
                            });
                        });
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
