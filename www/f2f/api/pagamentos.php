<?php
/**
 * API para Gestão de Pagamentos - Face a Face
 * Integração com n8n para automação de mensagens
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// Configuração do banco de dados
$host = 'dev_mysql';
$dbname = 'face_a_face';
$username = 'root';
$password = 'root123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro na conexão com o banco de dados']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['REQUEST_URI'];

// Roteamento das APIs
if (strpos($path, '/api/pagamentos-pendentes') !== false) {
    handlePagamentosPendentes($pdo);
} elseif (strpos($path, '/api/pagamentos-atrasados') !== false) {
    handlePagamentosAtrasados($pdo);
} elseif (strpos($path, '/api/novos-confirmados') !== false) {
    handleNovosConfirmados($pdo);
} elseif (strpos($path, '/api/confirmar-pagamento') !== false) {
    handleConfirmarPagamento($pdo);
} elseif (strpos($path, '/api/log-mensagem') !== false) {
    handleLogMensagem($pdo);
} elseif (strpos($path, '/api/dashboard-pagamentos') !== false) {
    handleDashboardPagamentos($pdo);
} elseif (strpos($path, '/api/enviar-n8n') !== false) {
    handleEnviarN8N($pdo);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Endpoint não encontrado']);
}

/**
 * Buscar pagamentos pendentes para envio de lembretes
 */
function handlePagamentosPendentes($pdo) {
    $sql = "
        SELECT 
            pe.id,
            e.nome,
            e.telefone,
            e.email,
            pe.valor_devido,
            pe.valor_pago,
            pe.data_vencimento,
            'encontrista' as tipo_pessoa,
            ev.nome as evento_nome
        FROM pagamentos_encontristas pe
        JOIN inscricoes_encontristas ie ON pe.inscricao_id = ie.id
        JOIN encontristas e ON ie.encontrista_id = e.id
        JOIN eventos ev ON ie.evento_id = ev.id
        WHERE pe.status_pagamento IN ('pendente', 'parcial')
        AND pe.data_vencimento <= DATE_ADD(CURDATE(), INTERVAL 3 DAY)
        
        UNION ALL
        
        SELECT 
            pe.id,
            e.nome,
            e.telefone,
            e.email,
            pe.valor_devido,
            pe.valor_pago,
            pe.data_vencimento,
            'encontreiro' as tipo_pessoa,
            ev.nome as evento_nome
        FROM pagamentos_encontreiros pe
        JOIN participacao_encontreiros pae ON pe.participacao_id = pae.id
        JOIN encontreiros e ON pae.encontreiro_id = e.id
        JOIN eventos ev ON pae.evento_id = ev.id
        WHERE pe.status_pagamento IN ('pendente', 'parcial')
        AND pe.data_vencimento <= DATE_ADD(CURDATE(), INTERVAL 3 DAY)
        
        ORDER BY data_vencimento ASC
    ";
    
    $stmt = $pdo->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($result);
}

/**
 * Buscar novos confirmados para boas-vindas
 */
function handleNovosConfirmados($pdo) {
    $sql = "
        SELECT 
            e.id,
            e.nome,
            e.telefone,
            e.email,
            'encontrista' as tipo_pessoa,
            ev.nome as evento_nome,
            ev.data_inicio,
            ie.data_inscricao
        FROM inscricoes_encontristas ie
        JOIN encontristas e ON ie.encontrista_id = e.id
        JOIN eventos ev ON ie.evento_id = ev.id
        WHERE ie.status_inscricao = 'confirmada'
        AND ie.data_inscricao >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
        AND NOT EXISTS (
            SELECT 1 FROM log_mensagens_n8n lm 
            WHERE lm.destinatario_id = e.id 
            AND lm.destinatario_tipo = 'encontrista'
            AND lm.tipo_mensagem = 'welcome'
        )
        
        UNION ALL
        
        SELECT 
            e.id,
            e.nome,
            e.telefone,
            e.email,
            'encontreiro' as tipo_pessoa,
            ev.nome as evento_nome,
            ev.data_inicio,
            NOW() as data_inscricao
        FROM participacao_encontreiros pae
        JOIN encontreiros e ON pae.encontreiro_id = e.id
        JOIN eventos ev ON pae.evento_id = ev.id
        WHERE pae.id IN (
            SELECT participacao_id FROM pagamentos_encontreiros 
            WHERE status_pagamento = 'pago'
            AND data_pagamento >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
        )
        AND NOT EXISTS (
            SELECT 1 FROM log_mensagens_n8n lm 
            WHERE lm.destinatario_id = e.id 
            AND lm.destinatario_tipo = 'encontreiro'
            AND lm.tipo_mensagem = 'welcome'
        )
    ";
    
    $stmt = $pdo->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($result);
}

/**
 * Dashboard com resumo dos pagamentos
 */
function handleDashboardPagamentos($pdo) {
    // Total arrecadado
    $sqlArrecadado = "
        SELECT 
            COALESCE(SUM(pe.valor_pago), 0) as total_encontristas,
            COALESCE((SELECT SUM(valor_pago) FROM pagamentos_encontreiros), 0) as total_encontreiros
        FROM pagamentos_encontristas pe
    ";
    
    $stmt = $pdo->query($sqlArrecadado);
    $arrecadacao = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalArrecadado = $arrecadacao['total_encontristas'] + $arrecadacao['total_encontreiros'];
    
    // Pendentes e atrasados
    $sqlPendentes = "
        SELECT 
            COUNT(*) as qtd_pendentes,
            COALESCE(SUM(valor_devido - valor_pago), 0) as valor_pendente
        FROM (
            SELECT valor_devido, valor_pago FROM pagamentos_encontristas 
            WHERE status_pagamento IN ('pendente', 'parcial')
            UNION ALL
            SELECT valor_devido, valor_pago FROM pagamentos_encontreiros 
            WHERE status_pagamento IN ('pendente', 'parcial')
        ) as pendentes
    ";
    
    $stmt = $pdo->query($sqlPendentes);
    $pendentes = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Atrasados
    $sqlAtrasados = "
        SELECT 
            COUNT(*) as qtd_atrasados,
            COALESCE(SUM(valor_devido - valor_pago), 0) as valor_atrasado
        FROM (
            SELECT valor_devido, valor_pago FROM pagamentos_encontristas 
            WHERE status_pagamento IN ('pendente', 'parcial', 'atrasado')
            AND data_vencimento < CURDATE()
            UNION ALL
            SELECT valor_devido, valor_pago FROM pagamentos_encontreiros 
            WHERE status_pagamento IN ('pendente', 'parcial', 'atrasado')
            AND data_vencimento < CURDATE()
        ) as atrasados
    ";
    
    $stmt = $pdo->query($sqlAtrasados);
    $atrasados = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Mensagens enviadas hoje
    $sqlMensagens = "
        SELECT COUNT(*) as mensagens_hoje
        FROM log_mensagens_n8n
        WHERE DATE(data_envio) = CURDATE()
    ";
    
    $stmt = $pdo->query($sqlMensagens);
    $mensagens = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $dashboard = [
        'total_arrecadado' => number_format($totalArrecadado, 2, ',', '.'),
        'total_pendente' => number_format($pendentes['valor_pendente'], 2, ',', '.'),
        'qtd_pendentes' => $pendentes['qtd_pendentes'],
        'total_atrasado' => number_format($atrasados['valor_atrasado'], 2, ',', '.'),
        'qtd_atrasados' => $atrasados['qtd_atrasados'],
        'mensagens_hoje' => $mensagens['mensagens_hoje']
    ];
    
    echo json_encode($dashboard);
}

/**
 * Confirmar pagamento
 */
function handleConfirmarPagamento($pdo) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Método não permitido']);
        return;
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    $id = $input['id'] ?? null;
    $tipo = $input['tipo'] ?? null; // 'encontrista' ou 'encontreiro'
    $valor_pago = $input['valor_pago'] ?? null;
    $forma_pagamento = $input['forma_pagamento'] ?? null;
    
    if (!$id || !$tipo || !$valor_pago) {
        http_response_code(400);
        echo json_encode(['error' => 'Dados incompletos']);
        return;
    }
    
    $tabela = $tipo === 'encontrista' ? 'pagamentos_encontristas' : 'pagamentos_encontreiros';
    
    $sql = "
        UPDATE $tabela 
        SET 
            valor_pago = valor_pago + ?,
            forma_pagamento = ?,
            data_pagamento = NOW(),
            status_pagamento = CASE 
                WHEN valor_pago + ? >= valor_devido THEN 'pago'
                ELSE 'parcial'
            END
        WHERE id = ?
    ";
    
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([$valor_pago, $forma_pagamento, $valor_pago, $id]);
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Pagamento confirmado']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao confirmar pagamento']);
    }
}

/**
 * Registrar log de mensagem enviada
 */
function handleLogMensagem($pdo) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Método não permitido']);
        return;
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    $sql = "
        INSERT INTO log_mensagens_n8n (
            destinatario_tipo, destinatario_id, tipo_mensagem, canal,
            numero_telefone, email_destinatario, conteudo_mensagem, status_envio
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ";
    
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        $input['destinatario_tipo'],
        $input['destinatario_id'],
        $input['tipo_mensagem'],
        $input['canal'],
        $input['numero_telefone'] ?? null,
        $input['email_destinatario'] ?? null,
        $input['conteudo_mensagem'] ?? null,
        $input['status_envio']
    ]);
    
    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao registrar log']);
    }
}

/**
 * Enviar dados para n8n via webhook
 */
function handleEnviarN8N($pdo) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Método não permitido']);
        return;
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    $webhookUrl = 'http://dev_n8n:5678' . $input['webhook_path'];
    $dados = $input['dados'];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $webhookUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dados));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode >= 200 && $httpCode < 300) {
        echo json_encode(['success' => true, 'response' => $response]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao enviar para n8n', 'http_code' => $httpCode]);
    }
}
?>
