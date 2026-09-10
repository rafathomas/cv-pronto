# CVPronto

Plataforma self-service de criação, análise e otimização de currículos com Inteligência Artificial. Monólito Laravel organizado por domínio.

## Stack

- PHP 8.4 · Laravel 11 · PostgreSQL
- Blade + Livewire 3 (UI reativa, sem SPA)
- Tailwind CSS
- IA: camada de abstração (`App\Domain\AI\Contracts\AiProviderInterface`), implementação inicial em OpenAI
- PDF: barryvdh/laravel-dompdf
- Extração de texto: smalot/pdfparser (PDF) e phpoffice/phpword (DOCX)
- Pagamentos: abstração (`App\Domain\Payment\Contracts\PaymentGatewayInterface`), implementação inicial Mercado Pago
- Testes: Pest

## Como rodar localmente

### Com Docker

```bash
cp .env.example .env
docker compose up -d --build
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
```

Acesse em `http://localhost:8000`.

### Sem Docker

Requer PostgreSQL rodando localmente e as credenciais configuradas no `.env`.

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install && npm run build
php artisan serve
```

## Testes

```bash
php artisan test
```

Os testes usam SQLite em memória (configurado em `phpunit.xml`), independente do banco configurado para desenvolvimento/produção.

## Arquitetura

Código de domínio organizado em `app/Domain/<Contexto>`:

```
app/Domain/
├── Resume/            currículo, seções, templates, importação
├── ResumeAnalysis/     análise de currículo por IA
├── Job/                descrição de vaga, ações, rastreador de candidaturas
├── JobMatch/           compatibilidade currículo x vaga
├── CoverLetter/        carta de apresentação
├── AI/                 abstração de IA, provedores, créditos e uso
├── Subscription/       planos e assinaturas
├── Payment/             abstração de gateway de pagamento
├── User/
└── Analytics/           eventos de funil
```

Cada domínio segue o padrão: `Models/`, `Actions/` (uma operação de negócio), `DTOs/` (dados imutáveis entre camadas), `Services/` (regras que não cabem em uma única Action), `Enums/`.

- Controllers e componentes Livewire ficam finos — nunca têm regra de negócio, apenas orquestram Actions/Services.
- Toda chamada de IA passa por `App\Domain\AI\Services\AiUsageService`, que verifica créditos, executa, registra uso/custo e desconta o crédito. Nenhum outro lugar do sistema chama `AiProviderInterface` diretamente.
- Prompts de IA vivem em `resources/prompts/`, nunca embutidos em código.
- A IA nunca inventa dados: os prompts exigem que informações ausentes retornem como sugestão para o usuário preencher, nunca como fato inventado.

## Funcionalidades implementadas

- Autenticação completa (cadastro, login, verificação de e-mail, recuperação de senha, exclusão de conta)
- Criação manual de currículo com múltiplas experiências, formações, cursos, habilidades e idiomas
- Importação de currículo (PDF/DOCX) com extração via IA e revisão obrigatória antes de salvar
- Melhoria de texto com IA (resumo e descrições de experiência)
- Análise de currículo com nota 0-100, categorias, pontos fortes/fracos e recomendações
- Análise de compatibilidade com vaga (match score, competências encontradas/parciais/ausentes)
- Adaptação de currículo para uma vaga específica (nunca inventa experiência/habilidade)
- Geração de carta de apresentação editável
- 5 templates de currículo e exportação em PDF
- Sistema de créditos de IA (ledger com concessões e consumos) e registro de uso/custo por operação
- Planos (Free/Pro/Premium) configuráveis via `config/plans.php`, sem alterar código
- Assinaturas e pagamentos via Mercado Pago (checkout, webhook, cancelamento)
- Painel administrativo simples (`/admin`, requer usuário com `is_admin`)
- Páginas públicas de SEO e landing page
- Eventos de analytics do funil (`analytics_events`)
- Rastreador de candidaturas: arquitetura pronta (`applications`), desativado na UI do MVP

## Segurança e LGPD

- Autorização por Policy em todos os recursos do usuário (currículo, vaga, etc.) — um usuário nunca acessa dado de outro
- Exclusão de conta remove em cascata currículos, análises, matches, cartas e assinaturas
- Nenhum dado do usuário é usado para treinar modelos de IA
- Rate limiting nas rotas que consomem IA
- Política de Privacidade e Termos de Uso em `/politica-de-privacidade` e `/termos-de-uso`
