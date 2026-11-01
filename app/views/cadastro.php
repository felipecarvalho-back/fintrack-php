<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo ?? 'Cadastro - Sistema Financeiro'; ?></title>

    <!-- Bootstrap 6 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@6.0.0-alpha.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-B/GM4XqrwHnWXNOWMbloTmrYXZg10cakYGmpfsR/bbzQ6JAJI4ihuyADKLnBgrCe" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #eef2f7 0%, #e5ebf2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;
            padding-top: 2rem;
            padding-bottom: 2rem;
        }

        .register-card {
            width: 100%;
            max-width: 480px;
            border-radius: 1rem;
            box-shadow: 0 12px 32px rgba(15, 23, 42, 0.08);
            border: 1px solid rgba(226, 232, 240, 0.8);
            background-color: #ffffff;
        }

        .brand-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
            font-size: 1.75rem;
        }

        .input-group-integrated {
            border: 1px solid #d0d7de;
            border-radius: 0.5rem;
            background-color: #ffffff;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            overflow: hidden;
        }

        .input-group-integrated .form-control,
        .input-group-integrated .input-group-text,
        .input-group-integrated .btn {
            border: none !important;
            box-shadow: none !important;
            outline: none !important;
            background-color: transparent !important;
        }

        /* Destaque suave e uniforme no contêiner */
        .input-group-integrated:focus-within {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.12);
        }

        .input-group-text {
            color: #6c757d;
        }

        .btn-register {
            padding-top: 0.65rem;
            padding-bottom: 0.65rem;
            font-weight: 600;
            letter-spacing: 0.2px;
        }
    </style>
</head>

<body>
    <div class="container p-3">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
                <div class="card register-card p-4 p-sm-5 mx-auto">

                    <!-- Cabeçalho / Identidade Visual -->
                    <div class="text-center mb-4">
                        <div class="brand-icon mb-3">
                            <i class="bi bi-person-plus"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-1">Criar Conta</h4>
                        <p class="text-muted small mb-0">Preencha os dados abaixo para começar</p>
                    </div>

                    <!-- Formulário de Cadastro -->
                    <form action="/cadastro" method="POST">

                        <!-- Alerta de Erro -->
                        <?php if (!empty($_SESSION['erro'])): ?>
                            <div class="alert alert-danger py-2 small d-flex align-items-center mb-3" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2 fs-6"></i>
                                <div><?php echo htmlspecialchars($_SESSION['erro']); ?></div>
                            </div>
                            <?php unset($_SESSION['erro']); ?>
                        <?php endif; ?>

                        <!-- Nome Completo -->
                        <div class="mb-3">
                            <label for="nome_completo" class="form-label small fw-semibold text-secondary">Nome Completo</label>
                            <div class="input-group input-group-integrated">
                                <span class="input-group-text pe-2">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text" class="form-control ps-1" id="nome_completo" name="nome_completo"
                                    placeholder="Ex: João da Silva"
                                    value="<?php echo htmlspecialchars($_SESSION['dados_antigos']['nome_completo'] ?? ''); ?>"
                                    required autocomplete="nome_completo">
                            </div>
                        </div>

                        <!-- E-mail -->
                        <div class="mb-3">
                            <label for="email" class="form-label small fw-semibold text-secondary">E-mail</label>
                            <div class="input-group input-group-integrated">
                                <span class="input-group-text pe-2">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email" class="form-control ps-1" id="email" name="email"
                                    placeholder="seu@email.com"
                                    value="<?php echo htmlspecialchars($_SESSION['dados_antigos']['email'] ?? ''); ?>"
                                    required autocomplete="username">
                            </div>
                        </div>
                        <?php unset($_SESSION['dados_antigos']); ?>

                        <!-- Senha -->
                        <div class="mb-3">
                            <label for="senha" class="form-label small fw-semibold text-secondary">Senha</label>
                            <div class="input-group input-group-integrated">
                                <span class="input-group-text pe-2">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" class="form-control ps-1" id="senha" name="senha"
                                    placeholder="Mínimo de 8 caracteres" required autocomplete="new-password">
                                <button class="btn text-muted" type="button" id="toggleSenha"
                                    aria-label="Mostrar ou ocultar senha">
                                    <i class="bi bi-eye" id="toggleIcon"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Confirmar Senha -->
                        <div class="mb-3">
                            <label for="confirmar_senha" class="form-label small fw-semibold text-secondary">Confirmar Senha</label>
                            <div class="input-group input-group-integrated">
                                <span class="input-group-text pe-2">
                                    <i class="bi bi-shield-check"></i>
                                </span>
                                <input type="password" class="form-control ps-1" id="confirmar_senha"
                                    name="confirmar_senha" placeholder="Repita sua senha" required
                                    autocomplete="new-password">
                            </div>
                        </div>

                        <!-- Termos e Condições -->
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="termos" name="termos" required>
                            <label class="form-check-label small text-secondary" for="termos">
                                Concordo com os <a href="#" class="text-decoration-none text-primary">Termos de Uso</a> e <a href="#" class="text-decoration-none text-primary">Privacidade</a>
                            </label>
                        </div>

                        <!-- Botão de Cadastro -->
                        <button type="submit" class="btn btn-primary btn-register w-100 mb-3 shadow-sm">
                            Criar Minha Conta
                        </button>

                        <!-- Link para Login -->
                        <div class="text-center mt-3 pt-3 border-top">
                            <p class="small text-muted mb-0">
                                Já possui uma conta?
                                <a href="/" class="text-decoration-none fw-semibold text-primary">Fazer Login</a>
                            </p>
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
