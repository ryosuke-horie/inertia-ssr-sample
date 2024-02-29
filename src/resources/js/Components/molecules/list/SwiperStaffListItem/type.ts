export type SwiperStaffListItemPorps = {
  staffId: number;
  businessId: number;
  businessName?: string;
  staffProfileImages: { image: string; order: number }[];
  staffName: string;
  href: string;
};
