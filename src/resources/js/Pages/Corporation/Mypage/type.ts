export type MypageProps = {
  /**
   * 法人名
   */
  corporationName: string;

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
   * 非公開設定の事業者
   */
  isPrivatePublish: boolean;
};
