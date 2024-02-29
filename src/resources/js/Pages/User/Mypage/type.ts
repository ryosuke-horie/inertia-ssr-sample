import { SwiperStaffListItemPorps } from '@/Components/molecules/list/SwiperStaffListItem/type';

export type MypageProps = {
  /**
   * ユーザーID
   */
  userId: string;

  /**
   * ユーザー名
   */
  nickname: string;

  /**
   * お知らせリスト
   */
  notifications: {
    notificationId: number;
    isRead: boolean;
    title: string;
    startAt: string;
    url: string;
    fileType: string;
    fileName: string;
    privateFlag: boolean;
  }[];

  /**
   * プロフィール画像
   */
  userProfileImage: string;

  /**
   * お気に入りスタッフ
   */
  favoriteStaff: SwiperStaffListItemPorps[];
};
