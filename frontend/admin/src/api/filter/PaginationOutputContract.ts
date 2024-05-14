export interface PaginationOutputContractInterface {
  count: number;
  totalPages: number;
  page: number;
  size: number;
}

export default class PaginationOutputContract {
  public count: number;
  public totalPages: number;
  public page: number;
  public size: number;

  constructor(pagination: PaginationOutputContractInterface) {
    this.count = pagination.count;
    this.totalPages = pagination.totalPages;
    this.page = pagination.page;
    this.size = pagination.size;
  }
}
