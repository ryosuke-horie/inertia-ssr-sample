export type SwiperStaffImageProps = {
  images: {
    image: string;
    order: number;
  }[];
  staffId: number;
  favoriteId: number | null;
  userLikeId: number | null;
  userLikeCount: number | null;
};
