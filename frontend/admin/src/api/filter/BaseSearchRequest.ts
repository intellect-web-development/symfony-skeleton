import FilterCollection from "@/api/filter/filter/FilterCollection";
import SortCollection from "@/api/filter/sort/SortCollection";
import Pagination from "@/api/filter/pagination/Pagination";
import searchModes from "@/api/filter/constants/searchModes";
import {Strategy} from "@/api/filter/strategy/Strategy";
import BaseJWTRequest from "@/api/BaseJWTRequest";

export default class BaseSearchRequest extends BaseJWTRequest {
  method = 'get'

  params = {}

  constructor(
    url: string,
    filters: FilterCollection,
    sorts: SortCollection,
    pagination: Pagination,
    lang: string,
    strategy: Strategy,
  ) {
    super(url);
    this.setFilters(filters);
    this.setSorts(sorts);
    this.setPagination(pagination)
    this.setLang(lang)
    this.setStrategy(strategy)
  }

  private setFilters(filters: FilterCollection) {
    let params = filters.toParams();
    for (let property in params) {
      for (let mode in params[property]) {
        if ([searchModes.IN, searchModes.NOT_IN].includes(mode)) {
          // @ts-ignore
          this.params[`filter[${property}][${mode}]`] = params[property][mode];
        } else {
          // @ts-ignore
          this.params[`filter[${property}][${mode}]`] = params[property][mode];
        }
      }
    }
  }

  private setSorts(sorts: SortCollection) {
    if (sorts.sorts.length > 0) {
      // @ts-ignore
      this.params.sort = sorts.toParams();
    }
  }

  private setPagination(pagination: Pagination) {
    // @ts-ignore
    this.params['page[number]'] = pagination.number;
    // @ts-ignore
    this.params['page[size]'] = pagination.size;
  }

  private setLang(lang: string) {
    // @ts-ignore
    this.params.lang = lang;
  }

  private setStrategy(strategy: Strategy) {
    // @ts-ignore
    this.params.strategy = strategy.toString();
  }
}
