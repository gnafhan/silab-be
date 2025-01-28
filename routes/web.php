<?php

use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\PengadaanController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        // 'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', HandleInertiaRequests::class])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('inventory/template', [InventoryController::class, 'downloadTemplate'])->name('inventory.template');
    Route::post('inventory/import', [InventoryController::class, 'import'])->name('inventory.import');
    
    // Inventory Routes
    Route::resource('inventory', \App\Http\Controllers\Admin\InventoryController::class);
    Route::resource('room', RoomController::class);

    // Inventory Import Routes

    // Pengadaan Routes
    Route::get('pengadaan/template', [PengadaanController::class, 'downloadTemplate'])->name('pengadaan.template');
    Route::post('pengadaan/import', [PengadaanController::class, 'import'])->name('pengadaan.import');
    Route::get('/pengadaan/{pengadaan}', [PengadaanController::class, 'show'])->name('pengadaan.show');
    Route::get('/pengadaan/{pengadaan}/edit-inventory', [PengadaanController::class, 'editInventory'])->name('pengadaan.edit-inventory');
    Route::post('/pengadaan/{pengadaan}/update-inventory', [PengadaanController::class, 'updateInventory'])->name('pengadaan.update-inventory');
    Route::delete('/pengadaan/{pengadaan}/inventory/{inventory}', [PengadaanController::class, 'removeInventory'])->name('pengadaan.remove-inventory');
    
    // Pengadaan Import Routes
    Route::resource('pengadaan', \App\Http\Controllers\Admin\PengadaanController::class);
});

require __DIR__.'/auth.php';
