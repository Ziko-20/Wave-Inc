<footer class="bg-white border-t border-gray-200 font-sans mt-auto">

    {{-- Main --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 px-6 md:px-12 py-8">

        {{-- Branding --}}
        <div>
            <p class="text-sm font-medium text-gray-900 mb-1">Wave — Gestion Clients & Abonnements</p>
            <p class="text-xs text-gray-400 font-light leading-relaxed mb-5 max-w-xs">
                Votre espace de gestion sécurisé.
            </p>
            <div class="flex items-center gap-4">
                <a href="https://www.wave.ma/" target="_blank"
                   class="flex items-center gap-1.5 text-xs text-gray-500 hover:text-blue-500 transition-colors">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 14 14" fill="none">
                        <circle cx="7" cy="7" r="6" stroke="currentColor" stroke-width="1.3"/>
                        <path d="M7 1c0 0-2 2.5-2 6s2 6 2 6" stroke="currentColor" stroke-width="1.3"/>
                        <path d="M1 7h12" stroke="currentColor" stroke-width="1.3"/>
                        <path d="M1.5 4.5h11M1.5 9.5h11" stroke="currentColor" stroke-width="1.3"/>
                    </svg>
                    wave.ma
                </a>
                <div class="w-px h-3 bg-gray-200"></div>
                <a href="https://www.linkedin.com/company/wavema/" target="_blank"
                   class="flex items-center gap-1.5 text-xs text-gray-500 hover:text-blue-500 transition-colors">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 14 14" fill="currentColor">
                        <rect x="1" y="1" width="12" height="12" rx="2"/>
                        <rect x="3" y="5.5" width="1.8" height="5.5" fill="white"/>
                        <circle cx="3.9" cy="3.6" r="1" fill="white"/>
                        <path d="M6.2 5.5h1.7v.9c.3-.5.9-1 1.8-1 1.5 0 2.1 1 2.1 2.6V11H10V8.3c0-.8-.2-1.5-1-1.5-.8 0-1.1.6-1.1 1.5V11H6.2V5.5z" fill="white"/>
                    </svg>
                    LinkedIn
                </a>
            </div>
        </div>

        {{-- Contact --}}
        <div>
            <p class="text-xs font-medium text-gray-300 uppercase tracking-widest mb-4">Contact</p>
            <ul class="space-y-3">
                <li>
                    <a href="mailto:contact@wave.ma"
                       class="flex items-center gap-2 text-sm text-gray-500 font-light hover:text-gray-900 transition-colors">
                        <svg class="w-3.5 h-3.5 text-gray-300 shrink-0" viewBox="0 0 14 14" fill="none">
                            <rect x="1" y="3" width="12" height="8" rx="1.5" stroke="currentColor" stroke-width="1.3"/>
                            <path d="M1 4l6 4 6-4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                        </svg>
                        contact@wave.ma
                    </a>
                </li>
                <li>
                    <a href="tel:+212809893112"
                       class="flex items-center gap-2 text-sm text-gray-500 font-light hover:text-gray-900 transition-colors">
                        <svg class="w-3.5 h-3.5 text-gray-300 shrink-0" viewBox="0 0 14 14" fill="none">
                            <path d="M2 2.5C2 2.2 2.2 2 2.5 2h2.1c.3 0 .5.2.5.4l.6 2.5c.1.2 0 .5-.2.6L4.3 6.3c.7 1.4 1.9 2.6 3.4 3.4l.8-1.2c.2-.2.4-.3.6-.2l2.5.6c.3.1.4.3.4.5V11.5c0 .3-.2.5-.5.5C5.2 12 2 8.8 2 2.5z" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                        </svg>
                        +212 809 893 112
                       
                    </a>
                </li>
            </ul>
        </div>

    </div>

    {{-- Bottom bar --}}
    <div class="flex items-center justify-between px-6 md:px-12 py-4 border-t border-gray-50 flex-wrap gap-3">
        <span class="text-xs text-gray-400 font-light">© {{ date('Y') }} Wave</span>
        <div class="flex items-center gap-2">
            <span class="text-xs text-gray-400 bg-gray-50 rounded px-2 py-1">Laravel 11</span>
            <span class="text-xs text-gray-400 bg-gray-50 rounded px-2 py-1">Livewire</span>
            <span class="text-xs text-gray-400 bg-gray-50 rounded px-2 py-1">v1.0.0</span>
        </div>
    </div>

</footer>