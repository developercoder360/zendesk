<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased" data-base="olive" data-theme="emerald" data-radius="1" data-input-style="inset" data-font="inter" data-font-heading="inter" data-shadow="xl" data-spacing="compact" data-tracking="tight">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Help Widget</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @livewireScriptConfig

    <!-- Dark Mode FOUC Script -->
    <script>
        (function () {
            const get = (k, d) => localStorage.getItem('theme:' + k) || d;
            const mode = get('mode', 'system');
            const dark = mode === 'dark' || (mode === 'system' && matchMedia('(prefers-color-scheme: dark)').matches);
            document.documentElement.classList.toggle('dark', dark);
        })();
    </script>
</head>
<body x-data class="h-screen w-screen bg-transparent text-foreground font-sans selection:bg-primary selection:text-primary-foreground overflow-hidden">
    <main class="h-full w-full">
        {{ $slot }}
    </main>
    
</body>
</html>
