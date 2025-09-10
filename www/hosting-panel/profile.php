<?php
session_start();
require_once 'config.php';
requireLogin();

// Obter dados do usuário
$stmt = $pdo->prepare("SELECT * FROM hosting_users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Processar alterações
if ($_POST) {
    $action = $_POST['action'];
    
    switch ($action) {
        case 'update_profile':
            $email = $_POST['email'];
            $current_password = $_POST['current_password'] ?? '';
            $new_password = $_POST['new_password'] ?? '';
            
            // Verificar senha atual se uma nova senha foi fornecida
            if (!empty($new_password)) {
                if (empty($current_password) || !password_verify($current_password, $user['password'])) {
                    $error = "Senha atual incorreta!";
                    break;
                }
                
                // Atualizar com nova senha
                $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE hosting_users SET email = ?, password = ? WHERE id = ?");
                $success = $stmt->execute([$email, $hashedPassword, $_SESSION['user_id']]);
                
                if ($success) {
                    $success_msg = "Perfil e senha atualizados com sucesso!";
                }
            } else {
                // Atualizar apenas email
                $stmt = $pdo->prepare("UPDATE hosting_users SET email = ? WHERE id = ?");
                $success = $stmt->execute([$email, $_SESSION['user_id']]);
                
                if ($success) {
                    $success_msg = "Email atualizado com sucesso!";
                }
            }
            
            // Recarregar dados do usuário
            $stmt = $pdo->prepare("SELECT * FROM hosting_users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch();
            break;
            
        case 'delete_account':
            $confirm_password = $_POST['confirm_password'];
            
            if (!password_verify($confirm_password, $user['password'])) {
                $error = "Senha incorreta! Não é possível excluir a conta.";
            } else {
                // Excluir conta (CASCADE remove sites e databases)
                $stmt = $pdo->prepare("DELETE FROM hosting_users WHERE id = ?");
                if ($stmt->execute([$_SESSION['user_id']])) {
                    session_destroy();
                    header('Location: index.php?deleted=1');
                    exit;
                }
            }
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - <?= htmlspecialchars($user['username']) ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: #f8fafc;
        }
        .header {
            background: white;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo h2 { color: #4f46e5; }
        .nav {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        .nav a {
            color: #6b7280;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 6px;
            transition: background 0.3s;
        }
        .nav a:hover {
            background: #f3f4f6;
            color: #1f2937;
        }
        .nav a.active {
            background: #4f46e5;
            color: white;
        }
        .container {
            max-width: 800px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .card-header {
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
        }
        .card-header h3 {
            color: #1f2937;
            font-size: 18px;
        }
        .card-body {
            padding: 30px;
        }
        .form-group {
            margin-bottom: 25px;
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
        .form-group input:focus {
            outline: none;
            border-color: #4f46e5;
        }
        .btn {
            background: #4f46e5;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            display: inline-block;
            font-weight: 500;
        }
        .btn:hover { background: #4338ca; }
        .btn-danger {
            background: #ef4444;
        }
        .btn-danger:hover {
            background: #dc2626;
        }
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }
        .profile-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        .info-item {
            padding: 15px;
            background: #f9fafb;
            border-radius: 8px;
        }
        .info-label {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .info-value {
            font-size: 16px;
            color: #1f2937;
            font-weight: 500;
        }
        .danger-zone {
            border: 2px solid #fee2e2;
            border-radius: 8px;
            padding: 20px;
            background: #fef2f2;
        }
        .danger-zone h4 {
            color: #dc2626;
            margin-bottom: 10px;
        }
        .danger-zone p {
            color: #7f1d1d;
            font-size: 14px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <h2>🚀 HostPanel</h2>
        </div>
        <div class="nav">
            <a href="dashboard.php">Dashboard</a>
            <a href="profile.php" class="active">Meu Perfil</a>
            <a href="logout.php">Sair</a>
        </div>
    </div>

    <div class="container">
        <?php if (isset($success_msg)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success_msg) ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Informações da Conta -->
        <div class="card">
            <div class="card-header">
                <h3>👤 Informações da Conta</h3>
            </div>
            <div class="card-body">
                <div class="profile-info">
                    <div class="info-item">
                        <div class="info-label">Usuário</div>
                        <div class="info-value"><?= htmlspecialchars($user['username']) ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Plano</div>
                        <div class="info-value"><?= strtoupper($user['plan']) ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Status</div>
                        <div class="info-value"><?= ucfirst($user['status']) ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Membro desde</div>
                        <div class="info-value"><?= date('d/m/Y', strtotime($user['created_at'])) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Editar Perfil -->
        <div class="card">
            <div class="card-header">
                <h3>✏️ Editar Perfil</h3>
            </div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="action" value="update_profile">
                    
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Senha Atual (apenas para alterar senha)</label>
                        <input type="password" name="current_password" placeholder="Deixe em branco para não alterar">
                    </div>
                    
                    <div class="form-group">
                        <label>Nova Senha</label>
                        <input type="password" name="new_password" placeholder="Deixe em branco para não alterar">
                    </div>
                    
                    <button type="submit" class="btn">Salvar Alterações</button>
                </form>
            </div>
        </div>

        <!-- Zona de Perigo -->
        <div class="card">
            <div class="card-header">
                <h3>⚠️ Zona de Perigo</h3>
            </div>
            <div class="card-body">
                <div class="danger-zone">
                    <h4>Excluir Conta</h4>
                    <p>Esta ação é <strong>irreversível</strong>. Todos os seus sites, bancos de dados e arquivos serão permanentemente excluídos.</p>
                    
                    <form method="POST" onsubmit="return confirm('⚠️ ATENÇÃO: Esta ação é irreversível! Todos os seus dados serão perdidos. Deseja realmente excluir sua conta?');">
                        <input type="hidden" name="action" value="delete_account">
                        <div class="form-group">
                            <label>Digite sua senha para confirmar</label>
                            <input type="password" name="confirm_password" required>
                        </div>
                        <button type="submit" class="btn btn-danger">Excluir Conta Permanentemente</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
