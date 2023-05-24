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

Route::get('/', [\App\Http\Controllers\RegistrationController::class, 'create']);
Route::post('/', [\App\Http\Controllers\RegistrationController::class, 'store']);

Route::get('/betala/{competitor}', [\App\Http\Controllers\PaymentController::class, 'create'])->name('payment');
Route::get('/bekraftelse/{competitor}', [\App\Http\Controllers\RegistrationConfirmationController::class, 'show'])->name('confirmation');
Route::get('/avbryt/{competitor}', [\App\Http\Controllers\CancelController::class, 'create'])->name('cancel');

Route::get('betala/swedbank/{competitor}', [\App\Http\Controllers\SwedbankPaymentMethodController::class, 'index'])->name('swedbank.index');
Route::get('betala/swedbank/{competitor}/complete', [\App\Http\Controllers\SwedbankPaymentMethodController::class, 'complete'])->name('swedbank.complete');
Route::get('betala/swedbank/{competitor}/cancel', [\App\Http\Controllers\SwedbankPaymentMethodController::class, 'cancel'])->name('swedbank.cancel');
Route::get('betala/swedbank/{competitor}/callback', [\App\Http\Controllers\SwedbankPaymentMethodController::class, 'callback'])->name('swedbank.callback');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/test', fn() => new \App\Mail\RegistrationConfirmation(\App\Models\Competitor::latest()->first()));

require __DIR__.'/auth.php';
