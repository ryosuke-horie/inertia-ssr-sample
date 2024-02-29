import { RouteRoleName } from '@/Utilities';

export type AuthHeaderProps = {
  // TODO:BaseLayout移行後Role削除する
  role?: RouteRoleName;
  text: string;
  href?: string;
  params?: { [key: string]: number | string };
};
