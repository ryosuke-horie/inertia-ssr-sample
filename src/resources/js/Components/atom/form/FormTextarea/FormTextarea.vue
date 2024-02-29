<script setup lang="ts">
import { computed } from 'vue';
import type { FormTextareaProps } from './type';

const props = withDefaults(defineProps<FormTextareaProps>(), {});

const computedClasses = computed<string[]>(() => {
  const classes: string[] = [];

  if (props.isError) {
    classes.push('is-error');
  }

  return classes;
});

const emit = defineEmits(['update:modelValue']);

const onInput = (e: Event): void => {
  const value = (e.target as HTMLInputElement).value;
  emit('update:modelValue', value);
};
</script>

<template>
  <textarea
    :id="props.id"
    :class="computedClasses"
    class="c-form-text-area"
    :disabled="props.disabled"
    :name="props.name"
    :minlength="props.minlength"
    :maxlength="props.maxlength"
    :placeholder="props.placeholder"
    :value="props.modelValue"
    @input="onInput"
  />
</template>

<style lang="scss">
.c-form-text-area {
  height: 120px;
  border: solid 1px;
  border-color: #dddddd;
  border-radius: 4px;
  padding: 16px;
  max-width: 100%;
  color: #000000;
  background-color: #eeeeee;
  &.is-error {
    border-color: red;
    background-color: #ffe3e3;
  }
  &:focus {
    border-color: #66afe9;
    outline: 0;
    box-shadow:
      inset 0 1px 1px rgba(0, 0, 0, 0.075),
      0 0 8px rgba(102, 175, 233, 0.6);
  }
}
</style>
