<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName, getFileImageData } from '@/Utilities';
import FormGroupInput from '@/Components/molecules/form/FormGroupInput/FormGroupInput.vue';
import { FormRequiredLabelTypeProp } from '@/Components/atom/form/FormRequiredLabel/type';
import Button from '@/Components/atom/button/Button/Button.vue';
import ProfileImageEdit from '@/Components/organizms/profile/ProfileImageEdit/ProfileImageEdit.vue';
import LoadingModal from '@/Components/atom/loading/LoadingModal/LoadingModal.vue';
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { OrderEnum, ResponseStaffProfile, SsrStaffProfileUpdateRequest } from '@/api';
import { configuration } from '@/lib/configuration';
import { BUSINESSOPERATORApi } from '@/api';
import AnkerSettingListItem from '@/Components/molecules/list/AnkerSettingListItem/AnkerSettingListItem.vue';
import Divider from '@/Components/atom/divider/Divider.vue';

const props = defineProps<ResponseStaffProfile>();

const form = useForm<SsrStaffProfileUpdateRequest>({
  staffName: props.staffName,
  comment: props.comment || '',
});

const onSubmit = (): void => {
  if (form.processing) return;
  form.patch(route(RouteName.BusinessOperatorStaffProfileUpdate, { staffId: props.staffId }));
};

const isLoading = ref(false);
const profileImages = ref(props.images);

const updateFile = async (order: OrderEnum | null, file: Event): Promise<void> => {
  if (isLoading.value) return;
  if (!order) return;
  const fileData = getFileImageData(file);
  if (!fileData) return;
  isLoading.value = true;
  await new BUSINESSOPERATORApi(configuration)
    .postBusinessOperatorStaffStaffIdProfileImage(props.staffId, fileData, order)
    .then((res) => {
      profileImages.value = res.data.images;
    })
    .catch(() => {
      alert('予期せぬエラーが発生しました。');
    })
    .finally(() => {
      isLoading.value = false;
    });
};

const deleteFile = async (order: OrderEnum): Promise<void> => {
  if (isLoading.value) return;
  isLoading.value = true;
  await new BUSINESSOPERATORApi(configuration)
    .deleteBusinessOperatorStaffStaffIdProfileImage(props.staffId, { order })
    .then((res) => {
      profileImages.value = res.data.images;
    })
    .finally(() => {
      isLoading.value = false;
    });
};

const computedImages = computed(() => {
  return profileImages.value.map((image) => {
    return {
      src: image.image ?? '',
      order: image.order,
    };
  });
});
</script>

<template>
  <LoadingModal v-if="isLoading" />
  <BaseLayout
    title="スタッフ詳細"
    :is-auth="true"
    :role="RouteRoleName.Business"
    :auth-header="{
      text: 'スタッフ詳細',
      href: RouteName.BusinessOperatorStaff,
    }"
  >
    <div class="p-profile">
      <ProfileImageEdit :images="computedImages" @update-file="updateFile" @delete-file="deleteFile" />
      <form @submit.prevent="onSubmit" class="p-profile__form">
        <FormGroupInput
          id="staff_name"
          label="ニックネーム"
          v-model="form.staffName"
          :required-type="FormRequiredLabelTypeProp.Required"
          :error="form.errors.staffName"
          class="p-profile__staffname"
        />
        <FormGroupInput
          id="comment"
          label="一言コメント"
          annotation="一言コメントは、100文字以内で入力してください。てきすとてきすとてきすとてきすとてきすと"
          :is-count="true"
          :count-limit="100"
          v-model="form.comment"
          :error="form.errors.comment"
          class="p-profile__comment"
        />
        <Button
          class="p-profile__submit"
          :disabled="form.processing"
          :is-loading="form.processing"
          loading-text="Uploading"
          text="プロフィール更新"
        />
      </form>
      <Divider />
      <AnkerSettingListItem
        text="ポイント変動履歴"
        :href="route(RouteName.BusinessOperatorStaffProfilePointHistory, { staffId: props.staffId })"
      />
      <AnkerSettingListItem
        text="メールアドレス変更"
        :href="route(RouteName.BusinessOperatorStaffProfileChangeEmailShow, { staffId: props.staffId })"
      />
      <AnkerSettingListItem
        text="各種設定"
        :href="route(RouteName.BusinessOperatorStaffProfileSettingShow, { staffId: props.staffId })"
      />
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-profile {
  &__form {
    padding: 24px 16px;
    display: flex;
    flex-direction: column;
  }
  &__staffname,
  &__comment {
    margin-bottom: 30px;
  }
}
</style>
