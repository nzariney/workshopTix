<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\WorkshopController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public landing & catalog
Route::get('/', [WorkshopController::class, 'home'])->name('home');
Route::get('/workshops', [WorkshopController::class, 'index'])->name('workshops.index');
Route::get('/workshops/{slug}', [WorkshopController::class, 'show'])->name('workshops.show');
Route::get('/tickets/verify/{code}', [TicketController::class, 'verify'])->name('tickets.verify');

// Authentication (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    Route::post('/quick-login', [AuthController::class, 'quickDemoLogin'])->name('quick.login');
});

// Authenticated User Routes (Participant & Admin)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Workshop Registration
    Route::get('/workshops/{slug}/register', [RegistrationController::class, 'create'])->name('registrations.create');
    Route::post('/workshops/{slug}/register', [RegistrationController::class, 'store'])->name('registrations.store');
    Route::get('/registrations/{code}', [RegistrationController::class, 'show'])->name('registrations.show');

    // Payment Processing
    Route::get('/payments/{code}', [PaymentController::class, 'show'])->name('payments.show');
    Route::post('/payments/{code}/upload-proof', [PaymentController::class, 'uploadProof'])->name('payments.uploadProof');
    Route::post('/payments/{code}/simulate-sandbox', [PaymentController::class, 'simulateSandbox'])->name('payments.simulateSandbox');

    // Digital Tickets
    Route::get('/my-tickets', [TicketController::class, 'myTickets'])->name('tickets.myTickets');
    Route::get('/tickets/{code}', [TicketController::class, 'show'])->name('tickets.show');
    Route::get('/tickets/{code}/print', [TicketController::class, 'printTicket'])->name('tickets.print');

    // Admin & Staff Area
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/dashboard', [AdminController::class, 'dashboard']);

        // Workshops CRUD
        Route::get('/workshops', [AdminController::class, 'workshopsIndex'])->name('workshops.index');
        Route::get('/workshops/create', [AdminController::class, 'workshopCreate'])->name('workshops.create');
        Route::post('/workshops', [AdminController::class, 'workshopStore'])->name('workshops.store');
        Route::get('/workshops/{id}/edit', [AdminController::class, 'workshopEdit'])->name('workshops.edit');
        Route::put('/workshops/{id}', [AdminController::class, 'workshopUpdate'])->name('workshops.update');
        Route::delete('/workshops/{id}', [AdminController::class, 'workshopDestroy'])->name('workshops.destroy');
        Route::patch('/workshops/{id}/toggle-status', [AdminController::class, 'toggleWorkshopStatus'])->name('workshops.toggleStatus');

        // Registrations
        Route::get('/registrations', [AdminController::class, 'registrationsIndex'])->name('registrations.index');
        Route::delete('/registrations/{id}', [AdminController::class, 'registrationDestroy'])->name('registrations.destroy');

        // Payments Verification
        Route::get('/payments', [AdminController::class, 'paymentsIndex'])->name('payments.index');
        Route::post('/payments/{id}/verify', [AdminController::class, 'verifyPayment'])->name('payments.verify');
        Route::post('/payments/{id}/reject', [AdminController::class, 'rejectPayment'])->name('payments.reject');

        // Tickets & QR Check-in
        Route::get('/tickets', [AdminController::class, 'ticketsIndex'])->name('tickets.index');
        Route::post('/tickets/{code}/check-in', [AdminController::class, 'checkInTicket'])->name('tickets.checkIn');
        Route::get('/scanner', [AdminController::class, 'scannerView'])->name('scanner');
        Route::post('/scanner/scan', [AdminController::class, 'scanProcess'])->name('scanner.process');

        // Participants list
        Route::get('/users', [AdminController::class, 'usersIndex'])->name('users.index');
    });
});
