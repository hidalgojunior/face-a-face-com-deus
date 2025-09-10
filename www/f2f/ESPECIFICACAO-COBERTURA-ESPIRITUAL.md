# Sistema Face a Face com Deus - Especificação Atualizada
## Incluindo Conceito de Cobertura Espiritual

### 📊 Estrutura do Banco de Dados Atualizada

```sql
-- Eventos principais (retiros, encontros)
CREATE TABLE eventos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(200) NOT NULL,
    descricao TEXT,
    data_inicio DATE NOT NULL,
    data_fim DATE NOT NULL,
    local_evento VARCHAR(200),
    observacoes TEXT,
    ativo BOOLEAN DEFAULT TRUE
);

-- Cada encontro dentro do evento
CREATE TABLE encontros (
    id INT PRIMARY KEY AUTO_INCREMENT,
    evento_id INT,
    titulo VARCHAR(200) NOT NULL,
    data_encontro DATE NOT NULL,
    horario_inicio TIME NOT NULL,
    horario_fim TIME NOT NULL,
    tema_central TEXT,
    objetivo TEXT,
    FOREIGN KEY (evento_id) REFERENCES eventos(id)
);

-- Ministrações dentro de cada encontro
CREATE TABLE ministracoes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    encontro_id INT,
    ordem_sequencia INT NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    texto_base TEXT NOT NULL,
    duracao_explicativa INT DEFAULT 30,
    duracao_pratica INT DEFAULT 20,
    responsavel_ministrante VARCHAR(100),
    observacoes TEXT,
    playlist_id INT,
    slides_arquivo VARCHAR(255),
    FOREIGN KEY (encontro_id) REFERENCES encontros(id),
    FOREIGN KEY (playlist_id) REFERENCES playlists(id)
);

-- 🆕 COBERTURA ESPIRITUAL DAS MINISTRAÇÕES
CREATE TABLE cobertura_espiritual (
    id INT PRIMARY KEY AUTO_INCREMENT,
    ministracao_id INT NOT NULL,
    nome_responsavel VARCHAR(100) NOT NULL,
    funcao VARCHAR(50) DEFAULT 'Cobertura Espiritual',
    telefone VARCHAR(20),
    email VARCHAR(100),
    observacoes TEXT,
    principal BOOLEAN DEFAULT FALSE,        -- Se é o responsável principal
    ativo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ministracao_id) REFERENCES ministracoes(id) ON DELETE CASCADE
);

-- Mídias (áudios, vídeos, imagens)
CREATE TABLE midias (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(200) NOT NULL,
    arquivo VARCHAR(255) NOT NULL,
    tipo ENUM('audio', 'video', 'imagem') NOT NULL,
    duracao INT DEFAULT NULL,
    tags VARCHAR(500),
    observacoes TEXT,
    data_upload TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Playlists para organizar mídias
CREATE TABLE playlists (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(200) NOT NULL,
    descricao TEXT,
    evento_id INT,
    ordem_global INT DEFAULT 0,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (evento_id) REFERENCES eventos(id)
);

-- Itens das playlists
CREATE TABLE playlist_itens (
    id INT PRIMARY KEY AUTO_INCREMENT,
    playlist_id INT,
    midia_id INT,
    ordem_sequencia INT NOT NULL,
    volume_personalizado FLOAT DEFAULT 1.0,
    fade_in INT DEFAULT 3,
    fade_out INT DEFAULT 3,
    FOREIGN KEY (playlist_id) REFERENCES playlists(id),
    FOREIGN KEY (midia_id) REFERENCES midias(id)
);

-- Estados dos decks do mixer
CREATE TABLE deck_states (
    id INT PRIMARY KEY AUTO_INCREMENT,
    deck_numero INT NOT NULL,
    midia_atual_id INT,
    posicao_atual FLOAT DEFAULT 0,
    volume FLOAT DEFAULT 1.0,
    is_playing BOOLEAN DEFAULT FALSE,
    loop_ativo BOOLEAN DEFAULT FALSE,
    ultima_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (midia_atual_id) REFERENCES midias(id)
);

-- Configurações do automix
CREATE TABLE automix_config (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome_configuracao VARCHAR(100) NOT NULL,
    fade_duration INT DEFAULT 5,
    gap_between_tracks INT DEFAULT 2,
    auto_gain_control BOOLEAN DEFAULT TRUE,
    bpm_sync BOOLEAN DEFAULT FALSE,
    configuracao_json JSON
);
```

### 🔧 Funcionalidades de Cobertura Espiritual

#### 1. Gestão de Cobertura Espiritual
- **Múltiplos Responsáveis**: Cada ministração pode ter uma ou mais pessoas responsáveis pela cobertura espiritual
- **Responsável Principal**: Identificação de quem é o coordenador principal da cobertura
- **Contatos**: Telefone e email para comunicação durante o evento
- **Status Ativo**: Controle de disponibilidade dos responsáveis

#### 2. Interface de Cadastro
```html
<!-- Seção de Cobertura Espiritual no formulário de Ministração -->
<div class="cobertura-section">
    <h4>🛡️ Cobertura Espiritual</h4>
    <div id="coberturas-list">
        <!-- Lista dinâmica de responsáveis -->
    </div>
    <button type="button" class="btn btn-outline-primary" onclick="adicionarCobertura()">
        + Adicionar Responsável
    </button>
</div>
```

#### 3. JavaScript para Gerenciamento
```javascript
function adicionarCobertura() {
    const coberturaHtml = `
        <div class="cobertura-item mb-3 p-3 border rounded">
            <div class="row">
                <div class="col-md-6">
                    <label>Nome do Responsável</label>
                    <input type="text" class="form-control" name="cobertura_nome[]" required>
                </div>
                <div class="col-md-3">
                    <label>Telefone</label>
                    <input type="tel" class="form-control" name="cobertura_telefone[]">
                </div>
                <div class="col-md-3">
                    <label>Principal</label>
                    <select class="form-control" name="cobertura_principal[]">
                        <option value="0">Não</option>
                        <option value="1">Sim</option>
                    </select>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-6">
                    <label>Email</label>
                    <input type="email" class="form-control" name="cobertura_email[]">
                </div>
                <div class="col-md-5">
                    <label>Observações</label>
                    <input type="text" class="form-control" name="cobertura_obs[]">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm" onclick="removerCobertura(this)">
                        ×
                    </button>
                </div>
            </div>
        </div>
    `;
    document.getElementById('coberturas-list').insertAdjacentHTML('beforeend', coberturaHtml);
}

function removerCobertura(btn) {
    btn.closest('.cobertura-item').remove();
}
```

### 📋 Cronograma e Integração PDF

Para integrar com o `Cronograma.pdf` que você mencionou, precisamos entender sua estrutura. Você poderia me descrever:

1. **Formato do Cronograma**: Como estão organizadas as informações no PDF?
2. **Dados Principais**: Quais são as colunas/campos principais?
3. **Estrutura de Tempo**: Como são definidos os horários e durações?
4. **Responsáveis**: Como aparecem os ministrantes no cronograma?

### 🔄 Fluxo de Trabalho Atualizado

1. **Planejamento do Evento**
   - Criação do evento principal
   - Definição dos encontros
   - Importação/criação do cronograma

2. **Configuração das Ministrações**
   - Cadastro de cada ministração
   - Definição do ministrante responsável
   - **🆕 Atribuição da cobertura espiritual**
   - Upload dos slides (PowerPoint)
   - Criação da playlist musical

3. **Durante o Evento**
   - Controle do mixer 4 decks
   - Automix entre músicas
   - Integração com PowerPoint
   - **🆕 Contato rápido com cobertura espiritual**

4. **Relatórios e Acompanhamento**
   - Relatório de ministrações realizadas
   - **🆕 Lista de responsáveis por cobertura**
   - Histórico de uso das mídias

### 🎯 Próximos Passos

1. **Análise do Cronograma.pdf**: Você pode descrever sua estrutura?
2. **Implementação da Base**: Criar as tabelas no banco de dados
3. **Interface de Cadastro**: Desenvolver os formulários
4. **Sistema de Mixer**: Implementar os 4 decks
5. **Integração PowerPoint**: Configurar a comunicação

Gostaria que eu continue com alguma parte específica ou você pode me explicar como está estruturado o cronograma PDF?
