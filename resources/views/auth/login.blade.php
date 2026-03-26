<!DOCTYPE html>
<html lang="az">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - HR AI Platform</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('assets/js/tailwind-config.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/hr-platform.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
</head>

<body class="login-page">
    <main class="login-shell">
        <section class="login-stage">
            <div class="login-brand">
                <div class="login-mark">
                    <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <div class="login-brand-title">HR AI Platform</div>
                    <div class="login-brand-subtitle">Recruitment and people operations workspace</div>
                </div>
            </div>

            <div class="login-grid">
                <section class="login-panel login-panel-story">
                    <div class="login-eyebrow">Access Workspace</div>
                    <h1 class="login-title">Secure sign in for recruitment and HR operations</h1>
                    <p class="login-copy">
                        Use your company credentials to access vacancies, candidates, interviews, talent pool
                        and user management modules.
                    </p>

                    <div class="login-points">
                        <div class="login-point">
                            <span class="login-point-dot"></span>
                            <div>
                                <strong>Unified hiring flow</strong>
                                <p>Move from vacancy creation to interview and offer in one workspace.</p>
                            </div>
                        </div>
                        <div class="login-point">
                            <span class="login-point-dot"></span>
                            <div>
                                <strong>Role-based access</strong>
                                <p>Permissions can be mapped by role before backend auth is connected.</p>
                            </div>
                        </div>
                        <div class="login-point">
                            <span class="login-point-dot"></span>
                            <div>
                                <strong>Backend-ready UI</strong>
                                <p>This page is static and ready to be wired to a real login route.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="login-panel login-panel-form">
                    <div class="login-card-top">
                        <div>
                            <div class="login-eyebrow">Sign in</div>
                            <h2 class="login-form-title">Welcome back</h2>
                        </div>
                        <button class="theme-btn" onclick="toggleTheme()" title="Toggle theme" type="button">
                            <div class="theme-btn-knob">â˜€ï¸</div>
                            <span class="theme-icon-dark">ðŸŒ™</span>
                            <span class="theme-icon-light">â˜€ï¸</span>
                        </button>
                    </div>

                    <form class="login-form" method="post">
                        @csrf
                        <div>
                            <label class="label" for="email">Email</label>
                            <input class="input" id="email" name="email" type="email"
                                placeholder="hr@company.az">
                        </div>

                        <div>
                            <div class="login-label-row">
                                <label class="label" for="password">Password</label>
                                <a class="login-link" href="#">Forgot password?</a>
                            </div>
                            <input class="input" id="password" name="password" type="password"
                                placeholder="Enter your password">
                        </div>

                        <div class="login-row">
                            <label class="login-checkbox">
                                <input type="checkbox" name="remember">
                                <span>Remember me</span>
                            </label>
                            <span class="login-helper">SSO can be added later</span>
                        </div>

                        <button class="btn-primary login-submit" type="submit">Sign in</button>
                    </form>

                    <div class="login-footer">
                        <span>Need access?</span>
                        <a class="login-link" href="users.html">Ask system admin</a>
                    </div>
                </section>
            </div>
        </section>
    </main>

    <script src="{{ asset('assets/js/ui-shell.js') }}"></script>
</body>

</html>
