<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nossa Equipe - HostPanel Pro</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body { 
            font-family: 'Inter', 'Segoe UI', system-ui, sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #1f2937;
        }
        
        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 15px 0;
            position: sticky;
            top: 0;
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
        
        .btn-home {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.3);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 60px 20px;
        }
        
        .hero-section {
            text-align: center;
            margin-bottom: 80px;
            color: white;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #fff, #e0e7ff);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .hero-subtitle {
            font-size: 1.25rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
        }
        
        .content-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 60px;
            margin-bottom: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        }
        
        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 30px;
            text-align: center;
            position: relative;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            border-radius: 2px;
        }
        
        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            margin: 60px 0;
        }
        
        .team-member {
            background: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .team-member:hover {
            transform: translateY(-10px);
            border-color: #4f46e5;
            box-shadow: 0 20px 40px rgba(79, 70, 229, 0.2);
        }
        
        .member-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
        }
        
        .member-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 10px;
        }
        
        .member-role {
            color: #4f46e5;
            font-weight: 600;
            margin-bottom: 15px;
            text-transform: uppercase;
            font-size: 0.9rem;
        }
        
        .member-description {
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        
        .skills {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: center;
        }
        
        .skill-tag {
            background: #f3f4f6;
            color: #4b5563;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .project-features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin: 40px 0;
        }
        
        .feature-card {
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            padding: 30px;
            border-radius: 15px;
            border-left: 4px solid #4f46e5;
            transition: all 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }
        
        .feature-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 10px;
        }
        
        .feature-description {
            color: #6b7280;
            line-height: 1.6;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            margin: 60px 0;
        }
        
        .stat-card {
            text-align: center;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
        }
        
        .stat-label {
            color: #6b7280;
            font-weight: 500;
        }
        
        .tech-stack {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
            margin: 40px 0;
        }
        
        .tech-item {
            background: white;
            padding: 15px 25px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            color: #374151;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .tech-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        .cta-section {
            text-align: center;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            padding: 60px;
            border-radius: 20px;
            margin-top: 60px;
        }
        
        .cta-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        .cta-description {
            font-size: 1.2rem;
            margin-bottom: 30px;
            opacity: 0.9;
        }
        
        .cta-button {
            background: white;
            color: #4f46e5;
            padding: 15px 30px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            display: inline-block;
        }
        
        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(255, 255, 255, 0.3);
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
                <a href="index.php" class="btn-home">← Voltar ao Painel</a>
            </div>
        </div>
    </div>

    <!-- Container Principal -->
    <div class="container">
        <!-- Hero Section -->
        <div class="hero-section">
            <h1 class="hero-title">Nossa Equipe & Projeto</h1>
            <p class="hero-subtitle">Conheça a equipe de desenvolvimento por trás do HostPanel Pro e descubra as tecnologias e metodologias utilizadas na criação desta plataforma.</p>
        </div>

        <!-- Sobre o Projeto -->
        <div class="content-section">
            <h2 class="section-title">🎯 Sobre o Projeto</h2>
            <p style="font-size: 1.2rem; line-height: 1.8; color: #4b5563; text-align: center; margin-bottom: 40px;">
                O <strong>HostPanel Pro</strong> é uma plataforma completa de gerenciamento de hospedagem web desenvolvida com as mais modernas tecnologias. 
                Criado para simular um ambiente real de hospedagem, oferece recursos avançados como gerenciamento de usuários, 
                criação de sites, bancos de dados e monitoramento de recursos em tempo real.
            </p>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">100%</div>
                    <div class="stat-label">Código Original</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">15+</div>
                    <div class="stat-label">Tecnologias</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">30+</div>
                    <div class="stat-label">Funcionalidades</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">1000+</div>
                    <div class="stat-label">Linhas de Código</div>
                </div>
            </div>
        </div>

        <!-- Equipe de Desenvolvimento -->
        <div class="content-section">
            <h2 class="section-title">👥 Equipe de Desenvolvimento</h2>
            
            <div class="team-grid">
                <div class="team-member">
                    <div class="member-avatar">🧑‍💻</div>
                    <h3 class="member-name">Desenvolvedor Principal</h3>
                    <p class="member-role">Full Stack Developer</p>
                    <p class="member-description">
                        Especialista em desenvolvimento web moderno, responsável pela arquitetura do sistema, 
                        backend em PHP e integração com banco de dados MySQL.
                    </p>
                    <div class="skills">
                        <span class="skill-tag">PHP</span>
                        <span class="skill-tag">MySQL</span>
                        <span class="skill-tag">JavaScript</span>
                        <span class="skill-tag">Docker</span>
                    </div>
                </div>

                <div class="team-member">
                    <div class="member-avatar">🎨</div>
                    <h3 class="member-name">Designer UI/UX</h3>
                    <p class="member-role">Interface Designer</p>
                    <p class="member-description">
                        Responsável pela criação da interface moderna e intuitiva, experiência do usuário 
                        e design responsivo para todas as plataformas.
                    </p>
                    <div class="skills">
                        <span class="skill-tag">CSS3</span>
                        <span class="skill-tag">HTML5</span>
                        <span class="skill-tag">UI Design</span>
                        <span class="skill-tag">Responsive</span>
                    </div>
                </div>

                <div class="team-member">
                    <div class="member-avatar">⚙️</div>
                    <h3 class="member-name">DevOps Engineer</h3>
                    <p class="member-role">Infrastructure</p>
                    <p class="member-description">
                        Especialista em containerização e orquestração, responsável pela configuração 
                        do ambiente Docker e otimização de performance.
                    </p>
                    <div class="skills">
                        <span class="skill-tag">Docker</span>
                        <span class="skill-tag">Nginx</span>
                        <span class="skill-tag">Linux</span>
                        <span class="skill-tag">Performance</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tecnologias Utilizadas -->
        <div class="content-section">
            <h2 class="section-title">🛠️ Stack Tecnológico</h2>
            
            <div class="tech-stack">
                <div class="tech-item">
                    <span>🐘</span>
                    <span>PHP 8.2</span>
                </div>
                <div class="tech-item">
                    <span>🗄️</span>
                    <span>MySQL 8.0</span>
                </div>
                <div class="tech-item">
                    <span>🌐</span>
                    <span>Nginx</span>
                </div>
                <div class="tech-item">
                    <span>🐳</span>
                    <span>Docker</span>
                </div>
                <div class="tech-item">
                    <span>📱</span>
                    <span>JavaScript ES6+</span>
                </div>
                <div class="tech-item">
                    <span>🎨</span>
                    <span>CSS3 Grid/Flexbox</span>
                </div>
                <div class="tech-item">
                    <span>🔒</span>
                    <span>SSL/TLS</span>
                </div>
                <div class="tech-item">
                    <span>📊</span>
                    <span>PDO Database</span>
                </div>
            </div>
        </div>

        <!-- Funcionalidades -->
        <div class="content-section">
            <h2 class="section-title">⚡ Funcionalidades Implementadas</h2>
            
            <div class="project-features">
                <div class="feature-card">
                    <div class="feature-icon">👥</div>
                    <h3 class="feature-title">Gestão de Usuários</h3>
                    <p class="feature-description">Sistema completo de cadastro, autenticação e gerenciamento de perfis com diferentes níveis de acesso.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">🌐</div>
                    <h3 class="feature-title">Gerenciamento de Sites</h3>
                    <p class="feature-description">Criação e administração de domínios, subdomínios e configurações SSL para múltiplos sites.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">🗄️</div>
                    <h3 class="feature-title">Bancos de Dados</h3>
                    <p class="feature-description">Criação e gerenciamento de bancos MySQL com usuários dedicados para cada projeto.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h3 class="feature-title">Dashboard Analytics</h3>
                    <p class="feature-description">Monitoramento em tempo real de recursos, estatísticas de uso e performance dos sites.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">🔐</div>
                    <h3 class="feature-title">Segurança Avançada</h3>
                    <p class="feature-description">Autenticação segura, criptografia de senhas e proteção contra ataques comuns.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">🎨</div>
                    <h3 class="feature-title">Interface Moderna</h3>
                    <p class="feature-description">Design responsivo e intuitivo com animações suaves e experiência otimizada.</p>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="cta-section">
            <h2 class="cta-title">Pronto para Testar?</h2>
            <p class="cta-description">Explore todas as funcionalidades do HostPanel Pro e veja como pode ser útil para seus projetos.</p>
            <a href="index.php" class="cta-button">Acessar Painel de Demonstração</a>
        </div>
    </div>
</body>
</html>
