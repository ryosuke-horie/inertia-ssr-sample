export const FlashVariantProp = {
  Success: 'success',
  Failed: 'failed',
} as const;

export type FlashVariantProp = (typeof FlashVariantProp)[keyof typeof FlashVariantProp];

export type FlashProps = {
  variant?: FlashVariantProp;
  message?: string;
};
