import { defineStore } from 'pinia';
import api from '@/bootstrap/axios';

export const useAuth = defineStore('auth', {
    state: () => ({
        user: null,
        token: localStorage.getItem('token') || null,
    }),

    actions: {
        async login(email, password) {
            const { data } = await api.post('/auth/login', { email, password });
            this.user  = data.user;
            this.token = data.token;
            localStorage.setItem('token', data.token);
        },

        async fetchMe() {
            if (!this.token) return;
            try {
                const { data } = await api.get('/me');
                this.user = data;
            } catch {
                this.logout();
            }
        },

        logout() {
            this.user  = null;
            this.token = null;
            localStorage.removeItem('token');
        },
    },
});