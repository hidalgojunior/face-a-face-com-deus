# 🎵 Sistema de Mídia Face a Face - Especificação Técnica

## 🎯 **Funcionalidade Central: Player Profissional com Fader**

### **📋 Resumo Executivo**
Sistema web para upload, organização e reprodução profissional de mídia (áudio/vídeo) para eventos "Face a Face com Deus", com foco na eliminação de quebras bruscas através de controles de transição avançados.

---

## 🎛️ **Componentes do Player**

### **🎵 Player de Áudio**
- **Reprodução**: Controles play/pause/stop
- **Fader Principal**: Controle de volume com crossfade
- **Pré-escuta**: Canal separado para preview
- **Loop**: Repetição automática quando necessário
- **Fade In/Out**: Entrada e saída suaves
- **Crossfade**: Transição entre duas faixas simultâneas

### **🎬 Player de Vídeo**  
- **Reprodução Sincronizada**: Vídeo + áudio coordenados
- **Controle de Volume**: Independente do áudio ambiente
- **Fullscreen**: Modo tela cheia para projeção
- **Preview**: Visualização antes da exibição
- **Fade de Vídeo**: Transições visuais suaves

### **🎚️ Sistema de 4 Decks**
```
[DECK A] ----[CROSSFADER A-B]---- [DECK B]
   |              |                   |
[Vol A]       [Master Vol]        [Vol B]
   |              |                   |
[Preview]     [Fade Speed]        [Preview]

[DECK C] ----[CROSSFADER C-D]---- [DECK D]  
   |              |                   |
[Vol C]       [Master Vol]        [Vol D]
   |              |                   |
[Preview]     [Fade Speed]        [Preview]
```

### **🎵 Funcionalidade Drag & Drop**
- **4 Decks Simultâneos**: Slots independentes para músicas
- **Arrastar e Soltar**: Interface drag & drop intuitiva
- **Preview por Deck**: Pré-escuta individual
- **Crossfade Entre Decks**: A↔B e C↔D independentes
- **Controle Individual**: Volume e efeitos por deck

---

## 📤 **Sistema de Upload**

### **🎵 Upload de Áudio**
- **Formatos Suportados**: MP3, WAV, FLAC, AAC, OGG
- **Validação**: Verificação de formato e tamanho
- **Metadata**: Extração automática (título, duração, artista)
- **Organização**: Categorização por tipo de evento

### **🎬 Upload de Vídeo**
- **Formatos Suportados**: MP4, AVI, MOV, WMV, WebM
- **Compressão**: Otimização automática para web
- **Thumbnails**: Geração automática de miniaturas
- **Resolução**: Múltiplas qualidades (480p, 720p, 1080p)

### **📁 Estrutura de Armazenamento**
```
/f2f/uploads/
├── audio/
│   ├── face-homens/
│   ├── face-mulheres/
│   ├── face-casais/
│   ├── face-criancas/
│   └── face-jovens/
└── video/
    ├── face-homens/
    ├── face-mulheres/  
    ├── face-casais/
    ├── face-criancas/
    └── face-jovens/
```

---

## 🗄️ **Estrutura do Banco de Dados**

### **Tabelas Principais**
```sql
-- Tipos de eventos Face a Face
CREATE TABLE eventos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,        -- 'Face de Homens', etc
    descricao TEXT,
    ativo BOOLEAN DEFAULT TRUE,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Arquivos de mídia
CREATE TABLE midias (
    id INT PRIMARY KEY AUTO_INCREMENT,
    evento_id INT,
    tipo ENUM('audio', 'video') NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    arquivo_original VARCHAR(255) NOT NULL,
    arquivo_otimizado VARCHAR(255),
    duracao_segundos INT,
    tamanho_bytes BIGINT,
    formato VARCHAR(10),
    upload_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ativo BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (evento_id) REFERENCES eventos(id)
);

-- Playlists para cada evento
CREATE TABLE playlists (
    id INT PRIMARY KEY AUTO_INCREMENT,
    evento_id INT,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (evento_id) REFERENCES eventos(id)
);

-- Itens das playlists  
CREATE TABLE playlist_itens (
    id INT PRIMARY KEY AUTO_INCREMENT,
    playlist_id INT,
    midia_id INT,
    ordem INT NOT NULL,
    fade_in_segundos DECIMAL(4,2) DEFAULT 2.0,
    fade_out_segundos DECIMAL(4,2) DEFAULT 2.0,
    loop_individual INT DEFAULT 1,         -- Quantas vezes repetir esta música
    slide_numero INT NULL,                 -- Slide do PowerPoint associado
    tempo_entrada_segundos INT NULL,       -- Quando entrar na ministração
    FOREIGN KEY (playlist_id) REFERENCES playlists(id),
    FOREIGN KEY (midia_id) REFERENCES midias(id)
);

-- Encontros Face a Face
CREATE TABLE encontros (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tipo_face ENUM('homens', 'mulheres', 'casais', 'criancas', 'jovens') NOT NULL,
    data_evento DATE NOT NULL,
    tema_principal VARCHAR(200),
    versiculo_central TEXT,
    responsavel_geral VARCHAR(100),
    local VARCHAR(150),
    observacoes TEXT,
    status ENUM('planejando', 'confirmado', 'realizado') DEFAULT 'planejando',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Ministrações dentro de cada encontro
CREATE TABLE ministracoes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    encontro_id INT,
    ordem_sequencia INT NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    texto_base TEXT NOT NULL,              -- Versículo/passagem principal
    duracao_explicativa INT DEFAULT 30,    -- minutos da parte explicativa
    duracao_pratica INT DEFAULT 20,        -- minutos da parte prática  
    responsavel_ministrante VARCHAR(100),
    observacoes TEXT,
    playlist_id INT,                       -- Playlist associada
    slides_arquivo VARCHAR(255),           -- Arquivo PowerPoint
    FOREIGN KEY (encontro_id) REFERENCES encontros(id),
    FOREIGN KEY (playlist_id) REFERENCES playlists(id)
);

-- Configurações do automix
CREATE TABLE automix_config (
    id INT PRIMARY KEY AUTO_INCREMENT,
    playlist_id INT,
    crossfade_segundos DECIMAL(4,2) DEFAULT 3.0,
    pausa_entre_tracks DECIMAL(4,2) DEFAULT 0.0,
    loop_playlist INT DEFAULT 1,           -- Quantas vezes repetir playlist (0 = infinito)
    auto_advance_slides BOOLEAN DEFAULT FALSE,
    volume_normalization BOOLEAN DEFAULT TRUE,
    silence_detection BOOLEAN DEFAULT TRUE,
    ativo BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (playlist_id) REFERENCES playlists(id)
);

-- Configurações do player
CREATE TABLE configuracoes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    chave VARCHAR(100) UNIQUE NOT NULL,
    valor TEXT,
    descricao VARCHAR(255),
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

---

## 🎚️ **Interface do Player**

### **Layout com 4 Decks (Áudio + Vídeo)**
```
┌───────────────────────────────────────────────────────────────┐
│                🎵🎬 F2F PLAYER - 4 DECKS A/V                 │
├────────────────────────┬──────────────────────────────────────┤
│        DECK A          │            DECK B                    │
│ ┌────────────────────┐ │ ┌──────────────────────────────────┐ │
│ │   [DROP ZONE A]    │ │ │         [DROP ZONE B]            │ │
│ │🎵🎬 Arraste mídia  │ │ │     🎵🎬 Arraste mídia           │ │
│ │ [📱VIDEO] Título   │ │ │      [📱VIDEO] Título            │ │
│ │ Clique p/ ocultar  │ │ │     Clique p/ ocultar            │ │
│ └────────────────────┘ │ └──────────────────────────────────┘ │
│ [▶] [⏸] [⏹] [🎧] [⛶] │  [▶] [⏸] [⏹] [🎧] [⛶]            │
│ Vol:[████░░░░░░] [👁] │  Vol:[██████░░░░] [👁]               │
├────────────────────────┼──────────────────────────────────────┤
│           CROSSFADER A ↔ B                                   │
│        A ●──────────○──────────● B                          │
├────────────────────────┬──────────────────────────────────────┤
│        DECK C          │            DECK D                    │
│ ┌────────────────────┐ │ ┌──────────────────────────────────┐ │
│ │   [DROP ZONE C]    │ │ │         [DROP ZONE D]            │ │
│ │🎵🎬 Arraste mídia  │ │ │     🎵🎬 Arraste mídia           │ │
│ │ [📱VIDEO] Título   │ │ │      [📱VIDEO] Título            │ │
│ │ Clique p/ ocultar  │ │ │     Clique p/ ocultar            │ │
│ └────────────────────┘ │ └──────────────────────────────────┘ │
│ [▶] [⏸] [⏹] [🎧] [⛶] │  [▶] [⏸] [⏹] [🎧] [⛶]            │
│ Vol:[███░░░░░░░] [👁] │  Vol:[████████░░] [👁]               │
├────────────────────────┼──────────────────────────────────────┤
│           CROSSFADER C ↔ D                                   │
│        C ●──────────○──────────● D                          │
├──────────────────────────────────────────────────────────────┤
│ Master Vol: [███████░░░] │ Fade Speed: [████░░░░░░]          │
├──────────────────────────────────────────────────────────────┤
│     BIBLIOTECA DE MÍDIAS - Arraste para os Decks            │
│ [🎵 Audio1] [🎬 Video1] [🎵 Audio2] [� Video2] [🎵 Audio3] │
│ [� Video3] [🎵 Audio4] [� Video4] [🎵 Audio5] [🎬 Video5] │
├──────────────────────────────────────────────────────────────┤
│ [📂 Upload] [📋 Playlist] [⚙️ Config] [🎚️ Mix] [🖥️ Display]│
└──────────────────────────────────────────────────────────────┘

LEGENDA DOS BOTÕES:
[⛶] = Fullscreen rápido    [👁] = Toggle vídeo on/off
[🎧] = Preview/Monitor      [📱] = Área clicável do vídeo
```

---

## 🎚️ **Sistema de 4 Decks - Especificação Detalhada**

### **🎵 Funcionalidade dos Decks (Áudio + Vídeo)**
- **DECK A & B**: Par principal com crossfader A↔B
- **DECK C & D**: Par secundário com crossfader C↔D  
- **Mídia Híbrida**: Suporte para áudio e vídeo nos mesmos decks
- **Independência**: Cada deck opera de forma autônoma
- **Sincronização**: Possibilidade de sync entre decks

### **🎬 Controles de Vídeo nos Decks**
- **Fullscreen Rápido**: Botão [⛶] para tela cheia imediata
- **Modo Áudio Only**: Clique no preview para ocultar vídeo
- **Toggle Visual**: Alternar entre vídeo visível/oculto
- **Projeção Direta**: Exibição em tela cheia para projetor
- **Audio Independente**: Áudio continua mesmo com vídeo oculto

### **🖱️ Drag & Drop Interface**
```javascript
// Funcionalidade de arrastar e soltar
- Arrastar da biblioteca → Deck (carrega música)
- Arrastar entre decks → Troca posições
- Drop zones visuais → Feedback visual ao arrastar
- Preview ao hover → Mostra info da música
```

### **🎛️ Controles por Deck**
- **Play/Pause/Stop**: Controle individual
- **Volume**: Slider independente (0-100%)  
- **Preview**: Botão de pré-escuta (fone 🎧)
- **Loop**: Repetição automática
- **Fade In/Out**: Configurável por deck
- **Tempo/BPM**: Detecção automática (futuro)

### **🎬 Controles Específicos para Vídeo**
- **Fullscreen [⛶]**: Tela cheia rápida (F11 programático)
- **Video Toggle [👁]**: Mostrar/ocultar vídeo (áudio continua)
- **Preview Click**: Clique no preview alterna visibilidade
- **Aspect Ratio**: Manter proporção em tela cheia
- **Escape Easy**: ESC ou clique para sair da tela cheia

### **🔄 Sistema de Crossfade**
- **Crossfader A↔B**: Transição entre deck A e B
- **Crossfader C↔D**: Transição entre deck C e D
- **Mixer Master**: Controle geral A/B ↔ C/D
- **Curva Configurável**: Linear, logarítmica, exponencial

### **📚 Biblioteca de Músicas**
- **Lista Dinâmica**: Todas as músicas disponíveis
- **Drag Visual**: Elementos arrastáveis
- **Filtros**: Por evento, artista, duração
- **Busca**: Campo de pesquisa em tempo real
- **Categorização**: Por tipo de Face a Face

### **💾 Estado dos Decks (Áudio + Vídeo)**
```sql
-- Nova tabela para estado dos decks
CREATE TABLE deck_states (
    id INT PRIMARY KEY AUTO_INCREMENT,
    deck_numero INT NOT NULL (1-4),
    midia_id INT,
    posicao_segundos DECIMAL(10,2) DEFAULT 0,
    volume_percent INT DEFAULT 75,
    loop_ativo BOOLEAN DEFAULT FALSE,
    fade_in_segundos DECIMAL(4,2) DEFAULT 2.0,
    fade_out_segundos DECIMAL(4,2) DEFAULT 2.0,
    video_visivel BOOLEAN DEFAULT TRUE,
    fullscreen_ativo BOOLEAN DEFAULT FALSE,
    audio_only_mode BOOLEAN DEFAULT FALSE,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (midia_id) REFERENCES midias(id)
);
```

### **🎬 Funcionalidades de Vídeo Implementadas**

#### **⛶ Fullscreen Rápido**
```javascript
// Função para tela cheia instantânea
function toggleFullscreen(deckNumber) {
    const videoElement = document.getElementById(`video-deck-${deckNumber}`);
    if (!document.fullscreenElement) {
        videoElement.requestFullscreen();
    } else {
        document.exitFullscreen();
    }
}
```

#### **👁 Modo Áudio Only**
```javascript
// Clique no preview ou botão para ocultar vídeo
function toggleVideoVisibility(deckNumber) {
    const videoElement = document.getElementById(`video-deck-${deckNumber}`);
    const audioElement = document.getElementById(`audio-deck-${deckNumber}`);
    
    if (videoElement.style.display !== 'none') {
        // Ocultar vídeo, manter áudio
        videoElement.style.display = 'none';
        audioElement.currentTime = videoElement.currentTime;
        audioElement.play();
        videoElement.pause();
    } else {
        // Mostrar vídeo, sincronizar com áudio
        videoElement.style.display = 'block';
        videoElement.currentTime = audioElement.currentTime;
        videoElement.play();
        audioElement.pause();
    }
}
```

#### **🔄 Sincronização A/V**
```javascript
// Manter sincronização entre áudio e vídeo
function syncAudioVideo(deckNumber) {
    const video = document.getElementById(`video-deck-${deckNumber}`);
    const audio = document.getElementById(`audio-deck-${deckNumber}`);
    
    // Eventos de sincronização
    video.addEventListener('timeupdate', () => {
        if (Math.abs(video.currentTime - audio.currentTime) > 0.1) {
            audio.currentTime = video.currentTime;
        }
    });
}
```

---

## 🚀 **Tecnologias a Utilizar**

### **Frontend**
- **HTML5**: Estrutura base + Drag & Drop API
- **CSS3**: Styling responsivo + animações de drag
- **JavaScript ES6+**: Controle do player + funcionalidade drag & drop
- **Web Audio API**: Controles avançados de áudio
- **Bootstrap 5**: Framework UI responsivo
- **Sortable.js**: Biblioteca para drag & drop avançado

### **Backend**  
- **PHP 8.2**: Lógica do servidor
- **MySQL 8.0**: Banco de dados
- **FFmpeg**: Processamento de mídia (conversão/otimização)
- **File Upload**: Gerenciamento de uploads grandes

### **Player Engine**
- **Howler.js**: Biblioteca de áudio avançada
- **Video.js**: Player de vídeo robusto com fullscreen API
- **Fullscreen API**: Controle nativo de tela cheia
- **Media Session API**: Controle de mídia avançado
- **WebRTC**: Para funcionalidades em tempo real
- **Progressive Web App**: Funcionalidade offline

### **Controle de Vídeo Avançado**
- **HTML5 Video Element**: Base para reprodução
- **Fullscreen Events**: Detecção de entrada/saída de tela cheia
- **Keyboard Shortcuts**: ESC para sair, F para fullscreen
- **Aspect Ratio**: Manutenção de proporção em qualquer resolução
- **Performance**: Otimização para múltiplos vídeos simultâneos

---

## 📋 **Requisitos do Sistema**

### **Server Requirements**
- **PHP**: 8.2+
- **MySQL**: 8.0+
- **FFmpeg**: Para processamento de mídia
- **Upload Max**: 500MB (configurável)
- **Storage**: Mínimo 10GB (expansível)

### **Client Requirements**
- **Browser**: Chrome 90+, Firefox 88+, Safari 14+
- **JavaScript**: Habilitado
- **Audio API**: Web Audio API support
- **Upload**: HTML5 File API support

---

## ⚙️ **Funcionalidades do Instalador**

### **🔧 Setup Visual**
1. **Verificação de Requisitos**: PHP, MySQL, FFmpeg, permissões
2. **Configuração do Banco**: Criação das 5 tabelas documentadas
3. **Setup de Pastas**: Criação da estrutura de uploads
4. **Configurações Iniciais**: Settings padrão do player
5. **Teste de Funcionalidades**: Validação do sistema

### **📊 Feedback Detalhado**
- ✅ "Criando tabela 'eventos'... OK"
- ✅ "Criando tabela 'midias'... OK"  
- ✅ "Criando tabela 'playlists'... OK"
- ✅ "Criando tabela 'playlist_itens'... OK"
- ✅ "Criando tabela 'configuracoes'... OK"
- ✅ "Configurando pastas de upload... OK"
- ✅ "Testando FFmpeg... OK"

---

**Status**: 📋 **Especificação Técnica Completa** - Pronto para aprovação e desenvolvimento
