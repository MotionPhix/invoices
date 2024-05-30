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
    Route::get('/dashboard', function () {
      return view('dashboard');
    })->middleware(['verified'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route::resource('invoices', \App\Http\Controllers\InvoiceController::class);
    Route::group(['prefix' => 'invoices'], function () {

      Route::get(
        '/',
        '\App\Http\Controllers\InvoiceController@index'
      )->name('invoices.index');

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
