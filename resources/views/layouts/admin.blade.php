<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Painel' }} — Admin CVPronto</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500;600;700&family=Fira+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script>
        // Aplicado antes do primeiro paint para evitar flash de tema errado.
        (function () {
            var stored = localStorage.getItem('admin-theme');
            var isDark = stored ? stored === 'dark' : true; // escuro por padrão
            document.documentElement.classList.toggle('dark', isDark);
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Fira Sans', system-ui, sans-serif; }
        .font-mono-admin { font-family: 'Fira Code', ui-monospace, monospace; font-variant-numeric: tabular-nums; }
    </style>
</head>
<body class="bg-white text-gray-900 dark:bg-[#0F172A] dark:text-slate-100 antialiased min-h-screen transition-colors">
    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside class="hidden lg:flex w-60 shrink-0 flex-col border-r border-gray-200 dark:border-slate-700/60 bg-gray-50 dark:bg-[#0B1222] px-4 py-6 transition-colors">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-2 mb-8">
                <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#1E3A5F] to-accent flex items-center justify-center text-white text-xs font-bold font-mono-admin">CV</span>
                <div class="leading-tight">
                    <p class="font-semibold text-sm text-gray-900 dark:text-white">CVPronto</p>
                    <p class="text-[11px] text-gray-400 dark:text-slate-500 uppercase tracking-wide">Admin</p>
                </div>
            </a>

            <nav class="flex-1 space-y-0.5">
                <a href="{{ route('admin.dashboard') }}" data-nav-section="visao-geral"
                    @class([
                        'nav-section-link flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition',
                        'bg-gray-200/70 dark:bg-slate-800/70 text-gray-900 dark:text-white' => request()->routeIs('admin.dashboard'),
                        'text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-slate-800/50' => ! request()->routeIs('admin.dashboard'),
                    ])>
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M5 10v10h5v-6h4v6h5V10"/></svg>
                    Visão geral
                </a>
                <a href="{{ route('admin.dashboard') }}#usuarios" data-nav-section="usuarios" class="nav-section-link flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-slate-800/50 transition">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 0 0-3-3.87M9 20H4v-2a4 4 0 0 1 3-3.87m6-1.13a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm6-2a4 4 0 1 0-3.2-6.4"/></svg>
                    Usuários
                </a>
                <a href="{{ route('admin.subscriptions.index') }}"
                    @class([
                        'flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition',
                        'bg-gray-200/70 dark:bg-slate-800/70 text-gray-900 dark:text-white' => request()->routeIs('admin.subscriptions.*'),
                        'text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-slate-800/50' => ! request()->routeIs('admin.subscriptions.*'),
                    ])>
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="9"/></svg>
                    Assinaturas
                </a>
                <a href="{{ route('admin.dashboard') }}#receita" data-nav-section="receita" class="nav-section-link flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-slate-800/50 transition">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    Receita &amp; pagamentos
                </a>
                <a href="{{ route('admin.dashboard') }}#ia" data-nav-section="ia" class="nav-section-link flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-slate-800/50 transition">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 2 3 14h7l-1 8 10-12h-7l1-8Z"/></svg>
                    Uso de IA
                </a>
            </nav>

            <div class="pt-4 mt-4 border-t border-gray-200 dark:border-slate-800 space-y-0.5">
                <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-slate-800/50 transition">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0 7-7m-7 7h18"/></svg>
                    Voltar ao app
                </a>
            </div>
        </aside>

        <!-- Main -->
        <div class="flex-1 min-w-0">
            <header class="sticky top-0 z-10 flex items-center justify-between gap-4 border-b border-gray-200 dark:border-slate-700/60 bg-white/90 dark:bg-[#0F172A]/90 backdrop-blur px-5 py-4 lg:px-8 transition-colors">
                <div>
                    <h1 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $title ?? 'Painel administrativo' }}</h1>
                    @isset($subtitle)
                        <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5">{{ $subtitle }}</p>
                    @endisset
                </div>
                <div class="flex items-center gap-2.5">
                    <span class="hidden sm:inline-flex items-center gap-1.5 text-xs font-medium text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-400/10 border border-emerald-200 dark:border-emerald-400/20 rounded-full px-2.5 py-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 dark:bg-emerald-400"></span>
                        Dados em tempo real
                    </span>

                    <button type="button" id="admin-theme-toggle" aria-label="Alternar tema claro/escuro"
                        class="w-8 h-8 rounded-lg border border-gray-200 dark:border-slate-700 text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-slate-800 flex items-center justify-center transition focus:outline-none focus:ring-2 focus:ring-accent">
                        <svg id="admin-theme-icon-sun" class="w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path stroke-linecap="round" d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
                        <svg id="admin-theme-icon-moon" class="w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12.8A9 9 0 1 1 11.2 3 7 7 0 0 0 21 12.8Z"/></svg>
                    </button>

                    <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-slate-700 flex items-center justify-center text-xs font-semibold text-gray-700 dark:text-white">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>
            </header>

            <main class="px-5 py-6 lg:px-8 lg:py-8">
                {{ $slot }}
            </main>
        </div>
    </div>

    <script>
        (function () {
            var root = document.documentElement;
            var sun = document.getElementById('admin-theme-icon-sun');
            var moon = document.getElementById('admin-theme-icon-moon');

            function syncIcon() {
                var isDark = root.classList.contains('dark');
                sun.classList.toggle('hidden', !isDark);   // no escuro, oferece ir para claro (sol)
                moon.classList.toggle('hidden', isDark);   // no claro, oferece ir para escuro (lua)
            }

            document.getElementById('admin-theme-toggle').addEventListener('click', function () {
                var isDark = root.classList.toggle('dark');
                localStorage.setItem('admin-theme', isDark ? 'dark' : 'light');
                syncIcon();
            });

            syncIcon();
        })();

        (function () {
            var links = document.querySelectorAll('.nav-section-link[data-nav-section]');
            if (! links.length) return;

            var activeClasses = ['bg-gray-200/70', 'dark:bg-slate-800/70', 'text-gray-900', 'dark:text-white'];
            var idleClasses = ['text-gray-500', 'dark:text-slate-400'];

            function setActive(id) {
                links.forEach(function (link) {
                    var isActive = link.dataset.navSection === id;
                    activeClasses.forEach(function (c) { link.classList.toggle(c, isActive); });
                    idleClasses.forEach(function (c) { link.classList.toggle(c, !isActive); });
                });
            }

            var sections = Array.prototype.slice.call(document.querySelectorAll('main section[id]'));
            if (sections.length && 'IntersectionObserver' in window) {
                var observer = new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) setActive(entry.target.id);
                    });
                }, { rootMargin: '-40% 0px -55% 0px', threshold: 0 });

                sections.forEach(function (section) { observer.observe(section); });
            }

            if (window.location.hash) {
                setActive(window.location.hash.slice(1));
            }
        })();
    </script>
</body>
</html>
