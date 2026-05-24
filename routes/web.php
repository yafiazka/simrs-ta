<?php

use App\Livewire\Auth\Login;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', Login::class)->name('login');

Route::get('/admin/resume-medis/{id}/print', [\App\Http\Controllers\ResumeMedisController::class, 'print'])
    ->name('resume-medis.print')
    ->middleware(\Filament\Http\Middleware\Authenticate::class);

