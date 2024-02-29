import { useForm } from '@inertiajs/vue3';
import { RouteName } from '@/Utilities';

export const useForgotPassword = () => {
  const form = useForm({
    email: '',
  });

  const onSubmit = () => {
    form.post(route(RouteName.BusinessOperatorPasswordEmail));
  };

  return {
    form,
    onSubmit,
  };
};
