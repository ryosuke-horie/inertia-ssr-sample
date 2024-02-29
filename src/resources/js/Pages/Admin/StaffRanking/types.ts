export type StaffRankingProps = {
  yearMonth: string | null;
  yearMonthOptions: { value: string; label: string }[];
  staffRanking: {
    staffId: string;
    staffName: string;
    businessId: string;
    businessName: string;
    totalAmount: number;
  }[];
};
