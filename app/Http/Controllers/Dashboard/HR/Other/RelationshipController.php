<?php

namespace App\Http\Controllers\Dashboard\HR\Other;

use App\Http\Controllers\Controller;
use App\Models\Relationship;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RelationshipController extends Controller
{
    public function relationship()
    {
        $relationship = Relationship::where('delete_status', 1)->get();
        return view('dashboard.hr.other.relationship.list', compact('relationship'));
    }

    public function relationshippost(Request $request)
    {
        $request->validate([
            'relationship_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('blood_groups')->where(function ($query) {
                    return $query->where('delete_status', 1);
                })
            ]
        ]);

        Relationship::create([
            'relationship_name' => $request->relationship_name,
            'delete_status' => 1
        ]);

        return redirect()->route('relationship')->with('success', 'relationship added successfully');
    }

    public function relationshipedit($relationship_id)
    {
        $relationship = Relationship::where('delete_status', 1)->get();
        $editrelationship = Relationship::findOrFail($relationship_id);
        return view('dashboard.hr.other.relationship.list', compact('relationship', 'editrelationship'));
    }

    public function relationshipupdate(Request $request, $relationship_id)
    {
        $request->validate([
            'relationship_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('blood_groups')->where(function ($query) {
                    return $query->where('delete_status', 1);
                })->ignore($relationship_id, 'relationship_id')
            ]
        ]);

        $relationship = Relationship::findOrFail($relationship_id);
        $relationship->update([
            'relationship_name' => $request->relationship_name
        ]);

        return redirect()->route('relationship')->with('success', 'relationship updated successfully');
    }

    public function relationshipdelete($relationship_id)
    {
        $relationship = Relationship::findOrFail($relationship_id);
        $relationship->update(['delete_status' => 0]);

        return redirect()->route('relationship')->with('error', 'relationship deleted successfully');
    }
}
