# Zendesk Clone - Multi-Tenant Architecture & Auth

## Architecture Overview
This application uses a Single-Database multi-tenancy approach, implemented via `stancl/tenancy` v3.
- All tenants share the same PostgreSQL database.
- Tenant isolation is enforced at the query level using `Stancl\Tenancy\Database\Concerns\BelongsToTenant` (which automatically applies the `TenantScope`).
- Domain routing: The central app runs on `localhost:8000`. Each tenant runs on a subdomain (e.g., `acme.localhost:8000`).

## Security & Authentication
- **Central Auth**: Authentication is built on Laravel Breeze. Users can only log in at `localhost:8000/login`. No login pages exist on tenant subdomains.
- **Open Redirect Prevention**: After a user authenticates centrally, they are directed to their tenant domain. We don't use raw `?redirect=` query parameters. Instead, a short-lived token (cached server-side) containing the target URL and user ID is generated. The user is redirected to `{tenant}.localhost:8000/tenant-login?token={token}`, which verifies the server-side cache and authenticates the user into the tenant context securely.
- **Enumeration Prevention**: The registration flow (`companySlug`) validates domains in real time. We use an aggressive rate limit on the payment/processing endpoint to prevent brute-force tenant enumeration.

## Tenant Provisioning (Domain Events)
When a tenant registers, a mock payment is charged via `PaymentGatewayContract`.
Upon a `PaymentResult::successful()`, the `RegisterTenant` action inserts records using a DB transaction.
Once successful, `TenantRegistered` event is dispatched. The `SeedTenantDefaults` listener provisions:
1. Roles and Permissions (Admin, Agent) via Spatie.
2. Initial Departments (e.g. General Support).
3. Initial Ticket Statuses (Open, Pending, Resolved).
4. Initial Email Templates.

## Modifying Data Models
When creating a new Model that belongs to a tenant:
1. Add a `uuid('tenant_id')` foreign key to its migration.
2. Ensure the Model uses the `Stancl\Tenancy\Database\Concerns\BelongsToTenant` trait.
3. No need to manually scope `->where('tenant_id', ...)` in controllers — the global scope does this automatically.

## Running Tests
Pest is installed for feature tests:
`php vendor/bin/pest tests/Feature/Tenancy`
Tests verify that tenant data is isolated correctly.
