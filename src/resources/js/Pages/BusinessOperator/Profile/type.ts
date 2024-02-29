import { OrderEnum } from '@/api';

export type ProfileProps = {
  /**
   * スタッフID
   */
  staffId: number;

  /**
   * 画像
   */
  images: {
    image: string | null;
    order: OrderEnum;
  }[];

  /**
   * 店舗説明
   */
  businessDescription: string;

  /**
   * 法人名
   */
  corporationName: string;

  /**
   * 事業者名
   */
  businessName: string;

  /**
   * メールアドレス
   */
  email: string;
};
