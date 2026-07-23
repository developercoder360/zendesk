<div class="space-y-6 max-w-6xl mx-auto sm:px-6 lg:px-8">
    <nav class="flex items-center text-sm text-muted-foreground mb-4">
        <span class="font-medium text-foreground">Profile</span>
    </nav>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold tracking-tight">Profile</h1>
            <p class="text-sm text-muted-foreground">Manage your account settings and profile.</p>
        </div>
    </div>
    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <div class="max-w-xl">
            <livewire:profile.update-profile-information-form />
        </div>
    </div>
    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <div class="max-w-xl">
            <livewire:profile.update-password-form />
        </div>
    </div>
    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <div class="max-w-xl">
            <livewire:profile.delete-agent-form />
        </div>
    </div>
</div>
