<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents, WithTitle
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function title(): string
    {
        return 'Attendance Report';
    }

    public function headings(): array
    {
        return [
            ['ATTENDANCE REPORT'],
            [],
            [
                'Emp Code',
                'Name',
                'Dept',
                'Date',
                'Clock In',
                'Clock Out',
                'Work Hrs',
                'Status'
            ]
        ];
    }

    public function map($row): array
    {
        $row = (object) $row;

        // Safe time formatting
        $clockIn = !empty($row->clock_in)
            ? Carbon::parse($row->clock_in)->format('h:i A')
            : '-';

        $clockOut = !empty($row->clock_out)
            ? Carbon::parse($row->clock_out)->format('h:i A')
            : '-';

        // Work hours
        $workHours = '-';
        if (!empty($row->clock_in) && !empty($row->clock_out)) {
            try {
                $start = Carbon::parse($row->clock_in);
                $end = Carbon::parse($row->clock_out);
                $diff = $start->diff($end);
                $workHours = "{$diff->h}h {$diff->i}m";
            } catch (\Exception $e) {
                $workHours = '-';
            }
        }

        return [
            $row->emp_code ?? '',
            $row->name ?? '',
            $row->dept ?? '',
            $row->date ?? '',
            $clockIn,
            $clockOut,
            $workHours,
            $row->status ?? '',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // ✅ FIX: Correct column range (8 columns → A:H)
        $sheet->mergeCells('A1:H1');

        // Title style
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2c3e50']
            ]
        ]);

        // Header style
        $sheet->getStyle('A3:H3')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '3498db']
            ]
        ]);

        return $sheet;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function ($event) {

                $lastRow = count($this->data) + 3;

                for ($row = 4; $row <= $lastRow; $row++) {

                    $status = $event->sheet->getCell("H{$row}")->getValue();

                    // ✅ Status colors
                    $colors = [
                        'Present' => '27ae60',
                        'Absent' => 'e74c3c',
                        'Late' => 'f1c40f',
                        'Half Day' => '2980b9',
                        'Holiday' => '8e44ad',
                        'Day Off' => '34495e',
                        'Leave' => 'e67e22',
                        'Half Leave' => 'f39c12'
                    ];

                    if (isset($colors[$status])) {
                        $event->sheet->getStyle("H{$row}")->applyFromArray([
                            'font' => [
                                'color' => ['rgb' => 'FFFFFF'],
                                'bold' => true
                            ],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => $colors[$status]]
                            ]
                        ]);
                    }

                    // ✅ TOTAL row highlight (safe check)
                    $name = $event->sheet->getCell("B{$row}")->getValue();

                    if (strtoupper(trim($name)) === 'TOTAL') {
                        $event->sheet->getStyle("A{$row}:H{$row}")->applyFromArray([
                            'font' => ['bold' => true],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'dfe6e9']
                            ]
                        ]);
                    }
                }
            }
        ];
    }
}
