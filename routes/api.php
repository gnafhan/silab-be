<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReserveRuleController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\umum\InventoryReserfController;
use App\Http\Controllers\umum\LaboratoriumController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/', function(){
    return response()->json(["message"=> "Server Running"]);
});

// Routes tanpa middleware
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Routes 'auth:sanctum' middleware
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/email/verification-link', [EmailVerificationController::class, 'getVerificationLink']);
    Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail']);
});

// Routes 'auth:sanctum' dan 'verified' middleware
Route::middleware(['auth:sanctum', 'verified', 'kaleb'])->group(function () {
    Route::get('/current', [AuthController::class, 'current']);

    Route::apiResource('rooms', RoomController::class);
    Route::apiResource('inventories', InventoryController::class);
    Route::apiResource('subjects', SubjectController::class);
    Route::apiResource('rules', ReserveRuleController::class);

    // Dashboard route
    Route::get('dashboard/countLab', [DashboardController::class, 'countLab'])->name('dashboard.count-lab');
    Route::get('dashboard/countInventory', [DashboardController::class, 'countInvent'])->name('dashboard.count-inventory');
    Route::get('dashboard/schedules', [DashboardController::class, 'getSchedule'])->name('dashboard.schedules');
    Route::get('dashboard/labReserve', [DashboardController::class, 'getLabReserve'])->name('dashboard.labReserve');
    Route::get('dashboard/inventoryReserve', [DashboardController::class, 'getInventoryReserve'])->name('dashboard.inventoryReserve');

    // Schedule route
    Route::get('schedules', [JadwalController::class, 'getSchedule'])->name('schedule');
    Route::get('schedules/{id}', [JadwalController::class, 'getScheduleByRoom'])->name('schedule.detail');

    // Reserve route
    Route::get('reserve/laboratorium', [PeminjamanController::class, 'getLabReserve'])->name('reserve.laboratorium');
    Route::patch('reserve/laboratorium/{id}', [PeminjamanController::class, 'changeStatusRoom'])->name('reserve.lab.update');
    Route::get('reserve/inventory', [PeminjamanController::class, 'getInventoryReserve'])->name('reserve.inventory');
    Route::patch('reserve/inventory/{id}', [PeminjamanController::class, 'changeStatusInventory'])->name('reserve.invent.update');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // Profile route
    Route::get('users', [ProfileController::class, 'getProfile'])->name('users.profile');
    Route::patch('users', [ProfileController::class, 'update'])->name('users.update');
    
    // Laboratorium route
    Route::get('laboratorium', [LaboratoriumController::class, 'index'])->name('laboratorium');
    Route::get('laboratorium/{id}', [LaboratoriumController::class, 'detail'])->name('laboratorium.detail');
    Route::get('laboratorium/schedule/{id}', [LaboratoriumController::class, 'getScheduleByRoom'])->name('laboratorium.schedule');
    Route::get('laboratorium/{id}/reserve', [LaboratoriumController::class, 'reserveByRoom'])->name('laboratorium.reserves');
    Route::post('laboratorium/reserve', [LaboratoriumController::class, 'labReserve'])->name('laboratorium.reserve');

    // Inventory route
    Route::get('inventory', [InventoryReserfController::class, 'index'])->name('inventory');
    Route::get('inventory/reserve', [InventoryReserfController::class, 'getReserve'])->name('inventory.reserves');
    Route::post('inventory/reserve', [InventoryReserfController::class, 'inventoryReserve'])->name('inventory.reserve');
});