import Vue from 'vue'
import VueRouter from 'vue-router'
import LoginView from '../views/LoginView.vue'
import RegisterView from '../views/RegisterView.vue'
import TasksView from '../views/TasksView.vue'
import { useUserStore } from '@/stores/user'
import pinia from '@/pinia'

Vue.use(VueRouter)

const router = new VueRouter({
  mode: 'history',
  base: import.meta.env.BASE_URL,
  routes: [
    {
      path: '/',
      name: 'home',
      component: LoginView,
      beforeEnter: (to, from, next) => {
        next('/login')
      }
    },
    {
      path: '/login',
      name: 'login',
      component: LoginView,
      beforeEnter: (to, from, next) => {
        const userStore = useUserStore(pinia);
        if (userStore.isAuthenticated) {
          next('/tasks');
        } else {
          next();
        }
      }
    },
    {
      path: '/register',
      name: 'register',
      component: RegisterView,
      beforeEnter: (to, from, next) => {
        const userStore = useUserStore(pinia);
        if (userStore.isAuthenticated) {
          next('/tasks');
        } else {
          next();
        }
      }
    },
    {
      path: '/tasks',
      name: 'tasks',
      component: TasksView,
      beforeEnter: (to, from, next) => {
        const userStore = useUserStore(pinia);
        if (userStore.isAuthenticated) {
          next();
        } else {
          next('/login');
        }
      }
    },
  ]
})

export default router
