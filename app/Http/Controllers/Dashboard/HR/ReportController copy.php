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
        // Get data for filters
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

    /**
     * Export Items Report
     */
    public function exportItems(Request $request)
    {
        $query = InventoryItem::where('delete_status', 1)->with('category');

        // Filter by category
        if ($request->filled('category_ids')) {
            $categoryIds = $request->input('category_ids');
            if (is_array($categoryIds) && !empty($categoryIds)) {
                $query->whereIn('category_id', $categoryIds);
            }
        }

        // Filter by item type
        if ($request->filled('item_type')) {
            $query->where('item_type', $request->item_type);
        }


        // Sorting
        $sortBy = $request->input('sort_by', 'item_name');
        $sortOrder = $request->input('sort_order', 'asc');
        $allowedSortFields = ['item_name', 'item_code', 'purchase_date', 'purchase_cost', 'category_id'];

        if (in_array($sortBy, $allowedSortFields)) {
            if ($sortBy === 'category_id') {
                $query->join('inventory_categories', 'inventory_items.category_id', '=', 'inventory_categories.id')
                    ->orderBy('inventory_categories.category_name', $sortOrder)
                    ->select('inventory_items.*');
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }
        } else {
            $query->orderBy('item_name', 'asc');
        }

        $items = $query->get();

        // Calculate summaries
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
        $filterDescription = $this->buildFilterDescription($request);

        $data = [
            'title' => 'Inventory Items Report',
            'filter_description' => $filterDescription,
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

    /**
     * Export Assignments Report
     */
    public function exportAssignments(Request $request)
    {
        $query = InventoryAssignment::with(['item', 'employee', 'department']);

        // Filter by status
        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by condition status
        if ($request->filled('condition_status') && $request->condition_status !== '') {
            $query->where('condition_status', $request->condition_status);
        }

        // Filter by employee
        if ($request->filled('employee_ids')) {
            $employeeIds = $request->input('employee_ids');
            if (is_array($employeeIds) && !empty($employeeIds)) {
                $query->whereIn('employee_id', $employeeIds);
            }
        }

        // Filter by department
        if ($request->filled('department_ids')) {
            $departmentIds = $request->input('department_ids');
            if (is_array($departmentIds) && !empty($departmentIds)) {
                $query->whereIn('department_id', $departmentIds);
            }
        }





        // Sorting
        $sortBy = $request->input('sort_by', 'assigned_date');
        $sortOrder = $request->input('sort_order', 'desc');
        $allowedSortFields = ['assigned_date', 'return_date', 'status'];

        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('assigned_date', 'desc');
        }

        $assignments = $query->get();

        // Calculate summaries
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

        $employeeSummary = [];
        foreach ($assignments as $assignment) {
            $empName = $assignment->employee->fullname ?? 'Unknown';
            if (!isset($employeeSummary[$empName])) {
                $employeeSummary[$empName] = ['total' => 0, 'assigned' => 0, 'returned' => 0];
            }
            $employeeSummary[$empName]['total']++;
            if ($assignment->status == 0) {
                $employeeSummary[$empName]['assigned']++;
            } else {
                $employeeSummary[$empName]['returned']++;
            }
        }

        $data = [
            'title' => 'Inventory Assignments Report',
            'filter_description' => $this->buildAssignmentFilterDescription($request),
            'report_generated_date' => now()->format('d-m-Y H:i:s'),
            'assignments' => $assignments,
            'total_assignments' => $totalAssignments,
            'assigned_count' => $assignedCount,
            'returned_count' => $returnedCount,
            'active_count' => $activeCount,
            'scrap_count' => $scrapCount,
            'department_summary' => $departmentSummary,
            'employee_summary' => $employeeSummary,
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

    /**
     * Export Maintenance Report
     */
    public function exportMaintenances(Request $request)
    {
        $query = InventoryMaintenance::with(['item', 'employee']);

        // Filter by maintenance type
        if ($request->filled('maintenance_type') && $request->maintenance_type !== '') {
            $query->where('maintenance_type', $request->maintenance_type);
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'start_date');
        $sortOrder = $request->input('sort_order', 'desc');
        $allowedSortFields = ['start_date', 'end_date', 'cost', 'status'];

        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('start_date', 'desc');
        }

        $maintenances = $query->get();

        // Calculate summaries
        $totalMaintenances = $maintenances->count();
        $totalCost = $maintenances->sum('cost');

        $typeSummary = [
            'scrap' => ['count' => 0, 'total_cost' => 0],
            'service' => ['count' => 0, 'total_cost' => 0],
            'upgrade' => ['count' => 0, 'total_cost' => 0],
        ];

        $statusSummary = [
            'pending' => 0,
            'complete' => 0,
        ];

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

        $data = [
            'title' => 'Inventory Maintenance Report',
            'filter_description' => $this->buildMaintenanceFilterDescription($request),
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

    /**
     * Export Categories Report
     */
    public function exportCategories(Request $request)
    {
        $query = InventoryCategory::where('delete_status', 1)->with('items');


        // Filter by has items
        if ($request->filled('has_items')) {
            if ($request->has_items == 'yes') {
                $query->has('items');
            } elseif ($request->has_items == 'no') {
                $query->doesntHave('items');
            }
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'category_name');
        $sortOrder = $request->input('sort_order', 'asc');

        if ($sortBy == 'items_count') {
            $query->withCount('items')->orderBy('items_count', $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        $categories = $query->get();

        // Calculate summaries
        $totalCategories = $categories->count();
        $totalItems = $categories->sum(function ($cat) {
            return $cat->items->count();
        });
        $totalCost = $categories->sum(function ($cat) {
            return $cat->items->sum('purchase_cost');
        });

        $data = [
            'title' => 'Inventory Categories Report',
            'filter_description' => $this->buildCategoryFilterDescription($request),
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

    // Helper methods
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

    private function buildFilterDescription($request)
    {
        $parts = [];

        if ($request->filled('category_ids')) {
            $categories = InventoryCategory::whereIn('id', $request->category_ids)->pluck('category_name')->toArray();
            $parts[] = 'Categories: ' . implode(', ', $categories);
        }

        if ($request->filled('item_type')) {
            $parts[] = 'Item Type: ' . ($request->item_type == 1 ? 'Refurbished' : 'New');
        }

        if ($request->filled('purchase_date_from') || $request->filled('purchase_date_to')) {
            $from = $request->purchase_date_from ?? 'Start';
            $to = $request->purchase_date_to ?? 'End';
            $parts[] = "Purchase Date: {$from} to {$to}";
        }

        if ($request->filled('search')) {
            $parts[] = "Search: {$request->search}";
        }

        return empty($parts) ? 'All Items' : implode(' | ', $parts);
    }

    private function buildAssignmentFilterDescription($request)
    {
        $parts = [];

        if ($request->filled('status') && $request->status !== '') {
            $parts[] = 'Status: ' . ($request->status == 0 ? 'Assigned' : 'Returned');
        }

        if ($request->filled('condition_status') && $request->condition_status !== '') {
            $parts[] = 'Condition: ' . ($request->condition_status == 1 ? 'Active' : 'Scrap');
        }

        if ($request->filled('employee_ids')) {
            $employees = Employee::whereIn('emp_id', $request->employee_ids)->pluck('fullname')->toArray();
            $parts[] = 'Employees: ' . implode(', ', $employees);
        }

        if ($request->filled('department_ids')) {
            $departments = Department::whereIn('dep_id', $request->department_ids)->pluck('dep_name')->toArray();
            $parts[] = 'Departments: ' . implode(', ', $departments);
        }

        return empty($parts) ? 'All Assignments' : implode(' | ', $parts);
    }

    private function buildMaintenanceFilterDescription($request)
    {
        $parts = [];

        if ($request->filled('maintenance_type') && $request->maintenance_type !== '') {
            $types = ['Scrap', 'Service', 'Upgrade'];
            $parts[] = 'Type: ' . ($types[$request->maintenance_type] ?? 'Unknown');
        }

        if ($request->filled('status') && $request->status !== '') {
            $parts[] = 'Status: ' . ($request->status == 0 ? 'Pending' : 'Complete');
        }

        if ($request->filled('start_date_from') || $request->filled('start_date_to')) {
            $from = $request->start_date_from ?? 'Start';
            $to = $request->start_date_to ?? 'End';
            $parts[] = "Date Range: {$from} to {$to}";
        }

        return empty($parts) ? 'All Maintenance Records' : implode(' | ', $parts);
    }

    private function buildCategoryFilterDescription($request)
    {
        $parts = [];

        if ($request->filled('search')) {
            $parts[] = "Search: {$request->search}";
        }

        if ($request->filled('has_items')) {
            $parts[] = $request->has_items == 'yes' ? 'With Items Only' : 'Empty Categories Only';
        }

        return empty($parts) ? 'All Categories' : implode(' | ', $parts);
    }
}
