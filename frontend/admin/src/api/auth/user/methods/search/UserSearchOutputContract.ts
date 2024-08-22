import BaseOutputContract from "@/api/common/BaseOutputContract";
import type {SearchDataOutputContractInterface} from "@/api/common/filter/SearchDataOutputContract";
import { UserEntity } from "../../UserEntity";
import SearchDataOutputContract from "@/api/common/filter/SearchDataOutputContract";
import PaginationOutputContract from "@/api/common/filter/PaginationOutputContract";

export default class UserSearchOutputContract extends BaseOutputContract {
    public constructor(
        public status: number,
        public ok: boolean,
        public data: SearchDataOutputContractInterface<UserEntity>,
        public messages: object,
    ) {
        super(
            status,
            ok,
            new SearchDataOutputContract(
                data.data.map((entity: object) => {
                    new UserEntity(entity);
                }),
                new PaginationOutputContract(data.pagination),
            ),
            messages
        );
    }
}
