-- ===================================================
-- Sistema Face a Face com Deus - Banco de Dados
-- Instalação completa das tabelas
-- ===================================================

-- Criar banco de dados
CREATE DATABASE IF NOT EXISTS `face_a_face` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `face_a_face`;

-- ===================================================
-- TABELA: eventos
-- ===================================================
CREATE TABLE eventos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(200) NOT NULL,
    tipo ENUM('homens', 'mulheres') DEFAULT 'homens',
    publico_alvo VARCHAR(200),
    descricao TEXT,
    data_inicio DATE NOT NULL,
    data_fim DATE NOT NULL,
    local_evento VARCHAR(200),
    vagas_limite INT DEFAULT NULL,
    observacoes TEXT,
    ativo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- TABELA: encontreiros (Equipe Organizadora)
-- ===================================================
CREATE TABLE encontreiros (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(20),
    email VARCHAR(100),
    data_nascimento DATE,
    endereco TEXT,
    igreja_origem VARCHAR(200),
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    observacoes TEXT,
    ativo BOOLEAN DEFAULT TRUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- TABELA: encontristas (Participantes)
-- ===================================================
CREATE TABLE encontristas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(20),
    email VARCHAR(100),
    data_nascimento DATE,
    endereco TEXT,
    igreja_origem VARCHAR(200),
    profissao VARCHAR(100),
    estado_civil ENUM('solteiro', 'casado', 'divorciado', 'viuvo', 'outros'),
    como_conheceu VARCHAR(200),
    observacoes_medicas TEXT,
    contato_emergencia VARCHAR(200),
    telefone_emergencia VARCHAR(20),
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ativo BOOLEAN DEFAULT TRUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- TABELA: inscricoes_encontristas
-- ===================================================
CREATE TABLE inscricoes_encontristas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    evento_id INT,
    encontrista_id INT,
    data_inscricao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status_inscricao ENUM('pendente', 'confirmada', 'cancelada') DEFAULT 'pendente',
    valor_contribuicao DECIMAL(10,2) DEFAULT 0.00,
    data_pagamento DATE DEFAULT NULL,
    forma_pagamento VARCHAR(50),
    observacoes TEXT,
    FOREIGN KEY (evento_id) REFERENCES eventos(id) ON DELETE CASCADE,
    FOREIGN KEY (encontrista_id) REFERENCES encontristas(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- TABELA: participacao_encontreiros
-- ===================================================
CREATE TABLE participacao_encontreiros (
    id INT PRIMARY KEY AUTO_INCREMENT,
    evento_id INT,
    encontreiro_id INT,
    tipo_equipe ENUM(
        'equipe_externa_1', 'equipe_externa_2', 'equipe_externa_3', 'equipe_externa_4',
        'adoratorio', 'torre', 'som_imagem', 'sauda', 'correio', 'standby', 
        'lider_externo', 'teatro_fogo', 'cozinha', 'coordenacao'
    ) NOT NULL,
    funcao_especifica VARCHAR(100),
    is_lider BOOLEAN DEFAULT FALSE,
    is_vice_lider BOOLEAN DEFAULT FALSE,
    observacoes TEXT,
    FOREIGN KEY (evento_id) REFERENCES eventos(id) ON DELETE CASCADE,
    FOREIGN KEY (encontreiro_id) REFERENCES encontreiros(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- TABELA: valores_evento
-- ===================================================
CREATE TABLE valores_evento (
    id INT PRIMARY KEY AUTO_INCREMENT,
    evento_id INT,
    valor_encontrista DECIMAL(10,2) NOT NULL,
    valor_encontreiro DECIMAL(10,2) NOT NULL,
    descricao_valor TEXT,
    data_limite_pagamento DATE,
    desconto_antecipado DECIMAL(5,2) DEFAULT 0.00,
    data_limite_desconto DATE,
    observacoes TEXT,
    FOREIGN KEY (evento_id) REFERENCES eventos(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- TABELA: pagamentos_encontristas
-- ===================================================
CREATE TABLE pagamentos_encontristas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    inscricao_id INT,
    valor_devido DECIMAL(10,2) NOT NULL,
    valor_pago DECIMAL(10,2) DEFAULT 0.00,
    desconto_aplicado DECIMAL(10,2) DEFAULT 0.00,
    status_pagamento ENUM('pendente', 'parcial', 'pago', 'atrasado', 'cancelado') DEFAULT 'pendente',
    forma_pagamento VARCHAR(50),
    data_vencimento DATE,
    data_pagamento DATETIME DEFAULT NULL,
    comprovante_arquivo VARCHAR(255),
    observacoes TEXT,
    notificacao_enviada BOOLEAN DEFAULT FALSE,
    data_ultima_notificacao DATETIME DEFAULT NULL,
    FOREIGN KEY (inscricao_id) REFERENCES inscricoes_encontristas(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- TABELA: pagamentos_encontreiros
-- ===================================================
CREATE TABLE pagamentos_encontreiros (
    id INT PRIMARY KEY AUTO_INCREMENT,
    participacao_id INT,
    valor_devido DECIMAL(10,2) NOT NULL,
    valor_pago DECIMAL(10,2) DEFAULT 0.00,
    desconto_aplicado DECIMAL(10,2) DEFAULT 0.00,
    status_pagamento ENUM('pendente', 'parcial', 'pago', 'atrasado', 'cancelado') DEFAULT 'pendente',
    forma_pagamento VARCHAR(50),
    data_vencimento DATE,
    data_pagamento DATETIME DEFAULT NULL,
    comprovante_arquivo VARCHAR(255),
    observacoes TEXT,
    notificacao_enviada BOOLEAN DEFAULT FALSE,
    data_ultima_notificacao DATETIME DEFAULT NULL,
    FOREIGN KEY (participacao_id) REFERENCES participacao_encontreiros(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- TABELA: sub_eventos
-- ===================================================
CREATE TABLE sub_eventos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    evento_id INT,
    nome VARCHAR(200) NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fim TIME,
    ordem_sequencia INT NOT NULL,
    obrigatorio_encontristas BOOLEAN DEFAULT TRUE,
    observacoes TEXT,
    FOREIGN KEY (evento_id) REFERENCES eventos(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- TABELA: ministracoes
-- ===================================================
CREATE TABLE ministracoes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    sub_evento_id INT,
    ministro_id INT,
    titulo VARCHAR(200),
    tema VARCHAR(500),
    hora_inicio TIME NOT NULL,
    hora_fim TIME,
    duracao_estimada INT,
    observacoes TEXT,
    slides_arquivo VARCHAR(255),
    FOREIGN KEY (sub_evento_id) REFERENCES sub_eventos(id) ON DELETE CASCADE,
    FOREIGN KEY (ministro_id) REFERENCES encontreiros(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- TABELA: ministracao_intercessores (Cobertura Espiritual)
-- ===================================================
CREATE TABLE ministracao_intercessores (
    id INT PRIMARY KEY AUTO_INCREMENT,
    ministracao_id INT,
    encontreiro_id INT,
    principal BOOLEAN DEFAULT FALSE,
    observacoes TEXT,
    FOREIGN KEY (ministracao_id) REFERENCES ministracoes(id) ON DELETE CASCADE,
    FOREIGN KEY (encontreiro_id) REFERENCES encontreiros(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- TABELA: log_mensagens_n8n
-- ===================================================
CREATE TABLE log_mensagens_n8n (
    id INT PRIMARY KEY AUTO_INCREMENT,
    destinatario_tipo ENUM('encontrista', 'encontreiro') NOT NULL,
    destinatario_id INT NOT NULL,
    tipo_mensagem ENUM('pagamento_pendente', 'pagamento_confirmado', 'lembrete_evento', 'welcome', 'custom') NOT NULL,
    canal ENUM('whatsapp', 'email', 'sms') NOT NULL,
    numero_telefone VARCHAR(20),
    email_destinatario VARCHAR(100),
    conteudo_mensagem TEXT,
    status_envio ENUM('enviado', 'entregue', 'falhou', 'pendente') DEFAULT 'pendente',
    webhook_n8n_id VARCHAR(100),
    resposta_n8n JSON,
    data_envio DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_entrega DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- TABELA: config_n8n
-- ===================================================
CREATE TABLE config_n8n (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome_config VARCHAR(100) NOT NULL,
    webhook_url VARCHAR(500) NOT NULL,
    tipo_automacao ENUM('pagamento', 'evento', 'geral') NOT NULL,
    ativo BOOLEAN DEFAULT TRUE,
    headers_customizados JSON,
    parametros_default JSON,
    observacoes TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- INSERIR DADOS INICIAIS
-- ===================================================

-- Configurações padrão do n8n
INSERT INTO config_n8n (nome_config, webhook_url, tipo_automacao, ativo, observacoes) VALUES
('Lembrete de Pagamento', 'http://localhost:5678/webhook/pagamento-lembrete', 'pagamento', TRUE, 'Webhook para lembretes automáticos de pagamento'),
('Boas-vindas', 'http://localhost:5678/webhook/boas-vindas', 'evento', TRUE, 'Mensagem de boas-vindas para novos inscritos'),
('Lembrete de Evento', 'http://localhost:5678/webhook/lembrete-evento', 'evento', TRUE, 'Lembretes sobre datas do evento');

-- Evento de exemplo
INSERT INTO eventos (nome, tipo, publico_alvo, descricao, data_inicio, data_fim, local_evento) VALUES
('Face a Face de Homens 2025', 'homens', 'Homens de 18 a 60 anos', 'Encontro anual Face a Face com Deus para homens', '2025-11-15', '2025-11-17', 'Centro de Retiros Água Viva');

-- Valores do evento
INSERT INTO valores_evento (evento_id, valor_encontrista, valor_encontreiro, data_limite_pagamento, desconto_antecipado, data_limite_desconto) VALUES
(1, 180.00, 120.00, '2025-11-01', 10.00, '2025-10-15');

-- Sub-eventos de exemplo
INSERT INTO sub_eventos (evento_id, nome, hora_inicio, hora_fim, ordem_sequencia) VALUES
(1, 'Chegada e Acolhida', '18:00:00', '19:30:00', 1),
(1, 'Abertura e Louvor', '19:30:00', '20:30:00', 2),
(1, 'Primeira Ministração', '20:30:00', '21:30:00', 3),
(1, 'Café da Madrugada', '06:00:00', '07:00:00', 4),
(1, 'Segunda Ministração', '07:00:00', '08:30:00', 5),
(1, 'Terceira Ministração', '09:00:00', '10:30:00', 6),
(1, 'Encerramento e Envio', '15:00:00', '16:00:00', 7);

-- Encontreiros de exemplo
INSERT INTO encontreiros (nome, telefone, email, igreja_origem) VALUES
('João Silva', '(11) 99999-1111', 'joao@igreja.com', 'Igreja Água Viva'),
('Pedro Santos', '(11) 99999-2222', 'pedro@igreja.com', 'Igreja Água Viva'),
('Paulo Oliveira', '(11) 99999-3333', 'paulo@igreja.com', 'Igreja Água Viva');

-- Participação dos encontreiros
INSERT INTO participacao_encontreiros (evento_id, encontreiro_id, tipo_equipe, funcao_especifica, is_lider) VALUES
(1, 1, 'coordenacao', 'Coordenador Geral', TRUE),
(1, 2, 'adoratorio', 'Líder de Adoração', TRUE),
(1, 3, 'som_imagem', 'Técnico de Som', FALSE);

-- Encontristas de exemplo
INSERT INTO encontristas (nome, telefone, email, profissao, estado_civil) VALUES
('Carlos Mendes', '(11) 88888-1111', 'carlos@email.com', 'Engenheiro', 'casado'),
('Roberto Lima', '(11) 88888-2222', 'roberto@email.com', 'Professor', 'solteiro'),
('Antonio Costa', '(11) 88888-3333', 'antonio@email.com', 'Comerciante', 'casado');

-- Inscrições
INSERT INTO inscricoes_encontristas (evento_id, encontrista_id, status_inscricao) VALUES
(1, 1, 'confirmada'),
(1, 2, 'pendente'),
(1, 3, 'confirmada');

-- Pagamentos
INSERT INTO pagamentos_encontristas (inscricao_id, valor_devido, data_vencimento, status_pagamento) VALUES
(1, 180.00, '2025-11-01', 'pendente'),
(2, 180.00, '2025-11-01', 'pendente'),
(3, 162.00, '2025-11-01', 'pago');

INSERT INTO pagamentos_encontreiros (participacao_id, valor_devido, data_vencimento, status_pagamento) VALUES
(1, 120.00, '2025-11-01', 'pago'),
(2, 120.00, '2025-11-01', 'pendente'),
(3, 120.00, '2025-11-01', 'pendente');

-- ===================================================
-- ÍNDICES PARA PERFORMANCE
-- ===================================================

-- Índices para buscas frequentes
CREATE INDEX idx_eventos_data ON eventos(data_inicio, data_fim);
CREATE INDEX idx_encontreiros_nome ON encontreiros(nome);
CREATE INDEX idx_encontristas_nome ON encontristas(nome);
CREATE INDEX idx_pagamentos_status ON pagamentos_encontristas(status_pagamento);
CREATE INDEX idx_pagamentos_encontreiros_status ON pagamentos_encontreiros(status_pagamento);
CREATE INDEX idx_log_mensagens_data ON log_mensagens_n8n(data_envio);

-- ===================================================
-- VIEWS ÚTEIS
-- ===================================================

-- View para relatório de pagamentos geral
CREATE VIEW v_pagamentos_geral AS
SELECT 
    'encontrista' as tipo_pessoa,
    e.nome,
    e.telefone,
    e.email,
    p.valor_devido,
    p.valor_pago,
    p.status_pagamento,
    p.data_vencimento,
    ev.nome as evento_nome
FROM pagamentos_encontristas p
JOIN inscricoes_encontristas i ON p.inscricao_id = i.id
JOIN encontristas e ON i.encontrista_id = e.id
JOIN eventos ev ON i.evento_id = ev.id

UNION ALL

SELECT 
    'encontreiro' as tipo_pessoa,
    e.nome,
    e.telefone,
    e.email,
    p.valor_devido,
    p.valor_pago,
    p.status_pagamento,
    p.data_vencimento,
    ev.nome as evento_nome
FROM pagamentos_encontreiros p
JOIN participacao_encontreiros pe ON p.participacao_id = pe.id
JOIN encontreiros e ON pe.encontreiro_id = e.id
JOIN eventos ev ON pe.evento_id = ev.id;

-- View para dashboard resumo
CREATE VIEW v_dashboard_resumo AS
SELECT 
    (SELECT COUNT(*) FROM encontreiros WHERE ativo = TRUE) as total_encontreiros,
    (SELECT COUNT(*) FROM encontristas WHERE ativo = TRUE) as total_encontristas,
    (SELECT COUNT(*) FROM inscricoes_encontristas WHERE status_inscricao = 'confirmada') as encontristas_confirmados,
    (SELECT COUNT(*) FROM v_pagamentos_geral WHERE status_pagamento = 'pendente') as pagamentos_pendentes,
    (SELECT COUNT(*) FROM v_pagamentos_geral WHERE status_pagamento = 'pago') as pagamentos_pagos,
    (SELECT COALESCE(SUM(valor_pago), 0) FROM v_pagamentos_geral) as total_arrecadado;

-- ===================================================
-- PROCEDIMENTOS ÚTEIS
-- ===================================================

DELIMITER $$

-- Procedure para calcular relatório de pagamentos por evento
CREATE PROCEDURE sp_relatorio_pagamentos(IN evento_id INT)
BEGIN
    SELECT 
        tipo_pessoa,
        COUNT(*) as quantidade,
        SUM(valor_devido) as total_devido,
        SUM(valor_pago) as total_pago,
        SUM(valor_devido - valor_pago) as total_pendente
    FROM v_pagamentos_geral v
    JOIN eventos e ON v.evento_nome = e.nome
    WHERE e.id = evento_id
    GROUP BY tipo_pessoa;
END$$

DELIMITER ;

-- ===================================================
-- COMENTÁRIOS DAS TABELAS
-- ===================================================

ALTER TABLE eventos COMMENT = 'Eventos principais do Face a Face';
ALTER TABLE encontreiros COMMENT = 'Equipe organizadora dos eventos';
ALTER TABLE encontristas COMMENT = 'Participantes dos eventos';
ALTER TABLE pagamentos_encontristas COMMENT = 'Controle de pagamentos dos participantes';
ALTER TABLE pagamentos_encontreiros COMMENT = 'Controle de pagamentos da equipe';
ALTER TABLE log_mensagens_n8n COMMENT = 'Log de mensagens automáticas enviadas via n8n';

-- ===================================================
-- BANCO DE DADOS INSTALADO COM SUCESSO!
-- ===================================================

SELECT 'Sistema Face a Face com Deus - Banco instalado com sucesso!' as STATUS;
