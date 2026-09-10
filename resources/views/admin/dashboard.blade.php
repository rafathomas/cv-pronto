<x-admin-layout title="Visão geral" subtitle="Métricas de produto, receita e uso de IA em tempo real">

    <div class="space-y-10">

        {{-- ============ VISÃO GERAL ============ --}}
        <section id="visao-geral" class="scroll-mt-20">
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

                <div class="rounded-xl border border-slate-700/60 bg-slate-800/40 p-5">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-slate-400 uppercase tracking-wide">Usuários</span>
                        <span class="w-8 h-8 rounded-lg bg-sky-400/10 text-sky-400 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 0 0-3-3.87M9 20H4v-2a4 4 0 0 1 3-3.87m6-1.13a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm6-2a4 4 0 1 0-3.2-6.4"/></svg>
                        </span>
                    </div>
                    <p class="font-mono-admin text-3xl font-semibold text-white mt-3">{{ number_format($usersCount, 0, ',', '.') }}</p>
                    <p class="text-xs text-slate-400 mt-1">{{ $usersActiveLast30Days }} ativos nos últimos 30 dias</p>
                </div>

                <div class="rounded-xl border border-slate-700/60 bg-slate-800/40 p-5">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-slate-400 uppercase tracking-wide">Currículos</span>
                        <span class="w-8 h-8 rounded-lg bg-violet-400/10 text-violet-400 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20M4 19.5A2.5 2.5 0 0 0 6.5 22H20v-5M4 19.5V6a2 2 0 0 1 2-2h14v13"/></svg>
                        </span>
                    </div>
                    <p class="font-mono-admin text-3xl font-semibold text-white mt-3">{{ number_format($resumesCount, 0, ',', '.') }}</p>
                    <p class="text-xs text-slate-400 mt-1">{{ number_format($analysesCount, 0, ',', '.') }} análises geradas</p>
                </div>

                <div class="rounded-xl border border-slate-700/60 bg-slate-800/40 p-5">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-slate-400 uppercase tracking-wide">Assinaturas ativas</span>
                        <span class="w-8 h-8 rounded-lg bg-emerald-400/10 text-emerald-400 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="9"/></svg>
                        </span>
                    </div>
                    <p class="font-mono-admin text-3xl font-semibold text-white mt-3">{{ number_format($activeSubscriptions, 0, ',', '.') }}</p>
                    <p class="text-xs text-slate-400 mt-1">Planos Pro + Premium</p>
                </div>

                <div class="rounded-xl border border-slate-700/60 bg-slate-800/40 p-5">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-slate-400 uppercase tracking-wide">Receita total</span>
                        <span class="w-8 h-8 rounded-lg bg-amber-400/10 text-amber-400 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        </span>
                    </div>
                    <p class="font-mono-admin text-3xl font-semibold text-white mt-3">R$ {{ number_format($revenueCents / 100, 2, ',', '.') }}</p>
                    <p class="text-xs text-slate-400 mt-1">Pagamentos aprovados</p>
                </div>

            </div>
        </section>

        {{-- ============ USUÁRIOS ============ --}}
        <section id="usuarios" class="scroll-mt-20">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-semibold text-white">Usuários recentes</h2>
                <span class="text-xs text-slate-500">Últimos {{ $recentUsers->count() }} cadastros</span>
            </div>
            <div class="rounded-xl border border-slate-700/60 bg-slate-800/40 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-700/60 text-left text-xs text-slate-400 uppercase tracking-wide">
                                <th class="px-5 py-3 font-medium">Nome</th>
                                <th class="px-5 py-3 font-medium">E-mail</th>
                                <th class="px-5 py-3 font-medium text-right">Cadastrado em</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700/40">
                            @forelse ($recentUsers as $user)
                                <tr class="hover:bg-slate-700/20 transition">
                                    <td class="px-5 py-3 text-white font-medium">{{ $user->name }}</td>
                                    <td class="px-5 py-3 text-slate-400">{{ $user->email }}</td>
                                    <td class="px-5 py-3 text-slate-400 text-right font-mono-admin">{{ $user->created_at->format('d/m/Y') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="px-5 py-8 text-center text-slate-500">Nenhum usuário cadastrado ainda.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        {{-- ============ RECEITA & PAGAMENTOS ============ --}}
        <section id="receita" class="scroll-mt-20">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-semibold text-white">Pagamentos recentes</h2>
                <span class="text-xs text-slate-500">Últimos {{ $recentPayments->count() }} registros</span>
            </div>
            <div class="rounded-xl border border-slate-700/60 bg-slate-800/40 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-700/60 text-left text-xs text-slate-400 uppercase tracking-wide">
                                <th class="px-5 py-3 font-medium">Usuário</th>
                                <th class="px-5 py-3 font-medium">Valor</th>
                                <th class="px-5 py-3 font-medium text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700/40">
                            @forelse ($recentPayments as $payment)
                                <tr class="hover:bg-slate-700/20 transition">
                                    <td class="px-5 py-3 text-white font-medium">{{ $payment->user?->name ?? 'Usuário removido' }}</td>
                                    <td class="px-5 py-3 text-slate-300 font-mono-admin">R$ {{ number_format($payment->amount_cents / 100, 2, ',', '.') }}</td>
                                    <td class="px-5 py-3 text-right">
                                        <span @class([
                                            'inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full',
                                            'bg-emerald-400/10 text-emerald-400' => $payment->status === 'approved',
                                            'bg-amber-400/10 text-amber-400' => $payment->status === 'pending',
                                            'bg-red-400/10 text-red-400' => in_array($payment->status, ['failed', 'refunded']),
                                        ])>
                                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                            {{ ['approved' => 'Aprovado', 'pending' => 'Pendente', 'failed' => 'Falhou', 'refunded' => 'Reembolsado'][$payment->status] ?? $payment->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="px-5 py-8 text-center text-slate-500">Nenhum pagamento registrado ainda.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        {{-- ============ USO DE IA ============ --}}
        <section id="ia" class="scroll-mt-20">
            <h2 class="text-sm font-semibold text-white mb-3">Uso de IA</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="rounded-xl border border-slate-700/60 bg-slate-800/40 p-5">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-slate-400 uppercase tracking-wide">Chamadas de IA</span>
                        <span class="w-8 h-8 rounded-lg bg-fuchsia-400/10 text-fuchsia-400 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 2 3 14h7l-1 8 10-12h-7l1-8Z"/></svg>
                        </span>
                    </div>
                    <p class="font-mono-admin text-3xl font-semibold text-white mt-3">{{ number_format($aiCallsCount, 0, ',', '.') }}</p>
                    <p class="text-xs text-slate-400 mt-1">Operações executadas com sucesso</p>
                </div>
                <div class="rounded-xl border border-slate-700/60 bg-slate-800/40 p-5">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-slate-400 uppercase tracking-wide">Custo estimado</span>
                        <span class="w-8 h-8 rounded-lg bg-rose-400/10 text-rose-400 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M12 7v5l3 3"/></svg>
                        </span>
                    </div>
                    <p class="font-mono-admin text-3xl font-semibold text-white mt-3">US$ {{ number_format($aiCostUsd, 4, ',', '.') }}</p>
                    <p class="text-xs text-slate-400 mt-1">Baseado no preço por 1k tokens de cada modelo</p>
                </div>
            </div>
        </section>

    </div>

</x-admin-layout>
