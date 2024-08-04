import BaseOutputContract from "@/api/common/BaseOutputContract";
import type {TokenInterface} from "@/api/auth/token/dto/Token";
import {Token} from "@/api/auth/token/dto/Token";

export default class RefreshTokenOutputContract extends BaseOutputContract {
  public constructor(
    status: number,
    ok: boolean,
    data: TokenInterface,
    messages: object,
  ) {
    super(
      status,
      ok,
      new Token(data),
      messages
    );
  }
}
