# Sistema Face a Face - Gestão de Encontreiros e Encontristas
## Estrutura Completa com Participantes e Organizadores

### 📊 Estrutura do Banco de Dados Atualizada

```sql
-- Eventos principais
CREATE TABLE eventos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(200) NOT NULL,
    tipo ENUM('homens', 'mulheres') DEFAULT 'homens',
    publico_alvo VARCHAR(200), -- Ex: "Homens 18-35 anos", "Casais"
    descricao TEXT,
    data_inicio DATE NOT NULL,
    data_fim DATE NOT NULL,
    local_evento VARCHAR(200),
    vagas_limite INT DEFAULT NULL,
    observacoes TEXT,
    ativo BOOLEAN DEFAULT TRUE
);

-- 👥 ENCONTREIROS (Equipe Organizadora/Servos)
CREATE TABLE encontreiros (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(20),
    email VARCHAR(100),
    data_nascimento DATE,
    endereco TEXT,
    igreja_origem VARCHAR(200),
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    observacoes TEXT,
    ativo BOOLEAN DEFAULT TRUE
);

-- 🎯 ENCONTRISTAS (Participantes do Encontro)
CREATE TABLE encontristas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(20),
    email VARCHAR(100),
    data_nascimento DATE,
    endereco TEXT,
    igreja_origem VARCHAR(200),
    profissao VARCHAR(100),
    estado_civil ENUM('solteiro', 'casado', 'divorciado', 'viuvo', 'outros'),
    como_conheceu VARCHAR(200), -- Como soube do encontro
    observacoes_medicas TEXT,
    contato_emergencia VARCHAR(200),
    telefone_emergencia VARCHAR(20),
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ativo BOOLEAN DEFAULT TRUE
);

-- Inscrições dos encontristas nos eventos
CREATE TABLE inscricoes_encontristas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    evento_id INT,
    encontrista_id INT,
    data_inscricao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status_inscricao ENUM('pendente', 'confirmada', 'cancelada') DEFAULT 'pendente',
    valor_contribuicao DECIMAL(10,2) DEFAULT 0.00,
    data_pagamento DATE DEFAULT NULL,
    forma_pagamento VARCHAR(50),
    observacoes TEXT,
    FOREIGN KEY (evento_id) REFERENCES eventos(id),
    FOREIGN KEY (encontrista_id) REFERENCES encontristas(id)
);

-- Sub-eventos dentro do evento principal
CREATE TABLE sub_eventos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    evento_id INT,
    nome VARCHAR(200) NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fim TIME,
    ordem_sequencia INT NOT NULL,
    obrigatorio_encontristas BOOLEAN DEFAULT TRUE, -- Se todos encontristas devem participar
    observacoes TEXT,
    FOREIGN KEY (evento_id) REFERENCES eventos(id)
);

-- Ministrações (momento específico no cronograma)
CREATE TABLE ministracoes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    sub_evento_id INT,
    ministro_id INT,
    titulo VARCHAR(200),
    tema VARCHAR(500),
    hora_inicio TIME NOT NULL,
    hora_fim TIME,
    duracao_estimada INT, -- em minutos
    observacoes TEXT,
    FOREIGN KEY (sub_evento_id) REFERENCES sub_eventos(id),
    FOREIGN KEY (ministro_id) REFERENCES encontreiros(id) -- Ministro é um encontreiro
);

-- 🏗️ VINCULAÇÃO DOS ENCONTREIROS ÀS EQUIPES

-- Participação dos encontreiros nos eventos (como equipe)
CREATE TABLE participacao_encontreiros (
    id INT PRIMARY KEY AUTO_INCREMENT,
    evento_id INT,
    encontreiro_id INT,
    tipo_equipe ENUM(
        'equipe_externa_1', 'equipe_externa_2', 'equipe_externa_3', 'equipe_externa_4',
        'adoratorio', 'torre', 'som_imagem', 'sauda', 'correio', 'standby', 
        'lider_externo', 'teatro_fogo', 'cozinha', 'coordenacao'
    ) NOT NULL,
    funcao_especifica VARCHAR(100), -- Ex: "Líder", "Vice-Líder", "Membro", "Coordenador"
    is_lider BOOLEAN DEFAULT FALSE,
    is_vice_lider BOOLEAN DEFAULT FALSE,
    observacoes TEXT,
    FOREIGN KEY (evento_id) REFERENCES eventos(id),
    FOREIGN KEY (encontreiro_id) REFERENCES encontreiros(id)
);

-- Intercessores (cobertura espiritual) - são encontreiros
CREATE TABLE ministracao_intercessores (
    id INT PRIMARY KEY AUTO_INCREMENT,
    ministracao_id INT,
    encontreiro_id INT, -- Intercessor é um encontreiro
    principal BOOLEAN DEFAULT FALSE,
    observacoes TEXT,
    FOREIGN KEY (ministracao_id) REFERENCES ministracoes(id),
    FOREIGN KEY (encontreiro_id) REFERENCES encontreiros(id)
);

-- 📊 PRESENÇA E PARTICIPAÇÃO

-- Controle de presença dos encontristas nas ministrações
CREATE TABLE presenca_encontristas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    ministracao_id INT,
    encontrista_id INT,
    presente BOOLEAN DEFAULT FALSE,
    horario_chegada TIME DEFAULT NULL,
    observacoes TEXT,
    FOREIGN KEY (ministracao_id) REFERENCES ministracoes(id),
    FOREIGN KEY (encontrista_id) REFERENCES encontristas(id)
);

-- Controle de presença dos encontreiros (equipe)
CREATE TABLE presenca_encontreiros (
    id INT PRIMARY KEY AUTO_INCREMENT,
    evento_id INT,
    sub_evento_id INT, -- Pode ser específico para um sub-evento
    encontreiro_id INT,
    presente BOOLEAN DEFAULT FALSE,
    horario_chegada TIME DEFAULT NULL,
    observacoes TEXT,
    FOREIGN KEY (evento_id) REFERENCES eventos(id),
    FOREIGN KEY (sub_evento_id) REFERENCES sub_eventos(id),
    FOREIGN KEY (encontreiro_id) REFERENCES encontreiros(id)
);

-- 📋 ACOMPANHAMENTO E RELATÓRIOS

-- Avaliações dos encontristas sobre as ministrações
CREATE TABLE avaliacoes_ministracoes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    ministracao_id INT,
    encontrista_id INT,
    nota INT CHECK (nota BETWEEN 1 AND 5),
    comentario TEXT,
    data_avaliacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ministracao_id) REFERENCES ministracoes(id),
    FOREIGN KEY (encontrista_id) REFERENCES encontristas(id)
);

-- Histórico de participações dos encontristas
CREATE TABLE historico_encontristas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    encontrista_id INT,
    evento_id INT,
    concluiu_evento BOOLEAN DEFAULT FALSE,
    data_conclusao DATE DEFAULT NULL,
    observacoes_finais TEXT,
    FOREIGN KEY (encontrista_id) REFERENCES encontristas(id),
    FOREIGN KEY (evento_id) REFERENCES eventos(id)
);
```

### 🐳 n8n Integrado ao Docker

O n8n foi adicionado ao docker-compose.yml e estará disponível em:
- **URL**: http://localhost:5678
- **Usuário**: admin  
- **Senha**: admin123

### 📊 Dashboard de Pagamentos com Automação

#### 1. Interface de Controle de Pagamentos
```html
<div class="dashboard-pagamentos">
    <div class="row">
        <!-- Cards de Resumo -->
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>💰 Total Arrecadado</h5>
                    <h3 id="total-arrecadado">R$ 0,00</h3>
                    <small>Encontristas + Encontreiros</small>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5>⏳ Pendentes</h5>
                    <h3 id="total-pendente">R$ 0,00</h3>
                    <small><span id="qtd-pendentes">0</span> pessoas</small>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5>🚨 Atrasados</h5>
                    <h3 id="total-atrasado">R$ 0,00</h3>
                    <small><span id="qtd-atrasados">0</span> pessoas</small>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>📱 Mensagens Hoje</h5>
                    <h3 id="mensagens-enviadas">0</h3>
                    <small>WhatsApp + Email + SMS</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Controles de Automação -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>🤖 Automação de Mensagens via n8n</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <button class="btn btn-primary btn-block" onclick="enviarLembretesPagamento()">
                                📨 Enviar Lembretes de Pagamento
                            </button>
                            <small class="text-muted">Para quem está com pagamento pendente</small>
                        </div>
                        
                        <div class="col-md-4">
                            <button class="btn btn-success btn-block" onclick="enviarBoasVindas()">
                                👋 Enviar Boas-vindas
                            </button>
                            <small class="text-muted">Para novos inscritos confirmados</small>
                        </div>
                        
                        <div class="col-md-4">
                            <button class="btn btn-info btn-block" onclick="enviarLembreteEvento()">
                                📅 Lembrete do Evento
                            </button>
                            <small class="text-muted">1 semana antes do encontro</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Pagamentos -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5>💳 Controle de Pagamentos</h5>
                    <div>
                        <button class="btn btn-sm btn-outline-success" onclick="exportarPagamentos()">
                            📊 Exportar Relatório
                        </button>
                        <button class="btn btn-sm btn-outline-primary" onclick="atualizarStatusPagamentos()">
                            🔄 Atualizar Status
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="tabela-pagamentos">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Tipo</th>
                                <th>Valor Devido</th>
                                <th>Valor Pago</th>
                                <th>Status</th>
                                <th>Vencimento</th>
                                <th>Última Mensagem</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="lista-pagamentos">
                            <!-- Dados carregados via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
```

#### 2. JavaScript para Integração com n8n
```javascript
class GestorPagamentosN8N {
    constructor() {
        this.baseUrlN8N = 'http://localhost:5678';
        this.webhooks = {
            pagamento: '/webhook/pagamento-lembrete',
            boasVindas: '/webhook/boas-vindas',
            lembreteEvento: '/webhook/lembrete-evento'
        };
    }

    // Enviar lembretes de pagamento via n8n
    async enviarLembretesPagamento() {
        try {
            const pendentes = await this.buscarPagamentosPendentes();
            
            for (const pagamento of pendentes) {
                await this.enviarMensagemN8N('pagamento', {
                    nome: pagamento.nome,
                    telefone: pagamento.telefone,
                    email: pagamento.email,
                    valor_devido: pagamento.valor_devido,
                    data_vencimento: pagamento.data_vencimento,
                    tipo_pessoa: pagamento.tipo_pessoa // encontrista ou encontreiro
                });
            }
            
            this.showNotification('success', `Lembretes enviados para ${pendentes.length} pessoas!`);
        } catch (error) {
            this.showNotification('error', 'Erro ao enviar lembretes: ' + error.message);
        }
    }

    // Enviar boas-vindas para novos confirmados
    async enviarBoasVindas() {
        try {
            const novosConfirmados = await this.buscarNovosConfirmados();
            
            for (const pessoa of novosConfirmados) {
                await this.enviarMensagemN8N('boasVindas', {
                    nome: pessoa.nome,
                    telefone: pessoa.telefone,
                    email: pessoa.email,
                    evento_nome: pessoa.evento_nome,
                    data_evento: pessoa.data_inicio,
                    tipo_pessoa: pessoa.tipo_pessoa
                });
            }
            
            this.showNotification('success', `Boas-vindas enviadas para ${novosConfirmados.length} pessoas!`);
        } catch (error) {
            this.showNotification('error', 'Erro ao enviar boas-vindas: ' + error.message);
        }
    }

    // Método genérico para enviar mensagens via n8n
    async enviarMensagemN8N(tipoMensagem, dados) {
        const webhookUrl = this.baseUrlN8N + this.webhooks[tipoMensagem];
        
        const payload = {
            ...dados,
            timestamp: new Date().toISOString(),
            sistema: 'face-a-face'
        };

        const response = await fetch(webhookUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        if (!response.ok) {
            throw new Error(`Erro HTTP ${response.status}: ${response.statusText}`);
        }

        // Registrar no log
        await this.registrarLogMensagem({
            destinatario_tipo: dados.tipo_pessoa,
            destinatario_id: dados.id,
            tipo_mensagem: tipoMensagem,
            canal: 'whatsapp', // ou determinar dinamicamente
            numero_telefone: dados.telefone,
            email_destinatario: dados.email,
            status_envio: 'enviado'
        });

        return response.json();
    }

    // Buscar pagamentos pendentes
    async buscarPagamentosPendentes() {
        const response = await fetch('/api/pagamentos-pendentes.php');
        return await response.json();
    }

    // Registrar log da mensagem
    async registrarLogMensagem(dados) {
        await fetch('/api/log-mensagem.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(dados)
        });
    }

    // Mostrar notificação
    showNotification(type, message) {
        // Implementar sistema de notificações (toast, alert, etc.)
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const alertDiv = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        `;
        document.getElementById('notifications').innerHTML = alertDiv;
    }
}

// Inicializar o gestor
const gestorN8N = new GestorPagamentosN8N();
```

### 🔄 Exemplos de Workflows n8n

#### 1. Workflow de Lembrete de Pagamento
```json
{
  "name": "Face a Face - Lembrete Pagamento",
  "nodes": [
    {
      "name": "Webhook",
      "type": "n8n-nodes-base.webhook",
      "position": [250, 300],
      "parameters": {
        "path": "pagamento-lembrete",
        "httpMethod": "POST"
      }
    },
    {
      "name": "WhatsApp",
      "type": "n8n-nodes-base.httpRequest",
      "position": [450, 200],
      "parameters": {
        "url": "https://api.whatsapp.business/{{$json.telefone}}",
        "method": "POST",
        "body": {
          "message": "Olá {{$json.nome}}! Lembramos que o pagamento de R$ {{$json.valor_devido}} do Face a Face vence em {{$json.data_vencimento}}. 🙏"
        }
      }
    },
    {
      "name": "Email",
      "type": "n8n-nodes-base.emailSend",
      "position": [450, 400],
      "parameters": {
        "to": "{{$json.email}}",
        "subject": "Face a Face - Lembrete de Pagamento",
        "text": "Olá {{$json.nome}}, seu pagamento de R$ {{$json.valor_devido}} vence em {{$json.data_vencimento}}."
      }
    }
  ],
  "connections": {
    "Webhook": {
      "main": [
        [{"node": "WhatsApp", "type": "main", "index": 0}],
        [{"node": "Email", "type": "main", "index": 0}]
      ]
    }
  }
}
```

### 💡 Funcionalidades de Automação

1. **Lembretes Automáticos de Pagamento**
   - 7 dias antes do vencimento
   - No dia do vencimento  
   - 3 dias após vencimento (cobrança)

2. **Mensagens de Boas-vindas**
   - Confirmação de inscrição
   - Informações do evento
   - Instruções de pagamento

3. **Lembretes do Evento**
   - 1 semana antes
   - 1 dia antes
   - 2 horas antes (localização, horário)

4. **Notificações para Equipe**
   - Novos inscritos
   - Pagamentos recebidos
   - Alertas de inadimplência

### 🎯 Próximos Passos

1. **Iniciar o n8n**: `docker-compose up -d n8n`
2. **Configurar Workflows**: Acesso via http://localhost:5678
3. **Implementar APIs PHP**: Para comunicação com n8n
4. **Testar Integração**: Envio de mensagens de teste

Quer que eu implemente alguma parte específica ou começamos subindo o n8n?

#### 1. Cadastro de Encontreiros (Equipe)
```html
<form id="form-encontreiro">
    <div class="card">
        <div class="card-header">
            <h5>👷‍♂️ Cadastro de Encontreiro (Equipe)</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <label>Nome Completo</label>
                    <input type="text" class="form-control" name="nome" required>
                </div>
                <div class="col-md-3">
                    <label>Telefone</label>
                    <input type="tel" class="form-control" name="telefone" required>
                </div>
                <div class="col-md-3">
                    <label>Data de Nascimento</label>
                    <input type="date" class="form-control" name="data_nascimento">
                </div>
            </div>
            
            <div class="row mt-3">
                <div class="col-md-6">
                    <label>Email</label>
                    <input type="email" class="form-control" name="email">
                </div>
                <div class="col-md-6">
                    <label>Igreja de Origem</label>
                    <input type="text" class="form-control" name="igreja_origem">
                </div>
            </div>

            <!-- Seção de Participação no Evento -->
            <div class="mt-4">
                <h6>🏗️ Participação no Evento</h6>
                <div class="row">
                    <div class="col-md-4">
                        <label>Equipe</label>
                        <select class="form-control" name="tipo_equipe" required>
                            <option value="">Selecionar Equipe</option>
                            <optgroup label="Equipes Externas">
                                <option value="equipe_externa_1">Equipe Externa 1</option>
                                <option value="equipe_externa_2">Equipe Externa 2</option>
                                <option value="equipe_externa_3">Equipe Externa 3</option>
                                <option value="equipe_externa_4">Equipe Externa 4</option>
                            </optgroup>
                            <optgroup label="Liderança">
                                <option value="adoratorio">Adoratório</option>
                                <option value="torre">Torre</option>
                                <option value="som_imagem">Som e Imagem</option>
                            </optgroup>
                            <optgroup label="Apoio">
                                <option value="sauda">Saúda</option>
                                <option value="correio">Correio</option>
                                <option value="standby">Standby</option>
                            </optgroup>
                            <optgroup label="Especiais">
                                <option value="teatro_fogo">Teatro e Fogo</option>
                                <option value="cozinha">Cozinha</option>
                                <option value="lider_externo">Líder Externo</option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Função</label>
                        <select class="form-control" name="funcao_especifica">
                            <option value="membro">Membro</option>
                            <option value="lider">Líder</option>
                            <option value="vice_lider">Vice-Líder</option>
                            <option value="coordenador">Coordenador</option>
                            <option value="auxiliar">Auxiliar</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
```

#### 2. Cadastro de Encontristas (Participantes)
```html
<form id="form-encontrista">
    <div class="card">
        <div class="card-header">
            <h5>🎯 Cadastro de Encontrista (Participante)</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <label>Nome Completo</label>
                    <input type="text" class="form-control" name="nome" required>
                </div>
                <div class="col-md-4">
                    <label>Telefone</label>
                    <input type="tel" class="form-control" name="telefone" required>
                </div>
                <div class="col-md-4">
                    <label>Email</label>
                    <input type="email" class="form-control" name="email">
                </div>
            </div>
            
            <div class="row mt-3">
                <div class="col-md-3">
                    <label>Data de Nascimento</label>
                    <input type="date" class="form-control" name="data_nascimento">
                </div>
                <div class="col-md-3">
                    <label>Estado Civil</label>
                    <select class="form-control" name="estado_civil">
                        <option value="solteiro">Solteiro</option>
                        <option value="casado">Casado</option>
                        <option value="divorciado">Divorciado</option>
                        <option value="viuvo">Viúvo</option>
                        <option value="outros">Outros</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Profissão</label>
                    <input type="text" class="form-control" name="profissao">
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <label>Igreja de Origem</label>
                    <input type="text" class="form-control" name="igreja_origem">
                </div>
                <div class="col-md-6">
                    <label>Como conheceu o Face a Face?</label>
                    <input type="text" class="form-control" name="como_conheceu">
                </div>
            </div>

            <!-- Contato de Emergência -->
            <div class="mt-4">
                <h6>🚨 Contato de Emergência</h6>
                <div class="row">
                    <div class="col-md-6">
                        <label>Nome do Contato</label>
                        <input type="text" class="form-control" name="contato_emergencia">
                    </div>
                    <div class="col-md-6">
                        <label>Telefone de Emergência</label>
                        <input type="tel" class="form-control" name="telefone_emergencia">
                    </div>
                </div>
            </div>

            <!-- Observações Médicas -->
            <div class="mt-3">
                <label>Observações Médicas/Alergias</label>
                <textarea class="form-control" name="observacoes_medicas" rows="2"></textarea>
            </div>
        </div>
    </div>
</form>
```

### 📊 Dashboard de Gestão

#### 1. Visão Geral do Evento
```html
<div class="dashboard-cards row">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5>👷‍♂️ Encontreiros</h5>
                <h3 id="total-encontreiros">0</h3>
                <small>Equipe organizadora</small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5>🎯 Encontristas</h5>
                <h3 id="total-encontristas">0</h3>
                <small>Participantes inscritos</small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5>✅ Confirmados</h5>
                <h3 id="total-confirmados">0</h3>
                <small>Inscrições confirmadas</small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h5>⏳ Pendentes</h5>
                <h3 id="total-pendentes">0</h3>
                <small>Aguardando confirmação</small>
            </div>
        </div>
    </div>
</div>
```

### 🎯 Funcionalidades Principais

1. **Gestão Separada**: Encontreiros (equipe) vs Encontristas (participantes)
2. **Inscrições Controladas**: Status, pagamento, confirmação
3. **Presença Individual**: Controle por ministração
4. **Relatórios Completos**: Participação, avaliações, histórico
5. **Contatos de Emergência**: Para segurança dos participantes

Está perfeito assim? Quer que eu implemente alguma funcionalidade específica ou ajuste alguma parte da estrutura?
