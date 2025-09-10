# 🌐 Informações de Rede e Acesso

## 📡 Sobre sua Conexão com a Internet

**Não se preocupe!** O Docker não interfere em sua conexão com a internet. Ele apenas:

- Cria uma rede interna isolada chamada `dev-network` para os containers se comunicarem
- Sua rede principal (Wi-Fi/Ethernet) permanece intacta
- Você continuará acessando a internet normalmente
- Os containers também têm acesso à internet através da sua conexão

## 🔗 URLs de Acesso

### Interfaces Web
- **Site Principal**: http://localhost ou https://localhost
- **phpMyAdmin**: http://localhost:8080
- **pgAdmin**: http://localhost:8081
- **File Manager**: http://localhost:8083

### Aplicações
- **Python Apps**: http://localhost:8000 ou http://localhost:5000
- **Flutter Web**: http://localhost:3000
- **Jupyter Notebook**: http://localhost:8888

## 📁 Acesso FTP

### Informações de Conexão FTP
```
Protocolo: FTP
Servidor: localhost (ou seu IP local)
Porta: 21
Usuário: devuser
Senha: devpassword
Modo: Passivo (recomendado)
```

### Seu IP Local
Para descobrir seu IP local na rede, execute no PowerShell:
```powershell
ipconfig | findstr "IPv4"
```

### Portas Passivas FTP
O servidor FTP usa as portas 21000-21010 para transferências passivas.

### Clientes FTP Recomendados
- **FileZilla** (gratuito) - https://filezilla-project.org/
- **WinSCP** (Windows) - https://winscp.net/
- **Interface Web** - http://localhost:8083 (mais fácil!)

## 🖥️ Acessando de Outros Dispositivos na Rede

Para acessar de outros computadores/celulares na mesma rede Wi-Fi:

1. **Descubra seu IP local**:
   ```powershell
   ipconfig | findstr "IPv4"
   ```
   
2. **Use o IP ao invés de localhost**:
   - Site: http://SEU_IP
   - phpMyAdmin: http://SEU_IP:8080
   - FTP: SEU_IP:21

### Exemplo
Se seu IP for `192.168.1.100`:
- Site: http://192.168.1.100
- phpMyAdmin: http://192.168.1.100:8080
- FTP: 192.168.1.100:21

## 🔒 Firewall do Windows

Se não conseguir acessar de outros dispositivos, pode ser necessário liberar as portas no Firewall:

1. Abra "Windows Defender Firewall"
2. Clique em "Configurações Avançadas"
3. Crie regras de entrada para as portas:
   - 80, 443 (Web)
   - 8080, 8081, 8083 (Interfaces)
   - 21, 21000-21010 (FTP)

## ⚠️ Segurança

- **Só para desenvolvimento**: Este ambiente é para desenvolvimento local
- **Não expor para internet**: Mantenha apenas na rede local
- **Senhas padrão**: Altere as senhas em ambiente de produção

## 📊 Monitoramento

Para ver status da rede Docker:
```powershell
docker network ls
docker network inspect dev-server_dev-network
```
