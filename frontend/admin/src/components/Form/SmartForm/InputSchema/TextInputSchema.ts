import AbstractInputSchema from "@/components/Form/SmartForm/InputSchema/AbstractInputSchema";

export default class TextInputSchema extends AbstractInputSchema
{
    public constructor(
        name: string,
        label: string|null,
        order: number|null = null,
    ) {
        super(
            name,
            label,
            order,
        );
    }
}
