<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ImobiliáriaApp - Encontre seu imóvel ideal</title>
    <meta
      name="csrf-token"
      content="2|evCdJz4oB03JlFcMYvForV9lO9zQDz8U6eUg82yTf261338c"
    />

    <!-- Vue.js CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/3.3.4/vue.global.prod.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.4.0/axios.min.js"></script>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />

    <!-- Axios para requisições HTTP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.4.0/axios.min.js"></script>

    <!-- Font Awesome para ícones -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />

    <!-- Google Fonts -->
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />

    <style>
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
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

      .auth-tabs {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
      }

      .tab-btn {
        padding: 0.75rem 1.5rem;
        border: none;
        background: none;
        border-radius: 12px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s ease;
        color: #666;
      }

      .tab-btn.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
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
  </head>
  <body>
    <div id="app">
      <div class="app-container">
        <!-- Header -->
        <header class="header">
          <div class="nav-container">
            <div class="logo">
              <i class="fas fa-home"></i>
              ImobiliáriaApp
            </div>

            <div class="nav-buttons">
              <template v-if="!user">
                <button class="btn btn-secondary" @click="showLogin = true">
                  <i class="fas fa-sign-in-alt"></i>
                  Login
                </button>
                <button class="btn btn-primary" @click="showRegister = true">
                  <i class="fas fa-user-plus"></i>
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
                <button class="btn btn-primary" @click="showCreateModal = true">
                  <i class="fas fa-plus"></i>
                  Novo Imóvel
                </button>
                <button
                  class="btn btn-secondary"
                  @click="showMyProperties = !showMyProperties"
                >
                  <i class="fas fa-list"></i>
                  {{ showMyProperties ? 'Ver Todos' : 'Meus Imóveis' }}
                </button>
                <button class="btn btn-danger" @click="logout">
                  <i class="fas fa-sign-out-alt"></i>
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
              <i class="fas fa-search"></i>
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
                  @input="searchProperties"
                />
              </div>

              <div class="form-group">
                <label class="form-label">Bairro</label>
                <input
                  type="text"
                  class="form-input"
                  v-model="filters.bairro"
                  placeholder="Ex: Copacabana"
                  @input="searchProperties"
                />
              </div>

              <div class="form-group">
                <label class="form-label">CEP</label>
                <input
                  type="text"
                  class="form-input"
                  v-model="filters.cep"
                  placeholder="Ex: 22041001"
                  @input="searchProperties"
                />
              </div>

              <div class="form-group">
                <label class="form-label">Título</label>
                <input
                  type="text"
                  class="form-input"
                  v-model="filters.titulo"
                  placeholder="Ex: Casa Pé na Areia"
                  @input="searchProperties"
                />
              </div>
            </div>

            <button class="btn btn-primary" @click="searchProperties">
              <i class="fas fa-search"></i>
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
              <div class="property-image">
                <i class="fas fa-home"></i>
              </div>

              <div class="property-info">
                <h3 class="property-title">{{ property.titulo }}</h3>
                <p class="property-description">{{ property.descricao }}</p>
                <div class="property-price">
                  R$ {{ formatPrice(property.preco) }}
                </div>

                <div v-if="property.endereco" class="property-address">
                  <i class="fas fa-map-marker-alt"></i>
                  {{ property.endereco.bairro }}, {{ property.endereco.cidade }}
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
      </div>

      <!-- Login Modal -->
      <transition name="fade">
        <div
          v-if="showLogin"
          class="modal-overlay"
          @click.self="showLogin = false"
        >
          <div class="modal-content">
            <div class="modal-header">
              <h2 class="modal-title">Login</h2>
              <button class="close-btn" @click="showLogin = false">
                <i class="fas fa-times"></i>
              </button>
            </div>

            <div class="modal-body">
              <form @submit.prevent="login">
                <div class="form-group">
                  <label class="form-label">Email</label>
                  <input
                    type="email"
                    class="form-input"
                    v-model="loginForm.email"
                    required
                    placeholder="seu@email.com"
                  />
                </div>

                <div class="form-group">
                  <label class="form-label">Senha</label>
                  <input
                    type="password"
                    class="form-input"
                    v-model="loginForm.password"
                    required
                    placeholder="Sua senha"
                  />
                </div>

                <div class="form-actions">
                  <button
                    type="button"
                    class="btn btn-secondary"
                    @click="showLogin = false"
                  >
                    Cancelar
                  </button>
                  <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i>
                    Entrar
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </transition>

      <!-- Register Modal -->
      <transition name="fade">
        <div
          v-if="showRegister"
          class="modal-overlay"
          @click.self="showRegister = false"
        >
          <div class="modal-content">
            <div class="modal-header">
              <h2 class="modal-title">Cadastro</h2>
              <button class="close-btn" @click="showRegister = false">
                <i class="fas fa-times"></i>
              </button>
            </div>

            <div class="modal-body">
              <form @submit.prevent="register">
                <div class="form-group">
                  <label class="form-label">Nome</label>
                  <input
                    type="text"
                    class="form-input"
                    v-model="registerForm.name"
                    required
                    placeholder="Seu nome completo"
                  />
                </div>

                <div class="form-group">
                  <label class="form-label">Email</label>
                  <input
                    type="email"
                    class="form-input"
                    v-model="registerForm.email"
                    required
                    placeholder="seu@email.com"
                  />
                </div>

                <div class="form-group">
                  <label class="form-label">Senha</label>
                  <input
                    type="password"
                    class="form-input"
                    v-model="registerForm.password"
                    required
                    placeholder="Crie uma senha"
                  />
                </div>

                <div class="form-group">
                  <label class="form-label">Confirmar Senha</label>
                  <input
                    type="password"
                    class="form-input"
                    v-model="registerForm.password_confirmation"
                    required
                    placeholder="Confirme sua senha"
                  />
                </div>

                <div class="form-actions">
                  <button
                    type="button"
                    class="btn btn-secondary"
                    @click="showRegister = false"
                  >
                    Cancelar
                  </button>
                  <button type="submit" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i>
                    Cadastrar
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </transition>

      <!-- Create/Edit Property Modal -->
      <transition name="fade">
        <div
          v-if="showCreateModal || editingProperty"
          class="modal-overlay"
          @click.self="closePropertyModal"
        >
          <div class="modal-content">
            <div class="modal-header">
              <h2 class="modal-title">
                {{ editingProperty ? 'Editar Imóvel' : 'Novo Imóvel' }}
              </h2>
              <button class="close-btn" @click="closePropertyModal">
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
                      v-model="propertyForm.titulo"
                      required
                      placeholder="Ex: Casa Pé na Areia"
                    />
                  </div>

                  <div class="form-group">
                    <label class="form-label">Preço (R$)</label>
                    <input
                      type="number"
                      class="form-input"
                      v-model="propertyForm.preco"
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
                    v-model="propertyForm.descricao"
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
                    multiple
                    @change="handleFiles"
                  />
                  <div class="preview-images" v-if="propertyForm.fotos?.length">
                    <img
                      v-for="(foto, index) in propertyForm.fotos"
                      :key="index"
                      :src="foto.preview"
                      class="preview-thumb"
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
                      v-model="propertyForm.pais"
                      required
                      placeholder="Brasil"
                    />
                  </div>

                  <div class="form-group">
                    <label class="form-label">CEP</label>
                    <input
                      type="text"
                      class="form-input"
                      v-model="propertyForm.cep"
                      required
                      placeholder="22041001"
                    />
                  </div>
                </div>

                <div class="form-grid">
                  <div class="form-group">
                    <label class="form-label">Cidade</label>
                    <input
                      type="text"
                      class="form-input"
                      v-model="propertyForm.cidade"
                      required
                      placeholder="Rio de Janeiro"
                    />
                  </div>

                  <div class="form-group">
                    <label class="form-label">Bairro</label>
                    <input
                      type="text"
                      class="form-input"
                      v-model="propertyForm.bairro"
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
                      v-model="propertyForm.rua"
                      required
                      placeholder="Rua das Flores"
                    />
                  </div>

                  <div class="form-group">
                    <label class="form-label">Número</label>
                    <input
                      type="text"
                      class="form-input"
                      v-model="propertyForm.numero"
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
                    v-model="propertyForm.complemento"
                    placeholder="Apto 101, Bloco A"
                  />
                </div>

                <div class="form-actions">
                  <button
                    type="button"
                    class="btn btn-secondary"
                    @click="closePropertyModal"
                  >
                    Cancelar
                  </button>
                  <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    {{ editingProperty ? 'Atualizar' : 'Criar' }} Imóvel
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </transition>

      <!-- Property Detail Modal -->
      <transition name="fade">
        <div
          v-if="selectedProperty"
          class="modal-overlay"
          @click.self="selectedProperty = null"
        >
          <div class="modal-content">
            <div class="modal-header">
              <h2 class="modal-title">{{ selectedProperty.titulo }}</h2>
              <button class="close-btn" @click="selectedProperty = null">
                <i class="fas fa-times"></i>
              </button>
            </div>

            <div class="modal-body">
              <div
                class="property-image"
                style="margin-bottom: 1.5rem; border-radius: 12px"
              >
                <i class="fas fa-home"></i>
              </div>

              <div
                class="property-price"
                style="text-align: center; margin-bottom: 1.5rem"
              >
                R$ {{ formatPrice(selectedProperty.preco) }}
              </div>

              <div class="form-group">
                <label class="form-label">Descrição</label>
                <p style="color: #666; line-height: 1.6">
                  {{ selectedProperty.descricao }}
                </p>
              </div>

              <div v-if="selectedProperty.endereco">
                <h3 style="margin: 1.5rem 0 1rem 0; color: #667eea">
                  <i class="fas fa-map-marker-alt"></i>
                  Endereço
                </h3>

                <div class="form-grid">
                  <div class="form-group">
                    <label class="form-label">País</label>
                    <p style="color: #666">
                      {{ selectedProperty.endereco.pais }}
                    </p>
                  </div>

                  <div class="form-group">
                    <label class="form-label">CEP</label>
                    <p style="color: #666">
                      {{ selectedProperty.endereco.cep }}
                    </p>
                  </div>
                </div>

                <div class="form-grid">
                  <div class="form-group">
                    <label class="form-label">Cidade</label>
                    <p style="color: #666">
                      {{ selectedProperty.endereco.cidade }}
                    </p>
                  </div>

                  <div class="form-group">
                    <label class="form-label">Bairro</label>
                    <p style="color: #666">
                      {{ selectedProperty.endereco.bairro }}
                    </p>
                  </div>
                </div>

                <div class="form-group">
                  <label class="form-label">Endereço Completo</label>
                  <p style="color: #666">
                    {{ selectedProperty.endereco.rua }}, {{
                    selectedProperty.endereco.numero }}
                    <span v-if="selectedProperty.endereco.complemento">
                      - {{ selectedProperty.endereco.complemento }}
                    </span>
                  </p>
                </div>
              </div>

              <div
                v-if="user && selectedProperty.user_id === user.id"
                class="form-actions"
              >
                <button
                  class="btn btn-primary"
                  @click="editPropertyFromDetail(selectedProperty)"
                >
                  <i class="fas fa-edit"></i>
                  Editar Imóvel
                </button>
                <button
                  class="btn btn-danger"
                  @click="deletePropertyFromDetail(selectedProperty.id)"
                >
                  <i class="fas fa-trash"></i>
                  Excluir Imóvel
                </button>
              </div>
            </div>
          </div>
        </div>
      </transition>
    </div>

    <script>
      const { createApp } = Vue;

      createApp({
        data() {
          return {
            // API Base URL - altere para sua URL do backend
            baseURL: "http://localhost:8000/api",

            // Authentication
            user: null,
            token: "2|evCdJz4oB03JlFcMYvForV9lO9zQDz8U6eUg82yTf261338c",

            // UI States
            loading: false,
            showLogin: false,
            showRegister: false,
            showCreateModal: false,
            editingProperty: null,
            selectedProperty: null,
            showMyProperties: false,
            fotos: [], // Aqui armazenamos os arquivos,
            emptyStateMessage() {
              return this.user
                ? "Tente ajustar os filtros de busca ou cadastre um novo imóvel"
                : "Tente ajustar os filtros de busca ou faça login para ver mais opções";
            },
            // Data
            properties: [],

            // Forms
            loginForm: {
              email: "",
              password: "",
            },

            registerForm: {
              name: "",
              email: "",
              password: "",
              password_confirmation: "",
            },

            propertyForm: {
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
            },

            // Filters
            filters: {
              pais: "",
              bairro: "",
              cep: "",
              titulo: "",
            },
          };
        },

        async mounted() {
          this.setupAxiosDefaults();

          if (this.token) {
            await this.getCurrentUser();
          }

          await this.loadProperties();
        },
        methods: {
          handleFiles(event) {
            this.propertyForm.fotos = Array.from(event.target.files);
          },
          async saveProperty() {
            const formData = new FormData();

            for (const key in this.propertyForm) {
              if (key === "fotos") {
                this.propertyForm.fotos.forEach((file) =>
                  formData.append("fotos[]", file)
                );
              } else {
                formData.append(key, this.propertyForm[key]);
              }
            }

            await axios.post("/api/imoveis", formData, {
              headers: {
                "Content-Type": "multipart/form-data",
              },
            });
          },

          async saveProperty() {
            const formData = new FormData();

            for (const key in this.propertyForm) {
              if (key === "fotos") {
                this.propertyForm.fotos.forEach((file) => {
                  formData.append("fotos[]", file);
                });
              } else {
                formData.append(key, this.propertyForm[key]);
              }
            }

            try {
              const url = this.editingProperty
                ? `/api/imoveis/${this.propertyForm.id}`
                : "/api/imoveis";

              const method = this.editingProperty ? "put" : "post";

              const response = await axios({
                method,
                url,
                data: formData,
                headers: { "Content-Type": "multipart/form-data" },
              });

              console.log("Sucesso:", response.data);
              this.closePropertyModal();
            } catch (err) {
              console.error("Erro ao salvar imóvel:", err);
            }
          },
          setupAxiosDefaults() {
            axios.defaults.baseURL = this.baseURL;
            // Include CSRF token
            const csrfToken = document
              .querySelector('meta[name="csrf-token"]')
              .getAttribute("content");
            axios.defaults.headers.common["X-CSRF-TOKEN"] = csrfToken;
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
              console.error("Erro ao buscar usuário:", error);
              this.logout();
            }
          },

          async login() {
            try {
              this.loading = true;
              const response = await axios.post("/login", this.loginForm);

              this.token = response.data.token;
              this.user = response.data.user;

              localStorage.setItem("auth_token", this.token);
              axios.defaults.headers.common[
                "Authorization"
              ] = `Bearer ${this.token}`;

              this.showLogin = false;
              this.loginForm = { email: "", password: "" };

              await this.loadProperties();

              this.showAlert("Login realizado com sucesso!", "success");
            } catch (error) {
              console.error("Erro no login:", error);
              this.showAlert(
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
              const response = await axios.post("/register", this.registerForm);

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
              console.error("Erro no cadastro:", error);
              this.showAlert("Erro no cadastro. Tente novamente.", "error");
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

              let endpoint = "/imoveis";

              if (this.showMyProperties && this.user) {
                // Carrega imóveis do usuário logado
                const response = await axios.get("/imoveis", {
                  headers: {
                    Authorization: `Bearer ${this.token}`,
                  },
                });
                this.properties = response.data;
              } else {
                // Carrega imóveis públicos
                const response = await axios.get("/imoveis");
                this.properties = response.data;
              }
            } catch (error) {
              console.error("Erro ao carregar imóveis:", error);
              this.showAlert("Erro ao carregar imóveis", "error");
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

              let endpoint =
                this.showMyProperties && this.user
                  ? "/imoveis/filter"
                  : "/imoveis";

              const response = await axios.get(endpoint, {
                params,
                headers:
                  this.showMyProperties && this.user
                    ? {
                        Authorization: `Bearer ${this.token}`,
                      }
                    : {},
              });

              this.properties = response.data;
            } catch (error) {
              console.error("Erro na busca:", error);
              this.showAlert("Erro na busca de imóveis", "error");
            } finally {
              this.loading = false;
            }
          },

          resetPropertyForm() {
            this.propertyForm = {
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
            };
          },

          async saveProperty() {
            try {
              this.loading = true;

              if (this.editingProperty) {
                // Update property
                await axios.put(
                  `/imoveis/${this.editingProperty.id}`,
                  this.propertyForm
                );
                this.showAlert("Imóvel atualizado com sucesso!", "success");
              } else {
                // Create property
                await axios.post("/imoveis", this.propertyForm);
                this.showAlert("Imóvel criado com sucesso!", "success");
              }

              this.closePropertyModal();
              await this.loadProperties();
            } catch (error) {
              console.error("Erro ao salvar imóvel:", error);
              this.showAlert("Erro ao salvar imóvel", "error");
            } finally {
              this.loading = false;
            }
          },

          editProperty(property) {
            this.editingProperty = property;

            // Preenche o formulário com os dados do imóvel
            this.propertyForm = {
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
            };
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
              console.error("Erro ao excluir imóvel:", error);
              this.showAlert("Erro ao excluir imóvel", "error");
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
            // Simple alert - you could replace with a toast notification library
            alert(message);
          },
        },

        watch: {
          showMyProperties() {
            this.loadProperties();
          },
        },
      }).mount("#app");
    </script>
  </body>
</html>
