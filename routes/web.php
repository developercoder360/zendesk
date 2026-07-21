<?php

use App\Livewire\Pages\Marketing\Pricing;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\Marketing\Index;
use App\Livewire\Marketing\Features;
use App\Livewire\Marketing\About;
use App\Livewire\Marketing\Contact;
use App\Livewire\Legal\Privacy;
use App\Livewire\Legal\Terms;
use App\Livewire\Legal\Cookies;
use App\Livewire\Central\Dashboard;
use App\Livewire\Central\Billing;
use App\Livewire\Central\Account;
// Marketing Pages
Route::get('/', Index::class)->name('home');
Route::get('/pricing', Pricing::class)->name('pricing');
Route::get('/features', Features::class)->name('features');
Route::get('/about', About::class)->name('about');
Route::get('/contact', Contact::class)->name('contact');
// Legal Pages
Route::get('/privacy-policy', Privacy::class)->name('privacy');
Route::get('/terms-of-service', Terms::class)->name('terms');
Route::get('/cookie-policy', Cookies::class)->name('cookies');
// Central Admin Dashboard / Customer Portal Placeholder
Route::get('/dashboard', Dashboard::class)->middleware(['auth', 'super.admin', 'verified'])->name('central.dashboard');
Route::get('/billing', Billing::class)->middleware(['auth', 'super.admin', 'verified'])->name('central.billing');
Route::get('/account', Account::class)->middleware(['auth', 'super.admin', 'verified'])->name('central.account');
require __DIR__ . '/auth.php';
