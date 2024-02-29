<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName, generateUUID } from '@/Utilities';
import Button from '@/Components/atom/button/Button/Button.vue';
import FormGroupInput from '@/Components/molecules/form/FormGroupInput/FormGroupInput.vue';
import { FormRequiredLabelTypeProp } from '@/Components/atom/form/FormRequiredLabel/type';
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import Alert from '@/Components/atom/alert/Alert.vue';

interface FormData {
  staffs: { id: string; staffName: string; email: string }[];
}

const form = useForm<FormData>({
  staffs: [{ id: generateUUID(), staffName: '', email: '' }],
});

const formErrors = computed<{ [key: string]: string }>(() => {
  return form.errors;
});

const onClickAddStaff = () => {
  const id = generateUUID();
  form.staffs.push({ id, staffName: '', email: '' });
};

const onClickDeleteStaff = (id: string) => {
  form.staffs = form.staffs.filter((item) => item.id !== id);
};

const onClickRegister = () => {
  if (form.processing) return;
  form.post(route(RouteName.BusinessOperatorStaffAddCreate));
};
</script>

<template>
  <BaseLayout
    title="スタッフ登録"
    :is-auth="true"
    :role="RouteRoleName.Business"
    :auth-header="{
      text: 'スタッフ登録',
      href: RouteName.BusinessOperatorStaff,
    }"
  >
    <div class="p-business-operator-staff-create-staff">
      <div v-if="form.errors.staffs" class="p-business-operator-staff-create-staff__alert">
        <Alert :text="form.errors.staffs" />
      </div>
      <form v-for="(item, index) of form.staffs" :key="index" class="p-business-operator-staff-create-staff__form">
        <div class="p-business-operator-staff-create-staff__form-item">
          <FormGroupInput
            :id="`create-staff-name-${index}`"
            type="text"
            label="スタッフ名"
            :required-type="FormRequiredLabelTypeProp.Required"
            v-model="item.staffName"
            :error="formErrors[`staffs.${item.id}.staffName`]"
            class="p-business-operator-staff-create-staff__form-name"
          />
          <FormGroupInput
            :id="`create-staff-email-${index}`"
            type="text"
            label="メールアドレス"
            :required-type="FormRequiredLabelTypeProp.Any"
            v-model="item.email"
            :error="formErrors[`staffs.${item.id}.email`]"
            class="p-business-operator-staff-create-staff__form-email"
          />
          <button
            v-if="form.staffs.length > 1"
            class="p-business-operator-staff-create-staff__form-delete"
            type="button"
            @click="onClickDeleteStaff(item.id)"
          >
            <i class="fa-solid fa-trash"></i>
            削除
          </button>
        </div>
      </form>
      <div class="p-business-operator-staff-create-staff__buttons">
        <Button
          class="p-business-operator-staff-create-staff__buttons-add"
          text="スタッフ追加"
          @click="onClickAddStaff"
        />
        <Button
          class="p-business-operator-staff-create-staff__buttons-register"
          text="登録"
          @click="onClickRegister"
          :is-loading="form.processing"
        />
      </div>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-business-operator-staff-create-staff {
  &__alert {
    padding: 24px 16px 0;
  }
  &__form {
    display: flex;
    flex-direction: column;
    &-item {
      display: flex;
      flex-direction: column;
      padding: 24px 16px;
      border-bottom: solid 1px #ddd;
    }
    &-email {
      margin-top: 16px;
    }
    &-delete {
      display: inline-block;
      margin-top: 16px;
      margin-left: auto;
    }
  }
  &__buttons {
    display: flex;
    flex-direction: column;
    padding: 24px 16px;
    &-add {
      height: 40px !important;
      background-color: #fff !important;
      color: #000 !important;
      border-color: #666 !important;
    }
    &-register {
      margin-top: 24px;
    }
  }
}
</style>
