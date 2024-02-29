export const EntityType = {
    "User": {
        "value": 1,
        "name": "投げ銭ユーザー"
    },
    "Staff": {
        "value": 2,
        "name": "スタッフ"
    },
    "BusinessOperator": {
        "value": 3,
        "name": "事業者"
    },
    "Corporation": {
        "value": 4,
        "name": "法人"
    },
    "AdminStaff": {
        "value": 5,
        "name": "管理スタッフ"
    },
    "Web": {
        "value": 6,
        "name": "全体"
    },
} as const;

export type EntityType= typeof EntityType[keyof typeof EntityType];
