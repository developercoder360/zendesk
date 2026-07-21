<x-ui.card variant="sectioned" id="danger" class="border-destructive/40 scroll-mt-24">
    <x-ui.card-header>
        <x-ui.card-title class="text-destructive">Danger zone</x-ui.card-title>
        <x-ui.card-description>Irreversible and destructive actions.</x-ui.card-description>
    </x-ui.card-header>
    <x-ui.card-content>
        <div class="border-destructive/30 flex flex-wrap items-center justify-between gap-3 rounded-lg border border-dashed p-4">
            <div>
                <div class="text-sm font-medium">Delete this account</div>
                <div class="text-muted-foreground text-sm">Permanently remove your account and all of its data.</div>
            </div>
            
            <x-ui.alert-dialog>
                <x-ui.alert-dialog-trigger>
                    <x-ui.button variant="destructive">Delete account</x-ui.button>
                </x-ui.alert-dialog-trigger>
                
                <x-ui.alert-dialog-content>
                    <x-ui.alert-dialog-header>
                        <x-ui.alert-dialog-title>Are you absolutely sure?</x-ui.alert-dialog-title>
                        <x-ui.alert-dialog-description>
                            This permanently deletes your account, workspaces and all data. This action cannot be undone.
                            Please enter your password to confirm you would like to permanently delete your account.
                        </x-ui.alert-dialog-description>
                    </x-ui.alert-dialog-header>
                    
                    <form wire:submit="deleteUser">
                        <div class="py-4">
                            <x-ui.input 
                                wire:model="password"
                                id="password"
                                type="password"
                                placeholder="Password"
                                required
                            />
                            @error('password') <span class="mt-2 block text-sm text-destructive">{{ $message }}</span> @enderror
                        </div>
                        
                        <x-ui.alert-dialog-footer>
                            <x-ui.alert-dialog-cancel>Cancel</x-ui.alert-dialog-cancel>
                            <x-ui.button type="submit" variant="destructive" class="ml-2">
                                <span wire:loading.remove wire:target="deleteUser">Delete account</span>
                                <span wire:loading wire:target="deleteUser">Deleting...</span>
                            </x-ui.button>
                        </x-ui.alert-dialog-footer>
                    </form>
                </x-ui.alert-dialog-content>
            </x-ui.alert-dialog>
        </div>
    </x-ui.card-content>
</x-ui.card>
