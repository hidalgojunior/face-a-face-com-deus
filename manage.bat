@echo off
echo ========================================
echo      SERVIDOR DE DESENVOLVIMENTO
echo ========================================
echo.

REM Verificar se Docker está rodando
docker version >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ Docker nao esta rodando!
    echo Por favor, inicie o Docker Desktop primeiro.
    pause
    exit
)

echo ✅ Docker detectado!
echo.

REM Verificar status dos containers
echo 📊 Status dos servicos:
docker-compose ps

echo.
echo 🌐 URLs de Acesso:
echo ==================
echo Site Principal:     https://localhost
echo phpMyAdmin:         http://localhost:8080
echo pgAdmin:           http://localhost:8081
echo File Manager:      http://localhost:8083
echo.

REM Menu de opções
:MENU
echo.
echo 🛠️  O que deseja fazer?
echo ==================
echo [1] Iniciar todos os servicos
echo [2] Parar todos os servicos  
echo [3] Reiniciar todos os servicos
echo [4] Ver logs em tempo real
echo [5] Acessar container PHP
echo [6] Acessar container Python
echo [7] Acessar container Flutter
echo [8] Backup MySQL
echo [9] Ver status detalhado
echo [0] Sair
echo.

set /p choice=Digite sua opcao: 

if "%choice%"=="1" goto START
if "%choice%"=="2" goto STOP
if "%choice%"=="3" goto RESTART
if "%choice%"=="4" goto LOGS
if "%choice%"=="5" goto PHP_SHELL
if "%choice%"=="6" goto PYTHON_SHELL
if "%choice%"=="7" goto FLUTTER_SHELL
if "%choice%"=="8" goto BACKUP
if "%choice%"=="9" goto STATUS
if "%choice%"=="0" exit
goto MENU

:START
echo 🚀 Iniciando servicos...
docker-compose up -d
goto MENU

:STOP
echo 🛑 Parando servicos...
docker-compose down
goto MENU

:RESTART
echo 🔄 Reiniciando servicos...
docker-compose restart
goto MENU

:LOGS
echo 📝 Logs em tempo real (Ctrl+C para sair):
docker-compose logs -f
goto MENU

:PHP_SHELL
echo 🐘 Acessando container PHP...
docker-compose exec php bash
goto MENU

:PYTHON_SHELL
echo 🐍 Acessando container Python...
docker-compose exec python bash
goto MENU

:FLUTTER_SHELL
echo 📱 Acessando container Flutter...
docker-compose exec flutter bash
goto MENU

:BACKUP
echo 💾 Criando backup do MySQL...
docker-compose exec mysql mysqldump -u root -prootpassword --all-databases > backup_%date:~-4,4%-%date:~-10,2%-%date:~-7,2%.sql
echo Backup criado: backup_%date:~-4,4%-%date:~-10,2%-%date:~-7,2%.sql
goto MENU

:STATUS
echo 📊 Status detalhado:
echo.
docker-compose ps
echo.
docker stats --no-stream
goto MENU
