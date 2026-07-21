<?php

declare(strict_types=1);

use App\Livewire\Public\Ticket\ViewTicket;
use App\Livewire\Public\Widget\TicketForm;
use App\Livewire\Tenant\Tickets\TicketDetail;
use App\Livewire\Tenant\Tickets\TicketList;
use App\Models\User;
use Illuminate\Http\Middleware\FrameGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
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
    $data = cache()->pull('tenant_login_'.$token);
    if (! $data) {
        abort(401, 'Invalid or expired login token.');
    }
    $user = User::find($data['user_id']);
    if ($user) {
        Auth::login($user, $data['remember'] ?? false);
    }

    return redirect($data['redirect'] ?? '/dashboard');
})->name('tenant.login');
use App\Livewire\Tenant\Dashboard;
use App\Livewire\Tenant\Profile;
use App\Livewire\Tenant\Settings\RolesList;
use App\Livewire\Tenant\Settings\RoleForm;
use App\Livewire\Tenant\Settings\Domains;

Route::get('dashboard', Dashboard::class)->middleware(['auth', 'verified'])->name('tenant.dashboard');
Route::get('profile', Profile::class)->middleware(['auth'])->name('tenant.profile');
Route::middleware(['auth', 'can:view_settings'])->prefix('settings/roles')->name('tenant.settings.roles.')->group(function () {
    Route::get('/', RolesList::class)->name('index');
    Route::get('/create', RoleForm::class)->name('create');
    Route::get('/{role}/edit', RoleForm::class)->name('edit');
});

Route::middleware(['auth', 'can:view_settings'])->prefix('settings/domains')->name('tenant.settings.domains.')->group(function () {
    Route::get('/', Domains::class)->name('index');
});

Route::middleware(['auth'])->prefix('tickets')->name('tenant.tickets.')->group(function () {
    Route::get('/', TicketList::class)->name('index');
    Route::get('/{ticket}', TicketDetail::class)->name('show');
});

// Public Ticket View (Tokenized)
Route::get('/t/{token}', ViewTicket::class)->name('tenant.tickets.public');

// Embeddable Widget
Route::get('/widget/frame', TicketForm::class)->name('tenant.widget.frame')
    ->withoutMiddleware([FrameGuard::class]);
