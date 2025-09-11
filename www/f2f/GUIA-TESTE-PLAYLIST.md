# 🎵 Guia de Teste - Sistema de Inclusão de Músicas

## ✅ Como Testar o Sistema de Playlist

### 1. **Abrir o Player**
- Acesse: `http://localhost/f2f/player-4-decks.html`
- Verifique se a página carrega completamente
- Procure pela seção "Playlist AutoMix"

### 2. **Teste Básico - Adicionar Música Manual**
```
1. Clique em "Adicionar Manual"
2. Digite um nome de música (ex: "Amazing Grace")
3. Digite um artista (ex: "Traditional")
4. Digite um gênero (ex: "Hino")
5. Verifique se a música aparece na lista
```

### 3. **Teste de Upload Múltiplo**
```
1. Clique em "Upload Múltiplo"
2. Selecione um ou mais arquivos de áudio (MP3, WAV, etc.)
3. Nomeie os arquivos como: "Artista - Título.mp3" para detecção automática
4. Verifique se as músicas aparecem na lista com ícone verde (✅)
```

### 4. **Teste de Lista de Exemplo**
```
1. Com playlist vazia, clique em "Carregar Lista de Exemplo"
2. Confirme no popup
3. Verifique se 5 músicas de exemplo aparecem
4. Use os botões de upload (📤) para adicionar arquivos às músicas
```

### 5. **Debug Console (F12)**
```
1. Pressione F12 para abrir Developer Tools
2. Vá na aba "Console"
3. Digite: testPlaylistSystem()
4. Verifique se uma música de teste é adicionada
5. Observe os logs no console
```

## 🔧 Funcionalidades que Devem Funcionar

### ✅ **Upload Múltiplo**
- Seleção de múltiplos arquivos de áudio
- Detecção automática de título/artista do nome do arquivo
- Exibição imediata na playlist
- Cálculo automático da duração

### ✅ **Gerenciamento da Playlist**
- Adicionar músicas manualmente
- Remover músicas individuais
- Limpar playlist completa
- Contador visual de músicas carregadas

### ✅ **Interface Visual**
- Lista vazia mostra opções de início
- Contador de músicas (vazia/parcial/completa)
- Ícones de status (⚪ não carregado, ✅ carregado)
- Botões de ação para cada música

### ✅ **AutoMix**
- Configuração de intervalos
- Modos: sequencial, aleatório, loop
- Status em tempo real
- Integração com os 4 decks

## 🐛 Como Reportar Problemas

Se algo não funcionar:

### 1. **Verificar Console**
```
F12 → Console → procurar por erros em vermelho
```

### 2. **Testar Função Debug**
```
No console digite: testPlaylistSystem()
```

### 3. **Verificar Arquivos**
```
- Use apenas MP3 ou WAV
- Tamanho menor que 50MB por arquivo
- Nome sem caracteres especiais
```

### 4. **Informações para Debug**
```
- Qual botão não funciona?
- Mensagens de erro no console?
- Tipo e tamanho dos arquivos testados?
- Sistema operacional e navegador?
```

## 📁 Arquivos de Teste Recomendados

### **Nomes de Arquivo Ideais:**
```
✅ "Fernandinho - Tua Graça me Basta.mp3"
✅ "Hillsong - Oceanos.wav"
✅ "Aline Barros - Ressuscita-me.mp3"

❌ "música1.mp3" (sem informações)
❌ "Fernandinho_Tua_Graça.mp3" (use hífen, não underscore)
```

### **Formatos Suportados:**
- ✅ MP3 (recomendado)
- ✅ WAV (alta qualidade)
- ✅ OGG (alternativo)
- ⚠️ AAC (pode ter problemas)
- ❌ FLAC (muito pesado)

## 🎯 Resultado Esperado

Após os testes, você deve conseguir:
1. ✅ Adicionar músicas à playlist
2. ✅ Ver contador atualizado
3. ✅ Carregar arquivos de áudio
4. ✅ Usar AutoMix com suas músicas
5. ✅ Gerenciar playlist completa

---
**💡 Dica:** Use o arquivo `test-playlist.html` para testar funcionalidades básicas se o principal não funcionar.
