import BaseJWTRequest from "@/api/common/BaseJWTRequest";
import type AccessToken from "@/api/auth/token/dto/AccessToken";
import type FormPayloadInterface from "@/api/common/FormPayloadInterface";
import * as yup from "yup";
import {validate} from "@/api/common/validator";
import Violations from "@/api/common/Violations";

export class AuthUserCreatePayload implements FormPayloadInterface {
  email: string|null = null;

  static schema = yup.object({

  });

  public validate(): Violations
  {
    return validate(AuthUserCreatePayload.schema, this);
  }
}

export default class AuthUserCreateRequest extends BaseJWTRequest {
  public method = 'post'

  public data = new AuthUserCreatePayload()

  constructor(payload: AuthUserCreatePayload|null, accessToken: AccessToken) {
    super(`/api/admin/auth/users/create`);
    if (payload) {
      this.data = payload;
    }
    this.setAccessToken(accessToken.raw);
  }
}
