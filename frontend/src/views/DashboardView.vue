<template>
  <section v-if="!user" class="min-h-screen flex items-center justify-center bg-base-200">
    <span class="loading loading-ring loading-lg"></span>
  </section>

  <section v-else class="min-h-screen flex items-center justify-center bg-base-200">
    <div class="card w-full max-w-lg shadow-xl bg-base-100">
      <div class="card-body items-center text-center space-y-6">
        <div class="avatar placeholder">
          <div class="bg-primary text-primary-content rounded-full w-24 aspect-square overflow-hidden">
            <div class="flex items-center justify-center w-full h-full">
              <span class="text-3xl">{{ initials }}</span>
            </div>
          </div>
        </div>

        <h2 class="card-title text-2xl">
          Welcome,&nbsp;<span class="text-primary">{{ user.name }}</span>!
        </h2>

        <ul class="menu menu-horizontal md:menu-vertical gap-2">
          <li>
            <router-link class="btn btn-sm btn-outline" to="/vacations">
              My requests
            </router-link>
          </li>
          <li v-if="user.role === 'manager'">
            <router-link class="btn btn-sm btn-outline" to="/manager/vacations">
              All requests
            </router-link>
          </li>
          <li v-if="user.role === 'manager'">
            <router-link class="btn btn-sm btn-outline" to="/manager/users">
              Users
            </router-link>
          </li>
        </ul>

        <button class="btn btn-error btn-sm" @click="logout">
          Exit
        </button>
      </div>
    </div>
  </section>
</template>

<script setup>
import { computed } from 'vue';
import { useAuth }   from '@/stores/auth';
import { useRouter } from 'vue-router';

const auth   = useAuth();
const router = useRouter();
const user   = computed(() => auth.user);

const initials = computed(() => {
  if (!user.value?.name) return '?';
  return user.value.name
      .split(' ')
      .map(p => p[0])
      .join('')
      .toUpperCase()
      .slice(0, 2);
});

function logout () {
  auth.logout();
  router.push('/login');
}
</script>