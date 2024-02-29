export const LeadCompany = {
    "Mits": {
        "value": 1,
        "name": "マミヤITソリューションズ株式会社"
    },
    "Fs": {
        "value": 2,
        "name": "エフエス株式会社"
    },
    "Out": {
        "value": 3,
        "name": "外部営業"
    },
} as const;

export type LeadCompany= typeof LeadCompany[keyof typeof LeadCompany];
