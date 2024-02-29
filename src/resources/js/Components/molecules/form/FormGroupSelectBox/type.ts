import type { FormRequiredLabelTypeProp } from '@/Components/atom/form/FormRequiredLabel/type';
import type { FormSelectBoxOptionItem } from '@/Components/atom/form/FormSelectBox/type';

export type FormGroupSelectBoxProps = {
  id: string;
  label?: string;
  requiredType?: FormRequiredLabelTypeProp;
  description?: string;
  annotation?: string;
  modelValue: string | number;
  placeholder?: string;
  error?: string;
  disabled?: boolean;
  options: FormSelectBoxOptionItem[];
};
