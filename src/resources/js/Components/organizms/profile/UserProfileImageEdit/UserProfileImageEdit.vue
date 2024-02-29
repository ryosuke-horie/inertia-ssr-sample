<script lang="ts" setup>
import { ref } from 'vue';
import LoadingModal from '@/Components/atom/loading/LoadingModal/LoadingModal.vue';
import ProfileImageTop from '@/Components/molecules/image/ProfileImageTop/ProfileImageTop.vue';
import ColorSection from '@/Components/atom/section/ColorSection/ColorSection.vue';
import { UserProfileImageEditProps } from './type';
import Button from '@/Components/atom/button/Button/Button.vue';
import FormInputFileButton from '@/Components/atom/form/FormInputFileButton/FormInputFileButton.vue';
import { ButtonVariantProp } from '@/Components/atom/button/Button/type';
import Modal from '@/Components/molecules/modal/Modal/Modal.vue';

const props = defineProps<UserProfileImageEditProps>();

const selectedOrder = ref<boolean>(false);

const onClickSelectOrder = (val: boolean): void => {
  selectedOrder.value = val;
};

const emit = defineEmits<{
  updateFile: [file: Event];
  deleteFile: [];
}>();

const onClickUpdateFile = (file: Event): void => {
  emit('updateFile', file);
  selectedOrder.value = false;
};
</script>

<template>
  <LoadingModal v-if="false" />
  <div class="p-profile-image-edit">
    <ColorSection class="p-profile-image-edit__images">
      <ProfileImageTop :src="props?.src" :alt="props?.alt" @click="onClickSelectOrder(true)" />
    </ColorSection>
    <Modal
      v-if="selectedOrder"
      header-text="プロフィール画像編集"
      class="p-profile-image-edit__modal"
      @close="onClickSelectOrder(false)"
    >
      <figure v-if="props.src" class="p-profile-image-edit__modal-image">
        <img :src="props.src" alt="" />
      </figure>
      <div class="p-profile-image-edit__modal-buttons">
        <FormInputFileButton
          :id="`file-${selectedOrder}`"
          text="写真・画像を選択"
          accept="image/png, image/jpeg"
          @change="onClickUpdateFile($event)"
        />
        <Button
          :variant="ButtonVariantProp.Secondary"
          :is-outlined="true"
          text="キャンセル"
          @click="onClickSelectOrder(false)"
        />
      </div>
    </Modal>
  </div>
</template>

<style lang="scss">
.p-profile-image-edit {
  &__images {
    &-wrap {
      display: flex;
      justify-content: center;
      margin-top: 30px;
      > :first-of-type {
        margin-right: 20px;
      }
    }
  }
  &__modal {
    &-image {
      width: 100%;
      height: 280px;
      margin: 0;
      margin-bottom: 32px;
      img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        vertical-align: top;
      }
    }
    &-buttons {
      display: grid;
      grid-row-gap: 16px;
    }
  }
}
</style>
