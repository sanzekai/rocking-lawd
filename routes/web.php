<?php

use App\Http\Controllers\Admin\ArtistController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\ScannerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicEventController;
use App\Http\Controllers\UserTicketController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/events/{slug}', [PublicEventController::class, 'show'])->name('events.show');

// Dashboard (Breeze Default)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Protected Participant & User Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/events/{event}/checkout', [OrderController::class, 'store'])->name('orders.checkout');
    Route::get('/my-tickets', [UserTicketController::class, 'index'])->name('my-tickets.index');
    Route::get('/tickets/{ticket_code}/download', [UserTicketController::class, 'downloadPdf'])->name('tickets.download');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin & Organizer Routes
Route::middleware(['auth', 'role:organizer|admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('artists', ArtistController::class);
    Route::resource('events', EventController::class);
    Route::get('/events/{event}/scanner', [ScannerController::class, 'index'])->name('scanner.index');
    Route::post('/events/{event}/scanner/check-in', [ScannerController::class, 'processCheckIn'])->name('scanner.process');
});

require __DIR__.'/auth.php';
