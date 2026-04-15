<?php

namespace App\Http\Controllers\Dashboard\HR\Kanbanboard;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Employee;
use App\Models\Client;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Modulo;
use App\Models\PmtsImage;
use App\Models\Subtask;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function kanbandashboard()
    {
        $projectcount =  Project::where('delete_status', 1)->get();
        $modulocount =  Modulo::where('delete_status', 1)->get();
        $taskcount =  Task::where('delete_status', 1)->get();
        $subtaskcount =  Subtask::where('delete_status', 1)->get();

        return view('dashboard.hr.kanbanboard.kanbandashboard', compact(
            'projectcount',
            'modulocount',
            'taskcount',
            'subtaskcount'
        ));
    }

  public function __construct()
{
    // Restrict all Kanban board routes to only Super Admin (categorie 1) and Admin (categorie 3)
    $this->middleware(function ($request, $next) {
        $user = auth()->user();

        // Check user category - only allow 1 (Super Admin) and 3 (Admin)
        if ($user->categorie == 2) { // Employee
            return redirect()->route('emphome')->with('error', 'Access denied to Kanban Board.');
        }

        return $next($request);
    });


}
    // In ProjectController.php


    public function projectlist()
    {
        $projects = Project::with([
            'projectLead',
            'projectHead',
            'client',
            'pmtsImages'
        ])
            ->where('delete_status', 1)
            ->get();

        $clients = Client::all();

        $projectHeadIds = $projects->pluck('pro_head')->filter()->unique();
        $projectLeadIds = $projects->pluck('pro_lead')->filter()->unique();

        $projectHeads = Employee::whereIn('emp_id', $projectHeadIds)->get();
        $projectLeads = Employee::whereIn('emp_id', $projectLeadIds)->get();

        $allProjects = $projects->pluck('pro_name', 'pro_id');

        return view('dashboard.hr.kanbanboard.project.list', compact(
            'projects',
            'clients',
            'projectHeads',
            'projectLeads',
            'allProjects'
        ));
    }

    public function projectcreate()
    {
        $employees = Employee::with('Departmentid')
            ->get(['emp_id', 'fullname', 'employee_id', 'email_company as email', 'image', 'cur_department']);




        return view('dashboard.hr.kanbanboard.project.create', compact('employees'));
    }

  public function projectstore(Request $request)
{
    $validator = Validator::make($request->all(), [
        'pro_name' => 'required|string|max:255',
        'pro_desc' => 'required|string',
        'pro_avater' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        'pro_attachment' => 'nullable|array',
        'pro_attachment.*' => 'file|mimes:jpg,jpeg,png,pdf,ppt,pptx,csv,xlsx,xls,doc,docx|max:10240',
        'pro_deadline' => 'required|date',
        'pro_client' => 'required|exists:clients,cl_id',
        'pro_lead' => 'required|exists:employees,emp_id',
        'pro_head' => 'required|exists:employees,emp_id',
        'pro_member' => 'required|array|min:1',
        'pro_member.*' => 'exists:employees,emp_id',
        'pro_accessmod' => 'required|boolean',
        'send_email' => 'nullable|boolean',
    ]);
    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }
    try {
        $avatarPath = null;
        if ($request->hasFile('pro_avater')) {
            $file = $request->file('pro_avater');
            $currentDateTime = Carbon::now()->format('dmYHis');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $fileName = $currentDateTime . '_' . Str::slug($originalName) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('project_avatars');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $file->move($destinationPath, $fileName);
            $avatarPath = 'project_avatars/' . $fileName;
        }
        $members = is_array($request->pro_member) ? $request->pro_member : [];
        $project = Project::create([
            'pro_name' => $request->pro_name,
            'pro_desc' => $request->pro_desc,
            'pro_avater' => $avatarPath,
            'pro_attachment' => json_encode([]),
            'pro_deadline' => $request->pro_deadline,
            'pro_client' => $request->pro_client,
            'pro_lead' => $request->pro_lead,
            'pro_head' => $request->pro_head,
            'pro_member' => !empty($members) ? json_encode($members) : json_encode([]),
            'pro_accessmod' => $request->pro_accessmod,
            'delete_status' => 1,
            'pro_status' => 0,
            'pro_emailvia' => $request->has('send_email') ? 1 : 0,
        ]);
        $pmtsIds = [];
        if ($request->hasFile('pro_attachment')) {
            foreach ($request->file('pro_attachment') as $file) {
                $currentDateTime = Carbon::now()->format('dmYHis');
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $fileName = $currentDateTime . '_' . Str::slug($originalName) . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('project_attachments');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                $file->move($destinationPath, $fileName);
                $pmtsImage = PmtsImage::create([
                    'pmtsimage_name' => $fileName,
                    'menu_id' => $project->pro_id,
                    'menu_type' => 'project',
                    'delete_status' => 1,
                ]);
                $pmtsIds[] = $pmtsImage->pmts_id;
            }
            $project->update([
                'pro_attachment' => json_encode($pmtsIds)
            ]);
        }
      if ($request->has('send_email') && $request->send_email == 1) {
    $project->sendEmailNotification(false);
}
        return redirect()->route('projectlist')
            ->with('success', 'Project created successfully!');
    } catch (\Exception $e) {
        \Log::error('Project creation error: ' . $e->getMessage());
        return redirect()->back()
            ->with('error', 'Error creating project: ' . $e->getMessage())
            ->withInput();
    }
}
    public function searchEmployees(Request $request)
    {
        $searchTerm = $request->input('term');
        $type = $request->input('type', 'employee');
        $excludeIds = $request->input('exclude_ids', []);

        if ($type === 'client') {
            $clients = Client::where('cl_name', 'LIKE', "%{$searchTerm}%")
                ->orWhere('cl_email', 'LIKE', "%{$searchTerm}%")
                ->select('cl_id', 'cl_name', 'cl_email', 'cl_image')
                ->limit(10)
                ->get()
                ->map(function ($client) {
                    return [
                        'id' => $client->cl_id,
                        'fullname' => $client->cl_name,
                        'email' => $client->cl_email,
                        'image' => $client->cl_image,
                        'type' => 'client',
                        'employee_id' => 'CL-' . $client->cl_id,
                        'department' => ['dep_name' => 'Client']
                    ];
                });

            return response()->json($clients);
        } else {
            $employees = Employee::with('Departmentid', 'Designationid')
                ->where(function($query) use ($searchTerm) {
                    $query->where('fullname', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('employee_id', 'LIKE', "%{$searchTerm}%");
                });

            if (!empty($excludeIds)) {
                $employees = $employees->whereNotIn('emp_id', $excludeIds);
            }

            $employees = $employees->select(
                    'emp_id',
                    'fullname',
                    'employee_id',
                    'email_company as email',
                    'cur_department',
                    'cur_designation',
                    'image'
                )
                ->limit(10)
                ->get()
                ->map(function ($employee) {
                    $employee->department = $employee->Departmentid ? [
                        'dep_id' => $employee->Departmentid->dep_id,
                        'dep_name' => $employee->Departmentid->dep_name
                    ] : null;
                    $employee->designation = $employee->Designationid ? [
                        'des_id' => $employee->Designationid->des_id,
                        'des_name' => $employee->Designationid->des_name
                    ] : null;
                    $employee->type = 'employee';

                    return $employee;
                });

            return response()->json($employees);
        }
    }

    public function getMemberNames(Request $request)
    {
        $memberIds = $request->input('member_ids', []);

        if (empty($memberIds)) {
            return response()->json(['memberNames' => []]);
        }

        $members = Employee::whereIn('emp_id', $memberIds)
            ->get(['emp_id', 'fullname'])
            ->pluck('fullname', 'emp_id')
            ->toArray();

        return response()->json(['memberNames' => $members]);
    }

    public function edit($id)
    {
        try {
            $project = Project::with([
                'projectLead',
                'projectHead',
                'client',
                'pmtsImages'
            ])->findOrFail($id);

            $employees = Employee::with('Departmentid')
                ->get(['emp_id', 'fullname', 'employee_id', 'email_company as email', 'image', 'cur_department']);

            return view('dashboard.hr.kanbanboard.project.edit', compact('project', 'employees'));
        } catch (\Exception $e) {
            \Log::error('Error fetching project for edit: ' . $e->getMessage());
            return redirect()->route('projectlist')
                ->with('error', 'Project not found.');
        }
    }

    public function showDetails($id)
    {
        try {
            $project = Project::with([
                'projectLead',
                'projectHead',
                'client',
                'pmtsImages'
            ])->findOrFail($id);

            return view('dashboard.hr.kanbanboard.project.details', compact('project'));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Project not found'], 404);
        }
    }

 public function update(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'pro_name' => 'required|string|max:255',
        'pro_desc' => 'required|string',
        'pro_avater' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        'pro_attachment' => 'nullable|array',
        'pro_attachment.*' => 'file|mimes:jpg,jpeg,png,pdf,ppt,pptx,csv,xlsx,xls,doc,docx|max:10240',
        'pro_deadline' => 'required|date',
        'pro_client' => 'required|exists:clients,cl_id',
        'pro_lead' => 'required|exists:employees,emp_id',
        'pro_head' => 'required|exists:employees,emp_id',
        'pro_member' => 'required|array|min:1',
        'pro_member.*' => 'exists:employees,emp_id',
        'pro_accessmod' => 'required|boolean',
        'pro_status' => 'required|in:0,1,2',
        'send_email' => 'nullable|boolean',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    try {
        $project = Project::findOrFail($id);

        $avatarPath = $project->pro_avater;
        if ($request->hasFile('pro_avater')) {
            if ($avatarPath && file_exists(public_path($avatarPath))) {
                unlink(public_path($avatarPath));
            }

            $file = $request->file('pro_avater');
            $currentDateTime = Carbon::now()->format('dmYHis');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $fileName = $currentDateTime . '_' . Str::slug($originalName) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('project_avatars');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            $file->move($destinationPath, $fileName);
            $avatarPath = 'project_avatars/' . $fileName;
        }

        if ($request->has('remove_avater') && $request->remove_avater) {
            if ($avatarPath && file_exists(public_path($avatarPath))) {
                unlink(public_path($avatarPath));
            }
            $avatarPath = null;
        }

        $existingPmtsIds = [];
        if ($project->pro_attachment) {
            $existingPmtsIds = json_decode($project->pro_attachment, true) ?? [];
        }

        $newPmtsIds = [];
        if ($request->hasFile('pro_attachment')) {
            foreach ($request->file('pro_attachment') as $file) {
                $currentDateTime = Carbon::now()->format('dmYHis');
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $fileName = $currentDateTime . '_' . Str::slug($originalName) . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('project_attachments');

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }

                $file->move($destinationPath, $fileName);

                $pmtsImage = PmtsImage::create([
                    'pmtsimage_name' => $fileName,
                    'menu_id' => $project->pro_id,
                    'menu_type' => 'project',
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

        $members = is_array($request->pro_member) ? $request->pro_member : [];

        $updateData = [
            'pro_name' => $request->pro_name,
            'pro_desc' => $request->pro_desc,
            'pro_avater' => $avatarPath,
            'pro_attachment' => !empty($allPmtsIds) ? json_encode($allPmtsIds) : json_encode([]),
            'pro_deadline' => $request->pro_deadline,
            'pro_client' => $request->pro_client,
            'pro_lead' => $request->pro_lead,
            'pro_head' => $request->pro_head,
            'pro_member' => !empty($members) ? json_encode($members) : json_encode([]),
            'pro_accessmod' => $request->pro_accessmod,
            'pro_status' => $request->pro_status,
        ];

        if ($request->pro_status == 1 && !$project->pro_onprogress) {
            $updateData['pro_onprogress'] = Carbon::now();
        }

        if ($request->pro_status == 2) {
            if (!$project->pro_complete) {
                $updateData['pro_complete'] = Carbon::now();
            }

            $completionDate = $project->pro_complete ? Carbon::parse($project->pro_complete) : Carbon::now();
            $deadline = Carbon::parse($request->pro_deadline);

            if ($completionDate->gt($deadline)) {
                $updateData['pro_overdue'] = $deadline->diffInDays($completionDate);
            } else {
                $updateData['pro_overdue'] = 0;
            }
        } else {
            $today = Carbon::now();
            $deadline = Carbon::parse($request->pro_deadline);

            if ($today->gt($deadline)) {
                $updateData['pro_overdue'] = $deadline->diffInDays($today);
            } else {
                $updateData['pro_overdue'] = 0;
            }
        }

        $project->update($updateData);
if ($request->has('send_email') && $request->send_email == 1) {
    $project->sendEmailNotification(true);
}

        return redirect()->route('projectlist')
            ->with('success', 'Project updated successfully!');
    } catch (\Exception $e) {
        \Log::error('Project update error: ' . $e->getMessage());
        return redirect()->back()
            ->with('error', 'Error updating project: ' . $e->getMessage())
            ->withInput();
    }
}
    public function destroy($id)
    {
        try {
            $project = Project::findOrFail($id);

            PmtsImage::where('menu_id', $id)
                ->where('menu_type', 'project')
                ->update(['delete_status' => 0]);

            $project->update([
                'delete_status' => 0
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Project deleted successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Project deletion error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error deleting project'
            ], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $project = Project::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'pro_status' => 'required|in:0,1,2'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status value'
                ], 400);
            }

            $project->updateStatus($request->pro_status);

            return response()->json([
                'success' => true,
                'message' => 'Project status updated successfully',
                'status_text' => $project->status_text,
                'status_badge' => $project->status_badge_class,
                'overdue_text' => $project->overdue_text
            ]);
        } catch (\Exception $e) {
            \Log::error('Project status update error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error updating project status'
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
                    'url' => asset('project_attachments/' . $image->pmtsimage_name),
                    'type' => pathinfo($image->pmtsimage_name, PATHINFO_EXTENSION)
                ];
            })
            ->toArray();

        return response()->json(['images' => $images]);
    }
}
