import AbstractInputSchema from "@/components/Form/SmartForm/InputSchema/AbstractInputSchema";

export default class CheckPasswordInputSchema extends AbstractInputSchema
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
