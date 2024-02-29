<script lang="ts" setup>
import { ref } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import { RouteName } from '@/Utilities';
import { getNameByEnumValue, formatSelectBoxOptions } from '@/Utilities/enum';
import { BusinessFormNameStatus } from '@/Enums/BusinessFormNameStatus';
import { BusinessPublishedStatus } from '@/Enums/BusinessPublishedStatus';
import { BusinessStatus } from '@/Enums/BusinessStatus';
import { ButtonVariantProp } from '@/Components/atom/button/Button/type';
import AdminLayout from '@/Layouts/AdminLayout/AdminLayout.vue';
import Button from '@/Components/atom/button/Button/Button.vue';
import AnkerButton from '@/Components/atom/button/AnkerButton/AnkerButton.vue';
import Pagination from '@/Components/_admin/Pagination/Pagination.vue';
import FormGroupInput from '@/Components/molecules/form/FormGroupInput/FormGroupInput.vue';
import FormGroupSelectBox from '@/Components/molecules/form/FormGroupSelectBox/FormGroupSelectBox.vue';
import Modal from '@/Components/_admin/Modal/Modal.vue';

type PaginationProps = {
  links: Array<{
    url: string;
    label: string;
    active: boolean;
  }>;
  currentPage: string;
  total: string;
};

type BusinessOperatorProps = {
  businessId: number;
  businessName: string;
  corporationName: string;
  parentCorporationName: string;
  businessForm: string;
  businessStatus: string;
  isPublish: boolean;
};

type ParentCorporationProps = {
  corporationId: number;
  corporationName: string;
};

const props = defineProps<{
  businessList: BusinessOperatorProps[];
  businessCount: number;
  parentCorporationList: ParentCorporationProps[];
  pagination: PaginationProps;
}>();

const form = useForm({
  businessName: '',
  corporationName: '',
  businessForm: '',
  parentCorporationId: '',
  isPublish: '',
  businessStatus: '',
});

const parentCorporationOptions = formatSelectBoxOptions(
  props.parentCorporationList.map((corp) => ({
    value: corp.corporationId,
    label: corp.corporationName,
  })),
);

const businessFormOptions = formatSelectBoxOptions(
  Object.values(BusinessFormNameStatus).map((status) => ({
    value: status.name,
    label: status.name,
  })),
);

const isPublishOptions = formatSelectBoxOptions(
  Object.values(BusinessPublishedStatus).map((status) => ({
    value: status.value === 1 ? 'true' : 'false',
    label: status.name,
  })),
);

const businessStatusOptions = formatSelectBoxOptions(
  Object.values(BusinessStatus).map((status) => ({
    value: status.value,
    label: status.name,
  })),
);

const showModal = ref(false);

const setIsModal = (val: boolean): void => {
  showModal.value = val;
};

const onSearchSubmit = () => {
  form.get(route(RouteName.AdminBusinessOperator));
  setIsModal(false);
};
</script>
<template>
  <AdminLayout title="チアペイ管理" text="事業者一覧">
    <div class="p-business-operator">
      <div class="p-business-operator__header">
        <div class="p-business-operator__header-controls">
          <p class="p-business-operator__count">導入店舗数：{{ props.businessCount }}</p>
          <Button :variant="ButtonVariantProp.Derk" @click="setIsModal(true)" text="検索条件" />
        </div>
        <AnkerButton :href="route(RouteName.AdminBusinessOperatorCreate)" text="新規登録" />
      </div>
      <div class="p-business-operator__table-container">
        <table class="p-business-operator__table">
          <thead>
            <tr>
              <th>事業者名</th>
              <th>法人名</th>
              <th>事業形態</th>
              <th>事業状態</th>
              <th>公開状態</th>
              <th>操作</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="business in props.businessList" :key="business.businessName">
              <td>{{ business.businessName }}</td>
              <td>{{ business.corporationName }}</td>
              <td>{{ business.businessForm }}</td>
              <td>{{ getNameByEnumValue(BusinessStatus, Number(business.businessStatus)) }}</td>
              <td>{{ business.isPublish ? '公開' : '非公開' }}</td>
              <td class="p-business-operator__table-actions">
                <Link
                  :href="route(RouteName.AdminBusinessOperatorReview, { businessId: business.businessId })"
                  text="口コミ"
                  class="p-business-operator__table-actions--review"
                />
                <Link
                  :href="route(RouteName.AdminBusinessOperatorShow, { businessId: business.businessId })"
                  text="編集"
                  class="p-business-operator__table-actions--show"
                />
                <Link
                  :href="route(RouteName.AdminBusinessOperatorDelete, { businessId: business.businessId })"
                  text="削除"
                  class="p-business-operator__table-actions--delete"
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
        <div class="p-business-operator__modal-form--input">
          <FormGroupInput
            id="businessName"
            label="事業者名"
            v-model="form.businessName"
            :error="form.errors.businessName"
            class="p-business-operator__modal-form-business-name"
          />
          <FormGroupInput
            id="corporationName"
            label="法人名"
            v-model="form.corporationName"
            :error="form.errors.corporationName"
          />
        </div>

        <div class="p-business-operator__modal-form--select">
          <FormGroupSelectBox
            id="parentCorporationId"
            label="親法人"
            :model-value="form.parentCorporationId"
            :options="parentCorporationOptions"
            class="p-business-operator__modal-form-select-box p-business-operator__modal-form-parent-corporation"
            @update:model-value="(val) => (form.parentCorporationId = val)"
          />
          <FormGroupSelectBox
            id="businessForm"
            label="事業形態"
            :model-value="form.businessForm"
            :options="businessFormOptions"
            class="p-business-operator__modal-form-select-box p-business-operator__modal-form-business-form"
            @update:model-value="(val) => (form.businessForm = val)"
          />
          <FormGroupSelectBox
            id="isPublish"
            label="公開状況"
            :model-value="form.isPublish"
            :options="isPublishOptions"
            class="p-business-operator__modal-form-select-box p-business-operator__modal-form-is-publish"
            @update:model-value="(val) => (form.isPublish = val)"
          />
          <FormGroupSelectBox
            id="businessStatus"
            label="稼働状況"
            :model-value="form.businessStatus"
            :options="businessStatusOptions"
            class="p-business-operator__modal-form-select-box p-business-operator__modal-form-business-status"
            @update:model-value="(val) => (form.businessStatus = val)"
          />
        </div>
        <Button
          :variant="ButtonVariantProp.Primary"
          text="検索"
          class="p-business-operator__modal-form-search--button"
          @click="onSearchSubmit"
        />
      </Modal>
    </div>
  </AdminLayout>
</template>
<style lang="scss" scoped>
.p-business-operator {
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
        display: flex;
        justify-content: space-between;
        margin-top: 16px;
        margin-bottom: 16px;
      }
      &--select {
        display: flex;
        justify-content: flex-start;
        flex-direction: column;
      }
      &-select-box {
        margin-bottom: 16px;
        background: #fff;
      }
      &-label {
        margin-bottom: 8px;
      }
      &-search--button {
        width: 95%;
        margin: 16px auto;
      }
    }
  }
}
</style>
