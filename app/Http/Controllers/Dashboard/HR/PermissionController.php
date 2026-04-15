<?php

namespace App\Http\Controllers\Dashboard\HR;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;


use Str;




class PermissionController extends Controller
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
    //     $this->middleware('permission:site->permission view')->only(['index', 'show']);
    //     $this->middleware('permission:site->permission create')->only(['create', 'store']);
    //     $this->middleware('permission:site->permission edit')->only(['edit', 'update']);
    //     $this->middleware('permission:site->permission delete')->only(['destroy']);
    // }


    public  function index()
    {
        $permission =  Permission::all();
        return view('dashboard.hr.role-and-permission.permission.index', compact('permission'));
    }
    public  function create()
    {
        return view('dashboard.hr.role-and-permission.permission.create');
    }
    public  function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|unique:permissions,name'
        ]);

        Permission::create([
            'name' => str::lower($request->name)
        ]);

        return redirect()->back()->with('success', 'permission  created successfully');
    }
    public  function edit($id)
    {
        $permission = Permission::findOrFail($id);
        return view('dashboard.hr.role-and-permission.permission.edit', compact('permission'));
    }
    public  function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);

        $request->validate([
            'name' => 'required|string|unique:permissions,name,' . $permission->id
        ]);

        $permission->update([
            'name' => str::lower($request->name)
        ]);

        return  redirect()->back()->with('success', 'permission  updated successfully');
    }
    public  function show($id)
    {
        $permission = Permission::findOrFail($id);

        return view('dashboard.hr.role-and-permission.permission.show', compact('permission'));
    }
    public  function destroy($id)
    {
        $permission = Permission::findOrFail($id);

        $permission->delete();

        return redirect()->back()->with('error', 'permission  deleted successfully');
    }
}
