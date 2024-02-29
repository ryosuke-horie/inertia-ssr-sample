<script lang="ts" setup>
import { computed } from 'vue';
import { UserStaffListItemProps } from './type';
import { Link } from '@inertiajs/vue3';
import Image from '@/Components/atom/image/Image/Image.vue';
import Divider from '@/Components/atom/divider/Divider.vue';
import NoImageUser from '@/../assets/images/noimage/noimage_user.png';

const props = defineProps<UserStaffListItemProps>();

// アイコン定義
const buttons = [
  { id: 'work', value: 1, icon: 'fa-regular fa-circle', text: '出勤' },
  { id: 'holiday', value: 2, icon: 'fa-solid fa-xmark', text: '休日' },
  { id: 'notYet', value: 3, icon: 'fa-solid fa-question', text: '未定' },
];

// スケジュールが無い場合はデフォルトボタン
const selectedButton = computed(() => {
  return getButtonByValue(props.staff.todayShiftStatus) || buttons[2];
});

function getButtonByValue(value: number): { id: string; value: number; icon: string; text: string } | undefined {
  return buttons.find((button) => button.value === value);
}

const emit = defineEmits(['toggleFavorite']);

const onClickToggleFavorite = (e: Event, staff: object) => {
  e.preventDefault();
  e.stopPropagation();
  emit('toggleFavorite', staff);
};
</script>

<template>
  <li>
    <Link :href="props.href" class="c-user-staff-list-item__staff-list-item">
      <div class="c-user-staff-list-item__details">
        <div class="c-user-staff-list-item__image-container">
          <Image
            :src="props.images.length && $props.images[0].image ? $props.images[0].image : NoImageUser"
            :width="`100%`"
            :height="`100%`"
            :alt="`Image of ${props.staffName}`"
            class="c-user-staff-list-item__image"
          />
        </div>
        <div class="c-user-staff-list-item__favorite" v-if="!props.isGuestUser">
          <p class="c-user-staff-list-item__name">{{ props.staffName }}</p>
          <p v-if="props.favoriteId" @click="(e) => onClickToggleFavorite(e, props.staff)">
            <i class="fa-solid fa-star aaa"></i><span class="c-user-staff-list-item__cancell-text">解除</span>
          </p>
          <p v-else @click="(e) => onClickToggleFavorite(e, props.staff)">
            <i class="fa-regular fa-star"></i>お気に入り
          </p>
        </div>
        <div class="c-user-staff-list-item__favorite" v-else>
          <p class="c-user-staff-list-item__name">{{ props.staffName }}</p>
        </div>
      </div>
      <div class="c-user-staff-list-item__status">
        <i :class="`c-user-staff-list-item__status-icon ${selectedButton.icon}`"></i>
        <p class="c-user-staff-list-item__status-text">{{ selectedButton.text }}</p>
      </div>
    </Link>
    <Divider />
  </li>
</template>

<style lang="scss">
.c-user-staff-list-item {
  &__staff-list-item {
    color: var(--black);
    width: 100%;
    height: 100%;
    display: flex;
    padding: 10px;
    justify-content: space-between;
  }
  &__details {
    display: flex;
    align-items: center;
    gap: 10px;
  }
  &__image img {
    width: 100px;
    height: 100px;
    object-fit: cover;
  }
  &__status {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 10px;
    border-left: 1px solid #ddd;
    padding-left: 20px;
    padding-right: 10px;
  }
  &__name {
    font-weight: bold;
    margin-bottom: 5px;
  }
}
.fa-star {
  font-size: 20px;
  transition: all 0.5s 0s ease;
}
.fa-solid.fa-star {
  color: var(--yellow);
}
.fa-regular.fa-circle {
  color: #1d9bf0;
}
.fa-solid.fa-xmark {
  color: #ff0000;
}
.fa-solid.fa-question {
  color: #f0850e;
}
</style>
