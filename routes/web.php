<?php

use App\Http\Controllers\Admin\AuditTrailController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Auth\StaffLoginController;
use App\Http\Controllers\Billing\CasemixController;
use App\Http\Controllers\Billing\InvoiceController;
use App\Http\Controllers\Billing\PaymentController;
use App\Http\Controllers\BPJS\EClaimController;
use App\Http\Controllers\BPJS\VClaimController;
use App\Http\Controllers\Clinical\MedicalRecordController;
use App\Http\Controllers\Clinical\NursingAssessmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Laboratory\LabOrderController;
use App\Http\Controllers\Laboratory\LabResultController;
use App\Http\Controllers\Pharmacy\InventoryController;
use App\Http\Controllers\Pharmacy\PrescriptionController;
use App\Http\Controllers\Pharmacy\ProcurementController;
use App\Http\Controllers\Radiology\RadiologyOrderController;
use App\Http\Controllers\Radiology\RadiologyResultController;
use App\Http\Controllers\Registration\EncounterController;
use App\Http\Controllers\Registration\PatientController;
use App\Http\Controllers\Registration\QueueController;
use App\Http\Controllers\Report\BIAnalyticsController;
use App\Http\Controllers\Report\ClinicalReportController;
use App\Http\Controllers\Report\FinancialReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => auth('staff')->check() ? redirect()->route('dashboard') : redirect()->route('login'));

Route::get('display-antrean', [\App\Http\Controllers\Public\QueueDisplayController::class, 'index'])->name('public.queue.display');

Route::middleware('guest:staff')->group(function () {
    Route::get('login', [StaffLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [StaffLoginController::class, 'login'])->name('login.store');
});

Route::post('logout', [StaffLoginController::class, 'logout'])->name('logout');

Route::middleware('staff')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('admin')->name('admin.')->middleware('role:super-administrator,sys-admin')->group(function () {
        Route::get('users', [UserManagementController::class, 'index'])->name('users.index');
        Route::post('users', [UserManagementController::class, 'store'])->name('users.store');
        Route::patch('users/{user}/update', [UserManagementController::class, 'update'])->name('users.update');
        Route::patch('users/{user}/toggle', [UserManagementController::class, 'toggle'])->name('users.toggle');
        Route::delete('users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
        Route::get('audit', [AuditTrailController::class, 'index'])->name('audit.index');
    });

    Route::prefix('pendaftaran')->name('pendaftaran.')->middleware('role:super-administrator,pendaftaran,casemix,dokter-umum,dokter-spesialis,perawat')->group(function () {
        Route::get('pasien', [PatientController::class, 'index'])->name('pasien.index');
        Route::get('pasien/create', [PatientController::class, 'create'])->name('pasien.create');
        Route::post('pasien', [PatientController::class, 'store'])->name('pasien.store');
        Route::get('pasien/{patient}', [PatientController::class, 'show'])->name('pasien.show');
        Route::get('pasien/{patient}/edit', [PatientController::class, 'edit'])->name('pasien.edit');
        Route::patch('pasien/{patient}', [PatientController::class, 'update'])->name('pasien.update');
        Route::delete('pasien/{patient}', [PatientController::class, 'destroy'])->name('pasien.destroy');
        Route::get('kunjungan/create', [EncounterController::class, 'create'])->name('kunjungan.create');
        Route::post('kunjungan', [EncounterController::class, 'store'])->name('kunjungan.store');
        Route::get('antrian', [QueueController::class, 'index'])->name('antrian');
        Route::get('beds', [\App\Http\Controllers\Registration\BedManagementController::class, 'index'])->name('beds.index');
        Route::patch('kunjungan/{encounter}/cancel', [EncounterController::class, 'cancel'])->name('kunjungan.cancel');
    });

    Route::prefix('keperawatan')->name('keperawatan.')->middleware('role:super-administrator,perawat,dokter-umum,dokter-spesialis')->group(function () {
        Route::get('antrian', [NursingAssessmentController::class, 'queue'])->name('antrian');
        Route::get('{encounter}/asesmen', [NursingAssessmentController::class, 'edit'])->name('asesmen.edit');
        Route::post('{encounter}/asesmen', [NursingAssessmentController::class, 'store'])->name('asesmen.store');
    });

    Route::prefix('rekam-medis')->name('rekam-medis.')->middleware('role:super-administrator,dokter-umum,dokter-spesialis,perawat,casemix')->group(function () {
        Route::get('antrian', [MedicalRecordController::class, 'queue'])->name('antrian');
        Route::get('{encounter}/edit', [MedicalRecordController::class, 'edit'])->name('edit');
        Route::get('{encounter}/resume', [\App\Http\Controllers\Clinical\DischargeController::class, 'resume'])->name('resume');
        Route::post('{encounter}', [MedicalRecordController::class, 'update'])->name('update');
    });

    Route::prefix('farmasi')->name('farmasi.')->middleware('role:super-administrator,apoteker,dokter-umum,dokter-spesialis,casemix')->group(function () {
        Route::get('antrian-resep', [PrescriptionController::class, 'index'])->name('antrian-resep');
        Route::post('resep/{prescription}/dispense', [PrescriptionController::class, 'dispense'])->name('dispense');
        Route::get('inventory', [InventoryController::class, 'index'])->name('inventory.index');
        Route::post('inventory', [InventoryController::class, 'store'])->name('inventory.store');
        Route::get('inventory/{medicine}', [InventoryController::class, 'show'])->name('inventory.show');
        Route::patch('inventory/{medicine}', [InventoryController::class, 'update'])->name('inventory.update');
        Route::delete('inventory/{medicine}', [InventoryController::class, 'destroy'])->name('inventory.destroy');
        
        Route::get('procurement', [ProcurementController::class, 'index'])->name('procurement.index');
        Route::get('procurement/create', [ProcurementController::class, 'create'])->name('procurement.create');
        Route::post('procurement', [ProcurementController::class, 'store'])->name('procurement.store');
        Route::post('procurement/{po}/receive', [ProcurementController::class, 'receive'])->name('procurement.receive');
    });

    Route::prefix('lab')->name('lab.')->middleware('role:super-administrator,analis-lab,dokter-umum,dokter-spesialis,casemix')->group(function () {
        Route::get('antrian', [LabOrderController::class, 'index'])->name('antrian');
        Route::get('hasil/{labOrder}', [LabResultController::class, 'edit'])->name('hasil.edit');
        Route::post('hasil/{labOrder}', [LabResultController::class, 'update'])->name('hasil.update');
    });

    Route::prefix('rad')->name('rad.')->middleware('role:super-administrator,radiografer,dokter-umum,dokter-spesialis,casemix')->group(function () {
        Route::get('antrian', [RadiologyOrderController::class, 'index'])->name('antrian');
        Route::get('hasil/{radiologyOrder}', [RadiologyResultController::class, 'edit'])->name('hasil.edit');
        Route::post('hasil/{radiologyOrder}', [RadiologyResultController::class, 'update'])->name('hasil.update');
    });

    Route::prefix('keuangan')->name('keuangan.')->middleware('role:super-administrator,kasir,casemix')->group(function () {
        Route::get('antrian-kasir', [InvoiceController::class, 'queue'])->name('antrian-kasir');
        Route::get('invoice/{invoice}', [InvoiceController::class, 'show'])->name('invoice.show');
        Route::post('invoice/generate/{encounter}', [InvoiceController::class, 'generate'])->name('invoice.generate');
        Route::post('invoice/{invoice}/payment', [PaymentController::class, 'store'])->name('payment.store');
    });

    Route::prefix('casemix')->name('casemix.')->middleware('role:super-administrator,casemix')->group(function () {
        Route::get('/', [CasemixController::class, 'index'])->name('index');
        Route::get('simulasi/{encounter}', [CasemixController::class, 'simulate'])->name('simulate');
    });

    Route::prefix('bpjs')->name('bpjs.')->middleware('role:super-administrator,pendaftaran,casemix')->group(function () {
        Route::get('/', [VClaimController::class, 'index'])->name('index');
        Route::post('sep/{encounter}', [VClaimController::class, 'createSep'])->name('sep.store');
        Route::post('eclaim/simulasi/{encounter}', [EClaimController::class, 'simulate'])->name('eclaim.simulate');
        Route::post('eclaim/ajukan/{encounter}', [EClaimController::class, 'submit'])->name('eclaim.submit');
    });

    Route::prefix('laporan')->name('laporan.')->middleware('role:super-administrator,kasir,casemix,dokter-umum,dokter-spesialis')->group(function () {
        Route::get('bi-dashboard', [BIAnalyticsController::class, 'dashboard'])->name('bi.dashboard');
        Route::get('kunjungan', [ClinicalReportController::class, 'visits'])->name('kunjungan');
        Route::get('morbiditas', [ClinicalReportController::class, 'morbidity'])->name('morbiditas');
        Route::get('pendapatan', [FinancialReportController::class, 'revenue'])->name('pendapatan');
        Route::get('export/{type}', [ClinicalReportController::class, 'export'])->name('export');
    });
});
