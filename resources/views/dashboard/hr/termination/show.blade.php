<x-layout>
    @section('title', 'Termination Details')
    <div class="container-fluid py-4 px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-2">Termination Details</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('terminationlist') }}" class="text-decoration-none text-muted">
                                <i class="bi bi-people-fill me-2"></i>Termination List
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-primary" aria-current="page">Termination Details</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('terminationlist') }}" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-arrow-left me-2"></i>Back to List
                </a>
                <a href="{{ route('termination.edit', $termination->termination_id) }}" class="btn btn-primary">
                    <i class="bi bi-pencil me-2"></i>Edit
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Employee Information -->
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-primary text-white py-3">
                        <h6 class="mb-0"><i class="bi bi-person-badge me-2"></i>Employee Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <img src="{{ $termination->employee->image ? asset('employee_images/' . $termination->employee->image) : asset('images/admin_default.jpg') }}"
                                class="rounded-circle mb-3" width="120" height="120">
                            <h5 class="mb-1">{{ $termination->employee->fullname ?? 'N/A' }}</h5>
                            <p class="text-muted mb-0">ID: {{ $termination->employee->employee_id ?? 'N/A' }}</p>
                            <p class="text-muted">{{ $termination->employee->email_company ?? 'Email not available' }}
                            </p>
                        </div>

                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted">Department:</span>
                            <span class="fw-medium">{{ $termination->departmentRelation->dep_name ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted">Designation:</span>
                            <span class="fw-medium">{{ $termination->designationRelation->des_name ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted">Date of Hire:</span>
                            <span class="fw-medium">{{ $termination->date_of_hire->format('d-m-Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted">Termination Date:</span>
                            <span class="fw-medium">{{ $termination->termination_date->format('d-m-Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Termination Details -->
            <div class="col-md-8 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-primary text-white py-3">
                        <h6 class="mb-0"><i class="bi bi-exclamation-octagon me-2"></i>Termination Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card bg-light border-0 mb-3">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted">Termination Type</h6>
                                        <h4
                                            class="{{ $termination->termination_type ? 'text-danger' : 'text-warning' }}">
                                            {{ $termination->termination_type ? 'Voluntary' : 'Involuntary' }}
                                        </h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light border-0 mb-3">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted">Rehire Status</h6>
                                        <h4
                                            class="{{ $termination->can_be_rehired ? 'text-success' : 'text-danger' }}">
                                            {{ $termination->can_be_rehired ? 'Eligible' : 'Not Eligible' }}
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between border-bottom py-2">
                                    <span class="text-muted">Proposed Designation:</span>
                                    <span
                                        class="fw-medium">{{ $termination->proposedDesignation->des_name ?? 'N/A' }}</span>
                                </div>
                                <div class="d-flex justify-content-between border-bottom py-2">
                                    <span class="text-muted">Employee Email:</span>
                                    <span class="fw-medium">{{ $termination->employee->email_company }}</span>
                                </div>
                            </div>

                        </div>

                        <div class="mt-4">
                            <h6 class="text-muted mb-3">Reason for Termination</h6>
                            <div class="bg-light p-3 rounded">
                                <p class="mb-0">{{ $termination->reason_for_termination }}</p>
                            </div>
                        </div>

                        @if ($termination->employee_statement)
                            <div class="mt-4">
                                <h6 class="text-muted mb-3">Employee Statement</h6>
                                <div class="bg-light p-3 rounded">
                                    <p class="mb-0">{{ $termination->employee_statement }}</p>
                                </div>
                            </div>
                        @endif

                        @if ($termination->rehire_conditions)
                            <div class="mt-4">
                                <h6 class="text-muted mb-3">Rehire Conditions</h6>
                                <div class="bg-light p-3 rounded">
                                    <p class="mb-0">{{ $termination->rehire_conditions }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Supporting Documents & Signatures -->
        <div class="row">
            <!-- Supporting Documents -->
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-primary text-white py-3 d-flex align-items-center">
                        <i class="bi bi-file-earmark-text me-2 fs-5"></i>
                        <h6 class="mb-0">Supporting Documents</h6>
                    </div>
                    <div class="card-body">
                        @if ($termination->supporting_documents)
                            @php
                                $filePath = asset($termination->supporting_documents);
                                $extension = strtolower(
                                    pathinfo($termination->supporting_documents, PATHINFO_EXTENSION),
                                );
                                $fileName = basename($termination->supporting_documents);
                            @endphp

                            <div class="document-preview-container">
                                <div class="document-card">
                                    <div class="document-icon">
                                        @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                            <i class="bi bi-image text-primary fs-1"></i>
                                        @elseif($extension === 'pdf')
                                            <i class="bi bi-file-earmark-pdf text-danger fs-1"></i>
                                        @elseif(in_array($extension, ['doc', 'docx']))
                                            <i class="bi bi-file-earmark-word text-primary fs-1"></i>
                                        @elseif(in_array($extension, ['xls', 'xlsx']))
                                            <i class="bi bi-file-earmark-excel text-success fs-1"></i>
                                        @else
                                            <i class="bi bi-file-earmark text-secondary fs-1"></i>
                                        @endif
                                    </div>

                                    <div class="document-info">
                                        <h6 class="document-title">{{ Str::limit($fileName, 25) }}</h6>
                                        <small class="text-muted">{{ strtoupper($extension) }} file •
                                            {{ $termination->created_at->format('M d, Y') }}</small>
                                    </div>

                                    <div class="document-actions">
                                        <div class="btn-group btn-group-sm">
                                            @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                                <a href="{{ $filePath }}" data-fancybox="gallery"
                                                    class="btn btn-outline-primary">
                                                    <i class="bi bi-eye me-1"></i>View
                                                </a>
                                            @else
                                                <a href="{{ $filePath }}" target="_blank"
                                                    class="btn btn-outline-primary">
                                                    <i class="bi bi-eye me-1"></i>View
                                                </a>
                                            @endif
                                            <a href="{{ $filePath }}" download class="btn btn-outline-secondary">
                                                <i class="bi bi-download me-1"></i>Download
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Image preview for image files -->
                                @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                    <div class="image-preview mt-3 text-center">
                                        <img src="{{ $filePath }}" alt="Document Preview"
                                            class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="empty-state-icon mb-3">
                                    <i class="bi bi-file-earmark-x display-4 text-light-emphasis"></i>
                                </div>
                                <h6 class="text-muted mb-2">No documents uploaded</h6>
                                <p class="text-muted small">Supporting documents haven't been added to this termination
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Employee Signature -->
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-primary text-white py-3 d-flex align-items-center">
                        <i class="bi bi-pen me-2 fs-5"></i>
                        <h6 class="mb-0">Employee Signature</h6>
                    </div>
                    <div class="card-body">
                        @if ($termination->employee_signature)
                            <div class="signature-container text-center">
                                <div class="signature-header mb-3">
                                    <h6 class="text-primary mb-1">Signed By</h6>
                                    <p class="mb-0 fw-medium">{{ $termination->employee->fullname }}</p>
                                </div>

                                <div class="signature-preview mb-3">
                                    <div class="signature-box bg-light rounded p-3 mx-auto" style="max-width: 300px;">
                                        <img src="{{ asset($termination->employee_signature) }}"
                                            alt="Employee Signature" class="img-fluid signature-image">
                                    </div>
                                </div>

                                <div class="signature-meta">
                                    <div class="badge bg-info bg-opacity-10 text-info mb-2">
                                        <i class="bi bi-check-circle-fill me-1"></i>Signed
                                    </div>
                                    <p class="text-muted small mb-0">
                                        Terminated on {{ $termination->termination_date->format('d-m-Y') }}
                                    </p>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="empty-state-icon mb-3">
                                    <i class="bi bi-pen display-4 text-light-emphasis"></i>
                                </div>
                                <h6 class="text-muted mb-2">No Employee Signature</h6>
                                <p class="text-muted small">Employee signature is not available for this termination
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Manager Signature -->
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-primary text-white py-3 d-flex align-items-center">
                        <i class="bi bi-pen me-2 fs-5"></i>
                        <h6 class="mb-0">Manager Approval</h6>
                    </div>
                    <div class="card-body">
                        @if ($termination->manager_signature)
                            <div class="signature-container text-center">
                                <div class="signature-header mb-3">
                                    <h6 class="text-primary mb-1">Approved By</h6>
                                    <p class="mb-0 fw-medium">{{ $termination->manager_name ?? 'Manager' }}</p>
                                </div>

                                <div class="signature-preview mb-3">
                                    <div class="signature-box bg-light rounded p-3 mx-auto" style="max-width: 300px;">
                                        <img src="{{ asset($termination->manager_signature) }}"
                                            alt="Manager Signature" class="img-fluid signature-image">
                                    </div>
                                </div>

                                <div class="signature-meta">
                                    <div class="badge bg-success bg-opacity-10 text-success mb-2">
                                        <i class="bi bi-check-circle-fill me-1"></i>Approved
                                    </div>
                                    <p class="text-muted small mb-0">
                                        Signed on {{ $termination->created_at->format('d-m-Y') }}
                                    </p>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="empty-state-icon mb-3">
                                    <i class="bi bi-pen display-4 text-light-emphasis"></i>
                                </div>
                                <h6 class="text-muted mb-2">Awaiting Signature</h6>
                                <p class="text-muted small">Manager approval is pending for this termination</p>
                                <div class="pending-indicator mt-3">
                                    <span class="badge bg-warning bg-opacity-15 text-warning">
                                        <i class="bi bi-clock-history me-1"></i>Pending
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <style>
            .document-card {
                display: flex;
                align-items: center;
                background: #f8f9fa;
                border-radius: 8px;
                padding: 15px;
                margin-bottom: 15px;
            }

            .document-icon {
                flex-shrink: 0;
                margin-right: 15px;
            }

            .document-info {
                flex-grow: 1;
                margin-right: 15px;
            }

            .document-title {
                margin-bottom: 3px;
                color: #344767;
            }

            .document-actions .btn {
                border-radius: 6px;
                font-size: 0.8rem;
            }

            .signature-box {
                border: 1px dashed #dee2e6;
            }

            .signature-image {
                filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
            }

            .empty-state-icon {
                opacity: 0.6;
            }

            /* Responsive adjustments */
            @media (max-width: 768px) {
                .document-card {
                    flex-direction: column;
                    text-align: center;
                }

                .document-icon {
                    margin-right: 0;
                    margin-bottom: 10px;
                }

                .document-info {
                    margin-right: 0;
                    margin-bottom: 10px;
                }

                .document-actions .btn-group {
                    flex-direction: column;
                }

                .document-actions .btn {
                    margin-bottom: 5px;
                }
            }
        </style>

        <!-- Add Fancybox for image lightbox (if needed) -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
        <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
        <script>
            Fancybox.bind("[data-fancybox]", {
                // Custom options
            });
        </script>
    </div>

    <style>
        .bg-primary {
            background: var(--ra-primary-set) !important;
        }

        .card {
            border-radius: 10px;
            overflow: hidden;
        }

        .card-header {
            border-radius: 10px 10px 0 0 !important;
        }

        .bg-light {
            background-color: #f8f9fa !important;
        }
    </style>
</x-layout>
