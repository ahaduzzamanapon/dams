<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Frontend Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/our-doctors', [DoctorController::class, 'index'])->name('doctors.index');
Route::get('/our-doctors/{slug}', [DoctorController::class, 'show'])->name('doctors.show');
Route::get('/appointment', [AppointmentController::class, 'showForm'])->name('appointment.form');
Route::post('/appointment', [AppointmentController::class, 'store'])->name('appointment.store');
Route::get('/appointment/slots', [AppointmentController::class, 'getSlots'])->name('appointment.slots');
Route::get('/appointment/doctors', [AppointmentController::class, 'getDoctors'])->name('appointment.doctors');
Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');

/*
|--------------------------------------------------------------------------
| Admin Auth Routes (no middleware)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [Admin\AuthController::class, 'showLogin'])->name('login')->middleware('guest');
    Route::post('/login', [Admin\AuthController::class, 'login'])->name('login.post')->middleware('guest');
    Route::post('/logout', [Admin\AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Admin Protected Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Departments (permissions: department.view / create / edit / delete)
    Route::resource('departments', Admin\DepartmentController::class);

    // Doctors (permissions: doctor.view / create / edit / delete / toggle)
    Route::resource('doctors', Admin\DoctorController::class);
    Route::patch('/doctors/{doctor}/toggle', [Admin\DoctorController::class, 'toggle'])->name('doctors.toggle');

    // Appointments — daily-sheet MUST be before resource to avoid {appointment} wildcard
    Route::get('/appointments/daily-sheet', [Admin\AppointmentController::class, 'dailySheet'])->name('appointments.daily-sheet');
    Route::resource('appointments', Admin\AppointmentController::class)->only(['index', 'show', 'create', 'store']);
    Route::patch('/appointments/{appointment}/confirm', [Admin\AppointmentController::class, 'confirm'])->name('appointments.confirm');
    Route::patch('/appointments/{appointment}/cancel', [Admin\AppointmentController::class, 'cancel'])->name('appointments.cancel');
    Route::patch('/appointments/{appointment}/complete', [Admin\AppointmentController::class, 'complete'])->name('appointments.complete');

    // Services (permissions: service.view / create / edit / delete)
    Route::resource('services', Admin\ServiceController::class);

    // User Management (super-admin only)
    Route::resource('users', Admin\UserController::class);
    Route::patch('/users/{user}/toggle', [Admin\UserController::class, 'toggle'])->name('users.toggle');

    // Role Management (super-admin only)
    Route::resource('roles', Admin\RoleController::class);
});
