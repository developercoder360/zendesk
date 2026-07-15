# Zendesk Multi-Tenant Clone

This is a modern, enterprise-ready SaaS Multi-Tenant application built with Laravel 12, Livewire 3, Tailwind CSS v4, and PostgreSQL. It uses **Single Database Tenancy** via the `stancl/tenancy` package, ensuring strict data isolation per tenant using Eloquent Global Scopes.

## Prerequisites

- PHP >= 8.3
- Composer
- Node.js & npm (v20+)
- PostgreSQL
- Local environment (e.g., Laragon, Laravel Herd, or Valet)

## Local Setup Instructions

Follow these steps to get the application running on your local machine.

### 1. Clone the Repository
```bash
git clone https://github.com/developercoder360/zendesk.git
cd zendesk
```

### 2. Install Dependencies
Install the required PHP and Node.js packages:
```bash
composer install
npm install
```

### 3. Environment Configuration
Copy the `.env.example` file to create your own `.env` configuration:
```bash
cp .env.example .env
```
Generate your application key:
```bash
php artisan key:generate
```

Open your `.env` file and configure your database connection to point to your PostgreSQL instance:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=zendesk
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

### 4. Run Migrations
Run the central database migrations. Because this uses Single Database Tenancy, this will also migrate all the required columns (like `tenant_id`) into your core tables:
```bash
php artisan migrate
```

### 5. Build Frontend Assets
Compile the Tailwind CSS v4 and Livewire components using Vite:
```bash
npm run build
# Or run the dev server during development:
# npm run dev
```

### 6. Create Your First Tenant
You can easily create a new tenant using Laravel Tinker:
```bash
php artisan tinker
```
Then, inside Tinker, run the following to create a tenant with the domain `tenant1.zendesk.test`:
```php
$tenant = App\Models\Tenant::create(['id' => 'tenant1']);
$tenant->domains()->create(['domain' => 'tenant1.zendesk.test']);
```

### 7. Configure DNS (Windows `hosts` file)
By default, Windows does not support wildcard DNS resolution for local domains. To visit your tenant in the browser, you must map the subdomain in your `hosts` file.

1. Open Notepad **as Administrator**.
2. Open `C:\Windows\System32\drivers\etc\hosts`.
3. Add the following lines to the bottom:
```text
127.0.0.1 zendesk.test
127.0.0.1 tenant1.zendesk.test
```
4. Save the file.

### 8. Access the Application
- **Central Landing Page**: Visit `http://zendesk.test`
- **Tenant Registration**: Visit `http://tenant1.zendesk.test/register`
- **Tenant Login**: Visit `http://tenant1.zendesk.test/login`
- **Tenant Dashboard**: Visit `http://tenant1.zendesk.test/dashboard`

## Architecture Highlights
- **Tenant Isolation**: Uses `BelongsToTenant` trait on models (like `User`) to automatically scope queries via a `WHERE tenant_id = ?` clause.
- **Session Scoping**: Cross-tenant session leaking is prevented natively via domain-bound cookies (`SESSION_DOMAIN=null`).
- **Auth Separation**: Authentication routes are physically removed from the central domain (`routes/web.php`) and restricted exclusively to tenants (`routes/tenant.php`).
- **Middleware Safety**: Uses `InitializeTenancyByDomain` and `PreventAccessFromCentralDomains` to enforce strict boundaries.
