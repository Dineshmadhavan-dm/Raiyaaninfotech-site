<?php

namespace App\Http\Controllers\Dashboard\HR\Other;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BranchController extends Controller
{


    //branch controller



    public function branch()
    {
        $branch = Branch::where('delete_status', 1)->get();
        return view('dashboard.hr.other.branch.list', compact('branch'));
    }

    public function branchpost(Request $request)
    {
        $request->validate([
            'branch_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('branches')->where(function ($query) {
                    return $query->where('delete_status', 1);
                })
            ]
        ]);

        Branch::create([
            'branch_name' => $request->branch_name,
            'delete_status' => 1
        ]);

        return redirect()->route('branch')->with('success', 'Branch added successfully');
    }

    public function branchedit($branch_id)
    {
        $branch = Branch::where('delete_status', 1)->get();
        $editbranch = Branch::findOrFail($branch_id);
        return view('dashboard.hr.other.branch.list', compact('branch', 'editbranch'));
    }

    public function branchupdate(Request $request, $branch_id)
    {
        $request->validate([
            'branch_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('branches')->where(function ($query) {
                    return $query->where('delete_status', 1);
                })->ignore($branch_id, 'branch_id')
            ]
        ]);

        $branch = Branch::findOrFail($branch_id);
        $branch->update([
            'branch_name' => $request->branch_name
        ]);

        return redirect()->route('branch')->with('success', 'Branch updated successfully');
    }

    public function branchdelete($branch_id)
    {
        $branch = Branch::findOrFail($branch_id);
        $branch->update(['delete_status' => 0]);

        return redirect()->route('branch')->with('error', 'Branch deleted successfully');
    }
}
