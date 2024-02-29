export const PointBuyListItemCategoryProp = {
  paid: 1,
  free: 2,
} as const;

export type PointBuyListItemCategoryProp =
  (typeof PointBuyListItemCategoryProp)[keyof typeof PointBuyListItemCategoryProp];

export type PointByListItemProps = {
  category: PointBuyListItemCategoryProp;
  expiredAt: string;
  points: number;
  date: string;
};
