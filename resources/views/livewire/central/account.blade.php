<div class="mx-auto max-w-5xl px-6 py-10">
    <h1 class="text-3xl font-bold tracking-tight">Settings</h1>
    <p class="text-muted-foreground mt-1">Manage your profile, preferences and account.</p>

    <div class="mt-8 grid gap-10 lg:grid-cols-[180px_1fr]">
        {{-- Settings nav --}}
        <aside class="lg:sticky lg:top-24 lg:self-start">
            <nav class="flex gap-1 overflow-x-auto text-sm lg:flex-col">
                <a href="#profile" class="rounded-md px-3 py-2 font-medium whitespace-nowrap transition-colors bg-accent text-accent-foreground">Profile</a>
                <a href="#security" class="rounded-md px-3 py-2 font-medium whitespace-nowrap transition-colors text-muted-foreground hover:text-foreground hover:bg-accent/60">Security</a>
                <a href="#danger" class="rounded-md px-3 py-2 font-medium whitespace-nowrap transition-colors text-muted-foreground hover:text-foreground hover:bg-accent/60">Danger zone</a>
            </nav>
        </aside>

        <div class="space-y-8">
            <livewire:central.account.update-profile />
            
            <livewire:central.account.update-password />
            
            <livewire:central.account.delete-user />
        </div>
    </div>
</div>
