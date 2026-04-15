<?php
namespace App\Http\Controllers\Dashboard\HR\Kanbanboard;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Subtask;
use App\Models\Chattask;
use App\Models\Chatimage;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChattaskController extends Controller
{


    public function showTaskChat($id)
    {
        $task = Task::findOrFail($id);

        $tasks = Task::with([
            'modulo.project.projectLead',
            'assignedEmployee',
            'subtasks' => function ($query) {
                $query->where('delete_status', 1);
            }
        ])
            ->where('delete_status', 1)
            ->whereHas('modulo.project', function ($query) use ($task) {
                $query->where('pro_lead', $task->modulo->project->pro_lead);
            })
            ->get();

        $projectLead = Employee::find($task->modulo->project->pro_lead);
        $statistics = $this->calculateStatistics($tasks);

        return view('dashboard.hr.kanbanboard.task-chat', array_merge(
            compact('tasks', 'projectLead'),
            $statistics
        ));
    }

    public function showSubtaskChat($id)
    {
        $subtask = Subtask::findOrFail($id);
        $task = Task::find($subtask->stask_task);

        $tasks = Task::with([
            'modulo.project.projectLead',
            'assignedEmployee',
            'subtasks' => function ($query) {
                $query->where('delete_status', 1);
            }
        ])
            ->where('delete_status', 1)
            ->whereHas('modulo.project', function ($query) use ($task) {
                $query->where('pro_lead', $task->modulo->project->pro_lead);
            })
            ->get();

        $projectLead = Employee::find($task->modulo->project->pro_lead);
        $statistics = $this->calculateStatistics($tasks);

        return view('dashboard.hr.kanbanboard.task-chat', array_merge(
            compact('tasks', 'projectLead'),
            $statistics
        ));
    }

    public function calculateProLeadStatistics($proLeadId)
    {
        if (!$proLeadId) {
            return [
                'totalTasks' => 0,
                'totalSubtasks' => 0,
                'tasksOnTime' => 0,
                'tasksOnProgress' => 0,
                'tasksOverdue' => 0,
                'subtasksOnTime' => 0,
                'subtasksOnProgress' => 0,
                'subtasksOverdue' => 0,
            ];
        }

        $tasks = Task::with(['subtasks'])
            ->where('delete_status', 1)
            ->whereHas('modulo.project', function ($query) use ($proLeadId) {
                $query->where('pro_lead', $proLeadId);
            })
            ->get();

        $totalTasks = $tasks->count();
        $totalSubtasks = $tasks->flatMap->subtasks->count();

        $tasksOnTime = 0;
        $tasksOnProgress = 0;
        $tasksOverdue = 0;
        $subtasksOnTime = 0;
        $subtasksOnProgress = 0;
        $subtasksOverdue = 0;

        foreach ($tasks as $task) {
            if ($task->task_status == 2 && $task->task_complete) {
                $completionDate = Carbon::parse($task->task_complete);
                $deadline = Carbon::parse($task->task_deadline);
                if ($completionDate->lte($deadline)) {
                    $tasksOnTime++;
                } else {
                    $tasksOverdue++;
                }
            } elseif ($task->task_status == 1) {
                $tasksOnProgress++;
            }

            foreach ($task->subtasks as $subtask) {
                if ($subtask->stask_status == 2 && $subtask->stask_complete) {
                    $subtaskCompletionDate = Carbon::parse($subtask->stask_complete);
                    $subtaskDeadline = Carbon::parse($subtask->stask_deadline);
                    if ($subtaskCompletionDate->lte($subtaskDeadline)) {
                        $subtasksOnTime++;
                    } else {
                        $subtasksOverdue++;
                    }
                } elseif ($subtask->stask_status == 1) {
                    $subtasksOnProgress++;
                }
            }
        }

        return [
            'totalTasks' => $totalTasks,
            'totalSubtasks' => $totalSubtasks,
            'tasksOnTime' => $tasksOnTime,
            'tasksOnProgress' => $tasksOnProgress,
            'tasksOverdue' => $tasksOverdue,
            'subtasksOnTime' => $subtasksOnTime,
            'subtasksOnProgress' => $subtasksOnProgress,
            'subtasksOverdue' => $subtasksOverdue,
        ];
    }

    private function calculateStatistics($tasks)
    {
        $totalTasks = $tasks->count();
        $totalSubtasks = $tasks->flatMap->subtasks->count();

        $tasksOnTime = 0;
        $tasksOnProgress = 0;
        $tasksOverdue = 0;
        $subtasksOnTime = 0;
        $subtasksOnProgress = 0;
        $subtasksOverdue = 0;

        foreach ($tasks as $task) {
            if ($task->task_status == 2 && $task->task_complete) {
                $completionDate = Carbon::parse($task->task_complete);
                $deadline = Carbon::parse($task->task_deadline);
                if ($completionDate->lte($deadline)) {
                    $tasksOnTime++;
                } else {
                    $tasksOverdue++;
                }
            } elseif ($task->task_status == 1) {
                $tasksOnProgress++;
            }

            foreach ($task->subtasks as $subtask) {
                if ($subtask->stask_status == 2 && $subtask->stask_complete) {
                    $subtaskCompletionDate = Carbon::parse($subtask->stask_complete);
                    $subtaskDeadline = Carbon::parse($subtask->stask_deadline);
                    if ($subtaskCompletionDate->lte($subtaskDeadline)) {
                        $subtasksOnTime++;
                    } else {
                        $subtasksOverdue++;
                    }
                } elseif ($subtask->stask_status == 1) {
                    $subtasksOnProgress++;
                }
            }
        }

        return [
            'totalTasks' => $totalTasks,
            'totalSubtasks' => $totalSubtasks,
            'tasksOnTime' => $tasksOnTime,
            'tasksOnProgress' => $tasksOnProgress,
            'tasksOverdue' => $tasksOverdue,
            'subtasksOnTime' => $subtasksOnTime,
            'subtasksOnProgress' => $subtasksOnProgress,
            'subtasksOverdue' => $subtasksOverdue,
        ];
    }

    public function getTaskDetails($taskId)
    {
        try {
            $task = Task::with(['assignedEmployee', 'modulo.project'])
                ->where('delete_status', 1)
                ->findOrFail($taskId);

            return response()->json([
                'success' => true,
                'task' => [
                    'task_desc' => $task->task_desc,
                    'task_deadline' => $task->task_deadline,
                    'task_onprogress' => $task->task_onprogress,
                    'task_complete' => $task->task_complete,
                    'assigned_employee' => $task->assignedEmployee ? $task->assignedEmployee->fullname : null,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found'
            ], 404);
        }
    }

    public function getSubtaskDetails($subtaskId)
    {
        try {
            $subtask = Subtask::with(['task.assignedEmployee'])
                ->where('delete_status', 1)
                ->findOrFail($subtaskId);

            return response()->json([
                'success' => true,
                'subtask' => [
                    'stask_desc' => $subtask->stask_desc,
                    'stask_deadline' => $subtask->stask_deadline,
                    'stask_onprogress' => $subtask->stask_onprogress,
                    'stask_complete' => $subtask->stask_complete,
                    'assigned_employee' => $subtask->assignedEmployee ? $subtask->assignedEmployee->fullname : null,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Subtask not found'
            ], 404);
        }
    }

    public function getTaskMessages($id)
    {
        $messages = Chattask::with(['sender', 'receiver'])
            ->where('delete_status', 1)
            ->where('task_id', $id)
            ->whereNull('subtask_id')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'messages' => $messages
        ]);
    }

    public function getSubtaskMessages($id)
    {
        $messages = Chattask::with(['sender', 'receiver'])
            ->where('delete_status', 1)
            ->where('subtask_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'messages' => $messages
        ]);
    }

    public function sendTaskMessage(Request $request, $id)
    {
        return $this->sendMessage($request, 'task', $id);
    }

    public function sendSubtaskMessage(Request $request, $id)
    {
        return $this->sendMessage($request, 'subtask', $id);
    }

  private function sendMessage(Request $request, $type, $id)
{
    $validator = Validator::make($request->all(), [
        'message' => 'nullable|string|max:1000',
        'files.*' => 'nullable|file|max:10240',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'error' => $validator->errors()->first()
        ], 422);
    }

    try {
        DB::beginTransaction();

        // Get the authenticated user
        $user = auth()->user();

        if (!$user) {
            throw new \Exception('User not authenticated');
        }

        Log::info('Auth user:', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email
        ]);

        // IMPORTANT: Get the employee ID from the users table
        // Since your users table doesn't have emp_id, you need to find the employee record
        // based on the email or other matching field

        // Option 1: If users.employeerole_id corresponds to employees.emp_id
        $currentUserId = $user->employeerole_id ?? $user->id;

        // Option 2: Find employee by email
        if (!$currentUserId) {
            $employee = Employee::where('email_company', $user->email)->first();
            if ($employee) {
                $currentUserId = $employee->emp_id;
            }
        }

        // Option 3: Find employee by name
        if (!$currentUserId) {
            $employee = Employee::where('fullname', $user->name)->first();
            if ($employee) {
                $currentUserId = $employee->emp_id;
            }
        }

        // If still no user ID, use the user ID from auth
        if (!$currentUserId) {
            $currentUserId = $user->id;
            Log::warning('Using auth user ID as employee ID: ' . $currentUserId);
        }

        Log::info('Final current user ID: ' . $currentUserId);

        $chatImageIds = [];

        // Handle file uploads (existing code)
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $fileSize = $file->getSize();
                $dateTime = Carbon::now()->format('dmYHi');
                $fileName = $dateTime . '_' . uniqid() . '.' . $extension;
                $uploadPath = public_path('chat_files');

                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                $file->move($uploadPath, $fileName);

                $chatImageData = [
                    'type' => $type,
                    'file_name' => $fileName,
                    'file_type' => $extension,
                    'file_size' => $fileSize,
                    'delete_status' => 1
                ];

                if ($type === 'task') {
                    $chatImageData['task_id'] = $id;
                    $chatImageData['subtask_id'] = null;
                } else {
                    $chatImageData['subtask_id'] = $id;
                    $chatImageData['task_id'] = null;
                }

                $chatImage = Chatimage::create($chatImageData);
                $chatImageIds[] = $chatImage->chatimage_id;
            }
        }

        if ($type === 'task') {
            $task = Task::with(['modulo.project'])->findOrFail($id);
            $assignedToId = $task->task_assignedto;
            $projectLeadId = $task->modulo->project->pro_lead ?? null;

            Log::info('Task chat info:', [
                'task_id' => $id,
                'project_lead_id' => $projectLeadId,
                'assigned_to_id' => $assignedToId,
                'current_user_id' => $currentUserId
            ]);

            // Determine receiver
            if ($currentUserId == $projectLeadId) {
                $receiverId = $assignedToId; // Project Lead → Assignee
            } elseif ($currentUserId == $assignedToId) {
                $receiverId = $projectLeadId; // Assignee → Project Lead
            } else {
                $receiverId = $assignedToId; // Others → Assignee
            }

            $messageData = [
                'task_id' => $id,
                'subtask_id' => null,
                'sender_id' => $currentUserId,
                'receiver_id' => $receiverId,
                'message' => $request->message ?: null,
                'is_read' => 0,
                'delete_status' => 1
            ];

        } else {
            $subtask = Subtask::with(['task.modulo.project'])->findOrFail($id);
            $assignedToId = $subtask->stask_assignedto;
            $projectLeadId = $subtask->task->modulo->project->pro_lead ?? null;

            Log::info('Subtask chat info:', [
                'subtask_id' => $id,
                'project_lead_id' => $projectLeadId,
                'assigned_to_id' => $assignedToId,
                'current_user_id' => $currentUserId
            ]);

            // Determine receiver
            if ($currentUserId == $projectLeadId) {
                $receiverId = $assignedToId; // Project Lead → Assignee
            } elseif ($currentUserId == $assignedToId) {
                $receiverId = $projectLeadId; // Assignee → Project Lead
            } else {
                $receiverId = $assignedToId; // Others → Assignee
            }

            $messageData = [
                'subtask_id' => $id,
                'task_id' => null,
                'sender_id' => $currentUserId,
                'receiver_id' => $receiverId,
                'message' => $request->message ?: null,
                'is_read' => 0,
                'delete_status' => 1
            ];
        }

        // Add chatfile if any
        if (!empty($chatImageIds)) {
            $messageData['chatfile'] = $chatImageIds;
        }

        // Add reply_to if any
        if ($request->has('reply_to')) {
            $messageData['reply_to'] = $request->reply_to;
        }

        Log::info('Creating chat message:', $messageData);

        $chatMessage = Chattask::create($messageData);
        $chatMessage->load(['sender', 'receiver']);

        DB::commit();

        Log::info('Message created:', [
            'chat_id' => $chatMessage->chat_id,
            'sender_id' => $chatMessage->sender_id,
            'receiver_id' => $chatMessage->receiver_id
        ]);

        return response()->json([
            'success' => true,
            'message' => $chatMessage
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error sending message: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'error' => 'Failed to send message. Please try again.'
        ], 500);
    }
}

 public function updateMessage(Request $request, $id)
{
    try {
        $message = Chattask::findOrFail($id);

        $user = auth()->user();
        $currentUserId = $user->employeerole_id ?? $user->id;

        if (!$currentUserId) {
            $employee = Employee::where('email_company', $user->email)->first();
            if ($employee) {
                $currentUserId = $employee->emp_id;
            }
        }

        if (!$currentUserId) {
            $employee = Employee::where('fullname', $user->name)->first();
            if ($employee) {
                $currentUserId = $employee->emp_id;
            }
        }

        if ($message->sender_id != $currentUserId) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->errors()->first()
            ], 422);
        }

        $message->update([
            'message' => $request->message
        ]);

        $message->load(['sender', 'receiver']);

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    } catch (\Exception $e) {
        \Log::error('Message update error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'error' => 'Failed to update message'
        ], 500);
    }
}

public function deleteMessage($id)
{
    try {
        $message = Chattask::findOrFail($id);

        $user = auth()->user();
        $currentUserId = $user->employeerole_id ?? $user->id;

        if (!$currentUserId) {
            $employee = Employee::where('email_company', $user->email)->first();
            if ($employee) {
                $currentUserId = $employee->emp_id;
            }
        }

        if (!$currentUserId) {
            $employee = Employee::where('fullname', $user->name)->first();
            if ($employee) {
                $currentUserId = $employee->emp_id;
            }
        }

        if ($message->sender_id !== $currentUserId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $message->update(['delete_status' => 0]);

        return response()->json([
            'success' => true,
            'message' => 'Message deleted successfully'
        ]);
    } catch (\Exception $e) {
        \Log::error('Message delete error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'error' => 'Failed to delete message'
        ], 500);
    }
}

    public function markMessagesAsRead(Request $request, $id)
    {
        $type = $request->input('type', 'task');
        $userEmpId = auth()->user()->emp_id;

        if ($type === 'task') {
            Chattask::where('delete_status', 1)
                ->where('receiver_id', $userEmpId)
                ->where('task_id', $id)
                ->whereNull('subtask_id')
                ->delete();
        } else {
            Chattask::where('delete_status', 1)
                ->where('receiver_id', $userEmpId)
                ->where('subtask_id', $id)
                ->delete();
        }

        return response()->json(['success' => true]);
    }

    public function getUnreadCounts()
    {
        $userEmpId = auth()->user()->emp_id;

        $taskUnreadCounts = Task::where('delete_status', 1)
            ->get()
            ->mapWithKeys(function ($task) use ($userEmpId) {
                $count = Chattask::where('delete_status', 1)
                    ->where('task_id', $task->task_id)
                    ->whereNull('subtask_id')
                    ->where('receiver_id', $userEmpId)
                    ->count();
                return [$task->task_id => $count];
            });

        $subtaskUnreadCounts = Subtask::where('delete_status', 1)
            ->get()
            ->mapWithKeys(function ($subtask) use ($userEmpId) {
                $count = Chattask::where('delete_status', 1)
                    ->where('subtask_id', $subtask->stask_id)
                    ->where('receiver_id', $userEmpId)
                    ->count();
                return [$subtask->stask_id => $count];
            });

        return response()->json([
            'success' => true,
            'task_unread_counts' => $taskUnreadCounts,
            'subtask_unread_counts' => $subtaskUnreadCounts
        ]);
    }
}
