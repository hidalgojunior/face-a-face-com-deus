<?php
session_start();
require_once 'config.php';
requireLogin();

// Obter dados do usuário
$stmt = $pdo->prepare("SELECT * FROM hosting_users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Obter sites do usuário
$stmt = $pdo->prepare("SELECT * FROM hosting_sites WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$sites = $stmt->fetchAll();

// Obter bancos de dados
$stmt = $pdo->prepare("SELECT d.*, s.domain FROM hosting_databases d LEFT JOIN hosting_sites s ON d.site_id = s.id WHERE d.user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$databases = $stmt->fetchAll();

// Processar formulários
if ($_POST) {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_site':
                $domain = $_POST['domain'];
                $subdomain = $_POST['subdomain'] ?? null;
                $path = '/sites/' . $user['username'] . '/' . ($subdomain ? $subdomain : 'main');
                
                $stmt = $pdo->prepare("INSERT INTO hosting_sites (user_id, domain, subdomain, path) VALUES (?, ?, ?, ?)");
                if ($stmt->execute([$_SESSION['user_id'], $domain, $subdomain, $path])) {
                    $success = "Site adicionado com sucesso!";
                }
                break;
                
            case 'add_database':
                $site_id = $_POST['site_id'];
                $db_name = $_POST['db_name'];
                $db_user = $_POST['db_user'];
                $db_password = $_POST['db_password'];
                
                $stmt = $pdo->prepare("INSERT INTO hosting_databases (user_id, site_id, db_name, db_user, db_password) VALUES (?, ?, ?, ?, ?)");
                if ($stmt->execute([$_SESSION['user_id'], $site_id, $db_name, $db_user, $db_password])) {
                    $success = "Banco de dados criado com sucesso!";
                }
                break;
        }
        
        // Recarregar dados
        header('Location: dashboard.php');
        exit;
    }
}

$disk_percent = ($user['disk_used'] / $user['disk_quota']) * 100;
$bandwidth_percent = ($user['bandwidth_used'] / $user['bandwidth_quota']) * 100;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?= htmlspecialchars($user['username']) ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Inter', 'Segoe UI', system-ui, sans-serif; 
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
        }
        .header {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            padding: 20px 0;
            box-shadow: 0 4px 20px rgba(79, 70, 229, 0.3);
        }
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }
        .logo h2 { 
            color: white;
            font-size: 24px;
            font-weight: 700;
        }
        .user-info { 
            display: flex; 
            align-items: center; 
            gap: 20px; 
        }
        .user-info span {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
        }
        .plan-badge {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            text-transform: uppercase;
            font-weight: 600;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .logout-btn {
            background: rgba(239, 68, 68, 0.9);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .logout-btn:hover {
            background: #dc2626;
            transform: translateY(-1px);
        }
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: 1px solid rgba(79, 70, 229, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(79, 70, 229, 0.2);
        }
        .stat-card h3 {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        .stat-value {
            font-size: 28px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 10px;
        }
        .progress-bar {
            height: 6px;
            background: #e5e7eb;
            border-radius: 3px;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            background: #10b981;
            transition: width 0.3s;
        }
        .progress-fill.warning { background: #f59e0b; }
        .progress-fill.danger { background: #ef4444; }
        .section {
            background: white;
            margin-bottom: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: 1px solid rgba(229, 231, 235, 0.8);
            overflow: hidden;
        }
        .section-header {
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .section-header h3 {
            color: #1f2937;
            font-size: 18px;
        }
        .btn {
            background: #4f46e5;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            display: inline-block;
        }
        .btn:hover { background: #4338ca; }
        .btn-small {
            padding: 5px 12px;
            font-size: 12px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        .table th {
            background: #f9fafb;
            color: #6b7280;
            font-weight: 500;
            font-size: 14px;
        }
        .status {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }
        .status.active { background: #d1fae5; color: #065f46; }
        .status.suspended { background: #fee2e2; color: #991b1b; }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
        }
        .modal-content {
            background: white;
            width: 90%;
            max-width: 500px;
            margin: 50px auto;
            border-radius: 10px;
            padding: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #374151;
            font-weight: 500;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e5e7eb;
            border-radius: 6px;
            font-size: 14px;
        }
        .close-modal {
            float: right;
            background: #6b7280;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <div class="logo">
                <h2>🚀 HostPanel Pro</h2>
            </div>
            <div class="user-info">
            <span>Olá, <strong><?= htmlspecialchars($user['username']) ?></strong></span>
            <span class="plan-badge"><?= strtoupper($user['plan']) ?></span>
            <a href="profile.php" style="background: #10b981; color: white; padding: 8px 16px; border: none; border-radius: 6px; text-decoration: none; font-size: 14px; margin-right: 10px;">Meu Perfil</a>
            <a href="logout.php" class="logout-btn">Sair</a>
        </div>
        </div>
    </div>

    <div class="container">
        <?php if (isset($success)): ?>
            <div style="background: #d1fae5; color: #065f46; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <!-- Estatísticas -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Armazenamento</h3>
                <div class="stat-value"><?= formatBytes($user['disk_used'] * 1024 * 1024) ?></div>
                <div class="progress-bar">
                    <div class="progress-fill <?= $disk_percent > 80 ? 'danger' : ($disk_percent > 60 ? 'warning' : '') ?>" 
                         style="width: <?= $disk_percent ?>%"></div>
                </div>
                <small><?= formatBytes($user['disk_quota'] * 1024 * 1024) ?> total</small>
            </div>

            <div class="stat-card">
                <h3>Largura de Banda</h3>
                <div class="stat-value"><?= formatBytes($user['bandwidth_used'] * 1024 * 1024) ?></div>
                <div class="progress-bar">
                    <div class="progress-fill <?= $bandwidth_percent > 80 ? 'danger' : ($bandwidth_percent > 60 ? 'warning' : '') ?>" 
                         style="width: <?= $bandwidth_percent ?>%"></div>
                </div>
                <small><?= formatBytes($user['bandwidth_quota'] * 1024 * 1024) ?> mensal</small>
            </div>

            <div class="stat-card">
                <h3>Sites</h3>
                <div class="stat-value"><?= count($sites) ?></div>
                <small>sites ativos</small>
            </div>

            <div class="stat-card">
                <h3>Bancos de Dados</h3>
                <div class="stat-value"><?= count($databases) ?></div>
                <small>databases criados</small>
            </div>
        </div>

        <!-- Sites -->
        <div class="section">
            <div class="section-header">
                <h3>📂 Meus Sites</h3>
                <button class="btn" onclick="openModal('siteModal')">Adicionar Site</button>
            </div>
            <?php if ($sites): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Domínio</th>
                            <th>Subdomínio</th>
                            <th>Caminho</th>
                            <th>SSL</th>
                            <th>Status</th>
                            <th>Criado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sites as $site): ?>
                            <tr>
                                <td><?= htmlspecialchars($site['domain']) ?></td>
                                <td><?= htmlspecialchars($site['subdomain'] ?? '-') ?></td>
                                <td><code><?= htmlspecialchars($site['path']) ?></code></td>
                                <td><?= $site['ssl_enabled'] ? '✅ Ativo' : '❌ Inativo' ?></td>
                                <td><span class="status <?= $site['status'] ?>"><?= ucfirst($site['status']) ?></span></td>
                                <td><?= date('d/m/Y', strtotime($site['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div style="padding: 40px; text-align: center; color: #6b7280;">
                    Nenhum site criado ainda. Clique em "Adicionar Site" para começar.
                </div>
            <?php endif; ?>
        </div>

        <!-- Bancos de Dados -->
        <div class="section">
            <div class="section-header">
                <h3>🗄️ Bancos de Dados</h3>
                <button class="btn" onclick="openModal('dbModal')">Criar Database</button>
            </div>
            <?php if ($databases): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nome do Banco</th>
                            <th>Usuário</th>
                            <th>Site Vinculado</th>
                            <th>Tamanho</th>
                            <th>Criado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($databases as $db): ?>
                            <tr>
                                <td><code><?= htmlspecialchars($db['db_name']) ?></code></td>
                                <td><code><?= htmlspecialchars($db['db_user']) ?></code></td>
                                <td><?= htmlspecialchars($db['domain'] ?? 'N/A') ?></td>
                                <td><?= formatBytes($db['db_size'] * 1024 * 1024) ?></td>
                                <td><?= date('d/m/Y', strtotime($db['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div style="padding: 40px; text-align: center; color: #6b7280;">
                    Nenhum banco de dados criado ainda.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal Adicionar Site -->
    <div id="siteModal" class="modal">
        <div class="modal-content">
            <button class="close-modal" onclick="closeModal('siteModal')">×</button>
            <h3>Adicionar Novo Site</h3>
            <form method="POST">
                <input type="hidden" name="action" value="add_site">
                <div class="form-group">
                    <label>Domínio</label>
                    <input type="text" name="domain" placeholder="exemplo.com" required>
                </div>
                <div class="form-group">
                    <label>Subdomínio (opcional)</label>
                    <input type="text" name="subdomain" placeholder="www, blog, app">
                </div>
                <button type="submit" class="btn">Criar Site</button>
            </form>
        </div>
    </div>

    <!-- Modal Criar Database -->
    <div id="dbModal" class="modal">
        <div class="modal-content">
            <button class="close-modal" onclick="closeModal('dbModal')">×</button>
            <h3>Criar Novo Banco de Dados</h3>
            <form method="POST">
                <input type="hidden" name="action" value="add_database">
                <div class="form-group">
                    <label>Site</label>
                    <select name="site_id" required>
                        <option value="">Selecione um site</option>
                        <?php foreach ($sites as $site): ?>
                            <option value="<?= $site['id'] ?>"><?= htmlspecialchars($site['domain']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Nome do Banco</label>
                    <input type="text" name="db_name" placeholder="meu_banco_db" required>
                </div>
                <div class="form-group">
                    <label>Usuário do Banco</label>
                    <input type="text" name="db_user" placeholder="usuario_db" required>
                </div>
                <div class="form-group">
                    <label>Senha</label>
                    <input type="password" name="db_password" required>
                </div>
                <button type="submit" class="btn">Criar Database</button>
            </form>
        </div>
    </div>

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
        }
        
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
        
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }
    </script>
</body>
</html>
