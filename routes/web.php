<?php

use App\Livewire\Auth\Login;
use App\Livewire\CategoryManager;
use App\Livewire\Dashboard;
use App\Livewire\ReceiptComponent;
use App\Livewire\RecipientManager;
use App\Livewire\Report;
use App\Livewire\SettingManager;
use App\Livewire\TransactionManager;
use App\Livewire\UserManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/login', Login::class)->name('login')->middleware('guest');
Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();

    return redirect('/login');
})->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', Dashboard::class)->name('home');
    Route::get('/transactions', TransactionManager::class)->name('transactions');
    Route::get('/report', Report::class)->name('report');
    Route::get('/receipt', ReceiptComponent::class)->name('receipt');
    Route::get('/categories', CategoryManager::class)->name('categories');
    Route::get('/recipients', RecipientManager::class)->name('recipients');
    Route::get('/settings', SettingManager::class)->name('settings');
    Route::get('/users', UserManager::class)->name('users');
});
