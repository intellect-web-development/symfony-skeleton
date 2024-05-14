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
