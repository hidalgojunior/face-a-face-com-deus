# DevServer Pro - Configuração de Firewall
# Execute como Administrador

$Host.UI.RawUI.WindowTitle = "DevServer Pro - Firewall Setup"

Write-Host ""
Write-Host "================================================================" -ForegroundColor Cyan
Write-Host "            DevServer Pro - Configuração de Firewall           " -ForegroundColor Yellow
Write-Host "================================================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Configurando acesso externo para IP: " -NoNewline
Write-Host "172.16.1.125" -ForegroundColor Green
Write-Host ""

# Verificar se está executando como administrador
if (-NOT ([Security.Principal.WindowsPrincipal] [Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole([Security.Principal.WindowsBuiltInRole] "Administrator")) {
    Write-Host "ERRO: Execute este script como Administrador!" -ForegroundColor Red
    Write-Host ""
    Write-Host "Clique com botão direito no PowerShell e selecione 'Executar como administrador'" -ForegroundColor Yellow
    Write-Host ""
    Read-Host "Pressione ENTER para sair"
    exit 1
}

Write-Host "[INFO] Executando como Administrador... " -NoNewline
Write-Host "OK" -ForegroundColor Green
Write-Host ""

# Lista de portas para configurar
$ports = @(
    @{Port=80; Name="DevServer HTTP"; Service="Servidor Web"},
    @{Port=443; Name="DevServer HTTPS"; Service="Servidor Web SSL"},
    @{Port=3306; Name="DevServer MySQL"; Service="Banco MySQL"},
    @{Port=5432; Name="DevServer PostgreSQL"; Service="Banco PostgreSQL"},
    @{Port=8000; Name="DevServer Python"; Service="Python Server"},
    @{Port=8080; Name="DevServer phpMyAdmin"; Service="phpMyAdmin"},
    @{Port=8081; Name="DevServer pgAdmin"; Service="pgAdmin"},
    @{Port=8083; Name="DevServer FileManager"; Service="File Manager"},
    @{Port=21; Name="DevServer FTP"; Service="FTP Server"}
)

Write-Host "Configurando regras do Firewall..." -ForegroundColor Yellow
Write-Host ""

$successCount = 0

foreach ($portConfig in $ports) {
    $port = $portConfig.Port
    $name = $portConfig.Name
    $service = $portConfig.Service
    
    Write-Host "[$($ports.IndexOf($portConfig) + 1)/$($ports.Count)] Liberando porta $port ($service)... " -NoNewline
    
    try {
        New-NetFirewallRule -DisplayName $name -Direction Inbound -Protocol TCP -LocalPort $port -Action Allow -ErrorAction Stop | Out-Null
        Write-Host "OK" -ForegroundColor Green
        $successCount++
    }
    catch {
        if ($_.Exception.Message -match "already exists") {
            Write-Host "JÁ EXISTE" -ForegroundColor Yellow
            $successCount++
        } else {
            Write-Host "ERRO" -ForegroundColor Red
        }
    }
}

# Configurar portas FTP passivas
Write-Host "[$($ports.Count + 1)/$($ports.Count + 1)] Liberando portas FTP passivas (21000-21010)... " -NoNewline
try {
    New-NetFirewallRule -DisplayName "DevServer FTP Passive" -Direction Inbound -Protocol TCP -LocalPort "21000-21010" -Action Allow -ErrorAction Stop | Out-Null
    Write-Host "OK" -ForegroundColor Green
    $successCount++
}
catch {
    if ($_.Exception.Message -match "already exists") {
        Write-Host "JÁ EXISTE" -ForegroundColor Yellow
        $successCount++
    } else {
        Write-Host "ERRO" -ForegroundColor Red
    }
}

Write-Host ""
Write-Host "================================================================" -ForegroundColor Cyan
Write-Host "                    CONFIGURAÇÃO CONCLUÍDA!                    " -ForegroundColor Green
Write-Host "================================================================" -ForegroundColor Cyan
Write-Host ""

if ($successCount -eq ($ports.Count + 1)) {
    Write-Host "✅ Todas as portas foram configuradas com sucesso!" -ForegroundColor Green
} else {
    Write-Host "⚠️  Algumas portas já estavam configuradas ou houve erros." -ForegroundColor Yellow
}

Write-Host ""
Write-Host "🌐 URLs DE ACESSO EXTERNO:" -ForegroundColor Yellow
Write-Host ""
Write-Host "  • Painel Principal:    " -NoNewline
Write-Host "http://172.16.1.125/" -ForegroundColor Green
Write-Host "  • HostPanel Pro:       " -NoNewline  
Write-Host "http://172.16.1.125/hosting-panel/" -ForegroundColor Green
Write-Host "  • phpMyAdmin:          " -NoNewline
Write-Host "http://172.16.1.125:8080/" -ForegroundColor Green
Write-Host "  • pgAdmin:             " -NoNewline
Write-Host "http://172.16.1.125:8081/" -ForegroundColor Green
Write-Host "  • File Manager:        " -NoNewline
Write-Host "http://172.16.1.125:8083/" -ForegroundColor Green
Write-Host "  • Python Server:       " -NoNewline
Write-Host "http://172.16.1.125:8000/" -ForegroundColor Green

Write-Host ""
Write-Host "🔗 CONEXÕES DE BANCO EXTERNAS:" -ForegroundColor Yellow
Write-Host ""
Write-Host "  • MySQL:      172.16.1.125:3306 (devuser/devpassword)" -ForegroundColor Cyan
Write-Host "  • PostgreSQL: 172.16.1.125:5432 (devuser/devpassword)" -ForegroundColor Cyan
Write-Host "  • FTP:        172.16.1.125:21 (devuser/devpassword)" -ForegroundColor Cyan

Write-Host ""
Write-Host "🎯 TESTE DE CONECTIVIDADE:" -ForegroundColor Yellow
Write-Host ""
Write-Host "De outro equipamento na rede, execute:" -ForegroundColor White
Write-Host "  Test-NetConnection 172.16.1.125 -Port 80" -ForegroundColor Gray
Write-Host "  Test-NetConnection 172.16.1.125 -Port 8080" -ForegroundColor Gray

Write-Host ""
Write-Host "================================================================" -ForegroundColor Cyan
Write-Host ""

Read-Host "Pressione ENTER para sair"
