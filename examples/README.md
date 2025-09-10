# 📁 Exemplos de Código

Este diretório contém exemplos de código para diferentes linguagens e frameworks disponíveis no ambiente.

## 🐍 Python Examples

### Flask App
```python
from flask import Flask

app = Flask(__name__)

@app.route('/')
def hello():
    return '<h1>Hello from Flask!</h1>'

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=8000, debug=True)
```

### Django Quick Start
```bash
# Dentro do container Python
docker-compose exec python bash
cd projects
django-admin startproject mysite
cd mysite
python manage.py runserver 0.0.0.0:8000
```

## 🐘 PHP Examples

### Conexão MySQL
```php
<?php
$pdo = new PDO('mysql:host=mysql;dbname=devdb', 'devuser', 'devpassword');
$stmt = $pdo->query('SELECT NOW() as now');
$result = $stmt->fetch();
echo $result['now'];
?>
```

### Conexão PostgreSQL
```php
<?php
$pdo = new PDO('pgsql:host=postgres;dbname=devdb', 'devuser', 'devpassword');
$stmt = $pdo->query('SELECT NOW() as now');
$result = $stmt->fetch();
echo $result['now'];
?>
```

## 📱 Flutter Examples

### Criar e Executar App
```bash
# Dentro do container Flutter
docker-compose exec flutter bash
cd projects
flutter create my_app
cd my_app
flutter run -d web-server --web-port=3000 --web-hostname=0.0.0.0
```

## 🗄️ SQL Examples

### MySQL
```sql
-- Conectar via phpMyAdmin ou terminal
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100)
);

INSERT INTO users (name, email) VALUES 
('João Silva', 'joao@email.com'),
('Maria Santos', 'maria@email.com');
```

### PostgreSQL
```sql
-- Conectar via pgAdmin ou terminal
CREATE TABLE products (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100),
    price DECIMAL(10,2)
);

INSERT INTO products (name, price) VALUES 
('Produto A', 29.99),
('Produto B', 49.99);
```

## 🔧 Comandos Úteis

### Docker
```bash
# Ver logs
docker-compose logs [service_name]

# Entrar no container
docker-compose exec [service_name] bash

# Reiniciar serviço
docker-compose restart [service_name]
```

### Backup/Restore
```bash
# Backup MySQL
docker-compose exec mysql mysqldump -u root -prootpassword devdb > backup.sql

# Restore MySQL  
docker-compose exec -T mysql mysql -u root -prootpassword devdb < backup.sql

# Backup PostgreSQL
docker-compose exec postgres pg_dump -U devuser devdb > backup.sql

# Restore PostgreSQL
docker-compose exec -T postgres psql -U devuser devdb < backup.sql
```
