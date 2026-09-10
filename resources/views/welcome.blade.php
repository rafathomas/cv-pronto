<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} — Currículos com Inteligência Artificial</title>
    <meta name="description" content="Analise, melhore e adapte seu currículo para cada vaga usando IA. Sem inventar dados — só destacando o que você já tem.">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased font-sans text-gray-900 bg-white">

    <header class="border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2 font-bold text-lg">
                <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand to-accent flex items-center justify-center text-white text-sm">CV</span>
                CVPronto
            </a>
            <nav class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-brand hover:text-brand-dark">Ir para o dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900">Entrar</a>
                    <a href="{{ route('register') }}" class="text-sm font-semibold text-white bg-accent hover:bg-accent-dark rounded-lg px-4 py-2 transition">Criar conta grátis</a>
                @endauth
            </nav>
        </div>
    </header>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-14 grid lg:grid-cols-2 gap-12 items-center">
        <div>
            <span class="text-xs font-bold uppercase tracking-wide text-accent-dark">Currículo + Inteligência Artificial</span>
            <h1 class="text-4xl sm:text-5xl font-bold leading-tight mt-3 text-gray-900">
                Seu currículo pode estar impedindo você de conseguir entrevistas.
            </h1>
            <p class="text-lg text-gray-600 mt-5 max-w-xl">
                Use IA para analisar, melhorar e adaptar seu currículo para cada vaga — sem inventar nada, só destacando o que você já tem.
            </p>
            <div class="flex flex-wrap gap-3 mt-8">
                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-accent hover:bg-accent-dark rounded-lg px-5 py-3 transition">
                    Analisar meu currículo grátis
                </a>
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-lg px-5 py-3 transition">
                    Já tenho conta
                </a>
            </div>
            <div class="flex flex-wrap gap-5 mt-8 text-xs text-gray-500">
                <span>✓ Sem cartão de crédito</span>
                <span>✓ Compatível com ATS</span>
                <span>✓ Você revisa antes de salvar</span>
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-2xl shadow-xl p-6 relative">
            <div class="absolute -top-4 right-6 bg-white border border-gray-200 rounded-xl px-4 py-2 shadow-lg text-center">
                <p class="text-xl font-bold text-accent-dark leading-none">86</p>
                <p class="text-[10px] text-gray-400 uppercase mt-0.5">Nota ATS</p>
            </div>
            <div class="h-4 bg-brand/80 rounded w-1/2 mb-3"></div>
            <div class="h-2.5 bg-gray-100 rounded w-1/3 mb-6"></div>
            <div class="h-2 bg-gray-100 rounded w-1/4 mb-2"></div>
            <div class="h-2 bg-gray-100 rounded w-full mb-1.5"></div>
            <div class="h-2 bg-gray-100 rounded w-11/12 mb-1.5"></div>
            <div class="h-2 bg-gray-100 rounded w-4/5 mb-5"></div>
            <div class="h-2 bg-gray-100 rounded w-1/4 mb-2"></div>
            <div class="h-2 bg-gray-100 rounded w-full mb-1.5"></div>
            <div class="h-2 bg-gray-100 rounded w-3/5"></div>
        </div>
    </section>

    <section class="border-t border-gray-100 py-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-xl mx-auto mb-10">
                <span class="text-xs font-bold uppercase tracking-wide text-accent-dark">Como funciona</span>
                <h2 class="text-2xl sm:text-3xl font-bold mt-2">Do currículo parado ao pronto para enviar</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                @foreach ([
                    ['1', 'Crie ou importe', 'Preencha manualmente ou envie seu PDF/DOCX atual — a IA extrai os dados sem inventar nada.'],
                    ['2', 'Receba a nota', 'Análise completa de estrutura, clareza, ATS e palavras-chave em segundos.'],
                    ['3', 'Melhore com IA', 'Sugestões de texto por seção, sempre a partir das suas informações reais.'],
                    ['4', 'Adapte e exporte', 'Compare com uma vaga específica e gere um PDF profissional em 1 clique.'],
                ] as [$num, $title, $desc])
                    <div class="bg-white border border-gray-200 rounded-xl p-5">
                        <span class="w-8 h-8 rounded-lg bg-gray-100 text-brand font-bold text-sm flex items-center justify-center">{{ $num }}</span>
                        <h3 class="font-semibold mt-3">{{ $title }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="border-t border-gray-100 py-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-xl mx-auto mb-10">
                <span class="text-xs font-bold uppercase tracking-wide text-accent-dark">O que você recebe</span>
                <h2 class="text-2xl sm:text-3xl font-bold mt-2">Feito para quem quer resultado</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ([
                    ['Análise com IA', 'Nota de 0 a 100 com pontos fortes, fracos e recomendações objetivas.'],
                    ['Currículo personalizado', 'Uma versão adaptada para cada vaga, reorganizando o que você já possui.'],
                    ['Compatibilidade com vagas', 'Compare seu currículo com a descrição da vaga e veja o percentual de match.'],
                    ['5 templates prontos', 'Do clássico ao tecnológico — troque o visual sem perder seus dados.'],
                    ['Compatível com ATS', 'Estrutura pensada para passar pelos sistemas automáticos de triagem.'],
                    ['Carta de apresentação', 'Gerada a partir do seu currículo e da vaga, pronta para editar.'],
                ] as [$title, $desc])
                    <div class="bg-white border border-gray-200 rounded-xl p-5">
                        <h3 class="font-semibold">{{ $title }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="border-t border-gray-100 py-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-xl mx-auto mb-10">
                <span class="text-xs font-bold uppercase tracking-wide text-accent-dark">Preços</span>
                <h2 class="text-2xl sm:text-3xl font-bold mt-2">Comece grátis, evolua quando precisar</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 max-w-4xl mx-auto">
                @php
                    $plans = config('plans.plans');
                    $featureLabels = config('plans.feature_labels');
                @endphp
                @foreach ($plans as $key => $plan)
                    <div class="bg-white border-2 rounded-xl p-6 {{ $key === 'pro' ? 'border-accent' : 'border-gray-200' }}">
                        <span class="text-xs font-bold uppercase text-gray-400">{{ $plan['name'] }}</span>
                        <p class="text-2xl font-bold mt-1">
                            R$ {{ number_format($plan['price'] / 100, 2, ',', '.') }}
                            @if ($plan['price'] > 0)<span class="text-sm font-normal text-gray-400">/mês</span>@endif
                        </p>
                        <ul class="text-sm text-gray-600 mt-4 space-y-1.5">
                            @foreach (array_slice($plan['features'], 0, 4) as $feature)
                                <li>✓ {{ $featureLabels[$feature] ?? str_replace('_', ' ', $feature) }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
            <p class="text-center mt-8">
                <a href="{{ route('register') }}" class="inline-flex text-sm font-semibold text-white bg-accent hover:bg-accent-dark rounded-lg px-5 py-3 transition">Começar grátis</a>
            </p>
        </div>
    </section>

    <section class="border-t border-gray-100 py-16">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <span class="text-xs font-bold uppercase tracking-wide text-accent-dark">Dúvidas</span>
                <h2 class="text-2xl sm:text-3xl font-bold mt-2">Perguntas frequentes</h2>
            </div>
            <div class="space-y-2">
                @foreach ([
                    ['A IA inventa informações no meu currículo?', 'Não. A IA só melhora a apresentação do que você já forneceu. Quando falta uma informação importante, ela sugere que você preencha — nunca cria dados novos.'],
                    ['Meu currículo fica salvo com segurança?', 'Sim. Cada currículo é vinculado só à sua conta, com opção de exclusão a qualquer momento no seu perfil.'],
                    ['Posso cancelar quando quiser?', 'Sim, o cancelamento é imediato e você mantém acesso até o fim do período já pago.'],
                ] as [$q, $a])
                    <details class="border-b border-gray-200 py-3">
                        <summary class="font-semibold cursor-pointer text-sm">{{ $q }}</summary>
                        <p class="text-sm text-gray-500 mt-2">{{ $a }}</p>
                    </details>
                @endforeach
            </div>
        </div>
    </section>

    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <div class="bg-gradient-to-br from-brand to-brand-dark rounded-2xl px-8 py-14 text-center text-white">
            <h2 class="text-2xl sm:text-3xl font-bold">Pronto para parar de ser ignorado pelos recrutadores?</h2>
            <p class="text-white/80 mt-2">Leva menos de 2 minutos para receber sua primeira análise.</p>
            <a href="{{ route('register') }}" class="inline-flex mt-6 text-sm font-semibold text-brand bg-white hover:bg-gray-100 rounded-lg px-5 py-3 transition">
                Criar minha conta grátis
            </a>
        </div>
    </section>

    <footer class="border-t border-gray-100 py-8 text-center text-xs text-gray-400">
        <p>© {{ now()->year }} CVPronto. Feito para quem quer conseguir a próxima entrevista.</p>
        <p class="mt-2 space-x-3">
            <a href="{{ route('legal.privacy') }}" class="hover:text-gray-600">Política de Privacidade</a>
            <a href="{{ route('legal.terms') }}" class="hover:text-gray-600">Termos de Uso</a>
        </p>
    </footer>

</body>
</html>
