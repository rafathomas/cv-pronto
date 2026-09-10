<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Painel Administrativo</h2>
    </x-slot>

    <div class="py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <p class="text-xs font-semibold text-gray-400 uppercase">Usuários</p>
                <p class="text-2xl font-bold mt-1">{{ $usersCount }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $usersActiveLast30Days }} ativos (30d)</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <p class="text-xs font-semibold text-gray-400 uppercase">Currículos</p>
                <p class="text-2xl font-bold mt-1">{{ $resumesCount }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $analysesCount }} análises geradas</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <p class="text-xs font-semibold text-gray-400 uppercase">Assinaturas ativas</p>
                <p class="text-2xl font-bold mt-1">{{ $activeSubscriptions }}</p>
                <p class="text-xs text-gray-400 mt-1">Receita: R$ {{ number_format($revenueCents / 100, 2, ',', '.') }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <p class="text-xs font-semibold text-gray-400 uppercase">Chamadas de IA</p>
                <p class="text-2xl font-bold mt-1">{{ $aiCallsCount }}</p>
                <p class="text-xs text-gray-400 mt-1">Custo estimado: US$ {{ number_format($aiCostUsd, 4, ',', '.') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <h3 class="font-semibold mb-3">Usuários recentes</h3>
                <table class="w-full text-sm">
                    <tbody>
                        @foreach ($recentUsers as $user)
                            <tr class="border-t border-gray-100">
                                <td class="py-2">{{ $user->name }}</td>
                                <td class="py-2 text-gray-400">{{ $user->email }}</td>
                                <td class="py-2 text-gray-400 text-right">{{ $user->created_at->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <h3 class="font-semibold mb-3">Pagamentos recentes</h3>
                <table class="w-full text-sm">
                    <tbody>
                        @forelse ($recentPayments as $payment)
                            <tr class="border-t border-gray-100">
                                <td class="py-2">{{ $payment->user?->name ?? '—' }}</td>
                                <td class="py-2 text-gray-400">R$ {{ number_format($payment->amount_cents / 100, 2, ',', '.') }}</td>
                                <td class="py-2 text-gray-400 text-right">{{ $payment->status }}</td>
                            </tr>
                        @empty
                            <tr><td class="py-2 text-gray-400">Nenhum pagamento ainda.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
