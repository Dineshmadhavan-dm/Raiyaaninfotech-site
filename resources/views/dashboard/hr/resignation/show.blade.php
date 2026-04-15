<x-layout>
    @section('title', 'Resignation Details')
    <div class="container-fluid py-4 px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-2">Resignation Details</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('resignationlist') }}" class="text-decoration-none text-muted">
                                <i class="bi bi-people-fill me-2"></i>Resignation List
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-primary" aria-current="page">Resignation Details</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('resignationlist') }}" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-arrow-left me-2"></i>Back to List
                </a>
                <a href="{{ route('resignation.edit', $resignation->resignation_id) }}" class="btn btn-primary">
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
                            <img src="{{ $resignation->employee->image ? asset('employee_images/' . $resignation->employee->image) : asset('images/admin_default.jpg') }}"
                                class="rounded-circle mb-3" width="120" height="120">
                            <h5 class="mb-1">{{ $resignation->employee->fullname ?? 'N/A' }}</h5>
                            <p class="text-muted mb-0">ID: {{ $resignation->employee->employee_id ?? 'N/A' }}</p>
                            <p class="text-muted">{{ $resignation->employee->email_company ?? 'Email not available' }}
                            </p>
                        </div>

                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted">Department:</span>
                            <span class="fw-medium">{{ $resignation->departmentRelation->dep_name ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted">Designation:</span>
                            <span class="fw-medium">{{ $resignation->designationRelation->des_name ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted">Date of Joining:</span>
                            <span
                                class="fw-medium">{{ $resignation->employee->dojprovision_from_date ? \Carbon\Carbon::parse($resignation->employee->dojprovision_from_date)->format('d-m-Y') : 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted">Resignation Date:</span>
                            <span
                                class="fw-medium">{{ \Carbon\Carbon::parse($resignation->date_of_resignation)->format('d-m-Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resignation Details -->
            <div class="col-md-8 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-primary text-white py-3">
                        <h6 class="mb-0"><i class="bi bi-exclamation-octagon me-2"></i>Resignation Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card bg-light border-0 mb-3">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted">Resignation Type</h6>
                                        <h4 class="{{ $resignation->is_voluntary ? 'text-danger' : 'text-warning' }}">
                                            {{ $resignation->is_voluntary ? 'Voluntary' : 'Involuntary' }}
                                        </h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light border-0 mb-3">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted">Rehire Status</h6>
                                        <h4
                                            class="{{ $resignation->can_be_rehired ? 'text-success' : 'text-danger' }}">
                                            {{ $resignation->can_be_rehired ? 'Eligible' : 'Not Eligible' }}
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card bg-light border-0 mb-3">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted">Notice Period</h6>
                                        <h4
                                            class="{{ $resignation->has_notice_period ? 'text-info' : 'text-secondary' }}">
                                            {{ $resignation->has_notice_period ? 'Served' : 'Not Served' }}
                                        </h4>
                                        @if ($resignation->has_notice_period && $resignation->notice_period)
                                            <h6 class="text-muted">Notice Period Duration</h6>
                                            <p class="mb-0  fw-bold small fs-6">{{ $resignation->notice_period }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light border-0 mb-3">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted">Last Working Day</h6>
                                        <h4 class="text-dark">
                                            {{ $resignation->last_working_day ? \Carbon\Carbon::parse($resignation->last_working_day)->format('d-m-Y') : 'N/A' }}
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($resignation->has_notice_period)
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between border-bottom py-2">
                                        <span class="text-muted">Notice Start Date:</span>
                                        <span
                                            class="fw-medium">{{ $resignation->notice_start_date ? \Carbon\Carbon::parse($resignation->notice_start_date)->format('d-m-Y') : 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between border-bottom py-2">
                                        <span class="text-muted">Notice End Date:</span>
                                        <span
                                            class="fw-medium">{{ $resignation->notice_end_date ? \Carbon\Carbon::parse($resignation->notice_end_date)->format('d-m-Y') : 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-between border-bottom py-2">
                                        <span class="text-muted">Notice Period Duration:</span>
                                        <span class="fw-medium">{{ $resignation->notice_period ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="text-muted mb-3">Reason for Resignation</h6>
                                <div class="bg-light p-3 rounded">
                                    @php
                                        $reasons = [
                                            0 => 'Other',
                                            1 => 'Career Growth Opportunity',
                                            2 => 'Higher Compensation',
                                            3 => 'Relocation',
                                            4 => 'Health Reasons',
                                            5 => 'Personal Reasons',
                                            6 => 'Work Environment',
                                            7 => 'Job Dissatisfaction',
                                        ];
                                    @endphp
                                    <p class="mb-1 fw-medium">
                                        {{ $reasons[$resignation->resignation_reason] ?? 'Unknown Reason' }}</p>
                                    @if ($resignation->resignation_reason == 0 && $resignation->reason_other_details)
                                        <p class="mb-0 mt-2">{{ $resignation->reason_other_details }}</p>
                                    @endif
                                    @if ($resignation->reason_details)
                                        <p class="mb-0 mt-2">{{ $resignation->reason_details }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if ($resignation->rehire_conditions)
                            <div class="mt-4">
                                <h6 class="text-muted mb-3">Rehire Conditions</h6>
                                <div class="bg-light p-3 rounded">
                                    <p class="mb-0">{{ $resignation->rehire_conditions }}</p>
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
                        @if ($resignation->resignation_document)
                            @php
                                $filePath = asset($resignation->resignation_document);
                                $extension = strtolower(
                                    pathinfo($resignation->resignation_document, PATHINFO_EXTENSION),
                                );
                                $fileName = basename($resignation->resignation_document);
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
                                            {{ $resignation->created_at->format('M d, Y') }}</small>
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
                                <p class="text-muted small">Supporting documents haven't been added to this resignation
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
                        @if ($resignation->employee_signature)
                            <div class="signature-container text-center">
                                <div class="signature-header mb-3">
                                    <h6 class="text-primary mb-1">Signed By</h6>
                                    <p class="mb-0 fw-medium">{{ $resignation->employee->fullname }}</p>
                                </div>

                                <div class="signature-preview mb-3">
                                    <div class="signature-box bg-light rounded p-3 mx-auto" style="max-width: 300px;">
                                        <img src="{{ asset($resignation->employee_signature) }}"
                                            alt="Employee Signature" class="img-fluid signature-image">
                                    </div>
                                </div>

                                <div class="signature-meta">
                                    <div class="badge bg-info bg-opacity-10 text-info mb-2">
                                        <i class="bi bi-check-circle-fill me-1"></i>Signed
                                    </div>
                                    <p class="text-muted small mb-0">
                                        Resigned on
                                        {{ \Carbon\Carbon::parse($resignation->date_of_resignation)->format('d-m-Y') }}
                                    </p>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="empty-state-icon mb-3">
                                    <i class="bi bi-pen display-4 text-light-emphasis"></i>
                                </div>
                                <h6 class="text-muted mb-2">No Employee Signature</h6>
                                <p class="text-muted small">Employee signature is not available for this resignation
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Management Signature -->
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-primary text-white py-3 d-flex align-items-center">
                        <i class="bi bi-pen me-2 fs-5"></i>
                        <h6 class="mb-0">Management Approval</h6>
                    </div>
                    <div class="card-body">
                        @if ($resignation->management_signature)
                            <div class="signature-container text-center">
                                <div class="signature-header mb-3">
                                    <h6 class="text-primary mb-1">Approved By</h6>
                                    <p class="mb-0 fw-medium">Management</p>
                                </div>

                                <div class="signature-preview mb-3">
                                    <div class="signature-box bg-light rounded p-3 mx-auto" style="max-width: 300px;">
                                        <img src="{{ asset($resignation->management_signature) }}"
                                            alt="Management Signature" class="img-fluid signature-image">
                                    </div>
                                </div>

                                <div class="signature-meta">
                                    <div class="badge bg-success bg-opacity-10 text-success mb-2">
                                        <i class="bi bi-check-circle-fill me-1"></i>Approved
                                    </div>
                                    <p class="text-muted small mb-0">
                                        Signed on {{ $resignation->created_at->format('d-m-Y') }}
                                    </p>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="empty-state-icon mb-3">
                                    <i class="bi bi-pen display-4 text-light-emphasis"></i>
                                </div>
                                <h6 class="text-muted mb-2">Awaiting Signature</h6>
                                <p class="text-muted small">Management approval is pending for this resignation</p>
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
