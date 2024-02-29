<script setup>
import { Head } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';

const logout = () => {
  router.post(route('business-operator.logout'));
};
</script>

<template>
  <Head title="Admin Dashboard" />

  <div>
    <p>This is an business-operator dashboard page!</p>
    <button @click="logout">Logout</button>
  </div>
</template>
