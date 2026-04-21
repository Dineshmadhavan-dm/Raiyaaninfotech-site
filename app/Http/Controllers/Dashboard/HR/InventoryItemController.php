<?php

namespace App\Http\Controllers\Dashboard\HR;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Models\InventoryCategory;
use Illuminate\Http\Request;

class InventoryItemController extends Controller
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
    $query = InventoryItem::where('delete_status', 1);

    // 🔍 FILTERS
    if ($request->filled('item_name')) {
        $query->where('item_name', 'like', '%' . $request->item_name . '%');
    }

    if ($request->filled('item_code')) {
        $query->where('item_code', 'like', '%' . $request->item_code . '%');
    }

    if ($request->filled('item_type')) {
        $query->where('item_type', $request->item_type);
    }

    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id);
    }

   $perPage = $request->get('per_page', 5);

$items = $query->latest()->paginate($perPage)->withQueryString();

    // categories for filter dropdown
    $categories = InventoryCategory::where('delete_status', 1)->get();

    return view('dashboard.hr.inventory.item.index', compact('items','categories'));
}

    // ✅ CREATE FORM
    public function create()
    {
        $categories = InventoryCategory::where('delete_status', 1)->get();

        $lastItem = InventoryItem::latest()->first();
$lastId = $lastItem ? $lastItem->id : 0;


        return view('dashboard.hr.inventory.item.create', compact('categories','lastId'));
    }

    // ✅ STORE
    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required',
            'item_code' => 'required|unique:inventory_items,item_code',
            'category_id' => 'required',
        ]);

        $item = new InventoryItem();
        $item->fill($request->all());

        // IMAGE
        if ($request->filled('item_image') && is_string($request->item_image)) {
            $this->processBase64Image($request->item_image, $item);
        }

        // DOCUMENT
        if ($request->hasFile('document_file')) {
            $file = $request->file('document_file');
            $filename = 'doc_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('inventory_docs'), $filename);
            $item->document_file = $filename;
        }

        $item->save();

        return response()->json([
            'status' => true,
            'message' => 'Inventory item created successfully'
        ]);
    }

    // ✅ SHOW
    public function show($id)
    {
        $item = InventoryItem::findOrFail($id);
        return response()->json([
            'status' => true,
            'item' => $item
        ]);
    }

    // ✅ EDIT FORM
    public function edit($id)
    {
        $item = InventoryItem::findOrFail($id);
        $categories = InventoryCategory::where('delete_status', 1)->get();

        return view('dashboard.hr.inventory.item.edit', compact('item', 'categories'));
    }

    // ✅ UPDATE
    public function update(Request $request, $id)
{
    $item = InventoryItem::findOrFail($id);

    $request->validate([
        'item_name' => 'required',
        'item_code' => 'required|unique:inventory_items,item_code,' . $id,
    ]);

    $data = $request->except('item_image');
    $item->fill($data);

    if ($request->filled('item_image') && is_string($request->item_image)) {
        $this->processBase64Image($request->item_image, $item);
    }

    if ($request->hasFile('document_file')) {
        $file = $request->file('document_file');
        $filename = 'doc_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('inventory_docs'), $filename);
        $item->document_file = $filename;
    }

    $item->save();

    return response()->json([
        'status' => true,
        'message' => 'Inventory item updated successfully'
    ]);
}

    // ✅ SOFT DELETE
    public function destroy($id)
    {
        $item = InventoryItem::findOrFail($id);
        $item->update(['delete_status' => 0]);

        return response()->json([
            'status' => true,
            'message' => 'Item deleted successfully'
        ]);
    }

    // ✅ IMAGE PROCESS (BASE64)
    private function processBase64Image($imageData, $item)
    {
        if (strpos($imageData, 'data:image') === 0) {

            $parts = explode(',', $imageData);
            $mime = explode(';', explode(':', $parts[0])[1])[0];

            $extension = match ($mime) {
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                default => 'jpg'
            };

            $image = base64_decode($parts[1]);
            $imageName = 'item_' . time() . '_' . uniqid() . '.' . $extension;

            $folder = public_path('inventory_images');

            if (!file_exists($folder)) {
                mkdir($folder, 0777, true);
            }

            file_put_contents($folder . '/' . $imageName, $image);

            // delete old image
            if ($item->item_image && file_exists($folder . '/' . $item->item_image)) {
                unlink($folder . '/' . $item->item_image);
            }

            $item->item_image = $imageName;
        }
    }
}
