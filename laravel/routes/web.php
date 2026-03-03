<?php

use App\Http\Controllers\Admin\AdminTicketController;
use App\Http\Controllers\Admin\TicketStatusController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::view('/widget', 'widget');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:manager'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/tickets', [AdminTicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/{ticket}', [AdminTicketController::class, 'show'])->name('tickets.show');

        Route::post('/tickets/{ticket}/status', TicketStatusController::class)
            ->name('tickets.status');
        Route::patch('/tickets/{ticket}/status', [AdminTicketController::class, 'updateStatus'])
            ->name('tickets.status');
    });

require __DIR__.'/auth.php';
