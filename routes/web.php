<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CopyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;
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

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/sessions/revoke-others', [SessionController::class, 'destroyOthers'])
        ->name('sessions.revoke-others');

    Route::middleware('can:manage-users')->group(function () {
        Route::resource('users', UserController::class);
    });

    Route::middleware('can:manage-customers')->group(function () {
        Route::post('/customers/activate-all', [CustomerController::class, 'activateAll'])->name('customers.activateAll');
        Route::post('/customers/deactivate-all', [CustomerController::class, 'deactivateAll'])->name('customers.deactivateAll');
        Route::resource('customers', CustomerController::class);
    });

    Route::middleware('can:manage-publications')->group(function () {
        Route::get('/publications', [PublicationController::class, 'index'])->name('publications.index');
        Route::get('/publications/create', [PublicationController::class, 'create'])->name('publications.create');
        Route::post('/publications', [PublicationController::class, 'store'])->name('publications.store');
        Route::get('/publications/{id}', [PublicationController::class, 'show'])->name('publications.show');
        Route::get('/publications/{id}/edit', [PublicationController::class, 'edit'])->name('publications.edit');
        Route::match(['put', 'patch'], '/publications/{id}', [PublicationController::class, 'update'])->name('publications.update');
        Route::delete('/publications/{id}', [PublicationController::class, 'destroy'])->name('publications.destroy');
    });

    Route::middleware('can:manage-copies')->group(function () {
        Route::get('/copies', [CopyController::class, 'index'])->name('copies.index');
        Route::get('/copies/create', [CopyController::class, 'create'])->name('copies.create');
        Route::post('/copies', [CopyController::class, 'store'])->name('copies.store');
        Route::get('/copies/upload', [CopyController::class, 'upload'])->name('copies.upload');
        Route::post('/copies/upload', [CopyController::class, 'uploadStore'])->name('copies.upload.store');
        Route::delete('/copies/{id}', [CopyController::class, 'destroy'])->name('copies.destroy');
    });
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
