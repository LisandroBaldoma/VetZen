<?php

use App\Http\Controllers\Admin\ClientController as AdminClientController;
use App\Http\Controllers\Admin\ClinicalRecordController as AdminClinicalRecordController;
use App\Http\Controllers\Admin\PetController as AdminPetController;
use App\Http\Controllers\ClinicalRecord\ClinicalRecordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Pet\PetController;
use App\Models\Client;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::get('pets', [PetController::class, 'index'])->name('pets.index');
    Route::get('pets/create', [PetController::class, 'create'])->name('pets.create');
    Route::post('pets', [PetController::class, 'store'])->name('pets.store');
    Route::get('pets/{pet}', [PetController::class, 'show'])->name('pets.show');
    Route::get('pets/{pet}/edit', [PetController::class, 'edit'])->name('pets.edit');
    Route::patch('pets/{pet}', [PetController::class, 'update'])->name('pets.update');
    Route::get('pets/{pet}/photo', [PetController::class, 'photo'])->name('pets.photo');
    Route::delete('pets/{pet}/photo', [PetController::class, 'destroyPhoto'])->name('pets.photo.destroy');
    Route::get('pets/{pet}/medical-records', [ClinicalRecordController::class, 'index'])
        ->name('pets.medical-records.index');
    Route::get('pets/{pet}/medical-records/{clinicalRecord}', [ClinicalRecordController::class, 'show'])
        ->name('pets.medical-records.show');

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
        Route::get('pets', [AdminPetController::class, 'index'])->name('pets.index');
        Route::get('pets/create', [AdminPetController::class, 'create'])->name('pets.create');
        Route::post('pets', [AdminPetController::class, 'store'])->name('pets.store');
        Route::get('pets/{pet}', [AdminPetController::class, 'show'])->name('pets.show');
        Route::get('pets/{pet}/edit', [AdminPetController::class, 'edit'])->name('pets.edit');
        Route::patch('pets/{pet}', [AdminPetController::class, 'update'])->name('pets.update');
        Route::get('pets/{pet}/medical-records', [AdminClinicalRecordController::class, 'index'])
            ->name('pets.medical-records.index');
        Route::get('pets/{pet}/medical-records/create', [AdminClinicalRecordController::class, 'create'])
            ->name('pets.medical-records.create');
        Route::post('pets/{pet}/medical-records', [AdminClinicalRecordController::class, 'store'])
            ->name('pets.medical-records.store');
        Route::get('pets/{pet}/medical-records/{clinicalRecord}', [AdminClinicalRecordController::class, 'show'])
            ->name('pets.medical-records.show');
        Route::get('pets/{pet}/medical-records/{clinicalRecord}/edit', [AdminClinicalRecordController::class, 'edit'])
            ->name('pets.medical-records.edit');
        Route::patch('pets/{pet}/medical-records/{clinicalRecord}', [AdminClinicalRecordController::class, 'update'])
            ->name('pets.medical-records.update');
    });
});

require __DIR__.'/settings.php';
