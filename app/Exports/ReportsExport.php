<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReportsExport implements FromQuery, WithHeadings, WithMapping
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
        // បង្ហាញក្បាលតារាងតាមអ្វីដែលយើងរើស
        return array_map('ucfirst', $this->columns);
    }

    public function map($report): array {
        $data = [];
        foreach ($this->columns as $column) {
            $data[] = $report->{$column};
        }
        return $data;
    }
}