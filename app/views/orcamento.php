<?php
$usuario = $_SESSION['usuario'] ?? null;
$nomeCompleto = $usuario['nomeCompleto'] ?? 'Usuário FinTrack';
$primeiroNome = explode(' ', trim($nomeCompleto))[0];
$iniciais = strtoupper(substr($primeiroNome, 0, 1) . (isset(explode(' ', trim($nomeCompleto))[1]) ? substr(explode(' ', trim($nomeCompleto))[1], 0, 1) : ''));
$emailUsuario = $usuario['email'] ?? 'usuario@fintrack.com';

$mesAtual = (int) ($mes ?? date('n'));
$anoAtual = (int) ($ano ?? date('Y'));

$mesesNomes = [
    1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
    5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
    9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
];

$nomeMesAtual = $mesesNomes[$mesAtual] ?? 'Mês';

// Dados recebidos do backend ou mocks de exemplo se ainda estiver desenvolvendo
$variaveisReceitas = $receitas ?? [
    ['id' => 1, 'nome' => 'Salário Líquido', 'valor_padrao' => 4500.00, 'valor' => 4500.00],
    ['id' => 2, 'nome' => 'Vale Refeição (VR)', 'valor_padrao' => 850.00, 'valor' => 850.00],
    ['id' => 3, 'nome' => 'Renda Extra / Freelance', 'valor_padrao' => 600.00, 'valor' => 600.00],
];

$variaveisDespesas = $despesas ?? [
    ['id' => 4, 'nome' => 'Aluguel / Condomínio', 'valor_padrao' => 1500.00, 'valor' => 1500.00],
    ['id' => 5, 'nome' => 'Internet Fibra', 'valor_padrao' => 129.90, 'valor' => 129.90],
    ['id' => 6, 'nome' => 'Gemini Advanced / IA', 'valor_padrao' => 96.00, 'valor' => 96.00],
    ['id' => 7, 'nome' => 'Supermercado', 'valor_padrao' => 1100.00, 'valor' => 1100.00],
    ['id' => 8, 'nome' => 'Luz & Água', 'valor_padrao' => 250.00, 'valor' => 250.00],
    ['id' => 9, 'nome' => 'Cartão de Crédito', 'valor_padrao' => 850.00, 'valor' => 850.00],
];

// Cálculo de navegação de meses
$mesAnterior = $mesAtual - 1;
$anoAnterior = $anoAtual;
if ($mesAnterior < 1) {
    $mesAnterior = 12;
    $anoAnterior--;
}

$proximoMes = $mesAtual + 1;
$proximoAno = $anoAtual;
if ($proximoMes > 12) {
    $proximoMes = 1;
    $proximoAno++;
}

// Separar em variáveis que se repetem em todos os meses vs variáveis exclusivas deste mês
$receitasTodosMeses = array_filter($variaveisReceitas, fn($i) => !empty($i['is_fixo']));
$receitasDoMes = array_filter($variaveisReceitas, fn($i) => empty($i['is_fixo']));

$despesasTodosMeses = array_filter($variaveisDespesas, fn($i) => !empty($i['is_fixo']));
$despesasDoMes = array_filter($variaveisDespesas, fn($i) => empty($i['is_fixo']));

function renderLinhaVariavel(array $item, string $tipoClasse): void {
    $isMult = ($item['tipo_calculo'] ?? 'simples') === 'multiplicador';
    $isPontual = empty($item['is_fixo']);
    ?>
    <div class="p-3 bg-light rounded-3 border d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
        <div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="fw-semibold text-dark"><?php echo htmlspecialchars($item['nome']); ?></span>
                <?php if ($isMult): ?>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle small py-1" style="font-size: 0.7rem;">
                        <i class="bi bi-calculator me-1"></i> Multiplicador
                    </span>
                <?php endif; ?>
                <?php if ($isPontual): ?>
                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle small py-1" style="font-size: 0.7rem;" title="<?php echo htmlspecialchars($item['meses_formatados'] ?? ''); ?>">
                        <i class="bi bi-calendar-event me-1"></i> <?php echo htmlspecialchars($item['meses_formatados'] ?? 'Apenas deste mês'); ?>
                    </span>
                <?php endif; ?>
            </div>
            <span class="text-secondary small">
                <?php if ($isMult): ?>
                    Cálculo: <?php echo htmlspecialchars($item['label_multiplicador'] ?? 'Dias'); ?> &times; R$ <?php echo number_format($item['valor_padrao'], 2, ',', '.'); ?>/unid.
                <?php else: ?>
                    Padrão: R$ <?php echo number_format($item['valor_padrao'], 2, ',', '.'); ?>
                <?php endif; ?>
            </span>
        </div>

        <?php if ($isMult): ?>
            <div class="d-flex flex-wrap align-items-center gap-1 justify-content-sm-end">
                <div class="input-group input-group-sm" style="width: 105px;">
                    <span class="input-group-text bg-white text-secondary px-2" title="<?php echo htmlspecialchars($item['label_multiplicador'] ?? 'Dias'); ?>">
                        <?php echo htmlspecialchars(mb_substr($item['label_multiplicador'] ?? 'Dias', 0, 5)); ?>
                    </span>
                    <input type="number" step="any" min="0" 
                        name="valores[<?php echo $item['id']; ?>][quantidade]"
                        id="qtd_<?php echo $item['id']; ?>"
                        data-id="<?php echo $item['id']; ?>"
                        value="<?php echo (float) ($item['quantidade'] ?? 1); ?>"
                        class="form-control text-end fw-semibold input-multiplicador-qtd"
                        placeholder="0">
                </div>

                <span class="text-secondary fw-bold small">&times;</span>

                <div class="input-group input-group-sm" style="width: 115px;">
                    <span class="input-group-text bg-white text-secondary px-2">R$</span>
                    <input type="number" step="0.01" min="0" 
                        name="valores[<?php echo $item['id']; ?>][valor_unitario]"
                        id="unit_<?php echo $item['id']; ?>"
                        data-id="<?php echo $item['id']; ?>"
                        value="<?php echo number_format($item['valor_unitario'] ?? $item['valor_padrao'], 2, '.', ''); ?>"
                        class="form-control text-end fw-semibold input-multiplicador-unit"
                        placeholder="0.00">
                </div>

                <span class="text-secondary fw-bold small">=</span>

                <div class="input-group input-group-sm" style="width: 130px;">
                    <span class="input-group-text bg-light text-secondary px-2">R$</span>
                    <input type="number" step="0.01" min="0" readonly
                        name="valores[<?php echo $item['id']; ?>][valor]"
                        id="total_var_<?php echo $item['id']; ?>"
                        value="<?php echo number_format($item['valor'] ?? 0, 2, '.', ''); ?>"
                        class="form-control text-end fw-bold bg-light input-valor <?php echo $tipoClasse; ?>"
                        placeholder="0.00">
                </div>
            </div>
        <?php else: ?>
            <div class="input-group" style="max-width: 200px;">
                <span class="input-group-text bg-white border-end-0 text-secondary">R$</span>
                <input type="number" step="0.01" min="0" 
                    name="valores[<?php echo $item['id']; ?>]"
                    value="<?php echo number_format($item['valor'] ?? $item['valor_padrao'], 2, '.', ''); ?>"
                    class="form-control text-end fw-bold input-valor <?php echo $tipoClasse; ?>"
                    placeholder="0.00">
            </div>
        <?php endif; ?>
    </div>
    <?php
}
?>
<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orçamento de <?php echo $nomeMesAtual; ?>/<?php echo $anoAtual; ?> - FinTrack</title>
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
                        <a class="nav-link active fw-semibold text-primary" href="/orcamento">
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
                        <a class="nav-link text-secondary fw-medium" href="/variaveis">
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

        <!-- Mensagens de Feedback -->
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

        <!-- Topo: Navegação do Mês e Ações Rápidas -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <div class="d-flex align-items-center gap-2">
                    <a href="/orcamento/<?php echo $anoAnterior; ?>/<?php echo $mesAnterior; ?>" class="btn btn-outline-secondary btn-sm" title="Mês Anterior">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                    <h1 class="h3 fw-bold text-dark mb-0">
                        <?php echo $nomeMesAtual; ?> <span class="text-secondary fw-light"><?php echo $anoAtual; ?></span>
                    </h1>
                    <a href="/orcamento/<?php echo $proximoAno; ?>/<?php echo $proximoMes; ?>" class="btn btn-outline-secondary btn-sm" title="Próximo Mês">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </div>
                <p class="text-secondary small mb-0 mt-1">Preencha e ajuste seus valores planejados para este mês.</p>
            </div>

            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-outline-primary btn-sm px-3" data-bs-toggle="modal" data-bs-target="#modalNovaVariavel">
                    <i class="bi bi-plus-lg me-1"></i> Nova Variável
                </button>
                <a href="/variaveis" class="btn btn-light btn-sm border px-3">
                    <i class="bi bi-gear me-1"></i> Gerenciar Campos
                </a>
            </div>
        </div>

        <!-- 3 Cards de Resumo Dinâmico do Mês -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <div class="stat-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-secondary small fw-medium text-uppercase tracking-wider">Total Receitas</span>
                            <h3 class="fw-bold text-success mb-0 mt-1" id="cardTotalReceitas">R$ <?php echo number_format($totais['receitas'] ?? 0, 2, ',', '.'); ?></h3>
                        </div>
                        <div class="stat-icon stat-icon-success">
                            <i class="bi bi-arrow-down-left"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="stat-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-secondary small fw-medium text-uppercase tracking-wider">Total Despesas</span>
                            <h3 class="fw-bold text-danger mb-0 mt-1" id="cardTotalDespesas">R$ <?php echo number_format($totais['despesas'] ?? 0, 2, ',', '.'); ?></h3>
                        </div>
                        <div class="stat-icon stat-icon-danger">
                            <i class="bi bi-arrow-up-right"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="stat-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-secondary small fw-medium text-uppercase tracking-wider">Saldo Previsto</span>
                            <?php $saldoInicial = (float) ($totais['saldo'] ?? 0); ?>
                            <h3 class="fw-bold <?php echo $saldoInicial >= 0 ? 'text-primary' : 'text-danger'; ?> mb-0 mt-1" id="cardSaldo">
                                R$ <?php echo number_format($saldoInicial, 2, ',', '.'); ?>
                            </h3>
                        </div>
                        <div class="stat-icon stat-icon-primary">
                            <i class="bi bi-calculator"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulário do Mês -->
        <form action="/orcamento/salvar" method="POST" id="formOrcamento">
            <input type="hidden" name="mes" value="<?php echo $mesAtual; ?>">
            <input type="hidden" name="ano" value="<?php echo $anoAtual; ?>">

            <div class="row g-4 mb-4">
                <!-- Coluna de Receitas -->
                <div class="col-12 col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-success-subtle text-success p-2 rounded-circle">
                                    <i class="bi bi-plus-circle-fill fs-6"></i>
                                </span>
                                <h5 class="fw-bold mb-0 text-dark">Receitas do Mês</h5>
                            </div>
                            <button type="button" class="btn btn-sm btn-link text-success p-0 text-decoration-none fw-semibold"
                                data-bs-toggle="modal" data-bs-target="#modalNovaVariavel" data-tipo="receita">
                                + Adicionar Campo
                            </button>
                        </div>
                        <div class="card-body p-3 p-md-4">
                            <div class="d-flex flex-column gap-3" id="listaReceitas">
                                <!-- Seção: Variáveis em Todos os Meses -->
                                <div class="d-flex flex-column gap-2 mb-2">
                                    <div class="d-flex align-items-center gap-1 text-secondary small fw-bold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">
                                        <i class="bi bi-arrow-repeat text-success"></i> Em todos os meses
                                    </div>
                                    <?php if (empty($receitasTodosMeses)): ?>
                                        <p class="text-muted small mb-0 fst-italic" style="font-size: 0.8rem;">Nenhuma receita fixa cadastrada.</p>
                                    <?php else: ?>
                                        <?php foreach ($receitasTodosMeses as $item): ?>
                                            <?php renderLinhaVariavel($item, 'input-receita'); ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>

                                <!-- Seção: Variáveis Apenas Deste Mês -->
                                <?php if (!empty($receitasDoMes)): ?>
                                    <div class="d-flex flex-column gap-2 pt-3 border-top">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center gap-1 text-secondary small fw-bold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">
                                                <i class="bi bi-calendar-event text-warning"></i> Apenas deste mês (<?php echo $nomeMesAtual; ?>)
                                            </div>
                                            <span class="badge bg-light text-secondary border"><?php echo count($receitasDoMes); ?></span>
                                        </div>
                                        <?php foreach ($receitasDoMes as $item): ?>
                                            <?php renderLinhaVariavel($item, 'input-receita'); ?>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Coluna de Despesas -->
                <div class="col-12 col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-danger-subtle text-danger p-2 rounded-circle">
                                    <i class="bi bi-dash-circle-fill fs-6"></i>
                                </span>
                                <h5 class="fw-bold mb-0 text-dark">Despesas do Mês</h5>
                            </div>
                            <button type="button" class="btn btn-sm btn-link text-danger p-0 text-decoration-none fw-semibold"
                                data-bs-toggle="modal" data-bs-target="#modalNovaVariavel" data-tipo="despesa">
                                + Adicionar Campo
                            </button>
                        </div>
                        <div class="card-body p-3 p-md-4">
                            <div class="d-flex flex-column gap-3" id="listaDespesas">
                                <!-- Seção: Variáveis em Todos os Meses -->
                                <div class="d-flex flex-column gap-2 mb-2">
                                    <div class="d-flex align-items-center gap-1 text-secondary small fw-bold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">
                                        <i class="bi bi-arrow-repeat text-danger"></i> Em todos os meses
                                    </div>
                                    <?php if (empty($despesasTodosMeses)): ?>
                                        <p class="text-muted small mb-0 fst-italic" style="font-size: 0.8rem;">Nenhuma despesa fixa cadastrada.</p>
                                    <?php else: ?>
                                        <?php foreach ($despesasTodosMeses as $item): ?>
                                            <?php renderLinhaVariavel($item, 'input-despesa'); ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>

                                <!-- Seção: Variáveis Apenas Deste Mês -->
                                <div class="d-flex flex-column gap-2 pt-3 border-top">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-1 text-secondary small fw-bold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">
                                            <i class="bi bi-calendar-event text-warning"></i> Apenas deste mês (<?php echo $nomeMesAtual; ?>)
                                        </div>
                                        <span class="badge bg-light text-secondary border"><?php echo count($despesasDoMes); ?></span>
                                    </div>
                                    <?php if (empty($despesasDoMes)): ?>
                                        <p class="text-secondary small mb-0 fst-italic" style="font-size: 0.82rem;">Nenhum gasto pontual neste mês.</p>
                                    <?php else: ?>
                                        <?php foreach ($despesasDoMes as $item): ?>
                                            <?php renderLinhaVariavel($item, 'input-despesa'); ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Barra Fixa/Rodapé de Ações -->
            <div class="card border-0 shadow-sm rounded-4 p-3 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                <span class="text-secondary small">
                    <i class="bi bi-info-circle me-1"></i> As alterações só são salvas permanentemente ao clicar no botão ao lado.
                </span>
                <div class="d-flex gap-2 w-100 w-sm-auto justify-content-end">
                    <button type="button" class="btn btn-light border px-4" onclick="location.reload()">
                        Descartar
                    </button>
                    <button type="submit" class="btn btn-primary px-5 fw-semibold shadow-sm">
                        <i class="bi bi-check2-circle me-1"></i> Salvar Mês
                    </button>
                </div>
            </div>
        </form>

    </main>

    <!-- Modal Nova Variável Personalizada (Alpine.js Powered) -->
    <div class="modal fade" id="modalNovaVariavel" tabindex="-1" aria-labelledby="modalNovaVariavelLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden" 
                 x-data="modalVariavelHandler(<?php echo (int) $mesAtual; ?>, <?php echo (int) $anoAtual; ?>)">
                
                <!-- Cabeçalho Elegante -->
                <div class="modal-header border-bottom bg-light px-4 py-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge p-2 rounded-3" 
                              :class="tipo === 'receita' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger'">
                            <i class="bi fs-5" :class="tipo === 'receita' ? 'bi-plus-circle-fill' : 'bi-dash-circle-fill'"></i>
                        </span>
                        <div>
                            <h5 class="modal-title fw-bold text-dark mb-0 fs-6">Novo Campo no Orçamento</h5>
                            <span class="text-secondary small" style="font-size: 0.75rem;">Cadastre despesas ou receitas com periodicidade personalizada</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>

                <form action="/variaveis/adicionar" method="POST">
                    <input type="hidden" name="origem" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI'] ?? '/orcamento'); ?>">
                    <input type="hidden" name="mes" value="<?php echo $mesAtual; ?>">
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
                                    <label for="nomeVariavel" class="form-label small fw-semibold text-secondary mb-1">Nome do Campo</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 text-muted">
                                            <i class="bi bi-tag"></i>
                                        </span>
                                        <input type="text" class="form-control border-start-0 ps-0" id="nomeVariavel" name="nome"
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
                                    <label for="labelMultiplicador" class="form-label small fw-semibold text-secondary mb-1">Rótulo da Quantidade</label>
                                    <input type="text" class="form-control form-control-sm" id="labelMultiplicador" name="label_multiplicador"
                                        x-model="labelMultiplicador" placeholder="Ex: Dias, Horas, Diárias, Viagens">
                                    <small class="text-secondary" style="font-size: 0.72rem;">Ex: dias trabalhados para calcular o repasse à mãe.</small>
                                </div>

                                <!-- Valor Padrão -->
                                <div>
                                    <label for="valorPadrao" class="form-label small fw-semibold text-secondary mb-1"
                                        x-text="tipoCalculo === 'multiplicador' ? 'Valor Unitário Padrão (R$)' : 'Valor Padrão Sugerido (R$)'"></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 text-muted">R$</span>
                                        <input type="number" step="0.01" min="0" class="form-control border-start-0 ps-0" id="valorPadrao" name="valor_padrao"
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
                                                @click="selecionarApenasMes(<?php echo (int) $mesAtual; ?>)">
                                                Apenas <?php echo $nomeMesAtual; ?>
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

    <!-- Script de Cálculo Dinâmico em Tempo Real -->
    <script>
        function formatarMoeda(valor) {
            return valor.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
        }

        function recalcularLinhaMultiplicador(id) {
            const qtdInput = document.getElementById('qtd_' + id);
            const unitInput = document.getElementById('unit_' + id);
            const totalInput = document.getElementById('total_var_' + id);

            if (qtdInput && unitInput && totalInput) {
                const qtd = parseFloat(qtdInput.value) || 0;
                const unit = parseFloat(unitInput.value) || 0;
                const total = qtd * unit;
                totalInput.value = total.toFixed(2);
            }
            recalcularTotais();
        }

        function recalcularTotais() {
            let totalReceitas = 0;
            let totalDespesas = 0;

            document.querySelectorAll('.input-receita').forEach(input => {
                const val = parseFloat(input.value) || 0;
                totalReceitas += val;
            });

            document.querySelectorAll('.input-despesa').forEach(input => {
                const val = parseFloat(input.value) || 0;
                totalDespesas += val;
            });

            const saldo = totalReceitas - totalDespesas;

            const elTotalReceitas = document.getElementById('cardTotalReceitas');
            const elTotalDespesas = document.getElementById('cardTotalDespesas');
            const elSaldo = document.getElementById('cardSaldo');

            if (elTotalReceitas) elTotalReceitas.innerText = formatarMoeda(totalReceitas);
            if (elTotalDespesas) elTotalDespesas.innerText = formatarMoeda(totalDespesas);
            if (elSaldo) {
                elSaldo.innerText = formatarMoeda(saldo);
                if (saldo >= 0) {
                    elSaldo.className = 'fw-bold text-primary mb-0 mt-1';
                } else {
                    elSaldo.className = 'fw-bold text-danger mb-0 mt-1';
                }
            }
        }

        // Listener para campos normais
        document.querySelectorAll('.input-valor:not([readonly])').forEach(input => {
            input.addEventListener('input', recalcularTotais);
        });

        // Listeners para campos multiplicadores (Qtd e Valor Unitário)
        document.querySelectorAll('.input-multiplicador-qtd, .input-multiplicador-unit').forEach(input => {
            input.addEventListener('input', function() {
                const id = this.getAttribute('data-id');
                recalcularLinhaMultiplicador(id);
            });
        });

        // Alpine.js Component para o Modal de Criação de Variável
        document.addEventListener('alpine:init', () => {
            Alpine.data('modalVariavelHandler', (mesPadrao, anoPadrao) => ({
                tipo: 'despesa',
                tipoCalculo: 'simples',
                labelMultiplicador: 'Dias',
                valorPadrao: '',
                anoTipo: 'todos',
                ano: anoPadrao || new Date().getFullYear(),
                meses: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
                
                init() {
                    const modalEl = document.getElementById('modalNovaVariavel');
                    if (modalEl) {
                        modalEl.addEventListener('show.bs.modal', (event) => {
                            const btn = event.relatedTarget;
                            if (btn && btn.getAttribute('data-tipo')) {
                                this.tipo = btn.getAttribute('data-tipo');
                            }
                        });
                    }
                },

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

        // Calcular ao carregar a página
        document.addEventListener('DOMContentLoaded', recalcularTotais);
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
