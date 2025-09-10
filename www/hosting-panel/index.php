<?php
session_start();
require_once 'config.php';

// Verificar se já existe um admin
$stmt = $pdo->query("SELECT COUNT(*) FROM hosting_users WHERE plan = 'unlimited'");
$hasAdmin = $stmt->fetchColumn() > 0;

if ($_POST) {
    if (!$hasAdmin) {
        // Criar conta admin
        $username = $_POST['admin_username'];
        $email = $_POST['admin_email'];
        $password = password_hash($_POST['admin_password'], PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("INSERT INTO hosting_users (username, email, password, plan, disk_quota, bandwidth_quota) VALUES (?, ?, ?, 'unlimited', 999999, 999999)");
        if ($stmt->execute([$username, $email, $password])) {
            $_SESSION['admin_user'] = $username;
            header('Location: admin.php');
            exit;
        }
    } elseif (isset($_POST['demo_signup'])) {
        // Criar conta de demonstração
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $plan = $_POST['demo_plan'];
        
        // Verificar se usuário já existe
        $checkUser = $pdo->prepare("SELECT COUNT(*) FROM hosting_users WHERE username = ? OR email = ?");
        $checkUser->execute([$username, $email]);
        
        if ($checkUser->fetchColumn() > 0) {
            $error = "Usuário ou email já existe!";
        } else {
            // Definir quotas por plano
            $quotas = [
                'basic' => ['disk' => 1000, 'bandwidth' => 10000],
                'premium' => ['disk' => 5000, 'bandwidth' => 50000],
                'business' => ['disk' => 20000, 'bandwidth' => 200000]
            ];
            
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO hosting_users (username, email, password, plan, disk_quota, bandwidth_quota) VALUES (?, ?, ?, ?, ?, ?)");
            
            if ($stmt->execute([$username, $email, $hashedPassword, $plan, $quotas[$plan]['disk'], $quotas[$plan]['bandwidth']])) {
                $success = "Conta criada com sucesso! Faça login para continuar.";
            } else {
                $error = "Erro ao criar conta. Tente novamente.";
            }
        }
    } else {
        // Login normal
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        $stmt = $pdo->prepare("SELECT * FROM hosting_users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_plan'] = $user['plan'];
            
            // Atualizar último login
            $pdo->prepare("UPDATE hosting_users SET last_login = NOW() WHERE id = ?")->execute([$user['id']]);
            
            if ($user['plan'] === 'unlimited') {
                header('Location: admin.php');
            } else {
                header('Location: dashboard.php');
            }
            exit;
        } else {
            $error = "Usuário ou senha inválidos!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= !$hasAdmin ? 'Configuração Inicial' : 'Login' ?> - Painel de Hospedagem</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Inter', 'Segoe UI', system-ui, sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }
        
        /* Efeito de partículas animadas */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="25" cy="25" r="2" fill="white" opacity="0.1"><animate attributeName="opacity" values="0.1;0.3;0.1" dur="3s" repeatCount="indefinite"/></circle><circle cx="75" cy="75" r="1.5" fill="white" opacity="0.1"><animate attributeName="opacity" values="0.1;0.4;0.1" dur="2s" repeatCount="indefinite"/></circle><circle cx="50" cy="10" r="1" fill="white" opacity="0.1"><animate attributeName="opacity" values="0.1;0.2;0.1" dur="4s" repeatCount="indefinite"/></circle></svg>');
            pointer-events: none;
            z-index: -1;
        }
        
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 15px 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .logo h1 {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 24px;
            font-weight: 700;
        }
        
        .nav-links {
            display: flex;
            gap: 30px;
            align-items: center;
        }
        
        .nav-links a {
            color: #4b5563;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .nav-links a:hover {
            color: #4f46e5;
        }
        
        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: #4f46e5;
            transition: width 0.3s ease;
        }
        
        .nav-links a:hover::after {
            width: 100%;
        }
        
        .main-container {
            display: flex;
            min-height: 100vh;
            padding-top: 80px;
        }
        
        .hero-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }
        
        .hero-content {
            text-align: center;
            color: white;
            max-width: 600px;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #fff, #e0e7ff);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 4px 20px rgba(255,255,255,0.3);
        }
        
        .hero-subtitle {
            font-size: 1.25rem;
            margin-bottom: 30px;
            opacity: 0.9;
            line-height: 1.6;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .feature-item {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: transform 0.3s ease;
        }
        
        .feature-item:hover {
            transform: translateY(-5px);
        }
        
        .feature-icon {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        
        .login-section {
            width: 450px;
            padding: 40px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-left: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .login-container {
            max-width: 100%;
            background: transparent;
            padding: 0;
            box-shadow: none;
            border-radius: 0;
        }
        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .login-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 8px;
        }
        
        .login-subtitle {
            color: #6b7280;
            font-size: 14px;
        }
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #374151;
            font-weight: 500;
            font-size: 14px;
        }
        
        .form-group input, .form-group select {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
        }
        
        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
        
        .btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }
        
        .btn:hover::before {
            left: 100%;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.3);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #6b7280, #4b5563);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
        }
        .alert {
            padding: 16px 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            font-size: 14px;
            font-weight: 500;
            border-left: 4px solid;
        }
        
        .alert-error {
            background: #fef2f2;
            color: #dc2626;
            border-left-color: #dc2626;
        }
        
        .alert-success {
            background: #f0fdf4;
            color: #16a34a;
            border-left-color: #16a34a;
        }
        
        .alert-info {
            background: #f0f9ff;
            color: #0284c7;
            border-left-color: #0284c7;
        }
        
        .demo-info {
            background: linear-gradient(135deg, #dbeafe, #e0e7ff);
            border: 1px solid #c7d2fe;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .demo-info h3 {
            color: #1d4ed8;
            margin-bottom: 10px;
            font-size: 18px;
        }
        
        .demo-info p {
            color: #1e40af;
            margin-bottom: 20px;
        }
        
        .demo-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }
        
        .demo-buttons .btn {
            width: auto;
            padding: 12px 24px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <div class="logo">
                <h1>🚀 HostPanel Pro</h1>
            </div>
            <div class="nav-links">
                <a href="#about">Sobre</a>
                <a href="#features">Recursos</a>
                <a href="#plans">Planos</a>
                <a href="about.php">Nossa Equipe</a>
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="main-container">
        <!-- Hero Section -->
        <div class="hero-section">
            <div class="hero-content">
                <h1 class="hero-title">Hospedagem Profissional</h1>
                <p class="hero-subtitle">Plataforma completa de gerenciamento de hospedagem com recursos avançados, interface intuitiva e suporte técnico especializado.</p>
                
                <div class="features-grid">
                    <div class="feature-item">
                        <div class="feature-icon">🚀</div>
                        <h4>Alta Performance</h4>
                        <p>Servidores otimizados</p>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">🔒</div>
                        <h4>Segurança Total</h4>
                        <p>SSL gratuito e backups</p>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">📊</div>
                        <h4>Dashboard Avançado</h4>
                        <p>Controle completo</p>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">🌐</div>
                        <h4>Domínios Ilimitados</h4>
                        <p>Gerencie todos os sites</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Login Section -->
        <div class="login-section">
            <div class="login-container">
                <div class="login-header">
                    <h2 class="login-title">Acesse sua Conta</h2>
                    <p class="login-subtitle">Entre no painel de controle da sua hospedagem</p>
                </div>
        
                <?php if (isset($error)): ?>
                    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                
                <?php if (isset($success)): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>
                
                <?php if (isset($_GET['deleted'])): ?>
                    <div class="alert alert-info">Conta excluída com sucesso. Obrigado por usar nossos serviços!</div>
                <?php endif; ?>
        
        <?php if (!$hasAdmin): ?>
            <div class="setup-note">
                <strong>Configuração Inicial</strong><br>
                Crie a conta de administrador para começar a usar o painel.
            </div>
            
            <form method="POST">
                <div class="form-group">
                    <label>Nome de Usuário Admin</label>
                    <input type="text" name="admin_username" required>
                </div>
                <div class="form-group">
                    <label>Email Admin</label>
                    <input type="email" name="admin_email" required>
                </div>
                <div class="form-group">
                    <label>Senha Admin</label>
                    <input type="password" name="admin_password" required>
                </div>
                <button type="submit" class="btn">Criar Conta Admin</button>
            </form>
        <?php else: ?>
                <div class="demo-info">
                    <h3>🎯 Área de Demonstração</h3>
                    <p>Crie sua conta gratuita e explore todos os recursos da nossa plataforma de hospedagem</p>
                    <div class="demo-buttons">
                        <button class="btn btn-success" onclick="showDemoForm()">Criar Conta Demo</button>
                        <button class="btn btn-secondary" onclick="showLoginForm()">Já tenho conta</button>
                    </div>
                </div>
            
            <!-- Formulário de Demonstração -->
            <div id="demoForm" style="display: none;">
                <form method="POST">
                    <input type="hidden" name="demo_signup" value="1">
                    <div class="form-group">
                        <label>Nome de Usuário</label>
                        <input type="text" name="username" placeholder="Escolha um nome de usuário" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="seu@email.com" required>
                    </div>
                    <div class="form-group">
                        <label>Senha</label>
                        <input type="password" name="password" placeholder="Mínimo 6 caracteres" required>
                    </div>
                    <div class="form-group">
                        <label>Plano de Demonstração</label>
                        <select name="demo_plan" required>
                            <option value="basic">Basic - 1GB de espaço</option>
                            <option value="premium" selected>Premium - 5GB de espaço</option>
                            <option value="business">Business - 20GB de espaço</option>
                        </select>
                    </div>
                    <div style="display: flex; gap: 15px;">
                        <button type="submit" class="btn">Criar Conta Demo</button>
                        <button type="button" class="btn btn-secondary" onclick="showLoginForm()">Cancelar</button>
                    </div>
                </form>
            </div>
            
            <!-- Formulário de Login -->
            <div id="loginForm" style="display: none;">
                <form method="POST">
                    <div class="form-group">
                        <label>Usuário ou Email</label>
                        <input type="text" name="username" required>
                    </div>
                    <div class="form-group">
                        <label>Senha</label>
                        <input type="password" name="password" required>
                    </div>
                    <div style="display: flex; gap: 15px;">
                        <button type="submit" class="btn">Entrar</button>
                        <button type="button" class="btn btn-success" onclick="showDemoForm()">Nova Conta</button>
                    </div>
                </form>
            </div>
        <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function showDemoForm() {
            document.getElementById('demoForm').style.display = 'block';
            document.getElementById('loginForm').style.display = 'none';
        }
        
        function showLoginForm() {
            document.getElementById('demoForm').style.display = 'none';
            document.getElementById('loginForm').style.display = 'block';
        }
        
        // Animação suave para os elementos
        window.addEventListener('load', function() {
            document.querySelectorAll('.feature-item').forEach((item, index) => {
                item.style.animationDelay = (index * 0.1) + 's';
                item.classList.add('animate-in');
            });
        });
    </script>

    <style>
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-in {
            animation: slideInUp 0.6s ease forwards;
        }
        
        @media (max-width: 768px) {
            .main-container {
                flex-direction: column;
            }
            
            .hero-section, .login-section {
                width: 100%;
                padding: 20px;
            }
            
            .hero-title {
                font-size: 2.5rem;
            }
            
            .features-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</body>
</html>
