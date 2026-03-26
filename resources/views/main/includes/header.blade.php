<div class="topbar px-6 py-3 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <div class="text-lg font-display font-700 text-white">@yield('title')</div>
    </div>
    <div class="flex items-center gap-3">
        <div class="relative">
            <input type="text" placeholder="Axtar..." class="input text-sm" style="width:220px; padding-left:36px">
            <svg class="w-4 h-4 text-slate-500 absolute left-3 top-1/2 -translate-y-1/2" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <button class="relative p-2 rounded-lg hover:bg-surface-600 transition-colors">
            <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <div class="notif-dot"></div>
        </button>
        <button class="theme-btn" onclick="toggleTheme()" title="Tema dəyiş">
            <div class="theme-btn-knob">☀️</div>
            <span class="theme-icon-dark">🌙</span>
            <span class="theme-icon-light">☀️</span>
        </button>
        <div class="relative" data-profile-menu>
            <button class="avatar bg-gradient-to-br from-brand-600 to-brand-400 text-white text-xs cursor-pointer"
                type="button" data-profile-menu-toggle aria-expanded="false" aria-haspopup="true">
                @if (!empty(auth()->user()?->image))
                    <img src="{{ asset(auth()->user()->image) }}" alt="{{ auth()->user()?->name }}"
                        class="h-full w-full rounded-full object-cover">
                @else
                    {{ auth()->user()?->short_name }}
                @endif
            </button>
            <div class="profile-dropdown" data-profile-menu-panel>
                <a class="profile-dropdown-item" href="{{ route('profile') }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zM19 21H5a2 2 0 01-2-2v-1a7 7 0 0114 0v1a2 2 0 01-2 2z" />
                    </svg>
                    <span>Profil</span>
                </a>
                <a class="profile-dropdown-item danger" href="{{ route('logout') }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H9m4 8H7a2 2 0 01-2-2V6a2 2 0 012-2h6" />
                    </svg>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </div>
</div>

@once
    @push('js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const profileMenu = document.querySelector('[data-profile-menu]');
                const profileMenuToggle = document.querySelector('[data-profile-menu-toggle]');
                const profileMenuPanel = document.querySelector('[data-profile-menu-panel]');

                if (!profileMenu || !profileMenuToggle || !profileMenuPanel) {
                    return;
                }

                const closeProfileMenu = function() {
                    profileMenuPanel.classList.remove('open');
                    profileMenuToggle.setAttribute('aria-expanded', 'false');
                };

                profileMenuToggle.addEventListener('click', function(event) {
                    event.preventDefault();
                    event.stopPropagation();

                    const isOpen = profileMenuPanel.classList.contains('open');
                    closeProfileMenu();

                    if (!isOpen) {
                        profileMenuPanel.classList.add('open');
                        profileMenuToggle.setAttribute('aria-expanded', 'true');
                    }
                });

                document.addEventListener('click', function(event) {
                    if (!profileMenu.contains(event.target)) {
                        closeProfileMenu();
                    }
                });

                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape') {
                        closeProfileMenu();
                    }
                });
            });
        </script>
    @endpush
@endonce
