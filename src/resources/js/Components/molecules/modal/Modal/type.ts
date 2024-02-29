export const ModalVariantProp = {
  White: 'white',
  Black: 'black',
};

export type ModalVariantProp = (typeof ModalVariantProp)[keyof typeof ModalVariantProp];

export type ModalProps = {
  isBackgroundClose?: boolean;
  variant?: ModalVariantProp;
  headerText?: string;
};
