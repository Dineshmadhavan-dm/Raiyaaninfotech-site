<?php

namespace App\Http\Controllers\Dashboard\HR;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use App\Rules\MatchOldPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;


class HrDashboardController extends Controller
{



   public function __construct()
    {
        // Apply middleware to all methods except 'index'
        $this->middleware(function ($request, $next) {
            $user = auth()->user();

            // Check user category - only allow 1 (Super Admin) and 3 (Admin)
            if ($user->categorie == 2) { // Employee
                return redirect()->route('emphome')->with('error', 'Access denied.');
            }

            return $next($request);
        })->only(['index']);
    }






    public function  index()
    {
        $user = Auth::user();

        $admincount =  User::with(['employee', 'roles'])
            ->where('delete_status', 1)
            ->whereHas('roles', function ($q) {
                $q->where('name', 'Admin');
            })->get();



        $employeecount =  Employee::where('delete_status', 1)->get();
        $branchcount =  Branch::where('delete_status', 1)->get();
        $depcount =  Department::where('delete_status', 1)->get();



      if ($user->categorie == '1' || $user->hasRole('Admin') || $user->hasRole('Super admin')) {
    return view('dashboard.hr.hrdashboard.index', compact('admincount', 'employeecount', 'branchcount', 'depcount'));
} else {
    return view('dashboard.employee.empdashboard.index');
}
    }
    public function  maintenance()
    {
        return view('dashboard.netural.setting.maintenance');
    }
    public function  setting()
    {
        return view('dashboard.netural.setting.applogo');
    }








    public function adminshow($id)
    {
        $user = User::findOrFail($id);
        return view('dashboard.hr.admin.show', compact('user'));
    }


























    public function adminempshow($id)
    {
        // Get employee with all relationships
        $personal = User::with([
            'families',
            'educations',
            'pastemps',
            'roles.permissions', // Eager load roles with permissions
            'permissions',
            'prorefs',
            'employee'
        ])->where('id', $id)
            ->where('delete_status', 1)
            ->firstOrFail();

        // Check if employee has any roles
        if ($personal->roles->isEmpty()) {
            return redirect()->back()->with('error', 'Employee has no role assigned');
        }

        // Get all permissions (from roles and direct assignments)
        $allPermissions = $personal->getAllPermissions();

        // Group permissions hierarchically
        $grouped = [];
        $hierarchyMap = [];

        foreach ($allPermissions as $permission) {
            $parts = explode(' ', $permission->name, 2);
            $resource = $parts[0] ?? 'other';
            $action = $parts[1] ?? '';

            // Handle hierarchical resources
            if (str_contains($resource, '->')) {
                $hierarchy = explode('->', $resource);
                $parent = $hierarchy[0];
                $child = $hierarchy[1];

                // Track parent-child relationships
                if (!isset($hierarchyMap[$parent])) {
                    $hierarchyMap[$parent] = [];
                }
                if (!in_array($child, $hierarchyMap[$parent])) {
                    $hierarchyMap[$parent][] = $child;
                }

                // Group by full resource path
                if (!isset($grouped[$resource])) {
                    $grouped[$resource] = [];
                }
                $grouped[$resource][] = [
                    'permission' => $permission,
                    'action' => $action,
                    'checked' => true // Since we're getting all assigned permissions
                ];
            } else {
                // Regular permission
                if (!isset($grouped[$resource])) {
                    $grouped[$resource] = [];
                }
                $grouped[$resource][] = [
                    'permission' => $permission,
                    'action' => $action,
                    'checked' => true // Since we're getting all assigned permissions
                ];
            }
        }

        // Sort grouped permissions alphabetically
        ksort($grouped);

        return view('dashboard.hr.employee.adminshow', compact(
            'personal',
            'grouped',
            'hierarchyMap'
        ));
    }






    public function createadmin(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'nullable|string|regex:/^data:image\/\w+;base64,/',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'roles' => 'nullable|array', // Add validation for roles
        ]);

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');

        $user->password = Hash::make($request->input('password'));

        $user->save();







        if ($request->has('image') && $request->input('image')) {
            $this->processBase64Image($request->input('image'), $user);
        }
        $user->syncRoles($request->roles);
        return response()->json([
            'status' => true,
            'message' => 'Admin created successfully'
        ]);
    }







    public function adminedit($id)
    {
        try {
            $user = User::with(['employee', 'roles'])
                ->findOrFail($id);

            $allRoles = Role::pluck('name')->toArray();

            return response()->json([
                'status' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->employee->fullname ?? 'N/A',
                    'email' => $user->email,
                    'image' => $user->employee->image ?? null,
                    'current_roles' => $user->roles->pluck('name')->toArray(),
                    'all_roles' => $allRoles
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'User not found',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    public function adminupdate(Request $request, $id)
    {
        $request->validate([
            'roles' => 'required|array|min:1',
            'roles.*' => 'string|exists:roles,name'
        ]);

        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);

            // Sync roles (replaces all existing roles)
            $user->syncRoles($request->roles);

            // Update current role in employee if applicable
            if ($user->employee) {
                $user->employee->update([
                    'cur_role' => $request->roles[0] ?? null
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Admin roles updated successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Failed to update roles: ' . $e->getMessage()
            ], 500);
        }
    }

































    public function admindestroy($id)
    {
        $admin = User::findOrFail($id);

        // Delete image if exists
        if ($admin->image && file_exists(public_path('admin_images/' . $admin->image))) {
            unlink(public_path('admin_images/' . $admin->image));
        }

        $admin->update(['delete_status' => 0]);

        return response()->json([
            'status' => true,
            'message' => 'Admin deleted successfully'
        ]);
    }

    private function processBase64Image($imageData, $user)
    {
        $extension = explode(';', explode('/', $imageData)[1])[0];
        $image = base64_decode(explode(',', $imageData)[1]);

        $imageName = $user->id . '.' . $extension;
        $folderPath = public_path('admin_images');
        $imagePath = $folderPath . '/' . $imageName;

        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        file_put_contents($imagePath, $image);
        $user->image = $imageName;
        $user->save();
    }




    // public function  profile(Request $request)
    // {

    //     $user = Auth::user();
    //     return view('dashboard.hr.admin.profile', compact('user'));
    // }




    public function profile(Request $request, $id = null)
    {
        $currentUser = Auth::user();

        // Super admin logic
        if ($currentUser->hasRole('Super admin')) {
            $user = $id ? User::findOrFail($id) : $currentUser;
            return view('dashboard.hr.admin.profile', compact('user'));
        }

        // Validate ID for non-super admins
        if ($id && $id != $currentUser->id) {
            abort(403, 'Unauthorized action.');
        }

        // For regular users (including Admin role)
        $personal = User::with([
            'families',
            'educations',
            'pastemps',
            'prorefs',
            'employee'
        ])
            ->where('id', $currentUser->id)
            ->where('delete_status', 1)
            ->firstOrFail();

        return view('dashboard.hr.admin.profileshow2', compact('personal'));
    }


    public function profilepost(Request $request)
    {
        $request->validate([
            'image' => 'required|string',
        ]);

        $imageData = $request->input('image');
        $extension = explode(';', explode('/', $imageData)[1])[0];
        $image = base64_decode(explode(',', $imageData)[1]);

        $user = Auth::user();
        $imageName = $user->id . '.' . $extension;

        $folderPath = public_path('admin_images');
        $imagePath = $folderPath . '/' . $imageName;

        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        file_put_contents($imagePath, $image);
        $user->image = $imageName;
        $user->save();

        return redirect()->route('profile')->with('success', 'Image successfully uploaded!');
    }




    public  function  cpwd()
    {

        return  view('dashboard.hr.admin.cpwd');
    }

    public  function  cpwdpost(Request  $request)
    {

        $request->validate([
            'current_password' => ['required',  new MatchOldPassword],
            'password' => 'required',
            'confirm_password' => 'same:password',
        ]);

        User::find(Auth::user()->id)->update(['password' => Hash::make($request->password)]);

        return redirect()->back()->with('success', 'password has been changed');
    }

    public function bulkDelete(Request $request)
    {
        $userIds = $request->input('ids');

        $users = User::whereIn('id', $userIds)  // Changed from employeerole_id to id
            ->get();

        foreach ($users as $user) {
            // Update user's delete_status
            $user->update(['delete_status' => 0]);
        }


        return response()->json(['success' => true]);
    }



    // In your controller (e.g., AdminController.php)
    // In your AdminController or relevant controller

    public function admineditcolumn()
    {
        $defaultColumns = ['serial', 'image', 'name', 'email', 'department', 'role', 'action'];
        // $defaultColumns = ['checkbox', 'serial', 'image', 'name', 'email', 'role', 'action'];

        // Get user's column preferences
        $user = auth()->user();
        $columnPreferences = $user->column_preferences;

        // Decode JSON safely
        $visibleColumns = [];
        if ($columnPreferences) {
            $decoded = json_decode($columnPreferences, true);
            $visibleColumns = is_array($decoded) ? $decoded : $defaultColumns;
        } else {
            $visibleColumns = $defaultColumns;
        }

        // Define all possible columns with their display names
        $allColumns = [
            // 'checkbox' => 'Checkbox',
            'serial' => 'Serial #',
            'image' => 'Image',
            'name' => 'Name',
            'email' => 'Email',
            'department' => 'Department',
            'role' => 'Role',
            'action' => 'Action'
        ];

        // Maintain order while including all columns
        $orderedColumns = [];

        // First add visible columns in their current order
        foreach ($visibleColumns as $col) {
            if (array_key_exists($col, $allColumns)) {
                $orderedColumns[$col] = $allColumns[$col];
            }
        }

        // Then add any remaining columns that weren't visible
        foreach ($allColumns as $col => $name) {
            if (!isset($orderedColumns[$col])) {
                $orderedColumns[$col] = $name;
            }
        }

        return view('dashboard.hr.admin.managecolumn', [
            'visibleColumns' => $visibleColumns,
            'orderedColumns' => $orderedColumns,
            'allColumns' => $allColumns,
            'defaultColumns' => $defaultColumns
        ]);
    }

    public function saveColumns(Request $request)
    {
        try {
            $validated = $request->validate([
                'columns' => 'required|array',
                'columns.*' => 'string',
                'order' => 'sometimes|array',
                'order.*' => 'string'
            ]);

            $user = auth()->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Process column order if provided
            $columnsToSave = $validated['columns'];
            if (!empty($validated['order'])) {
                // Maintain order while ensuring all selected columns are included
                $orderedColumns = array_values(array_intersect($validated['order'], $validated['columns']));
                $remainingColumns = array_diff($validated['columns'], $orderedColumns);
                $columnsToSave = array_merge($orderedColumns, $remainingColumns);
            }

            // Update user preferences
            $user->column_preferences = json_encode(array_values($columnsToSave));
            $saved = $user->save();

            if (!$saved) {
                throw new \Exception('Failed to save user preferences');
            }

            return response()->json([
                'success' => true,
                'message' => 'Columns saved successfully',
                'columns' => $columnsToSave
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving columns: ' . $e->getMessage()
            ], 500);
        }
    }





















    public function adminlist()
    {
        $defaultColumns = ['checkbox', 'serial', 'image', 'name', 'email', 'department', 'role', 'action'];

        $columnPreferences = auth()->user()->column_preferences ?? null;
        $visibleColumns = $columnPreferences ? json_decode($columnPreferences, true) : $defaultColumns;

        if (!is_array($visibleColumns)) {
            $visibleColumns = $defaultColumns;
        }

        $user = auth()->user();

        // Get all users with employee and role data
        $query = User::with(['employee', 'roles'])
            ->where('delete_status', 1)

            ->whereHas('roles', function ($q) {
                $q->where('name', 'Admin');
            });


        $users = $query->get();
        $roles = Role::all()->pluck('name', 'name');

        return view('dashboard.hr.admin.list', compact('users', 'roles', 'visibleColumns'));
    }
}
