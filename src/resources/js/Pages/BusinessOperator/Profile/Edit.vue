<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName, getFileImageData } from '@/Utilities';
import FormGroupInput from '@/Components/molecules/form/FormGroupInput/FormGroupInput.vue';
import Button from '@/Components/atom/button/Button/Button.vue';
import ProfileImageEdit from '@/Components/organizms/profile/ProfileImageEdit/ProfileImageEdit.vue';
import Link from '@/Components/atom/link/Link/Link.vue';
import LoadingModal from '@/Components/atom/loading/LoadingModal/LoadingModal.vue';
import Divider from '@/Components/atom/divider/Divider.vue';
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import type { ProfileProps } from './type';
import DefinitionListItem from '@/Components/molecules/list/DefinitionListItem/DefinitionListItem.vue';
import { BUSINESSOPERATORApi, OrderEnum } from '@/api';
import { configuration } from '@/lib/configuration';

const props = defineProps<ProfileProps>();

const isLoading = ref(false);
const profileImages = ref(props.images);

const form = useForm({
  business_description: props.businessDescription || '',
});

const onSubmit = (): void => {
  form.patch(route(RouteName.BusinessOperatorProfileUpdate));
};

const updateFile = async (order: OrderEnum | null, file: Event): Promise<void> => {
  if (isLoading.value) return;
  if (!order) return;
  const fileData = getFileImageData(file);
  if (!fileData) return;
  isLoading.value = true;
  await new BUSINESSOPERATORApi(configuration)
    .postBusinessOperatorProfileImage(fileData, order)
    .then((res) => {
      profileImages.value = res.data.images;
    })
    .finally(() => {
      isLoading.value = false;
    });
};

const deleteFile = async (order: OrderEnum): Promise<void> => {
  if (isLoading.value) return;
  isLoading.value = true;
  await new BUSINESSOPERATORApi(configuration)
    .deleteBusinessOperatorProfileImage({ order })
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
    title="プロフィール"
    :is-auth="true"
    :role="RouteRoleName.Business"
    :auth-header="{
      text: 'プロフィール',
      href: RouteName.BusinessOperatorMypage,
    }"
  >
    <div class="p-profile">
      <ProfileImageEdit :images="computedImages" @update-file="updateFile" @delete-file="deleteFile" />
      <form @submit.prevent="onSubmit" class="p-profile__form">
        <FormGroupInput
          id="business_description"
          label="店舗コメント"
          annotation="※300文字以内"
          :is-count="true"
          :count-limit="100"
          v-model="form.business_description"
          :error="form.errors.business_description"
          class="p-profile__business_description"
        />
        <div class="p-profile__form__container">
          <DefinitionListItem label="法人名" :content="props.corporationName" class="p-profile__definition-list-item" />
          <Divider class="p-profile__form__divider" />
        </div>
        <div class="p-profile__form__container">
          <DefinitionListItem label="店舗名" :content="props.businessName" class="p-profile__definition-list-item" />
          <Divider class="p-profile__form__divider" />
        </div>
        <div class="p-profile__form__link-container">
          <DefinitionListItem label="メールアドレス" :content="props.email" class="p-profile__definition-list-item" />
          <Divider class="p-profile__form__divider" />
          <Link
            text="メールアドレスを変更する >"
            :href="RouteName.BusinessOperatorProfileChangeEmailShow"
            class="p-profile__form__change-link"
          />
        </div>
        <div class="p-profile__form__link-container">
          <DefinitionListItem label="パスワード" content="＊＊＊＊＊＊＊＊" class="p-profile__definition-list-item" />
          <Divider class="p-profile__form__divider" />
          <Link
            text="パスワードを変更する >"
            :href="RouteName.BusinessOperatorProfileChangePasswordShow"
            class="p-profile__form__change-link"
          />
        </div>
        <Button class="p-profile__form__submit" :disabled="form.processing" text="プロフィール更新" />
      </form>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-profile {
  &__form {
    width: 90%;
    margin: 20px auto;
    &__container {
      margin-top: 20px;
      margin-bottom: 35px;
    }
    &__link-container {
      margin-top: 20px;
    }
    &__divider {
      border-bottom: 1px solid #000000;
    }
    &__change-link {
      width: 100%;
      text-align: right;
      margin-top: 5px;
    }
    &__submit {
      width: 100%;
      margin-top: 20px;
      font-size: 18px;
      font-weight: 700;
      letter-spacing: -0.015em;
    }
  }
}
</style>
