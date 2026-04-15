<?php

namespace App\Http\Controllers\Dashboard\HR\Kanbanboard;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Subtask;
use App\Models\Modulo;
use App\Models\Employee;
use App\Models\PmtsImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Str;

class TaskController extends Controller
{
        public function __construct()
{
    // Restrict all Kanban board routes to only Super Admin (categorie 1) and Admin (categorie 3)
    $this->middleware(function ($request, $next) {
        $user = auth()->user();

        // Check user category - only allow 1 (Super Admin) and 3 (Admin)
        if ($user->categorie == 2) { // Employee
            return redirect()->route('emphome')->with('error', 'Access denied .');
        }

        return $next($request);
    });


}
    // public function __construct()
    // {
    //     $this->middleware('permission:task view')->only(['tasklist']);
    //     $this->middleware('permission:task create')->only(['taskcreate', 'taskstore']);
    //     $this->middleware('permission:task edit')->only(['taskedit', 'taskupdate']);
    //     $this->middleware('permission:task delete')->only(['taskdestroy']);
    // }

 public function tasklist(Request $request)
{
    $query = Task::with(['modulo', 'modulo.project', 'modulo.project.client', 'pmtsImages', 'subtasks', 'subtasks.pmtsImages', 'assignedEmployee'])
        ->where('delete_status', 1);

    // Apply filters based on URL parameters
    $filter = $request->get('filter');

    if ($filter) {
        switch ($filter) {
            case 'ontime':
                // Tasks completed on or before deadline
                $query->where('task_status', 2)
                      ->whereNotNull('task_complete')
                      ->whereColumn('task_complete', '<=', 'task_deadline');
                break;

            case 'progress':
                // Tasks in progress
                $query->where('task_status', 1);
                break;

            case 'overdue':
                // Tasks that are overdue
                $query->where(function($q) {
                    $q->where('task_status', 1)
                      ->where('task_deadline', '<', Carbon::now());
                })->orWhere(function($q) {
                    $q->where('task_status', 2)
                      ->whereNotNull('task_complete')
                      ->whereColumn('task_complete', '>', 'task_deadline');
                });
                break;

            case 'subtask-ontime':
                // Filter tasks that have subtasks completed on time
                $query->whereHas('subtasks', function($q) {
                    $q->where('delete_status', 1)
                      ->where('stask_status', 2)
                      ->whereNotNull('stask_complete')
                      ->whereColumn('stask_complete', '<=', 'stask_deadline');
                });
                break;

            case 'subtask-progress':
                // Filter tasks that have subtasks in progress
                $query->whereHas('subtasks', function($q) {
                    $q->where('delete_status', 1)
                      ->where('stask_status', 1);
                });
                break;

            case 'subtask-overdue':
                // Filter tasks that have overdue subtasks
                $query->whereHas('subtasks', function($q) {
                    $q->where('delete_status', 1)
                      ->where(function($subq) {
                          $subq->where('stask_status', 1)
                               ->where('stask_deadline', '<', Carbon::now());
                      })->orWhere(function($subq) {
                          $subq->where('stask_status', 2)
                               ->whereNotNull('stask_complete')
                               ->whereColumn('stask_complete', '>', 'stask_deadline');
                      });
                });
                break;
        }
    }

    $tasks = $query->get();
    $modulos = Modulo::where('delete_status', 1)->get(['mod_id', 'mod_name']);

    return view('dashboard.hr.kanbanboard.task.list', compact('tasks', 'modulos'));
}
    public function taskcreate($moduloId = null)
    {
        $modulo = null;
        $moduloMembers = [];

        if ($moduloId) {
            $modulo = Modulo::with(['project', 'project.client'])->find($moduloId);

            if ($modulo) {
                try {
                    $memberData = $modulo->mod_member;
                    $memberIds = [];

                    if (is_string($memberData)) {
                        $decoded = json_decode($memberData, true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                            $memberIds = $decoded;
                        }
                    } elseif (is_array($memberData)) {
                        $memberIds = $memberData;
                    }

                    if (!empty($memberIds)) {
                        $moduloMembers = Employee::whereIn('emp_id', $memberIds)
                            ->get(['emp_id', 'fullname', 'employee_id', 'email_company as email', 'image']);
                    }
                } catch (\Exception $e) {
                    \Log::error('Error parsing modulo members for task create: ' . $e->getMessage());
                }
            }
        }

        return view('dashboard.hr.kanbanboard.task.create', compact('modulo', 'moduloMembers'));
    }

    public function taskstore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_name' => 'required|string|max:255',
            'task_desc' => 'required|string',
            'task_avater' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'task_attachment' => 'nullable|array',
            'task_attachment.*' => 'file|mimes:jpg,jpeg,png,pdf,ppt,pptx,csv,xlsx,xls,doc,docx|max:10240',
            'task_deadline' => 'required|date',
            'task_modulo' => 'required|exists:modulos,mod_id',
            // CHANGED: Updated validation for single assigned person
            'task_assignedto' => 'required|exists:employees,emp_id',
            'task_priority' => 'required|in:1,2,3',
            'task_accessmod' => 'required|boolean',
            'task_status' => 'required|in:0,1,2',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $avatarPath = null;
            if ($request->hasFile('task_avater')) {
                $file = $request->file('task_avater');
                $currentDateTime = Carbon::now()->format('dmYHis');
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $fileName = $currentDateTime . '_' . Str::slug($originalName) . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('task_avatars');

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }

                $file->move($destinationPath, $fileName);
                $avatarPath = 'task_avatars/' . $fileName;
            }

            $today = Carbon::now();
            $deadline = Carbon::parse($request->task_deadline);
            $overdueDays = 0;

            if ($today->gt($deadline)) {
                $overdueDays = $deadline->diffInDays($today);
            }

            $onProgressDate = null;
            $completeDate = null;

            if ($request->task_status == 1) {
                $onProgressDate = Carbon::now();
            } elseif ($request->task_status == 2) {
                $onProgressDate = Carbon::now();
                $completeDate = Carbon::now();

                if ($completeDate->gt($deadline)) {
                    $overdueDays = $deadline->diffInDays($completeDate);
                }
            }

            // CHANGED: Updated to use task_assignedto instead of task_member
            $task = Task::create([
                'task_name' => $request->task_name,
                'task_desc' => $request->task_desc,
                'task_avater' => $avatarPath,
                'task_attachment' => json_encode([]),
                'task_deadline' => $request->task_deadline,
                'task_modulo' => $request->task_modulo,
                'task_assignedto' => $request->task_assignedto, // Single person assignment
                'task_priority' => $request->task_priority,
                'task_accessmod' => $request->task_accessmod,
                'delete_status' => 1,
                'task_status' => $request->task_status,
                'task_overdue' => $overdueDays,
                'task_onprogress' => $onProgressDate,
                'task_complete' => $completeDate,
            ]);

            $pmtsIds = [];
            if ($request->hasFile('task_attachment')) {
                foreach ($request->file('task_attachment') as $file) {
                    $currentDateTime = Carbon::now()->format('dmYHis');
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $fileName = $currentDateTime . '_' . Str::slug($originalName) . '.' . $file->getClientOriginalExtension();
                    $destinationPath = public_path('task_attachments');

                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0777, true);
                    }

                    $file->move($destinationPath, $fileName);
                    $filePath = 'task_attachments/' . $fileName;

                    $pmtsImage = PmtsImage::create([
                        'pmtsimage_name' => $fileName,
                        'menu_id' => $task->task_id,
                        'menu_type' => 'task',
                        'delete_status' => 1,
                    ]);

                    $pmtsIds[] = $pmtsImage->pmts_id;
                }

                $task->update([
                    'task_attachment' => json_encode($pmtsIds)
                ]);
            }
   if ($request->has('send_email') && $request->send_email == 1) {
            $task->sendEmailNotification(false);
        }
            return redirect()->route('tasklist')
                ->with('success', 'Task created successfully!');
        } catch (\Exception $e) {
            \Log::error('Task creation error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error creating task: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function taskedit($id)
    {
        try {
            // CHANGED: Updated to include assignedEmployee relationship
            $task = Task::with(['modulo', 'modulo.project', 'pmtsImages', 'assignedEmployee'])->findOrFail($id);

            $moduloMembers = [];
            if ($task->modulo && $task->modulo->mod_member) {
                try {
                    $memberData = $task->modulo->mod_member;
                    $memberIds = [];

                    if (is_string($memberData)) {
                        $decoded = json_decode($memberData, true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                            $memberIds = $decoded;
                        }
                    } elseif (is_array($memberData)) {
                        $memberIds = $memberData;
                    }

                    if (!empty($memberIds)) {
                        $moduloMembers = Employee::whereIn('emp_id', $memberIds)
                            ->get(['emp_id', 'fullname', 'employee_id', 'email_company as email', 'image']);
                    }
                } catch (\Exception $e) {
                    \Log::error('Error parsing modulo members in task edit: ' . $e->getMessage());
                }
            }

            return view('dashboard.hr.kanbanboard.task.edit', compact('task', 'moduloMembers'));
        } catch (\Exception $e) {
            \Log::error('Error fetching task for edit: ' . $e->getMessage());
            return redirect()->route('tasklist')
                ->with('error', 'Task not found.');
        }
    }

    public function taskupdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'task_name' => 'required|string|max:255',
            'task_desc' => 'required|string',
            'task_avater' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'task_attachment' => 'nullable|array',
            'task_attachment.*' => 'file|mimes:jpg,jpeg,png,pdf,ppt,pptx,csv,xlsx,xls,doc,docx|max:10240',
            'task_deadline' => 'required|date',
            // CHANGED: Updated validation for single assigned person
            'task_assignedto' => 'required|exists:employees,emp_id',
            'task_priority' => 'required|in:1,2,3',
            'task_accessmod' => 'required|boolean',
            'task_status' => 'required|in:0,1,2',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $task = Task::findOrFail($id);

            $avatarPath = $task->task_avater;
            if ($request->hasFile('task_avater')) {
                if ($avatarPath && file_exists(public_path($avatarPath))) {
                    unlink(public_path($avatarPath));
                }

                $file = $request->file('task_avater');
                $currentDateTime = Carbon::now()->format('dmYHis');
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $fileName = $currentDateTime . '_' . Str::slug($originalName) . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('task_avatars');

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }

                $file->move($destinationPath, $fileName);
                $avatarPath = 'task_avatars/' . $fileName;
            }

            if ($request->has('remove_avater') && $request->remove_avater) {
                if ($avatarPath && file_exists(public_path($avatarPath))) {
                    unlink(public_path($avatarPath));
                }
                $avatarPath = null;
            }

            $existingPmtsIds = [];
            if ($task->task_attachment) {
                $existingPmtsIds = json_decode($task->task_attachment, true) ?? [];
            }

            $newPmtsIds = [];
            if ($request->hasFile('task_attachment')) {
                foreach ($request->file('task_attachment') as $file) {
                    $currentDateTime = Carbon::now()->format('dmYHis');
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $fileName = $currentDateTime . '_' . Str::slug($originalName) . '.' . $file->getClientOriginalExtension();
                    $destinationPath = public_path('task_attachments');

                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0777, true);
                    }

                    $file->move($destinationPath, $fileName);
                    $filePath = 'task_attachments/' . $fileName;

                    $pmtsImage = PmtsImage::create([
                        'pmtsimage_name' => $fileName,
                        'menu_id' => $task->task_id,
                        'menu_type' => 'task',
                        'delete_status' => 1,
                    ]);

                    $newPmtsIds[] = $pmtsImage->pmts_id;
                }
            }

            $removePmtsIds = $request->input('remove_attachments', []);
            if (!empty($removePmtsIds)) {
                PmtsImage::whereIn('pmts_id', $removePmtsIds)
                    ->update(['delete_status' => 0]);

                $existingPmtsIds = array_diff($existingPmtsIds, $removePmtsIds);
            }

            $allPmtsIds = array_merge($existingPmtsIds, $newPmtsIds);

            // CHANGED: Updated to use task_assignedto instead of task_member
            $updateData = [
                'task_name' => $request->task_name,
                'task_desc' => $request->task_desc,
                'task_avater' => $avatarPath,
                'task_attachment' => !empty($allPmtsIds) ? json_encode($allPmtsIds) : json_encode([]),
                'task_deadline' => $request->task_deadline,
                'task_assignedto' => $request->task_assignedto, // Single person assignment
                'task_priority' => $request->task_priority,
                'task_accessmod' => $request->task_accessmod,
                'task_status' => $request->task_status,
            ];

            if ($request->task_status == 1 && !$task->task_onprogress) {
                $updateData['task_onprogress'] = Carbon::now();
            }

            if ($request->task_status == 2) {
                if (!$task->task_complete) {
                    $updateData['task_complete'] = Carbon::now();
                }

                $completionDate = $task->task_complete ? Carbon::parse($task->task_complete) : Carbon::now();
                $deadline = Carbon::parse($request->task_deadline);

                if ($completionDate->gt($deadline)) {
                    $updateData['task_overdue'] = $deadline->diffInDays($completionDate);
                } else {
                    $updateData['task_overdue'] = 0;
                }
            } else {
                $today = Carbon::now();
                $deadline = Carbon::parse($request->task_deadline);

                if ($today->gt($deadline)) {
                    $updateData['task_overdue'] = $deadline->diffInDays($today);
                } else {
                    $updateData['task_overdue'] = 0;
                }
            }

            $task->update($updateData);
 if ($request->has('send_email') && $request->send_email == 1) {
            $task->sendEmailNotification(true);
        }

            return redirect()->route('tasklist')
                ->with('success', 'Task updated successfully!');
        } catch (\Exception $e) {
            \Log::error('Task update error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error updating task: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function taskdestroy($id)
    {
        try {
            $task = Task::findOrFail($id);

            PmtsImage::where('menu_id', $id)
                ->where('menu_type', 'task')
                ->update(['delete_status' => 0]);

            $task->update([
                'delete_status' => 0
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Task deleted successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Task deletion error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error deleting task'
            ], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $task = Task::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'task_status' => 'required|in:0,1,2'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status value'
                ], 400);
            }

            $task->updateStatus($request->task_status);

            return response()->json([
                'success' => true,
                'message' => 'Task status updated successfully',
                'status_text' => $task->status_text,
                'status_badge' => $task->status_badge_class,
                'overdue_text' => $task->overdue_text
            ]);
        } catch (\Exception $e) {
            \Log::error('Task status update error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error updating task status'
            ], 500);
        }
    }

    public function getPmtsImageDetails(Request $request)
    {
        $pmtsIds = $request->input('pmts_ids', []);

        if (empty($pmtsIds)) {
            return response()->json(['images' => []]);
        }

        $images = PmtsImage::whereIn('pmts_id', $pmtsIds)
            ->where('delete_status', 1)
            ->get()
            ->map(function ($image) {
                return [
                    'pmts_id' => $image->pmts_id,
                    'name' => $image->pmtsimage_name,
                    'url' => asset('task_attachments/' . $image->pmtsimage_name),
                    'type' => pathinfo($image->pmtsimage_name, PATHINFO_EXTENSION)
                ];
            })
            ->toArray();

        return response()->json(['images' => $images]);
    }

    public function getTaskDetails($id)
    {
        try {
            \Log::info('Fetching task details for ID: ' . $id);

            // CHANGED: Updated to include assignedEmployee relationship
            $task = Task::with([
                'modulo',
                'modulo.project',
                'modulo.project.client',
                'pmtsImages',
                'assignedEmployee', // Added assigned employee relationship
                'subtasks' => function ($query) {
                    $query->where('delete_status', 1);
                }
            ])->where('delete_status', 1)->find($id);

            if (!$task) {
                \Log::error('Task not found with ID: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Task not found'
                ], 404);
            }

            // CHANGED: Get assigned employee name instead of multiple members
            $assignedEmployeeName = null;
            if ($task->assignedEmployee) {
                $assignedEmployeeName = $task->assignedEmployee->fullname;
            }

            $attachments = [];
            if ($task->task_attachment) {
                $attachmentIds = json_decode($task->task_attachment, true) ?? [];
                if (!empty($attachmentIds)) {
                    $attachments = PmtsImage::whereIn('pmts_id', $attachmentIds)
                        ->where('delete_status', 1)
                        ->get();
                }
            }

            // CHANGED: Pass assignedEmployeeName instead of memberNames
            $html = view('dashboard.hr.kanbanboard.task.partials.task-details', compact('task', 'assignedEmployeeName', 'attachments'))->render();

            return response()->json([
                'success' => true,
                'html' => $html
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading task details: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Task not found'
            ], 404);
        }
    }

    public function completeTask($id)
    {
        try {
            $task = Task::findOrFail($id);
            $task->update([
                'task_status' => 2,
                'task_complete' => Carbon::now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Task marked as completed'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error completing task'
            ], 500);
        }
    }

    public function reopenTask($id)
    {
        try {
            $task = Task::findOrFail($id);
            $task->update([
                'task_status' => 1,
                'task_complete' => null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Task reopened'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error reopening task'
            ], 500);
        }
    }

    public function reopenWithDeadline(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_id' => 'required|integer',
            'item_type' => 'required|in:task,subtask',
            'new_deadline' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input data'
            ], 400);
        }

        try {
            if ($request->item_type === 'task') {
                $task = Task::findOrFail($request->item_id);
                $task->update([
                    'task_status' => 1,
                    'task_deadline' => $request->new_deadline,
                    'task_complete' => null,
                    'task_overdue' => 0
                ]);
            } else {
                $subtask = Subtask::findOrFail($request->item_id);
                $subtask->update([
                    'stask_status' => 1,
                    'stask_deadline' => $request->new_deadline,
                    'stask_complete' => null,
                    'stask_overdue' => 0
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Item reopened with new deadline'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error reopening item with deadline: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error reopening item'
            ], 500);
        }
    }

    public static function formatOverdueDuration($days)
    {
        if ($days == 0) {
            return '<span class="badge bg-success mt-1" style="width: 70px">On-time</span>';
        }

        $formattedText = '';

        if ($days < 30) {
            // Show days only
            $formattedText = $days . ' day' . ($days > 1 ? 's' : '');
        } elseif ($days < 365) {
            // Show months and remaining days
            $months = floor($days / 30);
            $remainingDays = $days % 30;

            $formattedText = $months . ' month' . ($months > 1 ? 's' : '');
            if ($remainingDays > 0) {
                $formattedText .= ' ' . $remainingDays . ' day' . ($remainingDays > 1 ? 's' : '');
            }
        } else {
            // Show years, months and remaining days
            $years = floor($days / 365);
            $remainingDaysAfterYears = $days % 365;
            $months = floor($remainingDaysAfterYears / 30);
            $remainingDays = $remainingDaysAfterYears % 30;

            $formattedText = $years . ' year' . ($years > 1 ? 's' : '');
            if ($months > 0) {
                $formattedText .= ' ' . $months . ' month' . ($months > 1 ? 's' : '');
            }
            if ($remainingDays > 0) {
                $formattedText .= ' ' . $remainingDays . ' day' . ($remainingDays > 1 ? 's' : '');
            }
        }

        return '<span class="badge bg-danger mt-1 text-wrap" style="width: 75px;">Overdue ' . $formattedText . '</span>';
    }
}
