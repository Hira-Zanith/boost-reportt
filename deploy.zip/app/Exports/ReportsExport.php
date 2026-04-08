<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ReportsExport implements FromQuery, WithHeadings, WithMapping, WithEvents
{
    protected $query;
    protected $columns;

    public function __construct($query, $columns) {
        $this->query = $query;
        $this->columns = $columns;
    }

    public function query() {
        return $this->query;
    }

    public function headings(): array {
        return $this->columns; // បង្ហាញចំណងជើងតាមអ្នករើស
    }

    public function map($report): array
    {
        $data = [];
        foreach ($this->columns as $column) {
            if ($column == 'created_at') {
                // កាត់យកតែថ្ងៃខែឆ្នាំ
                $data[] = \Carbon\Carbon::parse($report->created_at)->format('Y-m-d');
            } else {
                $data[] = $report->$column;
            }
        }
        return $data;
    }

    public function registerEvents(): array {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $lastRow = $event->sheet->getHighestRow();
                $nextRow = $lastRow + 1;

                // បន្ថែមពាក្យថា "Total" នៅជួរចុងក្រោយ
                $event->sheet->setCellValue("A{$nextRow}", 'TOTAL');
                
                // បន្ថែមរូបមន្តបូកសរុប (ឧទាហរណ៍៖ បើ Spend នៅ Column D)
                // $event->sheet->setCellValue("D{$nextRow}", "=SUM(D2:D{$lastRow})");
            },
        ];
    }
}