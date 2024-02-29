import { useForm } from '@inertiajs/vue3';
import { RouteName } from '@/Utilities';

export const useLogin = () => {
  const form = useForm({
    email: '',
    password: '',
    remember: false,
  });

  const onSubmit = () => {
    form.post(route(RouteName.BusinessOperatorLogin), {
      onFinish: () => form.reset('password'),
    });
  };

  return {
    form,
    onSubmit,
  };
};
