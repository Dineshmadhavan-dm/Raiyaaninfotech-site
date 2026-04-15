<?php

namespace App\Http\Controllers\Dashboard\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Subtask;
use App\Models\Chattask;
use App\Models\Chatimage;
use App\Models\Employee;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class EmpTaskchatController extends Controller
{
    public function etaskchat()
    {
        $authUser = auth()->user();
        $employeeId = $authUser->employeerole_id;
        $isProjectLead = Project::where('pro_lead', $employeeId)->where('delete_status', 1)->exists();
        if ($isProjectLead) {
            $tasks = Task::with(['modulo.project','assignedEmployee','subtasks' => function ($query) { $query->where('delete_status', 1); }])->whereHas('modulo.project', function ($query) use ($employeeId) { $query->where('pro_lead', $employeeId)->where('delete_status', 1); })->where('delete_status', 1)->get();
            $allSubtasks = Subtask::with(['task.modulo.project'])->whereHas('task.modulo.project', function ($query) use ($employeeId) { $query->where('pro_lead', $employeeId)->where('delete_status', 1); })->where('delete_status', 1)->get();
        } else {
            $tasks = Task::with(['modulo.project','assignedEmployee','subtasks' => function ($query) use ($employeeId) { $query->where('delete_status', 1)->where(function ($q) use ($employeeId) { $q->where('stask_assignedto', 'LIKE', '%"' . $employeeId . '"%')->orWhere('stask_assignedto', 'LIKE', "%{$employeeId}%")->orWhereRaw('JSON_CONTAINS(stask_assignedto, ?)', [json_encode($employeeId)]); }); }])->where('delete_status', 1)->where(function ($query) use ($employeeId) { $query->where('task_assignedto', 'LIKE', '%"' . $employeeId . '"%')->orWhere('task_assignedto', 'LIKE', "%{$employeeId}%")->orWhereRaw('JSON_CONTAINS(task_assignedto, ?)', [json_encode($employeeId)]); })->get();
            $allSubtasks = Subtask::where('delete_status', 1)->where(function ($query) use ($employeeId) { $query->where('stask_assignedto', 'LIKE', '%"' . $employeeId . '"%')->orWhere('stask_assignedto', 'LIKE', "%{$employeeId}%")->orWhereRaw('JSON_CONTAINS(stask_assignedto, ?)', [json_encode($employeeId)]); })->get();
        }
        $projectLeadIds = $tasks->pluck('modulo.project.pro_lead')->filter()->unique();
        $projectLeads = Employee::whereIn('emp_id', $projectLeadIds)->get()->keyBy('emp_id');
        $statistics = $this->calculateEmployeeStatistics($tasks, $allSubtasks, $employeeId, $isProjectLead);
        $controller = $this;
        $focusItem = request()->query('focus');
        return view('dashboard.employee.empkanbanboard.etaskchat', compact('tasks', 'projectLeads', 'statistics', 'isProjectLead', 'controller', 'focusItem'));
    }

    private function calculateEmployeeStatistics($tasks, $allSubtasks, $employeeId, $isProjectLead)
    {
        $totalTasks = $tasks->count();
        $totalSubtasks = $allSubtasks->count();
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
                if ($completionDate->lte($deadline)) { $tasksOnTime++; } else { $tasksOverdue++; }
            } elseif ($task->task_status == 1) { $tasksOnProgress++; }
        }
        foreach ($allSubtasks as $subtask) {
            if ($subtask->stask_status == 2 && $subtask->stask_complete) {
                $completionDate = Carbon::parse($subtask->stask_complete);
                $deadline = Carbon::parse($subtask->stask_deadline);
                if ($completionDate->lte($deadline)) { $subtasksOnTime++; } else { $subtasksOverdue++; }
            } elseif ($subtask->stask_status == 1) { $subtasksOnProgress++; }
        }
        return ['totalTasks' => $totalTasks,'totalSubtasks' => $totalSubtasks,'tasksOnTime' => $tasksOnTime,'tasksOnProgress' => $tasksOnProgress,'tasksOverdue' => $tasksOverdue,'subtasksOnTime' => $subtasksOnTime,'subtasksOnProgress' => $subtasksOnProgress,'subtasksOverdue' => $subtasksOverdue,'isProjectLead' => $isProjectLead,];
    }

    public function getTaskMessages($id)
    {
        $userEmpId = auth()->user()->employeerole_id;
        $task = Task::with('modulo.project')->findOrFail($id);
        $isCurrentUserProjectLead = $task->modulo->project->pro_lead == $userEmpId;
        $isCurrentUserAssigned = $this->isUserAssignedToTask($task, $userEmpId);
        if (!$isCurrentUserProjectLead && !$isCurrentUserAssigned) {
            return response()->json(['success' => false,'error' => 'Unauthorized to view messages'], 403);
        }
        $messages = Chattask::with(['sender', 'receiver'])->where('delete_status', 1)->where('task_id', $id)->whereNull('subtask_id')->where(function ($query) use ($userEmpId) { $query->where('sender_id', $userEmpId)->orWhere('receiver_id', $userEmpId); })->orderBy('created_at', 'asc')->get();
        $messages->each(function ($message) {
            if ($message->chatfile) {
                $fileIds = is_string($message->chatfile) ? json_decode($message->chatfile, true) : $message->chatfile;
                if (is_array($fileIds) && !empty($fileIds)) {
                    $files = Chatimage::whereIn('chatimage_id', $fileIds)->get();
                    $message->files_info = $files->map(function ($file) {
                        return ['url' => asset('chat_files/' . $file->file_name),'name' => $file->file_name,'is_image' => $this->isImageFile($file->file_type),'icon' => $this->getFileIcon($file->file_type)];
                    })->toArray();
                } else { $message->files_info = []; }
            } else { $message->files_info = []; }
        });
        return response()->json(['success' => true,'messages' => $messages,'isProjectLead' => $isCurrentUserProjectLead]);
    }

    public function getSubtaskMessages($id)
    {
        $userEmpId = auth()->user()->employeerole_id;
        $subtask = Subtask::with('task.modulo.project')->findOrFail($id);
        $isCurrentUserProjectLead = $subtask->task->modulo->project->pro_lead == $userEmpId;
        $isCurrentUserAssigned = $this->isUserAssignedToSubtask($subtask, $userEmpId);
        if (!$isCurrentUserProjectLead && !$isCurrentUserAssigned) {
            return response()->json(['success' => false,'error' => 'Unauthorized to view messages'], 403);
        }
        $messages = Chattask::with(['sender', 'receiver'])->where('delete_status', 1)->where('subtask_id', $id)->where(function ($query) use ($userEmpId) { $query->where('sender_id', $userEmpId)->orWhere('receiver_id', $userEmpId); })->orderBy('created_at', 'asc')->get();
        $messages->each(function ($message) {
            if ($message->chatfile) {
                $fileIds = json_decode($message->chatfile, true);
                if (is_array($fileIds) && !empty($fileIds)) {
                    $files = Chatimage::whereIn('chatimage_id', $fileIds)->get();
                    $message->files_info = $files->map(function ($file) {
                        return ['url' => asset('chat_files/' . $file->file_name),'name' => $file->file_name,'is_image' => $this->isImageFile($file->file_type),'icon' => $this->getFileIcon($file->file_type)];
                    })->toArray();
                } else { $message->files_info = []; }
            } else { $message->files_info = []; }
        });
        return response()->json(['success' => true,'messages' => $messages,'isProjectLead' => $isCurrentUserProjectLead]);
    }

    private function isImageFile($fileType)
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'];
        return in_array(strtolower($fileType), $imageExtensions);
    }

    private function isUserAssignedToTask($task, $userId)
    {
        $assignedTo = $task->task_assignedto;
        if (empty($assignedTo)) { return false; }
        if (is_numeric($assignedTo)) { return (int) $assignedTo == $userId; }
        if (is_array($assignedTo)) { return in_array($userId, $assignedTo); }
        if (is_string($assignedTo)) {
            $decoded = json_decode($assignedTo, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) { return in_array($userId, $decoded); }
            if (strpos($assignedTo, ',') !== false) {
                $ids = array_map('trim', explode(',', $assignedTo));
                return in_array($userId, $ids);
            }
            return $assignedTo == $userId;
        }
        return false;
    }

    private function isUserAssignedToSubtask($subtask, $userId)
    {
        $assignedTo = $subtask->stask_assignedto;
        if (empty($assignedTo)) { return false; }
        if (is_numeric($assignedTo)) { return (int) $assignedTo == $userId; }
        if (is_array($assignedTo)) { return in_array($userId, $assignedTo); }
        if (is_string($assignedTo)) {
            $decoded = json_decode($assignedTo, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) { return in_array($userId, $decoded); }
            if (strpos($assignedTo, ',') !== false) {
                $ids = array_map('trim', explode(',', $assignedTo));
                return in_array($userId, $ids);
            }
            return $assignedTo == $userId;
        }
        return false;
    }

    private function getFileIcon($fileType)
    {
        $type = strtolower($fileType);
        if (in_array($type, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) { return 'bi-image'; }
        elseif ($type === 'pdf') { return 'bi-file-earmark-pdf'; }
        elseif (in_array($type, ['doc', 'docx'])) { return 'bi-file-earmark-word'; }
        elseif (in_array($type, ['xls', 'xlsx'])) { return 'bi-file-earmark-excel'; }
        elseif (in_array($type, ['ppt', 'pptx'])) { return 'bi-file-earmark-ppt'; }
        elseif (in_array($type, ['zip', 'rar', '7z'])) { return 'bi-file-earmark-zip'; }
        else { return 'bi-file-earmark'; }
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
        $validator = Validator::make($request->all(), ['message' => 'nullable|string|max:1000','files.*' => 'nullable|file|max:10240',]);
        if ($validator->fails()) { return response()->json(['success' => false,'error' => $validator->errors()->first()], 422); }
        try {
            DB::beginTransaction();
            $currentUserId = auth()->user()->employeerole_id;
            $chatImageIds = [];
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $originalName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $fileSize = $file->getSize();
                    $dateTime = Carbon::now()->format('dmYHi');
                    $fileName = $dateTime . '_' . uniqid() . '.' . $extension;
                    $uploadPath = public_path('chat_files');
                    if (!file_exists($uploadPath)) { mkdir($uploadPath, 0777, true); }
                    $file->move($uploadPath, $fileName);
                    $chatImageData = ['type' => $type,'file_name' => $fileName,'file_type' => $extension,'file_size' => $fileSize,'delete_status' => 1];
                    if ($type === 'task') { $chatImageData['task_id'] = $id; $chatImageData['subtask_id'] = null; }
                    else { $chatImageData['subtask_id'] = $id; $chatImageData['task_id'] = null; }
                    $chatImage = Chatimage::create($chatImageData);
                    $chatImageIds[] = $chatImage->chatimage_id;
                }
            }
            $receiverId = null;
            $senderId = $currentUserId;
            if ($type === 'task') {
                $task = Task::with('modulo.project')->findOrFail($id);
                $projectLeadId = $task->modulo->project->pro_lead;
                if ($currentUserId == $projectLeadId) { $receiverId = $this->extractFirstId($task->task_assignedto); }
                else { $receiverId = $projectLeadId; }
                $messageData = ['task_id' => $id,'subtask_id' => null,'sender_id' => $senderId,'receiver_id' => $receiverId,'message' => $request->message ?: null,'delete_status' => 1,'is_read' => 0];
            } else {
                $subtask = Subtask::with('task.modulo.project')->findOrFail($id);
                $projectLeadId = $subtask->task->modulo->project->pro_lead;
                if ($currentUserId == $projectLeadId) { $receiverId = $this->extractFirstId($subtask->stask_assignedto); }
                else { $receiverId = $projectLeadId; }
                $messageData = ['subtask_id' => $id,'task_id' => null,'sender_id' => $senderId,'receiver_id' => $receiverId,'message' => $request->message ?: null,'delete_status' => 1,'is_read' => 0];
            }
            if (!empty($chatImageIds)) { $messageData['chatfile'] = json_encode($chatImageIds); }
            if ($request->has('reply_to')) { $messageData['reply_to'] = $request->reply_to; }
            $chatMessage = Chattask::create($messageData);
            $chatMessage->load(['sender', 'receiver']);
            DB::commit();
            return response()->json(['success' => true,'message' => $chatMessage,'debug' => ['sender_id' => $senderId,'receiver_id' => $receiverId,'type' => $type,'chat_id' => $chatMessage->chat_id]]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Chat message send error: ' . $e->getMessage());
            return response()->json(['success' => false,'error' => 'Failed to send message. Please try again.','debug' => $e->getMessage()], 500);
        }
    }

    public function extractFirstId($assignedTo)
    {
        if (empty($assignedTo)) { return null; }
        if (is_numeric($assignedTo)) { return (int) $assignedTo; }
        if (is_array($assignedTo)) { return !empty($assignedTo) ? (int) $assignedTo[0] : null; }
        if (is_string($assignedTo)) {
            $decoded = json_decode($assignedTo, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) { return !empty($decoded) ? (int) $decoded[0] : null; }
            if (strpos($assignedTo, ',') !== false) {
                $ids = array_map('trim', explode(',', $assignedTo));
                $ids = array_filter($ids, function ($id) { return is_numeric($id) && $id > 0; });
                return !empty($ids) ? (int) $ids[0] : null;
            }
            if (is_numeric($assignedTo) && $assignedTo > 0) { return (int) $assignedTo; }
        }
        return null;
    }

    public function updateMessage(Request $request, $id)
    {
        try {
            $message = Chattask::findOrFail($id);
            if ($message->sender_id !== auth()->user()->employeerole_id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            $validator = Validator::make($request->all(), ['message' => 'required|string|max:1000',]);
            if ($validator->fails()) {
                return response()->json(['success' => false,'error' => $validator->errors()->first()], 422);
            }
            $message->update(['message' => $request->message]);
            $message->load(['sender', 'receiver']);
            return response()->json(['success' => true,'message' => $message]);
        } catch (\Exception $e) {
            \Log::error('Message update error: ' . $e->getMessage());
            return response()->json(['success' => false,'error' => 'Failed to update message'], 500);
        }
    }

    public function deleteMessage($id)
    {
        try {
            $message = Chattask::findOrFail($id);
            if ($message->sender_id !== auth()->user()->employeerole_id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            $message->update(['delete_status' => 0]);
            return response()->json(['success' => true,'message' => 'Message deleted successfully']);
        } catch (\Exception $e) {
            \Log::error('Message delete error: ' . $e->getMessage());
            return response()->json(['success' => false,'error' => 'Failed to delete message'], 500);
        }
    }

    public function getUnreadCounts()
    {
        $userEmpId = auth()->user()->employeerole_id;
        $isProjectLead = Project::where('pro_lead', $userEmpId)->where('delete_status', 1)->exists();
        if ($isProjectLead) {
            $taskQuery = Task::where('delete_status', 1)->whereHas('modulo.project', function ($query) use ($userEmpId) { $query->where('pro_lead', $userEmpId)->where('delete_status', 1); });
            $subtaskQuery = Subtask::where('delete_status', 1)->whereHas('task.modulo.project', function ($query) use ($userEmpId) { $query->where('pro_lead', $userEmpId)->where('delete_status', 1); });
        } else {
            $taskQuery = Task::where('delete_status', 1)->where(function ($query) use ($userEmpId) { $query->where('task_assignedto', 'LIKE', '%"' . $userEmpId . '"%')->orWhere('task_assignedto', 'LIKE', "%{$userEmpId}%")->orWhereRaw('JSON_CONTAINS(task_assignedto, ?)', [json_encode($userEmpId)]); });
            $subtaskQuery = Subtask::where('delete_status', 1)->where(function ($query) use ($userEmpId) { $query->where('stask_assignedto', 'LIKE', '%"' . $userEmpId . '"%')->orWhere('stask_assignedto', 'LIKE', "%{$userEmpId}%")->orWhereRaw('JSON_CONTAINS(stask_assignedto, ?)', [json_encode($userEmpId)]); });
        }
        $taskUnreadCounts = $taskQuery->get()->mapWithKeys(function ($task) use ($userEmpId) {
            $count = Chattask::where('delete_status', 1)->where('task_id', $task->task_id)->whereNull('subtask_id')->where('receiver_id', $userEmpId)->where('is_read', 0)->count();
            return [$task->task_id => $count];
        });
        $subtaskUnreadCounts = $subtaskQuery->get()->mapWithKeys(function ($subtask) use ($userEmpId) {
            $count = Chattask::where('delete_status', 1)->where('subtask_id', $subtask->stask_id)->where('receiver_id', $userEmpId)->where('is_read', 0)->count();
            return [$subtask->stask_id => $count];
        });
        $totalUnread = $taskUnreadCounts->sum() + $subtaskUnreadCounts->sum();
        return response()->json(['success' => true,'total_unread' => $totalUnread,'task_unread_counts' => $taskUnreadCounts,'subtask_unread_counts' => $subtaskUnreadCounts]);
    }

    public function getTaskNotifications()
    {
        $userEmpId = auth()->user()->employeerole_id;
        $notifications = Chattask::with(['sender', 'task', 'subtask.task'])->where('receiver_id', $userEmpId)->where('is_read', 0)->where('delete_status', 1)->orderBy('created_at', 'desc')->take(10)->get()->map(function ($notification) {
            $notificationType = $notification->task_id ? 'task' : 'subtask';
            $senderName = $notification->sender ? $notification->sender->fullname : 'Unknown User';
            $taskTitle = $notification->task ? $notification->task->task_name : null;
            $subtaskTitle = $notification->subtask ? $notification->subtask->stask_name : null;
            $parentTaskId = $notification->subtask ? $notification->subtask->task_id : null;
return [
    'id' => $notification->chattask_id,
    'type' => $notificationType,
    'task_id' => $notification->task_id,
    'subtask_id' => $notification->subtask_id,
    'task_title' => $taskTitle,
    'subtask_title' => $subtaskTitle,
    'parent_task_id' => $parentTaskId,
    'sender_name' => $senderName,
    'sender_image' => $notification->sender && $notification->sender->image
        ? asset('employee_images/' . $notification->sender->image)
        : asset('images/admin_default.jpg'),
    'message' => $notification->message ?: 'Sent an attachment',
    'plain_message' => trim(preg_replace('/\s+/', ' ', strip_tags($notification->message))),
    'time' => $notification->created_at->diffForHumans(),
    'has_files' => !empty($notification->chatfile)
];
        });
        return response()->json(['success' => true,'notifications' => $notifications,'count' => $notifications->count()]);
    }

    private function checkIfFirstFileIsImage($chatfile)
    {
        if (!$chatfile) return false;
        $fileIds = json_decode($chatfile, true);
        if (!is_array($fileIds) || empty($fileIds)) return false;
        $firstFile = Chatimage::find($fileIds[0]);
        if (!$firstFile) return false;
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        return in_array(strtolower($firstFile->file_type), $imageExtensions);
    }

    public function markAsRead($id)
    {
        try {
            $notification = Chattask::findOrFail($id);
            if ($notification->receiver_id !== auth()->user()->employeerole_id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            $notification->update(['is_read' => 1]);
            return response()->json(['success' => true,'message' => 'Notification marked as read']);
        } catch (\Exception $e) {
            return response()->json(['success' => false,'error' => 'Failed to mark as read'], 500);
        }
    }

    public function markAllAsRead()
    {
        try {
            $userEmpId = auth()->user()->employeerole_id;
            Chattask::where('receiver_id', $userEmpId)->where('is_read', 0)->update(['is_read' => 1]);
            return response()->json(['success' => true,'message' => 'All notifications marked as read']);
        } catch (\Exception $e) {
            return response()->json(['success' => false,'error' => 'Failed to mark all as read'], 500);
        }
    }
}
