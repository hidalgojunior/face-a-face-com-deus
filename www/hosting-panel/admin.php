<?php
session_start();
require_once 'config.php';
requireLogin();

if (!isAdmin()) {
    header('Location: dashboard.php');
    exit;
}

// Obter estatísticas gerais
$stats = [];
$stats['total_users'] = $pdo->query("SELECT COUNT(*) FROM hosting_users")->fetchColumn();
$stats['total_sites'] = $pdo->query("SELECT COUNT(*) FROM hosting_sites")->fetchColumn();
$stats['total_databases'] = $pdo->query("SELECT COUNT(*) FROM hosting_databases")->fetchColumn();
$stats['disk_used'] = $pdo->query("SELECT SUM(disk_used) FROM hosting_users")->fetchColumn();

// Obter usuários
$users = $pdo->query("
    SELECT u.*, 
           COUNT(DISTINCT s.id) as site_count,
           COUNT(DISTINCT d.id) as db_count
    FROM hosting_users u 
    LEFT JOIN hosting_sites s ON u.id = s.user_id 
    LEFT JOIN hosting_databases d ON u.id = d.user_id 
    GROUP BY u.id 
    ORDER BY u.created_at DESC
")->fetchAll();

// Processar ações
if ($_POST) {
    switch ($_POST['action']) {
        case 'add_user':
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $plan = $_POST['plan'];
            
            $quotas = [
                'basic' => ['disk' => 1000, 'bandwidth' => 10000],
                'premium' => ['disk' => 5000, 'bandwidth' => 50000],
                'business' => ['disk' => 20000, 'bandwidth' => 200000]
            ];
            
            $stmt = $pdo->prepare("INSERT INTO hosting_users (username, email, password, plan, disk_quota, bandwidth_quota) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $username, 
                $email, 
                $password, 
                $plan, 
                $quotas[$plan]['disk'], 
                $quotas[$plan]['bandwidth']
            ]);
            break;
            
        case 'update_status':
            $user_id = $_POST['user_id'];
            $status = $_POST['status'];
            $pdo->prepare("UPDATE hosting_users SET status = ? WHERE id = ?")->execute([$status, $user_id]);
            break;
            
        case 'delete_user':
            $user_id = $_POST['user_id'];
            $pdo->prepare("DELETE FROM hosting_users WHERE id = ?")->execute([$user_id]);
            break;
    }
    
    header('Location: admin.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo - HostPanel</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: #f8fafc;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo h2 { font-size: 24px; }
        .admin-badge {
            background: rgba(255,255,255,0.2);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
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
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
        .stat-value {
            font-size: 32px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 5px;
        }
        .stat-label {
            color: #6b7280;
            font-size: 14px;
            text-transform: uppercase;
        }
        .section {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
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
        .btn-danger { background: #ef4444; }
        .btn-danger:hover { background: #dc2626; }
        .btn-small { padding: 6px 12px; font-size: 12px; }
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
        .status.pending { background: #fef3c7; color: #92400e; }
        .plan-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
        }
        .plan-basic { background: #e0e7ff; color: #3730a3; }
        .plan-premium { background: #dcfce7; color: #166534; }
        .plan-business { background: #fef3c7; color: #92400e; }
        .plan-unlimited { background: #f3e8ff; color: #6b21a8; }
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
        .actions {
            display: flex;
            gap: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <div class="logo">
                <h2>🚀 HostPanel - Administração</h2>
            </div>
            <div class="admin-badge">
                👨‍💼 Administrador: <?= htmlspecialchars($_SESSION['username']) ?>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Estatísticas Gerais -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-value"><?= $stats['total_users'] ?></div>
                <div class="stat-label">Usuários</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🌐</div>
                <div class="stat-value"><?= $stats['total_sites'] ?></div>
                <div class="stat-label">Sites</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🗄️</div>
                <div class="stat-value"><?= $stats['total_databases'] ?></div>
                <div class="stat-label">Databases</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">💾</div>
                <div class="stat-value"><?= formatBytes($stats['disk_used'] * 1024 * 1024) ?></div>
                <div class="stat-label">Armazenamento</div>
            </div>
        </div>

        <!-- Gerenciar Usuários -->
        <div class="section">
            <div class="section-header">
                <h3>👥 Gerenciar Usuários</h3>
                <button class="btn" onclick="openModal('userModal')">Adicionar Usuário</button>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Usuário</th>
                        <th>Email</th>
                        <th>Plano</th>
                        <th>Sites</th>
                        <th>DBs</th>
                        <th>Armazenamento</th>
                        <th>Status</th>
                        <th>Cadastro</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($user['username']) ?></strong></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><span class="plan-badge plan-<?= $user['plan'] ?>"><?= strtoupper($user['plan']) ?></span></td>
                            <td><?= $user['site_count'] ?></td>
                            <td><?= $user['db_count'] ?></td>
                            <td><?= formatBytes($user['disk_used'] * 1024 * 1024) ?> / <?= formatBytes($user['disk_quota'] * 1024 * 1024) ?></td>
                            <td><span class="status <?= $user['status'] ?>"><?= ucfirst($user['status']) ?></span></td>
                            <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                            <td>
                                <div class="actions">
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="action" value="update_status">
                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                        <select name="status" onchange="this.form.submit()" class="btn-small">
                                            <option value="active" <?= $user['status'] === 'active' ? 'selected' : '' ?>>Ativo</option>
                                            <option value="suspended" <?= $user['status'] === 'suspended' ? 'selected' : '' ?>>Suspenso</option>
                                            <option value="pending" <?= $user['status'] === 'pending' ? 'selected' : '' ?>>Pendente</option>
                                        </select>
                                    </form>
                                    <?php if ($user['plan'] !== 'unlimited'): ?>
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('Excluir usuário?')">
                                            <input type="hidden" name="action" value="delete_user">
                                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                            <button type="submit" class="btn btn-danger btn-small">Excluir</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Adicionar Usuário -->
    <div id="userModal" class="modal">
        <div class="modal-content">
            <button class="close-modal" onclick="closeModal('userModal')">×</button>
            <h3>Adicionar Novo Usuário</h3>
            <form method="POST">
                <input type="hidden" name="action" value="add_user">
                <div class="form-group">
                    <label>Nome de Usuário</label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Senha</label>
                    <input type="password" name="password" required>
                </div>
                <div class="form-group">
                    <label>Plano</label>
                    <select name="plan" required>
                        <option value="basic">Basic (1GB / 10GB banda)</option>
                        <option value="premium">Premium (5GB / 50GB banda)</option>
                        <option value="business">Business (20GB / 200GB banda)</option>
                    </select>
                </div>
                <button type="submit" class="btn">Criar Usuário</button>
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
