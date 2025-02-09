<?php

use App\Http\Controllers\Client\ClientActivityController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientImportExportController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
  return Inertia::render('Welcome', [
    'canLogin' => Route::has('login'),
    'canRegister' => Route::has('register'),
    'laravelVersion' => Application::VERSION,
    'phpVersion' => PHP_VERSION,
  ]);
});

Route::middleware([
  'auth:sanctum',
  config('jetstream.auth_session'),
  'verified',
])->group(function () {

  Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
  })->name('dashboard');

  Route::resource(
    'clients',
    \App\Http\Controllers\ClientController::class
  );

  Route::get('trashed-clients', [ClientController::class, 'trashed'])->name('clients.trashed');
  Route::put('clients/{id}/restore', [ClientController::class, 'restore'])->name('clients.restore');
  Route::delete('clients/{id}/force-delete', [ClientController::class, 'forceDelete'])->name('clients.force-delete');
  Route::get('clients/{client}/activity', [ClientActivityController::class, 'index'])
    ->name('clients.activity');
  Route::post('clients/import', [ClientImportExportController::class, 'import'])->name('clients.import');
  Route::get('clients/export', [ClientImportExportController::class, 'export'])->name('clients.export');

});
