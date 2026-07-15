<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased" data-base="zinc" data-theme="default" data-radius="0.5">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Zendesk Auth' }}</title>

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
<body x-data class="min-h-screen bg-muted/30 text-foreground font-sans selection:bg-primary selection:text-primary-foreground flex flex-col items-center justify-center p-4">
    <div class="absolute top-4 right-4 flex items-center gap-2">
        <a href="{{ route('home') }}" class="text-sm font-medium text-muted-foreground hover:text-foreground transition-colors">
            Back to website
        </a>
    </div>

    <div class="w-full max-w-sm">
        {{ $slot }}
    </div>
</body>
</html>
