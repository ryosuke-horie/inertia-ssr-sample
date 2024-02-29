export type UserRankingProps = {
  yearMonth: string | null;
  yearMonthOptions: { value: string; label: string }[];
  userRanking: {
    userId: string;
    nickname: string;
    totalAmount: number;
  }[];
};
