<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
  <div class="container min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
    <div>
      <Link href="/">
        <ApplicationLogo class="applicatio_logo w-20 h-20 fill-current text-gray-500" />
      </Link>
    </div>

    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
      <slot />
    </div>
  </div>
</template>

<style lang="scss">
.container {
  width: 40%;
  margin: 100px auto;
  border: rgb(14, 112, 119) solid 3px;
  border-radius: 50px;
  padding: 1%;
  background-color: #e0ecfc;
}
</style>
