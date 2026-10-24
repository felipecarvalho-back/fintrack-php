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

    <style>
        body {
            background: linear-gradient(135deg, #eef2f7 0%, #e5ebf2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
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

        /* Efeito ao focar no campo: borda uniforme no bloco inteiro, sem anel azul separado */
        .input-group-integrated:focus-within {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.12);
        }

        .input-group-text {
            color: #6c757d;
        }

        .btn-login {
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
                        <!-- Campo de E-mail / Usuário -->
                        <div class="mb-3">
                            <label for="email" class="form-label small fw-semibold text-secondary">E-mail ou Usuário</label>
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
                                <input type="password" class="form-control ps-1" id="senha"
                                    name="senha" placeholder="••••••••" required autocomplete="current-password">
                                <button class="btn text-muted"
                                    type="button" id="toggleSenha" aria-label="Mostrar ou ocultar senha">
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
                            <a href="/cadastro" class="small fw-semibold text-primary text-decoration-none">Cadastre-se</a>
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
