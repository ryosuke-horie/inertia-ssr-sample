<script lang="ts" setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { RouteName } from '@/Utilities';
import AdminLayout from '@/Layouts/AdminLayout/AdminLayout.vue';
import Button from '@/Components/atom/button/Button/Button.vue';
import LoadingModal from '@/Components/atom/loading/LoadingModal/LoadingModal.vue';

const props = defineProps<{
  businessId: number;
}>();

const isLoading = ref(false);

const submit = () => {
  if (isLoading.value) return;
  isLoading.value = true;
  router.delete(route(RouteName.AdminBusinessOperatorDelete, { businessId: props.businessId }));
  isLoading.value = false;
};
</script>
<template>
  <LoadingModal v-if="isLoading" class="loading" />
  <AdminLayout title="チアペイ管理" text="事業者削除" :href="RouteName.AdminBusinessOperator">
    <div class="p-business-operator-delete">
      <h2>事業者削除</h2>
      <p>事業者を削除します。よろしいですか？</p>
      <Button text="削除" @click="submit" />
    </div>
  </AdminLayout>
</template>
<style lang="scss" scoped>
.p-business-operator-delete {
  margin-top: 16px;
  background: #f5f5f5;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  border: 1px solid #dcdcdc;
  padding: 32px;
}
</style>
