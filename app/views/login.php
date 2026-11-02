<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo ?? 'Login - Sistema Financeiro'; ?></title>

    <!-- Bootstrap 6 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@6.0.0-alpha.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-B/GM4XqrwHnWXNOWMbloTmrYXZg10cakYGmpfsR/bbzQ6JAJI4ihuyADKLnBgrCe" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Estilos Customizados -->
    <link rel="stylesheet" href="/css/auth.css">
</head>

<body>
    <div class="container p-3">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-5 col-xl-4">
                <div class="card login-card p-4 p-sm-5 mx-auto">

                    <!-- Cabeçalho / Identidade Visual -->
                    <div class="text-center mb-4">
                        <div class="brand-icon mb-3">
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-1">Painel Financeiro</h4>
                        <p class="text-muted small mb-0">Entre com seus dados para continuar</p>
                    </div>

                    <!-- Formulário de Login -->
                    <form action="/login" method="POST">

                        <!-- Alerta de Erro -->
                        <?php if (!empty($_SESSION['erro'])): ?>
                            <div class="alert alert-danger theme-danger py-2 small d-flex align-items-center mb-3" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2 fs-6"></i>
                                <div>
                                    <?php echo htmlspecialchars($_SESSION['erro']); ?>
                                </div>
                            </div>
                            <?php unset($_SESSION['erro']); ?>
                        <?php endif; ?>

                        <!-- Alerta de Sucesso -->
                        <?php if (!empty($_SESSION['sucesso'])): ?>
                            <div class="alert alert-success theme-success py-2 small d-flex align-items-center mb-3" role="alert">
                                <i class="bi bi-check-circle-fill me-2 fs-6"></i>
                                <div><?php echo htmlspecialchars($_SESSION['sucesso']); ?></div>
                            </div>
                            <?php unset($_SESSION['sucesso']); ?>
                        <?php endif; ?>

                        <!-- Campo de E-mail / Usuário -->
                        <div class="mb-3">
                            <label for="email" class="form-label small fw-semibold text-secondary">E-mail ou
                                Usuário</label>
                            <div class="input-group input-group-integrated">
                                <span class="input-group-text pe-2">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email" class="form-control ps-1" id="email" name="email"
                                    placeholder="seu@email.com" required autocomplete="username">
                            </div>
                        </div>

                        <!-- Campo de Senha -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="senha" class="form-label small fw-semibold text-secondary">Senha</label>
                                <a href="#" class="small text-decoration-none text-primary">Esqueceu?</a>
                            </div>
                            <div class="input-group input-group-integrated">
                                <span class="input-group-text pe-2">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" class="form-control ps-1" id="senha" name="senha"
                                    placeholder="••••••••" required autocomplete="current-password">
                                <button class="btn text-muted" type="button" id="toggleSenha"
                                    aria-label="Mostrar ou ocultar senha">
                                    <i class="bi bi-eye" id="toggleIcon"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Lembrar-me -->
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="lembrar" name="lembrar">
                            <label class="form-check-label small text-secondary" for="lembrar">
                                Lembrar neste dispositivo
                            </label>
                        </div>

                        <!-- Botão de Acesso -->
                        <button type="submit" class="btn btn-primary btn-login w-100 mb-3 shadow-sm">
                            Entrar no Sistema
                        </button>

                        <!-- Link para Cadastro -->
                        <div class="text-center mb-3">
                            <span class="small text-muted">Não possui uma conta? </span>
                            <a href="/cadastro"
                                class="small fw-semibold text-primary text-decoration-none">Cadastre-se</a>
                        </div>

                        <!-- Indicador de Segurança -->
                        <div class="text-center mt-3 pt-3 border-top">
                            <span class="text-muted small d-inline-flex align-items-center gap-1">
                                <i class="bi bi-shield-check text-success"></i> Conexão segura e criptografada
                            </span>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 6 Bundle JS -->
    <script type="module" src="https://cdn.jsdelivr.net/npm/bootstrap@6.0.0-alpha.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-1a/pXj49ZQ1aHEmrJ+gMw1otqoVsYwlEnlD8mIfY2TV03r20Y0CN7uqx1tQogjPL"
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