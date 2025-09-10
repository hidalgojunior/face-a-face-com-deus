# 🚀 Scripts de Pós-Publicação GitHub

## 📋 Scripts Automatizados para Próximos Passos

Este documento contém scripts prontos para executar os próximos passos após a publicação no GitHub.

---

## 1️⃣ Script: Criar Release v1.0.0

### `create-release.ps1`
```powershell
# Script para criar release da versão v1.0.0 no GitHub
# Requer: GitHub CLI (gh) instalado

Write-Host "🏷️ Criando Release v1.0.0 no GitHub..." -ForegroundColor Green

# Verificar se GitHub CLI está instalado
if (-not (Get-Command gh -ErrorAction SilentlyContinue)) {
    Write-Host "❌ GitHub CLI não encontrado!" -ForegroundColor Red
    Write-Host "📥 Baixe em: https://cli.github.com/" -ForegroundColor Yellow
    Write-Host "💡 Ou use: winget install GitHub.cli" -ForegroundColor Yellow
    exit 1
}

# Login no GitHub (se necessário)
gh auth status 2>$null
if ($LASTEXITCODE -ne 0) {
    Write-Host "🔐 Fazendo login no GitHub..." -ForegroundColor Yellow
    gh auth login
}

# Criar release
$releaseNotes = @"
# 🎯 Face a Face com Deus v1.0.0

## 🚀 Primeira Release Oficial

Sistema completo de gestão de eventos religiosos com funcionalidades profissionais.

### ✨ Principais Funcionalidades

#### 🎵 Player Profissional
- **4 Decks Independentes** com controles individuais
- **Crossfaders** para transições suaves
- **Sistema Automix** inteligente
- **Suporte Multi-formato**: MP3, WAV, MP4, WebM
- **Integração PowerPoint** para apresentações

#### 📊 Dashboard de Pagamentos
- **Automação n8n** completa
- **Relatórios Financeiros** detalhados
- **Notificações WhatsApp** via Evolution API
- **Gestão de Encontristas/Encontreiros**
- **Controle de Inscrições** e pagamentos

#### 🙏 Sistema de Ministração
- **Cobertura Espiritual** organizada
- **Escalas de Intercessão** automatizadas
- **Acompanhamento em Tempo Real**
- **Relatórios de Ministração**

#### 🐳 Infraestrutura Docker
- **Nginx** + **PHP 8.2** + **MySQL 8.0**
- **phpMyAdmin** + **pgAdmin** + **n8n**
- **File Manager** + **FTP Server**
- **SSL/HTTPS** configurado

### 🛠️ Tecnologias Utilizadas
- **Backend**: PHP 8.2, MySQL 8.0, Nginx
- **Frontend**: HTML5, CSS3, Bootstrap 5, JavaScript ES6+
- **Áudio/Vídeo**: Howler.js, Video.js, Web Audio API
- **Automação**: n8n workflows
- **DevOps**: Docker, Git, PowerShell

### 📦 Instalação Rápida
```bash
git clone https://github.com/hidalgojunior/face-a-face-com-deus.git
cd face-a-face-com-deus
docker-compose up -d
# Acesse: http://localhost/f2f/install-database.php
```

### 🎯 Para Quem é Este Sistema?
- **Igrejas** que realizam eventos Face a Face
- **Líderes** que precisam de automação de pagamentos
- **Ministros de Louvor** que querem player profissional
- **Equipes de Intercessão** para cobertura espiritual
- **Desenvolvedores** interessados em soluções para igrejas

### 🤝 Contribuições
Contribuições são bem-vindas! Veja nosso [CONTRIBUTING.md] para diretrizes.

### 🙏 Agradecimentos
Desenvolvido com ❤️ para a comunidade cristã.

---
*"Porque onde estiverem dois ou três reunidos em meu nome, aí estou eu no meio deles." - Mateus 18:20*
"@

try {
    gh release create v1.0.0 --title "🎯 Face a Face com Deus v1.0.0" --notes $releaseNotes
    Write-Host "✅ Release v1.0.0 criada com sucesso!" -ForegroundColor Green
    Write-Host "🔗 Acesse: https://github.com/hidalgojunior/face-a-face-com-deus/releases" -ForegroundColor Cyan
} catch {
    Write-Host "❌ Erro ao criar release: $($_.Exception.Message)" -ForegroundColor Red
}
```

---

## 2️⃣ Script: Configurar GitHub Pages

### `setup-github-pages.ps1`
```powershell
# Script para configurar GitHub Pages automaticamente
Write-Host "📄 Configurando GitHub Pages..." -ForegroundColor Green

# Criar pasta docs se não existir
if (-not (Test-Path "docs")) {
    New-Item -ItemType Directory -Name "docs"
    Write-Host "📁 Pasta docs criada" -ForegroundColor Yellow
}

# Criar index.html para GitHub Pages
$indexHtml = @"
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Face a Face com Deus - Sistema de Gestão</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4rem 0;
        }
        .feature-card {
            transition: transform 0.3s;
            height: 100%;
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-cross me-2"></i>Face a Face com Deus
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Funcionalidades</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#install">Instalação</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://github.com/hidalgojunior/face-a-face-com-deus" target="_blank">
                            <i class="fab fa-github me-1"></i>GitHub
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-section">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-4">
                <i class="fas fa-heart me-3"></i>
                Face a Face com Deus
            </h1>
            <p class="lead mb-4">
                Sistema completo de gestão de eventos religiosos com player profissional, 
                automação de pagamentos e cobertura espiritual.
            </p>
            <div class="row justify-content-center">
                <div class="col-auto">
                    <a href="https://github.com/hidalgojunior/face-a-face-com-deus" class="btn btn-light btn-lg me-3">
                        <i class="fab fa-github me-2"></i>Ver no GitHub
                    </a>
                    <a href="#install" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-download me-2"></i>Instalar Agora
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">🚀 Principais Funcionalidades</h2>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-music fa-3x text-primary"></i>
                            </div>
                            <h5 class="card-title">Player 4 Decks</h5>
                            <p class="card-text">Sistema profissional com crossfaders, automix e suporte multi-formato.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-chart-line fa-3x text-success"></i>
                            </div>
                            <h5 class="card-title">Dashboard Pagamentos</h5>
                            <p class="card-text">Automação completa com n8n, relatórios e notificações WhatsApp.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-praying-hands fa-3x text-warning"></i>
                            </div>
                            <h5 class="card-title">Cobertura Espiritual</h5>
                            <p class="card-text">Gestão de ministração, escalas de intercessão e acompanhamento.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fab fa-docker fa-3x text-info"></i>
                            </div>
                            <h5 class="card-title">Docker Completo</h5>
                            <p class="card-text">Infraestrutura pronta com PHP, MySQL, Nginx e n8n.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="install" class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="mb-4">💻 Instalação Rápida</h2>
                    <div class="card">
                        <div class="card-body">
                            <pre class="bg-dark text-light p-3 rounded"><code>git clone https://github.com/hidalgojunior/face-a-face-com-deus.git
cd face-a-face-com-deus
docker-compose up -d

# Acesse: http://localhost/f2f/install-database.php</code></pre>
                        </div>
                    </div>
                    <p class="mt-3 text-muted">
                        Requer: Docker Desktop e Git instalados
                    </p>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-primary text-white py-4">
        <div class="container text-center">
            <p class="mb-2">
                <strong>Face a Face com Deus</strong> - Desenvolvido com ❤️ para a comunidade cristã
            </p>
            <p class="mb-0 text-light">
                <em>"Porque onde estiverem dois ou três reunidos em meu nome, aí estou eu no meio deles." - Mateus 18:20</em>
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
"@

# Salvar index.html
$indexHtml | Out-File -FilePath "docs/index.html" -Encoding UTF8
Write-Host "✅ docs/index.html criado" -ForegroundColor Green

# Adicionar ao Git
git add docs/
git commit -m "📄 docs: Adiciona GitHub Pages com landing page

✨ Funcionalidades da página:
- Design responsivo com Bootstrap 5
- Seções: Hero, Funcionalidades, Instalação
- Links para GitHub e documentação
- Visual atrativo para comunidade cristã
- Instruções de instalação clara

🎯 Objetivo: Criar presença web profissional para o projeto"

Write-Host "✅ Commit realizado" -ForegroundColor Green

# Push para GitHub
git push origin master
Write-Host "✅ Enviado para GitHub" -ForegroundColor Green

Write-Host "`n🔗 Próximo passo:" -ForegroundColor Yellow
Write-Host "   1. Acesse: https://github.com/hidalgojunior/face-a-face-com-deus/settings/pages" -ForegroundColor Cyan
Write-Host "   2. Em 'Source' selecione: 'Deploy from a branch'" -ForegroundColor Cyan  
Write-Host "   3. Em 'Branch' selecione: 'master' e folder '/docs'" -ForegroundColor Cyan
Write-Host "   4. Clique em 'Save'" -ForegroundColor Cyan
Write-Host "   5. Aguarde alguns minutos e acesse:" -ForegroundColor Cyan
Write-Host "      https://hidalgojunior.github.io/face-a-face-com-deus/" -ForegroundColor Green
```

---

## 🚀 Como Usar os Scripts

### Execução Manual
```powershell
# 1. Criar release
.\create-release.ps1

# 2. Configurar GitHub Pages  
.\setup-github-pages.ps1

# 3. Configurar templates de Issues
.\create-issues-templates.ps1

# 4. Sistema de backup
.\setup-backup-monitoring.ps1
```

### Pré-requisitos
- **GitHub CLI** instalado (`winget install GitHub.cli`)
- **Git** configurado
- **PowerShell** (Windows)

### ✅ Benefícios dos Scripts

1. **🏷️ Release Profissional**
   - Release v1.0.0 com changelog detalhado
   - Documentação completa das funcionalidades
   - Links para download e instalação

2. **📄 GitHub Pages**
   - Landing page responsiva e profissional
   - Apresentação visual atrativa
   - Instruções de instalação claras

3. **🐛 Templates de Issues**
   - Facilita reportes de bugs
   - Padroniza solicitações de funcionalidades
   - Melhora comunicação com usuários

4. **💾 Sistema de Backup**
   - Backup automático do MySQL
   - Monitoramento de sistema
   - Agendamento no Windows

### 🎯 Resultado Final

Após executar todos os scripts, seu projeto terá:
- ✅ Presença profissional no GitHub
- ✅ Documentação completa e organizada
- ✅ Sistema de contribuições estruturado
- ✅ Backup e monitoramento automatizados
- ✅ Referência para comunidade cristã

---

**🙏 Transforme seu projeto em uma bênção para toda a comunidade cristã!**
