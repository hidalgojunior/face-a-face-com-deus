# 🐍 GUIA COMPLETO - COMO ACESSAR PYTHON

## 1. 🟢 Verificar se Python está rodando

```powershell
# Verificar status do container
docker ps | findstr python

# Ver logs do Python
docker logs dev_python
```

## 2. 🚀 Entrar no ambiente Python

```powershell
# Entrar no container Python
docker-compose exec python bash

# Ou diretamente:
docker exec -it dev_python bash
```

## 3. 🌐 Criar servidor web Python

### Opção A - Flask Simples:
```bash
# Dentro do container Python
cd /app
cat << 'EOF' > app.py
from flask import Flask
app = Flask(__name__)

@app.route('/')
def hello():
    return '''
    <h1>🐍 Python Server Funcionando!</h1>
    <p>Servidor Flask rodando com sucesso</p>
    <p><a href="/test">Teste aqui</a></p>
    '''

@app.route('/test')
def test():
    return '<h2>✅ Teste OK!</h2>'

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=8000, debug=True)
EOF

python app.py
```

### Opção B - Django Rápido:
```bash
# Dentro do container
cd /app/projects
django-admin startproject mysite
cd mysite
python manage.py runserver 0.0.0.0:8000
```

### Opção C - FastAPI:
```bash
# Dentro do container
cd /app
cat << 'EOF' > fastapi_app.py
from fastapi import FastAPI
app = FastAPI()

@app.get("/")
def read_root():
    return {"message": "FastAPI funcionando!", "status": "ok"}

@app.get("/test")
def test():
    return {"test": "successful"}
EOF

uvicorn fastapi_app:app --host 0.0.0.0 --port 8000
```

## 4. 🌐 URLs de Acesso

Após executar qualquer servidor acima:
- **Local**: http://localhost:8000
- **Rede**: http://172.16.1.125:8000

## 5. 📊 Jupyter Notebook

```bash
# Dentro do container Python
cd /app
jupyter notebook --ip=0.0.0.0 --port=8888 --no-browser --allow-root --NotebookApp.token=''
```

Acesso: http://localhost:8888

## 6. 🛠️ Comandos Úteis

```powershell
# Parar servidor (Ctrl+C)
# Sair do container (exit)

# Reiniciar container Python
docker-compose restart python

# Ver portas ativas
netstat -an | findstr :8000
```

## ⚡ TESTE RÁPIDO

Execute este comando para testar:

```powershell
docker-compose exec python python -c "
from flask import Flask
import threading
import time

app = Flask(__name__)

@app.route('/')
def hello():
    return '<h1>Python OK!</h1>'

def run_server():
    app.run(host='0.0.0.0', port=8000)

thread = threading.Thread(target=run_server)
thread.daemon = True
thread.start()
print('Servidor iniciado em http://localhost:8000')
time.sleep(2)
print('Teste: Acesse http://localhost:8000 no navegador')
"
```
