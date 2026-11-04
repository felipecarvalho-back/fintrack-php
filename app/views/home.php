<?php
$usuario = $_SESSION['usuario'] ?? null;
$nomeCompleto = $usuario['nomeCompleto'] ?? 'Usuário FinTrack';
$primeiroNome = explode(' ', trim($nomeCompleto))[0];
$iniciais = strtoupper(substr($primeiroNome, 0, 1) . (isset(explode(' ', trim($nomeCompleto))[1]) ? substr(explode(' ', trim($nomeCompleto))[1], 0, 1) : ''));
$emailUsuario = $usuario['email'] ?? 'usuario@fintrack.com';
?>
<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($titulo ?? 'Painel Financeiro - FinTrack'); ?></title>

    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="/css/dashboard.css">
</head>

<body>

    <!-- Barra de Navegação Superior -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top py-2">
        <div class="container-fluid px-3 px-lg-4">

            <!-- Marca / Logo -->
            <a class="navbar-brand d-flex align-items-center gap-2" href="/home">
                <span class="brand-icon-sm">
                    <i class="bi bi-wallet2"></i>
                </span>
                <span class="fw-bold fs-5 text-dark tracking-tight">Fin<span class="text-primary">Track</span></span>
            </a>

            <!-- Botão Mobile -->
            <button class="navbar-toggler border-0 shadow-none p-1" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false"
                aria-label="Alternar navegação">
                <i class="bi bi-list fs-2"></i>
            </button>

            <!-- Links e Menu -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4 gap-lg-1">
                    <li class="nav-item">
                        <a class="nav-link active fw-semibold text-primary" href="/home">
                            <i class="bi bi-grid-fill me-1"></i> Dashboard
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
                        <a class="nav-link text-secondary fw-medium" href="/variaveis">
                            <i class="bi bi-sliders me-1"></i> Minhas Variáveis
                        </a>
                    </li>
                </ul>

                <!-- Área do Usuário -->
                <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
                    <!-- Seletor de Período -->
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light border dropdown-toggle fw-medium text-secondary"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-calendar3 me-1"></i> Este Mês (Novembro)
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                            <li><a class="dropdown-item active" href="#">Novembro 2026</a></li>
                            <li><a class="dropdown-item" href="#">Outubro 2026</a></li>
                            <li><a class="dropdown-item" href="#">Setembro 2026</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Personalizado...</a></li>
                        </ul>
                    </div>


                    <!-- Perfil Dropdown -->
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="avatar-sm me-2"><?php echo htmlspecialchars($iniciais); ?></span>
                            <div class="d-none d-md-block text-start me-1">
                                <span class="d-block fw-semibold small text-truncate" style="max-width: 130px;">
                                    <?php echo htmlspecialchars($primeiroNome); ?>
                                </span>
                                <span class="d-block text-secondary small" style="font-size: 0.75rem;">
                                    Minha Conta
                                </span>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                            <li class="px-3 py-2 border-bottom">
                                <p class="mb-0 fw-semibold text-dark small"><?php echo htmlspecialchars($nomeCompleto); ?></p>
                                <p class="mb-0 text-muted small" style="font-size: 0.75rem;"><?php echo htmlspecialchars($emailUsuario); ?></p>
                            </li>
                            <li><a class="dropdown-item py-2 small" href="#"><i class="bi bi-person me-2 text-secondary"></i> Meu Perfil</a></li>
                            <li><a class="dropdown-item py-2 small" href="/variaveis"><i class="bi bi-sliders me-2 text-secondary"></i> Minhas Variáveis</a></li>
                            <li><a class="dropdown-item py-2 small" href="#"><i class="bi bi-gear me-2 text-secondary"></i> Configurações</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item py-2 small text-danger" href="/logout">
                                    <i class="bi bi-box-arrow-right me-2"></i> Encerrar Sessão
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </nav>

    <!-- Conteúdo Principal -->
    <main class="container-fluid px-3 px-lg-4 py-4">

        <!-- Boas-vindas e Ações Rápidas -->
        <div class="row align-items-center g-3 mb-4">
            <div class="col-12 col-md-6">
                <h3 class="fw-bold mb-1 text-dark">Olá, <?php echo htmlspecialchars($primeiroNome); ?>! 👋</h3>
                <p class="text-secondary small mb-0">Acompanhe seu fluxo de caixa e alcance seus objetivos financeiros.</p>
            </div>
            <div class="col-12 col-md-6 text-md-end d-flex flex-wrap gap-2 justify-content-md-end">
                <button class="btn btn-outline-secondary btn-action bg-white border" type="button">
                    <i class="bi bi-download"></i> Exportar
                </button>
                <button class="btn btn-success btn-action text-white" type="button" data-bs-toggle="modal" data-bs-target="#modalNovaTransacao">
                    <i class="bi bi-plus-circle"></i> Nova Receita
                </button>
                <button class="btn btn-danger btn-action text-white" type="button" data-bs-toggle="modal" data-bs-target="#modalNovaTransacao">
                    <i class="bi bi-dash-circle"></i> Nova Despesa
                </button>
            </div>
        </div>

        <!-- Cards de Resumo (KPIs) -->
        <div class="row g-3 mb-4">
            <!-- Saldo Geral -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="text-secondary small fw-semibold">Saldo Atual</span>
                        <div class="stat-icon stat-icon-primary">
                            <i class="bi bi-wallet2"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">R$ 12.450,80</h3>
                    <div class="d-flex align-items-center small text-success">
                        <i class="bi bi-arrow-up-right me-1"></i>
                        <span class="fw-semibold">+14.2%</span>
                        <span class="text-secondary ms-1">vs mês anterior</span>
                    </div>
                </div>
            </div>

            <!-- Total de Receitas -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="text-secondary small fw-semibold">Receitas do Mês</span>
                        <div class="stat-icon stat-icon-success">
                            <i class="bi bi-arrow-down-left"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">R$ 8.920,00</h3>
                    <div class="d-flex align-items-center small text-success">
                        <i class="bi bi-check-circle me-1"></i>
                        <span>3 entradas confirmadas</span>
                    </div>
                </div>
            </div>

            <!-- Total de Despesas -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="text-secondary small fw-semibold">Despesas do Mês</span>
                        <div class="stat-icon stat-icon-danger">
                            <i class="bi bi-arrow-up-right"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">R$ 3.469,20</h3>
                    <div class="d-flex align-items-center small text-danger">
                        <i class="bi bi-exclamation-circle me-1"></i>
                        <span>38% da renda mensal</span>
                    </div>
                </div>
            </div>

            <!-- Economia / Meta -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="text-secondary small fw-semibold">Meta de Reserva</span>
                        <div class="stat-icon stat-icon-warning">
                            <i class="bi bi-piggy-bank"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">R$ 5.451,60</h3>
                    <div class="mt-2">
                        <div class="progress" role="progressbar" style="height: 6px;" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar bg-warning" style="width: 72%"></div>
                        </div>
                        <span class="d-block text-secondary small mt-1" style="font-size: 0.75rem;">72% da meta alcançada (R$ 7.500)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seção Principal: Transações Recentes e Visão Lateral -->
        <div class="row g-4">

            <!-- Tabela de Transações Recentes (8 colunas) -->
            <div class="col-12 col-xl-8">
                <div class="card-dashboard h-100">
                    <div class="card-dashboard-header">
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">Transações Recentes</h5>
                            <span class="text-secondary small">Suas últimas movimentações registradas</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <a href="#" class="btn btn-sm btn-light border small fw-medium">
                                Ver todas <i class="bi bi-chevron-right ms-1"></i>
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-custom align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Transação</th>
                                    <th>Categoria</th>
                                    <th>Data</th>
                                    <th>Status</th>
                                    <th class="text-end">Valor</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Item 1 -->
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="transaction-icon bg-success-subtle text-success">
                                                <i class="bi bi-cash-stack"></i>
                                            </div>
                                            <div>
                                                <span class="d-block fw-semibold text-dark">Salário Mensal</span>
                                                <span class="text-secondary small">Tech Solutions Ltda.</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill bg-light text-secondary border">
                                            <i class="bi bi-briefcase me-1"></i> Renda
                                        </span>
                                    </td>
                                    <td class="text-secondary small">02 Nov, 2026</td>
                                    <td>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                            Concluído
                                        </span>
                                    </td>
                                    <td class="text-end fw-bold text-success">+ R$ 6.500,00</td>
                                </tr>

                                <!-- Item 2 -->
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="transaction-icon bg-danger-subtle text-danger">
                                                <i class="bi bi-cart3"></i>
                                            </div>
                                            <div>
                                                <span class="d-block fw-semibold text-dark">Supermercado</span>
                                                <span class="text-secondary small">Compras do Mês</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill bg-light text-secondary border">
                                            <i class="bi bi-basket me-1"></i> Alimentação
                                        </span>
                                    </td>
                                    <td class="text-secondary small">01 Nov, 2026</td>
                                    <td>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                            Concluído
                                        </span>
                                    </td>
                                    <td class="text-end fw-bold text-danger">- R$ 428,50</td>
                                </tr>

                                <!-- Item 3 -->
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="transaction-icon bg-info-subtle text-info">
                                                <i class="bi bi-laptop"></i>
                                            </div>
                                            <div>
                                                <span class="d-block fw-semibold text-dark">Projeto Freelance</span>
                                                <span class="text-secondary small">Design UI Web</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill bg-light text-secondary border">
                                            <i class="bi bi-code-slash me-1"></i> Serviços
                                        </span>
                                    </td>
                                    <td class="text-secondary small">29 Out, 2026</td>
                                    <td>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                            Concluído
                                        </span>
                                    </td>
                                    <td class="text-end fw-bold text-success">+ R$ 1.800,00</td>
                                </tr>

                                <!-- Item 4 -->
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="transaction-icon bg-warning-subtle text-warning">
                                                <i class="bi bi-tv"></i>
                                            </div>
                                            <div>
                                                <span class="d-block fw-semibold text-dark">Streaming & Apps</span>
                                                <span class="text-secondary small">Netflix e Spotify</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill bg-light text-secondary border">
                                            <i class="bi bi-controller me-1"></i> Assinaturas
                                        </span>
                                    </td>
                                    <td class="text-secondary small">28 Out, 2026</td>
                                    <td>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                            Concluído
                                        </span>
                                    </td>
                                    <td class="text-end fw-bold text-danger">- R$ 79,90</td>
                                </tr>

                                <!-- Item 5 -->
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="transaction-icon bg-danger-subtle text-danger">
                                                <i class="bi bi-fuel-pump"></i>
                                            </div>
                                            <div>
                                                <span class="d-block fw-semibold text-dark">Combustível</span>
                                                <span class="text-secondary small">Posto Shell</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill bg-light text-secondary border">
                                            <i class="bi bi-car-front me-1"></i> Transporte
                                        </span>
                                    </td>
                                    <td class="text-secondary small">25 Out, 2026</td>
                                    <td>
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1">
                                            Pendente
                                        </span>
                                    </td>
                                    <td class="text-end fw-bold text-danger">- R$ 180,00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Painel Lateral: Cartão & Categorias (4 colunas) -->
            <div class="col-12 col-xl-4 d-flex flex-column gap-4">

                <!-- Prévia do Cartão de Crédito -->
                <div class="card-dashboard p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="fw-bold mb-0 text-dark">Cartão Principal</h6>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle">Físico & Virtual</span>
                    </div>

                    <div class="credit-card-preview mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="credit-card-chip"></span>
                            <i class="bi bi-wifi fs-4 opacity-75"></i>
                        </div>
                        <div class="mb-4">
                            <span class="d-block text-white-50 small" style="font-size: 0.7rem; letter-spacing: 0.1em;">NÚMERO DO CARTÃO</span>
                            <span class="fs-5 fw-bold letter-spacing-1">•••• •••• •••• 4892</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-end">
                            <div>
                                <span class="d-block text-white-50" style="font-size: 0.65rem;">TITULAR</span>
                                <span class="fw-semibold small text-uppercase"><?php echo htmlspecialchars($nomeCompleto); ?></span>
                            </div>
                            <div class="text-end">
                                <span class="d-block text-white-50" style="font-size: 0.65rem;">VALIDADE</span>
                                <span class="fw-semibold small">08/30</span>
                            </div>
                        </div>
                    </div>

                    <!-- Limite do Cartão -->
                    <div>
                        <div class="d-flex justify-content-between small text-secondary mb-1">
                            <span>Limite Usado: <strong>R$ 1.180,00</strong></span>
                            <span>Total: R$ 5.000,00</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 23.6%"></div>
                        </div>
                    </div>
                </div>

                <!-- Despesas por Categoria -->
                <div class="card-dashboard p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="fw-bold mb-0 text-dark">Gastos por Categoria</h6>
                        <span class="text-muted small">Este mês</span>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        <!-- Categoria 1 -->
                        <div>
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="text-dark fw-medium"><i class="bi bi-house-door me-1 text-primary"></i> Moradia & Contas</span>
                                <span class="text-dark fw-bold">R$ 1.450,00 (42%)</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-primary" style="width: 42%"></div>
                            </div>
                        </div>

                        <!-- Categoria 2 -->
                        <div>
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="text-dark fw-medium"><i class="bi bi-cart me-1 text-success"></i> Alimentação</span>
                                <span class="text-dark fw-bold">R$ 980,00 (28%)</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success" style="width: 28%"></div>
                            </div>
                        </div>

                        <!-- Categoria 3 -->
                        <div>
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="text-dark fw-medium"><i class="bi bi-car-front me-1 text-warning"></i> Transporte</span>
                                <span class="text-dark fw-bold">R$ 540,00 (15%)</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-warning" style="width: 15%"></div>
                            </div>
                        </div>

                        <!-- Categoria 4 -->
                        <div>
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="text-dark fw-medium"><i class="bi bi-controller me-1 text-danger"></i> Lazer & Outros</span>
                                <span class="text-dark fw-bold">R$ 499,20 (15%)</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-danger" style="width: 15%"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </main>

    <!-- Modal de Nova Transação (Apenas Mockup Visual) -->
    <div class="modal fade" id="modalNovaTransacao" tabindex="-1" aria-labelledby="modalNovaTransacaoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold" id="modalNovaTransacaoLabel">Nova Movimentação</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body p-4">
                    <form>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Tipo</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="tipoTransacao" id="tipoReceita" autocomplete="off" checked>
                                <label class="btn btn-outline-success" for="tipoReceita"><i class="bi bi-plus-circle me-1"></i> Receita</label>

                                <input type="radio" class="btn-check" name="tipoTransacao" id="tipoDespesa" autocomplete="off">
                                <label class="btn btn-outline-danger" for="tipoDespesa"><i class="bi bi-dash-circle me-1"></i> Despesa</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="descricao" class="form-label small fw-semibold text-secondary">Descrição</label>
                            <input type="text" class="form-control" id="descricao" placeholder="Ex: Supermercado, Salário, Internet...">
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label for="valor" class="form-label small fw-semibold text-secondary">Valor (R$)</label>
                                <input type="number" step="0.01" class="form-control" id="valor" placeholder="0,00">
                            </div>
                            <div class="col-6">
                                <label for="data" class="form-label small fw-semibold text-secondary">Data</label>
                                <input type="date" class="form-control" id="data" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="categoria" class="form-label small fw-semibold text-secondary">Categoria</label>
                            <select class="form-select" id="categoria">
                                <option value="alimentacao">Alimentação</option>
                                <option value="moradia">Moradia</option>
                                <option value="transporte">Transporte</option>
                                <option value="lazer">Lazer</option>
                                <option value="salario">Salário / Renda</option>
                                <option value="outros">Outros</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary px-4">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5.3.3 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

</body>

</html>
