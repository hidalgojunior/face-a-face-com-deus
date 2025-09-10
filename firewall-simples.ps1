# Script simples para configurar firewall - DevServer Pro
# Execute como administrador

Write-Host "Configurando Firewall do DevServer Pro..." -ForegroundColor Green

# Verificar se é administrador
if (-NOT ([Security.Principal.WindowsPrincipal] [Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole([Security.Principal.WindowsBuiltInRole] "Administrator")) {
    Write-Host "ERRO: Execute como Administrador!" -ForegroundColor Red
    exit 1
}

# Configurar portas
$portas = 80,443,3306,5432,8000,8080,8081,8083,21

foreach ($porta in $portas) {
    Write-Host "Configurando porta $porta..." -NoNewline
    try {
        New-NetFirewallRule -DisplayName "DevServer-$porta" -Direction Inbound -Protocol TCP -LocalPort $porta -Action Allow -ErrorAction SilentlyContinue | Out-Null
        Write-Host " OK" -ForegroundColor Green
    } catch {
        Write-Host " JA EXISTE" -ForegroundColor Yellow
    }
}

# Portas FTP passivas
Write-Host "Configurando FTP passivo..." -NoNewline
try {
    New-NetFirewallRule -DisplayName "DevServer-FTP-Passive" -Direction Inbound -Protocol TCP -LocalPort "21000-21010" -Action Allow -ErrorAction SilentlyContinue | Out-Null
    Write-Host " OK" -ForegroundColor Green
} catch {
    Write-Host " JA EXISTE" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "CONFIGURACAO CONCLUIDA!" -ForegroundColor Green
Write-Host ""
Write-Host "Acesse de outros equipamentos:" -ForegroundColor Yellow
Write-Host "http://172.16.1.125/" -ForegroundColor Cyan
Write-Host "http://172.16.1.125/hosting-panel/" -ForegroundColor Cyan
Write-Host ""
