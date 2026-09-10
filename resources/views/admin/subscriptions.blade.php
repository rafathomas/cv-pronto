<x-admin-layout title="Assinaturas" subtitle="Gerencie o plano de cada usuário manualmente — cortesias, correções, downgrades">

    @if (session('status'))
        <div class="mb-5 rounded-lg bg-emerald-50 dark:bg-emerald-400/10 border border-emerald-200 dark:border-emerald-400/20 text-emerald-700 dark:text-emerald-400 text-sm px-4 py-3">
            {{ session('status') }}
        </div>
    @endif

    <div class="rounded-xl border border-gray-200 dark:border-slate-700/60 bg-white dark:bg-slate-800/40 overflow-hidden transition-colors">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-slate-700/60 text-left text-xs text-gray-500 dark:text-slate-400 uppercase tracking-wide">
                        <th class="px-5 py-3 font-medium">Usuário</th>
                        <th class="px-5 py-3 font-medium">Plano atual</th>
                        <th class="px-5 py-3 font-medium">Situação</th>
                        <th class="px-5 py-3 font-medium text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700/40">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/20 transition">
                            <td class="px-5 py-3">
                                <p class="text-gray-900 dark:text-white font-medium">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-slate-400">{{ $user->email }}</p>
                            </td>
                            <td class="px-5 py-3">
                                <span @class([
                                    'inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full',
                                    'bg-gray-100 dark:bg-slate-700/50 text-gray-600 dark:text-slate-300' => $user->currentPlan->key === 'free',
                                    'bg-emerald-50 dark:bg-emerald-400/10 text-emerald-600 dark:text-emerald-400' => $user->currentPlan->key !== 'free',
                                ])>
                                    {{ $user->currentPlan->name }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-gray-500 dark:text-slate-400">
                                @if ($user->activeSubscription)
                                    @if ($user->activeSubscription->gateway === 'manual')
                                        <span class="text-xs">Concedido manualmente</span>
                                    @else
                                        <span class="text-xs">Via {{ ucfirst($user->activeSubscription->gateway) }}</span>
                                    @endif
                                @else
                                    <span class="text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <form method="POST" action="{{ route('admin.subscriptions.update', $user) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <label for="plan-{{ $user->id }}" class="sr-only">Alterar plano de {{ $user->name }}</label>
                                        <select id="plan-{{ $user->id }}" name="plan_id" onchange="this.form.requestSubmit()"
                                            class="text-xs rounded-lg border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-700 dark:text-slate-200 focus:border-accent focus:ring-accent py-1.5">
                                            @foreach ($plans as $plan)
                                                <option value="{{ $plan->id }}" @selected($plan->id === $user->currentPlan->id)>{{ $plan->name }}</option>
                                            @endforeach
                                        </select>
                                    </form>

                                    @if ($user->activeSubscription && $user->activeSubscription->status->value === 'active')
                                        <form method="POST" action="{{ route('admin.subscriptions.destroy', $user) }}" onsubmit="return confirm('Cancelar a assinatura de {{ $user->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs font-semibold text-red-500 hover:text-red-600 dark:hover:text-red-400 px-2 py-1.5">
                                                Cancelar
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-5 py-8 text-center text-gray-500 dark:text-slate-500">Nenhum usuário cadastrado ainda.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 text-gray-600 dark:text-slate-400 [&_span:not([aria-current])>a]:text-gray-600 [&_span:not([aria-current])>a]:dark:text-slate-400 [&_a]:hover:text-gray-900 dark:[&_a]:hover:text-white">
        {{ $users->links() }}
    </div>

</x-admin-layout>
