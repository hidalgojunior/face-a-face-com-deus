@echo off
echo Adicionando entradas ao arquivo hosts...

REM Verificar se está executando como administrador
net session >nul 2>&1
if %errorlevel% neq 0 (
    echo Este script precisa ser executado como Administrador!
    echo Clique com botao direito e selecione "Executar como administrador"
    pause
    exit
)

REM Backup do arquivo hosts
copy C:\Windows\System32\drivers\etc\hosts C:\Windows\System32\drivers\etc\hosts.backup

REM Adicionar entradas
echo. >> C:\Windows\System32\drivers\etc\hosts
echo # Dev Server Entries >> C:\Windows\System32\drivers\etc\hosts
echo 127.0.0.1 dev.local >> C:\Windows\System32\drivers\etc\hosts
echo 127.0.0.1 python.dev.local >> C:\Windows\System32\drivers\etc\hosts
echo 127.0.0.1 flutter.dev.local >> C:\Windows\System32\drivers\etc\hosts

echo Entradas adicionadas com sucesso!
echo Agora voce pode acessar:
echo - https://dev.local
echo - https://python.dev.local  
echo - https://flutter.dev.local
pause
