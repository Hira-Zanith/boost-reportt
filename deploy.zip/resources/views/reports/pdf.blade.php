<table class="table">
    <thead>
        <tr>
            @foreach($columns as $column)
                <th>
                    @if($column == 'created_at')
                        Date
                    @else
                        {{ ucwords(str_replace('_', ' ', $column)) }}
                    @endif
                </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($reports as $report)
            <tr>
                @foreach($columns as $column)
                    <td>
                        @if($column == 'created_at')
                            {{-- បង្ហាញតែ ឆ្នាំ-ខែ-ថ្ងៃ (ឧទាហរណ៍៖ 2026-04-06) --}}
                            {{ \Carbon\Carbon::parse($report->$column)->format('Y-m-d') }}
                        @elseif($column == 'spend' || $column == 'invoice_amount')
                            {{ number_format($report->$column, 2) }}
                        @else
                            {{ $report->$column }}
                        @endif
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
    
    {{-- ផ្នែកសរុប (TOTAL ROW) --}}
    <tfoot>
        <tr class="total-row">
            @foreach($columns as $index => $column)
                <td>
                    @if($index == 0)
                        <strong>TOTAL</strong>
                    @elseif(in_array($column, ['spend', 'invoice_amount', 'messages', 'new_id']))
                        <strong>{{ number_format($reports->sum($column), 2) }}</strong>
                    @else
                        -
                    @endif
                </td>
            @endforeach
        </tr>
    </tfoot>
</table>

<!-- //* CSS សម្រាប់សរុប (TOTAL ROW) */ -->
<style>
    table {
        width: 100%;
        border-collapse: collapse;
        font-family: 'Kantumruy Pro', sans-serif; /* ឬ font ខ្មែរដែលអ្នកប្រើ */
        font-size: 12px;
    }
    th {
        background-color: #f8f9fa;
        color: #333;
        font-weight: bold;
        padding: 10px;
        border: 1px solid #dee2e6;
        text-align: left;
    }
    td {
        padding: 8px;
        border: 1px solid #dee2e6;
    }
    .total-row {
        background-color: #f2f2f2; /* ពណ៌ប្រផេះស្រាលដូចក្នុងរូបភាព */
    }
    .total-row td {
        font-size: 14px;
        color: #000;
    }
</style>