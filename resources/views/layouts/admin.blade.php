<!DOCTYPE html>
<html lang="pt-BR" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Painel' }} — Admin CVPronto</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500;600;700&family=Fira+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Fira Sans', system-ui, sans-serif; }
        .font-mono-admin { font-family: 'Fira Code', ui-monospace, monospace; font-variant-numeric: tabular-nums; }
    </style>
</head>
<body class="bg-[#0F172A] text-slate-100 antialiased min-h-screen">
    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside class="hidden lg:flex w-60 shrink-0 flex-col border-r border-slate-700/60 bg-[#0B1222] px-4 py-6">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-2 mb-8">
                <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#1E3A5F] to-accent flex items-center justify-center text-white text-xs font-bold font-mono-admin">CV</span>
                <div class="leading-tight">
                    <p class="font-semibold text-sm text-white">CVPronto</p>
                    <p class="text-[11px] text-slate-500 uppercase tracking-wide">Admin</p>
                </div>
            </a>

            <nav class="flex-1 space-y-0.5">
                <a href="#visao-geral" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium bg-slate-800/70 text-white">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M5 10v10h5v-6h4v6h5V10"/></svg>
                    Visão geral
                </a>
                <a href="#usuarios" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800/50 transition">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 0 0-3-3.87M9 20H4v-2a4 4 0 0 1 3-3.87m6-1.13a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm6-2a4 4 0 1 0-3.2-6.4"/></svg>
                    Usuários
                </a>
                <a href="#receita" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800/50 transition">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    Receita &amp; pagamentos
                </a>
                <a href="#ia" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800/50 transition">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 2 3 14h7l-1 8 10-12h-7l1-8Z"/></svg>
                    Uso de IA
                </a>
            </nav>

            <div class="pt-4 mt-4 border-t border-slate-800 space-y-0.5">
                <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800/50 transition">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0 7-7m-7 7h18"/></svg>
                    Voltar ao app
                </a>
            </div>
        </aside>

        <!-- Main -->
        <div class="flex-1 min-w-0">
            <header class="sticky top-0 z-10 flex items-center justify-between gap-4 border-b border-slate-700/60 bg-[#0F172A]/90 backdrop-blur px-5 py-4 lg:px-8">
                <div>
                    <h1 class="text-lg font-semibold text-white">{{ $title ?? 'Painel administrativo' }}</h1>
                    @isset($subtitle)
                        <p class="text-xs text-slate-400 mt-0.5">{{ $subtitle }}</p>
                    @endisset
                </div>
                <div class="flex items-center gap-3">
                    <span class="hidden sm:inline-flex items-center gap-1.5 text-xs font-medium text-emerald-400 bg-emerald-400/10 border border-emerald-400/20 rounded-full px-2.5 py-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                        Dados em tempo real
                    </span>
                    <div class="w-8 h-8 rounded-full bg-slate-700 flex items-center justify-center text-xs font-semibold text-white">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>
            </header>

            <main class="px-5 py-6 lg:px-8 lg:py-8">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
