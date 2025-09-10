#!/bin/bash
echo "🚀 Iniciando serviços Python..."

# Matar qualquer processo na porta 8000
pkill -f "python.*8000" 2>/dev/null || true

# Aguardar um pouco
sleep 2

# Iniciar servidor Python em background
cd /app
nohup python server.py > /app/server.log 2>&1 &

echo "✅ Servidor Python iniciado em background"
echo "📋 Log disponível em /app/server.log"

# Manter container ativo
tail -f /dev/null
