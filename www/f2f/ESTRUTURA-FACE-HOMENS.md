# Sistema Face a Face de Homens - Estrutura Completa
## Baseado no Cronograma Real do Evento

### 📊 Estrutura do Banco de Dados - Face de Homens

```sql
-- Eventos principais
CREATE TABLE eventos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(200) NOT NULL,
    tipo ENUM('homens', 'mulheres') DEFAULT 'homens',
    descricao TEXT,
    data_inicio DATE NOT NULL,
    data_fim DATE NOT NULL,
    local_evento VARCHAR(200),
    observacoes TEXT,
    ativo BOOLEAN DEFAULT TRUE
);

-- Sub-eventos dentro do evento principal
CREATE TABLE sub_eventos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    evento_id INT,
    nome VARCHAR(200) NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fim TIME,
    ordem_sequencia INT NOT NULL,
    observacoes TEXT,
    FOREIGN KEY (evento_id) REFERENCES eventos(id)
);

-- Ministros (pessoas que ministram)
CREATE TABLE ministros (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(20),
    email VARCHAR(100),
    especializacao TEXT,
    observacoes TEXT,
    ativo BOOLEAN DEFAULT TRUE
);

-- Intercessores (cobertura espiritual)
CREATE TABLE intercessores (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(20),
    email VARCHAR(100),
    observacoes TEXT,
    ativo BOOLEAN DEFAULT TRUE
);

-- Ministrações (momento específico no cronograma)
CREATE TABLE ministracoes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    sub_evento_id INT,
    ministro_id INT,
    titulo VARCHAR(200),
    hora_prevista TIME NOT NULL,
    duracao_estimada INT, -- em minutos
    observacoes TEXT,
    FOREIGN KEY (sub_evento_id) REFERENCES sub_eventos(id),
    FOREIGN KEY (ministro_id) REFERENCES ministros(id)
);

-- Relação ministração-intercessores (muitos para muitos)
CREATE TABLE ministracao_intercessores (
    id INT PRIMARY KEY AUTO_INCREMENT,
    ministracao_id INT,
    intercessor_id INT,
    principal BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (ministracao_id) REFERENCES ministracoes(id),
    FOREIGN KEY (intercessor_id) REFERENCES intercessores(id)
);

-- 🏗️ ESTRUTURA DE EQUIPES DO FACE DE HOMENS

-- Equipes Externas (4 equipes)
CREATE TABLE equipes_externas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL, -- Ex: "Equipe Externa 1", "Equipe Externa 2"
    lider_nome VARCHAR(100) NOT NULL,
    vice_lider_nome VARCHAR(100),
    lider_telefone VARCHAR(20),
    vice_lider_telefone VARCHAR(20),
    ativo BOOLEAN DEFAULT TRUE
);

-- Membros das Equipes Externas
CREATE TABLE membros_equipes_externas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    equipe_id INT,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(20),
    funcao VARCHAR(50), -- "Membro", "Líder", "Vice-Líder"
    observacoes TEXT,
    FOREIGN KEY (equipe_id) REFERENCES equipes_externas(id)
);

-- Líderes de Adoratório
CREATE TABLE lideres_adoratorio (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(20),
    is_lider_principal BOOLEAN DEFAULT FALSE,
    observacoes TEXT,
    ativo BOOLEAN DEFAULT TRUE
);

-- Líderes de Torre
CREATE TABLE lideres_torre (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(20),
    is_lider_principal BOOLEAN DEFAULT FALSE,
    observacoes TEXT,
    ativo BOOLEAN DEFAULT TRUE
);

-- Líderes de Som e Imagem
CREATE TABLE lideres_som_imagem (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(20),
    especialidade ENUM('som', 'imagem', 'ambos') DEFAULT 'ambos',
    is_lider_principal BOOLEAN DEFAULT FALSE,
    observacoes TEXT,
    ativo BOOLEAN DEFAULT TRUE
);

-- Líderes de Saúda (1 a 4 pessoas)
CREATE TABLE lideres_sauda (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(20),
    observacoes TEXT,
    ativo BOOLEAN DEFAULT TRUE
);

-- Correio (2 a 4 pessoas)
CREATE TABLE equipe_correio (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(20),
    observacoes TEXT,
    ativo BOOLEAN DEFAULT TRUE
);

-- Standby (ao menos 1)
CREATE TABLE equipe_standby (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(20),
    especialidade VARCHAR(100), -- "Geral", "Som", "Torre", etc.
    observacoes TEXT,
    ativo BOOLEAN DEFAULT TRUE
);

-- Líderes Externos (normalmente 2, pode ser mais)
CREATE TABLE lideres_externos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(20),
    igreja_origem VARCHAR(200),
    observacoes TEXT,
    ativo BOOLEAN DEFAULT TRUE
);

-- Equipe de Teatro e Fogo/Tochas
CREATE TABLE equipe_teatro_fogo (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(20),
    especialidade ENUM('teatro', 'fogo_tochas', 'ambos') NOT NULL,
    observacoes TEXT,
    ativo BOOLEAN DEFAULT TRUE
);

-- Equipe de Cozinha (mais numerosa)
CREATE TABLE equipe_cozinha (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(20),
    funcao VARCHAR(50), -- "Coordenador", "Cozinheiro", "Auxiliar", "Serviço"
    turno VARCHAR(20), -- "Manhã", "Tarde", "Noite", "Integral"
    observacoes TEXT,
    ativo BOOLEAN DEFAULT TRUE
);

-- 📅 PARTICIPAÇÃO DAS EQUIPES NOS SUB-EVENTOS

-- Controla quais equipes participam de cada sub-evento
CREATE TABLE participacao_equipes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    sub_evento_id INT,
    tipo_equipe ENUM(
        'equipe_externa', 'adoratorio', 'torre', 'som_imagem', 
        'sauda', 'correio', 'standby', 'lider_externo', 
        'teatro_fogo', 'cozinha'
    ) NOT NULL,
    equipe_id INT, -- ID da equipe específica
    observacoes TEXT,
    FOREIGN KEY (sub_evento_id) REFERENCES sub_eventos(id)
);
```

### 🎯 Interface de Gestão do Cronograma

#### 1. Cadastro de Ministração com Intercessores
```html
<form id="form-ministracao">
    <div class="row">
        <div class="col-md-6">
            <label>Ministro</label>
            <select class="form-control" name="ministro_id" required>
                <option value="">Selecione o Ministro</option>
                <!-- Lista de ministros cadastrados -->
            </select>
        </div>
        <div class="col-md-3">
            <label>Hora Prevista</label>
            <input type="time" class="form-control" name="hora_prevista" required>
        </div>
        <div class="col-md-3">
            <label>Duração (min)</label>
            <input type="number" class="form-control" name="duracao_estimada" value="30">
        </div>
    </div>

    <!-- Seção de Intercessores -->
    <div class="mt-4">
        <h5>🛡️ Intercessores (Cobertura Espiritual)</h5>
        <div id="intercessores-list">
            <!-- Lista dinâmica de intercessores -->
        </div>
        <button type="button" class="btn btn-outline-primary" onclick="adicionarIntercessor()">
            + Adicionar Intercessor
        </button>
    </div>
</form>
```

#### 2. Gestão de Equipes por Sub-Evento
```html
<div class="equipes-participacao">
    <h5>👥 Equipes Participantes</h5>
    <div class="row">
        <div class="col-md-3">
            <h6>Equipes Externas</h6>
            <div class="form-check">
                <input type="checkbox" name="equipes[]" value="equipe_externa_1">
                <label>Equipe Externa 1</label>
            </div>
            <!-- Repetir para as 4 equipes -->
        </div>
        
        <div class="col-md-3">
            <h6>Liderança</h6>
            <div class="form-check">
                <input type="checkbox" name="equipes[]" value="adoratorio">
                <label>Adoratório</label>
            </div>
            <div class="form-check">
                <input type="checkbox" name="equipes[]" value="torre">
                <label>Torre</label>
            </div>
            <div class="form-check">
                <input type="checkbox" name="equipes[]" value="som_imagem">
                <label>Som e Imagem</label>
            </div>
        </div>
        
        <div class="col-md-3">
            <h6>Apoio</h6>
            <div class="form-check">
                <input type="checkbox" name="equipes[]" value="sauda">
                <label>Saúda</label>
            </div>
            <div class="form-check">
                <input type="checkbox" name="equipes[]" value="correio">
                <label>Correio</label>
            </div>
            <div class="form-check">
                <input type="checkbox" name="equipes[]" value="standby">
                <label>Standby</label>
            </div>
        </div>
        
        <div class="col-md-3">
            <h6>Especiais</h6>
            <div class="form-check">
                <input type="checkbox" name="equipes[]" value="teatro_fogo">
                <label>Teatro e Fogo</label>
            </div>
            <div class="form-check">
                <input type="checkbox" name="equipes[]" value="cozinha">
                <label>Cozinha</label>
            </div>
            <div class="form-check">
                <input type="checkbox" name="equipes[]" value="lider_externo">
                <label>Líderes Externos</label>
            </div>
        </div>
    </div>
</div>
```

### 📋 Visualização do Cronograma

```html
<div class="cronograma-view">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Hora</th>
                <th>Ministro</th>
                <th>Intercessores</th>
                <th>Sub-Evento</th>
                <th>Equipes Participantes</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody id="cronograma-tbody">
            <!-- Dados do cronograma carregados dinamicamente -->
        </tbody>
    </table>
</div>
```

### 🎯 Funcionalidades Principais

1. **Cadastro Completo de Equipes**: Todas as 10+ categorias de equipes
2. **Gestão de Ministros e Intercessores**: Múltiplos intercessores por ministração
3. **Cronograma Detalhado**: Horários, durações, participações
4. **Controle de Participação**: Quais equipes participam de cada momento
5. **Relatórios**: Listagens de responsabilidades por pessoa/equipe

Está tudo certo até aqui? Quer que eu continue desenvolvendo alguma parte específica ou tem algum ajuste na estrutura?
