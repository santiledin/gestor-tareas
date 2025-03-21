<template>
  <main class="min-vh-100 d-flex align-items-center bg-light py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-5">
          <div class="text-center mb-4">
            <i class="bi bi-check2-square text-primary display-3"></i>
            <h2 class="fw-bold text-primary mb-0">Gestor de Tareas</h2>
          </div>
          <div class="card shadow border-0">
            <div class="card-body p-4">
              <h3 class="card-title text-center mb-4">Iniciar Sesión</h3>
              <div v-if="error" class="alert alert-danger d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <div>{{ error }}</div>
              </div>
              <form @submit.prevent="login">
                <div class="form-floating mb-3">
                  <input type="email" id="email" class="form-control" placeholder="nombre@ejemplo.com" v-model="email"
                    required />
                  <label for="email">
                    <i class="bi bi-envelope me-1"></i> Correo electrónico
                  </label>
                </div>
                <div class="form-floating mb-4 position-relative">
                  <input :type="showPassword ? 'text' : 'password'" id="password" class="form-control"
                    placeholder="Contraseña" v-model="password" required />
                  <label for="password">
                    <i class="bi bi-lock me-1"></i> Contraseña
                  </label>
                  <button type="button" class="btn btn-link position-absolute top-50 end-0 translate-middle-y pe-3"
                    @click="togglePassword">
                    <i class="bi" :class="showPassword ? 'bi-eye-slash' : 'bi-eye'"></i>
                  </button>
                </div>
                <div class="d-grid mb-3">
                  <button type="submit" class="btn btn-primary py-3" :disabled="isLoading">
                    <span v-if="isLoading" class="spinner-border spinner-border-sm me-2" role="status"
                      aria-hidden="true"></span>
                    <i v-else class="bi bi-box-arrow-in-right me-2"></i>
                    {{ isLoading ? 'Accediendo...' : 'Iniciar Sesión' }}
                  </button>
                </div>
                <div class="text-center">
                  <router-link to="/register" class="btn btn-link text-decoration-none">
                    <i class="bi bi-person-plus me-1"></i> ¿No tienes cuenta? Regístrate aquí
                  </router-link>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<script>
import axios from 'axios';
import { useUserStore } from '@/stores/user';
import API_URL from '@/config/api';

export default {
  data() {
    return {
      email: '',
      password: '',
      error: '',
      isLoading: false,
      showPassword: false
    };
  },
  methods: {
    async login() {
      this.error = '';
      this.isLoading = true;

      try {
        const response = await axios.post(`${API_URL}/api/login`, {
          email: this.email,
          password: this.password
        });

        const userStore = useUserStore();
        userStore.setUser({
          user: response.data.user,
          token: response.data.token
        });

        localStorage.setItem('token', response.data.token);
        this.$router.push('/tasks');
      } catch (err) {
        this.error = err.response?.data?.message || 'Ocurrió un error al iniciar sesión. Verifica tus credenciales.';
        this.password = '';
      } finally {
        this.isLoading = false;
      }
    },
    togglePassword() {
      this.showPassword = !this.showPassword;
    }
  },
  beforeUnmount() {
    this.email = '';
    this.password = '';
  }
};
</script>

<style scoped>
.card {
  border-radius: 1rem;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.btn-primary {
  border-radius: 0.5rem;
  transition: all 0.3s;
}

.btn-primary:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
}

.form-control:focus {
  box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
  border-color: #86b7fe;
}

.form-floating input:focus~label,
.form-floating input:not(:placeholder-shown)~label {
  color: #0d6efd;
  opacity: 0.8;
}

.alert {
  animation: fadeIn 0.5s ease;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@media (max-width: 768px) {
  .col-md-5 {
    padding: 0 15px;
  }
}
</style>