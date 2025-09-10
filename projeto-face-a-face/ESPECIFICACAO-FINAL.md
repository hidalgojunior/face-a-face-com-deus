# 🎯 **SISTEMA FACE A FACE COM DEUS - ESPECIFICAÇÃO FINAL**

## 📋 **Resumo Executivo do Projeto**

### **🏛️ Contexto**
Sistema completo para gestão técnica dos eventos "Face a Face com Deus" da igreja em Marília, focado nas necessidades da equipe de Som e Imagem e Projeção.

### **👥 Usuários do Sistema**
- **Administradores**: Cadastro de encontros e configurações gerais
- **Líderes**: Definição de ministrações e textos base
- **Equipe de Som**: Controle de áudio/vídeo e automix (você)
- **Equipe de Projeção**: Controle de slides e apresentações

---

## 🎛️ **Módulos do Sistema**

### **1. 🎵 Player Profissional de 4 Decks**
- ✅ **Decks A, B, C, D**: Áudio e vídeo nos mesmos slots
- ✅ **Drag & Drop**: Arrastar mídia da biblioteca para qualquer deck
- ✅ **Crossfaders**: A↔B e C↔D para transições profissionais
- ✅ **Controles de Vídeo**: Tela cheia [⛶] e modo áudio-only [👁]
- ✅ **Preview Individual**: Pré-escuta por deck [🎧]

### **2. 🎵 Sistema de Automix**
- ✅ **Playlists Personalizadas**: Por encontro, ministração ou momento
- ✅ **Reprodução Automática**: Sequência com crossfade automático
- ✅ **Loop de Música**: Repetir música X vezes ou infinito
- ✅ **Loop de Playlist**: Repetir lista X vezes ou infinito
- ✅ **Controle de Timing**: Crossfade configurável (2-10s)
- ✅ **Override Manual**: 4 decks sempre disponíveis para intervenção

### **3. 📊 Integração PowerPoint**
- ✅ **Controle Remoto**: Avançar/voltar slides via sistema
- ✅ **Sincronização**: Slide muda com nova música (opcional)
- ✅ **Texto Base**: Exibição automática do versículo da ministração
- ✅ **Templates**: Modelos padrão para cada tipo de Face
- ✅ **Projeção Dual**: Slide + vídeo simultâneos

### **4. 📋 Gestão de Ministrações**
- ✅ **Cadastro de Encontros**: Por tipo de Face (homens, mulheres, etc.)
- ✅ **Ministrações**: Ordem, duração, responsável, texto base
- ✅ **Cronograma**: Timeline completo do evento
- ✅ **Textos Base**: Versículos obrigatórios por ministração
- ✅ **Controle de Tempo**: Timer para parte explicativa e prática

### **5. 📤 Sistema de Upload**
- ✅ **Áudio**: MP3, WAV, FLAC, AAC, OGG
- ✅ **Vídeo**: MP4, AVI, MOV, WMV, WebM
- ✅ **Organização**: Por tipo de evento Face a Face
- ✅ **Otimização**: Compressão automática para web
- ✅ **Biblioteca**: Interface drag & drop para os decks

---

## 🗄️ **Estrutura do Banco (8 Tabelas)**

```sql
1. eventos          -- Tipos de Face a Face
2. midias           -- Arquivos de áudio/vídeo  
3. playlists        -- Listas de músicas
4. playlist_itens   -- Músicas de cada playlist (com loops individuais)
5. deck_states      -- Estado atual dos 4 decks
6. encontros        -- Eventos Face a Face específicos
7. ministracoes     -- Ministrações de cada encontro (com texto base)
8. automix_config   -- Configurações do automix por playlist
9. configuracoes    -- Settings gerais do sistema
```

---

## 🎯 **Fluxo de Uso Real**

### **📅 Preparação do Encontro**
1. **Admin** cadastra "Face de Homens - 15/09/2025"
2. **Líder** define 4 ministrações com textos base
3. **Equipe de Som** cria playlists para cada ministração
4. **Equipe de Projeção** prepara slides PowerPoint

### **🎛️ Durante o Evento**
1. **Abertura**: Automix com playlist "Background Suave" (loop infinito)
2. **Ministração 1**: 
   - Timer inicia automaticamente (30min explicativa)
   - Slide mostra texto base "João 3:16"
   - Música de fundo continua em automix
   - Crossfade automático entre faixas
3. **Parte Prática**: 
   - Timer muda para prática (20min)
   - Equipe pode usar decks manuais para dinâmicas
   - PowerPoint sincronizado com atividades
4. **Intervalo**: Automix playlist "Descontração" 
5. **Encerramento**: Crossfade para playlist "Reflexão Final"

### **🚨 Controle Manual**
- **Emergência**: 4 decks sempre disponíveis para override
- **Dinâmicas**: Arrastar música específica para deck livre
- **Tela Cheia**: Vídeo para projeção instantânea [⛶]
- **Áudio Only**: Ocultar vídeo mantendo som [👁]

---

## 🛠️ **Tecnologias Utilizadas**

### **Backend**
- **PHP 8.2**: Lógica do servidor e APIs
- **MySQL 8.0**: Banco de dados principal
- **FFmpeg**: Processamento de vídeo/áudio
- **File Upload**: Gerenciamento de mídias grandes

### **Frontend**
- **HTML5**: Video/Audio API + Drag & Drop
- **CSS3**: Interface responsiva e animações
- **JavaScript ES6+**: Controle dos players e automix
- **Bootstrap 5**: Framework UI
- **Sortable.js**: Drag & drop avançado

### **Player Engine**
- **Howler.js**: Controle avançado de áudio
- **Video.js**: Player de vídeo robusto
- **Fullscreen API**: Controle de tela cheia nativo
- **Web Audio API**: Crossfade profissional

### **Integração**
- **PowerPoint COM**: Controle remoto via JavaScript/PHP
- **Media Session API**: Controles de mídia do navegador
- **Local Storage**: Persistência de estados

---

## 📁 **Estrutura de Arquivos**

```
/f2f/
├── install/
│   ├── index.php              -- Instalador visual
│   ├── setup_database.php     -- Criação das tabelas
│   └── check_requirements.php -- Verificação de requisitos
│
├── assets/
│   ├── css/
│   │   ├── player.css         -- Estilos do player
│   │   ├── automix.css        -- Interface do automix
│   │   └── admin.css          -- Painel administrativo
│   ├── js/
│   │   ├── player.js          -- Controle dos 4 decks
│   │   ├── automix.js         -- Sistema de automix
│   │   ├── powerpoint.js      -- Integração PowerPoint
│   │   └── drag-drop.js       -- Drag & drop
│   └── uploads/
│       ├── audio/             -- Áudios por tipo de Face
│       └── video/             -- Vídeos por tipo de Face
│
├── modules/
│   ├── player/                -- Módulo do player principal
│   ├── automix/               -- Módulo do automix
│   ├── admin/                 -- Gestão de encontros/ministrações
│   ├── upload/                -- Sistema de upload
│   └── powerpoint/            -- Controle de slides
│
├── api/
│   ├── get_media.php          -- Buscar informações de mídia
│   ├── save_deck_state.php    -- Salvar estado dos decks
│   ├── automix_control.php    -- Controle do automix
│   └── powerpoint_control.php -- Controle do PowerPoint
│
├── config/
│   ├── database.php           -- Configurações do banco
│   ├── settings.php           -- Configurações gerais
│   └── automix_defaults.php   -- Padrões do automix
│
└── index.php                  -- Página principal do sistema
```

---

## ✅ **Cronograma de Desenvolvimento**

### **Fase 1: Base (Semana 1)**
- ✅ Instalador visual com criação das 8 tabelas
- ✅ Sistema de upload de áudio/vídeo
- ✅ Interface básica dos 4 decks

### **Fase 2: Player (Semana 2)**  
- ✅ Drag & drop da biblioteca para decks
- ✅ Controles de reprodução por deck
- ✅ Crossfaders A↔B e C↔D
- ✅ Tela cheia e modo áudio-only

### **Fase 3: Automix (Semana 3)**
- ✅ Sistema de playlists
- ✅ Reprodução automática com crossfade
- ✅ Loops individuais e de playlist
- ✅ Interface de controle do automix

### **Fase 4: Integração (Semana 4)**
- ✅ Controle remoto do PowerPoint
- ✅ Gestão de encontros e ministrações
- ✅ Textos base e cronograma
- ✅ Sincronização música + slides

### **Fase 5: Finalização (Semana 5)**
- ✅ Testes completos do sistema
- ✅ Documentação de usuário
- ✅ Deploy e treinamento da equipe

---

## 🎯 **Resultado Final**

**Sistema profissional completo para gestão técnica dos eventos Face a Face com Deus, incluindo:**

- 🎛️ **Mixer de 4 decks** para controle manual
- 🎵 **Automix inteligente** para reprodução automática  
- 📊 **Integração PowerPoint** para sincronização
- 📋 **Gestão completa** de encontros e ministrações
- ⏱️ **Controle de tempo** por parte da ministração
- 🎬 **Suporte híbrido** para áudio e vídeo
- 📱 **Interface responsiva** para tablets/dispositivos móveis

**Objetivo alcançado: Eliminar quebras bruscas e ter controle total sobre a experiência de mídia nos eventos Face a Face com Deus!** 🎉

---

**📋 Status**: ✅ **SISTEMA COMPLETAMENTE ESPECIFICADO** - Pronto para desenvolvimento completo
