<?php

namespace App\Http\Controllers\Dashboard\HR;

use App\Http\Controllers\Controller;
use App\Models\InventoryCategory;
use App\Models\InventoryHistory;
use Illuminate\Http\Request;

class InventoryCategoryController extends Controller
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

    public function checkCategory(Request $request)
    {
        $query = InventoryCategory::whereRaw(
            'LOWER(category_name) = ?',
            [strtolower($request->category_name)]
        )->where('delete_status', 1);

        if ($request->id) {
            $query->where('id', '!=', $request->id);
        }

        $exists = $query->exists();

        return response()->json(['exists' => $exists]);
    }

    public function index(Request $request)
    {
        $query = InventoryCategory::where('delete_status', 1);

        if ($request->filled('category_name')) {
            $query->where('category_name', 'like', '%' . $request->category_name . '%');
        }

        $perPage = $request->get('per_page', 5);

        $categories = $query->latest()->paginate($perPage)->withQueryString();

        return view('dashboard.hr.inventory.category.index', compact('categories'));
    }

    public function create()
    {
        return view('dashboard.hr.inventory.category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => [
                'required',
                function ($attr, $value, $fail) {
                    $exists = InventoryCategory::whereRaw(
                        'LOWER(category_name) = ?',
                        [strtolower($value)]
                    )
                    ->where('delete_status', 1)
                    ->exists();

                    if ($exists) {
                        $fail('Category already exists');
                    }
                }
            ],
        ]);

        $category = InventoryCategory::create([
            'category_name' => $request->category_name,
            'description' => $request->description,
            'delete_status' => 1
        ]);

        InventoryHistory::log([
            'module' => InventoryHistory::MODULE_CATEGORY,
            'action' => InventoryHistory::ACTION_CREATED,
            'category_id' => $category->id,
            'new_data' => $category->toArray(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Category created successfully'
        ]);
    }

    public function edit($id)
    {
        $category = InventoryCategory::findOrFail($id);
        return view('dashboard.hr.inventory.category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = InventoryCategory::findOrFail($id);

        $request->validate([
            'category_name' => [
                'required',
                function ($attr, $value, $fail) use ($id) {
                    $exists = InventoryCategory::whereRaw(
                        'LOWER(category_name) = ?',
                        [strtolower($value)]
                    )
                    ->where('delete_status', 1)
                    ->where('id', '!=', $id)
                    ->exists();

                    if ($exists) {
                        $fail('Category already exists');
                    }
                }
            ],
        ]);

        $oldData = $category->getOriginal();

        $category->update([
            'category_name' => $request->category_name,
            'description' => $request->description,
        ]);

        InventoryHistory::log([
            'module' => InventoryHistory::MODULE_CATEGORY,
            'action' => InventoryHistory::ACTION_UPDATED,
            'category_id' => $category->id,
            'old_data' => $oldData,
            'new_data' => $category->fresh()->toArray(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Category updated successfully'
        ]);
    }

    public function destroy($id)
    {
        $category = InventoryCategory::findOrFail($id);

        $oldData = $category->toArray();

        $category->update(['delete_status' => 0]);

        InventoryHistory::log([
            'module' => InventoryHistory::MODULE_CATEGORY,
            'action' => InventoryHistory::ACTION_DELETED,
            'category_id' => $category->id,
            'old_data' => $oldData,
            'new_data' => ['delete_status' => 0],
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Category deleted successfully'
        ]);
    }
}
