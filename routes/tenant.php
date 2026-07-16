<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Livewire\Volt\Volt;
/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::get('/', function () {
    return redirect('/dashboard');
});
Route::get('/tenant-login', function (Request $request) {
    $token = $request->get('token');
    $data = cache()->pull('tenant_login_' . $token);
    if (! $data) {
        abort(401, 'Invalid or expired login token.');
    }
    $user = User::find($data['user_id']);
    if ($user) {
        Auth::login($user, $data['remember'] ?? false);
    }
    return redirect($data['redirect'] ?? '/dashboard');
})->name('tenant.login');
Volt::route('dashboard', 'tenant.dashboard')->middleware(['auth', 'verified'])->name('tenant.dashboard');
Volt::route('profile', 'tenant.profile')->middleware(['auth'])->name('tenant.profile');
Route::middleware(['auth', 'can:view_settings'])->prefix('settings/roles')->name('tenant.settings.roles.')->group(function () {
    Volt::route('/', 'tenant.settings.roles-list')->name('index');
    Volt::route('/create', 'tenant.settings.role-form')->name('create');
    Volt::route('/{role}/edit', 'tenant.settings.role-form')->name('edit');
});

Route::middleware(['auth'])->prefix('tickets')->name('tenant.tickets.')->group(function () {
    Route::get('/', \App\Livewire\Tenant\Tickets\TicketList::class)->name('index');
    Route::get('/{ticket}', \App\Livewire\Tenant\Tickets\TicketDetail::class)->name('show');
});

// Public Ticket View (Tokenized)
Route::get('/t/{token}', \App\Livewire\Public\Ticket\ViewTicket::class)->name('tenant.tickets.public');

// Embeddable Widget
Route::get('/widget/frame', \App\Livewire\Public\Widget\TicketForm::class)->name('tenant.widget.frame')
    ->withoutMiddleware([\Illuminate\Http\Middleware\FrameGuard::class]);
