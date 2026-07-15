<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased" data-base="zinc" data-theme="default" data-radius="0.5">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'SaaS Central' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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
    <div class="grid min-h-screen lg:grid-cols-2">
        {{-- Brand panel --}}
        <div class="bg-primary text-primary-foreground relative hidden flex-col justify-between overflow-hidden p-12 lg:flex">
            <div class="pointer-events-none absolute -right-20 -top-20 size-80 rounded-full bg-white/10 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-24 -left-16 size-80 rounded-full bg-white/10 blur-3xl"></div>
            <a href="/" class="relative flex items-center gap-2 font-semibold">
                <span class="flex size-8 items-center justify-center rounded-lg bg-white/15"><x-lucide-cloud class="size-5" /></span> Zendesk SaaS
            </a>
            <figure class="relative max-w-md">
                <x-lucide-quote class="mb-4 size-8 opacity-40" />
                <blockquote class="text-2xl font-medium leading-snug text-balance">
                    Zendesk SaaS is the first tool our whole team actually agreed on. Onboarding took an afternoon.
                </blockquote>
                <figcaption class="mt-6 flex items-center gap-3">
                    <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=120&q=80" alt="" class="size-11 rounded-full object-cover ring-2 ring-white/20" />
                    <div class="text-sm">
                        <div class="font-semibold">Sofia Davis</div>
                        <div class="opacity-70">VP Operations, Acme</div>
                    </div>
                </figcaption>
            </figure>
            <div class="relative flex gap-8 text-sm">
                <div><div class="text-2xl font-bold">12k+</div><div class="opacity-70">Teams</div></div>
                <div><div class="text-2xl font-bold">99.99%</div><div class="opacity-70">Uptime</div></div>
                <div><div class="text-2xl font-bold">4.9/5</div><div class="opacity-70">Rating</div></div>
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
