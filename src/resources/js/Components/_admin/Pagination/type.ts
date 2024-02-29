export type PaginationProps = {
  links: {
    url: string;
    label: string;
    active: boolean;
  }[];
  currentPage: string;
  total: string;
};
