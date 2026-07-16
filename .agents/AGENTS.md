# Livewire Architecture Rules

1. NO SINGLE-FILE COMPONENTS: Do NOT use Laravel Volt or any Single-File Component architecture under any circumstances. 
2. ALWAYS USE CLASS-BASED COMPONENTS: Every Livewire component you create or suggest must be a traditional, class-based component.
3. TWO-FILE STRUCTURE: Every component must consist of exactly two separate files:
   - A PHP Class file containing all state, variables, and logic.
   - A Blade View file containing strictly HTML and Blade directives.
4. NO INLINE LOGIC: Never place PHP logic blocks (`<?php ... ?>`) at the top of Blade files to manage Livewire state.
5. FEATURE-BASED FOLDER STRUCTURE: Organize components into logical sub-folders based on their domain context.
   - PHP Classes must follow proper sub-namespaces (e.g., `app/Livewire/Tenant/Employee/BreakTracker.php` with namespace `App\Livewire\Tenant\Employee`).
   - Blade Views must be placed in corresponding sub-directories (e.g., `resources/views/livewire/tenant/employee/break-tracker.blade.php`).
   - Use correct dot notation syntax for rendering (e.g., `<livewire:tenant.employee.break-tracker />`).
6. SUBDOMAIN MULTI-TENANCY AWARENESS (`stancl/tenancy` ^3.10): This project uses subdomain-based multi-tenancy.
   - You MUST clearly distinguish between "Central App" logic (main domain) and "Tenant App" logic (subdomains).
   - When generating components, models, or routes, ask me or explicitly state whether it belongs to the Central connection/routes or the Tenant connection/routes.
   - Keep in mind Livewire's specific routing and asset behaviors in a `stancl/tenancy` environment (e.g., Livewire update routes within tenant middleware).
