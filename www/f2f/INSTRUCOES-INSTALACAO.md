# 🎯 Instalação do Banco de Dados - Face a Face

## ✅ Instalador Criado com Sucesso!

### 📂 Arquivos Criados:

1. **`install-database.php`** - Instalador web interativo
2. **`database-install.sql`** - Script SQL completo
3. **`test-database.php`** - Teste de verificação

### 🌐 **Opção 1: Instalador Web (Recomendado)**

**Acesse**: http://localhost/f2f/install-database.php

1. Abra o link no navegador
2. Clique em **"Iniciar Instalação"**
3. Aguarde o processo (será automático)
4. Ao final, clique em **"Acessar Dashboard"**

### 💾 **Opção 2: Instalação Manual via SQL**

Se preferir instalar manualmente:

```bash
# Conectar ao MySQL
docker exec -it dev_mysql mysql -u root -prootpassword

# Executar o script
source /var/www/html/f2f/database-install.sql
```

### 🔍 **Verificar Instalação**

Após instalar, teste em: http://localhost/f2f/test-database.php

### 📊 **O que será instalado:**

#### 🏗️ **13 Tabelas Principais:**
- ✅ `eventos` - Eventos Face a Face
- ✅ `encontreiros` - Equipe organizadora  
- ✅ `encontristas` - Participantes
- ✅ `inscricoes_encontristas` - Controle de inscrições
- ✅ `participacao_encontreiros` - Vinculação às equipes
- ✅ `valores_evento` - Valores e preços
- ✅ `pagamentos_encontristas` - Pagamentos participantes
- ✅ `pagamentos_encontreiros` - Pagamentos equipe
- ✅ `sub_eventos` - Programação detalhada
- ✅ `ministracoes` - Ministrações e temas
- ✅ `ministracao_intercessores` - Cobertura espiritual
- ✅ `log_mensagens_n8n` - Mensagens automáticas
- ✅ `config_n8n` - Configurações automação

#### 🎯 **Dados Iniciais Inclusos:**
- **Evento de exemplo**: "Face a Face de Homens 2025"
- **Valores**: R$ 180 (encontristas) / R$ 120 (encontreiros)
- **3 Encontreiros** e **3 Encontristas** de exemplo
- **7 Sub-eventos** com programação
- **Configurações n8n** pré-definidas
- **Pagamentos de teste** para demonstração

#### 🚀 **Recursos Avançados:**
- ✅ **Índices otimizados** para performance
- ✅ **Views úteis** para relatórios
- ✅ **Procedures** para cálculos
- ✅ **Foreign Keys** para integridade
- ✅ **Comentários** nas tabelas

### 🎉 **Após a Instalação:**

1. **Dashboard**: http://localhost/f2f/dashboard-pagamentos.html
2. **n8n**: http://localhost:5678 (admin/admin123)
3. **phpMyAdmin**: http://localhost:8080 (root/rootpassword)

### ⚙️ **Credenciais do Sistema:**

- **MySQL**: root / rootpassword
- **Banco**: face_a_face
- **n8n**: admin / admin123

---

## 🚨 **Status Atual:**

- ✅ **Instalador criado**
- ✅ **Scripts testados**  
- ✅ **Credenciais corretas**
- ⏳ **Aguardando execução**

**Execute o instalador para começar a usar o sistema!** 🎯
