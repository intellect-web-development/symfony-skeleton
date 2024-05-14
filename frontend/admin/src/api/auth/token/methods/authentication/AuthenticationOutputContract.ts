import BaseOutputContract from "@/api/BaseOutputContract";
import type {TokenInterface} from "@/api/auth/token/dto/Token";
import {Token} from "@/api/auth/token/dto/Token";

export default class AuthenticationOutputContract extends BaseOutputContract {
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
