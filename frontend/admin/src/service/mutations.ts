export function assignWithTypes(data: any, target: any): void {
    Object.entries(data).forEach(([key, value]) => {
        const targetValue = target[key];
        if (typeof targetValue === typeof value) {
            target[key] = value;
        } else if (typeof targetValue === 'number' && typeof value === 'string') {
            target[key] = Number(value);
        } else if (typeof targetValue === 'boolean' && typeof value === 'string') {
            target[key] = value.toLowerCase() === 'true';
        } else if (typeof targetValue === 'object' && typeof value === 'object') {
            assignWithTypes(value, targetValue);
        }
    })
}

/**
 * @param target - Целевой объект, на который будет производиться маппинг
 * @param data - Объект данных, с которого данные будут взяты
 */
export function dataMap(target: object, data: object): void {
    for (const key in data) {
        if (data.hasOwnProperty(key) && target.hasOwnProperty(key)) {
            //@ts-ignore
            target[key] = data[key];
        }
    }
}
