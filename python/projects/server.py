#!/usr/bin/env python3
import http.server
import socketserver

class Handler(http.server.SimpleHTTPRequestHandler):
    def do_GET(self):
        self.send_response(200)
        self.send_header('Content-type', 'text/html; charset=utf-8')
        self.end_headers()
        
        if self.path == '/':
            html = """
            <html>
            <head>
                <title>Python Server OK!</title>
                <meta charset="utf-8">
            </head>
            <body style="font-family: Arial, sans-serif; text-align: center; padding: 50px; background: #f5f5f5;">
                <h1 style="color: #2e7d32;">🐍 Servidor Python Funcionando!</h1>
                <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 500px; margin: 0 auto;">
                    <p>Container Python rodando com sucesso</p>
                    <p><a href="/test" style="color: #1976d2; text-decoration: none; font-weight: bold;">Página de Teste →</a></p>
                    <p><a href="/info" style="color: #1976d2; text-decoration: none; font-weight: bold;">Info do Sistema →</a></p>
                    <hr style="margin: 20px 0;">
                    <p style="color: #666; font-size: 14px;">Servidor HTTP ativo na porta 8000</p>
                </div>
            </body>
            </html>
            """
        elif self.path == '/test':
            html = """
            <html>
            <head><title>Teste Python</title><meta charset="utf-8"></head>
            <body style="font-family: Arial, sans-serif; text-align: center; padding: 50px; background: #f5f5f5;">
                <h2 style="color: #2e7d32;">✅ Teste Python OK!</h2>
                <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 500px; margin: 0 auto;">
                    <p>Tudo funcionando perfeitamente!</p>
                    <p><a href="/" style="color: #1976d2; text-decoration: none; font-weight: bold;">← Voltar para Home</a></p>
                </div>
            </body>
            </html>
            """
        elif self.path == '/info':
            import sys, os
            html = f"""
            <html>
            <head><title>Info do Sistema</title><meta charset="utf-8"></head>
            <body style="font-family: Arial, sans-serif; text-align: center; padding: 50px; background: #f5f5f5;">
                <h2 style="color: #2e7d32;">📊 Informações do Sistema</h2>
                <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 600px; margin: 0 auto; text-align: left;">
                    <p><strong>Python:</strong> {sys.version}</p>
                    <p><strong>Plataforma:</strong> {sys.platform}</p>
                    <p><strong>Diretório:</strong> {os.getcwd()}</p>
                    <p style="text-align: center; margin-top: 20px;">
                        <a href="/" style="color: #1976d2; text-decoration: none; font-weight: bold;">← Voltar para Home</a>
                    </p>
                </div>
            </body>
            </html>
            """
        else:
            html = """
            <html>
            <head><title>404 - Não Encontrado</title><meta charset="utf-8"></head>
            <body style="font-family: Arial, sans-serif; text-align: center; padding: 50px; background: #f5f5f5;">
                <h1 style="color: #d32f2f;">❌ Página não encontrada</h1>
                <p><a href="/" style="color: #1976d2; text-decoration: none; font-weight: bold;">← Voltar para Home</a></p>
            </body>
            </html>
            """
            
        self.wfile.write(html.encode('utf-8'))

if __name__ == "__main__":
    PORT = 8000
    print(f"🚀 Iniciando servidor Python na porta {PORT}...")
    
    try:
        with socketserver.TCPServer(("", PORT), Handler) as httpd:
            print(f"✅ Servidor ativo em http://localhost:{PORT}")
            print("📝 Endpoints disponíveis:")
            print("   • /      - Página inicial")  
            print("   • /test  - Página de teste")
            print("   • /info  - Informações do sistema")
            print("🔄 Pressione Ctrl+C para parar")
            httpd.serve_forever()
    except KeyboardInterrupt:
        print("\n⏹️  Servidor parado pelo usuário")
    except Exception as e:
        print(f"❌ Erro: {e}")
