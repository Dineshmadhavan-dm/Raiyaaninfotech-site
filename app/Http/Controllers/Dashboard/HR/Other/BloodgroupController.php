<?php

namespace App\Http\Controllers\Dashboard\HR\Other;

use App\Http\Controllers\Controller;
use App\Models\BloodGroup;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BloodgroupController extends Controller
{
    public function bloodgroup()
    {
        $bloodgroup = BloodGroup::where('delete_status', 1)->get();
        return view('dashboard.hr.other.bloodgroup.list', compact('bloodgroup'));
    }

    public function bloodgrouppost(Request $request)
    {
        $request->validate([
            'bloodgroup_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('blood_groups')->where(function ($query) {
                    return $query->where('delete_status', 1);
                })
            ]
        ]);

        BloodGroup::create([
            'bloodgroup_name' => $request->bloodgroup_name,
            'delete_status' => 1
        ]);

        return redirect()->route('bloodgroup')->with('success', 'bloodgroup added successfully');
    }

    public function bloodgroupedit($bloodgroup_id)
    {
        $bloodgroup = BloodGroup::where('delete_status', 1)->get();
        $editbloodgroup = BloodGroup::findOrFail($bloodgroup_id);
        return view('dashboard.hr.other.bloodgroup.list', compact('dep', 'editbloodgroup'));
    }

    public function bloodgroupupdate(Request $request, $bloodgroup_id)
    {
        $request->validate([
            'bloodgroup_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('blood_groups')->where(function ($query) {
                    return $query->where('delete_status', 1);
                })->ignore($bloodgroup_id, 'bloodgroup_id')
            ]
        ]);

        $bloodgroup = BloodGroup::findOrFail($bloodgroup_id);
        $bloodgroup->update([
            'bloodgroup_name' => $request->bloodgroup_name
        ]);

        return redirect()->route('bloodgroup')->with('success', 'bloodgroup updated successfully');
    }

    public function bloodgroupdelete($bloodgroup_id)
    {
        $bloodgroup = BloodGroup::findOrFail($bloodgroup_id);
        $bloodgroup->update(['delete_status' => 0]);

        return redirect()->route('bloodgroup')->with('error', 'bloodgroup deleted successfully');
    }
}
