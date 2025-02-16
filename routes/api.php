<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\LaboranController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\ResearchController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\ReserveRuleController;
use App\Http\Controllers\umum\LandingpageController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\umum\LaboratoriumController;
use App\Http\Controllers\LaboratoriumSupportController;
use App\Http\Controllers\umum\InventoryReserfController;

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

Route::get('/', function () {
    return response()->json(["message" => "Server Running"]);
});

// Routes tanpa middleware
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('landingpage', [LandingpageController::class, 'index'])->name('landingpage');

Route::get('check-auth', [AuthController::class, 'checkAuth'])->name('checkAuth');
Route::post('update/profil', [AuthController::class, 'updateProfil'])->name('updateProfil');

Route::get('inventory/reserve/{id}', [InventoryReserfController::class, 'reservebyId'])->name('inventoryReserves.detail');


Route::group([], function(){
    Route::get('dashboard/countLab', [DashboardController::class, 'countLab'])->name('dashboard.count-lab');

    Route::get('laboratorium/all-reserve', [LaboratoriumController::class, 'allReserve'])->name('laboratorium.allReserve');
    Route::get('laboratorium', [LaboratoriumController::class, 'index'])->name('laboratorium');
    Route::get('laboratorium/reserve/search/{query?}', [LaboratoriumController::class, 'searchReservations']);
    Route::post('laboratorium/reserve', [LaboratoriumController::class, 'labReserve'])->name('laboratorium.reserve');
    Route::get('laboratorium/reserve/{id}', [LaboratoriumController::class, 'reservebyId'])->name('laboratorium.reservebyId');
    Route::get('laboratorium/schedule/{id}', [LaboratoriumController::class, 'getScheduleByRoom'])->name('laboratorium.schedule');
    Route::get('laboratorium/{id}/reserve', [LaboratoriumController::class, 'reserveByRoom'])->name('laboratorium.reserves');
    Route::get('laboratorium/{id}', [LaboratoriumController::class, 'detail'])->name('laboratorium.detail');



    Route::get('inventory', [InventoryReserfController::class, 'index'])->name('inventory');
    Route::get('inventory/reserve', [InventoryReserfController::class, 'getReserve'])->name('inventory.reserves');

    //student
    Route::apiResource('studentCount', StudentController::class);

    //Dosen
    Route::apiResource('lecturerCount', LecturerController::class);

    //research
    Route::apiResource('researchCount', ResearchController::class);

    //laboran
    Route::apiResource('laboranCount', LaboranController::class);
    Route::apiResource('laboratorium-support', LaboratoriumSupportController::class);
});

// Laboratorium


Route::middleware(['auth:sanctum', 'verified'])->group(function () {
   // Dashboard Route
    Route::get('dashboard/countInventory', [DashboardController::class, 'countInvent'])->name('dashboard.count-inventory');
    Route::get('dashboard/schedules', [DashboardController::class, 'getSchedule'])->name('dashboard.schedules');
    Route::get('dashboard/labReserve', [DashboardController::class, 'getLabReserve'])->name('dashboard.labReserve');
    Route::get('dashboard/inventoryReserve', [DashboardController::class, 'getInventoryReserve'])->name('dashboard.inventoryReserve');// Profile route
    Route::put('laboratorium/reserve/{id}/approve', [LaboratoriumController::class, 'approve'])->name('laboratorium.reserve.approve');
    Route::put('laboratorium/reserve/{id}/reject', [LaboratoriumController::class, 'reject'])->name('laboratorium.reserve.reject');
    Route::put('inventory/reserve/{id}/approve', [InventoryReserfController::class, 'approve'])->name('inventory.reserve.approve');
    Route::put('inventory/reserve/{id}/reject', [InventoryReserfController::class, 'reject'])->name('inventory.reserve.reject');

    Route::apiResource('rooms', RoomController::class);
    Route::apiResource('inventories', InventoryController::class);
    Route::apiResource('subjects', SubjectController::class);

    Route::get('schedules', [JadwalController::class, 'getSchedule'])->name('schedule');
    Route::get('schedules/{id}', [JadwalController::class, 'getScheduleByRoom'])->name('schedule.detail');
    Route::get('schedule/{id}', [JadwalController::class, 'show'])->name('schedule.detail');
    Route::post('schedules/reserve/{id}', [JadwalController::class, 'update'])->name('schedule.edit');
    Route::post('schedules/reserve', [JadwalController::class, 'store'])->name('schedule.reserve');
    Route::post('inventory/reserve', [InventoryReserfController::class, 'inventoryReserve'])->name('inventory.reserve');
});

// Rules
Route::apiResource('rules', ReserveRuleController::class);

//support



Route::post('/upload-foto', [FileUploadController::class, 'uploadFoto']);
// Routes 'auth:sanctum' middleware
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/email/verification-link', [EmailVerificationController::class, 'getVerificationLink']);
    // Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail']);
});

// Routes 'auth:sanctum' dan 'verified' middleware
Route::middleware(['auth:sanctum', 'verified', 'kaleb'])->group(function () {
    // Reserve route
    Route::get('reserve/laboratorium', [PeminjamanController::class, 'getLabReserve'])->name('reserve.laboratorium');
    Route::patch('reserve/laboratorium/{id}', [PeminjamanController::class, 'changeStatusRoom'])->name('reserve.lab.update');
    Route::get('reserve/inventory', [PeminjamanController::class, 'getInventoryReserve'])->name('reserve.inventory');
    Route::patch('reserve/inventory/{id}', [PeminjamanController::class, 'changeStatusInventory'])->name('reserve.invent.update');
});



Route::middleware([])->group(function () {
    Route::get('/current', [AuthController::class, 'current']);

    // Dashboard route
    
    Route::get('users', [ProfileController::class, 'getProfile'])->name('users.profile');
    Route::patch('users', [ProfileController::class, 'update'])->name('users.update');

    // Laboratorium route
   
    // Inventory route
    // Route::get('inventory/reserve', [InventoryReserfController::class, 'getReserve'])->name('inventory.reserves');
    
});
    // Route::get('laboratorium/all-reserve', [LaboratoriumController::class, 'allReserve'])->name('laboratorium.allReserve');
