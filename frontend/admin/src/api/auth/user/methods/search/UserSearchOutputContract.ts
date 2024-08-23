import BaseOutputContract from "@/api/common/BaseOutputContract";
import type {SearchDataOutputContractInterface} from "@/api/common/filter/SearchDataOutputContract";
import { AuthUserEntity } from "../../AuthUserEntity";
import SearchDataOutputContract from "@/api/common/filter/SearchDataOutputContract";
import PaginationOutputContract from "@/api/common/filter/PaginationOutputContract";

export default class UserSearchOutputContract extends BaseOutputContract {
  public constructor(
    public status: number,
    public ok: boolean,
    public data: SearchDataOutputContractInterface<AuthUserEntity>,
    public messages: object,
  ) {
    super(
      status,
      ok,
      new SearchDataOutputContract(
        data.data.map((entity: object) => {
          new AuthUserEntity(entity);
        }),
        new PaginationOutputContract(data.pagination),
      ),
      messages
    );
  }
}
