import AbstractInputSchema from "@/components/Form/SmartForm/InputSchema/AbstractInputSchema";
import type {CaseType} from "@/components/Common/CaseType/CaseType";

export default class ChoiceInputSchema extends AbstractInputSchema
{
    public constructor(
        name: string,
        label: string|null,
        public cases: CaseType[],
        public editable: boolean = false,
        order: number|null = null,
    ) {
        super(
            name,
            label,
            order,
        );
    }
}
