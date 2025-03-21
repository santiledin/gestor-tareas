import { defineStore } from 'pinia'

export const useUserStore = defineStore('user', {
    state: () => ({
        user: null,
        token: null
    }),
    actions: {
        setUser(data) {
            this.user = data.user
            this.token = data.token
        },
        logout() {
            this.user = null
            this.token = null
        }
    },
    getters: {
        isAuthenticated: (state) => {
            return !!state.token
        }
    }
})
