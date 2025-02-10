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


  Route::post('media', [\App\Http\Controllers\MediaController::class, 'store'])->name('media.store');
  Route::get('media/{media}', [\App\Http\Controllers\MediaController::class, 'show'])->name('media.show');
  Route::post('media/validate', [\App\Http\Controllers\MediaController::class, 'validateFile'])->name('media.validate');
  Route::patch('media/{media}', [\App\Http\Controllers\MediaController::class, 'update'])->name('media.update');
  Route::delete('media/{media}', [\App\Http\Controllers\MediaController::class, 'destroy'])->name('media.destroy');

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

// Client Portal Routes
Route::prefix('client-portal')->name('client-portal.')->group(function () {
  // Guest routes
  Route::middleware('guest:client')->group(function () {
    Route::get('login', [\App\Http\Controllers\Client\Portal\ClientAuthController::class, 'showLogin'])->name('login');
    Route::post('login', [\App\Http\Controllers\Client\Portal\ClientAuthController::class, 'sendLoginLink'])->name('login.send-link');
    Route::get('login/{token}', [\App\Http\Controllers\Client\Portal\ClientAuthController::class, 'login'])->name('login.token');
  });

  // Authenticated routes
  Route::middleware('auth:client')->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\Client\Portal\DashboardController::class, 'index'])->name('dashboard');
    Route::post('logout', [\App\Http\Controllers\Client\Portal\ClientAuthController::class, 'logout'])->name('logout');

    // Profile routes
    Route::get('profile', [\App\Http\Controllers\Client\Portal\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [\App\Http\Controllers\Client\Portal\ProfileController::class, 'update'])->name('profile.update');

    // Invoice routes
    Route::get('invoices', [\App\Http\Controllers\Client\Portal\InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('invoices/{invoice}', [\App\Http\Controllers\Client\Portal\InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('invoices/{invoice}/download', [\App\Http\Controllers\Client\Portal\InvoiceController::class, 'download'])->name('invoices.download');

    // Payment routes
    Route::get('payments', [\App\Http\Controllers\Client\Portal\PaymentController::class, 'index'])->name('payments.index');
    Route::get('payments/create', [\App\Http\Controllers\Client\Portal\PaymentController::class, 'create'])->name('payments.create');
    Route::post('payments', [\App\Http\Controllers\Client\Portal\PaymentController::class, 'store'])->name('payments.store');
    Route::get('payments/callback', [\App\Http\Controllers\Client\Portal\PaymentController::class, 'callback'])->name('payments.callback');
    Route::get('payments/complete', [\App\Http\Controllers\Client\Portal\PaymentController::class, 'complete'])->name('payments.complete');

    // Support request routes
    Route::resource('support-requests', \App\Http\Controllers\Client\Portal\SupportRequestController::class);

    // Statements
    Route::get('statements', [\App\Http\Controllers\Client\Portal\StatementController::class, 'index'])->name('statements.index');
    Route::get('statements/download', [\App\Http\Controllers\Client\Portal\StatementController::class, 'download'])->name('statements.download');
  });
});
