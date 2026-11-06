<?php
$usuario = $_SESSION['usuario'] ?? null;
$nomeCompleto = $usuario['nomeCompleto'] ?? 'Usuário FinTrack';
$primeiroNome = explode(' ', trim($nomeCompleto))[0];
$iniciais = strtoupper(substr($primeiroNome, 0, 1) . (isset(explode(' ', trim($nomeCompleto))[1]) ? substr(explode(' ', trim($nomeCompleto))[1], 0, 1) : ''));
$emailUsuario = $usuario['email'] ?? 'usuario@fintrack.com';

$todasVariaveis = $variaveis ?? [];

$receitas = array_filter($todasVariaveis, fn($v) => $v['tipo'] === 'receita');
$despesas = array_filter($todasVariaveis, fn($v) => $v['tipo'] === 'despesa');
?>
<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Variáveis de Orçamento - FinTrack</title>
    <link rel="shortcut icon" href="/favicon.ico">

    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/css/dashboard.css">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
</head>

<body>

    <!-- Barra de Navegação -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top py-2">
        <div class="container-fluid px-3 px-lg-4">
            <a class="navbar-brand d-flex align-items-center gap-2" href="/home">
                <span class="brand-icon-sm">
                    <i class="bi bi-wallet2"></i>
                </span>
                <span class="fw-bold fs-5 text-dark tracking-tight">Fin<span class="text-primary">Track</span></span>
            </a>

            <button class="navbar-toggler border-0 shadow-none p-1" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false"
                aria-label="Alternar navegação">
                <i class="bi bi-list fs-2"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4 gap-lg-1">
                    <li class="nav-item">
                        <a class="nav-link text-secondary fw-medium" href="/home">
                            <i class="bi bi-grid me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-secondary fw-medium" href="/orcamento">
                            <i class="bi bi-calendar2-range me-1"></i> Orçamento Mensal
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-secondary fw-medium" href="/caixinhas">
                            <i class="bi bi-piggy-bank me-1"></i> Caixinhas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-secondary fw-medium" href="/investimentos">
                            <i class="bi bi-graph-up-arrow me-1"></i> Investimentos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active fw-semibold text-primary" href="/variaveis">
                            <i class="bi bi-sliders me-1"></i> Minhas Variáveis
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
                    <!-- Usuário -->
                    <div class="d-flex align-items-center gap-2">
                        <span class="avatar-sm"><?php echo htmlspecialchars($iniciais); ?></span>
                        <span class="d-none d-md-inline fw-semibold small text-dark text-truncate" style="max-width: 140px;">
                            <?php echo htmlspecialchars($primeiroNome); ?>
                        </span>
                    </div>

                    <!-- Botão Logout -->
                    <a href="/logout" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1 py-1 px-2 fw-medium" title="Encerrar Sessão">
                        <i class="bi bi-box-arrow-right"></i>
                        <span class="d-none d-sm-inline">Sair</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Conteúdo Principal -->
    <main class="container-fluid px-3 px-lg-4 py-4">

        <!-- Mensagens de Sucesso ou Erro Geral -->
        <?php if (!empty($_SESSION['sucesso'])): ?>
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 py-2 small mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <?php echo htmlspecialchars($_SESSION['sucesso']); unset($_SESSION['sucesso']); ?>
                <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['erro'])): ?>
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 py-2 small mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <?php echo htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?>
                <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Topo -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h1 class="h3 fw-bold text-dark mb-0">Minhas Variáveis de Orçamento</h1>
                <p class="text-secondary small mb-0 mt-1">Gerencie os campos personalizados de receita e despesa que aparecem no seu orçamento mensal.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="/orcamento" class="btn btn-light btn-sm border px-3">
                    <i class="bi bi-arrow-left me-1"></i> Ir para Orçamento
                </a>
                <button type="button" class="btn btn-primary btn-sm px-3 shadow-sm fw-semibold d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modalNovaVariavel">
                    <i class="bi bi-plus-lg"></i> Nova Variável
                </button>
            </div>
        </div>

        <div class="row g-4">
            <!-- Tabela de Variáveis de Receita -->
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-success-subtle text-success p-2 rounded-circle">
                                <i class="bi bi-arrow-down-left fs-6"></i>
                            </span>
                            <h5 class="fw-bold mb-0 text-dark">Campos de Receita</h5>
                        </div>
                        <span class="badge bg-light text-secondary border"><?php echo count($receitas); ?> Campos</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-secondary small text-uppercase">
                                <tr>
                                    <th class="ps-3">Nome do Campo</th>
                                    <th class="text-end">Valor Padrão</th>
                                    <th class="text-center">Frequência</th>
                                    <th class="text-center pe-3">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($receitas as $item): ?>
                                    <tr>
                                        <td class="ps-3">
                                            <div class="fw-semibold text-dark"><?php echo htmlspecialchars($item['nome']); ?></div>
                                            <?php if (($item['tipoCalculo'] ?? 'simples') === 'multiplicador'): ?>
                                                <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.7rem;">
                                                    <i class="bi bi-calculator me-1"></i> Multiplicador (<?php echo htmlspecialchars($item['labelMultiplicador'] ?? 'Dias'); ?> &times; R$)
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end text-success fw-medium">
                                            R$ <?php echo number_format($item['valorPadrao'], 2, ',', '.'); ?>
                                            <?php if (($item['tipoCalculo'] ?? 'simples') === 'multiplicador'): ?>
                                                <span class="text-muted small d-block" style="font-size: 0.72rem;">/unid.</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($item['isFixo']): ?>
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle"><i class="bi bi-arrow-repeat me-1"></i> Todos os meses</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle" title="<?php echo htmlspecialchars($item['mesesFormatados'] ?? ''); ?>">
                                                    <i class="bi bi-calendar-event me-1"></i> <?php echo htmlspecialchars($item['mesesFormatados'] ?? 'Apenas do mês'); ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center pe-3">
                                            <form action="/variaveis/excluir/<?php echo $item['id']; ?>" method="POST" onsubmit="return confirm('Deseja excluir esta variável?');" style="display:inline;">
                                                <button type="submit" class="btn btn-sm btn-light border text-danger" title="Excluir">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tabela de Variáveis de Despesa -->
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-danger-subtle text-danger p-2 rounded-circle">
                                <i class="bi bi-arrow-up-right fs-6"></i>
                            </span>
                            <h5 class="fw-bold mb-0 text-dark">Campos de Despesa</h5>
                        </div>
                        <span class="badge bg-light text-secondary border"><?php echo count($despesas); ?> Campos</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-secondary small text-uppercase">
                                <tr>
                                    <th class="ps-3">Nome do Campo</th>
                                    <th class="text-end">Valor Padrão</th>
                                    <th class="text-center">Frequência</th>
                                    <th class="text-center pe-3">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($despesas as $item): ?>
                                    <tr>
                                        <td class="ps-3">
                                            <div class="fw-semibold text-dark"><?php echo htmlspecialchars($item['nome']); ?></div>
                                            <?php if (($item['tipoCalculo'] ?? 'simples') === 'multiplicador'): ?>
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle" style="font-size: 0.7rem;">
                                                    <i class="bi bi-calculator me-1"></i> Multiplicador (<?php echo htmlspecialchars($item['labelMultiplicador'] ?? 'Dias'); ?> &times; R$)
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end text-danger fw-medium">
                                            R$ <?php echo number_format($item['valorPadrao'], 2, ',', '.'); ?>
                                            <?php if (($item['tipoCalculo'] ?? 'simples') === 'multiplicador'): ?>
                                                <span class="text-muted small d-block" style="font-size: 0.72rem;">/unid.</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($item['isFixo']): ?>
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle"><i class="bi bi-arrow-repeat me-1"></i> Todos os meses</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle" title="<?php echo htmlspecialchars($item['mesesFormatados'] ?? ''); ?>">
                                                    <i class="bi bi-calendar-event me-1"></i> <?php echo htmlspecialchars($item['mesesFormatados'] ?? 'Apenas do mês'); ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center pe-3">
                                            <form action="/variaveis/excluir/<?php echo $item['id']; ?>" method="POST" onsubmit="return confirm('Deseja excluir esta variável?');" style="display:inline;">
                                                <button type="submit" class="btn btn-sm btn-light border text-danger" title="Excluir">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!-- Modal Nova Variável (Alpine.js Powered) -->
    <?php $dadosModal = $_SESSION['dados_modal_variavel'] ?? []; ?>
    <div class="modal fade" id="modalNovaVariavel" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden" 
                 x-data="modalVariavelHandler(<?php echo (int) date('n'); ?>, <?php echo (int) date('Y'); ?>)">
                
                <!-- Cabeçalho Elegante -->
                <div class="modal-header border-bottom bg-light px-4 py-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge p-2 rounded-3" 
                              :class="tipo === 'receita' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger'">
                            <i class="bi fs-5" :class="tipo === 'receita' ? 'bi-plus-circle-fill' : 'bi-dash-circle-fill'"></i>
                        </span>
                        <div>
                            <h5 class="modal-title fw-bold text-dark mb-0 fs-6">Cadastrar Nova Variável</h5>
                            <span class="text-secondary small" style="font-size: 0.75rem;">Defina os campos e a periodicidade das suas finanças</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>

                <form action="/variaveis/adicionar" method="POST">
                    <input type="hidden" name="origem" value="/variaveis">
                    <input type="hidden" name="tipo" :value="tipo">
                    <input type="hidden" name="tipo_calculo" :value="tipoCalculo">
                    <input type="hidden" name="ano_tipo" :value="anoTipo">

                    <div class="modal-body p-4">

                        <!-- Alerta de Erro de Validação -->
                        <?php if (!empty($_SESSION['erro_modal_variavel'])): ?>
                            <div class="alert alert-danger py-2 px-3 small d-flex align-items-center mb-3 rounded-3" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2 fs-6"></i>
                                <span><?php echo htmlspecialchars($_SESSION['erro_modal_variavel']); ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="row g-4">
                            <!-- Coluna Esquerda: Definições da Variável -->
                            <div class="col-12 col-md-6 d-flex flex-column gap-3">
                                <!-- Seletor Tipo: Receita vs Despesa (Pill Tabs Modernos) -->
                                <div>
                                    <label class="form-label small fw-semibold text-secondary mb-1">Tipo de Movimentação</label>
                                    <div class="p-1 bg-light rounded-3 d-flex gap-1 border">
                                        <button type="button" class="btn btn-sm flex-fill rounded-2 fw-semibold transition-all py-2"
                                            :class="tipo === 'receita' ? 'bg-success text-white shadow-sm' : 'text-secondary hover-bg-white border-0'"
                                            @click="tipo = 'receita'">
                                            <i class="bi bi-arrow-down-left me-1"></i> Receita (+ Ganho)
                                        </button>
                                        <button type="button" class="btn btn-sm flex-fill rounded-2 fw-semibold transition-all py-2"
                                            :class="tipo === 'despesa' ? 'bg-danger text-white shadow-sm' : 'text-secondary hover-bg-white border-0'"
                                            @click="tipo = 'despesa'">
                                            <i class="bi bi-arrow-up-right me-1"></i> Despesa (- Gasto)
                                        </button>
                                    </div>
                                </div>

                                <!-- Nome do Campo -->
                                <div>
                                    <label for="nome" class="form-label small fw-semibold text-secondary mb-1">Nome do Campo</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 text-muted">
                                            <i class="bi bi-tag"></i>
                                        </span>
                                        <input type="text" class="form-control border-start-0 ps-0" id="nome" name="nome" 
                                            value="<?php echo htmlspecialchars($dadosModal['nome'] ?? ''); ?>"
                                            placeholder="Ex: Ajuda Mãe, Netflix, Freelance..." required>
                                    </div>
                                </div>

                                <!-- Modo de Cálculo (Cards Clicáveis) -->
                                <div>
                                    <label class="form-label small fw-semibold text-secondary mb-1">Modo de Cálculo</label>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="p-2 rounded-3 cursor-pointer transition-all border d-flex align-items-center gap-2 h-100"
                                                 :class="tipoCalculo === 'simples' ? 'border-primary bg-primary-subtle text-primary shadow-sm' : 'bg-light text-secondary border-dashed hover-bg-white'"
                                                 @click="tipoCalculo = 'simples'">
                                                <i class="bi bi-cash-stack fs-5"></i>
                                                <div>
                                                    <div class="fw-bold small">Valor Direto</div>
                                                    <div class="small opacity-75" style="font-size: 0.7rem;">R$ fixo ou variável</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-2 rounded-3 cursor-pointer transition-all border d-flex align-items-center gap-2 h-100"
                                                 :class="tipoCalculo === 'multiplicador' ? 'border-primary bg-primary-subtle text-primary shadow-sm' : 'bg-light text-secondary border-dashed hover-bg-white'"
                                                 @click="tipoCalculo = 'multiplicador'">
                                                <i class="bi bi-calculator fs-5"></i>
                                                <div>
                                                    <div class="fw-bold small">Multiplicador</div>
                                                    <div class="small opacity-75" style="font-size: 0.7rem;">Qtd &times; Unitário</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Campo Multiplicador Dinâmico -->
                                <div x-show="tipoCalculo === 'multiplicador'" x-transition class="p-3 bg-light rounded-3 border">
                                    <label for="label_multiplicador" class="form-label small fw-semibold text-secondary mb-1">Rótulo da Quantidade</label>
                                    <input type="text" class="form-control form-control-sm" id="label_multiplicador" name="label_multiplicador" 
                                        x-model="labelMultiplicador" placeholder="Ex: Dias, Horas, Diárias">
                                    <small class="text-secondary" style="font-size: 0.72rem;">Ex: dias trabalhados para calcular o repasse à mãe.</small>
                                </div>

                                <!-- Valor Padrão -->
                                <div>
                                    <label for="valor_padrao" class="form-label small fw-semibold text-secondary mb-1"
                                        x-text="tipoCalculo === 'multiplicador' ? 'Valor Unitário Padrão (R$)' : 'Valor Padrão Sugerido (R$)'"></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 text-muted">R$</span>
                                        <input type="number" step="0.01" min="0" class="form-control border-start-0 ps-0" id="valor_padrao" name="valor_padrao" 
                                            x-model="valorPadrao" placeholder="0.00">
                                    </div>
                                    <small class="text-secondary" style="font-size: 0.72rem;"
                                        x-text="tipoCalculo === 'multiplicador' ? 'Valor por unidade (ex: R$ 12,20 por dia).' : 'Sugerido automaticamente nos meses programados.'"></small>
                                </div>
                            </div>

                            <!-- Coluna Direita: Programação & Frequência (Ano e Meses) -->
                            <div class="col-12 col-md-6 d-flex flex-column">
                                <div class="card border-0 bg-light rounded-3 p-3 h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <label class="form-label small fw-bold text-dark mb-0">
                                                <i class="bi bi-calendar-week me-1 text-primary"></i> Programação & Frequência
                                            </label>
                                            <!-- Seletor Ano Recorrente vs Ano Específico -->
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-xs py-1 px-2 transition-all"
                                                    :class="anoTipo === 'todos' ? 'btn-primary shadow-sm text-white' : 'btn-outline-secondary bg-white'"
                                                    @click="anoTipo = 'todos'">
                                                    <i class="bi bi-arrow-repeat me-1"></i> Todo ano
                                                </button>
                                                <button type="button" class="btn btn-xs py-1 px-2 transition-all"
                                                    :class="anoTipo === 'especifico' ? 'btn-primary shadow-sm text-white' : 'btn-outline-secondary bg-white'"
                                                    @click="anoTipo = 'especifico'">
                                                    <i class="bi bi-calendar-check me-1"></i> Ano específico
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Input de Ano Específico -->
                                        <div x-show="anoTipo === 'especifico'" x-transition class="mb-2 py-2 px-3 bg-white rounded-2 border d-flex align-items-center justify-content-between">
                                            <span class="small text-secondary fw-semibold">Ano de aplicação:</span>
                                            <div class="input-group input-group-sm" style="width: 140px;">
                                                <button type="button" class="btn btn-outline-secondary" @click="ano = parseInt(ano) - 1">-</button>
                                                <input type="number" name="ano" class="form-control text-center fw-bold text-primary" x-model="ano" min="2020" max="2099">
                                                <button type="button" class="btn btn-outline-secondary" @click="ano = parseInt(ano) + 1">+</button>
                                            </div>
                                        </div>

                                        <!-- Atalhos de Meses -->
                                        <div class="d-flex flex-wrap gap-1 mb-2">
                                            <button type="button" class="btn btn-xs py-1 px-2 rounded-pill transition-all"
                                                :class="meses.length === 12 ? 'btn-primary text-white shadow-sm' : 'btn-white border text-secondary'"
                                                @click="selecionarTodosMeses()">
                                                Todos
                                            </button>
                                            <button type="button" class="btn btn-xs py-1 px-2 rounded-pill btn-white border text-secondary transition-all"
                                                @click="selecionarApenasMes(<?php echo (int) date('n'); ?>)">
                                                Apenas mês atual
                                            </button>
                                            <button type="button" class="btn btn-xs py-1 px-2 rounded-pill btn-white border text-secondary transition-all"
                                                @click="selecionarSemestre(1)">
                                                1º Sem.
                                            </button>
                                            <button type="button" class="btn btn-xs py-1 px-2 rounded-pill btn-white border text-secondary transition-all"
                                                @click="selecionarSemestre(2)">
                                                2º Sem.
                                            </button>
                                            <button type="button" class="btn btn-xs py-1 px-2 rounded-pill btn-white border text-danger transition-all"
                                                @click="limparMeses()">
                                                Limpar
                                            </button>
                                        </div>

                                        <!-- Grid dos 12 meses (4 colunas x 3 linhas) -->
                                        <div class="d-grid mb-2" style="grid-template-columns: repeat(4, 1fr); gap: 6px;">
                                            <template x-for="(mNome, mNum) in {1:'Jan',2:'Fev',3:'Mar',4:'Abr',5:'Mai',6:'Jun',7:'Jul',8:'Ago',9:'Set',10:'Out',11:'Nov',12:'Dez'}" :key="mNum">
                                                <button type="button" class="btn btn-sm py-2 px-1 text-center rounded-2 transition-all fw-semibold"
                                                    :class="temMes(parseInt(mNum)) 
                                                        ? 'btn-primary text-white shadow-sm' 
                                                        : 'btn-outline-light text-secondary border bg-white hover-bg-white'"
                                                    @click="alternarMes(parseInt(mNum))">
                                                    <span x-text="mNome" style="font-size: 0.8rem;"></span>
                                                    <i x-show="temMes(parseInt(mNum))" class="bi bi-check2 d-block" style="font-size: 0.65rem; line-height: 1;"></i>
                                                </button>
                                            </template>
                                        </div>

                                        <!-- Inputs hidden dos meses[] -->
                                        <template x-for="m in meses" :key="m">
                                            <input type="hidden" name="meses[]" :value="m">
                                        </template>
                                    </div>

                                    <!-- Resumo Dinâmico em Tempo Real -->
                                    <div class="mt-2 pt-2 border-top d-flex align-items-center gap-1 text-secondary" style="font-size: 0.74rem;">
                                        <i class="bi bi-info-circle text-primary"></i>
                                        <span>Vai entrar em: <strong class="text-dark" x-text="resumoProgramacao()"></strong></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer border-top bg-light px-4 py-3">
                        <button type="button" class="btn btn-light border px-3" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary px-4 fw-semibold shadow-sm">
                            <i class="bi bi-plus-lg me-1"></i> Cadastrar Variável
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5.3.3 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <!-- Script Alpine.js Handler para o Modal -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('modalVariavelHandler', (mesPadrao, anoPadrao) => ({
                tipo: '<?php echo htmlspecialchars($dadosModal['tipo'] ?? 'receita'); ?>',
                tipoCalculo: '<?php echo htmlspecialchars($dadosModal['tipo_calculo'] ?? 'simples'); ?>',
                labelMultiplicador: '<?php echo htmlspecialchars($dadosModal['label_multiplicador'] ?? 'Dias'); ?>',
                valorPadrao: '<?php echo htmlspecialchars((string) ($dadosModal['valor_padrao'] ?? '')); ?>',
                anoTipo: '<?php echo !empty($dadosModal['ano']) ? 'especifico' : 'todos'; ?>',
                ano: <?php echo (int) ($dadosModal['ano'] ?? date('Y')); ?>,
                meses: <?php 
                    $preMarcados = $dadosModal['meses'] ?? range(1, 12);
                    if (is_string($preMarcados)) {
                        $preMarcados = array_map('intval', explode(',', $preMarcados));
                    }
                    echo json_encode(array_values(array_map('intval', (array)$preMarcados)));
                ?>,

                selecionarTodosMeses() {
                    this.meses = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
                },
                selecionarApenasMes(m) {
                    this.meses = [m];
                },
                selecionarSemestre(sem) {
                    this.meses = sem === 1 ? [1, 2, 3, 4, 5, 6] : [7, 8, 9, 10, 11, 12];
                },
                limparMeses() {
                    this.meses = [];
                },
                alternarMes(m) {
                    if (this.meses.includes(m)) {
                        this.meses = this.meses.filter(x => x !== m);
                    } else {
                        this.meses.push(m);
                        this.meses.sort((a, b) => a - b);
                    }
                },
                temMes(m) {
                    return this.meses.includes(m);
                },
                resumoProgramacao() {
                    let texto = '';
                    if (this.meses.length === 12) {
                        texto = 'Todos os meses';
                    } else if (this.meses.length === 0) {
                        texto = 'Nenhum mês selecionado';
                    } else {
                        const nomes = {1:'Jan',2:'Fev',3:'Mar',4:'Abr',5:'Mai',6:'Jun',7:'Jul',8:'Ago',9:'Set',10:'Out',11:'Nov',12:'Dez'};
                        texto = this.meses.map(m => nomes[m]).join(', ');
                    }
                    if (this.anoTipo === 'especifico') {
                        texto += ' de ' + this.ano;
                    } else {
                        texto += ' (recorrente todo ano)';
                    }
                    return texto;
                }
            }));
        });
    </script>

    <!-- Script para reabrir o modal automaticamente se houver erro -->
    <?php if (!empty($_SESSION['erro_modal_variavel'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modalEl = document.getElementById('modalNovaVariavel');
                if (modalEl) {
                    const modal = new bootstrap.Modal(modalEl);
                    modal.show();
                }
            });
        </script>
        <?php 
            unset($_SESSION['erro_modal_variavel']);
            unset($_SESSION['dados_modal_variavel']);
        ?>
    <?php endif; ?>
</body>

</html>
