# Sistema de Gestão - Face a Face com Deus

## 📋 **Informações do Projeto**

### **🏛️ Contexto Organizacional*---

## 🔧 **Requisitos Técnicos para Implementação**

### **📁 Funcionalidades de Upload**
- **Formatos de Áudio**: MP3, WAV, FLAC, AAC
- **Formatos de Vídeo**: MP4, AVI, MOV, WMV
- **Tamanho Máximo**: A definir (sugestão: 100MB por arquivo)
- **Storage**: Sistema de pastas organizadas por evento/tipo

### **🎛️ Player Técnico**
- **HTML5 Audio/Video**: Base para reprodução
- **Web Audio API**: Para controles avançados de fader
- **JavaScript**: Controle de crossfade e transições
- **Interface Responsiva**: Funcionamento em tablets/dispositivos móveis

### **📊 Estrutura de Dados**
```sql
- Tabela: eventos (tipos de face a face)
- Tabela: midias (arquivos de áudio/vídeo)  
- Tabela: playlists (organização por evento)
- Tabela: configuracoes (settings do player)
```

## 📝 **Próximos Passos**
- ✅ **Definido**: Sistema de mídia com player e fader
- ✅ **Confirmado**: Upload de áudios e vídeos
- ✅ **Especificado**: Eliminação de quebras bruscas
- ⏳ **Aguardando**: Aprovação para iniciar desenvolvimento

---

**Status**: 🎵 **Funcionalidade Principal Definida** - Sistema de Mídia Profissionalcal**: Igreja em Marília/SP
- **Responsável**: Área de Som e Imagem
- **Tipo de Eventos**: Face a Face com Deus
- **Características**: Eventos segmentados por público-alvo específico

---

## 🎭 **Tipos de Eventos Identificados**

### **1. 👨 Face de Homens**
- **Público**: Exclusivamente homens
- **Restrições**: Acesso totalmente restrito ao gênero masculino

### **2. 👩 Face de Mulheres** 
- **Público**: Exclusivamente mulheres
- **Exceção**: Apenas 3 homens permitidos (segurança da fazenda e das participantes)
- **Restrições**: Acesso controlado com exceções específicas de segurança

### **3. 👫 Face de Casais**
- **Público**: Obrigatoriamente casais completos
- **Ministração**: Realizada exclusivamente por casais
- **Regra Rígida**: Se um cônjuge não pode participar, o outro também não pode
- **Restrições**: Participação conjunta obrigatória

### **4. 👶 Face de Crianças**
- **Público**: Ambos os sexos (crianças)
- **Estrutura**: Eventos simultâneos em auditórios separados por gênero
- **Características**: Segregação física mesmo sendo o mesmo tipo de evento

### **5. 👦👧 Face de Moças e Rapazes**
- **Público**: Jovens com limite de idade específico
- **Estrutura**: Auditórios separados por gênero
- **Características**: Eventos simultâneos com segregação física

---

## 🎵 **Área de Responsabilidade**
- **Som**: Equipamentos de áudio, mixagem, microfones
- **Imagem**: Projeção, telões, transmissão, gravação
- **Coordenação**: Gestão técnica dos eventos

---

## 🎵 **Funcionalidade Principal Definida**

### **🎯 Sistema de Mídia para Ministrações**

#### **� Upload de Conteúdo**
- **Áudios**: Upload de arquivos de música para as ministrações
- **Vídeos**: Upload de vídeos para projeção durante eventos
- **Organização**: Sistema de categorização por tipo de evento

#### **🎛️ Player Profissional com 4 Decks (Áudio + Vídeo)**
- **4 Decks Híbridos**: Áudio E vídeo nos mesmos slots
- **Drag & Drop Universal**: Arraste qualquer mídia para qualquer deck
- **Crossfaders Duplos**: A↔B e C↔D para transições profissionais
- **Controles de Vídeo**: Tela cheia rápida com botão [⛶]
- **Modo Áudio Only**: Clique no preview para ocultar vídeo

#### **🎵 Sistema de Automix e Playlists**
- **Criação de Playlists**: Listas organizadas por encontro/ministração
- **Automix**: Reprodução automática em sequência com crossfade
- **Loop de Música**: Repetir música atual X vezes ou infinito
- **Loop de Playlist**: Repetir lista completa X vezes ou infinito
- **Controle de Timing**: Definir duração de cada transição
- **Pausa Automática**: Entre ministrações ou partes

#### **📊 Integração PowerPoint**
- **Controle de Slides**: Avançar/voltar slides remotamente
- **Sincronização**: Música + slide coordenados
- **Projeção Dual**: Slide principal + vídeo secundário
- **Templates**: Modelos padrão para cada tipo de Face
- **Texto Base**: Exibição automática do versículo da ministração

#### **📋 Gestão de Ministrações**
- **Cadastro de Encontros**: Data, tipo, tema, responsáveis
- **Ministrações por Encontro**: Ordem, duração, responsável
- **Textos Base**: Versículos/passagens por ministração  
- **Cronograma**: Timeline do evento completo
- **Recursos Necessários**: Lista de mídias por ministração

#### **🎼 Controles Avançados**
- **Crossfade**: Transição gradual entre faixas
- **Loop**: Repetição contínua quando necessário
- **Volume Individual**: Controle independente por mídia
- **Pré-escuta**: Preview antes da execução
- **Sincronização**: Coordenação entre áudio e vídeo

### **💡 Objetivo Principal**
**Sistema completo para eventos "Face a Face com Deus" incluindo controle de mídia profissional, automix, integração PowerPoint e gestão de ministrações.**

### **🎭 Estrutura dos Eventos**
#### **📅 Encontros Face a Face**
- **Face de Homens**: Exclusivamente homens
- **Face de Mulheres**: Exclusivamente mulheres + 3 seguranças  
- **Face de Casais**: Casais completos obrigatoriamente
- **Face de Crianças**: Gêneros separados, eventos simultâneos
- **Face de Jovens**: Moças e rapazes com limite de idade

#### **🎤 Estrutura das Ministrações**
- **Parte Explicativa**: Conteúdo teórico/doutrinário
- **Parte Prática**: Aplicação/exercícios/dinâmicas  
- **Texto Base**: Versículo/passagem bíblica de referência
- **Tempo Controlado**: Duração definida por ministração
- **Sequência Ordenada**: Ordem específica no encontro

#### **👥 Equipes Envolvidas**
- **Som e Imagem**: Controle técnico (você)
- **Equipe de Projeção**: Slides e apresentações
- **Administradores**: Configuração dos encontros
- **Líderes**: Definição de conteúdos e textos base

---

## �️ **Especificações Técnicas**

### **📂 Estrutura no Servidor**
- **Pasta do Sistema**: `/f2f/` (Face to Face)
- **Localização**: `http://172.16.1.125/f2f/`
- **URL Externa**: Acessível pela rede local

### **💾 Instalação e Configuração**
- **Arquivo de Instalação**: Sistema com interface visual
- **Configuração de Banco**: Processo interativo e visual
- **Progresso Detalhado**: Cada etapa da criação do banco informada ao usuário
- **Feedback por Tabela**: Sistema informa ao usuário a criação de CADA tabela individualmente

### **🎯 Características do Instalador**
- ✅ **Interface Visual**: Setup com progresso visual
- ✅ **Informações Detalhadas**: Cada tabela criada é reportada
- ✅ **Controle de Progresso**: Usuário acompanha cada etapa
- ✅ **Configuração Guiada**: Process step-by-step
- ✅ **Validação**: Verificação de cada componente instalado

### **🗂️ Estrutura de Pastas Prevista**
```
/f2f/
├── install/           # Sistema de instalação
├── config/           # Arquivos de configuração
├── database/         # Scripts SQL e migração
├── assets/           # CSS, JS, imagens
├── includes/         # Arquivos PHP incluídos
├── modules/          # Módulos do sistema
└── index.php         # Arquivo principal
```

---

## �📝 **Próximos Passos**
- ⏳ **Aguardando**: Detalhamento das funcionalidades desejadas
- ⏳ **Pendente**: Definição dos requisitos funcionais
- ⏳ **Em espera**: Aprovação para iniciar estruturação do repositório
- ✅ **Definido**: Pasta `/f2f/` e sistema de instalação visual

---

**Status**: 📋 **Documentando Requisitos** - Especificações técnicas adicionadas
