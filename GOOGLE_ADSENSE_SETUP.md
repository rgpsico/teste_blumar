# Google AdSense - Guia de Configuração

Este guia irá ajudá-lo a configurar o Google AdSense em sua plataforma de imóveis.

## 📋 Visão Geral

O Google AdSense foi implementado estrategicamente em 3 locais principais:

1. **Lista de Imóveis** (`PropertyList.vue`) - Banner entre os cards de imóveis (a cada 6 imóveis)
2. **Detalhes do Imóvel** (`PropertyDetail.vue`) - Banner na sidebar direita
3. **Rodapé** (`Footer.vue`) - Banner horizontal no topo do rodapé (todas as páginas públicas)

## 🚀 Passo a Passo para Ativar o AdSense

### 1. Criar Conta no Google AdSense

1. Acesse: https://www.google.com/adsense
2. Clique em "Começar"
3. Faça login com sua conta Google
4. Preencha os dados do seu site e informações pessoais
5. Aceite os termos e condições

### 2. Adicionar Seu Site ao AdSense

1. No painel do AdSense, vá em "Sites"
2. Clique em "Adicionar site"
3. Digite o domínio do seu site (ex: `www.imoveispro.com.br`)
4. Aguarde a aprovação (pode levar de 1 a 7 dias)

### 3. Obter Seu ID de Publicador (Publisher ID)

Após aprovação, você receberá um ID de publicador no formato: `ca-pub-XXXXXXXXXXXXXXXX`

### 4. Configurar o ID no Código

Substitua o ID placeholder nos seguintes arquivos:

#### Arquivo: `resources/views/app.blade.php` (linha 9)
```html
<!-- Substitua ca-pub-XXXXXXXXXXXXXXXX pelo seu ID real -->
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-XXXXXXXXXXXXXXXX"
     crossorigin="anonymous"></script>
```

#### Arquivo: `resources/js/components/AdSense.vue` (linha 9)
```javascript
adClient: {
  type: String,
  default: 'ca-pub-XXXXXXXXXXXXXXXX' // Substitua pelo seu ID
}
```

### 5. Criar Blocos de Anúncios

No painel do AdSense:

1. Vá em "Anúncios" → "Por unidade de anúncio"
2. Crie 3 blocos de anúncios com os seguintes nomes:

#### Bloco 1: PropertyList - Between Cards
- **Tipo**: Display responsivo
- **Nome**: PropertyList Between Cards
- **Formato**: Retangular (336x280 recomendado)
- Copie o `data-ad-slot` gerado (ex: `1234567890`)

#### Bloco 2: PropertyDetail - Sidebar
- **Tipo**: Display responsivo
- **Nome**: PropertyDetail Sidebar
- **Formato**: Retangular médio (300x250 recomendado)
- Copie o `data-ad-slot` gerado (ex: `0987654321`)

#### Bloco 3: Footer - Banner
- **Tipo**: Display responsivo
- **Nome**: Footer Banner
- **Formato**: Banner horizontal (728x90 recomendado)
- Copie o `data-ad-slot` gerado (ex: `5555555555`)

### 6. Atualizar os Ad Slots no Código

#### Arquivo: `resources/js/views/Public/PropertyList.vue` (linha 366)
```vue
<AdSense
  ad-slot="1234567890"  <!-- Substitua pelo Ad Slot do Bloco 1 -->
  :style="{ display: 'block', minHeight: '90px' }"
/>
```

#### Arquivo: `resources/js/views/Public/PropertyDetail.vue` (linha 246)
```vue
<AdSense
  ad-slot="0987654321"  <!-- Substitua pelo Ad Slot do Bloco 2 -->
  ad-format="auto"
  :style="{ display: 'block', minHeight: '250px' }"
/>
```

#### Arquivo: `resources/js/components/Footer.vue` (linha 5)
```vue
<AdSense
  ad-slot="5555555555"  <!-- Substitua pelo Ad Slot do Bloco 3 -->
  ad-format="horizontal"
  :style="{ display: 'block', minHeight: '90px', maxHeight: '120px' }"
/>
```

## 🔧 Configurações Avançadas

### Ajustar Frequência de Anúncios

Para alterar a frequência dos anúncios na lista de imóveis:

**Arquivo**: `resources/js/views/Public/PropertyList.vue` (linha 363)

```vue
<!-- Alterar de 6 para outro número -->
<div v-if="(index + 1) % 6 === 0 && index !== filteredProperties.length - 1">
  <!-- Mudar % 6 para % 3 mostrará anúncios a cada 3 imóveis -->
  <!-- Mudar % 6 para % 9 mostrará anúncios a cada 9 imóveis -->
</div>
```

### Adicionar Mais Posições de Anúncios

Você pode adicionar o componente `<AdSense>` em qualquer página Vue:

```vue
<template>
  <div>
    <!-- Seu conteúdo -->

    <AdSense
      ad-slot="SEU_AD_SLOT_AQUI"
      ad-format="auto"
      :style="{ display: 'block', minHeight: '100px' }"
    />
  </div>
</template>

<script setup>
import AdSense from '@/components/AdSense.vue';
</script>
```

## 📊 Monitoramento e Otimização

### Verificar se os Anúncios Estão Funcionando

1. **Ambiente de Desenvolvimento**:
   - Os anúncios NÃO aparecerão em `localhost`
   - Você verá apenas espaços cinza de placeholder

2. **Ambiente de Produção**:
   - Faça deploy da aplicação
   - Aguarde 10-30 minutos para os anúncios aparecerem
   - Nunca clique nos próprios anúncios (pode banir sua conta)

### Acompanhar Desempenho

1. Acesse o painel do AdSense
2. Vá em "Relatórios"
3. Monitore métricas importantes:
   - **Impressões**: Quantas vezes os anúncios foram vistos
   - **Cliques**: Quantos cliques nos anúncios
   - **CTR** (Taxa de Cliques): Cliques ÷ Impressões
   - **RPM** (Receita por Mil): Quanto você ganha a cada 1000 visualizações
   - **Receita Total**: Quanto você ganhou

### Otimização de Receita

1. **A/B Testing**:
   - Teste diferentes posições de anúncios
   - Experimente formatos diferentes (banner, quadrado, vertical)

2. **Frequência Ideal**:
   - Se anúncios demais: usuários ficam incomodados
   - Se anúncios de menos: perde receita
   - Recomendado: 1 anúncio a cada 6-8 imóveis

3. **Análise de Páginas**:
   - Veja quais páginas geram mais receita
   - Otimize conteúdo das páginas de melhor desempenho

## 🎯 Estimativa de Receita

**Exemplo com 10.000 visualizações/mês**:

- CTR médio: 0.5% a 2%
- CPC (Custo por Clique) médio: R$ 0,20 a R$ 2,00
- RPM estimado: R$ 2,00 a R$ 10,00

**Fórmula**:
```
Receita Mensal = (Pageviews × RPM) ÷ 1000
```

Com 10.000 pageviews e RPM de R$ 5,00:
```
Receita = (10.000 × 5) ÷ 1000 = R$ 50,00/mês
```

**Para aumentar receita**:
1. Aumentar tráfego (SEO, marketing)
2. Melhorar CTR (posicionamento estratégico)
3. Conteúdo de qualidade (anúncios de maior valor)

## ⚠️ Políticas do AdSense (IMPORTANTE)

**NUNCA faça isso ou sua conta será banida**:

1. ❌ Clicar nos próprios anúncios
2. ❌ Pedir para outras pessoas clicarem
3. ❌ Usar bots ou tráfego falso
4. ❌ Colocar anúncios em conteúdo adulto/ilegal
5. ❌ Modificar o código JavaScript do AdSense

**Práticas Recomendadas**:

1. ✅ Criar conteúdo original e de qualidade
2. ✅ Tráfego orgânico real
3. ✅ Seguir as diretrizes de conteúdo
4. ✅ Respeitar a privacidade dos usuários

## 🆘 Solução de Problemas

### Anúncios não aparecem

**Possíveis causas**:

1. **Site não aprovado**:
   - Verifique status no painel do AdSense
   - Aguarde aprovação (pode levar até 7 dias)

2. **Ad Blocker ativo**:
   - Desative extensões de bloqueio de anúncios
   - Teste em navegador anônimo

3. **IDs incorretos**:
   - Verifique se o Publisher ID está correto
   - Verifique se os Ad Slots estão corretos

4. **Ambiente de desenvolvimento**:
   - AdSense só funciona em domínios aprovados
   - Faça deploy em produção para testar

### Receita muito baixa

**Soluções**:

1. Aumentar tráfego qualificado
2. Melhorar SEO do site
3. Criar conteúdo relevante
4. Testar diferentes posições de anúncios
5. Usar formatos de anúncios de maior performance

## 📚 Recursos Úteis

- [Central de Ajuda do AdSense](https://support.google.com/adsense)
- [Políticas do Programa](https://support.google.com/adsense/answer/48182)
- [Otimização de Receita](https://support.google.com/adsense/answer/9183460)
- [Formatos de Anúncios](https://support.google.com/adsense/answer/9183363)

## 🔄 Próximos Passos

1. ✅ Criar conta no Google AdSense
2. ✅ Configurar Publisher ID e Ad Slots
3. ✅ Fazer deploy em produção
4. 📊 Monitorar desempenho por 30 dias
5. 🎯 Otimizar baseado em dados
6. 💰 Configurar pagamento no AdSense

---

**Lembre-se**: A receita do AdSense depende de **tráfego de qualidade** e **engajamento dos usuários**. Foque primeiro em criar uma ótima plataforma, e a monetização virá naturalmente!
