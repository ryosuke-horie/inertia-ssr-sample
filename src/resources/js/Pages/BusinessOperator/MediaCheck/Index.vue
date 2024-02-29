<script setup lang="ts">
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
import IconImage from '@/Components/atom/image/IconImage/IconImage.vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps<{
  businessName: string;
  dateList: {
    date: string;
    staffList: {
      tipId: number;
      mediaId: number;
      staffName: string;
      staffFileName: string;
      userName: string;
      replyFileName: string;
      replyFileType: string;
    }[];
  }[];
}>();

const form = useForm<{ mediaId: number | string }>({
  mediaId: '',
});

const deleteMedia = (mediaId: number) => {
  if (window.confirm('本当に削除しますか？')) {
    form.mediaId = mediaId;
    form.delete(route(RouteName.BusinessOperatorMediaCheckDelete));
  }
};
</script>

<template>
  <BaseLayout
    title="チアペイ"
    :is-auth="true"
    :role="RouteRoleName.Business"
    :auth-header="{
      text: props.businessName,
      href: RouteName.BusinessOperatorMypage,
    }"
  >
    <div class="p-business-operator-image-check">
      <Paragraph v-if="props.dateList.length == 0">投稿している動画・画像はございません。</Paragraph>
      <div
        v-for="(date, dateIndex) in props.dateList"
        :key="dateIndex"
        class="p-business-operator-image-check__date-container"
      >
        <div class="p-business-operator-image-check__date-container__date">{{ date.date }}</div>
        <div class="p-business-operator-image-check__date-container__staff-list">
          <div
            v-for="(staff, staffIndex) in date.staffList"
            :key="staffIndex"
            class="p-business-operator-image-check__date-container__staff-list__container"
          >
            <div class="p-business-operator-image-check__date-container__staff-list__container__profile">
              <IconImage
                :src="staff.staffFileName"
                :width="40"
                :height="40"
                class="p-business-operator-image-check__date-container__staff-list__container__profile__icon"
              />
              <div class="p-business-operator-image-check__date-container__staff-list__container__profile__name">
                <Paragraph>{{ staff.staffName }}</Paragraph>
                <Paragraph>返信先：{{ staff.userName }}</Paragraph>
              </div>
            </div>
            <div class="p-business-operator-image-check__date-container__staff-list__container__content">
              <img
                v-if="staff.replyFileType != 'mp4'"
                src="https://www.mamiya-its.co.jp/public/images/1_top/topimage_sp_b.jpg"
                alt="動画・画像"
                loading="lazy"
                class="p-business-operator-image-check__date-container__staff-list__container__content__image"
              />
              <video
                v-if="staff.replyFileType == 'mp4'"
                class="p-business-operator-image-check__date-container__staff-list__container__content__image"
                loading="lazy"
                controls
              >
                <source src="https://192.168.80.32:8080/storage/video/test.mp4" type="video/mp4" />
              </video>
              <div class="p-business-operator-image-check__date-container__staff-list__container__content__button">
                <button
                  class="p-business-operator-image-check__date-container__staff-list__container__content__button__delete"
                  @click="deleteMedia(staff.mediaId)"
                >
                  データ削除
                </button>
                <Link
                  :href="route(RouteName.BusinessOperatorStaffTipsShow, { tipId: staff.tipId })"
                  class="p-business-operator-image-check__date-container__staff-list__container__content__button__link"
                  >投稿を確認 ></Link
                >
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-business-operator-image-check {
  &__date-container {
    &__date {
      padding: 10px 20px;
      background-color: #eeeeee;
    }
    &__staff-list {
      padding: 20px 30px;
      &__container {
        padding-bottom: 30px;
        &__profile {
          display: flex;
          align-items: center;
          &__name {
            padding-left: 20px;
            font-size: 13px;
          }
        }
        &__content {
          display: flex;
          align-items: flex-end;
          padding-top: 10px;
          &__image {
            width: 210px;
          }
          &__button {
            padding-left: 30px;
            font-size: 12px;
            &__delete {
              border: 1px solid #ccc;
              border-radius: 5px;
              padding: 3px 6px;
            }
            &__link {
              display: block;
              padding-top: 20px;
            }
          }
        }
      }
    }
  }
}
</style>
