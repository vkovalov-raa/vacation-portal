import { defineStore } from 'pinia';
import api from '@/bootstrap/axios';

export const useVacations = defineStore('vacations', {
    state: () => ({ list: [] }),

    actions: {
        async fetchMine() {
            const { data } = await api.get('/vacations');
            this.list = data;
        },

        async create(start, end, reason) {
            await api.post('/vacations', { start_date: start, end_date: end, reason });
            await this.fetchMine();
        },

        // manager
        async fetchAll() {
            const { data } = await api.get('/manager/vacations');
            this.list = data;
        },
        async setStatus(id, status) {
            await api.patch(`/manager/vacations/${id}`, { status });
            await this.fetchAll();
        },
    },
});
