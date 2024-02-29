<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName, getFileImageData } from '@/Utilities';
import FormGroupInput from '@/Components/molecules/form/FormGroupInput/FormGroupInput.vue';
import { FormRequiredLabelTypeProp } from '@/Components/atom/form/FormRequiredLabel/type';
import Button from '@/Components/atom/button/Button/Button.vue';
import UserProfileImageEdit from '@/Components/organizms/profile/UserProfileImageEdit/UserProfileImageEdit.vue';
import AnkerSettingListItem from '@/Components/molecules/list/AnkerSettingListItem/AnkerSettingListItem.vue';
import LoadingModal from '@/Components/atom/loading/LoadingModal/LoadingModal.vue';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Divider from '@/Components/atom/divider/Divider.vue';
import { configuration } from '@/lib/configuration';
import { GUESTApi } from '@/api';

const props = defineProps<{ userId: number; nickname: string; birthdate: string; userProfileImage: string }>();

const form = useForm<{ nickname: string }>({
  nickname: props.nickname,
});

const onSubmit = (): void => {
  if (form.processing) return;
  form.patch(route(RouteName.UserProfileUpdate));
};

const isLoading = ref(false);
const userProfileImage = ref<string>(props.userProfileImage);

const updateFile = async (file: Event): Promise<void> => {
  if (isLoading.value) return;
  const fileData = getFileImageData(file);
  if (!fileData) return;
  isLoading.value = true;
  await new GUESTApi(configuration)
    .postUserProfileImage(fileData)
    .then((res) => {
      userProfileImage.value = res.data.userProfileImage;
    })
    .finally(() => {
      isLoading.value = false;
    });
};
</script>

<template>
  <LoadingModal v-if="isLoading" />
  <BaseLayout
    title="プロフィール"
    :is-auth="true"
    :role="RouteRoleName.User"
    :auth-header="{
      text: 'プロフィール',
      href: RouteName.UserMypage,
    }"
  >
    <div class="p-profile">
      <UserProfileImageEdit :src="userProfileImage" :alt="props.nickname" @update-file="updateFile" />
      <form @submit.prevent="onSubmit" class="p-profile__form">
        <FormGroupInput
          id="nick_name"
          label="ニックネーム"
          v-model="form.nickname"
          :required-type="FormRequiredLabelTypeProp.Required"
          :error="form.errors.nickname"
          class="p-profile__nickname"
        />
        <div class="p-profile__dummy-form">
          <div class="p-profile__dymmy-form-label">誕生日</div>
          <div class="p-profile__dymmy-form-value">{{ props.birthdate }}</div>
          <div class="p-profile__dymmy-form-annotation">一度登録された生年月日は変更できません</div>
        </div>
        <Button
          class="p-profile__submit"
          :disabled="form.processing"
          :is-loading="form.processing"
          loading-text="Uploading"
          text="プロフィール更新"
        />
      </form>
      <Divider />
      <AnkerSettingListItem text="メールアドレス変更" :href="route(RouteName.UserProfileChangeEmailShow)" />
      <AnkerSettingListItem text="お支払い方法・カード情報変更" :href="route(RouteName.UserPaymentInfoShow)" />
      <AnkerSettingListItem text="ランキング表示設定" :href="route(RouteName.UserProfileSettingShow)" />
      <AnkerSettingListItem text="パスワード変更" :href="route(RouteName.UserProfileChangePasswordShow)" />
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
  &__nickname {
    margin-bottom: 30px;
  }
  &__dymmy-form {
    &-label {
      font-size: 16px;
      margin-bottom: 8px;
      font-weight: var(--bold);
    }
    &-value {
      line-height: 50px;
      height: 50px;
      border: solid 1px;
      border-color: #dddddd;
      border-radius: 4px;
      padding: 0 16px;
      max-width: 100%;
      color: #000000;
      background-color: #eeeeee;
    }
    &-annotation {
      text-align: right;
      margin-top: 8px;
      font-size: 12px;
      color: #666666;
    }
  }
  &__submit {
    margin-top: 30px;
  }
}
</style>
