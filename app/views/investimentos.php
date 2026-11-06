<?php
$usuario = $_SESSION['usuario'] ?? null;
$nomeCompleto = $usuario['nomeCompleto'] ?? 'Usuário FinTrack';
$primeiroNome = explode(' ', trim($nomeCompleto))[0];
$iniciais = strtoupper(substr($primeiroNome, 0, 1) . (isset(explode(' ', trim($nomeCompleto))[1]) ? substr(explode(' ', trim($nomeCompleto))[1], 0, 1) : ''));
$emailUsuario = $usuario['email'] ?? 'usuario@fintrack.com';

// Dados recebidos do backend ou mocks para pré-visualização
$listaInvestimentos = $investimentos ?? [
    [
        'id' => 1,
        'name' => 'Tesouro Selic 2029',
        'type' => 'Tesouro Direto',
        'institution' => 'Nubank',
        'amount_invested' => 5000.00,
        'current_value' => 5480.20,
    ],
    [
        'id' => 2,
        'name' => 'CDB 110% CDI',
        'type' => 'Renda Fixa',
        'institution' => 'Inter',
        'amount_invested' => 3000.00,
        'current_value' => 3240.50,
    ],
    [
        'id' => 3,
        'name' => 'MXRF11 - Fundo Imobiliário',
        'type' => 'FIIs',
        'institution' => 'XP Investimentos',
        'amount_invested' => 2500.00,
        'current_value' => 2610.00,
    ],
    [
        'id' => 4,
        'name' => 'Bitcoin / Ethereum',
        'type' => 'Cripto',
        'institution' => 'Binance',
        'amount_invested' => 1200.00,
        'current_value' => 1450.00,
    ],
];

$totalInvestido = array_sum(array_column($listaInvestimentos, 'amount_invested'));
$patrimonioAtual = array_sum(array_column($listaInvestimentos, 'current_value'));
$lucroTotal = $patrimonioAtual - $totalInvestido;
$rentabilidadePercentual = $totalInvestido > 0 ? ($lucroTotal / $totalInvestido) * 100 : 0;
?>
<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carteira de Investimentos - FinTrack</title>
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
                        <a class="nav-link text-secondary fw-medium" href="/caixinhas">
                            <i class="bi bi-piggy-bank me-1"></i> Caixinhas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active fw-semibold text-primary" href="/investimentos">
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
                <h1 class="h3 fw-bold text-dark mb-0">Carteira de Investimentos</h1>
                <p class="text-secondary small mb-0 mt-1">Acompanhe a rentabilidade e evolução do seu patrimônio investido.</p>
            </div>
            <div>
                <button type="button" class="btn btn-primary btn-sm px-3 shadow-sm fw-semibold d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modalNovoInvestimento">
                    <i class="bi bi-plus-lg"></i> Novo Ativo
                </button>
            </div>
        </div>

        <!-- 3 Cards de Resumo -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <div class="stat-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-secondary small fw-medium text-uppercase tracking-wider">Total Investido</span>
                            <h3 class="fw-bold text-dark mb-0 mt-1">R$ <?php echo number_format($totalInvestido, 2, ',', '.'); ?></h3>
                        </div>
                        <div class="stat-icon stat-icon-primary">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="stat-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-secondary small fw-medium text-uppercase tracking-wider">Patrimônio Atual</span>
                            <h3 class="fw-bold text-success mb-0 mt-1">R$ <?php echo number_format($patrimonioAtual, 2, ',', '.'); ?></h3>
                        </div>
                        <div class="stat-icon stat-icon-success">
                            <i class="bi bi-graph-up"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="stat-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-secondary small fw-medium text-uppercase tracking-wider">Rendimento Global</span>
                            <h3 class="fw-bold <?php echo $lucroTotal >= 0 ? 'text-success' : 'text-danger'; ?> mb-0 mt-1">
                                <?php echo ($lucroTotal >= 0 ? '+R$ ' : '-R$ ') . number_format(abs($lucroTotal), 2, ',', '.'); ?>
                                <span class="badge <?php echo $lucroTotal >= 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger'; ?> fs-6 fw-semibold align-middle ms-1">
                                    <?php echo ($lucroTotal >= 0 ? '+' : '') . number_format($rentabilidadePercentual, 2, ',', '.') . '%'; ?>
                                </span>
                            </h3>
                        </div>
                        <div class="stat-icon <?php echo $lucroTotal >= 0 ? 'stat-icon-success' : 'stat-icon-danger'; ?>">
                            <i class="bi <?php echo $lucroTotal >= 0 ? 'bi-arrow-up-right' : 'bi-arrow-down-right'; ?>"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabela de Investimentos -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-dark">Seus Ativos em Carteira</h5>
                <span class="badge bg-light text-secondary border"><?php echo count($listaInvestimentos); ?> Ativos</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary small text-uppercase">
                        <tr>
                            <th class="ps-4">Ativo</th>
                            <th>Tipo</th>
                            <th>Instituição</th>
                            <th class="text-end">Valor Aplicado</th>
                            <th class="text-end">Saldo Atual</th>
                            <th class="text-end">Rendimento</th>
                            <th class="text-center pe-4">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($listaInvestimentos as $item): 
                            $rendimento = $item['current_value'] - $item['amount_invested'];
                            $pct = $item['amount_invested'] > 0 ? ($rendimento / $item['amount_invested']) * 100 : 0;
                            $lucrou = $rendimento >= 0;
                        ?>
                            <tr>
                                <td class="ps-4 fw-semibold text-dark">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="stat-icon-sm rounded-3 p-2 bg-light text-primary">
                                            <i class="bi bi-bank"></i>
                                        </div>
                                        <span><?php echo htmlspecialchars($item['name']); ?></span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary fw-medium px-2 py-1">
                                        <?php echo htmlspecialchars($item['type']); ?>
                                    </span>
                                </td>
                                <td class="text-secondary"><?php echo htmlspecialchars($item['institution'] ?? '-'); ?></td>
                                <td class="text-end text-secondary">R$ <?php echo number_format($item['amount_invested'], 2, ',', '.'); ?></td>
                                <td class="text-end fw-bold text-dark">R$ <?php echo number_format($item['current_value'], 2, ',', '.'); ?></td>
                                <td class="text-end <?php echo $lucrou ? 'text-success' : 'text-danger'; ?> fw-semibold">
                                    <?php echo ($lucrou ? '+R$ ' : '-R$ ') . number_format(abs($rendimento), 2, ',', '.'); ?>
                                    <span class="small d-block fw-normal">(<?php echo ($lucrou ? '+' : '') . number_format($pct, 2, ',', '.') . '%'; ?>)</span>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="d-inline-flex gap-1">
                                        <button type="button" class="btn btn-sm btn-light border" 
                                            data-bs-toggle="modal" data-bs-target="#modalAtualizarSaldo"
                                            data-id="<?php echo $item['id']; ?>" data-nome="<?php echo htmlspecialchars($item['name']); ?>" data-saldo="<?php echo $item['current_value']; ?>"
                                            title="Atualizar Saldo Atual">
                                            <i class="bi bi-arrow-repeat text-primary"></i>
                                        </button>
                                        <form action="/investimentos/excluir/<?php echo $item['id']; ?>" method="POST" onsubmit="return confirm('Deseja remover este investimento?');" style="display:inline;">
                                            <button type="submit" class="btn btn-sm btn-light border text-danger" title="Excluir">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <!-- Modal Novo Investimento -->
    <div class="modal fade" id="modalNovoInvestimento" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header border-bottom bg-light px-4 py-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary-subtle text-primary p-2 rounded-3">
                            <i class="bi bi-graph-up-arrow fs-5"></i>
                        </span>
                        <div>
                            <h5 class="modal-title fw-bold text-dark mb-0 fs-6">Cadastrar Novo Ativo</h5>
                            <span class="text-secondary small" style="font-size: 0.75rem;">Acompanhe seus rendimentos e patrimônio</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <form action="/investimentos" method="POST">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Nome do Ativo</label>
                            <input type="text" class="form-control" name="name" placeholder="Ex: Tesouro Selic 2029, CDB Inter, PETR4..." required>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label small fw-semibold text-secondary">Tipo</label>
                                <select class="form-select" name="type" required>
                                    <option value="Renda Fixa">Renda Fixa / CDB</option>
                                    <option value="Tesouro Direto">Tesouro Direto</option>
                                    <option value="Ações">Ações</option>
                                    <option value="FIIs">Fundos Imobiliários (FIIs)</option>
                                    <option value="Cripto">Criptomoedas</option>
                                    <option value="Outros">Outros</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-semibold text-secondary">Instituição / Corretora</label>
                                <input type="text" class="form-control" name="institution" placeholder="Ex: Nubank, XP, Inter...">
                            </div>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label small fw-semibold text-secondary">Valor Aplicado (R$)</label>
                                <input type="number" step="0.01" min="0" class="form-control" name="amount_invested" placeholder="0.00" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-semibold text-secondary">Saldo Atual (R$)</label>
                                <input type="number" step="0.01" min="0" class="form-control" name="current_value" placeholder="0.00" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top bg-light px-4 py-3">
                        <button type="button" class="btn btn-light border px-3" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary px-4 fw-semibold shadow-sm">
                            <i class="bi bi-plus-lg me-1"></i> Salvar Ativo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Atualizar Saldo do Investimento -->
    <div class="modal fade" id="modalAtualizarSaldo" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header border-bottom bg-light px-4 py-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary-subtle text-primary p-2 rounded-3">
                            <i class="bi bi-arrow-repeat fs-5"></i>
                        </span>
                        <div>
                            <h6 class="modal-title fw-bold text-dark mb-0 fs-6">Atualizar Cotação</h6>
                            <span class="text-secondary small" id="atualizarNomeAtivo" style="font-size: 0.75rem;">Ativo</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <form action="/investimentos/atualizar-saldo" method="POST">
                    <input type="hidden" name="investimento_id" id="atualizarId">
                    <div class="modal-body p-4 text-center">
                        <div class="input-group mb-2">
                            <span class="input-group-text bg-white border-end-0">R$</span>
                            <input type="number" step="0.01" min="0" class="form-control form-control-lg text-end fw-bold" 
                                name="current_value" id="atualizarValorInput" placeholder="0.00" required autofocus>
                        </div>
                        <small class="text-secondary">Informe o valor de mercado ou saldo atual consolidado.</small>
                    </div>
                    <div class="modal-footer border-top bg-light p-3 d-flex gap-2">
                        <button type="button" class="btn btn-light border px-3 flex-fill" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary px-3 flex-fill fw-semibold shadow-sm">Salvar Saldo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5.3.3 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <script>
        const modalAtualizarSaldo = document.getElementById('modalAtualizarSaldo');
        modalAtualizarSaldo.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const nome = button.getAttribute('data-nome');
            const saldo = button.getAttribute('data-saldo');

            document.getElementById('atualizarId').value = id;
            document.getElementById('atualizarNomeAtivo').innerText = nome;
            document.getElementById('atualizarValorInput').value = saldo;
        });
    </script>
</body>

</html>
