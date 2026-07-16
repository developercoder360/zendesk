<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased" data-base="olive" data-theme="emerald" data-radius="1" data-input-style="inset" data-font="inter" data-font-heading="inter" data-shadow="xl" data-spacing="compact" data-tracking="tight">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Zendesk SaaS' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireScriptConfig

    <!-- Dark Mode Script -->
    <script>
        (function () {
            const get = (k, d) => localStorage.getItem('theme:' + k) || d;
            const mode = get('mode', 'system');
            const dark = mode === 'dark' || (mode === 'system' && matchMedia('(prefers-color-scheme: dark)').matches);
            document.documentElement.classList.toggle('dark', dark);
        })();
    </script>
</head>
<body x-data class="min-h-screen bg-muted/30 text-foreground font-sans selection:bg-primary selection:text-primary-foreground">
    <div class="flex min-h-screen">
        {{-- Sidebar (desktop) --}}
        <aside class="bg-card hidden w-64 shrink-0 flex-col border-r lg:flex">
            @livewire('central.layout.sidebar')
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            {{-- Topbar --}}
            <header class="bg-background/80 supports-[backdrop-filter]:bg-background/60 sticky top-0 z-30 flex h-16 items-center gap-3 border-b px-4 backdrop-blur-xl lg:px-6">
                <x-ui.sheet>
                    <x-ui.sheet-trigger class="lg:hidden">
                        <x-ui.button variant="outline" size="icon" aria-label="Menu"><x-lucide-menu class="size-4" /></x-ui.button>
                    </x-ui.sheet-trigger>
                    <x-ui.sheet-content side="left" class="w-64 p-0">
                        <div class="flex h-full flex-col">
                            @livewire('central.layout.sidebar')
                        </div>
                    </x-ui.sheet-content>
                </x-ui.sheet>

                <div class="relative hidden sm:block">
                    <x-ui.input type="search" placeholder="Search…" class="h-9 w-56 pe-12">
                        <x-slot:leading><x-lucide-search /></x-slot:leading>
                    </x-ui.input>
                    <x-ui.kbd class="absolute right-1.5 top-1/2 -translate-y-1/2">⌘K</x-ui.kbd>
                </div>

                <div class="ml-auto flex items-center gap-1.5">
                    <button type="button" @click="$store.theme && $store.theme.toggle()" class="hover:bg-accent inline-flex size-9 items-center justify-center rounded-md transition-colors" aria-label="Toggle theme">
                        <x-lucide-sun class="size-4 dark:hidden" /><x-lucide-moon class="hidden size-4 dark:block" />
                    </button>
                    
                    @livewire('shared.layout.user-dropdown')
                </div>
            </header>

            {{-- Main Content --}}
            <main class="flex-1 p-4 lg:p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
