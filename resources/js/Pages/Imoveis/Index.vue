<template>
    <div class="app-container">
        <!-- Header -->
        <header class="header">
            <div class="nav-container">
                <div class="logo">
                    <i class="fas fa-home"></i>
                    BEACH
                </div>

                <div class="nav-buttons">
                    <template v-if="!user">
                        <Link href="/login" class="btn btn-secondary">
                            <i class="fas fa-sign-in-alt"></i>
                            Login
                        </Link>
                        <Link href="/register" class="btn btn-primary">
                            <i class="fas fa-user-plus"></i>
                            Cadastrar
                        </Link>
                    </template>

                    <template v-else>
                        <div class="user-info">
                            <div class="user-avatar">
                                {{ user.name?.charAt(0).toUpperCase() }}
                            </div>
                            <span>{{ user.name }}</span>
                        </div>
                        <button
                            class="btn btn-primary"
                            @click="showCreateModal = true"
                        >
                            <i class="fas fa-plus"></i>
                            Novo Imóvel
                        </button>
                        <button
                            class="btn btn-info"
                            @click="showProfileModal = true"
                        >
                            <i class="fas fa-user"></i>
                            Perfil
                        </button>
                        <Link
                            :href="showMyProperties ? '/' : '/meus-imoveis'"
                            class="btn btn-secondary"
                        >
                            <i class="fas fa-list"></i>
                            {{
                                showMyProperties ? "Ver Todos" : "Meus Imóveis"
                            }}
                        </Link>
                        <Link
                            href="/logout"
                            method="post"
                            as="button"
                            class="btn btn-danger"
                        >
                            <i class="fas fa-sign-out-alt"></i>
                            Sair
                        </Link>
                    </template>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Search Section -->
            <div class="search-section" v-if="!showMyProperties">
                <h2 class="search-title">
                    <i class="fas fa-search"></i>
                    Encontre seu imóvel ideal
                </h2>

                <form @submit.prevent="searchProperties" class="search-form">
                    <div class="search-grid">
                        <div class="form-group">
                            <label class="form-label">País</label>
                            <input
                                type="text"
                                class="form-input"
                                v-model="searchForm.pais"
                                placeholder="Ex: Brasil"
                            />
                        </div>

                        <div class="form-group">
                            <label class="form-label">Bairro</label>
                            <input
                                type="text"
                                class="form-input"
                                v-model="searchForm.bairro"
                                placeholder="Ex: Copacabana"
                            />
                        </div>

                        <div class="form-group">
                            <label class="form-label">CEP</label>
                            <input
                                type="text"
                                class="form-input"
                                v-model="searchForm.cep"
                                placeholder="Ex: 22041001"
                            />
                        </div>

                        <div class="form-group">
                            <label class="form-label">Título</label>
                            <input
                                type="text"
                                class="form-input"
                                v-model="searchForm.titulo"
                                placeholder="Ex: Casa Pé na Areia"
                            />
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                        Buscar Imóveis
                    </button>
                </form>
            </div>

            <!-- Properties Grid -->
            <div v-if="properties.length > 0" class="properties-grid">
                <div
                    v-for="property in properties"
                    :key="property.id"
                    class="property-card"
                    @click="viewProperty(property)"
                >
                    <div
                        class="property-image"
                        :style="{
                            backgroundImage: `url(${getImageUrl(
                                property.fotos?.[0]?.path
                            )})`,
                        }"
                    >
                        <i
                            v-if="!property.fotos?.[0]?.path"
                            class="fas fa-home"
                        ></i>
                    </div>

                    <div class="property-info">
                        <h3 class="property-title">{{ property.titulo }}</h3>
                        <p class="property-description">
                            {{ property.descricao }}
                        </p>
                        <div class="property-price">
                            {{ formatPrice(property.preco) }}
                        </div>

                        <div v-if="property.endereco" class="property-address">
                            <i class="fas fa-map-marker-alt"></i>
                            {{ property.endereco.bairro }},
                            {{ property.endereco.cidade }}
                        </div>

                        <div
                            v-if="user && property.user_id === user.id"
                            class="property-actions"
                        >
                            <button
                                class="btn btn-primary btn-small"
                                @click.stop="editProperty(property)"
                            >
                                <i class="fas fa-edit"></i>
                                Editar
                            </button>
                            <button
                                class="btn btn-danger btn-small"
                                @click.stop="deleteProperty(property.id)"
                            >
                                <i class="fas fa-trash"></i>
                                Excluir
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="empty-state">
                <i class="fas fa-home"></i>
                <h3>Nenhum imóvel encontrado</h3>
                <p>{{ emptyStateMessage }}</p>
            </div>
        </main>

        <!-- Toast Notifications -->
        <div class="toast-container" v-if="$page.props.flash">
            <div v-if="$page.props.flash.success" class="toast toast-success">
                {{ $page.props.flash.success }}
            </div>
            <div v-if="$page.props.flash.error" class="toast toast-error">
                {{ $page.props.flash.error }}
            </div>
        </div>

        <!-- Property Modal -->
        <PropertyModal
            :show="showCreateModal || !!editingProperty"
            :property="editingProperty"
            @close="closePropertyModal"
        />

        <!-- Property Detail Modal -->
        <PropertyDetailModal
            :show="!!selectedProperty"
            :property="selectedProperty"
            :user="user"
            @close="selectedProperty = null"
            @edit="editPropertyFromDetail"
            @delete="deletePropertyFromDetail"
        />
    </div>
</template>

<script>
import { ref, computed, watch } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import PropertyModal from "@/Components/PropertyModal.vue";
import PropertyDetailModal from "@/Components/PropertyDetailModal.vue";

export default {
    components: {
        Head,
        Link,
        PropertyModal,
        PropertyDetailModal,
    },

    props: {
        user: Object,
        properties: Array,
        filters: Object,
        showMyProperties: {
            type: Boolean,
            default: false,
        },
    },

    setup(props) {
        const showCreateModal = ref(false);
        const editingProperty = ref(null);
        const selectedProperty = ref(null);

        const searchForm = ref({
            pais: props.filters?.pais || "",
            bairro: props.filters?.bairro || "",
            cep: props.filters?.cep || "",
            titulo: props.filters?.titulo || "",
        });

        const emptyStateMessage = computed(() => {
            return props.user
                ? "Tente ajustar os filtros de busca ou cadastre um novo imóvel"
                : "Tente ajustar os filtros de busca ou faça login para ver mais opções";
        });

        const searchProperties = () => {
            const url = props.showMyProperties ? "/meus-imoveis" : "/";
            router.get(url, searchForm.value, {
                preserveState: true,
                preserveScroll: true,
            });
        };

        const getImageUrl = (path) => {
            return path ? `/storage/${path}` : "";
        };

        const formatPrice = (price) => {
            return new Intl.NumberFormat("pt-BR", {
                style: "currency",
                currency: "BRL",
            }).format(price);
        };

        const editProperty = (property) => {
            editingProperty.value = property;
        };

        const editPropertyFromDetail = (property) => {
            selectedProperty.value = null;
            editProperty(property);
        };

        const deleteProperty = (id) => {
            if (confirm("Tem certeza que deseja excluir este imóvel?")) {
                router.delete(`/imoveis/${id}`, {
                    preserveState: true,
                    onSuccess: () => {
                        // Success message will be shown via flash
                    },
                });
            }
        };

        const deletePropertyFromDetail = (id) => {
            selectedProperty.value = null;
            deleteProperty(id);
        };

        const closePropertyModal = () => {
            showCreateModal.value = false;
            editingProperty.value = null;
        };

        const viewProperty = (property) => {
            selectedProperty.value = property;
        };

        return {
            showCreateModal,
            editingProperty,
            selectedProperty,
            searchForm,
            emptyStateMessage,
            searchProperties,
            getImageUrl,
            formatPrice,
            editProperty,
            editPropertyFromDetail,
            deleteProperty,
            deletePropertyFromDetail,
            closePropertyModal,
            viewProperty,
        };
    },
};
</script>

<style scoped>
/* Todos os estilos CSS que você já tinha */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: "Inter", sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
}

.app-container {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    position: relative;
}

/* Header */
.header {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    padding: 1rem 0;
    box-shadow: 0 8px 32px rgba(31, 38, 135, 0.15);
    border-bottom: 1px solid rgba(255, 255, 255, 0.18);
}

.nav-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo {
    font-size: 1.8rem;
    font-weight: 700;
    color: #667eea;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.nav-buttons {
    display: flex;
    gap: 1rem;
    align-items: center;
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

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
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

.btn-danger {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
    color: white;
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 107, 107, 0.3);
}

/* Main Content */
.main-content {
    flex: 1;
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
    width: 100%;
}

/* Search Section */
.search-section {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 8px 32px rgba(31, 38, 135, 0.15);
}

.search-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.search-grid {
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

/* Properties Grid */
.properties-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.property-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(31, 38, 135, 0.15);
    transition: all 0.3s ease;
    cursor: pointer;
}

.property-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(31, 38, 135, 0.2);
}

.property-image {
    height: 200px;
    background-size: cover;
    background-position: center;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 3rem;
    position: relative;
}

.property-info {
    padding: 1.5rem;
}

.property-title {
    font-size: 1.2rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 0.5rem;
}

.property-description {
    color: #666;
    margin-bottom: 1rem;
    font-size: 0.9rem;
    line-height: 1.5;
}

.property-price {
    font-size: 1.5rem;
    font-weight: 700;
    color: #667eea;
    margin-bottom: 1rem;
}

.property-address {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 1rem;
}

.property-actions {
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
}

.btn-small {
    padding: 0.5rem 1rem;
    font-size: 0.8rem;
}

/* User info */
.user-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    color: #333;
    font-weight: 500;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
}

/* Empty state */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #666;
}

.empty-state i {
    font-size: 4rem;
    color: #ccc;
    margin-bottom: 1rem;
}

.empty-state h3 {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
    color: #333;
}

/* Toast Notifications */
.toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 2000;
}

.toast {
    padding: 1rem 1.5rem;
    margin-bottom: 1rem;
    border-radius: 12px;
    color: white;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    max-width: 300px;
}

.toast-success {
    background: linear-gradient(135deg, #34c759 0%, #2ea043 100%);
}

.toast-error {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
}

/* Responsive */
@media (max-width: 768px) {
    .nav-container {
        padding: 0 1rem;
        flex-direction: column;
        gap: 1rem;
    }

    .main-content {
        padding: 1rem;
    }

    .search-grid {
        grid-template-columns: 1fr;
    }

    .properties-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .property-image {
        height: 150px;
    }
}
</style>
