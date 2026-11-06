<?php
$usuario = $_SESSION['usuario'] ?? null;
$nomeCompleto = $usuario['nomeCompleto'] ?? 'Usuário FinTrack';
$primeiroNome = explode(' ', trim($nomeCompleto))[0];
$iniciais = strtoupper(substr($primeiroNome, 0, 1) . (isset(explode(' ', trim($nomeCompleto))[1]) ? substr(explode(' ', trim($nomeCompleto))[1], 0, 1) : ''));
$emailUsuario = $usuario['email'] ?? 'usuario@fintrack.com';

// Dados vindos do backend ou mocks para visualização prévia
$listaCaixinhas = $caixinhas ?? [
    [
        'id' => 1,
        'name' => 'Reserva de Emergência',
        'target_amount' => 15000.00,
        'current_amount' => 10500.00,
        'color' => '#10b981',
        'icon' => 'bi-shield-check'
    ],
    [
        'id' => 2,
        'name' => 'Viagem de Férias',
        'target_amount' => 5000.00,
        'current_amount' => 2850.00,
        'color' => '#0d6efd',
        'icon' => 'bi-airplane'
    ],
    [
        'id' => 3,
        'name' => 'Novo Notebook / Setup',
        'target_amount' => 7000.00,
        'current_amount' => 7000.00,
        'color' => '#8b5cf6',
        'icon' => 'bi-laptop'
    ],
    [
        'id' => 4,
        'name' => 'Troca de Carro',
        'target_amount' => 25000.00,
        'current_amount' => 4200.00,
        'color' => '#f59e0b',
        'icon' => 'bi-car-front'
    ],
];

$totalGuardado = array_sum(array_column($listaCaixinhas, 'current_amount'));
$totalMetas = array_sum(array_column($listaCaixinhas, 'target_amount'));
$metasConcluidas = count(array_filter($listaCaixinhas, fn($c) => $c['current_amount'] >= $c['target_amount']));
?>
<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Caixinhas & Metas - FinTrack</title>
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
                        <a class="nav-link text-secondary fw-medium" href="/orcamento">
                            <i class="bi bi-calendar2-range me-1"></i> Orçamento Mensal
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active fw-semibold text-primary" href="/caixinhas">
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

        <!-- Topo -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h1 class="h3 fw-bold text-dark mb-0">Caixinhas & Metas</h1>
                <p class="text-secondary small mb-0 mt-1">Separe e acompanhe o dinheiro reservado para os seus sonhos e objetivos.</p>
            </div>
            <div>
                <button type="button" class="btn btn-primary px-4 shadow-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#modalNovaCaixinha">
                    <i class="bi bi-plus-lg me-1"></i> Nova Caixinha
                </button>
            </div>
        </div>

        <!-- 3 Cards de Resumo -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <div class="stat-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-secondary small fw-medium text-uppercase tracking-wider">Total Guardado</span>
                            <h3 class="fw-bold text-success mb-0 mt-1">R$ <?php echo number_format($totalGuardado, 2, ',', '.'); ?></h3>
                        </div>
                        <div class="stat-icon stat-icon-success">
                            <i class="bi bi-piggy-bank"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="stat-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-secondary small fw-medium text-uppercase tracking-wider">Objetivo Total das Metas</span>
                            <h3 class="fw-bold text-primary mb-0 mt-1">R$ <?php echo number_format($totalMetas, 2, ',', '.'); ?></h3>
                        </div>
                        <div class="stat-icon stat-icon-primary">
                            <i class="bi bi-bullseye"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="stat-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-secondary small fw-medium text-uppercase tracking-wider">Metas Atingidas</span>
                            <h3 class="fw-bold text-dark mb-0 mt-1"><?php echo $metasConcluidas; ?> <span class="text-secondary fs-6 fw-normal">de <?php echo count($listaCaixinhas); ?></span></h3>
                        </div>
                        <div class="stat-icon stat-icon-warning">
                            <i class="bi bi-trophy"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid de Caixinhas -->
        <div class="row g-4">
            <?php foreach ($listaCaixinhas as $caixa): 
                $progresso = $caixa['target_amount'] > 0 ? min(100, round(($caixa['current_amount'] / $caixa['target_amount']) * 100)) : 0;
                $concluida = $caixa['current_amount'] >= $caixa['target_amount'];
            ?>
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-4 transition-hover">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="stat-icon" style="background-color: <?php echo htmlspecialchars($caixa['color']); ?>20; color: <?php echo htmlspecialchars($caixa['color']); ?>;">
                                    <i class="bi <?php echo htmlspecialchars($caixa['icon']); ?> fs-4"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-0 text-dark"><?php echo htmlspecialchars($caixa['name']); ?></h5>
                                    <?php if ($concluida): ?>
                                        <span class="badge bg-success-subtle text-success small fw-semibold">
                                            <i class="bi bi-check-circle-fill me-1"></i> Meta Atingida!
                                        </span>
                                    <?php else: ?>
                                        <span class="text-secondary small">Meta: R$ <?php echo number_format($caixa['target_amount'], 2, ',', '.'); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="dropdown">
                                <button class="btn btn-sm btn-light border-0" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                    <li>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalEditarCaixinha<?php echo $caixa['id']; ?>">
                                            <i class="bi bi-pencil me-2"></i> Editar Meta
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="/caixinhas/excluir/<?php echo $caixa['id']; ?>" method="POST" onsubmit="return confirm('Deseja excluir esta caixinha?');">
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-trash me-2"></i> Excluir
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Valores -->
                        <div class="my-2">
                            <span class="text-secondary small d-block">Saldo Atual</span>
                            <h3 class="fw-bold text-dark mb-2">R$ <?php echo number_format($caixa['current_amount'], 2, ',', '.'); ?></h3>
                        </div>

                        <!-- Barra de Progresso -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between text-secondary small fw-medium mb-1">
                                <span>Progresso</span>
                                <span class="fw-bold text-dark"><?php echo $progresso; ?>%</span>
                            </div>
                            <div class="progress" style="height: 10px; border-radius: 6px;">
                                <div class="progress-bar" role="progressbar" 
                                    style="width: <?php echo $progresso; ?>%; background-color: <?php echo htmlspecialchars($caixa['color']); ?>;" 
                                    aria-valuenow="<?php echo $progresso; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                        <!-- Ações Rápidas de Depósito / Resgate -->
                        <div class="d-flex gap-2 mt-auto pt-2">
                            <button type="button" class="btn btn-light border flex-grow-1 btn-sm fw-semibold"
                                data-bs-toggle="modal" data-bs-target="#modalMovimentar"
                                data-id="<?php echo $caixa['id']; ?>" data-nome="<?php echo htmlspecialchars($caixa['name']); ?>" data-tipo="resgatar">
                                <i class="bi bi-dash me-1"></i> Resgatar
                            </button>
                            <button type="button" class="btn btn-primary flex-grow-1 btn-sm fw-semibold"
                                data-bs-toggle="modal" data-bs-target="#modalMovimentar"
                                data-id="<?php echo $caixa['id']; ?>" data-nome="<?php echo htmlspecialchars($caixa['name']); ?>" data-tipo="guardar">
                                <i class="bi bi-plus me-1"></i> Guardar
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </main>

    <!-- Modal Nova Caixinha -->
    <div class="modal fade" id="modalNovaCaixinha" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold">Criar Nova Caixinha</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="/caixinhas" method="POST">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Nome da Meta / Objetivo</label>
                            <input type="text" class="form-control" name="name" placeholder="Ex: Reserva, Viagem, Reforma, Casamento..." required>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label small fw-semibold text-secondary">Valor da Meta (R$)</label>
                                <input type="number" step="0.01" min="0" class="form-control" name="target_amount" placeholder="0.00" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-semibold text-secondary">Depósito Inicial (R$)</label>
                                <input type="number" step="0.01" min="0" class="form-control" name="current_amount" placeholder="0.00" value="0.00">
                            </div>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label small fw-semibold text-secondary">Cor Identificadora</label>
                                <select class="form-select" name="color">
                                    <option value="#0d6efd">Azul</option>
                                    <option value="#10b981">Verde</option>
                                    <option value="#8b5cf6">Roxo</option>
                                    <option value="#f59e0b">Laranja</option>
                                    <option value="#ec4899">Rosa</option>
                                    <option value="#06b6d4">Ciano</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-semibold text-secondary">Ícone</label>
                                <select class="form-select" name="icon">
                                    <option value="bi-piggy-bank">Porquinho</option>
                                    <option value="bi-shield-check">Segurança</option>
                                    <option value="bi-airplane">Viagem</option>
                                    <option value="bi-car-front">Carro</option>
                                    <option value="bi-house">Casa</option>
                                    <option value="bi-laptop">Notebook</option>
                                    <option value="bi-heart">Coração</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary px-4 fw-semibold">Criar Caixinha</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Movimentar (Guardar ou Resgatar) -->
    <div class="modal fade" id="modalMovimentar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-bottom">
                    <h6 class="modal-title fw-bold" id="tituloModalMovimentar">Movimentar Caixinha</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="/caixinhas/movimentar" method="POST">
                    <input type="hidden" name="caixinha_id" id="movimentarId">
                    <input type="hidden" name="operacao" id="movimentarOperacao" value="guardar">

                    <div class="modal-body p-4 text-center">
                        <p class="text-secondary small mb-3" id="movimentarNomeCaixinha">Caixinha</p>
                        
                        <div class="input-group mb-2">
                            <span class="input-group-text bg-white border-end-0">R$</span>
                            <input type="number" step="0.01" min="0.01" class="form-control form-control-lg text-end fw-bold" 
                                name="valor" placeholder="0.00" required autofocus>
                        </div>
                    </div>
                    <div class="modal-footer border-top p-2">
                        <button type="button" class="btn btn-sm btn-light flex-grow-1" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-sm btn-primary flex-grow-1 fw-semibold" id="btnConfirmarMovimentar">Confirmar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5.3.3 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <!-- Script para alimentar o Modal de Movimentação -->
    <script>
        const modalMovimentar = document.getElementById('modalMovimentar');
        modalMovimentar.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const nome = button.getAttribute('data-nome');
            const tipo = button.getAttribute('data-tipo');

            document.getElementById('movimentarId').value = id;
            document.getElementById('movimentarNomeCaixinha').innerText = nome;
            document.getElementById('movimentarOperacao').value = tipo;

            const titulo = document.getElementById('tituloModalMovimentar');
            const btn = document.getElementById('btnConfirmarMovimentar');

            if (tipo === 'resgatar') {
                titulo.innerText = 'Resgatar Valor';
                btn.className = 'btn btn-sm btn-danger flex-grow-1 fw-semibold';
                btn.innerText = 'Confirmar Resgate';
            } else {
                titulo.innerText = 'Guardar Valor';
                btn.className = 'btn btn-sm btn-success flex-grow-1 fw-semibold';
                btn.innerText = 'Confirmar Depósito';
            }
        });
    </script>
</body>

</html>
