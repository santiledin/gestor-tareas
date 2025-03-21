<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    <i class="bi bi-check2-square me-2"></i>Gestor de Tareas
                </a>
                <button class="btn btn-outline-light my-2 my-sm-0" @click="logout">
                    <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                </button>
            </div>
        </nav>

        <div class="container mt-4">
            <div class="row">
                <div class="col-md-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title text-center">{{ isEditing ? 'Editar Tarea' : 'Crear Tarea' }}</h5>
                            <form @submit.prevent="saveTask">
                                <div class="form-group mb-3">
                                    <label for="title" class="form-label">Título</label>
                                    <input type="text" id="title" v-model="taskForm.title" class="form-control"
                                        required />
                                </div>
                                <div class="form-group mb-3">
                                    <label for="description" class="form-label">Descripción</label>
                                    <textarea id="description" v-model="taskForm.description" class="form-control"
                                        rows="3" placeholder="Descripción detallada..."></textarea>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="due_date" class="form-label">Fecha de Vencimiento</label>
                                    <input type="date" id="due_date" v-model="taskForm.due_date" class="form-control" />
                                </div>
                                <div class="form-group mb-3">
                                    <label for="priority" class="form-label">Prioridad</label>
                                    <select id="priority" v-model="taskForm.priority" class="form-select" required>
                                        <option value="baja">Baja</option>
                                        <option value="media">Media</option>
                                        <option value="alta">Alta</option>
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="status" class="form-label">Estado</label>
                                    <select id="status" v-model="taskForm.status" class="form-select" required>
                                        <option value="pendiente">Pendiente</option>
                                        <option value="completa">Completada</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 py-2">
                                    <i class="bi" :class="isEditing ? 'bi-pencil-square' : 'bi-plus-circle'"></i>
                                    {{ isEditing ? 'Actualizar' : 'Crear' }} Tarea
                                </button>
                                <button v-if="isEditing" type="button" @click="cancelEditing"
                                    class="btn btn-secondary w-100 py-2 my-2">
                                    <i class="bi bi-x-circle"></i> Cancelar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title text-center mb-4">Mis Tareas</h5>
                            <div class="d-flex justify-content-between mb-4">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn"
                                        :class="activeFilter === 'todas' ? 'btn-primary' : 'btn-outline-primary'"
                                        @click="setFilter('todas')">
                                        Todas
                                    </button>
                                    <button type="button" class="btn"
                                        :class="activeFilter === 'pendiente' ? 'btn-primary' : 'btn-outline-primary'"
                                        @click="setFilter('pendiente')">
                                        Pendientes
                                    </button>
                                    <button type="button" class="btn"
                                        :class="activeFilter === 'completa' ? 'btn-primary' : 'btn-outline-primary'"
                                        @click="setFilter('completa')">
                                        Completadas
                                    </button>
                                </div>

                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                        id="priorityDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-filter"></i> Prioridad: {{ priorityFilterLabel }}
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="priorityDropdown">
                                        <li><a class="dropdown-item" href="#"
                                                @click.prevent="setPriorityFilter('todas')">Todas</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item" href="#"
                                                @click.prevent="setPriorityFilter('alta')">
                                                <span class="badge bg-danger me-2">Alta</span> Alta</a>
                                        </li>
                                        <li><a class="dropdown-item" href="#"
                                                @click.prevent="setPriorityFilter('media')">
                                                <span class="badge bg-warning me-2">Media</span> Media</a>
                                        </li>
                                        <li><a class="dropdown-item" href="#"
                                                @click.prevent="setPriorityFilter('baja')">
                                                <span class="badge bg-info me-2">Baja</span> Baja</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="filteredTasks.length === 0" class="text-center py-5">
                                <i class="bi bi-clipboard-x display-1 text-muted"></i>
                                <p class="mt-3 text-muted">No hay tareas que coincidan con los filtros seleccionados</p>
                                <button class="btn btn-primary mt-2" @click="resetFilters">
                                    <i class="bi bi-arrow-repeat"></i> Restablecer filtros
                                </button>
                            </div>
                            <div v-else>
                                <div v-for="task in filteredTasks" :key="task.id"
                                    class="task-card mb-3 p-3 rounded shadow-sm border-start border-5"
                                    :class="getPriorityBorderClass(task.priority)">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="d-flex align-items-center mb-2">
                                                <h5 class="mb-0 me-2">{{ task.title }}</h5>
                                                <span class="badge" :class="getPriorityBadgeClass(task.priority)">{{
                                                    task.priority }}</span>
                                            </div>
                                            <p class="mb-1 text-muted" v-if="task.description">{{ task.description }}
                                            </p>
                                            <div class="d-flex align-items-center mt-2">
                                                <span class="badge" :class="getStatusBadgeClass(task.status)">
                                                    <i class="bi" :class="getStatusIcon(task.status)"></i>
                                                    {{ getStatusText(task.status) }}
                                                </span>
                                                <span v-if="task.due_date" class="ms-2 small text-muted">
                                                    <i class="bi bi-calendar-event"></i> {{ formatDate(task.due_date) }}
                                                </span>
                                                <span v-if="isTaskOverdue(task)"
                                                    class="ms-2 badge bg-danger">¡Vencida!</span>
                                            </div>
                                        </div>
                                        <div>
                                            <button class="btn btn-sm btn-outline-primary me-1" @click="editTask(task)"
                                                title="Editar">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" @click="deleteTask(task.id)"
                                                title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { useUserStore } from '@/stores/user';
import API_URL from '@/config/api';

export default {
    name: 'TasksView',
    data() {
        return {
            tasks: [],
            showTaskForm: false,
            isEditing: false,
            activeFilter: 'todas',
            priorityFilter: 'todas',
            taskForm: {
                title: '',
                description: '',
                due_date: '',
                status: 'pendiente',
                priority: 'baja'
            },
            token: '',
        };
    },
    computed: {
        filteredTasks() {
            let result = [...this.tasks];

            if (this.activeFilter !== 'todas') {
                result = result.filter(task => task.status === this.activeFilter);
            }

            if (this.priorityFilter !== 'todas') {
                result = result.filter(task => task.priority === this.priorityFilter);
            }

            return result.sort((a, b) => {
                const priorityOrder = { alta: 0, media: 1, baja: 2 };
                const priorityDiff = priorityOrder[a.priority] - priorityOrder[b.priority];

                if (priorityDiff !== 0) return priorityDiff;

                if (a.due_date && b.due_date) {
                    return new Date(a.due_date) - new Date(b.due_date);
                }

                if (a.due_date && !b.due_date) return -1;
                if (!a.due_date && b.due_date) return 1;

                return 0;
            });
        },
        priorityFilterLabel() {
            return this.priorityFilter.charAt(0).toUpperCase() + this.priorityFilter.slice(1);
        }
    },
    methods: {
        logout() {
            axios.post(`${API_URL}/api/logout`, {}, {
                headers: { 'Authorization': `Bearer ${this.token}` },
            })
                .then(() => {
                    const userStore = useUserStore();
                    userStore.logout();
                    this.$router.push('/login');
                })
                .catch(error => {
                    console.error('Error al cerrar sesión:', error);
                });
        },
        fetchTasks() {
            axios.get(`${API_URL}/api/tasks`, {
                headers: { 'Authorization': `Bearer ${this.token}` },
            })
                .then(response => {
                    this.tasks = response.data;
                })
                .catch(error => {
                    console.error('Error al obtener tareas:', error);
                });
        },
        saveTask() {
            if (this.isEditing) {
                axios.put(`${API_URL}/api/tasks/${this.taskForm.id}`, this.taskForm, {
                    headers: { 'Authorization': `Bearer ${this.token}` },
                })
                    .then(() => {
                        this.fetchTasks();
                        this.resetForm();
                    })
                    .catch(error => {
                        console.error('Error al actualizar tarea:', error);
                    });
            } else {
                axios.post(`${API_URL}/api/tasks`, this.taskForm, {
                    headers: { 'Authorization': `Bearer ${this.token}` },
                })
                    .then(() => {
                        this.fetchTasks();
                        this.resetForm();
                    })
                    .catch(error => {
                        console.error('Error al crear tarea:', error);
                    });
            }
        },
        resetForm() {
            this.taskForm = {
                title: '',
                description: '',
                due_date: '',
                status: 'pendiente',
                priority: 'baja'
            };
            this.showTaskForm = false;
            this.isEditing = false;
        },
        editTask(task) {
            this.showTaskForm = true;
            this.isEditing = true;
            this.taskForm = { ...task };
        },
        deleteTask(taskId) {
            if (confirm('¿Estás seguro de que deseas eliminar esta tarea?')) {
                axios.delete(`${API_URL}/api/tasks/${taskId}`, {
                    headers: { 'Authorization': `Bearer ${this.token}` },
                })
                    .then(() => {
                        this.fetchTasks();
                    })
                    .catch(error => {
                        console.error('Error al eliminar tarea:', error);
                    });
            }
        },
        cancelEditing() {
            this.resetForm();
            this.isEditing = false;
        },
        isTaskOverdue(task) {
            if (!task.due_date) return false;
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const dueDate = new Date(task.due_date);
            return dueDate < today && task.status !== 'completa';
        },
        formatDate(dateString) {
            const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
            return new Date(dateString).toLocaleDateString('es-ES', options);
        },
        setFilter(filter) {
            this.activeFilter = filter;
        },
        setPriorityFilter(priority) {
            this.priorityFilter = priority;
        },
        resetFilters() {
            this.activeFilter = 'todas';
            this.priorityFilter = 'todas';
        },
        getPriorityBadgeClass(priority) {
            const classes = {
                alta: 'bg-danger',
                media: 'bg-warning',
                baja: 'bg-info'
            };
            return classes[priority] || 'bg-secondary';
        },
        getPriorityBorderClass(priority) {
            const classes = {
                alta: 'border-danger',
                media: 'border-warning',
                baja: 'border-info'
            };
            return classes[priority] || 'border-secondary';
        },
        getStatusBadgeClass(status) {
            const classes = {
                pendiente: 'bg-secondary',
                en_progreso: 'bg-primary',
                completa: 'bg-success'
            };
            return classes[status] || 'bg-secondary';
        },
        getStatusIcon(status) {
            const icons = {
                pendiente: 'bi-hourglass-split',
                en_progreso: 'bi-arrow-repeat',
                completa: 'bi-check-circle'
            };
            return icons[status] || 'bi-question-circle';
        },
        getStatusText(status) {
            const texts = {
                pendiente: 'Pendiente',
                completa: 'Completada'
            };
            return texts[status] || status;
        }
    },
    beforeMount() {
        const userStore = useUserStore();
        this.token = userStore.token;
        if (!userStore.isAuthenticated) {
            this.$router.push('/login');
        }
    },
    mounted() {
        this.fetchTasks();
    },
};
</script>

<style scoped>
.navbar {
    border-bottom: 2px solid #f1f1f1;
}

.card {
    border-radius: 15px;
    border: none;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    overflow: hidden;
}

.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.task-card {
    background-color: #fff;
    transition: transform 0.2s ease;
    position: relative;
}

.task-card:hover {
    transform: translateX(5px);
}

.btn {
    border-radius: 8px;
    transition: all 0.2s;
}

.btn i {
    margin-right: 5px;
}

.btn-sm i {
    margin-right: 0;
}

.btn:hover {
    transform: translateY(-2px);
}

.badge {
    font-weight: 500;
    padding: 0.5em 0.8em;
    border-radius: 6px;
}

.dropdown-menu {
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border: none;
    padding: 10px;
}

.dropdown-item {
    border-radius: 6px;
    padding: 8px 16px;
    transition: background-color 0.2s;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
}

@media (max-width: 768px) {
    .btn-group {
        flex-wrap: wrap;
    }

    .btn-group .btn {
        margin-bottom: 5px;
    }
}
</style>