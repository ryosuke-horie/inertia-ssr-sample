<script setup lang="ts">
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
import Button from '@/Components/atom/button/Button/Button.vue';
import { ref, onMounted } from 'vue';
import { ButtonVariantProp } from '@/Components/atom/button/Button/type';
import { useForm } from '@inertiajs/vue3';

const props = defineProps<{
  businessTippingAmountSettings: {
    settingId: number;
    amount: number;
    isSelect: boolean;
  }[];
}>();

const form = useForm<{ settingIdList: number[] }>({
  settingIdList: [],
});

const selectedCount = ref(0);

onMounted(() => {
  props.businessTippingAmountSettings.map((businessTippingAmountSetting) => {
    if (businessTippingAmountSetting.isSelect) {
      form.settingIdList.push(businessTippingAmountSetting.settingId);
      selectedCount.value++;
    }
  });
});

// ボタンが選択されているかどうかを判断
const isSelected = (settingId: number) => {
  return form.settingIdList.includes(settingId);
};

// ボタンの選択/解除
const toggleSelectButton = (settingId: number) => {
  const selectedIndex = form.settingIdList.indexOf(settingId);
  if (selectedIndex === -1 && selectedCount.value < 5) {
    // ボタンが選択されていない場合、選択対象に追加
    form.settingIdList.push(settingId);
    selectedCount.value++;
  } else if (selectedIndex === -1 && selectedCount.value === 5) {
    return;
  } else {
    // ボタンが既に選択されている場合、選択を解除
    form.settingIdList.splice(selectedIndex, 1);
    selectedCount.value--;
  }
};

const submit = () => {
  form.post(route(RouteName.BusinessOperatorSettingTipUpdate));
};
</script>

<template>
  <BaseLayout
    title="投げ銭設定"
    :is-auth="true"
    :role="RouteRoleName.Business"
    :auth-header="{
      text: '投げ銭設定',
      href: RouteName.BusinessOperatorSetting,
    }"
  >
    <div class="p-business-operator-setting-tip">
      <Paragraph class="p-business-operator-setting-tip__explain">
        ユーザーが投げ銭できる金額を5つ選択してください。
      </Paragraph>
      <Paragraph class="p-business-operator-setting-tip__count"> ({{ form.settingIdList.length }}/5) </Paragraph>
      <div class="p-business-operator-setting-tip__wrapper">
        <div
          v-for="(item, index) in props.businessTippingAmountSettings"
          :key="index"
          class="p-business-operator-setting-tip__wrapper__container"
        >
          <Button
            class="p-business-operator-setting-tip__wrapper__container__button"
            :class="{ selected: isSelected(item.settingId) }"
            :text="`${item.amount}pt`"
            :icon="`fa-solid fa-coins`"
            :is-outlined="true"
            :variant="ButtonVariantProp.Derk"
            @click="toggleSelectButton(item.settingId)"
          />
        </div>
      </div>
      <Button
        :is-loading="form.processing"
        text="更新"
        class="p-business-operator-setting-tip__button"
        :class="{
          'p-business-operator-setting-tip__none-button': form.settingIdList.length < 5,
        }"
        @click="submit()"
      />
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-business-operator-setting-tip {
  padding: 24px 16px;
  &__explain {
    font-size: 15px;
    text-align: center;
  }
  &__count {
    text-align: center;
    margin-top: 10px;
    margin-bottom: 16px;
  }
  &__wrapper {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
    &__container {
      width: 100%;
      &__button {
        width: 100%;
        box-shadow: 0 5px 5px rgba(0, 0, 0, 0.05);
        & span i {
          color: #ffeb3b;
        }
        &.selected {
          background: var(--gray) !important;
          color: white !important;
        }
      }
    }
  }
  &__button {
    margin: 30px auto 0;
    width: 50%;
  }
  &__none-button {
    pointer-events: none;
    opacity: 0.5;
    cursor: not-allowed;
  }
}
</style>
