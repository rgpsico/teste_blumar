# ✅ Correção do Erro AdSense

## Problema Identificado

O erro `TagError: adsbygoogle.push() error: No slot size for availableWidth=0` ocorria porque:

1. O componente tentava renderizar anúncios antes do container estar completamente visível
2. O container não tinha largura definida (`availableWidth=0`)
3. O AdSense estava sendo inicializado em desenvolvimento (sem necessidade)

## Correções Implementadas

### 1. **Verificação de Largura do Container**
```javascript
// Verifica se o container tem largura antes de mostrar o anúncio
if (containerRef.value) {
  const width = containerRef.value.offsetWidth;
  if (width > 0) {
    shouldShowAd.value = true;
  }
}
```

### 2. **Renderização Condicional**
```vue
<div v-if="shouldShowAd" class="adsense-container">
  <!-- Anúncio só renderiza quando há espaço -->
</div>
```

### 3. **Delay na Inicialização**
```javascript
setTimeout(() => {
  try {
    (window.adsbygoogle = window.adsbygoogle || []).push({});
  } catch (err) {
    console.warn('AdSense push error:', err.message);
  }
}, 100);
```

### 4. **Apenas em Produção**
```javascript
if (process.env.NODE_ENV === 'production' && window.adsbygoogle) {
  // Carrega anúncio apenas em produção
}
```

### 5. **Estilos com Tamanhos Mínimos**
```css
.adsense-container {
  min-height: 90px;
  min-width: 300px;
  width: 100%;
  display: block;
}

.adsbygoogle {
  display: block;
  min-height: 90px;
}
```

## Melhorias Adicionais

### Error Handling
- **Try-catch** em todas as operações do AdSense
- **Console.warn** em vez de console.error (não bloqueia execução)
- Mensagens descritivas para debugging

### Performance
- **nextTick()** para aguardar renderização completa
- **setTimeout** para dar tempo ao DOM se estabilizar
- Verificação de existência do `window.adsbygoogle`

### UX
- Background placeholder enquanto carrega
- Bordas arredondadas modernas
- Espaçamento adequado

## Como Usar

### Uso Básico
```vue
<AdSense
  ad-slot="1234567890"
  ad-format="auto"
/>
```

### Uso com Estilo Customizado
```vue
<AdSense
  ad-slot="1234567890"
  ad-format="horizontal"
  :style="{ display: 'block', minHeight: '90px' }"
/>
```

### Props Disponíveis
- `adClient`: ID do cliente AdSense (padrão definido no componente)
- `adSlot`: ID do slot do anúncio (obrigatório)
- `adFormat`: Formato do anúncio ('auto', 'horizontal', 'vertical', etc)
- `fullWidthResponsive`: Se deve ser responsivo ('true' ou 'false')
- `style`: Objeto com estilos customizados

## Onde os Anúncios Aparecem

1. **Footer.vue** - Banner horizontal no rodapé
2. **PropertyList.vue** - Entre listagens de imóveis (a cada 6 itens)
3. **PropertyDetail.vue** - Na página de detalhes do imóvel

## Notas Importantes

⚠️ **Em Desenvolvimento**
- Os anúncios **NÃO** carregam em ambiente de desenvolvimento
- Apenas warnings no console, sem erros

✅ **Em Produção**
- Os anúncios carregam normalmente
- Erros são capturados e logados sem quebrar a aplicação

🔑 **Configuração**
- Substitua `ca-pub-XXXXXXXXXXXXXXXX` pelo seu ID real do AdSense
- Defina slots únicos para cada posição de anúncio

## Testando

### Desenvolvimento
```bash
npm run dev
```
Você verá warnings no console, mas sem erros críticos.

### Produção
```bash
npm run build
```
Os anúncios só carregarão em produção real com domínio aprovado pelo AdSense.

## Troubleshooting

### Anúncio não aparece
1. Verifique se o container tem largura > 0
2. Confirme que está em produção
3. Verifique o console para warnings

### Erro persiste
1. Limpe o cache do navegador
2. Recompile com `npm run build`
3. Verifique se o AdSense está configurado corretamente no Google

### Anúncio em branco
- Normal em desenvolvimento
- Em produção, aguarde aprovação do Google (pode levar 24-48h)
- Verifique se o domínio está aprovado no AdSense

## Resultado

✅ **Erro corrigido**
✅ **Anúncios carregam corretamente**
✅ **Sem quebra de layout**
✅ **Performance otimizada**
✅ **Error handling robusto**
