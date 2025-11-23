<template>
  <div class="fixed bottom-6 right-6 z-50 flex flex-col items-end">
    
    <transition name="fade">
      <div v-if="isOpen" class="bg-white w-80 md:w-96 rounded-2xl shadow-2xl overflow-hidden border border-gray-100 mb-4 flex flex-col max-h-[500px]">
        
        <div class="bg-blue-600 p-4 text-white flex justify-between items-center">
          <h3 class="font-bold flex items-center gap-2">
            <span class="text-xl">🤖</span> Assistente Virtual
          </h3>
          <button @click="isOpen = false" class="hover:text-gray-200">✕</button>
        </div>

        <div class="flex-1 p-4 overflow-y-auto bg-gray-50 space-y-3" ref="messagesContainer">
          
          <div class="bg-blue-100 text-blue-800 p-3 rounded-br-xl rounded-bl-xl rounded-tr-xl self-start text-sm max-w-[90%]">
            Olá! Como posso ajudar você com este imóvel hoje?
          </div>

          <div v-for="(msg, index) in messages" :key="index" 
               :class="['p-3 rounded-xl text-sm max-w-[90%]', 
               msg.isUser ? 'bg-blue-600 text-white self-end ml-auto rounded-tr-none' : 'bg-white border text-gray-700 self-start rounded-tl-none']">
            {{ msg.text }}
          </div>

          <div v-if="loading" class="text-xs text-gray-500 italic ml-2">Digitando...</div>
        </div>

        <div class="p-3 bg-white border-t">
          
          <div v-if="!authStore.user" class="space-y-2">
            <p class="text-xs text-center text-gray-500 mb-2">Faça login para perguntar qualquer coisa.</p>
            <div class="flex flex-wrap gap-2 justify-center">
              <button v-for="faq in faqs" :key="faq.id" 
                      @click="selectFaq(faq)"
                      class="bg-blue-50 text-blue-700 text-xs px-3 py-2 rounded-full hover:bg-blue-100 transition border border-blue-200">
                {{ faq.question }}
              </button>
              <span v-if="faqs.length === 0" class="text-xs text-gray-400">Sem perguntas cadastradas.</span>
            </div>
             <router-link to="/login" class="block text-center text-xs text-blue-600 font-bold mt-2 hover:underline">Entrar para conversar com IA</router-link>
          </div>

          <div v-else class="flex gap-2">
            <input v-model="userInput" 
                   @keyup.enter="sendMessage"
                   type="text" 
                   placeholder="Pergunte sobre o imóvel..." 
                   class="flex-1 bg-gray-100 rounded-full px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                   :disabled="loading">
            <button @click="sendMessage" 
                    :disabled="loading || !userInput.trim()"
                    class="bg-blue-600 text-white p-2 rounded-full hover:bg-blue-700 disabled:opacity-50 transition">
              ➤
            </button>
          </div>
        </div>
      </div>
    </transition>

    <button @click="isOpen = !isOpen" 
            class="bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-full shadow-lg transition transform hover:scale-105 flex items-center justify-center">
      <span v-if="!isOpen" class="text-2xl">💬</span>
      <span v-else class="text-xl">▼</span>
    </button>
  </div>
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue';
import { useAuthStore } from '../stores/auth'; // Caminho baseado na sua estrutura
import axios from 'axios';

const props = defineProps(['propertyId']);
const authStore = useAuthStore();

const isOpen = ref(false);
const loading = ref(false);
const faqs = ref([]);
const userInput = ref('');
const messages = ref([]);
const messagesContainer = ref(null);

// Carregar FAQs ao montar
onMounted(async () => {
  try {
    const response = await axios.get(`/api/properties/${props.propertyId}/faqs`);
    faqs.value = response.data;
  } catch (error) {
    console.error("Erro ao carregar FAQs", error);
  }
});

// Usuário escolhe uma FAQ (Sem Login)
const selectFaq = (faq) => {
  messages.value.push({ text: faq.question, isUser: true });
  setTimeout(() => {
    messages.value.push({ text: faq.answer, isUser: false });
    scrollToBottom();
  }, 500);
};

// Usuário envia pergunta para IA (Logado)
const sendMessage = async () => {
  if (!userInput.value.trim()) return;

  const text = userInput.value;
  userInput.value = '';
  messages.value.push({ text: text, isUser: true });
  loading.value = true;
  scrollToBottom();

  try {
    const response = await axios.post(`/api/properties/${props.propertyId}/chat`, {
      message: text
    });
    
    messages.value.push({ text: response.data.reply, isUser: false });
  } catch (error) {
    messages.value.push({ text: "Desculpe, tive um problema ao processar sua pergunta.", isUser: false });
  } finally {
    loading.value = false;
    scrollToBottom();
  }
};

const scrollToBottom = () => {
  nextTick(() => {
    if (messagesContainer.value) {
      messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
    }
  });
};
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s, transform 0.3s; }
.fade-enter-from, .fade-leave-to { opacity: 0; transform: translateY(20px); }
</style>