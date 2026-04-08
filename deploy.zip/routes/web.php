<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ១. ទំព័រដើម (Public)
Route::get('/', function () {
    return view('welcome');
});

// ២. រាល់ Route ដែលត្រូវការ Login
Route::middleware(['auth', 'verified'])->group(function () {

    // --- ទំព័រ Dashboard (បង្ហាញ Form + Table) ---
    Route::get('/dashboard', [ReportController::class, 'index'])->name('dashboard');

    // --- ទំព័រ របាយការណ៍ (បង្ហាញតែ Table) ---
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // --- មុខងារគ្រប់គ្រងបុគ្គលិក (Admin Only) ---
    Route::middleware(['admin'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::put('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset_password');
    });

    // --- សកម្មភាពលើរបាយការណ៍ ---
    Route::post('/report/store', [ReportController::class, 'store'])->name('report.store');
    Route::delete('/report/delete/{id}', [ReportController::class, 'destroy'])->name('report.destroy');

    // --- មុខងារ Profile ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- មុខងារ Export ---
    Route::get('/export/excel', [ReportController::class, 'exportExcel'])->name('export.excel');
    Route::get('/export/pdf', [ReportController::class, 'exportPdf'])->name('export.pdf');
});

require __DIR__.'/auth.php';