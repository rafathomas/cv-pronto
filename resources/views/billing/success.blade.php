<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Assinatura</h2>
    </x-slot>

    <div class="py-16 text-center max-w-md mx-auto px-4">
        <div class="w-14 h-14 rounded-full bg-green-50 text-accent-dark flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/></svg>
        </div>
        <h1 class="text-xl font-bold text-gray-900">Pagamento em processamento</h1>
        <p class="text-sm text-gray-500 mt-2">Assim que a confirmação chegar do gateway de pagamento, seu plano será atualizado automaticamente.</p>
        <a href="{{ route('dashboard') }}" wire:navigate class="inline-flex mt-6 text-sm font-semibold text-white bg-accent hover:bg-accent-dark rounded-lg px-5 py-2.5 transition">
            Voltar ao dashboard
        </a>
    </div>
</x-app-layout>
