<x-layout>
    @section('title', 'Probation Details')
    <div class="container-fluid py-4 px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-2">Probation Details</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('probationlist') }}" class="text-decoration-none text-muted">
                                <i class="bi bi-people-fill me-2"></i>Probation List
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-primary" aria-current="page">Probation Details</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('probationlist') }}" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-arrow-left me-2"></i>Back to List
                </a>
                <a href="{{ route('probation.edit', $probation->probation_id) }}" class="btn btn-primary">
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
                            <img src="{{ $probation->employee->image ? asset('employee_images/' . $probation->employee->image) : asset('images/admin_default.jpg') }}"
                                class="rounded-circle mb-3" width="120" height="120">
                            <h5 class="mb-1">{{ $probation->employee->fullname ?? 'N/A' }}</h5>
                            <p class="text-muted mb-0">ID: {{ $probation->employee->employee_id ?? 'N/A' }}</p>
                            <p class="text-muted">{{ $probation->employee->email_company ?? 'N/A' }}</p>
                        </div>

                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted">Department:</span>
                            <span class="fw-medium">{{ $probation->departmentRelation->dep_name ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted">Designation:</span>
                            <span class="fw-medium">{{ $probation->designationRelation->des_name ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted">Date of Joining:</span>
                            <span class="fw-medium">
                                {{ $probation->date_of_joined ? \Carbon\Carbon::parse($probation->date_of_joined)->format('d-m-Y') : 'N/A' }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted">Supervisor:</span>
                            <span class="fw-medium">{{ $probation->supervisor_name ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Probation Details -->
            <div class="col-md-8 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-primary text-white py-3">
                        <h6 class="mb-0"><i class="bi bi-calendar-range me-2"></i>Probation Period</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card bg-light border-0 mb-3">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted">Probation Start Date</h6>
                                        <h4 class="text-info">
                                            {{ $probation->probation_from ? \Carbon\Carbon::parse($probation->probation_from)->format('d-m-Y') : 'N/A' }}
                                        </h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light border-0 mb-3">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted">Probation End Date</h6>
                                        <h4 class="text-info">
                                            {{ $probation->probation_to ? \Carbon\Carbon::parse($probation->probation_to)->format('d-m-Y') : 'N/A' }}
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Evaluation Scores -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-muted mb-3"><i class="bi bi-clipboard-data me-2"></i>Evaluation Scores
                                </h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex justify-content-between border-bottom py-2">
                                            <span class="text-muted">Knowledge:</span>
                                            <span class="fw-medium">
                                                {{ $probation->knowledge_score }}/3
                                                <span
                                                    class="badge bg-{{ getScoreColor($probation->knowledge_score) }} ms-1">
                                                    {{ getScoreText($probation->knowledge_score) }}
                                                </span>
                                            </span>
                                        </div>
                                        @if ($probation->knowledge_notes)
                                            <div class="mt-1 small text-muted">{{ $probation->knowledge_notes }}</div>
                                        @endif
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex justify-content-between border-bottom py-2">
                                            <span class="text-muted">Skills:</span>
                                            <span class="fw-medium">
                                                {{ $probation->skills_score }}/3
                                                <span
                                                    class="badge bg-{{ getScoreColor($probation->skills_score) }} ms-1">
                                                    {{ getScoreText($probation->skills_score) }}
                                                </span>
                                            </span>
                                        </div>
                                        @if ($probation->skills_notes)
                                            <div class="mt-1 small text-muted">{{ $probation->skills_notes }}</div>
                                        @endif
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex justify-content-between border-bottom py-2">
                                            <span class="text-muted">Quality:</span>
                                            <span class="fw-medium">
                                                {{ $probation->quality_score }}/3
                                                <span
                                                    class="badge bg-{{ getScoreColor($probation->quality_score) }} ms-1">
                                                    {{ getScoreText($probation->quality_score) }}
                                                </span>
                                            </span>
                                        </div>
                                        @if ($probation->quality_notes)
                                            <div class="mt-1 small text-muted">{{ $probation->quality_notes }}</div>
                                        @endif
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex justify-content-between border-bottom py-2">
                                            <span class="text-muted">Productivity:</span>
                                            <span class="fw-medium">
                                                {{ $probation->productivity_score }}/3
                                                <span
                                                    class="badge bg-{{ getScoreColor($probation->productivity_score) }} ms-1">
                                                    {{ getScoreText($probation->productivity_score) }}
                                                </span>
                                            </span>
                                        </div>
                                        @if ($probation->productivity_notes)
                                            <div class="mt-1 small text-muted">{{ $probation->productivity_notes }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex justify-content-between border-bottom py-2">
                                            <span class="text-muted">Teamwork:</span>
                                            <span class="fw-medium">
                                                {{ $probation->teamwork_score }}/3
                                                <span
                                                    class="badge bg-{{ getScoreColor($probation->teamwork_score) }} ms-1">
                                                    {{ getScoreText($probation->teamwork_score) }}
                                                </span>
                                            </span>
                                        </div>
                                        @if ($probation->teamwork_notes)
                                            <div class="mt-1 small text-muted">{{ $probation->teamwork_notes }}</div>
                                        @endif
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex justify-content-between border-bottom py-2">
                                            <span class="text-muted">Punctuality:</span>
                                            <span class="fw-medium">
                                                {{ $probation->punctuality_score }}/3
                                                <span
                                                    class="badge bg-{{ getScoreColor($probation->punctuality_score) }} ms-1">
                                                    {{ getScoreText($probation->punctuality_score) }}
                                                </span>
                                            </span>
                                        </div>
                                        @if ($probation->punctuality_notes)
                                            <div class="mt-1 small text-muted">{{ $probation->punctuality_notes }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex justify-content-between border-bottom py-2">
                                            <span class="text-muted">Dependability:</span>
                                            <span class="fw-medium">
                                                {{ $probation->dependability_score }}/3
                                                <span
                                                    class="badge bg-{{ getScoreColor($probation->dependability_score) }} ms-1">
                                                    {{ getScoreText($probation->dependability_score) }}
                                                </span>
                                            </span>
                                        </div>
                                        @if ($probation->dependability_notes)
                                            <div class="mt-1 small text-muted">{{ $probation->dependability_notes }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex justify-content-between border-bottom py-2">
                                            <span class="text-muted">Communication:</span>
                                            <span class="fw-medium">
                                                {{ $probation->communication_score }}/3
                                                <span
                                                    class="badge bg-{{ getScoreColor($probation->communication_score) }} ms-1">
                                                    {{ getScoreText($probation->communication_score) }}
                                                </span>
                                            </span>
                                        </div>
                                        @if ($probation->communication_notes)
                                            <div class="mt-1 small text-muted">{{ $probation->communication_notes }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Evaluation Results -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card bg-light border-0 mb-3">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted">Overall Rating</h6>
                                        <h4 class="text-{{ getRatingColor($probation->overall_rating) }}">
                                            {{ $probation->overall_rating_text }}
                                        </h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light border-0 mb-3">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted">Decision</h6>
                                        <h4 class="text-{{ getDecisionColor($probation->appropriate_option) }}">
                                            {{ $probation->appropriate_option_text }}
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($probation->comments)
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h6 class="text-muted mb-3"><i class="bi bi-chat-left-text me-2"></i>Additional
                                        Comments</h6>
                                    <div class="bg-light p-3 rounded">
                                        <p class="mb-0">{{ $probation->comments }}</p>
                                    </div>
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
                        @if ($probation->supporting_documents)
                            @php
                                $filePath = asset($probation->supporting_documents);
                                $extension = strtolower(pathinfo($probation->supporting_documents, PATHINFO_EXTENSION));
                                $fileName = basename($probation->supporting_documents);
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
                                            {{ $probation->created_at->format('M d, Y') }}</small>
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
                                <p class="text-muted small">Supporting documents haven't been added to this probation
                                    record</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- HR Signature -->
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-primary text-white py-3 d-flex align-items-center">
                        <i class="bi bi-pen me-2 fs-5"></i>
                        <h6 class="mb-0">HR Signature</h6>
                    </div>
                    <div class="card-body">
                        @if ($probation->hr_signature)
                            <div class="signature-container text-center">
                                <div class="signature-header mb-3">
                                    <h6 class="text-primary mb-1">Signed By</h6>
                                    <p class="mb-0 fw-medium">{{ $probation->full_name ?? 'HR Representative' }}</p>
                                </div>

                                <div class="signature-preview mb-3">
                                    <div class="signature-box bg-light rounded p-3 mx-auto" style="max-width: 300px;">
                                        <img src="{{ asset($probation->hr_signature) }}" alt="HR Signature"
                                            class="img-fluid signature-image">
                                    </div>
                                </div>

                                <div class="signature-meta">
                                    <div class="badge bg-info bg-opacity-10 text-info mb-2">
                                        <i class="bi bi-check-circle-fill me-1"></i>Signed
                                    </div>
                                    <p class="text-muted small mb-0">
                                        Evaluated on
                                        {{ $probation->date_of_evaluation ? \Carbon\Carbon::parse($probation->date_of_evaluation)->format('d-m-Y') : 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="empty-state-icon mb-3">
                                    <i class="bi bi-pen display-4 text-light-emphasis"></i>
                                </div>
                                <h6 class="text-muted mb-2">No HR Signature</h6>
                                <p class="text-muted small">HR signature is not available for this probation record</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Evaluation Details -->
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-primary text-white py-3 d-flex align-items-center">
                        <i class="bi bi-calendar-check me-2 fs-5"></i>
                        <h6 class="mb-0">Evaluation Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="evaluation-details">
                            <div class="d-flex justify-content-between border-bottom py-2">
                                <span class="text-muted">Evaluation Date:</span>
                                <span class="fw-medium">
                                    {{ $probation->date_of_evaluation ? \Carbon\Carbon::parse($probation->date_of_evaluation)->format('d-m-Y') : 'N/A' }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between border-bottom py-2">
                                <span class="text-muted">Evaluated By:</span>
                                <span class="fw-medium">{{ $probation->employee->fullname ?? 'N/A' }}</span>
                            </div>
                            <div class="d-flex justify-content-between border-bottom py-2">
                                <span class="text-muted">Status:</span>
                                <span class="badge bg-{{ getDecisionColor($probation->appropriate_option) }} p-2">
                                    {{ $probation->appropriate_option_text }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between border-bottom py-2">
                                <span class="text-muted">Overall Rating:</span>
                                <span class="badge bg-{{ getRatingColor($probation->overall_rating) }} p-2">
                                    {{ $probation->overall_rating_text }}
                                </span>
                            </div>
                            <div class="mt-3 p-3 bg-light rounded">
                                <h6 class="text-muted mb-3">With the above evaluation, choose the appropriate option
                                    for the employee?​</h6>
                                <p class="small mb-0">
                                    @if ($probation->appropriate_option == 1)
                                        Employee should be confirmed in their position.
                                    @elseif($probation->appropriate_option == 2)
                                        Probation period should be extended with clear improvement goals.
                                    @else
                                        Termination process should be initiated as per company policy.
                                    @endif
                                </p>
                            </div>
                        </div>
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

<?php

function getScoreColor($score)
{
    switch ($score) {
        case 1:
            return 'danger';
        case 2:
            return 'warning';
        case 3:
            return 'success';
        default:
            return 'secondary';
    }
}

function getScoreText($score)
{
    switch ($score) {
        case 1:
            return 'Poor';
        case 2:
            return 'Average';
        case 3:
            return 'Good';
        default:
            return 'N/A';
    }
}

function getRatingColor($rating)
{
    switch ($rating) {
        case 1:
            return 'danger';
        case 2:
            return 'warning';
        case 3:
            return 'success';
        default:
            return 'secondary';
    }
}

function getDecisionColor($decision)
{
    switch ($decision) {
        case 1:
            return 'success'; // Confirmed
        case 2:
            return 'warning'; // Extended
        case 3:
            return 'danger'; // Terminated
        default:
            return 'secondary';
    }
}
?>
