<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo ?? 'Criar Conta - FinTrack'; ?></title>

    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Estilos de Autenticação -->
    <link rel="stylesheet" href="/css/auth.css">
</head>

<body>
    <div class="auth-card auth-card-compact mx-3">
        <!-- Logo e Cabeçalho -->
        <div class="text-center mb-3">
            <div class="d-inline-flex align-items-center gap-2 mb-1">
                <span class="brand-icon-sm">
                    <i class="bi bi-wallet2"></i>
                </span>
                <span class="fw-bold fs-4 text-dark tracking-tight">Fin<span class="text-primary">Track</span></span>
            </div>
            <p class="text-secondary small mb-0">Crie sua conta e organize suas finanças</p>
        </div>

        <!-- Formulário de Cadastro -->
        <form action="/cadastro" method="POST">
            <!-- Alerta de Erro -->
            <?php if (!empty($_SESSION['erro'])): ?>
                <div class="alert alert-danger py-1 px-2 small d-flex align-items-center mb-2" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-6"></i>
                    <div><?php echo htmlspecialchars($_SESSION['erro']); ?></div>
                </div>
                <?php unset($_SESSION['erro']); ?>
            <?php endif; ?>

            <!-- Nome Completo -->
            <div class="mb-2">
                <label for="nome_completo" class="form-label fw-semibold text-secondary">Nome Completo</label>
                <input type="text" class="form-control" id="nome_completo" name="nome_completo"
                    placeholder="Seu nome"
                    value="<?php echo htmlspecialchars($_SESSION['dados_antigos']['nome_completo'] ?? ''); ?>"
                    required autocomplete="name">
            </div>

            <!-- E-mail -->
            <div class="mb-2">
                <label for="email" class="form-label fw-semibold text-secondary">E-mail</label>
                <input type="email" class="form-control" id="email" name="email"
                    placeholder="seu@email.com"
                    value="<?php echo htmlspecialchars($_SESSION['dados_antigos']['email'] ?? ''); ?>"
                    required autocomplete="username">
            </div>
            <?php unset($_SESSION['dados_antigos']); ?>

            <!-- Senha e Confirmar Senha (Lado a Lado para economizar altura) -->
            <div class="row g-2 mb-2">
                <div class="col-6">
                    <label for="senha" class="form-label fw-semibold text-secondary">Senha</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="senha" name="senha"
                            placeholder="Mín. 8 dígitos" required autocomplete="new-password">
                        <button class="btn btn-outline-secondary border-start-0 px-2" type="button" id="toggleSenha"
                            aria-label="Mostrar ou ocultar senha">
                            <i class="bi bi-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>
                <div class="col-6">
                    <label for="confirmar_senha" class="form-label fw-semibold text-secondary">Confirmar</label>
                    <input type="password" class="form-control" id="confirmar_senha"
                        name="confirmar_senha" placeholder="Repita a senha" required
                        autocomplete="new-password">
                </div>
            </div>

            <!-- Termos e Condições -->
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="termos" name="termos" required>
                <label class="form-check-label small text-secondary" for="termos" style="font-size: 0.78rem;">
                    Concordo com os <a href="#" class="text-decoration-none text-primary">Termos</a> e <a href="#" class="text-decoration-none text-primary">Privacidade</a>
                </label>
            </div>

            <!-- Botão de Cadastro -->
            <button type="submit" class="btn btn-primary btn-auth w-100 shadow-sm mb-2">
                Criar Minha Conta
            </button>

            <!-- Link para Login -->
            <div class="text-center pt-2 border-top">
                <span class="small text-secondary">Já possui uma conta? </span>
                <a href="/" class="small fw-semibold text-primary text-decoration-none">Entrar</a>
            </div>
        </form>
    </div>

    <!-- Bootstrap 5.3.3 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <!-- Script de alternância de exibição da senha -->
    <script>
        const toggleSenha = document.getElementById('toggleSenha');
        const senhaInput = document.getElementById('senha');
        const toggleIcon = document.getElementById('toggleIcon');

        if (toggleSenha && senhaInput && toggleIcon) {
            toggleSenha.addEventListener('click', function () {
                const isPassword = senhaInput.type === 'password';
                senhaInput.type = isPassword ? 'text' : 'password';
                toggleIcon.classList.toggle('bi-eye');
                toggleIcon.classList.toggle('bi-eye-slash');
            });
        }
    </script>
</body>

</html>
