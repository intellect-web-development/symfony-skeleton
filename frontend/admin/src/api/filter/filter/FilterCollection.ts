import Filter from "@/api/filter/filter/Filter";
import searchModes from "@/api/filter/constants/searchModes";

export default class FilterCollection {
  public filtersAssoc: any = {};

  constructor(
    public filters: Filter[]
  ) {
    filters.forEach((filter: Filter) => {
      this.filtersAssoc[filter.property] = filter;
    })
  }

  toParams() {
      let params: any;
      params = {}
      this.filters.filter((filter) => {
          if (filter.value === null) {
              return false;
          }
          if (typeof filter.value !== 'boolean') {
            if (filter.value.length === 0) {
              return false;
            }
          }
          return true;
      }).forEach(filter => {
          if (!params[filter.property]) {
            params[filter.property] = {};
          }
          if ([searchModes.IS_NULL, searchModes.NOT_NULL].includes(filter.searchMode)) {
            if (filter.value === false) {
              return;
            }
          }
          params[filter.property][filter.searchMode] = filter.value;
      })

      return params;
  }
}
