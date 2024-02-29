import { Config, RouteParam, Router } from 'ziggy-js';

declare global {
  declare function route(
    name?: undefined,
    params?: RouteParamsWithQueryOverload | RouteParam,
    absolute?: boolean,
    config?: Config,
  ): Router;

  declare function route(
    name: string,
    params?: RouteParamsWithQueryOverload | RouteParam,
    absolute?: boolean,
    config?: Config,
  ): string;
  declare const Ziggy: any;
}

declare module 'vue' {
  interface ComponentCustomProperties {
    route: ((
      name?: undefined,
      params?: RouteParamsWithQueryOverload | RouteParam,
      absolute?: boolean,
      config?: Config,
    ) => Router) &
      ((
        name: string,
        params?: RouteParamsWithQueryOverload | RouteParam,
        absolute?: boolean,
        config?: Config,
      ) => string);
  }
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
  flash: {
    type: 'success' | 'failed';
    text: string;
  } | null;
};
