<?php

use App\Http\Controllers\BillController;
use App\Http\Controllers\TelcoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VednorController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CounselorController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ExaminationController;
use App\Http\Controllers\CasesController;
use App\Http\Controllers\CaseDetailController;
use App\Http\Controllers\SalaryController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


// Route::get('/dashboard', function () {
//     return view('admin/dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Bills Routes
    Route::group(['prefix' => 'bills'], function() {
        Route::get('/', [BillController::class, 'index'])->name('billsListing');
        Route::get('/import', [BillController::class, 'import'])->name('importBills');
        Route::get('/reports', [BillController::class, 'reports'])->name('reportsBills');
        Route::post('/import', [BillController::class, 'import'])->name('importBillsSaved');
    });

    // TELCO Routes
    Route::group(['prefix' => 'telco'], function() {
        Route::get('/', [TelcoController::class, 'index'])->name('telcoListing');
        Route::get('/import', [TelcoController::class, 'import'])->name('importTelcoBills');
        Route::get('/reports', [TelcoController::class, 'reports'])->name('reportsTelcoBills');
        Route::post('/import', [TelcoController::class, 'import'])->name('importTelcoBillsSaved');
    });

});

require __DIR__.'/auth.php';
