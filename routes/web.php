<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('leads', LeadController::class);

    Route::resource('products', ProductController::class);
    Route::post('/products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])
        ->name('products.toggle-status')
        ->middleware('role:admin,manager');

    Route::resource('projects', ProjectController::class);
    Route::post('/projects/{project}/submit', [ProjectController::class, 'submit'])->name('projects.submit');
    
    Route::middleware('role:admin,manager')->group(function () {
        Route::get('/projects-pending', [ProjectController::class, 'pending'])->name('projects.pending');
        Route::post('/projects/{project}/approve', [ProjectController::class, 'approve'])->name('projects.approve');
        Route::post('/projects/{project}/reject', [ProjectController::class, 'reject'])->name('projects.reject');
    });

    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    
    Route::middleware('role:admin,manager')->group(function () {
        Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
        Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
        Route::patch('/customers/{customer}/status', [CustomerController::class, 'updateStatus'])->name('customers.update-status');
    });

    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });
});

require __DIR__.'/auth.php';
