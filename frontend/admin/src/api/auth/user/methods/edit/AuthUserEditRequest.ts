import BaseJWTRequest from "@/api/common/BaseJWTRequest";
import type AccessToken from "@/api/auth/token/dto/AccessToken";
import type FormPayloadInterface from "@/api/common/FormPayloadInterface";
import * as yup from "yup";
import {validate} from "@/api/common/validator";
import Violations from "@/api/common/Violations";

export class AuthUserEditPayload implements FormPayloadInterface {
  id: string|null = null;
  email: string|null = null;

  static schema = yup.object({
    id: yup.string().required(),
  });

  public validate(): Violations
  {
    return validate(AuthUserEditPayload.schema, this);
  }
}

export default class AuthUserEditRequest extends BaseJWTRequest {
  public method = 'post'

  public data = new AuthUserEditPayload()

  constructor(payload: AuthUserEditPayload|null, accessToken: AccessToken) {
    super(`/api/admin/auth/users/edit`);
    if (payload) {
      this.data = payload;
    }
    this.setAccessToken(accessToken.raw);
  }
}
