<template>
  <main class="min-vh-100 d-flex align-items-center bg-light py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="text-center mb-4">
            <i class="bi bi-check2-square text-primary display-3"></i>
            <h2 class="fw-bold text-primary mb-0">Gestor de Tareas</h2>
          </div>
          <div class="card shadow border-0">
            <div class="card-body p-4">
              <h3 class="card-title text-center mb-4">Crear Cuenta</h3>
              <div v-if="error" class="alert alert-danger d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <div>{{ error }}</div>
              </div>
              <form @submit.prevent="register">
                <div class="form-floating mb-3">
                  <input type="text" id="name" class="form-control" placeholder="Tu nombre" v-model="name" required />
                  <label for="name">
                    <i class="bi bi-person me-1"></i> Nombre completo
                  </label>
                </div>
                <div class="form-floating mb-3">
                  <input type="email" id="email" class="form-control" placeholder="nombre@ejemplo.com" v-model="email"
                    required />
                  <label for="email">
                    <i class="bi bi-envelope me-1"></i> Correo electrónico
                  </label>
                </div>
                <div class="form-floating mb-3 position-relative">
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

                <div class="form-floating mb-4 position-relative">
                  <input :type="showConfirmPassword ? 'text' : 'password'" id="confirm_password" class="form-control"
                    placeholder="Confirmar contraseña" v-model="confirmPassword" required />
                  <label for="confirm_password">
                    <i class="bi bi-shield-lock me-1"></i> Confirmar contraseña
                  </label>
                  <button type="button" class="btn btn-link position-absolute top-50 end-0 translate-middle-y pe-3"
                    @click="toggleConfirmPassword">
                    <i class="bi" :class="showConfirmPassword ? 'bi-eye-slash' : 'bi-eye'"></i>
                  </button>
                </div>
                <div class="mb-3" v-if="password">
                  <label class="form-label small">Seguridad de la contraseña</label>
                  <div class="progress" style="height: 5px;">
                    <div class="progress-bar" :class="passwordStrengthClass" :style="{ width: passwordStrength + '%' }">
                    </div>
                  </div>
                  <p class="form-text mt-1" :class="passwordStrengthTextClass">
                    <i class="bi" :class="passwordStrengthIcon"></i>
                    {{ passwordStrengthText }}
                  </p>
                </div>
                <div class="d-grid mb-3">
                  <button type="submit" class="btn btn-primary py-3" :disabled="isLoading || !isFormValid">
                    <span v-if="isLoading" class="spinner-border spinner-border-sm me-2" role="status"
                      aria-hidden="true"></span>
                    <i v-else class="bi bi-person-plus me-2"></i>
                    {{ isLoading ? 'Procesando...' : 'Crear Cuenta' }}
                  </button>
                </div>
                <div class="text-center">
                  <router-link to="/login" class="btn btn-link text-decoration-none">
                    <i class="bi bi-box-arrow-in-right me-1"></i> ¿Ya tienes cuenta? Inicia sesión
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
      name: '',
      email: '',
      password: '',
      confirmPassword: '',
      error: '',
      isLoading: false,
      showPassword: false,
      showConfirmPassword: false
    };
  },
  computed: {
    isFormValid() {
      return this.name &&
        this.email &&
        this.password &&
        this.confirmPassword &&
        this.password === this.confirmPassword;
    },
    passwordStrength() {
      if (!this.password) return 0;

      let strength = 0;

      // Longitud
      if (this.password.length >= 8) strength += 25;

      // Contiene números
      if (/\d/.test(this.password)) strength += 25;

      // Contiene letras minúsculas y mayúsculas
      if (/[a-z]/.test(this.password) && /[A-Z]/.test(this.password)) strength += 25;

      // Contiene caracteres especiales
      if (/[^a-zA-Z0-9]/.test(this.password)) strength += 25;

      return strength;
    },
    passwordStrengthClass() {
      if (this.passwordStrength < 50) return 'bg-danger';
      if (this.passwordStrength < 75) return 'bg-warning';
      return 'bg-success';
    },
    passwordStrengthText() {
      if (this.passwordStrength < 50) return 'Débil';
      if (this.passwordStrength < 75) return 'Media';
      return 'Fuerte';
    },
    passwordStrengthTextClass() {
      if (this.passwordStrength < 50) return 'text-danger';
      if (this.passwordStrength < 75) return 'text-warning';
      return 'text-success';
    },
    passwordStrengthIcon() {
      if (this.passwordStrength < 50) return 'bi-shield-exclamation';
      if (this.passwordStrength < 75) return 'bi-shield-check';
      return 'bi-shield-fill-check';
    }
  },
  methods: {
    async register() {
      this.error = '';

      if (this.password !== this.confirmPassword) {
        this.error = 'Las contraseñas no coinciden';
        return;
      }

      this.isLoading = true;

      try {
        const response = await axios.post(`${API_URL}/api/register`, {
          name: this.name,
          email: this.email,
          password: this.password,
          confirm_password: this.confirmPassword
        });

        const userStore = useUserStore();
        userStore.setUser({
          user: response.data.user,
          token: response.data.token
        });

        localStorage.setItem('token', response.data.token);
        this.$router.push('/tasks');
      } catch (err) {
        this.error = err.response?.data?.message || 'Ocurrió un error al registrarte. Intenta nuevamente.';
      } finally {
        this.isLoading = false;
      }
    },
    togglePassword() {
      this.showPassword = !this.showPassword;
    },
    toggleConfirmPassword() {
      this.showConfirmPassword = !this.showConfirmPassword;
    }
  },
  beforeUnmount() {
    this.name = '';
    this.email = '';
    this.password = '';
    this.confirmPassword = '';
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

.progress-bar {
  transition: width 0.5s ease-in-out;
}

@media (max-width: 768px) {
  .col-md-6 {
    padding: 0 15px;
  }
}
</style>