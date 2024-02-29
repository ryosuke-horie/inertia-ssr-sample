export const HtmlDatasetKeyEnum = {
  IsModal: 'isModal',
  IsSidebar: 'isSidebar',
} as const;

export type HtmlDatasetKeyEnum = (typeof HtmlDatasetKeyEnum)[keyof typeof HtmlDatasetKeyEnum];

export const addHtmlDataset = (key: string, value: string): void => {
  document.documentElement.dataset[key] = value;
};

export const deleteHtmlDataset = (key: string): void => {
  delete document.documentElement.dataset[key];
};
