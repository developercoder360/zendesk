<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased" data-base="olive" data-theme="emerald" data-radius="1" data-input-style="inset" data-font="inter" data-font-heading="inter" data-shadow="xl" data-spacing="compact" data-tracking="tight">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'SaaS Central' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
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
<body x-data class="min-h-screen bg-muted/30 text-foreground font-sans selection:bg-primary selection:text-primary-foreground" style="font-family: 'Plus Jakarta Sans', sans-serif;">
    <div class="grid min-h-screen lg:grid-cols-2">
        {{-- Brand panel --}}
        <div class="bg-primary text-primary-foreground relative hidden flex-col justify-between overflow-hidden p-12 lg:flex">
            <div class="pointer-events-none absolute -right-20 -top-20 size-80 rounded-full bg-white/20 blur-3xl mix-blend-overlay"></div>
            <div class="pointer-events-none absolute -bottom-24 -left-16 size-80 rounded-full bg-black/10 blur-3xl mix-blend-multiply"></div>
            <a href="/" class="relative flex items-center gap-2 font-semibold text-lg tracking-tight">
                <span class="flex size-10 items-center justify-center rounded-xl bg-white/20 shadow-sm backdrop-blur-md"><x-lucide-cloud class="size-6" /></span> Zendesk SaaS
            </a>
            <figure class="relative max-w-md">
                <x-lucide-quote class="mb-6 size-10 opacity-30 mix-blend-overlay" />
                <blockquote class="text-3xl font-medium leading-tight text-balance">
                    Zendesk SaaS is the first tool our whole team actually agreed on. Onboarding took an afternoon and the results were immediate.
                </blockquote>
                <figcaption class="mt-8 flex items-center gap-4">
                    <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=120&q=80" alt="Sofia Davis" class="size-14 rounded-full object-cover ring-4 ring-white/30 shadow-lg" />
                    <div class="text-sm">
                        <div class="font-bold text-base">Sofia Davis</div>
                        <div class="opacity-80 font-medium">VP Operations, Acme Corp</div>
                    </div>
                </figcaption>
            </figure>
            <div class="relative flex gap-10 text-sm border-t border-white/20 pt-8 mt-8">
                <div><div class="text-3xl font-bold tracking-tight">12k+</div><div class="opacity-80 font-medium mt-1">Teams</div></div>
                <div><div class="text-3xl font-bold tracking-tight">99.99%</div><div class="opacity-80 font-medium mt-1">Uptime</div></div>
                <div><div class="text-3xl font-bold tracking-tight">4.9/5</div><div class="opacity-80 font-medium mt-1">Rating</div></div>
            </div>
        </div>

        {{-- Form Area --}}
        <div class="relative flex items-center justify-center p-6 sm:p-10">
            <button type="button" @click="$store.theme && $store.theme.toggle()" class="hover:bg-accent absolute right-5 top-5 inline-flex size-9 items-center justify-center rounded-md transition-colors" aria-label="Toggle theme">
                <x-lucide-sun class="size-4 dark:hidden" /><x-lucide-moon class="hidden size-4 dark:block" />
            </button>

            <div class="w-full max-w-sm">
                <a href="/" class="mb-8 flex items-center gap-2 font-semibold lg:hidden">
                    <span class="bg-primary text-primary-foreground flex size-8 items-center justify-center rounded-lg"><x-lucide-cloud class="size-5" /></span> Zendesk SaaS
                </a>
                
                {{ $slot }}

                <p class="text-muted-foreground mt-8 text-center text-xs">Protected by reCAPTCHA · SOC 2 Type II compliant</p>
            </div>
        </div>
    </div>
</body>
</html>
