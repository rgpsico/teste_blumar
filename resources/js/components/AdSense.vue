<template>
  <div class="adsense-container">
    <ins class="adsbygoogle"
         :style="adStyle"
         :data-ad-client="adClient"
         :data-ad-slot="adSlot"
         :data-ad-format="adFormat"
         :data-full-width-responsive="fullWidthResponsive"></ins>
  </div>
</template>

<script setup>
import { onMounted } from 'vue';

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

const adStyle = props.style;

onMounted(() => {
  try {
    if (window.adsbygoogle && process.env.NODE_ENV === 'production') {
      (window.adsbygoogle = window.adsbygoogle || []).push({});
    }
  } catch (error) {
    console.error('AdSense error:', error);
  }
});
</script>

<style scoped>
.adsense-container {
  margin: 1rem 0;
  min-height: 50px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.adsbygoogle {
  background: #f9fafb;
  border-radius: 0.5rem;
}
</style>
