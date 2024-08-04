import searchModes, { searchModesArray } from "@/api/common/filter/constants/searchModes";

export default class Filter {
    /**
     * @param {string} property
     * @param {string|array} value
     * @param {string[searchModes]} searchMode
     */
    constructor(
      public property: string,
      public searchMode: string,
      public value: string|string[]|number[]|null|boolean
    ) {
        if (searchModesArray.indexOf(searchMode) === -1) {
            throw new Error('Invalid search searchMode');
        }
        if ([searchModes.IN, searchModes.NOT_IN].includes(searchMode) && typeof value === 'string') {
          this.value = [ value ];
        } else {
          this.value = value;
        }

        this.property = property;
        this.searchMode = searchMode;
    }
}
