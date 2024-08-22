export default class AbstractInputSchema
{
    name: string;
    label: string|null = null;
    order: number|null = null;

    public constructor(
        name: string,
        label: string|null,
        order: number|null = null,
    ) {
        this.name = name;
        this.label = label;
        this.order = order;
    }
}
