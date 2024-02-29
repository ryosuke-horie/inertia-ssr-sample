<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName, getDayOfWeek } from '@/Utilities';
import LoadingModal from '@/Components/atom/loading/LoadingModal/LoadingModal.vue';
import { ref, reactive } from 'vue';
import ScheduleListItem from '@/Components/molecules/list/ScheduleListItem/ScheduleListItem.vue';
import Button from '@/Components/atom/button/Button/Button.vue';
import { configuration } from '@/lib/configuration';
import { BUSINESSOPERATORApi, ResponseStaffSchedules, StaffListItem } from '@/api';
import Divider from '@/Components/atom/divider/Divider.vue';
import EmptySection from '@/Components/atom/section/EmptySection/EmptySection.vue';
import ScheduleStaffListButton from '@/Components/organizms/schedule/ScheduleStaffListButton/ScheduleStaffListButton.vue';
import FlashMessage from '@/Components/molecules/flash/FlashMessage/FlashMessage.vue';
import { PageProps } from '@/index';

const flashMessages = ref<PageProps['flash'][]>([]);
const isLoading = ref<boolean>(false);
const isOpen = ref<boolean>(false);
const isStaffListLoading = ref<boolean>(false);
const isSubmitLoading = ref<boolean>(false);
const staffId = ref<number>(0);
const staffList = ref<StaffListItem[]>([]);
const schedulesData = reactive<ResponseStaffSchedules>({
  startDate: '',
  endDate: '',
  schedules: [],
});

const setStaffId = (val: number): void => {
  staffId.value = val;
};

const setIsOpen = async (): Promise<void> => {
  isOpen.value = !isOpen.value;
  if (isOpen.value) await fetchStaffList();
};

const fetchStaffList = async (): Promise<void> => {
  if (isStaffListLoading.value) return;
  isStaffListLoading.value = true;
  await new BUSINESSOPERATORApi(configuration)
    .getBusinessOperatorStaff()
    .then((res) => {
      staffList.value = res.data.staffList;
    })
    .catch(() => {
      flashMessages.value.push({ type: 'failed', text: '予期せぬエラーが発生しました' });
      setIsOpen();
    })
    .finally(() => {
      isStaffListLoading.value = false;
    });
};

const fetchStaffSchedules = async (staffId: number): Promise<void> => {
  if (isLoading.value) return;
  isLoading.value = true;
  await new BUSINESSOPERATORApi(configuration)
    .getBusinessOperatorStaffStaffIdSchedules(staffId)
    .then((res) => {
      Object.assign(schedulesData, { ...res.data });
      setStaffId(staffId);
    })
    .catch(() => {
      flashMessages.value.push({ type: 'failed', text: '予期せぬエラーが発生しました' });
    })
    .finally(() => {
      isLoading.value = false;
      setIsOpen();
    });
};

const onSubmit = async () => {
  if (!staffId.value) return;
  if (!schedulesData.schedules.length) return;

  isSubmitLoading.value = true;
  await new BUSINESSOPERATORApi(configuration)
    .postBusinessOperatorStaffStaffIdSchedules(staffId.value, { schedules: schedulesData.schedules })
    .then((res) => {
      Object.assign(schedulesData, { ...res.data });
      flashMessages.value.push({ type: 'success', text: 'スケジュールを更新しました。' });
    })
    .catch(() => {
      flashMessages.value.push({ type: 'failed', text: '予期せぬエラーが発生しました。もう一度お試しください。' });
    })
    .finally(() => {
      isSubmitLoading.value = false;
      isOpen.value = false;
    });
};
</script>

<template>
  <LoadingModal v-if="isLoading" />
  <template v-for="(item, index) of flashMessages" :key="item + index">
    <FlashMessage v-if="item" :message="item.text" :variant="item.type" />
  </template>
  <BaseLayout
    title="出勤スケジュール"
    :is-auth="true"
    :role="RouteRoleName.Business"
    :auth-header="{
      text: '出勤スケジュール',
      href: RouteName.BusinessOperatorStaffSelect,
    }"
  >
    <div class="p-business-operator-staff-schedules">
      <ScheduleStaffListButton
        :is-open="isOpen"
        :is-loading="isStaffListLoading"
        :staff-id="staffId"
        :staff-list="staffList"
        @click-button="setIsOpen"
        @click-staff-id="fetchStaffSchedules"
      />
      <template v-if="schedulesData.schedules.length">
        <div v-if="schedulesData.startDate" class="p-business-operator-staff-schedules__start-end">
          {{ schedulesData.startDate }}～{{ schedulesData.endDate }}
        </div>
        <Divider />
        <ul class="p-business-operator-staff-schedules__list">
          <ScheduleListItem
            v-for="(item, index) of schedulesData.schedules"
            :key="`${item.date}-${index}`"
            :date="item.date"
            :day-of-week="getDayOfWeek(item.dayOfWeek)"
            v-model="item.status"
          />
        </ul>
        <div class="p-business-operator-staff-schedules__button">
          <Button class="button" text="登録" :is-loading="isSubmitLoading" loading-text="Sending" @click="onSubmit" />
        </div>
      </template>
      <EmptySection v-else text="スタッフを選択して、スケジュールを設定しよう" />
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-business-operator-staff-schedules {
  position: relative;
  display: flex;
  flex-direction: column;
  &__button-select {
    width: 100%;
  }
  &__select {
    background-color: var(--white);
    width: 100%;
  }
  &__start-end {
    padding: 16px;
    font-size: 18px;
    letter-spacing: -1.5px;
    text-align: center;
    color: var(--black);
    font-weight: var(--bold);
  }
  &__list {
    margin-bottom: 16px;
  }
  &__button {
    padding: 0 16px;
    .button {
      width: 100%;
    }
  }
}
</style>
