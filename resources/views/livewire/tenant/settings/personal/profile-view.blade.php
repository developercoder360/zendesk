<div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
    <nav class="flex items-center text-sm text-muted-foreground mb-4">
        <span class="hover:text-foreground transition-colors cursor-pointer">Settings</span>
        <span class="inline-flex items-center mx-1.5"><x-lucide-chevron-right class="size-3.5" /></span>
        <span class="font-medium text-foreground">Personal Profile</span>
    </nav>
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight">Personal Profile</h1>
            <p class="text-sm text-muted-foreground">View and manage your personal profile settings.</p>
        </div>
    </div>
    <x-ui.card>
        <div class="p-4 sm:p-8">
            <div class="max-w-xl">
                <livewire:profile.update-profile-information-form />
            </div>
        </div>
    </x-ui.card>
    <x-ui.card>
        <div class="p-4 sm:p-8">
            <div class="max-w-xl">
                <livewire:profile.update-password-form />
            </div>
        </div>
    </x-ui.card>
</div>
