<?php

use App\Http\Controllers\Client\ClientActivityController;
use App\Http\Controllers\Client\ClientImportExportController;
use App\Http\Controllers\ClientController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Http\Controllers\VerifyEmailController;

Route::get('/', function () {
  return Inertia::render('Welcome', [
    'canLogin' => Route::has('login'),
    'canRegister' => Route::has('register'),
    'laravelVersion' => Application::VERSION,
    'phpVersion' => PHP_VERSION,
  ]);
});

Route::get('client/verify-email/{id}/{token}', [VerifyEmailController::class, 'verify'])
  ->name('client.verify-email')
  ->middleware('signed');

Route::post('client/{client}/verify-email/resend', [VerifyEmailController::class, 'resend'])
  ->name('client.verification.resend')
  ->middleware('auth');

Route::get('verification/success', [VerifyEmailController::class, 'success'])
  ->name('verification.success');

Route::middleware([
  'auth:sanctum',
  config('jetstream.auth_session'),
  'verified',
])->group(function () {

  Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
  })->name('dashboard');

  Route::get('trashed-clients', [ClientController::class, 'trashed'])->name('clients.trashed');
  Route::put('clients/{id}/restore', [ClientController::class, 'restore'])->name('clients.restore');
  Route::delete('clients/{id}/force-delete', [ClientController::class, 'forceDelete'])->name('clients.force-delete');
  Route::get('clients/{client}/activity', [ClientActivityController::class, 'index'])
    ->name('clients.activity');

  // Add this new route
  Route::get(
    'clients/sample',
    [ClientImportExportController::class, 'getSampleFile']
  )->name('clients.sample');

  Route::post(
    'clients/import',
    [ClientImportExportController::class, 'import']
  )->name('clients.import');

  Route::get(
    'clients/export',
    [ClientImportExportController::class, 'export']
  )->name('clients.export');

  Route::post(
    'clients/bulk-action',
    [ClientController::class, 'bulkAction']
  )->name('clients.bulk-action');

  Route::resource(
    'clients',
    \App\Http\Controllers\ClientController::class
  );

});
