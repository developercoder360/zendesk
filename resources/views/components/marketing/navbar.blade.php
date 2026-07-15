<header class="sticky top-0 z-40 w-full border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
    <div class="container flex h-14 items-center mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}" class="mr-6 flex items-center space-x-2">
            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 19h8"/>
                <path d="m4 17 6-6-6-6"/>
            </svg>
            <span class="font-bold inline-block">Zendesk</span>
        </a>

        <!-- Desktop Navigation -->
        <nav class="hidden md:flex items-center space-x-6 text-sm font-medium">
            <a href="{{ route('features') }}" class="transition-colors hover:text-foreground/80 text-foreground/60">Features</a>
            <a href="{{ route('pricing') }}" class="transition-colors hover:text-foreground/80 text-foreground/60">Pricing</a>
            <a href="{{ route('about') }}" class="transition-colors hover:text-foreground/80 text-foreground/60">About</a>
            <a href="{{ route('contact') }}" class="transition-colors hover:text-foreground/80 text-foreground/60">Contact</a>
        </nav>

        <div class="flex flex-1 items-center justify-end space-x-2 sm:space-x-4">
            <nav class="flex items-center space-x-1">
                @auth
                    <a href="{{ url('/dashboard') }}">
                        <x-ui.button variant="ghost" size="sm">Dashboard</x-ui.button>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="hidden sm:inline-block">
                        <x-ui.button variant="ghost" size="sm">Log in</x-ui.button>
                    </a>
                    <a href="{{ route('register') }}">
                        <x-ui.button size="sm">Get Started</x-ui.button>
                    </a>
                @endauth
            </nav>

            <!-- Mobile Menu -->
            <div class="md:hidden" x-data="{ open: false }">
                <x-ui.sheet>
                    <x-ui.sheet-trigger>
                        <x-ui.button variant="ghost" size="icon" class="md:hidden">
                            <x-lucide-menu class="h-5 w-5" />
                            <span class="sr-only">Toggle Menu</span>
                        </x-ui.button>
                    </x-ui.sheet-trigger>
                    <x-ui.sheet-content side="right">
                        <div class="flex flex-col space-y-4 mt-4">
                            <a href="{{ route('features') }}" class="text-sm font-medium">Features</a>
                            <a href="{{ route('pricing') }}" class="text-sm font-medium">Pricing</a>
                            <a href="{{ route('about') }}" class="text-sm font-medium">About</a>
                            <a href="{{ route('contact') }}" class="text-sm font-medium">Contact</a>
                            @guest
                                <div class="border-t pt-4">
                                    <a href="{{ route('login') }}" class="text-sm font-medium w-full block py-2">Log in</a>
                                </div>
                            @endguest
                        </div>
                    </x-ui.sheet-content>
                </x-ui.sheet>
            </div>
        </div>
    </div>
</header>
