<?php

use App\Http\Controllers\Admin\ClientController as AdminClientController;
use App\Http\Controllers\DashboardController;
use App\Models\Client;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('clients', [AdminClientController::class, 'index'])
            ->can('viewAny', Client::class)
            ->name('clients.index');
        Route::get('clients/{client}', [AdminClientController::class, 'edit'])
            ->can('view', 'client')
            ->name('clients.edit');
        Route::patch('clients/{client}', [AdminClientController::class, 'update'])
            ->can('update', 'client')
            ->name('clients.update');
    });
});

require __DIR__.'/settings.php';
