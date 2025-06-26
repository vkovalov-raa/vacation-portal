import { createRouter, createWebHistory } from 'vue-router';
import { useAuth } from '../stores/auth';

const routes = [
    { path: '/login',      component: () => import('@/views/LoginView.vue'), meta: { guest: true } },
    { path: '/dashboard',  component: () => import('@/views/DashboardView.vue'), meta: { auth: true } },
    { path: '/:pathMatch(.*)*', redirect: '/dashboard' },  // fallback
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to) => {
    const auth = useAuth();

    // если токен в localStorage, но user ещё не загружен
    if (!auth.user && auth.token) await auth.fetchMe();

    if (to.meta.auth && !auth.user) {
        return '/login';
    }
    if (to.meta.guest && auth.user) {
        return '/dashboard';
    }
});

export default router;
