from flask import Flask
app = Flask(__name__)

@app.route('/')
def home():
    return '''
    <h1>🐍 Python Server Funcionando!</h1>
    <p>Servidor Flask rodando com sucesso no Docker</p>
    <p><a href=\
