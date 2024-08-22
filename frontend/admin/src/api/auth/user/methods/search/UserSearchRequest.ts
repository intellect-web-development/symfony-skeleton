import BaseSearchRequest from "@/api/common/filter/BaseSearchRequest";
import type FilterCollection from "@/api/common/filter/filter/FilterCollection";
import type SortCollection from "@/api/common/filter/sort/SortCollection";
import type Pagination from "@/api/common/filter/pagination/Pagination";
import type {Strategy} from "@/api/common/filter/strategy/Strategy";

export default class UserSearchRequest extends BaseSearchRequest {
    constructor(
        filters: FilterCollection,
        sorts: SortCollection,
        pagination: Pagination,
        lang: string,
        strategy: Strategy,
    ) {
        super(
            `/api/admin/auth/users`,
            filters,
            sorts,
            pagination,
            lang,
            strategy
        );
    }
}
