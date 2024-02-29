import { ref, watch, onBeforeMount, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { generateUUID } from '@/Utilities';
import { PageProps } from '@/index';

export type FlashMessage = {
  id: string;
  message: PageProps['flash'];
};

export type ReturnTypeFlashMessages = {
  flashMessages: FlashMessage[];
  pushFlashMessage: (message: PageProps['flash']) => void;
};

export const useFlashMessages = (): ReturnTypeFlashMessages => {
  const page = usePage<PageProps>();
  const flash = computed<PageProps['flash']>(() => page.props.flash);
  const flashMessages = ref<FlashMessage[]>([]);

  const pushFlashMessage = (message: PageProps['flash']) => {
    const id = generateUUID();
    flashMessages.value.push({ id, message });
    page.props.flash = null;
  };

  onBeforeMount(() => {
    if (!flash.value) return;
    pushFlashMessage(flash.value);
  });

  watch(flash, (newValue) => {
    if (!newValue) return;
    pushFlashMessage(newValue);
  });

  return {
    flashMessages: flashMessages.value,
    pushFlashMessage,
  };
};
