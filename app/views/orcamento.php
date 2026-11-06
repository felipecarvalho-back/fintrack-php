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
                                <?php foreach ($variaveisReceitas as $item): ?>
                                    <?php $isMult = ($item['tipo_calculo'] ?? 'simples') === 'multiplicador'; ?>
                                    <div class="p-3 bg-light rounded-3 border d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                                        <div>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="fw-semibold text-dark"><?php echo htmlspecialchars($item['nome']); ?></span>
                                                <?php if ($isMult): ?>
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle small py-1" style="font-size: 0.72rem;">
                                                        <i class="bi bi-calculator me-1"></i> Multiplicador
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
                                                <!-- Quantidade -->
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

                                                <!-- Valor Unitário -->
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

                                                <!-- Total Calculado -->
                                                <div class="input-group input-group-sm" style="width: 130px;">
                                                    <span class="input-group-text bg-light text-secondary px-2">R$</span>
                                                    <input type="number" step="0.01" min="0" readonly
                                                        name="valores[<?php echo $item['id']; ?>][valor]"
                                                        id="total_var_<?php echo $item['id']; ?>"
                                                        value="<?php echo number_format($item['valor'] ?? 0, 2, '.', ''); ?>"
                                                        class="form-control text-end fw-bold bg-light input-valor input-receita"
                                                        placeholder="0.00">
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div class="input-group" style="max-width: 200px;">
                                                <span class="input-group-text bg-white border-end-0 text-secondary">R$</span>
                                                <input type="number" step="0.01" min="0" 
                                                    name="valores[<?php echo $item['id']; ?>]"
                                                    value="<?php echo number_format($item['valor'] ?? $item['valor_padrao'], 2, '.', ''); ?>"
                                                    class="form-control text-end fw-bold input-valor input-receita"
                                                    placeholder="0.00">
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
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
                                <?php foreach ($variaveisDespesas as $item): ?>
                                    <?php $isMult = ($item['tipo_calculo'] ?? 'simples') === 'multiplicador'; ?>
                                    <div class="p-3 bg-light rounded-3 border d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                                        <div>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="fw-semibold text-dark"><?php echo htmlspecialchars($item['nome']); ?></span>
                                                <?php if ($isMult): ?>
                                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle small py-1" style="font-size: 0.72rem;">
                                                        <i class="bi bi-calculator me-1"></i> Multiplicador
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
                                                <!-- Quantidade -->
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

                                                <!-- Valor Unitário -->
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

                                                <!-- Total Calculado -->
                                                <div class="input-group input-group-sm" style="width: 130px;">
                                                    <span class="input-group-text bg-light text-secondary px-2">R$</span>
                                                    <input type="number" step="0.01" min="0" readonly
                                                        name="valores[<?php echo $item['id']; ?>][valor]"
                                                        id="total_var_<?php echo $item['id']; ?>"
                                                        value="<?php echo number_format($item['valor'] ?? 0, 2, '.', ''); ?>"
                                                        class="form-control text-end fw-bold bg-light input-valor input-despesa"
                                                        placeholder="0.00">
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div class="input-group" style="max-width: 200px;">
                                                <span class="input-group-text bg-white border-end-0 text-secondary">R$</span>
                                                <input type="number" step="0.01" min="0" 
                                                    name="valores[<?php echo $item['id']; ?>]"
                                                    value="<?php echo number_format($item['valor'] ?? $item['valor_padrao'], 2, '.', ''); ?>"
                                                    class="form-control text-end fw-bold input-valor input-despesa"
                                                    placeholder="0.00">
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
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

    <!-- Modal Nova Variável Personalizada -->
    <div class="modal fade" id="modalNovaVariavel" tabindex="-1" aria-labelledby="modalNovaVariavelLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold" id="modalNovaVariavelLabel">Nova Variável de Orçamento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <form action="/variaveis/adicionar" method="POST">
                    <input type="hidden" name="origem" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI'] ?? '/orcamento'); ?>">
                    <div class="modal-body p-4">

                        <!-- Alerta de Erro de Validação -->
                        <?php if (!empty($_SESSION['erro_modal_variavel'])): ?>
                            <div class="alert alert-danger py-2 px-3 small d-flex align-items-center mb-3 rounded-3" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2 fs-6"></i>
                                <span><?php echo htmlspecialchars($_SESSION['erro_modal_variavel']); ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Tipo do Campo</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="tipo" id="modalTipoReceita" value="receita" checked>
                                <label class="btn btn-outline-success" for="modalTipoReceita"><i class="bi bi-plus-circle me-1"></i> Receita</label>

                                <input type="radio" class="btn-check" name="tipo" id="modalTipoDespesa" value="despesa">
                                <label class="btn btn-outline-danger" for="modalTipoDespesa"><i class="bi bi-dash-circle me-1"></i> Despesa</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="nomeVariavel" class="form-label small fw-semibold text-secondary">Nome do Campo</label>
                            <input type="text" class="form-control" id="nomeVariavel" name="nome" placeholder="Ex: Ajuda Mãe, Netflix, Freelance, Academia..." required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Modo de Cálculo</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="tipo_calculo" id="modalCalcSimples" value="simples" checked>
                                    <label class="btn btn-outline-secondary w-100 text-start p-2" for="modalCalcSimples">
                                        <span class="fw-bold d-block small"><i class="bi bi-cash me-1"></i> Valor Fixo</span>
                                        <span class="text-muted" style="font-size: 0.72rem;">Valor único (R$)</span>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="tipo_calculo" id="modalCalcMultiplicador" value="multiplicador">
                                    <label class="btn btn-outline-secondary w-100 text-start p-2" for="modalCalcMultiplicador">
                                        <span class="fw-bold d-block small"><i class="bi bi-calculator me-1"></i> Multiplicador</span>
                                        <span class="text-muted" style="font-size: 0.72rem;">Qtd &times; Valor</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 d-none" id="modalContainerMultiplicador">
                            <label for="modalLabelMultiplicador" class="form-label small fw-semibold text-secondary">Rótulo da Quantidade</label>
                            <input type="text" class="form-control" id="modalLabelMultiplicador" name="label_multiplicador" placeholder="Ex: Dias, Horas, Diárias" value="Dias">
                            <small class="text-secondary">Nome do multiplicador (ex: dias trabalhados para calcular o repasse à mãe).</small>
                        </div>

                        <div class="mb-3">
                            <label for="valorPadrao" class="form-label small fw-semibold text-secondary" id="modalLabelValorPadrao">Valor Padrão Sugerido (R$)</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="valorPadrao" name="valor_padrao" placeholder="0.00">
                            <small class="text-secondary" id="modalDicaValorPadrao">Este valor será sugerido automaticamente em novos meses.</small>
                        </div>

                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" role="switch" id="isFixo" name="is_fixo" value="1" checked>
                            <label class="form-check-label small" for="isFixo">Repetir todo mês no orçamento</label>
                        </div>
                    </div>
                    <div class="modal-footer border-top">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary px-4 fw-semibold">Cadastrar Variável</button>
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

        // Toggle do Modo de Cálculo no Modal
        const modalCalcSimples = document.getElementById('modalCalcSimples');
        const modalCalcMultiplicador = document.getElementById('modalCalcMultiplicador');
        const modalContainerMultiplicador = document.getElementById('modalContainerMultiplicador');
        const modalLabelValorPadrao = document.getElementById('modalLabelValorPadrao');
        const modalDicaValorPadrao = document.getElementById('modalDicaValorPadrao');

        function atualizarModoModal() {
            if (modalCalcMultiplicador && modalCalcMultiplicador.checked) {
                modalContainerMultiplicador.classList.remove('d-none');
                modalLabelValorPadrao.innerText = 'Valor Unitário Padrão (R$)';
                modalDicaValorPadrao.innerText = 'Ex: Valor pago por dia ou unidade trabalhada.';
            } else if (modalContainerMultiplicador) {
                modalContainerMultiplicador.classList.add('d-none');
                modalLabelValorPadrao.innerText = 'Valor Padrão Sugerido (R$)';
                modalDicaValorPadrao.innerText = 'Este valor será sugerido automaticamente em novos meses.';
            }
        }

        if (modalCalcSimples && modalCalcMultiplicador) {
            modalCalcSimples.addEventListener('change', atualizarModoModal);
            modalCalcMultiplicador.addEventListener('change', atualizarModoModal);
        }

        // Configurar botão de modal para pré-selecionar tipo
        const modalNovaVariavel = document.getElementById('modalNovaVariavel');
        if (modalNovaVariavel) {
            modalNovaVariavel.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const tipo = button ? button.getAttribute('data-tipo') : null;
                if (tipo === 'despesa') {
                    document.getElementById('modalTipoDespesa').checked = true;
                } else if (tipo === 'receita') {
                    document.getElementById('modalTipoReceita').checked = true;
                }
            });
        }

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
