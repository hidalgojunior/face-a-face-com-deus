@echo off
title DevServer Pro - Configuracao de Firewall
color 0A

echo.
echo ================================================================
echo            DevServer Pro - Configuracao de Firewall
echo ================================================================
echo.
echo Este script vai configurar o Windows Firewall para permitir
echo acesso externo ao DevServer Pro na rede local.
echo.
echo IP da maquina: 172.16.1.125
echo.
echo ================================================================
echo.

:: Verificar se está executando como administrador
net session >nul 2>&1
if %errorlevel% neq 0 (
    echo ERRO: Execute este script como Administrador!
    echo.
    echo Clique com botao direito e selecione "Executar como administrador"
    echo.
    pause
    exit /b 1
)

echo [INFO] Executando como Administrador... OK
echo.
echo [INFO] Configurando regras do Firewall...
echo.

:: Configurar regras do firewall
echo [1/10] Liberando porta 80 (HTTP)...
netsh advfirewall firewall add rule name="DevServer HTTP" dir=in action=allow protocol=TCP localport=80 >nul 2>&1
if %errorlevel% equ 0 (echo        OK) else (echo        ERRO)

echo [2/10] Liberando porta 443 (HTTPS)...
netsh advfirewall firewall add rule name="DevServer HTTPS" dir=in action=allow protocol=TCP localport=443 >nul 2>&1
if %errorlevel% equ 0 (echo        OK) else (echo        ERRO)

echo [3/10] Liberando porta 3306 (MySQL)...
netsh advfirewall firewall add rule name="DevServer MySQL" dir=in action=allow protocol=TCP localport=3306 >nul 2>&1
if %errorlevel% equ 0 (echo        OK) else (echo        ERRO)

echo [4/10] Liberando porta 5432 (PostgreSQL)...
netsh advfirewall firewall add rule name="DevServer PostgreSQL" dir=in action=allow protocol=TCP localport=5432 >nul 2>&1
if %errorlevel% equ 0 (echo        OK) else (echo        ERRO)

echo [5/10] Liberando porta 8000 (Python)...
netsh advfirewall firewall add rule name="DevServer Python" dir=in action=allow protocol=TCP localport=8000 >nul 2>&1
if %errorlevel% equ 0 (echo        OK) else (echo        ERRO)

echo [6/10] Liberando porta 8080 (phpMyAdmin)...
netsh advfirewall firewall add rule name="DevServer phpMyAdmin" dir=in action=allow protocol=TCP localport=8080 >nul 2>&1
if %errorlevel% equ 0 (echo        OK) else (echo        ERRO)

echo [7/10] Liberando porta 8081 (pgAdmin)...
netsh advfirewall firewall add rule name="DevServer pgAdmin" dir=in action=allow protocol=TCP localport=8081 >nul 2>&1
if %errorlevel% equ 0 (echo        OK) else (echo        ERRO)

echo [8/10] Liberando porta 8083 (File Manager)...
netsh advfirewall firewall add rule name="DevServer FileManager" dir=in action=allow protocol=TCP localport=8083 >nul 2>&1
if %errorlevel% equ 0 (echo        OK) else (echo        ERRO)

echo [9/10] Liberando porta 21 (FTP)...
netsh advfirewall firewall add rule name="DevServer FTP" dir=in action=allow protocol=TCP localport=21 >nul 2>&1
if %errorlevel% equ 0 (echo        OK) else (echo        ERRO)

echo [10/10] Liberando portas 21000-21010 (FTP Passivo)...
netsh advfirewall firewall add rule name="DevServer FTP Passive" dir=in action=allow protocol=TCP localport=21000-21010 >nul 2>&1
if %errorlevel% equ 0 (echo        OK) else (echo        ERRO)

echo.
echo ================================================================
echo                    CONFIGURACAO CONCLUIDA!
echo ================================================================
echo.
echo O DevServer Pro agora pode ser acessado de outros equipamentos
echo na rede local atraves do IP: 172.16.1.125
echo.
echo URLs DE ACESSO EXTERNO:
echo.
echo  Painel Principal:    http://172.16.1.125/
echo  HostPanel Pro:       http://172.16.1.125/hosting-panel/
echo  phpMyAdmin:          http://172.16.1.125:8080/
echo  pgAdmin:             http://172.16.1.125:8081/  
echo  File Manager:        http://172.16.1.125:8083/
echo  Python Server:       http://172.16.1.125:8000/
echo.
echo CONEXOES DE BANCO EXTERNAS:
echo.
echo  MySQL:     172.16.1.125:3306 (devuser/devpassword)
echo  PostgreSQL: 172.16.1.125:5432 (devuser/devpassword)
echo  FTP:       172.16.1.125:21 (devuser/devpassword)
echo.
echo ================================================================
echo.
pause
