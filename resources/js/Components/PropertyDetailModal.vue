<template>
    <Teleport to="body">
        <Transition name="fade">
            <div
                v-if="show && property"
                class="modal-overlay"
                @click.self="$emit('close')"
            >
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title">{{ property.titulo }}</h2>
                        <button class="close-btn" @click="$emit('close')">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="modal-body">
                        <!-- Images -->
                        <div v-if="property.fotos && property.fotos.length > 0">
                            <div
                                v-for="foto in property.fotos"
                                :key="foto.id"
                                class="property-image"
                                style="
                                    margin-bottom: 1.5rem;
                                    border-radius: 12px;
                                "
                                :style="{
                                    backgroundImage: `url(/storage/${foto.path})`,
                                }"
                            ></div>
                        </div>

                        <div
                            v-else
                            class="property-image no-image"
                            style="margin-bottom: 1.5rem; border-radius: 12px"
                        >
                            <i class="fas fa-home"></i>
                        </div>

                        <!-- Price -->
                        <div
                            class="property-price"
                            style="text-align: center; margin-bottom: 1.5rem"
                        >
                            {{ formatPrice(property.preco) }}
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label class="form-label">Descrição</label>
                            <p class="property-text">
                                {{ property.descricao }}
                            </p>
                        </div>

                        <!-- Address -->
                        <div v-if="property.endereco">
                            <h3 class="section-title">
                                <i class="fas fa-map-marker-alt"></i>
                                Endereço
                            </h3>

                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">País</label>
                                    <p class="property-text">
                                        {{ property.endereco.pais }}
                                    </p>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">CEP</label>
                                    <p class="property-text">
                                        {{ property.endereco.cep }}
                                    </p>
                                </div>
                            </div>

                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">Cidade</label>
                                    <p class="property-text">
                                        {{ property.endereco.cidade }}
                                    </p>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Bairro</label>
                                    <p class="property-text">
                                        {{ property.endereco.bairro }}
                                    </p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label"
                                    >Endereço Completo</label
                                >
                                <p class="property-text">
                                    {{ property.endereco.rua }},
                                    {{ property.endereco.numero }}
                                    <span v-if="property.endereco.complemento">
                                        - {{ property.endereco.complemento }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <!-- Actions for property owner -->
                        <div
                            v-if="user && property.user_id === user.id"
                            class="form-actions"
                        >
                            <button
                                class="btn btn-primary"
                                @click="$emit('edit', property)"
                            >
                                <i class="fas fa-edit"></i>
                                Editar Imóvel
                            </button>
                            <button
                                class="btn btn-danger"
                                @click="$emit('delete', property.id)"
                            >
                                <i class="fas fa-trash"></i>
                                Excluir Imóvel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script>
export default {
    emits: ["close", "edit", "delete"],

    props: {
        show: {
            type: Boolean,
            default: false,
        },
        property: {
            type: Object,
            default: null,
        },
        user: {
            type: Object,
            default: null,
        },
    },

    setup() {
        const formatPrice = (price) => {
            return new Intl.NumberFormat("pt-BR", {
                style: "currency",
                currency: "BRL",
            }).format(price);
        };

        return {
            formatPrice,
        };
    },
};
</script>

<style scoped>
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

.property-image {
    height: 250px;
    background-size: cover;
    background-position: center;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 3rem;
    position: relative;
}

.no-image {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.property-price {
    font-size: 2rem;
    font-weight: 700;
    color: #667eea;
}

.section-title {
    margin: 1.5rem 0 1rem 0;
    color: #667eea;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1.2rem;
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

.property-text {
    color: #666;
    line-height: 1.6;
    margin: 0;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #eee;
}

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

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.btn-danger {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
    color: white;
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 107, 107, 0.3);
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

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

    .property-image {
        height: 200px;
    }

    .property-price {
        font-size: 1.5rem;
    }

    .form-actions {
        flex-direction: column;
    }
}
</style>
