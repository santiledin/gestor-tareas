# Task Manager - Andean Travel Company

## Descripción del Proyecto

Este proyecto es un gestor de tareas desarrollado para Andean Travel Company. La aplicación cuenta con un sistema de autenticación (login y registro) y un módulo principal de tareas que permite realizar operaciones CRUD (Crear, Leer, Actualizar y Eliminar).

## Tecnologías Utilizadas

- **Backend**: Laravel 12 (API REST)
- **Frontend**: Vue 2
- **Autenticación**: Laravel Sanctum
- **Estado Frontend**: Pinia

## Estructura del Proyecto

El proyecto está dividido en dos carpetas principales:

```
proyecto/
├── backend/     # API REST en Laravel 12
└── frontend/    # Aplicación cliente en Vue 2
```

### Backend

La API REST construida en Laravel 12 cuenta con:

- **Controladores**:
  - `AuthController`: Maneja la autenticación de usuarios
  - `TaskController`: Gestiona las operaciones CRUD de tareas

- **Modelos**:
  - `User`: Representa a los usuarios del sistema
  - `Task`: Representa las tareas

- **Requests personalizados**:
  - `TaskRequest`: Validación para operaciones con tareas
  - `LoginRequest`: Validación para inicio de sesión
  - `RegisterRequest`: Validación para registro de usuarios

### Frontend

La aplicación cliente desarrollada en Vue 2 incluye:

- **Vistas principales**:
  - `Login`: Página de inicio de sesión
  - `Register`: Página de registro de usuarios
  - `Tasks`: Página principal para gestión de tareas

- **Gestión de estado**:
  - Pinia para almacenar el token de autenticación

## Instalación y Configuración

### Requisitos previos

- Node.js y npm
- Servidor local (XAMPP, WAMP, etc.)
- Composer

### Pasos para inicializar el proyecto

1. **Configurar el Backend**:
   ```
   cd backend
   composer install
   cp .env.example .env
   php artisan key:generate
   php artisan migrate
   npm install
   composer run dev
   ```

2. **Configurar el Frontend**:
   ```
   cd frontend
   npm install
   ```

3. **Configurar la URL de la API**:
   - Copiar la URL que proporciona la consola al ejecutar `composer run dev` en el backend
   - Pegar esta URL en `frontend/src/config/api.js` en la constante `API_URL`
   ```javascript
   // Ejemplo en frontend/src/config/api.js
   export const API_URL = 'http://localhost:8000';
   ```

4. **Iniciar el Frontend**:
   ```
   cd frontend
   npm run dev
   ```

## Uso de la Aplicación

1. Registre un nuevo usuario en la página de registro
2. Inicie sesión con sus credenciales
3. Gestione sus tareas en el módulo principal:
   - Crear nuevas tareas
   - Ver lista de tareas existentes
   - Actualizar tareas
   - Eliminar tareas

## Contacto

Desarrollado por **Eddie Lemos**, Desarrollador de Software.

---

Agradezco la oportunidad de presentar este proyecto como parte de mi postulación para la vacante de programador en Andean Travel Company. Estoy disponible para resolver cualquier duda o proporcionar información adicional sobre la implementación.