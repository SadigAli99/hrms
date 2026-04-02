<aside class="sidebar flex flex-col">
    <!-- Logo -->
    <div class="p-5 border-b border-blue-900/30">
        <div class="flex items-center gap-3">
            <div
                class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-500 to-accent flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div>
                <div class="font-display font-800 text-sm text-white leading-tight">HR AI</div>
                <div class="text-[10px] text-slate-500 font-medium">Platform v2.0</div>
            </div>
        </div>
    </div>

    <!-- Nav -->
    <nav class="flex-1 py-3 overflow-y-auto">
        <div class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"
            onclick="window.location.href='{{ route('dashboard') }}'">
            <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 7h7v7H3V7zm11 0h7v3h-7V7zm0 7h7v3h-7v-3zm-11 4h7v3H3v-3z" />
            </svg>
            Dashboard
        </div>

        <div class="nav-section">İşə qəbul</div>
        <div class="nav-item {{ request()->routeIs('vacancy.*') ? 'active' : '' }}"
            onclick="window.location.href='{{ route('vacancy.index') }}'">
            <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            Vakansiyalar
        </div>
        {{-- <div class="nav-item" onclick="window.location.href='candidates.html'">
            <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            Namizədlər
        </div> --}}
        <div class="nav-item" class="nav-item {{ request()->routeIs('talent-pool.*') ? 'active' : '' }}"
            onclick="window.location.href='{{ route('talent-pool.index') }}'">
            <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
            </svg>
            Talent Pool
        </div>
        <div class="nav-item" onclick="window.location.href='interviews.html'">
            <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Müsahibələr
        </div>
        <div class="nav-item {{ request()->routeIs('department.*') ? 'active' : '' }}"
            onclick="window.location.href='{{ route('department.index') }}'">
            <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 21h18M5 21V7l7-4 7 4v14M9 9h.01M15 9h.01M9 13h.01M15 13h.01M9 17h6" />
            </svg>
            Departamentlər
        </div>

        {{-- <div class="nav-section">Ä°ÅŸÃ§i Ä°darÉ™si</div>
        <div class="nav-item" onclick="window.location.href='employees.html'">
            <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2" />
            </svg>
            Ä°ÅŸÃ§ilÉ™r
        </div>
        <div class="nav-item" onclick="window.location.href='leaves.html'">
            <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" />
            </svg>
            MÉ™zuniyyÉ™t
        </div>
        <div class="nav-item" onclick="window.location.href='permissions.html'">
            <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Ä°cazÉ™lÉ™r
        </div>
        <div class="nav-item" onclick="window.location.href='trips.html'">
            <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064" />
            </svg>
            EzamiyyÉ™tlÉ™r
        </div>
        <div class="nav-item" onclick="window.location.href='performance.html'">
            <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            Performans
        </div>
        <div class="nav-item" onclick="window.location.href='attendance.html'">
            <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
            DavamiyyÉ™t
        </div> --}}

        <div class="nav-section">İstifadəçi idarəetməsi</div>
        <div class="nav-item {{ request()->routeIs('permission.*') ? 'active' : '' }} "
            onclick="window.location.href='{{ route('permission.index') }}'">
            <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 3l7 4v5c0 5-3.5 8-7 9-3.5-1-7-4-7-9V7l7-4z" />
            </svg>
            İcazələr
        </div>
        <div class="nav-item {{ request()->routeIs('role.*') ? 'active' : '' }}"
            onclick="window.location.href='{{ route('role.index') }}'">
            <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 7h16M6 3h12a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V5a2 2 0 012-2z" />
            </svg>
            Rollar
        </div>
        <div class="nav-item {{ request()->routeIs('user.*') ? 'active' : '' }}"
            onclick="window.location.href='{{ route('user.index') }}'">
            <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zM19 21H5a2 2 0 01-2-2v-1a7 7 0 0114 0v1a2 2 0 01-2 2z" />
            </svg>
            İstifadəçilər
        </div>

        {{-- <div class="nav-section">Analitika</div>
        <div class="nav-item" onclick="window.location.href='analytics.html'">
            <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
            </svg>
            Hesabatlar
        </div> --}}
    </nav>

    <!-- User -->
    <div class="p-4 border-t border-blue-900/30">
        <div class="flex items-center gap-3">
            <div class="avatar bg-gradient-to-br from-brand-500 to-accent text-white text-sm">
                {{ auth()->user()->short_name }}</div>
            <div class="flex-1 min-w-0">
                <div class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</div>
                <div class="text-xs text-slate-500">{{ auth()->user()->roles->first()?->name }}</div>
            </div>
            <div class="w-2 h-2 rounded-full bg-success"></div>
        </div>
    </div>
</aside>
