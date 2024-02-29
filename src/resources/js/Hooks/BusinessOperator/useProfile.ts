import { useForm } from '@inertiajs/vue3';

export const useProfile = () => {
  const form = useForm({
    nickname: '',
    comment: '',
  });

  const onSubmit = (): void => {};

  return {
    form,
    onSubmit,
  };
};
