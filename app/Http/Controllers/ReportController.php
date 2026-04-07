<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Models\Report;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportsExport;
use Barryvdh\DomPDF\Facade\Pdf;


class ReportController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
{
    $query = Report::query();

    // ១. ស្វែងរកតាមឈ្មោះបុគ្គលិក
    if ($request->filled('search_staff')) {
        $query->where('staff_name', 'LIKE', '%' . $request->search_staff . '%');
    }

    // ២. ស្វែងរកតាមចន្លោះកាលបរិច្ឆេទ (សប្តាហ៍, ខែ, ឬរើសតាមចិត្ត)
    if ($request->filled('start_date') && $request->filled('end_date')) {
        // បើអ្នករើស Start និង End Date
        $query->whereBetween('created_at', [
            $request->start_date . ' 00:00:00', 
            $request->end_date . ' 23:59:59'
        ]);
    } elseif ($request->filled('search_date')) {
        // បើអ្នករើសតែថ្ងៃមួយ (ដូចមុន)
        $query->whereDate('created_at', $request->search_date);
    }

    // ៣. គណនាសរុប "មុនពេល" ធ្វើការ Paginate (ដើម្បីឱ្យតួលេខសរុបត្រូវនឹង Filter)
    // យើងប្រើ clone() ដើម្បីកុំឱ្យប៉ះពាល់ដល់ $query ចម្បងសម្រាប់ទាញតារាង
    $total_spend = (clone $query)->sum('spend');
    $total_msg = (clone $query)->sum('messages');
    $total_new = (clone $query)->sum('new_id');
    $total_invoice_amount = (clone $query)->sum('invoice_amount');

    // ៤. ទាញទិន្នន័យសម្រាប់តារាង (Pagination)
    $reports = $query->latest()->paginate(20)->withQueryString();

    return view('reports.index', compact(
        'reports', 
        'total_spend', 
        'total_msg', 
        'total_new', 
        'total_invoice_amount'
    ));
}

    public function store(Request $request)
    {
        // ១. ត្រួតពិនិត្យទិន្នន័យ (Validation)
        $request->validate([
            'product'        => 'required|string|max:255',
            'spend'          => 'required|numeric|min:0',
            'invoice_amount' => 'required|numeric|min:0',
            'messages'       => 'required|integer|min:0',
            'new_id'         => 'required|integer|min:0',
        ]);

        // ២. បង្កើតទិន្នន័យ (ប្រើវិធីសាស្ត្រ Manual ដើម្បីសុវត្ថិភាពជាង $request->all())
        $report = new \App\Models\Report();
        $report->user_id        = auth()->id(); 
        $report->staff_name     = auth()->user()->name; // ប្រើឈ្មោះពិតពី Account តែម្តង
        $report->product        = $request->product;
        $report->spend          = $request->spend;
        $report->invoice_amount = $request->invoice_amount;
        $report->messages       = $request->messages;
        $report->new_id         = $request->new_id;
        $report->save();

        // ៣. រៀបចំទិន្នន័យសម្រាប់ Telegram
        $token = "8772483908:AAENzt2TkxYLbpLx_o1UWUXRjip0uDuQxUY";
        $chat_id = "-1003871438575";
        
        // គណនា Cost Per Message (CPM)
        $cpm = ($report->messages > 0) ? number_format($report->spend / $report->messages, 2) : 0;
        
        // កំណត់ម៉ោងនៅកម្ពុជា
        $date = now()->timezone('Asia/Phnom_Penh')->format('d/m/Y h:i A');

        // រៀបចំអត្ថបទផ្ញើទៅ Telegram (បន្ថែម Product ចូល)
        $text = "🚀 <b>ADS REPORT SUBMITTED</b>\n"
            . "━━━━━━━━━━━━━━━\n"
            . "👤 <b>បុគ្គលិក:</b> {$report->staff_name}\n"
            . "📦 <b>ឈ្មោះក្បាល:</b> {$report->product}\n" // បន្ថែមព័ត៌មានផលិតផល
            . "📅 <b>ថ្ងៃទី:</b> {$date}\n"
            . "━━━━━━━━━━━━━━━\n"
            . "💰 Spend: <b>\${$report->spend}</b>\n"
            . "🧾 Invoice: <b>\${$report->invoice_amount}</b>\n"
            . "💬 Messages: <b>{$report->messages}</b>\n"
            . "👥 New ID: <b>{$report->new_id}</b>\n"
            . "🎯 Cost/Msg: <b>\${$cpm}</b>\n"
            . "━━━━━━━━━━━━━━━\n"
            . "✅ <i>រក្សាទុកក្នុងប្រព័ន្ធរួចរាល់</i>";

        // ៤. ផ្ញើទៅ Telegram (ប្រើ post() ជំនួស get() ដើម្បីសុវត្ថិភាព និងទិន្នន័យធំ)
        \Illuminate\Support\Facades\Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id'    => $chat_id,
            'text'       => $text,
            'parse_mode' => 'HTML'
        ]);

        return back()->with('success', 'របាយការណ៍ត្រូវបានផ្ញើ និងរក្សាទុកជោគជ័យ! 🚀');
    }

    public function destroy($id)
    {
        $report = \App\Models\Report::findOrFail($id);

        // លក្ខខណ្ឌ៖ ទាល់តែជា Admin ឬជាម្ចាស់របាយការណ៍ (user_id ដូចគ្នា) ទើបលុបបាន
        if (auth()->user()->role === 'admin' || auth()->id() === $report->user_id) {
            $report->delete();
            return back()->with('success', 'លុបរបាយការណ៍ជោគជ័យ!');
        }

        return back()->with('error', 'អ្នកមិនមានសិទ្ធិលុបរបាយការណ៍នេះទេ!');
    }

    // Export to Excel and PDF
    // ១. សម្រាប់ Excel
    public function exportExcel(Request $request) {
        $columns = $request->input('columns', ['created_at', 'staff_name', 'spend']); // Default បើអត់រើស
        $query = $this->applyFilter($request);
        
        return Excel::download(new ReportsExport($query, $columns), 'report_custom.xlsx');
    }
    // ២. សម្រាប់ PDF
    public function exportPdf(Request $request) {
        $columns = $request->input('columns', ['created_at', 'staff_name', 'spend']);
        $reports = $this->applyFilter($request)->get();
        
        $pdf = Pdf::loadView('reports.pdf', compact('reports', 'columns'));
        return $pdf->download('report_custom.pdf');
    }

    // Function ជំនួយសម្រាប់ចាប់យក Filter ដូចក្នុង Index
    private function applyFilter($request) {
        $query = \App\Models\Report::query();
        if ($request->filled('start_date')) {
            $query->whereBetween('created_at', [$request->start_date.' 00:00:00', $request->end_date.' 23:59:59']);
        }
        return $query;
    }
}