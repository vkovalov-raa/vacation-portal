import { createRouter, createWebHistory } from 'vue-router';
import { useAuth } from '../stores/auth';
import VacationsView from '@/views/VacationsView.vue';
import ManagerVacationsView from '@/views/ManagerVacationsView.vue';
import UsersView from '@/views/UsersView.vue';

const routes = [
    { path: '/login',      component: () => import('@/views/LoginView.vue'), meta: { guest: true } },
    { path: '/dashboard',  component: () => import('@/views/DashboardView.vue'), meta: { auth: true } },
    { path: '/vacations', component: VacationsView, meta: { auth: true } },
    { path: '/manager/vacations', component: ManagerVacationsView, meta: { auth: true, role: 'manager' } },
    { path:'/manager/users', component: UsersView, meta:{ auth:true, role:'manager' } },
    { path: '/:pathMatch(.*)*', redirect: '/dashboard' },  // fallback
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to) => {
    const auth = useAuth();

    if (!auth.user && auth.token) await auth.fetchMe();

    if (to.meta.auth && !auth.user) {
        return '/login'
    }
    if (to.meta.guest && auth.user) {
        return '/dashboard'
    }
    if (to.meta.role && auth.user?.role !== to.meta.role) {
        return '/dashboard'
    }
});

export default router;
