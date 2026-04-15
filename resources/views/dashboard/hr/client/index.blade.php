<x-layout>
    @section('title', 'Clients List')

    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-2">Clients Management</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 mt-2">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dhome') }}" class="text-decoration-none text-muted">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-primary" aria-current="page">Clients</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('clients.create') }}" class="btn btn-primary d-flex align-items-center">
                <i class="bi bi-plus-circle me-2"></i>Add New Client
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="dataTables_length">
                            <label>
                                Show
                                <select name="showEntries" id="showEntries" class="form-select form-select-sm" style="display: inline-block; width: auto;">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select> entries
                            </label>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover" id="clientsTable">
                        <thead class="table-light">
                            <tr>
                                <th width="50">#</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Created At</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="clientsTableBody">
                            @foreach ($clients as $index => $client)
                                <tr class="client-row" data-id="{{ $client->cl_id }}">
                                    <td class="row-number">{{ $index + 1 }}</td>
                                    <td>
                                        <img src="{{ $client->cl_image ? asset('client_images/' . $client->cl_image) : asset('images/admin_default.jpg') }}"
                                             alt="{{ $client->cl_name }}"
                                             class="rounded-circle client-image"
                                             width="45"
                                             height="45">
                                    </td>
                                    <td class="client-name">{{ $client->cl_name }}</td>
                                    <td class="client-email">{{ $client->cl_email }}</td>
                                    <td class="client-created">{{ $client->created_at->format('Y-m-d') }}</td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-info view-client-btn"
                                                    data-id="{{ $client->cl_id }}"
                                                    data-image="{{ $client->cl_image ? asset('client_images/' . $client->cl_image) : asset('images/admin_default.jpg') }}"
                                                    data-name="{{ $client->cl_name }}"
                                                    data-email="{{ $client->cl_email }}"
                                                    data-created="{{ $client->created_at->format('Y-m-d') }}"
                                                    data-updated="{{ $client->updated_at->format('Y-m-d') }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#viewModal">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <a href="{{ route('clients.edit', $client->cl_id) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger delete-client"
                                                    data-id="{{ $client->cl_id }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="row mt-3">
                    <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info" id="tableInfo" role="status" aria-live="polite">
                            Showing 1 to {{ min(5, count($clients)) }} of {{ count($clients) }} entries
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <div class="dataTables_paginate paging_simple_numbers" id="clientsTable_paginate">
                            <ul class="pagination justify-content-end mb-0" id="paginationContainer">
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewModalLabel">Client Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <img id="viewClientImage"
                             src=""
                             alt="Client Image"
                             class="rounded-circle mb-3"
                             width="120"
                             height="120">
                        <h4 id="viewClientName" class="mb-1"></h4>
                        <p class="text-muted" id="viewClientEmail"></p>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <p class="mb-2"><strong>Client ID:</strong></p>
                            <p class="mb-2"><strong>Created:</strong></p>
                            <p class="mb-0"><strong>Updated:</strong></p>
                        </div>
                        <div class="col-6">
                            <p class="mb-2" id="viewClientId"></p>
                            <p class="mb-2" id="viewClientCreated"></p>
                            <p class="mb-0" id="viewClientUpdated"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            let allClients = [];
            let currentPage = 1;
            let entriesPerPage = 5;
            let totalPages = 1;
            let maxVisiblePages = 5;

            function initClientsData() {
                allClients = [];
                $('.client-row').each(function() {
                    const client = {
                        id: $(this).data('id'),
                        image: $(this).find('.client-image').attr('src'),
                        name: $(this).find('.client-name').text(),
                        email: $(this).find('.client-email').text(),
                        created: $(this).find('.client-created').text(),
                        rowHtml: $(this).clone().wrap('<div>').parent().html()
                    };
                    allClients.push(client);
                });

                calculateTotalPages();
                renderTable();
                setupPagination();
                attachViewModalEvents();
            }

            function calculateTotalPages() {
                totalPages = Math.ceil(allClients.length / entriesPerPage);
            }

            function renderTable() {
                const tableBody = $('#clientsTableBody');
                tableBody.empty();

                const startIndex = (currentPage - 1) * entriesPerPage;
                const endIndex = Math.min(startIndex + entriesPerPage, allClients.length);

                for (let i = startIndex; i < endIndex; i++) {
                    const client = allClients[i];
                    const rowNumber = i + 1;

                    const rowHtml = client.rowHtml.replace(
                        'row-number">' + (i + 1) + '</td>',
                        'row-number">' + rowNumber + '</td>'
                    );

                    const $row = $(rowHtml);
                    tableBody.append($row);
                }

                updateTableInfo(startIndex, endIndex);
                attachViewModalEvents();
            }

            function updateTableInfo(startIndex, endIndex) {
                const totalEntries = allClients.length;
                const showingText = `Showing ${startIndex + 1} to ${endIndex} of ${totalEntries} entries`;
                $('#tableInfo').text(showingText);
            }

            function setupPagination() {
                const paginationContainer = $('#paginationContainer');
                paginationContainer.empty();

                if (totalPages <= 1) return;

                const prevButton = `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${currentPage - 1}">Previous</a>
                </li>`;
                paginationContainer.append(prevButton);

                let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
                let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

                if (endPage - startPage + 1 < maxVisiblePages) {
                    startPage = Math.max(1, endPage - maxVisiblePages + 1);
                }

                if (startPage > 1) {
                    paginationContainer.append(`<li class="page-item"><a class="page-link" href="#" data-page="1">1</a></li>`);
                    if (startPage > 2) {
                        paginationContainer.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
                    }
                }

                for (let i = startPage; i <= endPage; i++) {
                    const activeClass = i === currentPage ? 'active' : '';
                    paginationContainer.append(
                        `<li class="page-item ${activeClass}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`
                    );
                }

                if (endPage < totalPages) {
                    if (endPage < totalPages - 1) {
                        paginationContainer.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
                    }
                    paginationContainer.append(
                        `<li class="page-item"><a class="page-link" href="#" data-page="${totalPages}">${totalPages}</a></li>`
                    );
                }

                const nextButton = `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${currentPage + 1}">Next</a>
                </li>`;
                paginationContainer.append(nextButton);
            }

            function attachViewModalEvents() {
                $(document).off('click', '.view-client-btn').on('click', '.view-client-btn', function() {
                    const clientId = $(this).data('id');
                    const clientImage = $(this).data('image');
                    const clientName = $(this).data('name');
                    const clientEmail = $(this).data('email');
                    const clientCreated = $(this).data('created');
                    const clientUpdated = $(this).data('updated');

                    $('#viewClientImage').attr('src', clientImage);
                    $('#viewClientName').text(clientName);
                    $('#viewClientEmail').text(clientEmail);
                    $('#viewClientId').text(clientId);
                    $('#viewClientCreated').text(clientCreated);
                    $('#viewClientUpdated').text(clientUpdated);

                    $('#viewModal').modal('show');
                });
            }

            $(document).on('click', '#paginationContainer .page-link', function(e) {
                e.preventDefault();
                const page = $(this).data('page');
                if (page && page >= 1 && page <= totalPages && page !== currentPage) {
                    currentPage = page;
                    renderTable();
                    setupPagination();
                }
            });

            $('#showEntries').change(function() {
                entriesPerPage = parseInt($(this).val());
                currentPage = 1;
                calculateTotalPages();
                renderTable();
                setupPagination();
            });

            $(document).on('click', '.delete-client', function() {
                var clientId = $(this).data('id');
                var button = $(this);
                var row = button.closest('tr');
                var rowIndex = $('.client-row').index(row);

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ url("dashboard/employees/clients") }}/' + clientId,
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if(response.status) {
                                    allClients = allClients.filter(client => client.id != clientId);

                                    if (allClients.length === 0) {
                                        $('#clientsTableBody').html('<tr><td colspan="6" class="text-center">No clients found</td></tr>');
                                        $('#tableInfo').text('Showing 0 to 0 of 0 entries');
                                        $('#paginationContainer').empty();
                                    } else {
                                        calculateTotalPages();
                                        if (currentPage > totalPages) {
                                            currentPage = totalPages;
                                        }
                                        renderTable();
                                        setupPagination();
                                    }

                                    Swal.fire(
                                        'Deleted!',
                                        'Client has been deleted.',
                                        'success'
                                    );
                                }
                            },
                            error: function() {
                                Swal.fire(
                                    'Error!',
                                    'Failed to delete client.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

            initClientsData();
        });
    </script>
</x-layout>
