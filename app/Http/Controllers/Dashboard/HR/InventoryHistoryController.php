<?php

namespace App\Http\Controllers\Dashboard\HR;

use App\Http\Controllers\Controller;
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

    // ✅ LIST (VIEW ONLY)

    public function index(Request $request)
{
    $query = InventoryHistory::with('item');

    // 🔍 FILTER ITEM
    if ($request->filled('item')) {
        $query->whereHas('item', function($q) use ($request){
            $q->where('item_name', 'like', '%' . $request->item . '%');
        });
    }

    // 🔍 FILTER ACTION
    if ($request->filled('action')) {
        $query->where('action_type', $request->action);
    }

    // 🔍 FILTER STATUS
    if ($request->filled('status')) {
        $query->where('new_status', $request->status);
    }

    $perPage = $request->get('per_page', 5);

    $histories = $query->latest()
        ->paginate($perPage)
        ->withQueryString();

    return view('dashboard.hr.inventory.history.index', compact('histories'));
}

    // ✅ SHOW DETAILS
    public function show($id)
    {
        $history = InventoryHistory::with(['item'])->findOrFail($id);

        return response()->json([
            'status' => true,
            'history' => $history
        ]);
    }
}
