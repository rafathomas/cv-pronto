<x-guest-layout>
    <div class="max-w-3xl mx-auto py-14 px-4 sm:px-6 prose prose-sm">
        <h1 class="text-2xl font-bold text-gray-900">Política de Privacidade</h1>
        <p class="text-sm text-gray-500">Última atualização: {{ now()->format('d/m/Y') }}</p>

        <p>O CVPronto trata seus dados pessoais em conformidade com a Lei Geral de Proteção de Dados (LGPD - Lei nº 13.709/2018).</p>

        <h2>Quais dados coletamos</h2>
        <p>Nome, e-mail, telefone, cidade/estado, links de redes profissionais, e o conteúdo do currículo que você preenche ou envia (PDF/DOCX).</p>

        <h2>Como usamos seus dados</h2>
        <p>Seus dados são usados exclusivamente para gerar, analisar e otimizar seu currículo. Não utilizamos seus dados para treinar modelos de inteligência artificial.</p>

        <h2>Compartilhamento com terceiros</h2>
        <p>O conteúdo do seu currículo é enviado ao provedor de IA configurado (ex.: OpenAI) apenas no momento de cada análise ou melhoria solicitada por você, e ao gateway de pagamento apenas quando você assina um plano pago.</p>

        <h2>Seus direitos</h2>
        <ul>
            <li>Acessar os dados que temos sobre você</li>
            <li>Corrigir dados incompletos ou desatualizados</li>
            <li>Excluir sua conta e todos os currículos/arquivos associados, a qualquer momento, em <a href="{{ route('profile') }}">Perfil</a></li>
            <li>Solicitar a portabilidade dos seus dados</li>
        </ul>

        <h2>Retenção e exclusão</h2>
        <p>Ao excluir sua conta, todos os seus currículos, análises, arquivos enviados e dados de uso são removidos permanentemente do nosso storage.</p>

        <h2>Contato</h2>
        <p>Dúvidas sobre privacidade podem ser enviadas para o e-mail de suporte do CVPronto.</p>
    </div>
</x-guest-layout>
