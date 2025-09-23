<template>
    <Teleport to="body">
        <Transition name="fade">
            <div v-if="show" class="modal-overlay" @click.self="$emit('close')">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title">
                            {{ property ? "Editar Imóvel" : "Novo Imóvel" }}
                        </h2>
                        <button class="close-btn" @click="$emit('close')">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form @submit.prevent="saveProperty">
                            <h3 style="margin-bottom: 1rem; color: #667eea">
                                <i class="fas fa-info-circle"></i>
                                Informações Básicas
                            </h3>

                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">Título</label>
                                    <input
                                        type="text"
                                        class="form-input"
                                        v-model="form.titulo"
                                        required
                                        placeholder="Ex: Casa Pé na Areia"
                                    />
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Preço (R$)</label>
                                    <input
                                        type="number"
                                        class="form-input"
                                        v-model="form.preco"
                                        required
                                        step="0.01"
                                        placeholder="750000.00"
                                    />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Descrição</label>
                                <textarea
                                    class="form-input"
                                    v-model="form.descricao"
                                    required
                                    rows="3"
                                    placeholder="Descrição detalhada do imóvel..."
                                ></textarea>
                            </div>

                            <h3 style="margin: 2rem 0 1rem 0; color: #667eea">
                                <i class="fas fa-image"></i>
                                Fotos do Imóvel
                            </h3>

                            <div class="form-group">
                                <input
                                    type="file"
                                    class="form-input"
                                    accept="image/*"
                                    multiple
                                    ref="fileInput"
                                    @change="handleFiles"
                                />
                                <div
                                    class="preview-images"
                                    v-if="previewImages.length"
                                >
                                    <img
                                        v-for="(image, index) in previewImages"
                                        :key="index"
                                        :src="image"
                                        class="preview-thumb"
                                        alt="Preview"
                                    />
                                </div>
                            </div>

                            <h3 style="margin: 2rem 0 1rem 0; color: #667eea">
                                <i class="fas fa-map-marker-alt"></i>
                                Endereço
                            </h3>

                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">País</label>
                                    <input
                                        type="text"
                                        class="form-input"
                                        v-model="form.pais"
                                        required
                                        placeholder="Brasil"
                                    />
                                </div>

                                <div class="form-group">
                                    <label class="form-label">CEP</label>
                                    <input
                                        type="text"
                                        class="form-input"
                                        v-model="form.cep"
                                        required
                                        placeholder="22041001"
                                        @blur="searchCep"
                                    />
                                </div>
                            </div>

                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">Cidade</label>
                                    <input
                                        type="text"
                                        class="form-input"
                                        v-model="form.cidade"
                                        required
                                        placeholder="Rio de Janeiro"
                                    />
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Bairro</label>
                                    <input
                                        type="text"
                                        class="form-input"
                                        v-model="form.bairro"
                                        required
                                        placeholder="Copacabana"
                                    />
                                </div>
                            </div>

                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">Rua</label>
                                    <input
                                        type="text"
                                        class="form-input"
                                        v-model="form.rua"
                                        required
                                        placeholder="Rua das Flores"
                                    />
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Número</label>
                                    <input
                                        type="text"
                                        class="form-input"
                                        v-model="form.numero"
                                        required
                                        placeholder="123"
                                    />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Complemento</label>
                                <input
                                    type="text"
                                    class="form-input"
                                    v-model="form.complemento"
                                    placeholder="Apto 101, Bloco A"
                                />
                            </div>

                            <div class="form-actions">
                                <button
                                    type="button"
                                    class="btn btn-secondary"
                                    @click="$emit('close')"
                                >
                                    Cancelar
                                </button>
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                    :disabled="form.processing"
                                >
                                    <i class="fas fa-save"></i>
                                    {{ property ? "Atualizar" : "Criar" }}
                                    Imóvel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script>
import { ref, reactive, watch, nextTick } from "vue";
import { useForm } from "@inertiajs/vue3";
import axios from "axios";

export default {
    emits: ["close"],

    props: {
        show: {
            type: Boolean,
            default: false,
        },
        property: {
            type: Object,
            default: null,
        },
    },

    setup(props, { emit }) {
        const fileInput = ref(null);
        const previewImages = ref([]);

        const form = useForm({
            titulo: "",
            descricao: "",
            preco: "",
            pais: "Brasil",
            cep: "",
            rua: "",
            numero: "",
            bairro: "",
            cidade: "",
            complemento: "",
            fotos: [],
        });

        const resetForm = () => {
            form.reset();
            form.titulo = "";
            form.descricao = "";
            form.preco = "";
            form.pais = "Brasil";
            form.cep = "";
            form.rua = "";
            form.numero = "";
            form.bairro = "";
            form.cidade = "";
            form.complemento = "";
            form.fotos = [];
            previewImages.value = [];

            if (fileInput.value) {
                fileInput.value.value = "";
            }
        };

        const populateForm = (property) => {
            if (property) {
                form.titulo = property.titulo || "";
                form.descricao = property.descricao || "";
                form.preco = property.preco || "";
                form.pais = property.endereco?.pais || "Brasil";
                form.cep = property.endereco?.cep || "";
                form.rua = property.endereco?.rua || "";
                form.numero = property.endereco?.numero || "";
                form.bairro = property.endereco?.bairro || "";
                form.cidade = property.endereco?.cidade || "";
                form.complemento = property.endereco?.complemento || "";

                // Load existing photos as preview
                if (property.fotos && property.fotos.length > 0) {
                    previewImages.value = property.fotos.map(
                        (foto) => `/storage/${foto.path}`
                    );
                }
            }
        };

        const handleFiles = (event) => {
            const files = Array.from(event.target.files);
            form.fotos = files;

            // Create preview images
            previewImages.value = [];
            files.forEach((file) => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    previewImages.value.push(e.target.result);
                };
                reader.readAsDataURL(file);
            });
        };

        const searchCep = async () => {
            const cep = form.cep.replace(/\D/g, "");

            if (cep.length === 8) {
                try {
                    const response = await axios.get(`/cep/${cep}`);
                    const data = response.data;

                    if (!data.erro) {
                        form.rua = data.logradouro || "";
                        form.bairro = data.bairro || "";
                        form.cidade = data.localidade || "";
                        form.pais = "Brasil";
                    }
                } catch (error) {
                    console.error("Erro ao buscar CEP:", error);
                }
            }
        };

        const saveProperty = () => {
            const url = props.property
                ? `/imoveis/${props.property.id}`
                : "/imoveis";

            if (props.property) {
                // Para update, usar POST com _method
                form.transform((data) => ({
                    ...data,
                    _method: "PUT",
                })).post(url, {
                    onSuccess: () => {
                        emit("close");
                        resetForm();
                    },
                });
            } else {
                // Para create, usar POST normal
                form.post(url, {
                    onSuccess: () => {
                        emit("close");
                        resetForm();
                    },
                });
            }
        };

        // Watch for property changes
        watch(
            () => props.property,
            (newProperty) => {
                if (newProperty) {
                    populateForm(newProperty);
                } else {
                    resetForm();
                }
            },
            { immediate: true }
        );

        // Reset form when modal is closed
        watch(
            () => props.show,
            (newShow) => {
                if (!newShow) {
                    nextTick(() => {
                        resetForm();
                    });
                }
            }
        );

        return {
            form,
            fileInput,
            previewImages,
            handleFiles,
            searchCep,
            saveProperty,
        };
    },
};
</script>

<style scoped>
/* Modal Styles */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(5px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    padding: 1rem;
}

.modal-content {
    background: white;
    border-radius: 20px;
    max-width: 600px;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

.modal-header {
    padding: 2rem;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #333;
}

.close-btn {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #666;
    padding: 0.5rem;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.close-btn:hover {
    background: #f0f0f0;
    color: #333;
}

.modal-body {
    padding: 2rem;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-label {
    font-weight: 500;
    color: #555;
    font-size: 0.9rem;
}

.form-input {
    padding: 0.75rem 1rem;
    border: 2px solid #e0e7ff;
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: rgba(255, 255, 255, 0.8);
}

.form-input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
}

/* Preview Images */
.preview-images {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-top: 1rem;
}

.preview-thumb {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid #e0e7ff;
}

/* Buttons */
.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.2);
    color: #333;
    border: 2px solid rgba(255, 255, 255, 0.3);
}

.btn-secondary:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
}

/* Transitions */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

/* Responsive */
@media (max-width: 600px) {
    .modal-content {
        margin: 0.5rem;
        max-height: calc(100vh - 1rem);
    }

    .modal-header {
        padding: 1rem;
    }

    .modal-body {
        padding: 1rem;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .preview-thumb {
        width: 80px;
        height: 80px;
    }
}
</style>
