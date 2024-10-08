<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('splade')->group(function () {
  // Registers routes to support the interactive components...
  Route::spladeWithVueBridge();

  // Registers routes to support password confirmation in Form and Link components...
  Route::spladePasswordConfirmation();

  // Registers routes to support Table Bulk Actions and Exports...
  Route::spladeTable();

  // Registers routes to support async File Uploads with Filepond...
  Route::spladeUploads();

  Route::get('/', function () {
    return view('welcome');
  });

  Route::middleware('auth')->group(function () {

    Route::get('/', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Settings
    Route::get('/settings', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');

    Route::patch('/settings/u/{settings}', [\App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');

    Route::group(['prefix' => 'invoices'], function () {

      Route::get(
        '/',
        '\App\Http\Controllers\InvoiceController@index'
      )->name('invoices.index');

      Route::get(
        '/p/{invoice:iid}',
        \App\Http\Controllers\DownloadController::class
      )->name('invoices.print');

      Route::get(
        '/s/{invoice:iid}',
        '\App\Http\Controllers\InvoiceController@show'
      )->name('invoices.show');

      Route::get(
        '/u/{invoice:iid}',
        '\App\Http\Controllers\InvoiceController@edit'
      )->name('invoices.edit');

      Route::get(
        '/c',
        '\App\Http\Controllers\InvoiceController@create'
      )->name('invoices.create');

      Route::post(
        '/',
        '\App\Http\Controllers\InvoiceController@store'
      )->name('invoices.store');

      Route::patch(
        '/p/{invoice:iid}',
        '\App\Http\Controllers\InvoiceController@update'
      )->name('invoices.update');

      Route::delete(
        '/d/{invoice:iid}',
        '\App\Http\Controllers\InvoiceController@destroy'
      )->name('invoices.destroy');

    });

  });

  require __DIR__ . '/auth.php';
});
