<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// ១. ទំព័រដើម (សម្រាប់អ្នកមិនទាន់ Login)
Route::get('/', function () {
    return view('welcome');
});

// ២. រាល់ Route ដែលត្រូវការ Login ទើបចូលបាន
Route::middleware(['auth', 'verified'])->group(function () {
    
    // ទំព័រ Dashboard បង្ហាញរបាយការណ៍ Ads
    Route::get('/dashboard', [ReportController::class, 'index'])->name('dashboard');
    
    // មុខងារ Store និង Delete របាយការណ៍
    Route::post('/report/store', [ReportController::class, 'store'])->name('report.store');
    Route::delete('/report/delete/{id}', [ReportController::class, 'destroy'])->name('report.destroy');

    // --- បន្ថែមផ្នែកនេះដើម្បីបាត់ Error profile.edit ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // 

    // --- មុខងារ Export Excel និង PDF ---
    Route::get('/export/excel', [ReportController::class, 'exportExcel'])->name('export.excel');
    Route::get('/export/pdf', [ReportController::class, 'exportPdf'])->name('export.pdf');


});

// ៣. ទាញយក Route សម្រាប់ Login/Register (ដាក់តែម្តងបានហើយ)
require __DIR__.'/auth.php';