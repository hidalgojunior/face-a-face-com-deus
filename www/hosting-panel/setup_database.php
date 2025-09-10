<?php
// Configuração do banco de dados
$host = 'mysql';
$dbname = 'devdb';
$username = 'devuser';
$password = 'devpassword';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}

// Criar tabelas se não existirem
$createTables = "
CREATE TABLE IF NOT EXISTS hosting_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    plan VARCHAR(20) DEFAULT 'basic',
    disk_quota INT DEFAULT 1000,
    disk_used INT DEFAULT 0,
    bandwidth_quota INT DEFAULT 10000,
    bandwidth_used INT DEFAULT 0,
    status ENUM('active', 'suspended', 'pending') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
);

CREATE TABLE IF NOT EXISTS hosting_sites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    domain VARCHAR(100) NOT NULL,
    subdomain VARCHAR(50),
    path VARCHAR(255),
    status ENUM('active', 'suspended', 'maintenance') DEFAULT 'active',
    ssl_enabled BOOLEAN DEFAULT FALSE,
    php_version VARCHAR(10) DEFAULT '8.2',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES hosting_users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS hosting_databases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    site_id INT,
    db_name VARCHAR(50) NOT NULL,
    db_user VARCHAR(50) NOT NULL,
    db_password VARCHAR(255) NOT NULL,
    db_size INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES hosting_users(id) ON DELETE CASCADE,
    FOREIGN KEY (site_id) REFERENCES hosting_sites(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS hosting_stats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    site_id INT,
    date DATE,
    visits INT DEFAULT 0,
    bandwidth INT DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES hosting_users(id) ON DELETE CASCADE,
    FOREIGN KEY (site_id) REFERENCES hosting_sites(id) ON DELETE CASCADE
);
";

try {
    $pdo->exec($createTables);
    echo "✅ Banco de dados configurado com sucesso!\n";
} catch(PDOException $e) {
    echo "❌ Erro ao criar tabelas: " . $e->getMessage() . "\n";
}

// Não inserir dados iniciais - sistema limpo para demonstração
echo "✅ Sistema configurado para demonstração - sem dados iniciais!\n";
?>
