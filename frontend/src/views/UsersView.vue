<script setup>
import { ref, onMounted } from 'vue';
import { useUsers } from '@/stores/users';

const users = useUsers();
const show  = ref(false);
const form  = ref({ name:'', email:'', employee_code:'', password:'', role:'employee' });

function openNew() { form.value={name:'', email:'', employee_code:'', password:'', role:'employee'}; show.value=true; }
function save() {
  users.create(form.value);
  show.value=false;
}

onMounted(users.fetchAll);
</script>

<template>
  <div class="max-w-4xl mx-auto mt-10 space-y-6">
    <div class="flex justify-between items-center">
      <router-link to="/dashboard" class="btn btn-outline btn-sm">← Back</router-link>
      <h2 class="text-2xl font-bold flex-1 text-center">Users</h2>
      <button class="btn btn-primary btn-sm" @click="openNew">New user</button>
    </div>

    <table class="table w-full">
      <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Employee Code</th><th>Role</th><th></th></tr></thead>
      <tbody>
      <tr v-for="u in users.list" :key="u.id">
        <td>{{u.id}}</td><td>{{u.name}}</td><td>{{u.email}}</td><td>{{u.employee_code}}</td><td>{{u.role}}</td>
        <td><button class="btn btn-xs btn-error" @click="users.remove(u.id)">✕</button></td>
      </tr>
      </tbody>
    </table>

    <dialog v-if="show" open class="modal" @close="show=false">
      <div class="modal-box space-y-4">
        <h3 class="font-bold text-lg">New user</h3>
        <input v-model="form.name"  class="input input-bordered w-full" placeholder="Name">
        <input v-model="form.email" class="input input-bordered w-full" placeholder="Email">
        <input v-model="form.employee_code" class="input input-bordered w-full" placeholder="Employee Code">
        <input v-model="form.password" type="password" class="input input-bordered w-full" placeholder="Password">
        <select v-model="form.role" class="select select-bordered w-full">
          <option value="employee">employee</option>
          <option value="manager">manager</option>
        </select>
        <div class="modal-action">
          <button class="btn" @click="show=false">Cancel</button>
          <button class="btn btn-primary" @click="save">Save</button>
        </div>
      </div>
    </dialog>
  </div>
</template>
