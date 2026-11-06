<?php
$usuario = $_SESSION['usuario'] ?? null;
$nomeCompleto = $usuario['nomeCompleto'] ?? 'Usuário FinTrack';
$primeiroNome = explode(' ', trim($nomeCompleto))[0];
$iniciais = strtoupper(substr($primeiroNome, 0, 1) . (isset(explode(' ', trim($nomeCompleto))[1]) ? substr(explode(' ', trim($nomeCompleto))[1], 0, 1) : ''));
$emailUsuario = $usuario['email'] ?? 'usuario@fintrack.com';

$mesesNomes = [
    1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
    5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
    9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
];
$nomeMes = $mesesNomes[$mesAtual ?? (int)date('n')] ?? 'Mês Atual';
$ano = $anoAtual ?? (int)date('Y');

// Porcentagem de comprometimento da renda
$pctDespesas = ($totalReceitas ?? 0) > 0 ? min(100, round((($totalDespesas ?? 0) / $totalReceitas) * 100)) : 0;
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
                    <!-- Seletor do Mês Atual -->
                    <a href="/orcamento/<?php echo $ano; ?>/<?php echo $mesAtual ?? date('n'); ?>" class="btn btn-sm btn-light border fw-medium text-secondary">
                        <i class="bi bi-calendar3 me-1 text-primary"></i> <?php echo $nomeMes; ?> <?php echo $ano; ?>
                    </a>

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
                            <li><a class="dropdown-item py-2 small" href="/orcamento"><i class="bi bi-calendar2-range me-2 text-secondary"></i> Orçamento Mensal</a></li>
                            <li><a class="dropdown-item py-2 small" href="/variaveis"><i class="bi bi-sliders me-2 text-secondary"></i> Minhas Variáveis</a></li>
                            <li><a class="dropdown-item py-2 small" href="/caixinhas"><i class="bi bi-piggy-bank me-2 text-secondary"></i> Caixinhas</a></li>
                            <li><a class="dropdown-item py-2 small" href="/investimentos"><i class="bi bi-graph-up-arrow me-2 text-secondary"></i> Investimentos</a></li>
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
                <p class="text-secondary small mb-0">Visão consolidada das suas finanças em <strong><?php echo $nomeMes; ?> de <?php echo $ano; ?></strong>.</p>
            </div>
            <div class="col-12 col-md-6 text-md-end d-flex flex-wrap gap-2 justify-content-md-end">
                <a href="/orcamento" class="btn btn-primary btn-action text-white shadow-sm">
                    <i class="bi bi-calendar2-range me-1"></i> Abrir Orçamento do Mês
                </a>
                <a href="/caixinhas" class="btn btn-action btn-caixinhas-action shadow-sm">
                    <i class="bi bi-piggy-bank me-1"></i> Caixinhas
                </a>
            </div>
        </div>

        <!-- 4 Cards de Resumo Consolidado (Dados Reais) -->
        <div class="row g-3 mb-4">
            <!-- Saldo Geral -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="text-secondary small fw-semibold text-uppercase tracking-wider">Saldo Previsto</span>
                        <div class="stat-icon stat-icon-primary">
                            <i class="bi bi-wallet2"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold <?php echo (($saldoMes ?? 0) >= 0) ? 'text-primary' : 'text-danger'; ?> mb-1">
                        R$ <?php echo number_format($saldoMes ?? 0.0, 2, ',', '.'); ?>
                    </h3>
                    <div class="d-flex align-items-center small text-secondary">
                        <i class="bi bi-check2 me-1 text-success"></i>
                        <span>Receitas menos despesas</span>
                    </div>
                </div>
            </div>

            <!-- Total de Receitas -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="text-secondary small fw-semibold text-uppercase tracking-wider">Receitas do Mês</span>
                        <div class="stat-icon stat-icon-success">
                            <i class="bi bi-arrow-down-left"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-success mb-1">R$ <?php echo number_format($totalReceitas ?? 0.0, 2, ',', '.'); ?></h3>
                    <div class="d-flex align-items-center small text-success">
                        <i class="bi bi-plus-circle me-1"></i>
                        <span>Entradas planejadas</span>
                    </div>
                </div>
            </div>

            <!-- Total de Despesas -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="text-secondary small fw-semibold text-uppercase tracking-wider">Despesas do Mês</span>
                        <div class="stat-icon stat-icon-danger">
                            <i class="bi bi-arrow-up-right"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-danger mb-1">R$ <?php echo number_format($totalDespesas ?? 0.0, 2, ',', '.'); ?></h3>
                    <div class="d-flex align-items-center small text-secondary">
                        <span><?php echo $pctDespesas; ?>% da renda comprometida</span>
                    </div>
                </div>
            </div>

            <!-- Total em Caixinhas / Metas -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="text-secondary small fw-semibold text-uppercase tracking-wider">Total em Caixinhas</span>
                        <div class="stat-icon stat-icon-warning">
                            <i class="bi bi-piggy-bank"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">R$ <?php echo number_format($totalCaixinhas ?? 0.0, 2, ',', '.'); ?></h3>
                    <div class="d-flex align-items-center small text-secondary">
                        <i class="bi bi-shield-check me-1 text-warning"></i>
                        <span><?php echo count($caixinhas ?? []); ?> metas ativas</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seção Principal: 2 Colunas com Conteúdo Real -->
        <div class="row g-4 mb-4">

            <!-- Coluna Esquerda: Cartão de Saúde do Mês & Ações Rápidas -->
            <div class="col-12 col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="fw-bold mb-1 text-dark">Orçamento de <?php echo $nomeMes; ?></h5>
                            <span class="text-secondary small">Balanço das suas entradas e saídas planejadas</span>
                        </div>
                        <a href="/orcamento/<?php echo $ano; ?>/<?php echo $mesAtual ?? date('n'); ?>" class="btn btn-sm btn-outline-primary px-3">
                            Editar Valores <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>

                    <!-- Barra de Saúde do Mês -->
                    <div class="p-3 bg-light rounded-3 mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-semibold text-dark small">Comprometimento de Renda</span>
                            <span class="fw-bold <?php echo ($pctDespesas > 80) ? 'text-danger' : 'text-success'; ?> small">
                                <?php echo $pctDespesas; ?>%
                            </span>
                        </div>
                        <div class="progress" style="height: 10px; border-radius: 6px;">
                            <div class="progress-bar <?php echo ($pctDespesas > 80) ? 'bg-danger' : (($pctDespesas > 60) ? 'bg-warning' : 'bg-success'); ?>" 
                                role="progressbar" 
                                style="width: <?php echo min(100, $pctDespesas); ?>%"></div>
                        </div>
                        <div class="d-flex justify-content-between text-secondary mt-2" style="font-size: 0.75rem;">
                            <span>R$ 0,00</span>
                            <span>R$ <?php echo number_format($totalReceitas ?? 0.0, 2, ',', '.'); ?> (100%)</span>
                        </div>
                    </div>

                    <!-- Acessos Rápidos do Módulo -->
                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <a href="/variaveis" class="text-decoration-none">
                                <div class="p-3 border rounded-3 bg-white h-100 d-flex align-items-center gap-3 transition-hover">
                                    <div class="stat-icon-sm rounded-3 p-2 bg-primary-subtle text-primary">
                                        <i class="bi bi-sliders fs-5"></i>
                                    </div>
                                    <div>
                                        <span class="d-block fw-semibold text-dark">Minhas Variáveis</span>
                                        <span class="text-secondary small" style="font-size: 0.8rem;">Adicionar ou remover campos fixos</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-12 col-sm-6">
                            <a href="/investimentos" class="text-decoration-none">
                                <div class="p-3 border rounded-3 bg-white h-100 d-flex align-items-center gap-3 transition-hover">
                                    <div class="stat-icon-sm rounded-3 p-2 bg-success-subtle text-success">
                                        <i class="bi bi-graph-up-arrow fs-5"></i>
                                    </div>
                                    <div>
                                        <span class="d-block fw-semibold text-dark">Carteira de Ativos</span>
                                        <span class="text-secondary small" style="font-size: 0.8rem;">
                                            Patrimônio: R$ <?php echo number_format($patrimonioInvest ?? 0.0, 2, ',', '.'); ?>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Coluna Direita: Metas e Caixinhas Reais -->
            <div class="col-12 col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">Suas Caixinhas</h5>
                            <span class="text-secondary small">Progresso dos seus objetivos</span>
                        </div>
                        <a href="/caixinhas" class="btn btn-sm btn-link text-primary p-0 text-decoration-none fw-semibold">
                            Ver todas <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>

                    <?php if (empty($caixinhas)): ?>
                        <div class="text-center py-4 my-auto">
                            <div class="stat-icon stat-icon-warning mx-auto mb-3">
                                <i class="bi bi-piggy-bank fs-4"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1">Nenhuma caixinha criada</h6>
                            <p class="text-secondary small mb-3">Comece separando dinheiro para sua reserva de emergência ou viagem.</p>
                            <a href="/caixinhas" class="btn btn-sm btn-primary px-3">
                                <i class="bi bi-plus-lg me-1"></i> Criar Caixinha
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="d-flex flex-column gap-3">
                            <?php foreach (array_slice($caixinhas, 0, 4) as $item): 
                                $prog = $item['target_amount'] > 0 ? min(100, round(($item['current_amount'] / $item['target_amount']) * 100)) : 0;
                            ?>
                                <a href="/caixinhas" class="caixinha-preview-item text-decoration-none">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi <?php echo htmlspecialchars($item['icon'] ?? 'bi-piggy-bank'); ?> fs-5" style="color: <?php echo htmlspecialchars($item['color'] ?? '#0d6efd'); ?>;"></i>
                                            <span class="fw-semibold text-dark small"><?php echo htmlspecialchars($item['name']); ?></span>
                                        </div>
                                        <span class="fw-bold text-dark small">R$ <?php echo number_format($item['current_amount'], 2, ',', '.'); ?></span>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar" style="width: <?php echo $prog; ?>%; background-color: <?php echo htmlspecialchars($item['color'] ?? '#0d6efd'); ?>;"></div>
                                    </div>
                                    <div class="d-flex justify-content-between text-secondary mt-1" style="font-size: 0.72rem;">
                                        <span>Meta: R$ <?php echo number_format($item['target_amount'], 2, ',', '.'); ?></span>
                                        <span class="fw-semibold"><?php echo $prog; ?>%</span>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>

    </main>

    <!-- Bootstrap 5.3.3 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

</body>

</html>
