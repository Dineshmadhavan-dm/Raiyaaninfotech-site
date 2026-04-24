<?php

namespace App\Http\Controllers\Dashboard\HR;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Models\InventoryCategory;
use App\Models\InventoryHistory;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

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

        $categories = InventoryCategory::where('delete_status', 1)->get();

        return view('dashboard.hr.inventory.item.index', compact('items', 'categories'));
    }

  public function create()
{
    $categories = InventoryCategory::where('delete_status', 1)->get();

    $lastItem = InventoryItem::orderBy('id', 'desc')->first();

    $lastNumber = 0;

    if ($lastItem && $lastItem->item_code) {
        $parts = explode('-', $lastItem->item_code);
        $lastNumber = isset($parts[2]) ? (int)$parts[2] : 0;
    }

    return view('dashboard.hr.inventory.item.create', compact('categories', 'lastNumber'));
}

   public function store(Request $request)
{
    $request->validate([
        'item_name' => 'required',
        'category_id' => 'required',
    ]);

    $item = new InventoryItem();

    $data = $request->except('item_image');
    $data['item_type'] = $request->item_type ?? 0;
    $data['delete_status'] = 1;

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

    $lastItem = InventoryItem::where('item_code', 'like', 'ITM-' . date('Y') . '-%')
        ->orderBy('id', 'desc')
        ->first();

    $lastNumber = 0;

    if ($lastItem && $lastItem->item_code) {
        $parts = explode('-', $lastItem->item_code);
        $lastNumber = isset($parts[2]) ? (int) $parts[2] : 0;
    }

    $nextNumber = $lastNumber + 1;

    $year = date('Y');

    $item->item_code = 'ITM-' . $year . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

    $item->save();

    InventoryHistory::log([
        'module' => InventoryHistory::MODULE_ITEM,
        'action' => InventoryHistory::ACTION_CREATED,
        'item_id' => $item->id,
        'new_data' => $item->toArray(),
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Inventory item created successfully'
    ]);
}
    public function show($id)
    {
        $item = InventoryItem::findOrFail($id);

        return response()->json([
            'status' => true,
            'item' => $item
        ]);
    }

    public function edit($id)
    {
        $item = InventoryItem::findOrFail($id);
        $categories = InventoryCategory::where('delete_status', 1)->get();

        return view('dashboard.hr.inventory.item.edit', compact('item', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $item = InventoryItem::findOrFail($id);

        $request->validate([
            'item_name' => 'required',
            'item_code' => 'required|unique:inventory_items,item_code,' . $id,
        ]);

        $oldData = $item->getOriginal();

        $data = $request->except('item_image');
        $data['item_type'] = $request->item_type ?? 0;

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

        InventoryHistory::log([
            'module' => InventoryHistory::MODULE_ITEM,
            'action' => InventoryHistory::ACTION_UPDATED,
            'item_id' => $item->id,
            'old_data' => $oldData,
            'new_data' => $item->fresh()->toArray(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Inventory item updated successfully'
        ]);
    }

    public function destroy($id)
    {
        $item = InventoryItem::findOrFail($id);

        $oldData = $item->toArray();

        $item->update(['delete_status' => 0]);

        InventoryHistory::log([
            'module' => InventoryHistory::MODULE_ITEM,
            'action' => InventoryHistory::ACTION_DELETED,
            'item_id' => $item->id,
            'old_data' => $oldData,
            'new_data' => ['delete_status' => 0],
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Item deleted successfully'
        ]);
    }

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

            if ($item->item_image && file_exists($folder . '/' . $item->item_image)) {
                unlink($folder . '/' . $item->item_image);
            }

            $item->item_image = $imageName;
        }
    }
}
