#!/bin/bash

# Script para gerar certificados SSL auto-assinados

# Criar diretório SSL se não existir
mkdir -p ./nginx/ssl

# Gerar chave privada
openssl genrsa -out ./nginx/ssl/server.key 2048

# Gerar certificado auto-assinado
openssl req -new -x509 -key ./nginx/ssl/server.key -out ./nginx/ssl/server.crt -days 365 -subj "/C=BR/ST=SP/L=São Paulo/O=Dev Server/OU=IT Department/CN=localhost"

# Definir permissões
chmod 600 ./nginx/ssl/server.key
chmod 644 ./nginx/ssl/server.crt

echo "Certificados SSL gerados com sucesso!"
echo "Certificado: ./nginx/ssl/server.crt"
echo "Chave privada: ./nginx/ssl/server.key"
