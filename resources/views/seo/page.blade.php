<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} — CVPronto</title>
    <meta name="description" content="{{ $lede }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased font-sans text-gray-900 bg-white">
    <header class="border-b border-gray-100">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2 font-bold text-lg">
                <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand to-accent flex items-center justify-center text-white text-sm">CV</span>
                CVPronto
            </a>
            <a href="{{ route('register') }}" class="text-sm font-semibold text-white bg-accent hover:bg-accent-dark rounded-lg px-4 py-2 transition">Criar conta grátis</a>
        </div>
    </header>

    <main class="max-w-2xl mx-auto px-4 sm:px-6 py-16">
        <h1 class="text-3xl font-bold text-gray-900">{{ $title }}</h1>
        <p class="text-lg text-gray-600 mt-3">{{ $lede }}</p>
        <p class="text-gray-600 mt-5 leading-relaxed">{{ $body }}</p>

        <a href="{{ route('register') }}" class="inline-flex mt-8 text-sm font-semibold text-white bg-accent hover:bg-accent-dark rounded-lg px-5 py-3 transition">
            Começar agora, é grátis
        </a>
    </main>

    <footer class="border-t border-gray-100 py-8 text-center text-xs text-gray-400">
        © {{ now()->year }} CVPronto.
    </footer>
</body>
</html>
