<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-foreground leading-tight">
            Personal Settings
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
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
    </div>
</div>
