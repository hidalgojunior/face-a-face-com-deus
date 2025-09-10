# Script executor principal para todos os próximos passos
param(
    [switch]$All,
    [switch]$Release,
    [switch]$Pages,
    [switch]$Help
)

function Show-Help {
    Write-Host "🚀 Face a Face com Deus - Scripts de Pós-Publicação" -ForegroundColor Green
    Write-Host "================================================" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "Uso: .\run-next-steps.ps1 [opções]" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "Opções:" -ForegroundColor White
    Write-Host "  -All      Executa todos os scripts" -ForegroundColor Cyan
    Write-Host "  -Release  Cria release v1.0.0 no GitHub" -ForegroundColor Cyan
    Write-Host "  -Pages    Configura GitHub Pages" -ForegroundColor Cyan
    Write-Host "  -Help     Mostra esta ajuda" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "Exemplos:" -ForegroundColor Yellow
    Write-Host "  .\run-next-steps.ps1 -All" -ForegroundColor Gray
    Write-Host "  .\run-next-steps.ps1 -Release -Pages" -ForegroundColor Gray
    Write-Host ""
    Write-Host "📋 Pré-requisitos:" -ForegroundColor Yellow
    Write-Host "  - GitHub CLI instalado (gh)" -ForegroundColor Gray
    Write-Host "  - Git configurado" -ForegroundColor Gray
    Write-Host "  - Login no GitHub realizado" -ForegroundColor Gray
    Write-Host ""
}

if ($Help) {
    Show-Help
    return
}

# Definir caminhos
$scriptPath = "d:\dev-server\scripts\github"
$rootPath = "d:\dev-server"

Write-Host "🎯 Face a Face com Deus - Automação Pós-Publicação" -ForegroundColor Green
Write-Host "=================================================" -ForegroundColor Cyan
Write-Host ""

# Ir para a pasta raiz
Set-Location $rootPath

if ($Release -or $All) {
    Write-Host "1️⃣ Criando Release v1.0.0..." -ForegroundColor Yellow
    try {
        & "$scriptPath\create-release.ps1"
        Write-Host "✅ Release criada com sucesso!" -ForegroundColor Green
    } catch {
        Write-Host "❌ Erro ao criar release: $($_.Exception.Message)" -ForegroundColor Red
    }
    Write-Host ""
}

if ($Pages -or $All) {
    Write-Host "2️⃣ Configurando GitHub Pages..." -ForegroundColor Yellow
    try {
        & "$scriptPath\setup-github-pages.ps1"
        Write-Host "✅ GitHub Pages configurado!" -ForegroundColor Green
    } catch {
        Write-Host "❌ Erro ao configurar Pages: $($_.Exception.Message)" -ForegroundColor Red
    }
    Write-Host ""
}

if (-not ($Release -or $Pages -or $All)) {
    Write-Host "⚠️ Nenhuma opção selecionada!" -ForegroundColor Yellow
    Show-Help
    return
}

Write-Host "🎉 PRÓXIMOS PASSOS CONCLUÍDOS!" -ForegroundColor Green
Write-Host "================================" -ForegroundColor Cyan
Write-Host "✅ Seu repositório agora tem:" -ForegroundColor White
if ($Release -or $All) {
    Write-Host "   - 🏷️ Release profissional v1.0.0" -ForegroundColor Cyan
}
if ($Pages -or $All) {
    Write-Host "   - 📄 GitHub Pages com landing page" -ForegroundColor Cyan
}
Write-Host ""
Write-Host "🔗 Links Importantes:" -ForegroundColor Yellow
Write-Host "   Repository: https://github.com/hidalgojunior/face-a-face-com-deus" -ForegroundColor Gray
if ($Release -or $All) {
    Write-Host "   Releases: https://github.com/hidalgojunior/face-a-face-com-deus/releases" -ForegroundColor Gray
}
if ($Pages -or $All) {
    Write-Host "   GitHub Pages: https://hidalgojunior.github.io/face-a-face-com-deus/" -ForegroundColor Gray
}
Write-Host ""
Write-Host "🙏 Seu projeto agora é uma referência para a comunidade cristã!" -ForegroundColor Green
