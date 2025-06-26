<script setup>
import { ref } from 'vue';
import { useVacations } from '@/stores/vacations';

const show   = defineModel({ type: Boolean });
const start  = ref('');
const end    = ref('');
const reason = ref('');
const vac    = useVacations();

async function submit() {
  await vac.create(start.value, end.value, reason.value);
  show.value = false;
  start.value = end.value = reason.value = '';
}
</script>

<template>
  <dialog :open="show" class="modal">
    <div class="modal-box space-y-4">
      <h3 class="font-bold text-lg">New request</h3>

      <input type="date" v-model="start"  class="input input-bordered w-full" />
      <input type="date" v-model="end"    class="input input-bordered w-full" />
      <textarea v-model="reason" class="textarea textarea-bordered w-full" placeholder="Reason (not required)"></textarea>

      <div class="modal-action">
        <button class="btn" @click="show=false">Cancel</button>
        <button class="btn btn-primary" @click="submit">Save</button>
      </div>
    </div>
  </dialog>
</template>
