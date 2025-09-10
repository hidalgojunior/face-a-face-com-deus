# 🌐 SOLUCIONANDO PROBLEMAS DE REDE

## ❌ Problema Relatado
- WiFi fica desabilitado quando Docker roda
- Não consegue acessar Python e Flutter
- Precisa de internet + servidor simultaneamente

## 🔍 Diagnóstico e Soluções

### 1. 🛠️ Verificar Configuração de Rede Docker

```powershell
# Verificar redes Docker
docker network ls

# Verificar configuração da rede do projeto
docker network inspect dev-server_dev-network

# Verificar adaptadores de rede
ipconfig /all
```

### 2. 🔧 Soluções para WiFi

#### Opção A: Reiniciar Adaptador de Rede
```powershell
# Execute como Administrador
netsh wlan disconnect
netsh wlan connect name="NOME_DA_SUA_REDE"
```

#### Opção B: Resetar Configurações Docker
```powershell
# Parar Docker
docker-compose down

# Limpar redes Docker
docker network prune

# Reiniciar Docker Desktop
# Restart-Service docker (como Admin)
```

#### Opção C: Configuração Manual de DNS
```powershell
# Definir DNS manualmente (como Admin)
netsh interface ipv4 set dns "Wi-Fi" static 8.8.8.8
netsh interface ipv4 add dns "Wi-Fi" 8.8.4.4 index=2
```

### 3. 🐍 Acessar Python

```powershell
# Entrar no container Python
docker-compose exec python bash

# Verificar se está rodando
docker logs dev_python

# Testar conexão
curl http://localhost:8000
```

#### Criar servidor Python de teste:
```powershell
# Dentro do container Python
docker-compose exec python bash
cd /app
echo "from flask import Flask; app = Flask(__name__); @app.route('/') def hello(): return '<h1>Python funcionando!</h1>'; app.run(host='0.0.0.0', port=8000)" > test_server.py
python test_server.py
```

### 4. 📱 Acessar Flutter

```powershell
# Entrar no container Flutter (quando terminar o build)
docker-compose exec flutter bash

# Criar projeto teste
cd /app/projects
flutter create test_app
cd test_app

# Executar para web
flutter run -d web-server --web-port=3000 --web-hostname=0.0.0.0
```

### 5. 🌐 Configuração de Rede Corrigida

Vou criar uma configuração que NÃO interfere na sua WiFi:

```yaml
# Nova configuração de rede no docker-compose
networks:
  dev-network:
    driver: bridge
    driver_opts:
      com.docker.network.bridge.name: docker-dev
    ipam:
      config:
        - subnet: 172.20.0.0/16
```

### 6. 🔄 Script de Recuperação Rápida

```powershell
# Script para recuperar rede (save as fix-network.bat)
@echo off
echo Recuperando configuracao de rede...
ipconfig /release
ipconfig /renew
ipconfig /flushdns
netsh winsock reset
echo Rede resetada! Reinicie se necessario.
pause
```

## ⚡ SOLUÇÃO RÁPIDA

Execute estes comandos em ordem:

1. **Testar conexão atual:**
```powershell
ping google.com
```

2. **Se não funcionar, resetar:**
```powershell
docker-compose down
ipconfig /renew
```

3. **Reiniciar serviços:**
```powershell
docker-compose up -d
```

4. **Testar Python:**
```powershell
docker-compose exec python python -c "print('Python OK!')"
```

5. **Acessar URLs:**
- Python: http://localhost:8000
- Flutter: http://localhost:3000 (após criar projeto)
- Site principal: http://localhost

## 🆘 Se Nada Funcionar

1. **Restart Docker Desktop completamente**
2. **Reiniciar Windows**  
3. **Usar configuração de rede alternativa** (vou criar abaixo)

Vou implementar essas soluções agora!
