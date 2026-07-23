<div>
    <nav class="flex items-center text-sm text-muted-foreground mb-4">
        <span class="hover:text-foreground transition-colors cursor-pointer">Settings</span>
        <span class="inline-flex items-center mx-1.5"><x-lucide-chevron-right class="size-3.5" /></span>
        <span class="font-medium text-foreground">Domains</span>
    </nav>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold tracking-tight">Domains</h1>
            <p class="text-sm text-muted-foreground">Manage your workspace domains and subdomains.</p>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900">Current Domains</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        @if(is_null($limit))
                            {{ $domains->count() }} subdomains used (Unlimited)
                        @else
                            {{ $domains->count() }} of {{ $limit }} subdomains used
                        @endif
                    </p>

                    <ul class="mt-4 border border-gray-200 rounded-md divide-y divide-gray-200">
                        @foreach($domains as $domain)
                            <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                <div class="w-0 flex-1 flex items-center">
                                    <span class="ml-2 flex-1 w-0 truncate">
                                        {{ $domain->domain }}
                                    </span>
                                </div>
                                <div class="ml-4 flex-shrink-0">
                                    @if($domain->is_primary)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Primary
                                        </span>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900">Add Subdomain</h3>
                    
                    @if (session('status'))
                        <div class="mt-4 p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if($canAdd)
                        <form wire:submit="addDomain" class="mt-4 space-y-6">
                            <div>
                                <x-input-label for="newSubdomain" :value="__('New Subdomain')" />
                                <div class="flex items-center mt-1">
                                    <x-text-input id="newSubdomain" name="newSubdomain" type="text" class="block w-full max-w-md" wire:model="newSubdomain" required />
                                    <span class="ml-2 text-gray-500">.{{ config('tenancy.central_domains')[0] ?? 'zendesk.test' }}</span>
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('newSubdomain')" />
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Add Subdomain') }}</x-primary-button>
                            </div>
                        </form>
                    @else
                        <div class="mt-4 p-4 rounded-md bg-yellow-50 border border-yellow-200">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">
                                        Subdomain limit reached
                                    </h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <p>
                                            You have reached the maximum number of subdomains allowed on your current package. Upgrade your package to add more subdomains.
                                        </p>
                                    </div>
                                    <div class="mt-4">
                                        <div class="-mx-2 -my-1.5 flex">
                                            <a href="{{ route('tenant.dashboard') }}" class="px-2 py-1.5 rounded-md text-sm font-medium text-yellow-800 hover:bg-yellow-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-yellow-50 focus:ring-yellow-600">
                                                Upgrade Package
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
