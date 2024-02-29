export type MypageProps = {
  /**
   * 事業者名
   */
  businessName: string;

  /**
   * 画像パス
   */
  src: string;

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
   * 公開設定
   */
  isPublish: boolean;
};
