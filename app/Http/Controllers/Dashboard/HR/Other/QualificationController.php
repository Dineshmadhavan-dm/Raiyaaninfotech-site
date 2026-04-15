<?php

namespace App\Http\Controllers\Dashboard\HR\Other;

use App\Http\Controllers\Controller;
use App\Models\Qualification;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class QualificationController extends Controller
{

    //qua


    public function qualification()
    {
        $qualification = Qualification::where('delete_status', 1)->get();
        return view('dashboard.hr.other.qualification.list', compact('qualification'));
    }

    public function qualificationpost(Request $request)
    {
        $request->validate([
            'qua_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('qualifications')->where(function ($query) {
                    return $query->where('delete_status', 1);
                })
            ]
        ]);

        Qualification::create([
            'qua_name' => $request->qua_name,
            'delete_status' => 1
        ]);

        return redirect()->route('qualification')->with('success', 'qualification added successfully');
    }

    public function qualificationedit($qua_id)
    {
        $qualification = Qualification::where('delete_status', 1)->get();
        $editqualification = Qualification::findOrFail($qua_id);
        return view('dashboard.hr.other.qualification.list', compact('qualification', 'editqualification'));
    }

    public function qualificationupdate(Request $request, $qua_id)
    {
        $request->validate([
            'qua_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('qualifications')->where(function ($query) {
                    return $query->where('delete_status', 1);
                })->ignore($qua_id, 'qua_id')
            ]
        ]);

        $qualification = Qualification::findOrFail($qua_id);
        $qualification->update([
            'qua_name' => $request->qua_name
        ]);

        return redirect()->route('qualification')->with('success', 'qualification updated successfully');
    }

    public function qualificationdelete($qua_id)
    {
        $qualification = Qualification::findOrFail($qua_id);
        $qualification->update(['delete_status' => 0]);

        return redirect()->route('qualification')->with('error', 'qualification deleted successfully');
    }
}
