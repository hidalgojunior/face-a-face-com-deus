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
``````bash
git clone https://github.com/hidalgojunior/face-a-face-com-deus.git
cd face-a-face-com-deus
docker-compose up -d
# Acesse: http://localhost/f2f/install-database.php
``````

### 🎯 Para Quem é Este Sistema?
- **Igrejas** que realizam eventos Face a Face
- **Líderes** que precisam de automação de pagamentos
- **Ministros de Louvor** que querem player profissional
- **Equipes de Intercessão** para cobertura espiritual
- **Desenvolvedores** interessados em soluções para igrejas

### 🤝 Contribuições
Contribuições são bem-vindas! Veja nosso CONTRIBUTING.md para diretrizes.

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
