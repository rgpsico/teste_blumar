<template>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1 class="auth-title">
                    <i class="fas fa-home"></i>
                    ImobiliáriaApp
                </h1>
                <h2 class="auth-subtitle">Crie sua conta</h2>
            </div>

            <form @submit.prevent="submit" class="auth-form">
                <div class="form-group">
                    <label class="form-label">Nome</label>
                    <input
                        type="text"
                        class="form-input"
                        v-model="form.name"
                        :class="{ error: form.errors.name }"
                        placeholder="Seu nome completo"
                        required
                    />
                    <div v-if="form.errors.name" class="error-message">
                        {{ form.errors.name }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input
                        type="email"
                        class="form-input"
                        v-model="form.email"
                        :class="{ error: form.errors.email }"
                        placeholder="seu@email.com"
                        required
                    />
                    <div v-if="form.errors.email" class="error-message">
                        {{ form.errors.email }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Senha</label>
                    <input
                        type="password"
                        class="form-input"
                        v-model="form.password"
                        :class="{ error: form.errors.password }"
                        placeholder="Crie uma senha"
                        required
                    />
                    <div v-if="form.errors.password" class="error-message">
                        {{ form.errors.password }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Confirmar Senha</label>
                    <input
                        type="password"
                        class="form-input"
                        v-model="form.password_confirmation"
                        :class="{ error: form.errors.password_confirmation }"
                        placeholder="Confirme sua senha"
                        required
                    />
                    <div
                        v-if="form.errors.password_confirmation"
                        class="error-message"
                    >
                        {{ form.errors.password_confirmation }}
                    </div>
                </div>

                <button
                    type="submit"
                    class="btn btn-primary btn-full"
                    :disabled="form.processing"
                >
                    <i class="fas fa-user-plus"></i>
                    {{ form.processing ? "Cadastrando..." : "Cadastrar" }}
                </button>

                <div class="auth-links">
                    <Link href="/login" class="auth-link">
                        Já tem uma conta? Faça login
                    </Link>
                    <Link href="/" class="auth-link"> Voltar ao início </Link>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import { useForm, Link } from "@inertiajs/vue3";

export default {
    components: {
        Link,
    },

    setup() {
        const form = useForm({
            name: "",
            email: "",
            password: "",
            password_confirmation: "",
        });

        const submit = () => {
            form.post("/register");
        };

        return {
            form,
            submit,
        };
    },
};
</script>

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

.auth-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
}

.auth-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 3rem;
    box-shadow: 0 8px 32px rgba(31, 38, 135, 0.15);
    width: 100%;
    max-width: 400px;
}

.auth-header {
    text-align: center;
    margin-bottom: 2rem;
}

.auth-title {
    font-size: 2rem;
    font-weight: 700;
    color: #667eea;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.auth-subtitle {
    font-size: 1.1rem;
    color: #666;
    font-weight: 400;
}

.auth-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
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

.form-input.error {
    border-color: #ff6b6b;
}

.error-message {
    color: #ff6b6b;
    font-size: 0.8rem;
    font-weight: 500;
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
    justify-content: center;
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

.btn-full {
    width: 100%;
}

.auth-links {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    align-items: center;
    margin-top: 1rem;
}

.auth-link {
    color: #667eea;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.auth-link:hover {
    color: #764ba2;
    text-decoration: underline;
}

@media (max-width: 480px) {
    .auth-container {
        padding: 1rem;
    }

    .auth-card {
        padding: 2rem;
    }

    .auth-title {
        font-size: 1.5rem;
    }
}
</style>
