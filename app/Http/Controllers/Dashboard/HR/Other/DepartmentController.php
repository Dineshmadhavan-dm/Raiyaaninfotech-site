<?php

namespace App\Http\Controllers\Dashboard\HR\Other;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{

    //department controller

    public function department()
    {
        $dep = Department::where('delete_status', 1)->get();
        return view('dashboard.hr.other.department.list', compact('dep'));
    }

    public function departmentpost(Request $request)
    {
        $request->validate([
            'dep_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('departments')->where(function ($query) {
                    return $query->where('delete_status', 1);
                })
            ]
        ]);

        Department::create([
            'dep_name' => $request->dep_name,
            'delete_status' => 1
        ]);

        return redirect()->route('department')->with('success', 'Department added successfully');
    }

    public function departmentedit($dep_id)
    {
        $dep = Department::where('delete_status', 1)->get();
        $editDepartment = Department::findOrFail($dep_id);
        return view('dashboard.hr.other.department.list', compact('dep', 'editDepartment'));
    }

    public function departmentupdate(Request $request, $dep_id)
    {
        $request->validate([
            'dep_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('departments')->where(function ($query) {
                    return $query->where('delete_status', 1);
                })->ignore($dep_id, 'dep_id')
            ]
        ]);

        $department = Department::findOrFail($dep_id);
        $department->update([
            'dep_name' => $request->dep_name
        ]);

        return redirect()->route('department')->with('success', 'Department updated successfully');
    }

    public function departmentdelete($dep_id)
    {
        $department = Department::findOrFail($dep_id);
        $department->update(['delete_status' => 0]);

        return redirect()->route('department')->with('error', 'Department deleted successfully');
    }
}
