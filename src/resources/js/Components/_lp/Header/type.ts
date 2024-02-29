import { RouteRoleName } from '@/Utilities';

type HeaderMenuItemProp = {
  text: string;
  href: string;
  hrefId?: string;
};

export type HeaderProps = {
  role: RouteRoleName | 'web';
  menus: HeaderMenuItemProp[];
};
