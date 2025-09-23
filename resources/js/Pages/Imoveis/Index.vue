<template>
    <div class="app-container">
        <!-- Header -->
        <header class="header">
            <div class="nav-container">
                <div class="logo">
                    <font-awesome-icon icon="home" />
                    ImobiliáriaApp
                </div>

                <div class="nav-buttons">
                    <template v-if="!user">
                        <button
                            class="btn btn-secondary"
                            @click="showLogin = true"
                        >
                            <font-awesome-icon icon="sign-in-alt" />
                            Login
                        </button>
                        <button
                            class="btn btn-primary"
                            @click="showRegister = true"
                        >
                            <font-awesome-icon icon="user-plus" />
                            Cadastrar
                        </button>
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
                            <font-awesome-icon icon="plus" />
                            Novo Imóvel
                        </button>
                        <button
                            class="btn btn-secondary"
                            @click="showMyProperties = !showMyProperties"
                        >
                            <font-awesome-icon icon="list" />
                            {{
                                showMyProperties ? "Ver Todos" : "Meus Imóveis"
                            }}
                        </button>
                        <button class="btn btn-danger" @click="logout">
                            <font-awesome-icon icon="sign-out-alt" />
                            Sair
                        </button>
                    </template>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Search Section -->
            <div class="search-section" v-if="!showMyProperties">
                <h2 class="search-title">
                    <font-awesome-icon icon="search" />
                    Encontre seu imóvel ideal
                </h2>

                <div class="search-grid">
                    <div class="form-group">
                        <label class="form-label">País</label>
                        <input
                            type="text"
                            class="form-input"
                            v-model="filters.pais"
                            placeholder="Ex: Brasil"
                            @input="debouncedSearch"
                        />
                    </div>
                    <!-- Outros campos de busca -->
                </div>

                <button class="btn btn-primary" @click="debouncedSearch">
                    <font-awesome-icon icon="search" />
                    Buscar Imóveis
                </button>
            </div>

            <!-- Loading -->
            <div v-if="loading" class="loading">
                <div class="spinner"></div>
                Carregando...
            </div>

            <!-- Properties Grid -->
            <div v-else-if="properties.length > 0" class="properties-grid">
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
                        <font-awesome-icon
                            v-if="!property.fotos?.[0]?.path"
                            icon="home"
                        />
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
                            <font-awesome-icon icon="map-marker-alt" />
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
                                <font-awesome-icon icon="edit" />
                                Editar
                            </button>
                            <button
                                class="btn btn-danger btn-small"
                                @click.stop="deleteProperty(property.id)"
                            >
                                <font-awesome-icon icon="trash" />
                                Excluir
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="empty-state">
                <font-awesome-icon icon="home" />
                <h3>Nenhum imóvel encontrado</h3>
                <p>{{ emptyStateMessage }}</p>
            </div>
        </main>

        <!-- Toast Notifications -->
        <transition-group name="fade" tag="div" class="toast-container">
            <div
                v-for="toast in toasts"
                :key="toast.id"
                :class="['toast', `toast-${toast.type}`]"
            >
                {{ toast.message }}
                <button class="close-btn" @click="removeToast(toast.id)">
                    ×
                </button>
            </div>
        </transition-group>

        <!-- Modals (Login, Register, Create/Edit Property, Property Detail) -->
        <!-- Adapte os modals de forma semelhante, substituindo <i> por <font-awesome-icon> -->
    </div>
</template>

<script>
import { Inertia } from "@inertiajs/inertia";
import axios from "axios";

export default {
    props: {
        user: Object,
        properties: Array,
    },
    data() {
        return {
            baseURL: "http://localhost:8000/api",
            baseURLImage: "http://localhost:8000/",
            token: localStorage.getItem("auth_token") || null,
            loading: false,
            showLogin: false,
            showRegister: false,
            showCreateModal: false,
            editingProperty: null,
            selectedProperty: null,
            showMyProperties: false,
            loginForm: { email: "", password: "" },
            registerForm: {
                name: "",
                email: "",
                password: "",
                password_confirmation: "",
            },
            propertyForm: {
                id: null,
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
            },
            filters: {
                pais: "",
                bairro: "",
                cep: "",
                titulo: "",
            },
            toasts: [],
        };
    },
    computed: {
        emptyStateMessage() {
            return this.user
                ? "Tente ajustar os filtros de busca ou cadastre um novo imóvel"
                : "Tente ajustar os filtros de busca ou faça login para ver mais opções";
        },
    },
    async mounted() {
        this.setupAxiosDefaults();
        if (this.token) {
            await this.getCurrentUser();
        }
        // Use a deep copy to assign properties to avoid mutating the prop
        this.properties = JSON.parse(JSON.stringify(this.$props.properties));
    },
    created() {
        this.debouncedSearch = this.debounce(this.searchProperties, 500);
    },
    methods: {
        async searchCep() {
            const cep = this.propertyForm.cep.replace(/\D/g, "");
            if (cep.length === 8) {
                try {
                    const response = await axios.get(`/cep/${cep}`);
                    const data = response.data;
                    if (!data.erro) {
                        this.propertyForm.rua = data.logradouro;
                        this.propertyForm.bairro = data.bairro;
                        this.propertyForm.cidade = data.localidade;
                        this.propertyForm.pais = "Brasil";
                        this.showAlert(
                            "Endereço preenchido automaticamente.",
                            "success"
                        );
                    } else {
                        this.clearAddressFields();
                        this.showAlert("CEP não encontrado.", "error");
                    }
                } catch (error) {
                    this.clearAddressFields();
                    this.showAlert(
                        error.response?.data?.erro ||
                            "Erro ao buscar CEP no servidor.",
                        "error"
                    );
                }
            } else {
                this.clearAddressFields();
            }
        },
        clearAddressFields() {
            this.propertyForm.rua = "";
            this.propertyForm.bairro = "";
            this.propertyForm.cidade = "";
            this.propertyForm.pais = "";
            this.propertyForm.complemento = "";
        },
        debounce(fn, wait) {
            let timer;
            return function (...args) {
                clearTimeout(timer);
                timer = setTimeout(() => fn.apply(this, args), wait);
            };
        },
        getImageUrl(path) {
            return path ? `${this.baseURLImage}storage/${path}` : "";
        },
        setupAxiosDefaults() {
            if (this.token) {
                axios.defaults.headers.common[
                    "Authorization"
                ] = `Bearer ${this.token}`;
            }
            axios.interceptors.response.use(
                (response) => response,
                (error) => {
                    if (error.response?.status === 401) {
                        this.logout();
                    }
                    return Promise.reject(error);
                }
            );
        },
        async getCurrentUser() {
            try {
                const response = await axios.get("/me");
                this.user = response.data;
            } catch (error) {
                this.logout();
            }
        },
        async login() {
            try {
                this.loading = true;
                const response = await axios.post("/login", this.loginForm);
                this.token = response.data.access_token;
                localStorage.setItem("auth_token", this.token);
                axios.defaults.headers.common[
                    "Authorization"
                ] = `Bearer ${this.token}`;
                const userResponse = await axios.get("/me");
                this.user = userResponse.data;
                this.showLogin = false;
                this.loginForm = { email: "", password: "" };
                await this.loadProperties();
                this.showAlert("Login realizado com sucesso!", "success");
            } catch (error) {
                this.showAlert(
                    error.response?.data?.message ||
                        "Erro no login. Verifique suas credenciais.",
                    "error"
                );
            } finally {
                this.loading = false;
            }
        },
        async register() {
            try {
                this.loading = true;
                const response = await axios.post(
                    "/register",
                    this.registerForm
                );
                this.token = response.data.token;
                this.user = response.data.user;
                localStorage.setItem("auth_token", this.token);
                axios.defaults.headers.common[
                    "Authorization"
                ] = `Bearer ${this.token}`;
                this.showRegister = false;
                this.registerForm = {
                    name: "",
                    email: "",
                    password: "",
                    password_confirmation: "",
                };
                await this.loadProperties();
                this.showAlert("Cadastro realizado com sucesso!", "success");
            } catch (error) {
                this.showAlert(
                    error.response?.data?.message ||
                        "Erro no cadastro. Tente novamente.",
                    "error"
                );
            } finally {
                this.loading = false;
            }
        },
        async logout() {
            try {
                if (this.token) {
                    await axios.post("/logout");
                }
            } catch (error) {
                console.error("Erro no logout:", error);
            }
            this.user = null;
            this.token = null;
            this.showMyProperties = false;
            localStorage.removeItem("auth_token");
            delete axios.defaults.headers.common["Authorization"];
            await this.loadProperties();
            this.showAlert("Logout realizado com sucesso!", "info");
        },
        async loadProperties() {
            try {
                this.loading = true;
                const endpoint =
                    this.showMyProperties && this.user
                        ? "/imoveis"
                        : "/imoveis";
                const response = await axios.get(endpoint, {
                    headers:
                        this.showMyProperties && this.user
                            ? { Authorization: `Bearer ${this.token}` }
                            : {},
                });
                this.properties = response.data;
            } catch (error) {
                this.showAlert(
                    error.response?.data?.message || "Erro ao carregar imóveis",
                    "error"
                );
            } finally {
                this.loading = false;
            }
        },
        async searchProperties() {
            try {
                this.loading = true;
                const params = {};
                Object.keys(this.filters).forEach((key) => {
                    if (this.filters[key]) {
                        params[key] = this.filters[key];
                    }
                });
                const endpoint =
                    this.showMyProperties && this.user
                        ? "/imoveis/filter"
                        : "/imoveis";
                const response = await axios.get(endpoint, {
                    params,
                    headers:
                        this.showMyProperties && this.user
                            ? { Authorization: `Bearer ${this.token}` }
                            : {},
                });
                this.properties = response.data;
            } catch (error) {
                this.showAlert(
                    error.response?.data?.message || "Erro na busca de imóveis",
                    "error"
                );
            } finally {
                this.loading = false;
            }
        },
        resetPropertyForm() {
            this.propertyForm = {
                id: null,
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
            };
        },
        handleFiles(event) {
            const files = Array.from(event.target.files);
            this.propertyForm.fotos = files.map((file) => ({
                file,
                preview: URL.createObjectURL(file),
            }));
        },
        async saveProperty() {
            if (
                !this.propertyForm.titulo ||
                this.propertyForm.titulo.trim() === ""
            ) {
                this.showAlert("O título é obrigatório", "error");
                return;
            }
            if (!this.propertyForm.preco || this.propertyForm.preco <= 0) {
                this.showAlert("O preço deve ser maior que zero", "error");
                return;
            }
            if (
                !this.propertyForm.descricao ||
                this.propertyForm.descricao.trim() === ""
            ) {
                this.showAlert("A descrição é obrigatória", "error");
                return;
            }
            if (
                !this.propertyForm.pais ||
                !this.propertyForm.cep ||
                !this.propertyForm.cidade ||
                !this.propertyForm.bairro ||
                !this.propertyForm.rua ||
                !this.propertyForm.numero
            ) {
                this.showAlert(
                    "Todos os campos de endereço são obrigatórios",
                    "error"
                );
                return;
            }

            try {
                this.loading = true;
                const formData = new FormData();
                if (this.editingProperty) {
                    formData.append("_method", "POST");
                }
                for (const key in this.propertyForm) {
                    if (key === "fotos") {
                        this.propertyForm.fotos.forEach((foto) => {
                            if (foto.file) {
                                formData.append("fotos[]", foto.file);
                            }
                        });
                    } else if (this.propertyForm[key] && key !== "id") {
                        formData.append(key, this.propertyForm[key]);
                    }
                }
                const url = this.editingProperty
                    ? `/imoveis/${this.editingProperty.id}`
                    : "/imoveis";
                const response = await axios.post(url, formData, {
                    headers: { "Content-Type": "multipart/form-data" },
                });
                this.showAlert(response.data.message, "success");
                this.closePropertyModal();
                await this.loadProperties();
            } catch (error) {
                this.showAlert(
                    error.response?.data?.message || "Erro ao salvar imóvel",
                    "error"
                );
            } finally {
                this.loading = false;
            }
        },
        editProperty(property) {
            this.editingProperty = property;
            this.propertyForm = {
                id: property.id,
                titulo: property.titulo,
                descricao: property.descricao,
                preco: property.preco,
                pais: property.endereco?.pais || "Brasil",
                cep: property.endereco?.cep || "",
                rua: property.endereco?.rua || "",
                numero: property.endereco?.numero || "",
                bairro: property.endereco?.bairro || "",
                cidade: property.endereco?.cidade || "",
                complemento: property.endereco?.complemento || "",
                fotos:
                    property.fotos?.map((foto) => ({
                        file: null,
                        preview: this.getImageUrl(foto.path),
                    })) || [],
            };
            this.showCreateModal = true;
        },
        editPropertyFromDetail(property) {
            this.selectedProperty = null;
            this.editProperty(property);
        },
        async deleteProperty(id) {
            if (!confirm("Tem certeza que deseja excluir este imóvel?")) {
                return;
            }
            try {
                this.loading = true;
                await axios.delete(`/imoveis/${id}`);
                this.showAlert("Imóvel excluído com sucesso!", "success");
                await this.loadProperties();
            } catch (error) {
                this.showAlert(
                    error.response?.data?.message || "Erro ao excluir imóvel",
                    "error"
                );
            } finally {
                this.loading = false;
            }
        },
        async deletePropertyFromDetail(id) {
            this.selectedProperty = null;
            await this.deleteProperty(id);
        },
        closePropertyModal() {
            this.showCreateModal = false;
            this.editingProperty = null;
            this.resetPropertyForm();
        },
        viewProperty(property) {
            this.selectedProperty = property;
        },
        formatPrice(price) {
            return new Intl.NumberFormat("pt-BR", {
                style: "currency",
                currency: "BRL",
            }).format(price);
        },
        showAlert(message, type = "info") {
            const id = Date.now();
            this.toasts.push({ id, message, type });
            setTimeout(() => this.removeToast(id), 5000);
        },
        removeToast(id) {
            this.toasts = this.toasts.filter((toast) => toast.id !== id);
        },
    },
    watch: {
        showMyProperties(newValue) {
            this.loadProperties();
        },
    },
};
</script>

<style scoped>
/* Opcional: Mova o CSS para cá se preferir escopo local, ou mantenha em app.css */
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

/* Modal */
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

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
}

/* Auth Section */
.auth-section {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 8px 32px rgba(31, 38, 135, 0.15);
}

/* Loading */
.loading {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 4rem;
    color: #666;
    font-size: 1.1rem;
}

.spinner {
    display: inline-block;
    width: 40px;
    height: 40px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-right: 1rem;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
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

.toast-info {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.toast .close-btn {
    background: none;
    border: none;
    color: white;
    font-size: 1rem;
    cursor: pointer;
}

/* Image Preview */
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

    .modal-content {
        margin: 1rem;
        max-height: calc(100vh - 2rem);
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .property-image {
        height: 150px;
    }
}

@media (max-width: 400px) {
    .modal-content {
        margin: 0.5rem;
        padding: 1rem;
    }
    .modal-header {
        padding: 1rem;
    }
    .modal-body {
        padding: 1rem;
    }
    .modal-title {
        font-size: 1.2rem;
    }
    .form-label {
        font-size: 0.8rem;
    }
    .form-input {
        font-size: 0.9rem;
        padding: 0.5rem;
    }
    .btn {
        font-size: 0.8rem;
        padding: 0.5rem 1rem;
    }
    .preview-thumb {
        width: 80px;
        height: 80px;
    }
    .property-image {
        height: 120px;
    }
}

/* Animations */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.slide-enter-active,
.slide-leave-active {
    transition: all 0.3s ease;
}
.slide-enter-from {
    transform: translateY(-20px);
    opacity: 0;
}
.slide-leave-to {
    transform: translateY(20px);
    opacity: 0;
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
</style>
