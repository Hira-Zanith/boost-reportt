<table border="1" width="100%" style="border-collapse: collapse; font-family: 'Kantumruy Pro', sans-serif;">
    <thead>
        <tr style="background: #f3f4f6;">
            @foreach($columns as $col)
                <th style="padding: 8px;">{{ ucfirst($col) }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($reports as $row)
        <tr>
            @foreach($columns as $col)
                <td style="padding: 8px; text-align: center;">
                    {{ $col == 'created_at' ? $row->created_at->format('d/m/Y') : $row->{$col} }}
                </td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>