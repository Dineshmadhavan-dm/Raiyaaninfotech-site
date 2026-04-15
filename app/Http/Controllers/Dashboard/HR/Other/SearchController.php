<?php

namespace App\Http\Controllers\Dashboard\HR\Other;

use App\Http\Controllers\Controller;
use App\Models\BloodGroup;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Holidaytype;
use App\Models\Jobtype;
use App\Models\Qualification;
use App\Models\Relationship;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class SearchController extends Controller
{
    public function searchloc(Request $request)
    {
        $search = $request->get('search');

        $curlocation = Branch::where('branch_name', 'like', '%' . $search . '%')->where('delete_status', 1)->get([
            'branch_id',
            'branch_name'
        ]);


        return response()->json($curlocation);
    }


    public function searchrole(Request $request)
    {
        $search = $request->get('search');

        $currole = Role::where('name', '!=', 'Super admin')->where('name', 'like', '%' . $search . '%')->get();




        return response()->json($currole);
    }


    public function searchdesignation(Request $request)
    {
        $search = $request->get('search');

        $curdesignation = Designation::where('des_name', 'like', '%' . $search . '%')->where('delete_status', 1)->get([
            'des_id',
            'des_name'
        ]);

        return response()->json($curdesignation);
    }

    public function searchdepartment(Request $request)
    {
        $search = $request->get('search');

        $curdepartment = Department::where('dep_name', 'like', '%' . $search . '%')
            ->where('delete_status', 1)
            ->get([
                'dep_id',
                'dep_name'
            ]);

        return response()->json($curdepartment);
    }
    // In your controller
    public function searchbloodgroup(Request $request)
    {
        $search = $request->input('search');

        $bloodgroups = BloodGroup::where('bloodgroup_name', 'like', '%' . $search . '%')
            ->where('delete_status', 1)
            ->get(['bloodgroup_id', 'bloodgroup_name']);

        return response()->json($bloodgroups);
    }
    public function searchrelationship(Request $request)
    {
        $search = $request->input('search');

        $relationships = Relationship::where('relationship_name', 'like', '%' . $search . '%')
            ->where('delete_status', 1)
            ->get(['relationship_id', 'relationship_name']);

        return response()->json($relationships);
    }
    public function searchjobtype(Request $request)
    {
        $search = $request->input('search');

        $jobtypes = Jobtype::where('jobtype_name', 'like', '%' . $search . '%')
            ->where('delete_status', 1)
            ->get(['jobtype_id', 'jobtype_name']);

        return response()->json($jobtypes);
    }
    public function searchqualification(Request $request)
    {
        $search = $request->get('search');

        $qualification = Qualification::where('qua_name', 'like', '%' . $search . '%')->where('delete_status', 1)->get(['qua_id', 'qua_name']);

        return response()->json($qualification);
    }




    public function searchholidaytype(Request $request)
    {
        $search = $request->input('search');

        $holidaytypes = Holidaytype::where('holidaytype_name', 'like', '%' . $search . '%')
            ->where('delete_status', 1)
            ->get(['holidaytype_id', 'holidaytype_name']);

        return response()->json($holidaytypes);
    }
}