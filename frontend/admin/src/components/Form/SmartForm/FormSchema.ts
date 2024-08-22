import type AbstractInputSchema from "@/components/Form/SmartForm/InputSchema/AbstractInputSchema";

export default class FormSchema
{
    orderCounter: number = 0;
    inputs: object = {};

    public constructor(
        inputs: object = {},
    ) {
        for (let field in inputs) {
            // @ts-ignore
            this.add(inputs[field])
        }
    }

    add(inputSchema: AbstractInputSchema): void
    {
        // @ts-ignore
        this.inputs[inputSchema.name] = inputSchema;

        if (null === inputSchema.order) {
            this.orderCounter++;
            inputSchema.order = this.orderCounter;
        } else {
            this.orderCounter = Math.max(inputSchema.order, this.orderCounter);
        }
    }
}
