<?php

namespace App\Http\Controllers\Dashboard\HR;


use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CompanyEmailController extends Controller
{


    public  function __construct()
    {
        $this->middleware('permission:login->email view')->only(['index']);
        $this->middleware('permission:login->email edit')->only(['edit', 'update', 'getPermissionForRoles']);

        $this->middleware('permission:login->email create')->only(['create', 'store', 'getPermissionForRoles']);
    }





    public function index()
    {
        $employees = Employee::with(['user', 'user.roles' => function ($query) {
            $query->where('name', '!=', 'Super Admin');
        }])
            ->where('delete_status', 1)
            ->orderByDesc('created_at')
            ->get();

        $allRoles = Role::where('name', '!=', 'Super Admin')->pluck('name');

        return view('dashboard.hr.other.companyemail.index', [
            'employees' => $employees,
            'allRoles' => $allRoles
        ]);
    }

    public function create(Employee $employee)
    {
        $allRoles = Role::where('name', '!=', 'Super Admin')->pluck('name');
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode(' ', $permission->name)[0];
        });

        return view('dashboard.hr.other.companyemail.create', [
            'employee' => $employee,
            'allRoles' => $allRoles,
            'permissions' => $permissions,
            'designation' => $employee->cur_designation ?? 'N/A',
            'department' => $employee->cur_department ?? 'N/A'
        ]);
    }




    public function getPermissionsForRoles(Request $request)
    {
        if (in_array('All Roles', $request->roles) || count($request->roles) === Role::count()) {
            $permissions = Permission::all();
        } else {
            $roles = Role::whereIn('name', $request->roles)->with('permissions')->get();
            $permissions = collect();
            foreach ($roles as $role) {
                $permissions = $permissions->merge($role->permissions);
            }
            $permissions = $permissions->unique('id');
        }

        // Group permissions hierarchically
        $grouped = [];
        foreach ($permissions as $permission) {
            $parts = explode(' ', $permission->name, 2);
            $resource = $parts[0] ?? 'other';
            $action = $parts[1] ?? '';

            // Handle Site->Setting case
            if (str_contains($resource, '->')) {
                $hierarchy = explode('->', $resource);
                $parent = trim($hierarchy[0]);
                $child = trim($hierarchy[1]);

                if (!isset($grouped[$parent])) {
                    $grouped[$parent] = [];
                }

                if (!isset($grouped[$parent][$child])) {
                    $grouped[$parent][$child] = [];
                }

                $grouped[$parent][$child][] = $action;
            } else {
                if (!isset($grouped[$resource])) {
                    $grouped[$resource] = [];
                }
                $grouped[$resource][] = $action;
            }
        }

        $html = '<div class="permissions-container">';
        $html .= '<h5 class="permissions-header">Assigned Permissions For Role</h5>';
        $html .= '<div class="permissions-grid">';

        foreach ($grouped as $resource => $data) {
            $html .= '<div class="permission-category">';
            $html .= '<h6 class="category-title">' . ucfirst($resource) . '</h6>';

            if (is_array($data) && array_keys($data) !== range(0, count($data) - 1)) {
                // This is a parent category with children
                $html .= '<div class="subcategories">';
                foreach ($data as $subcategory => $actions) {
                    $html .= '<div class="subcategory">';
                    $html .= '<div class="subcategory-title">' . ucfirst($subcategory) . '</div>';
                    $html .= '<ul class="permission-list">';
                    foreach ($actions as $action) {
                        $html .= '<li class="permission-item">';
                        $html .= '<i class="bi bi-check-circle-fill text-success me-2"></i>';
                        $html .= ucfirst($action);
                        $html .= '</li>';
                    }
                    $html .= '</ul></div>';
                }
                $html .= '</div>';
            } else {
                // This is a regular category
                $html .= '<ul class="permission-list">';
                foreach ($data as $action) {
                    $html .= '<li class="permission-item">';
                    $html .= '<i class="bi bi-check-circle-fill text-success me-2"></i>';
                    $html .= ucfirst($action);
                    $html .= '</li>';
                }
                $html .= '</ul>';
            }

            $html .= '</div>';
        }

        $html .= '</div></div>';
        return response()->json(['html' => $html]);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,emp_id',
            'login_access' => 'required|boolean',

            'email_company' => [
                'required',
                'email',
                Rule::unique('employees', 'email_company')->where('delete_status', 1),
                Rule::unique('users', 'email')->where('delete_status', 1),
            ],
            'password_company' => 'required|string|min:8',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name'
        ]);
        // Add this after validation
        $validated['login_access'] = (int) ($validated['login_access'] ?? 0);
        return DB::transaction(function () use ($validated) {
            $employee = Employee::findOrFail($validated['employee_id']);

            // Update employee record
            $employee->update([
                'email_company' => $validated['email_company'],
                'password_company' => Hash::make($validated['password_company']),
                'login_access' => $validated['login_access']
            ]);

            // Create/update user account
            $this->syncUserAccount($employee, $validated);

            return redirect()->route('emplist')
                ->with('success', 'Company email created successfully');
        });
    }

    /**
     * Show form to edit email credentials
     */
    public function edit(Employee $employee)
    {
        $allRoles = Role::where('name', '!=', 'Super Admin')->pluck('name')->toArray();

        // Get current roles safely
        $currentRoles = $employee->user
            ? $employee->user->roles->pluck('name')->toArray()
            : [];

        return view('dashboard.hr.other.companyemail.edit', [
            'employee' => $employee,
            'allRoles' => $allRoles,
            'currentRoles' => $currentRoles
        ]);
    }

    /**
     * Update email credentials
     */
   public function update(Request $request, Employee $employee)
{
    $validated = $request->validate([
        'login_access' => 'nullable|boolean',
        'email_company' => [
            'nullable',
            'email',
            Rule::unique('employees', 'email_company')
                ->ignore($employee->emp_id, 'emp_id')
                ->where('delete_status', 1),
            Rule::unique('users', 'email')
                ->ignore($employee->user_id)
                ->where('delete_status', 1),
        ],
        'password_company' => 'nullable|string|min:8',
        'roles' => 'nullable|array',
        'roles.*' => 'exists:roles,name'
    ]);

    $validated['login_access'] = (int) ($validated['login_access'] ?? 0);

    return DB::transaction(function () use ($validated, $employee, $request) {
        try {
            // Initialize update arrays
            $employeeUpdates = [];
            $userUpdates = [];

            // Handle login access (optional)
            if (isset($validated['login_access'])) {
                $employeeUpdates['login_access'] = $validated['login_access'];
                $userUpdates['login_access'] = $validated['login_access'];
            }

            // Handle email (optional)
            if (isset($validated['email_company'])) {
                $employeeUpdates['email_company'] = $validated['email_company'];
                $userUpdates['email'] = $validated['email_company'];
            }

            // Handle password (optional)
            if (isset($validated['password_company'])) {
                $employeeUpdates['password_company'] = Hash::make($validated['password_company']);
                $userUpdates['password'] = Hash::make($validated['password_company']);
            }

            // Update employee
            if (!empty($employeeUpdates)) {
                $employee->update($employeeUpdates);
            }

            // Update user if exists
            if ($employee->user) {
                // Calculate categorie based on roles (same logic as in syncUserAccount)
                if (isset($validated['roles']) && count($validated['roles']) > 0) {
                    $categorie = 3; // Default Admin

                    if (in_array('Super Admin', $validated['roles'])) {
                        $categorie = 1;
                    } elseif (in_array('Employee', $validated['roles'])) {
                        $categorie = 2;
                    } elseif (in_array('Admin', $validated['roles'])) {
                        $categorie = 3;
                    }

                    $userUpdates['categorie'] = $categorie;
                } else {
                    // If no roles provided, keep existing categorie
                    $userUpdates['categorie'] = $employee->user->categorie;
                }

                if (!empty($userUpdates)) {
                    $employee->user->update($userUpdates);
                }

                // Sync roles if provided
                if (isset($validated['roles'])) {
                    $employee->user->syncRoles($validated['roles']);
                }
            } elseif (isset($validated['roles'])) {
                // If no user exists but roles were provided, create a user account
                $this->syncUserAccount($employee, array_merge($validated, [
                    'email_company' => $validated['email_company'] ?? $employee->email_company,
                    'password_company' => $validated['password_company'] ?? 'defaultpassword123',
                    'login_access' => $validated['login_access'] ?? 1
                ]));
            }

            return redirect()->route('emplist')
                ->with('success', 'Employee email credentials updated successfully');
        } catch (\Exception $e) {
            // Rollback transaction and return with error
            DB::rollBack();
            return back()->with('error', 'Failed to update: ' . $e->getMessage());
        }
    });
}
    /**
     * Sync user account with employee data
     */
    protected function syncUserAccount(Employee $employee, array $validated): User
    {
        // Default categorie
        $categorie = 3;

        if (isset($validated['roles']) && count($validated['roles']) > 0) {
            if (in_array('Super Admin', $validated['roles'])) {
                $categorie = 1;
            } elseif (in_array('Employee', $validated['roles'])) {
                $categorie = 2;
            } elseif (in_array('Admin', $validated['roles'])) {
                $categorie = 3;
            }
        }

        $userData = [
            'name' => $employee->fullname,
            'email' => $validated['email_company'] ?? $employee->email_company,
            'password' => $validated['password_company']
                ? Hash::make($validated['password_company'])
                : $employee->password_company,
            'login_access' => $validated['login_access'],
            'categorie' => $categorie,   // ✅ dynamic category
            'delete_status' => 1,
            'employeerole_id' => $employee->emp_id
        ];

        if ($employee->user) {
            $user = $employee->user;
            $user->update($userData);
        } else {
            $user = User::create($userData);
            $employee->user_id = $user->id;
            $employee->save();
        }

        // Sync roles
        if (isset($validated['roles'])) {
            $user->syncRoles($validated['roles']);
        } elseif (!$user->roles()->exists()) {
            $user->assignRole('Employee');
            $user->update(['categorie' => 2]); // default Employee category
        }

        return $user;
    }
}
