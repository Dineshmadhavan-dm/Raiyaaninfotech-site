<?php

namespace App\Http\Controllers\Dashboard\HR\Other;

use App\Http\Controllers\Controller;
use App\Models\Jobtype;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JobtypeController extends Controller
{
    public function jobtype()
    {
        $jobtype = Jobtype::where('delete_status', 1)->get();
        return view('dashboard.hr.other.jobtype.list', compact('jobtype'));
    }

    public function jobtypepost(Request $request)
    {
        $request->validate([
            'jobtype_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('jobtypes')->where(function ($query) {
                    return $query->where('delete_status', 1);
                })
            ]
        ]);

        Jobtype::create([
            'jobtype_name' => $request->jobtype_name,
            'delete_status' => 1
        ]);

        return redirect()->route('jobtype')->with('success', 'jobtype added successfully');
    }

    public function jobtypeedit($jobtype_id)
    {
        $jobtype = Jobtype::where('delete_status', 1)->get();
        $editjobtype = Jobtype::findOrFail($jobtype_id);
        return view('dashboard.hr.other.jobtype.list', compact('dep', 'editjobtype'));
    }

    public function jobtypeupdate(Request $request, $jobtype_id)
    {
        $request->validate([
            'jobtype_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('jobtypes')->where(function ($query) {
                    return $query->where('delete_status', 1);
                })->ignore($jobtype_id, 'jobtype_id')
            ]
        ]);

        $jobtype = Jobtype::findOrFail($jobtype_id);
        $jobtype->update([
            'jobtype_name' => $request->jobtype_name
        ]);

        return redirect()->route('jobtype')->with('success', 'jobtype updated successfully');
    }

    public function jobtypedelete($jobtype_id)
    {
        $jobtype = Jobtype::findOrFail($jobtype_id);
        $jobtype->update(['delete_status' => 0]);

        return redirect()->route('jobtype')->with('error', 'jobtype deleted successfully');
    }
}