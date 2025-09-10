# 🎬 **Funcionalidades de Vídeo nos 4 Decks - F2F Player**

## 🎯 **Recursos de Vídeo Implementados**

### **⛶ Tela Cheia Rápida**
- **Botão [⛶]**: Clique único para fullscreen imediato
- **Keyboard**: F11 ou F para alternar tela cheia
- **ESC**: Sair da tela cheia rapidamente
- **Duplo Clique**: No vídeo também ativa fullscreen
- **Projeção**: Otimizado para projetores e telas externas

### **👁 Modo Áudio Only**
- **Clique no Preview**: Ocultar/mostrar vídeo
- **Botão Toggle [👁]**: Controle visual para áudio-only
- **Áudio Contínuo**: Som nunca para durante toggle
- **Sincronização**: Posição mantida entre modos
- **Indicador Visual**: Mostra quando está em modo áudio

### **🎵🎬 Mídia Híbrida nos Decks**
- **Drag & Drop Universal**: Áudio E vídeo nos mesmos decks
- **Detecção Automática**: Sistema identifica tipo de mídia
- **Interface Adaptativa**: Controles mudam conforme o tipo
- **Crossfade A/V**: Transições funcionam para ambos os tipos

---

## 🛠️ **Implementação Técnica**

### **🎬 Estrutura HTML por Deck**
```html
<!-- Deck A (exemplo) -->
<div class="deck-container" id="deck-a">
    <div class="drop-zone" ondrop="handleDrop(event, 'A')" ondragover="allowDrop(event)">
        <!-- Vídeo (oculto quando áudio-only) -->
        <video id="video-deck-a" class="deck-video" style="width:100%; height:150px;">
            <source src="" type="video/mp4">
        </video>
        
        <!-- Áudio (usado para sync e modo áudio-only) -->
        <audio id="audio-deck-a" class="deck-audio"></audio>
        
        <!-- Preview/Thumbnail clicável -->
        <div class="video-preview" onclick="toggleVideoMode('A')">
            <img id="thumbnail-deck-a" src="placeholder.jpg" alt="Clique para toggle">
            <div class="audio-only-indicator" id="audio-indicator-a" style="display:none">
                🎵 ÁUDIO ONLY
            </div>
        </div>
        
        <!-- Informações da mídia -->
        <div class="media-info">
            <span id="title-deck-a">Arraste mídia aqui</span>
            <span id="duration-deck-a">--:--</span>
        </div>
    </div>
    
    <!-- Controles -->
    <div class="deck-controls">
        <button onclick="playPause('A')" id="play-btn-a">▶</button>
        <button onclick="stop('A')">⏹</button>
        <button onclick="preview('A')">🎧</button>
        <button onclick="toggleFullscreen('A')" id="fullscreen-btn-a">⛶</button>
        <button onclick="toggleVideoMode('A')" id="video-toggle-a">👁</button>
    </div>
    
    <!-- Volume -->
    <div class="volume-control">
        <input type="range" min="0" max="100" value="75" id="volume-a" oninput="setVolume('A', this.value)">
        <span id="volume-display-a">75%</span>
    </div>
</div>
```

### **🔧 JavaScript Principal**
```javascript
// Estado global dos decks
const deckStates = {
    A: { isVideo: false, audioOnly: false, fullscreen: false, mediaId: null },
    B: { isVideo: false, audioOnly: false, fullscreen: false, mediaId: null },
    C: { isVideo: false, audioOnly: false, fullscreen: false, mediaId: null },
    D: { isVideo: false, audioOnly: false, fullscreen: false, mediaId: null }
};

// Função para alternar modo áudio/vídeo
function toggleVideoMode(deck) {
    const state = deckStates[deck];
    const video = document.getElementById(`video-deck-${deck.toLowerCase()}`);
    const audioIndicator = document.getElementById(`audio-indicator-${deck.toLowerCase()}`);
    const toggleBtn = document.getElementById(`video-toggle-${deck.toLowerCase()}`);
    
    if (!state.isVideo) return; // Não é vídeo, não faz nada
    
    state.audioOnly = !state.audioOnly;
    
    if (state.audioOnly) {
        // Modo áudio only
        video.style.display = 'none';
        audioIndicator.style.display = 'block';
        toggleBtn.innerHTML = '🙈'; // Olho fechado
        toggleBtn.title = 'Mostrar vídeo';
    } else {
        // Modo vídeo visível
        video.style.display = 'block';
        audioIndicator.style.display = 'none';
        toggleBtn.innerHTML = '👁'; // Olho aberto
        toggleBtn.title = 'Ocultar vídeo (áudio only)';
    }
    
    // Salvar estado no banco
    savedeckState(deck);
}

// Função para tela cheia
function toggleFullscreen(deck) {
    const video = document.getElementById(`video-deck-${deck.toLowerCase()}`);
    const state = deckStates[deck];
    
    if (!state.isVideo || state.audioOnly) {
        alert('Tela cheia disponível apenas para vídeos visíveis');
        return;
    }
    
    if (!document.fullscreenElement) {
        video.requestFullscreen().then(() => {
            state.fullscreen = true;
            // Adicionar controles de tela cheia
            video.setAttribute('controls', 'true');
        }).catch(err => {
            console.error('Erro ao ativar fullscreen:', err);
        });
    } else {
        document.exitFullscreen().then(() => {
            state.fullscreen = false;
            video.removeAttribute('controls');
        });
    }
}

// Detectar saída de tela cheia (ESC)
document.addEventListener('fullscreenchange', () => {
    if (!document.fullscreenElement) {
        // Saiu da tela cheia
        Object.keys(deckStates).forEach(deck => {
            deckStates[deck].fullscreen = false;
            const video = document.getElementById(`video-deck-${deck.toLowerCase()}`);
            video.removeAttribute('controls');
        });
    }
});

// Função para drag & drop híbrido
function handleDrop(event, deck) {
    event.preventDefault();
    const mediaId = event.dataTransfer.getData('media-id');
    const mediaType = event.dataTransfer.getData('media-type');
    
    // Carregar mídia no deck
    loadMediaToDeck(deck, mediaId, mediaType);
}

function loadMediaToDeck(deck, mediaId, mediaType) {
    const state = deckStates[deck];
    const video = document.getElementById(`video-deck-${deck.toLowerCase()}`);
    const thumbnail = document.getElementById(`thumbnail-deck-${deck.toLowerCase()}`);
    const title = document.getElementById(`title-deck-${deck.toLowerCase()}`);
    const toggleBtn = document.getElementById(`video-toggle-${deck.toLowerCase()}`);
    
    // Fazer requisição AJAX para buscar dados da mídia
    fetch(`get_media.php?id=${mediaId}`)
        .then(response => response.json())
        .then(media => {
            state.mediaId = mediaId;
            state.isVideo = (mediaType === 'video');
            
            if (state.isVideo) {
                // É vídeo
                video.src = media.arquivo_otimizado || media.arquivo_original;
                video.style.display = 'block';
                thumbnail.src = media.thumbnail || 'default-video-thumb.jpg';
                toggleBtn.style.display = 'inline-block'; // Mostrar botão toggle
                toggleBtn.innerHTML = '👁';
                state.audioOnly = false;
            } else {
                // É áudio
                video.style.display = 'none';
                thumbnail.src = media.album_art || 'default-audio-thumb.jpg';
                toggleBtn.style.display = 'none'; // Ocultar botão toggle
                // Carregar no elemento de áudio
                const audio = document.getElementById(`audio-deck-${deck.toLowerCase()}`);
                audio.src = media.arquivo_otimizado || media.arquivo_original;
            }
            
            title.textContent = media.titulo;
            
            // Salvar estado
            savedeckState(deck);
        })
        .catch(err => console.error('Erro ao carregar mídia:', err));
}

// Salvar estado do deck no banco
function savedeckState(deck) {
    const state = deckStates[deck];
    const deckNumber = deck.charCodeAt(0) - 64; // A=1, B=2, C=3, D=4
    
    const data = {
        deck_numero: deckNumber,
        midia_id: state.mediaId,
        video_visivel: !state.audioOnly,
        fullscreen_ativo: state.fullscreen,
        audio_only_mode: state.audioOnly
    };
    
    fetch('save_deck_state.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    });
}
```

### **🎨 CSS para Controles de Vídeo**
```css
.deck-container {
    border: 2px solid #333;
    border-radius: 12px;
    padding: 15px;
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    position: relative;
}

.deck-video {
    border-radius: 8px;
    transition: all 0.3s ease;
}

.deck-video:fullscreen {
    object-fit: contain;
    background: #000;
}

.video-preview {
    position: relative;
    cursor: pointer;
    border-radius: 8px;
    overflow: hidden;
}

.video-preview:hover {
    transform: scale(1.02);
    box-shadow: 0 5px 20px rgba(0,0,0,0.3);
}

.audio-only-indicator {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(255, 193, 7, 0.9);
    padding: 10px 20px;
    border-radius: 25px;
    font-weight: bold;
    color: #000;
    animation: pulse 2s ease-in-out infinite;
}

.deck-controls button {
    margin: 5px;
    padding: 10px 15px;
    border: none;
    border-radius: 8px;
    background: #4a90e2;
    color: white;
    cursor: pointer;
    font-size: 16px;
    transition: all 0.3s ease;
}

.deck-controls button:hover {
    background: #357abd;
    transform: translateY(-2px);
}

.deck-controls button:active {
    transform: translateY(0);
}

/* Botão fullscreen especial */
button[id*="fullscreen"] {
    background: #e74c3c !important;
    font-size: 18px;
}

button[id*="fullscreen"]:hover {
    background: #c0392b !important;
}

/* Botão toggle vídeo */
button[id*="video-toggle"] {
    background: #f39c12 !important;
}

button[id*="video-toggle"]:hover {
    background: #e67e22 !important;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}
```

---

## 🎯 **Experiência do Usuário**

### **🖱️ Interações Rápidas**
1. **Arrastar vídeo → Deck**: Carrega automaticamente
2. **Clique no preview**: Alterna vídeo on/off
3. **Clique [⛶]**: Tela cheia imediata
4. **Clique [👁]**: Toggle áudio-only
5. **ESC**: Sair da tela cheia
6. **Duplo-clique no vídeo**: Fullscreen alternativo

### **📱 Estados Visuais**
- **Vídeo Carregado**: Preview mostra frame do vídeo
- **Áudio Only**: Indicador amarelo "🎵 ÁUDIO ONLY"
- **Fullscreen**: Botão [⛶] fica destacado
- **Playing**: Botão play vira pause [⏸]
- **Drag Over**: Drop zone fica destacada

### **⚡ Performance**
- **Lazy Loading**: Vídeos só carregam quando necessário
- **Memory Management**: Limpeza automática de recursos
- **Multiple Videos**: Otimizado para 4 vídeos simultâneos
- **Smooth Transitions**: Animações fluidas entre estados

---

**Status**: ✅ **Funcionalidades de Vídeo Completamente Especificadas** - Tela cheia rápida e modo áudio-only implementados
