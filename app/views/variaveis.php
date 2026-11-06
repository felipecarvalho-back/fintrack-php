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
                            <li><a class="dropdown-item py-2 small active" href="/variaveis"><i class="bi bi-sliders me-2 text-secondary"></i> Minhas Variáveis</a></li>
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
            <div class="d-flex gap-2">
                <a href="/orcamento" class="btn btn-light border px-3">
                    <i class="bi bi-arrow-left me-1"></i> Ir para Orçamento
                </a>
                <button type="button" class="btn btn-primary px-4 shadow-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#modalNovaVariavel">
                    <i class="bi bi-plus-lg me-1"></i> Nova Variável
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
                                    <th class="text-center">Tipo</th>
                                    <th class="text-center pe-3">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($receitas as $item): ?>
                                    <tr>
                                        <td class="ps-3 fw-semibold text-dark"><?php echo htmlspecialchars($item['nome']); ?></td>
                                        <td class="text-end text-success fw-medium">R$ <?php echo number_format($item['valorPadrao'], 2, ',', '.'); ?></td>
                                        <td class="text-center">
                                            <?php if ($item['isFixo']): ?>
                                                <span class="badge bg-primary-subtle text-primary">Fixo</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary-subtle text-secondary">Variável</span>
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
                                    <th class="text-center">Tipo</th>
                                    <th class="text-center pe-3">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($despesas as $item): ?>
                                    <tr>
                                        <td class="ps-3 fw-semibold text-dark"><?php echo htmlspecialchars($item['nome']); ?></td>
                                        <td class="text-end text-danger fw-medium">R$ <?php echo number_format($item['valorPadrao'], 2, ',', '.'); ?></td>
                                        <td class="text-center">
                                            <?php if ($item['isFixo']): ?>
                                                <span class="badge bg-primary-subtle text-primary">Fixo</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary-subtle text-secondary">Variável</span>
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

    <!-- Modal Nova Variável -->
    <?php $dadosModal = $_SESSION['dados_modal_variavel'] ?? []; ?>
    <div class="modal fade" id="modalNovaVariavel" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold">Cadastrar Nova Variável</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="/variaveis/adicionar" method="POST">
                    <input type="hidden" name="origem" value="/variaveis">
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
                                <input type="radio" class="btn-check" name="tipo" id="tipoReceita" value="receita" 
                                    <?php echo (($dadosModal['tipo'] ?? 'receita') === 'receita') ? 'checked' : ''; ?>>
                                <label class="btn btn-outline-success" for="tipoReceita"><i class="bi bi-plus-circle me-1"></i> Receita</label>

                                <input type="radio" class="btn-check" name="tipo" id="tipoDespesa" value="despesa"
                                    <?php echo (($dadosModal['tipo'] ?? '') === 'despesa') ? 'checked' : ''; ?>>
                                <label class="btn btn-outline-danger" for="tipoDespesa"><i class="bi bi-dash-circle me-1"></i> Despesa</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="nome" class="form-label small fw-semibold text-secondary">Nome do Campo</label>
                            <input type="text" class="form-control" id="nome" name="nome" 
                                value="<?php echo htmlspecialchars($dadosModal['nome'] ?? ''); ?>"
                                placeholder="Ex: Netflix, Gemini, Academia, Renda Extra..." required>
                        </div>

                        <div class="mb-3">
                            <label for="valor_padrao" class="form-label small fw-semibold text-secondary">Valor Padrão Sugerido (R$)</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="valor_padrao" name="valor_padrao" 
                                value="<?php echo htmlspecialchars((string) ($dadosModal['valor_padrao'] ?? '0.00')); ?>"
                                placeholder="0.00">
                        </div>

                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" role="switch" id="isFixo" name="is_fixo" value="1" 
                                <?php echo (!isset($dadosModal['is_fixo']) || !empty($dadosModal['is_fixo'])) ? 'checked' : ''; ?>>
                            <label class="form-check-label small" for="isFixo">Repetir todo mês como valor fixo</label>
                        </div>
                    </div>
                    <div class="modal-footer border-top">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary px-4 fw-semibold">Cadastrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5.3.3 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

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
