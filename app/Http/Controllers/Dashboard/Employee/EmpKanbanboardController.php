<?php

namespace App\Http\Controllers\Dashboard\Employee;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Modulo;
use App\Models\Task;
use App\Models\Subtask;
use App\Models\PmtsImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EmpKanbanboardController extends Controller
{
    public function empkanbanboard()
    {
        $authUser = auth()->user();
        $employeeId = $authUser->employeerole_id;

        Log::info("Fetching projects for employee ID: {$employeeId}");

        // Get projects where employee is a member, lead, or head
        $projects = Project::with([
            'projectLead',
            'projectHead',
            'client',
            'pmtsImages',
            'modules' => function ($query) use ($employeeId) {
                $query->where('delete_status', 1)
                    ->where(function ($q) use ($employeeId) {
                        $q->where('mod_member', 'LIKE', '%"' . $employeeId . '"%')
                            ->orWhere('mod_member', 'LIKE', "%{$employeeId}%")
                            ->orWhereRaw('JSON_CONTAINS(mod_member, ?)', [json_encode($employeeId)]);
                    });
            },
            'modules.tasks' => function ($query) use ($employeeId) {
                $query->where('delete_status', 1)
                    ->where(function ($q) use ($employeeId) {
                        $q->where('task_assignedto', 'LIKE', '%"' . $employeeId . '"%')
                            ->orWhere('task_assignedto', 'LIKE', "%{$employeeId}%")
                            ->orWhereRaw('JSON_CONTAINS(task_assignedto, ?)', [json_encode($employeeId)]);
                    })
                    ->with(['subtasks' => function ($q) use ($employeeId) {
                        $q->where('delete_status', 1)
                            ->where(function ($sq) use ($employeeId) {
                                $sq->where('stask_assignedto', 'LIKE', '%"' . $employeeId . '"%')
                                    ->orWhere('stask_assignedto', 'LIKE', "%{$employeeId}%")
                                    ->orWhereRaw('JSON_CONTAINS(stask_assignedto, ?)', [json_encode($employeeId)]);
                            });
                    }]);
            }
        ])
            ->where('delete_status', 1)
            ->where(function ($query) use ($employeeId) {
                // Check if employee is project lead
                $query->where('pro_lead', $employeeId);

                // Check if employee is project head
                $query->orWhere('pro_head', $employeeId);

                // Check if employee is in members array
                $query->orWhere(function ($q) use ($employeeId) {
                    // Multiple methods to handle different member ID formats
                    $q->where('pro_member', 'LIKE', '%"' . $employeeId . '"%')
                        ->orWhere('pro_member', 'LIKE', "%{$employeeId}%")
                        ->orWhereRaw('JSON_CONTAINS(pro_member, ?)', [json_encode($employeeId)]);
                });
            })
            ->get();

        Log::info("Found {$projects->count()} projects for employee {$employeeId}");

        // Process project data for the view
        $processedProjects = $projects->map(function ($project) use ($employeeId) {
            // Parse member IDs
            $memberIds = $this->parseMemberIds($project->pro_member);

            Log::info("Parsed member IDs for project {$project->pro_id}: " . print_r($memberIds, true));

            // Get member names
            $memberNames = [];
            if (!empty($memberIds)) {
                $members = \App\Models\Employee::whereIn('emp_id', $memberIds)
                    ->get(['emp_id', 'fullname']);
                $memberNames = $members->pluck('fullname')->toArray();
            }

            // Calculate overdue status
            $today = \Carbon\Carbon::now();
            $deadline = $project->pro_deadline ? \Carbon\Carbon::parse($project->pro_deadline) : null;
            $overdueDays = 0;
            $isOverdue = false;

            if ($deadline) {
                if ($project->pro_status === 2 && $project->pro_complete) {
                    $completionDate = $project->pro_complete ? \Carbon\Carbon::parse($project->pro_complete) : $today;
                    if ($completionDate->gt($deadline)) {
                        $overdueDays = $deadline->diffInDays($completionDate);
                        $isOverdue = true;
                    }
                } else {
                    if ($today->gt($deadline)) {
                        $overdueDays = $deadline->diffInDays($today);
                        $isOverdue = true;
                    }
                }
            }

            // Get project avatar
            $projectAvatar = null;
            if ($project->pro_avater) {
                $projectAvatar = asset($project->pro_avater);
            } else {
                $pmtsImages = $project->pmtsImages;
                foreach ($pmtsImages as $pmtsImage) {
                    $extension = pathinfo($pmtsImage->pmtsimage_name, PATHINFO_EXTENSION);
                    if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                        $projectAvatar = asset('project_attachments/' . $pmtsImage->pmtsimage_name);
                        break;
                    }
                }
            }

            // Process attachments
            $projectFiles = [];
            $pmtsImages = $project->pmtsImages;
            if ($pmtsImages->count() > 0) {
                foreach ($pmtsImages as $pmtsImage) {
                    $projectFiles[] = [
                        'url' => asset('project_attachments/' . $pmtsImage->pmtsimage_name),
                        'name' => $pmtsImage->pmtsimage_name,
                        'type' => pathinfo($pmtsImage->pmtsimage_name, PATHINFO_EXTENSION),
                    ];
                }
            }

            // Get assigned modules count and progress
            $modules = $project->modules ?? collect();
            $totalModules = $modules->count();
            $completedModules = $modules->where('mod_status', 2)->count();
            $moduleProgress = $totalModules > 0 ? round(($completedModules / $totalModules) * 100) : 0;

            return [
                'project' => $project,
                'memberNames' => $memberNames,
                'memberIds' => $memberIds,
                'overdueDays' => $overdueDays,
                'isOverdue' => $isOverdue,
                'projectAvatar' => $projectAvatar,
                'projectFiles' => $projectFiles,
                'totalModules' => $totalModules,
                'completedModules' => $completedModules,
                'moduleProgress' => $moduleProgress,
                'modules' => $modules
            ];
        });

        return view('dashboard.employee.empkanbanboard.project', compact('processedProjects'));
    }

    /**
     * Parse member IDs from various formats
     */
    private function parseMemberIds($proMember)
    {
        if (empty($proMember)) {
            return [];
        }

        $memberIds = [];

        try {
            // If it's already an array, return it
            if (is_array($proMember)) {
                return array_filter(array_map('intval', $proMember), function ($id) {
                    return $id > 0;
                });
            }

            // If it's a JSON string
            if (is_string($proMember)) {
                $decoded = json_decode($proMember, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    return array_filter(array_map('intval', $decoded), function ($id) {
                        return $id > 0;
                    });
                }

                // If JSON decode failed, try to parse as comma-separated string
                $cleaned = trim($proMember, ' "[]');
                if (!empty($cleaned)) {
                    $parts = explode(',', $cleaned);
                    foreach ($parts as $part) {
                        $cleanPart = trim($part, ' "\\[]');
                        if (is_numeric($cleanPart) && $cleanPart > 0) {
                            $memberIds[] = (int)$cleanPart;
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error("Error parsing member IDs: " . $e->getMessage());
        }

        return array_unique($memberIds);
    }

    public function getProjectDetails($id)
    {
        $authUser = auth()->user();
        $employeeId = $authUser->employeerole_id;

        $project = Project::with([
            'projectLead',
            'projectHead',
            'client',
            'pmtsImages',
            'modules' => function ($query) use ($employeeId) {
                $query->where('delete_status', 1)
                    ->where(function ($q) use ($employeeId) {
                        $q->where('mod_member', 'LIKE', '%"' . $employeeId . '"%')
                            ->orWhere('mod_member', 'LIKE', "%{$employeeId}%")
                            ->orWhereRaw('JSON_CONTAINS(mod_member, ?)', [json_encode($employeeId)]);
                    })
                    ->with(['tasks' => function ($q) use ($employeeId) {
                        $q->where('delete_status', 1)
                            ->where(function ($sq) use ($employeeId) {
                                $sq->where('task_assignedto', 'LIKE', '%"' . $employeeId . '"%')
                                    ->orWhere('task_assignedto', 'LIKE', "%{$employeeId}%")
                                    ->orWhereRaw('JSON_CONTAINS(task_assignedto, ?)', [json_encode($employeeId)]);
                            })
                            ->with(['subtasks' => function ($sq) use ($employeeId) {
                                $sq->where('delete_status', 1)
                                    ->where(function ($ssq) use ($employeeId) {
                                        $ssq->where('stask_assignedto', 'LIKE', '%"' . $employeeId . '"%')
                                            ->orWhere('stask_assignedto', 'LIKE', "%{$employeeId}%")
                                            ->orWhereRaw('JSON_CONTAINS(stask_assignedto, ?)', [json_encode($employeeId)]);
                                    });
                            }]);
                    }]);
            }
        ])
            ->where('pro_id', $id)
            ->where('delete_status', 1)
            ->where(function ($query) use ($employeeId) {
                $query->where('pro_lead', $employeeId)
                    ->orWhere('pro_head', $employeeId)
                    ->orWhere(function ($q) use ($employeeId) {
                        $q->where('pro_member', 'LIKE', '%"' . $employeeId . '"%')
                            ->orWhere('pro_member', 'LIKE', "%{$employeeId}%")
                            ->orWhereRaw('JSON_CONTAINS(pro_member, ?)', [json_encode($employeeId)]);
                    });
            })
            ->firstOrFail();

        // Calculate project statistics for assigned items only
        $totalModules = $project->modules->count();
        $completedModules = $project->modules->where('mod_status', 2)->count();
        $moduleProgress = $totalModules > 0 ? round(($completedModules / $totalModules) * 100) : 0;

        $totalTasks = 0;
        $completedTasks = 0;
        $totalSubtasks = 0;
        $completedSubtasks = 0;

        foreach ($project->modules as $module) {
            $totalTasks += $module->tasks->count();
            $completedTasks += $module->tasks->where('task_status', 2)->count();

            foreach ($module->tasks as $task) {
                $totalSubtasks += $task->subtasks->count();
                $completedSubtasks += $task->subtasks->where('subtask_status', 2)->count();
            }
        }

        $taskProgress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
        $subtaskProgress = $totalSubtasks > 0 ? round(($completedSubtasks / $totalSubtasks) * 100) : 0;

        return response()->json([
            'success' => true,
            'project' => $project,
            'statistics' => [
                'modules' => [
                    'total' => $totalModules,
                    'completed' => $completedModules,
                    'progress' => $moduleProgress
                ],
                'tasks' => [
                    'total' => $totalTasks,
                    'completed' => $completedTasks,
                    'progress' => $taskProgress
                ],
                'subtasks' => [
                    'total' => $totalSubtasks,
                    'completed' => $completedSubtasks,
                    'progress' => $subtaskProgress
                ]
            ]
        ]);
    }
}
