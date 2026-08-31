<?php

use App\Http\Controllers\Admin\ClientController as AdminClientController;
use App\Http\Controllers\Admin\ClinicalRecordController as AdminClinicalRecordController;
use App\Http\Controllers\Admin\PetController as AdminPetController;
use App\Http\Controllers\Admin\PetTreatmentController as AdminPetTreatmentController;
use App\Http\Controllers\Admin\ProcedureCatalogController as AdminProcedureCatalogController;
use App\Http\Controllers\Admin\ProcedureController as AdminProcedureController;
use App\Http\Controllers\Admin\ProcedureStatusController as AdminProcedureStatusController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\ServiceRequestController as AdminServiceRequestController;
use App\Http\Controllers\Admin\ServiceStatusController as AdminServiceStatusController;
use App\Http\Controllers\Admin\TreatmentCatalogController as AdminTreatmentCatalogController;
use App\Http\Controllers\Admin\TreatmentController as AdminTreatmentController;
use App\Http\Controllers\Admin\TreatmentSessionController as AdminTreatmentSessionController;
use App\Http\Controllers\ClinicalRecord\ClinicalRecordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Pet\PetController;
use App\Http\Controllers\PetTreatmentController;
use App\Http\Controllers\Service\ServiceController;
use App\Http\Controllers\ServiceRequestController;
use App\Models\Client;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::get('services', [ServiceController::class, 'index'])->name('services.index');
    Route::get('services/{service}', [ServiceController::class, 'show'])->name('services.show');

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
    Route::scopeBindings()->group(function () {
        Route::get('pets/{pet}/service-requests', [ServiceRequestController::class, 'index'])->name('pets.service-requests.index');
        Route::get('pets/{pet}/service-requests/create', [ServiceRequestController::class, 'create'])->name('pets.service-requests.create');
        Route::post('pets/{pet}/service-requests', [ServiceRequestController::class, 'store'])->name('pets.service-requests.store');
        Route::get('pets/{pet}/service-requests/{serviceRequest}', [ServiceRequestController::class, 'show'])->name('pets.service-requests.show');
        Route::get('pets/{pet}/treatments', [PetTreatmentController::class, 'index'])->name('pets.treatments.index');
        Route::get('pets/{pet}/treatments/{petTreatment}', [PetTreatmentController::class, 'show'])->name('pets.treatments.show');
    });

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('services', [AdminServiceController::class, 'index'])->name('services.index');
        Route::get('services/create', [AdminServiceController::class, 'create'])->name('services.create');
        Route::post('services', [AdminServiceController::class, 'store'])->name('services.store');
        Route::get('services/{service}', [AdminServiceController::class, 'show'])->name('services.show');
        Route::get('services/{service}/edit', [AdminServiceController::class, 'edit'])->name('services.edit');
        Route::patch('services/{service}', [AdminServiceController::class, 'update'])->name('services.update');
        Route::patch('services/{service}/status', [AdminServiceStatusController::class, 'update'])
            ->name('services.status.update');
        Route::get('procedures', AdminProcedureCatalogController::class)->name('procedures.index');
        Route::get('treatments', AdminTreatmentCatalogController::class)->name('treatments.index');
        Route::get('service-requests', [AdminServiceRequestController::class, 'index'])->name('service-requests.index');
        Route::get('service-requests/{serviceRequest}', [AdminServiceRequestController::class, 'show'])->name('service-requests.show');
        Route::post('service-requests/{serviceRequest}/resolution', [AdminServiceRequestController::class, 'resolve'])->name('service-requests.resolve');
        Route::patch('service-requests/{serviceRequest}/cancellation', [AdminServiceRequestController::class, 'cancel'])->name('service-requests.cancel');
        Route::patch('treatment-sessions/{session}', [AdminTreatmentSessionController::class, 'update'])->name('treatment-sessions.update');
        Route::scopeBindings()->group(function () {
            Route::get('services/{service}/procedures', [AdminProcedureController::class, 'index'])
                ->name('services.procedures.index');
            Route::get('services/{service}/procedures/create', [AdminProcedureController::class, 'create'])
                ->name('services.procedures.create');
            Route::post('services/{service}/procedures', [AdminProcedureController::class, 'store'])
                ->name('services.procedures.store');
            Route::get('services/{service}/procedures/{procedure}', [AdminProcedureController::class, 'show'])
                ->name('services.procedures.show');
            Route::get('services/{service}/procedures/{procedure}/edit', [AdminProcedureController::class, 'edit'])
                ->name('services.procedures.edit');
            Route::patch('services/{service}/procedures/{procedure}', [AdminProcedureController::class, 'update'])
                ->name('services.procedures.update');
            Route::patch('services/{service}/procedures/{procedure}/status', [AdminProcedureStatusController::class, 'update'])
                ->name('services.procedures.status.update');
            Route::get('services/{service}/treatments', [AdminTreatmentController::class, 'index'])->name('services.treatments.index');
            Route::get('services/{service}/treatments/create', [AdminTreatmentController::class, 'create'])->name('services.treatments.create');
            Route::post('services/{service}/treatments', [AdminTreatmentController::class, 'store'])->name('services.treatments.store');
            Route::get('services/{service}/treatments/{treatment}/edit', [AdminTreatmentController::class, 'edit'])->name('services.treatments.edit');
            Route::patch('services/{service}/treatments/{treatment}', [AdminTreatmentController::class, 'update'])->name('services.treatments.update');
        });
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
        Route::scopeBindings()->group(function () {
            Route::get('pets/{pet}/treatments', [AdminPetTreatmentController::class, 'index'])->name('pets.treatments.index');
            Route::get('pets/{pet}/treatments/create', [AdminPetTreatmentController::class, 'create'])->name('pets.treatments.create');
            Route::post('pets/{pet}/treatments', [AdminPetTreatmentController::class, 'store'])->name('pets.treatments.store');
            Route::get('pets/{pet}/treatments/{petTreatment}', [AdminPetTreatmentController::class, 'show'])->name('pets.treatments.show');
            Route::patch('pets/{pet}/treatments/{petTreatment}', [AdminPetTreatmentController::class, 'update'])->name('pets.treatments.update');
            Route::patch('pets/{pet}/treatments/{petTreatment}/status', [AdminPetTreatmentController::class, 'updateStatus'])->name('pets.treatments.status.update');
        });
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
