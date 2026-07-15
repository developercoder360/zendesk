<footer class="border-t bg-muted/40 mt-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12 md:py-16">
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8">
            <div class="col-span-2 lg:col-span-2">
                <a href="{{ route('home') }}" class="flex items-center space-x-2 mb-4">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 19h8"/>
                        <path d="m4 17 6-6-6-6"/>
                    </svg>
                    <span class="font-bold text-lg inline-block">Zendesk</span>
                </a>
                <p class="text-sm text-muted-foreground leading-relaxed max-w-xs mb-6">
                    A modern, enterprise-grade platform to build better customer experiences and foster long-term loyalty.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="text-muted-foreground hover:text-foreground">
                        <span class="sr-only">Twitter</span>
                        <x-lucide-twitter class="h-5 w-5" />
                    </a>
                    <a href="#" class="text-muted-foreground hover:text-foreground">
                        <span class="sr-only">GitHub</span>
                        <x-lucide-github class="h-5 w-5" />
                    </a>
                    <a href="#" class="text-muted-foreground hover:text-foreground">
                        <span class="sr-only">LinkedIn</span>
                        <x-lucide-linkedin class="h-5 w-5" />
                    </a>
                </div>
            </div>
            
            <div>
                <h3 class="font-semibold mb-4 text-sm">Product</h3>
                <ul class="space-y-3 text-sm text-muted-foreground">
                    <li><a href="{{ route('features') }}" class="hover:text-foreground transition-colors">Features</a></li>
                    <li><a href="{{ route('pricing') }}" class="hover:text-foreground transition-colors">Pricing</a></li>
                    <li><a href="#" class="hover:text-foreground transition-colors">Integrations</a></li>
                    <li><a href="#" class="hover:text-foreground transition-colors">Changelog</a></li>
                </ul>
            </div>

            <div>
                <h3 class="font-semibold mb-4 text-sm">Company</h3>
                <ul class="space-y-3 text-sm text-muted-foreground">
                    <li><a href="{{ route('about') }}" class="hover:text-foreground transition-colors">About</a></li>
                    <li><a href="#" class="hover:text-foreground transition-colors">Blog</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-foreground transition-colors">Contact</a></li>
                    <li><a href="#" class="hover:text-foreground transition-colors">Partners</a></li>
                </ul>
            </div>

            <div>
                <h3 class="font-semibold mb-4 text-sm">Legal</h3>
                <ul class="space-y-3 text-sm text-muted-foreground">
                    <li><a href="{{ route('privacy') }}" class="hover:text-foreground transition-colors">Privacy Policy</a></li>
                    <li><a href="{{ route('terms') }}" class="hover:text-foreground transition-colors">Terms of Service</a></li>
                    <li><a href="{{ route('cookies') }}" class="hover:text-foreground transition-colors">Cookie Policy</a></li>
                </ul>
            </div>
        </div>
        
        <div class="mt-12 pt-8 border-t flex flex-col md:flex-row items-center justify-between text-xs text-muted-foreground">
            <p>&copy; {{ date('Y') }} Zendesk Inc. All rights reserved.</p>
            <div class="mt-4 md:mt-0 flex space-x-4">
                <button onclick="document.documentElement.classList.toggle('dark'); localStorage.setItem('theme:mode', document.documentElement.classList.contains('dark') ? 'dark' : 'light')" class="flex items-center space-x-1 hover:text-foreground transition-colors">
                    <x-lucide-moon class="h-4 w-4 hidden dark:block" />
                    <x-lucide-sun class="h-4 w-4 block dark:hidden" />
                    <span>Toggle Theme</span>
                </button>
            </div>
        </div>
    </div>
</footer>
