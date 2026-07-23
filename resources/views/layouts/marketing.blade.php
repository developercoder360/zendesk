<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased" data-base="neutral" data-theme="default"
    data-radius="0.625" data-input-style="outline" data-font="sans" data-font-heading="sans" data-shadow="default"
    data-spacing="default" data-tracking="normal">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Zendesk Marketing' }}</title>
    @if (isset($meta_description))
        <meta name="description" content="{{ $meta_description }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @livewireScriptConfig

    <!-- Dark Mode Script -->
    <script>
        (function() {
            const get = (k, d) => localStorage.getItem('theme:' + k) || d;
            const mode = get('mode', 'system');
            const dark = mode === 'dark' || (mode === 'system' && matchMedia('(prefers-color-scheme: dark)').matches);
            document.documentElement.classList.toggle('dark', dark);
        })();
    </script>
</head>

<body x-data
    class="min-h-screen bg-background text-foreground font-sans selection:bg-primary selection:text-primary-foreground">
    <!-- Navbar -->
    <x-marketing.navbar />

    <!-- Main Content -->
    <main class="flex-1">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <x-marketing.footer />

    <script src="http://iota-digital-solutions.localhost:8000/widget.js"
        data-embed-key="r0fjn2ixCJfbLWJp0G1RdO2YeAImXn9C4qiO5CZU" async></script>
</body>

</html>
