import type InputSchema from "@/components/SmartForm/InputSchema";

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

    add(inputSchema: InputSchema): void
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
