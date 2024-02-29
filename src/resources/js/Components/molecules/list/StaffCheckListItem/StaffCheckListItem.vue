<script lang="ts" setup>
import { StaffCheckListItemProps } from './type';
import IconImage from '@/Components/atom/image/IconImage/IconImage.vue';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';

const props = defineProps<StaffCheckListItemProps>();

const emit = defineEmits(['update:modelValue']);

const onCheck = (e: Event): void => {
  const value = Number((e.target as HTMLInputElement).value);
  emit(
    'update:modelValue',
    !props.modelValue.includes(value)
      ? [...props.modelValue, value]
      : props.modelValue.filter((item) => item !== value),
  );
};
</script>

<template>
  <li class="c-staff-check-list-item">
    <IconImage :src="props.src" :width="70" :height="70" />
    <Paragraph class="c-staff-check-list-item__name">{{ props.name }}</Paragraph>
    <div class="c-staff-check-list-item__check">
      <input
        :id="`check-staff-${props.value}`"
        type="checkbox"
        @change="onCheck"
        :checked="props.modelValue.includes(props.value)"
        :value="props.value"
      />
      <label :for="`check-staff-${props.value}`" />
    </div>
  </li>
</template>

<style lang="scss">
.c-staff-check-list-item {
  display: flex;
  align-items: center;
  padding: 16px;
  & + .c-staff-check-list-item {
    border-top: solid 1px #ddd;
  }
  &__name {
    flex: 1;
    width: 100%;
    text-align: left;
    margin: 0 16px;
  }
  &__check {
    width: 40px;
    height: 40px;
    margin-left: auto;
    input {
      display: none;
      &:checked + label {
        background-color: #36df98;
        &::before {
          display: block;
        }
      }
    }
    label {
      cursor: pointer;
      width: 100%;
      height: 100%;
      background-color: #eeeeee;
      display: inline-block;
      border-radius: 5px;
      position: relative;
      &::before {
        display: none;
        position: absolute;
        content: '✔';
        font-size: 25px;
        top: 2px;
        left: 10px;
      }
    }
  }
}
</style>
