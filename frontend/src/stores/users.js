import { defineStore } from 'pinia';
import api from '@/bootstrap/axios';

export const useUsers = defineStore('users', {
    state: () => ({ list: [] }),
    actions: {
        async fetchAll() {
            const { data } = await api.get('/manager/users');
            this.list = data;
        },
        async create(u) {
            await api.post('/manager/users', u);
            await this.fetchAll();
        },
        async update(id, u) {
            await api.patch(`/manager/users/${id}`, u);
            await this.fetchAll();
        },
        async remove(id) {
            await api.delete(`/manager/users/${id}`);
            this.list = this.list.filter(u => u.id !== id);
        },
    },
});