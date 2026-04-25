<?php

namespace App\Http\Controllers\Dashboard\HR;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Models\InventoryAssignment;
use App\Models\InventoryMaintenance;
use App\Models\InventoryCategory;
use App\Models\Department;
use App\Models\Employee;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
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

    public function index()
    {
        $categories = InventoryCategory::where('delete_status', 1)->get();
        $departments = Department::where('delete_status', 1)->get();
        $employees = Employee::where('delete_status', 1)
            ->whereDoesntHave('resignation')
            ->select('emp_id', 'fullname', 'employee_id')
            ->get();
        $items = InventoryItem::where('delete_status', 1)
            ->select('id', 'item_name', 'item_code')
            ->get();

        return view('dashboard.hr.inventory.reports.index', compact('categories', 'departments', 'employees', 'items'));
    }

    public function exportItems(Request $request)
    {
        $query = InventoryItem::where('delete_status', 1)->with('category');

        if ($request->filled('category_ids')) {
            $categoryIds = $request->input('category_ids');
            if (is_array($categoryIds) && !empty($categoryIds)) {
                $query->whereIn('category_id', $categoryIds);
            }
        }

        if ($request->filled('item_type')) {
            $query->where('item_type', $request->item_type);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('purchase_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('purchase_date', '<=', $request->to_date);
        }

        $sortBy = $request->input('sort_by', 'item_name');
        $sortOrder = $request->input('sort_order', 'asc');
        $allowedSortFields = ['item_name', 'item_code', 'purchase_date', 'purchase_cost'];

        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('item_name', 'asc');
        }

        $items = $query->get();

        $totalItems = $items->count();
        $totalCost = $items->sum('purchase_cost');
        $totalQuantity = $items->sum('quantity');
        $newItemsCount = $items->where('item_type', 0)->count();
        $refurbishedItemsCount = $items->where('item_type', 1)->count();

        $categoriesSummary = [];
        foreach ($items as $item) {
            $categoryName = $item->category->category_name ?? 'Uncategorized';
            if (!isset($categoriesSummary[$categoryName])) {
                $categoriesSummary[$categoryName] = ['count' => 0, 'total_cost' => 0, 'total_quantity' => 0];
            }
            $categoriesSummary[$categoryName]['count']++;
            $categoriesSummary[$categoryName]['total_cost'] += $item->purchase_cost;
            $categoriesSummary[$categoryName]['total_quantity'] += $item->quantity;
        }

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

        $companyLogo = $this->getCompanyLogo();
        $companyName = $this->getCompanyName();

        $filterParts = [];
        if ($request->filled('category_ids')) {
            $cats = InventoryCategory::whereIn('id', $request->category_ids)->pluck('category_name')->toArray();
            $filterParts[] = 'Categories: ' . implode(', ', $cats);
        }
        if ($request->filled('item_type')) {
            $filterParts[] = 'Type: ' . ($request->item_type == 1 ? 'Refurbished' : 'New');
        }
        if ($request->filled('from_date') || $request->filled('to_date')) {
            $from = $request->from_date ?? 'Start';
            $to = $request->to_date ?? 'End';
            $filterParts[] = "Date: {$from} to {$to}";
        }
        $filterDescription = empty($filterParts) ? 'All Items' : implode(' | ', $filterParts);

        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $data = [
            'title' => 'Inventory Items Report',
            'filter_description' => $filterDescription,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'new_items_count' => $newItemsCount,
            'refurbished_items_count' => $refurbishedItemsCount,
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
            'report_type' => 'items',
        ];

        $pdf = Pdf::loadView('dashboard.hr.inventory.reports.export-pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions(['defaultFont' => 'DejaVu Sans', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        return $pdf->download('items_report_' . now()->format('Ymd_His') . '.pdf');
    }

    public function exportAssignments(Request $request)
    {
        $query = InventoryAssignment::with(['item', 'employee', 'department']);

        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->filled('condition_status') && $request->condition_status !== '') {
            $query->where('condition_status', $request->condition_status);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('employee_ids')) {
            $employeeIds = $request->input('employee_ids');
            if (is_array($employeeIds) && !empty($employeeIds)) {
                $query->whereIn('employee_id', $employeeIds);
            }
        }

        if ($request->filled('from_date')) {
            $query->whereDate('assigned_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('assigned_date', '<=', $request->to_date);
        }

        $sortBy = $request->input('sort_by', 'assigned_date');
        $sortOrder = $request->input('sort_order', 'desc');
        $allowedSortFields = ['assigned_date', 'return_date', 'status'];

        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('assigned_date', 'desc');
        }

        $assignments = $query->get();

        $totalAssignments = $assignments->count();
        $assignedCount = $assignments->where('status', 0)->count();
        $returnedCount = $assignments->where('status', 1)->count();
        $activeCount = $assignments->where('condition_status', 1)->count();
        $scrapCount = $assignments->where('condition_status', 0)->count();

        $departmentSummary = [];
        foreach ($assignments as $assignment) {
            $deptName = $assignment->department->dep_name ?? 'Unknown';
            if (!isset($departmentSummary[$deptName])) {
                $departmentSummary[$deptName] = ['total' => 0, 'assigned' => 0, 'returned' => 0];
            }
            $departmentSummary[$deptName]['total']++;
            if ($assignment->status == 0) {
                $departmentSummary[$deptName]['assigned']++;
            } else {
                $departmentSummary[$deptName]['returned']++;
            }
        }

        $filterParts = [];
        if ($request->filled('status') && $request->status !== '') {
            $filterParts[] = 'Status: ' . ($request->status == 0 ? 'Assigned' : 'Returned');
        }
        if ($request->filled('condition_status') && $request->condition_status !== '') {
            $filterParts[] = 'Condition: ' . ($request->condition_status == 1 ? 'Active' : 'Scrap');
        }
        if ($request->filled('department_id')) {
            $dept = Department::where('dep_id', $request->department_id)->first();
            $filterParts[] = 'Department: ' . ($dept ? $dept->dep_name : 'Unknown');
        }
        if ($request->filled('from_date') || $request->filled('to_date')) {
            $from = $request->from_date ?? 'Start';
            $to = $request->to_date ?? 'End';
            $filterParts[] = "Date: {$from} to {$to}";
        }
        $filterDescription = empty($filterParts) ? 'All Assignments' : implode(' | ', $filterParts);

        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $data = [
            'title' => 'Inventory Assignments Report',
            'filter_description' => $filterDescription,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'report_generated_date' => now()->format('d-m-Y H:i:s'),
            'assignments' => $assignments,
            'total_assignments' => $totalAssignments,
            'assigned_count' => $assignedCount,
            'returned_count' => $returnedCount,
            'active_count' => $activeCount,
            'scrap_count' => $scrapCount,
            'department_summary' => $departmentSummary,
            'company_logo' => $this->getCompanyLogo(),
            'company_name' => $this->getCompanyName(),
            'sort_by' => $sortBy,
            'sort_order' => $sortOrder,
            'report_type' => 'assignments',
        ];

        $pdf = Pdf::loadView('dashboard.hr.inventory.reports.export-pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions(['defaultFont' => 'DejaVu Sans', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        return $pdf->download('assignments_report_' . now()->format('Ymd_His') . '.pdf');
    }

    public function exportMaintenances(Request $request)
    {
        $query = InventoryMaintenance::with(['item', 'employee']);

        if ($request->filled('maintenance_type') && $request->maintenance_type !== '') {
            $query->where('maintenance_type', $request->maintenance_type);
        }

        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('start_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('start_date', '<=', $request->to_date);
        }

        $sortBy = $request->input('sort_by', 'start_date');
        $sortOrder = $request->input('sort_order', 'desc');
        $allowedSortFields = ['start_date', 'end_date', 'cost', 'status'];

        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('start_date', 'desc');
        }

        $maintenances = $query->get();

        $totalMaintenances = $maintenances->count();
        $totalCost = $maintenances->sum('cost');

        $typeSummary = [
            'scrap' => ['count' => 0, 'total_cost' => 0],
            'service' => ['count' => 0, 'total_cost' => 0],
            'upgrade' => ['count' => 0, 'total_cost' => 0],
        ];

        $statusSummary = ['pending' => 0, 'complete' => 0];

        foreach ($maintenances as $maintenance) {
            $type = match($maintenance->maintenance_type) {
                0 => 'scrap',
                1 => 'service',
                2 => 'upgrade',
                default => 'service'
            };
            $typeSummary[$type]['count']++;
            $typeSummary[$type]['total_cost'] += $maintenance->cost;

            if ($maintenance->status == 0) {
                $statusSummary['pending']++;
            } else {
                $statusSummary['complete']++;
            }
        }

        $filterParts = [];
        if ($request->filled('maintenance_type') && $request->maintenance_type !== '') {
            $types = ['Scrap', 'Service', 'Upgrade'];
            $filterParts[] = 'Type: ' . ($types[$request->maintenance_type] ?? 'Unknown');
        }
        if ($request->filled('status') && $request->status !== '') {
            $filterParts[] = 'Status: ' . ($request->status == 0 ? 'Pending' : 'Complete');
        }
        if ($request->filled('from_date') || $request->filled('to_date')) {
            $from = $request->from_date ?? 'Start';
            $to = $request->to_date ?? 'End';
            $filterParts[] = "Date: {$from} to {$to}";
        }
        $filterDescription = empty($filterParts) ? 'All Maintenance Records' : implode(' | ', $filterParts);

        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $data = [
            'title' => 'Inventory Maintenance Report',
            'filter_description' => $filterDescription,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'report_generated_date' => now()->format('d-m-Y H:i:s'),
            'maintenances' => $maintenances,
            'total_maintenances' => $totalMaintenances,
            'total_cost' => $totalCost,
            'type_summary' => $typeSummary,
            'status_summary' => $statusSummary,
            'company_logo' => $this->getCompanyLogo(),
            'company_name' => $this->getCompanyName(),
            'sort_by' => $sortBy,
            'sort_order' => $sortOrder,
            'report_type' => 'maintenances',
        ];

        $pdf = Pdf::loadView('dashboard.hr.inventory.reports.export-pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions(['defaultFont' => 'DejaVu Sans', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        return $pdf->download('maintenance_report_' . now()->format('Ymd_His') . '.pdf');
    }

    public function exportCategories(Request $request)
    {
        $query = InventoryCategory::where('delete_status', 1)->with('items');

        if ($request->filled('category_ids')) {
            $categoryIds = $request->input('category_ids');
            if (is_array($categoryIds) && !empty($categoryIds)) {
                $query->whereIn('id', $categoryIds);
            }
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $sortBy = $request->input('sort_by', 'category_name');
        $sortOrder = $request->input('sort_order', 'asc');

        if ($sortBy == 'items_count') {
            $query->withCount('items')->orderBy('items_count', $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        $categories = $query->get();

        $totalCategories = $categories->count();
        $totalItems = $categories->sum(function ($cat) {
            return $cat->items->count();
        });
        $totalCost = $categories->sum(function ($cat) {
            return $cat->items->sum('purchase_cost');
        });

        $filterParts = [];
        if ($request->filled('category_ids')) {
            $cats = InventoryCategory::whereIn('id', $request->category_ids)->pluck('category_name')->toArray();
            $filterParts[] = 'Categories: ' . implode(', ', $cats);
        }
        if ($request->filled('from_date') || $request->filled('to_date')) {
            $from = $request->from_date ?? 'Start';
            $to = $request->to_date ?? 'End';
            $filterParts[] = "Date: {$from} to {$to}";
        }
        $filterDescription = empty($filterParts) ? 'All Categories' : implode(' | ', $filterParts);

        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $data = [
            'title' => 'Inventory Categories Report',
            'filter_description' => $filterDescription,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'report_generated_date' => now()->format('d-m-Y H:i:s'),
            'categories' => $categories,
            'total_categories' => $totalCategories,
            'total_items' => $totalItems,
            'total_cost' => $totalCost,
            'company_logo' => $this->getCompanyLogo(),
            'company_name' => $this->getCompanyName(),
            'sort_by' => $sortBy,
            'sort_order' => $sortOrder,
            'report_type' => 'categories',
        ];

        $pdf = Pdf::loadView('dashboard.hr.inventory.reports.export-pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions(['defaultFont' => 'DejaVu Sans', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        return $pdf->download('categories_report_' . now()->format('Ymd_His') . '.pdf');
    }

    private function getCompanyLogo()
    {
        $settings = \App\Models\Setting::first();
        if ($settings && $settings->weblogo) {
            $logoPath = public_path('weblogo/' . $settings->weblogo);
            if (file_exists($logoPath)) {
                $logoData = file_get_contents($logoPath);
                $mimeType = mime_content_type($logoPath);
                return 'data:' . $mimeType . ';base64,' . base64_encode($logoData);
            }
        }
        return null;
    }

    private function getCompanyName()
    {
        $settings = \App\Models\Setting::first();
        return $settings && $settings->webname ? $settings->webname : 'RAIYAAN INFOTECH';
    }
}
