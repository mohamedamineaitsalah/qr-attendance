<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\ScannerController;
use Illuminate\Support\Facades\Route;

// ─── Lang ─────────────────────────────────────────────────────────────────────
Route::get('lang/{lang}', [LanguageController::class, 'switchLang'])->name('lang.switch');

// ─── Auth ─────────────────────────────────────────────────────────────────────
Route::get('/',     [AdminLoginController::class, 'showLoginForm'])->name('login');
Route::get('/login', [AdminLoginController::class, 'showLoginForm']);
Route::post('/login', [AdminLoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

// ─── Protected Admin Routes ───────────────────────────────────────────────────
Route::middleware('auth.admin')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // People Management
    Route::resource('persons', PersonController::class)->except(['show']);
    Route::get('/persons/{person}/qr',          [PersonController::class, 'showQr'])->name('persons.qr');
    Route::get('/persons/{person}/qr/download', [PersonController::class, 'downloadQr'])->name('persons.qr.download');
    Route::delete('/persons/{person}/clear-history', [PersonController::class, 'clearHistory'])->name('persons.clear_history');

    // QR Scanner
    Route::get('/scanner/entry', [ScannerController::class, 'entry'])->name('scanner.entry');
    Route::get('/scanner/exit',  [ScannerController::class, 'exit'])->name('scanner.exit');
    Route::post('/scanner/scan', [ScannerController::class, 'scan'])->name('scanner.scan');

    // Attendance
    Route::get('/inside',           [AttendanceController::class, 'inside'])->name('attendance.inside');
    Route::get('/attendance',       [AttendanceController::class, 'history'])->name('attendance.history');
    Route::delete('/attendance/{attendance}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');
    Route::delete('/attendance-clear-all',    [AttendanceController::class, 'clearAll'])->name('attendance.clear_all');
    Route::delete('/rejected-clear-all',      [AttendanceController::class, 'clearRejected'])->name('attendance.clear_rejected');
    Route::get('/rejected-scans',   [AttendanceController::class, 'rejected'])->name('attendance.rejected');

    // Export
    Route::get('/attendance/export/excel', [ExportController::class, 'excel'])->name('export.excel');
    Route::get('/attendance/export/pdf',   [ExportController::class, 'pdf'])->name('export.pdf');
});
