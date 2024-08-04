import BaseRequest from "@/api/common/BaseRequest";
import Violations from "@/api/common/Violations";
import * as yup from 'yup';
import {validate} from "@/api/common/validator";
import type FormPayloadInterface from "@/api/common/FormPayloadInterface";

export class RefreshTokenPayload implements FormPayloadInterface {
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

export default class RefreshTokenRequest extends BaseRequest {
  public method = 'post'

  public data = new RefreshTokenPayload()

  constructor(payload: RefreshTokenPayload|null) {
    super(`/api/token/refresh`);
    if (payload) {
      this.data = payload;
    }
  }
}
