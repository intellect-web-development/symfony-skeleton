import SearchMode from "@/api/common/filter/constants/SearchMode";

export default class Filter {
    /**
     * @param {string} property
     * @param searchMode
     * @param {string|array} value
     */
    constructor(
      public property: string,
      public searchMode: SearchMode,
      public value: string|string[]|number[]|null|boolean
    ) {
        this.value = value;
        this.property = property;
        this.searchMode = searchMode;
    }
}
