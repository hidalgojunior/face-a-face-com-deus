<?php
/**
 * Página de teste para verificar conexões com bancos de dados
 */

// Configurações dos bancos
$mysql_config = [
    'host' => 'mysql',
    'dbname' => 'devdb',
    'username' => 'devuser',
    'password' => 'devpassword'
];

$pgsql_config = [
    'host' => 'postgres',
    'dbname' => 'devdb',
    'username' => 'devuser',
    'password' => 'devpassword'
];

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste de Conexões - Servidor de Desenvolvimento</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0; 
            padding: 20px; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            min-height: 100vh;
        }
        .container { 
            max-width: 800px; 
            margin: 0 auto; 
            background: rgba(255,255,255,0.1);
            padding: 30px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
        }
        .test-result {
            margin: 15px 0;
            padding: 15px;
            border-radius: 8px;
            background: rgba(255,255,255,0.1);
        }
        .success { background: rgba(76, 175, 80, 0.3) !important; }
        .error { background: rgba(244, 67, 54, 0.3) !important; }
        .info { background: rgba(33, 150, 243, 0.3) !important; }
        h1, h2 { text-align: center; }
        .back-link {
            text-align: center;
            margin-top: 30px;
        }
        .back-link a {
            color: #fff;
            text-decoration: none;
            background: rgba(255,255,255,0.2);
            padding: 10px 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 Teste de Conexões</h1>
        
        <?php
        // Teste PHP
        echo '<div class="test-result success">';
        echo '<h2>✅ PHP</h2>';
        echo '<p><strong>Versão:</strong> ' . PHP_VERSION . '</p>';
        echo '<p><strong>Servidor:</strong> ' . $_SERVER['SERVER_SOFTWARE'] . '</p>';
        echo '<p><strong>Timestamp:</strong> ' . date('Y-m-d H:i:s') . '</p>';
        echo '</div>';

        // Teste MySQL
        echo '<div class="test-result">';
        echo '<h2>🗄️ MySQL</h2>';
        try {
            $mysql_dsn = "mysql:host={$mysql_config['host']};dbname={$mysql_config['dbname']}";
            $mysql_pdo = new PDO($mysql_dsn, $mysql_config['username'], $mysql_config['password']);
            $mysql_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $stmt = $mysql_pdo->query('SELECT VERSION() as version');
            $version = $stmt->fetch(PDO::FETCH_ASSOC);
            
            echo '<p class="success">✅ <strong>Conexão:</strong> Sucesso</p>';
            echo '<p><strong>Versão:</strong> ' . $version['version'] . '</p>';
            echo '<p><strong>Host:</strong> ' . $mysql_config['host'] . '</p>';
            echo '<p><strong>Database:</strong> ' . $mysql_config['dbname'] . '</p>';
        } catch (PDOException $e) {
            echo '<p class="error">❌ <strong>Erro:</strong> ' . $e->getMessage() . '</p>';
        }
        echo '</div>';

        // Teste PostgreSQL
        echo '<div class="test-result">';
        echo '<h2>🐘 PostgreSQL</h2>';
        try {
            $pgsql_dsn = "pgsql:host={$pgsql_config['host']};dbname={$pgsql_config['dbname']}";
            $pgsql_pdo = new PDO($pgsql_dsn, $pgsql_config['username'], $pgsql_config['password']);
            $pgsql_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $stmt = $pgsql_pdo->query('SELECT version()');
            $version = $stmt->fetch(PDO::FETCH_ASSOC);
            
            echo '<p class="success">✅ <strong>Conexão:</strong> Sucesso</p>';
            echo '<p><strong>Versão:</strong> ' . substr($version['version'], 0, 50) . '...</p>';
            echo '<p><strong>Host:</strong> ' . $pgsql_config['host'] . '</p>';
            echo '<p><strong>Database:</strong> ' . $pgsql_config['dbname'] . '</p>';
        } catch (PDOException $e) {
            echo '<p class="error">❌ <strong>Erro:</strong> ' . $e->getMessage() . '</p>';
        }
        echo '</div>';

        // Informações do Sistema
        echo '<div class="test-result info">';
        echo '<h2>ℹ️ Informações do Sistema</h2>';
        echo '<p><strong>Sistema:</strong> ' . php_uname() . '</p>';
        echo '<p><strong>Memória Limite:</strong> ' . ini_get('memory_limit') . '</p>';
        echo '<p><strong>Tempo Limite:</strong> ' . ini_get('max_execution_time') . 's</p>';
        echo '<p><strong>Upload Máximo:</strong> ' . ini_get('upload_max_filesize') . '</p>';
        echo '<p><strong>POST Máximo:</strong> ' . ini_get('post_max_size') . '</p>';
        echo '</div>';

        // Extensões PHP
        echo '<div class="test-result info">';
        echo '<h2>📦 Extensões PHP Carregadas</h2>';
        $extensions = get_loaded_extensions();
        sort($extensions);
        echo '<p>' . implode(', ', $extensions) . '</p>';
        echo '</div>';
        ?>

        <div class="back-link">
            <a href="/">← Voltar ao Início</a>
        </div>
    </div>
</body>
</html>
