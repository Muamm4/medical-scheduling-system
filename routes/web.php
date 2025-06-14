<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

require __DIR__.'/auth.php';

Route::middleware('auth', 'verified')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Rotas para gerenciamento de pacientes
    Route::resource('patients', PatientController::class);
    
    // Rotas para agendamentos
    Route::resource('appointments', AppointmentController::class);
    Route::post('/appointments/find-doctors', [AppointmentController::class, 'findDoctors'])->name('appointments.findDoctors');
    Route::post('/appointments/get-time-slots', [AppointmentController::class, 'getTimeSlots'])->name('appointments.getTimeSlots');
    Route::post('/appointments/get-all-availability', [AppointmentController::class, 'getAllDoctorAvailability'])->name('appointments.getAllAvailability');
    Route::post('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
