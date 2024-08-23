import BaseOutputContract from '@/api/common/BaseOutputContract';
import { AuthUserEntity } from "./AuthUserEntity";

export default class UserCommonOutputContract extends BaseOutputContract {
  public constructor(
    status: number,
    ok: boolean,
    data: AuthUserEntity,
    messages: object,
  ) {
    super(
      status,
      ok,
      new AuthUserEntity(data),
      messages
    );
  }
}
