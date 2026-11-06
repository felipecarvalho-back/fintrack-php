CREATE DATABASE IF NOT EXISTS db_financeiro;
USE db_financeiro;

-- 1. Usuários / Contas de Acesso
CREATE TABLE IF NOT EXISTS contas (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome_completo VARCHAR(200) NOT NULL,
    email VARCHAR(200) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. "Variáveis Globais" do Usuário (Ex: Salário, Internet, Gemini, Aluguel, VR, Dinheiro da Mãe)
CREATE TABLE IF NOT EXISTS variaveis_orcamento (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    conta_id BIGINT UNSIGNED NOT NULL,
    nome VARCHAR(100) NOT NULL,                                     -- Ex: "Salário Líquido", "Internet", "Dinheiro Mãe"
    tipo ENUM('receita', 'despesa') NOT NULL,                       -- Define se soma ou subtrai no balanço
    tipo_calculo ENUM('simples', 'multiplicador') DEFAULT 'simples',-- 'simples' (R$ direto) ou 'multiplicador' (Qtd * Unitário)
    label_multiplicador VARCHAR(50) DEFAULT 'Dias',                 -- Ex: "Dias trabalhados", "Horas", "Unidades"
    is_fixo BOOLEAN DEFAULT TRUE,                                   -- Se costuma se repetir todo mês
    valor_padrao DECIMAL(10, 2) DEFAULT 0.00,                      -- Valor ou Valor Unitário sugerido
    ordem INT DEFAULT 0,                                            -- Para ordenar na interface
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (conta_id) REFERENCES contas(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Resumo do Mês do Usuário (Cabeçalho do Mês)
CREATE TABLE IF NOT EXISTS financas_mensais (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    conta_id BIGINT UNSIGNED NOT NULL,
    mes INT NOT NULL,
    ano INT NOT NULL,
    observacoes TEXT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY uk_conta_mes_ano (conta_id, mes, ano),
    FOREIGN KEY (conta_id) REFERENCES contas(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Valores de cada Variável no Mês
CREATE TABLE IF NOT EXISTS valores_mensais (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    financa_mensal_id BIGINT UNSIGNED NOT NULL,
    variavel_id BIGINT UNSIGNED NOT NULL,
    quantidade DECIMAL(10, 2) NULL DEFAULT 1.00,                   -- Ex: 22 dias
    valor_unitario DECIMAL(10, 2) NULL,                            -- Ex: R$ 25,00
    valor DECIMAL(10, 2) NOT NULL DEFAULT 0.00,                    -- Valor final calculado (22 * 25 = 550,00)

    UNIQUE KEY uk_mes_variavel (financa_mensal_id, variavel_id),
    FOREIGN KEY (financa_mensal_id) REFERENCES financas_mensais(id) ON DELETE CASCADE,
    FOREIGN KEY (variavel_id) REFERENCES variaveis_orcamento(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Gestão de Caixinhas / Metas Financeiras (Multi-usuário)
CREATE TABLE IF NOT EXISTS caixinhas (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    conta_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    target_amount DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    current_amount DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    color VARCHAR(50) NULL DEFAULT '#0d6efd',
    icon VARCHAR(50) NULL DEFAULT 'bi-piggy-bank',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (conta_id) REFERENCES contas(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Carteira de Investimentos (Multi-usuário)
CREATE TABLE IF NOT EXISTS investments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    conta_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    type VARCHAR(100) NOT NULL,                         -- Ex: 'CDB', 'Ações', 'FIIs', 'Tesouro Selic'
    amount_invested DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    current_value DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    institution VARCHAR(255) NULL,                      -- Ex: 'Nubank', 'XP', 'Inter'
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (conta_id) REFERENCES contas(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================================================
-- MIGRAÇÕES / ALTER TABLES (Execute caso a sua base já exista):
-- ==============================================================================
-- ALTER TABLE variaveis_orcamento 
--     ADD COLUMN IF NOT EXISTS tipo_calculo ENUM('simples', 'multiplicador') DEFAULT 'simples' AFTER tipo,
--     ADD COLUMN IF NOT EXISTS label_multiplicador VARCHAR(50) DEFAULT 'Dias' AFTER tipo_calculo;

-- ALTER TABLE valores_mensais 
--     ADD COLUMN IF NOT EXISTS quantidade DECIMAL(10, 2) NULL DEFAULT 1.00 AFTER variavel_id,
--     ADD COLUMN IF NOT EXISTS valor_unitario DECIMAL(10, 2) NULL AFTER quantidade;
