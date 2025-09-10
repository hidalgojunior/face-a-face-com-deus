# 🌐 Configuração de Acesso Externo - DevServer Pro

## 📍 **Seu IP atual: 172.16.1.125**

### ✅ **Como acessar de outros equipamentos na rede:**

## 🖥️ **URLs de Acesso Externo**

### 🚀 **Painel Principal**
```
http://172.16.1.125/
```

### 🎯 **HostPanel Pro**  
```
http://172.16.1.125/hosting-panel/
```

### 🗄️ **phpMyAdmin**
```
http://172.16.1.125:8080/
```

### 🐘 **pgAdmin**
```
http://172.16.1.125:8081/
```

### 📁 **File Manager**
```
http://172.16.1.125:8083/
```

### 🐍 **Python Server**
```
http://172.16.1.125:8000/
```

## 🔧 **Configurações Necessárias**

### 1. **Windows Firewall** 
Precisa liberar as portas no firewall do Windows:

```powershell
# Execute como Administrador no PowerShell:

# Porta 80 (HTTP)
New-NetFirewallRule -DisplayName "DevServer HTTP" -Direction Inbound -Protocol TCP -LocalPort 80 -Action Allow

# Porta 443 (HTTPS) 
New-NetFirewallRule -DisplayName "DevServer HTTPS" -Direction Inbound -Protocol TCP -LocalPort 443 -Action Allow

# Porta 3306 (MySQL)
New-NetFirewallRule -DisplayName "DevServer MySQL" -Direction Inbound -Protocol TCP -LocalPort 3306 -Action Allow

# Porta 5432 (PostgreSQL)
New-NetFirewallRule -DisplayName "DevServer PostgreSQL" -Direction Inbound -Protocol TCP -LocalPort 5432 -Action Allow

# Porta 8000 (Python)
New-NetFirewallRule -DisplayName "DevServer Python" -Direction Inbound -Protocol TCP -LocalPort 8000 -Action Allow

# Porta 8080 (phpMyAdmin)
New-NetFirewallRule -DisplayName "DevServer phpMyAdmin" -Direction Inbound -Protocol TCP -LocalPort 8080 -Action Allow

# Porta 8081 (pgAdmin)
New-NetFirewallRule -DisplayName "DevServer pgAdmin" -Direction Inbound -Protocol TCP -LocalPort 8081 -Action Allow

# Porta 8083 (File Manager)
New-NetFirewallRule -DisplayName "DevServer FileManager" -Direction Inbound -Protocol TCP -LocalPort 8083 -Action Allow

# Porta 21 (FTP)
New-NetFirewallRule -DisplayName "DevServer FTP" -Direction Inbound -Protocol TCP -LocalPort 21 -Action Allow

# Portas FTP passivas
New-NetFirewallRule -DisplayName "DevServer FTP Passive" -Direction Inbound -Protocol TCP -LocalPort 21000-21010 -Action Allow
```

### 2. **Docker Configuration**
O Docker já está configurado para aceitar conexões externas nas portas mapeadas.

### 3. **Verificar se funciona**
De outro equipamento na rede 172.16.x.x, acesse:
```
http://172.16.1.125/
```

## 🔒 **Conexões de Banco Externas**

### **MySQL** (de outros equipamentos)
```
Host: 172.16.1.125
Porta: 3306  
Usuário: devuser
Senha: devpassword
Database: devdb
```

### **PostgreSQL** (de outros equipamentos)  
```
Host: 172.16.1.125
Porta: 5432
Usuário: devuser 
Senha: devpassword
Database: devdb
```

### **FTP** (de outros equipamentos)
```
Host: 172.16.1.125
Porta: 21
Usuário: devuser
Senha: devpassword
```

## 🌐 **Teste de Conectividade**

De outro equipamento, teste se as portas estão acessíveis:

```bash
# Teste de ping
ping 172.16.1.125

# Teste de portas específicas (Linux/Mac)
telnet 172.16.1.125 80
telnet 172.16.1.125 8080  
telnet 172.16.1.125 3306

# Windows (PowerShell)
Test-NetConnection 172.16.1.125 -Port 80
Test-NetConnection 172.16.1.125 -Port 8080
Test-NetConnection 172.16.1.125 -Port 3306
```

## ⚠️ **Observações Importantes**

1. **Firewall**: O Windows Defender Firewall pode bloquear as conexões por padrão
2. **Antivírus**: Alguns antivírus podem bloquear conexões de rede
3. **Router**: Se houver firewall no roteador, pode precisar configurar
4. **IP Dinâmico**: Se o IP 172.16.1.125 mudar, os acessos externos precisarão ser atualizados

## 🎯 **Script Automático de Configuração**

Execute este comando como **Administrador** para liberar todas as portas automaticamente:

```cmd
@echo off
echo Configurando Firewall para DevServer Pro...

netsh advfirewall firewall add rule name="DevServer HTTP" dir=in action=allow protocol=TCP localport=80
netsh advfirewall firewall add rule name="DevServer HTTPS" dir=in action=allow protocol=TCP localport=443  
netsh advfirewall firewall add rule name="DevServer MySQL" dir=in action=allow protocol=TCP localport=3306
netsh advfirewall firewall add rule name="DevServer PostgreSQL" dir=in action=allow protocol=TCP localport=5432
netsh advfirewall firewall add rule name="DevServer Python" dir=in action=allow protocol=TCP localport=8000
netsh advfirewall firewall add rule name="DevServer phpMyAdmin" dir=in action=allow protocol=TCP localport=8080
netsh advfirewall firewall add rule name="DevServer pgAdmin" dir=in action=allow protocol=TCP localport=8081
netsh advfirewall firewall add rule name="DevServer FileManager" dir=in action=allow protocol=TCP localport=8083
netsh advfirewall firewall add rule name="DevServer FTP" dir=in action=allow protocol=TCP localport=21
netsh advfirewall firewall add rule name="DevServer FTP Passive" dir=in action=allow protocol=TCP localport=21000-21010

echo Firewall configurado com sucesso!
echo.  
echo Acesse de outros equipamentos:
echo http://172.16.1.125/
echo http://172.16.1.125/hosting-panel/
echo.
pause
```

## ✅ **Status da Configuração**

- ✅ **Docker**: Pronto para conexões externas
- ⚠️ **Firewall**: Precisa ser configurado (execute os comandos acima)
- ✅ **Rede**: IP 172.16.1.125 acessível na rede local
- ✅ **Portas**: Todas mapeadas corretamente no Docker

**Após configurar o firewall, outros equipamentos na rede 172.16.x.x poderão acessar todos os serviços!** 🚀
