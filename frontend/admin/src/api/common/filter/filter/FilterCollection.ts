import Filter from "@/api/common/filter/filter/Filter";
import SearchMode from "@/api/common/filter/constants/SearchMode";

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
          if (typeof filter.value === 'undefined') {
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
          if ([SearchMode.IS_NULL, SearchMode.NOT_NULL].includes(filter.searchMode)) {
            if (filter.value === 'false' || filter.value === false) {
              return;
            }
          }
          params[filter.property][filter.searchMode] = filter.value;
      })

      return params;
  }
}
