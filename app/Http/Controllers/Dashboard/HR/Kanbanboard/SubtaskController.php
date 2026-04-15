<?php

namespace App\Http\Controllers\Dashboard\HR\Kanbanboard;

use App\Http\Controllers\Controller;
use App\Models\Subtask;
use App\Models\Task;
use App\Models\Employee;
use App\Models\PmtsImage;
use App\Models\Modulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Str;

class SubtaskController extends Controller
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
    //     $this->middleware('permission:subtask view')->only(['subtasklist']);
    //     $this->middleware('permission:subtask create')->only(['subtaskcreate', 'subtaskstore']);
    //     $this->middleware('permission:subtask edit')->only(['subtaskedit', 'subtaskupdate']);
    //     $this->middleware('permission:subtask delete')->only(['subtaskdestroy']);
    // }

    public function subtasklist()
    {
        // CHANGED: Updated to include assignedEmployee relationship
        $subtasks = Subtask::with(['task', 'task.modulo', 'task.modulo.project', 'task.modulo.project.client', 'pmtsImages', 'assignedEmployee'])
            ->where('delete_status', 1)
            ->get();

        return view('dashboard.hr.kanbanboard.subtask.list', compact('subtasks'));
    }

    public function completeSubtask($id)
    {
        try {
            $subtask = Subtask::findOrFail($id);
            $subtask->update([
                'stask_status' => 2,
                'stask_complete' => Carbon::now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Subtask marked as completed'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error completing subtask: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error completing subtask'
            ], 500);
        }
    }

    public function reopenSubtask($id)
    {
        try {
            $subtask = Subtask::findOrFail($id);
            $subtask->update([
                'stask_status' => 1,
                'stask_complete' => null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Subtask reopened'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error reopening subtask: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error reopening subtask'
            ], 500);
        }
    }

    public function getSubtaskDetails($id)
    {
        try {
            // CHANGED: Updated to include assignedEmployee relationship
            $subtask = Subtask::with([
                'task',
                'task.modulo',
                'task.modulo.project',
                'pmtsImages',
                'assignedEmployee' // Added assigned employee relationship
            ])->findOrFail($id);

            // CHANGED: Get assigned employee name instead of multiple members
            $assignedEmployeeName = null;
            if ($subtask->assignedEmployee) {
                $assignedEmployeeName = $subtask->assignedEmployee->fullname;
            }

            $attachments = [];
            if ($subtask->stask_attachment) {
                $attachmentIds = json_decode($subtask->stask_attachment, true) ?? [];
                if (!empty($attachmentIds)) {
                    $attachments = PmtsImage::whereIn('pmts_id', $attachmentIds)
                        ->where('delete_status', 1)
                        ->get();
                }
            }

            // CHANGED: Pass assignedEmployeeName instead of memberNames
            $html = view('dashboard.hr.kanbanboard.task.partials.subtask-details', compact('subtask', 'assignedEmployeeName', 'attachments'))->render();

            return response()->json([
                'success' => true,
                'html' => $html
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading subtask details: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Subtask not found'
            ], 404);
        }
    }

    public function subtaskcreate($taskId = null)
    {
        $task = null;
        $availableMembers = [];

        if ($taskId) {
            $task = Task::with(['modulo', 'modulo.project', 'modulo.project.client'])->find($taskId);

            if ($task) {
                // STEP 2: Get members from task first, if not available then get from modulo
                $availableMembers = $this->getAvailableMembers($task);
            }
        }

        return view('dashboard.hr.kanbanboard.subtask.create', compact('task', 'availableMembers'));
    }

    /**
     * Get available members for subtask assignment
     * Priority: Task members -> Modulo members
     */
    private function getAvailableMembers($task)
    {
        $members = [];

        // First try to get members from task
        try {
            $memberData = $task->task_member;
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
                $members = Employee::whereIn('emp_id', $memberIds)
                    ->get(['emp_id', 'fullname', 'employee_id', 'email_company as email', 'image']);
                return $members;
            }
        } catch (\Exception $e) {
            \Log::error('Error parsing task members: ' . $e->getMessage());
        }

        // If no task members, try to get from modulo
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
                    $members = Employee::whereIn('emp_id', $memberIds)
                        ->get(['emp_id', 'fullname', 'employee_id', 'email_company as email', 'image']);
                }
            } catch (\Exception $e) {
                \Log::error('Error parsing modulo members: ' . $e->getMessage());
            }
        }

        return $members;
    }

    public function subtaskstore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'stask_name' => 'required|string|max:255',
            'stask_desc' => 'required|string',
            'stask_avater' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'stask_attachment' => 'nullable|array',
            'stask_attachment.*' => 'file|mimes:jpg,jpeg,png,pdf,ppt,pptx,csv,xlsx,xls,doc,docx|max:10240',
            'stask_deadline' => 'required|date',
            'stask_task' => 'required|exists:tasks,task_id',
            // CHANGED: Updated validation for single assigned person
            'stask_assignedto' => 'required|exists:employees,emp_id',
            'stask_priority' => 'required|in:1,2,3',
            'stask_accessmod' => 'required|boolean',
            'stask_status' => 'required|in:0,1,2',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Handle avatar upload (single image)
            $avatarPath = null;
            if ($request->hasFile('stask_avater')) {
                $file = $request->file('stask_avater');
                $currentDateTime = Carbon::now()->format('dmYHis');
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $fileName = $currentDateTime . '_' . Str::slug($originalName) . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('subtask_avatars');

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }

                $file->move($destinationPath, $fileName);
                $avatarPath = 'subtask_avatars/' . $fileName;
            }

            // Calculate initial overdue status
            $today = Carbon::now();
            $deadline = Carbon::parse($request->stask_deadline);
            $overdueDays = 0;

            if ($today->gt($deadline)) {
                $overdueDays = $deadline->diffInDays($today);
            }

            // Handle status-based date updates
            $onProgressDate = null;
            $completeDate = null;

            if ($request->stask_status == 1) {
                $onProgressDate = Carbon::now();
            } elseif ($request->stask_status == 2) {
                $onProgressDate = Carbon::now();
                $completeDate = Carbon::now();

                // Calculate overdue for completed subtasks
                if ($completeDate->gt($deadline)) {
                    $overdueDays = $deadline->diffInDays($completeDate);
                }
            }

            // Create subtask record
            // CHANGED: Updated to use stask_assignedto instead of stask_member
            $subtask = Subtask::create([
                'stask_name' => $request->stask_name,
                'stask_desc' => $request->stask_desc,
                'stask_avater' => $avatarPath,
                'stask_attachment' => json_encode([]),
                'stask_deadline' => $request->stask_deadline,
                'stask_task' => $request->stask_task,
                'stask_assignedto' => $request->stask_assignedto, // Single person assignment
                'stask_priority' => $request->stask_priority,
                'stask_accessmod' => $request->stask_accessmod,
                'delete_status' => 1,
                'stask_status' => $request->stask_status,
                'stask_overdue' => $overdueDays,
                'stask_onprogress' => $onProgressDate,
                'stask_complete' => $completeDate,
            ]);

            // Handle multiple attachments and save to PMTS table
            $pmtsIds = [];
            if ($request->hasFile('stask_attachment')) {
                foreach ($request->file('stask_attachment') as $file) {
                    $currentDateTime = Carbon::now()->format('dmYHis');
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $fileName = $currentDateTime . '_' . Str::slug($originalName) . '.' . $file->getClientOriginalExtension();
                    $destinationPath = public_path('subtask_attachments');

                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0777, true);
                    }

                    $file->move($destinationPath, $fileName);
                    $filePath = 'subtask_attachments/' . $fileName;

                    // Create PMTS record
                    $pmtsImage = PmtsImage::create([
                        'pmtsimage_name' => $fileName,
                        'menu_id' => $subtask->stask_id,
                        'menu_type' => 'subtask',
                        'delete_status' => 1,
                    ]);

                    $pmtsIds[] = $pmtsImage->pmts_id;
                }

                // Update subtask with PMTS IDs
                $subtask->update([
                    'stask_attachment' => json_encode($pmtsIds)
                ]);
            }
 if ($request->has('send_email') && $request->send_email == 1) {
            $subtask->sendEmailNotification(false);
        }
            return redirect()->route('tasklist')
                ->with('success', 'Subtask created successfully!');
        } catch (\Exception $e) {
            \Log::error('Subtask creation error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error creating subtask: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function subtaskedit($id)
    {
        try {
            // CHANGED: Updated to include assignedEmployee relationship
            $subtask = Subtask::with(['task', 'task.modulo', 'pmtsImages', 'assignedEmployee'])->findOrFail($id);

            // Get available members for dropdown
            $availableMembers = $this->getAvailableMembers($subtask->task);

            return view('dashboard.hr.kanbanboard.subtask.edit', compact('subtask', 'availableMembers'));
        } catch (\Exception $e) {
            \Log::error('Error fetching subtask for edit: ' . $e->getMessage());
            return redirect()->route('subtasklist')
                ->with('error', 'Subtask not found.');
        }
    }

    public function subtaskupdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'stask_name' => 'required|string|max:255',
            'stask_desc' => 'required|string',
            'stask_avater' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'stask_attachment' => 'nullable|array',
            'stask_attachment.*' => 'file|mimes:jpg,jpeg,png,pdf,ppt,pptx,csv,xlsx,xls,doc,docx|max:10240',
            'stask_deadline' => 'required|date',
            // CHANGED: Updated validation for single assigned person
            'stask_assignedto' => 'required|exists:employees,emp_id',
            'stask_priority' => 'required|in:1,2,3',
            'stask_accessmod' => 'required|boolean',
            'stask_status' => 'required|in:0,1,2',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $subtask = Subtask::findOrFail($id);

            // Handle avatar upload
            $avatarPath = $subtask->stask_avater;
            if ($request->hasFile('stask_avater')) {
                // Delete old avatar if exists
                if ($avatarPath && file_exists(public_path($avatarPath))) {
                    unlink(public_path($avatarPath));
                }

                $file = $request->file('stask_avater');
                $currentDateTime = Carbon::now()->format('dmYHis');
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $fileName = $currentDateTime . '_' . Str::slug($originalName) . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('subtask_avatars');

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }

                $file->move($destinationPath, $fileName);
                $avatarPath = 'subtask_avatars/' . $fileName;
            }

            // Handle remove avatar
            if ($request->has('remove_avater') && $request->remove_avater) {
                if ($avatarPath && file_exists(public_path($avatarPath))) {
                    unlink(public_path($avatarPath));
                }
                $avatarPath = null;
            }

            // Get existing PMTS IDs
            $existingPmtsIds = [];
            if ($subtask->stask_attachment) {
                $existingPmtsIds = json_decode($subtask->stask_attachment, true) ?? [];
            }

            // Handle new attachments and save to PMTS table
            $newPmtsIds = [];
            if ($request->hasFile('stask_attachment')) {
                foreach ($request->file('stask_attachment') as $file) {
                    $currentDateTime = Carbon::now()->format('dmYHis');
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $fileName = $currentDateTime . '_' . Str::slug($originalName) . '.' . $file->getClientOriginalExtension();
                    $destinationPath = public_path('subtask_attachments');

                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0777, true);
                    }

                    $file->move($destinationPath, $fileName);
                    $filePath = 'subtask_attachments/' . $fileName;

                    // Create PMTS record
                    $pmtsImage = PmtsImage::create([
                        'pmtsimage_name' => $fileName,
                        'menu_id' => $subtask->stask_id,
                        'menu_type' => 'subtask',
                        'delete_status' => 1,
                    ]);

                    $newPmtsIds[] = $pmtsImage->pmts_id;
                }
            }

            // Handle remove attachments
            $removePmtsIds = $request->input('remove_attachments', []);
            if (!empty($removePmtsIds)) {
                PmtsImage::whereIn('pmts_id', $removePmtsIds)
                    ->update(['delete_status' => 0]);

                // Remove from existing IDs
                $existingPmtsIds = array_diff($existingPmtsIds, $removePmtsIds);
            }

            // Combine existing and new PMTS IDs
            $allPmtsIds = array_merge($existingPmtsIds, $newPmtsIds);

            // Prepare update data
            // CHANGED: Updated to use stask_assignedto instead of stask_member
            $updateData = [
                'stask_name' => $request->stask_name,
                'stask_desc' => $request->stask_desc,
                'stask_avater' => $avatarPath,
                'stask_attachment' => !empty($allPmtsIds) ? json_encode($allPmtsIds) : json_encode([]),
                'stask_deadline' => $request->stask_deadline,
                'stask_assignedto' => $request->stask_assignedto, // Single person assignment
                'stask_priority' => $request->stask_priority,
                'stask_accessmod' => $request->stask_accessmod,
                'stask_status' => $request->stask_status,
            ];

            // Handle status-based date updates with proper overdue calculation
            if ($request->stask_status == 1 && !$subtask->stask_onprogress) {
                $updateData['stask_onprogress'] = Carbon::now();
            }

            if ($request->stask_status == 2) {
                if (!$subtask->stask_complete) {
                    $updateData['stask_complete'] = Carbon::now();
                }

                // Calculate overdue days properly - POSITIVE values only
                $completionDate = $subtask->stask_complete ? Carbon::parse($subtask->stask_complete) : Carbon::now();
                $deadline = Carbon::parse($request->stask_deadline);

                if ($completionDate->gt($deadline)) {
                    $updateData['stask_overdue'] = $deadline->diffInDays($completionDate);
                } else {
                    $updateData['stask_overdue'] = 0;
                }
            } else {
                // For ongoing subtasks, calculate current overdue status
                $today = Carbon::now();
                $deadline = Carbon::parse($request->stask_deadline);

                if ($today->gt($deadline)) {
                    $updateData['stask_overdue'] = $deadline->diffInDays($today);
                } else {
                    $updateData['stask_overdue'] = 0;
                }
            }

            // Update subtask record
            $subtask->update($updateData);

        if ($request->has('send_email') && $request->send_email == 1) {
            $subtask->sendEmailNotification(true);
        }

            return redirect()->route('tasklist')
                ->with('success', 'Subtask updated successfully!');
        } catch (\Exception $e) {
            \Log::error('Subtask update error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error updating subtask: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function subtaskdestroy($id)
    {
        try {
            $subtask = Subtask::findOrFail($id);

            // Soft delete related PMTS images
            PmtsImage::where('menu_id', $id)
                ->where('menu_type', 'subtask')
                ->update(['delete_status' => 0]);

            $subtask->update([
                'delete_status' => 0
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Subtask deleted successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Subtask deletion error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error deleting subtask'
            ], 500);
        }
    }

    // New method to update status only
    public function updateStatus(Request $request, $id)
    {
        try {
            $subtask = Subtask::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'stask_status' => 'required|in:0,1,2'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status value'
                ], 400);
            }

            $subtask->updateStatus($request->stask_status);

            return response()->json([
                'success' => true,
                'message' => 'Subtask status updated successfully',
                'status_text' => $subtask->status_text,
                'status_badge' => $subtask->status_badge_class,
                'overdue_text' => $subtask->overdue_text
            ]);
        } catch (\Exception $e) {
            \Log::error('Subtask status update error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error updating subtask status'
            ], 500);
        }
    }

    // New method to get PMTS image details
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
                    'url' => asset('subtask_attachments/' . $image->pmtsimage_name),
                    'type' => pathinfo($image->pmtsimage_name, PATHINFO_EXTENSION)
                ];
            })
            ->toArray();

        return response()->json(['images' => $images]);
    }
}
