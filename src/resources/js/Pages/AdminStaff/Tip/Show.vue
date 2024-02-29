<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName, getBinaryData, getFileImageData, getFileVideoData } from '@/Utilities';
import Divider from '@/Components/atom/divider/Divider.vue';
import ColorSection from '@/Components/atom/section/ColorSection/ColorSection.vue';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
import Link from '@/Components/atom/link/Link/Link.vue';
import FormGroupTextarea from '@/Components/molecules/form/FormGroupTextarea/FormGroupTextarea.vue';
import Button from '@/Components/atom/button/Button/Button.vue';
import { useForm } from '@inertiajs/vue3';
import MessageFixPhraseButton from '@/Components/organizms/message/MessageFixPhraseButton/MessageFixPhraseButton.vue';
import MessageChatGPTButton from '@/Components/organizms/message/MessageChatGPTButton/MessageChatGPTButton.vue';
import MessageFileButton from '@/Components/organizms/message/MessageFileButton/MessageFileButton.vue';
import { ref, reactive, computed } from 'vue';
import { ResponseTipShow, SsrPostStaffTipsTipIdRequest } from '@/api';
import CheerHistoryChat from '@/Components/presentationals/CheerHistory/CheerHistoryChat.vue';
import { MessageFileButtonProps } from '@/Components/organizms/message/MessageFileButton/type';
import NoImageUser from '@/../assets/images/noimage/noimage_user.png';

const props = defineProps<ResponseTipShow>();

const isGuestUser = computed<boolean>(() => props.userTip.userId === 1);

const limit = ref(props.aiCount);
const setLimit = (count: number): void => {
  limit.value = count;
};

const form = useForm<SsrPostStaffTipsTipIdRequest>({
  message: '',
  file: null,
});

const deleteForm = useForm({});
const onDelete = (): void => {
  if (!props.staffTipReply || deleteForm.processing) return;
  deleteForm.delete(
    route(RouteName.AdminStaffTipsDelete, {
      tipId: props.userTip.tipId,
      replyId: props.staffTipReply.replyId,
    }),
  );
};

const onSubmit = (): void => {
  form.post(route(RouteName.AdminStaffTipsCreate, { tipId: props.userTip.tipId }), {
    onSuccess: () => {
      form.reset();
      preview.previewType = null;
      preview.previewSrc = null;
    },
  });
};

const setFormMessage = (message: string): void => {
  form.message = message;
};

const setFormFile = (file: File | null): void => {
  form.file = file;
};

const preview = reactive<MessageFileButtonProps>({
  previewType: null,
  previewSrc: null,
});

const setPreview = (type: MessageFileButtonProps['previewType'], src: MessageFileButtonProps['previewSrc']): void => {
  preview.previewType = type;
  preview.previewSrc = src;
};

const onClickResetFile = () => {
  setFormFile(null);
  setPreview(null, null);
};

const onChangeImage = (e: Event) => {
  const file = getFileImageData(e);
  if (!file) return;
  setFormFile(file);
  getBinaryData(file, (binary: string) => setPreview('image', binary));
};

const onChangeVideo = (e: Event) => {
  const file = getFileVideoData(e);
  if (!file) return;
  setFormFile(file);
  getBinaryData(file, (binary: string) => setPreview('video', binary));
};
</script>

<template>
  <BaseLayout
    title="ユーザー名"
    :is-auth="true"
    :role="RouteRoleName.AdminStaff"
    :auth-header="{
      role: RouteRoleName.AdminStaff,
      text: props.userTip.userName,
      href: RouteName.AdminStaffTips,
    }"
  >
    <div class="p-cheer-history-detail">
      <CheerHistoryChat
        :processing="deleteForm.processing"
        :user-tip="{
          points: props.userTip.points,
          createdAt: props.userTip.createdAt,
          image: props.userTip.image || NoImageUser,
          message: props.userTip.message,
        }"
        :staff-tip-reply="
          props.staffTipReply
            ? {
                createdAt: props.staffTipReply.createdAt,
                image: props.staffTipReply.image || NoImageUser,
                message: props.staffTipReply.message,
                messageSrc: props.staffTipReply.messageSrc ?? undefined,
                messageSrcType: props.staffTipReply.messageSrcType,
              }
            : null
        "
        @delete-reply="onDelete"
      />
      <template v-if="!(props.staffTipReply || isGuestUser)">
        <Divider />
        <div class="p-cheer-history-detail__form-title">お礼メッセージ</div>
        <Divider />
        <ColorSection class="p-cheer-history-detail__form">
          <FormGroupTextarea
            id="message"
            class="p-cheer-history-detail__textarea"
            placeholder="メッセージを送って感謝の気持ちを伝えよう！"
            v-model="form.message"
            :error="form.errors.message"
            :is-count="true"
            :count-limit="100"
          />
          <MessageFixPhraseButton class="p-cheer-history-detail__fix-phrase" @click="setFormMessage" />
          <MessageChatGPTButton
            class="p-cheer-history-detail__ai"
            @click="
              (data) => {
                setFormMessage(data.message);
                setLimit(data.aiCount);
              }
            "
            :limit="limit"
            :tip-id="props.userTip.tipId"
          />
          <MessageFileButton
            class="p-cheer-history-detail__file"
            :preview-src="preview.previewSrc"
            :preview-type="preview.previewType"
            @change-image="onChangeImage"
            @change-video="onChangeVideo"
            @click-reset="onClickResetFile"
          />
          <Paragraph class="p-cheer-history-detail__message">
            他の人を傷つける恐れのある内容や個人を特定す るコメントは固く禁止されております。 詳しくは
            <Link text="ガイドライン" :href="RouteName.StaffMyPage" />
            をご確認ください。
          </Paragraph>
          <Button @click="onSubmit" text="送信する" :is-loading="form.processing" />
        </ColorSection>
      </template>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-cheer-history-detail {
  &__form-title {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 60px;
    font-size: 18px;
    color: var(--black);
    font-weight: var(--bold);
  }
  &__form {
    display: flex;
    flex-direction: column;
  }
  &__textarea,
  &__ai,
  &__message,
  &__fix-phrase,
  &__file {
    margin-bottom: 16px;
  }
}
</style>
