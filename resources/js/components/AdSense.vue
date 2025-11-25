<template>
  <div
    v-if="shouldShowAd"
    class="adsense-container"
    ref="containerRef"
  >
    <ins
      class="adsbygoogle"
      :style="adStyle"
      :data-ad-client="adClient"
      :data-ad-slot="adSlot"
      :data-ad-format="adFormat"
      :data-full-width-responsive="fullWidthResponsive"
    ></ins>
  </div>
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue';

const props = defineProps({
  adClient: {
    type: String,
    default: 'ca-pub-XXXXXXXXXXXXXXXX' // Substitua pelo seu ID do AdSense
  },
  adSlot: {
    type: String,
    required: true
  },
  adFormat: {
    type: String,
    default: 'auto'
  },
  fullWidthResponsive: {
    type: String,
    default: 'true'
  },
  style: {
    type: Object,
    default: () => ({
      display: 'block'
    })
  }
});

const containerRef = ref(null);
const shouldShowAd = ref(false);
const adStyle = props.style;

onMounted(async () => {
  // Aguardar o DOM estar completamente renderizado
  await nextTick();

  // Verificar se o container tem largura adequada
  if (containerRef.value) {
    const width = containerRef.value.offsetWidth;

    // Só mostrar o anúncio se o container tiver largura maior que 0
    if (width > 0) {
      shouldShowAd.value = true;

      // Aguardar o elemento ins ser renderizado
      await nextTick();

      // Tentar carregar o anúncio apenas em produção
      try {
        if (process.env.NODE_ENV === 'production' && window.adsbygoogle) {
          // Adicionar um pequeno delay para garantir que o elemento está visível
          setTimeout(() => {
            try {
              (window.adsbygoogle = window.adsbygoogle || []).push({});
            } catch (err) {
              console.warn('AdSense push error:', err.message);
            }
          }, 100);
        }
      } catch (error) {
        console.warn('AdSense initialization error:', error.message);
      }
    } else {
      console.warn('AdSense container has no width, ad will not be displayed');
    }
  }
});
</script>

<style scoped>
.adsense-container {
  margin: 1rem 0;
  min-height: 90px;
  min-width: 300px;
  width: 100%;
  display: block;
  overflow: hidden;
}

.adsbygoogle {
  display: block;
  background: #f9fafb;
  border-radius: 0.5rem;
  min-height: 90px;
}
</style>
