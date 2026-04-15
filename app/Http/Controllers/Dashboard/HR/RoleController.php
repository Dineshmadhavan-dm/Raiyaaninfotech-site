<?php

namespace App\Http\Controllers\Dashboard\HR;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Str;

class RoleController extends Controller
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

    // public  function __construct()
    // {
    //     $this->middleware('permission:hr->role view')->only(['index', 'show']);
    //     $this->middleware('permission:hr->role create')->only(['create', 'store']);
    //     $this->middleware('permission:hr->role edit')->only(['edit', 'update']);
    //     $this->middleware('permission:hr->role delete')->only(['destroy']);
    //     $this->middleware('permission:hr->managerole view')->only(['addPermission', 'givePermission']);
    // }


    public  function index()
    {
        $role = Role::where('name', '!=', 'Super admin')->get();
        return view('dashboard.hr.role-and-permission.role.index', compact('role'));
    }
    public  function create()
    {
        return view('dashboard.hr.role-and-permission.role.create');
    }
    public  function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|unique:roles,name'
        ]);

        Role::create([
            'name' => Str::ucfirst($request->name)
        ]);

        return  redirect()->back()->with('success', 'role  created successfully');
    }
    public  function edit($id)
    {
        $role = Role::findOrFail($id);
        return view('dashboard.hr.role-and-permission.role.edit', compact('role'));
    }
    public  function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id
        ]);

        $role->update([
            'name' => Str::ucfirst($request->name)
        ]);

        return  redirect()->back()->with('success', 'role  updated successfully');
    }
    public  function show($id)
    {
        $role = Role::findOrFail($id);

        return view('dashboard.hr.role-and-permission.role.show', compact('role'));
    }
    public  function destroy($id)
    {
        $role = Role::findOrFail($id);

        $role->delete();

        return redirect()->back()->with('error', 'role  deleted successfully');
    }




    // public function addpermission($id)
    // {
    //     $role = Role::findOrFail($id);
    //     $permissions = Permission::all();

    //     $rolepermissions = DB::table('role_has_permissions')
    //         ->where('role_has_permissions.role_id', $role->id)
    //         ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
    //         ->all();

    //     // Group permissions by resource with hierarchical structure
    //     $grouped = [];
    //     $hierarchyMap = []; // To track parent-child relationships

    //     foreach ($permissions as $permission) {
    //         $parts = explode(' ', $permission->name, 2); // Split into [resource, action]
    //         $resource = $parts[0] ?? 'other';
    //         $action = $parts[1] ?? '';

    //         // Check for hierarchical structure (e.g., "parent->child")
    //         if (str_contains($resource, '->')) {
    //             $hierarchy = explode('->', $resource);
    //             $parent = $hierarchy[0];
    //             $child = $hierarchy[1];

    //             // Track parent-child relationships
    //             if (!isset($hierarchyMap[$parent])) {
    //                 $hierarchyMap[$parent] = [];
    //             }
    //             if (!in_array($child, $hierarchyMap[$parent])) {
    //                 $hierarchyMap[$parent][] = $child;
    //             }

    //             // Group by full resource path
    //             if (!isset($grouped[$resource])) {
    //                 $grouped[$resource] = [];
    //             }
    //             $grouped[$resource][] = $permission;
    //         } else {
    //             // Regular permission
    //             if (!isset($grouped[$resource])) {
    //                 $grouped[$resource] = [];
    //             }
    //             $grouped[$resource][] = $permission;
    //         }
    //     }

    //     // Sort grouped permissions to show parent roles first, then their children
    //     uksort($grouped, function ($a, $b) use ($hierarchyMap) {
    //         $aIsChild = str_contains($a, '->');
    //         $bIsChild = str_contains($b, '->');

    //         // If both are children, sort by parent then child
    //         if ($aIsChild && $bIsChild) {
    //             $aParts = explode('->', $a);
    //             $bParts = explode('->', $b);

    //             // First compare parents
    //             $parentCompare = strcmp($aParts[0], $bParts[0]);
    //             if ($parentCompare !== 0) return $parentCompare;

    //             // Then compare children
    //             return strcmp($aParts[1], $bParts[1]);
    //         }

    //         // If one is child and one is parent
    //         if ($aIsChild && !$bIsChild) {
    //             $aParent = explode('->', $a)[0];
    //             if ($aParent === $b) return 1; // Child comes after parent
    //             return strcmp($aParent, $b);
    //         }
    //         if (!$aIsChild && $bIsChild) {
    //             $bParent = explode('->', $b)[0];
    //             if ($bParent === $a) return -1; // Parent comes before child
    //             return strcmp($a, $bParent);
    //         }

    //         // Both are parents
    //         return strcmp($a, $b);
    //     });

    //     return view('dashboard.hr.role-and-permission.role.addpermission', compact(
    //         'role',
    //         'permissions',
    //         'rolepermissions',
    //         'grouped',
    //         'hierarchyMap'
    //     ));
    // }


    public function addPermission($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();

        $rolePermissions = DB::table('role_has_permissions')
            ->where('role_id', $role->id)
            ->pluck('permission_id')
            ->toArray();

        // Group permissions hierarchically
        $grouped = [];
        $hierarchyMap = [];

        foreach ($permissions as $permission) {
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
                    'checked' => in_array($permission->id, $rolePermissions)
                ];
            } else {
                // Regular permission
                if (!isset($grouped[$resource])) {
                    $grouped[$resource] = [];
                }
                $grouped[$resource][] = [
                    'permission' => $permission,
                    'action' => $action,
                    'checked' => in_array($permission->id, $rolePermissions)
                ];
            }
        }

        // Sort grouped permissions hierarchically
        uksort($grouped, function ($a, $b) use ($hierarchyMap) {
            $aIsChild = str_contains($a, '->');
            $bIsChild = str_contains($b, '->');

            if ($aIsChild && $bIsChild) {
                $aParts = explode('->', $a);
                $bParts = explode('->', $b);

                // Compare parents first
                $parentCompare = strcmp($aParts[0], $bParts[0]);
                if ($parentCompare !== 0) return $parentCompare;

                // Then compare children
                return strcmp($aParts[1], $bParts[1]);
            }

            if ($aIsChild && !$bIsChild) {
                $aParent = explode('->', $a)[0];
                if ($aParent === $b) return 1; // Child after parent
                return strcmp($aParent, $b);
            }

            if (!$aIsChild && $bIsChild) {
                $bParent = explode('->', $b)[0];
                if ($bParent === $a) return -1; // Parent before child
                return strcmp($a, $bParent);
            }

            return strcmp($a, $b);
        });

        return view('dashboard.hr.role-and-permission.role.addpermission', compact(
            'role',
            'grouped',
            'hierarchyMap'
        ));
    }

    // Assign permissions to a role
    public function givePermission(Request $request, $id)
    {
        $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        $role = Role::findOrFail($id);
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->back()->with('success', 'Permissions updated successfully');
    }
}
