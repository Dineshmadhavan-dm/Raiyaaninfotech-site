<?php

namespace App\Http\Controllers\Dashboard\HR\Kanbanboard;

use App\Http\Controllers\Controller;
use App\Models\Modulo;
use App\Models\Project;
use App\Models\Employee;
use App\Models\PmtsImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ModuloController extends Controller
{
    public function __construct()
{
    $this->middleware(function ($request, $next) {
        $user = auth()->user();

        if ($user->categorie == 2) {
            return redirect()->route('emphome')->with('error', 'Access denied.');
        }

        return $next($request);
    });
}

    public function modulolist()
    {
        $modulos = Modulo::with(['project', 'project.projectLead', 'project.projectHead', 'project.client', 'pmtsImages'])
            ->where('delete_status', 1)
            ->get();

        $projects = Project::where('delete_status', 1)->get(['pro_id', 'pro_name']);

        return view('dashboard.hr.kanbanboard.module.list', compact('modulos', 'projects'));
    }

    public function modulocreate($projectId = null)
    {
        $project = null;
        $projectMembers = [];

        if ($projectId) {
            $project = Project::with(['projectLead', 'projectHead', 'client'])->find($projectId);

            if ($project) {
                try {
                    $rawData = $project->pro_member;

                    \Log::info("Raw member data for project {$projectId}: {$rawData}");

                    if ($rawData && !empty(trim($rawData))) {
                        if (is_string($rawData)) {
                            $cleanedData = trim($rawData, ' "');
                            $cleanedData = trim($cleanedData, '[]');

                            if (str_contains($cleanedData, '","')) {
                                $cleanedData = str_replace('\\"', '"', $cleanedData);
                                $cleanedData = trim($cleanedData, '[]');
                            }

                            $tempMembers = explode(',', $cleanedData);
                            $memberIds = [];

                            foreach ($tempMembers as $member) {
                                $cleanMember = trim($member, ' "\\[]');

                                if (str_contains($cleanMember, ',')) {
                                    $nestedMembers = explode(',', $cleanMember);
                                    foreach ($nestedMembers as $nestedMember) {
                                        $cleanNested = trim($nestedMember, ' "\\[]');
                                        if (is_numeric($cleanNested) && $cleanNested > 0) {
                                            $memberIds[] = (int)$cleanNested;
                                        }
                                    }
                                } elseif (is_numeric($cleanMember) && $cleanMember > 0) {
                                    $memberIds[] = (int)$cleanMember;
                                }
                            }

                            $memberIds = array_unique($memberIds);

                            \Log::info("Parsed member IDs for project {$projectId}: " . implode(', ', $memberIds));

                            if (!empty($memberIds)) {
                                $projectMembers = Employee::whereIn('emp_id', $memberIds)
                                    ->get(['emp_id', 'fullname', 'employee_id', 'email_company as email', 'image']);

                                \Log::info("Found project members: " . $projectMembers->count());
                            }
                        } elseif (is_array($rawData)) {
                            $memberIds = array_filter(
                                array_map('intval', $rawData),
                                function ($id) {
                                    return $id > 0;
                                }
                            );

                            if (!empty($memberIds)) {
                                $projectMembers = Employee::whereIn('emp_id', $memberIds)
                                    ->get(['emp_id', 'fullname', 'employee_id', 'email_company as email', 'image']);
                            }
                        }
                    } else {
                        \Log::info("No member data found for project {$projectId}");
                    }
                } catch (\Exception $e) {
                    \Log::error('Error parsing project members for modulo create: ' . $e->getMessage());
                    \Log::error('Raw data that caused error: ' . $rawData);
                }
            } else {
                \Log::warning("Project not found with ID: {$projectId}");
            }
        } else {
            \Log::info("No project ID provided for modulo create");
        }

        \Log::info("Passing to view - Project: " . ($project ? $project->pro_id : 'null'));
        \Log::info("Passing to view - Project Members count: " . count($projectMembers));

        return view('dashboard.hr.kanbanboard.module.create', compact('project', 'projectMembers'));
    }

    public function modulostore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mod_name' => 'required|string|max:255',
            'mod_desc' => 'required|string',
            'mod_avater' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'mod_attachment' => 'nullable|array',
            'mod_attachment.*' => 'file|mimes:jpg,jpeg,png,pdf,ppt,pptx,csv,xlsx,xls,doc,docx|max:10240',
            'mod_deadline' => 'required|date',
            'mod_project' => 'required|exists:projects,pro_id',
            'mod_member' => 'required|array|min:1',
            'mod_member.*' => 'exists:employees,emp_id',
            'mod_accessmod' => 'required|boolean',
            'send_email' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $avatarPath = null;
            if ($request->hasFile('mod_avater')) {
                $file = $request->file('mod_avater');
                $currentDateTime = Carbon::now()->format('dmYHis');
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $fileName = $currentDateTime . '_' . Str::slug($originalName) . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('modulo_avatars');

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }

                $file->move($destinationPath, $fileName);
                $avatarPath = 'modulo_avatars/' . $fileName;
            }

            $members = is_array($request->mod_member) ? $request->mod_member : [];

            $today = Carbon::now();
            $deadline = Carbon::parse($request->mod_deadline);
            $overdueDays = 0;

            if ($today->gt($deadline)) {
                $overdueDays = $deadline->diffInDays($today);
            }

            $modulo = Modulo::create([
                'mod_name' => $request->mod_name,
                'mod_desc' => $request->mod_desc,
                'mod_avater' => $avatarPath,
                'mod_attachment' => json_encode([]),
                'mod_deadline' => $request->mod_deadline,
                'mod_project' => $request->mod_project,
                'mod_member' => !empty($members) ? json_encode($members) : json_encode([]),
                'mod_accessmod' => $request->mod_accessmod,
                'delete_status' => 1,
                'mod_status' => 0,
                'mod_overdue' => $overdueDays,
                'mod_emailvia' => $request->has('send_email') ? 1 : 0,
            ]);

            $pmtsIds = [];
            if ($request->hasFile('mod_attachment')) {
                foreach ($request->file('mod_attachment') as $file) {
                    $currentDateTime = Carbon::now()->format('dmYHis');
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $fileName = $currentDateTime . '_' . Str::slug($originalName) . '.' . $file->getClientOriginalExtension();
                    $destinationPath = public_path('modulo_attachments');

                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0777, true);
                    }

                    $file->move($destinationPath, $fileName);
                    $filePath = 'modulo_attachments/' . $fileName;

                    $pmtsImage = PmtsImage::create([
                        'pmtsimage_name' => $fileName,
                        'menu_id' => $modulo->mod_id,
                        'menu_type' => 'modulo',
                        'delete_status' => 1,
                    ]);

                    $pmtsIds[] = $pmtsImage->pmts_id;
                }

                $modulo->update([
                    'mod_attachment' => json_encode($pmtsIds)
                ]);
            }

          if ($request->has('send_email') && $request->send_email == 1) {
    $modulo->sendEmailNotification(false);
}

            return redirect()->route('modulolist')
                ->with('success', 'Module created successfully!');
        } catch (\Exception $e) {
            \Log::error('Module creation error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error creating module: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function moduloedit($id)
    {
        try {
            $modulo = Modulo::with(['project', 'project.projectLead', 'project.projectHead', 'project.client', 'pmtsImages'])->findOrFail($id);

            $selectedMemberIds = [];
            try {
                $memberData = $modulo->mod_member;
                if (is_string($memberData)) {
                    $decoded = json_decode($memberData, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        $selectedMemberIds = $decoded;
                    }
                } elseif (is_array($memberData)) {
                    $selectedMemberIds = $memberData;
                }
            } catch (\Exception $e) {
                \Log::error('Error parsing modulo members: ' . $e->getMessage());
            }

            $projectMembers = [];
            if ($modulo->project && $modulo->project->pro_member) {
                try {
                    $rawData = $modulo->project->pro_member;
                    $projectMemberIds = [];

                    $cleaned = trim($rawData, '[]"');
                    $cleaned = str_replace('\\"', '', $cleaned);
                    $cleaned = str_replace('"', '', $cleaned);

                    $allParts = explode(',', $cleaned);

                    foreach ($allParts as $part) {
                        $cleanPart = trim($part);
                        if (is_numeric($cleanPart) && $cleanPart > 0) {
                            $projectMemberIds[] = (int)$cleanPart;
                        }
                    }

                    $projectMemberIds = array_unique($projectMemberIds);

                    if (!empty($projectMemberIds)) {
                        $projectMembers = Employee::whereIn('emp_id', $projectMemberIds)
                            ->get(['emp_id', 'fullname', 'employee_id', 'email_company as email', 'image']);
                    }
                } catch (\Exception $e) {
                    \Log::error('Error parsing project members in modulo edit: ' . $e->getMessage());
                }
            }

            return view('dashboard.hr.kanbanboard.module.edit', compact('modulo', 'selectedMemberIds', 'projectMembers'));
        } catch (\Exception $e) {
            \Log::error('Error fetching modulo for edit: ' . $e->getMessage());
            return redirect()->route('modulolist')
                ->with('error', 'Module not found.');
        }
    }

   public function moduloupdate(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'mod_name' => 'required|string|max:255',
        'mod_desc' => 'required|string',
        'mod_avater' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        'mod_attachment' => 'nullable|array',
        'mod_attachment.*' => 'file|mimes:jpg,jpeg,png,pdf,ppt,pptx,csv,xlsx,xls,doc,docx|max:10240',
        'mod_deadline' => 'required|date',
        'mod_member' => 'required|array|min:1',
        'mod_member.*' => 'exists:employees,emp_id',
        'mod_accessmod' => 'required|boolean',
        'mod_status' => 'required|in:0,1,2',
        'send_email' => 'nullable|boolean',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    try {
        $modulo = Modulo::findOrFail($id);

        $avatarPath = $modulo->mod_avater;
        if ($request->hasFile('mod_avater')) {
            if ($avatarPath && file_exists(public_path($avatarPath))) {
                unlink(public_path($avatarPath));
            }

            $file = $request->file('mod_avater');
            $currentDateTime = Carbon::now()->format('dmYHis');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $fileName = $currentDateTime . '_' . Str::slug($originalName) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('modulo_avatars');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            $file->move($destinationPath, $fileName);
            $avatarPath = 'modulo_avatars/' . $fileName;
        }

        if ($request->has('remove_avater') && $request->remove_avater) {
            if ($avatarPath && file_exists(public_path($avatarPath))) {
                unlink(public_path($avatarPath));
            }
            $avatarPath = null;
        }

        $existingPmtsIds = [];
        if ($modulo->mod_attachment) {
            $existingPmtsIds = json_decode($modulo->mod_attachment, true) ?? [];
        }

        $newPmtsIds = [];
        if ($request->hasFile('mod_attachment')) {
            foreach ($request->file('mod_attachment') as $file) {
                $currentDateTime = Carbon::now()->format('dmYHis');
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $fileName = $currentDateTime . '_' . Str::slug($originalName) . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('modulo_attachments');

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }

                $file->move($destinationPath, $fileName);
                $filePath = 'modulo_attachments/' . $fileName;

                $pmtsImage = PmtsImage::create([
                    'pmtsimage_name' => $fileName,
                    'menu_id' => $modulo->mod_id,
                    'menu_type' => 'modulo',
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

        $members = is_array($request->mod_member) ? $request->mod_member : [];

        $updateData = [
            'mod_name' => $request->mod_name,
            'mod_desc' => $request->mod_desc,
            'mod_avater' => $avatarPath,
            'mod_attachment' => !empty($allPmtsIds) ? json_encode($allPmtsIds) : json_encode([]),
            'mod_deadline' => $request->mod_deadline,
            'mod_member' => !empty($members) ? json_encode($members) : json_encode([]),
            'mod_accessmod' => $request->mod_accessmod,
            'mod_status' => $request->mod_status,
        ];

        if ($request->mod_status == 1 && !$modulo->mod_onprogress) {
            $updateData['mod_onprogress'] = Carbon::now();
        }

        if ($request->mod_status == 2) {
            if (!$modulo->mod_complete) {
                $updateData['mod_complete'] = Carbon::now();
            }

            $completionDate = $modulo->mod_complete ? Carbon::parse($modulo->mod_complete) : Carbon::now();
            $deadline = Carbon::parse($request->mod_deadline);

            if ($completionDate->gt($deadline)) {
                $updateData['mod_overdue'] = $deadline->diffInDays($completionDate);
            } else {
                $updateData['mod_overdue'] = 0;
            }
        } else {
            $today = Carbon::now();
            $deadline = Carbon::parse($request->mod_deadline);

            if ($today->gt($deadline)) {
                $updateData['mod_overdue'] = $deadline->diffInDays($today);
            } else {
                $updateData['mod_overdue'] = 0;
            }
        }

        $modulo->update($updateData);

        if ($request->has('send_email') && $request->send_email == 1) {
            $modulo->sendEmailNotification(true);
        }

        return redirect()->route('modulolist')
            ->with('success', 'Module updated successfully!');
    } catch (\Exception $e) {
        \Log::error('Module update error: ' . $e->getMessage());
        return redirect()->back()
            ->with('error', 'Error updating module: ' . $e->getMessage())
            ->withInput();
    }
}

    public function modulodestroy($id)
    {
        try {
            $modulo = Modulo::findOrFail($id);

            PmtsImage::where('menu_id', $id)
                ->where('menu_type', 'modulo')
                ->update(['delete_status' => 0]);

            $modulo->update([
                'delete_status' => 0
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Module deleted successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Module deletion error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error deleting module'
            ], 500);
        }
    }

    public function getModuloDetails($id)
    {
        try {
            $modulo = Modulo::with(['project', 'project.projectLead', 'project.projectHead', 'project.client', 'pmtsImages'])->findOrFail($id);

            $memberNames = [];
            try {
                if ($modulo->mod_member) {
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
                        $members = Employee::whereIn('emp_id', $memberIds)->get(['fullname']);
                        $memberNames = $members->pluck('fullname')->toArray();
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Error parsing modulo members: ' . $e->getMessage());
            }

            $html = view('dashboard.hr.kanbanboard.module.partials.modulo-details', compact('modulo', 'memberNames'))->render();

            return response()->json([
                'success' => true,
                'modulo' => $modulo,
                'html' => $html
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Module not found'
            ], 404);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $modulo = Modulo::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'mod_status' => 'required|in:0,1,2'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status value'
                ], 400);
            }

            $modulo->updateStatus($request->mod_status);

            return response()->json([
                'success' => true,
                'message' => 'Module status updated successfully',
                'status_text' => $modulo->status_text,
                'status_badge' => $modulo->status_badge_class,
                'overdue_text' => $modulo->overdue_text
            ]);
        } catch (\Exception $e) {
            \Log::error('Module status update error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error updating module status'
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
                    'url' => asset('modulo_attachments/' . $image->pmtsimage_name),
                    'type' => pathinfo($image->pmtsimage_name, PATHINFO_EXTENSION)
                ];
            })
            ->toArray();

        return response()->json(['images' => $images]);
    }
}
