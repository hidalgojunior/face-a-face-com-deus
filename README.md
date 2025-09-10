# 🚀 Servidor de Desenvolvimento Completo

Este é um ambiente de desenvolvimento completo usando Docker Compose com todos os serviços necessários para desenvolvimento web moderno.

## 📋 Serviços Incluídos

- **🌐 Nginx** - Servidor web com SSL/TLS (HTTP e HTTPS)
- **🐘 PHP 8.2** - PHP-FPM com extensões completas
- **🗄️ MySQL 8.0** - Banco de dados relacional
- **🔧 phpMyAdmin** - Interface web para MySQL
- **🐘 PostgreSQL 15** - Banco de dados avançado
- **🔧 pgAdmin** - Interface web para PostgreSQL
- **🐍 Python 3.11** - Ambiente Python com frameworks
- ** FTP Server** - Servidor FTP para transferência de arquivos
- **🗂️ File Manager** - Interface web para gerenciamento de arquivos

## 🚀 Como Usar

### 1. Pré-requisitos
- Docker Desktop instalado
- Docker Compose disponível

### 2. Configuração Inicial

```powershell
# Clone ou baixe este projeto
cd d:\dev-server

# Gerar certificados SSL
.\generate-ssl.bat

# Iniciar todos os serviços
docker-compose up -d
```

### 3. Verificar Status
```powershell
docker-compose ps
```

## 🔗 Acessos e URLs

### Interfaces Web
- **Site Principal**: https://localhost (HTTP redireciona para HTTPS)
- **phpMyAdmin**: http://localhost:8080
- **pgAdmin**: http://localhost:8081
- **File Manager**: http://localhost:8083
- **Python Server**: http://localhost:8000

### Bancos de Dados

#### MySQL
- **Host**: localhost:3306
- **Database**: devdb
- **Usuário**: devuser
- **Senha**: devpassword
- **Root**: rootpassword

#### PostgreSQL
- **Host**: localhost:5432
- **Database**: devdb
- **Usuário**: devuser
- **Senha**: devpassword

### FTP
- **Host**: localhost:21
- **Usuário**: devuser
- **Senha**: devpassword

### Interfaces de Administração

#### phpMyAdmin
- **URL**: http://localhost:8080
- **Usuário**: root
- **Senha**: rootpassword

#### pgAdmin
- **URL**: http://localhost:8081
- **Email**: admin@dev.local
- **Senha**: adminpassword

#### File Manager
- **URL**: http://localhost:8083
- **Usuário**: admin
- **Senha**: admin

## 📁 Estrutura de Diretórios

```
dev-server/
├── docker-compose.yml          # Configuração principal
├── nginx/                      # Configuração Nginx
│   ├── nginx.conf             # Configuração principal
│   ├── sites/                 # Sites virtuais
│   └── ssl/                   # Certificados SSL
├── php/                       # Configuração PHP
│   ├── Dockerfile            # Build PHP customizado
│   └── php.ini               # Configuração PHP
├── python/                    # Ambiente Python
│   ├── Dockerfile            # Build Python
│   └── projects/             # Seus projetos Python
├── flutter/                   # Ambiente Flutter
│   ├── Dockerfile            # Build Flutter
│   └── projects/             # Seus projetos Flutter
├── www/                       # Arquivos web (DocumentRoot)
├── mysql/init/               # Scripts iniciais MySQL
├── postgres/init/            # Scripts iniciais PostgreSQL
├── ftp/                      # Diretório FTP
└── logs/                     # Logs do sistema
```

## 🛠️ Desenvolvimento

### PHP
Coloque seus arquivos PHP na pasta `www/`. Eles estarão disponíveis em:
- https://localhost/seu-arquivo.php

### Python
```powershell
# Entrar no container Python
docker-compose exec python bash

# Criar projeto Django
cd projects
django-admin startproject meusite

# Executar servidor Django
cd meusite
python manage.py runserver 0.0.0.0:8000
```

### Flutter
```powershell
# Entrar no container Flutter
docker-compose exec flutter bash

# Criar projeto Flutter
cd projects
flutter create meuapp

# Executar para web
cd meuapp
flutter run -d web-server --web-port=3000 --web-hostname=0.0.0.0
```

## 🔧 Comandos Úteis

### Gerenciar Serviços
```powershell
# Iniciar todos os serviços
docker-compose up -d

# Parar todos os serviços
docker-compose down

# Reiniciar um serviço específico
docker-compose restart nginx

# Ver logs
docker-compose logs -f nginx

# Reconstruir imagens
docker-compose build --no-cache
```

### Acessar Containers
```powershell
# PHP
docker-compose exec php bash

# Python
docker-compose exec python bash

# Flutter
docker-compose exec flutter bash

# MySQL
docker-compose exec mysql mysql -u root -p
```

### Backup e Restore

#### MySQL
```powershell
# Backup
docker-compose exec mysql mysqldump -u root -prootpassword devdb > backup.sql

# Restore
docker-compose exec -T mysql mysql -u root -prootpassword devdb < backup.sql
```

#### PostgreSQL
```powershell
# Backup
docker-compose exec postgres pg_dump -U devuser devdb > backup.sql

# Restore
docker-compose exec -T postgres psql -U devuser devdb < backup.sql
```

## 🔒 SSL/HTTPS

Os certificados SSL são auto-assinados. Para evitar avisos do navegador:

1. Acesse https://localhost
2. Clique em "Avançado" ou "Advanced"
3. Clique em "Continuar para localhost (não seguro)"

Ou importe o certificado `nginx/ssl/server.crt` como autoridade certificadora confiável.

## 🌐 Hosts Personalizados

Adicione ao arquivo `C:\Windows\System32\drivers\etc\hosts`:

```
127.0.0.1 dev.local
127.0.0.1 python.dev.local
127.0.0.1 flutter.dev.local
```

## 🔍 Troubleshooting

### Portas em Uso
Se alguma porta estiver em uso, altere no `docker-compose.yml`:

```yaml
ports:
  - "8080:80"  # Mude 8080 para outra porta
```

### Problemas de Permissão
```powershell
# Resetar permissões
docker-compose down
docker-compose up -d
```

### Logs Detalhados
```powershell
# Ver todos os logs
docker-compose logs

# Log específico
docker-compose logs nginx
docker-compose logs php
docker-compose logs mysql
```

## 📚 Recursos Adicionais

### Extensões PHP Incluídas
- PDO (MySQL, PostgreSQL)
- GD, mbstring, zip
- OPcache, bcmath
- Composer instalado

### Pacotes Python Incluídos
- Django, Flask, FastAPI
- pandas, numpy, matplotlib
- Jupyter, JupyterLab
- SQLAlchemy, psycopg2
- requests, beautifulsoup4

### Flutter Features
- Web support habilitado
- Desktop support habilitado
- Chrome instalado para debug

## 🤝 Contribuição

Sinta-se livre para modificar e adaptar este ambiente às suas necessidades!

## 📄 Licença

Este projeto é de uso livre para desenvolvimento.
pra