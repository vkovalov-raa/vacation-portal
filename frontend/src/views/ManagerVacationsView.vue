<script setup>
import { onMounted } from 'vue';
import { useVacations } from '@/stores/vacations';

const vac = useVacations();

onMounted(vac.fetchAll);

function set(id, status) {
  vac.setStatus(id, status);
}
</script>

<template>
  <div class="max-w-4xl mx-auto mt-10 space-y-6">
    <div class="flex justify-between items-center mb-4">
      <router-link to="/dashboard" class="btn btn-outline btn-sm">
        ← Back
      </router-link>

      <h2 class="text-2xl font-bold flex-1 text-center">All requests</h2>
    </div>

    <table class="table w-full">
      <thead>
      <tr><th>ID</th><th>Employer</th><th>Dates</th><th>Status</th><th></th></tr>
      </thead>
      <tbody>
      <tr v-for="v in vac.list" :key="v.id">
        <td>{{ v.id }}</td>
        <td>{{ v.user_name }}</td>
        <td>{{ v.start_date }} — {{ v.end_date }}</td>
        <td>
            <span :class="{
                pending:'badge badge-warning',
                approved:'badge badge-success',
                rejected:'badge badge-error'
              }[v.status]">{{ v.status }}</span>
        </td>
        <td v-if="v.status==='pending'" class="space-x-2">
          <button class="btn btn-xs btn-success" @click="set(v.id,'approved')">✓</button>
          <button class="btn btn-xs btn-error"   @click="set(v.id,'rejected')">✕</button>
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</template>
