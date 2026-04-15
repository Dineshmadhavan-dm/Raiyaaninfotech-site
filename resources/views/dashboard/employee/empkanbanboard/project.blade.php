<x-layout>
    <div class="container-fluid px-4 py-4">
        <!-- Header & Breadcrumb -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>

                <h4 class="mb-0 mt-2 fw-bold text-dark">My Projects</h4>
                <p class="text-muted mb-0">Manage and track your assigned projects</p>
            </div>

            <div class="d-flex align-items-center gap-2">
                <div class="input-group input-group-sm" style="width: 280px;">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-start-0" placeholder="Search projects..."
                        id="projectSearch">
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100 bg-gradient-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-2 opacity-75">Total Projects</h6>
                                <h3 class="mb-0 fw-bold">{{ $processedProjects->count() }}</h3>
                            </div>
                            <div class="bg-white bg-opacity-20 p-3 rounded-circle">
                                <i class="bi bi-diagram-3 fs-4 text-dark"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100 bg-gradient-warning text-dark">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-2 opacity-75">Active Projects</h6>
                                <h3 class="mb-0 fw-bold">
                                    {{ $processedProjects->where('project.pro_status', 1)->count() }}
                                </h3>
                            </div>
                            <div class="bg-white bg-opacity-20 p-3 rounded-circle">
                                <i class="bi bi-play fs-4 text-dark"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100 bg-gradient-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-2 opacity-75">Completed</h6>
                                <h3 class="mb-0 fw-bold">
                                    {{ $processedProjects->where('project.pro_status', 2)->count() }}
                                </h3>
                            </div>
                            <div class="bg-white bg-opacity-20 p-3 rounded-circle">
                                <i class="bi bi-check-circle text-dark fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100 bg-gradient-danger text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-2 opacity-75">Overdue</h6>
                                <h3 class="mb-0 fw-bold">
                                    {{ $processedProjects->where('isOverdue', true)->count() }}
                                </h3>
                            </div>
                            <div class="bg-white bg-opacity-20 p-3 rounded-circle">
                                <i class="bi bi-exclamation-triangle  text-dark fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Projects Grid -->
        <div class="row" id="projectsGrid">
            @forelse($processedProjects as $projectData)
                        @php
                            $project = $projectData['project'];
                            $memberNames = $projectData['memberNames'];
                            $memberIds = $projectData['memberIds'];
                            $overdueDays = $projectData['overdueDays'];
                            $isOverdue = $projectData['isOverdue'];
                            $projectAvatar = $projectData['projectAvatar'];
                            $projectFiles = $projectData['projectFiles'];
                            $totalModules = $projectData['totalModules'];
                            $completedModules = $projectData['completedModules'];

                            $modules = $projectData['modules'];

                            // Format dates
                            $createdDate = \Carbon\Carbon::parse($project->created_at)->format('d-m-Y');
                            $deadlineDate = \Carbon\Carbon::parse($project->pro_deadline)->format('d-m-Y');

                            // Status badge
                            $statusBadge = match ($project->pro_status) {
                                0 => ['class' => 'bg-secondary', 'text' => 'Created'],
                                1 => ['class' => 'bg-warning', 'text' => 'In Progress'],
                                2 => ['class' => 'bg-success', 'text' => 'Completed'],
                                default => ['class' => 'bg-secondary', 'text' => 'Unknown']
                            };
                        @endphp

                        <div class="col-xl-3 col-auto mb-4 project-card ">
                            <div class="card border-0 shadow-lg h-100 project-card-inner p-3">
                                <!-- Project Header with Status -->
                                <div class="card-header bg-transparent border-0 position-relative p-0">
                                    <div class="project-image-container position-relative">
                                        @if($projectAvatar)
                                            <img src="{{ $projectAvatar }}" class="card-img-top project-image"
                                                alt="{{ $project->pro_name }}" style="height: 160px; object-fit: cover;">
                                        @else
                                            <div class="card-img-top project-image-placeholder d-flex align-items-center justify-content-center"
                                                style="height: 160px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                <i class="bi bi-diagram-3 text-white opacity-50" style="font-size: 3rem;"></i>
                                            </div>
                                        @endif

                                        <!-- Status & Overdue Badge -->

                                    </div>
                                </div>

                                <div class="card-body">
                                    <!-- Project Title -->
                                    <h6 class="card-title fw-bold text-dark mb-2">{{ $project->pro_name }}</h6>

                                    <!-- Project Description -->
                                    <p class="card-text text-muted small mb-3 project-description">
                                        {{ Str::limit($project->pro_desc, 70) }}
                                    </p>



                                    <!-- Project Meta -->
                                    <div class="project-meta border-top pt-3">
                                        <div class="row text-center">
                                            <div class="col-4">
                                                <div class="border-end">
                                                    <h6 class="mb-1 text-primary fw-bold">{{ $totalModules }}</h6>
                                                    <small class="text-muted">Modules</small>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="border-end">
                                                    <h6 class="mb-1 text-info fw-bold">
                                                        {{ $modules->sum(function ($mod) {
                    return $mod->tasks->count();
                }) }}
                                                    </h6>
                                                    <small class="text-muted">Tasks</small>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div>
                                                    <h6 class="mb-1 text-success fw-bold">{{ $modules->sum(function ($mod) {
                    return $mod->tasks->sum(function ($task) {
                        return $task->subtasks->count();
                    });
                }) }}</h6>
                                                    <small class="text-muted">Subtasks</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Dates -->
                                    <div class="mt-3 pt-3 border-top">
                                        <div class="d-flex justify-content-between text-muted small">
                                            <span>
                                                <div class=" fw-medium">
                                                    Assigned Date
                                                </div>
                                                <div>
                                                    <i class="bi bi-calendar-plus me-1"></i>
                                                    {{ $createdDate }}
                                                </div>
                                            </span>
                                            <span>
                                                <div class=" fw-medium">
                                                    Due Date
                                                </div>
                                                <div>
                                                    <i class="bi bi-calendar-check me-1"></i>
                                                    {{ $deadlineDate }}
                                                </div>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer bg-transparent border-top-0 pt-0">
                                    <div class="d-flex justify-content-between gap-2">

                                        <span class="btn btn-sm btn-outline-secondary disabled flex-fill">
                                            <i class="bi bi-paperclip me-1"></i>No Files
                                        </span>

                                        {{-- @if(!empty($projectFiles))
                                        <button class="btn btn-sm btn-outline-primary view-attachments flex-fill"
                                            data-project-name="{{ $project->pro_name }}"
                                            data-project-attachments='{{ json_encode($projectFiles) }}'>
                                            <i class="bi bi-paperclip me-1"></i>Files
                                        </button>
                                        @else
                                        <span class="btn btn-sm btn-outline-secondary disabled flex-fill">
                                            <i class="bi bi-paperclip me-1"></i>No Files
                                        </span>
                                        @endif --}}

                                     <button class="btn btn-sm btn-primary view-project-details flex-fill"
        data-project-id="{{ $project->pro_id }}">
                                            <i class="bi bi-eye me-1"></i>View Details
                                        </button>
                                        {{-- <button class="btn btn-sm btn-primary view-project-details flex-fill"
                                            data-project-id="{{ $project->pro_id }}">
                                            <i class="bi bi-eye me-1"></i>View Details
                                        </button> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-inbox display-4 text-muted opacity-50"></i>
                            <h5 class="mt-3 text-muted">No projects assigned</h5>
                            <p class="text-muted">You haven't been assigned to any projects yet.</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Project Details Modal -->
    <div class="modal fade" id="projectDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">

                <div class="modal-body p-0" id="projectModalBody">
                    <!-- Content will be loaded dynamically -->
                </div>
            </div>
        </div>
    </div>

    <!-- Attachments Modal -->
    <div class="modal fade" id="attachmentsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-light border-bottom">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-paperclip me-2 text-primary"></i>
                        <h5 class="modal-title mb-0 fw-bold">Project Attachments</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="project-info-section p-4 border-bottom bg-light">
                        <div class="d-flex align-items-center">
                            <div class="project-avatar me-3">
                                <div class="bg-primary text-white rounded-circle p-3">
                                    <i class="bi bi-folder fs-4"></i>
                                </div>
                            </div>
                            <div class="project-details">
                                <h6 class="mb-1 fw-bold" id="attachmentsProjectName">Project Name</h6>
                                <span class="badge bg-primary">
                                    <i class="bi bi-files me-1"></i>
                                    <span id="attachmentsCount">0</span> files
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="attachments-container p-4">
                        <div class="row g-3" id="attachmentsGrid">
                            <!-- Attachments will be loaded dynamically -->
                        </div>
                        <div class="empty-state text-center py-5 d-none" id="emptyAttachments">
                            <i class="bi bi-inbox display-4 text-muted mb-3 opacity-50"></i>
                            <h5 class="text-muted">No attachments found</h5>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .project-image-container {
            position: relative;
            overflow: hidden;
            border-radius: 12px 12px 0 0;
        }

        .project-modal-header {
            position: relative;
            height: 200px;
            overflow: hidden;
        }

        .project-modal-header-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .project-modal-header-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }


        .project-modal-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: white;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
            margin-bottom: 0.5rem;
        }

        .btn-close-white {
            filter: invert(1);
            opacity: 0.8;
        }

        .module-card {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            margin-bottom: 20px;
            background: #fff;
        }

        .module-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 16px 20px;
            border-bottom: 1px solid #dee2e6;
            border-radius: 12px 12px 0 0;
        }

        .task-item {
            border-left: 4px solid #007bff;
            padding: 16px;
            margin-bottom: 16px;
            background: #f8f9fa;
            border-radius: 0 8px 8px 0;
        }

        .subtask-item {
            border-left: 3px solid #28a745;
            padding: 12px;
            margin-bottom: 12px;
            background: #f8f9fa;
            border-radius: 0 6px 6px 0;
        }

        .priority-badge {
            font-size: 0.75rem;
            padding: 4px 8px;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
        }

        .bg-gradient-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        }

        .file-image {
            color: #28a745;
        }

        .file-pdf {
            color: #dc3545;
        }

        .file-doc {
            color: #007bff;
        }

        .file-xls {
            color: #28a745;
        }

        .file-ppt {
            color: #fd7e14;
        }

        .file-zip {
            color: #6f42c1;
        }

        .file-default {
            color: #6c757d;
        }

        .project-details-sidebar {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 24px;
        }

        .empty-module-state,
        .empty-task-state,
        .empty-subtask-state {
            padding: 24px;
            text-align: center;
            color: #6c757d;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 16px;
            border: 2px dashed #dee2e6;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Project search functionality
            const projectSearch = document.getElementById('projectSearch');
            if (projectSearch) {
                projectSearch.addEventListener('input', function () {
                    const searchTerm = this.value.toLowerCase();
                    const projectCards = document.querySelectorAll('.project-card');

                    projectCards.forEach(card => {
                        const projectName = card.querySelector('.card-title').textContent.toLowerCase();
                        const projectDescription = card.querySelector('.project-description').textContent.toLowerCase();

                        if (projectName.includes(searchTerm) || projectDescription.includes(searchTerm)) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            }

            // View project details
            document.addEventListener('click', function (e) {
                if (e.target.classList.contains('view-project-details') || e.target.closest('.view-project-details')) {
                    const button = e.target.classList.contains('view-project-details') ? e.target : e.target.closest('.view-project-details');
                    const projectId = button.getAttribute('data-project-id');
                    loadProjectDetails(projectId);
                }

                if (e.target.classList.contains('view-attachments') || e.target.closest('.view-attachments')) {
                    const button = e.target.classList.contains('view-attachments') ? e.target : e.target.closest('.view-attachments');
                    const projectName = button.getAttribute('data-project-name');
                    const attachments = JSON.parse(button.getAttribute('data-project-attachments') || '[]');
                    showAttachmentsModal(projectName, attachments);
                }
            });

            // Load project details
            function loadProjectDetails(projectId) {
                fetch(`/dashboard/employees/empkanbanboard/project/${projectId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            displayProjectDetails(data.project, data.statistics);
                        } else {
                            showToast('Failed to load project details', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('An error occurred while loading project details', 'error');
                    });
            }

            // Display project details in modal
            function displayProjectDetails(project, statistics) {
                // Format dates
                const createdDate = new Date(project.created_at);
                const deadlineDate = new Date(project.pro_deadline);
                const formattedCreatedDate = createdDate.toLocaleDateString('en-GB', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                }).replace(/\//g, '-');

                const formattedDeadlineDate = deadlineDate.toLocaleDateString('en-GB', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                }).replace(/\//g, '-');

                // Create modules HTML
                let modulesHTML = '';
                if (project.modules && project.modules.length > 0) {
                    modulesHTML = project.modules.map(module => {
                        let tasksHTML = '';
                        if (module.tasks && module.tasks.length > 0) {
                            tasksHTML = module.tasks.map(task => {
                                // Get priority badge for task
                                const taskPriority = getPriorityBadge(task.task_priority);

                                let subtasksHTML = '';
                                if (task.subtasks && task.subtasks.length > 0) {
                                    subtasksHTML = task.subtasks.map(subtask => {
                                        // Get priority badge for subtask
                                        const subtaskPriority = getPriorityBadge(subtask.stask_priority);

                                        return `
                                <div class="subtask-item">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="flex-grow-1">
                                            <strong class="d-block mb-1">${subtask.stask_name}</strong>
                                            <p class="mb-1 text-muted small">${subtask.stask_desc || 'No description'}</p>
                                        </div>
                                        <span class="badge ${subtaskPriority.class} priority-badge ms-2">
                                            ${subtaskPriority.text}
                                        </span>
                                    </div>
                                </div>
                            `;
                                    }).join('');
                                } else {
                                    subtasksHTML = `
                            <div class="empty-subtask-state">
                                <i class="bi bi-inbox me-2"></i>
                                <span>No assigned subtasks</span>
                            </div>
                        `;
                                }

                                return `
                        <div class="task-item">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold">${task.task_name}</h6>
                                    <p class="mb-2 text-muted small">${task.task_desc || 'No description'}</p>
                                </div>
                                <span class="badge ${taskPriority.class} priority-badge ms-2">
                                    ${taskPriority.text}
                                </span>
                            </div>
                            <div class="subtasks-container">
                                <h6 class="small fw-bold text-muted mb-2">Subtasks:</h6>
                                ${subtasksHTML}
                            </div>
                        </div>
                    `;
                            }).join('');
                        } else {
                            tasksHTML = `
                    <div class="empty-task-state">
                        <i class="bi bi-inbox me-2"></i>
                        <span>No assigned tasks</span>
                    </div>
                `;
                        }

                        return `
                <div class="module-card">
                    <div class="module-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold">${module.mod_name}</h6>
                            <span class="badge bg-secondary">${module.tasks ? module.tasks.length : 0} Tasks</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="card-text text-muted mb-3">${module.mod_desc || 'No description'}</p>
                        ${tasksHTML}
                    </div>
                </div>
            `;
                    }).join('');
                } else {
                    modulesHTML = `
            <div class="empty-module-state">
                <i class="bi bi-inbox display-6 mb-3 opacity-50"></i>
                <h5 class="text-muted">No assigned modules</h5>
                <p class="text-muted">You haven't been assigned to any modules in this project.</p>
            </div>
        `;
                }

                // Update modal body
                const modalBody = document.getElementById('projectModalBody');
                modalBody.innerHTML = `
        <div class="container-fluid py-4">
            <div class="row">
                <!-- Project Information - Now includes project name at top -->
                <div class="col-lg-8">
                    <!-- Project Name Header -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-0 text-muted small">Project Name</p>
                                <h5 class="mb-0 fw-bold">${project.pro_name}</h5>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <!-- Project Description -->
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">Project Description</h6>
                            <p class="mb-0">${project.pro_desc || 'No description available'}</p>
                        </div>
                    </div>

                    <!-- Modules Section -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-bold">Assigned Modules (${statistics.modules.total})</h6>
                        </div>
                        <div class="card-body">
                            ${modulesHTML}
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Project Details -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-bold">Project Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <small class="text-muted d-block">Project Manager</small>
                                <strong>${project.project_head ? project.project_head.fullname : 'N/A'}</strong>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block">Project Lead</small>
                                <strong>${project.project_lead ? project.project_lead.fullname : 'N/A'}</strong>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block">Assigned Date</small>
                                <strong>${formattedCreatedDate}</strong>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block">Due Date</small>
                                <strong>${formattedDeadlineDate}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Project Summary -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-bold">Project Summary</h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-4 mb-3">
                                    <div class="border-end">
                                        <h4 class="text-primary mb-1 fw-bold">${statistics.modules.total}</h4>
                                        <small class="text-muted">Modules</small>
                                    </div>
                                </div>
                                <div class="col-4 mb-3">
                                    <div class="border-end">
                                        <h4 class="text-info mb-1 fw-bold">${statistics.tasks.total}</h4>
                                        <small class="text-muted">Tasks</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <h4 class="text-success mb-1 fw-bold">${statistics.subtasks.total}</h4>
                                    <small class="text-muted">Subtasks</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;

                // Show modal
                const modal = new bootstrap.Modal(document.getElementById('projectDetailsModal'));
                modal.show();
            }
            // Get priority badge
            function getPriorityBadge(priority) {
                switch (parseInt(priority)) {
                    case 1:
                        return { class: 'bg-success', text: 'Low' };
                    case 2:
                        return { class: 'bg-warning', text: 'Medium' };
                    case 3:
                        return { class: 'bg-danger', text: 'High' };
                    default:
                        return { class: 'bg-secondary', text: 'Unknown' };
                }
            }

            // Show attachments modal
            function showAttachmentsModal(projectName, attachments) {
                document.getElementById('attachmentsProjectName').textContent = projectName;
                document.getElementById('attachmentsCount').textContent = attachments.length;

                const attachmentsGrid = document.getElementById('attachmentsGrid');
                attachmentsGrid.innerHTML = '';

                if (attachments.length === 0) {
                    document.getElementById('emptyAttachments').classList.remove('d-none');
                } else {
                    document.getElementById('emptyAttachments').classList.add('d-none');

                    attachments.forEach((file, index) => {
                        const fileType = file.type ? file.type.toLowerCase() : '';
                        const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileType);

                        let fileIcon = 'bi-file-earmark';
                        let fileColor = 'file-default';

                        if (isImage) {
                            fileIcon = 'bi-image';
                            fileColor = 'file-image';
                        } else if (fileType === 'pdf') {
                            fileIcon = 'bi-file-earmark-pdf';
                            fileColor = 'file-pdf';
                        } else if (['doc', 'docx'].includes(fileType)) {
                            fileIcon = 'bi-file-earmark-word';
                            fileColor = 'file-doc';
                        } else if (['xls', 'xlsx'].includes(fileType)) {
                            fileIcon = 'bi-file-earmark-excel';
                            fileColor = 'file-xls';
                        } else if (['ppt', 'pptx'].includes(fileType)) {
                            fileIcon = 'bi-file-earmark-ppt';
                            fileColor = 'file-ppt';
                        } else if (['zip', 'rar', '7z'].includes(fileType)) {
                            fileIcon = 'bi-file-earmark-zip';
                            fileColor = 'file-zip';
                        }

                        const fileCard = document.createElement('div');
                        fileCard.className = 'col-md-6 col-lg-4';
                        fileCard.innerHTML = `
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center p-3">
                                    <i class="bi ${fileIcon} ${fileColor} display-6 mb-3"></i>
                                    <h6 class="card-title fw-bold" style="font-size: 0.85rem; line-height: 1.2;">${file.name}</h6>
                                    <small class="text-muted text-uppercase">${fileType}</small>
                                </div>
                                <div class="card-footer bg-transparent border-0 pt-0">
                                    <button class="btn btn-sm btn-outline-primary w-100 view-file"
                                            data-file-url="${file.url}">
                                        <i class="bi bi-eye me-1"></i>View
                                    </button>
                                </div>
                            </div>
                        `;
                        attachmentsGrid.appendChild(fileCard);
                    });
                }

                // Add event listeners for view buttons
                attachmentsGrid.querySelectorAll('.view-file').forEach(button => {
                    button.addEventListener('click', function () {
                        const fileUrl = this.getAttribute('data-file-url');
                        window.open(fileUrl, '_blank');
                    });
                });

                const attachmentsModal = new bootstrap.Modal(document.getElementById('attachmentsModal'));
                attachmentsModal.show();
            }

            // Toast notification function
            function showToast(message, type = 'success') {
                // You can implement a toast notification system here
                console.log(`${type}: ${message}`);
            }
        });
    </script>
</x-layout>
