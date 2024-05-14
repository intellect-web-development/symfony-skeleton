import type { PaginationOutputContractInterface } from "@/api/filter/PaginationOutputContract";

export interface SearchDataOutputContractInterface<ModelInterface> {
  data: Array<ModelInterface>;
  pagination: PaginationOutputContractInterface;
}

export default class SearchDataOutputContract {
  constructor(
    public data: object,
    public pagination: PaginationOutputContractInterface,
  ) {
  }
}
