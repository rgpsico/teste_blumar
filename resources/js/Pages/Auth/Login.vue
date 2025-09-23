```vue
<template>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1 class="auth-title">
                    <i class="fas fa-home"></i>
                    ImobiliáriaApp
                </h1>
                <h2 class="auth-subtitle">Faça login em sua conta</h2>
            </div>

            <form @submit.prevent="submit" class="auth-form">
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
                        placeholder="Sua senha"
                        required
                    />
                    <div v-if="form.errors.password" class="error-message">
                        {{ form.errors.password }}
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i>
                    Entrar
                </button>

                <div class="auth-links">
                    <Link href="/register" class="auth-link">
                        Não tem uma conta? Cadastre-se
                    </Link>
                    <Link href="/forgot-password" class="auth-link">
                        Esqueceu sua senha?
                    </Link>
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
            email: "",
            password: "",
        });

        const submit = () => {
            form.post("/login", {
                preserveScroll: true,
                onSuccess: () => {
                    form.reset("password");
                },
            });
        };

        return {
            form,
            submit,
        };
    },
};
</script>

<style scoped>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.auth-container {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 1rem;
}

.auth-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px); /* Safari fallback */
    border-radius: 20px;
    padding: 2rem;
    width: 100%;
    max-width: 400px;
    box-shadow: 0 8px 32px rgba(31, 38, 135, 0.15);
}

.auth-header {
    text-align: center;
    margin-bottom: 2rem;
}

.auth-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: #667eea;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.auth-subtitle {
    font-size: 1.2rem;
    color: #333;
    margin-top: 0.5rem;
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
    margin-top: 0.25rem;
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

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.auth-links {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    margin-top: 1rem;
}

.auth-link {
    color: #667eea;
    text-decoration: none;
    font-size: 0.9rem;
}

.auth-link:hover {
    text-decoration: underline;
}

/* Responsive */
@media (max-width: 768px) {
    .auth-card {
        padding: 1.5rem;
    }

    .auth-title {
        font-size: 1.5rem;
    }

    .auth-subtitle {
        font-size: 1rem;
    }
}
</style>
