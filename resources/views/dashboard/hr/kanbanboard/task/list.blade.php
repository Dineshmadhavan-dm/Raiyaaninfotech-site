<x-kanbandashboardlayout>
    @section('title', 'Task')
    <div class="mt-5">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 mt-2">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dhome') }}" class="text-decoration-none text-muted">
                                    <i class="bi bi-house me-2"></i>Home
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <i class="bi bi-list-task me-2"></i>Task Management
                            </li>
                        </ol>
                    </nav>
                </div>
                <div>
                   <a href="{{ route('dhome') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-left me-2"></i>Main Dashboard
                </a>
                </div>
            </div>

            <x-message />

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-md-3">
                            <div class="input-group">
                                <input type="text" class="form-control" id="globalSearch" placeholder="Search tasks, subtasks...">
                                <span class="input-group-text bg-dark-subtle">
                                    <i class="bi bi-search text-muted"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end mt-2 mt-md-0">
                            <div class="dropdown d-inline-block me-2">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dateFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-calendar me-2"></i>Filter by Date
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dateFilterDropdown">
                                    <li><a class="dropdown-item date-filter" href="#" data-filter="today">Today</a></li>
                                    <li><a class="dropdown-item date-filter" href="#" data-filter="week">This Week</a></li>
                                    <li><a class="dropdown-item date-filter" href="#" data-filter="month">This Month</a></li>
                                    <li><a class="dropdown-item date-filter" href="#" data-filter="sixmonths">Last 6 Months</a></li>
                                    <li><a class="dropdown-item date-filter" href="#" data-filter="year">This Year</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item date-filter" href="#" data-filter="all">All Tasks</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                        <div class="d-flex align-items-center">
                            <span class="me-2">Show</span>
                            <select class="form-select form-select-sm w-auto" id="entriesPerPage">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <span class="ms-2">entries</span>
                        </div>
                        <div class="text-muted">
                            Showing <span id="showingStart">1</span> to <span id="showingEnd">5</span> of <span id="totalEntries">{{ count($tasks) }}</span> entries
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0" id="taskTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="20%">Task Name</th>
                                    <th width="15%">Module Name</th>
                                    <th width="12%">Access Type</th>
                                    <th width="18%">Assignee</th>
                                    <th width="10%">Assigned Date</th>
                                    <th width="10%">Due Date</th>
                                    <th width="10%">Completed Date</th>
                                    <th width="8%">Priority</th>
                                    <th width="7%">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="taskTableBody">
                                @php
                                    $tasks = $tasks->where('delete_status', 1);
                                @endphp

                                @foreach($tasks as $task)
                                    @php
                                        $taskMembers = [];
                                        if ($task->task_member) {
                                            $memberData = $task->task_member;
                                            if (is_string($memberData)) {
                                                $decoded = json_decode($memberData, true);
                                                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                                    $taskMembers = $decoded;
                                                }
                                            } elseif (is_array($memberData)) {
                                                $taskMembers = $memberData;
                                            }
                                        }

                                        $memberDetails = [];
                                        if (!empty($taskMembers)) {
                                            $memberDetails = \App\Models\Employee::whereIn('emp_id', $taskMembers)
                                                ->get(['emp_id', 'fullname', 'cur_designation', 'image']);
                                        }

                                        $isCompleted = $task->task_status == 2;

                                        $projectName = $task->modulo->project->pro_name ?? 'N/A';
                                        $moduleName = $task->modulo->mod_name ?? 'N/A';

                                        $completionStatus = '';
                                        if ($task->task_complete) {
                                            $completionDate = \Carbon\Carbon::parse($task->task_complete);
                                            $deadline = \Carbon\Carbon::parse($task->task_deadline);
                                            if ($completionDate->lte($deadline)) {
                                                $completionStatus = '<span class="badge bg-success mt-1" style="width: 70px">On-time</span>';
                                            } else {
                                                $overdueDays = $deadline->diffInDays($completionDate);
                                                $completionStatus = '<span class="badge bg-danger mt-2 text-wrap" style="width: 75px; ">Overdue' . $overdueDays . ' days</span>';
                                            }
                                        }
                                    @endphp

                                    <tr class="task-row" data-task-id="{{ $task->task_id }}" data-assigned-date="{{ \Carbon\Carbon::parse($task->created_at)->format('Y-m-d') }}" data-due-date="{{ $task->task_deadline ? \Carbon\Carbon::parse($task->task_deadline)->format('Y-m-d') : '' }}" data-original-deadline="{{ $task->task_deadline ? \Carbon\Carbon::parse($task->task_deadline)->format('Y-m-d') : '' }}">
                                        <td>
                                            <div class="d-flex align-items-center hover-task">
                                                <div class="task-icon me-2">
                                                    @if($isCompleted)
                                                        <i class="bi bi-check2-square text-success"></i>
                                                    @else
                                                        <i class="bi bi-kanban text-dark"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    <a href="{{ route('task.chat', ['id' => $task->task_id]) }}" class="dropdown-item">
                                                        <span class="{{ $isCompleted ? 'text-decoration-line-through text-muted' : 'fw-medium' }}">
                                                            {{ $task->task_name }}
                                                        </span>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <small class="text-primary fw-semibold">{{ $moduleName }}</small>
                                                <small class="text-dark py-1">{{ $projectName }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            @if($task->task_accessmod == 0)
                                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">
                                                    <i class="bi bi-globe me-1"></i>Public
                                                </span>
                                            @else
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">
                                                    <i class="bi bi-lock me-1"></i>Private
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $projectLeadId = $task->modulo->project->pro_lead ?? null;
                                                $assignee = $projectLeadId ? \App\Models\Employee::where('emp_id', $projectLeadId)->first(['emp_id', 'fullname', 'cur_designation', 'image']) : null;
                                            @endphp

                                            @if($assignee)
                                                <div class="d-flex align-items-center">
                                                    <div class="d-flex align-items-center me-3">
                                                        @if($assignee->image)
                                                            <img src="{{ asset('employee_images/' . $assignee->image) }}" class="rounded-circle me-2" width="32" height="32" alt="{{ $assignee->fullname }}" data-bs-toggle="tooltip" title="{{ $assignee->fullname }} - {{ $assignee->designationid->des_name }}">
                                                        @else
                                                            <div class="avatar-sm me-2" data-bs-toggle="tooltip" title="{{ $assignee->fullname }} - {{ $assignee->designationid->des_name }}">
                                                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                                                    {{ substr($assignee->fullname, 0, 1) }}
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <div class="d-none d-md-block">
                                                            <div class="fw-semibold small">{{ $assignee->fullname }}</div>
                                                            <div class="text-muted xsmall">{{ $assignee->designationid->des_name}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">Unassigned</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>{{ \Carbon\Carbon::parse($task->created_at)->format('d-m-Y') }}</small>
                                        </td>
                                        <td>
                                            <small class="text-dark">
                                                {{ $task->task_deadline ? \Carbon\Carbon::parse($task->task_deadline)->format('d-m-Y') : 'No deadline' }}
                                            </small>
                                        </td>
                                        <td>
                                            @if($task->task_complete)
                                                <div class="d-flex flex-column">
                                                    <small class="text-dark">
                                                        {{ \Carbon\Carbon::parse($task->task_complete)->format('d-m-Y') }}
                                                    </small>
                                                    @php
                                                        $completionStatus = '';
                                                        if ($task->task_complete) {
                                                            $completionDate = \Carbon\Carbon::parse($task->task_complete);
                                                            $deadline = \Carbon\Carbon::parse($task->task_deadline);
                                                            if ($completionDate->lte($deadline)) {
                                                                $completionStatus = '<span class="badge bg-success mt-1" style="width: 70px">On-time</span>';
                                                            } else {
                                                                $overdueDays = $deadline->diffInDays($completionDate);
                                                                $completionStatus = \App\Http\Controllers\Dashboard\HR\Kanbanboard\TaskController::formatOverdueDuration($overdueDays);
                                                            }
                                                        }
                                                    @endphp
                                                    {!! $completionStatus !!}
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $priorityClass = '';
                                                $priorityIcon = '';
                                                $priorityText = '';
                                                switch ($task->task_priority) {
                                                    case 1:
                                                        $priorityClass = 'bg-success bg-opacity-10 text-success border border-success border-opacity-25';
                                                        $priorityIcon = 'bi-arrow-down-circle';
                                                        $priorityText = 'Low';
                                                        break;
                                                    case 2:
                                                        $priorityClass = 'bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25';
                                                        $priorityIcon = 'bi-dash-circle';
                                                        $priorityText = 'Medium';
                                                        break;
                                                    case 3:
                                                        $priorityClass = 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25';
                                                        $priorityIcon = 'bi-arrow-up-circle';
                                                        $priorityText = 'High';
                                                        break;
                                                }
                                            @endphp
                                            <span class="badge {{ $priorityClass }}">
                                                <i class="bi {{ $priorityIcon }} me-1"></i>{{ $priorityText }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li class="border-1 border-bottom">
                                                        <a href="{{ route('subtaskcreate', $task->task_id) }}" class="dropdown-item">
                                                            <i class="bi bi-plus-lg me-2"></i>Add Subtask
                                                        </a>
                                                    </li>
                                                    <li class="border-1 border-bottom">
                                                        <button class="dropdown-item view-task" data-task-id="{{ $task->task_id }}" data-bs-toggle="modal" data-bs-target="#taskModal">
                                                            <i class="bi bi-eye me-2"></i>View
                                                        </button>
                                                    </li>
                                                    <li class="border-1 border-bottom">
                                                        <a href="{{ route('taskedit', $task->task_id) }}" class="dropdown-item">
                                                            <i class="bi bi-pencil me-2"></i>Edit
                                                        </a>
                                                    </li>
                                                    @if(!$isCompleted)
                                                        <li class="border-1 border-bottom">
                                                            <button class="dropdown-item complete-task" data-task-id="{{ $task->task_id }}" data-task-name="{{ $task->task_name }}">
                                                                <i class="bi bi-check-lg me-2"></i>Complete
                                                            </button>
                                                        </li>
                                                    @else
                                                        <li>
                                                            <button class="dropdown-item reopen-task" data-task-id="{{ $task->task_id }}" data-task-name="{{ $task->task_name }}" data-task-deadline="{{ $task->task_deadline }}">
                                                                <i class="bi bi-arrow-counterclockwise me-2"></i>Reopen
                                                            </button>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>

                                    @if($task->subtasks && $task->subtasks->where('delete_status', 1)->count() > 0)
                                        <tr class="subtask-header-row bg-light">
                                            <td colspan="9" class="py-2">
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-arrow-return-right text-secondary me-2"></i>
                                                    <strong class="text-dark">Subtask Name</strong>
                                                </div>
                                            </td>
                                        </tr>

                                        @foreach($task->subtasks->where('delete_status', 1) as $subtask)
                                            @php
                                                $subtaskMembers = [];
                                                if ($subtask->stask_member) {
                                                    $memberData = $subtask->stask_member;
                                                    if (is_string($memberData)) {
                                                        $decoded = json_decode($memberData, true);
                                                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                                            $subtaskMembers = $decoded;
                                                        }
                                                    } elseif (is_array($memberData)) {
                                                        $subtaskMembers = $memberData;
                                                    }
                                                }

                                                $subtaskMemberDetails = [];
                                                if (!empty($subtaskMembers)) {
                                                    $subtaskMemberDetails = \App\Models\Employee::whereIn('emp_id', $subtaskMembers)
                                                        ->get(['emp_id', 'fullname', 'cur_designation', 'image']);
                                                }

                                                $isSubtaskCompleted = $subtask->stask_status == 2;

                                                $subtaskCompletionStatus = '';
                                                if ($subtask->stask_complete) {
                                                    $completionDate = \Carbon\Carbon::parse($subtask->stask_complete);
                                                    $deadline = \Carbon\Carbon::parse($subtask->stask_deadline);
                                                    if ($completionDate->lte($deadline)) {
                                                        $subtaskCompletionStatus = '<span class="badge bg-success mt-1" style="width: 70px">On-time</span>';
                                                    } else {
                                                        $overdueDays = $deadline->diffInDays($completionDate);
                                                        $subtaskCompletionStatus = '<span class="badge bg-danger mt-2 text-wrap" style="width: 75px; ">Overdue' . $overdueDays . ' days</span>';
                                                    }
                                                }
                                            @endphp

                                            <tr class="subtask-row" data-task-id="{{ $task->task_id }}" data-subtask-id="{{ $subtask->stask_id }}" data-assigned-date="{{ \Carbon\Carbon::parse($subtask->created_at)->format('Y-m-d') }}" data-due-date="{{ $subtask->stask_deadline ? \Carbon\Carbon::parse($subtask->stask_deadline)->format('Y-m-d') : '' }}" data-original-deadline="{{ $subtask->stask_deadline ? \Carbon\Carbon::parse($subtask->stask_deadline)->format('Y-m-d') : '' }}">
                                                <td>
                                                    <div class="d-flex align-items-center ms-4 hover-task">
                                                        <div class="subtask-icon me-2">
                                                            @if($isSubtaskCompleted)
                                                                <i class="bi bi-check2-square text-success"></i>
                                                            @else
                                                                <i class="bi bi-diagram-2 text-dark"></i>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <a href="{{ route('subtask.chat', ['id' => $subtask->stask_id]) }}" class="dropdown-item">
                                                                <span class="{{ $isSubtaskCompleted ? 'text-decoration-line-through text-muted' : 'fw-medium' }}">
                                                                    {{ $subtask->stask_name }}
                                                                </span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-muted">-</span>
                                                </td>
                                                <td>
                                                    @if($subtask->stask_accessmod == 0)
                                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">
                                                            <i class="bi bi-globe me-1"></i>Public
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">
                                                            <i class="bi bi-lock me-1"></i>Private
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        $projectLeadId = $task->modulo->project->pro_lead ?? null;
                                                        $assignee = $projectLeadId ? \App\Models\Employee::where('emp_id', $projectLeadId)->first(['emp_id', 'fullname', 'cur_designation', 'image']) : null;
                                                    @endphp

                                                    @if($assignee)
                                                        <div class="d-flex align-items-center">
                                                            <div class="d-flex align-items-center me-3">
                                                                @if($assignee->image)
                                                                    <img src="{{ asset('employee_images/' . $assignee->image) }}" class="rounded-circle me-2" width="32" height="32" alt="{{ $assignee->fullname }}" data-bs-toggle="tooltip" title="{{ $assignee->fullname }} - {{ $assignee->designationid->des_name }}">
                                                                @else
                                                                    <div class="avatar-sm me-2" data-bs-toggle="tooltip" title="{{ $assignee->fullname }} - {{ $assignee->designationid->des_name }}">
                                                                        <div class="avatar-title bg-info-subtle text-info rounded-circle">
                                                                            {{ substr($assignee->fullname, 0, 1) }}
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                <div class="d-none d-md-block">
                                                                    <div class="fw-semibold small">{{ $assignee->fullname }}</div>
                                                                    <div class="text-muted xsmall">{{ $assignee->designationid->des_name }}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">Unassigned</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <small>{{ \Carbon\Carbon::parse($subtask->created_at)->format('d-m-Y') }}</small>
                                                </td>
                                                <td>
                                                    <small class="text-dark">
                                                        {{ $subtask->stask_deadline ? \Carbon\Carbon::parse($subtask->stask_deadline)->format('d-m-Y') : 'No deadline' }}
                                                    </small>
                                                </td>
                                                <td>
                                                    @if($subtask->stask_complete)
                                                        <div class="d-flex flex-column">
                                                            <small class="text-dark">
                                                                {{ \Carbon\Carbon::parse($subtask->stask_complete)->format('d-m-Y') }}
                                                            </small>
                                                            @php
                                                                $subtaskCompletionStatus = '';
                                                                if ($subtask->stask_complete) {
                                                                    $completionDate = \Carbon\Carbon::parse($subtask->stask_complete);
                                                                    $deadline = \Carbon\Carbon::parse($subtask->stask_deadline);
                                                                    if ($completionDate->lte($deadline)) {
                                                                        $subtaskCompletionStatus = '<span class="badge bg-success mt-1" style="width: 70px">On-time</span>';
                                                                    } else {
                                                                        $overdueDays = $deadline->diffInDays($completionDate);
                                                                        $subtaskCompletionStatus = \App\Http\Controllers\Dashboard\HR\Kanbanboard\TaskController::formatOverdueDuration($overdueDays);
                                                                    }
                                                                }
                                                            @endphp
                                                            {!! $subtaskCompletionStatus !!}
                                                        </div>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        $subtaskPriorityClass = '';
                                                        $subtaskPriorityIcon = '';
                                                        $subtaskPriorityText = '';
                                                        switch ($subtask->stask_priority) {
                                                            case 1:
                                                                $subtaskPriorityClass = 'bg-success bg-opacity-10 text-success border border-success border-opacity-25';
                                                                $subtaskPriorityIcon = 'bi-arrow-down-circle';
                                                                $subtaskPriorityText = 'Low';
                                                                break;
                                                            case 2:
                                                                $subtaskPriorityClass = 'bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25';
                                                                $subtaskPriorityIcon = 'bi-dash-circle';
                                                                $subtaskPriorityText = 'Medium';
                                                                break;
                                                            case 3:
                                                                $subtaskPriorityClass = 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25';
                                                                $subtaskPriorityIcon = 'bi-arrow-up-circle';
                                                                $subtaskPriorityText = 'High';
                                                                break;
                                                        }
                                                    @endphp
                                                    <span class="badge {{ $subtaskPriorityClass }}">
                                                        <i class="bi {{ $subtaskPriorityIcon }} me-1"></i>{{ $subtaskPriorityText }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                                            <i class="bi bi-three-dots-vertical"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li class="border-1 border-bottom">
                                                                <button class="dropdown-item view-subtask" data-subtask-id="{{ $subtask->stask_id }}" data-bs-toggle="modal" data-bs-target="#subtaskModal">
                                                                    <i class="bi bi-eye me-2"></i>View
                                                                </button>
                                                            </li>
                                                            <li class="border-1 border-bottom">
                                                                <a href="{{ route('subtaskedit', $subtask->stask_id) }}" class="dropdown-item">
                                                                    <i class="bi bi-pencil me-2"></i>Edit
                                                                </a>
                                                            </li>
                                                            @if(!$isSubtaskCompleted)
                                                                <li class="border-1 border-bottom">
                                                                    <button class="dropdown-item complete-subtask" data-subtask-id="{{ $subtask->stask_id }}" data-subtask-name="{{ $subtask->stask_name }}">
                                                                        <i class="bi bi-check-lg me-2"></i>Complete
                                                                    </button>
                                                                </li>
                                                            @else
                                                                <li class="border-1 border-bottom">
                                                                    <button class="dropdown-item reopen-subtask" data-subtask-id="{{ $subtask->stask_id }}" data-subtask-name="{{ $subtask->stask_name }}" data-subtask-deadline="{{ $subtask->stask_deadline }}">
                                                                        <i class="bi bi-arrow-counterclockwise me-2"></i>Reopen
                                                                    </button>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between align-items-center p-3 border-top">
                        <div class="text-muted" id="paginationInfo">
                            Showing 1 to 5 of {{ count($tasks) }} entries
                        </div>
                        <nav>
                            <ul class="pagination pagination-sm mb-0" id="pagination"></ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="taskModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Task Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="taskModalBody"></div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="subtaskModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Subtask Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="subtaskModalBody"></div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="reopenModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Deadline</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="reopenForm">
                            @csrf
                            <input type="hidden" id="reopenItemId" name="item_id">
                            <input type="hidden" id="reopenItemType" name="item_type">
                            <input type="hidden" id="originalDeadline" name="original_deadline">
                            <div class="mb-3">
                                <label for="newDeadline" class="form-label">New Deadline</label>
                                <input type="date" class="form-control" id="newDeadline" name="new_deadline" required>
                                <small class="text-muted">Original deadline: <span id="originalDeadlineText"></span></small>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Update & Reopen</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <x-actionbtn />
    </div>
</x-kanbandashboardlayout>

<style>
    .hover-task:hover{
color: var(--ra-primary-set);
    }

    .avatar-title {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 600;
        width: 32px;
        height: 32px;
    }
    .table-success {
        background-color: rgba(25, 135, 84, 0.05);
    }
    .card {
        border: 1px solid #e9ecef;
    }
    .table-light {
        background-color: #f8f9fa;
    }
    .dropdown-menu {
        border: 1px solid #e9ecef;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    .subtask-header-row {
        background-color: #f8f9fa !important;
    }
    .subtask-row td:first-child {
        padding-left: 2rem;
    }
    .task-icon,
    .subtask-icon {
        width: 24px;
        text-align: center;
    }
    .badge {
        font-size: 0.75rem;
        padding: 0.35rem 0.65rem;
    }
    .xsmall {
        font-size: 0.7rem;
    }
    .sortable {
        cursor: pointer;
        user-select: none;
    }
    .sortable:hover {
        background-color: #f8f9fa;
    }
    .sort-asc::after {
        content: " ↑";
        font-weight: bold;
    }
    .sort-desc::after {
        content: " ↓";
        font-weight: bold;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let currentSort = { column: null, direction: 'asc' };
        let currentPage = 1;
        let entriesPerPage = 5;
        let filteredTasks = [];
        let filteredSubtasks = [];
        let taskRows = [];

        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        const entriesPerPageSelect = document.getElementById('entriesPerPage');
        const taskTableBody = document.getElementById('taskTableBody');
        const pagination = document.getElementById('pagination');
        const paginationInfo = document.getElementById('paginationInfo');
        const showingStart = document.getElementById('showingStart');
        const showingEnd = document.getElementById('showingEnd');
        const totalEntries = document.getElementById('totalEntries');

        function setupEventListeners() {
            if (entriesPerPageSelect) {
                entriesPerPageSelect.addEventListener('change', function () {
                    entriesPerPage = parseInt(this.value);
                    currentPage = 1;
                    updatePagination();
                });
            }

            document.querySelectorAll('.reopen-task, .reopen-subtask').forEach(button => {
                button.addEventListener('click', function () {
                    const itemId = this.getAttribute('data-task-id') || this.getAttribute('data-subtask-id');
                    const itemType = this.getAttribute('data-task-id') ? 'task' : 'subtask';
                    const currentDeadline = this.getAttribute('data-task-deadline') || this.getAttribute('data-subtask-deadline');
                    const originalDeadline = this.closest('tr').getAttribute('data-original-deadline');

                    document.getElementById('reopenItemId').value = itemId;
                    document.getElementById('reopenItemType').value = itemType;
                    document.getElementById('originalDeadline').value = originalDeadline;

                    const newDeadlineInput = document.getElementById('newDeadline');
                    newDeadlineInput.value = currentDeadline ? currentDeadline.split('-').reverse().join('-') : '';

                    const originalDeadlineText = document.getElementById('originalDeadlineText');
                    if (originalDeadline) {
                        const formattedDate = originalDeadline.split('-').reverse().join('-');
                        originalDeadlineText.textContent = formattedDate;
                    } else {
                        originalDeadlineText.textContent = 'No deadline';
                    }

                    const today = new Date().toISOString().split('T')[0];
                    newDeadlineInput.min = today;

                    const reopenModal = new bootstrap.Modal(document.getElementById('reopenModal'));
                    reopenModal.show();
                });
            });

            document.getElementById('reopenForm').addEventListener('submit', function (e) {
                e.preventDefault();
                const formData = new FormData(this);
                fetch('/dashboard/task/reopen-with-deadline', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        bootstrap.Modal.getInstance(document.getElementById('reopenModal')).hide();
                        location.reload();
                    } else {
                        alert('Error reopening item');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error reopening item');
                });
            });

            document.querySelectorAll('.complete-task').forEach(button => {
                button.addEventListener('click', function () {
                    const taskId = this.getAttribute('data-task-id');
                    const taskName = this.getAttribute('data-task-name');
                    if (confirm(`Are you sure you want to mark "${taskName}" as completed?`)) {
                        completeTask(taskId);
                    }
                });
            });

            document.querySelectorAll('.complete-subtask').forEach(button => {
                button.addEventListener('click', function () {
                    const subtaskId = this.getAttribute('data-subtask-id');
                    const subtaskName = this.getAttribute('data-subtask-name');
                    if (confirm(`Are you sure you want to mark "${subtaskName}" as completed?`)) {
                        completeSubtask(subtaskId);
                    }
                });
            });

            document.querySelectorAll('.view-task').forEach(button => {
                button.addEventListener('click', function () {
                    const taskId = this.getAttribute('data-task-id');
                    const taskRow = document.querySelector(`.task-row[data-task-id="${taskId}"]`);
                    if (taskRow) {
                        loadTaskDetailsFromRow(taskRow);
                    }
                });
            });

            document.querySelectorAll('.view-subtask').forEach(button => {
                button.addEventListener('click', function () {
                    const subtaskId = this.getAttribute('data-subtask-id');
                    const subtaskRow = document.querySelector(`.subtask-row[data-subtask-id="${subtaskId}"]`);
                    if (subtaskRow) {
                        loadSubtaskDetailsFromRow(subtaskRow);
                    }
                });
            });

            const globalSearch = document.getElementById('globalSearch');
            if (globalSearch) {
                globalSearch.addEventListener('input', function () {
                    const searchTerm = this.value.toLowerCase();
                    filterTable(searchTerm);
                });
            }

            document.querySelectorAll('.date-filter').forEach(item => {
                item.addEventListener('click', function (e) {
                    e.preventDefault();
                    const filterType = this.getAttribute('data-filter');
                    applyDateFilter(filterType);
                    const dropdownButton = document.getElementById('dateFilterDropdown');
                    dropdownButton.innerHTML = `<i class="bi bi-calendar me-2"></i>${this.textContent}`;
                });
            });

            document.querySelectorAll('#taskTable th').forEach(header => {
                if (header.cellIndex !== 8) {
                    header.classList.add('sortable');
                    header.addEventListener('click', function () {
                        sortTable(this.cellIndex);
                    });
                }
            });

            document.addEventListener('click', function (e) {
                if (e.target.classList.contains('page-link')) {
                    e.preventDefault();
                    const page = parseInt(e.target.getAttribute('data-page'));
                    if (page && page !== currentPage) {
                        currentPage = page;
                        updatePagination();
                    }
                }
            });
        }

        function updatePagination() {
            const totalPages = Math.ceil(taskRows.length / entriesPerPage);
            const startIndex = (currentPage - 1) * entriesPerPage;
            const endIndex = Math.min(startIndex + entriesPerPage, taskRows.length);

            document.querySelectorAll('#taskTableBody tr').forEach(row => {
                row.style.display = 'none';
            });

            taskRows.forEach((row, index) => {
                if (index >= startIndex && index < endIndex) {
                    row.style.display = '';
                    if (row.classList.contains('task-row')) {
                        const taskId = row.getAttribute('data-task-id');
                        const subtaskRows = document.querySelectorAll(`.subtask-row[data-task-id="${taskId}"], .subtask-header-row[data-task-id="${taskId}"]`);
                        subtaskRows.forEach(subRow => {
                            subRow.style.display = '';
                        });
                    }
                }
            });

            showingStart.textContent = taskRows.length > 0 ? startIndex + 1 : 0;
            showingEnd.textContent = Math.min(endIndex, taskRows.length);
            totalEntries.textContent = taskRows.length;

            pagination.innerHTML = '';

            if (totalPages <= 1) {
                paginationInfo.textContent = `Showing ${showingStart.textContent} to ${showingEnd.textContent} of ${taskRows.length} entries`;
                return;
            }

            const maxVisiblePages = 5;
            let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
            let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

            if (endPage - startPage + 1 < maxVisiblePages) {
                startPage = Math.max(1, endPage - maxVisiblePages + 1);
            }

            if (currentPage > 1) {
                const prevLi = document.createElement('li');
                prevLi.className = 'page-item';
                prevLi.innerHTML = `<a class="page-link" href="#" data-page="${currentPage - 1}">Previous</a>`;
                pagination.appendChild(prevLi);
            }

            if (startPage > 1) {
                const firstLi = document.createElement('li');
                firstLi.className = 'page-item';
                firstLi.innerHTML = `<a class="page-link" href="#" data-page="1">1</a>`;
                pagination.appendChild(firstLi);
                if (startPage > 2) {
                    const ellipsisLi = document.createElement('li');
                    ellipsisLi.className = 'page-item disabled';
                    ellipsisLi.innerHTML = '<span class="page-link">...</span>';
                    pagination.appendChild(ellipsisLi);
                }
            }

            for (let i = startPage; i <= endPage; i++) {
                const pageLi = document.createElement('li');
                pageLi.className = `page-item ${i === currentPage ? 'active' : ''}`;
                pageLi.innerHTML = `<a class="page-link" href="#" data-page="${i}">${i}</a>`;
                pagination.appendChild(pageLi);
            }

            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    const ellipsisLi = document.createElement('li');
                    ellipsisLi.className = 'page-item disabled';
                    ellipsisLi.innerHTML = '<span class="page-link">...</span>';
                    pagination.appendChild(ellipsisLi);
                }
                const lastLi = document.createElement('li');
                lastLi.className = 'page-item';
                lastLi.innerHTML = `<a class="page-link" href="#" data-page="${totalPages}">${totalPages}</a>`;
                pagination.appendChild(lastLi);
            }

            if (currentPage < totalPages) {
                const nextLi = document.createElement('li');
                nextLi.className = 'page-item';
                nextLi.innerHTML = `<a class="page-link" href="#" data-page="${currentPage + 1}">Next</a>`;
                pagination.appendChild(nextLi);
            }

            paginationInfo.textContent = `Showing ${showingStart.textContent} to ${showingEnd.textContent} of ${taskRows.length} entries`;
        }

        function filterTable(searchTerm) {
            const allRows = document.querySelectorAll('#taskTableBody tr');
            taskRows = [];

            allRows.forEach(row => {
                if (row.classList.contains('task-row')) {
                    const text = row.textContent.toLowerCase();
                    if (text.includes(searchTerm)) {
                        taskRows.push(row);
                    }
                }
            });

            currentPage = 1;
            updatePagination();
        }

        function applyDateFilter(filterType) {
            const today = new Date();
            const allRows = document.querySelectorAll('#taskTableBody tr');
            taskRows = [];

            allRows.forEach(row => {
                if (row.classList.contains('task-row')) {
                    const dueDateStr = row.getAttribute('data-due-date');
                    const assignedDateStr = row.getAttribute('data-assigned-date');

                    if (!dueDateStr && !assignedDateStr) {
                        if (filterType === 'all') taskRows.push(row);
                        return;
                    }

                    let dateToCheck = dueDateStr || assignedDateStr;
                    const rowDate = new Date(dateToCheck);
                    let showRow = false;

                    switch (filterType) {
                        case 'today':
                            showRow = rowDate.toDateString() === today.toDateString();
                            break;
                        case 'week':
                            const weekAgo = new Date();
                            weekAgo.setDate(today.getDate() - 7);
                            showRow = rowDate >= weekAgo && rowDate <= today;
                            break;
                        case 'month':
                            const monthAgo = new Date();
                            monthAgo.setMonth(today.getMonth() - 1);
                            showRow = rowDate >= monthAgo && rowDate <= today;
                            break;
                        case 'sixmonths':
                            const sixMonthsAgo = new Date();
                            sixMonthsAgo.setMonth(today.getMonth() - 6);
                            showRow = rowDate >= sixMonthsAgo && rowDate <= today;
                            break;
                        case 'year':
                            const yearAgo = new Date();
                            yearAgo.setFullYear(today.getFullYear() - 1);
                            showRow = rowDate >= yearAgo && rowDate <= today;
                            break;
                        case 'all':
                            showRow = true;
                            break;
                    }

                    if (showRow) taskRows.push(row);
                }
            });

            currentPage = 1;
            updatePagination();
        }

        function sortTable(columnIndex) {
            const table = document.getElementById('taskTable');
            const tbody = table.querySelector('tbody');

            document.querySelectorAll('#taskTable th').forEach(th => {
                th.classList.remove('sort-asc', 'sort-desc');
            });

            if (currentSort.column === columnIndex) {
                currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
            } else {
                currentSort.column = columnIndex;
                currentSort.direction = 'asc';
            }

            const currentHeader = table.rows[0].cells[columnIndex];
            currentHeader.classList.add(currentSort.direction === 'asc' ? 'sort-asc' : 'sort-desc');

            const sortedRows = Array.from(taskRows).sort((a, b) => {
                let aValue, bValue;
                const aCell = a.cells[columnIndex];
                const bCell = b.cells[columnIndex];

                if (columnIndex === 0 || columnIndex === 1) {
                    aValue = aCell.textContent.trim().toLowerCase();
                    bValue = bCell.textContent.trim().toLowerCase();
                } else if (columnIndex === 2) {
                    aValue = aCell.querySelector('.badge').textContent.trim().toLowerCase();
                    bValue = bCell.querySelector('.badge').textContent.trim().toLowerCase();
                } else if (columnIndex === 3) {
                    aValue = aCell.textContent.trim().toLowerCase();
                    bValue = bCell.textContent.trim().toLowerCase();
                } else if (columnIndex === 4 || columnIndex === 5 || columnIndex === 6) {
                    aValue = new Date(a.getAttribute(columnIndex === 4 ? 'data-assigned-date' : columnIndex === 5 ? 'data-due-date' : 'data-completed-date') || 0);
                    bValue = new Date(b.getAttribute(columnIndex === 4 ? 'data-assigned-date' : columnIndex === 5 ? 'data-due-date' : 'data-completed-date') || 0);
                } else if (columnIndex === 7) {
                    const priorityMap = { 'high': 3, 'medium': 2, 'low': 1 };
                    aValue = priorityMap[aCell.querySelector('.badge').textContent.trim().toLowerCase().split(' ')[0]] || 0;
                    bValue = priorityMap[bCell.querySelector('.badge').textContent.trim().toLowerCase().split(' ')[0]] || 0;
                } else {
                    aValue = aCell.textContent.trim().toLowerCase();
                    bValue = bCell.textContent.trim().toLowerCase();
                }

                if (aValue < bValue) return currentSort.direction === 'asc' ? -1 : 1;
                if (aValue > bValue) return currentSort.direction === 'asc' ? 1 : -1;
                return 0;
            });

            taskRows = sortedRows;
            updatePagination();
        }

        function completeTask(taskId) {
            fetch(`/dashboard/task/complete/${taskId}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error completing task');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error completing task');
            });
        }

        function completeSubtask(subtaskId) {
            fetch(`/dashboard/subtask/complete/${subtaskId}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error completing subtask');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error completing subtask');
            });
        }

        function loadTaskDetailsFromRow(taskRow) {
            const taskName = taskRow.querySelector('td:nth-child(1) .fw-medium')?.textContent || taskRow.querySelector('td:nth-child(1) .text-decoration-line-through')?.textContent;
            const moduleInfo = taskRow.querySelector('td:nth-child(2)');
            const moduleName = moduleInfo.querySelector('.text-dark')?.textContent;
            const projectName = moduleInfo.querySelector('.text-primary')?.textContent;
            const accessType = taskRow.querySelector('td:nth-child(3) .badge')?.textContent;
            const assigneeInfo = taskRow.querySelector('td:nth-child(4)');
            const assigneeName = assigneeInfo.querySelector('.fw-semibold')?.textContent;
            const assigneeRole = assigneeInfo.querySelector('.text-muted')?.textContent;
            const assignedDate = taskRow.querySelector('td:nth-child(5) small')?.textContent;
            const dueDate = taskRow.querySelector('td:nth-child(6) small')?.textContent;
            const completedDate = taskRow.querySelector('td:nth-child(7) small')?.textContent;
            const completionStatus = taskRow.querySelector('td:nth-child(7) .badge')?.outerHTML || '';
            const priorityBadge = taskRow.querySelector('td:nth-child(8) .badge')?.outerHTML;
            const taskStatus = taskRow.querySelector('.task-icon i').classList.contains('bi-check2-square') ? 2 : 1;

            const html = `
                <div class="task-details">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <h4 class="mb-1">${taskName}</h4>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="badge ${taskStatus === 2 ? 'bg-success' : 'bg-warning'}">${taskStatus === 2 ? 'Completed' : 'In Progress'}</span>
                                ${priorityBadge}
                                <span class="badge ${accessType.includes('Public') ? 'bg-primary' : 'bg-secondary'}">
                                    <i class="bi ${accessType.includes('Public') ? 'bi-globe' : 'bi-lock'} me-1"></i>${accessType}
                                </span>
                            </div>
                        </div>
                        <div class="text-end">
                            <small class="text-muted">Assigned: ${assignedDate}</small>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Project</h6>
                            <p class="mb-1 fw-semibold">${projectName}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Module</h6>
                            <p class="mb-0 fw-semibold">${moduleName}</p>
                        </div>
                    </div>
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Assignee</h6>
                        <div class="d-flex align-items-center">${assigneeInfo.innerHTML}</div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <h6 class="text-muted mb-2">Deadline</h6>
                            <p class="mb-0 ${isOverdue(dueDate) ? 'text-danger fw-semibold' : ''}">
                                ${dueDate}${isOverdue(dueDate) ? '<i class="bi bi-exclamation-triangle ms-1"></i>' : ''}
                            </p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted mb-2">Completed Date</h6>
                            <p class="mb-0">${completedDate || '-'}</p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted mb-2">Status</h6>
                            <div>${completionStatus}</div>
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-info-circle me-2"></i>
                            <small>For full task details including description and attachments, please use the Edit option.</small>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('taskModalBody').innerHTML = html;
        }

        function loadSubtaskDetailsFromRow(subtaskRow) {
            const subtaskName = subtaskRow.querySelector('td:nth-child(1) .fw-medium')?.textContent || subtaskRow.querySelector('td:nth-child(1) .text-decoration-line-through')?.textContent;
            const accessType = subtaskRow.querySelector('td:nth-child(3) .badge')?.textContent;
            const assigneeInfo = subtaskRow.querySelector('td:nth-child(4)');
            const assignedDate = subtaskRow.querySelector('td:nth-child(5) small')?.textContent;
            const dueDate = subtaskRow.querySelector('td:nth-child(6) small')?.textContent;
            const completedDate = subtaskRow.querySelector('td:nth-child(7) small')?.textContent;
            const completionStatus = subtaskRow.querySelector('td:nth-child(7) .badge')?.outerHTML || '';
            const priorityBadge = subtaskRow.querySelector('td:nth-child(8) .badge')?.outerHTML;
            const subtaskStatus = subtaskRow.querySelector('.subtask-icon i').classList.contains('bi-check2-square') ? 2 : 1;

            const html = `
                <div class="subtask-details">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <h4 class="mb-1">${subtaskName}</h4>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="badge ${subtaskStatus === 2 ? 'bg-success' : 'bg-warning'}">${subtaskStatus === 2 ? 'Completed' : 'In Progress'}</span>
                                ${priorityBadge}
                                <span class="badge ${accessType.includes('Public') ? 'bg-primary' : 'bg-secondary'}">
                                    <i class="bi ${accessType.includes('Public') ? 'bi-globe' : 'bi-lock'} me-1"></i>${accessType}
                                </span>
                            </div>
                        </div>
                        <div class="text-end">
                            <small class="text-muted">Assigned: ${assignedDate}</small>
                        </div>
                    </div>
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Assignee</h6>
                        <div class="d-flex align-items-center">${assigneeInfo.innerHTML}</div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <h6 class="text-muted mb-2">Deadline</h6>
                            <p class="mb-0 ${isOverdue(dueDate) ? 'text-danger fw-semibold' : ''}">
                                ${dueDate}${isOverdue(dueDate) ? '<i class="bi bi-exclamation-triangle ms-1"></i>' : ''}
                            </p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted mb-2">Completed Date</h6>
                            <p class="mb-0">${completedDate || '-'}</p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted mb-2">Status</h6>
                            <div>${completionStatus}</div>
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-info-circle me-2"></i>
                            <small>For full subtask details including description and attachments, please use the Edit option.</small>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('subtaskModalBody').innerHTML = html;
        }

        function isOverdue(dueDate) {
            if (!dueDate || dueDate === 'No deadline') return false;
            const parts = dueDate.split('-');
            if (parts.length !== 3) return false;
            const due = new Date(parts[2], parts[1] - 1, parts[0]);
            const today = new Date();
            return due < today;
        }

        setupEventListeners();
        taskRows = Array.from(document.querySelectorAll('#taskTableBody .task-row'));
        updatePagination();
    });
</script>
