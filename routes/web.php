<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\PlanillaIngresoPdfController;
use App\Http\Controllers\PlanillaZafraPdfController; // Añade esta línea

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

});

Route::get('/planilla-ingreso/{id}/pdf', [PlanillaIngresoPdfController::class, 'show'])
    ->name('planilla.pdf');
// Nueva ruta para Planilla Zafra (sin middleware):
Route::get('/planilla-zafra/{id}/pdf', [PlanillaZafraPdfController::class, 'show'])
    ->name('planilla-zafra.pdf');

require __DIR__.'/auth.php';
