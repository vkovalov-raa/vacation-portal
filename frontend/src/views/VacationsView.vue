<script setup>
import { ref, onMounted } from 'vue';
import { useVacations } from '@/stores/vacations';
import VacationForm from '@/components/VacationForm.vue';

const vac  = useVacations();
const show = ref(false);

onMounted(vac.fetchMine);
</script>

<template>
  <div class="max-w-3xl mx-auto mt-10 space-y-6">
    <div class="flex justify-between items-center">
      <h2 class="text-2xl font-bold">My requests</h2>
      <button class="btn btn-primary" @click="show=true">New</button>
    </div>

    <table class="table w-full">
      <thead>
      <tr><th>ID</th><th>Date</th><th>Status</th><th>Reason</th></tr>
      </thead>
      <tbody>
      <tr v-for="v in vac.list" :key="v.id">
        <td>{{ v.id }}</td>
        <td>{{ v.start_date }} — {{ v.end_date }}</td>
        <td><span :class="{
                pending:'badge badge-warning',
                approved:'badge badge-success',
                rejected:'badge badge-error'
              }[v.status]">{{ v.status }}</span></td>
        <td>{{ v.reason }}</td>
      </tr>
      </tbody>
    </table>

    <VacationForm v-model:show="show" />
  </div>
</template>
