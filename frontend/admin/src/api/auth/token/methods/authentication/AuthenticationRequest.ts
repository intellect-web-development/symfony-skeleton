import BaseRequest from "@/api/common/BaseRequest";
import Violations from "@/api/common/Violations";
import * as yup from 'yup';
import * as yupTrans from "@/translations/ru/yup-translation";
import {validate} from "@/api/common/validator";
import type FormPayloadInterface from "@/api/common/FormPayloadInterface";

export class AuthenticationPayload implements FormPayloadInterface {
  email: string|null = null;
  password: string|null = null;

  static schema = yup.object({
    email: yup.string()
        .required(yupTrans.required)
        .email(yupTrans.email),
    password: yup.string()
        .required(yupTrans.required),
  });

  public validate(): Violations
  {
    return validate(AuthenticationPayload.schema, this);
  }
}

export default class AuthenticationRequest extends BaseRequest {
  public method = 'post'

  public data = new AuthenticationPayload()

  constructor(payload: AuthenticationPayload|null) {
    super(`/api/token/authentication`);
    if (payload) {
      this.data = payload;
    }
  }
}
