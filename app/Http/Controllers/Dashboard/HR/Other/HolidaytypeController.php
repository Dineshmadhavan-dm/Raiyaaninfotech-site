<?php

namespace App\Http\Controllers\Dashboard\HR\Other;

use App\Http\Controllers\Controller;
use App\Models\Holidaytype;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HolidaytypeController extends Controller
{
    public function holidaytype()
    {
        $holidaytype = Holidaytype::where('delete_status', 1)->get();
        return view('dashboard.hr.other.holidaytype.list', compact('holidaytype'));
    }

    public function holidaytypepost(Request $request)
    {
        $request->validate([
            'holidaytype_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('holidaytypes')->where(function ($query) {
                    return $query->where('delete_status', 1);
                })
            ]
        ]);

        Holidaytype::create([
            'holidaytype_name' => $request->holidaytype_name,
            'delete_status' => 1
        ]);

        return redirect()->route('holidaytype')->with('success', 'holidaytype added successfully');
    }

    public function holidaytypeedit($holidaytype_id)
    {
        $holidaytype = Holidaytype::where('delete_status', 1)->get();
        $editholidaytype = Holidaytype::findOrFail($holidaytype_id);
        return view('dashboard.hr.other.holidaytype.list', compact('holidaytype', 'editholidaytype'));
    }

    public function holidaytypeupdate(Request $request, $holidaytype_id)
    {
        $request->validate([
            'holidaytype_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('holidaytypes')->where(function ($query) {
                    return $query->where('delete_status', 1);
                })->ignore($holidaytype_id, 'holidaytype_id')
            ]
        ]);

        $holidaytype = Holidaytype::findOrFail($holidaytype_id);
        $holidaytype->update([
            'holidaytype_name' => $request->holidaytype_name
        ]);

        return redirect()->route('holidaytype')->with('success', 'holidaytype updated successfully');
    }

    public function holidaytypedelete($holidaytype_id)
    {
        $holidaytype = Holidaytype::findOrFail($holidaytype_id);
        $holidaytype->update(['delete_status' => 0]);

        return redirect()->route('holidaytype')->with('error', 'holidaytype deleted successfully');
    }
}