<?php
/**
 * Instalador do Banco de Dados - Sistema Face a Face com Deus
 * Este script cria todas as tabelas necessárias para o sistema
 */

header('Content-Type: text/html; charset=utf-8');

// Configuração do banco de dados
$host = 'dev_mysql';
$dbname = 'face_a_face';
$username = 'root';
$password = 'rootpassword';

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🎯 Face a Face - Instalador do Banco de Dados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .install-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
        }
        .step-card {
            transition: transform 0.2s;
            border-left: 4px solid #007bff;
        }
        .step-success {
            border-left-color: #28a745;
        }
        .step-error {
            border-left-color: #dc3545;
        }
        .log-area {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 1rem;
            height: 300px;
            overflow-y: auto;
            font-family: 'Courier New', monospace;
            font-size: 0.875rem;
        }
        .success-text { color: #28a745; }
        .error-text { color: #dc3545; }
        .info-text { color: #007bff; }
        .warning-text { color: #ffc107; }
    </style>
</head>
<body class="bg-light">
    <div class="install-header">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h1><i class="fas fa-database me-2"></i>Face a Face com Deus</h1>
                    <p class="lead mb-0">Instalador do Banco de Dados</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container my-5">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Instalação do Sistema</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['install'])) {
                            installDatabase($host, $dbname, $username, $password);
                        } else {
                            showInstallForm();
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
function showInstallForm() {
    global $host, $dbname, $username, $password;
    ?>
    <div class="text-center mb-4">
        <i class="fas fa-database fa-4x text-primary mb-3"></i>
        <h4>Sistema Face a Face com Deus</h4>
        <p class="text-muted">Este instalador criará todas as tabelas necessárias para o funcionamento do sistema.</p>
    </div>

    <div class="alert alert-info">
        <h6><i class="fas fa-info-circle me-2"></i>Configuração Atual:</h6>
        <ul class="mb-0">
            <li><strong>Servidor:</strong> <?php echo $host; ?></li>
            <li><strong>Banco:</strong> <?php echo $dbname; ?></li>
            <li><strong>Usuário:</strong> <?php echo $username; ?></li>
        </ul>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-3x text-success mb-3"></i>
                    <h6>Gestão de Pessoas</h6>
                    <small class="text-muted">Encontreiros, Encontristas, Equipes</small>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-money-bill fa-3x text-warning mb-3"></i>
                    <h6>Sistema de Pagamentos</h6>
                    <small class="text-muted">Controle financeiro completo</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-alt fa-3x text-info mb-3"></i>
                    <h6>Eventos e Ministrações</h6>
                    <small class="text-muted">Cronograma e programação</small>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-robot fa-3x text-purple mb-3"></i>
                    <h6>Automação n8n</h6>
                    <small class="text-muted">Mensagens e workflows</small>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="">
        <div class="text-center">
            <button type="submit" name="install" class="btn btn-primary btn-lg">
                <i class="fas fa-play me-2"></i>Iniciar Instalação
            </button>
        </div>
    </form>
    <?php
}

function installDatabase($host, $dbname, $username, $password) {
    ?>
    <div id="installation-log">
        <h5><i class="fas fa-cogs me-2"></i>Processo de Instalação</h5>
        <div class="log-area" id="log-content">
            <div class="info-text">[INFO] Iniciando instalação do banco de dados...</div>
        </div>
        <div class="mt-3">
            <div class="progress">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%" id="progress-bar"></div>
            </div>
        </div>
    </div>

    <script>
        let progress = 0;
        const logContent = document.getElementById('log-content');
        const progressBar = document.getElementById('progress-bar');
        
        function updateProgress(percent, message, type = 'info') {
            progress = percent;
            progressBar.style.width = percent + '%';
            
            const colors = {
                'info': 'info-text',
                'success': 'success-text',
                'error': 'error-text',
                'warning': 'warning-text'
            };
            
            const newLine = document.createElement('div');
            newLine.className = colors[type] || 'info-text';
            newLine.textContent = `[${type.toUpperCase()}] ${message}`;
            logContent.appendChild(newLine);
            logContent.scrollTop = logContent.scrollHeight;
        }
    </script>
    <?php

    try {
        // Conectar ao MySQL (sem especificar banco)
        updateLog("Conectando ao servidor MySQL...", 10);
        $pdo = new PDO("mysql:host=$host;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        updateLog("✅ Conexão com MySQL estabelecida", 15, 'success');

        // Criar banco de dados se não existir
        updateLog("Verificando/criando banco de dados: $dbname", 20);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `$dbname`");
        updateLog("✅ Banco de dados $dbname pronto", 25, 'success');

        // Array com todas as tabelas
        $tables = getSQLTables();
        $totalTables = count($tables);
        $currentTable = 0;

        foreach ($tables as $tableName => $sql) {
            $currentTable++;
            $progressPercent = 25 + (($currentTable / $totalTables) * 70);
            
            updateLog("Criando tabela: $tableName", $progressPercent);
            
            try {
                $pdo->exec($sql);
                updateLog("✅ Tabela $tableName criada com sucesso", $progressPercent, 'success');
            } catch (PDOException $e) {
                if (strpos($e->getMessage(), 'already exists') !== false) {
                    updateLog("⚠️ Tabela $tableName já existe - pulando", $progressPercent, 'warning');
                } else {
                    throw $e;
                }
            }
        }

        // Inserir dados iniciais
        updateLog("Inserindo dados iniciais...", 95);
        insertInitialData($pdo);
        updateLog("✅ Dados iniciais inseridos", 98, 'success');

        updateLog("🎉 INSTALAÇÃO CONCLUÍDA COM SUCESSO!", 100, 'success');
        updateLog("Sistema Face a Face com Deus está pronto para uso!", 100, 'success');

        // Botão para acessar o dashboard
        echo '<div class="text-center mt-4">';
        echo '<a href="dashboard-pagamentos.html" class="btn btn-success btn-lg">';
        echo '<i class="fas fa-rocket me-2"></i>Acessar Dashboard';
        echo '</a>';
        echo '</div>';

    } catch (PDOException $e) {
        $errorProgress = isset($progress) ? $progress : 0;
        updateLog("❌ ERRO: " . $e->getMessage(), $errorProgress, 'error');
        echo '<div class="alert alert-danger mt-4">';
        echo '<h6>Erro na Instalação:</h6>';
        echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<div class="mt-2">';
        echo '<a href="install-database.php" class="btn btn-primary">Tentar Novamente</a>';
        echo '</div>';
        echo '</div>';
    }
}

function updateLog($message, $progress, $type = 'info') {
    echo "<script>";
    echo "updateProgress($progress, " . json_encode($message) . ", '$type');";
    echo "</script>";
    flush();
    ob_flush();
    usleep(100000); // 0.1 segundo de delay para visualização
}

function getSQLTables() {
    return [
        'eventos' => "
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",
        
        'encontreiros' => "
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",

        'encontristas' => "
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",

        'inscricoes_encontristas' => "
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",

        'participacao_encontreiros' => "
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",

        'valores_evento' => "
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",

        'pagamentos_encontristas' => "
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",

        'pagamentos_encontreiros' => "
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",

        'sub_eventos' => "
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",

        'ministracoes' => "
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",

        'ministracao_intercessores' => "
            CREATE TABLE ministracao_intercessores (
                id INT PRIMARY KEY AUTO_INCREMENT,
                ministracao_id INT,
                encontreiro_id INT,
                principal BOOLEAN DEFAULT FALSE,
                observacoes TEXT,
                FOREIGN KEY (ministracao_id) REFERENCES ministracoes(id) ON DELETE CASCADE,
                FOREIGN KEY (encontreiro_id) REFERENCES encontreiros(id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",

        'log_mensagens_n8n' => "
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",

        'config_n8n' => "
            CREATE TABLE config_n8n (
                id INT PRIMARY KEY AUTO_INCREMENT,
                nome_config VARCHAR(100) NOT NULL,
                webhook_url VARCHAR(500) NOT NULL,
                tipo_automacao ENUM('pagamento', 'evento', 'geral') NOT NULL,
                ativo BOOLEAN DEFAULT TRUE,
                headers_customizados JSON,
                parametros_default JSON,
                observacoes TEXT
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        "
    ];
}

function insertInitialData($pdo) {
    // Inserir configurações padrão do n8n
    $pdo->exec("
        INSERT INTO config_n8n (nome_config, webhook_url, tipo_automacao, ativo, observacoes) VALUES
        ('Lembrete de Pagamento', 'http://localhost:5678/webhook/pagamento-lembrete', 'pagamento', TRUE, 'Webhook para lembretes automáticos de pagamento'),
        ('Boas-vindas', 'http://localhost:5678/webhook/boas-vindas', 'evento', TRUE, 'Mensagem de boas-vindas para novos inscritos'),
        ('Lembrete de Evento', 'http://localhost:5678/webhook/lembrete-evento', 'evento', TRUE, 'Lembretes sobre datas do evento')
    ");

    // Inserir evento de exemplo
    $pdo->exec("
        INSERT INTO eventos (nome, tipo, publico_alvo, descricao, data_inicio, data_fim, local_evento) VALUES
        ('Face a Face de Homens 2025', 'homens', 'Homens de 18 a 60 anos', 'Encontro anual Face a Face com Deus para homens', '2025-11-15', '2025-11-17', 'Centro de Retiros Água Viva')
    ");

    // Inserir valores do evento
    $pdo->exec("
        INSERT INTO valores_evento (evento_id, valor_encontrista, valor_encontreiro, data_limite_pagamento, desconto_antecipado, data_limite_desconto) VALUES
        (1, 180.00, 120.00, '2025-11-01', 10.00, '2025-10-15')
    ");
}
?>
