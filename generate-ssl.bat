@echo off
REM Script para gerar certificados SSL auto-assinados no Windows

echo Gerando certificados SSL...

REM Criar diretório SSL se não existir
if not exist "nginx\ssl" mkdir nginx\ssl

REM Gerar certificados SSL usando OpenSSL
docker run --rm -v "%CD%\nginx\ssl:/ssl" alpine/openssl req -newkey rsa:2048 -nodes -keyout /ssl/server.key -x509 -days 365 -out /ssl/server.crt -subj "/C=BR/ST=SP/L=Sao Paulo/O=Dev Server/OU=IT Department/CN=localhost"

echo Certificados SSL gerados com sucesso!
echo Certificado: nginx\ssl\server.crt
echo Chave privada: nginx\ssl\server.key
pause
