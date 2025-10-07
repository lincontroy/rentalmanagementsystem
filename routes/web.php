<?php
// routes/web.php
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

// Redirect root to login
Route::get('/', function () {
    return redirect('/login');
});

// Authentication Routes (should be outside auth middleware)
require __DIR__.'/auth.php';

// Protected Routes - ALL require authentication
Route::middleware(['auth'])->group(function () {
    
    // Dashboard - accessible to all authenticated users
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Property Management - Admin & Managers only
   
        Route::resource('properties', PropertyController::class);
        Route::post('/properties/{property}/toggle-status', [PropertyController::class, 'toggleStatus'])->name('properties.toggle-status');
Route::get('/properties/{property}/statistics', [PropertyController::class, 'statistics'])->name('properties.statistics');
        Route::resource('units', UnitController::class);
        Route::resource('tenants', TenantController::class);



        Route::resource('expenses', ExpenseController::class);
Route::get('/expenses/report', [ExpenseController::class, 'report'])->name('expenses.report');
Route::get('/expenses/export', [ExpenseController::class, 'export'])->name('expenses.export');
Route::get('/expenses/summary', [ExpenseController::class, 'summary'])->name('expenses.summary');
 

    // Payments - All authenticated users
    Route::resource('payments', PaymentController::class);

    // Notices - All authenticated users
    Route::resource('notices', NoticeController::class);
    Route::post('/notices/{notice}/toggle-status', [NoticeController::class, 'toggleStatus'])->name('notices.toggle-status');
    Route::get('/notices/active', [NoticeController::class, 'active'])->name('notices.active');

    // Reports - All authenticated users
    Route::prefix('reports')->group(function () {
        Route::get('/rent', [ReportController::class, 'rentReport'])->name('reports.rent');
        Route::get('/arrears', [ReportController::class, 'arrearsReport'])->name('reports.arrears');
        Route::get('/occupancy', [ReportController::class, 'occupancyReport'])->name('reports.occupancy');
    });

    // Admin Only Routes
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        Route::get('/users', function () {
            return view('admin.users');
        })->name('admin.users');
        
        Route::get('/settings', function () {
            return view('admin.settings');
        })->name('admin.settings');
    });
});

// Test route to check authentication (remove this after testing)
Route::get('/test-auth', function () {
    if (auth()->check()) {
        return "You are logged in as: " . auth()->user()->name;
    } else {
        return "You are NOT logged in";
    }
});