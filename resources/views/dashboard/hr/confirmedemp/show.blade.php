<x-layout>
    @section('title', 'Confirmed Employee Details')
    <div class="container-fluid py-4 px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-2">Confirmed Employee Details</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('confirmedemp') }}" class="text-decoration-none text-muted">
                                <i class="bi bi-people-fill me-2"></i>Confirmed Employees
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-primary" aria-current="page">Employee Details</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('confirmedemp') }}" class="btn btn-outline-primary d-flex align-items-center">
                <i class="bi bi-arrow-left me-2"></i>Back to Confirmed List
            </a>
        </div>

        <x-message />

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                @if ($probation)
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <img src="{{ $probation->employee->image ? asset('employee_images/' . $probation->employee->image) : asset('images/admin_default.jpg') }}"
                                    class="rounded-circle me-3" width="80" height="80">
                                <div>
                                    <h4 class="mb-1">{{ $probation->employee->fullname }}</h4>
                                    <p class="text-muted mb-1">Employee ID: {{ $probation->employee->employee_id }}</p>
                                    <p class="text-muted mb-0">Email: {{ $probation->employee_email ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <span class="badge bg-success p-2 fs-6">
                                <i class="bi bi-check-circle me-2"></i>Confirmed Employee
                            </span>
                            <p class="text-muted mt-2 mb-0">
                                Confirmed on:
                                {{ \Carbon\Carbon::parse($probation->date_of_evaluation)->format('d-m-Y') }}
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-md-12 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Basic Information</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="40%"><strong>Department:</strong></td>
                                            <td>{{ $probation->departmentRelation->dep_name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Designation:</strong></td>
                                            <td>{{ $probation->designationRelation->des_name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Supervisor:</strong></td>
                                            <td>{{ $probation->supervisor_name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Date of Joining:</strong></td>
                                            <td>{{ \Carbon\Carbon::parse($probation->date_of_joined)->format('d-m-Y') ?? 'N/A' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Probation Period:</strong></td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($probation->probation_from)->format('d-m-Y') }}
                                                to
                                                {{ \Carbon\Carbon::parse($probation->probation_to)->format('d-m-Y') }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Evaluation Scores -->
                        {{-- <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="bi bi-graph-up me-2"></i>Evaluation Scores</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="60%"><strong>Knowledge:</strong></td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $probation->knowledge_score == 3 ? 'success' : ($probation->knowledge_score == 2 ? 'warning' : 'danger') }}">
                                                    {{ $probation->knowledge_score }}/3
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Skills:</strong></td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $probation->skills_score == 3 ? 'success' : ($probation->skills_score == 2 ? 'warning' : 'danger') }}">
                                                    {{ $probation->skills_score }}/3
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Quality:</strong></td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $probation->quality_score == 3 ? 'success' : ($probation->quality_score == 2 ? 'warning' : 'danger') }}">
                                                    {{ $probation->quality_score }}/3
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Productivity:</strong></td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $probation->productivity_score == 3 ? 'success' : ($probation->productivity_score == 2 ? 'warning' : 'danger') }}">
                                                    {{ $probation->productivity_score }}/3
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Overall Rating:</strong></td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $probation->overall_rating == 3 ? 'success' : ($probation->overall_rating == 2 ? 'warning' : 'danger') }} p-2">
                                                    {{ $probation->getOverallRatingTextAttribute() }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div> --}}

                        <!-- HR Signature Section -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="bi bi-pen me-2"></i>HR Signature</h6>
                                </div>
                                <div class="card-body">
                                    @if ($probation->hr_signature)
                                        <div class="signature-container text-center">
                                            <div class="signature-header mb-3">
                                                <h6 class="text-primary mb-1">Signed By</h6>
                                                <p class="mb-0 fw-medium">
                                                    {{ $probation->full_name ?? 'HR Representative' }}</p>
                                            </div>

                                            <div class="signature-preview mb-3">
                                                <div class="signature-box bg-light rounded p-3 mx-auto"
                                                    style="max-width: 300px;">
                                                    <img src="{{ asset($probation->hr_signature) }}" alt="HR Signature"
                                                        class="img-fluid signature-image" style="max-height: 80px;">
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
                                        <div class="text-center py-4">
                                            <div class="empty-state-icon mb-3">
                                                <i class="bi bi-pen display-4 text-light-emphasis"></i>
                                            </div>
                                            <h6 class="text-muted mb-2">No HR Signature</h6>
                                            <p class="text-muted small">HR signature is not available for this
                                                probation record</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Supporting Documents Section -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="bi bi-paperclip me-2"></i>Supporting Documents</h6>
                                </div>
                                <div class="card-body">
                                    @if ($probation->supporting_documents)
                                        @php
                                            $filePath = asset($probation->supporting_documents);
                                            $extension = strtolower(
                                                pathinfo($probation->supporting_documents, PATHINFO_EXTENSION),
                                            );
                                            $fileName = basename($probation->supporting_documents);
                                        @endphp

                                        <div class="document-preview-container">
                                            <div
                                                class="document-card d-flex align-items-center p-3 border rounded mb-3">
                                                <div class="document-icon me-3">
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

                                                <div class="document-info flex-grow-1">
                                                    <h6 class="document-title mb-1">{{ $fileName }}</h6>
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
                                                        <a href="{{ $filePath }}" download
                                                            class="btn btn-outline-secondary">
                                                            <i class="bi bi-download me-1"></i>Download
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Image preview for image files -->
                                            @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                                <div class="image-preview mt-3 text-center">
                                                    <p class="text-muted small mb-2">Document Preview:</p>
                                                    <img src="{{ $filePath }}" alt="Document Preview"
                                                        class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <div class="empty-state-icon mb-3">
                                                <i class="bi bi-file-earmark-x display-4 text-light-emphasis"></i>
                                            </div>
                                            <h6 class="text-muted mb-2">No documents uploaded</h6>
                                            <p class="text-muted small">Supporting documents haven't been added to this
                                                probation
                                                record</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-exclamation-triangle display-4 text-muted d-block mb-2"></i>
                        <p class="text-muted">Confirmed employee record not found.</p>
                        <a href="{{ route('confirmedemp') }}" class="btn btn-primary">
                            <i class="bi bi-arrow-left me-2"></i>Back to Confirmed List
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
            background-color: #f8f9fa !important;
        }

        .table-borderless td {
            padding: 0.5rem 0;
            border: none;
        }

        .signature-image {
            max-height: 80px;
            width: auto;
        }

        .document-card {
            transition: all 0.3s ease;
        }

        .document-card:hover {
            background-color: #f8f9fa;
        }

        .empty-state-icon {
            color: #6c757d;
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
</x-layout>
