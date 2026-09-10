<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white border border-gray-200 rounded-xl p-6">
                <h3 class="text-lg font-bold text-gray-900">
                    Olá, {{ auth()->user()->name }}
                </h3>

                @if ($resume)
                    <p class="text-sm text-gray-500 mt-1">
                        Currículo: <span class="font-medium text-gray-800">{{ $resume->title }}</span>
                        · última atualização {{ $resume->updated_at->diffForHumans() }}
                        @if ($resume->latest_score !== null)
                            · nota <span class="font-semibold text-accent-dark">{{ $resume->latest_score }}/100</span>
                        @endif
                    </p>
                @else
                    <p class="text-sm text-gray-500 mt-1">Você ainda não criou um currículo.</p>
                @endif
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <a href="{{ route('resume.builder', $resume) }}" wire:navigate
                    class="bg-white border border-gray-200 rounded-xl p-5 hover:shadow-md transition">
                    <h4 class="font-semibold text-gray-900">{{ $resume ? 'Editar currículo' : 'Criar currículo' }}</h4>
                    <p class="text-sm text-gray-500 mt-1">Preencha seus dados, seção por seção.</p>
                    <span class="inline-flex items-center gap-1 text-sm font-semibold text-accent-dark mt-3">
                        Começar
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </span>
                </a>

                <a href="{{ route('resume.analyze', $resume) }}" wire:navigate
                    class="bg-white border border-gray-200 rounded-xl p-5 hover:shadow-md transition">
                    <h4 class="font-semibold text-gray-900">Analisar currículo</h4>
                    <p class="text-sm text-gray-500 mt-1">Receba nota e recomendações da IA.</p>
                    <span class="inline-flex items-center gap-1 text-sm font-semibold text-accent-dark mt-3">
                        Analisar
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </span>
                </a>

                <a href="{{ route('job.analyze') }}" wire:navigate
                    class="bg-white border border-gray-200 rounded-xl p-5 hover:shadow-md transition">
                    <h4 class="font-semibold text-gray-900">Analisar uma vaga</h4>
                    <p class="text-sm text-gray-500 mt-1">Compare seu currículo com uma descrição de vaga.</p>
                    <span class="inline-flex items-center gap-1 text-sm font-semibold text-accent-dark mt-3">
                        Comparar
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </span>
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
