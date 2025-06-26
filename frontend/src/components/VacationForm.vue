<script setup>
import { ref, watch } from 'vue';
import { useVacations } from '@/stores/vacations';

const show   = defineModel({ type: Boolean });
const dialog = ref(null);
const start  = ref('');
const end    = ref('');
const reason = ref('');
const vac    = useVacations();

watch(show, (val) => {
  if (val) dialog.value?.showModal();
  else     dialog.value?.close();
});

function close() { show.value = false; }

async function submit() {
  await vac.create(start.value, end.value, reason.value);
  start.value = end.value = reason.value = '';
  close();
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
