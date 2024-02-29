export const BusinessFormNameStatus = {
    "Restaurant": {
        "value": 1,
        "name": "飲食店"
    },
    "Zoo": {
        "value": 2,
        "name": "動物園"
    },
    "Aquarium": {
        "value": 3,
        "name": "水族館"
    },
} as const;

export type BusinessFormNameStatus= typeof BusinessFormNameStatus[keyof typeof BusinessFormNameStatus];
