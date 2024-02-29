<script lang="ts" setup>
const props = defineProps<{
  path: string;
}>();
</script>

<template>
  <div class="p-pdf">
    <object :data="props.path" type="application/pdf" width="100%" height="100%" class="p-pdf-view"></object>
  </div>
</template>

<style lang="scss">
.p-pdf {
  height: 100vh;
}
</style>
