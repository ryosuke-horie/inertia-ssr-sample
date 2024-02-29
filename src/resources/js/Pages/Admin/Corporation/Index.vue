<script lang="ts" setup>
import { ref } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import { RouteName } from '@/Utilities';
import { ButtonVariantProp } from '@/Components/atom/button/Button/type';
import AdminLayout from '@/Layouts/AdminLayout/AdminLayout.vue';
import Button from '@/Components/atom/button/Button/Button.vue';
import AnkerButton from '@/Components/atom/button/AnkerButton/AnkerButton.vue';
import Pagination from '@/Components/_admin/Pagination/Pagination.vue';
import FormGroupInput from '@/Components/molecules/form/FormGroupInput/FormGroupInput.vue';
import Modal from '@/Components/_admin/Modal/Modal.vue';

type PaginationProps = {
  links: {
    url: string;
    label: string;
    active: boolean;
  }[];
  currentPage: string;
  total: string;
};

type CorporationProps = {
  corporationId: number;
  corporationName: string;
  email: string;
  businessCount: {
    businessCount: number;
  }[];
};

const props = defineProps<{
  corporationList: CorporationProps[];
  corporationCount: number;
  pagination: PaginationProps;
}>();

const form = useForm({
  corporationName: '',
});

const showModal = ref(false);

const setIsModal = (value: boolean) => {
  showModal.value = value;
};

const onSearchSubmit = () => {
  form.get(route(RouteName.AdminCorporation));
  setIsModal(false);
};
</script>
<template>
  <AdminLayout title="チアペイ管理" text="法人一覧">
    <div class="p-corporation">
      <div class="p-corporation__header">
        <div class="p-corporation__header-controls">
          <p class="p-corporation__count">導入法人数：{{ props.corporationCount }}</p>
          <Button :variant="ButtonVariantProp.Derk" @click="setIsModal(true)" text="検索条件" />
        </div>
        <AnkerButton :href="route(RouteName.AdminCorporationCreate)" text="新規登録" />
      </div>
      <table class="p-corporation__table">
        <thead>
          <tr>
            <th>法人ID</th>
            <th>法人名</th>
            <th>事業者数</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="corporation in props.corporationList" :key="corporation.corporationName">
            <td>{{ corporation.corporationId }}</td>
            <td>{{ corporation.corporationName }}</td>
            <td>{{ corporation.businessCount }}</td>
            <td class="p-corporation__table-actions">
              <Link
                :href="route(RouteName.AdminCorporationShow, { corporationId: corporation.corporationId })"
                text="編集"
                class="p-corporation__table-actions--show"
              />
              <Link
                :href="route(RouteName.AdminCorporationDelete, { corporationId: corporation.corporationId })"
                text="削除"
                class="p-corporation__table-actions--delete"
              />
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <Pagination
      :links="props.pagination.links"
      :current-page="props.pagination.currentPage"
      :total="props.pagination.total"
    />
    <Modal v-if="showModal" variant="white" header-text="検索条件" @close="setIsModal(false)">
      <div class="p-corporation__modal-form--input">
        <FormGroupInput
          id="corporationName"
          label="法人名"
          v-model="form.corporationName"
          :error="form.errors.corporationName"
          class="p-corporation__modal-form-corporation-name"
        />
      </div>
      <Button
        :variant="ButtonVariantProp.Primary"
        text="検索"
        class="p-corporation__modal-form-search--button"
        @click="onSearchSubmit"
      />
    </Modal>
  </AdminLayout>
</template>
<style lang="scss" scoped>
.p-corporation {
  &__header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 24px 0;
    &-controls {
      display: flex;
      align-items: center;
      gap: 32px;
    }
  }
  &__count {
    box-shadow: 0px 3px 3px #e4e7ed;
    padding: 14px;
  }
  &__table {
    width: 100%;
    table-layout: fixed;
    border-collapse: collapse;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    th,
    td {
      text-align: left;
      padding: 12px 16px;
      border-bottom: 1px solid #e4e7ed;
    }
    td:last-child {
      display: flex;
      align-items: center;
    }
    th {
      background-color: #f5f5f5;
      color: #333;
    }
    tr:hover {
      background-color: #f0f0f0;
    }
    &-actions {
      &--show,
      &--review {
        margin-right: 24px;
      }
    }
  }
  &__modal {
    &-form {
      &--input {
        margin: 16px 0;
      }
      &-corporation-name {
        width: 100%;
      }
      &-search--button {
        width: 100%;
        margin: 16px auto;
      }
    }
  }
}
</style>
