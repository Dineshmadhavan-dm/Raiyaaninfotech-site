<x-kanbandashboardlayout>

    @section('title', 'Kanban Dashboard')

    <div class="container-fluid p-4">

        <x-message />

        <!-- Header Section -->
        <div class="row mb-5 align-items-center justify-content-between" style="margin-top: 2em;">

            <div class="col-auto">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item  text-dark fw-semibold ">Kanban Board</li>
                    </ol>
                </nav>
            </div>

            <div class="col-auto">
                <a href="{{ route('dhome') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-left me-2"></i>Main Dashboard
                </a>
            </div>

        </div>


        <div class="d-flex justify-content-end gap-4 mb-3">

            <div class="square-completed col-auto">
                <span class="completed-s">
                    <i class="bi bi-check2"></i>
                </span>
                <span class="count-s">Completed</span>
            </div>

            <div class="square-progress col-auto">
                <span class="progress-s">
                    <i class="bi bi-hourglass-split"></i>
                </span>
                <span class="count-s">In Progress</span>
            </div>

            <div class="square-created col-auto">
                <span class="created-s">
                    <i class="bi bi-plus-lg"></i>
                </span>
                <span class="count-s">Created</span>
            </div>

        </div>



        <style>
            .square-created,
            .square-progress,
            .square-completed {
                display: flex;
                align-items: center;
                gap: 8px;
            }

            /* Common shape styles */
            .created-s,
            .progress-s,
            .completed-s {
                display: flex;
                /* flex to center icon */
                justify-content: center;
                align-items: center;
                width: 18px;
                height: 18px;
                border-radius: 4px;
                color: #fff;
                /* icon color */
                font-size: 12px;
                /* icon size */
            }

            /* Colors */
            .created-s {
                background-color: #6b7280;
            }

            .progress-s {
                background-color: #f59e0b;
            }

            .completed-s {
                background-color: #10b981;
            }

            .count-s {
                font-weight: 600;
                font-size: 1rem;
            }
        </style>


        <!-- Stats Overview Cards -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="stats-grid">
                    <!-- Projects Card -->
                    <div class="stat-card project-card">
                        <div class="stat-icon">
                            <div class="icon-wrapper">
                                <i class="bi bi-kanban"></i>
                            </div>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number">{{ $projectcount->count() }}</div>
                            <div class="stat-title">Total Projects</div>
                            <div class="stat-breakdown">

                                <div class="breakdown-item">
                                    <span class="dot completed"></span>
                                    <span class="count">{{ $projectcount->where('pro_status', 2)->count() }}</span>
                                </div>
                                <div class="breakdown-item">
                                    <span class="dot progress"></span>
                                    <span class="count">{{ $projectcount->where('pro_status', 1)->count() }}</span>
                                </div>
                                <div class="breakdown-item">
                                    <span class="dot created"></span>
                                    <span class="count">{{ $projectcount->where('pro_status', 0)->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modules Card -->
                    <div class="stat-card module-card">
                        <div class="stat-icon">
                            <div class="icon-wrapper">
                                <i class="bi bi-diagram-3"></i>
                            </div>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number">{{ $modulocount->count() }}</div>
                            <div class="stat-title">Total Modules</div>
                            <div class="stat-breakdown">
                                <div class="breakdown-item">
                                    <span class="dot completed"></span>
                                    <span class="count">{{ $modulocount->where('mod_status', 2)->count() }}</span>
                                </div>

                                <div class="breakdown-item">
                                    <span class="dot progress"></span>
                                    <span class="count">{{ $modulocount->where('mod_status', 1)->count() }}</span>
                                </div>
                                <div class="breakdown-item">
                                    <span class="dot created"></span>
                                    <span class="count">{{ $modulocount->where('mod_status', 0)->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tasks Card -->
                    <div class="stat-card task-card">
                        <div class="stat-icon">
                            <div class="icon-wrapper">
                                <i class="bi bi-clipboard-check"></i>
                            </div>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number">{{ $taskcount->count() }}</div>
                            <div class="stat-title">Total Tasks</div>
                            <div class="stat-breakdown">
                                <div class="breakdown-item">
                                    <span class="dot completed"></span>
                                    <span class="count">{{ $taskcount->where('task_status', 2)->count() }}</span>
                                </div>

                                <div class="breakdown-item">
                                    <span class="dot progress"></span>
                                    <span class="count">{{ $taskcount->where('task_status', 1)->count() }}</span>
                                </div>
                                <div class="breakdown-item">
                                    <span class="dot created"></span>
                                    <span class="count">{{ $taskcount->where('task_status', 0)->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Subtasks Card -->
                    <div class="stat-card subtask-card">
                        <div class="stat-icon">
                            <div class="icon-wrapper">
                                <i class="bi bi-list-check"></i>
                            </div>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number">{{ $subtaskcount->count() }}</div>
                            <div class="stat-title">Total Subtasks</div>
                            <div class="stat-breakdown">
                                <div class="breakdown-item">
                                    <span class="dot completed"></span>
                                    <span class="count">{{ $subtaskcount->where('stask_status', 2)->count() }}</span>
                                </div>

                                <div class="breakdown-item">
                                    <span class="dot progress"></span>
                                    <span class="count">{{ $subtaskcount->where('stask_status', 1)->count() }}</span>
                                </div>
                                <div class="breakdown-item">
                                    <span class="dot created"></span>
                                    <span class="count">{{ $subtaskcount->where('stask_status', 0)->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>

    <style>
        :root {
            --primary-color: #4477b6;
            --primary-light: #e8f0fe;
            --primary-dark: #3a6aa0;
            --success-color: #10b981;
            --info-color: #06b6d4;
            --warning-color: #f59e0b;
            --created-color: #6b7280;
            --progress-color: #f59e0b;
            --completed-color: #10b981;
            --created-bg: #f9fafb;
            --progress-bg: #fffbeb;
            --completed-bg: #ecfdf5;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --card-shadow-hover: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }






        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            box-shadow: var(--card-shadow);
            border: 1px solid #f1f5f9;
        }

        .stat-icon .icon-wrapper {
            width: 64px;
            height: 64px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .project-card .icon-wrapper {
            background: linear-gradient(135deg, var(--primary-light) 0%, #dbeafe 100%);
            color: var(--primary-color);
        }

        .module-card .icon-wrapper {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            color: var(--success-color);
        }

        .task-card .icon-wrapper {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            color: var(--info-color);
        }

        .subtask-card .icon-wrapper {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            color: var(--warning-color);
        }

        .stat-content {
            flex: 1;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: #1e293b;
            line-height: 1;
            margin-bottom: 0.25rem;
        }

        .stat-title {
            font-size: 0.875rem;
            color: #64748b;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .stat-breakdown {
            display: flex;
            gap: 1rem;
        }

        .breakdown-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .dot.created {
            background-color: var(--created-color);
        }

        .dot.progress {
            background-color: var(--progress-color);
        }

        .dot.completed {
            background-color: var(--completed-color);
        }

        .breakdown-item .count {
            font-size: 0.875rem;
            font-weight: 700;
            color: #374151;
        }
    </style>

</x-kanbandashboardlayout>