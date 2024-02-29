export const BusinessPublishedStatus = {
    "Published": {
        "value": 1,
        "name": "公開"
    },
    "Unpublished": {
        "value": 2,
        "name": "非公開"
    },
} as const;

export type BusinessPublishedStatus= typeof BusinessPublishedStatus[keyof typeof BusinessPublishedStatus];
