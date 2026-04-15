<?php

namespace App\Http\Controllers\Dashboard\HR\Other;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DesignationController extends Controller
{

    //designation controller

    public function designation()
    {
        $des = Designation::where('delete_status', 1)->get();
        return view('dashboard.hr.other.designation.list', compact('des'));
    }

    public function designationpost(Request $request)
    {
        $request->validate([
            'des_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('designations')->where(function ($query) {
                    return $query->where('delete_status', 1);
                })
            ]
        ]);

        Designation::create([
            'des_name' => $request->des_name,
            'delete_status' => 1
        ]);

        return redirect()->route('designation')->with('success', 'Designation added successfully');
    }

    public function designationedit($des_id)
    {
        $des = Designation::where('delete_status', 1)->get();
        $editdesignation = Designation::findOrFail($des_id);
        return view('dashboard.hr.other.designation.list', compact('des', 'editdesignation'));
    }

    public function designationupdate(Request $request, $des_id)
    {
        $request->validate([
            'des_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('designations')->where(function ($query) {
                    return $query->where('delete_status', 1);
                })->ignore($des_id, 'des_id')
            ]
        ]);

        $designation = Designation::findOrFail($des_id);
        $designation->update([
            'des_name' => $request->des_name
        ]);

        return redirect()->route('designation')->with('success', 'Designation updated successfully');
    }

    public function designationdelete($des_id)
    {
        $designation = Designation::findOrFail($des_id);
        $designation->update(['delete_status' => 0]);

        return redirect()->route('designation')->with('error', 'Designation deleted successfully');
    }
}
