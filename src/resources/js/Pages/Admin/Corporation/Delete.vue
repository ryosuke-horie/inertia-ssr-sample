<!-- 公開側の法人退会が実装され次第、実装予定！ -->
<script lang="ts" setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { RouteName } from '@/Utilities';
import AdminLayout from '@/Layouts/AdminLayout/AdminLayout.vue';
import Button from '@/Components/atom/button/Button/Button.vue';
import LoadingModal from '@/Components/atom/loading/LoadingModal/LoadingModal.vue';

const props = defineProps<{
  corporationId: number;
}>();

const isLoading = ref(false);

const submit = () => {
  if (isLoading.value) return;
  isLoading.value = true;
  router.delete(route(RouteName.AdminCorporationDestroy, { corporationId: props.corporationId }));
  isLoading.value = false;
};
</script>
<template>
  <LoadingModal v-if="isLoading" class="loading" />
  <AdminLayout title="チアペイ管理" text="法人削除" :href="RouteName.AdminCorporation">
    <div class="p-corporation-delete">
      <h2>法人削除</h2>
      <p>法人を削除します。よろしいですか？</p>
      <Button text="削除" @click="submit" />
    </div>
  </AdminLayout>
</template>
<style lang="scss" scoped>
.p-corporation-delete {
  margin-top: 16px;
  background: #f5f5f5;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  border: 1px solid #dcdcdc;
  padding: 32px;
}
</style>
