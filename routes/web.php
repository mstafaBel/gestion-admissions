<?php

use App\Livewire\Actions\Logout;
use App\Livewire\Admin\Batiments;
use App\Livewire\Admin\Chambres;
use App\Livewire\Admin\Etablissements;
use App\Livewire\Admin\Etages;
use App\Livewire\Admin\Lits;
use App\Livewire\Admin\Services;
use App\Livewire\Admin\Users;
use App\Livewire\Secretaire\Admissions;
use App\Livewire\Secretaire\Patients;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::get('dashboard', function () {
    $user = auth()->user();
    if ($user?->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    if ($user?->isSecretaire()) {
        return redirect()->route('secretaire.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::view('/dashboard', 'admin.dashboard')->name('dashboard');

    Route::get('/utilisateurs', Users::class)->name('users');

    Route::prefix('parametrage')->group(function () {
        Route::get('/etablissements', Etablissements::class)->name('etablissements');
        Route::get('/batiments', Batiments::class)->name('batiments');
        Route::get('/etages', Etages::class)->name('etages');
        Route::get('/services', Services::class)->name('services');
        Route::get('/chambres', Chambres::class)->name('chambres');
        Route::get('/lits', Lits::class)->name('lits');
    });
});

Route::middleware(['auth', 'secretaire'])->prefix('secretaire')->name('secretaire.')->group(function () {
    Route::view('/dashboard', 'secretaire.dashboard')->name('dashboard');
    Route::get('/patients', Patients::class)->name('patients');
    Route::get('/admissions', Admissions::class)->name('admissions');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::post('logout', function (Logout $logout) {
    $logout();
    return redirect('/');
})->middleware('auth')->name('logout');

require __DIR__.'/auth.php';
