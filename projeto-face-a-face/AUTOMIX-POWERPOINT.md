# 🎵📊 Sistema de Automix e Integração PowerPoint - F2F

## 🎯 **Funcionalidades de Automix**

### **📝 Criação de Playlists**
- **Por Encontro**: Playlist específica para cada Face a Face
- **Por Ministração**: Lista para cada parte do encontro
- **Por Momento**: Background, adoração, reflexão, encerramento
- **Drag & Drop**: Arrastar músicas da biblioteca para a playlist
- **Reordenação**: Arrastar dentro da playlist para reordenar
- **Duração Total**: Cálculo automático do tempo da playlist

### **🔄 Sistema de Automix**
```
🎵 MÚSICA 1 ──[fade out]──┐
                          ├──[crossfade]──► 🎵 MÚSICA 2 ──[fade out]──┐
🎵 MÚSICA 2 ──[fade in ]──┘                                            ├──[crossfade]──► 🎵 MÚSICA 3
                                           🎵 MÚSICA 3 ──[fade in ]──┘
```

#### **⚙️ Configurações do Automix**
- **Tempo de Crossfade**: 2-10 segundos configurável
- **Pausa Entre Músicas**: 0-5 segundos opcional
- **Fade In/Out**: Velocidade da entrada/saída
- **Detecção de Silêncio**: Auto-skip em trechos mudos
- **Volume Automático**: Normalização entre faixas

### **🔁 Sistema de Loops**

#### **🎵 Loop de Música Individual**
- **Repetições Definidas**: 2x, 3x, 5x, 10x
- **Loop Infinito**: Até intervenção manual
- **Fade entre Loops**: Suave retorno ao início
- **Contador Visual**: Mostra repetição atual (3/5)

#### **📋 Loop de Playlist**
- **Repetir Lista**: 2x, 3x, 5x, infinito
- **Shuffle Mode**: Ordem aleatória a cada repetição
- **Cross-Lista**: Fade da última para primeira música
- **Pausa entre Ciclos**: Opcional entre repetições

---

## 📊 **Integração PowerPoint**

### **🖥️ Controle Remoto de Slides**
- **Avançar Slide**: Botão [→] ou tecla programada
- **Voltar Slide**: Botão [←] ou tecla programada  
- **Ir para Slide**: Input direto do número
- **Modo Apresentação**: Iniciar/parar apresentação
- **Tela Cheia**: Toggle fullscreen do PowerPoint

### **🔗 Sincronização Música + Slide**
- **Trigger Automático**: Slide muda com nova música
- **Marcadores Temporais**: Slide específico em tempo X
- **Pause & Play**: Música pausa quando apresentação para
- **Slide de Música**: Exibe título/artista da música atual
- **Background Sync**: Slide como plano de fundo do vídeo

### **📱 Interface de Controle**
```
┌─────────────────────────────────────────────────┐
│              CONTROLE DE PROJEÇÃO               │
├──────────────────┬──────────────────────────────┤
│   POWERPOINT     │         MÚSICA ATUAL         │
│ ┌──────────────┐ │ ┌──────────────────────────┐ │
│ │  SLIDE 5/12  │ │ │ 🎵 "Amazing Grace"       │ │
│ │              │ │ │ ⏱️  2:34 / 4:12          │ │
│ │ [Texto Base] │ │ │ 🔁 Loop: 3/5 repetições │ │
│ └──────────────┘ │ └──────────────────────────┘ │
│                  │                              │
│ [←] [■] [→] [⛶] │ [⏸] [⏭] [🔁] [📋]         │
│                  │                              │
├──────────────────┴──────────────────────────────┤
│           AUTOMIX - PLAYLIST ATIVA              │
│ ▶ Música 1 - Adoração Inicial      [03:45]     │
│ ▶ Música 2 - Reflexão Profunda     [04:12] ←atual│
│   Música 3 - Momento de Oração     [02:58]     │
│   Música 4 - Encerramento Suave    [03:33]     │
│                                                 │
│ 🔄 Loop Playlist: ∞  |  Crossfade: 3s         │
├─────────────────────────────────────────────────┤
│ [📋 Nova Playlist] [⚙️ Config] [💾 Salvar]    │
└─────────────────────────────────────────────────┘
```

---

## 📋 **Gestão de Ministrações**

### **🏛️ Estrutura de Encontros**
```sql
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

-- Cronograma detalhado
CREATE TABLE cronograma (
    id INT PRIMARY KEY AUTO_INCREMENT,
    encontro_id INT,
    horario_inicio TIME NOT NULL,
    horario_fim TIME,
    atividade ENUM('abertura', 'louvor', 'ministracao', 'intervalo', 'encerramento'),
    ministracao_id INT NULL,
    descricao VARCHAR(200),
    responsavel VARCHAR(100),
    FOREIGN KEY (encontro_id) REFERENCES encontros(id),
    FOREIGN KEY (ministracao_id) REFERENCES ministracoes(id)
);
```

### **📝 Textos Base das Ministrações**
- **Campo Obrigatório**: Todo ministração tem um versículo/passagem
- **Exibição Automática**: Texto aparece no slide automaticamente
- **Formatação**: Versículo, referência, tradução
- **Múltiplas Versões**: NIV, NVI, ARA, NTLH
- **Slide Dedicado**: Template especial para texto base

### **⏱️ Controle de Tempo**
- **Timer por Ministração**: Conta tempo decorrido
- **Alertas Visuais**: 5min, 2min, tempo esgotado
- **Parte Explicativa**: Timer separado
- **Parte Prática**: Timer separado  
- **Intervalo Automático**: Entre ministrações

---

## 🎛️ **Interface Integrada**

### **📊 Dashboard do Encontro**
```
┌─────────────────────────────────────────────────────────────────┐
│        FACE DE HOMENS - 15/09/2025 - "Propósito Divino"       │
├─────────────────────────────────────────────────────────────────┤
│ MINISTRAÇÃO ATUAL: "Descobrindo seu Chamado" (2/4)             │
│ Texto Base: "Jeremias 29:11" - Planos de prosperidade          │
│ ⏱️ Tempo: 15:23 / 30:00 (Explicativa) | Próximo: Prática      │
├─────────────────────────────────────────────────────────────────┤
│                    CONTROLES INTEGRADOS                         │
├──────────────────┬──────────────────┬───────────────────────────┤
│   POWERPOINT     │     AUTOMIX      │        DECKS MANUAIS      │
│                  │                  │                           │
│ Slide: 5/12      │ 🎵 "Mais Alto"   │ [DECK A] [DECK B]        │
│ [←][■][→][⛶]    │ ⏱️  1:45/3:20    │ [DECK C] [DECK D]        │
│                  │ 🔁 Loop: 2/∞     │                           │
│ Próximo Auto:    │                  │ [Crossfade A↔B]          │
│ "Texto Base"     │ [⏸][⏭][📋][⚙️] │ [Crossfade C↔D]          │
│                  │                  │                           │
├──────────────────┴──────────────────┴───────────────────────────┤
│                      CRONOGRAMA                                 │
│ ✅ 19:00 Abertura           ▶ 19:30 Ministração 2 (ATUAL)     │
│ ✅ 19:15 Louvor Inicial     ⏳ 20:00 Intervalo                 │
│ ✅ 19:20 Ministração 1      ⏳ 20:15 Ministração 3             │
│                             ⏳ 21:00 Encerramento              │
└─────────────────────────────────────────────────────────────────┘
```

### **🎵 Sistema de Automix Avançado**
```javascript
// Configuração de Automix
const automixConfig = {
    crossfadeDuration: 3, // segundos
    pauseBetweenTracks: 0,
    volumeNormalization: true,
    silenceDetection: true,
    autoAdvanceSlides: false,
    
    // Loops
    trackLoop: {
        enabled: false,
        count: 1, // infinito = -1
        currentLoop: 0
    },
    
    playlistLoop: {
        enabled: true,
        count: -1, // infinito
        currentCycle: 1
    }
};

// Função de automix
function startAutomix(playlistId) {
    const playlist = getPlaylist(playlistId);
    let currentTrackIndex = 0;
    
    function playNextTrack() {
        if (currentTrackIndex >= playlist.length) {
            // Fim da playlist
            handlePlaylistEnd();
            return;
        }
        
        const track = playlist[currentTrackIndex];
        const nextTrack = playlist[currentTrackIndex + 1];
        
        // Carregar música atual no deck ativo
        loadTrackToDeck(track, getActiveDeck());
        
        // Programar crossfade para o próximo
        if (nextTrack) {
            const crossfadeTime = track.duration - automixConfig.crossfadeDuration;
            setTimeout(() => {
                startCrossfade(getActiveDeck(), getInactiveDeck());
                loadTrackToDeck(nextTrack, getInactiveDeck());
            }, crossfadeTime * 1000);
        }
        
        // Auto-advance slide (se configurado)
        if (automixConfig.autoAdvanceSlides && track.slideNumber) {
            goToSlide(track.slideNumber);
        }
        
        currentTrackIndex++;
    }
    
    function handlePlaylistEnd() {
        if (automixConfig.playlistLoop.enabled) {
            if (automixConfig.playlistLoop.count === -1 || 
                automixConfig.playlistLoop.currentCycle < automixConfig.playlistLoop.count) {
                // Reiniciar playlist
                automixConfig.playlistLoop.currentCycle++;
                currentTrackIndex = 0;
                playNextTrack();
            }
        }
    }
    
    // Iniciar primeira música
    playNextTrack();
}
```

---

## 🎯 **Casos de Uso Reais**

### **📋 Cenário: Face de Casais**
1. **Preparação**: 
   - Admin cadastra encontro "Face de Casais - Comunicação no Casamento"
   - Define 3 ministrações com textos base
   - Equipe de som cria playlists por ministração

2. **Durante o Evento**:
   - Automix toca música de background (loop infinito)
   - PowerPoint sincronizado com cronograma
   - Texto base aparece automaticamente no slide
   - Timer controla duração de cada parte
   - Crossfade suave entre momentos

3. **Controle Técnico**:
   - Equipe de som monitora automix
   - Equipe de projeção controla slides
   - Override manual disponível nos 4 decks
   - Tudo salvo automaticamente

### **🎵 Cenário: Ministração "Perdão"**
- **Texto Base**: "Efésios 4:32" (configurado pelo líder)
- **Playlist**: 4 músicas sobre perdão (loop 2x cada)
- **Slides**: 15 slides com pontos da ministração
- **Timing**: 25min explicativa + 15min prática
- **Automix**: Música suave durante parte prática

---

**Status**: ✅ **Sistema Completo Especificado** - Automix, PowerPoint, Gestão de Ministrações
