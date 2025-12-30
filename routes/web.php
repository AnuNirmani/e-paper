<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


use App\Http\Controllers\CustomerController;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('customers', CustomerController::class);
});


use App\Http\Controllers\PublicationController;

Route::middleware(['auth'])->group(function () {
    Route::get('/publications', [PublicationController::class, 'index'])->name('publications.index');
    Route::get('/publications/create', [PublicationController::class, 'create'])->name('publications.create');
    Route::post('/publications', [PublicationController::class, 'store'])->name('publications.store');
    Route::get('/publications/{id}/edit', [PublicationController::class, 'edit'])->name('publications.edit');
    Route::put('/publications/{id}', [PublicationController::class, 'update'])->name('publications.update');
    Route::delete('/publications/{id}', [PublicationController::class, 'destroy'])->name('publications.destroy');
});


// use App\Http\Controllers\CustomerController;

// Route::middleware(['auth'])->group(function () {
//     Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
//     Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
//     Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
//     Route::get('/customers/{id}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
//     Route::put('/customers/{id}', [CustomerController::class, 'update'])->name('customers.update');
//     Route::delete('/customers/{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');
// });

require __DIR__.'/auth.php';
