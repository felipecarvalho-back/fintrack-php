<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo ?? 'Entrar - FinTrack'; ?></title>
    <link rel="shortcut icon" href="/favicon.ico">

    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Estilos de Autenticação -->
    <link rel="stylesheet" href="/css/auth.css">
</head>

<body>
    <div class="auth-card mx-3">
        <!-- Logo e Boas-Vindas -->
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center gap-2 mb-2">
                <span class="brand-icon-sm">
                    <i class="bi bi-wallet2"></i>
                </span>
                <span class="fw-bold fs-4 text-dark tracking-tight">Fin<span class="text-primary">Track</span></span>
            </div>
            <p class="text-secondary small mb-0">Entre com sua conta para continuar</p>
        </div>

        <!-- Formulário de Login -->
        <form action="/login" method="POST">
            <!-- Alerta de Erro -->
            <?php if (!empty($_SESSION['erro'])): ?>
                <div class="alert alert-danger py-2 small d-flex align-items-center mb-3" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-6"></i>
                    <div><?php echo htmlspecialchars($_SESSION['erro']); ?></div>
                </div>
                <?php unset($_SESSION['erro']); ?>
            <?php endif; ?>

            <!-- Alerta de Sucesso -->
            <?php if (!empty($_SESSION['sucesso'])): ?>
                <div class="alert alert-success py-2 small d-flex align-items-center mb-3" role="alert">
                    <i class="bi bi-check-circle-fill me-2 fs-6"></i>
                    <div><?php echo htmlspecialchars($_SESSION['sucesso']); ?></div>
                </div>
                <?php unset($_SESSION['sucesso']); ?>
            <?php endif; ?>

            <!-- Campo E-mail -->
            <div class="mb-3">
                <label for="email" class="form-label fw-semibold text-secondary">E-mail</label>
                <input type="email" class="form-control" id="email" name="email"
                    placeholder="seu@email.com" required autocomplete="username">
            </div>

            <!-- Campo Senha -->
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label for="senha" class="form-label fw-semibold text-secondary mb-0">Senha</label>
                    <a href="#" class="small text-decoration-none text-primary" style="font-size: 0.8rem;">Esqueceu?</a>
                </div>
                <div class="input-group">
                    <input type="password" class="form-control" id="senha" name="senha"
                        placeholder="••••••••" required autocomplete="current-password">
                    <button class="btn btn-outline-secondary border-start-0" type="button" id="toggleSenha"
                        aria-label="Mostrar ou ocultar senha">
                        <i class="bi bi-eye" id="toggleIcon"></i>
                    </button>
                </div>
            </div>

            <!-- Lembrar-me -->
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="lembrar" name="lembrar">
                <label class="form-check-label small text-secondary" for="lembrar">
                    Lembrar neste navegador
                </label>
            </div>

            <!-- Botão de Acesso -->
            <button type="submit" class="btn btn-primary btn-auth w-100 shadow-sm mb-3">
                Entrar
            </button>

            <!-- Link para Cadastro -->
            <div class="text-center pt-2 border-top">
                <span class="small text-secondary">Não possui conta? </span>
                <a href="/cadastro" class="small fw-semibold text-primary text-decoration-none">Cadastre-se</a>
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
