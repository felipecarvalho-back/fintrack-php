# 💰 FinTrack - Gestão Financeira Pessoal

O **FinTrack** é um sistema moderno de controle e planejamento financeiro pessoal desenvolvido em **PHP 8.2+** com arquitetura limpa, alta performance e interface responsiva.

O projeto foi construído para resolver problemas reais de orçamento diário: gerenciar receitas, contas fixas, despesas calculadas dinamicamente (como repasses calculados por diárias trabalhadas), metas em caixinhas e carteira de investimentos.

---

## ✨ Principais Funcionalidades

- **📊 Dashboard Central**: Visão consolidada do mês com saldo previsto, taxa de comprometimento de renda, total poupado em caixinhas e patrimônio investido.
- **🗓️ Orçamento Mensal Reativo**:
  - Cálculo instantâneo em tempo real via JavaScript (sem recarregar a tela).
  - **Modo Multiplicador (Cálculos Dinâmicos)**: permite calcular valores por quantidade $\times$ valor unitário (ex: *dias trabalhados $\times$ diária de repasse*).
  - **Inteligência de Despesas Fixas vs Ocasionais**:
    - **Fixas (`is_fixo = 1`)**: propagam automaticamente para os próximos meses, preservando o valor e os multiplicadores preenchidos no mês anterior.
    - **Ocasionais (`is_fixo = 0`)**: despesas pontuais ficam restritas apenas ao mês em que foram cadastradas, sem poluir meses futuros.
  - Navegação fluida entre meses anteriores e futuros.
- **🐷 Caixinhas & Metas**: Separação de dinheiro por objetivos (Reserva de Emergência, Viagem, Bens) com acompanhamento percentual de progresso.
- **📈 Carteira de Ativos**: Painel de investimentos em Renda Fixa, FIIs, Ações e Criptomoedas.
- **🔒 Segurança & Multi-Tenant**: Autenticação com senhas criptografadas via `password_hash` (Bcrypt) e isolamento rigoroso de dados por conta de usuário (`conta_id`).

---

## 🛠️ Tecnologias Utilizadas

- **Linguagem**: PHP 8.2+ (Strict Types habilitado)
- **Micro-framework**: [Slim Framework 4](https://www.slimframework.com/)
- **Injeção de Dependências**: [PHP-DI](https://php-di.org/)
- **HTTP / PSRs**: PSR-7 (`slim/psr7`), PSR-11 e `Slim\Views\PhpRenderer`
- **Banco de Dados**: MySQL / MariaDB via PDO com Prepared Statements
- **Frontend**: HTML5, CSS3, JavaScript puro, [Bootstrap 5.3.3](https://getbootstrap.com/) e [Bootstrap Icons](https://icons.getbootstrap.com/)

---

## 🏛️ Arquitetura do Projeto

O sistema adota os princípios de Clean Architecture e separação de responsabilidades:

```text
fintrack-php/
├── app/
│   ├── config/          # Injeção de dependências (PHP-DI) e conexão PDO
│   ├── Controller/      # Controladores HTTP (rotas Slim)
│   ├── Entity/          # Entidades de domínio ricas
│   ├── Enum/            # Enums tipados (TipoVariavel, TipoCalculo)
│   ├── Middleware/      # Autenticação de rotas protegidas
│   ├── Repository/      # Persistência no banco via PDO
│   ├── routes/          # Definição de rotas da aplicação
│   ├── Service/         # Regras de negócio e sanitização de cálculos
│   └── views/           # Telas e templates da aplicação
├── public/              # DocumentRoot web (index.php, CSS, favicon)
├── script.sql           # Estrutura do banco de dados relacional
└── composer.json        # Dependências do projeto
```

---

## 🚀 Instalação e Execução

### 1. Clonar o repositório
```bash
git clone https://github.com/seu-usuario/fintrack-php.git
cd fintrack-php
```

### 2. Instalar dependências via Composer
```bash
composer install
```

### 3. Configurar variáveis de ambiente
Crie o arquivo `.env` na raiz baseado no `.env.example`:
```ini
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_financeiro
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Criar o Banco de Dados
Importe o arquivo `script.sql` no seu MySQL:
```bash
mysql -u root -p < script.sql
```

### 5. Iniciar o servidor local
```bash
composer start
```
Acesse no seu navegador: `http://localhost:8000`

---

## 💡 Sugestão: Executando como Aplicativo Desktop no Windows

Se você deseja abrir o FinTrack diretamente como um aplicativo desktop dedicado (com ícone próprio, sem abas e sem barra de navegação), pode utilizar o modo **App** nativo do Windows:

1. **Criar um arquivo `FinTrack.vbs`** na pasta do projeto:
   ```vbs
   Set WshShell = CreateObject("WScript.Shell")
   Set fso = CreateObject("Scripting.FileSystemObject")
   currentDir = fso.GetParentFolderName(WScript.ScriptFullName)

   ' Inicia o servidor PHP em segundo plano (invisível)
   WshShell.Run "cmd.exe /c php -S 127.0.0.1:8000 -t """ & currentDir & "\public""", 0, False
   WScript.Sleep 800

   ' Abre a janela dedicada do app via Microsoft Edge ou Chrome
   WshShell.Run "msedge.exe --app=http://127.0.0.1:8000 --window-size=1280,850", 1, False
   ```
2. **Criar um atalho** apontando para o script na sua Área de Trabalho com o ícone do projeto.
3. Ao dar dois cliques, o FinTrack abrirá instantaneamente em uma janela limpa e isolada, com consumo mínimo de memória RAM e inicialização imediata.

---

## 📄 Licença

Este projeto é desenvolvido para fins de organização pessoal e estudos avançados de arquitetura PHP.
