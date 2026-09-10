<?php

namespace App\Http\Controllers\Seo;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class SeoPageController extends Controller
{
    /**
     * Páginas públicas de SEO. Conteúdo curto, direcionando para o cadastro.
     * Cada página compartilha o mesmo template (resources/views/seo/page.blade.php).
     */
    private const PAGES = [
        'criar-curriculo' => [
            'title' => 'Criar Currículo Online Grátis',
            'lede' => 'Monte seu currículo profissional em minutos, direto no navegador, sem precisar de Word ou Canva.',
            'body' => 'Preencha seus dados pessoais, experiências, formação e habilidades em um formulário guiado. Ao final, escolha entre 5 templates prontos e baixe seu currículo em PDF.',
        ],
        'curriculo-com-ia' => [
            'title' => 'Currículo com Inteligência Artificial',
            'lede' => 'Use IA para melhorar a redação do seu currículo sem inventar experiências que você não teve.',
            'body' => 'A IA do CVPronto analisa o que você já escreveu e sugere uma versão mais clara e profissional — sempre baseada nas suas informações reais, nunca inventando cargos, empresas ou resultados.',
        ],
        'curriculo-primeiro-emprego' => [
            'title' => 'Currículo para o Primeiro Emprego',
            'lede' => 'Sem experiência profissional ainda? Aprenda a destacar projetos, cursos e habilidades.',
            'body' => 'Para quem está buscando o primeiro emprego, o CVPronto ajuda a organizar formação, cursos livres e projetos pessoais de forma que chamem a atenção dos recrutadores.',
        ],
        'curriculo-estagio' => [
            'title' => 'Currículo para Estágio',
            'lede' => 'Currículo enxuto e direto ao ponto, pensado para vagas de estágio.',
            'body' => 'Estruture formação em andamento, idiomas e habilidades técnicas em um currículo compatível com os sistemas ATS usados por grandes empresas.',
        ],
        'curriculo-programador' => [
            'title' => 'Currículo para Programador',
            'lede' => 'Destaque stack técnica, projetos e experiência de forma clara para recrutadores técnicos.',
            'body' => 'Compare seu currículo com a descrição de vagas de desenvolvedor e veja quais tecnologias pedidas na vaga você já domina.',
        ],
        'curriculo-administrativo' => [
            'title' => 'Currículo para Área Administrativa',
            'lede' => 'Currículo organizado para vagas de assistente, analista e coordenador administrativo.',
            'body' => 'Destaque rotinas, sistemas utilizados (ERP, Excel avançado) e resultados quantificáveis nas suas experiências administrativas.',
        ],
        'curriculo-vendedor' => [
            'title' => 'Currículo para Vendedor',
            'lede' => 'Mostre metas batidas e resultados comerciais de forma objetiva.',
            'body' => 'A análise de IA do CVPronto sugere como reforçar números e resultados reais nas suas experiências em vendas, sem inventar metas que você não bateu.',
        ],
    ];

    public function show(string $slug): View|Response
    {
        if (! isset(self::PAGES[$slug])) {
            abort(404);
        }

        return view('seo.page', self::PAGES[$slug]);
    }
}
