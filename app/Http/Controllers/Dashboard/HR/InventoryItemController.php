<?php

namespace App\Http\Controllers\Dashboard\HR;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Models\InventoryCategory;
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


public function exportPdf(Request $request)
{
    $query = InventoryItem::where('delete_status', 1)
        ->with('category');

    // Filter by export type
    $exportType = $request->input('export_type', 'all');

    if ($exportType === 'category' && $request->has('category_ids')) {
        $categoryIds = $request->input('category_ids');
        if (is_array($categoryIds) && !empty($categoryIds)) {
            $query->whereIn('category_id', $categoryIds);
        }
    }

    if ($exportType === 'item_type' && $request->has('item_type')) {
        $itemType = $request->input('item_type');
        if (!empty($itemType)) {
            $query->where('item_type', $itemType);
        }
    }

    // Sort by
    $sortBy = $request->input('sort_by', 'item_name');
    $sortOrder = $request->input('sort_order', 'asc');

    $allowedSortFields = ['item_name', 'item_code', 'purchase_date', 'purchase_cost'];
    if (in_array($sortBy, $allowedSortFields)) {
        $query->orderBy($sortBy, $sortOrder);
    } elseif ($sortBy === 'category') {
        $query->join('inventory_categories', 'inventory_items.category_id', '=', 'inventory_categories.id')
              ->orderBy('inventory_categories.category_name', $sortOrder)
              ->select('inventory_items.*');
    } else {
        $query->orderBy('item_name', 'asc');
    }

    $items = $query->get();

    // Calculate totals
    $totalItems = $items->count();
    $totalCost = $items->sum('purchase_cost');
    $totalQuantity = $items->sum('quantity');

    // Group by category for summary
    $categoriesSummary = [];
    foreach ($items as $item) {
        $categoryName = $item->category->category_name ?? 'Uncategorized';
        if (!isset($categoriesSummary[$categoryName])) {
            $categoriesSummary[$categoryName] = [
                'count' => 0,
                'total_cost' => 0,
                'total_quantity' => 0
            ];
        }
        $categoriesSummary[$categoryName]['count']++;
        $categoriesSummary[$categoryName]['total_cost'] += $item->purchase_cost;
        $categoriesSummary[$categoryName]['total_quantity'] += $item->quantity;
    }

    // Group by item type
   $itemTypeSummary = [
    'new' => ['count' => 0, 'total_cost' => 0, 'total_quantity' => 0],
    'refurbished' => ['count' => 0, 'total_cost' => 0, 'total_quantity' => 0],
];
   foreach ($items as $item) {

    $type = $item->item_type == 1 ? 'refurbished' : 'new';

    $itemTypeSummary[$type]['count']++;
    $itemTypeSummary[$type]['total_cost'] += $item->purchase_cost;
    $itemTypeSummary[$type]['total_quantity'] += $item->quantity;
}

    // Get company details
    $companyLogo = null;
    $companyName = 'RAIYAAN INFOTECH';
    $settings = \App\Models\Setting::first();

    if ($settings) {
        if ($settings->webname) {
            $companyName = $settings->webname;
        }
        if ($settings->weblogo) {
            $logoPath = public_path('weblogo/' . $settings->weblogo);
            if (file_exists($logoPath)) {
                $logoData = file_get_contents($logoPath);
                $mimeType = mime_content_type($logoPath);
                $companyLogo = 'data:' . $mimeType . ';base64,' . base64_encode($logoData);
            }
        }
    }

    // Prepare filter description
    $filterDescription = 'All Items';
    if ($exportType === 'category' && $request->has('category_ids')) {
        $categoryNames = \App\Models\InventoryCategory::whereIn('id', $request->input('category_ids'))
            ->pluck('category_name')
            ->toArray();
        $filterDescription = 'Categories: ' . implode(', ', $categoryNames);
    } elseif ($exportType === 'item_type' && $request->has('item_type')) {
       $filterDescription = 'Item Type: ' .
    ($request->item_type == 1 ? 'Refurbished' : 'New');
    }

    $data = [
        'title' => 'Inventory Report',
        'filter_description' => $filterDescription,
        'report_generated_date' => now()->format('d-m-Y H:i:s'),
        'items' => $items,
        'total_items' => $totalItems,
        'total_cost' => $totalCost,
        'total_quantity' => $totalQuantity,
        'categories_summary' => $categoriesSummary,
        'item_type_summary' => $itemTypeSummary,
        'company_logo' => $companyLogo,
        'company_name' => $companyName,
        'sort_by' => $sortBy,
        'sort_order' => $sortOrder,
    ];

    $pdf = Pdf::loadView('dashboard.hr.inventory.item.export-pdf', $data);
    $pdf->setPaper('A4', 'landscape');

    $pdf->setOptions([
        'defaultFont' => 'DejaVu Sans',
        'isHtml5ParserEnabled' => true,
        'isRemoteEnabled' => true,
    ]);

    return $pdf->download('inventory_report_' . now()->format('Ymd_His') . '.pdf');
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
        $item->item_type = $request->item_type ?? 0;

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
