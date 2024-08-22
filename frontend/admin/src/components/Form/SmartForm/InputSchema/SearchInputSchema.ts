import AbstractInputSchema from "@/components/Form/SmartForm/InputSchema/AbstractInputSchema";
import type BaseSearchRequest from "@/api/common/filter/BaseSearchRequest";
import type SearchMode from "@/api/common/filter/constants/SearchMode";

type Constructor<T> = new (...args: any[]) => T;

export default class SearchInputSchema extends AbstractInputSchema
{
    public constructor(
        name: string,
        label: string|null,
        public targetValue: string,
        public searchMethod: Function,
        public searchProperties: {property: string, searchMode: SearchMode}[],
        public searchRequest: Constructor<BaseSearchRequest>,
        public propertyLabelFn: Function,
        order: number|null = null,
    ) {
        super(
            name,
            label,
            order,
        );
    }
}
