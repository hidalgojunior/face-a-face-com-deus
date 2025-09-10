<?php
/**
 * Teste de Conexão e Verificação do Banco de Dados
 * Sistema Face a Face com Deus
 */

header('Content-Type: application/json');

$host = 'dev_mysql';
$dbname = 'face_a_face';
$username = 'root';
$password = 'rootpassword';

$response = [
    'success' => false,
    'message' => '',
    'database_status' => [],
    'tables_count' => 0,
    'sample_data' => []
];

try {
    // Conectar ao banco
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $response['success'] = true;
    $response['message'] = 'Conexão com banco de dados estabelecida com sucesso!';
    
    // Verificar tabelas existentes
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $response['tables_count'] = count($tables);
    $response['database_status']['tables'] = $tables;
    
    // Verificar se as principais tabelas existem
    $required_tables = [
        'eventos', 'encontreiros', 'encontristas', 'pagamentos_encontristas',
        'pagamentos_encontreiros', 'inscricoes_encontristas', 'participacao_encontreiros'
    ];
    
    $missing_tables = array_diff($required_tables, $tables);
    $response['database_status']['missing_tables'] = $missing_tables;
    $response['database_status']['all_tables_present'] = empty($missing_tables);
    
    // Buscar dados de exemplo
    if (empty($missing_tables)) {
        // Contar registros por tabela
        $counts = [];
        foreach ($required_tables as $table) {
            $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
            $counts[$table] = $stmt->fetchColumn();
        }
        $response['sample_data']['record_counts'] = $counts;
        
        // Buscar alguns dados de exemplo
        $stmt = $pdo->query("SELECT nome, tipo, data_inicio, data_fim FROM eventos LIMIT 3");
        $response['sample_data']['eventos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $stmt = $pdo->query("SELECT nome, telefone, email FROM encontreiros LIMIT 3");
        $response['sample_data']['encontreiros'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $stmt = $pdo->query("SELECT nome, telefone, estado_civil FROM encontristas LIMIT 3");
        $response['sample_data']['encontristas'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Verificar configurações n8n
        $stmt = $pdo->query("SELECT nome_config, webhook_url, ativo FROM config_n8n");
        $response['sample_data']['config_n8n'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Status dos pagamentos
        $stmt = $pdo->query("
            SELECT 
                status_pagamento,
                COUNT(*) as quantidade,
                SUM(valor_devido) as total_devido,
                SUM(valor_pago) as total_pago
            FROM pagamentos_encontristas 
            GROUP BY status_pagamento
        ");
        $response['sample_data']['pagamentos_encontristas'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $stmt = $pdo->query("
            SELECT 
                status_pagamento,
                COUNT(*) as quantidade,
                SUM(valor_devido) as total_devido,
                SUM(valor_pago) as total_pago
            FROM pagamentos_encontreiros 
            GROUP BY status_pagamento
        ");
        $response['sample_data']['pagamentos_encontreiros'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
} catch (PDOException $e) {
    $response['success'] = false;
    $response['message'] = 'Erro na conexão: ' . $e->getMessage();
    $response['error_code'] = $e->getCode();
}

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>
