<template>
  <div class="mt-20 text-center">
    <h2 class="text-3xl font-bold">Добро пожаловать, {{ user?.name }}!</h2>

    <button @click="logout"
            class="mt-8 bg-gray-300 rounded px-4 py-2 hover:bg-gray-400">
      Выйти
    </button>
  </div>
</template>

<script setup>
import { useAuth } from '../stores/auth';
import { useRouter } from 'vue-router';

const auth   = useAuth();
const router = useRouter();
const user   = auth.user;

function logout() {
  auth.logout();
  router.push('/login');
}
</script>
