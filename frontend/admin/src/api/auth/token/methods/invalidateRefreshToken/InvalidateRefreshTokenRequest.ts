import Violations from "@/api/common/Violations";
import type FormPayloadInterface from "@/api/common/FormPayloadInterface";
import BaseJWTRequest from "@/api/common/BaseJWTRequest";
import * as yup from "yup";
import * as yupTrans from "@/translations/ru/yup-translation";
import {RefreshTokenPayload} from "@/api/auth/token/methods/refresh/RefreshTokenRequest";
import {validate} from "@/api/common/validator";
import type AccessToken from "@/api/auth/token/dto/AccessToken";

export class InvalidateRefreshTokenPayload implements FormPayloadInterface {
  refreshToken: string|null = null;

  static schema = yup.object({
    refreshToken: yup.string()
        .required(yupTrans.required),
  });

  public validate(): Violations
  {
    return validate(RefreshTokenPayload.schema, this);
  }
}

export default class InvalidateRefreshTokenRequest extends BaseJWTRequest {
  public method = 'post'

  public data = new InvalidateRefreshTokenPayload()

  constructor(payload: InvalidateRefreshTokenPayload|null, accessToken: AccessToken) {
    super(`/api/token/invalidate-refresh-token`);
    if (payload) {
      this.data = payload;
    }
    this.setAccessToken(accessToken.raw);
  }
}
