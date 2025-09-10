@echo off
echo ========================================
echo     CORRECAO DE REDE RAPIDA
echo ========================================
echo.

echo Verificando conexao com internet...
ping -n 2 google.com > nul
if %errorlevel% neq 0 (
    echo ❌ Internet nao acessivel. Corrigindo...
    echo.
    echo 1. Renovando IP...
    ipconfig /release > nul 2>&1
    ipconfig /renew > nul 2>&1
    
    echo 2. Limpando DNS...
    ipconfig /flushdns > nul 2>&1
    
    echo 3. Resetando Winsock...
    netsh winsock reset > nul 2>&1
    
    echo 4. Testando novamente...
    ping -n 2 google.com > nul
    if %errorlevel% neq 0 (
        echo ⚠️  Ainda sem internet. Pode ser necessario reiniciar.
    ) else (
        echo ✅ Internet restaurada!
    )
) else (
    echo ✅ Internet funcionando normalmente!
)

echo.
echo Verificando Docker...
docker --version > nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ Docker nao encontrado ou nao rodando
) else (
    echo ✅ Docker funcionando
)

echo.
echo Verificando servicos...
cd /d "%~dp0"
docker-compose ps

echo.
echo ========================================
echo URLs de Teste:
echo Site Principal: http://localhost
echo Python Apps:    http://localhost:8000
echo Flutter Apps:   http://localhost:3000
echo ========================================
echo.

pause
