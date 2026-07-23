<?php

declare(strict_types=1);

use App\Livewire\Public\Ticket\ViewTicket;
use App\Livewire\Public\Widget\TicketForm;
use App\Livewire\Tenant\Dashboard;
use App\Livewire\Tenant\Profile;
use App\Livewire\Tenant\Settings\Account\AccountIndex;
use App\Livewire\Tenant\Settings\Banned\BannedIndex;
use App\Livewire\Tenant\Settings\Company\CompanyIndex;
use App\Livewire\Tenant\Settings\Departments\DepartmentIndex;
use App\Livewire\Tenant\Settings\Domains;
use App\Livewire\Tenant\Settings\Notifications\NotificationIndex;
use App\Livewire\Tenant\Settings\Personal\ProfileView;
use App\Livewire\Tenant\Settings\RoleForm;
use App\Livewire\Tenant\Settings\RolesList;
use App\Livewire\Tenant\Settings\Shortcuts\ShortcutIndex;
use App\Livewire\Tenant\Settings\Widget\WidgetIndex;
use App\Livewire\Tenant\Tickets\TicketDetail;
use App\Livewire\Tenant\Tickets\TicketHistory;
use App\Livewire\Tenant\Tickets\TicketList;
use App\Livewire\Tenant\Agents\AgentIndex;
use App\Livewire\Tenant\Visitors\VisitorIndex;
use App\Models\User;
use Illuminate\Http\Middleware\FrameGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Routes registered here are loaded by the TenantRouteServiceProvider and
| are scoped to the currently resolved tenant.
|
*/

Route::get('/', fn() => redirect('/dashboard'));
// One-time cross-domain login handoff (central app -> tenant subdomain).
Route::get('/tenant-login', function (Request $request) {
    $token = $request->string('token');
    $payload = cache()->pull("tenant_login_{$token}");
    abort_unless($payload, 401, 'Invalid or expired login token.');
    if ($user = User::find($payload['user_id'])) {
        Auth::login($user, $payload['remember'] ?? false);
    }
    return redirect($payload['redirect'] ?? '/dashboard');
})->name('tenant.login');
Route::get('dashboard', Dashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('tenant.dashboard');
Route::get('profile', Profile::class)
    ->middleware('auth')
    ->name('tenant.profile');
Route::middleware('auth')->group(function () {
    Route::prefix('agents')->name('tenant.agents.')->group(function () {
        Route::get('/', AgentIndex::class)->name('index');
    });
    Route::prefix('visitors')->name('tenant.visitors.')->group(function () {
        Route::get('/', VisitorIndex::class)->name('index');
    });
    Route::prefix('tickets')->name('tenant.tickets.')->group(function () {
        Route::get('/', TicketList::class)->name('index');
        Route::get('/history', TicketHistory::class)->name('history');
        Route::get('/{ticket}', TicketDetail::class)->name('show');
    });
    // Settings pages that any authenticated agent can reach.
    Route::prefix('settings/personal')->name('tenant.settings.personal.')->group(function () {
        Route::get('/', ProfileView::class)->name('index');
    });
    Route::prefix('settings/account')->name('tenant.settings.account.')->group(function () {
        Route::get('/', AccountIndex::class)->name('index');
    });
    Route::prefix('settings/notifications')->name('tenant.settings.notifications.')->group(function () {
        Route::get('/', NotificationIndex::class)->name('index');
    });
    // Settings pages restricted to users who can manage tenant-wide settings.
    Route::middleware('can:view_settings')->group(function () {
        Route::prefix('settings/roles')->name('tenant.settings.roles.')->group(function () {
            Route::get('/', RolesList::class)->name('index');
            Route::get('/create', RoleForm::class)->name('create');
            Route::get('/{role}/edit', RoleForm::class)->name('edit');
        });
        Route::prefix('settings/domains')->name('tenant.settings.domains.')->group(function () {
            Route::get('/', Domains::class)->name('index');
        });
        Route::prefix('settings/departments')->name('tenant.settings.departments.')->group(function () {
            Route::get('/', DepartmentIndex::class)->name('index');
        });
        Route::prefix('settings/shortcuts')->name('tenant.settings.shortcuts.')->group(function () {
            Route::get('/', ShortcutIndex::class)->name('index');
        });
        Route::prefix('settings/company')->name('tenant.settings.company.')->group(function () {
            Route::get('/', CompanyIndex::class)->name('index');
        });
        Route::prefix('settings/banned')->name('tenant.settings.banned.')->group(function () {
            Route::get('/', BannedIndex::class)->name('index');
        });
        Route::prefix('settings/widget')->name('tenant.settings.widget.')->group(function () {
            Route::get('/', WidgetIndex::class)->name('index');
        });
    });
});
// Public, tokenized ticket view — no auth, reached via a shared link.
Route::get('/t/{token}', ViewTicket::class)->name('tenant.tickets.public');
// Embeddable widget iframe — must bypass FrameGuard to load inside a customer's site.
Route::get('/widget/frame', TicketForm::class)
    ->withoutMiddleware([FrameGuard::class])
    ->name('tenant.widget.frame');
