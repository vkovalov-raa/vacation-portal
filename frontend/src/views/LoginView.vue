<template>
  <div class="min-h-screen flex items-center justify-center px-4 bg-base-200">
    <div class="w-full max-w-sm bg-base-100 shadow-lg rounded-xl p-8">
      <h1 class="text-2xl font-semibold text-center mb-6">Sign&nbsp;in</h1>

      <form @submit.prevent="handleLogin" class="space-y-4">
        <label class="input validator">
          <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <g
                stroke-linejoin="round"
                stroke-linecap="round"
                stroke-width="2.5"
                fill="none"
                stroke="currentColor"
            >
              <rect width="20" height="16" x="2" y="4" rx="2"></rect>
              <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
            </g>
          </svg>
          <input
              v-model="email"
              type="email"
              placeholder="E-mail"
              required
          />
        </label>
        <div class="validator-hint hidden">Enter email</div>

        <label class="input validator">
          <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <g
                stroke-linejoin="round"
                stroke-linecap="round"
                stroke-width="2.5"
                fill="none"
                stroke="currentColor"
            >
              <path
                  d="M2.586 17.414A2 2 0 0 0 2 18.828V21a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1h1a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1h.172a2 2 0 0 0 1.414-.586l.814-.814a6.5 6.5 0 1 0-4-4z"
              ></path>
              <circle cx="16.5" cy="7.5" r=".5" fill="currentColor"></circle>
            </g>
          </svg>
          <input
              v-model="password"
              type="password"
              required
              placeholder="Password"
              minlength="8"
              pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
              title="Must be more than 8 characters, including number, lowercase letter, uppercase letter"
          />
        </label>
        <p class="validator-hint hidden">
          Must be more than 8 characters, including
          <br />At least one number <br />At least one lowercase letter <br />At least one uppercase letter
        </p>

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
