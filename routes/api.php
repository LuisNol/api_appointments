<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\Rol\RolesController;
use App\Http\Controllers\Patient\PatientController;
use App\Http\Controllers\Admin\Staff\StaffsController;
use App\Http\Controllers\Admin\Doctor\DoctorsController;
use App\Http\Controllers\Dashboard\DashboardKpiController;
use App\Http\Controllers\Admin\Doctor\SpecialityController;
use App\Http\Controllers\Appointment\AppointmentController;
use App\Http\Controllers\Appointment\AppointmentPayController;
use App\Http\Controllers\Appointment\AppointmentAttentionController;

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

// Ruta básica de usuario autenticado
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Grupo de rutas de autenticación
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
    Route::post('/me', [AuthController::class, 'me'])->name('me');
    Route::post('/list', [AuthController::class, 'list']);
    Route::post('/reg', [AuthController::class, 'reg']);
});

// Grupo principal con middleware de autenticación
Route::middleware('auth:api')->group(function () {

    // Rutas de roles
    Route::resource('roles', RolesController::class);

    // Rutas de staff
    Route::prefix('staffs')->group(function () {
        Route::get('config', [StaffsController::class, 'config']);
        Route::post('{id}', [StaffsController::class, 'update']);
        Route::resource('/', StaffsController::class)->parameters(['' => 'staffs']);
    });

    // Rutas de especialidades
    Route::resource('specialities', SpecialityController::class);

    // Rutas de doctores
    Route::prefix('doctors')->group(function () {
        Route::get('profile/{id}', [DoctorsController::class, 'profile']);
        Route::get('config', [DoctorsController::class, 'config']);
        Route::post('{id}', [DoctorsController::class, 'update']);
        Route::resource('/', DoctorsController::class)->parameters(['' => 'doctors']);
    });

    // Rutas de pacientes
    Route::prefix('patients')->group(function () {
        Route::get('profile/{id}', [PatientController::class, 'profile']);
        Route::post('{id}', [PatientController::class, 'update']);
        Route::resource('/', PatientController::class)->parameters(['' => 'patients']);
    });

    // Rutas de citas
    Route::prefix('appointments')->group(function () {
        Route::get('config', [AppointmentController::class, 'config']);
        Route::get('patient', [AppointmentController::class, 'query_patient']);
        Route::post('filter', [AppointmentController::class, 'filter']);
        Route::post('calendar', [AppointmentController::class, 'calendar']);
        Route::resource('/', AppointmentController::class)->parameters(['' => 'appointments']);
    });

    // Rutas de pagos y atenciones de citas
    Route::resource('appointment-pay', AppointmentPayController::class);
    Route::resource('appointment-attention', AppointmentAttentionController::class);

    // Rutas de dashboard
    Route::prefix('dashboard')->group(function () {
        Route::post('admin', [DashboardKpiController::class, 'dashboard_admin']);
        Route::post('admin-year', [DashboardKpiController::class, 'dashboard_admin_year']);
        Route::post('doctor', [DashboardKpiController::class, 'dashboard_doctor']);
        Route::get('config', [DashboardKpiController::class, 'config']);
        Route::post('doctor-year', [DashboardKpiController::class, 'dashboard_doctor_year']);
    });
});
