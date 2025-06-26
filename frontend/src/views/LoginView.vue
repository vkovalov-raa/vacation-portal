<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 px-4">
    <div class="w-full max-w-sm bg-white shadow-lg rounded-xl p-8">
      <h1 class="text-2xl font-semibold text-center mb-6">Sign&nbsp;in</h1>

      <form @submit.prevent="handleLogin" class="space-y-4">
        <div class="form-control">
          <input v-model="email"
                 type="email"
                 placeholder="E-mail"
                 class="input input-bordered w-full"
                 required />
        </div>

        <div class="form-control">
          <input v-model="password"
                 type="password"
                 placeholder="Password"
                 class="input input-bordered w-full"
                 required />
        </div>

        <button class="btn btn-primary w-full" :disabled="loading">
          <span v-if="loading" class="loading loading-spinner loading-sm mr-2"></span>
          Login
        </button>

        <p v-if="error" class="text-red-600 text-sm text-center">{{ error }}</p>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuth } from '@/stores/auth';

const email    = ref('');
const password = ref('');
const error    = ref('');
const loading  = ref(false);

const router = useRouter();
const auth   = useAuth();

async function handleLogin () {
  loading.value = true;  error.value = '';
  try {
    await auth.login(email.value, password.value);
    router.push('/dashboard');
  } catch {
    error.value = 'Wrong email or password';
  } finally {
    loading.value = false;
  }
}
</script>
