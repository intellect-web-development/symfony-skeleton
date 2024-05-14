import Violations from "@/api/Violations";
import type FormPayloadInterface from "@/api/FormPayloadInterface";
import BaseJWTRequest from "@/api/BaseJWTRequest";
import * as yup from "yup";
import {RefreshTokenPayload} from "@/api/auth/token/methods/refresh/RefreshTokenRequest";
import {validate} from "@/api/validator";
import type AccessToken from "@/api/auth/token/dto/AccessToken";

export class InvalidateRefreshTokenPayload implements FormPayloadInterface {
  refreshToken: string|null = null;

  static schema = yup.object({
    refreshToken: yup.string()
        .required(),
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
