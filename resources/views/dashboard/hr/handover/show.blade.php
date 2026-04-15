<x-layout>
    @section('title', 'Handover Details')
    <div class="container-fluid py-4 px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-2">Handover Details</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('handoverlist') }}" class="text-decoration-none text-muted">
                                <i class="bi bi-arrow-left-right me-2"></i>Handover List
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-primary" aria-current="page">Handover Details</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('handoverlist') }}" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-arrow-left me-2"></i>Back to List
                </a>
                <a href="{{ route('handover.edit', $handover->handover_id) }}" class="btn btn-primary">
                    <i class="bi bi-pencil me-2"></i>Edit
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Handover Employee Information -->
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-primary text-white py-3">
                        <h6 class="mb-0"><i class="bi bi-person-up me-2"></i>Handover Employee</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <img src="{{ $handover->handoverEmployee->image ? asset('employee_images/' . $handover->handoverEmployee->image) : asset('images/admin_default.jpg') }}"
                                class="rounded-circle mb-3" width="120" height="120">
                            <h5 class="mb-1">{{ $handover->handoverEmployee->fullname ?? 'N/A' }}</h5>
                            <p class="text-muted mb-0">ID: {{ $handover->handoverEmployee->employee_id ?? 'N/A' }}</p>
                            <p class="text-muted">
                                {{ $handover->handoverEmployee->email_company ?? 'Email not available' }}</p>
                        </div>

                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted">Department:</span>
                            <span
                                class="fw-medium">{{ $handover->handoverDepartmentRelation->dep_name ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted">Designation:</span>
                            <span
                                class="fw-medium">{{ $handover->handoverDesignationRelation->des_name ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted">Email:</span>
                            <span class="fw-medium">{{ $handover->handoverEmployee->email_company ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Takeover Employee Information -->
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-primary text-white py-3">
                        <h6 class="mb-0"><i class="bi bi-person-down me-2"></i>Takeover Employee</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <img src="{{ $handover->takeoverEmployee->image ? asset('employee_images/' . $handover->takeoverEmployee->image) : asset('images/admin_default.jpg') }}"
                                class="rounded-circle mb-3" width="120" height="120">
                            <h5 class="mb-1">{{ $handover->takeoverEmployee->fullname ?? 'N/A' }}</h5>
                            <p class="text-muted mb-0">ID: {{ $handover->takeoverEmployee->employee_id ?? 'N/A' }}</p>
                            <p class="text-muted">
                                {{ $handover->takeoverEmployee->email_company ?? 'Email not available' }}</p>
                        </div>

                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted">Department:</span>
                            <span
                                class="fw-medium">{{ $handover->takeoverDepartmentRelation->dep_name ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted">Designation:</span>
                            <span
                                class="fw-medium">{{ $handover->takeoverDesignationRelation->des_name ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted">Email:</span>
                            <span class="fw-medium">{{ $handover->takeoverEmployee->email_company ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Handover Details -->
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-primary text-white py-3">
                        <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Handover Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted">Handover Date:</span>
                            <span
                                class="fw-medium">{{ \Carbon\Carbon::parse($handover->handover_date)->format('d-m-Y') }}</span>
                        </div>

                        <div class="mt-3">
                            <h6 class="text-muted mb-3">Reason for Handover</h6>
                            <div class="bg-light p-3 rounded">
                                @php
                                    $reasons = [
                                        0 => 'Other',
                                        1 => 'Vacation',
                                        2 => 'End of Employment',
                                        3 => 'Transfer',
                                    ];
                                @endphp
                                <p class="mb-1 fw-medium">
                                    {{ $reasons[$handover->reason] ?? 'Unknown Reason' }}
                                </p>
                                @if ($handover->reason == 0 && $handover->reason_other)
                                    <p class="mb-0 mt-2">{{ $handover->reason_other }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tasks Section -->
        @if ($handover->tasks && count(json_decode($handover->tasks, true)) > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-primary text-white py-3">
                            <h6 class="mb-0"><i class="bi bi-list-task me-2"></i>Tasks Handover</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>S.no</th>
                                            <th>Task No</th>
                                            <th>Task Name</th>
                                            <th>Priority</th>
                                            <th>Status</th>
                                            <th>Due Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (json_decode($handover->tasks, true) as $task)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $task['task_no'] ?? 'N/A' }}</td>
                                                <td>{{ $task['task_name'] ?? 'N/A' }}</td>
                                                <td>
                                                    @php
                                                        $priorityClass = 'badge bg-secondary';
                                                        if (isset($task['priority'])) {
                                                            switch (strtolower($task['priority'])) {
                                                                case 'high':
                                                                    $priorityClass = 'badge bg-danger';
                                                                    break;
                                                                case 'medium':
                                                                    $priorityClass = 'badge bg-warning';
                                                                    break;
                                                                case 'low':
                                                                    $priorityClass = 'badge bg-info';
                                                                    break;
                                                            }
                                                        }
                                                    @endphp
                                                    <span
                                                        class="{{ $priorityClass }} p-1">{{ $task['priority'] ?? 'N/A' }}</span>
                                                </td>
                                                <td>
                                                    @php
                                                        $statusClass = 'badge bg-secondary';
                                                        if (isset($task['status'])) {
                                                            switch (strtolower($task['status'])) {
                                                                case 'completed':
                                                                    $statusClass = 'badge bg-success';
                                                                    break;
                                                                case 'in progress':
                                                                    $statusClass = 'badge bg-primary';
                                                                    break;
                                                                case 'pending':
                                                                    $statusClass = 'badge bg-warning';
                                                                    break;
                                                            }
                                                        }
                                                    @endphp
                                                    <span
                                                        class="{{ $statusClass }} p-1">{{ $task['status'] ?? 'N/A' }}</span>
                                                </td>
                                                <td>{{ isset($task['due_date']) ? \Carbon\Carbon::parse($task['due_date'])->format('d-m-Y') : 'N/A' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Documents & Signatures -->
        <div class="row">
            <!-- Documents -->
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-primary text-white py-3 d-flex align-items-center">
                        <i class="bi bi-file-earmark-text me-2 fs-5"></i>
                        <h6 class="mb-0">Supporting Documents</h6>
                    </div>
                    <div class="card-body">
                        <!-- Other Documents -->
                        @if ($handover->other_documents)
                            <h6 class="text-muted mb-3">Other Documents</h6>
                            @php
                                $filePath = asset($handover->other_documents);
                                $extension = strtolower(pathinfo($handover->other_documents, PATHINFO_EXTENSION));
                                $fileName = basename($handover->other_documents);
                            @endphp

                            <div class="document-preview-container mb-4">
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
                                            {{ $handover->created_at->format('M d, Y') }}</small>
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

                                @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                    <div class="image-preview mt-3 text-center">
                                        <img src="{{ $filePath }}" alt="Document Preview"
                                            class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Resignation Documents -->
                        @if ($handover->resignation_documents)
                            <h6 class="text-muted mb-3">Resignation Documents</h6>
                            @php
                                $filePath = asset($handover->resignation_documents);
                                $extension = strtolower(pathinfo($handover->resignation_documents, PATHINFO_EXTENSION));
                                $fileName = basename($handover->resignation_documents);
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
                                            {{ $handover->created_at->format('M d, Y') }}</small>
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

                                @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                    <div class="image-preview mt-3 text-center">
                                        <img src="{{ $filePath }}" alt="Document Preview"
                                            class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if (!$handover->other_documents && !$handover->resignation_documents)
                            <div class="text-center py-5">
                                <div class="empty-state-icon mb-3">
                                    <i class="bi bi-file-earmark-x display-4 text-light-emphasis"></i>
                                </div>
                                <h6 class="text-muted mb-2">No documents uploaded</h6>
                                <p class="text-muted small">Supporting documents haven't been added to this handover
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Handover Employee Signature -->
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-primary text-white py-3 d-flex align-items-center">
                        <i class="bi bi-pen me-2 fs-5"></i>
                        <h6 class="mb-0">Handover Employee Signature</h6>
                    </div>
                    <div class="card-body">
                        @if ($handover->handover_signature)
                            <div class="signature-container text-center">
                                <div class="signature-header mb-3">
                                    <h6 class="text-primary mb-1">Signed By</h6>
                                    <p class="mb-0 fw-medium">{{ $handover->handoverEmployee->fullname }}</p>
                                </div>

                                <div class="signature-preview mb-3">
                                    <div class="signature-box bg-light rounded p-3 mx-auto" style="max-width: 300px;">
                                        <img src="{{ asset($handover->handover_signature) }}"
                                            alt="Handover Employee Signature" class="img-fluid signature-image">
                                    </div>
                                </div>

                                <div class="signature-meta">
                                    <div class="badge bg-info bg-opacity-10 text-info mb-2">
                                        <i class="bi bi-check-circle-fill me-1"></i>Signed
                                    </div>
                                    <p class="text-muted small mb-0">
                                        Handover on
                                        {{ \Carbon\Carbon::parse($handover->handover_date)->format('d-m-Y') }}
                                    </p>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="empty-state-icon mb-3">
                                    <i class="bi bi-pen display-4 text-light-emphasis"></i>
                                </div>
                                <h6 class="text-muted mb-2">No Handover Signature</h6>
                                <p class="text-muted small">Handover employee signature is not available</p>
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

            <!-- Takeover Employee Signature -->
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-primary text-white py-3 d-flex align-items-center">
                        <i class="bi bi-pen me-2 fs-5"></i>
                        <h6 class="mb-0">Takeover Employee Signature</h6>
                    </div>
                    <div class="card-body">
                        @if ($handover->takeover_signature)
                            <div class="signature-container text-center">
                                <div class="signature-header mb-3">
                                    <h6 class="text-primary mb-1">Signed By</h6>
                                    <p class="mb-0 fw-medium">{{ $handover->takeoverEmployee->fullname }}</p>
                                </div>

                                <div class="signature-preview mb-3">
                                    <div class="signature-box bg-light rounded p-3 mx-auto" style="max-width: 300px;">
                                        <img src="{{ asset($handover->takeover_signature) }}"
                                            alt="Takeover Employee Signature" class="img-fluid signature-image">
                                    </div>
                                </div>

                                <div class="signature-meta">
                                    <div class="badge bg-success bg-opacity-10 text-success mb-2">
                                        <i class="bi bi-check-circle-fill me-1"></i>Signed
                                    </div>
                                    <p class="text-muted small mb-0">
                                        Taken over on
                                        {{ \Carbon\Carbon::parse($handover->handover_date)->format('d-m-Y') }}
                                    </p>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="empty-state-icon mb-3">
                                    <i class="bi bi-pen display-4 text-light-emphasis"></i>
                                </div>
                                <h6 class="text-muted mb-2">No Takeover Signature</h6>
                                <p class="text-muted small">Takeover employee signature is not available</p>
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

        <!-- Add Fancybox for image lightbox (if needed) -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
        <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
        <script>
            Fancybox.bind("[data-fancybox]", {
                // Custom options
            });
        </script>
    </div>
</x-layout>
