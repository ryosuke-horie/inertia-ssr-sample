export const PointExpiredListItemCategoryProp = {
  paid: 1,
  free: 2,
} as const;

export type PointExpiredListItemCategoryProp =
  (typeof PointExpiredListItemCategoryProp)[keyof typeof PointExpiredListItemCategoryProp];

export type PointExpiredListItemProps = {
  category: PointExpiredListItemCategoryProp;
  points: number;
  expiredAt: string;
};
