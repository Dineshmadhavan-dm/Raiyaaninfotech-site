<?php

namespace App\Http\Controllers\Dashboard\HR;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\InventoryHistory;
use Illuminate\Http\Request;

class InventoryHistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()->categorie == 2) {
                return redirect()->route('emphome')->with('error', 'Access denied');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        // ✅ Add 'department' to the with() array
        $query = InventoryHistory::with(['item', 'employee', 'assignment', 'maintenance', 'category']);

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('sub_action')) {
            $query->where('sub_action', $request->sub_action);
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('action_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('action_date', '<=', $request->date_to);
        }

        $perPage = $request->get('per_page', 1);

        $histories = $query->latest('action_date')
            ->paginate($perPage)
            ->withQueryString();

        $employees = Employee::where('delete_status', 1)
            ->whereDoesntHave('resignation')
            ->select('emp_id', 'fullname', 'employee_id')
            ->get();

        return view('dashboard.hr.inventory.history.index', compact('histories', 'employees'));
    }

    public function show($id)
    {
        // ✅ Add 'department' to the with() array
        $history = InventoryHistory::with([
            'item',
            'employee',
            'assignment',
            'maintenance',
            'category'
        ])->findOrFail($id);

        return response()->json([
            'status' => true,
            'history' => $history
        ]);
    }
}
