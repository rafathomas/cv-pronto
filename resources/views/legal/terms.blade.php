<x-guest-layout>
    <div class="max-w-3xl mx-auto py-14 px-4 sm:px-6 prose prose-sm">
        <h1 class="text-2xl font-bold text-gray-900">Termos de Uso</h1>
        <p class="text-sm text-gray-500">Última atualização: {{ now()->format('d/m/Y') }}</p>

        <h2>Sobre o serviço</h2>
        <p>O CVPronto é uma plataforma self-service de criação, análise e otimização de currículos com apoio de inteligência artificial.</p>

        <h2>Uso da IA</h2>
        <p>As sugestões geradas por IA se baseiam exclusivamente nas informações que você fornece. A IA não inventa experiências, cargos, empresas, habilidades, certificações ou resultados. É sua responsabilidade revisar e confirmar a veracidade de todo o conteúdo antes de usá-lo em candidaturas reais.</p>

        <h2>Créditos e planos</h2>
        <p>Cada operação de IA consome créditos, conforme o plano contratado. Os limites e preços de cada plano podem ser consultados na página de <a href="{{ route('billing.plans') }}">Planos</a>.</p>

        <h2>Cancelamento</h2>
        <p>Assinaturas pagas podem ser canceladas a qualquer momento. O acesso aos recursos do plano permanece até o fim do período já pago.</p>

        <h2>Responsabilidades do usuário</h2>
        <p>Você é responsável pela veracidade das informações enviadas e pelo uso do currículo e da carta de apresentação gerados.</p>
    </div>
</x-guest-layout>
