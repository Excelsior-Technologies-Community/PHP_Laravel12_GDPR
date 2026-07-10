<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            * { font-family: 'Space Grotesk', sans-serif; }
            :root {
                --bg-main: #f8fafc;
                --card-bg: rgba(255, 255, 255, 0.85);
                --card-border: rgba(15, 23, 42, 0.08);
                --text-main: #0f172a;
                --text-sub: #475569;
                --input-bg: #ffffff;
            }
            html.dark {
                --bg-main: #020617;
                --card-bg: rgba(30, 41, 59, 0.4);
                --card-border: rgba(255, 255, 255, 0.05);
                --text-main: #f3f4f6;
                --text-sub: #94a3b8;
                --input-bg: rgba(15, 23, 42, 0.6);
            }
            body {
                background: var(--bg-main) !important;
                color: var(--text-main) !important;
                transition: background 0.3s, color 0.3s;
            }
            .min-h-screen {
                background: var(--bg-main) !important;
            }
            header {
                background: var(--card-bg) !important;
                border-bottom: 1px solid var(--card-border) !important;
                backdrop-filter: blur(20px);
            }
            nav {
                background: var(--card-bg) !important;
                border-bottom: 1px solid var(--card-border) !important;
                backdrop-filter: blur(20px);
            }
            .bg-white, .dark\:bg-gray-800, .bg-gray-800 {
                background: var(--card-bg) !important;
                border: 1px solid var(--card-border) !important;
                backdrop-filter: blur(20px) !important;
                box-shadow: 0 10px 30px rgba(0,0,0,0.02) !important;
            }
            html.dark .bg-white, html.dark .dark\:bg-gray-800, html.dark .bg-gray-800 {
                box-shadow: 0 20px 40px rgba(0,0,0,0.2) !important;
            }
            input, select, textarea {
                background: var(--input-bg) !important;
                color: var(--text-main) !important;
                border: 1px solid var(--card-border) !important;
            }
            .text-gray-800, .text-gray-900, .dark\:text-gray-100, .dark\:text-gray-200 {
                color: var(--text-main) !important;
            }
            .text-gray-600, .text-gray-400, .dark\:text-gray-400 {
                color: var(--text-sub) !important;
            }
            .glass-theme-btn {
                position: fixed;
                bottom: 30px;
                left: 30px;
                width: 56px;
                height: 56px;
                border-radius: 50%;
                background: linear-gradient(135deg, #4f46e5, #06b6d4);
                border: none;
                color: white;
                cursor: pointer;
                display: flex;
                justify-content: center;
                align-items: center;
                box-shadow: 0 10px 25px rgba(79, 70, 229, 0.4);
                z-index: 9999;
                transition: transform 0.2s;
            }
            .glass-theme-btn:hover {
                transform: scale(1.05);
            }
            .gdpr-cookie-banner {
                position: fixed;
                bottom: 30px;
                right: 30px;
                width: 420px;
                background: rgba(255, 255, 255, 0.7);
                border: 1px solid rgba(15, 23, 42, 0.08);
                backdrop-filter: blur(25px);
                border-radius: 24px;
                padding: 25px;
                box-shadow: 0 20px 50px rgba(0,0,0,0.05);
                color: #0f172a;
                z-index: 99999;
                display: none;
            }
            html.dark .gdpr-cookie-banner {
                background: rgba(30, 41, 59, 0.7);
                border-color: rgba(255, 255, 255, 0.05);
                color: #f3f4f6;
                box-shadow: 0 20px 50px rgba(0,0,0,0.3);
            }
            .gdpr-banner-title { font-size: 18px; font-weight: 700; margin-bottom: 8px; color: #4f46e5; }
            html.dark .gdpr-banner-title { color: #22d3ee; }
            .gdpr-banner-desc { font-size: 13px; line-height: 1.6; margin-bottom: 20px; opacity: 0.85; }
            .gdpr-buttons { display: flex; gap: 10px; justify-content: flex-end; }
            .gdpr-btn { padding: 10px 20px; font-size: 13px; font-weight: 600; border-radius: 12px; cursor: pointer; border: none; transition: 0.2s; }
            .gdpr-accept { background: linear-gradient(135deg, #4f46e5, #06b6d4); color: white; }
            .gdpr-accept:hover { transform: translateY(-1px); box-shadow: 0 8px 15px rgba(6, 182, 212, 0.2); }
            .gdpr-reject { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
            .gdpr-reject:hover { background: #ef4444; color: white; }
        </style>
    </head>
    <body class="font-sans antialiased">
        
        <button class="glass-theme-btn" onclick="toggleGdprPlatformTheme()">
            <i class="ti ti-sun" id="gdpr-theme-icon" style="font-size: 1.4rem;"></i>
        </button>

        <div id="cookie-consent-banner" class="gdpr-cookie-banner">
            <div class="gdpr-banner-title">🍪 Cookie Consent Preferences</div>
            <div class="gdpr-banner-desc">We deploy necessary cookies to optimize cryptographic identity storage encryption models and maintain GDPR account portable system compliance protocols.</div>
            <div class="gdpr-buttons">
                <button class="gdpr-btn gdpr-reject" onclick="handleCookieChoice('rejected')">Reject All</button>
                <button class="gdpr-btn gdpr-accept" onclick="handleCookieChoice('accepted')">Accept All</button>
            </div>
        </div>

        <div class="min-h-screen">
            @include('layouts.navigation')

            @if (isset($header))
                <header class="shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main>
                {{ $slot }}
            </main>
        </div>

        <script>
            function toggleGdprPlatformTheme() {
                const isDark = document.documentElement.classList.toggle('dark');
                localStorage.setItem('gdpr-theme-pref', isDark ? 'dark' : 'light');
                document.getElementById('gdpr-theme-icon').className = isDark ? 'ti ti-moon' : 'ti ti-sun';
            }

            if (localStorage.getItem('gdpr-theme-pref') === 'dark' || (!('gdpr-theme-pref' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
                document.getElementById('gdpr-theme-icon').className = 'ti ti-moon';
            } else {
                document.documentElement.classList.remove('dark');
                document.getElementById('gdpr-theme-icon').className = 'ti ti-sun';
            }

            window.addEventListener('DOMContentLoaded', () => {
                if (!localStorage.getItem('gdpr-cookie-consent')) {
                    document.getElementById('cookie-consent-banner').style.display = 'block';
                }
            });

            function handleCookieChoice(choice) {
                localStorage.setItem('gdpr-cookie-consent', choice);
                document.getElementById('cookie-consent-banner').style.display = 'none';
            }
        </script>
    </body>
</html>