<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// Marketing Pages
Volt::route('/', 'pages.marketing.index')->name('home');
Volt::route('/pricing', 'pages.marketing.pricing')->name('pricing');
Volt::route('/features', 'pages.marketing.features')->name('features');
Volt::route('/about', 'pages.marketing.about')->name('about');
Volt::route('/contact', 'pages.marketing.contact')->name('contact');

// Legal Pages
Volt::route('/privacy-policy', 'pages.legal.privacy')->name('privacy');
Volt::route('/terms-of-service', 'pages.legal.terms')->name('terms');
Volt::route('/cookie-policy', 'pages.legal.cookies')->name('cookies');

// Central Admin Dashboard / Customer Portal Placeholder
Volt::route('/dashboard', 'central.dashboard')->middleware(['auth', 'verified'])->name('central.dashboard');
Volt::route('/billing', 'central.billing')->middleware(['auth', 'verified'])->name('central.billing');
Volt::route('/account', 'central.account')->middleware(['auth', 'verified'])->name('central.account');

require __DIR__.'/auth.php';
