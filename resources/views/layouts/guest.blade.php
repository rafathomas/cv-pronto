<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CVPronto') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen grid lg:grid-cols-2">

            <!-- Painel de marca -->
            <div class="hidden lg:flex flex-col justify-between bg-gradient-to-br from-brand to-brand-dark text-white p-12 relative overflow-hidden">
                <a href="/" class="flex items-center gap-2 font-bold text-lg relative z-10">
                    <span class="w-9 h-9 rounded-lg bg-white/15 backdrop-blur flex items-center justify-center text-sm">CV</span>
                    CVPronto
                </a>

                <div class="relative z-10 max-w-md">
                    <h2 class="text-3xl font-bold leading-tight">
                        Seu currículo pode estar impedindo você de conseguir entrevistas.
                    </h2>
                    <p class="text-white/75 mt-4">
                        Use IA para analisar, melhorar e adaptar seu currículo para cada vaga — sem inventar nada, só destacando o que você já tem.
                    </p>
                    <ul class="mt-8 space-y-3 text-sm text-white/85">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 6 9 17l-5-5"/></svg>
                            Análise de currículo com nota de 0 a 100
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 6 9 17l-5-5"/></svg>
                            Compatibilidade com sistemas ATS
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 6 9 17l-5-5"/></svg>
                            Currículo adaptado para cada vaga
                        </li>
                    </ul>
                </div>

                <p class="relative z-10 text-xs text-white/50">© {{ now()->year }} CVPronto</p>

                <div class="absolute -right-24 -bottom-24 w-80 h-80 rounded-full bg-accent/20 blur-3xl"></div>
                <div class="absolute -left-16 -top-16 w-64 h-64 rounded-full bg-white/10 blur-3xl"></div>
            </div>

            <!-- Formulário -->
            <div class="flex flex-col justify-center items-center px-6 py-12">
                <div class="w-full max-w-sm">
                    <a href="/" class="lg:hidden flex items-center gap-2 font-bold text-lg mb-8 justify-center">
                        <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand to-accent flex items-center justify-center text-white text-sm">CV</span>
                        CVPronto
                    </a>

                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
